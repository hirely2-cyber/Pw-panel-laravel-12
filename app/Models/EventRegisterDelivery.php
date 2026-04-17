<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventRegisterDelivery extends Model
{
    protected $table = 'event_register_deliveries';

    protected $fillable = [
        'event_id',
        'user_id',
        'char_level',
        'distributed',
        'distributed_at',
    ];

    protected $casts = [
        'distributed'    => 'boolean',
        'distributed_at' => 'datetime',
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
