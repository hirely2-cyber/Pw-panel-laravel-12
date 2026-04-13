<?php

/*
 * @author   Wahyu Suhandi <andietz.orion@gmail.com>
 * @link     https://wa.me/6208118719377
 * @project  Perfect World Panel
 * @version  2.0.0
 * @license  MIT
 */

use App\Http\Controllers\Admin;
use App\Http\Controllers\Front;
use App\Http\Controllers\GM;
use App\Http\Controllers\Partner;
use App\Http\Controllers\PayHookWebhookController;
use App\Http\Controllers\Website\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
// Language switcher
Route::get('/lang/{locale}', function (string $locale) {
    if (in_array($locale, ['id', 'en'])) {
        session(['locale' => $locale]);
        cookie()->queue(cookie('locale', $locale, 60 * 24 * 365));
    }
    return redirect()->back()->withInput();
})->name('lang.switch');

// Captcha refresh (AJAX)
Route::get('/auth/captcha/refresh', function () {
    $chars = \App\Services\CaptchaService::refresh();
    return response()->json(['chars' => $chars]);
})->name('captcha.refresh');

// Live server stats (JSON — public)
Route::get('/api/online-count', function () {
    $service = \App\Services\GameServerService::class;
    return response()->json([
        'online'   => $service::onlineCount(),
        'accounts' => $service::accountCount(),
        'server'   => $service::isOnline(),
    ], 200, ['Cache-Control' => 'no-store, max-age=0']);
})->name('api.online_count');

Route::get('/', [HomeController::class, 'index'])->name('home');

// Active character selector (AJAX)
Route::middleware('auth')->group(function () {
    Route::get('/api/characters', function () {
        return response()->json(auth()->user()->gameCharacters());
    })->name('api.characters');

    Route::post('/api/character/select', function (Request $request) {
        $request->validate(['role_id' => 'required|integer']);
        $char = auth()->user()->gameCharacters()->firstWhere('role_id', $request->role_id);
        if (! $char) {
            return response()->json(['error' => 'Character not found'], 404);
        }
        session(['active_character' => $char]);
        return response()->json(['ok' => true, 'character' => $char]);
    })->name('api.character.select');
});
Route::get('/news', [HomeController::class, 'newsList'])->name('news.index');
Route::get('/news/{slug}', [HomeController::class, 'show'])->name('news.show');
Route::get('/ranking', [HomeController::class, 'ranking'])->name('ranking')
    ->middleware('feature:ranking');
Route::get('/donatur', [HomeController::class, 'donatur'])->name('donatur');
Route::get('/event', [HomeController::class, 'event'])->name('event');
Route::get('/download', [HomeController::class, 'download'])->name('download');

// Legal pages
Route::get('/tos', function () {
    return view('website.tos');
})->name('tos');
Route::get('/privacy', function () {
    return view('website.privacy');
})->name('privacy');
Route::get('/terms', function () {
    return view('website.terms');
})->name('terms');

/*
|--------------------------------------------------------------------------
| PayHook Webhook (no auth, verified by HMAC signature)
|--------------------------------------------------------------------------
*/
Route::post('/api/webhook/payhook', [PayHookWebhookController::class, 'handle'])
    ->name('webhook.payhook')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

/*
|--------------------------------------------------------------------------
| Authenticated User (Player) Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [Front\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [Front\DashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [Front\DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/change-password', [Front\DashboardController::class, 'changePassword'])->name('profile.change-password');

    // Donate (Top-up)
    Route::middleware('feature:donate')->group(function () {
        Route::get('/donate', [Front\DonateController::class, 'index'])->name('donate');
        Route::post('/donate/invoice', [Front\DonateController::class, 'createInvoice'])->name('donate.invoice');
        Route::get('/donate/invoice/{invoiceNumber}', [Front\DonateController::class, 'show'])->name('donate.invoice.show');
        Route::get('/donate/invoice/{invoiceNumber}/status', [Front\DonateController::class, 'status'])->name('donate.invoice.status');
    Route::post('/donate/invoice/{invoiceNumber}/expire', [Front\DonateController::class, 'expire'])->name('donate.invoice.expire');
        Route::get('/donate/history', [Front\DonateController::class, 'history'])->name('donate.history');
    });

    // Shop (Cash Shop using in-game Gold)
    Route::middleware('feature:shop')->group(function () {
        Route::get('/shop', [Front\ShopController::class, 'index'])->name('shop');
        Route::post('/shop/buy/{item}', [Front\ShopController::class, 'buy'])->name('shop.buy');
        Route::get('/shop/history', [Front\ShopController::class, 'history'])->name('shop.history');
    });

    // Cubi Shop (buy Cubi Coin with real money + refcode discount)
    Route::get('/cubi-shop', [Front\CubiShopController::class, 'index'])->name('cubi-shop');
    Route::post('/cubi-shop/validate-refcode', [Front\CubiShopController::class, 'validateRefcode'])->name('cubi-shop.validate-refcode');
    Route::post('/cubi-shop/invoice', [Front\CubiShopController::class, 'createInvoice'])->name('cubi-shop.invoice');

    // Vote
    Route::middleware('feature:vote')->group(function () {
        Route::get('/vote', [Front\VoteController::class, 'index'])->name('vote');
        Route::post('/vote/{site}', [Front\VoteController::class, 'vote'])->name('vote.submit');
    });

    // Voucher Redeem
    Route::middleware('feature:voucher')->group(function () {
        Route::get('/voucher', [Front\VoucherController::class, 'index'])->name('voucher');
        Route::post('/voucher/redeem', [Front\VoucherController::class, 'redeem'])->name('voucher.redeem');
    });

    // Services (char rename, faction, etc.)
    Route::middleware('feature:service')->group(function () {
        Route::get('/services', [Front\ServiceController::class, 'index'])->name('services');
        Route::post('/services/order/{service}', [Front\ServiceController::class, 'order'])->name('services.order');
        Route::get('/services/history', [Front\ServiceController::class, 'history'])->name('services.history');
    });

    // Partner Application
    Route::get('/partner-apply', [Front\PartnerApplyController::class, 'index'])->name('partner-apply');
    Route::post('/partner-apply', [Front\PartnerApplyController::class, 'store'])->name('partner-apply.store');

});

/*
|--------------------------------------------------------------------------
| Admin Panel Routes
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Admin Panel Routes
|--------------------------------------------------------------------------
| Routes are split into two tiers:
|   - webadmin: accessible by Admin (superadmin) + Web Admin
|   - admin:    restricted to Superadmin only (sensitive operations)
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'webadmin'])->group(function () {

    // ─── Shared: Admin + Web Admin ──────────────────────────────

    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Member list & detail (read-only for Web Admin)
    Route::get('members', [Admin\MemberController::class, 'index'])->name('members.index');
    Route::get('members/{user}', [Admin\MemberController::class, 'show'])->name('members.show');
    Route::get('members/{user}/character/{roleId}', [Admin\MemberController::class, 'characterDetail'])->name('members.character');
    Route::post('members/{user}/character/{roleId}', [Admin\MemberController::class, 'saveCharacter'])->name('members.character.save');
    Route::post('members/{user}/reset-password', [Admin\MemberController::class, 'resetPassword'])->name('members.reset-password');

    // News management
    Route::resource('news', Admin\NewsController::class);

    // Shop management
    Route::resource('shop', Admin\ShopController::class);
    Route::post('shop/{item}/toggle', [Admin\ShopController::class, 'toggle'])->name('shop.toggle');

    // Vote management
    Route::resource('vote', Admin\VoteController::class)->except(['show']);

    // Voucher management
    Route::resource('voucher', Admin\VoucherController::class);
    Route::post('voucher/generate', [Admin\VoucherController::class, 'generate'])->name('voucher.generate');

    // Service management
    Route::resource('service', Admin\ServiceController::class);

    // Ranking management
    Route::get('ranking', [Admin\RankingController::class, 'index'])->name('ranking');
    Route::post('ranking/refresh', [Admin\RankingController::class, 'refresh'])->name('ranking.refresh');
    Route::post('ranking/faction-name', [Admin\RankingController::class, 'saveFactionName'])->name('ranking.faction.name');

    // Broadcast (world chat) — accessible by Web Admin + Admin
    Route::get('broadcast', [Admin\BroadcastController::class, 'index'])->name('broadcast');
    Route::post('broadcast', [Admin\BroadcastController::class, 'send'])->name('broadcast.send');

    // Username search (AJAX autocomplete)
    Route::get('api/users/search', function (Request $request) {
        $q = $request->input('q', '');
        if (strlen($q) < 2) return response()->json([]);
        return response()->json(
            \App\Models\User::where('name', 'like', "%{$q}%")
                ->whereNotIn('role', ['admin'])
                ->select('ID', 'name', 'email', 'role')
                ->limit(10)
                ->get()
        );
    })->name('api.users.search');

    // Cubi Monitor (security audit) — accessible by webadmin + superadmin
    Route::get('cubi-monitor', [Admin\CubiMonitorController::class, 'index'])->name('cubi-monitor');
    Route::get('cubi-monitor/user/{userId}', [Admin\CubiMonitorController::class, 'userDetail'])->name('cubi-monitor.user');

    // Server Control — accessible by webadmin + superadmin
    Route::get('server-control', [Admin\ServerControlController::class, 'index'])->name('server-control');
    Route::post('server-control/path', [Admin\ServerControlController::class, 'savePath'])->name('server-control.path');
    Route::post('server-control/action', [Admin\ServerControlController::class, 'action'])->name('server-control.action');
    Route::get('server-control/status', [Admin\ServerControlController::class, 'status'])->name('server-control.status');

    // DATAFILE Control — upload/replace by webadmin + superadmin
    Route::get('datafile-control', [Admin\DatafileControlController::class, 'adminIndex'])->name('datafile-control');
    Route::post('datafile-control/path', [Admin\DatafileControlController::class, 'savePath'])->name('datafile-control.path');
    Route::post('datafile-control/upload', [Admin\DatafileControlController::class, 'upload'])->name('datafile-control.upload');

    // ─── Superadmin Only ────────────────────────────────────────

    Route::middleware('admin')->group(function () {

        // Member management (write operations — superadmin only)
        Route::put('members/{user}', [Admin\MemberController::class, 'update'])->name('members.update');
        Route::delete('members/{user}', [Admin\MemberController::class, 'destroy'])->name('members.destroy');
        Route::post('members/{user}/topup', [Admin\MemberController::class, 'topup'])->name('members.topup');
        Route::post('members/{user}/ban', [Admin\MemberController::class, 'ban'])->name('members.ban');
        Route::post('members/{user}/unban', [Admin\MemberController::class, 'unban'])->name('members.unban');
        Route::post('members/{user}/cubi-topup', [Admin\MemberController::class, 'cubiTopup'])->name('members.cubi-topup');

        // Donate / Invoice management
        Route::get('donate', [Admin\DonateController::class, 'index'])->name('donate');
        Route::get('donate/{invoice}', [Admin\DonateController::class, 'show'])->name('donate.show');
        Route::post('donate/{invoice}/approve', [Admin\DonateController::class, 'approve'])->name('donate.approve');
        Route::post('donate/{invoice}/reject', [Admin\DonateController::class, 'reject'])->name('donate.reject');

        // Settings
        Route::get('settings', [Admin\SettingController::class, 'index'])->name('settings');
        Route::get('settings/content', [Admin\SettingController::class, 'content'])->name('settings.content');
        Route::post('settings/content', [Admin\SettingController::class, 'updateContent'])->name('settings.update');
        Route::get('settings/panel', [Admin\SettingController::class, 'panel'])->name('settings.panel');
        Route::post('settings/panel', [Admin\SettingController::class, 'updatePanel'])->name('settings.panel.update');

        // Game Mailer (in-game mailbox)
        Route::get('mailer', [Admin\MailerController::class, 'index'])->name('mailer');
        Route::post('mailer', [Admin\MailerController::class, 'send'])->name('mailer.send');

        // Game Configuration (gdeliveryd attributes)
        Route::get('game-config', [Admin\GameConfigController::class, 'index'])->name('game-config');
        Route::get('game-config/fetch', [Admin\GameConfigController::class, 'fetch'])->name('game-config.fetch');
        Route::post('game-config/toggle', [Admin\GameConfigController::class, 'toggleAttribute'])->name('game-config.toggle');
        Route::post('game-config/set-attr', [Admin\GameConfigController::class, 'setAttribute'])->name('game-config.set-attr');
        Route::post('game-config/max-online', [Admin\GameConfigController::class, 'setMaxOnline'])->name('game-config.max-online');

        // PW Backup Monitor (superadmin only)
        Route::get('backup-monitor', [Admin\BackupMonitorController::class, 'index'])->name('backup-monitor');
        Route::post('backup-monitor/download', [Admin\BackupMonitorController::class, 'download'])->name('backup-monitor.download');
        Route::post('backup-monitor/destroy', [Admin\BackupMonitorController::class, 'destroy'])->name('backup-monitor.destroy');

        // GM Account Management
        Route::get('gm', [Admin\GMManagementController::class, 'index'])->name('gm.index');
        Route::post('gm/promote', [Admin\GMManagementController::class, 'promote'])->name('gm.promote');
        Route::post('gm/{user}/demote', [Admin\GMManagementController::class, 'demote'])->name('gm.demote');
        Route::post('gm/{user}/perms', [Admin\GMManagementController::class, 'updatePerms'])->name('gm.perms');

        // Referral Management
        Route::get('referral', [Admin\ReferralController::class, 'index'])->name('referral');
        Route::get('referral/partners', [Admin\ReferralController::class, 'partners'])->name('referral.partners');
        Route::post('referral/settings', [Admin\ReferralController::class, 'updateSettings'])->name('referral.settings');
        Route::post('referral/partner', [Admin\ReferralController::class, 'addPartner'])->name('referral.partner.add');
        Route::put('referral/partner/{partner}', [Admin\ReferralController::class, 'updatePartner'])->name('referral.partner.update');
        Route::delete('referral/partner/{partner}', [Admin\ReferralController::class, 'deletePartner'])->name('referral.partner.delete');
        Route::post('referral/application/{application}/approve', [Admin\ReferralController::class, 'approveApplication'])->name('referral.application.approve');
        Route::post('referral/application/{application}/reject', [Admin\ReferralController::class, 'rejectApplication'])->name('referral.application.reject');
        Route::get('referral/terms', [Admin\ReferralController::class, 'termsEdit'])->name('referral.terms');
        Route::post('referral/terms', [Admin\ReferralController::class, 'termsUpdate'])->name('referral.terms.update');

        // Bonus Claims (Pencairan Bonus Partner)
        Route::get('bonus-claims', [Admin\BonusClaimController::class, 'index'])->name('bonus-claims');
        Route::post('bonus-claims/{claim}/approve', [Admin\BonusClaimController::class, 'approve'])->name('bonus-claims.approve');
        Route::post('bonus-claims/{claim}/reject', [Admin\BonusClaimController::class, 'reject'])->name('bonus-claims.reject');

        // Cubi Shop Management
        Route::get('cubi-shop', [Admin\CubiShopController::class, 'index'])->name('cubi-shop');
        Route::post('cubi-shop/package', [Admin\CubiShopController::class, 'storePackage'])->name('cubi-shop.package.store');
        Route::put('cubi-shop/package/{package}', [Admin\CubiShopController::class, 'updatePackage'])->name('cubi-shop.package.update');
        Route::delete('cubi-shop/package/{package}', [Admin\CubiShopController::class, 'deletePackage'])->name('cubi-shop.package.delete');
        Route::post('cubi-shop/settings', [Admin\CubiShopController::class, 'updateSettings'])->name('cubi-shop.settings');

        // Event Management
        Route::resource('events', Admin\EventController::class);
        Route::post('events/{event}/toggle', [Admin\EventController::class, 'toggle'])->name('events.toggle');
        Route::post('events/{event}/distribute', [Admin\EventController::class, 'distribute'])->name('events.distribute');

    });

});

/*
|--------------------------------------------------------------------------
| GM (Game Master) Panel Routes
|--------------------------------------------------------------------------
*/
Route::prefix('gm')->name('gm.')->middleware(['auth', 'gm'])->group(function () {

    Route::get('/', [GM\DashboardController::class, 'index'])->name('dashboard');

    // GM can only manage news/articles
    Route::resource('articles', GM\ArticleController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

    // Member lookup (read-only for GM)
    Route::get('members', [GM\MemberController::class, 'index'])->name('members.index');
    Route::get('members/{user}', [GM\MemberController::class, 'show'])->name('members.show');

    // Cubi Monitor (read-only)
    Route::get('cubi-monitor', [GM\CubiMonitorController::class, 'index'])->name('cubi-monitor');

    // Server Control (read-only view + actions)
    Route::get('server-control', [Admin\ServerControlController::class, 'gmView'])->name('server-control');
    Route::post('server-control/action', [Admin\ServerControlController::class, 'action'])->name('server-control.action');
    Route::get('server-control/status', [Admin\ServerControlController::class, 'status'])->name('server-control.status');

    // DATAFILE Control (upload/replace + history)
    Route::get('datafile-control', [Admin\DatafileControlController::class, 'gmIndex'])->name('datafile-control');
    Route::post('datafile-control/upload', [Admin\DatafileControlController::class, 'upload'])->name('datafile-control.upload');

    // Ranking (read-only)
    Route::get('ranking', [Admin\RankingController::class, 'gmView'])->name('ranking');

});

/*
|--------------------------------------------------------------------------
| Partner Panel Routes
|--------------------------------------------------------------------------
*/
Route::prefix('partner')->name('partner.')->middleware(['auth', 'partner'])->group(function () {

    Route::get('/', [Partner\DashboardController::class, 'index'])->name('dashboard');
    Route::put('/discount-code', [Partner\DashboardController::class, 'updateDiscountCode'])->name('discount-code.update');
    Route::put('/social-media', [Partner\DashboardController::class, 'updateSocialMedia'])->name('social-media.update');
    Route::get('/bonus', [Partner\BonusController::class, 'index'])->name('bonus');
    Route::post('/bonus/payment-info', [Partner\BonusController::class, 'savePaymentInfo'])->name('bonus.payment-info');
    Route::post('/bonus/claim', [Partner\BonusController::class, 'requestClaim'])->name('bonus.claim');
    Route::get('/referral-history', [Partner\ReferralHistoryController::class, 'index'])->name('referral-history');
    Route::get('/terms', [Partner\TermsController::class, 'index'])->name('terms');

});

