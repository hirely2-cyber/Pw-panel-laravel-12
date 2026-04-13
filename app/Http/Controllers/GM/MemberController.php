<?php

/*
 * @author   Wahyu Suhandi <andietz.orion@gmail.com>
 * @link     https://wa.me/6208118719377
 * @project  Perfect World Panel
 * @version  2.0.0
 * @license  MIT
 */

namespace App\Http\Controllers\GM;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query();

        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $members = $query->with('referrer:ID,name')->latest('ID')->paginate(30);
        return view('gm.members.index', compact('members'));
    }

    public function show(User $user): View
    {
        // Hanya admin yang boleh lihat detail member, GM diblokir
        if (!auth()->user()->isAdministrator()) {
            abort(403, 'GM tidak memiliki akses untuk melihat detail pemain.');
        }

        $user->load('referrer:ID,name');
        return view('gm.members.show', compact('user'));
    }
}
