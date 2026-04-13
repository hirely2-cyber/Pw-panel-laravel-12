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

class VoteSite extends Model
{
    protected $table = 'pw_vote_sites';

    protected $fillable = [
        'name', 'url', 'image', 'reward', 'reward_bonus',
        'cooldown', 'is_active', 'total_votes',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(VoteLog::class, 'site_id');
    }
}
