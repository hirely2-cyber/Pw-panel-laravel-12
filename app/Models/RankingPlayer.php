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

class RankingPlayer extends Model
{
    protected $table      = 'pw_ranking_players';
    public    $timestamps = false;

    protected $fillable = [
        'char_name', 'user_id', 'class_name', 'level', 'total_exp',
        'faction_name', 'pvp_wins', 'pvp_kills', 'updated_at',
    ];

    protected $casts = [
        'updated_at' => 'datetime',
    ];
}
