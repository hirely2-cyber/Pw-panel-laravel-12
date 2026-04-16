@extends('layouts.app')

@php
    $__siteName = \App\Models\Setting::get('seo_title') ?: config('pw-config.server.name') ?: 'Perfect World';
@endphp

@section('title', 'Event — ' . $__siteName)

@section('content')

{{-- PAGE HERO --}}
<div class="pw-page-hero">
    <div class="pw-page-hero__bg" aria-hidden="true"></div>
    <div class="pw-page-hero__inner" style="position:relative;z-index:1;">
        <div class="pw-page-hero__ornament" aria-hidden="true">
            <svg viewBox="0 0 160 20" fill="none" width="140">
                <line x1="0" y1="10" x2="55" y2="10" stroke="#c8972a" stroke-width="1"/>
                <path d="M65 3 L75 10 L65 17 L55 10 Z" fill="#c8972a" opacity=".5"/>
                <path d="M75 3 L85 10 L75 17 L65 10 Z" fill="#c8972a"/>
                <path d="M85 3 L95 10 L85 17 L75 10 Z" fill="#c8972a" opacity=".5"/>
                <line x1="95" y1="10" x2="150" y2="10" stroke="#c8972a" stroke-width="1"/>
            </svg>
        </div>
        <h1 style="font-family:'Cinzel',serif;font-size:clamp(1.8rem,4vw,2.8rem);font-weight:900;background:linear-gradient(135deg,#fbbf24 0%,#f59e0b 30%,#fcd34d 50%,#f59e0b 70%,#c8972a 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;line-height:1.1;filter:drop-shadow(0 2px 8px rgba(251,191,36,.3));margin:0;">
            Event
        </h1>
        <p class="pw-page-hero__sub">{{ $__siteName }}</p>
        <nav class="pw-breadcrumb" aria-label="breadcrumb">
            <a href="{{ route('home') }}" class="pw-breadcrumb__item">
                <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true">
                    <path d="M2 7.5L8 2l6 5.5V14a1 1 0 01-1 1h-3.5v-3.5h-3V15H3a1 1 0 01-1-1V7.5z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/>
                </svg>
                {{ __('main.breadcrumb_home') }}
            </a>
            <span class="pw-breadcrumb__sep" aria-hidden="true">/</span>
            <span class="pw-breadcrumb__item pw-breadcrumb__item--active">Event</span>
        </nav>
    </div>
</div>

<section class="pw-section" style="padding-top:2rem;padding-bottom:4rem;">
    <div class="pw-section__inner pw-section__inner--narrow" style="text-align:center;">
        <div class="pw-card" style="padding:3rem 2rem;max-width:550px;margin:0 auto;">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="var(--pw-text-muted)" stroke-width="1.2" style="margin:0 auto 1.5rem;display:block;opacity:.5;">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                <line x1="16" y1="2" x2="16" y2="6"/>
                <line x1="8" y1="2" x2="8" y2="6"/>
                <line x1="3" y1="10" x2="21" y2="10"/>
                <path d="M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01"/>
            </svg>

            <h2 style="font-family:'Cinzel',serif;font-size:1.4rem;font-weight:700;color:var(--pw-text-light);margin:0 0 .8rem;">
                Sedang Tidak Ada Event
            </h2>

            <p style="color:var(--pw-text-muted);font-size:.92rem;line-height:1.7;margin:0 0 1.5rem;">
                Maaf, saat ini belum ada event yang sedang berlangsung.<br>
                Silahkan tunggu event-event menarik lainnya!
            </p>

            <div style="display:flex;gap:.8rem;justify-content:center;flex-wrap:wrap;">
                <a href="{{ route('home') }}" class="pw-btn pw-btn--gold">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:-2px;">
                        <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                    Kembali ke Home
                </a>
                <a href="{{ route('promo.launch') }}" class="pw-btn pw-btn--ghost">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:-2px;">
                        <path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4-4v2"/>
                        <circle cx="8.5" cy="7" r="4"/>
                        <line x1="20" y1="8" x2="20" y2="14"/>
                        <line x1="23" y1="11" x2="17" y2="11"/>
                    </svg>
                    Pre-Register
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
