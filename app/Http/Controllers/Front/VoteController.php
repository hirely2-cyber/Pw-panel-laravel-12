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
use App\Models\VoteLog;
use App\Models\VoteSite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class VoteController extends Controller
{
    public function index(): View
    {
        $sites  = VoteSite::active()->orderBy('sort_order')->get();
        $userId = auth()->id();

        // Get which sites user already voted today
        $votedSiteIds = VoteLog::where('user_id', $userId)
            ->where('created_at', '>=', now()->subHours(config('pw-config.vote.cooldown_hours', 24)))
            ->pluck('site_id')
            ->toArray();

        return view('front.vote.index', compact('sites', 'votedSiteIds'));
    }

    public function vote(Request $request, VoteSite $site): RedirectResponse
    {
        if (! $site->is_active) {
            return back()->with('error', 'Site vote tidak tersedia.');
        }

        $user    = $request->user();
        $cooldown = config('pw-config.vote.cooldown_hours', 24);

        // Check cooldown per site
        $alreadyVoted = VoteLog::where('user_id', $user->ID)
            ->where('site_id', $site->id)
            ->where('created_at', '>=', now()->subHours($cooldown))
            ->exists();

        if ($alreadyVoted) {
            return back()->with('error', "Kamu sudah vote di {$site->name}. Tunggu {$cooldown} jam lagi.");
        }

        $reward = $site->reward ?? config('pw-config.vote.reward_gold', 5);

        DB::transaction(function () use ($user, $site, $reward) {
            $user->increment('money', $reward);

            VoteLog::create([
                'user_id'      => $user->ID,
                'site_id'      => $site->id,
                'ip_address'   => request()->ip(),
                'reward_given' => $reward,
            ]);
        });

        return back()->with('success', "Terima kasih sudah vote di {$site->name}! +{$reward} " . config('pw-config.currency.name'));
    }
}
