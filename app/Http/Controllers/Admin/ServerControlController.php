<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServerControlLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ServerControlController extends Controller
{
    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------

    private function serverPath(): string
    {
        $path = DB::table('pw_settings')
            ->where('key', 'server_path')
            ->value('value') ?? '';

        return rtrim($path, '/') . '/';
    }

    private function sh(string $cmd): array
    {
        $output = [];
        $rc     = 0;
        exec($cmd . ' 2>&1', $output, $rc);
        return ['output' => implode("\n", $output), 'exit' => $rc];
    }

    /** Read static + dynamic server specs. */
    private function serverInfo(): array
    {
        $cpuModel  = trim(@shell_exec("cat /proc/cpuinfo 2>/dev/null | grep 'model name' | head -1 | cut -d: -f2") ?? '');
        $cpuCores  = (int) trim(@shell_exec('nproc 2>/dev/null') ?? '0');
        $os        = trim(@shell_exec("cat /etc/os-release 2>/dev/null | grep PRETTY_NAME | cut -d= -f2 | tr -d '\"'") ?? '');
        $uptime    = trim(@shell_exec('uptime -p 2>/dev/null') ?? '');
        $hostname  = trim(@shell_exec('hostname 2>/dev/null') ?? '');

        // Disk usage for /
        $df        = preg_split('/\s+/', trim(@shell_exec("df -h / 2>/dev/null | tail -1") ?? ''));
        $diskTotal = $df[1] ?? '-';
        $diskUsed  = $df[2] ?? '-';
        $diskPct   = (int) str_replace('%', '', $df[4] ?? '0');

        // CPU load
        $loadRaw   = @shell_exec('cat /proc/loadavg 2>/dev/null') ?? '';
        $loadParts = explode(' ', trim($loadRaw));
        $load1     = $loadParts[0] ?? '0';
        $load5     = $loadParts[1] ?? '0';
        $load15    = $loadParts[2] ?? '0';

        return compact('cpuModel','cpuCores','os','uptime','hostname','diskTotal','diskUsed','diskPct','load1','load5','load15');
    }

    /** Read daemon process list from `ps -A w`. */
    private function processList(): array
    {
        $daemons = [
            'logservice'  => ['label' => 'Log Service',    'process' => './logservice',  'count' => 0],
            'authd'       => ['label' => 'Auth Daemon',    'process' => './authd',       'count' => 0],
            'uniquenamed' => ['label' => 'Unique Named',   'process' => './uniquenamed', 'count' => 0],
            'gacd'        => ['label' => 'AC Daemon',      'process' => './gacd',        'count' => 0],
            'gfactiond'   => ['label' => 'Faction Daemon', 'process' => './gfactiond',   'count' => 0],
            'gdeliveryd'  => ['label' => 'Delivery Daemon','process' => './gdeliveryd',  'count' => 0],
            'glinkd'      => ['label' => 'Link Daemon',    'process' => './glinkd',      'count' => 0],
            'gamedbd'     => ['label' => 'Game DB Daemon', 'process' => './gamedbd',     'count' => 0],
            'gs'          => ['label' => 'World Server',   'process' => './gs',          'count' => 0],
        ];

        $maps          = [];
        $backupRunning = false;

        $output = @shell_exec('ps -A w 2>/dev/null');
        if (! $output) return compact('daemons', 'maps', 'backupRunning');

        foreach (explode("\n", $output) as $line) {
            if (str_contains($line, './pw_backup.sh'))  $backupRunning = true;
            if (str_contains($line, './logservice'))    $daemons['logservice']['count']++;
            // authd runs as a Java wrapper: match 'authd table.xml', not './authd' (which may not appear)
            if (str_contains($line, 'authd') && str_contains($line, 'table.xml')) {
                $daemons['authd']['count']++;
            }
            if (str_contains($line, './uniquenamed'))   $daemons['uniquenamed']['count']++;
            if (str_contains($line, './gacd'))          $daemons['gacd']['count']++;
            if (str_contains($line, './gfactiond'))     $daemons['gfactiond']['count']++;
            if (str_contains($line, './gdeliveryd'))    $daemons['gdeliveryd']['count']++;
            if (str_contains($line, './glinkd'))        $daemons['glinkd']['count']++;
            if (str_contains($line, './gamedbd'))       $daemons['gamedbd']['count']++;
            if (preg_match('/\.(\/gs)\s+\S+/', $line)) $daemons['gs']['count']++;

            if (preg_match('/\.(\/gs\s+(\S+))/', $line, $m)) {
                $mapId = $m[2];
                $parts = preg_split('/\s+/', trim($line));
                $pid   = $parts[0] ?? '0';
                if (! isset($maps[$mapId])) {
                    $maps[$mapId] = $pid;
                }
            }
        }

        return compact('daemons', 'maps', 'backupRunning');
    }

    /** Read memory info from `free -m`. */
    private function memoryInfo(): array
    {
        $output = @shell_exec('free -m 2>/dev/null');
        if (! $output) return ['ram_total'=>0,'ram_used'=>0,'ram_buff'=>0,'ram_avail'=>0,'swp_total'=>0,'swp_used'=>0];

        $lines = explode("\n", trim($output));
        $mem   = preg_split('/\s+/', trim($lines[1] ?? ''));
        $swp   = preg_split('/\s+/', trim($lines[2] ?? ''));

        // free -m columns: Mem: total used free shared buff/cache available
        return [
            'ram_total' => (int) ($mem[1] ?? 0),
            'ram_used'  => (int) ($mem[2] ?? 0),   // apps only
            'ram_buff'  => (int) ($mem[5] ?? 0),   // buff/cache
            'ram_avail' => (int) ($mem[6] ?? 0),   // truly available
            'swp_total' => (int) ($swp[1] ?? 0),
            'swp_used'  => (int) ($swp[2] ?? 0),
        ];
    }

    private function isServerRunning(array $daemons): bool
    {
        return ($daemons['authd']['count'] ?? 0) > 0
            && ($daemons['gdeliveryd']['count'] ?? 0) > 0;
    }

    /** True kalau ada daemon apapun yang masih hidup (gamedbd, uniquenamed, dll). */
    private function anyDaemonRunning(array $daemons): bool
    {
        foreach ($daemons as $d) {
            if (($d['count'] ?? 0) > 0) return true;
        }
        return false;
    }

    /** Daftar semua map PW 1.5.x — mapId => nama */
    private array $availableMaps = [
        'gs01'    => 'World',
        'is01'    => 'City of Abominations',
        'is02'    => 'Secret Passage',
        'is05'    => 'Firecrag Grotto',
        'is06'    => 'Den of Rabid Wolves',
        'is07'    => 'Cave of the Vicious',
        'is08'    => 'Hall of Deception',
        'is09'    => 'Gate of Delirium',
        'is10'    => 'Secret Frostcover Grounds',
        'is11'    => 'Valley of Disaster',
        'is12'    => 'Forest Ruins',
        'is13'    => 'Cave of Sadistic Glee',
        'is14'    => 'Wraithgate',
        'is15'    => 'Hallucinatory Trench',
        'is16'    => 'Eden',
        'is17'    => 'Brimstone Pit',
        'is18'    => 'Temple of the Dragon',
        'is19'    => 'Nightscream Island',
        'is20'    => 'Snake Isle',
        'is21'    => 'Lothranis',
        'is22'    => 'Momaganon',
        'is23'    => 'Seat of Torment',
        'is24'    => 'Abaddon',
        'is25'    => 'Warsong City',
        'is26'    => 'Palace of Nirvana',
        'is27'    => 'Lunar Glade',
        'is28'    => 'Valley of Reciprocity',
        'is29'    => 'Frostcover City',
        'is31'    => 'Twilight Temple',
        'is32'    => 'Cube of Fate',
        'is33'    => 'Chrono City',
        'is34'    => 'Perfect Chapel',
        'is35'    => 'Guild Base',
        'is37'    => 'Morai',
        'is38'    => 'Phoenix Valley',
        'is39'    => 'Endless Universe',
        'is40'    => 'Blighted Chamber',
        'is41'    => 'Endless Universe',
        'is42'    => 'Wargod Gulch',
        'is43'    => 'Five Emperors',
        'is44'    => 'Nation War 2',
        'is45'    => 'Nation War Tower',
        'is46'    => 'Nation War Crystal',
        'is47'    => 'Sunset Valley',
        'is48'    => 'Shutter Palace',
        'is49'    => 'Dragon Hidden Den',
        'is50'    => 'Realm of Reflection',
        'is61'    => 'Startpoint',
        'is62'    => 'Origination',
        'is63'    => 'Primal World',
        'is66'    => 'Flowsilver Palace',
        'is67'    => 'Undercurrent Hall',
        'is68'    => 'Mortal Realm',
        'is69'    => 'LightSail Cave',
        'is70'    => 'Cube of Fate (2)',
        'is71'    => 'Dragon Conquest',
        'is72'    => 'Heavenfall Temple',
        'is73'    => 'Heavenfall Temple 2',
        'is74'    => 'Heavenfall Temple 3',
        'is75'    => 'Heavenfall Temple 4',
        'is76'    => 'Uncharted Paradise',
        'is77'    => 'Thurs Fights Cross',
        'is78'    => 'Western Steppes',
        'is80'    => 'Homestead, Beyond the Clouds',
        'is81'    => 'Homestead 2',
        'is82'    => 'Homestead 3',
        'is83'    => 'Homestead 4',
        'is84'    => 'Grape Valley',
        'is85'    => 'Nemesis Gauntlet',
        'is86'    => 'Palace of the Dawn (DR 1)',
        'is87'    => 'Mirage Lake',
        'is88'    => 'Desert Ruins',
        'is89'    => 'Nightmare Woods',
        'is90'    => 'Palace of the Dawn (DR 2)',
        'is91'    => 'Adventure Kingdom',
        'is92'    => 'The Indestructible City',
        'is93'    => 'Hall of Fame',
        'is94'    => 'Battlefield - Dusk Outpost',
        'is95'    => 'Ice Hell',
        'is96'    => 'Arena of the Gods',
        'is97'    => 'Twilight Palace',
        'is98'    => 'Peach Abode',
        'is99'    => 'Abode of Dreams',
        'is101'   => 'White Wolf Pass',
        'is102'   => 'Imperial Battle',
        'is103'   => 'Northern Lands',
        'is105'   => 'Altar of the Virgin',
        'is106'   => 'Imperial Battle 2',
        'is107'   => 'Northern Lands 2',
        'is108'   => 'Full Moon Pavilion',
        'is109'   => 'Abode of Changes',
        'bg01'    => 'Territory War T-3 PvP',
        'bg02'    => 'Territory War T-3 PvE',
        'bg03'    => 'Territory War T-2 PvP',
        'bg04'    => 'Territory War T-2 PvE',
        'bg05'    => 'Territory War T-1 PvP',
        'bg06'    => 'Territory War T-1 PvE',
        'arena01' => 'Etherblade Arena',
        'arena02' => 'Lost Arena',
        'arena03' => 'Plume Arena',
        'arena04' => 'Archosaur Arenas',
        'rand03'  => 'Quicksand Maze (Sandstorm)',
        'rand04'  => 'Quicksand Maze (Wandering Sands)',
        'rand05'  => 'Tomb of Whispers',
    ];

    // -------------------------------------------------------
    // Index
    // -------------------------------------------------------

    public function index(): View
    {
        $serverPath     = $this->serverPath();
        $pathConfigured = $serverPath !== '/';

        $processInfo = $pathConfigured
            ? $this->processList()
            : ['daemons' => [], 'maps' => [], 'backupRunning' => false];

        $memory            = $this->memoryInfo();
        $serverInfo        = $this->serverInfo();
        $serverRunning     = $pathConfigured ? $this->isServerRunning($processInfo['daemons']) : false;
        $anyDaemonRunning  = $pathConfigured ? $this->anyDaemonRunning($processInfo['daemons']) : false;
        $availableMaps     = $this->availableMaps;
        $actionLogs        = ServerControlLog::query()->latest()->limit(80)->get();

        return view('admin.server-control', compact(
            'serverPath', 'pathConfigured',
            'processInfo', 'memory', 'serverInfo', 'serverRunning', 'anyDaemonRunning', 'availableMaps', 'actionLogs'
        ));
    }


    // -------------------------------------------------------
    // GM View
    // -------------------------------------------------------

    public function gmView(): \Illuminate\View\View
    {
        $serverPath     = $this->serverPath();
        $pathConfigured = $serverPath !== '/';

        $processInfo = $pathConfigured
            ? $this->processList()
            : ['daemons' => [], 'maps' => [], 'backupRunning' => false];

        $memory            = $this->memoryInfo();
        $serverInfo        = $this->serverInfo();
        $serverRunning     = $pathConfigured ? $this->isServerRunning($processInfo['daemons']) : false;
        $anyDaemonRunning  = $pathConfigured ? $this->anyDaemonRunning($processInfo['daemons']) : false;
        $availableMaps     = $this->availableMaps;
        $actionLogs        = ServerControlLog::query()->latest()->limit(50)->get();

        return view('gm.server-control', compact(
            'serverPath', 'pathConfigured',
            'processInfo', 'memory', 'serverInfo', 'serverRunning', 'anyDaemonRunning', 'availableMaps', 'actionLogs'
        ));
    }

    // -------------------------------------------------------
    // Save path
    // -------------------------------------------------------

    public function savePath(Request $request): RedirectResponse
    {
        $request->validate([
            'server_path' => ['required', 'string', 'regex:#^/[a-zA-Z0-9/_\-\.]+/?$#'],
        ]);

        DB::table('pw_settings')->upsert(
            [
                'key'        => 'server_path',
                'value'      => rtrim($request->server_path, '/'),
                'group'      => 'server',
                'updated_at' => now(),
            ],
            ['key'],
            ['value', 'updated_at']
        );

        return back()->with('success', 'Server path disimpan.');
    }

    // -------------------------------------------------------
    // AJAX action
    // -------------------------------------------------------

    public function action(Request $request): JsonResponse
    {
        $request->validate(['action' => 'required|in:start,stop,clearram,backup,stopmap,startmap,stopall']);

        $path = $this->serverPath();
        if ($path === '/') {
            return response()->json(['ok' => false, 'message' => 'Server path belum dikonfigurasi.'], 422);
        }

        $response = match ($request->action) {
            'start'    => $this->doStart($path),
            'stop'     => $this->doStop($path),
            'clearram' => $this->doClearRam(),
            'backup'   => $this->doBackup($path),
            'stopmap'  => $this->doStopMap($request->input('map', '')),
            'startmap' => $this->doStartMap($path, $request->input('map', '')),
            'stopall'  => $this->doStopAllMaps((int) $request->input('delay', 300)),
        };

        $payload = $response->getData(true);
        $this->logAction(
            request: $request,
            ok: (bool) ($payload['ok'] ?? false),
            message: (string) ($payload['message'] ?? ''),
            targetMap: (string) $request->input('map', ''),
            delay: (int) $request->input('delay', 0),
        );

        return $response;
    }

    private function logAction(Request $request, bool $ok, string $message, string $targetMap = '', int $delay = 0): void
    {
        try {
            $user = auth()->user();
            ServerControlLog::create([
                'user_id' => $user?->ID,
                'actor_name' => $user?->name,
                'actor_role' => $user?->role,
                'panel_area' => $request->routeIs('gm.*') ? 'gm' : 'admin',
                'action' => (string) $request->input('action', ''),
                'target_map' => $targetMap !== '' ? $targetMap : null,
                'delay_seconds' => max(0, $delay),
                'result_ok' => $ok,
                'result_message' => $message,
            ]);
        } catch (\Throwable) {
            // Silent fail: action should continue even if audit log insert fails.
        }
    }

    private function doStart(string $path): JsonResponse
    {
        $script = rtrim($path, '/') . '/server';
        if (! file_exists($script)) {
            return response()->json(['ok' => false, 'message' => "Script start tidak ditemukan: {$script}"]);
        }
        // Must run as root (game daemons require root). Use sudo via sudoers entry.
        shell_exec("setsid bash -c 'sudo {$script} start > /tmp/pw_start.log 2>&1' > /dev/null 2>&1 &");
        return response()->json(['ok' => true, 'message' => 'Perintah start dikirim. Tunggu beberapa detik...']);
    }

    private function doStop(string $path): JsonResponse
    {
        $serverScript = rtrim($path, '/') . '/server';
        // Use the server script's own stop logic (same as pwAdmin) - must run as root
        shell_exec("setsid bash -c 'sudo {$serverScript} stop > /tmp/pw_stop.log 2>&1' > /dev/null 2>&1 &");
        return response()->json(['ok' => true, 'message' => 'Perintah stop dikirim.']);
    }

    private function doClearRam(): JsonResponse
    {
        // sync then drop_caches via sudo tee (www-data can't write /proc directly)
        @shell_exec('sync 2>/dev/null');
        $r = $this->sh('echo 3 | sudo /usr/bin/tee /proc/sys/vm/drop_caches');
        if ($r['exit'] !== 0) {
            return response()->json(['ok' => false, 'message' => 'Gagal clear cache: ' . trim($r['output'])]);
        }
        return response()->json(['ok' => true, 'message' => 'RAM cache dibersihkan.']);
    }

    private function doBackup(string $path): JsonResponse
    {
        $outFile = rtrim($path, '/') . '/pw_backup_' . date('Y-m-d_H-i-s') . '.tar.gz';
        $dbName  = env('PW_GAME_DB_DATABASE', 'pwdb');
        $dbUser  = env('PW_GAME_DB_USERNAME', 'root');
        $dbPass  = env('PW_GAME_DB_PASSWORD', '');
        $dbHost  = env('PW_GAME_DB_HOST', '127.0.0.1');

        $runner = rtrim($path, '/') . '/tools/pw_backup_runner.sh';
        if (! file_exists($runner)) {
            return response()->json(['ok' => false, 'message' => "Backup runner tidak ditemukan: {$runner}"]);
        }

        $cmd = sprintf(
            'sudo %s %s %s %s %s %s > /tmp/pw_backup.log 2>&1 &',
            escapeshellarg($runner),
            escapeshellarg(rtrim($path, '/')),
            escapeshellarg((string) $dbHost),
            escapeshellarg((string) $dbUser),
            escapeshellarg((string) $dbPass),
            escapeshellarg((string) $dbName)
        );
        shell_exec("setsid bash -c \"{$cmd}\" > /dev/null 2>&1 &");

        return response()->json(['ok' => true, 'message' => 'Backup dimulai di background. File disimpan di ' . $outFile]);
    }

    private function doStopMap(string $mapId): JsonResponse
    {
        if (! preg_match('/^\w+$/', $mapId)) {
            return response()->json(['ok' => false, 'message' => 'Map ID tidak valid.']);
        }

        $serverScript = rtrim($this->serverPath(), '/') . '/server';
        if (! file_exists($serverScript)) {
            return response()->json(['ok' => false, 'message' => "Script server tidak ditemukan: {$serverScript}"]);
        }

        $cmd = sprintf(
            'sudo %s stop-map %s',
            escapeshellarg($serverScript),
            escapeshellarg($mapId)
        );
        $r = $this->sh($cmd);

        if ($r['exit'] === 0) {
            return response()->json(['ok' => true, 'message' => "Perintah stop map {$mapId} dijalankan."]);
        }

        return response()->json([
            'ok' => false,
            'message' => 'Gagal stop map: ' . trim($r['output'] ?? ''),
        ]);
    }

    private function doStopAllMaps(int $delay): JsonResponse
    {
        $delay  = max(0, (int) $delay);
        $serverRoot = rtrim($this->serverPath(), '/');
        $root = base_path();

        $cmd = sprintf(
            'cd %s && nohup php artisan pw:safe-stop --delay=%d --server=%s > /tmp/pw_safe_stop.log 2>&1 &',
            escapeshellarg($root),
            $delay,
            escapeshellarg($serverRoot)
        );
        // setsid creates new session so process survives after request ends
        shell_exec('setsid bash -c ' . escapeshellarg($cmd) . ' > /dev/null 2>&1 &');

        $msg = $delay > 0
            ? "Safe Stop dikirim — countdown in-game aktif, map akan dihentikan dalam {$delay} detik."
            : 'Safe Stop dikirim — map dihentikan sekarang.';
        return response()->json(['ok' => true, 'message' => $msg]);
    }

    private function doStartMap(string $path, string $mapId): JsonResponse
    {
        if (! preg_match('/^\w+$/', $mapId)) {
            return response()->json(['ok' => false, 'message' => 'Map ID tidak valid.']);
        }

        $serverScript = rtrim($path, '/') . '/server';
        if (! file_exists($serverScript)) {
            return response()->json(['ok' => false, 'message' => "Script server tidak ditemukan: {$serverScript}"]);
        }

        $cmd = sprintf(
            'sudo %s start-map %s',
            escapeshellarg($serverScript),
            escapeshellarg($mapId)
        );
        $r = $this->sh($cmd);

        if ($r['exit'] === 0) {
            return response()->json(['ok' => true, 'message' => "Perintah start map {$mapId} dijalankan."]);
        }

        return response()->json([
            'ok' => false,
            'message' => 'Gagal start map: ' . trim($r['output'] ?? ''),
        ]);
    }

    // -------------------------------------------------------
    // AJAX status (polling)
    // -------------------------------------------------------

    public function status(): JsonResponse
    {
        $info   = $this->processList();
        $memory = $this->memoryInfo();

        $daemons = [];
        foreach ($info['daemons'] as $key => $d) {
            $daemons[$key] = $d['count'];
        }

        return response()->json([
            'daemons'        => $daemons,
            'maps'           => $info['maps'],
            'maps_count'     => count($info['maps']),
            'backup_running' => $info['backupRunning'],
            'server_running' => $this->isServerRunning($info['daemons']),
            'memory'         => $memory,
        ]);
    }
}
