<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatafileUpdateLog extends Model
{
    protected $table = 'pw_datafile_updates';

    protected $fillable = [
        'user_id',
        'actor_name',
        'actor_role',
        'panel_area',
        'target_file',
        'original_name',
        'file_size',
        'script_output',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
        ];
    }
}
