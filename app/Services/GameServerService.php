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
     * Get the number of online players.
     *
     * Priority:
     *  1. Query the game DB `roles` table for online characters (IsOnline = 1).
     *  2. If game DB is not configured / query fails → return PW_FAKE_ONLINE env value.
     *
     * Result is cached for 60 seconds.
     */
    public static function onlineCount(): int
    {
        return (int) Cache::remember('pw.server.online_count', 60, function () {
            try {
                // PW game DB table: roles (standard PW 1.5.x structure)
                // Column: IsOnline (tinyint 1 = online, 0 = offline)
                $count = DB::connection('mysql_game')
                    ->table(env('PW_GAME_ROLES_TABLE', 'roles'))
                    ->where(env('PW_GAME_ONLINE_COLUMN', 'IsOnline'), 1)
                    ->count();

                return max(0, (int) $count);
            } catch (\Throwable $e) {
                Log::debug("GameServerService::onlineCount DB error: {$e->getMessage()}");
            }

            // Fallback: value set in .env (0 = hide, or set a static number)
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
     * Flush all server stats caches.
     */
    public static function flushCache(): void
    {
        Cache::forget('pw.server.online');
        Cache::forget('pw.server.online_count');
        Cache::forget('pw.server.account_count');
    }
}
