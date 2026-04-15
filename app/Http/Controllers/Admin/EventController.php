<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventParticipant;
use App\Models\LaunchEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(): View
    {
        $events = LaunchEvent::latest()->get();

        return view('admin.events.index', compact('events'));
    }

    public function create(): View
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'              => 'required|string|max:255',
            'title_en'           => 'nullable|string|max:255',
            'description'        => 'nullable|string',
            'description_en'     => 'nullable|string',
            'req_level'          => 'required|integer|min:1|max:150',
            'req_cultivation'    => 'required|integer|min:0|max:32',
            'prize_total_cubi'   => 'required|integer|min:1',
            'prize_winner_count' => 'required|integer|min:4',
            'prize_rank1'        => 'nullable|integer|min:0',
            'prize_rank2'        => 'nullable|integer|min:0',
            'prize_rank3'        => 'nullable|integer|min:0',
            'start_at'           => 'required|date',
            'end_at'             => 'required|date|after:start_at',
        ]);

        $data['status'] = 'draft';

        LaunchEvent::create($data);

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dibuat.');
    }

    public function show(LaunchEvent $event): View
    {
        $participants = $event->participants()
            ->orderByRaw('qualified_at IS NULL, qualified_at ASC')
            ->orderByDesc('level')
            ->orderByDesc('cultivation')
            ->paginate(50);

        $qualifiedCount = $event->participants()->whereNotNull('qualified_at')->count();
        $totalParticipants = $event->participants()->count();

        return view('admin.events.show', compact('event', 'participants', 'qualifiedCount', 'totalParticipants'));
    }

    public function edit(LaunchEvent $event): View
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, LaunchEvent $event)
    {
        $data = $request->validate([
            'title'              => 'required|string|max:255',
            'title_en'           => 'nullable|string|max:255',
            'description'        => 'nullable|string',
            'description_en'     => 'nullable|string',
            'req_level'          => 'required|integer|min:1|max:150',
            'req_cultivation'    => 'required|integer|min:0|max:32',
            'prize_total_cubi'   => 'required|integer|min:1',
            'prize_winner_count' => 'required|integer|min:4',
            'prize_rank1'        => 'nullable|integer|min:0',
            'prize_rank2'        => 'nullable|integer|min:0',
            'prize_rank3'        => 'nullable|integer|min:0',
            'start_at'           => 'required|date',
            'end_at'             => 'required|date|after:start_at',
        ]);

        $event->update($data);

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil diperbarui.');
    }

    public function destroy(LaunchEvent $event)
    {
        if ($event->status === 'active') {
            return back()->with('error', 'Tidak bisa menghapus event yang sedang aktif.');
        }

        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dihapus.');
    }

    /**
     * Toggle event status: draft → active → ended
     */
    public function toggle(LaunchEvent $event)
    {
        if ($event->status === 'draft') {
            $event->update(['status' => 'active']);
            return back()->with('success', 'Event diaktifkan! Sync data akan berjalan otomatis.');
        }

        if ($event->status === 'active') {
            $event->update(['status' => 'ended']);
            return back()->with('success', 'Event diakhiri. Anda bisa distribute hadiah.');
        }

        return back()->with('error', 'Event sudah dalam status ' . $event->status);
    }

    /**
     * Distribute prizes to qualified winners.
     */
    public function distribute(LaunchEvent $event)
    {
        if ($event->status !== 'ended') {
            return back()->with('error', 'Event harus dalam status "ended" untuk distribute hadiah.');
        }

        $winners = $event->qualifiedParticipants()
            ->where('prize_distributed', false)
            ->limit($event->prize_winner_count)
            ->get();

        if ($winners->isEmpty()) {
            return back()->with('error', 'Tidak ada pemenang yang belum menerima hadiah.');
        }

        $distributed = 0;

        foreach ($winners as $index => $participant) {
            $rank = $index + 1;
            $prizeCubi = $event->prizeForRank($rank);
            $cashValue = $prizeCubi * 100; // 1 Cubi = 100 cash units

            // Insert to usecashnow for in-game cubi delivery
            $nextSn = (DB::connection('mysql_game')
                ->table('usecashnow')
                ->where('userid', $participant->user_id)
                ->where('zoneid', 1)
                ->min('sn') ?? 0) - 1;

            $now = now();

            DB::connection('mysql_game')->table('usecashnow')->insert([
                'userid'   => $participant->user_id,
                'zoneid'   => 1,
                'sn'       => $nextSn,
                'aid'      => 1,
                'point'    => 4,
                'cash'     => $cashValue,
                'status'   => 0,
                'creatime' => $now,
            ]);

            DB::table('pw_event_deliveries')->insert([
                'event_id'   => $event->id,
                'user_id'    => $participant->user_id,
                'rank'       => $rank,
                'amount'     => $prizeCubi,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $participant->update(['prize_distributed' => true]);
            $distributed++;
        }

        $event->update(['status' => 'distributed']);

        return back()->with('success', "Hadiah berhasil didistribusikan ke {$distributed} pemenang.");
    }
}
