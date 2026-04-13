<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartnerApplication;
use App\Models\PartnerTerms;
use App\Models\ReferralPartner;
use App\Models\ReferralReward;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReferralController extends Controller
{
    public function index(Request $request): View
    {
        $query = ReferralReward::with(['referrer:ID,name', 'referred:ID,name']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('referrer', fn ($sub) => $sub->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('referred', fn ($sub) => $sub->where('name', 'like', "%{$search}%"));
            });
        }

        $rewards = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        // Stats
        $totalReferrals = User::whereNotNull('referred_by')->count();
        $totalRewarded = ReferralReward::count();
        $totalGoldGiven = ReferralReward::where('type', 'registration')->sum('reward_amount');
        $totalCubiGiven = ReferralReward::where('type', 'registration_cubi')->sum('reward_amount');

        // Top referrers
        $topReferrers = User::select('users.ID', 'users.name')
            ->selectRaw('COUNT(r.ID) as total_referred')
            ->join('users as r', 'r.referred_by', '=', 'users.ID')
            ->groupBy('users.ID', 'users.name')
            ->orderByDesc('total_referred')
            ->limit(5)
            ->get();

        // Partners
        $partners = ReferralPartner::with('user:ID,name,referral_code')
            ->withCount(['user as total_referrals' => function ($q) {
                // This doesn't work cleanly, so we'll do it manually
            }])
            ->get();

        // Enrich partners with referral counts
        foreach ($partners as $p) {
            $p->total_referrals = User::where('referred_by', $p->user_id)->count();
            $p->total_rewarded = ReferralReward::where('referrer_id', $p->user_id)->count();
            $p->total_earned = ReferralReward::where('referrer_id', $p->user_id)->sum('reward_amount');
        }

        // Partner Applications
        $applications = PartnerApplication::with('user:ID,name,email')
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
            ->orderByDesc('created_at')
            ->get();
        $pendingCount = $applications->where('status', 'pending')->count();

        return view('admin.referral.index', compact(
            'rewards', 'totalReferrals', 'totalRewarded',
            'totalGoldGiven', 'totalCubiGiven', 'topReferrers'
        ));
    }

    public function partners(): View
    {
        $partners = ReferralPartner::with('user:ID,name,referral_code')->get();

        foreach ($partners as $p) {
            $p->total_referrals = User::where('referred_by', $p->user_id)->count();
            $p->total_rewarded  = ReferralReward::where('referrer_id', $p->user_id)->count();
            $p->total_earned    = ReferralReward::where('referrer_id', $p->user_id)->sum('reward_amount');
        }

        $applications = PartnerApplication::with('user:ID,name,email')
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
            ->orderByDesc('created_at')
            ->get();
        $pendingCount = $applications->where('status', 'pending')->count();

        return view('admin.referral.partners', compact('partners', 'applications', 'pendingCount'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'reward_type'            => ['required', 'in:gold,cubi'],
            'reward_amount'          => ['required', 'integer', 'min:1', 'max:999999'],
            'min_char_level'         => ['required', 'integer', 'min:1', 'max:150'],
            'min_cultivation'        => ['required', 'integer', 'in:0,1,2,3,4,5,6,7,8,20,21,22'],
            'max_per_day'            => ['required', 'integer', 'min:0', 'max:10000'],
            'referred_reward_type'   => ['required', 'in:none,gold,cubi'],
            'referred_reward_amount' => ['required', 'integer', 'min:0', 'max:999999'],
            'enabled'                => ['nullable'],
        ]);

        $this->setEnv('PW_REFERRAL_ENABLED', $request->has('enabled') ? 'true' : 'false');
        $this->setEnv('PW_REFERRAL_REWARD_TYPE', $request->reward_type);
        $this->setEnv('PW_REFERRAL_REWARD_GOLD', $request->reward_amount);
        $this->setEnv('PW_REFERRAL_MIN_CHAR_LEVEL', $request->min_char_level);
        $this->setEnv('PW_REFERRAL_MIN_CULTIVATION', $request->min_cultivation);
        $this->setEnv('PW_REFERRAL_MAX_PER_DAY', $request->max_per_day);
        $this->setEnv('PW_REFERRAL_REFERRED_REWARD_TYPE', $request->referred_reward_type);
        $this->setEnv('PW_REFERRAL_REFERRED_REWARD_AMOUNT', $request->referred_reward_amount);

        return back()->with('success', 'Pengaturan referral berhasil disimpan.');
    }

    public function addPartner(Request $request)
    {
        $request->validate([
            'username'       => ['required', 'string'],
            'label'          => ['required', 'string', 'max:50'],
            'reward_amount'  => ['required', 'integer', 'min:1', 'max:999999'],
            'reward_type'    => ['required', 'in:gold,cubi,tunai'],
            'min_char_level' => ['required', 'integer', 'min:1', 'max:150'],
            'max_per_day'    => ['required', 'integer', 'min:1', 'max:1000'],
            'max_total'      => ['nullable', 'integer', 'min:1'],
            'ip_unique_only' => ['nullable'],
            'notes'          => ['nullable', 'string', 'max:500'],
            'discount_code'  => ['nullable', 'string', 'min:4', 'max:30', 'regex:/^[A-Za-z0-9]+$/', 'unique:pw_referral_partners,discount_code'],
            'link_tiktok'    => ['nullable', 'url', 'max:255'],
            'link_youtube'   => ['nullable', 'url', 'max:255'],
            'link_facebook'  => ['nullable', 'url', 'max:255'],
        ]);

        $user = User::where('name', $request->username)->first();
        if (! $user) {
            return back()->with('error', 'Username "' . $request->username . '" tidak ditemukan.');
        }

        if (ReferralPartner::where('user_id', $user->ID)->exists()) {
            return back()->with('error', 'User ini sudah terdaftar sebagai partner.');
        }

        ReferralPartner::create([
            'user_id'        => $user->ID,
            'label'          => $request->label,
            'discount_code'  => $request->discount_code ? strtoupper($request->discount_code) : null,
            'reward_amount'  => $request->reward_amount,
            'reward_type'    => $request->reward_type,
            'min_char_level' => $request->min_char_level,
            'max_per_day'    => $request->max_per_day,
            'max_total'      => $request->max_total,
            'ip_unique_only' => $request->has('ip_unique_only'),
            'notes'          => $request->notes,
            'link_tiktok'    => $request->link_tiktok,
            'link_youtube'   => $request->link_youtube,
            'link_facebook'  => $request->link_facebook,
        ]);

        // Auto-set role to partner (only if currently a player)
        if (strtolower($user->role) === 'player') {
            DB::table('users')->where('ID', $user->ID)->update(['role' => 'partner']);
        }

        return back()->with('success', 'Partner "' . $user->name . '" berhasil ditambahkan.');
    }

    public function updatePartner(Request $request, ReferralPartner $partner)
    {
        $request->validate([
            'label'          => ['required', 'string', 'max:50'],
            'reward_amount'  => ['required', 'integer', 'min:1', 'max:999999'],
            'reward_type'    => ['required', 'in:gold,cubi,tunai'],
            'min_char_level' => ['required', 'integer', 'min:1', 'max:150'],
            'max_per_day'    => ['required', 'integer', 'min:1', 'max:1000'],
            'max_total'      => ['nullable', 'integer', 'min:1'],
            'ip_unique_only' => ['nullable'],
            'is_active'      => ['nullable'],
            'discount_code'  => ['nullable', 'string', 'min:4', 'max:30', 'regex:/^[A-Za-z0-9]+$/', 'unique:pw_referral_partners,discount_code,' . $partner->id],
            'notes'          => ['nullable', 'string', 'max:500'],
            'link_tiktok'    => ['nullable', 'url', 'max:255'],
            'link_youtube'   => ['nullable', 'url', 'max:255'],
            'link_facebook'  => ['nullable', 'url', 'max:255'],
        ]);

        $partner->update([
            'label'          => $request->label,
            'discount_code'  => $request->discount_code ? strtoupper($request->discount_code) : null,
            'reward_amount'  => $request->reward_amount,
            'reward_type'    => $request->reward_type,
            'min_char_level' => $request->min_char_level,
            'max_per_day'    => $request->max_per_day,
            'max_total'      => $request->max_total,
            'ip_unique_only' => $request->has('ip_unique_only'),
            'is_active'      => $request->has('is_active'),
            'notes'          => $request->notes,
            'link_tiktok'    => $request->link_tiktok,
            'link_youtube'   => $request->link_youtube,
            'link_facebook'  => $request->link_facebook,
        ]);

        return back()->with('success', 'Partner berhasil diperbarui.');
    }

    public function deletePartner(ReferralPartner $partner)
    {
        $name = $partner->user->name ?? 'Unknown';
        $userId = $partner->user_id;
        $partner->delete();

        // Revert role to player if currently partner
        $user = User::find($userId);
        if ($user && strtolower($user->role) === 'partner') {
            DB::table('users')->where('ID', $user->ID)->update(['role' => 'player']);
        }

        return back()->with('success', 'Partner "' . $name . '" berhasil dihapus.');
    }

    private function setEnv(string $key, string $value): void
    {
        $envFile = app()->environmentFilePath();
        $content = file_get_contents($envFile);

        if (str_contains($content, "{$key}=")) {
            $content = preg_replace(
                "/^{$key}=.*/m",
                "{$key}={$value}",
                $content
            );
        } else {
            $content .= "\n{$key}={$value}";
        }

        file_put_contents($envFile, $content);
    }

    public function approveApplication(Request $request, PartnerApplication $application)
    {
        if ($application->status !== 'pending') {
            return back()->with('error', 'Permohonan ini sudah ditinjau sebelumnya.');
        }

        $application->update([
            'status'      => 'approved',
            'admin_notes' => $request->admin_notes,
            'reviewed_at' => now(),
        ]);

        $name = $application->user->name ?? 'User #' . $application->user_id;

        return back()->with('success', 'Permohonan "' . $name . '" disetujui. Tambahkan sebagai Partner setelah perjanjian disepakati.');
    }

    public function rejectApplication(Request $request, PartnerApplication $application)
    {
        if ($application->status !== 'pending') {
            return back()->with('error', 'Permohonan ini sudah ditinjau sebelumnya.');
        }

        $application->update([
            'status'      => 'rejected',
            'admin_notes' => $request->admin_notes,
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Permohonan ditolak.');
    }

    public function termsEdit(): View
    {
        $termsId = PartnerTerms::where('lang', 'id')->first();
        $termsEn = PartnerTerms::where('lang', 'en')->first();

        return view('admin.referral.terms', compact('termsId', 'termsEn'));
    }

    public function termsUpdate(Request $request)
    {
        $request->validate([
            'content_id' => ['required', 'string'],
            'content_en' => ['required', 'string'],
        ]);

        PartnerTerms::updateOrCreate(['lang' => 'id'], ['content' => $request->content_id]);
        PartnerTerms::updateOrCreate(['lang' => 'en'], ['content' => $request->content_en]);

        return back()->with('success', 'Syarat & Ketentuan berhasil disimpan.');
    }
}
