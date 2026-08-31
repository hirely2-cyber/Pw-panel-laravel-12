<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncRolesToMySQL extends Command
{
    protected $signature = 'pw:sync-roles';
    protected $description = 'Sync all characters from gamedbd to MySQL roles table via pwAdmin';

    public function handle(): int
    {
        $this->info('Triggering role sync via pwAdmin...');

        $pwadminUrl = rtrim(config('pw-config.pwadmin_url', 'http://localhost:8080/pwAdmin'), '/');
        $token = (string) config('pw-api.pwadmin_api_token', 'pw_panel_sync_2026');
        $url = $pwadminUrl . '/api_sync_roles.jsp?token=' . urlencode($token);

        $this->info("Calling: {$url}");

        $beforeCount = DB::connection('mysql_game')->table('roles')->count();

        try {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 120,
                CURLOPT_CONNECTTIMEOUT => 10,
            ]);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error) {
                $this->error("Connection failed: {$error}");
                return 1;
            }

            if ($httpCode !== 200) {
                $this->error("pwAdmin returned HTTP {$httpCode}");
                return 1;
            }
        } catch (\Throwable $e) {
            $this->error('Failed to call pwAdmin: ' . $e->getMessage());
            return 1;
        }

        $afterCount = DB::connection('mysql_game')->table('roles')->count();
        $this->info("Sync complete. Roles in database: {$afterCount} (was {$beforeCount}).");

        // Flush per-user character cache so panel pages show fresh level/cultivation
        // immediately after the next request (instead of waiting up to TTL).
        try {
            $userIds = DB::connection('mysql')->table('users')->pluck('ID');
            $cleared = 0;
            foreach ($userIds as $uid) {
                if (Cache::forget('pw.user.characters.v10.' . $uid)) {
                    $cleared++;
                }
            }
            $this->info("Cleared character cache for {$cleared} user(s).");
        } catch (\Throwable $e) {
            Log::warning('SyncRolesToMySQL cache flush failed: ' . $e->getMessage());
        }

        // Push fresh roles table to remote panel VPS (if configured) so the public
        // panel/website also sees up-to-date level/cultivation/faction data.
        $this->pushRolesToRemotePanel();

        return 0;
    }

    /**
     * Dump local game `roles` table and import it on the panel VPS so the
     * public website reflects the latest character data. Configured via env:
     *   PANEL_SYNC_ENABLED=true
     *   PANEL_SYNC_SSH=root@134.209.108.181
     *   PANEL_SYNC_REMOTE_PATH=/www/wwwroot/panel-staging.pvesea.com
     */
    private function pushRolesToRemotePanel(): void
    {
        if (! filter_var(env('PANEL_SYNC_ENABLED', false), FILTER_VALIDATE_BOOLEAN)) {
            return;
        }

        $sshTarget  = (string) env('PANEL_SYNC_SSH', '');
        $remotePath = (string) env('PANEL_SYNC_REMOTE_PATH', '');
        if ($sshTarget === '' || $remotePath === '') {
            $this->warn('PANEL_SYNC_SSH or PANEL_SYNC_REMOTE_PATH not set; skipping remote push.');
            return;
        }

        $cfg = config('database.connections.mysql_game');
        $host = (string) ($cfg['host'] ?? '127.0.0.1');
        $port = (int) ($cfg['port'] ?? 3306);
        $db   = (string) ($cfg['database'] ?? 'pwdb');
        $user = (string) ($cfg['username'] ?? '');
        $pass = (string) ($cfg['password'] ?? '');

        $dumpFile = storage_path('app/roles_sync_' . date('Ymd_His') . '.sql');

        // Dump only roles table (small + critical) using --single-transaction for safety.
        $cmd = 'mysqldump --single-transaction --skip-lock-tables -h' . escapeshellarg($host)
            . ' -P' . (int) $port
            . ' -u' . escapeshellarg($user)
            . ' -p' . escapeshellarg($pass)
            . ' ' . escapeshellarg($db) . ' roles'
            . ' > ' . escapeshellarg($dumpFile) . ' 2>&1';

        exec($cmd, $out, $rc);
        if ($rc !== 0 || ! is_file($dumpFile) || filesize($dumpFile) === 0) {
            $this->error('mysqldump failed: ' . implode("\n", $out));
            @unlink($dumpFile);
            return;
        }

        // Pipe the dump through SSH into remote MySQL using remote .env creds.
        // Parse PW_GAME_DB_* vars from remote .env (strip CRLF) without sourcing whole file.
        $remoteScript = sprintf(
            'cd %s && '
            . 'eval "$(grep -E \'^PW_GAME_DB_(HOST|PORT|USERNAME|PASSWORD|DATABASE)=\' .env | tr -d \'\\r\')" && '
            . 'mysql -h"$PW_GAME_DB_HOST" -P"$PW_GAME_DB_PORT" -u"$PW_GAME_DB_USERNAME" -p"$PW_GAME_DB_PASSWORD" "$PW_GAME_DB_DATABASE" && '
            . 'php artisan cache:clear > /dev/null 2>&1',
            escapeshellarg($remotePath)
        );

        $sshCmd = 'cat ' . escapeshellarg($dumpFile)
            . ' | ssh -o ConnectTimeout=10 -o StrictHostKeyChecking=no '
            . escapeshellarg($sshTarget)
            . ' ' . escapeshellarg($remoteScript)
            . ' 2>&1';

        exec($sshCmd, $out2, $rc2);
        @unlink($dumpFile);

        if ($rc2 !== 0) {
            $this->error('Remote roles push failed: ' . implode("\n", $out2));
            return;
        }

        $this->info('Pushed roles table to remote panel and cleared remote cache.');
    }
}
