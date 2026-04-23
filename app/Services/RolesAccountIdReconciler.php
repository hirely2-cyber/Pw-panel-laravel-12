<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Samakan mysql_game.roles.account_id dengan gamedbd GetRole (base.userid).
 * Wajib dipanggil setelah Tomcat api_sync_roles karena import sering isi account_id salah.
 */
class RolesAccountIdReconciler
{
    public function __construct(
        private ?GameDbService $gameDb = null,
    ) {
        $this->gameDb = $gameDb ?? new GameDbService;
    }

    /**
     * @param  \Closure|null  $onMismatch  (int $roleId, int $mysqlAccount, int $gamedbUserId, string $name): void
     * @param  \Closure|null  $afterRow    (int $roleId): void — dipanggil per baris (progress bar, dll)
     * @return array{fixed: int, skipped: int, errors: int, affected_user_ids: int[]}
     */
    public function reconcile(
        int $limit = 20000,
        bool $dryRun = false,
        ?\Closure $onMismatch = null,
        ?\Closure $afterRow = null,
    ): array {
        $fixed = 0;
        $skipped = 0;
        $errors = 0;
        $affectedAccounts = [];

        $rows = DB::connection('mysql_game')
            ->table('roles')
            ->orderBy('role_id')
            ->limit($limit)
            ->get(['role_id', 'account_id']);

        foreach ($rows as $row) {
            $roleId = (int) $row->role_id;
            $mysqlAccount = (int) $row->account_id;

            try {
                Cache::forget("pw.role.{$roleId}");
                $data = $this->gameDb->getRoleData($roleId);

                if (! $data || empty($data['base'])) {
                    $skipped++;

                    continue;
                }

                $gamedbUserId = (int) ($data['base']['userid'] ?? 0);
                if ($gamedbUserId <= 0) {
                    $skipped++;

                    continue;
                }

                if ($gamedbUserId === $mysqlAccount) {
                    continue;
                }

                if ($onMismatch) {
                    $onMismatch(
                        $roleId,
                        $mysqlAccount,
                        $gamedbUserId,
                        (string) ($data['base']['name'] ?? '?')
                    );
                }

                if (! $dryRun) {
                    try {
                        DB::connection('mysql_game')
                            ->table('roles')
                            ->where('role_id', $roleId)
                            ->update(['account_id' => $gamedbUserId]);
                        $fixed++;
                        $affectedAccounts[$mysqlAccount] = true;
                        $affectedAccounts[$gamedbUserId] = true;
                    } catch (\Throwable $e) {
                        Log::warning('RolesAccountIdReconciler: UPDATE failed', [
                            'role_id' => $roleId,
                            'error' => $e->getMessage(),
                        ]);
                        $errors++;
                    }
                } else {
                    $fixed++;
                }
            } finally {
                if ($afterRow) {
                    $afterRow($roleId);
                }
            }
        }

        $affectedUserIds = array_map('intval', array_keys($affectedAccounts));
        if (! $dryRun) {
            $this->forgetCachesForUsers($affectedUserIds);
        }

        return [
            'fixed' => $fixed,
            'skipped' => $skipped,
            'errors' => $errors,
            'affected_user_ids' => $affectedUserIds,
        ];
    }

    public function forgetCachesForUsers(array $userIds): void
    {
        foreach ($userIds as $uid) {
            $uid = (int) $uid;
            if ($uid <= 0) {
                continue;
            }
            Cache::forget("pw.getuser.{$uid}");
            Cache::forget("pw.user.characters.v9.{$uid}");
        }
    }
}
