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
use App\Models\ShopItem;
use App\Models\ShopLog;
use App\Services\GameApiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(): View
    {
        $items = ShopItem::active()->orderBy('category')->orderBy('sort_order')->get()->groupBy('category');
        return view('front.shop.index', compact('items'));
    }

    public function buy(Request $request, ShopItem $item): RedirectResponse
    {
        if (! $item->is_active) {
            return back()->with('error', 'Item tidak tersedia.');
        }

        $user = $request->user();

        if ($user->money < $item->price) {
            return back()->with('error', 'Gold tidak cukup. Kamu butuh ' . $item->price . ' ' . config('pw-config.currency.name') . '.');
        }

        // Character selection required if item has game item_id
        $activeChar = session('active_character');
        if ($item->item_id && $item->item_id > 0) {
            if (! $activeChar) {
                return back()->with('error', 'Pilih karakter terlebih dahulu di navbar sebelum membeli item.');
            }

            // Verify character belongs to user
            $valid = $user->gameCharacters()->firstWhere('role_id', $activeChar->role_id);
            if (! $valid) {
                return back()->with('error', 'Karakter tidak valid. Pilih ulang karakter kamu.');
            }
        }

        $delivered = false;

        DB::transaction(function () use ($user, $item, $activeChar, &$delivered) {
            // Deduct gold from player
            $user->decrement('money', $item->price);

            // Record in cash log (native PW table)
            DB::table('usecashlog')->insert([
                'userid'     => $user->ID,
                'action'     => 2, // buy from shop
                'cash'       => -$item->price,
                'logtime'    => now()->timestamp,
                'logdate'    => now()->toDateString(),
                'note'       => 'Shop: ' . $item->name,
            ]);

            // Deliver item via in-game mail if item_id is set
            if ($item->item_id && $item->item_id > 0 && $activeChar) {
                $delivered = GameApiService::sendMail(
                    roleId: $activeChar->role_id,
                    title: 'Shop: ' . $item->name,
                    message: 'Pembelian dari Web Shop. Terima kasih!',
                    gold: 0,
                    item: [['id' => $item->item_id, 'pos' => 0, 'count' => $item->item_count ?? 1]]
                );
            }

            // Record in panel shop log
            ShopLog::create([
                'user_id'    => $user->ID,
                'item_id'    => $item->id,
                'item_name'  => $item->name,
                'price'      => $item->price,
                'quantity'   => 1,
                'recipient'  => $activeChar?->name,
                'status'     => ($item->item_id && $item->item_id > 0) ? ($delivered ? 'delivered' : 'pending') : 'completed',
            ]);
        });

        if ($item->item_id && $item->item_id > 0 && ! $delivered) {
            return back()->with('success', 'Berhasil membeli ' . $item->name . '! Item akan dikirim saat server online.');
        }

        $charInfo = $activeChar ? " → {$activeChar->name}" : '';
        return back()->with('success', 'Berhasil membeli ' . $item->name . '!' . $charInfo);
    }

    public function history(Request $request): View
    {
        $logs = ShopLog::where('user_id', $request->user()->ID)
            ->with('item')
            ->latest()
            ->paginate(20);

        return view('front.shop.history', compact('logs'));
    }
}
