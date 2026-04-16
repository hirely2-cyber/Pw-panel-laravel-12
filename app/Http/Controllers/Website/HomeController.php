<?php

/*
 * @author   Wahyu Suhandi <andietz.orion@gmail.com>
 * @link     https://wa.me/6208118719377
 * @project  Perfect World Panel
 * @version  2.0.0
 * @license  MIT
 */

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\EventParticipant;
use App\Models\Invoice;
use App\Models\LaunchEvent;
use App\Models\News;
use App\Models\ReferralMilestone;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $news    = News::active()->with('author')->latest()->take(7)->get();
        $ranking = Cache::remember('ranking_preview', config('pw-config.ranking_cache_minutes') * 60, function () {
            // Exclude GM accounts from game DB auth table
            $gmUserIds = DB::connection('mysql_game')->table('auth')->distinct()->pluck('userid')->toArray();

            return \App\Models\RankingPlayer::whereNotIn('user_id', $gmUserIds)
                ->orderBy('pk_points', 'desc')->orderBy('level', 'desc')->take(10)->get();
        });
        // Admin Web: role = 'admin' di panel
        $webAdmins = User::where('role', 'admin')->orderBy('name')->get();

        // GM Game: ada di tabel auth game DB
        $gmIds = DB::connection('mysql_game')->table('auth')->distinct()->pluck('userid');
        $gameGms = User::whereIn('ID', $gmIds)->orderBy('name')->get();

        return view('website.home', compact('news', 'ranking', 'webAdmins', 'gameGms'));
    }

    public function show(string $slug): View
    {
        $article = News::active()->where('slug', $slug)->firstOrFail();
        $recent  = News::active()->where('id', '!=', $article->id)->latest()->take(4)->get();

        return view('website.news-detail', compact('article', 'recent'));
    }

    public function newsList(): View
    {
        $news = News::active()->with('author')->latest()->paginate(8);

        return view('website.news', compact('news'));
    }

    public function ranking(): View
    {
        // Exclude GM/Admin accounts from player ranking (game DB auth table)
        $gmUserIds = DB::connection('mysql_game')->table('auth')->distinct()->pluck('userid')->toArray();

        $players = \App\Models\RankingPlayer::whereNotIn('user_id', $gmUserIds)
            ->orderBy('pk_points', 'desc')
            ->orderBy('level', 'desc')
            ->paginate(25, ['*'], 'players_page')
            ->withQueryString();

        $factions = \App\Models\RankingFaction::orderBy('rank', 'asc')
            ->paginate(25, ['*'], 'guilds_page')
            ->withQueryString();

        return view('website.ranking', compact('players', 'factions'));
    }

    public function donatur(Request $request): View
    {
        // Parse selected month or default to current
        $monthInput = $request->input('month');
        if ($monthInput && preg_match('/^\d{4}-\d{2}$/', $monthInput)) {
            $date = \Carbon\Carbon::createFromFormat('Y-m', $monthInput)->startOfMonth();
        } else {
            $date = now()->startOfMonth();
        }

        $monthStart = $date->copy()->startOfMonth();
        $monthEnd = $date->copy()->endOfMonth();
        $currentMonth = $date->translatedFormat('F Y');
        $selectedMonth = $date->format('Y-m');

        // Available months from invoices
        $availableMonths = Cache::remember('spender_available_months', 300, function () {
            return Invoice::paid()
                ->selectRaw("DISTINCT DATE_FORMAT(paid_at, '%Y-%m') as ym")
                ->orderByDesc('ym')
                ->pluck('ym')
                ->toArray();
        });

        // Make sure current month is always in the list
        $nowYm = now()->format('Y-m');
        if (!in_array($nowYm, $availableMonths)) {
            array_unshift($availableMonths, $nowYm);
        }

        $cacheKey = 'spender_of_month_' . $date->format('Y_m');

        $donatur = Cache::remember($cacheKey, 300, function () use ($monthStart, $monthEnd) {
            return Invoice::paid()
                ->with('user:ID,name,truename')
                ->whereBetween('paid_at', [$monthStart, $monthEnd])
                ->selectRaw('user_id,
                    SUM(amount) as total_amount,
                    COUNT(*) as total_transaksi,
                    MAX(paid_at) as last_paid')
                ->groupBy('user_id')
                ->orderByDesc('total_amount')
                ->take(50)
                ->get()
                ->each(function ($d) {
                    // Truename
                    $truename = $d->user?->truename;
                    $d->display_truename = ($truename && trim($truename) !== '') ? $truename : '—';

                    // Masked username
                    $name = $d->user?->name ?? 'Anonim';
                    $len = mb_strlen($name);
                    if ($len <= 2) {
                        $d->display_username = mb_substr($name, 0, 1) . '***';
                    } elseif ($len <= 4) {
                        $d->display_username = mb_substr($name, 0, 1) . str_repeat('*', $len - 2) . mb_substr($name, -1);
                    } else {
                        $d->display_username = mb_substr($name, 0, 2) . str_repeat('*', $len - 3) . mb_substr($name, -1);
                    }

                    // Keep display_name for podium (prefer truename, fall back to masked)
                    $d->display_name = $d->display_truename !== '—' ? $d->display_truename : $d->display_username;

                    // Characters from pw_ranking_players
                    $d->characters = DB::table('pw_ranking_players')
                        ->where('user_id', $d->user_id)
                        ->pluck('character_name')
                        ->toArray();
                });
        });

        $lastDonatur = Cache::remember('last_donatur', 120, function () {
            return Invoice::paid()
                ->with('user:ID,name,truename')
                ->latest('paid_at')
                ->take(10)
                ->get();
        });

        return view('website.donatur', compact('donatur', 'lastDonatur', 'currentMonth', 'selectedMonth', 'availableMonths'));
    }

    public function event(): View
    {
        // Only show active events
        $event = LaunchEvent::where('status', 'active')
            ->latest('start_at')
            ->first();

        if (! $event) {
            return view('website.event-empty');
        }

        // Pre-launch event: show referral-based view
        if ($event->isPreLaunch()) {
            $reqLevel = $event->referral_req_level ?? 50;

            // Total Cubi from all tiers (max tier reward as total pool)
            $tiers = collect($event->referral_tiers ?? []);
            $totalCubi = $tiers->sum('reward');
            $totalRupiah = $totalCubi * 1000; // 1 Cubi = IDR 1.000

            // Total registered during event period
            $totalRegistered = User::whereBetween('creatime', [$event->start_at, $event->end_at])->count();
            $totalReferrals = User::whereNotNull('referred_by')
                ->whereBetween('creatime', [$event->start_at, $event->end_at])->count();

            // Top 3 referrers (with truename)
            $referrers = User::select('users.ID', 'users.name', 'users.truename', 'users.referral_code', 'users.creatime')
                ->selectRaw('(SELECT COUNT(*) FROM users AS r WHERE r.referred_by = users.ID) as referral_count')
                ->having('referral_count', '>', 0)
                ->orderByDesc('referral_count')
                ->limit(3)
                ->get();

            // Admin names for notes section
            $adminNames = User::where('role', 'admin')->pluck('truename')->filter()->implode(', ');

            return view('website.event-prelaunch', compact(
                'event', 'tiers', 'totalCubi', 'totalRupiah',
                'totalRegistered', 'totalReferrals', 'referrers', 'reqLevel', 'adminNames'
            ));
        }

        // Grand-launch event: show level/cultivation leaderboard
        // Top 3 qualified (for podium)
        $topThree = $event->qualifiedParticipants()->limit(3)->get();

        $qualifiedCount = $event->participants()->whereNotNull('qualified_at')->count();

        // All participants sorted: qualified first (by time), then by level+cultivation
        $participants = $event->participants()
            ->orderByRaw('qualified_at IS NULL, qualified_at ASC')
            ->orderByDesc('level')
            ->orderByDesc('cultivation')
            ->paginate(100);

        return view('website.event', compact('event', 'topThree', 'qualifiedCount', 'participants'));
    }

    public function download(): View
    {
        $downloadUrl      = \App\Models\Setting::get('download_url');
        $downloadUrlPart  = \App\Models\Setting::get('download_url_part');
        $downloadUrlPatch = \App\Models\Setting::get('download_url_patch');

        return view('website.download', compact('downloadUrl', 'downloadUrlPart', 'downloadUrlPatch'));
    }

    public function referralRanking(): View
    {
        // Find the active or most recent pre_launch event
        $event = LaunchEvent::where('type', 'pre_launch')
            ->whereIn('status', ['active', 'ended', 'distributed'])
            ->orderByRaw("FIELD(status, 'active', 'ended', 'distributed')")
            ->latest('start_at')
            ->first();

        $referrers = collect();
        $registeredUsers = collect();
        $totalRegistered = 0;

        if ($event) {
            $reqLevel = $event->referral_req_level ?? 50;

            // Users who have referrals, ordered by count
            $referrers = User::select('users.ID', 'users.name', 'users.referral_code', 'users.creatime')
                ->selectRaw('(SELECT COUNT(*) FROM users AS r WHERE r.referred_by = users.ID) as referral_count')
                ->having('referral_count', '>', 0)
                ->orderByDesc('referral_count')
                ->paginate(50, ['*'], 'rank_page')
                ->withQueryString();

            // Add referred users for each referrer
            $referrerIds = $referrers->pluck('ID')->toArray();
            $referredMap = User::whereIn('referred_by', $referrerIds)
                ->get(['ID', 'name', 'referred_by', 'creatime'])
                ->groupBy('referred_by');

            // Batch get max levels of referred users
            $allReferredIds = $referredMap->flatten()->pluck('ID')->toArray();
            $maxLevelMap = [];
            if (!empty($allReferredIds)) {
                try {
                    DB::connection('mysql_game')
                        ->table('roles')
                        ->whereIn('account_id', $allReferredIds)
                        ->selectRaw('account_id, MAX(role_level) as max_level')
                        ->groupBy('account_id')
                        ->get()
                        ->each(fn($r) => $maxLevelMap[$r->account_id] = (int) $r->max_level);
                } catch (\Throwable $e) {}
            }

            // Attach data to each referrer
            foreach ($referrers as $referrer) {
                $referred = $referredMap[$referrer->ID] ?? collect();
                $referrer->referred_users = $referred->map(function ($u) use ($maxLevelMap, $reqLevel) {
                    $maxLevel = $maxLevelMap[$u->ID] ?? 0;
                    return (object) [
                        'name'     => $u->name,
                        'joined'   => $u->creatime,
                        'level'    => $maxLevel,
                        'level_ok' => $maxLevel >= $reqLevel,
                    ];
                });
                $referrer->qualified_count = $referrer->referred_users->filter(fn($u) => $u->level_ok)->count();
            }

            // Total registered users (with referral code)
            $totalRegistered = User::whereNotNull('referral_code')->count();

            // Distributed milestones
            $milestones = ReferralMilestone::where('event_id', $event->id)
                ->where('distributed', true)
                ->with('user')
                ->orderBy('milestone')
                ->get();
        } else {
            $milestones = collect();
        }

        return view('website.referral-ranking', compact(
            'event', 'referrers', 'totalRegistered', 'milestones'
        ));
    }
}
