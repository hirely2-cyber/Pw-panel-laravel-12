<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerWithdrawal extends Model
{
    protected $table = 'pw_partner_withdrawals';

    protected $fillable = [
        'user_id',
        'amount',
        'payment_method',
        'payment_detail',
        'status',
        'admin_note',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'amount'       => 'integer',
            'processed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'ID');
    }
}
