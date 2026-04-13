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

class IsGamemaster
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || (! $user->isAdministrator() && ! $user->isGamemaster())) {
            abort(403, 'Akses ditolak. Hanya GM atau Administrator yang dapat masuk ke halaman ini.');
        }

        return $next($request);
    }
}
