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
use App\Models\News;
use App\Models\ShopLog;
use App\Models\User;
use App\Services\GameServerService;
use Illuminate\Support\Facades\Cache;
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
            ];
        });

        $recentInvoices = Invoice::with('user')->latest()->take(10)->get();
        $recentShopLogs = ShopLog::with('user', 'item')->latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'recentInvoices', 'recentShopLogs'));
    }
}
