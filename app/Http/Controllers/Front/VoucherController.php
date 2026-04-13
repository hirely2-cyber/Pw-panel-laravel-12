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
use App\Models\Voucher;
use App\Models\VoucherLog;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class VoucherController extends Controller
{
    public function index(): View
    {
        $usageLogs = VoucherLog::with(['voucher', 'user'])
            ->latest()
            ->paginate(10, ['*'], 'usage_page');

        return view('front.voucher.index', compact('usageLogs'));
    }

    public function redeem(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'max:64', 'regex:/^[A-Z0-9]+$/'],
        ]);

        $user = $request->user();
        $code = strtoupper((string) $request->code);

        $voucher = Voucher::where('code', $code)->first();

        if (! $voucher) {
            return back()->with('error', __('main.voucher_error_not_found'));
        }

        if (! $voucher->is_active) {
            return back()->with('error', __('main.voucher_error_inactive'));
        }

        if ($voucher->expires_at && $voucher->expires_at->isPast()) {
            return back()->with('error', __('main.voucher_error_expired_at', [
                'date' => $voucher->expires_at->translatedFormat('d M Y H:i'),
            ]));
        }

        if ($voucher->max_uses !== null && $voucher->used_count >= $voucher->max_uses) {
            return back()->with('error', __('main.voucher_error_quota'));
        }

        // Check if this user already used this voucher
        $alreadyUsed = VoucherLog::where('user_id', $user->ID)
            ->where('voucher_id', $voucher->id)
            ->exists();

        if ($alreadyUsed) {
            return back()->with('error', __('main.voucher_error_already_used'));
        }

        try {
            $rewardText = DB::transaction(function () use ($user, $voucher, $code) {
                $lockedVoucher = Voucher::whereKey($voucher->id)->lockForUpdate()->firstOrFail();

                if (! $lockedVoucher->is_active) {
                    throw new \RuntimeException(__('main.voucher_error_inactive'));
                }
                if ($lockedVoucher->expires_at && $lockedVoucher->expires_at->isPast()) {
                    throw new \RuntimeException(__('main.voucher_error_expired_at', [
                        'date' => $lockedVoucher->expires_at->translatedFormat('d M Y H:i'),
                    ]));
                }
                if ($lockedVoucher->max_uses !== null && $lockedVoucher->used_count >= $lockedVoucher->max_uses) {
                    throw new \RuntimeException(__('main.voucher_error_quota'));
                }

                $alreadyRedeemed = VoucherLog::where('user_id', $user->ID)
                    ->where('voucher_id', $lockedVoucher->id)
                    ->exists();
                if ($alreadyRedeemed) {
                    throw new \RuntimeException(__('main.voucher_error_already_used'));
                }

                $type = $lockedVoucher->normalized_type;
                $value = (int) $lockedVoucher->value;

                if ($type === Voucher::TYPE_CUBI) {
                    $cashValue = $value * 100;
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

                    DB::table('pw_admin_cubi_topups')->insert([
                        'user_id'    => $user->ID,
                        'admin_id'   => 0,
                        'amount'     => $value,
                        'reason'     => 'Voucher:' . $code,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    DB::table('users')->where('ID', $user->ID)->increment('money', $value);
                }

                VoucherLog::create([
                    'user_id'        => $user->ID,
                    'voucher_id'     => $lockedVoucher->id,
                    'value_received' => $value,
                ]);

                $lockedVoucher->increment('used_count');

                return $type === Voucher::TYPE_CUBI
                    ? __('main.voucher_reward_cubi', ['value' => number_format($value)])
                    : __('main.voucher_reward_gold_points', ['value' => number_format($value)]);
            });

            return back()->with('success', __('main.voucher_success', ['reward' => $rewardText]));
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return back()->with('error', __('main.voucher_error_already_used'));
            }

            throw $e;
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
