<?php

/*
 * @author   Wahyu Suhandi <andietz.orion@gmail.com>
 * @link     https://wa.me/6208118719377
 * @project  Perfect World Panel
 * @version  2.0.0
 * @license  MIT
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use App\Models\VoucherLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class VoucherController extends Controller
{
    public function index(): View
    {
        $vouchers = Voucher::query()
            ->withCount('logs')
            ->withMax('logs', 'created_at')
            ->latest()
            ->paginate(30, ['*'], 'voucher_page');
        $recentLogs = VoucherLog::query()
            ->with(['user', 'voucher'])
            ->latest()
            ->paginate(20, ['*'], 'usage_page');

        return view('admin.voucher.index', compact('vouchers', 'recentLogs'));
    }

    public function create(): View
    {
        return view('admin.voucher.form', ['voucher' => new Voucher()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'description' => ['nullable', 'string', 'max:255'],
            'type'        => ['required', 'in:cubi,gold_points,gold'],
            'value'       => ['required', 'integer', 'min:1', 'max:999999999'],
            'max_uses'    => ['nullable', 'integer', 'min:1', 'max:1000000'],
            'expires_at'  => ['nullable', 'date'],
            'is_active'   => ['nullable', 'boolean'],
        ]);

        $data['code'] = $this->generateUniqueCode();
        $data['type'] = $this->normalizeType((string) $data['type']);
        $data['is_active'] = $request->boolean('is_active', true);

        Voucher::create($data);

        return redirect()->route('admin.voucher.index')->with('success', 'Voucher berhasil dibuat. Code: ' . $data['code']);
    }

    public function generate(Request $request): RedirectResponse
    {
        $request->validate([
            'count'       => ['required', 'integer', 'min:1', 'max:500'],
            'description' => ['nullable', 'string', 'max:255'],
            'type'        => ['required', 'in:cubi,gold_points,gold'],
            'value'       => ['required', 'integer', 'min:1', 'max:999999999'],
            'max_uses'    => ['nullable', 'integer', 'min:1', 'max:1000000'],
            'expires_at'  => ['nullable', 'date'],
        ]);

        $payload = [
            'description' => $request->input('description'),
            'type'        => $this->normalizeType((string) $request->input('type')),
            'value'       => (int) $request->input('value'),
            'max_uses'    => $request->filled('max_uses') ? (int) $request->input('max_uses') : null,
            'expires_at'  => $request->input('expires_at'),
            'is_active'   => true,
        ];

        for ($i = 0; $i < (int) $request->count; $i++) {
            $payload['code'] = $this->generateUniqueCode();
            Voucher::create($payload);
        }

        return redirect()->route('admin.voucher.index')
            ->with('success', $request->count . ' voucher berhasil digenerate.');
    }

    public function edit(Voucher $voucher): View
    {
        return view('admin.voucher.form', compact('voucher'));
    }

    public function update(Request $request, Voucher $voucher): RedirectResponse
    {
        $data = $request->validate([
            'description' => ['nullable', 'string', 'max:255'],
            'type'        => ['required', 'in:cubi,gold_points,gold'],
            'value'       => ['required', 'integer', 'min:1', 'max:999999999'],
            'max_uses'    => ['nullable', 'integer', 'min:1', 'max:1000000'],
            'expires_at'  => ['nullable', 'date'],
            'is_active'   => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['type'] = $this->normalizeType((string) $data['type']);
        $voucher->update($data);

        return redirect()->route('admin.voucher.index')->with('success', 'Voucher diperbarui.');
    }

    public function destroy(Voucher $voucher): RedirectResponse
    {
        $voucher->delete();
        return redirect()->route('admin.voucher.index')->with('success', 'Voucher dihapus.');
    }

    private function generateUniqueCode(): string
    {
        do {
            $code = strtoupper(Str::random(16));
        } while (Voucher::where('code', $code)->exists());

        return $code;
    }

    private function normalizeType(string $type): string
    {
        return in_array($type, ['gold', Voucher::TYPE_GOLD_POINTS], true)
            ? Voucher::TYPE_GOLD_POINTS
            : Voucher::TYPE_CUBI;
    }
}
