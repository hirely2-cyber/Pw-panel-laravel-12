<?php

/*
 * @author   Wahyu Suhandi <andietz.orion@gmail.com>
 * @link     https://wa.me/6208118719377
 * @project  Perfect World Panel
 * @version  2.0.0
 * @license  MIT
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RankingController extends Controller
{
    public function index(): View
    {
        $players  = \App\Models\RankingPlayer::orderBy('pk_points', 'desc')->orderBy('level', 'desc')->take(100)->get();
        $factions = \App\Models\RankingFaction::orderBy('rank', 'asc')->take(50)->get();

        // Ambil semua faction_id unik dari game DB beserta jumlah member, leader, dan territory
        $gameFactions = DB::connection('mysql_game')
            ->table('roles')
            ->select(
                'faction_id',
                DB::raw('COUNT(*) as members_count'),
                DB::raw('MAX(CASE WHEN role_faction_rank = 2 THEN role_name END) as leader_name'),
                DB::raw('MAX(faction_domains) as faction_domains')
            )
            ->where('faction_id', '>', 0)
            ->groupBy('faction_id')
            ->orderByDesc('members_count')
            ->take(100)
            ->get()
            ->map(function ($f) {
                $domains = array_filter(
                    explode(';', $f->faction_domains ?? ''),
                    fn($d) => trim($d) !== '' && trim($d) !== '0'
                );
                $f->territory_count = count($domains);
                return $f;
            });

        // Nama yang sudah disimpan
        $savedNames = DB::table('pw_faction_names')->pluck('name', 'faction_id');

        return view('admin.ranking.index', compact('players', 'factions', 'gameFactions', 'savedNames'));
    }

    /**
     * GM read-only ranking view (no sync/edit).
     */
    public function gmView(): View
    {
        $players  = \App\Models\RankingPlayer::orderBy('pk_points', 'desc')->orderBy('level', 'desc')->take(100)->get();
        $factions = \App\Models\RankingFaction::orderBy('rank', 'asc')->take(50)->get();

        return view('gm.ranking', compact('players', 'factions'));
    }

    public function saveFactionName(Request $request): RedirectResponse
    {
        $request->validate([
            'faction_id' => 'required|integer|min:1',
            'name'       => 'required|string|max:64',
        ]);

        DB::table('pw_faction_names')->upsert(
            ['faction_id' => $request->faction_id, 'name' => $request->name, 'created_at' => now(), 'updated_at' => now()],
            ['faction_id'],
            ['name', 'updated_at']
        );

        // Re-sync ranking so name takes effect immediately
        Artisan::call('pw:sync-ranking');
        Cache::forget('ranking_factions');

        return back()->with('success', 'Nama faction berhasil disimpan dan ranking di-sync.');
    }

    public function refresh(): RedirectResponse
    {
        Artisan::call('pw:sync-ranking');
        Cache::forget('ranking_players');
        Cache::forget('ranking_factions');
        Cache::forget('ranking_preview');

        return back()->with('success', 'Ranking berhasil di-sync dari game DB.');
    }
}
