<?php

namespace App\Console\Commands;

use App\Services\GameDbService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateTopSultan extends Command
{
    protected $signature = 'pw:update-sultan';
    protected $description = 'Fetch cash_used data from gamedbd and update pw_top_sultan table';

    public function handle(): int
    {
        $this->info('Fetching Top Sultan data...');

        // Get GM/Admin user IDs to exclude
        $gmIds = DB::connection('mysql_game')->table('auth')->distinct()->pluck('userid')->toArray();
        $adminIds = DB::table('users')->where('role', 'admin')->pluck('ID')->toArray();
        $excludeIds = array_unique(array_merge($gmIds, $adminIds));

        $this->info('Excluding ' . count($excludeIds) . ' GM/Admin accounts');

        // Get top 100 users by total top-up from usecashlog (candidates)
        $candidates = DB::connection('mysql_game')
            ->table('usecashlog')
            ->where('status', 4)
            ->whereNotIn('userid', $excludeIds)
            ->select('userid')
            ->selectRaw('SUM(cash) as total_added')
            ->groupBy('userid')
            ->orderByDesc('total_added')
            ->limit(100)
            ->get();

        $this->info("Found {$candidates->count()} candidates, fetching from gamedbd...");

        $gameDb = new GameDbService();
        $rows = [];
        $bar = $this->output->createProgressBar($candidates->count());

        foreach ($candidates as $c) {
            $data = $gameDb->getUserCash($c->userid, false); // no cache
            if ($data && $data['cash_used'] > 0) {
                // Get first character name (oldest role by role_id)
                $charName = DB::connection('mysql_game')
                    ->table('roles')
                    ->where('account_id', $c->userid)
                    ->orderBy('role_id')
                    ->value('role_name');

                // Fallback: masked username if no character found
                if (!$charName) {
                    $username = DB::connection('mysql_game')
                        ->table('users')
                        ->where('ID', $c->userid)
                        ->value('name') ?? 'Unknown';
                    $len = mb_strlen($username);
                    $charName = $len <= 3
                        ? str_repeat('*', $len)
                        : mb_substr($username, 0, 2) . str_repeat('*', $len - 3) . mb_substr($username, -1);
                }

                $rows[] = [
                    'userid'         => $c->userid,
                    'character_name' => $charName ?? 'Unknown',
                    'cash_used'      => $data['cash_used'],
                    'cash_add'       => $data['cash_add'],
                    'updated_at'     => now(),
                ];
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        // Sort by cash_used desc, keep top 25
        usort($rows, fn($a, $b) => $b['cash_used'] <=> $a['cash_used']);
        $rows = array_slice($rows, 0, 25);

        // Truncate and insert
        DB::table('pw_top_sultan')->truncate();
        foreach ($rows as $row) {
            DB::table('pw_top_sultan')->insert($row);
        }

        $this->info("Updated pw_top_sultan with " . count($rows) . " entries.");
        Log::info('pw:update-sultan completed with ' . count($rows) . ' entries.');

        return self::SUCCESS;
    }
}
