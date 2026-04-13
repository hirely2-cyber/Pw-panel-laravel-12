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
use App\Models\Service;
use App\Models\ServiceLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::active()->orderBy('sort_order')->orderBy('type')->get();
        return view('front.service.index', compact('services'));
    }

    public function order(Request $request, Service $service): RedirectResponse
    {
        if (! $service->is_active) {
            return back()->with('error', 'Layanan tidak tersedia.');
        }

        $request->validate(array_merge(
            ['character_name' => ['required', 'string', 'max:64']],
            $service->fields ?? []
        ));

        $user = $request->user();

        if ($user->money < $service->price) {
            return back()->with('error', 'Gold tidak cukup. Butuh ' . $service->price . ' ' . config('pw-config.currency.name') . '.');
        }

        DB::transaction(function () use ($user, $service, $request) {
            $user->decrement('money', $service->price);

            ServiceLog::create([
                'user_id'      => $user->ID,
                'service_id'   => $service->id,
                'service_name' => $service->name,
                'price'        => $service->price,
                'data'         => array_merge(
                    ['character_name' => $request->character_name],
                    $request->except(['_token', 'character_name'])
                ),
                'status'       => ServiceLog::STATUS_PENDING,
            ]);
        });

        return back()->with('success', 'Pesanan layanan "' . $service->name . '" berhasil dibuat. GM akan memproses dalam 1x24 jam.');
    }

    public function history(Request $request): View
    {
        $logs = ServiceLog::where('user_id', $request->user()->ID)
            ->with('service')
            ->latest()
            ->paginate(20);

        return view('front.service.history', compact('logs'));
    }
}
