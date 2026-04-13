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
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoteLog extends Model
{
    protected $table = 'pw_vote_logs';

    protected $fillable = [
        'user_id', 'site_id', 'ip_address', 'reward_given',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'ID');
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(VoteSite::class, 'site_id');
    }
}
