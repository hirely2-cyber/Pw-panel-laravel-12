<?php

/*
 * @author   Wahyu Suhandi <andietz.orion@gmail.com>
 * @link     https://wa.me/6208118719377
 * @project  Perfect World Panel
 * @version  2.0.0
 * @license  MIT
 */

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = auth()->user();

        // Always redirect to role-based URL.
        // Do NOT use redirect()->intended() — it picks up stale intended URLs
        // from session (e.g. /vote visited before login) and overrides the role redirect.
        if ($user->isAdministrator()) {
            return redirect(route('admin.dashboard'));
        }

        if ($user->isGamemaster()) {
            return redirect(route('gm.dashboard'));
        }

        return redirect(route('home'));
    }
}
