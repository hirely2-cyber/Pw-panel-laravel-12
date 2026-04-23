<?php

namespace App\Console\Commands;

use App\Models\EventRegisterDelivery;
use App\Models\LaunchEvent;
use App\Models\ReferralMilestone;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Otomatis distribute reward event ke user yang sudah memenuhi syarat.
 *
 * Register reward (100 Cubi): user harus punya karakter >= register_req_level (81).
 * Referral milestone:         referred user harus punya karakter >= referral_req_level (100).
 *
 * Semua Cubi dikirim dengan point=4 (Event) → Cubi Monitor mencatat sebagai sumber "Event".
 * Idempoten: user yang sudah terima tidak akan terima lagi.
 */
class AutoDistributeEventRewards extends Command
{
    protected $signature   = 'pw:auto-distribute-event {--event= : ID event spesifik (kosong = semua aktif)}';
    protected $description = 'Auto-distribute event rewards ke user yang memenuhi syarat level';

    public function handle(): int
    {
        $eventId = $this->option('event');

        $query = LaunchEvent::where('type', 'pre_launch')
            ->whereIn('status', ['active', 'ended'])
            ->where('start_at', '<=', now());

        if ($eventId) {
            $query->where('id', (int) $eventId);
        }

        $events = $query->get();

        if ($events->isEmpty()) {
            $this->info('Tidak ada pre_launch event aktif.');
            return 0;
        }

        foreach ($events as $event) {
            $this->line("--- Event: {$event->title} (ID {$event->id}) ---");
            $this->distributeRegisterRewards($event);
            $this->distributeReferralMilestones($event);
        }

        return 0;
    }

    /**
     * Kirim register_rewards ke user yang belum terima dan sudah reach level syarat.
     * Syarat: punya karakter dengan role_level >= register_req_level.
     */
    private function distributeRegisterRewards(LaunchEvent $event): void
    {
        $rewards   = $event->register_rewards ?? [];
        $reqLevel  = (int) ($event->register_req_level ?? 50);

        if (empty($rewards)) {
            $this->line('  [Register] Tidak ada register_rewards dikonfigurasi.');
            return;
        }

        // User yang sudah dapat reward ini
        $alreadyDistributed = EventRegisterDelivery::where('event_id', $event->id)
            ->pluck('user_id')
            ->toArray();

        // User yang terdaftar dalam rentang event
        $users = User::whereBetween('creatime', [$event->start_at, $event->end_at ?? now()->addYears(10)])
            ->when(!empty($alreadyDistributed), fn ($q) => $q->whereNotIn('ID', $alreadyDistributed))
            ->select('ID', 'name')
            ->get();

        if ($users->isEmpty()) {
            $this->line('  [Register] Tidak ada user baru yang memenuhi syarat.');
            return;
        }

        $userIds = $users->pluck('ID')->toArray();

        // Cek karakter yang sudah reach level syarat
        $qualifiedMap = DB::connection('mysql_game')
            ->table('roles')
            ->whereIn('account_id', $userIds)
            ->selectRaw('account_id, MAX(role_level) as max_level')
            ->groupBy('account_id')
            ->havingRaw('MAX(role_level) >= ?', [$reqLevel])
            ->pluck('max_level', 'account_id')
            ->toArray();

        $distributed = 0;
        $skipped     = 0;

        foreach ($users as $user) {
            if (!isset($qualifiedMap[$user->ID])) {
                $skipped++;
                continue;
            }

            $charLevel = (int) $qualifiedMap[$user->ID];

            try {
                DB::transaction(function () use ($event, $user, $rewards, $charLevel) {
                    $baseSn = (int)(DB::connection('mysql_game')
                        ->table('usecashnow')
                        ->where('userid', $user->ID)
                        ->where('zoneid', 1)
                        ->min('sn') ?? 0);

                    foreach ($rewards as $i => $reward) {
                        $cubiAmount = (int) $reward['amount'];
                        $cashValue  = $cubiAmount * 100;
                        $sn         = $baseSn - ($i + 1);

                        // Antri ke game dengan point=4 (Event)
                        DB::connection('mysql_game')->table('usecashnow')->insert([
                            'userid'   => $user->ID,
                            'zoneid'   => 1,
                            'sn'       => $sn,
                            'aid'      => 1,
                            'point'    => 4,
                            'cash'     => $cashValue,
                            'status'   => 0,
                            'creatime' => now(),
                        ]);

                        // Catat di pw_event_deliveries → Cubi Monitor: sumber "Event"
                        DB::table('pw_event_deliveries')->insert([
                            'event_id'   => $event->id,
                            'user_id'    => $user->ID,
                            'rank'       => 0,
                            'amount'     => $cubiAmount,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }

                    // Tandai sudah didistribusi agar tidak dikirim ulang
                    EventRegisterDelivery::create([
                        'event_id'       => $event->id,
                        'user_id'        => $user->ID,
                        'char_level'     => $charLevel,
                        'distributed'    => true,
                        'distributed_at' => now(),
                    ]);
                });

                $this->info("  [Register] ✓ User #{$user->ID} ({$user->name}) — char level {$charLevel} — reward dikirim.");
                $distributed++;

            } catch (\Throwable $e) {
                $this->error("  [Register] ✗ User #{$user->ID}: {$e->getMessage()}");
                Log::error('AutoDistributeEvent register reward failed', [
                    'event_id' => $event->id,
                    'user_id'  => $user->ID,
                    'error'    => $e->getMessage(),
                ]);
            }
        }

        $this->line("  [Register] Selesai: {$distributed} distribute, {$skipped} skip (belum reach level {$reqLevel}).");
    }

    /**
     * Kirim referral milestone rewards ke referrer yang referred user-nya sudah reach level syarat.
     * Syarat: referred user punya karakter dengan role_level >= referral_req_level.
     */
    private function distributeReferralMilestones(LaunchEvent $event): void
    {
        $tiers    = $event->referral_tiers ?? [];
        $reqLevel = (int) ($event->referral_req_level ?? 50);

        if (empty($tiers)) {
            $this->line('  [Referral] Tidak ada referral_tiers dikonfigurasi.');
            return;
        }

        // Semua user yang punya referral yang terdaftar selama event
        $referrers = User::select('users.ID', 'users.name')
            ->whereExists(function ($q) use ($event) {
                $q->selectRaw('1')
                    ->from('users as r')
                    ->whereColumn('r.referred_by', 'users.ID')
                    ->whereBetween('r.creatime', [$event->start_at, $event->end_at ?? now()->addYears(10)]);
            })
            ->get();

        if ($referrers->isEmpty()) {
            $this->line('  [Referral] Tidak ada referrer ditemukan.');
            return;
        }

        $totalDistributed = 0;

        foreach ($referrers as $referrer) {
            // Referred users yang daftar saat event
            $referredIds = User::where('referred_by', $referrer->ID)
                ->whereBetween('creatime', [$event->start_at, $event->end_at ?? now()->addYears(10)])
                ->pluck('ID')
                ->toArray();

            if (empty($referredIds)) continue;

            // Hitung berapa referred users yang sudah punya char >= referral_req_level
            $qualifiedCount = (int) DB::connection('mysql_game')
                ->table('roles')
                ->whereIn('account_id', $referredIds)
                ->selectRaw('account_id, MAX(role_level) as max_level')
                ->groupBy('account_id')
                ->havingRaw('MAX(role_level) >= ?', [$reqLevel])
                ->count();

            if ($qualifiedCount === 0) continue;

            $minTierCount = collect($tiers)->min('count');
            if ($qualifiedCount < $minTierCount) continue;

            // Hitung SN dasar sekali untuk referrer ini
            $baseSn   = (int)(DB::connection('mysql_game')
                ->table('usecashnow')
                ->where('userid', $referrer->ID)
                ->where('zoneid', 1)
                ->min('sn') ?? 0);
            $snOffset = 0;

            foreach ($tiers as $tier) {
                $tierCount  = (int) $tier['count'];
                $tierReward = (int) $tier['reward'];

                if ($qualifiedCount < $tierCount) continue;

                // Cek apakah milestone ini sudah pernah dikirim
                $exists = ReferralMilestone::where('event_id', $event->id)
                    ->where('user_id', $referrer->ID)
                    ->where('milestone', $tierCount)
                    ->exists();

                if ($exists) continue;

                $cashValue = $tierReward * 100;
                $snOffset--;
                $sn = $baseSn + $snOffset;

                try {
                    DB::transaction(function () use ($event, $referrer, $sn, $cashValue, $tierReward, $tierCount) {
                        // Antri ke game dengan point=4 (Event)
                        DB::connection('mysql_game')->table('usecashnow')->insert([
                            'userid'   => $referrer->ID,
                            'zoneid'   => 1,
                            'sn'       => $sn,
                            'aid'      => 1,
                            'point'    => 4,
                            'cash'     => $cashValue,
                            'status'   => 0,
                            'creatime' => now(),
                        ]);

                        // Catat di pw_event_deliveries → Cubi Monitor: sumber "Event"
                        DB::table('pw_event_deliveries')->insert([
                            'event_id'   => $event->id,
                            'user_id'    => $referrer->ID,
                            'rank'       => $tierCount,
                            'amount'     => $tierReward,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        // Tandai milestone ini sudah didistribusi
                        ReferralMilestone::create([
                            'event_id'       => $event->id,
                            'user_id'        => $referrer->ID,
                            'milestone'      => $tierCount,
                            'reward_amount'  => $tierReward,
                            'distributed'    => true,
                            'distributed_at' => now(),
                        ]);
                    });

                    $this->info("  [Referral] ✓ User #{$referrer->ID} ({$referrer->name}) — milestone {$tierCount} referral — {$tierReward} Cubi dikirim.");
                    $totalDistributed++;

                } catch (\Throwable $e) {
                    $this->error("  [Referral] ✗ User #{$referrer->ID} milestone {$tierCount}: {$e->getMessage()}");
                    Log::error('AutoDistributeEvent referral milestone failed', [
                        'event_id'  => $event->id,
                        'user_id'   => $referrer->ID,
                        'milestone' => $tierCount,
                        'error'     => $e->getMessage(),
                    ]);
                }
            }
        }

        $this->line("  [Referral] Selesai: {$totalDistributed} milestone distribute.");
    }
}
