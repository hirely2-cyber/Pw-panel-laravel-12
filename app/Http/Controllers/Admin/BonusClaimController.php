<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartnerWithdrawal;
use App\Models\ReferralPartner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BonusClaimController extends Controller
{
    public function index(Request $request)
    {
        $query = PartnerWithdrawal::with('user:ID,name')
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->orderByDesc('created_at');

        if ($request->filled('status') && in_array($request->status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $request->status);
        }

        $claims = $query->paginate(20)->withQueryString();

        // Stats
        $pendingCount  = PartnerWithdrawal::where('status', 'pending')->count();
        $approvedCount = PartnerWithdrawal::where('status', 'approved')->count();
        $rejectedCount = PartnerWithdrawal::where('status', 'rejected')->count();
        $totalPaidOut  = PartnerWithdrawal::where('status', 'approved')->sum('amount');

        return view('admin.bonus-claims', compact(
            'claims',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'totalPaidOut',
        ));
    }

    /**
     * Approve a bonus claim.
     * - tunai: just mark approved (admin transfers manually)
     * - cubi:  auto-deliver Cubi via usecashnow queue
     * - gold:  auto-credit Gold to panel balance (money field)
     */
    public function approve(Request $request, PartnerWithdrawal $claim)
    {
        if ($claim->status !== 'pending') {
            return back()->with('error', 'Claim ini sudah diproses.');
        }

        $partner = ReferralPartner::where('user_id', $claim->user_id)->first();
        $rewardType = $claim->payment_method; // 'bank', 'ewallet', 'cubi', 'gold'

        try {
            if ($rewardType === 'cubi') {
                // Auto-deliver Cubi Gold via usecashnow
                $cubiRate  = config('pw-config.currency.cubi_rate_idr', 1000);
                $cubiAmount = (int) floor($claim->amount / $cubiRate);
                $cashValue  = $cubiAmount * 100; // usecashnow stores in cents

                DB::connection('mysql_game')->transaction(function () use ($claim, $cashValue) {
                    $nextSn = (DB::connection('mysql_game')
                        ->table('usecashnow')
                        ->where('userid', $claim->user_id)
                        ->where('zoneid', 1)
                        ->min('sn') ?? 0) - 1;

                    DB::connection('mysql_game')->table('usecashnow')->insert([
                        'userid'   => $claim->user_id,
                        'zoneid'   => 1,
                        'sn'       => $nextSn,
                        'aid'      => 1,
                        'point'    => 0,
                        'cash'     => $cashValue,
                        'status'   => 0,
                        'creatime' => now(),
                    ]);
                });

                $adminNote = 'Cubi Gold (' . number_format($cubiAmount) . ' Cubi) otomatis dikirim ke game.';

            } elseif ($rewardType === 'gold') {
                // Auto-credit Gold Points to panel balance
                $goldRate   = config('pw-config.currency.rate_idr', 10000);
                $goldAmount = (int) floor($claim->amount / $goldRate);

                DB::table('users')
                    ->where('ID', $claim->user_id)
                    ->increment('money', $goldAmount);

                $adminNote = 'Gold Points (' . number_format($goldAmount) . ' Gold) otomatis ditambahkan ke saldo panel.';

            } else {
                // Bank / E-Wallet: manual transfer by admin
                $adminNote = $request->input('admin_note', 'Disetujui — transfer manual oleh admin.');
            }

            $claim->update([
                'status'       => 'approved',
                'admin_note'   => $adminNote,
                'processed_at' => now(),
            ]);

            return back()->with('success', 'Pencairan bonus berhasil disetujui.' .
                ($rewardType === 'cubi' ? ' Cubi Gold telah dikirim ke game.' : '') .
                ($rewardType === 'gold' ? ' Gold Points telah ditambahkan ke saldo.' : ''));

        } catch (\Throwable $e) {
            Log::error('Bonus claim approve failed', [
                'claim_id' => $claim->id,
                'error'    => $e->getMessage(),
            ]);

            return back()->with('error', 'Gagal memproses: ' . $e->getMessage());
        }
    }

    /**
     * Reject a bonus claim.
     */
    public function reject(Request $request, PartnerWithdrawal $claim)
    {
        if ($claim->status !== 'pending') {
            return back()->with('error', 'Claim ini sudah diproses.');
        }

        $validated = $request->validate([
            'admin_note' => 'required|string|max:255',
        ]);

        $claim->update([
            'status'       => 'rejected',
            'admin_note'   => $validated['admin_note'],
            'processed_at' => now(),
        ]);

        return back()->with('success', 'Pencairan bonus ditolak.');
    }
}
