<?php

namespace App\Http\Controllers\GM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CubiMonitorController extends Controller
{
    public function index(Request $request): View
    {
        $game = 'mysql_game';

        // ── Overall stats ──
        $totalDelivered = DB::connection($game)->table('usecashlog')->where('status', 4)->sum('cash');
        $totalUsers     = DB::connection($game)->table('usecashlog')->where('status', 4)->distinct('userid')->count('userid');
        $regBonus       = DB::connection($game)->table('usecashlog')->where('status', 4)->where('sn', 1)->sum('cash');
        $extraTopups    = DB::connection($game)->table('usecashlog')->where('status', 4)->where('sn', '>', 1)->sum('cash');
        $pendingCount   = DB::connection($game)->table('usecashnow')->count();

        $stats = compact('totalDelivered', 'totalUsers', 'regBonus', 'extraTopups', 'pendingCount');

        // ── Recent deliveries (last 100, deduplicated per userid+sn) ──
        $recentRaw = DB::connection($game)->select("
            SELECT l.*
            FROM usecashlog l
            INNER JOIN (
                SELECT userid, sn, MAX(`point`) as max_point
                FROM usecashlog
                GROUP BY userid, sn
            ) d ON l.userid = d.userid AND l.sn = d.sn AND l.point = d.max_point
            WHERE l.status = 4
            ORDER BY l.creatime DESC
            LIMIT 100
        ");

        $uids = collect($recentRaw)->pluck('userid')->unique()->values()->all();
        $panelInvoices = \Illuminate\Support\Facades\DB::table('pw_invoices')
            ->whereIn('user_id', $uids)
            ->where('type', 'cubi')->where('status', 'paid')
            ->get(['user_id', 'cubi_amount', 'paid_at']);
        $panelRewards = \Illuminate\Support\Facades\DB::table('pw_referral_rewards')
            ->whereIn('referrer_id', $uids)
            ->whereIn('type', ['registration', 'registration_cubi'])
            ->get(['referrer_id', 'reward_amount', 'type', 'created_at']);
        $panelBonus = \Illuminate\Support\Facades\DB::table('pw_referral_rewards')
            ->whereIn('referred_id', $uids)
            ->where('type', 'signup_bonus')
            ->get(['referred_id', 'reward_amount', 'created_at']);

        $panelVoucherTopups = \Illuminate\Support\Facades\DB::table('pw_admin_cubi_topups')
            ->whereIn('user_id', $uids)
            ->where('reason', 'like', 'Voucher:%')
            ->orderBy('created_at')
            ->get(['user_id', 'amount', 'created_at']);

        $panelAdminTopups = \Illuminate\Support\Facades\DB::table('pw_admin_cubi_topups')
            ->whereIn('user_id', $uids)
            ->orderBy('created_at')
            ->get(['user_id', 'amount', 'created_at']);
        $panelEventDeliveries = \Illuminate\Support\Facades\DB::table('pw_event_deliveries')
            ->whereIn('user_id', $uids)
            ->orderBy('created_at')
            ->get(['user_id', 'amount', 'created_at']);
        $cubiRate = config('pw-config.currency.cubi_rate_idr', 1000);
        $panelPartnerCommissions = \Illuminate\Support\Facades\DB::table('pw_invoices')
            ->whereIn('partner_user_id', $uids)
            ->where('type', 'cubi')->where('status', 'paid')
            ->where('commission_amount', '>', 0)
            ->orderBy('paid_at')
            ->get(['partner_user_id', 'commission_amount', 'paid_at']);
        $panelPartnerWithdrawals = \Illuminate\Support\Facades\DB::table('pw_partner_withdrawals')
            ->whereIn('user_id', $uids)
            ->where('payment_method', 'cubi')->where('status', 'approved')
            ->orderBy('processed_at')
            ->get(['user_id', 'amount', 'processed_at']);

        // Bulk fetch usernames and characters — eliminates N+1 queries
        $usernames  = DB::connection('mysql_game')->table('users')
            ->whereIn('ID', $uids)->pluck('name', 'ID');
        $characters = DB::connection('mysql_game')->table('roles')
            ->whereIn('account_id', $uids)->orderBy('role_id')
            ->get(['account_id', 'role_name'])
            ->groupBy('account_id')
            ->map(fn ($g) => $g->first()->role_name);

        $recent = collect($recentRaw)->map(function ($row) use ($panelInvoices, $panelRewards, $panelBonus, $panelVoucherTopups, $panelAdminTopups, $panelEventDeliveries, $panelPartnerCommissions, $panelPartnerWithdrawals, $usernames, $characters, $cubiRate) {
            $row->username  = $usernames[$row->userid] ?? null;
            $row->character = $characters[$row->userid] ?? null;
            if (($row->point ?? 0) == 0) {
                $t = \Carbon\Carbon::parse($row->creatime);
                $inv = $panelInvoices->first(fn ($i) =>
                    $i->user_id == $row->userid
                    && ($i->cubi_amount * 100) == $row->cash
                    && abs(\Carbon\Carbon::parse($i->paid_at)->diffInMinutes($t)) <= 120);
                if ($inv) { $row->point = 1; return $row; }
                $rw = $panelRewards->first(fn ($r) =>
                    $r->referrer_id == $row->userid
                    && ($r->reward_amount * 100) == $row->cash
                    && abs(\Carbon\Carbon::parse($r->created_at)->diffInMinutes($t)) <= 120);
                if ($rw) { $row->point = 2; return $row; }
                $bn = $panelBonus->first(fn ($b) =>
                    $b->referred_id == $row->userid
                    && ($b->reward_amount * 100) == $row->cash
                    && abs(\Carbon\Carbon::parse($b->created_at)->diffInMinutes($t)) <= 120);
                if ($bn) { $row->point = 2; return $row; }
                $vc = $panelVoucherTopups->first(fn ($v) =>
                    $v->user_id == $row->userid
                    && ($v->amount * 100) == $row->cash
                    && abs(\Carbon\Carbon::parse($v->created_at)->diffInMinutes($t)) <= 120);
                if ($vc) { $row->point = 6; return $row; }
                $adm = $panelAdminTopups->first(fn ($a) =>
                    $a->user_id == $row->userid
                    && ($a->amount * 100) == $row->cash
                    && abs(\Carbon\Carbon::parse($a->created_at)->diffInMinutes($t)) <= 120);
                if ($adm) { $row->point = 5; return $row; }
                $ev = $panelEventDeliveries->first(fn ($e) =>
                    $e->user_id == $row->userid
                    && ($e->amount * 100) == $row->cash
                    && abs(\Carbon\Carbon::parse($e->created_at)->diffInMinutes($t)) <= 120);
                if ($ev) { $row->point = 4; return $row; }
                $pc = $panelPartnerCommissions->first(fn ($c) =>
                    $c->partner_user_id == $row->userid
                    && (floor($c->commission_amount / $cubiRate) * 100) == $row->cash
                    && abs(\Carbon\Carbon::parse($c->paid_at)->diffInMinutes($t)) <= 120);
                if ($pc) { $row->point = 3; return $row; }
                $pw = $panelPartnerWithdrawals->first(fn ($w) =>
                    $w->user_id == $row->userid
                    && (floor($w->amount / $cubiRate) * 100) == $row->cash
                    && abs(\Carbon\Carbon::parse($w->processed_at)->diffInMinutes($t)) <= 120);
                if ($pw) { $row->point = 3; return $row; }
            }
            return $row;
        });

        // ── Pending queue ──
        $pendingRaw = DB::connection($game)->table('usecashnow')->orderBy('creatime')->get();
        $pendingUids = $pendingRaw->pluck('userid')->unique()->values()->all();
        $pendingNames = DB::connection('mysql_game')->table('users')
            ->whereIn('ID', $pendingUids)->pluck('name', 'ID');
        $pending = $pendingRaw->map(function ($row) use ($pendingNames) {
            $row->username = $pendingNames[$row->userid] ?? null;
            return $row;
        });

        // ── SN distribution ──
        $snDistribution = DB::connection($game)
            ->table('usecashlog')
            ->where('status', 4)
            ->select('sn')
            ->selectRaw('COUNT(*) as cnt, SUM(cash) as total_cash, COUNT(DISTINCT userid) as unique_users')
            ->groupBy('sn')
            ->orderBy('sn')
            ->get();

        return view('gm.cubi-monitor', compact('stats', 'recent', 'pending', 'snDistribution'));
    }
}
