@extends('layouts.panel')

@section('title', 'Dashboard')

@section('content')
<div class="pw-adm-content-inner">

    {{-- User Info Card --}}
        <div class="pw-card pw-card--gold" style="margin-bottom:1.5rem;">
            <div style="display:flex; align-items:center; gap:1.5rem; flex-wrap:wrap;">
                <div style="width:64px; height:64px; border-radius:50%; background:linear-gradient(135deg, var(--pw-gold-dark), var(--pw-gold)); display:flex; align-items:center; justify-content:center; font-family:'Cinzel',serif; font-size:1.5rem; font-weight:700; color:#1a1200; flex-shrink:0;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div style="flex:1;">
                    <div style="font-family:'Cinzel',serif; font-size:1.25rem; color:var(--pw-text-light);">{{ $user->name }}</div>
                    <div style="font-size:0.8rem; color:var(--pw-text-muted); margin-top:0.2rem;">{{ $user->email }}</div>
                    <div style="margin-top:0.5rem; display:flex; gap:0.75rem; flex-wrap:wrap;">
                        <span class="pw-badge pw-badge--warning">
                            {{ number_format($user->money) }} {{ config('pw-config.currency.name') }}
                        </span>
                        <span class="pw-badge {{ $user->isAdministrator() ? 'pw-front-role-badge pw-front-role-badge--admin' : ($user->isGamemaster() ? 'pw-front-role-badge pw-front-role-badge--gm' : 'pw-badge--muted') }}">
                            {{ $user->role ?? 'Player' }}
                        </span>
                        @if($user->isOnline())
                        <span class="pw-badge pw-badge--success">Online</span>
                        @endif
                    </div>
                </div>
                @if(config('pw-config.features.donate'))
                <a href="{{ route('cubi-shop') }}" class="pw-btn pw-btn--gold pw-btn--sm">+ Top-up Gold Points</a>
                @endif
            </div>
        </div>

        {{-- Quick Stats --}}
        <div class="pw-stats-grid">
            <div class="pw-stat-card">
                <div class="pw-stat-card__label">Saldo Gold Points</div>
                <div class="pw-stat-card__value">{{ number_format($user->money) }}</div>
            </div>
            <div class="pw-stat-card">
                <div class="pw-stat-card__label">Total Donate</div>
                <div class="pw-stat-card__value">{{ $recentInvoices->where('status', 'paid')->sum('gold_amount') }}</div>
            </div>
            <div class="pw-stat-card">
                <div class="pw-stat-card__label">Total Vote</div>
                <div class="pw-stat-card__value">{{ $recentVoteLogs->count() }}</div>
            </div>
            <div class="pw-stat-card">
                <div class="pw-stat-card__label">Pembelian Shop</div>
                <div class="pw-stat-card__value">{{ $recentShopLogs->count() }}</div>
            </div>
        </div>

        {{-- Recent Transactions --}}
        @if($recentInvoices->count())
        <div class="pw-card" style="margin-bottom:1.5rem;">
            <div class="pw-card__title">Transaksi Terakhir</div>
            <div class="pw-table-wrap">
                <table class="pw-table">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Gold Points</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentInvoices as $inv)
                        <tr>
                            <td><code style="font-size:0.78rem; color:var(--pw-text-muted);">{{ $inv->invoice_number }}</code></td>
                            <td>{{ number_format($inv->gold_amount) }}</td>
                            <td>Rp {{ number_format($inv->unique_amount) }}</td>
                            <td>
                                @if($inv->status === 'paid')
                                    <span class="pw-badge pw-badge--success">Sukses</span>
                                @elseif($inv->status === 'pending')
                                    <span class="pw-badge pw-badge--warning">Menunggu</span>
                                @else
                                    <span class="pw-badge pw-badge--danger">Gagal</span>
                                @endif
                            </td>
                            <td style="color:var(--pw-text-muted);">{{ $inv->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Recent Shop Logs --}}
        @if($recentShopLogs->count())
        <div class="pw-card">
            <div class="pw-card__title">Pembelian Shop Terakhir</div>
            <div class="pw-table-wrap">
                <table class="pw-table">
                    <thead>
                        <tr><th>Item</th><th>Harga</th><th>Tanggal</th></tr>
                    </thead>
                    <tbody>
                        @foreach($recentShopLogs as $log)
                        <tr>
                            <td>{{ $log->item_name }}</td>
                            <td>{{ number_format($log->price) }} Gold Points</td>
                            <td style="color:var(--pw-text-muted);">{{ $log->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
</div>
@endsection

@push('styles')
<style>
.pw-front-role-badge--admin {
    background: rgba(147, 51, 234, .18);
    border-color: rgba(147, 51, 234, .42);
    color: #c084fc;
}

.pw-front-role-badge--gm {
    background: rgba(239, 68, 68, .18);
    border-color: rgba(239, 68, 68, .42);
    color: #f87171;
}
</style>
@endpush
