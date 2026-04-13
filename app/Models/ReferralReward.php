<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReferralReward extends Model
{
    protected $table = 'pw_referral_rewards';

    protected $fillable = [
        'referrer_id',
        'referred_id',
        'type',
        'reward_amount',
    ];

    protected function casts(): array
    {
        return [
            'reward_amount' => 'integer',
        ];
    }

    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referrer_id', 'ID');
    }

    public function referred(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_id', 'ID');
    }
}
