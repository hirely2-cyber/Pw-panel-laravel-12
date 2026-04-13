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

class RankingFaction extends Model
{
    protected $table      = 'pw_ranking_factions';
    public    $timestamps = false;

    protected $fillable = [
        'faction_name', 'leader_name', 'member_count', 'total_exp', 'territory_count', 'updated_at',
    ];

    protected $casts = [
        'updated_at' => 'datetime',
    ];
}
