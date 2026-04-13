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
use App\Models\VoteSite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VoteController extends Controller
{
    public function index(): View
    {
        $sites = VoteSite::orderBy('sort_order')->get();
        return view('admin.vote.index', compact('sites'));
    }

    public function create(): View
    {
        return view('admin.vote.form', ['site' => new VoteSite()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'url'         => ['required', 'url', 'max:255'],
            'reward'      => ['required', 'integer', 'min:1'],
            'sort_order'  => ['nullable', 'integer'],
            'is_active'   => ['boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        VoteSite::create($data);

        return redirect()->route('admin.vote.index')->with('success', 'Site vote berhasil ditambahkan.');
    }

    public function edit(VoteSite $vote): View
    {
        return view('admin.vote.form', ['site' => $vote]);
    }

    public function update(Request $request, VoteSite $vote): RedirectResponse
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'url'         => ['required', 'url', 'max:255'],
            'reward'      => ['required', 'integer', 'min:1'],
            'sort_order'  => ['nullable', 'integer'],
            'is_active'   => ['boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        $vote->update($data);

        return redirect()->route('admin.vote.index')->with('success', 'Site vote berhasil diperbarui.');
    }

    public function destroy(VoteSite $vote): RedirectResponse
    {
        $vote->delete();
        return redirect()->route('admin.vote.index')->with('success', 'Site vote dihapus.');
    }
}
