<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\PartnerWithdrawal;
use App\Models\ReferralPartner;
use Illuminate\Http\Request;

class BonusController extends Controller
{
    public function index(Request $request)
    {
        $user    = $request->user();
        $partner = ReferralPartner::where('user_id', $user->ID)->firstOrFail();

        $rewardType = $partner->reward_type;

        // Total commission earned (IDR)
        $totalCommissionIdr = Invoice::where('partner_user_id', $user->ID)
            ->where('status', 'paid')
            ->where('commission_credited', true)
            ->sum('commission_amount');

        // Total already withdrawn/claimed (approved)
        $totalWithdrawn = PartnerWithdrawal::where('user_id', $user->ID)
            ->where('status', 'approved')
            ->sum('amount');

        $availableBalanceIdr = max(0, $totalCommissionIdr - $totalWithdrawn);

        // Convert display values based on reward type
        if ($rewardType === 'gold') {
            $rate = config('pw-config.currency.rate_idr', 10000);
            $availableDisplay = (int) floor($availableBalanceIdr / $rate);
            $currencyLabel = 'Gold';
            $minClaim = 1;
        } elseif ($rewardType === 'cubi') {
            $cubiRate = config('pw-config.currency.cubi_rate_idr', 1000);
            $availableDisplay = (int) floor($availableBalanceIdr / $cubiRate);
            $currencyLabel = 'Cubi';
            $minClaim = 1;
        } else {
            $availableDisplay = $availableBalanceIdr;
            $currencyLabel = 'IDR';
            $minClaim = 10000;
        }

        // Can claim only in first 7 days of the month
        // TODO: revert after testing — temporarily allow any day
        $canClaim = true; // Original: now()->day <= 7;
        $claimDeadline = now()->startOfMonth()->addDays(7);

        // Check if already requested this month
        $alreadyRequested = PartnerWithdrawal::where('user_id', $user->ID)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->exists();

        // Claim history
        $claims = PartnerWithdrawal::where('user_id', $user->ID)
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('partner.bonus', compact(
            'partner',
            'rewardType',
            'availableBalanceIdr',
            'availableDisplay',
            'currencyLabel',
            'minClaim',
            'canClaim',
            'claimDeadline',
            'alreadyRequested',
            'claims',
        ));
    }

    /**
     * Save payment info (bank / ewallet) — only for tunai partners.
     */
    public function savePaymentInfo(Request $request)
    {
        $user    = $request->user();
        $partner = ReferralPartner::where('user_id', $user->ID)->firstOrFail();

        $validated = $request->validate([
            'bank_name'      => 'nullable|string|max:50',
            'bank_account'   => 'nullable|string|max:30',
            'bank_holder'    => 'nullable|string|max:100',
            'ewallet_type'   => 'nullable|string|max:30',
            'ewallet_number' => 'nullable|string|max:20',
        ]);

        $partner->update($validated);

        return back()->with('success', 'Data pembayaran berhasil disimpan.');
    }

    /**
     * Submit a bonus claim request.
     */
    public function requestClaim(Request $request)
    {
        $user    = $request->user();
        $partner = ReferralPartner::where('user_id', $user->ID)->firstOrFail();

        // Only allow in first 7 days of month
        // TODO: revert after testing
        // if (now()->day > 7) {
        //     return back()->with('error', 'Pencairan bonus hanya bisa dilakukan pada tanggal 1-7 setiap bulan.');
        // }

        // Only one request per month
        $alreadyRequested = PartnerWithdrawal::where('user_id', $user->ID)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->exists();

        if ($alreadyRequested) {
            return back()->with('error', 'Kamu sudah mengajukan pencairan bulan ini.');
        }

        $rewardType = $partner->reward_type;

        // Total available balance in IDR
        $totalCommissionIdr = Invoice::where('partner_user_id', $user->ID)
            ->where('status', 'paid')
            ->where('commission_credited', true)
            ->sum('commission_amount');

        $totalWithdrawn = PartnerWithdrawal::where('user_id', $user->ID)
            ->where('status', 'approved')
            ->sum('amount');

        $availableBalanceIdr = max(0, $totalCommissionIdr - $totalWithdrawn);

        if ($rewardType === 'tunai') {
            // Tunai: validate amount in IDR, need payment method
            $validated = $request->validate([
                'payment_method' => 'required|in:bank,ewallet',
                'amount'         => 'required|integer|min:10000',
            ]);

            if ($validated['amount'] > $availableBalanceIdr) {
                return back()->with('error', 'Saldo tidak mencukupi.');
            }

            // Build payment detail
            if ($validated['payment_method'] === 'bank') {
                if (! $partner->bank_name || ! $partner->bank_account || ! $partner->bank_holder) {
                    return back()->with('error', 'Lengkapi data rekening bank terlebih dahulu.');
                }
                $paymentDetail = $partner->bank_name . ' - ' . $partner->bank_account . ' (' . $partner->bank_holder . ')';
            } else {
                if (! $partner->ewallet_type || ! $partner->ewallet_number) {
                    return back()->with('error', 'Lengkapi data e-wallet terlebih dahulu.');
                }
                $paymentDetail = $partner->ewallet_type . ' - ' . $partner->ewallet_number;
            }

            $amount = $validated['amount'];

        } elseif ($rewardType === 'cubi') {
            // Cubi: claim all available as Cubi Gold
            $cubiRate = config('pw-config.currency.cubi_rate_idr', 1000);
            $cubiAmount = (int) floor($availableBalanceIdr / $cubiRate);

            if ($cubiAmount < 1) {
                return back()->with('error', 'Saldo Cubi belum mencukupi.');
            }

            $amount = $cubiAmount * $cubiRate; // Back to IDR for storage
            $paymentDetail = 'Cubi Gold — ' . number_format($cubiAmount) . ' Cubi → User ID ' . $user->ID;

        } else {
            // Gold: claim all available as Gold Points
            $goldRate = config('pw-config.currency.rate_idr', 10000);
            $goldAmount = (int) floor($availableBalanceIdr / $goldRate);

            if ($goldAmount < 1) {
                return back()->with('error', 'Saldo Gold belum mencukupi.');
            }

            $amount = $goldAmount * $goldRate;
            $paymentDetail = 'Gold Points — ' . number_format($goldAmount) . ' Gold → User ID ' . $user->ID;
        }

        PartnerWithdrawal::create([
            'user_id'        => $user->ID,
            'amount'         => $amount,
            'payment_method' => $rewardType === 'tunai' ? $validated['payment_method'] : $rewardType,
            'payment_detail' => $paymentDetail,
            'status'         => 'pending',
        ]);

        return back()->with('success', 'Permintaan pencairan bonus berhasil diajukan. Akan diproses oleh admin.');
    }
}
