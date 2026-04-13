<?php

namespace App\Console\Commands;

use App\Models\EventParticipant;
use App\Models\LaunchEvent;
use App\Services\GameDbService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncEventProgress extends Command
{
    protected $signature   = 'pw:sync-event';
    protected $description = 'Sync level & cultivation data untuk event yang aktif';

    private const CLASS_MAP = [
        0 => 'Blademaster',  1 => 'Wizard',    2 => 'Cleric',
        3 => 'Archer',       4 => 'Barbarian',  5 => 'Venomancer',
        6 => 'Assassin',     7 => 'Psychic',    8 => 'Seeker',
        9 => 'Mystic',      10 => 'Duskblade', 11 => 'Stormbringer',
    ];

    public function handle(): int
    {
        $events = LaunchEvent::where('status', 'active')->get();

        if ($events->isEmpty()) {
            $this->info('Tidak ada event aktif.');
            return 0;
        }

        foreach ($events as $event) {
            // Skip event if start date hasn't arrived yet
            if ($event->start_at && $event->start_at->isFuture()) {
                $this->info("Event '{$event->title}' belum mulai (start: {$event->start_at->format('d M Y H:i')}).");
                continue;
            }

            // Auto-end event if past end date
            if ($event->end_at && $event->end_at->isPast()) {
                $event->update(['status' => 'ended']);
                $this->warn("Event '{$event->title}' otomatis diakhiri (melewati end_at).");
                continue;
            }

            $this->syncEvent($event);
        }

        return 0;
    }

    private function syncEvent(LaunchEvent $event): void
    {
        $this->info("Sync event: {$event->title}");

        // Get all characters from game DB that were created during event period
        // Since roles table has no creation date, we sync ALL characters
        // and let the qualification check handle filtering
        $roles = DB::connection('mysql_game')
            ->table('roles')
            ->get();

        if ($roles->isEmpty()) {
            $this->warn('  Tidak ada character di game DB.');
            return;
        }

        $gameDbService = app(GameDbService::class);
        $synced = 0;
        $qualified = 0;

        foreach ($roles as $role) {
            // Fetch cultivation from gamedbd via TCP
            $roleData = $gameDbService->getRoleData($role->role_id);
            $cultivation = $roleData['status']['cultivation'] ?? 0;
            $level = $roleData['status']['level'] ?? $role->role_level;
            $cultivationLabel = LaunchEvent::CULTIVATION_MAP[$cultivation] ?? 'Lv.' . $cultivation;

            // Upsert participant record
            $participant = EventParticipant::updateOrCreate(
                [
                    'event_id' => $event->id,
                    'role_id'  => $role->role_id,
                ],
                [
                    'user_id'           => $role->account_id,
                    'character_name'    => $role->role_name,
                    'class'             => self::CLASS_MAP[$role->role_occupation] ?? 'Unknown',
                    'level'             => $level,
                    'cultivation'       => $cultivation,
                    'cultivation_label' => $cultivationLabel,
                    'last_synced_at'    => now(),
                ]
            );

            // Check qualification (only set once — first to qualify wins)
            if (
                $participant->qualified_at === null
                && $level >= $event->req_level
                && $event->meetsCultivation($cultivation)
            ) {
                $participant->update(['qualified_at' => now()]);
                $qualified++;
            }

            $synced++;
        }

        $this->info("  Synced: {$synced} characters, Qualified baru: {$qualified}");
    }
}
