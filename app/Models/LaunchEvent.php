<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LaunchEvent extends Model
{
    protected $table = 'pw_events';

    protected $fillable = [
        'type',
        'title',
        'title_en',
        'description',
        'description_en',
        'req_level',
        'req_cultivation',
        'prize_total_cubi',
        'prize_winner_count',
        'prize_rank1',
        'prize_rank2',
        'prize_rank3',
        'referral_tiers',
        'register_rewards',
        'register_req_level',
        'referral_req_level',
        'status',
        'start_at',
        'end_at',
    ];

    protected $casts = [
        'start_at'       => 'datetime',
        'end_at'         => 'datetime',
        'referral_tiers'     => 'array',
        'register_rewards'   => 'array',
        'register_req_level' => 'integer',
    ];

    public function localizedTitle(): string
    {
        if (app()->getLocale() === 'en' && $this->title_en) {
            return $this->title_en;
        }
        return $this->title;
    }

    public function localizedDescription(): ?string
    {
        if (app()->getLocale() === 'en' && $this->description_en) {
            return $this->description_en;
        }
        return $this->description;
    }

    public function participants(): HasMany
    {
        return $this->hasMany(EventParticipant::class, 'event_id');
    }

    public function referralMilestones(): HasMany
    {
        return $this->hasMany(ReferralMilestone::class, 'event_id');
    }

    public function registerDeliveries(): HasMany
    {
        return $this->hasMany(EventRegisterDelivery::class, 'event_id');
    }

    public function isPreLaunch(): bool
    {
        return $this->type === 'pre_launch';
    }

    public function qualifiedParticipants(): HasMany
    {
        return $this->participants()->whereNotNull('qualified_at')->orderBy('qualified_at');
    }

    public function winners(): HasMany
    {
        return $this->qualifiedParticipants()->limit($this->prize_winner_count);
    }

    public function isActive(): bool
    {
        return $this->status === 'active'
            && $this->start_at <= now()
            && ($this->end_at === null || $this->end_at >= now());
    }

    public function prizePerWinner(): int
    {
        return $this->prize_winner_count > 0
            ? intdiv($this->prize_total_cubi, $this->prize_winner_count)
            : 0;
    }

    /**
     * Get prize amount for a specific rank (1-based).
     * Rank 1/2/3 get custom amounts, rest get equal share of remainder.
     */
    public function prizeForRank(int $rank): int
    {
        if ($rank === 1 && $this->prize_rank1 > 0) return $this->prize_rank1;
        if ($rank === 2 && $this->prize_rank2 > 0) return $this->prize_rank2;
        if ($rank === 3 && $this->prize_rank3 > 0) return $this->prize_rank3;

        // If no tiered prizes set, fall back to equal split
        if ($this->prize_rank1 == 0 && $this->prize_rank2 == 0 && $this->prize_rank3 == 0) {
            return $this->prizePerWinner();
        }

        // Remaining split equally among rank 4+
        $remaining = $this->prize_total_cubi - $this->prize_rank1 - $this->prize_rank2 - $this->prize_rank3;
        $restCount = max(1, $this->prize_winner_count - 3);

        return intdiv(max(0, $remaining), $restCount);
    }

    /**
     * Check if tiered prizes are configured.
     */
    public function hasTieredPrizes(): bool
    {
        return $this->prize_rank1 > 0 || $this->prize_rank2 > 0 || $this->prize_rank3 > 0;
    }

    public const CULTIVATION_MAP = [
        0  => 'Inchoation',
        1  => 'Autoscopy',
        2  => 'Transform',
        3  => 'Naissance',
        4  => 'Reborn',
        5  => 'Vigilance',
        6  => 'Doom',
        7  => 'Disengage',
        8  => 'Nirvana',
        20 => 'Prime Immortal',
        21 => 'Daimon Baresark',
        22 => 'Pure Immortal',
        30 => 'Daimon Saint',
        31 => 'Ether Immortal',
        32 => 'Daimon Elder',
    ];

    /**
     * Dropdown options for admin — grouped by tier (light/dark = same tier)
     * Value stored = light path value, meetsCultivation() handles both paths.
     */
    public const CULTIVATION_OPTIONS = [
        0  => 'Inchoation',
        1  => 'Autoscopy',
        2  => 'Transform',
        3  => 'Naissance',
        4  => 'Reborn',
        5  => 'Vigilance',
        6  => 'Doom',
        7  => 'Disengage',
        8  => 'Nirvana',
        20 => 'Sage / Demon — Tier 1 (Prime Immortal / Daimon Baresark)',
        22 => 'Sage / Demon — Tier 2 (Pure Immortal / Daimon Saint)',
        31 => 'Sage / Demon — Tier 3 (Ether Immortal / Daimon Elder)',
    ];

    /**
     * Cultivation tier equivalence (light ↔ dark):
     * Tier 1: 20 (Prime Immortal) ↔ 21 (Daimon Baresark)
     * Tier 2: 22 (Pure Immortal)  ↔ 30 (Daimon Saint)
     * Tier 3: 31 (Ether Immortal) ↔ 32 (Daimon Elder)
     */
    private const CULTIVATION_TIERS = [
        20 => 1, 21 => 1,
        22 => 2, 30 => 2,
        31 => 3, 32 => 3,
    ];

    /**
     * Check if a cultivation value meets the requirement.
     * Compares by tier — both light and dark paths accepted at same tier.
     */
    public function meetsCultivation(int $cultivation): bool
    {
        $req = $this->req_cultivation;

        // Pre-nirvana (0–8): direct comparison
        if ($req <= 8) {
            return $cultivation >= $req;
        }

        // Post-nirvana: compare by tier
        $reqTier = self::CULTIVATION_TIERS[$req] ?? 0;
        $cultTier = self::CULTIVATION_TIERS[$cultivation] ?? 0;

        return $cultTier >= $reqTier;
    }
}
