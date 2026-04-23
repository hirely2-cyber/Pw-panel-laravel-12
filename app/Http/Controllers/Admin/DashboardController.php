<?php

/*
 * @author   Wahyu Suhandi <andietz.orion@gmail.com>
 * @link     https://wa.me/6208118719377
 * @project  Perfect World Panel
 * @version  2.0.0
 * @license  MIT
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\LaunchEvent;
use App\Models\News;
use App\Models\ShopLog;
use App\Models\User;
use App\Services\GameServerService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = Cache::remember('admin_stats', 300, function () {
            return [
                'total_players'    => User::count(),
                'online_players'   => GameServerService::onlineCount(),
                'total_income'     => Invoice::where('status', 'paid')->sum('amount'),
                'total_cubi'       => Invoice::where('type', 'cubi')->where('status', 'paid')->sum('cubi_amount'),
                'total_donate'     => Invoice::paid()->sum('gold_amount'),
                'pending_invoices' => Invoice::pending()->count(),
                'total_referrals'  => User::whereNotNull('referred_by')->count(),
                'total_roles'      => DB::connection('mysql_game')->table('roles')->count(),
                'pending_cubi'     => DB::connection('mysql_game')->table('usecashnow')->count(),
                'active_events'    => LaunchEvent::whereIn('status', ['active'])->count(),
            ];
        });

        $recentInvoices = Invoice::with('user')->latest()->take(8)->get();
        $recentShopLogs = ShopLog::with('user', 'item')->latest()->take(8)->get();

        // Data chart: registrasi 30 hari terakhir
        $regChartRaw = Cache::remember('admin_chart_reg', 600, function () {
            return DB::table('users')
                ->selectRaw('DATE(creatime) as tgl, COUNT(*) as total')
                ->where('creatime', '>=', now()->subDays(29)->startOfDay())
                ->groupBy('tgl')
                ->orderBy('tgl')
                ->pluck('total', 'tgl');
        });

        // Fill semua hari 30 hari terakhir (hari tanpa registrasi = 0)
        $regChart = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $regChart[] = [
                'date'  => Carbon::parse($date)->format('d M'),
                'total' => (int) ($regChartRaw[$date] ?? 0),
            ];
        }

        // Data chart: income 30 hari terakhir
        $incomeChartRaw = Cache::remember('admin_chart_income', 600, function () {
            return DB::table('pw_invoices')
                ->selectRaw('DATE(created_at) as tgl, COALESCE(SUM(amount),0) as total')
                ->where('status', 'paid')
                ->where('created_at', '>=', now()->subDays(29)->startOfDay())
                ->groupBy('tgl')
                ->orderBy('tgl')
                ->pluck('total', 'tgl');
        });

        $incomeChart = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $incomeChart[] = [
                'date'  => Carbon::parse($date)->format('d M'),
                'total' => (float) ($incomeChartRaw[$date] ?? 0),
            ];
        }

        // Recent registrations (5 user terbaru)
        $recentUsers = User::latest('creatime')->take(5)->get(['ID', 'name', 'email', 'creatime', 'referred_by']);

        // Active event info
        $activeEvent = LaunchEvent::where('status', 'active')->latest()->first();

        return view('admin.dashboard', compact(
            'stats',
            'recentInvoices',
            'recentShopLogs',
            'regChart',
            'incomeChart',
            'recentUsers',
            'activeEvent'
        ));
    }
}
