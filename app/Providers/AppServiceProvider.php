<?php

namespace App\Providers;

use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS for all generated URLs (behind Cloudflare proxy)
        if (config('app.url') && str_starts_with(config('app.url'), 'https')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        $locale = $this->resolveLocale();
        app()->setLocale($locale);
        Carbon::setLocale($locale);
        setlocale(
            LC_TIME,
            $locale === 'id' ? 'id_ID.UTF-8' : 'en_US.UTF-8',
            $locale === 'id' ? 'id_ID' : 'en_US',
            $locale
        );

        // Override feature toggles from DB (so admin can toggle without editing .env)
        try {
            $features = ['shop', 'donate', 'voucher', 'ranking', 'vote', 'service', 'news', 'register'];
            foreach ($features as $key) {
                $val = Setting::get('feature_' . $key);
                if ($val !== null) {
                    config(['pw-config.features.' . $key => $val === '1']);
                }
            }
            $cubiVal = Setting::get('feature_cubi_shop');
            if ($cubiVal !== null) {
                config(['pw-config.cubi_shop.enabled' => $cubiVal === '1']);
            }
        } catch (\Throwable $e) {
            // DB not ready (first install, migration, etc.) — silently skip
        }
    }

    private function resolveLocale(): string
    {
        if (app()->runningInConsole()) {
            return config('app.locale', 'en');
        }

        /** @var Request $request */
        $request = request();

        if ($request->hasSession() && $request->session()->has('locale')) {
            $locale = $request->session()->get('locale');

            if (in_array($locale, ['id', 'en'], true)) {
                return $locale;
            }
        }

        $cookieLocale = $request->cookie('locale');
        if (in_array($cookieLocale, ['id', 'en'], true)) {
            return $cookieLocale;
        }

        return $this->isIndonesianIp($request->ip()) ? 'id' : 'en';
    }

    private function isIndonesianIp(?string $ip): bool
    {
        if (! $ip) {
            return false;
        }

        $prefixes = [
            '36.', '39.', '43.', '45.', '47.', '49.',
            '61.', '101.', '103.', '110.', '112.', '114.',
            '116.', '117.', '118.', '119.', '120.', '121.',
            '122.', '123.', '124.', '125.', '139.', '140.',
            '146.', '147.', '149.', '152.', '155.', '156.',
            '157.', '158.', '160.', '163.', '167.', '169.',
            '175.', '180.', '182.', '183.', '192.168.', '202.',
            '203.', '210.', '211.', '218.', '219.', '223.',
        ];

        if (in_array($ip, ['127.0.0.1', '::1'], true) || str_starts_with($ip, '10.') || str_starts_with($ip, '192.168.')) {
            return true;
        }

        foreach ($prefixes as $prefix) {
            if (str_starts_with($ip, $prefix)) {
                return true;
            }
        }

        return false;
    }
}
