<?php

/*
 * @author   Wahyu Suhandi <andietz.orion@gmail.com>
 * @link     https://wa.me/6208118719377
 * @project  Perfect World Panel
 * @version  2.0.0
 * @license  MIT
 */

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\ReferralPartner;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user    = $request->user();
        $partner = ReferralPartner::where('user_id', $user->ID)->first();

        if (! $partner) {
            return view('partner.dashboard', [
                'partner'           => null,
                'totalReferrals'    => 0,
                'totalTransactions' => 0,
                'totalCommission'   => 0,
                'pendingCommission' => 0,
                'monthCommission'   => 0,
                'currencyLabel'     => 'Gold',
                'transactions'      => collect(),
            ]);
        }

        // Total unique users who used this partner's refcode
        $totalReferrals = User::where('referred_by', $user->ID)->count();

        // Commission stats from cubi invoices with this partner's refcode
        $totalCommissionIdr = Invoice::where('partner_user_id', $user->ID)
            ->where('status', 'paid')
            ->where('commission_credited', true)
            ->sum('commission_amount');

        $pendingCommissionIdr = Invoice::where('partner_user_id', $user->ID)
            ->where('status', 'paid')
            ->where('commission_credited', false)
            ->sum('commission_amount');

        $monthCommissionIdr = Invoice::where('partner_user_id', $user->ID)
            ->where('status', 'paid')
            ->where('commission_credited', true)
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('commission_amount');

        // Convert display based on partner's reward_type
        $rewardType = $partner->reward_type;

        if ($rewardType === 'gold') {
            $rate = config('pw-config.currency.rate_idr', 10000);
            $totalCommission   = max(0, (int) floor($totalCommissionIdr / $rate));
            $pendingCommission = max(0, (int) floor($pendingCommissionIdr / $rate));
            $monthCommission   = max(0, (int) floor($monthCommissionIdr / $rate));
            $currencyLabel = 'Gold';
        } elseif ($rewardType === 'cubi') {
            $cubiRate = config('pw-config.currency.cubi_rate_idr', 1000);
            $totalCommission   = max(0, (int) floor($totalCommissionIdr / $cubiRate));
            $pendingCommission = max(0, (int) floor($pendingCommissionIdr / $cubiRate));
            $monthCommission   = max(0, (int) floor($monthCommissionIdr / $cubiRate));
            $currencyLabel = 'Cubi';
        } else {
            // Tunai: show as Rupiah (IDR)
            $totalCommission   = $totalCommissionIdr;
            $pendingCommission = $pendingCommissionIdr;
            $monthCommission   = $monthCommissionIdr;
            $currencyLabel = 'IDR';
        }

        // Total paid transactions using this partner's discount code
        $totalTransactions = Invoice::where('partner_user_id', $user->ID)
            ->where('status', 'paid')
            ->count();

        // Recent transactions (cubi sales with this partner's refcode)
        $transactions = Invoice::where('partner_user_id', $user->ID)
            ->where('status', 'paid')
            ->with('user:ID,name')
            ->orderByDesc('paid_at')
            ->paginate(15);

        return view('partner.dashboard', compact(
            'partner',
            'totalReferrals',
            'totalTransactions',
            'totalCommission',
            'pendingCommission',
            'monthCommission',
            'currencyLabel',
            'transactions',
        ));
    }

    public function updateDiscountCode(Request $request)
    {
        $request->validate([
            'discount_code' => ['required', 'string', 'min:4', 'max:30', 'regex:/^[A-Za-z0-9]+$/'],
        ], [
            'discount_code.regex' => 'Kode diskon hanya boleh huruf dan angka, tanpa spasi.',
        ]);

        $user    = $request->user();
        $partner = ReferralPartner::where('user_id', $user->ID)->firstOrFail();

        $code = strtoupper($request->discount_code);

        // Check uniqueness (exclude own)
        $exists = ReferralPartner::where('discount_code', $code)
            ->where('id', '!=', $partner->id)
            ->exists();

        if ($exists) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Kode diskon sudah digunakan partner lain. Pilih kode lain.'], 422);
            }
            return back()->with('error', 'Kode diskon sudah digunakan partner lain. Pilih kode lain.');
        }

        $partner->update(['discount_code' => $code]);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Kode diskon berhasil diperbarui!']);
        }
        return back()->with('success', 'Kode diskon berhasil diperbarui!');
    }

    public function updateSocialMedia(Request $request)
    {
        $request->validate([
            'link_tiktok'   => ['nullable', 'url', 'max:255'],
            'link_youtube'  => ['nullable', 'url', 'max:255'],
            'link_facebook' => ['nullable', 'url', 'max:255'],
        ]);

        $partner = ReferralPartner::where('user_id', $request->user()->ID)->firstOrFail();
        $partner->update($request->only(['link_tiktok', 'link_youtube', 'link_facebook']));

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Media sosial berhasil disimpan!']);
        }
        return back()->with('success', 'Media sosial berhasil disimpan!');
    }
}
