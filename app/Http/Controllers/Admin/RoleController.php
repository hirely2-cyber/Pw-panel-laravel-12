<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use App\Services\GameDbService;
use App\Services\PwAdminRoleXmlService;
use App\Services\RolesAccountIdReconciler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class RoleController extends Controller
{
    private const CLASS_MAP = [
        0 => 'Blademaster', 1 => 'Wizard', 2 => 'Psychic', 3 => 'Venomancer',
        4 => 'Barbarian', 5 => 'Assassin', 6 => 'Archer', 7 => 'Cleric',
        8 => 'Seeker', 9 => 'Mystic', 10 => 'Duskblade', 11 => 'Stormbringer',
    ];

    private const ICON_MAP = [
        0 => 'blademaster', 1 => 'wizzard', 2 => 'psychic', 3 => 'venomancer',
        4 => 'barbarian', 5 => 'assasin', 6 => 'archer', 7 => 'cleric',
        8 => 'seeker', 9 => 'mystic', 10 => 'duskblade', 11 => 'stormbringer',
    ];

    private const RACE_MAP = [
        0 => 'Human', 1 => 'Winged Elf', 2 => 'Untamed', 3 => 'Tideborn',
        4 => 'Earthguard', 5 => 'Nightshade',
    ];

    private const FACTION_RANK_MAP = [
        0 => 'Member', 1 => 'Executor', 2 => 'Commissar', 3 => 'Marshal',
        4 => 'Director', 5 => 'Faction Master', 6 => 'Vice Master',
    ];

    /**
     * Display all characters from the MySQL roles table.
     */
    public function index(Request $request): Response
    {
        // Hindari cache browser untuk daftar (setelah Tomcat import data harus terlihat segar)
        $query = DB::connection('mysql_game')->table('roles');

        if ($search = $request->get('search')) {
            $query->where('role_name', 'like', "%{$search}%");
        }

        if ($classFilter = $request->get('class')) {
            $query->where('role_occupation', $classFilter);
        }

        $sortField = $request->get('sort', 'role_level');
        $sortDir = $request->get('dir', 'desc');
        $allowed = ['role_id', 'account_id', 'role_name', 'role_level', 'role_occupation', 'faction_name', 'pvp_kills'];
        if (!in_array($sortField, $allowed)) $sortField = 'role_level';
        if (!in_array($sortDir, ['asc', 'desc'])) $sortDir = 'desc';

        $roles = $query->orderBy($sortField, $sortDir)->paginate(30)->withQueryString();

        $totalRoles = DB::connection('mysql_game')->table('roles')->count();

        return response()
            ->view('admin.roles.index', [
                'roles'      => $roles,
                'totalRoles' => $totalRoles,
                'classMap'   => self::CLASS_MAP,
                'iconMap'    => self::ICON_MAP,
                'raceMap'    => self::RACE_MAP,
                'rankMap'    => self::FACTION_RANK_MAP,
                'search'     => $search,
                'classFilter'=> $classFilter,
                'sort'       => $sortField,
                'dir'        => $sortDir,
            ])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache');
    }

    /**
     * Show single character detail (read data from roles table + live gamedbd if available).
     */
    public function show(Request $request, int $roleId): View
    {
        $role = DB::connection('mysql_game')->table('roles')->where('role_id', $roleId)->first();

        // Try to get live data from gamedbd
        $liveData = null;
        try {
            $gameDb = new \App\Services\GameDbService();
            $liveData = $gameDb->getRoleData($roleId);
        } catch (\Throwable $e) {
            Log::debug("RoleController::show - live data unavailable for role {$roleId}: " . $e->getMessage());
        }

        // If not in MySQL and no live data either, then truly not found
        if (!$role && !$liveData) {
            abort(404, 'Character not found.');
        }

        return view('admin.roles.show', [
            'role'      => $role,
            'roleId'    => $roleId,
            'liveData'  => $liveData,
            'classMap'  => self::CLASS_MAP,
            'iconMap'   => self::ICON_MAP,
            'raceMap'   => self::RACE_MAP,
            'rankMap'   => self::FACTION_RANK_MAP,
        ]);
    }

    /**
     * Show edit form for a character.
     */
    public function edit(Request $request, int $roleId): View
    {
        $gameDb = new \App\Services\GameDbService();
        \Illuminate\Support\Facades\Cache::forget("pw.role.{$roleId}");
        $liveData = $gameDb->getRoleData($roleId);

        if (!$liveData) {
            abort(503, 'Game server tidak tersedia. Edit hanya bisa dilakukan saat server running.');
        }

        return view('admin.roles.edit', [
            'roleId'    => $roleId,
            'liveData'  => $liveData,
            'classMap'  => self::CLASS_MAP,
            'iconMap'   => self::ICON_MAP,
            'raceMap'   => self::RACE_MAP,
        ]);
    }

    /**
     * Send edit data to Tomcat pwAdmin save endpoint.
     */
    public function update(Request $request, int $roleId)
    {
        $validated = $request->validate([
            'world' => 'required|integer|min:0',
            'coordinateX' => 'required|numeric',
            'coordinateZ' => 'required|numeric',
            'coordinateY' => 'required|numeric',
            'reputation' => 'required|integer|min:0|max:2147483647',
            'exp' => 'required|integer|min:0|max:2147483647',
            'sp' => 'required|integer|min:0|max:2147483647',
            'cultivation' => 'required|integer|in:0,1,2,3,4,5,6,7,8,20,21,22,30,31,32',
            'vigor' => 'required|integer|in:0,99,199,299,399',
            'pocketcoins' => 'required|integer|min:0|max:200000000',
            'storehousecoins' => 'required|integer|min:0|max:200000000',
        ]);

        // Fetch current data to determine what actually changed
        $gameDb = new GameDbService();
        Cache::forget("pw.role.{$roleId}");
        $before = $gameDb->getRoleData($roleId);
        if (! $before) {
            return back()->withErrors(['error' => 'Gagal mengambil data karakter dari game server.'])->withInput();
        }

        $beforeStatus = $before['status'] ?? [];
        $beforeProp = $beforeStatus['property'] ?? [];
        $beforePocket = (int) ($before['pocket']['money'] ?? 0);
        $beforeStore = (int) ($before['storehouse']['money'] ?? 0);

        // Build payload with only changed fields
        $isFloatChanged = static fn (float $a, float $b): bool => abs($a - $b) > 0.0001;
        $payload = [];

        if ((int) $validated['world'] !== (int) ($beforeStatus['world_tag'] ?? 0)) {
            $payload['world'] = (int) $validated['world'];
        }
        if ($isFloatChanged((float) $validated['coordinateX'], (float) ($beforeStatus['pos_x'] ?? 0))) {
            $payload['coordinateX'] = (float) $validated['coordinateX'];
        }
        if ($isFloatChanged((float) $validated['coordinateZ'], (float) ($beforeStatus['pos_z'] ?? 0))) {
            $payload['coordinateZ'] = (float) $validated['coordinateZ'];
        }
        if ($isFloatChanged((float) $validated['coordinateY'], (float) ($beforeStatus['pos_y'] ?? 0))) {
            $payload['coordinateY'] = (float) $validated['coordinateY'];
        }
        if ((int) $validated['reputation'] !== (int) ($beforeStatus['reputation'] ?? 0)) {
            $payload['reputation'] = (int) $validated['reputation'];
        }
        if ((int) $validated['exp'] !== (int) ($beforeStatus['exp'] ?? 0)) {
            $payload['exp'] = (int) $validated['exp'];
        }
        if ((int) $validated['sp'] !== (int) ($beforeStatus['sp'] ?? 0)) {
            $payload['sp'] = (int) $validated['sp'];
        }
        if ((int) $validated['cultivation'] !== (int) ($beforeStatus['cultivation'] ?? 0)) {
            $payload['cultivation'] = (int) $validated['cultivation'];
        }
        if ((int) $validated['vigor'] !== (int) ($beforeProp['max_ap'] ?? 0)) {
            $payload['vigor'] = (int) $validated['vigor'];
        }
        if ((int) $validated['pocketcoins'] !== $beforePocket) {
            $payload['pocketcoins'] = (int) $validated['pocketcoins'];
        }
        if ((int) $validated['storehousecoins'] !== $beforeStore) {
            $payload['storehousecoins'] = (int) $validated['storehousecoins'];
        }

        if (empty($payload)) {
            return back()->with('success', 'Tidak ada perubahan data untuk disimpan.');
        }

        $session = $this->getTomcatSession();
        if (! $session) {
            return back()->withErrors(['error' => 'Gagal mengambil sesi Tomcat (login).'])->withInput();
        }

        // Try to save via Tomcat - may not support coordinates/exp, we'll handle separately
        [$ok, $saveError] = $this->saveRoleViaTomcat($roleId, $payload);

        if (! $ok) {
            return back()->with('error', $saveError ?? 'Tomcat menolak save character.');
        }

        $gameDb = new GameDbService();
        Cache::forget("pw.role.{$roleId}");
        $after = $gameDb->getRoleData($roleId);
        if (! $after) {
            return back()->withErrors(['error' => 'Save terkirim, tapi verifikasi gagal membaca data terbaru.'])->withInput();
        }

        $status = $after['status'] ?? [];
        $prop = $status['property'] ?? [];
        $pocket = (int) ($after['pocket']['money'] ?? 0);
        $store = (int) ($after['storehouse']['money'] ?? 0);

        // Verify that changes were applied (only check fields that were sent)
        $isFloatChanged = static fn (float $a, float $b): bool => abs($a - $b) > 0.0001;
        $notApplied = [];

        if (isset($payload['world']) && (int) $payload['world'] !== (int) ($status['world_tag'] ?? 0)) $notApplied[] = 'world';
        if (isset($payload['coordinateX']) && $isFloatChanged((float) $payload['coordinateX'], (float) ($status['pos_x'] ?? 0))) $notApplied[] = 'coordinateX';
        if (isset($payload['coordinateZ']) && $isFloatChanged((float) $payload['coordinateZ'], (float) ($status['pos_z'] ?? 0))) $notApplied[] = 'coordinateZ';
        if (isset($payload['coordinateY']) && $isFloatChanged((float) $payload['coordinateY'], (float) ($status['pos_y'] ?? 0))) $notApplied[] = 'coordinateY';
        if (isset($payload['reputation']) && (int) $payload['reputation'] !== (int) ($status['reputation'] ?? 0)) $notApplied[] = 'reputation';
        if (isset($payload['exp']) && (int) $payload['exp'] !== (int) ($status['exp'] ?? 0)) $notApplied[] = 'exp';
        if (isset($payload['sp']) && (int) $payload['sp'] !== (int) ($status['sp'] ?? 0)) $notApplied[] = 'sp';
        if (isset($payload['cultivation']) && (int) $payload['cultivation'] !== (int) ($status['cultivation'] ?? 0)) $notApplied[] = 'cultivation';
        if (isset($payload['vigor']) && (int) $payload['vigor'] !== (int) ($prop['max_ap'] ?? 0)) $notApplied[] = 'vigor';
        if (isset($payload['pocketcoins']) && (int) $payload['pocketcoins'] !== $pocket) $notApplied[] = 'pocketcoins';
        if (isset($payload['storehousecoins']) && (int) $payload['storehousecoins'] !== $store) $notApplied[] = 'storehousecoins';

        if (! empty($notApplied)) {
            return back()->withErrors(['error' => 'Save dikirim, tapi data belum berubah pada: ' . implode(', ', $notApplied) . '.'])->withInput();
        }

        return redirect()->route('admin.roles.show', $roleId)
            ->with('success', 'Character berhasil diupdate.');
    }

    /**
     * Sync roles from gamedbd to MySQL roles table.
     * Calls the same Java-based sync that role.jsp uses (via Tomcat HTTP).
     */
    public function sync(Request $request): JsonResponse
    {
        // Prevent double sync with cache lock (60 seconds)
        $lockKey = 'pw_role_sync_lock';
        if (\Illuminate\Support\Facades\Cache::has($lockKey)) {
            return response()->json([
                'ok' => false,
                'message' => 'Sync sedang berjalan, tunggu hingga selesai.',
            ], 429);
        }
        \Illuminate\Support\Facades\Cache::put($lockKey, true, 60);

        try {
            $sqlSyncNote = '';
            $tomcatBase = rtrim((string) config('pw-api.pwadmin_url', 'http://127.0.0.1:8080/pwAdmin/'), '/');
            $token = (string) config('pw-api.pwadmin_api_token', 'pw_panel_sync_2026');
            $syncUrl = $tomcatBase . '/api_sync_roles.jsp?token=' . rawurlencode($token);

            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $syncUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 120,
                CURLOPT_CONNECTTIMEOUT => 10,
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($curlError) {
                return response()->json([
                    'ok' => false,
                    'message' => "Koneksi ke Tomcat gagal: {$curlError}",
                ], 422);
            }

            $data = json_decode($response, true);

            if ($httpCode !== 200 || !($data['ok'] ?? false)) {
                return response()->json([
                    'ok' => false,
                    'message' => $data['message'] ?? "api_sync_roles gagal (HTTP {$httpCode})",
                ], 422);
            }

            if (config('pw-api.roles_sync_also_sqlsync', true)) {
                $sqlUrl = $tomcatBase . '/role.jsp?action=sqlsync';
                $ch2 = curl_init();
                curl_setopt_array($ch2, [
                    CURLOPT_URL => $sqlUrl,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 180,
                    CURLOPT_CONNECTTIMEOUT => 10,
                ]);
                $sqlBody = curl_exec($ch2);
                $sqlHttp = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
                $sqlErr = curl_error($ch2);
                curl_close($ch2);
                if ($sqlErr) {
                    Log::warning('role.jsp sqlsync: curl ' . $sqlErr);
                    $sqlSyncNote = ' Perhatian: role.jsp?action=sqlsync gagal (curl). Cek Tomcat / jalankan: php artisan pw:sync-roles';
                } elseif ($sqlHttp < 200 || $sqlHttp >= 400) {
                    Log::warning("role.jsp sqlsync: HTTP {$sqlHttp}", [
                        'body' => is_string($sqlBody) ? mb_substr($sqlBody, 0, 500) : '',
                    ]);
                    $sqlSyncNote = " Perhatian: sqlsync HTTP {$sqlHttp} — cek konfigurasi pwAdmin/role.jsp.";
                } else {
                    $sqlSyncNote = ' sqlsync (role.jsp) selesai.';
                }
            }

            DB::connection('mysql_game')->reconnect();
            $totalAfter = (int) DB::connection('mysql_game')->table('roles')->count();

            $reconcileLimit = (int) config('pw-config.game_account.reconcile_from_gamedb_limit', 20000);
            app()->terminating(function () use ($reconcileLimit): void {
                try {
                    $stats = app(RolesAccountIdReconciler::class)->reconcile(
                        max(1, $reconcileLimit),
                        false
                    );
                    Log::info('After Tomcat role sync: account_id diselaraskan dengan gamedbd', [
                        'fixed' => $stats['fixed'],
                        'errors' => $stats['errors'],
                        'affected' => $stats['affected_user_ids'],
                    ]);
                } catch (\Throwable $e) {
                    Log::error('Reconcile account_id setelah sync roles gagal: ' . $e->getMessage(), [
                        'exception' => $e,
                    ]);
                }
            });

            return response()->json([
                'ok' => true,
                'message' => "Sync gamedb→MySQL selesai. Total {$totalAfter} baris di tabel roles.{$sqlSyncNote} account_id diselaraskan sebentar lagi (reconcile).",
                'total' => $totalAfter,
            ]);
        } finally {
            \Illuminate\Support\Facades\Cache::forget($lockKey);
        }
    }

    /**
     * Role XML (XmlRole) — same source as rolexml.jsp / member character ?view=xml.
     */
    public function showRoleXml(int $roleId): View
    {
        $role = DB::connection('mysql_game')->table('roles')->where('role_id', $roleId)->first();

        $liveData = null;
        try {
            $gameDb = new GameDbService();
            $liveData = $gameDb->getRoleData($roleId);
        } catch (\Throwable $e) {
            Log::debug("RoleController::showRoleXml - live data unavailable for role {$roleId}: " . $e->getMessage());
        }

        if (! $role && ! $liveData) {
            abort(404, 'Character not found.');
        }

        [$roleXml, $roleXmlError] = (new PwAdminRoleXmlService())->fetchRoleXmlWithError($roleId);
        $pwadminRolexmlUrl = rtrim(config('pw-api.pwadmin_url', 'http://127.0.0.1:8080/pwAdmin/'), '/') . '/rolexml.jsp?ident=' . $roleId;
        $charName = $role->role_name ?? ($liveData['base']['name'] ?? '—');

        $memberCharacterUrl = null;
        $accountId = (int) ($role->account_id ?? ($liveData['base']['userid'] ?? 0));
        if ($accountId > 0) {
            $u = User::query()->where('ID', $accountId)->first();
            if ($u && $u->gameCharacters()->firstWhere('role_id', $roleId)) {
                $memberCharacterUrl = route('admin.members.character', ['user' => $u, 'roleId' => $roleId]);
            }
        }

        return view('admin.roles.role-xml', [
            'role' => $role,
            'roleId' => $roleId,
            'liveData' => $liveData,
            'roleXml' => $roleXml,
            'roleXmlError' => $roleXmlError,
            'pwadminRolexmlUrl' => $pwadminRolexmlUrl,
            'charName' => $charName,
            'memberCharacterUrl' => $memberCharacterUrl,
        ]);
    }

    public function saveRoleXml(Request $request, int $roleId): RedirectResponse
    {
        $request->validate([
            'xml' => ['required', 'string', 'max:15000000'],
        ], [
            'xml.required' => 'Kolom XML wajib diisi.',
        ]);

        $role = DB::connection('mysql_game')->table('roles')->where('role_id', $roleId)->first();
        $liveData = null;
        try {
            $liveData = (new GameDbService())->getRoleData($roleId);
        } catch (\Throwable $e) {
        }
        if (! $role && ! $liveData) {
            abort(404, 'Character not found.');
        }

        $userId = (int) ($liveData['base']['userid'] ?? 0);
        if ($userId > 0 && $this->isAccountGameOnline($userId)) {
            return redirect()
                ->route('admin.roles.role-xml', $roleId)
                ->with('error', 'Character harus offline untuk mengubah XML. Kick dulu dari server.');
        }

        $service = new PwAdminRoleXmlService();
        [$ok, $err] = $service->saveRoleXmlWithError($roleId, (string) $request->input('xml', ''));
        if (! $ok) {
            return redirect()
                ->route('admin.roles.role-xml', $roleId)
                ->with('error', 'Simpan XML gagal: ' . $err);
        }

        Cache::forget("pw.role.{$roleId}");

        return redirect()
            ->route('admin.roles.role-xml', $roleId)
            ->with('success', 'Role XML tersimpan (Tomcat: XmlRole.putRoleToDB).');
    }

    private function saveRoleViaTomcat(int $roleId, array $payload): array
    {
        // Use custom JSP API endpoint with token (no session login required)
        $tomcatUrl = rtrim((string) config('pw-api.pwadmin_url', 'http://127.0.0.1:8080/pwAdmin/'), '/');
        $token = (string) config('pw-api.pwadmin_api_token', 'pw_panel_sync_2026');
        $saveUrl = $tomcatUrl . '/api_role_save.jsp';

        $postData = array_merge(['token' => $token, 'ident' => $roleId], $payload);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $saveUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($postData),
        ]);

        $body = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError !== '') {
            return [false, 'Koneksi ke pwAdmin gagal: ' . $curlError];
        }

        $body = is_string($body) ? trim($body) : '';
        $data = json_decode($body, true);

        if (! is_array($data)) {
            return [false, "Response pwAdmin tidak valid (HTTP {$httpCode})."];
        }

        if (empty($data['ok'])) {
            $msg = $data['message'] ?? 'pwAdmin menolak save.';
            return [false, 'pwAdmin error: ' . $msg];
        }

        return [true, null];
    }

    private function getTomcatSession(): ?string
    {
        $tomcatBase = rtrim((string) config('pw-api.pwadmin_url', 'http://127.0.0.1:8080/pwAdmin/'), '/');
        $username = (string) config('pw-api.pwadmin_user', 'admin');
        $password = (string) config('pw-api.pwadmin_pass', '');

        if ($username === '') {
            return null;
        }

        // PWAdmin login endpoint: POST to index.jsp?page=login with user, key, captcha (6+7=13)
        $loginUrl = $tomcatBase . '/index.jsp?page=login';

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $loginUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_CONNECTTIMEOUT => 8,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query([
                'user' => $username,
                'key' => $password,
                'captcha' => '13',  // Answer to "What is 6 + 7?"
            ]),
            CURLOPT_HEADER => true,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (PWPanel/2.0)',
        ]);

        $response = curl_exec($ch);
        $curlError = curl_error($ch);
        curl_close($ch);

        if (! is_string($response) || $response === '') {
            if ($curlError !== '') {
                Log::warning('RoleController::getTomcatSession curl error: ' . $curlError);
            }

            return null;
        }

        // Extract JSESSIONID from Set-Cookie header
        if (preg_match('/Set-Cookie:\s*JSESSIONID=([^;\s]+)/i', $response, $m) === 1) {
            return $m[1];
        }

        // Fallback: check if it's in the response body (shouldn't be, but just in case)
        if (preg_match('/JSESSIONID=([^;\s]+)/i', $response, $m) === 1) {
            return $m[1];
        }

        return null;
    }

    private function isAccountGameOnline(int $userId): bool
    {
        if ($userId <= 0) {
            return false;
        }
        $user = User::query()->where('ID', $userId)->first();
        if ($user) {
            return $user->isOnline();
        }
        try {
            return DB::table('point')
                ->where('uid', $userId)
                ->where('zoneid', 1)
                ->exists();
        } catch (\Throwable $e) {
            return false;
        }
    }
}
