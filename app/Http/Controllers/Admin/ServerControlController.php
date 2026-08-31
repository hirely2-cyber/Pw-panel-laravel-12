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
    private ?array $remoteSnapshot = null;

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
        $marker = '__PW_RC__';
        $wrapped = "bash -lc " . escapeshellarg($cmd . '; printf "\\n' . $marker . '%s" "$?"');
        $raw = @shell_exec($wrapped . ' 2>&1');

        if (! is_string($raw) || $raw === '') {
            return ['output' => '', 'exit' => 127];
        }

        $pos = strrpos($raw, $marker);
        if ($pos === false) {
            return ['output' => trim($raw), 'exit' => 0];
        }

        $output = trim(substr($raw, 0, $pos));
        $codeRaw = trim(substr($raw, $pos + strlen($marker)));
        $exit = is_numeric($codeRaw) ? (int) $codeRaw : 1;

        return ['output' => $output, 'exit' => $exit];
    }

    private function getRemoteSnapshot(): ?array
    {
        if ($this->remoteSnapshot !== null) {
            return $this->remoteSnapshot;
        }

        $script = '/home/pw_server155/tools/server_status_json.sh';
        if (! is_file($script)) {
            return null;
        }

        $r = $this->sh('sudo -n ' . escapeshellarg($script));
        if (($r['exit'] ?? 1) !== 0) {
            return null;
        }

        $json = trim((string) ($r['output'] ?? ''));
        if ($json === '') {
            return null;
        }

        $decoded = json_decode($json, true);
        if (! is_array($decoded)) {
            return null;
        }

        $this->remoteSnapshot = $decoded;
        return $this->remoteSnapshot;
    }

    /** Read static + dynamic server specs. */
    private function serverInfo(): array
    {
        $remote = $this->getRemoteSnapshot();
        if (is_array($remote) && isset($remote['serverInfo']) && is_array($remote['serverInfo'])) {
            $info = $remote['serverInfo'];
            return [
                'cpuModel'  => (string) ($info['cpuModel'] ?? ''),
                'cpuCores'  => (int) ($info['cpuCores'] ?? 0),
                'os'        => (string) ($info['os'] ?? ''),
                'uptime'    => (string) ($info['uptime'] ?? ''),
                'hostname'  => (string) ($info['hostname'] ?? env('PW_SERVER_IP', 'game-backend')),
                'diskTotal' => (string) ($info['diskTotal'] ?? '-'),
                'diskUsed'  => (string) ($info['diskUsed'] ?? '-'),
                'diskPct'   => (int) ($info['diskPct'] ?? 0),
                'load1'     => (string) ($info['load1'] ?? '0'),
                'load5'     => (string) ($info['load5'] ?? '0'),
                'load15'    => (string) ($info['load15'] ?? '0'),
            ];
        }

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

        $remote = $this->getRemoteSnapshot();
        if (is_array($remote) && isset($remote['daemons']) && is_array($remote['daemons'])) {
            foreach ($daemons as $key => $meta) {
                $daemons[$key]['count'] = (int) ($remote['daemons'][$key] ?? 0);
            }

            $maps = is_array($remote['maps'] ?? null) ? $remote['maps'] : [];
            $backupRunning = (bool) ($remote['backupRunning'] ?? false);

            return compact('daemons', 'maps', 'backupRunning');
        }

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
        $remote = $this->getRemoteSnapshot();
        if (is_array($remote) && isset($remote['memory']) && is_array($remote['memory'])) {
            return [
                'ram_total' => (int) ($remote['memory']['ram_total'] ?? 0),
                'ram_used'  => (int) ($remote['memory']['ram_used'] ?? 0),
                'ram_buff'  => (int) ($remote['memory']['ram_buff'] ?? 0),
                'ram_avail' => (int) ($remote['memory']['ram_avail'] ?? 0),
                'swp_total' => (int) ($remote['memory']['swp_total'] ?? 0),
                'swp_used'  => (int) ($remote['memory']['swp_used'] ?? 0),
            ];
        }

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

    /** Daftar semua map PW 1.5.x — mapId => nama (lihat config/pw_maps.php) */
    private function availableMaps(): array
    {
        return config('pw_maps', []);
    }
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
        $availableMaps     = $this->availableMaps();
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
        $availableMaps     = $this->availableMaps();
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
