<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServerControlLog extends Model
{
    protected $table = 'pw_server_control_logs';

    protected $fillable = [
        'user_id',
        'actor_name',
        'actor_role',
        'panel_area',
        'action',
        'target_map',
        'delay_seconds',
        'result_ok',
        'result_message',
    ];

    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'delay_seconds' => 'integer',
            'result_ok' => 'boolean',
        ];
    }
}
