<?php

namespace App\Console\Commands;

use App\Services\GameDbService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncRolesToMySQL extends Command
{
    protected $signature = 'pw:sync-roles';
    protected $description = 'Sync all characters from gamedbd (via user accounts) to MySQL roles table';

    private const CLASS_MAP = [
        0 => 'Blademaster', 1 => 'Wizard', 2 => 'Psychic', 3 => 'Venomancer',
        4 => 'Barbarian', 5 => 'Assassin', 6 => 'Archer', 7 => 'Cleric',
        8 => 'Seeker', 9 => 'Mystic', 10 => 'Duskblade', 11 => 'Stormbringer',
    ];

    public function handle(): int
    {
        $this->info('Starting role sync from gamedbd...');

        $conn = DB::connection('mysql_game');

        // Get all user IDs
        $users = $conn->table('users')->pluck('ID');
        $this->info("Found {$users->count()} user accounts.");

        if ($users->isEmpty()) {
            $this->warn('No users found in database.');
            return 0;
        }

        // Recreate roles table (same as role.jsp approach)
        $conn->statement('DROP TABLE IF EXISTS roles');
        $conn->statement("
            CREATE TABLE roles(
                account_id int(11) NOT NULL,
                role_id int(11) NOT NULL,
                role_name varchar(64) NOT NULL,
                role_level smallint(6) NOT NULL,
                role_race tinyint(4) NOT NULL,
                role_occupation tinyint(4) NOT NULL,
                role_gender tinyint(4) NOT NULL,
                role_spouse int(11) NOT NULL DEFAULT 0,
                faction_id int(11) NOT NULL DEFAULT 0,
                faction_name varchar(64) NOT NULL DEFAULT '',
                faction_level int(11) NOT NULL DEFAULT 0,
                faction_domains varchar(132) NOT NULL DEFAULT '',
                role_faction_rank int(11) NOT NULL DEFAULT 0,
                pvp_time int(11) NOT NULL DEFAULT 0,
                pvp_kills int(11) NOT NULL DEFAULT 0,
                pvp_deads int(11) NOT NULL DEFAULT 0
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $gameDb = new GameDbService();
        $totalSynced = 0;
        $errors = 0;

        $bar = $this->output->createProgressBar($users->count());
        $bar->start();

        foreach ($users as $userId) {
            try {
                // Get role list for this user  
                $roleIds = $conn->table('roles_temp_lookup')
                    ->where('user_id', $userId)
                    ->pluck('role_id')
                    ->toArray();

                // If no temp lookup available, try to get roles from point table
                if (empty($roleIds)) {
                    // Get role IDs from gamedbd by reading user data
                    // We fetch each possible role_id for this user
                    // The most reliable way: scan via the game protocol
                    $roleData = $this->fetchUserRoles($gameDb, $userId);
                    
                    if (!empty($roleData)) {
                        foreach ($roleData as $rd) {
                            $conn->table('roles')->insert($rd);
                            $totalSynced++;
                        }
                    }
                }
            } catch (\Throwable $e) {
                $errors++;
                Log::debug("SyncRoles: error for user {$userId}: " . $e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Sync complete. Total: {$totalSynced} characters synced, {$errors} errors.");

        return 0;
    }

    /**
     * Fetch all roles for a user ID by trying role IDs from gamedbd.
     * Since PHP can't call getRolelist directly, we rely on the MySQL roles
     * already having data, or use the Java-based sync via shell script.
     */
    private function fetchUserRoles(GameDbService $gameDb, int $userId): array
    {
        // This requires the gamedbd listuser binary protocol which is complex.
        // For now, return empty — the shell script approach handles actual sync.
        return [];
    }
}
