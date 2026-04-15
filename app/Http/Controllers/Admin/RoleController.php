<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
    public function index(Request $request): View
    {
        $query = DB::connection('mysql_game')->table('roles');

        if ($search = $request->get('search')) {
            $query->where('role_name', 'like', "%{$search}%");
        }

        if ($classFilter = $request->get('class')) {
            $query->where('role_occupation', $classFilter);
        }

        $sortField = $request->get('sort', 'role_level');
        $sortDir = $request->get('dir', 'desc');
        $allowed = ['role_id', 'role_name', 'role_level', 'role_occupation', 'faction_name', 'pvp_kills'];
        if (!in_array($sortField, $allowed)) $sortField = 'role_level';
        if (!in_array($sortDir, ['asc', 'desc'])) $sortDir = 'desc';

        $roles = $query->orderBy($sortField, $sortDir)->paginate(30)->withQueryString();

        $totalRoles = DB::connection('mysql_game')->table('roles')->count();

        return view('admin.roles.index', [
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
        ]);
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
            'world'         => 'required|integer|min:0',
            'coordinateX'   => 'required|numeric',
            'coordinateZ'   => 'required|numeric',
            'coordinateY'   => 'required|numeric',
            'reputation'    => 'required|integer|min:0|max:2147483647',
            'exp'           => 'required|integer|min:0|max:2147483647',
            'sp'            => 'required|integer|min:0|max:2147483647',
            'cultivation'   => 'required|integer|in:0,1,2,3,4,5,6,7,8,20,21,22,30,31,32',
            'vigor'         => 'required|integer|in:0,99,199,299,399',
            'pocketcoins'   => 'required|integer|min:0|max:200000000',
            'storehousecoins' => 'required|integer|min:0|max:200000000',
        ]);

        $tomcatUrl = config('pw-api.pwadmin_url', 'http://127.0.0.1:8080/pwAdmin/');
        $saveUrl = rtrim($tomcatUrl, '/') . "/index.jsp?page=role&show=details&ident={$roleId}&type=id&process=save";

        $session = $this->getTomcatSession();

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $saveUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($validated),
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPHEADER     => ['Accept: text/html'],
            CURLOPT_COOKIE         => 'JSESSIONID=' . ($session ?? ''),
        ]);

        $response  = curl_exec($ch);
        $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            return back()->withErrors(['error' => "Koneksi ke Tomcat gagal: {$curlError}"])->withInput();
        }

        // Flush cache so show() gets fresh data
        \Illuminate\Support\Facades\Cache::forget("pw.role.{$roleId}");

        $failed = stripos($response ?? '', 'error') !== false && $httpCode !== 200;
        if ($failed) {
            return back()->withErrors(['error' => "Tomcat menolak save. HTTP {$httpCode}."])->withInput();
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
            // Call dedicated sync API endpoint (localhost only, no login required)
            $tomcatUrl = config('pw-api.pwadmin_url', 'http://127.0.0.1:8080/pwAdmin/');
            $syncUrl = rtrim($tomcatUrl, '/') . '/api_sync_roles.jsp?token=pw_panel_sync_2026';

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
                    'message' => $data['message'] ?? "Sync gagal (HTTP {$httpCode})",
                ], 422);
            }

            $totalAfter = DB::connection('mysql_game')->table('roles')->count();

            return response()->json([
                'ok' => true,
                'message' => "Sync berhasil. Total {$totalAfter} character di database.",
                'total' => $totalAfter,
            ]);
        } finally {
            \Illuminate\Support\Facades\Cache::forget($lockKey);
        }
    }
}
