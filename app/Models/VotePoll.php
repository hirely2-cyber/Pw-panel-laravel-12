<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VotePoll extends Model
{
    protected $table = 'pw_vote_polls';

    protected $fillable = ['title', 'is_active', 'closed_at'];

    protected $casts = [
        'is_active' => 'boolean',
        'closed_at' => 'datetime',
    ];

    public function options(): HasMany
    {
        return $this->hasMany(VoteOption::class, 'poll_id')->orderByDesc('votes');
    }

    public function dungeonVoteLogs(): HasMany
    {
        return $this->hasMany(DungeonVoteLog::class, 'poll_id');
    }

    /** Total votes across all options */
    public function totalVotes(): int
    {
        return $this->options->sum('votes');
    }
}
