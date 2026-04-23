<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DungeonVoteLog extends Model
{
    protected $table = 'pw_dungeon_vote_logs';
    public $timestamps = false;

    protected $fillable = ['poll_id', 'voter_ip', 'map_id'];

    protected $casts = [
        'voted_at' => 'datetime',
    ];

    public function poll(): BelongsTo
    {
        return $this->belongsTo(VotePoll::class, 'poll_id');
    }
}
