<?php

/*
 * @author   Wahyu Suhandi <andietz.orion@gmail.com>
 * @link     https://wa.me/6208118719377
 * @project  Perfect World Panel
 * @version  2.0.0
 * @license  MIT
 */

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GameServerService
{
    private static function remoteSnapshot(): ?array
    {
        $cacheKey = 'pw.server.remote_snapshot';

        return Cache::remember($cacheKey, 10, function () {
            $script = '/home/pw_server155/tools/server_status_json.sh';
            if (! is_file($script)) {
                return null;
            }

            $raw = @shell_exec('sudo -n ' . escapeshellarg($script) . ' 2>/dev/null');
            if (! is_string($raw) || trim($raw) === '') {
                return null;
            }

            $decoded = json_decode(trim($raw), true);
            return is_array($decoded) ? $decoded : null;
        });
    }

    /**
     * Check if the PW game server is online by attempting a TCP connection
     * to the client port (PW_PORT_CLIENT, default 29000).
     *
     * Result is cached for 30 seconds to avoid socket overhead on every request.
     */
    public static function isOnline(): bool
    {
        return Cache::remember('pw.server.online', 30, function () {
            $ip      = config('pw-config.server.ip', '127.0.0.1');
            $port    = (int) config('pw-api.ports.client', 29000);
            $timeout = 2; // seconds

            try {
                $sock = @fsockopen($ip, $port, $errno, $errstr, $timeout);
                if ($sock) {
                    fclose($sock);
                    return true;
                }
            } catch (\Throwable $e) {
                Log::debug("GameServerService::isOnline failed: {$e->getMessage()}");
            }

            return false;
        });
    }

    /**
     * Get server uptime in seconds by checking the elapsed time of the glinkd process.
     * Returns 0 if the process is not running.
     */
    public static function uptime(): int
    {
        return Cache::remember('pw.server.uptime', 30, function () {
            try {
                $snapshot = self::remoteSnapshot();
                if (is_array($snapshot)) {
                    $sec = (int) ($snapshot['serverInfo']['uptimeSec'] ?? 0);
                    if ($sec > 0) {
                        return $sec;
                    }
                }
            } catch (\Throwable $e) {
                Log::debug("GameServerService::uptime remote snapshot failed: {$e->getMessage()}");
            }

            try {
                // Get elapsed time of glinkd in seconds (etimes format)
                $output = shell_exec("ps -C glinkd -o etimes= 2>/dev/null");
                if ($output) {
                    // Take the first (oldest) glinkd process
                    $lines = array_filter(array_map('trim', explode("\n", trim($output))));
                    if (!empty($lines)) {
                        return (int) max($lines);
                    }
                }
            } catch (\Throwable $e) {
                Log::debug("GameServerService::uptime failed: {$e->getMessage()}");
            }
            return 0;
        });
    }

    /**
     * Get the number of online players.
     *
     * Counts established TCP connections on glinkd external ports (29000–29006).
     * Each player maintains exactly one TCP connection to a glinkd instance.
     * Localhost connections (inter-daemon) are excluded.
     *
     * Result is cached for 30 seconds.
     */
    public static function onlineCount(): int
    {
        return (int) Cache::remember('pw.server.online_count', 30, function () {
            try {
                // Prefer pwAdmin API through tunnel for accuracy on split-VPS setup.
                $players = self::onlinePlayers();
                if (is_array($players)) {
                    return max(0, count($players));
                }
            } catch (\Throwable $e) {
                Log::debug("GameServerService::onlineCount onlinePlayers fallback: {$e->getMessage()}");
            }

            try {
                $clientPort = (int) config('pw-api.ports.client', 29000);
                $glinkdCount = (int) env('PW_GLINKD_COUNT', 7);
                $lastPort = $clientPort + $glinkdCount - 1;

                // Build port filter for ss command
                $portFilters = [];
                for ($p = $clientPort; $p <= $lastPort; $p++) {
                    $portFilters[] = "sport = :{$p}";
                }
                $filter = implode(' or ', $portFilters);

                $output = @shell_exec("ss -tn state established '( {$filter} )' 2>/dev/null");

                if (! $output) {
                    return max(0, (int) env('PW_FAKE_ONLINE', 0));
                }

                $count = 0;
                foreach (explode("\n", trim($output)) as $line) {
                    // Skip header line and empty lines
                    if (str_contains($line, 'Recv-Q') || trim($line) === '') {
                        continue;
                    }
                    // Skip inter-daemon connections (localhost to localhost)
                    if (preg_match('/127\.0\.0\.1.+127\.0\.0\.1/', $line)) {
                        continue;
                    }
                    $count++;
                }

                return max(0, $count);
            } catch (\Throwable $e) {
                Log::debug("GameServerService::onlineCount error: {$e->getMessage()}");
            }

            return max(0, (int) env('PW_FAKE_ONLINE', 0));
        });
    }

    /**
     * Get total registered account count from the panel database.
     * Cached for 5 minutes.
     */
    public static function accountCount(): int
    {
        return (int) Cache::remember('pw.server.account_count', 300, function () {
            try {
                return \App\Models\User::count();
            } catch (\Throwable $e) {
                Log::debug("GameServerService::accountCount error: {$e->getMessage()}");
                return 0;
            }
        });
    }

    /**
     * Check all PW game daemon ports and return status for each.
     * Result cached for 15 seconds (frequent checks are fast TCP probes).
     *
     * Returns: array of ['name' => string, 'port' => int, 'online' => bool, 'label' => string]
     */
    public static function daemonStatus(): array
    {
        return Cache::remember('pw.server.daemons', 15, function () {
            $ip = config('pw-config.server.ip', '127.0.0.1');

            $daemons = [
                ['key' => 'gdeliveryd', 'label' => 'gdeliveryd',  'desc' => 'Game Delivery',    'port' => (int) config('pw-api.ports.gdeliveryd', 29100)],
                ['key' => 'gamedbd',    'label' => 'gamedbd',     'desc' => 'Game Database',    'port' => (int) config('pw-api.ports.gamedbd',    29400)],
                ['key' => 'gacd',       'label' => 'gacd',        'desc' => 'Cash/Account',     'port' => (int) config('pw-api.ports.gacd',       29300)],
                ['key' => 'authd',      'label' => 'authd',       'desc' => 'Auth Daemon',      'port' => (int) env('PW_PORT_AUTHD', 29000)],
                ['key' => 'uniquenamed','label' => 'uniquenamed', 'desc' => 'Name Service',     'port' => (int) env('PW_PORT_UNIQUENAMED', 29005)],
                ['key' => 'logserver',  'label' => 'logserver',   'desc' => 'Log Server',       'port' => (int) env('PW_PORT_LOGSERVER', 29006)],
            ];

            foreach ($daemons as &$d) {
                // gamedbd: internal-only daemon — proxy check via DB connectivity
                if ($d['key'] === 'gamedbd') {
                    try {
                        DB::connection('mysql_game')->getPdo();
                        $d['online'] = true;
                    } catch (\Throwable $e) {
                        $d['online'] = false;
                    }
                    continue;
                }

                // gacd: internal-only daemon — proxy check via users table (gacd manages accounts)
                if ($d['key'] === 'gacd') {
                    try {
                        DB::connection('mysql')->table('users')->limit(1)->exists();
                        $d['online'] = true;
                    } catch (\Throwable $e) {
                        $d['online'] = false;
                    }
                    continue;
                }

                // Other daemons: TCP probe
                try {
                    $sock = @fsockopen($ip, $d['port'], $errno, $errstr, 1);
                    $d['online'] = (bool) $sock;
                    if ($sock) fclose($sock);
                } catch (\Throwable $e) {
                    $d['online'] = false;
                }
            }
            unset($d);

            return $daemons;
        });
    }

    /**
     * Get list of running maps from gs processes.
     * Returns array of map IDs (e.g. ['gs01', 'is01', 'is02']).
     * Cached for 30 seconds.
     */
    public static function runningMaps(): array
    {
        return Cache::remember('pw.server.maps', 30, function () {
            try {
                $snapshot = self::remoteSnapshot();
                if (is_array($snapshot) && is_array($snapshot['maps'] ?? null)) {
                    $maps = array_keys($snapshot['maps']);
                    sort($maps);
                    return $maps;
                }
            } catch (\Throwable $e) {
                Log::debug("GameServerService::runningMaps remote snapshot failed: {$e->getMessage()}");
            }

            $maps = [];
            $output = @shell_exec('ps -A w 2>/dev/null');
            if (! $output) return $maps;

            foreach (explode("\n", $output) as $line) {
                if (preg_match('/\.(\/gs\s+(\S+))/', $line, $m)) {
                    $mapId = $m[2];
                    if (! in_array($mapId, $maps)) {
                        $maps[] = $mapId;
                    }
                }
            }

            sort($maps);
            return $maps;
        });
    }

    /**
     * Get list of currently online characters via pwAdmin/gdeliveryd.
     * Each entry: {id, name, level, class, gender, faction}
     * Cached for 30 seconds.
     */
    public static function onlinePlayers(): array
    {
        return Cache::remember('pw.server.online_players', 60, function () {
            try {
                $url = 'http://127.0.0.1:8080/pwAdmin/api_online_players.jsp?token=pw_panel_sync_2026';
                $ctx = stream_context_create(['http' => ['timeout' => 5]]);
                $response = @file_get_contents($url, false, $ctx);

                if ($response === false) {
                    return [];
                }

                $data = json_decode($response, true);
                if (! $data || ! ($data['ok'] ?? false)) {
                    return [];
                }

                return $data['players'] ?? [];
            } catch (\Throwable $e) {
                Log::debug("GameServerService::onlinePlayers error: {$e->getMessage()}");
                return [];
            }
        });
    }

    /**
     * Flush all server stats caches.
     */
    public static function flushCache(): void
    {
        Cache::forget('pw.server.online');
        Cache::forget('pw.server.online_count');
        Cache::forget('pw.server.account_count');
        Cache::forget('pw.server.maps');
        Cache::forget('pw.server.online_players');
        Cache::forget('pw.server.remote_snapshot');
    }
}
