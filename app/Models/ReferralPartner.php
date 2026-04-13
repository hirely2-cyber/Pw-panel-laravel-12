<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReferralPartner extends Model
{
    protected $table = 'pw_referral_partners';

    protected $fillable = [
        'user_id',
        'discount_code',
        'label',
        'reward_amount',
        'reward_type',
        'min_char_level',
        'max_per_day',
        'max_total',
        'ip_unique_only',
        'is_active',
        'notes',
        'link_tiktok',
        'link_youtube',
        'link_facebook',
        'bank_name',
        'bank_account',
        'bank_holder',
        'ewallet_type',
        'ewallet_number',
    ];

    protected function casts(): array
    {
        return [
            'reward_amount'  => 'integer',
            'min_char_level' => 'integer',
            'max_per_day'    => 'integer',
            'max_total'      => 'integer',
            'ip_unique_only' => 'boolean',
            'is_active'      => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'ID');
    }
}
