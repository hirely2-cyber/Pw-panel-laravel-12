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
use App\Services\GameApiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BroadcastController extends Controller
{
    public function index(): View
    {
        $apiAvailable = GameApiService::isAvailable();
        return view('admin.broadcast.index', compact('apiAvailable'));
    }

    public function send(Request $request): RedirectResponse
    {
        $request->validate([
            'message' => ['required', 'string', 'max:500'],
            'channel' => ['required', 'integer', 'in:0,1,2,3,4,7,9,12'],
        ]);

        $sent = GameApiService::worldChat(
            message: $request->message,
            channel: (int) $request->channel,
            roleId:  0
        );

        if ($sent) {
            return back()->with('success', 'Broadcast berhasil dikirim ke server.');
        }

        // If API not available, show warning but record the attempt in session
        return back()->with('error',
            'Broadcast gagal. Pastikan game server aktif dan port gacd (29300) dapat diakses.'
        )->withInput();
    }
}
