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
use App\Models\Invoice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DonateController extends Controller
{
    public function index(Request $request): View
    {
        $query = Invoice::with('user');

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->get('search')) {
            $query->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('user', fn ($q) => $q->where('name', 'like', "%{$search}%"));
        }

        $invoices = $query->latest()->paginate(30);

        $summary = [
            'total_gold_paid' => Invoice::paid()->where('type', 'gold')->sum('gold_amount'),
            'total_cubi_paid' => Invoice::paid()->where('type', 'cubi')->sum('cubi_amount'),
            'pending_count' => Invoice::pending()->count(),
        ];

        return view('admin.donate.index', compact('invoices', 'summary'));
    }

    public function show(Invoice $invoice): View
    {
        $invoice->load('user');
        return view('admin.donate.show', compact('invoice'));
    }

    public function approve(Invoice $invoice): RedirectResponse
    {
        if ($invoice->status !== Invoice::STATUS_PENDING) {
            return back()->with('error', 'Invoice ini sudah diproses sebelumnya.');
        }

        DB::transaction(function () use ($invoice) {
            if ($invoice->type === 'cubi') {
                $cashValue = $invoice->cubi_amount * 100;
                $nextSn = (DB::connection('mysql_game')
                    ->table('usecashnow')
                    ->where('userid', $invoice->user_id)
                    ->where('zoneid', 1)
                    ->min('sn') ?? 0) - 1;

                DB::connection('mysql_game')->table('usecashnow')->insert([
                    'userid'   => $invoice->user_id,
                    'zoneid'   => 1,
                    'sn'       => $nextSn,
                    'aid'      => 1,
                    'point'    => 0,
                    'cash'     => $cashValue,
                    'status'   => 0,
                    'creatime' => now(),
                ]);
            } else {
                DB::table('users')->where('ID', $invoice->user_id)->increment('money', $invoice->gold_amount);

                $nextSn = (DB::table('usecashlog')->where('userid', $invoice->user_id)->max('sn') ?? 0) + 1;
                DB::table('usecashlog')->insert([
                    'userid'   => $invoice->user_id,
                    'zoneid'   => 1,
                    'sn'       => $nextSn,
                    'aid'      => 1,
                    'point'    => 0,
                    'cash'     => $invoice->gold_amount,
                    'status'   => 4,
                    'creatime' => now(),
                    'fintime'  => now(),
                ]);
            }

            $invoice->update([
                'status'         => Invoice::STATUS_PAID,
                'paid_at'        => now(),
                'payment_source' => 'manual_admin',
            ]);
        });

        if ($invoice->type === 'cubi') {
            return back()->with('success', "Invoice #{$invoice->invoice_number} berhasil di-approve. " . number_format($invoice->cubi_amount) . " Cubi Gold dikirim ke antrian game user.");
        }

        return back()->with('success', "Invoice #{$invoice->invoice_number} berhasil di-approve. " . number_format($invoice->gold_amount) . " Gold Points dikreditkan ke user.");
    }

    public function reject(Invoice $invoice): RedirectResponse
    {
        if ($invoice->status !== Invoice::STATUS_PENDING) {
            return back()->with('error', 'Invoice ini sudah diproses sebelumnya.');
        }

        $invoice->update(['status' => Invoice::STATUS_FAILED]);

        return back()->with('success', "Invoice #{$invoice->invoice_number} ditolak.");
    }
}
