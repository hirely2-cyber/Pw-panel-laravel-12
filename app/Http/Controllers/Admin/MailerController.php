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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class MailerController extends Controller
{
    public function index(): View
    {
        $totalCharacters = DB::connection('mysql_game')->table('roles')->count();
        $apiAvailable    = GameApiService::isAvailable();

        return view('admin.mailer.index', compact('totalCharacters', 'apiAvailable'));
    }

    public function send(Request $request): RedirectResponse
    {
        $request->validate([
            'target'     => ['required', 'in:all,single'],
            'role_id'    => ['required_if:target,single', 'nullable', 'integer', 'min:1'],
            'title'      => ['required', 'string', 'max:64'],
            'message'    => ['required', 'string', 'max:512'],
            'item_id'    => ['nullable', 'integer', 'min:0'],
            'item_count' => ['nullable', 'integer', 'min:1', 'max:9999'],
            'gold'       => ['nullable', 'integer', 'min:0'],
        ]);

        if (! GameApiService::isAvailable()) {
            return back()->with('error', 'Tidak dapat terhubung ke gdeliveryd. Pastikan game server berjalan dan port 29100 dapat diakses.')->withInput();
        }

        $title   = $request->input('title');
        $message = $request->input('message');
        $gold    = $request->integer('gold', 0);
        $itemId  = $request->integer('item_id', 0);
        $itemCnt = $request->integer('item_count', 1);

        $items = [];
        if ($itemId > 0) {
            $items[] = ['id' => $itemId, 'pos' => 0, 'count' => $itemCnt];
        }

        // Single character
        if ($request->target === 'single') {
            $roleId = $request->integer('role_id');

            // Verify character exists
            $exists = DB::connection('mysql_game')->table('roles')->where('role_id', $roleId)->exists();
            if (! $exists) {
                return back()->with('error', "Character dengan Role ID {$roleId} tidak ditemukan.")->withInput();
            }

            $ok = GameApiService::sendMail($roleId, $title, $message, $gold, $items);

            return back()->with(
                $ok ? 'success' : 'error',
                $ok ? "Mail berhasil dikirim ke Role ID {$roleId}." : "Gagal mengirim mail ke Role ID {$roleId}. Cek log server."
            );
        }

        // All characters
        $roleIds = DB::connection('mysql_game')->table('roles')->pluck('role_id');
        $success = 0;
        $failed  = 0;

        foreach ($roleIds as $roleId) {
            $ok = GameApiService::sendMail((int) $roleId, $title, $message, $gold, $items);
            $ok ? $success++ : $failed++;
        }

        $msg = "Mail dikirim ke {$success} karakter.";
        if ($failed > 0) {
            $msg .= " ({$failed} gagal)";
        }

        return back()->with($failed === $roleIds->count() ? 'error' : 'success', $msg);
    }
}
