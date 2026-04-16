<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventParticipant;
use App\Models\LaunchEvent;
use App\Models\ReferralMilestone;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(Request $request): View
    {
        $tab = $request->query('tab', 'grand_launch');
        $events = LaunchEvent::where('type', $tab)->latest()->get();

        return view('admin.events.index', compact('events', 'tab'));
    }

    public function create(): View
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $type = $request->input('type', 'grand_launch');

        $rules = [
            'type'               => 'required|in:pre_launch,grand_launch',
            'title'              => 'required|string|max:255',
            'title_en'           => 'nullable|string|max:255',
            'description'        => 'nullable|string',
            'description_en'     => 'nullable|string',
            'start_at'           => 'required|date',
            'end_at'             => 'required|date|after:start_at',
        ];

        if ($type === 'grand_launch') {
            $rules += [
                'req_level'          => 'required|integer|min:1|max:150',
                'req_cultivation'    => 'required|integer|min:0|max:32',
                'prize_total_cubi'   => 'required|integer|min:1',
                'prize_winner_count' => 'required|integer|min:4',
                'prize_rank1'        => 'nullable|integer|min:0',
                'prize_rank2'        => 'nullable|integer|min:0',
                'prize_rank3'        => 'nullable|integer|min:0',
            ];
        } else {
            $rules += [
                'referral_req_level' => 'required|integer|min:1|max:150',
                'referral_tiers'     => 'required|array|min:1',
                'referral_tiers.*.count'  => 'required|integer|min:1',
                'referral_tiers.*.reward' => 'required|integer|min:1',
            ];
        }

        $data = $request->validate($rules);
        $data['status'] = 'draft';

        if ($type === 'pre_launch') {
            $data['req_level'] = $data['referral_req_level'];
            $data['req_cultivation'] = 0;
            $data['prize_total_cubi'] = 0;
            $data['prize_winner_count'] = 4;
        }

        LaunchEvent::create($data);

        return redirect()->route('admin.events.index', ['tab' => $type])->with('success', 'Event berhasil dibuat.');
    }

    public function show(LaunchEvent $event): View
    {
        if ($event->isPreLaunch()) {
            // Pre-launch: show referral leaderboard
            $referrers = User::select('users.ID', 'users.name', 'users.referral_code', 'users.creatime')
                ->selectRaw('COUNT(r.ID) as referral_count')
                ->join('users as r', 'r.referred_by', '=', 'users.ID')
                ->groupBy('users.ID', 'users.name', 'users.referral_code', 'users.creatime')
                ->orderByDesc('referral_count')
                ->paginate(50);

            $totalRegistered = User::whereBetween('creatime', [$event->start_at, $event->end_at])->count();
            $totalReferrals = User::whereNotNull('referred_by')
                ->whereBetween('creatime', [$event->start_at, $event->end_at])->count();

            $milestones = $event->referralMilestones()->with('user')->latest()->get();

            return view('admin.events.show-prelaunch', compact('event', 'referrers', 'totalRegistered', 'totalReferrals', 'milestones'));
        }

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
        $rules = [
            'title'              => 'required|string|max:255',
            'title_en'           => 'nullable|string|max:255',
            'description'        => 'nullable|string',
            'description_en'     => 'nullable|string',
            'start_at'           => 'required|date',
            'end_at'             => 'required|date|after:start_at',
        ];

        if ($event->isPreLaunch()) {
            $rules += [
                'referral_req_level' => 'required|integer|min:1|max:150',
                'referral_tiers'     => 'required|array|min:1',
                'referral_tiers.*.count'  => 'required|integer|min:1',
                'referral_tiers.*.reward' => 'required|integer|min:1',
            ];
        } else {
            $rules += [
                'req_level'          => 'required|integer|min:1|max:150',
                'req_cultivation'    => 'required|integer|min:0|max:32',
                'prize_total_cubi'   => 'required|integer|min:1',
                'prize_winner_count' => 'required|integer|min:4',
                'prize_rank1'        => 'nullable|integer|min:0',
                'prize_rank2'        => 'nullable|integer|min:0',
                'prize_rank3'        => 'nullable|integer|min:0',
            ];
        }

        $data = $request->validate($rules);

        if ($event->isPreLaunch()) {
            $data['req_level'] = $data['referral_req_level'];
        }

        $event->update($data);

        return redirect()->route('admin.events.index', ['tab' => $event->type])->with('success', 'Event berhasil diperbarui.');
    }

    public function destroy(LaunchEvent $event)
    {
        if ($event->status === 'active') {
            return back()->with('error', 'Tidak bisa menghapus event yang sedang aktif.');
        }

        $tab = $event->isPreLaunch() ? 'pre_launch' : 'grand_launch';
        $event->delete();

        return redirect()->route('admin.events.index', ['tab' => $tab])->with('success', 'Event berhasil dihapus.');
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

    /**
     * Distribute referral milestone rewards for pre-launch events.
     */
    public function distributeReferrals(LaunchEvent $event)
    {
        if (! $event->isPreLaunch()) {
            return back()->with('error', 'Fitur ini hanya untuk event Pre-Launch.');
        }

        if ($event->status !== 'ended') {
            return back()->with('error', 'Event harus dalam status "ended" untuk distribute.');
        }

        $tiers = $event->referral_tiers ?? [];
        if (empty($tiers)) {
            return back()->with('error', 'Referral tiers belum diatur.');
        }

        // Get all referrers with their referral counts
        $reqLevel = $event->referral_req_level;

        $referrers = User::select('users.ID', 'users.name')
            ->whereExists(function ($q) {
                $q->selectRaw('1')
                    ->from('users as r')
                    ->whereColumn('r.referred_by', 'users.ID');
            })
            ->get();

        // For each referrer, count only QUALIFIED referrals (referred user has char at req level)
        $distributed = 0;

        foreach ($referrers as $referrer) {
            $referredIds = User::where('referred_by', $referrer->ID)->pluck('ID')->toArray();
            if (empty($referredIds)) continue;

            // Count referred users who have at least 1 character at the required level
            $qualifiedCount = DB::connection('mysql_game')
                ->table('roles')
                ->whereIn('account_id', $referredIds)
                ->selectRaw('account_id, MAX(role_level) as max_level')
                ->groupBy('account_id')
                ->havingRaw('MAX(role_level) >= ?', [$reqLevel])
                ->count();

            if ($qualifiedCount < collect($tiers)->min('count')) continue;

            foreach ($tiers as $tier) {
                if ($qualifiedCount >= $tier['count']) {
                    // Check if milestone already claimed
                    $exists = ReferralMilestone::where('event_id', $event->id)
                        ->where('user_id', $referrer->ID)
                        ->where('milestone', $tier['count'])
                        ->exists();

                    if (! $exists) {
                        $cashValue = $tier['reward'] * 100;

                        $nextSn = (DB::connection('mysql_game')
                            ->table('usecashnow')
                            ->where('userid', $referrer->ID)
                            ->where('zoneid', 1)
                            ->min('sn') ?? 0) - 1;

                        DB::connection('mysql_game')->table('usecashnow')->insert([
                            'userid'   => $referrer->ID,
                            'zoneid'   => 1,
                            'sn'       => $nextSn,
                            'aid'      => 1,
                            'point'    => 4,
                            'cash'     => $cashValue,
                            'status'   => 0,
                            'creatime' => now(),
                        ]);

                        ReferralMilestone::create([
                            'event_id'       => $event->id,
                            'user_id'        => $referrer->ID,
                            'milestone'      => $tier['count'],
                            'reward_amount'  => $tier['reward'],
                            'distributed'    => true,
                            'distributed_at' => now(),
                        ]);

                        $distributed++;
                    }
                }
            }
        }

        if ($distributed > 0) {
            $event->update(['status' => 'distributed']);
        }

        return back()->with('success', "Berhasil distribute {$distributed} milestone rewards.");
    }
}
