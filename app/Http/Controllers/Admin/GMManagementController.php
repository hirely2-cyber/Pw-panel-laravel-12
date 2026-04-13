<?php

/*
 * @author   Wahyu Suhandi <andietz.orion@gmail.com>
 * @link     https://wa.me/6208118719377
 * @project  Perfect World Panel
 * @version  2.0.0
 * @license  MIT
 *
 * Manages GM accounts:
 * - Panel side: user.role = 'gm' / 'admin'
 * - Game DB side: auth table (if game DB is connected)
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class GMManagementController extends Controller
{
    /** All permission RIDs available in PW */
    protected const GM_PERMS = [
        0   => 'Ganti Nama/ID Pemain',
        1   => 'Mode Tersembunyi / Invincible',
        2   => 'Ganti Status Online',
        4   => 'Teleport ke Pemain',
        5   => 'Teleport Pemain ke GM',
        6   => 'Teleport via Ctrl+Peta',
        11  => 'Lihat Jumlah Online',
        100 => 'Ban Akun/Karakter',
        101 => 'Mute Akun/Karakter',
        102 => 'Ban Trading',
        103 => 'Ban Selling',
        104 => 'GM Broadcast',
        105 => 'Restart Gameserver',
        200 => 'Buat Monster',
        206 => 'Aktifkan Monster Creator',
    ];

    public function index(): View
    {
        $gms     = User::whereIn('role', ['gm', 'webadmin', 'admin'])->get();
        $gameDbOk = $this->gameDbOk();
        $perms   = self::GM_PERMS;

        // Load game DB auth rows if available
        $authRows = [];
        if ($gameDbOk) {
            $authRows = DB::connection('mysql_game')
                ->table('auth')
                ->get()
                ->groupBy('userid')
                ->map(fn ($rows) => $rows->pluck('rid')->toArray())
                ->toArray();
        }

        return view('admin.gm.index', compact('gms', 'gameDbOk', 'perms', 'authRows'));
    }

    public function promote(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'string', 'max:100'],
            'role'     => ['required', 'in:gm,webadmin'],
        ]);

        $user = User::where('name', $request->username)->first();

        if (! $user) {
            return back()->with('error', "Akun '{$request->username}' tidak ditemukan.");
        }

        if ($user->ID === auth()->id()) {
            return back()->with('error', 'Tidak dapat mengubah role akun sendiri.');
        }

        if ($user->role === 'admin') {
            return back()->with('error', 'Tidak dapat mengubah role Superadmin.');
        }

        $targetRole = $request->role;
        $roleLabel  = $targetRole === 'webadmin' ? 'Web Admin' : 'GM';

        DB::table('users')->where('ID', $user->ID)->update(['role' => $targetRole]);

        // Add to game DB auth table for both GM and Web Admin
        if ($this->gameDbOk()) {
            try {
                DB::connection('mysql_game')->statement(
                    "CALL addGM(?, 1)", [$user->ID]
                );
            } catch (\Throwable $e) {
                Log::warning("addGM SP failed for user {$user->ID}: " . $e->getMessage());
            }
        }

        return back()->with('success', "'{$user->name}' berhasil dipromosikan menjadi {$roleLabel}.");
    }

    public function demote(User $user): RedirectResponse
    {
        if ($user->ID === auth()->id()) {
            return back()->with('error', 'Tidak dapat mengubah role akun sendiri.');
        }

        if ($user->role === 'admin') {
            return back()->with('error', 'Tidak dapat demote Superadmin.');
        }

        $wasGm = in_array($user->role, ['gm', 'webadmin']);

        DB::table('users')->where('ID', $user->ID)->update(['role' => 'player']);

        // Remove from game DB auth table if was GM or Web Admin
        if ($wasGm && $this->gameDbOk()) {
            try {
                DB::connection('mysql_game')
                    ->table('auth')
                    ->where('userid', $user->ID)
                    ->delete();
            } catch (\Throwable $e) {
                Log::warning("Remove auth failed for user {$user->ID}: " . $e->getMessage());
            }
        }

        return back()->with('success', "'{$user->name}' berhasil diturunkan ke Player.");
    }

    public function updatePerms(Request $request, User $user): RedirectResponse
    {
        if (! $this->gameDbOk()) {
            return back()->with('error', 'Game DB tidak terhubung. Tidak dapat mengubah permission.');
        }

        $rids = array_map('intval', array_keys(
            array_filter($request->only(array_map('strval', array_keys(self::GM_PERMS))))
        ));

        DB::connection('mysql_game')->table('auth')->where('userid', $user->ID)->delete();

        foreach ($rids as $rid) {
            if (array_key_exists($rid, self::GM_PERMS)) {
                DB::connection('mysql_game')->table('auth')->insert([
                    'userid' => $user->ID,
                    'zoneid' => 1,
                    'rid'    => $rid,
                ]);
            }
        }

        return back()->with('success', "Permission GM '{$user->name}' berhasil diperbarui.");
    }

    protected function gameDbOk(): bool
    {
        try {
            DB::connection('mysql_game')->getPdo();
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }
}
