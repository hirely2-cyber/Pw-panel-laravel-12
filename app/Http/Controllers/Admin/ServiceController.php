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
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::orderBy('type')->orderBy('sort_order')->paginate(20);
        return view('admin.service.index', compact('services'));
    }

    public function create(): View
    {
        return view('admin.service.form', ['service' => new Service()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'type'        => ['required', 'string', 'max:50'],
            'price'       => ['required', 'numeric', 'min:0'],
            'sort_order'  => ['nullable', 'integer'],
        ]);

        $data['price']     = (int) $data['price'];
        $data['is_active'] = $request->input('is_active') === '1';

        Service::create($data);

        return redirect()->route('admin.service.index')->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit(Service $service): View
    {
        return view('admin.service.form', compact('service'));
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'type'        => ['required', 'string', 'max:50'],
            'price'       => ['required', 'numeric', 'min:0'],
            'sort_order'  => ['nullable', 'integer'],
        ]);

        $data['price']     = (int) $data['price'];
        $data['is_active'] = $request->input('is_active') === '1';

        $service->update($data);

        return redirect()->route('admin.service.index')->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();
        return redirect()->route('admin.service.index')->with('success', 'Layanan dihapus.');
    }
}
