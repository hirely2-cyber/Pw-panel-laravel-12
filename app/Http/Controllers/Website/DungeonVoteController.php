<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\VotePoll;
use App\Models\VoteOption;
use App\Models\DungeonVoteLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class DungeonVoteController extends Controller
{
    /** Halaman publik dungeon voting */
    public function index(): View
    {
        $poll = VotePoll::where('is_active', true)
            ->with(['options' => fn($q) => $q->orderByDesc('votes')])
            ->first();

        $hasVoted = false;
        $votedMapId = null;

        if ($poll) {
            $ip = request()->ip();
            $log = DungeonVoteLog::where('poll_id', $poll->id)
                ->where('voter_ip', $ip)
                ->first();
            $hasVoted   = (bool) $log;
            $votedMapId = $log?->map_id;
        }

        // Hasil poll terakhir (sudah ditutup) jika tidak ada yang aktif
        $lastPoll = null;
        if (! $poll) {
            $lastPoll = VotePoll::where('is_active', false)
                ->whereNotNull('closed_at')
                ->with(['options' => fn($q) => $q->orderByDesc('votes')])
                ->latest('closed_at')
                ->first();
        }

        return view('website.dungeon-vote', compact('poll', 'hasVoted', 'votedMapId', 'lastPoll'));
    }

    /** Submit vote (AJAX + fallback POST) */
    public function vote(Request $request): JsonResponse
    {
        $request->validate([
            'poll_id' => ['required', 'integer'],
            'map_id'  => ['required', 'string', 'max:20'],
        ]);

        $poll = VotePoll::where('id', $request->poll_id)
            ->where('is_active', true)
            ->firstOrFail();

        $option = VoteOption::where('poll_id', $poll->id)
            ->where('map_id', $request->map_id)
            ->firstOrFail();

        $ip = $request->ip();

        // Cegah double vote per IP
        $alreadyVoted = DungeonVoteLog::where('poll_id', $poll->id)
            ->where('voter_ip', $ip)
            ->exists();

        if ($alreadyVoted) {
            return response()->json([
                'success' => false,
                'message' => 'Kamu sudah pernah vote di poll ini.',
            ], 422);
        }

        DB::transaction(function () use ($poll, $option, $ip) {
            $option->increment('votes');
            DungeonVoteLog::create([
                'poll_id'  => $poll->id,
                'voter_ip' => $ip,
                'map_id'   => $option->map_id,
            ]);
        });

        // Return updated options data
        $poll->refresh();
        $options = VoteOption::where('poll_id', $poll->id)
            ->orderByDesc('votes')
            ->get(['map_id', 'map_name', 'votes']);

        return response()->json([
            'success' => true,
            'message' => 'Vote kamu berhasil dicatat!',
            'voted_map_id' => $option->map_id,
            'options' => $options,
            'total_votes' => $options->sum('votes'),
        ]);
    }
}
