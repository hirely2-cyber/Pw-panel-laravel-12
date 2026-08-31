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
use App\Services\ItemDatabase;
use App\Services\PwAdminRoleXmlService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
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

        $probeRoleId = (int) ($characters->first()->role_id ?? 0);
        $cubiData = $this->resolveCubiData($user->ID, $probeRoleId > 0 ? $probeRoleId : null);

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

        try {
            DB::connection('mysql_game')->transaction(function () use ($user, $cashValue) {
                $nextSn = (DB::connection('mysql_game')
                    ->table('usecashnow')
                    ->where('userid', $user->ID)
                    ->where('zoneid', 1)
                    ->min('sn') ?? 0) - 1;

                DB::connection('mysql_game')->table('usecashnow')->insert([
                    'userid'   => $user->ID,
                    'zoneid'   => 1,
                    'sn'       => $nextSn,
                    'aid'      => 1,
                    'point'    => 0,
                    'cash'     => $cashValue,
                    'status'   => 0,
                    'creatime' => now(),
                ]);
                // usecashlog is written by the billing daemon after delivery.
            });
        } catch (\Throwable $e) {
            \Log::error("Cubi topup error: " . $e->getMessage());
            return back()->with('error', 'Gagal mengirim Cubi: ' . $e->getMessage());
        }

        // Track for Cubi Monitor source detection
        DB::table('pw_admin_cubi_topups')->insert([
            'user_id'    => $user->ID,
            'admin_id'   => auth()->id(),
            'amount'     => $request->amount,
            'reason'     => $request->reason,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Berhasil mengirim ' . number_format($request->amount) . ' Cubi Gold ke ' . $user->name . '. Akan diterima saat login/relog.');
    }

    public function characterDetail(Request $request, User $user, int $roleId): View
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

        $cubiData = $this->resolveCubiData($user->ID, $roleId);

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

        // ?view=xml: same XML as /pwAdmin/rolexml.jsp (Java XmlRole), fetched via api_rolexml_xml.jsp
        $roleXml = null;
        $roleXmlError = null;
        if ($request->query('view') === 'xml' || $request->query('view') === 'raw') {
            [$roleXml, $roleXmlError] = (new PwAdminRoleXmlService())->fetchRoleXmlWithError($roleId);
        }

        $pwadminRolexmlUrl = rtrim(config('pw-api.pwadmin_url', 'http://127.0.0.1:8080/pwAdmin/'), '/') . '/rolexml.jsp?ident=' . $roleId;

        return view('admin.members.character', compact('user', 'character', 'roleData', 'cubiData', 'itemNames', 'roleXml', 'roleXmlError', 'pwadminRolexmlUrl'));
    }

    public function saveCharacter(Request $request, User $user, int $roleId): RedirectResponse
    {
        $characters = $user->gameCharacters();
        $character = $characters->firstWhere('role_id', $roleId);
        if (! $character) {
            abort(404, 'Character not found.');
        }

        if ($user->isOnline()) {
            return back()->with('error', 'Character harus offline untuk dimodifikasi. Kick dulu dari server.');
        }

        $validated = $request->validate([
            'world' => ['required', 'integer', 'min:0'],
            'pos_x' => ['required', 'numeric'],
            'pos_z' => ['required', 'numeric'],
            'pos_y' => ['required', 'numeric'],
            'reputation' => ['required', 'integer', 'min:0', 'max:2147483647'],
            'exp' => ['required', 'integer', 'min:0', 'max:2147483647'],
            'sp' => ['required', 'integer', 'min:0', 'max:2147483647'],
            'cultivation' => ['required', 'integer', 'in:0,1,2,3,4,5,6,7,8,20,21,22,30,31,32'],
            'vigor' => ['required', 'integer', 'in:0,99,199,299,399'],
            'pocket_money' => ['required', 'integer', 'min:0', 'max:200000000'],
            'store_money' => ['required', 'integer', 'min:0', 'max:200000000'],
        ]);

        $gameDb = new GameDbService();
        Cache::forget("pw.role.{$roleId}");
        $before = $gameDb->getRoleData($roleId);
        if (! $before) {
            return back()->with('error', 'Gagal mengambil data karakter dari game server.');
        }

        $beforeStatus = $before['status'] ?? [];
        $beforeProp = $beforeStatus['property'] ?? [];
        $beforePocket = (int) ($before['pocket']['money'] ?? 0);
        $beforeStore = (int) ($before['storehouse']['money'] ?? 0);

        $target = [
            'world' => (int) $validated['world'],
            'pos_x' => (float) $validated['pos_x'],
            'pos_z' => (float) $validated['pos_z'],
            'pos_y' => (float) $validated['pos_y'],
            'reputation' => (int) $validated['reputation'],
            'exp' => (int) $validated['exp'],
            'sp' => (int) $validated['sp'],
            'cultivation' => (int) $validated['cultivation'],
            'vigor' => (int) $validated['vigor'],
            'pocketcoins' => (int) $validated['pocket_money'],
            'storehousecoins' => (int) $validated['store_money'],
        ];

        $isFloatChanged = static fn (float $a, float $b): bool => abs($a - $b) > 0.0001;
        $payload = [];

        // Only add fields that actually changed
        if ($target['world'] !== (int) ($beforeStatus['world_tag'] ?? 0)) {
            $payload['world'] = $target['world'];
        }
        if ($isFloatChanged($target['pos_x'], (float) ($beforeStatus['pos_x'] ?? 0))) {
            $payload['coordinateX'] = $target['pos_x'];
        }
        if ($isFloatChanged($target['pos_z'], (float) ($beforeStatus['pos_z'] ?? 0))) {
            $payload['coordinateZ'] = $target['pos_z'];
        }
        if ($isFloatChanged($target['pos_y'], (float) ($beforeStatus['pos_y'] ?? 0))) {
            $payload['coordinateY'] = $target['pos_y'];
        }
        if ($target['reputation'] !== (int) ($beforeStatus['reputation'] ?? 0)) {
            $payload['reputation'] = $target['reputation'];
        }
        if ($target['exp'] !== (int) ($beforeStatus['exp'] ?? 0)) {
            $payload['exp'] = $target['exp'];
        }
        if ($target['sp'] !== (int) ($beforeStatus['sp'] ?? 0)) {
            $payload['sp'] = $target['sp'];
        }
        if ($target['cultivation'] !== (int) ($beforeStatus['cultivation'] ?? 0)) {
            $payload['cultivation'] = $target['cultivation'];
        }
        if ($target['vigor'] !== (int) ($beforeProp['max_ap'] ?? 0)) {
            $payload['vigor'] = $target['vigor'];
        }
        if ($target['pocketcoins'] !== $beforePocket) {
            $payload['pocketcoins'] = $target['pocketcoins'];
        }
        if ($target['storehousecoins'] !== $beforeStore) {
            $payload['storehousecoins'] = $target['storehousecoins'];
        }

        if (empty($payload)) {
            return back()->with('success', 'Tidak ada perubahan data untuk disimpan.');
        }

        [$ok, $saveError] = $this->saveRoleViaTomcat($roleId, $payload);

        if (! $ok) {
            return back()->with('error', $saveError ?? 'Tomcat menolak save character.');
        }

        Cache::forget("pw.role.{$roleId}");
        $after = $gameDb->getRoleData($roleId);
        if (! $after) {
            return back()->with('error', 'Save terkirim, tapi verifikasi gagal baca data terbaru.');
        }

        $afterStatus = $after['status'] ?? [];
        $afterProp = $afterStatus['property'] ?? [];
        $afterPocket = (int) ($after['pocket']['money'] ?? 0);
        $afterStore = (int) ($after['storehouse']['money'] ?? 0);

        // Verify that changes were applied (only check fields that were sent)
        $isFloatChanged = static fn (float $a, float $b): bool => abs($a - $b) > 0.0001;
        $notApplied = [];

        if (isset($payload['world']) && $target['world'] !== (int) ($afterStatus['world_tag'] ?? 0)) $notApplied[] = 'world';
        if (isset($payload['coordinateX']) && $isFloatChanged($target['pos_x'], (float) ($afterStatus['pos_x'] ?? 0))) $notApplied[] = 'coordinateX';
        if (isset($payload['coordinateZ']) && $isFloatChanged($target['pos_z'], (float) ($afterStatus['pos_z'] ?? 0))) $notApplied[] = 'coordinateZ';
        if (isset($payload['coordinateY']) && $isFloatChanged($target['pos_y'], (float) ($afterStatus['pos_y'] ?? 0))) $notApplied[] = 'coordinateY';
        if (isset($payload['reputation']) && $target['reputation'] !== (int) ($afterStatus['reputation'] ?? 0)) $notApplied[] = 'reputation';
        if (isset($payload['exp']) && $target['exp'] !== (int) ($afterStatus['exp'] ?? 0)) $notApplied[] = 'exp';
        if (isset($payload['sp']) && $target['sp'] !== (int) ($afterStatus['sp'] ?? 0)) $notApplied[] = 'sp';
        if (isset($payload['cultivation']) && $target['cultivation'] !== (int) ($afterStatus['cultivation'] ?? 0)) $notApplied[] = 'cultivation';
        if (isset($payload['vigor']) && $target['vigor'] !== (int) ($afterProp['max_ap'] ?? 0)) $notApplied[] = 'vigor';
        if (isset($payload['pocketcoins']) && $target['pocketcoins'] !== $afterPocket) $notApplied[] = 'pocketcoins';
        if (isset($payload['storehousecoins']) && $target['storehousecoins'] !== $afterStore) $notApplied[] = 'storehousecoins';

        if (! empty($notApplied)) {
            return back()->with('error', 'Save dikirim, tapi data belum berubah pada: ' . implode(', ', $notApplied) . '.');
        }

        return back()->with('success', 'Character data berhasil disimpan.');
    }

    public function saveRoleXml(Request $request, User $user, int $roleId): RedirectResponse
    {
        $request->validate([
            'xml' => ['required', 'string', 'max:15000000'],
        ], [
            'xml.required' => 'Kolom XML wajib diisi.',
        ]);

        $characters = $user->gameCharacters();
        $character = $characters->firstWhere('role_id', $roleId);
        if (! $character) {
            abort(404, 'Character not found.');
        }

        if ($user->isOnline()) {
            $url = route('admin.members.character', [
                'user' => $user,
                'roleId' => $roleId,
            ]) . '?view=xml';

            return redirect($url)
                ->with('error', 'Character harus offline untuk mengubah XML. Kick dulu dari server.');
        }

        $service = new PwAdminRoleXmlService();
        [$ok, $err] = $service->saveRoleXmlWithError($roleId, (string) $request->input('xml', ''));
        if (! $ok) {
            $url = route('admin.members.character', [
                'user' => $user,
                'roleId' => $roleId,
            ]) . '?view=xml';

            return redirect($url)
                ->with('error', 'Simpan XML gagal: ' . $err);
        }

        Cache::forget("pw.role.{$roleId}");

        $url = route('admin.members.character', [
            'user' => $user,
            'roleId' => $roleId,
        ]) . '?view=xml';

        return redirect($url)
            ->with('success', 'Role XML tersimpan (Tomcat: XmlRole.putRoleToDB).');
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
        curl_close($ch);

        if (! is_string($response) || $response === '') {
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

    /**
     * Resolve Cubi data for display.
     *
     * Preferred source is live GetUser (gamedbd). If the live value is clearly
     * inconsistent on split-VPS setups, fallback to legacy aggregates from
     * mysql_game tables to match old panel behavior.
     */
    private function resolveCubiData(int $userId, ?int $roleId = null): ?array
    {
        // 1) Preferred source: pwAdmin Java API (same source as role.jsp realtime)
        try {
            $base = rtrim((string) config('pw-api.pwadmin_url', 'http://127.0.0.1:8080/pwAdmin/'), '/');
            $token = (string) config('pw-api.pwadmin_api_token', 'pw_panel_sync_2026');
            $query = [
                'token' => $token,
            ];
            if ($roleId !== null && $roleId > 0) {
                $query['ident'] = (string) $roleId;
            } else {
                $query['userid'] = (string) $userId;
            }

            $res = Http::timeout(10)->get($base . '/api_user_cash.jsp', $query);
            if ($res->ok()) {
                $json = $res->json();
                if (is_array($json) && ($json['ok'] ?? false)) {
                    $cashAdd = (int) ($json['cash_add'] ?? 0);
                    $cashBuy = (int) ($json['cash_buy'] ?? 0);
                    $cashSell = (int) ($json['cash_sell'] ?? 0);
                    $cashUsed = (int) ($json['cash_used'] ?? 0);
                    $rawCash = (int) ($json['cash'] ?? 0);
                    $derivedCash = max(0, $cashAdd + $cashBuy - $cashUsed - $cashSell);

                    return [
                        'logicuid' => (int) ($json['logicuid'] ?? 0),
                        'cash' => $rawCash > 0 ? $rawCash : $derivedCash,
                        'cash_add' => $cashAdd,
                        'cash_buy' => $cashBuy,
                        'cash_sell' => $cashSell,
                        'cash_used' => $cashUsed,
                        'source' => 'pwadmin_java',
                    ];
                }
            }
        } catch (\Throwable $e) {
            // continue to fallback sources
        }

        // 2) Fallback source: direct GetUser from gamedbd
        $live = null;
        try {
            $live = (new GameDbService())->getUserCash($userId);
        } catch (\Throwable $e) {
            $live = null;
        }

        $delivered = 0;
        $pending = 0;
        $spent = 0;

        try {
            $delivered = (int) DB::connection('mysql_game')
                ->table('usecashlog')
                ->where('userid', $userId)
                ->where('status', 4)
                ->sum('cash');
        } catch (\Throwable $e) {
            $delivered = 0;
        }

        try {
            $pending = (int) DB::connection('mysql_game')
                ->table('usecashnow')
                ->where('userid', $userId)
                ->sum('cash');
        } catch (\Throwable $e) {
            $pending = 0;
        }

        try {
            $spent = (int) (DB::connection('mysql_game')
                ->table('pw_top_sultan')
                ->where('userid', $userId)
                ->value('cash_used') ?? 0);
        } catch (\Throwable $e) {
            $spent = 0;
        }

        $legacyBalance = max(0, $delivered + $pending - $spent);

        if (! is_array($live)) {
            return [
                'logicuid' => 0,
                'cash' => $legacyBalance,
                'cash_add' => $delivered,
                'cash_buy' => 0,
                'cash_sell' => 0,
                'cash_used' => $spent,
                'source' => 'legacy_logs',
            ];
        }

        $liveCash = max(0, (int) ($live['cash'] ?? 0));
        $looksAnomalous = $legacyBalance > 0
            && ($liveCash <= 0 || $liveCash < (int) floor($legacyBalance * 0.5));

        if ($looksAnomalous) {
            $live['cash'] = $legacyBalance;
            $live['cash_add'] = $delivered;
            $live['cash_buy'] = 0;
            $live['cash_sell'] = 0;
            $live['cash_used'] = $spent;
            $live['source'] = 'legacy_logs';

            return $live;
        }

        $live['source'] = 'gamedbd';

        return $live;
    }
}
