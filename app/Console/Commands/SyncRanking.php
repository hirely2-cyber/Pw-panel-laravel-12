<?php

/*
 * @author   Wahyu Suhandi <andietz.orion@gmail.com>
 * @project  Perfect World Panel
 * Sync ranking data dari game DB (roles table) ke pw_ranking_players dan pw_ranking_factions.
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class SyncRanking extends Command
{
    protected $signature   = 'pw:sync-ranking';
    protected $description = 'Sync ranking players & factions dari game DB (tabel roles)';

    // Mapping role_occupation → nama class PW
    private const CLASS_MAP = [
        0 => 'Blademaster',
        1 => 'Wizard',
        2 => 'Psychic',
        3 => 'Venomancer',
        4 => 'Barbarian',
        5 => 'Assassin',
        6 => 'Archer',
        7 => 'Cleric',
        8 => 'Seeker',
        9 => 'Mystic',
        10 => 'Duskblade',
        11 => 'Stormbringer',
    ];

    public function handle(): int
    {
        $this->info('Memulai sync ranking...');

        $this->syncPlayers();
        $this->syncFactions();

        // Hapus cache ranking supaya langsung fresh
        Cache::forget('ranking_preview');
        Cache::forget('ranking_players');
        Cache::forget('ranking_factions');

        $this->info('Sync ranking selesai.');
        return self::SUCCESS;
    }

    private function syncPlayers(): void
    {
        $this->info('Sync pw_ranking_players...');

        // Ambil semua karakter dari game DB, exclude GM accounts (auth table)
        $gmIds = DB::connection('mysql_game')->table('auth')->distinct()->pluck('userid')->toArray();

        $roles = DB::connection('mysql_game')
            ->table('roles')
            ->whereNotIn('account_id', $gmIds)
            ->get();

        $now  = now();
        $rank = 1;

        // Sort by pvp_kills desc (kill terbanyak = rank tertinggi)
        $sorted = $roles->sortByDesc('pvp_kills')->values();

        $upserts = [];
        foreach ($sorted as $r) {
            $className = self::CLASS_MAP[$r->role_occupation] ?? 'Unknown';

            // Hitung territory dari faction_domains (comma separated IDs, ignore '0')
            $domains = array_filter(
                explode(',', $r->faction_domains ?? ''),
                fn($d) => trim($d) !== '' && trim($d) !== '0'
            );

            $upserts[] = [
                'user_id'        => $r->account_id,
                'character_name' => $r->role_name,
                'class'          => $className,
                'level'          => $r->role_level,
                'exp'            => (int) $r->role_level * 1000,
                'faction_name'   => ($r->faction_name && trim($r->faction_name) !== '') ? $r->faction_name : null,
                'pk_points'      => $r->pvp_kills,
                'rank'           => $rank++,
                'last_synced_at' => $now,
                'created_at'     => $now,
                'updated_at'     => $now,
            ];
        }

        if (empty($upserts)) {
            $this->warn('Tidak ada data karakter ditemukan.');
            return;
        }

        // Truncate + insert ulang (lebih simpel dari upsert per baris)
        DB::table('pw_ranking_players')->truncate();
        foreach (array_chunk($upserts, 500) as $chunk) {
            DB::table('pw_ranking_players')->insert($chunk);
        }

        $this->info('  → ' . count($upserts) . ' karakter diimport.');
    }

    private function syncFactions(): void
    {
        $this->info('Sync pw_ranking_factions...');

        // Load nama faction dari tabel lookup (diisi manual oleh admin)
        $factionNames = DB::table('pw_faction_names')
            ->pluck('name', 'faction_id');

        // Group by faction_id, ambil leader (role_faction_rank=2) dan domains
        $factions = DB::connection('mysql_game')
            ->table('roles')
            ->select(
                'faction_id',
                DB::raw('COUNT(*) as members_count'),
                DB::raw('MAX(CASE WHEN role_faction_rank = 2 THEN role_name END) as leader_name'),
                DB::raw('MAX(faction_domains) as faction_domains')
            )
            ->where('faction_id', '>', 0)
            ->groupBy('faction_id')
            ->get();

        if ($factions->isEmpty()) {
            $this->warn('Tidak ada data faction ditemukan.');
            return;
        }

        $now  = now();
        $rows = [];

        foreach ($factions as $f) {
            // faction_domains format: "1;2;7;14" (titik koma)
            $domains = array_filter(
                explode(';', $f->faction_domains ?? ''),
                fn($d) => trim($d) !== '' && trim($d) !== '0'
            );

            $territoryCount = count($domains);

            // Prioritas nama: lookup table > fallback "Faction #ID"
            $name = $factionNames->get($f->faction_id)
                 ?? 'Faction #' . $f->faction_id;

            $rows[] = [
                'faction_id'      => $f->faction_id,
                'name'            => $name,
                'leader_name'     => $f->leader_name,
                'members_count'   => $f->members_count,
                'territory_count' => $territoryCount,
                'last_synced_at'  => $now,
                'created_at'      => $now,
                'updated_at'      => $now,
            ];
        }

        // Urut: terbanyak wilayah dulu, baru terbanyak member
        usort($rows, fn($a, $b) =>
            $b['territory_count'] <=> $a['territory_count']
            ?: $b['members_count'] <=> $a['members_count']
        );

        // Assign rank setelah sorting
        foreach ($rows as $i => &$row) {
            $row['rank'] = $i + 1;
        }
        unset($row);

        DB::table('pw_ranking_factions')->truncate();
        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('pw_ranking_factions')->insert($chunk);
        }

        $this->info('  → ' . count($rows) . ' faction diimport.');
    }
}
