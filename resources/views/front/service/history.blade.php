@extends('layouts.app')

@php
    $__siteName = \App\Models\Setting::get('seo_title') ?: config('pw-config.server.name') ?: 'Perfect World';
@endphp

@section('title', 'Riwayat Layanan — ' . $__siteName)

@section('content')

{{-- PAGE HERO --}}
<div class="pw-page-hero">
    <div class="pw-page-hero__bg" aria-hidden="true"></div>
    <div class="pw-page-hero__inner">
        <div class="pw-page-hero__ornament" aria-hidden="true">
            <svg viewBox="0 0 160 20" fill="none" width="140">
                <line x1="0" y1="10" x2="55" y2="10" stroke="#c8972a" stroke-width="1"/>
                <path d="M65 3 L75 10 L65 17 L55 10 Z" fill="#c8972a" opacity=".5"/>
                <path d="M75 3 L85 10 L75 17 L65 10 Z" fill="#c8972a"/>
                <path d="M85 3 L95 10 L85 17 L75 10 Z" fill="#c8972a" opacity=".5"/>
                <line x1="95" y1="10" x2="150" y2="10" stroke="#c8972a" stroke-width="1"/>
            </svg>
        </div>
        <h1 class="pw-page-hero__title">Riwayat Pesanan</h1>
        <p class="pw-page-hero__sub">Semua pesanan layanan karakter yang kamu buat</p>
        <nav class="pw-breadcrumb" aria-label="breadcrumb">
            <a href="{{ route('home') }}" class="pw-breadcrumb__item">
                <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true">
                    <path d="M2 7.5L8 2l6 5.5V14a1 1 0 01-1 1h-3.5v-3.5h-3V15H3a1 1 0 01-1-1V7.5z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/>
                </svg>
                Beranda
            </a>
            <span class="pw-breadcrumb__sep" aria-hidden="true">/</span>
            <a href="{{ route('services') }}" class="pw-breadcrumb__item">Layanan</a>
            <span class="pw-breadcrumb__sep" aria-hidden="true">/</span>
            <span class="pw-breadcrumb__item pw-breadcrumb__item--active">Riwayat</span>
        </nav>
    </div>
</div>

{{-- MAIN CONTENT --}}
<section class="pw-section">
    <div class="pw-section__inner pw-section__inner--narrow">

        <div style="margin-bottom:1rem;">
            <a href="{{ route('services') }}" class="pw-btn pw-btn--ghost pw-btn--sm">
                <svg viewBox="0 0 16 16" fill="none" width="12" aria-hidden="true"><path d="M10 3L5 8l5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Kembali ke Layanan
            </a>
        </div>

        <div class="pw-card" style="padding:1.5rem;">
            @if($logs->isEmpty())
            <div style="text-align:center;padding:2.5rem 1rem;">
                <svg viewBox="0 0 48 48" fill="none" width="44" style="margin:0 auto .8rem;display:block;opacity:.3;"><rect x="8" y="10" width="32" height="30" rx="3" stroke="#c8972a" stroke-width="1.5"/><path d="M16 20h16M16 27h10" stroke="#c8972a" stroke-width="1.3" stroke-linecap="round"/></svg>
                <p style="color:var(--pw-text-muted);font-size:.88rem;">Belum ada riwayat pesanan layanan.</p>
                <a href="{{ route('services') }}" class="pw-btn pw-btn--gold pw-btn--sm" style="margin-top:1rem;display:inline-flex;">Lihat Layanan</a>
            </div>
            @else
            <div class="pw-table-wrap">
                <table class="pw-table">
                    <thead>
                        <tr>
                            <th>Layanan</th>
                            <th>Karakter</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                        <tr>
                            <td><strong>{{ $log->service_name }}</strong></td>
                            <td style="color:var(--pw-text-muted);">{{ $log->data['character_name'] ?? '—' }}</td>
                            <td>
                                @if($log->price > 0)
                                <strong style="color:var(--pw-gold);">{{ number_format($log->price) }} GP</strong>
                                @else
                                <span style="color:#4caf8a;font-weight:600;">Gratis</span>
                                @endif
                            </td>
                            <td>
                                @if($log->status === 'completed')
                                    <span class="pw-badge pw-badge--success">Selesai</span>
                                @elseif($log->status === 'rejected')
                                    <span class="pw-badge pw-badge--danger">Ditolak</span>
                                @else
                                    <span class="pw-badge pw-badge--warning">Pending</span>
                                @endif
                            </td>
                            <td style="color:var(--pw-text-muted);font-size:.78rem;white-space:nowrap;">{{ $log->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="margin-top:1rem;">{{ $logs->links() }}</div>
            @endif
        </div>

    </div>
</section>
@endsection
