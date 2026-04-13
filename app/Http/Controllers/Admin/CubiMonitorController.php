<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\GameDbService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CubiMonitorController extends Controller
{
    public function index(Request $request): View
    {
        // Overall stats
        $stats = $this->getOverallStats();

        // All transactions (deduplicated: per userid+sn, prefer highest point = panel-tagged wins over daemon point=0)
        $suspiciousRaw = DB::connection('mysql_game')->select("
            SELECT l.*
            FROM usecashlog l
            INNER JOIN (
                SELECT userid, sn, MAX(`point`) as max_point
                FROM usecashlog
                WHERE sn > 1
                GROUP BY userid, sn
            ) d ON l.userid = d.userid AND l.sn = d.sn AND l.point = d.max_point
            WHERE l.status = 4
            ORDER BY l.creatime DESC
            LIMIT 50
        ");

        $suspicious = collect($suspiciousRaw)->map(function ($row) {
            $row->username = DB::connection('mysql_game')
                ->table('users')->where('ID', $row->userid)->value('name');
            $row->character = DB::connection('mysql_game')
                ->table('roles')->where('account_id', $row->userid)->orderBy('role_id')->value('role_name');
            return $row;
        });

        // Source detection for point=0 entries (daemon-inserted, source unknown without panel join)
        // Partner IDs needed to distinguish Referral (point=2) vs Partner (point=3)
        $partnerIds = DB::table('pw_referral_partners')->pluck('user_id')->toArray();

        $unknownRows = $suspicious->where('point', 0);
        if ($unknownRows->isNotEmpty()) {
            $uids = $unknownRows->pluck('userid')->unique()->values()->all();
            $panelInvoices = DB::table('pw_invoices')
                ->whereIn('user_id', $uids)
                ->where('type', 'cubi')->where('status', 'paid')
                ->orderBy('paid_at')
                ->get(['user_id', 'cubi_amount', 'paid_at']);
            $panelRewards = DB::table('pw_referral_rewards')
                ->whereIn('referrer_id', $uids)
                ->whereIn('type', ['registration', 'registration_cubi'])
                ->orderBy('created_at')
                ->get(['referrer_id', 'reward_amount', 'type', 'created_at']);
            $panelBonus = DB::table('pw_referral_rewards')
                ->whereIn('referred_id', function ($q) use ($uids) {
                    $q->select('ID')->from('users')->whereIn('ID', $uids);
                })
                ->where('type', 'signup_bonus')
                ->orderBy('created_at')
                ->get(['referred_id', 'reward_amount', 'created_at']);
            $voucherTopups = DB::table('pw_admin_cubi_topups')
                ->whereIn('user_id', $uids)
                ->where('reason', 'like', 'Voucher:%')
                ->orderBy('created_at')
                ->get(['user_id', 'amount', 'created_at', 'reason']);
            $adminTopups = DB::table('pw_admin_cubi_topups')
                ->whereIn('user_id', $uids)
                ->orderBy('created_at')
                ->get(['user_id', 'amount', 'created_at']);
            $eventDeliveries = DB::table('pw_event_deliveries')
                ->whereIn('user_id', $uids)
                ->orderBy('created_at')
                ->get(['user_id', 'amount', 'created_at']);
            // Partner commission: partner_user_id got Cubi from buyer's invoice
            $cubiRate = config('pw-config.currency.cubi_rate_idr', 1000);
            $partnerCommissions = DB::table('pw_invoices')
                ->whereIn('partner_user_id', $uids)
                ->where('type', 'cubi')->where('status', 'paid')
                ->where('commission_amount', '>', 0)
                ->orderBy('paid_at')
                ->get(['partner_user_id', 'commission_amount', 'paid_at']);
            // Partner withdrawal claimed as Cubi
            $partnerWithdrawals = DB::table('pw_partner_withdrawals')
                ->whereIn('user_id', $uids)
                ->where('payment_method', 'cubi')->where('status', 'approved')
                ->orderBy('processed_at')
                ->get(['user_id', 'amount', 'processed_at']);

            $suspicious = $suspicious->map(function ($row) use ($panelInvoices, $panelRewards, $panelBonus, $voucherTopups, $adminTopups, $eventDeliveries, $partnerCommissions, $partnerWithdrawals, $partnerIds, $cubiRate) {
                if (($row->point ?? 0) != 0) return $row;
                $t = \Carbon\Carbon::parse($row->creatime);
                // Cubi Shop purchase
                $inv = $panelInvoices->first(fn ($i) =>
                    $i->user_id == $row->userid
                    && ($i->cubi_amount * 100) == $row->cash
                    && abs(\Carbon\Carbon::parse($i->paid_at)->diffInMinutes($t)) <= 120);
                if ($inv) { $row->point = 1; return $row; }
                // Referral / Partner reward (referrer gets cubi)
                $rw = $panelRewards->first(fn ($r) =>
                    $r->referrer_id == $row->userid
                    && ($r->reward_amount * 100) == $row->cash
                    && abs(\Carbon\Carbon::parse($r->created_at)->diffInMinutes($t)) <= 120);
                if ($rw) {
                    $row->point = in_array($rw->referrer_id, $partnerIds) ? 3 : 2;
                    return $row;
                }
                // Signup bonus (referred user)
                $bn = $panelBonus->first(fn ($b) =>
                    isset($b->referred_id) && $b->referred_id == $row->userid
                    && ($b->reward_amount * 100) == $row->cash
                    && abs(\Carbon\Carbon::parse($b->created_at)->diffInMinutes($t)) <= 120);
                if ($bn) { $row->point = 2; return $row; }
                // Voucher Cubi
                $vc = $voucherTopups->first(fn ($v) =>
                    $v->user_id == $row->userid
                    && ($v->amount * 100) == $row->cash
                    && abs(\Carbon\Carbon::parse($v->created_at)->diffInMinutes($t)) <= 120);
                if ($vc) { $row->point = 6; return $row; }
                // Admin manual topup
                $adm = $adminTopups->first(fn ($a) =>
                    $a->user_id == $row->userid
                    && ($a->amount * 100) == $row->cash
                    && abs(\Carbon\Carbon::parse($a->created_at)->diffInMinutes($t)) <= 120);
                if ($adm) { $row->point = 5; return $row; }
                // Event prize
                $ev = $eventDeliveries->first(fn ($e) =>
                    $e->user_id == $row->userid
                    && ($e->amount * 100) == $row->cash
                    && abs(\Carbon\Carbon::parse($e->created_at)->diffInMinutes($t)) <= 120);
                if ($ev) { $row->point = 4; return $row; }
                // Partner commission (from buyer's invoice)
                $pc = $partnerCommissions->first(fn ($c) =>
                    $c->partner_user_id == $row->userid
                    && (floor($c->commission_amount / $cubiRate) * 100) == $row->cash
                    && abs(\Carbon\Carbon::parse($c->paid_at)->diffInMinutes($t)) <= 120);
                if ($pc) { $row->point = 3; return $row; }
                // Partner withdrawal as Cubi
                $pw = $partnerWithdrawals->first(fn ($w) =>
                    $w->user_id == $row->userid
                    && (floor($w->amount / $cubiRate) * 100) == $row->cash
                    && abs(\Carbon\Carbon::parse($w->processed_at)->diffInMinutes($t)) <= 120);
                if ($pw) { $row->point = 3; return $row; }
                return $row;
            });
        }

        // Top spenders (from pw_top_sultan)
        $topSpenders = DB::table('pw_top_sultan')->orderByDesc('cash_used')->limit(10)->get();

        // Ghost accounts: entries in usecashlog but NOT in users table
        $ghostAccounts = DB::connection('mysql_game')
            ->table('usecashlog')
            ->where('status', 4)
            ->whereNotIn('userid', function ($q) {
                $q->select('ID')->from('users');
            })
            ->select('userid')
            ->selectRaw('SUM(cash) as total_cash, COUNT(*) as tx_count')
            ->groupBy('userid')
            ->orderByDesc('total_cash')
            ->limit(20)
            ->get();

        // Large single transactions (> 10,000 Cubi = 1,000,000 in DB)
        $largeTx = DB::connection('mysql_game')
            ->table('usecashlog')
            ->where('status', 4)
            ->where('cash', '>', 1000000) // > 10,000 Cubi
            ->orderByDesc('cash')
            ->limit(30)
            ->get()
            ->map(function ($row) {
                $row->username = DB::connection('mysql_game')
                    ->table('users')->where('ID', $row->userid)->value('name');
                return $row;
            });

        // Source detection for $largeTx (same logic as $suspicious)
        $largeTxUnknown = $largeTx->where('point', 0);
        if ($largeTxUnknown->isNotEmpty()) {
            $ltUids = $largeTxUnknown->pluck('userid')->unique()->values()->all();
            $ltInvoices = DB::table('pw_invoices')
                ->whereIn('user_id', $ltUids)
                ->where('type', 'cubi')->where('status', 'paid')
                ->orderBy('paid_at')
                ->get(['user_id', 'cubi_amount', 'paid_at']);
            $ltRewards = DB::table('pw_referral_rewards')
                ->whereIn('referrer_id', $ltUids)
                ->whereIn('type', ['registration', 'registration_cubi'])
                ->orderBy('created_at')
                ->get(['referrer_id', 'reward_amount', 'created_at']);
            $ltVoucherTopups = DB::table('pw_admin_cubi_topups')
                ->whereIn('user_id', $ltUids)
                ->where('reason', 'like', 'Voucher:%')
                ->orderBy('created_at')
                ->get(['user_id', 'amount', 'created_at']);
            $ltAdminTopups = DB::table('pw_admin_cubi_topups')
                ->whereIn('user_id', $ltUids)
                ->orderBy('created_at')
                ->get(['user_id', 'amount', 'created_at']);
            $ltEventDeliveries = DB::table('pw_event_deliveries')
                ->whereIn('user_id', $ltUids)
                ->orderBy('created_at')
                ->get(['user_id', 'amount', 'created_at']);
            $ltCubiRate = config('pw-config.currency.cubi_rate_idr', 1000);
            $ltPartnerCommissions = DB::table('pw_invoices')
                ->whereIn('partner_user_id', $ltUids)
                ->where('type', 'cubi')->where('status', 'paid')
                ->where('commission_amount', '>', 0)
                ->orderBy('paid_at')
                ->get(['partner_user_id', 'commission_amount', 'paid_at']);
            $ltPartnerWithdrawals = DB::table('pw_partner_withdrawals')
                ->whereIn('user_id', $ltUids)
                ->where('payment_method', 'cubi')->where('status', 'approved')
                ->orderBy('processed_at')
                ->get(['user_id', 'amount', 'processed_at']);
            $largeTx = $largeTx->map(function ($row) use ($ltInvoices, $ltRewards, $ltVoucherTopups, $ltAdminTopups, $ltEventDeliveries, $ltPartnerCommissions, $ltPartnerWithdrawals, $partnerIds, $ltCubiRate) {
                if (($row->point ?? 0) != 0) return $row;
                $t = \Carbon\Carbon::parse($row->creatime);
                $inv = $ltInvoices->first(fn ($i) =>
                    $i->user_id == $row->userid
                    && ($i->cubi_amount * 100) == $row->cash
                    && abs(\Carbon\Carbon::parse($i->paid_at)->diffInMinutes($t)) <= 120);
                if ($inv) { $row->point = 1; return $row; }
                $rw = $ltRewards->first(fn ($r) =>
                    $r->referrer_id == $row->userid
                    && ($r->reward_amount * 100) == $row->cash
                    && abs(\Carbon\Carbon::parse($r->created_at)->diffInMinutes($t)) <= 120);
                if ($rw) {
                    $row->point = in_array($rw->referrer_id, $partnerIds) ? 3 : 2;
                    return $row;
                }
                $vc = $ltVoucherTopups->first(fn ($v) =>
                    $v->user_id == $row->userid
                    && ($v->amount * 100) == $row->cash
                    && abs(\Carbon\Carbon::parse($v->created_at)->diffInMinutes($t)) <= 120);
                if ($vc) { $row->point = 6; return $row; }
                $adm = $ltAdminTopups->first(fn ($a) =>
                    $a->user_id == $row->userid
                    && ($a->amount * 100) == $row->cash
                    && abs(\Carbon\Carbon::parse($a->created_at)->diffInMinutes($t)) <= 120);
                if ($adm) { $row->point = 5; return $row; }
                $ev = $ltEventDeliveries->first(fn ($e) =>
                    $e->user_id == $row->userid
                    && ($e->amount * 100) == $row->cash
                    && abs(\Carbon\Carbon::parse($e->created_at)->diffInMinutes($t)) <= 120);
                if ($ev) { $row->point = 4; return $row; }
                $pc = $ltPartnerCommissions->first(fn ($c) =>
                    $c->partner_user_id == $row->userid
                    && (floor($c->commission_amount / $ltCubiRate) * 100) == $row->cash
                    && abs(\Carbon\Carbon::parse($c->paid_at)->diffInMinutes($t)) <= 120);
                if ($pc) { $row->point = 3; return $row; }
                $pw = $ltPartnerWithdrawals->first(fn ($w) =>
                    $w->user_id == $row->userid
                    && (floor($w->amount / $ltCubiRate) * 100) == $row->cash
                    && abs(\Carbon\Carbon::parse($w->processed_at)->diffInMinutes($t)) <= 120);
                if ($pw) { $row->point = 3; return $row; }
                return $row;
            });
        }

        // Source distribution (from panel DB — authoritative per source, in standard Cubi units)
        $eventCubi  = (int) DB::connection('mysql_game')->table('usecashlog')->where('status', 4)->where('point', 4)->sum('cash') / 100;
        $eventCount = DB::connection('mysql_game')->table('usecashlog')->where('status', 4)->where('point', 4)->count();
        $eventUsers = DB::connection('mysql_game')->table('usecashlog')->where('status', 4)->where('point', 4)->distinct('userid')->count('userid');
        $adminCubi  = (int) DB::connection('mysql_game')->table('usecashlog')->where('status', 4)->where('point', 5)->sum('cash') / 100;
        $adminCount = DB::connection('mysql_game')->table('usecashlog')->where('status', 4)->where('point', 5)->count();
        $adminUsers = DB::connection('mysql_game')->table('usecashlog')->where('status', 4)->where('point', 5)->distinct('userid')->count('userid');

        $sourceDistribution = [
            [
                'label' => 'Cubi Shop',
                'color' => '#63b3ed',
                'bg'    => 'rgba(99,179,237,.15)',
                'count' => DB::table('pw_invoices')->where('type', 'cubi')->where('status', 'paid')->count(),
                'total' => (int) DB::table('pw_invoices')->where('type', 'cubi')->where('status', 'paid')->sum('cubi_amount'),
                'users' => DB::table('pw_invoices')->where('type', 'cubi')->where('status', 'paid')->distinct('user_id')->count('user_id'),
            ],
            [
                'label' => 'Referral',
                'color' => '#50c878',
                'bg'    => 'rgba(80,200,120,.15)',
                'count' => DB::table('pw_referral_rewards')->where('type', 'registration_cubi')->whereNotIn('referrer_id', $partnerIds)->count(),
                'total' => (int) DB::table('pw_referral_rewards')->where('type', 'registration_cubi')->whereNotIn('referrer_id', $partnerIds)->sum('reward_amount'),
                'users' => DB::table('pw_referral_rewards')->where('type', 'registration_cubi')->whereNotIn('referrer_id', $partnerIds)->distinct('referrer_id')->count('referrer_id'),
            ],
            [
                'label' => 'Partner',
                'color' => '#c084fc',
                'bg'    => 'rgba(168,85,247,.15)',
                'count' => DB::table('pw_referral_rewards')->where('type', 'registration_cubi')->whereIn('referrer_id', $partnerIds)->count(),
                'total' => (int) DB::table('pw_referral_rewards')->where('type', 'registration_cubi')->whereIn('referrer_id', $partnerIds)->sum('reward_amount'),
                'users' => DB::table('pw_referral_rewards')->where('type', 'registration_cubi')->whereIn('referrer_id', $partnerIds)->distinct('referrer_id')->count('referrer_id'),
            ],
            [
                'label' => 'Event',
                'color' => '#fbbf24',
                'bg'    => 'rgba(251,191,36,.15)',
                'count' => $eventCount,
                'total' => $eventCubi,
                'users' => $eventUsers,
            ],
            [
                'label' => 'Admin',
                'color' => '#ffffff',
                'bg'    => 'rgba(220,38,38,.85)',
                'count' => $adminCount,
                'total' => $adminCubi,
                'users' => $adminUsers,
            ],
            [
                'label' => 'Voucher',
                'color' => '#ffffff',
                'bg'    => 'rgba(14,165,233,.85)',
                'count' => DB::table('pw_admin_cubi_topups')->where('reason', 'like', 'Voucher:%')->count(),
                'total' => (int) DB::table('pw_admin_cubi_topups')->where('reason', 'like', 'Voucher:%')->sum('amount'),
                'users' => DB::table('pw_admin_cubi_topups')->where('reason', 'like', 'Voucher:%')->distinct('user_id')->count('user_id'),
            ],
            [
                'label' => 'Unknown',
                'color' => '#e05252',
                'bg'    => 'rgba(220,60,60,.15)',
                'count' => null,
                'total' => $stats['unknown_cubi'],
                'users' => null,
            ],
        ];

        return view('admin.cubi-monitor', compact(
            'stats', 'suspicious', 'topSpenders', 'ghostAccounts', 'largeTx', 'sourceDistribution'
        ));
    }

    public function userDetail(Request $request, int $userId): View
    {
        $user = DB::connection('mysql_game')->table('users')->where('ID', $userId)->first();
        $characters = DB::connection('mysql_game')->table('roles')->where('account_id', $userId)->get();
        $isGM = DB::connection('mysql_game')->table('auth')->where('userid', $userId)->exists();

        // All cash log entries for this user
        $cashLog = DB::connection('mysql_game')
            ->table('usecashlog')
            ->where('userid', $userId)
            ->orderByDesc('creatime')
            ->get();

        // Pending entries
        $pending = DB::connection('mysql_game')
            ->table('usecashnow')
            ->where('userid', $userId)
            ->get();

        // Real-time cash data from gamedbd
        $liveData = null;
        try {
            $gameDb = new GameDbService();
            $liveData = $gameDb->getUserCash($userId, false);
        } catch (\Throwable $e) {
            // gamedbd might be offline
        }

        $logTotal = $cashLog->where('status', 4)->sum('cash');

        return view('admin.cubi-monitor-user', compact(
            'user', 'userId', 'characters', 'isGM', 'cashLog', 'pending', 'liveData', 'logTotal'
        ));
    }

    private function getOverallStats(): array
    {
        $game = 'mysql_game';

        // Game DB total (ground truth for all delivered Cubi)
        $totalDeliveredRaw = (float) DB::connection($game)
            ->table('usecashlog')
            ->where('status', 4)
            ->sum('cash');
        $totalDelivered = $totalDeliveredRaw / 100; // cash×100 → standard Cubi units

        $totalUsers = DB::connection($game)
            ->table('usecashlog')
            ->where('status', 4)
            ->distinct('userid')
            ->count('userid');

        // Panel DB sources — authoritative per source (values in standard Cubi units)
        $shopCubi = (int) DB::table('pw_invoices')
            ->where('type', 'cubi')
            ->where('status', 'paid')
            ->sum('cubi_amount');

        $shopUsers = DB::table('pw_invoices')
            ->where('type', 'cubi')
            ->where('status', 'paid')
            ->distinct('user_id')
            ->count('user_id');

        // Referral reward Cubi — regular (non-partner) referrers
        $referralCubi = (int) DB::table('pw_referral_rewards')
            ->where('type', 'registration_cubi')
            ->whereNotIn('referrer_id', function ($q) {
                $q->select('user_id')->from('pw_referral_partners');
            })
            ->sum('reward_amount');

        $referralUsers = DB::table('pw_referral_rewards')
            ->where('type', 'registration_cubi')
            ->whereNotIn('referrer_id', function ($q) {
                $q->select('user_id')->from('pw_referral_partners');
            })
            ->distinct('referrer_id')
            ->count('referrer_id');

        // Partner reward Cubi — registered partner accounts
        $partnerCubi = (int) DB::table('pw_referral_rewards')
            ->where('type', 'registration_cubi')
            ->whereIn('referrer_id', function ($q) {
                $q->select('user_id')->from('pw_referral_partners');
            })
            ->sum('reward_amount');

        $partnerUsers = DB::table('pw_referral_rewards')
            ->where('type', 'registration_cubi')
            ->whereIn('referrer_id', function ($q) {
                $q->select('user_id')->from('pw_referral_partners');
            })
            ->distinct('referrer_id')
            ->count('referrer_id');

        $voucherCubi = (int) DB::table('pw_admin_cubi_topups')
            ->where('reason', 'like', 'Voucher:%')
            ->sum('amount');

        $voucherUsers = DB::table('pw_admin_cubi_topups')
            ->where('reason', 'like', 'Voucher:%')
            ->distinct('user_id')
            ->count('user_id');

        // Unknown = game total minus all panel-tracked sources (GM/manual additions)
        $unknownCubi = max(0, (int) ($totalDelivered - $shopCubi - $referralCubi - $partnerCubi - $voucherCubi));

        $pendingCount = DB::connection($game)->table('usecashnow')->count();
        $gmCount      = DB::connection($game)->table('auth')->distinct('userid')->count('userid');

        return [
            'total_delivered' => $totalDelivered,
            'total_users'     => $totalUsers,
            'shop_cubi'       => $shopCubi,
            'shop_users'      => $shopUsers,
            'referral_cubi'   => $referralCubi,
            'referral_users'  => $referralUsers,
            'partner_cubi'    => $partnerCubi,
            'partner_users'   => $partnerUsers,
            'voucher_cubi'    => $voucherCubi,
            'voucher_users'   => $voucherUsers,
            'unknown_cubi'    => $unknownCubi,
            'pending_queue'   => $pendingCount,
            'gm_count'        => $gmCount,
        ];
    }
}
