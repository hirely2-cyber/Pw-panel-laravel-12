<?php

/*
 * @author   Wahyu Suhandi <andietz.orion@gmail.com>
 * @link     https://wa.me/6208118719377
 * @project  Perfect World Panel
 * @version  2.0.0
 * @license  MIT
 */

namespace App\Http\Controllers\GM;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\News;
use App\Models\ServiceLog;
use App\Models\ShopLog;
use App\Models\User;
use App\Services\GameServerService;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = Cache::remember('gm_stats', 300, function () {
            return [
                'total_players'    => User::where('role', 'player')->count(),
                'online_players'   => GameServerService::onlineCount(),
                'total_cubi'       => Invoice::where('type', 'cubi')->where('status', 'paid')->sum('cubi_amount'),
                'total_donate'     => Invoice::paid()->sum('gold_amount'),
                'pending_invoices' => Invoice::pending()->count(),
            ];
        });

        $pendingServices = ServiceLog::where('status', 'pending')->with('user', 'service')->latest()->take(10)->get();
        $recentNews      = News::latest()->take(5)->get();
        $recentShopLogs  = ShopLog::with('user', 'item')->latest()->take(10)->get();

        return view('gm.dashboard', compact('stats', 'pendingServices', 'recentNews', 'recentShopLogs'));
    }
}
