@extends('layouts.admin')
@section('title', 'Donate / Invoice')

@section('content')
{{-- Flash messages --}}
@if(session('success'))
<div style="margin-bottom:1rem;padding:.8rem 1rem;background:rgba(80,200,100,.15);border:1px solid rgba(80,200,100,.4);border-radius:8px;color:#4caf50;font-size:.85rem;">
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div style="margin-bottom:1rem;padding:.8rem 1rem;background:rgba(220,60,60,.15);border:1px solid rgba(220,60,60,.4);border-radius:8px;color:#e05252;font-size:.85rem;">
    {{ session('error') }}
</div>
@endif

{{-- Summary Cards --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem;">
    <div class="pw-adm-card" style="text-align:center;">
        <div style="font-size:1.4rem;font-weight:700;color:#b89d4f;">{{ number_format($summary['total_gold_paid']) }}</div>
        <div style="font-size:.72rem;color:var(--pw-text-muted);text-transform:uppercase;margin-top:.2rem;">Total Gold Points Terjual</div>
    </div>
    <div class="pw-adm-card" style="text-align:center;">
        <div style="font-size:1.4rem;font-weight:700;color:#63b3ed;">{{ number_format($summary['total_cubi_paid']) }}</div>
        <div style="font-size:.72rem;color:var(--pw-text-muted);text-transform:uppercase;margin-top:.2rem;">Total Cubi Gold Terjual</div>
    </div>
    <div class="pw-adm-card" style="text-align:center;">
        <div style="font-size:1.4rem;font-weight:700;color:#e05252;">{{ $summary['pending_count'] }}</div>
        <div style="font-size:.72rem;color:var(--pw-text-muted);text-transform:uppercase;margin-top:.2rem;">Invoice Pending</div>
    </div>
    <div class="pw-adm-card" style="text-align:center;">
        <div style="font-size:1.4rem;font-weight:700;">{{ $invoices->total() }}</div>
        <div style="font-size:.72rem;color:var(--pw-text-muted);text-transform:uppercase;margin-top:.2rem;">Total Invoice</div>
    </div>
</div>

<div class="pw-adm-card">
    {{-- Filters --}}
    <form method="GET" style="display:flex;gap:.6rem;flex-wrap:wrap;margin-bottom:1.2rem;">
        <input type="text" name="search" class="pw-form__input" style="max-width:220px;"
               placeholder="Cari invoice / username…" value="{{ request('search') }}">
        <select name="status" class="pw-form__input" style="max-width:140px;">
            <option value="">Semua Status</option>
            <option value="paid"    @selected(request('status')=='paid')>Paid</option>
            <option value="pending" @selected(request('status')=='pending')>Pending</option>
            <option value="failed"  @selected(request('status')=='failed')>Gagal</option>
        </select>
        <button type="submit" class="pw-adm-btn">Filter</button>
        @if(request()->anyFilled(['search','status']))
            <a href="{{ route('admin.donate') }}" class="pw-adm-btn pw-adm-btn--ghost">Reset</a>
        @endif
    </form>

    <div class="pw-table-wrap">
        <table class="pw-table">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>User</th>
                    <th>Produk</th>
                    <th>Nominal (Rp)</th>
                    <th>Metode</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $inv)
                @php
                    $isCubi = $inv->type === 'cubi';
                    $productLabel = $isCubi ? 'Cubi Gold' : 'Gold Points';
                    $productAmount = $isCubi ? (int) $inv->cubi_amount : (int) $inv->gold_amount;
                @endphp
                <tr>
                    <td style="font-size:.75rem;color:var(--pw-text-muted);">{{ $inv->invoice_number }}</td>
                    <td>{{ $inv->user->name ?? '-' }}</td>
                    <td>
                        <strong style="color:{{ $isCubi ? '#63b3ed' : '#b89d4f' }};">{{ number_format($productAmount) }}</strong>
                        <span style="color:var(--pw-text-muted);font-size:.72rem;">{{ $productLabel }}</span>
                    </td>
                    <td>{{ number_format($inv->unique_amount) }}</td>
                    <td style="font-size:.78rem;text-transform:uppercase;">{{ $inv->channel_type ?? '—' }}</td>
                    <td>
                        @if($inv->status === 'paid')
                            <span class="pw-badge pw-badge--success">Paid</span>
                        @elseif($inv->status === 'pending')
                            <span class="pw-badge pw-badge--warning">Pending</span>
                        @elseif($inv->status === 'expired')
                            <span class="pw-badge" style="background:rgba(249,115,22,.15);color:#fb923c;border:1px solid rgba(249,115,22,.3);">Waktu Habis</span>
                        @else
                            <span class="pw-badge pw-badge--danger">Gagal</span>
                        @endif
                    </td>
                    <td style="font-size:.75rem;color:var(--pw-text-muted);">{{ $inv->created_at->format('d M Y H:i') }}</td>
                    <td>
                        <div style="display:flex;gap:.4rem;align-items:center;">
                            <a href="{{ route('admin.donate.show', $inv->id) }}" class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--ghost" style="font-size:.75rem;">Detail</a>
                            @if($inv->status === 'pending')
                            <form method="POST" action="{{ route('admin.donate.approve', $inv->id) }}"
                                    data-confirm="Approve Invoice|Kirim {{ number_format($productAmount) }} {{ $productLabel }} ke {{ $inv->user->name ?? 'user' }}?"
                                  data-confirm-variant="success"
                                  data-confirm-ok="Ya, Approve">
                                @csrf
                                <button type="submit" title="Approve" style="display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:6px;border:none;background:#1b5e20;cursor:pointer;transition:background .15s;" onmouseover="this.style.background='#2e7d32'" onmouseout="this.style.background='#1b5e20'">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#4caf50" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.donate.reject', $inv->id) }}"
                                  data-confirm="Tolak Invoice|Invoice {{ $inv->invoice_number }} akan ditolak dan Gold Points tidak dikreditkan."
                                  data-confirm-ok="Ya, Tolak">
                                @csrf
                                <button type="submit" title="Reject" style="display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:6px;border:none;background:#7f1d1d;cursor:pointer;transition:background .15s;" onmouseover="this.style.background='#c62828'" onmouseout="this.style.background='#7f1d1d'">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#ef5350" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;color:var(--pw-text-muted);">Belum ada data.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:1rem;">{{ $invoices->withQueryString()->links() }}</div>
</div>
@endsection
