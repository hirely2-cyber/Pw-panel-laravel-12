<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\ReferralPartner;
use App\Services\PayHookService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class CubiShopController extends Controller
{
    public function __construct(private PayHookService $payHook) {}

    public function index(): View
    {
        $discountPercent = config('pw-config.cubi_shop.discount_percent', 10);
        $minPurchase     = (int) config('pw-config.cubi_shop.min_purchase', 50000);
        $bonusMultiple   = (int) config('pw-config.cubi_shop.bonus_multiple', 50);
        $bonusAmount     = (int) config('pw-config.cubi_shop.bonus_amount', 5);
        $cubiRate        = (int) config('pw-config.currency.cubi_rate_idr', 1000);
        $currencyRates   = config('pw-config.currency_rates', []);

        return view('front.cubi-shop.index', compact(
            'discountPercent', 'minPurchase', 'bonusMultiple', 'bonusAmount', 'cubiRate', 'currencyRates'
        ));
    }

    public function validateRefcode(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate(['refcode' => 'required|string|max:30']);

        $partner = ReferralPartner::where('discount_code', $request->refcode)
            ->where('is_active', true)
            ->first();

        if (! $partner) {
            return response()->json(['valid' => false, 'message' => 'Kode diskon tidak ditemukan.']);
        }

        $user = $partner->user;

        if (auth()->id() === $user->ID) {
            return response()->json(['valid' => false, 'message' => 'Tidak bisa menggunakan kode diskon sendiri.']);
        }

        $discount = config('pw-config.cubi_shop.discount_percent', 10);

        return response()->json([
            'valid'    => true,
            'partner'  => $partner->label . ' — ' . $user->name,
            'discount' => $discount,
        ]);
    }

    public function createInvoice(Request $request): RedirectResponse
    {
        $validChannels = ['qris', 'dana', 'ovo', 'gopay', 'linkaja', 'shopeepay', 'bank_transfer', 'virtual_account'];
        $minPurchase   = (int) config('pw-config.cubi_shop.min_purchase', 50000);
        $cubiRate      = (int) config('pw-config.currency.cubi_rate_idr', 1000);
        $bonusMultiple = (int) config('pw-config.cubi_shop.bonus_multiple', 50);
        $bonusAmount   = (int) config('pw-config.cubi_shop.bonus_amount', 5);

        $request->validate([
            'amount'       => ['required', 'integer', 'min:' . $minPurchase],
            'channel_type' => ['required', 'string', 'in:' . implode(',', $validChannels)],
            'refcode'      => ['nullable', 'string', 'max:30'],
        ]);

        $user        = $request->user();
        $channelType = $request->channel_type;
        $basePrice   = (int) $request->amount;

        // Check for existing unpaid invoice (pending, not expired)
        $existing = Invoice::where('user_id', $user->ID)
            ->where('type', 'cubi')
            ->where('status', Invoice::STATUS_PENDING)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if ($existing) {
            return redirect()->route('donate.invoice.show', $existing->invoice_number)
                ->with('warning', 'Kamu masih memiliki invoice yang belum dibayar. Selesaikan pembayaran terlebih dahulu.');
        }

        // Calculate cubi from amount
        $baseCubi  = intdiv($basePrice, $cubiRate);
        $multiples = intdiv($baseCubi, $bonusMultiple);
        $bonusCubi = $multiples * $bonusAmount;
        $totalCubi = $baseCubi + $bonusCubi;

        // Refcode discount logic
        $refcode           = null;
        $partnerUserId     = null;
        $discountPercent   = null;
        $discountAmount    = null;
        $commissionPercent = null;
        $commissionAmount  = null;

        if ($request->filled('refcode')) {
            $partner = ReferralPartner::where('discount_code', $request->refcode)
                ->where('is_active', true)
                ->first();

            if ($partner && $partner->user_id !== $user->ID) {
                $refcode           = $request->refcode;
                $partnerUserId     = $partner->user_id;
                $discountPercent   = config('pw-config.cubi_shop.discount_percent', 10);
                $discountAmount    = (int) floor($basePrice * $discountPercent / 100);
                $commissionPercent = config('pw-config.cubi_shop.commission_percent', 10);
                $commissionAmount  = (int) floor($basePrice * $commissionPercent / 100);
            }
        }

        $finalPrice = $basePrice - ($discountAmount ?? 0);

        // Create PayHook invoice
        $result = $this->payHook->createInvoice(
            $user->ID, $totalCubi, $finalPrice,
            $user->name ?? "User #{$user->ID}",
            $channelType
        );

        if (! $result) {
            return back()->with('error', 'Gagal membuat invoice. Coba beberapa saat lagi.');
        }

        $payhookNum = $result['invoice_number'] ?? null;
        if ($payhookNum && (str_contains($payhookNum, '<') || strlen($payhookNum) > 64)) {
            Log::warning('PayHook returned invalid invoice_number for cubi, discarded.', ['value_length' => strlen($payhookNum)]);
            $payhookNum = null;
        }

        $invoice = Invoice::create([
            'user_id'                => $user->ID,
            'type'                   => 'cubi',
            'gold_amount'            => 0,
            'bonus_amount'           => 0,
            'cubi_amount'            => $totalCubi,
            'amount'                 => $basePrice,
            'unique_suffix'          => $result['unique_suffix'] ?? 0,
            'unique_amount'          => $result['pay_amount'] ?? $finalPrice,
            'status'                 => Invoice::STATUS_PENDING,
            'expires_at'             => now()->addHours(24),
            'qris_url'               => $result['qris_url'] ?? null,
            'channel_type'           => $result['channel_type'] ?? $channelType,
            'payment_instruction'    => $result['payment_instruction'] ?? null,
            'payhook_invoice_number' => $payhookNum,
            'refcode'                => $refcode,
            'partner_user_id'        => $partnerUserId,
            'discount_percent'       => $discountPercent,
            'discount_amount'        => $discountAmount,
            'commission_percent'     => $commissionPercent,
            'commission_amount'      => $commissionAmount,
        ]);

        return redirect()->route('donate.invoice.show', $invoice->invoice_number);
    }

    public function cancelInvoice(Request $request, string $invoiceNumber): RedirectResponse
    {
        $invoice = Invoice::where('invoice_number', $invoiceNumber)
            ->where('user_id', $request->user()->ID)
            ->where('type', 'cubi')
            ->where('status', Invoice::STATUS_PENDING)
            ->firstOrFail();

        $invoice->update(['status' => Invoice::STATUS_EXPIRED]);

        return redirect()->route('cubi-shop')
            ->with('success', 'Invoice dibatalkan. Kamu bisa membuat invoice baru sekarang.');
    }
}
