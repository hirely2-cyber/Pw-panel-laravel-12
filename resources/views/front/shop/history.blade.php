@extends('layouts.app')

@php
    $__siteName = \App\Models\Setting::get('seo_title') ?: config('pw-config.server.name') ?: 'Perfect World';
@endphp

@section('title', 'Riwayat Pembelian — ' . $__siteName)

@section('content')

{{-- ============================================================
     PAGE HERO
============================================================ --}}
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
        <h1 class="pw-page-hero__title">Riwayat Pembelian</h1>
        <p class="pw-page-hero__sub">Semua transaksi item yang pernah kamu lakukan</p>
        <nav class="pw-breadcrumb" aria-label="breadcrumb">
            <a href="{{ route('home') }}" class="pw-breadcrumb__item">
                <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true">
                    <path d="M2 7.5L8 2l6 5.5V14a1 1 0 01-1 1h-3.5v-3.5h-3V15H3a1 1 0 01-1-1V7.5z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/>
                </svg>
                Beranda
            </a>
            <span class="pw-breadcrumb__sep" aria-hidden="true">/</span>
            <a href="{{ route('shop') }}" class="pw-breadcrumb__item">Item Shop</a>
            <span class="pw-breadcrumb__sep" aria-hidden="true">/</span>
            <span class="pw-breadcrumb__item pw-breadcrumb__item--active">Riwayat</span>
        </nav>
    </div>
</div>

{{-- ============================================================
     HISTORY CONTENT
============================================================ --}}
<section class="pw-section">
    <div class="pw-section__inner pw-section__inner--narrow">

        {{-- Back + summary bar --}}
        <div class="pw-shist-topbar">
            <a href="{{ route('shop') }}" class="pw-btn pw-btn--ghost pw-btn--sm">
                <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true">
                    <path d="M10 3L5 8l5 5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Kembali ke Shop
            </a>
            @if(!$logs->isEmpty())
            <span class="pw-shist-topbar__total">
                {{ $logs->total() }} transaksi
            </span>
            @endif
        </div>

        @forelse($logs as $log)

        {{-- Log Card --}}
        <div class="pw-shist-card">
            {{-- Icon / thumb --}}
            <div class="pw-shist-card__icon">
                @if($log->item && $log->item->image)
                    <img src="{{ Storage::url($log->item->image) }}" alt="{{ $log->item_name }}" loading="lazy">
                @else
                <svg viewBox="0 0 32 32" fill="none" width="22" aria-hidden="true">
                    <rect x="3" y="9" width="26" height="19" rx="2" stroke="#c8972a" stroke-width="1.3" opacity=".4"/>
                    <path d="M11 9V7a5 5 0 0110 0v2" stroke="#c8972a" stroke-width="1.3" stroke-linecap="round" opacity=".4"/>
                    <circle cx="16" cy="18" r="3" stroke="#c8972a" stroke-width="1.2" opacity=".4"/>
                </svg>
                @endif
            </div>

            {{-- Info --}}
            <div class="pw-shist-card__info">
                <div class="pw-shist-card__name">{{ $log->item_name }}</div>
                @if($log->recipient)
                <div class="pw-shist-card__meta">
                    <svg viewBox="0 0 16 16" fill="none" width="11" aria-hidden="true">
                        <circle cx="8" cy="5" r="3" stroke="currentColor" stroke-width="1.2"/>
                        <path d="M2 14c0-3.3 2.7-6 6-6s6 2.7 6 6" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                    </svg>
                    {{ $log->recipient }}
                </div>
                @endif
            </div>

            {{-- Right side --}}
            <div class="pw-shist-card__right">
                <div class="pw-shist-card__price">
                    <img src="{{ asset('images/gif_icon/web_coin.gif') }}" alt="Gold Points" width="14" height="14" style="vertical-align:middle;">
                    {{ number_format($log->price) }}
                    <span class="pw-shist-card__price-unit">Gold Points</span>
                </div>
                @if($log->quantity > 1)
                <div class="pw-shist-card__qty">×{{ $log->quantity }}</div>
                @endif
                <div class="pw-shist-card__date">{{ $log->created_at->translatedFormat('d M Y, H:i') }}</div>
                @php
                    $statusMap = [
                        'success'  => ['label' => 'Berhasil',  'class' => 'pw-shist-badge--success'],
                        'pending'  => ['label' => 'Pending',   'class' => 'pw-shist-badge--pending'],
                        'failed'   => ['label' => 'Gagal',     'class' => 'pw-shist-badge--failed'],
                    ];
                    $s = $statusMap[$log->status] ?? ['label' => ucfirst($log->status ?? '-'), 'class' => 'pw-shist-badge--pending'];
                @endphp
                <span class="pw-shist-badge {{ $s['class'] }}">{{ $s['label'] }}</span>
            </div>
        </div>

        @empty
        <div class="pw-shop-empty">
            <svg viewBox="0 0 64 64" fill="none" width="52" aria-hidden="true">
                <rect x="8" y="18" width="48" height="38" rx="4" stroke="#c8972a" stroke-width="1.8" opacity=".4"/>
                <path d="M22 18v-4a10 10 0 0120 0v4" stroke="#c8972a" stroke-width="1.8" stroke-linecap="round" opacity=".5"/>
                <circle cx="32" cy="37" r="4" stroke="#c8972a" stroke-width="1.5" opacity=".5"/>
            </svg>
            <p>Belum ada riwayat pembelian.</p>
            <a href="{{ route('shop') }}" class="pw-btn pw-btn--gold pw-btn--sm" style="margin-top:.4rem;">Mulai Belanja</a>
        </div>
        @endforelse

        @if($logs->hasPages())
        <div style="margin-top:1rem;">
            {{ $logs->links() }}
        </div>
        @endif

    </div>
</section>

@endsection
