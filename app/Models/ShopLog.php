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

class ShopLog extends Model
{
    protected $table = 'pw_shop_logs';

    protected $fillable = [
        'user_id', 'item_id', 'item_name', 'price', 'quantity', 'recipient', 'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'ID');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(ShopItem::class, 'item_id');
    }
}
