<?php

namespace App\Console\Commands;

use App\Services\RolesAccountIdReconciler;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Menyelaraskan mysql_game.roles.account_id dengan gamedbd (base.userid per role).
 * Berguna jika sync Tomcat/MySQL pernah mengisi account_id salah — char tidak muncul di user yang benar.
 * Setelah sync di admin/roles, panel otomatis menjalankan logika yang sama (RolesAccountIdReconciler).
 */
class FixRolesAccountFromGamedb extends Command
{
    protected $signature = 'pw:fix-roles-account-id
                            {--dry-run : Hanya tampilkan perbedaan, tidak menulis ke database}
                            {--limit=20000 : Maksimal jumlah baris di tabel roles yang dicek}';

    protected $description = 'Perbaiki kolom account_id di mysql_game.roles dari userid gamedbd (GetRole)';

    public function handle(RolesAccountIdReconciler $reconciler): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $limit = max(1, (int) $this->option('limit'));

        if ($dryRun) {
            $this->warn('Mode DRY-RUN — tidak ada UPDATE ke database.');
        }

        $this->info("Membaca sampai {$limit} baris dari mysql_game.roles ...");

        $roleTableCount = (int) DB::connection('mysql_game')->table('roles')->count();
        $totalRows = min($limit, $roleTableCount);

        $bar = $this->output->createProgressBar($totalRows);
        $bar->start();

        $result = $reconciler->reconcile(
            $limit,
            $dryRun,
            function (int $roleId, int $mysqlAccount, int $gamedbUserId, string $name) use ($dryRun): void {
                $this->newLine();
                $this->line(sprintf(
                    'role_id=%d  account_id MySQL=%d  →  gamedbd userid=%d  (%s)',
                    $roleId,
                    $mysqlAccount,
                    $gamedbUserId,
                    $name
                ));
            },
            function (int $roleId) use ($bar): void {
                $bar->advance();
            }
        );

        $bar->finish();
        $this->newLine(2);

        $this->info("Selesai. Diperbaiki: {$result['fixed']}, dilewati: {$result['skipped']}, error: {$result['errors']}");
        if (! $dryRun && $result['fixed'] > 0) {
            $this->comment('Cache list char (user terdampak) dan GetUser sudah di-flush untuk akun terkait.');
        }
        if ($dryRun) {
            $this->comment('Jalankan tanpa --dry-run untuk menerapkan, lalu: php artisan cache:clear  jika perlu.');
        }

        return $result['errors'] > 0 ? 1 : 0;
    }
}
