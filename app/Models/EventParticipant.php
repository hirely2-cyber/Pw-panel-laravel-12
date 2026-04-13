<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventParticipant extends Model
{
    protected $table = 'pw_event_participants';

    protected $fillable = [
        'event_id',
        'user_id',
        'role_id',
        'character_name',
        'class',
        'level',
        'cultivation',
        'cultivation_label',
        'qualified_at',
        'prize_distributed',
        'last_synced_at',
    ];

    protected $casts = [
        'qualified_at'      => 'datetime',
        'last_synced_at'    => 'datetime',
        'prize_distributed' => 'boolean',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(LaunchEvent::class, 'event_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'ID');
    }
}
