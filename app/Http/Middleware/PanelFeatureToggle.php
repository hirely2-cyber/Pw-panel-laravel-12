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

/**
 * Blocks access to a panel feature if disabled in config/pw-config.php.
 *
 * Usage in routes:
 *   Route::middleware(['feature:shop'])->group(...)
 *   Route::middleware(['feature:donate'])->group(...)
 */
class PanelFeatureToggle
{
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        if (! config("pw-config.features.{$feature}", false)) {
            abort(404);
        }

        return $next($request);
    }
}
