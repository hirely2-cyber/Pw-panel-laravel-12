<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoteOption extends Model
{
    protected $table = 'pw_vote_options';

    protected $fillable = ['poll_id', 'map_id', 'map_name', 'votes'];

    public function poll(): BelongsTo
    {
        return $this->belongsTo(VotePoll::class, 'poll_id');
    }
}
