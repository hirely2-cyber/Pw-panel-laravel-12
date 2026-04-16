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
use App\Models\ShopItem;
use App\Services\ImageOptimizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(): View
    {
        $items = ShopItem::orderBy('category')->orderBy('sort_order')->paginate(30);
        return view('admin.shop.index', compact('items'));
    }

    public function create(): View
    {
        return view('admin.shop.form', ['item' => new ShopItem()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'category'    => ['required', 'string', 'max:50'],
            'price'       => ['required', 'integer', 'min:1'],
            'item_id'     => ['nullable', 'integer', 'min:0'],
            'item_count'  => ['nullable', 'integer', 'min:1'],
            'image'       => ['nullable', 'image', 'max:2048'],
            'sort_order'  => ['nullable', 'integer'],
            'is_active'   => ['boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $data['image'] = ImageOptimizer::storeAsWebp($request->file('image'), 'shop');
        }

        ShopItem::create($data);

        return redirect()->route('admin.shop.index')->with('success', 'Item berhasil ditambahkan.');
    }

    public function edit(ShopItem $shop): View
    {
        return view('admin.shop.form', ['item' => $shop]);
    }

    public function update(Request $request, ShopItem $shop): RedirectResponse
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'category'    => ['required', 'string', 'max:50'],
            'price'       => ['required', 'integer', 'min:1'],
            'item_id'     => ['nullable', 'integer', 'min:0'],
            'item_count'  => ['nullable', 'integer', 'min:1'],
            'image'       => ['nullable', 'image', 'max:2048'],
            'sort_order'  => ['nullable', 'integer'],
            'is_active'   => ['boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $data['image'] = ImageOptimizer::storeAsWebp($request->file('image'), 'shop');
        }

        $shop->update($data);

        return redirect()->route('admin.shop.index')->with('success', 'Item berhasil diperbarui.');
    }

    public function destroy(ShopItem $shop): RedirectResponse
    {
        $shop->delete();
        return redirect()->route('admin.shop.index')->with('success', 'Item dihapus.');
    }

    public function toggle(ShopItem $shop): RedirectResponse
    {
        $shop->update(['is_active' => ! $shop->is_active]);
        $status = $shop->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Item \"{$shop->name}\" berhasil {$status}.");
    }
}
