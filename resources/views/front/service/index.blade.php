@extends('layouts.app')

@php
    $__siteName = \App\Models\Setting::get('seo_title') ?: config('pw-config.server.name') ?: 'Perfect World';
@endphp

@section('title', 'Layanan - ' . $__siteName)
@section('meta_description', 'Pesan layanan karakter seperti ganti nama, pindah guild, dan lainnya di ' . $__siteName)

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
        <h1 class="pw-page-hero__title">Layanan Karakter</h1>
        <p class="pw-page-hero__sub">Pesan layanan khusus karakter kamu - diproses GM dalam 1x24 jam</p>
        <nav class="pw-breadcrumb" aria-label="breadcrumb">
            <a href="{{ route('home') }}" class="pw-breadcrumb__item">
                <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true">
                    <path d="M2 7.5L8 2l6 5.5V14a1 1 0 01-1 1h-3.5v-3.5h-3V15H3a1 1 0 01-1-1V7.5z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/>
                </svg>
                Beranda
            </a>
            <span class="pw-breadcrumb__sep" aria-hidden="true">/</span>
            <span class="pw-breadcrumb__item pw-breadcrumb__item--active">Layanan</span>
        </nav>
    </div>
</div>

{{-- MAIN CONTENT --}}
<section class="pw-section">
    <div class="pw-section__inner">

        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="pw-alert pw-alert--success" role="alert">
            <svg viewBox="0 0 16 16" fill="none" width="16"><circle cx="8" cy="8" r="7" stroke="currentColor" stroke-width="1.3"/><path d="M5 8l2 2 4-4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="pw-alert pw-alert--error" role="alert">
            <svg viewBox="0 0 16 16" fill="none" width="16"><circle cx="8" cy="8" r="7" stroke="currentColor" stroke-width="1.3"/><path d="M8 5v3M8 10.5v.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
            {{ session('error') }}
        </div>
        @endif

        {{-- Balance Bar --}}
        <div class="pw-shop-balance" style="margin-bottom:1.8rem;">
            <div class="pw-shop-balance__left">
                <div class="pw-shop-balance__label">Gold Points Kamu</div>
                <div class="pw-shop-balance__amount">
                    <img src="{{ asset('images/gif_icon/web_coin.gif') }}" alt="Gold Points" width="18" style="vertical-align:middle;">
                    {{ number_format(auth()->user()->money) }}
                    <span class="pw-shop-balance__unit">Gold Points</span>
                </div>
            </div>
            <div class="pw-shop-balance__actions">
                <a href="{{ route('services.history') }}" class="pw-btn pw-btn--ghost pw-btn--sm">
                    <svg viewBox="0 0 16 16" fill="none" width="13"><rect x="2" y="3" width="12" height="11" rx="1.5" stroke="currentColor" stroke-width="1.2"/><path d="M5 1v4M11 1v4M2 7h12" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/></svg>
                    Riwayat Pesanan
                </a>
                <a href="{{ route('cubi-shop') }}" class="pw-btn pw-btn--gold pw-btn--sm">
                    <svg viewBox="0 0 16 16" fill="none" width="13"><path d="M8 2v12M2 8h12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    Top-up Gold
                </a>
            </div>
        </div>

        {{-- 3-Column Service Grid --}}
        @if($services->isEmpty())
        <div class="pw-card" style="text-align:center;padding:3rem 2rem;">
            <svg viewBox="0 0 48 48" fill="none" width="44" style="margin:0 auto .8rem;display:block;opacity:.3;"><circle cx="24" cy="24" r="20" stroke="#c8972a" stroke-width="1.5"/><path d="M16 24h16M24 16v16" stroke="#c8972a" stroke-width="1.5" stroke-linecap="round"/></svg>
            <p style="color:var(--pw-text-muted);font-size:.88rem;">Tidak ada layanan yang tersedia saat ini.</p>
        </div>
        @else
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;">
            @foreach($services as $service)
            <div class="pw-card" style="display:flex;flex-direction:column;gap:.7rem;padding:1.2rem 1.3rem;position:relative;">

                {{-- Category badge top-right --}}
                <span style="position:absolute;top:.7rem;right:.75rem;font-size:.65rem;font-weight:600;letter-spacing:.06em;text-transform:uppercase;background:rgba(200,151,42,.12);color:var(--pw-gold);border:1px solid rgba(200,151,42,.2);border-radius:4px;padding:.15rem .45rem;line-height:1.5;">{{ $service->type }}</span>

                {{-- Icon --}}
                <div style="width:38px;height:38px;border-radius:8px;background:rgba(200,151,42,.1);border:1px solid rgba(200,151,42,.18);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg viewBox="0 0 20 20" fill="none" width="18"><path d="M10 2a4 4 0 100 8 4 4 0 000-8zm-7 14c0-3.3 3.1-6 7-6s7 2.7 7 6" stroke="#c8972a" stroke-width="1.3" stroke-linecap="round"/></svg>
                </div>

                {{-- Name & Desc --}}
                <div style="padding-right:2.5rem;">
                    <div style="font-size:.88rem;font-weight:700;color:var(--pw-text);line-height:1.35;">{{ $service->name }}</div>
                    @if($service->description)
                    <div style="font-size:.74rem;color:var(--pw-text-muted);margin-top:.2rem;line-height:1.5;">{{ $service->description }}</div>
                    @endif
                </div>

                {{-- Price --}}
                <div style="display:flex;align-items:center;gap:.3rem;margin-top:auto;">
                    <img src="{{ asset('images/gif_icon/web_coin.gif') }}" alt="" width="13" style="flex-shrink:0;">
                    @if($service->price > 0)
                    <span style="font-weight:700;color:var(--pw-gold);font-size:.85rem;">{{ number_format($service->price) }} Gold</span>
                    @else
                    <span style="font-weight:600;color:#4caf8a;font-size:.83rem;">Gratis</span>
                    @endif
                </div>

                {{-- Action --}}
                @if(auth()->user()->money >= $service->price)
                <button type="button" class="pw-btn pw-btn--gold pw-btn--sm"
                        onclick="openServiceModal({{ $service->id }})">
                    Pesan Sekarang
                </button>
                @else
                <button type="button" class="pw-btn pw-btn--sm" disabled
                        style="opacity:.4;cursor:not-allowed;font-size:.78rem;">
                    Gold Tidak Cukup
                </button>
                @endif

            </div>
            @endforeach
        </div>

        {{-- Responsive: 4â†’2â†’1 cols --}}
        <style>
            @media(max-width:900px){
                .pw-section__inner > div[style*="grid-template-columns:repeat(4"]{
                    grid-template-columns:repeat(2,1fr)!important;
                }
            }
            @media(max-width:500px){
                .pw-section__inner > div[style*="grid-template-columns:repeat(4"]{
                    grid-template-columns:1fr!important;
                }
            }
        </style>
        @endif

    </div>
</section>

{{-- ORDER MODALS --}}
@foreach($services as $service)
<div id="service-modal-{{ $service->id }}"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.65);z-index:200;align-items:center;justify-content:center;padding:1rem;"
     onclick="if(event.target===this)closeServiceModal({{ $service->id }})">
    <div class="pw-card" style="width:100%;max-width:440px;padding:1.8rem;position:relative;">
        <button type="button" onclick="closeServiceModal({{ $service->id }})"
                style="position:absolute;top:.8rem;right:.8rem;width:28px;height:28px;border:none;background:rgba(255,255,255,.06);border-radius:50%;cursor:pointer;color:var(--pw-text-muted);font-size:1.1rem;display:flex;align-items:center;justify-content:center;">Ã-</button>

        <div style="font-size:1rem;font-weight:700;margin-bottom:.25rem;">{{ $service->name }}</div>
        @if($service->description)
        <div style="font-size:.78rem;color:var(--pw-text-muted);margin-bottom:1.2rem;">{{ $service->description }}</div>
        @else<div style="margin-bottom:1.2rem;"></div>@endif

        <form action="{{ route('services.order', $service->id) }}" method="POST">
            @csrf
            <label class="pw-form__label">Nama Karakter <span style="color:#e05252;">*</span></label>
            <input type="text" name="character_name" class="pw-form__input" required
                   placeholder="Nama karakter in-game kamu" style="margin-bottom:1rem;">

            <div style="background:rgba(200,151,42,.08);border:1px solid rgba(200,151,42,.25);border-radius:8px;padding:.65rem 1rem;font-size:.83rem;margin-bottom:1.2rem;display:flex;align-items:center;gap:.5rem;">
                <img src="{{ asset('images/gif_icon/web_coin.gif') }}" alt="" width="13">
                Biaya: <strong style="color:var(--pw-gold);">
                    @if($service->price > 0) {{ number_format($service->price) }} Gold Points @else Gratis @endif
                </strong>
            </div>

            <div style="display:flex;gap:.6rem;">
                <button type="submit" class="pw-btn pw-btn--gold" style="flex:1;">Konfirmasi Pesanan</button>
                <button type="button" class="pw-btn pw-btn--ghost" onclick="closeServiceModal({{ $service->id }})">Batal</button>
            </div>
        </form>
    </div>
</div>
@endforeach

@push('scripts')
<script>
function openServiceModal(id) {
    document.getElementById('service-modal-' + id).style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function closeServiceModal(id) {
    document.getElementById('service-modal-' + id).style.display = 'none';
    document.body.style.overflow = '';
}
</script>
@endpush
@endsection