<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VotePoll;
use App\Models\VoteOption;
use App\Models\DungeonVoteLog;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class DungeonVoteController extends Controller
{
    /** List semua poll */
    public function index(): View
    {
        $polls = VotePoll::withCount('options')
            ->with('options')
            ->latest()
            ->paginate(10);

        return view('admin.dungeon-vote.index', compact('polls'));
    }

    /** Form buat poll baru */
    public function create(): View
    {
        $availableMaps = config('pw_maps', []);
        return view('admin.dungeon-vote.create', compact('availableMaps'));
    }

    /** Simpan poll baru */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title'    => ['required', 'string', 'max:100'],
            'map_ids'  => ['required', 'array', 'min:2'],
            'map_ids.*'=> ['required', 'string'],
        ]);

        $availableMaps = config('pw_maps', []);
        $mapIds = array_unique($request->map_ids);

        DB::transaction(function () use ($request, $availableMaps, $mapIds) {
            $poll = VotePoll::create([
                'title'     => $request->title,
                'is_active' => false,
            ]);

            foreach ($mapIds as $mapId) {
                if (isset($availableMaps[$mapId])) {
                    VoteOption::create([
                        'poll_id'  => $poll->id,
                        'map_id'   => $mapId,
                        'map_name' => $availableMaps[$mapId],
                        'votes'    => 0,
                    ]);
                }
            }
        });

        return redirect()->route('admin.dungeon-vote.index')
            ->with('success', 'Poll voting dungeon berhasil dibuat.');
    }

    /** Aktifkan poll (nonaktifkan yg lain dulu) */
    public function activate(VotePoll $poll): RedirectResponse
    {
        DB::transaction(function () use ($poll) {
            VotePoll::where('is_active', true)->update(['is_active' => false]);
            $poll->update(['is_active' => true, 'closed_at' => null]);
        });

        return back()->with('success', 'Poll "' . $poll->title . '" sekarang aktif.');
    }

    /** Nonaktifkan/tutup poll */
    public function deactivate(VotePoll $poll): RedirectResponse
    {
        $poll->update([
            'is_active' => false,
            'closed_at' => now(),
        ]);

        return back()->with('success', 'Poll "' . $poll->title . '" ditutup.');
    }

    /** Reset semua vote di poll ini */
    public function reset(VotePoll $poll): RedirectResponse
    {
        DB::transaction(function () use ($poll) {
            VoteOption::where('poll_id', $poll->id)->update(['votes' => 0]);
            DungeonVoteLog::where('poll_id', $poll->id)->delete();
        });

        return back()->with('success', 'Semua vote di "' . $poll->title . '" berhasil direset.');
    }

    /** Hapus poll */
    public function destroy(VotePoll $poll): RedirectResponse
    {
        $poll->delete();
        return redirect()->route('admin.dungeon-vote.index')
            ->with('success', 'Poll voting berhasil dihapus.');
    }
}
