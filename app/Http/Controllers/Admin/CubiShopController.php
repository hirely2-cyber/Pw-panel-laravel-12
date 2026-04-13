<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CubiPackage;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CubiShopController extends Controller
{
    public function index(Request $request): View
    {
        $packages = CubiPackage::orderBy('sort_order')->orderBy('price_idr')->get();

        $query = Invoice::with(['user:ID,name', 'partner:ID,name'])
            ->where('type', 'cubi')
            ->where('status', Invoice::STATUS_PAID);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn ($sub) => $sub->where('name', 'like', "%{$search}%"))
                  ->orWhere('refcode', 'like', "%{$search}%")
                  ->orWhere('invoice_number', 'like', "%{$search}%");
            });
        }

        $transactions = $query->orderByDesc('paid_at')->paginate(20)->withQueryString();

        // Stats
        $totalSales        = Invoice::where('type', 'cubi')->where('status', 'paid')->count();
        $totalRevenue      = Invoice::where('type', 'cubi')->where('status', 'paid')->sum('amount');
        $totalDiscount     = Invoice::where('type', 'cubi')->where('status', 'paid')->whereNotNull('refcode')->sum('discount_amount');
        $totalCommission   = Invoice::where('type', 'cubi')->where('status', 'paid')->sum('commission_amount');
        $totalCubiSold     = Invoice::where('type', 'cubi')->where('status', 'paid')->sum('cubi_amount');

        return view('admin.cubi-shop.index', compact(
            'packages', 'transactions',
            'totalSales', 'totalRevenue', 'totalDiscount', 'totalCommission', 'totalCubiSold'
        ));
    }

    public function storePackage(Request $request)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'cubi_amount' => ['required', 'integer', 'min:1'],
            'price_idr'   => ['required', 'integer', 'min:1000'],
            'bonus_cubi'  => ['nullable', 'integer', 'min:0'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
        ]);

        CubiPackage::create([
            'name'        => $request->name,
            'cubi_amount' => $request->cubi_amount,
            'price_idr'   => $request->price_idr,
            'bonus_cubi'  => $request->bonus_cubi ?? 0,
            'sort_order'  => $request->sort_order ?? 0,
        ]);

        return back()->with('success', 'Paket Cubi berhasil ditambahkan.');
    }

    public function updatePackage(Request $request, CubiPackage $package)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'cubi_amount' => ['required', 'integer', 'min:1'],
            'price_idr'   => ['required', 'integer', 'min:1000'],
            'bonus_cubi'  => ['nullable', 'integer', 'min:0'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
            'is_active'   => ['nullable'],
        ]);

        $package->update([
            'name'        => $request->name,
            'cubi_amount' => $request->cubi_amount,
            'price_idr'   => $request->price_idr,
            'bonus_cubi'  => $request->bonus_cubi ?? 0,
            'sort_order'  => $request->sort_order ?? 0,
            'is_active'   => $request->has('is_active'),
        ]);

        return back()->with('success', 'Paket Cubi berhasil diperbarui.');
    }

    public function deletePackage(CubiPackage $package)
    {
        $package->delete();
        return back()->with('success', 'Paket Cubi berhasil dihapus.');
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'enabled'            => ['nullable'],
            'discount_percent'   => ['nullable', 'numeric', 'min:0', 'max:100'],
            'commission_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'min_purchase'       => ['nullable', 'integer', 'min:1000'],
            'bonus_multiple'     => ['nullable', 'integer', 'min:1'],
            'bonus_amount'       => ['nullable', 'integer', 'min:0'],
        ]);

        // Only update fields that are present in the request
        if ($request->has('min_purchase')) {
            $this->setEnv('PW_CUBI_SHOP_ENABLED', $request->has('enabled') ? 'true' : 'false');
            $this->setEnv('PW_CUBI_SHOP_MIN_PURCHASE', $request->min_purchase);
            $this->setEnv('PW_CUBI_SHOP_BONUS_MULTIPLE', $request->bonus_multiple);
            $this->setEnv('PW_CUBI_SHOP_BONUS_AMOUNT', $request->bonus_amount);
        }

        if ($request->has('discount_percent')) {
            $this->setEnv('PW_CUBI_SHOP_DISCOUNT_PERCENT', $request->discount_percent);
            $this->setEnv('PW_CUBI_SHOP_COMMISSION_PERCENT', $request->commission_percent);
        }

        return back()->with('success', 'Pengaturan Cubi Shop berhasil disimpan.');
    }

    private function setEnv(string $key, string $value): void
    {
        $envFile = app()->environmentFilePath();
        $content = file_get_contents($envFile);

        if (str_contains($content, "{$key}=")) {
            $content = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $content);
        } else {
            $content .= "\n{$key}={$value}";
        }

        file_put_contents($envFile, $content);
    }
}
