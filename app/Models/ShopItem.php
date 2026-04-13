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

class ShopItem extends Model
{
    protected $table = 'pw_shop_items';

    protected $fillable = [
        'name', 'image', 'description', 'category',
        'item_id', 'item_count', 'price', 'is_active', 'stock',
    ];

    protected function casts(): array
    {
        return [
            'price'     => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function logs()
    {
        return $this->hasMany(ShopLog::class, 'item_id');
    }
}
