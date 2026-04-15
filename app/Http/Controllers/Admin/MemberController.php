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
use App\Models\User;
use App\Services\GameDbService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($role = $request->get('role')) {
            if ($role === 'game_gm') {
                // Filter by game GM: users whose ID exists in auth table
                $gmUserIds = DB::connection('mysql_game')->table('auth')->pluck('userid')->toArray();
                $query->whereIn('ID', $gmUserIds);
            } else {
                $query->where('role', $role);
            }
        }

        $members = $query->with('referrer:ID,name')->oldest('ID')->paginate(30);

        // Batch-load game GM status from auth table
        $memberIds = $members->pluck('ID')->toArray();
        $gameGmIds = [];
        try {
            $gameGmIds = DB::connection('mysql_game')
                ->table('auth')
                ->whereIn('userid', $memberIds)
                ->pluck('userid')
                ->toArray();
        } catch (\Throwable $e) {
            // mysql_game might be offline
        }

        return view('admin.members.index', compact('members', 'gameGmIds'));
    }

    public function show(User $user): View
    {
        $user->load([
            'invoices'    => fn($q) => $q->latest()->limit(10),
            'shopLogs'    => fn($q) => $q->latest()->limit(10),
            'voteLogs'    => fn($q) => $q->latest()->limit(10),
            'serviceLogs' => fn($q) => $q->latest()->limit(10),
            'referrer:ID,name',
        ]);

        // Game characters
        $characters = $user->gameCharacters();

        // Fetch lastlogin per character from gamedbd
        $rolesData = [];
        try {
            $gameDb = new GameDbService();
            $roleIds = $characters->pluck('role_id')->toArray();
            $rolesData = $gameDb->getRolesData($roleIds);
        } catch (\Throwable $e) {
            // gamedbd might be offline
        }

        // Real-time Cubi balance from gamedbd
        $cubiData = null;
        try {
            $cubiData = (new GameDbService())->getUserCash($user->ID);
        } catch (\Throwable $e) {
            // gamedbd might be offline
        }

        return view('admin.members.show', compact('user', 'characters', 'rolesData', 'cubiData'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'role'  => ['required', 'in:admin,gm,player'],
            'email' => ['required', 'email', 'max:255'],
        ]);

        DB::table('users')->where('ID', $user->ID)->update([
            'role'  => $request->role,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Member berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        // Prevent self-deletion
        if ($user->ID === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        DB::table('users')->where('ID', $user->ID)->delete();

        return redirect()->route('admin.members.index')->with('success', 'Member dihapus.');
    }

    public function topup(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'amount' => ['required', 'integer', 'min:1', 'max:100000'],
            'reason' => ['required', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($user, $request) {
            $user->increment('money', $request->amount);

            // Schema: userid, zoneid, sn, aid, point, cash, status, creatime, fintime
            $nextSn = (DB::table('usecashlog')->where('userid', $user->ID)->max('sn') ?? 0) + 1;
            DB::table('usecashlog')->insert([
                'userid'   => $user->ID,
                'zoneid'   => 1,
                'sn'       => $nextSn,
                'aid'      => 1,
                'point'    => 0,
                'cash'     => $request->amount,
                'status'   => 4, // 4 = completed/finalized
                'creatime' => now(),
                'fintime'  => now(),
            ]);
        });

        return back()->with('success', 'Berhasil menambahkan ' . $request->amount . ' Gold ke ' . $user->name . '.');
    }

    public function ban(User $user): RedirectResponse
    {
        if ($user->ID === auth()->id()) {
            return back()->with('error', 'Tidak bisa ban akun sendiri.');
        }

        // Schema: userid, type, ctime, forbid_time, reason, gmroleid
        // type=0: account ban | forbid_time=0: permanent
        DB::table('forbid')->updateOrInsert(
            ['userid' => $user->ID, 'type' => 0],
            ['ctime' => now(), 'forbid_time' => 0, 'reason' => 'Banned by admin', 'gmroleid' => 0]
        );

        return back()->with('success', "{$user->name} telah di-ban.");
    }

    public function unban(User $user): RedirectResponse
    {
        DB::table('forbid')->where('userid', $user->ID)->delete();
        return back()->with('success', "{$user->name} telah di-unban.");
    }

    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'new_password' => ['required', 'string', 'min:6', 'regex:/^[a-z0-9]+$/'],
        ]);

        $concat = strtolower($user->name) . $request->new_password;
        $newHash = base64_encode(md5($concat, true));

        DB::table('users')->where('ID', $user->ID)->update([
            'passwd'  => $newHash,
            'passwd2' => $newHash,
        ]);

        return back()->with('success', 'Password ' . $user->name . ' berhasil direset.');
    }

    public function cubiTopup(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'amount' => ['required', 'integer', 'min:1', 'max:999999'],
            'reason' => ['required', 'string', 'max:255'],
        ]);

        $cashValue = $request->amount * 100; // Cubi stored as cents in DB

        DB::connection('mysql_game')->transaction(function () use ($user, $cashValue) {
            // Insert ke usecashlog langsung dengan status=4 (paid/confirmed) agar muncul di Cubi Monitor
            $nextSn = (DB::connection('mysql_game')->table('usecashlog')->where('userid', $user->ID)->max('sn') ?? 0) + 1;

            DB::connection('mysql_game')->table('usecashlog')->insert([
                'userid'   => $user->ID,
                'zoneid'   => 1,
                'sn'       => $nextSn,
                'aid'      => auth()->id(), // admin ID dari panel
                'point'    => 5, // point=5 = admin topup (untuk tracking di monitor)
                'cash'     => $cashValue,
                'status'   => 4, // 4 = paid/confirmed
                'creatime' => now(),
                'fintime'  => now(),
            ]);
        });

        // Track for Cubi Monitor source detection
        DB::table('pw_admin_cubi_topups')->insert([
            'user_id'    => $user->ID,
            'admin_id'   => auth()->id(),
            'amount'     => $request->amount,
            'reason'     => $request->reason,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Berhasil mengirim ' . number_format($request->amount) . ' Cubi Gold ke ' . $user->name . '. Langsung masuk ke akun game.');
    }

    public function characterDetail(User $user, int $roleId): View
    {
        // Verify this role belongs to the user
        $characters = $user->gameCharacters();
        $character = $characters->firstWhere('role_id', $roleId);

        if (!$character) {
            abort(404, 'Character not found for this user.');
        }

        // Fetch full role data with items (bypass cache for fresh data)
        $roleData = null;
        try {
            $gameDb = new GameDbService();
            $roleData = $gameDb->getRoleData($roleId);
        } catch (\Throwable $e) {
            // gamedbd offline
        }

        // Real-time Cubi balance
        $cubiData = null;
        try {
            $cubiData = (new GameDbService())->getUserCash($user->ID);
        } catch (\Throwable $e) {
            // gamedbd offline
        }

        // Build item name lookup for this character items
        $itemNames = [];
        if ($roleData) {
            try {
                $itemDb = new ItemDatabase();
                $itemNames = $itemDb->forItems([
                    $roleData['equipment']['items'] ?? [],
                    $roleData['pocket']['items'] ?? [],
                    $roleData['storehouse']['items'] ?? [],
                ]);
            } catch (\Throwable $e) {
                // item DB unavailable
            }
        }

        return view('admin.members.character', compact('user', 'character', 'roleData', 'cubiData', 'itemNames'));
    }

    public function saveCharacter(Request $request, User $user, int $roleId): RedirectResponse
    {
        // Verify character belongs to user
        $characters = $user->gameCharacters();
        $character = $characters->firstWhere('role_id', $roleId);
        if (!$character) {
            abort(404, 'Character not found.');
        }

        // Block saving if character is online
        if ($user->isOnline()) {
            return back()->with('error', 'Character harus offline untuk dimodifikasi. Kick dulu dari server.');
        }

        $gameDb = new GameDbService();
        $errors = [];

        // 1. Update status fields (position, rep, exp, sp, cultivation) via PutRoleStatus
        $statusChanges = [];
        $statusFields = ['world', 'pos_x', 'pos_z', 'pos_y', 'reputation', 'exp', 'sp', 'cultivation'];
        foreach ($statusFields as $field) {
            if ($request->filled($field)) {
                $statusChanges[$field] = $request->input($field);
            }
        }

        if (!empty($statusChanges)) {
            try {
                // Get fresh raw status bytes
                Cache::forget("pw.role.{$roleId}");
                $roleData = $gameDb->getRoleData($roleId);
                $rawStatus = $roleData['_raw_status'] ?? null;

                if (!$rawStatus) {
                    $errors[] = 'Gagal membaca raw status data.';
                } else {
                    $ok = $gameDb->updateRoleStatus($roleId, $rawStatus, $statusChanges);
                    if (!$ok) $errors[] = 'PutRoleStatus gagal.';
                }
            } catch (\Throwable $e) {
                $errors[] = 'Status error: ' . $e->getMessage();
            }
        }

        // 2. Update money via DBModifyRoleData
        $pocketMoney = $request->filled('pocket_money') ? (int) $request->input('pocket_money') : null;
        $storeMoney = $request->filled('store_money') ? (int) $request->input('store_money') : null;

        if ($pocketMoney !== null || $storeMoney !== null) {
            try {
                $ok = $gameDb->modifyRoleMoney($roleId, $pocketMoney, $storeMoney);
                if (!$ok) $errors[] = 'ModifyRoleMoney gagal.';
            } catch (\Throwable $e) {
                $errors[] = 'Money error: ' . $e->getMessage();
            }
        }

        if (empty($errors)) {
            return back()->with('success', 'Character data berhasil disimpan.');
        } else {
            return back()->with('error', implode(' | ', $errors));
        }
    }
}
