<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CubiPackage extends Model
{
    protected $table = 'pw_cubi_packages';

    protected $fillable = [
        'name', 'cubi_amount', 'price_idr', 'bonus_cubi',
        'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'cubi_amount' => 'integer',
            'price_idr'   => 'integer',
            'bonus_cubi'  => 'integer',
            'is_active'   => 'boolean',
            'sort_order'  => 'integer',
        ];
    }

    public function getTotalCubiAttribute(): int
    {
        return $this->cubi_amount + $this->bonus_cubi;
    }
}
