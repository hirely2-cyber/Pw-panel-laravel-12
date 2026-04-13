<?php

/*
 * @author   Wahyu Suhandi <andietz.orion@gmail.com>
 * @link     https://wa.me/6208118719377
 * @project  Perfect World Panel
 * @version  2.0.0
 * @license  MIT
 */

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\PayHookService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class DonateController extends Controller
{
    public function __construct(private PayHookService $payHook) {}

    public function index(): View
    {
        $packages = $this->getDonatePackages();
        return view('front.donate.index', compact('packages'));
    }

    public function createInvoice(Request $request): RedirectResponse
    {
        $validChannels = ['qris', 'dana', 'ovo', 'gopay', 'linkaja', 'shopeepay', 'bank_transfer', 'virtual_account'];

        $request->validate([
            'package'      => ['required', 'string', 'in:' . implode(',', array_keys($this->getDonatePackages()))],
            'channel_type' => ['required', 'string', 'in:' . implode(',', $validChannels)],
        ]);

        $packages = $this->getDonatePackages();
        $package  = $packages[$request->package];

        $user        = $request->user();
        $totalGold   = $package['gold'] + $package['bonus'];
        $channelType = $request->channel_type;

        // Send base price to PayHook — PayHook adds its own unique suffix.
        // Do NOT add a panel suffix here, otherwise amounts double-stack.
        $baseAmount = $package['price_idr'];

        // Create invoice in PayHook server
        $result = $this->payHook->createInvoice(
            $user->ID, $totalGold, $baseAmount,
            $user->name ?? "User #{$user->ID}",
            $channelType
        );

        if (! $result) {
            return back()->with('error', 'Gagal membuat invoice. Coba beberapa saat lagi.');
        }

        // Store locally in pw_invoices — panel generates its own invoice_number (PW-XXXXXX)
        // PayHook's invoice_number stored separately in payhook_invoice_number for webhook matching
        $payhookNum = $result['invoice_number'] ?? null;
        // Sanitize: if PayHook returned SVG/XML instead of an invoice number, discard it
        if ($payhookNum && (str_contains($payhookNum, '<') || strlen($payhookNum) > 64)) {
            Log::warning('PayHook returned invalid invoice_number, discarded.', ['value_length' => strlen($payhookNum)]);
            $payhookNum = null;
        }

        $invoice = Invoice::create([
            'user_id'                => $user->ID,
            'gold_amount'            => $totalGold,
            'bonus_amount'           => $package['bonus'],
            'amount'                 => $baseAmount,
            'unique_suffix'          => $result['unique_suffix'] ?? 0,
            'unique_amount'          => $result['pay_amount'] ?? $baseAmount,
            'status'                 => Invoice::STATUS_PENDING,
            'expires_at'             => now()->addMinutes(10),
            'qris_url'               => $result['qris_url'] ?? null,
            'channel_type'           => $result['channel_type'] ?? $channelType,
            'payment_instruction'    => $result['payment_instruction'] ?? null,
            'payhook_invoice_number' => $payhookNum,
        ]);

        return redirect()->route('donate.invoice.show', $invoice->invoice_number);
    }

    public function show(string $invoiceNumber): View
    {
        $invoice = Invoice::where('invoice_number', $invoiceNumber)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Auto-expire if time is up
        if ($invoice->status === Invoice::STATUS_PENDING && $invoice->isExpired()) {
            $invoice->update(['status' => Invoice::STATUS_EXPIRED]);
            $invoice->refresh();
        }

        return view('front.donate.invoice', compact('invoice'));
    }

    public function status(string $invoiceNumber): \Illuminate\Http\JsonResponse
    {
        $invoice = Invoice::where('invoice_number', $invoiceNumber)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Auto-expire if time is up
        if ($invoice->status === Invoice::STATUS_PENDING && $invoice->isExpired()) {
            $invoice->update(['status' => Invoice::STATUS_EXPIRED]);
            $invoice->refresh();
        }

        return response()->json([
            'status'  => $invoice->status,
            'paid_at' => $invoice->paid_at?->toIso8601String(),
        ]);
    }

    public function expire(string $invoiceNumber): \Illuminate\Http\JsonResponse
    {
        $invoice = Invoice::where('invoice_number', $invoiceNumber)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($invoice->status === Invoice::STATUS_PENDING && $invoice->isExpired()) {
            $invoice->update(['status' => Invoice::STATUS_EXPIRED]);
        }

        return response()->json(['status' => $invoice->fresh()->status]);
    }

    public function history(Request $request): View
    {
        // Auto-expire any pending invoices that have passed their expiry time
        Invoice::where('user_id', $request->user()->ID)
            ->where('status', Invoice::STATUS_PENDING)
            ->where('expires_at', '<', now())
            ->update(['status' => Invoice::STATUS_EXPIRED]);

        $invoices = Invoice::where('user_id', $request->user()->ID)
            ->latest()
            ->paginate(25);

        return view('front.donate.history', compact('invoices'));
    }

    private function getDonatePackages(): array
    {
        $rate = config('pw-config.currency.rate_idr', 10000);

        return [
            '10'  => ['gold' => 10,  'bonus' => 10,  'price_idr' => 10  * $rate, 'label' => '10 Gold'],
            '20'  => ['gold' => 20,  'bonus' => 20,  'price_idr' => 20  * $rate, 'label' => '20 Gold'],
            '50'  => ['gold' => 50,  'bonus' => 50,  'price_idr' => 50  * $rate, 'label' => '50 Gold'],
            '100' => ['gold' => 100, 'bonus' => 100, 'price_idr' => 100 * $rate, 'label' => '100 Gold'],
            '200' => ['gold' => 200, 'bonus' => 200, 'price_idr' => 200 * $rate, 'label' => '200 Gold'],
            '500' => ['gold' => 500, 'bonus' => 500, 'price_idr' => 500 * $rate, 'label' => '500 Gold'],
        ];
    }
}
