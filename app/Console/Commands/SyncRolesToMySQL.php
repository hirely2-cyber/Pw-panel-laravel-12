<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
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
        $url = $pwadminUrl . '/role.jsp?action=sqlsync';

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

        return 0;
    }
}
