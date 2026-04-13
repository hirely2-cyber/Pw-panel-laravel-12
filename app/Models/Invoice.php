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
use Illuminate\Support\Str;

class Invoice extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID    = 'paid';
    public const STATUS_FAILED  = 'failed';
    public const STATUS_EXPIRED = 'expired';

    protected $table = 'pw_invoices';

    protected $fillable = [
        'user_id', 'type', 'invoice_number', 'amount', 'unique_suffix',
        'unique_amount', 'gold_amount', 'bonus_amount', 'cubi_amount',
        'status', 'payment_source', 'channel_type',
        'refcode', 'partner_user_id',
        'discount_percent', 'discount_amount',
        'commission_percent', 'commission_amount', 'commission_credited',
        'payment_instruction', 'payhook_invoice_number', 'qris_url', 'meta',
        'paid_at', 'expires_at', 'expired_at',
    ];

    protected function casts(): array
    {
        return [
            'amount'               => 'decimal:2',
            'unique_amount'        => 'decimal:2',
            'discount_percent'     => 'decimal:2',
            'discount_amount'      => 'decimal:2',
            'commission_percent'   => 'decimal:2',
            'commission_amount'    => 'decimal:2',
            'commission_credited'  => 'boolean',
            'paid_at'              => 'datetime',
            'expires_at'           => 'datetime',
            'payment_instruction'  => 'array',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Invoice $invoice) {
            if (empty($invoice->invoice_number)) {
                $invoice->invoice_number = 'PW-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            }
            // Only calculate unique_amount locally if PayHook hasn't provided one.
            // When using PayHook, pay_amount is stored directly and must not be overwritten.
            if (empty($invoice->unique_amount) || $invoice->unique_amount == $invoice->amount) {
                if (empty($invoice->unique_suffix)) {
                    $invoice->unique_suffix = random_int(1, 999);
                }
                $invoice->unique_amount = $invoice->amount + $invoice->unique_suffix;
            }
            if (empty($invoice->expires_at)) {
                $invoice->expires_at = now()->addHours(2);
            }
        });
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING)->where('expires_at', '>', now());
    }

    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function markAsPaid(string $source): void
    {
        $this->update([
            'status'         => 'paid',
            'paid_at'        => now(),
            'payment_source' => $source,
        ]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'ID');
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'partner_user_id', 'ID');
    }
}
