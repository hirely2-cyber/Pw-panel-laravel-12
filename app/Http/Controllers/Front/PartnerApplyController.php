<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\PartnerApplication;
use App\Models\PartnerTerms;
use App\Models\ReferralPartner;
use Illuminate\Http\Request;

class PartnerApplyController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $lang = app()->getLocale();
        $terms = PartnerTerms::forLang($lang);

        // Already a partner
        $isPartner = ReferralPartner::where('user_id', $user->ID)->exists();

        // Existing application
        $application = PartnerApplication::where('user_id', $user->ID)
            ->orderByDesc('created_at')
            ->first();

        return view('front.partner-apply.index', compact('isPartner', 'application', 'terms', 'lang'));
    }

    public function store(Request $request)
    {
        $user = $request->user();

        // Already a partner
        if (ReferralPartner::where('user_id', $user->ID)->exists()) {
            return back()->with('error', __('main.pa_flash_already'));
        }

        // Check pending application
        $pending = PartnerApplication::where('user_id', $user->ID)
            ->where('status', 'pending')
            ->exists();

        if ($pending) {
            return back()->with('error', __('main.pa_flash_pending'));
        }

        $request->validate([
            'channel_name'    => ['required', 'string', 'max:100'],
            'platform'        => ['required', 'in:tiktok,youtube,facebook,instagram,twitter,other'],
            'channel_url'     => ['required', 'url', 'max:255'],
            'followers_count' => ['required', 'integer', 'min:0', 'max:99999999'],
            'reason'          => ['nullable', 'string', 'max:1000'],
            'agree_terms'     => ['required', 'accepted'],
        ], [
            'agree_terms.required' => __('main.pa_agree_must'),
            'agree_terms.accepted' => __('main.pa_agree_must'),
        ]);

        PartnerApplication::create([
            'user_id'         => $user->ID,
            'channel_name'    => $request->channel_name,
            'platform'        => $request->platform,
            'channel_url'     => $request->channel_url,
            'followers_count' => $request->followers_count,
            'reason'          => $request->reason,
        ]);

        return back()->with('success', __('main.pa_flash_success'));
    }
}
