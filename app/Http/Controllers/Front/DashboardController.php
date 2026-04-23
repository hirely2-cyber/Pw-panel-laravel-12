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
use App\Models\LaunchEvent;
use App\Models\ReferralMilestone;
use App\Models\ReferralPartner;
use App\Models\ReferralReward;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('home');
    }

    public function profile(Request $request): View
    {
        $user = $request->user();

        // Get full character data from game DB
        $characters = collect();
        $cubiCoins = 0;
        try {
            $classMap = [
                0 => 'Blademaster', 1 => 'Wizard', 2 => 'Psychic', 3 => 'Venomancer',
                4 => 'Barbarian', 5 => 'Assassin', 6 => 'Archer', 7 => 'Cleric',
                8 => 'Seeker', 9 => 'Mystic', 10 => 'Duskblade', 11 => 'Stormbringer',
            ];
            // Race is fixed per class in PW — derive from occupation
            $classRaceMap = [
                0 => 'Human', 1 => 'Human',
                2 => 'Tideborn', 3 => 'Untamed',
                4 => 'Untamed', 5 => 'Tideborn',
                6 => 'Winged Elf', 7 => 'Winged Elf',
                8 => 'Earthguard', 9 => 'Earthguard',
                10 => 'Nightshade', 11 => 'Nightshade',
            ];
            $iconMap = [
                0 => 'blademaster', 1 => 'wizzard', 2 => 'psychic', 3 => 'venomancer',
                4 => 'barbarian', 5 => 'assasin', 6 => 'archer', 7 => 'cleric',
                8 => 'seeker', 9 => 'mystic', 10 => 'duskblade', 11 => 'stormbringer',
            ];
            $cultivationMap = [
                0 => 'Inchoation',
                1 => 'Autoscopy',
                2 => 'Transform',
                3 => 'Naissance',
                4 => 'Reborn',
                5 => 'Vigilance',
                6 => 'Doom',
                7 => 'Disengage',
                8 => 'Nirvana',
                20 => 'Prime Immortal',
                21 => 'Pure Immortal',
                22 => 'Ether Immortal',
                30 => 'Daimon Baresark',
                31 => 'Daimon Saint',
                32 => 'Daimon Elder',
            ];

            // Sumber role_id sama dengan User::gameCharacters: rentang [userId..userId+slot-1] (bukan account_id)
            $gameDb = new \App\Services\GameDbService();
            $roleIds = $user->gameCharacters()->pluck('role_id')->all();
            $rolesData = $gameDb->getRolesData($roleIds);
            $order = array_flip($roleIds);
            $rolesRows = count($roleIds) > 0
                ? DB::connection('mysql_game')->table('roles')->whereIn('role_id', $roleIds)->get()->keyBy('role_id')
                : collect();

            $mapChar = static function (object $r, $rd) use ($classMap, $classRaceMap, $iconMap, $cultivationMap) {
                $status = $rd['status'] ?? [];
                $prop = $status['property'] ?? [];
                $pocket = $rd['pocket'] ?? [];
                $storehouse = $rd['storehouse'] ?? [];

                return (object) [
                    'role_id'       => $r->role_id,
                    'name'          => $r->role_name,
                    'level'         => $r->role_level,
                    'class'         => $classMap[$r->role_occupation] ?? 'Unknown',
                    'class_id'      => $r->role_occupation,
                    'class_icon'    => ($iconMap[$r->role_occupation] ?? 'blademaster') . '.png',
                    'race'          => $classRaceMap[$r->role_occupation] ?? 'Unknown',
                    'gender'        => $r->role_gender == 0 ? 'Male' : 'Female',
                    'spouse'        => $r->role_spouse > 0 ? $r->role_spouse : null,
                    'faction_name'  => ($r->faction_name && trim($r->faction_name) !== '') ? $r->faction_name : null,
                    'faction_level' => $r->faction_level,
                    'pvp_kills'     => $r->pvp_kills,
                    'pvp_deads'     => $r->pvp_deads,
                    'has_extended'  => $rd !== null,
                    'reputation'    => $status['reputation'] ?? null,
                    'sp'            => $status['sp'] ?? null,
                    'cultivation'   => isset($status['cultivation'])
                        ? ($cultivationMap[$status['cultivation']] ?? 'Lv.' . $status['cultivation'])
                        : null,
                    'hp'            => $prop['max_hp'] ?? null,
                    'mp'            => $prop['max_mp'] ?? null,
                    'vigor'         => $prop['max_ap'] ?? null,
                    'pocket_coins'  => $pocket['money'] ?? null,
                    'store_coins'   => $storehouse['money'] ?? null,
                    'vitality'      => $prop['vitality'] ?? null,
                    'energy'        => $prop['energy'] ?? null,
                    'strength'      => $prop['strength'] ?? null,
                    'agility'       => $prop['agility'] ?? null,
                    'p_def'         => $prop['defense'] ?? null,
                    'p_atk_min'     => $prop['damage_low'] ?? null,
                    'p_atk_max'     => $prop['damage_high'] ?? null,
                    'm_atk_min'     => $prop['damage_magic_low'] ?? null,
                    'm_atk_max'     => $prop['damage_magic_high'] ?? null,
                ];
            };

            $characters = collect($roleIds)
                ->map(function (int $rid) use ($rolesRows, $rolesData, $mapChar) {
                    $r = $rolesRows->get($rid);
                    if (! $r) {
                        $rd = $rolesData[$rid] ?? null;
                        if (! $rd || empty($rd['base'])) {
                            return null;
                        }
                        $b = $rd['base'];
                        $s = $rd['status'] ?? [];
                        $r = (object) [
                            'role_id'         => $rid,
                            'role_name'       => $b['name'] ?? '?',
                            'role_level'      => (int) ($s['level'] ?? 0),
                            'role_occupation' => (int) ($b['cls'] ?? 0),
                            'role_gender'     => (int) ($b['gender'] ?? 0),
                            'role_spouse'     => 0,
                            'faction_name'    => null,
                            'faction_level'   => 0,
                            'pvp_kills'      => 0,
                            'pvp_deads'      => 0,
                        ];
                    }
                    $rd = $rolesData[$r->role_id] ?? null;

                    return $mapChar($r, $rd);
                })
                ->filter()
                ->values();
            if (count($order) > 0) {
                $characters = $characters
                    ->sortBy(fn ($c) => $order[$c->role_id] ?? 999)
                    ->values();
            }

            // Cubi Coins (real-time from gamedbd via GetUser RPC)
            // cash_add = total ever topped up, cash_used = total spent, cash_buy/sell = trade
            $userCash = $gameDb->getUserCash($user->ID);
            if ($userCash) {
                $cubiCoins = ($userCash['cash_add'] + $userCash['cash_buy']
                            - $userCash['cash_used'] - $userCash['cash_sell']) / 100;
            }
        } catch (\Throwable $e) {
            // Game DB unavailable
        }

        // Check & process referral reward
        $this->processReferralReward($user, $characters);

        // Referral stats for profile page
        $referralStats = null;
        if (config('pw-config.referral.enabled')) {
            $referrals    = $user->referrals()->get(['ID', 'name', 'truename', 'creatime']);
            $rewardedIds  = $user->referralRewards()->pluck('referred_id')->toArray();
            $partner      = ReferralPartner::where('user_id', $user->ID)->where('is_active', true)->first();
            $referredIds  = $referrals->pluck('ID')->toArray();

            // Requirements (partner overrides global for level, cultivation is always global)
            $reqLevel = $partner ? $partner->min_char_level : (int) config('pw-config.referral.min_char_level', 1);
            $reqCult  = (int) config('pw-config.referral.min_cultivation', 0);
            // Normalize legacy 30/31/32 → 20/21/22
            if (in_array($reqCult, [30, 31, 32])) {
                $reqCult -= 10;
            }

            $cultNameMap = [
                0=>'—', 1=>'Autoscopy', 2=>'Transform', 3=>'Naissance', 4=>'Reborn',
                5=>'Vigilance', 6=>'Doom', 7=>'Disengage', 8=>'Nirvana',
                20=>'Prime Immortal', 21=>'Pure Immortal', 22=>'Ether Immortal',
                30=>'Daimon Baresark', 31=>'Daimon Saint', 32=>'Daimon Elder',
            ];

            // Max level per referred: rentang char [referredId .. referredId+slot-1] bila mode slot, else account_id
            $maxLevelMap = [];
            if (! empty($referredIds)) {
                try {
                    $refSlotBounds = User::gameCharacterRoleIdSlotBoundsForUserId((int) $referredIds[0]);
                    if ($refSlotBounds !== null) {
                        $slotsN = (int) $refSlotBounds['slots'];
                        foreach ($referredIds as $refId) {
                            $refId = (int) $refId;
                            $m = DB::connection('mysql_game')
                                ->table('roles')
                                ->whereBetween('role_id', [$refId, $refId + $slotsN - 1])
                                ->max('role_level');
                            if ($m !== null) {
                                $maxLevelMap[$refId] = (int) $m;
                            }
                        }
                    } else {
                        DB::connection('mysql_game')
                            ->table('roles')
                            ->whereIn('account_id', $referredIds)
                            ->selectRaw('account_id, MAX(role_level) as max_level')
                            ->groupBy('account_id')
                            ->get()
                            ->each(fn ($r) => $maxLevelMap[$r->account_id] = (int) $r->max_level);
                    }
                } catch (\Throwable $e) {}
            }

            // Max cultivation per referred (gamedbd) — bila slot, agregasi per rentang
            $maxCultMap = [];
            if ($reqCult > 0 && ! empty($referredIds)) {
                try {
                    $roleIdToAccount = [];
                    $allRoleIds = [];
                    $cultRefSlot = User::gameCharacterRoleIdSlotBoundsForUserId((int) $referredIds[0]);
                    if ($cultRefSlot !== null) {
                        $slotsN = (int) $cultRefSlot['slots'];
                        $q = DB::connection('mysql_game')->table('roles');
                        $q->where(function ($w) use ($referredIds, $slotsN) {
                            foreach ($referredIds as $id) {
                                $id = (int) $id;
                                $w->orWhereBetween('role_id', [$id, $id + $slotsN - 1]);
                            }
                        });
                        $roleRows = $q->get(['role_id']);
                        foreach ($roleRows as $row) {
                            $roleId = (int) $row->role_id;
                            $accId = null;
                            foreach ($referredIds as $refId) {
                                $refId = (int) $refId;
                                if ($roleId >= $refId && $roleId <= $refId + $slotsN - 1) {
                                    $accId = $refId;
                                    break;
                                }
                            }
                            if ($accId) {
                                $roleIdToAccount[$roleId] = $accId;
                                $allRoleIds[] = $roleId;
                            }
                        }
                    } else {
                        $roleRows = DB::connection('mysql_game')
                            ->table('roles')
                            ->whereIn('account_id', $referredIds)
                            ->get(['role_id', 'account_id']);
                        foreach ($roleRows as $row) {
                            $roleIdToAccount[$row->role_id] = $row->account_id;
                            $allRoleIds[] = $row->role_id;
                        }
                    }

                    if (! empty($allRoleIds)) {
                        $gameDb2 = new \App\Services\GameDbService();
                        $rolesData2 = $gameDb2->getRolesData($allRoleIds);
                        foreach ($rolesData2 as $roleId => $rd) {
                            $accId = $roleIdToAccount[$roleId] ?? null;
                            if (! $accId) {
                                continue;
                            }
                            $cult = (int) ($rd['status']['cultivation'] ?? -1);
                            if (! isset($maxCultMap[$accId]) || $cult > $maxCultMap[$accId]) {
                                $maxCultMap[$accId] = $cult;
                            }
                        }
                    }
                } catch (\Throwable $e) {}
            }

            $referralStats = (object) [
                'code'       => $user->referral_code,
                'total'      => $referrals->count(),
                'rewarded'   => count($rewardedIds),
                'pending'    => $referrals->count() - count($rewardedIds),
                'is_partner' => $partner !== null,
                'partner'    => $partner,
                'req_level'  => $reqLevel,
                'req_cult'   => $reqCult,
                'list'       => $referrals->map(function ($r) use ($rewardedIds, $maxLevelMap, $maxCultMap, $reqLevel, $reqCult, $cultNameMap) {
                    $maxLevel = $maxLevelMap[$r->ID] ?? null;
                    $maxCult  = $maxCultMap[$r->ID]  ?? null;
                    $levelOk  = $maxLevel !== null && $maxLevel >= $reqLevel;

                    // Tier-aware cultivation check
                    if ($reqCult === 0) {
                        $cultOk = true;
                    } elseif ($maxCult === null) {
                        $cultOk = false;
                    } elseif ($reqCult <= 8) {
                        $cultOk = $maxCult >= $reqCult;
                    } elseif ($reqCult === 20) {
                        $cultOk = in_array($maxCult, [20, 21, 22, 30, 31, 32]);
                    } elseif ($reqCult === 21) {
                        $cultOk = in_array($maxCult, [21, 22, 31, 32]);
                    } else {
                        $cultOk = in_array($maxCult, [22, 32]);
                    }

                    return (object) [
                        'name'          => $r->truename ?: $r->name,
                        'joined'        => $r->creatime,
                        'rewarded'      => in_array($r->ID, $rewardedIds),
                        'max_level'     => $maxLevel,
                        'level_ok'      => $levelOk,
                        'max_cult'      => $maxCult,
                        'max_cult_name' => $maxCult !== null ? ($cultNameMap[$maxCult] ?? 'Lv.' . $maxCult) : null,
                        'cult_ok'       => $cultOk,
                    ];
                }),
            ];
        }

        // Pre-Launch Event: referral milestone progress
        $preLaunchEvent = null;
        $preLaunchMilestones = collect();
        $preLaunchQualified = 0;

        $activePreLaunch = LaunchEvent::where('type', 'pre_launch')
            ->whereIn('status', ['active', 'ended', 'distributed'])
            ->orderByRaw("FIELD(status, 'active', 'ended', 'distributed')")
            ->latest('start_at')
            ->first();

        if ($activePreLaunch) {
            $preLaunchEvent = $activePreLaunch;
            $reqLevel = $activePreLaunch->referral_req_level ?? 50;

            // Count qualified referrals (referred users with character at req level)
            $referredIds = $user->referrals()->pluck('ID')->toArray();
            if (! empty($referredIds)) {
                try {
                    $plBounds = User::gameCharacterRoleIdSlotBoundsForUserId((int) $referredIds[0]);
                    if ($plBounds !== null) {
                        $plSlotsN = (int) $plBounds['slots'];
                        $preLaunchQualified = 0;
                        foreach ($referredIds as $plRef) {
                            $plRef = (int) $plRef;
                            $m = (int) (DB::connection('mysql_game')
                                ->table('roles')
                                ->whereBetween('role_id', [$plRef, $plRef + $plSlotsN - 1])
                                ->max('role_level') ?? 0);
                            if ($m >= $reqLevel) {
                                $preLaunchQualified++;
                            }
                        }
                    } else {
                        $preLaunchQualified = DB::connection('mysql_game')
                            ->table('roles')
                            ->whereIn('account_id', $referredIds)
                            ->selectRaw('account_id, MAX(role_level) as max_level')
                            ->groupBy('account_id')
                            ->havingRaw('MAX(role_level) >= ?', [$reqLevel])
                            ->count();
                    }
                } catch (\Throwable $e) {}
            }

            // User's distributed milestones for this event
            $preLaunchMilestones = ReferralMilestone::where('event_id', $activePreLaunch->id)
                ->where('user_id', $user->ID)
                ->where('distributed', true)
                ->get();
        }

        return view('front.profile', compact(
            'user', 'characters', 'cubiCoins', 'referralStats',
            'preLaunchEvent', 'preLaunchMilestones', 'preLaunchQualified'
        ));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();

        $request->validate([
            'email'         => ['required', 'email', 'max:255'],
            'mobilenumber'  => ['nullable', 'string', 'max:20'],
        ]);

        DB::table('users')->where('ID', $user->ID)->update([
            'email'        => $request->email,
            'mobilenumber' => $request->mobilenumber,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function changePassword(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'pin'              => ['required', 'string'],
            'new_password'     => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'pin.required'          => 'PIN wajib diisi.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min'      => 'Password baru minimal 6 karakter.',
            'new_password.confirmed'=> 'Konfirmasi password tidak cocok.',
        ]);

        // Validate PIN against qq field
        if ((string) $user->qq !== (string) $request->pin) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => ['pin' => ['PIN yang kamu masukkan salah.']]], 422);
            }
            return back()->withErrors(['pin' => 'PIN yang kamu masukkan salah.'])->withInput();
        }

        $concat = strtolower($user->name) . $request->new_password;
        $hash   = base64_encode(md5($concat, true));

        DB::table('users')->where('ID', $user->ID)->update([
            'passwd'  => $hash,
            'passwd2' => $hash,
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Password berhasil diubah!']);
        }

        return back()->with('password_success', 'Password berhasil diubah!');
    }

    private function processReferralReward($user, $characters): void
    {
        if (! config('pw-config.referral.enabled')) {
            return;
        }

        // Determine if user is a Partner (Streamer/CC) with custom rules
        $partner = ReferralPartner::where('user_id', $user->ID)
            ->where('is_active', true)
            ->first();

        // Partner overrides (Partner settings take priority over global settings)
        $rewardAmount = $partner ? $partner->reward_amount : (int) config('pw-config.referral.reward_gold', 10);
        $rewardType   = $partner ? $partner->reward_type   : config('pw-config.referral.reward_type', 'gold');
        $minCharLevel = $partner ? $partner->min_char_level : (int) config('pw-config.referral.min_char_level', 1);
        $maxPerDay    = $partner ? $partner->max_per_day    : (int) config('pw-config.referral.max_per_day', 0);

        // Min cultivation (global setting only — partners use level check as before)
        $minCultivation = (int) config('pw-config.referral.min_cultivation', 0);

        // Reward for the referred user (penerima) — global setting only
        $referredRewardType   = config('pw-config.referral.referred_reward_type', 'none');
        $referredRewardAmount = (int) config('pw-config.referral.referred_reward_amount', 0);

        // Safety: non-partner referral can only be gold or cubi — never tunai (real money)
        if (! $partner && ! in_array($rewardType, ['gold', 'cubi'])) {
            $rewardType = 'gold';
        }

        $rewardTypeKey = $rewardType === 'cubi' ? 'registration_cubi' : 'registration';

        // Check all users referred by this user and not yet rewarded
        $referredUsers = $user->referrals()
            ->whereNotIn('ID', function ($q) use ($user) {
                $q->select('referred_id')
                    ->from('pw_referral_rewards')
                    ->where('referrer_id', $user->ID)
                    ->whereIn('type', ['registration', 'registration_cubi']);
            })
            ->get();

        // Daily cap — applies to all users (partner uses max_per_day, regular uses global setting)
        // 0 = unlimited
        $dailyRemaining = PHP_INT_MAX;
        if ($maxPerDay > 0) {
            $todayRewardCount = ReferralReward::where('referrer_id', $user->ID)
                ->whereDate('created_at', today())
                ->count();
            if ($todayRewardCount >= $maxPerDay) {
                return; // Daily limit reached
            }
            $dailyRemaining = $maxPerDay - $todayRewardCount;
        }

        // Partner-only: total cap check
        if ($partner && $partner->max_total) {
            $totalRewardCount = ReferralReward::where('referrer_id', $user->ID)->count();
            if ($totalRewardCount >= $partner->max_total) {
                return;
            }
        }

        $rewardedCount = 0;
        foreach ($referredUsers as $referred) {
            // Daily cap per iteration (applies to all users)
            if ($rewardedCount >= $dailyRemaining) {
                break;
            }

            // Partner-only total cap per iteration
            if ($partner && $partner->max_total) {
                $totalSoFar = ReferralReward::where('referrer_id', $user->ID)->count();
                if ($totalSoFar >= $partner->max_total) {
                    break;
                }
            }

            // Partner: IP unique check — skip if same IP already rewarded
            if ($partner && $partner->ip_unique_only && $referred->register_ip) {
                $ipAlreadyRewarded = ReferralReward::where('referrer_id', $user->ID)
                    ->where('referred_ip', $referred->register_ip)
                    ->exists();
                if ($ipAlreadyRewarded) {
                    continue;
                }
            }

            // ── Check character level (MySQL): rentang slot [userId..max] atau account_id
            try {
                $rb = User::gameCharacterRoleIdSlotBoundsForUserId((int) $referred->ID);
                if ($rb !== null) {
                    $rMin = (int) $rb['min'];
                    $rMax = (int) $rb['max'];
                    if ($minCharLevel > 1) {
                        $hasQualified = DB::connection('mysql_game')
                            ->table('roles')
                            ->whereBetween('role_id', [$rMin, $rMax])
                            ->where('role_level', '>=', $minCharLevel)
                            ->exists();
                    } else {
                        $hasQualified = DB::connection('mysql_game')
                            ->table('roles')
                            ->whereBetween('role_id', [$rMin, $rMax])
                            ->exists();
                    }
                } elseif ($minCharLevel > 1) {
                    $hasQualified = DB::connection('mysql_game')
                        ->table('roles')
                        ->where('account_id', $referred->ID)
                        ->where('role_level', '>=', $minCharLevel)
                        ->exists();
                } else {
                    $hasQualified = DB::connection('mysql_game')
                        ->table('roles')
                        ->where('account_id', $referred->ID)
                        ->exists();
                }
            } catch (\Throwable $e) {
                continue;
            }

            if (! $hasQualified) {
                continue;
            }

            // ── Check cultivation requirement (gamedbd) ──
            if ($minCultivation > 0) {
                try {
                    $rBounds = User::gameCharacterRoleIdSlotBoundsForUserId((int) $referred->ID);
                    if ($rBounds !== null) {
                        $refRoleIds = DB::connection('mysql_game')
                            ->table('roles')
                            ->whereBetween('role_id', [(int) $rBounds['min'], (int) $rBounds['max']])
                            ->pluck('role_id')
                            ->toArray();
                    } else {
                        $refRoleIds = DB::connection('mysql_game')
                            ->table('roles')
                            ->where('account_id', $referred->ID)
                            ->pluck('role_id')
                            ->toArray();
                    }

                    if (empty($refRoleIds)) {
                        continue;
                    }

                    $gameDb = new \App\Services\GameDbService();
                    $refRolesData = $gameDb->getRolesData($refRoleIds);

                    $hasCultivation = false;
                    foreach ($refRolesData as $rd) {
                        $cult = (int) ($rd['status']['cultivation'] ?? -1);
                        // Tier-aware check: cultivations branch into two paths (20-22 pure, 30-32 daimon)
                        // but both paths at the same tier are considered equivalent.
                        if ($minCultivation <= 8) {
                            // Linear tier: char qualifies if >= minCult, OR already on either branch (>= 20)
                            $meets = $cult >= $minCultivation;
                        } elseif ($minCultivation === 20) {
                            $meets = in_array($cult, [20, 21, 22, 30, 31, 32]);
                        } elseif ($minCultivation === 21) {
                            $meets = in_array($cult, [21, 22, 31, 32]);
                        } elseif ($minCultivation === 22) {
                            $meets = in_array($cult, [22, 32]);
                        } else {
                            $meets = false;
                        }
                        if ($meets) {
                            $hasCultivation = true;
                            break;
                        }
                    }

                    if (! $hasCultivation) {
                        continue;
                    }
                } catch (\Throwable $e) {
                    // gamedbd offline — skip, will retry on next login
                    continue;
                }
            }

            // ── Deliver referrer reward ──
            if ($rewardType === 'cubi') {
                $cashValue = $rewardAmount * 100;
                try {
                    DB::connection('mysql_game')->transaction(function () use ($user, $cashValue) {
                        $nextSn = (DB::connection('mysql_game')
                            ->table('usecashnow')
                            ->where('userid', $user->ID)
                            ->where('zoneid', 1)
                            ->min('sn') ?? 0) - 1;

                        DB::connection('mysql_game')->table('usecashnow')->insert([
                            'userid'   => $user->ID,
                            'zoneid'   => 1,
                            'sn'       => $nextSn,
                            'aid'      => 1,
                            'point'    => 0,
                            'cash'     => $cashValue,
                            'status'   => 0,
                            'creatime' => now(),
                        ]);
                        // usecashlog is written by the billing daemon after delivery.
                    });
                } catch (\Throwable $e) {
                    continue;
                }
            } elseif ($rewardType === 'gold') {
                DB::table('users')
                    ->where('ID', $user->ID)
                    ->increment('money', $rewardAmount);
            }
            // 'tunai': no in-game delivery — just recorded in pw_referral_rewards below.
            // Partner claims earnings via the withdrawal (bonus claim) system.

            // Record referrer reward
            ReferralReward::create([
                'referrer_id'   => $user->ID,
                'referred_id'   => $referred->ID,
                'type'          => $rewardTypeKey,
                'reward_amount' => $rewardAmount,
                'referred_ip'   => $referred->register_ip,
            ]);

            $rewardedCount++;

            // ── Deliver penerima (referred user) reward ──
            // Only if configured and not already given to this referred user
            if ($referredRewardType !== 'none' && $referredRewardAmount > 0) {
                $alreadyGiven = ReferralReward::where('referred_id', $referred->ID)
                    ->where('type', 'signup_bonus')
                    ->exists();

                if (! $alreadyGiven) {
                    if ($referredRewardType === 'cubi') {
                        $refCashValue = $referredRewardAmount * 100;
                        try {
                            DB::connection('mysql_game')->transaction(function () use ($referred, $refCashValue) {
                                $nextSn = (DB::connection('mysql_game')
                                    ->table('usecashnow')
                                    ->where('userid', $referred->ID)
                                    ->where('zoneid', 1)
                                    ->min('sn') ?? 0) - 1;

                                DB::connection('mysql_game')->table('usecashnow')->insert([
                                    'userid'   => $referred->ID,
                                    'zoneid'   => 1,
                                    'sn'       => $nextSn,
                                    'aid'      => 1,
                                    'point'    => 0,
                                    'cash'     => $refCashValue,
                                    'status'   => 0,
                                    'creatime' => now(),
                                ]);
                                // usecashlog is written by the billing daemon after delivery.
                            });

                            ReferralReward::create([
                                'referrer_id'   => $user->ID,
                                'referred_id'   => $referred->ID,
                                'type'          => 'signup_bonus',
                                'reward_amount' => $referredRewardAmount,
                                'referred_ip'   => $referred->register_ip,
                            ]);
                        } catch (\Throwable $e) {
                            // gamedbd offline — skip, will retry on next login
                        }
                    } else {
                        // Gold to referred user
                        DB::table('users')
                            ->where('ID', $referred->ID)
                            ->increment('money', $referredRewardAmount);

                        ReferralReward::create([
                            'referrer_id'   => $user->ID,
                            'referred_id'   => $referred->ID,
                            'type'          => 'signup_bonus',
                            'reward_amount' => $referredRewardAmount,
                            'referred_ip'   => $referred->register_ip,
                        ]);
                    }
                }
            }
        }
    }
}
