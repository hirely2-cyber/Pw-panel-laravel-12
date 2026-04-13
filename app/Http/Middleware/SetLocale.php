<?php

/*
 * @author   Wahyu Suhandi <andietz.orion@gmail.com>
 * @link     https://wa.me/6208118719377
 * @project  Perfect World Panel
 * @version  2.0.0
 * @license  MIT
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Indonesian IP ranges (APNIC allocated to ID).
     * Using first-octet check for common Indonesian ISPs.
     */
    private const ID_IP_PREFIXES = [
        '36.', '39.', '43.', '45.', '47.', '49.',
        '61.', '101.', '103.', '110.', '112.', '114.',
        '116.', '117.', '118.', '119.', '120.', '121.',
        '122.', '123.', '124.', '125.', '139.', '140.',
        '146.', '147.', '149.', '152.', '155.', '156.',
        '157.', '158.', '160.', '163.', '167.', '169.',
        '175.', '180.', '182.', '183.', '192.168.', '202.',
        '203.', '210.', '211.', '218.', '219.', '223.',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        // If user has explicitly chosen a locale (via lang switcher), use it
        if (session()->has('locale')) {
            $locale = session('locale');
        } else {
            // Auto-detect: Indonesian IP → 'id', otherwise → 'en'
            $locale = $this->isIndonesianIp($request->ip()) ? 'id' : 'en';
        }

        if (! in_array($locale, ['id', 'en'])) {
            $locale = 'id';
        }

        app()->setLocale($locale);

        return $next($request);
    }

    private function isIndonesianIp(string $ip): bool
    {
        // Localhost / private IPs default to Indonesian
        if (in_array($ip, ['127.0.0.1', '::1']) || str_starts_with($ip, '10.') || str_starts_with($ip, '192.168.')) {
            return true;
        }

        foreach (self::ID_IP_PREFIXES as $prefix) {
            if (str_starts_with($ip, $prefix)) {
                return true;
            }
        }

        return false;
    }
}
