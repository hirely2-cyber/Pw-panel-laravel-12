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
use App\Models\PartnerTerms;
use App\Models\ReferralPartner;
use Illuminate\Http\Request;

class TermsController extends Controller
{
    public function index(Request $request)
    {
        $partner = ReferralPartner::where('user_id', $request->user()->ID)->first();

        $lang = in_array($request->query('lang'), ['id', 'en']) ? $request->query('lang') : 'id';
        $terms = PartnerTerms::forLang($lang);

        return view('partner.terms', compact('partner', 'terms', 'lang'));
    }
}
