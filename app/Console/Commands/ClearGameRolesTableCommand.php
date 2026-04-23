<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

/**
 * Kosongkan tabel MySQL `roles` (koneksi mysql_game) sebelum sync ulang dari admin/roles (Tomcat).
 * Data diisi kembali oleh api_sync_roles — jangan jalan bila game DB tidak punya backup/sumber yang jelas.
 */
class ClearGameRolesTableCommand extends Command
{
    protected $signature = 'pw:clear-game-roles
                            {--force : Tanpa prompt (hati-hati: automation/production)}';

    protected $description = 'Hapus semua baris di mysql_game.roles (pola: kosong dulu, lalu Sync Roles di admin)';

    public function handle(): int
    {
        if (! $this->option('force')) {
            if (! $this->confirm('Ini akan MENGHAPUS SEMUA baris di tabel `roles` (mysql_game). Lanjut?', false)) {
                $this->warn('Dibatalkan.');

                return 1;
            }
        }

        $conn = 'mysql_game';
        $countBefore = (int) DB::connection($conn)->table('roles')->count();
        $this->info("Baris sebelum: {$countBefore}");

        try {
            Schema::connection($conn)->disableForeignKeyConstraints();
            try {
                DB::connection($conn)->unprepared('TRUNCATE TABLE `roles`');
            } finally {
                Schema::connection($conn)->enableForeignKeyConstraints();
            }
        } catch (\Throwable $e) {
            Log::notice('TRUNCATE roles fallback ke DELETE: ' . $e->getMessage());
            $deleted = DB::connection($conn)->table('roles')->delete();
            $this->info("DELETE selesai. Baris dihapus: {$deleted}.");
        }

        $countAfter = (int) DB::connection($conn)->table('roles')->count();
        if ($countAfter > 0) {
            $this->error("Tabel belum benar-benar kosong (tersisa {$countAfter} baris). Cek koneksi / kunci.");

            return 1;
        }

        Log::warning('pw:clear-game-roles — tabel roles dikosongkan (sync ulang dari Tomcat)');

        $this->line('');
        $this->info('Tabel `roles` sudah kosong.');
        $this->comment('Isi ulang: Admin → Roles → Sync, atau CLI: `php artisan pw:sync-roles`');
        $this->comment('Lalu: `php artisan cache:clear`  dan bila perlu: `php artisan pw:fix-roles-account-id`');

        return 0;
    }
}
