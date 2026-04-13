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
use Illuminate\Http\Request;

class ReferralHistoryController extends Controller
{
    public function index(Request $request)
    {
        $user    = $request->user();
        $partner = ReferralPartner::where('user_id', $user->ID)->first();

        $referrals = User::where('referred_by', $user->ID)
            ->orderByDesc('creatime')
            ->paginate(20);

        // Total commissions per referred user
        $commissionByUser = Invoice::where('partner_user_id', $user->ID)
            ->where('status', 'paid')
            ->selectRaw('user_id, SUM(commission_amount) as total')
            ->groupBy('user_id')
            ->pluck('total', 'user_id');

        return view('partner.referral-history', compact(
            'partner',
            'referrals',
            'commissionByUser',
        ));
    }
}
