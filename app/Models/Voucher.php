<?php

/*
 * @author   Wahyu Suhandi <andietz.orion@gmail.com>
 * @link     https://wa.me/6208118719377
 * @project  Perfect World Panel
 * @version  2.0.0
 * @license  MIT
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Voucher extends Model
{
    protected $table = 'pw_vouchers';

    public const TYPE_GOLD_POINTS = 'gold_points';
    public const TYPE_CUBI = 'cubi';

    protected $fillable = [
        'code', 'description', 'type', 'value',
        'max_uses', 'used_count', 'is_active', 'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active'  => 'boolean',
            'expires_at' => 'datetime',
        ];
    }

    public function getNormalizedTypeAttribute(): string
    {
        return in_array($this->type, ['gold', self::TYPE_GOLD_POINTS], true)
            ? self::TYPE_GOLD_POINTS
            : self::TYPE_CUBI;
    }

    public function getRewardTypeLabelAttribute(): string
    {
        return $this->normalized_type === self::TYPE_CUBI ? 'Cubi Gold' : 'Gold Points';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            });
    }

    public function isAvailable(): bool
    {
        if (! $this->is_active) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        if ($this->max_uses !== null && $this->used_count >= $this->max_uses) return false;
        return true;
    }

    public function logs(): HasMany
    {
        return $this->hasMany(VoucherLog::class, 'voucher_id');
    }
}
