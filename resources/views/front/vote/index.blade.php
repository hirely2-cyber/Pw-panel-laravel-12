@extends('layouts.app')

@php
    $__siteName = \App\Models\Setting::get('seo_title') ?: config('pw-config.server.name') ?: 'Perfect World';
@endphp

@section('title', 'Vote — ' . $__siteName)
@section('meta_description', 'Vote untuk server dan dapatkan reward Gold Points gratis setiap hari!')

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
        <h1 class="pw-page-hero__title">Vote Server</h1>
        <p class="pw-page-hero__sub">Vote harian untuk mendukung server — dapatkan reward Gold Points gratis!</p>
        <nav class="pw-breadcrumb" aria-label="breadcrumb">
            <a href="{{ route('home') }}" class="pw-breadcrumb__item">
                <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true">
                    <path d="M2 7.5L8 2l6 5.5V14a1 1 0 01-1 1h-3.5v-3.5h-3V15H3a1 1 0 01-1-1V7.5z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/>
                </svg>
                Beranda
            </a>
            <span class="pw-breadcrumb__sep" aria-hidden="true">/</span>
            <span class="pw-breadcrumb__item pw-breadcrumb__item--active">Vote</span>
        </nav>
    </div>
</div>

{{-- MAIN CONTENT --}}
<section class="pw-section">
    <div class="pw-section__inner pw-section__inner--narrow">

        @if(session('success'))
        <div class="pw-alert pw-alert--success" role="alert">
            <svg viewBox="0 0 16 16" fill="none" width="16" aria-hidden="true"><circle cx="8" cy="8" r="7" stroke="currentColor" stroke-width="1.3"/><path d="M5 8l2 2 4-4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="pw-alert pw-alert--error" role="alert">
            <svg viewBox="0 0 16 16" fill="none" width="16" aria-hidden="true"><circle cx="8" cy="8" r="7" stroke="currentColor" stroke-width="1.3"/><path d="M8 5v3M8 10.5v.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
            {{ session('error') }}
        </div>
        @endif

        {{-- Gold Points balance bar --}}
        <div class="pw-shop-balance" style="margin-bottom:2rem;">
            <div class="pw-shop-balance__left">
                <div class="pw-shop-balance__label">Gold Points Kamu Saat Ini</div>
                <div class="pw-shop-balance__amount">
                    <img src="{{ asset('images/gif_icon/web_coin.gif') }}" alt="Gold Points" width="22" style="vertical-align:middle;">
                    {{ number_format(auth()->user()->money) }}
                    <span class="pw-shop-balance__unit">Gold Points</span>
                </div>
            </div>
            <div class="pw-shop-balance__actions">
                <div class="pw-vote-cooldown-badge">
                    <svg viewBox="0 0 16 16" fill="none" width="14" aria-hidden="true"><circle cx="8" cy="8" r="7" stroke="currentColor" stroke-width="1.2"/><path d="M8 4v4l2.5 1.5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/></svg>
                    <span>Cooldown <strong>{{ config('pw-config.vote.cooldown_hours', 24) }} jam</strong> per site</span>
                </div>
            </div>
        </div>

        {{-- Section title --}}
        <div class="pw-donate-section-title">
            <svg viewBox="0 0 20 20" fill="none" width="18" aria-hidden="true">
                <path d="M10 2l2.4 5 5.6.8-4 3.9.9 5.5L10 14.5l-4.9 2.7.9-5.5L2 7.8l5.6-.8L10 2z" stroke="#c8972a" stroke-width="1.3" stroke-linejoin="round"/>
            </svg>
            Site Vote Tersedia
        </div>

        @if($sites->isEmpty())
        <div style="text-align:center;padding:3rem 1rem;color:var(--pw-text-muted);">
            <svg viewBox="0 0 48 48" fill="none" width="48" style="margin-bottom:1rem;opacity:.4;" aria-hidden="true">
                <circle cx="24" cy="24" r="22" stroke="currentColor" stroke-width="1.5"/>
                <path d="M24 14v10M24 28v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <p>Belum ada site vote yang tersedia.</p>
        </div>
        @else
        <div class="pw-vote-grid">
            @foreach($sites as $site)
            @php $voted = in_array($site->id, $votedSiteIds); @endphp
            <div class="pw-vote-card {{ $voted ? 'pw-vote-card--voted' : '' }}">
                <div class="pw-vote-card__header">
                    <div class="pw-vote-card__icon">
                        <svg viewBox="0 0 20 20" fill="none" width="20">
                            <path d="M10 2l2.4 5 5.6.8-4 3.9.9 5.5L10 14.5l-4.9 2.7.9-5.5L2 7.8l5.6-.8L10 2z"
                                  stroke="{{ $voted ? '#4ade80' : '#c8972a' }}" stroke-width="1.3" stroke-linejoin="round"
                                  fill="{{ $voted ? 'rgba(74,222,128,.15)' : 'none' }}"/>
                        </svg>
                    </div>
                    <div class="pw-vote-card__title">{{ $site->name }}</div>
                    @if($voted)
                    <span class="pw-vote-card__badge">
                        <svg viewBox="0 0 14 14" fill="none" width="11"><circle cx="7" cy="7" r="6" fill="#4ade80"/><path d="M4 7l2 2 4-4" stroke="#0a0a14" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Voted
                    </span>
                    @endif
                </div>

                <a href="{{ $site->url }}" target="_blank" rel="noopener" class="pw-vote-card__url">
                    <svg viewBox="0 0 16 16" fill="none" width="12" aria-hidden="true"><path d="M6 3H3a1 1 0 00-1 1v9a1 1 0 001 1h9a1 1 0 001-1v-3M10 2h4v4M7 9L14 2" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    {{ Str::limit($site->url, 45) }}
                </a>

                <div class="pw-vote-card__reward">
                    <img src="{{ asset('images/gif_icon/web_coin.gif') }}" alt="" width="16">
                    +{{ number_format($site->reward) }} Gold Points per vote
                </div>

                @if($voted)
                <button type="button" class="pw-btn pw-btn--sm pw-vote-card__btn pw-vote-card__btn--done" disabled>
                    <svg viewBox="0 0 16 16" fill="none" width="13"><circle cx="8" cy="8" r="7" stroke="currentColor" stroke-width="1.2"/><path d="M8 4v4l2.5 1.5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/></svg>
                    Tunggu {{ config('pw-config.vote.cooldown_hours', 24) }} Jam
                </button>
                @else
                <form action="{{ route('vote.submit', $site->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="pw-btn pw-btn--gold pw-btn--sm pw-vote-card__btn">
                        <svg viewBox="0 0 20 20" fill="none" width="14"><path d="M10 2l2.4 5 5.6.8-4 3.9.9 5.5L10 14.5l-4.9 2.7.9-5.5L2 7.8l5.6-.8L10 2z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/></svg>
                        Vote Sekarang
                    </button>
                </form>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        {{-- Info section --}}
        <div class="pw-donate-info-bar">
            <div class="pw-donate-info-card">
                <div class="pw-donate-info-card__title">
                    <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true">
                        <circle cx="8" cy="8" r="7" stroke="#c8972a" stroke-width="1.2"/>
                        <path d="M8 7v4M8 5v.5" stroke="#c8972a" stroke-width="1.3" stroke-linecap="round"/>
                    </svg>
                    Cara Vote
                </div>
                <ol class="pw-donate-steps">
                    <li>Klik <strong>"Vote Sekarang"</strong> pada site yang tersedia</li>
                    <li>Kamu akan mendapat <strong>Gold Points reward</strong> langsung</li>
                    <li>Buka link site vote dan vote server di sana</li>
                    <li>Tunggu <strong>{{ config('pw-config.vote.cooldown_hours', 24) }} jam</strong> untuk vote lagi</li>
                    <li>Vote di semua site untuk reward maksimal!</li>
                </ol>
            </div>

            <div class="pw-donate-info-card">
                <div class="pw-donate-info-card__title">
                    <svg viewBox="0 0 20 20" fill="none" width="13" aria-hidden="true">
                        <path d="M10 2l2.4 5 5.6.8-4 3.9.9 5.5L10 14.5l-4.9 2.7.9-5.5L2 7.8l5.6-.8L10 2z" stroke="#c8972a" stroke-width="1.3" stroke-linejoin="round"/>
                    </svg>
                    Kenapa Vote?
                </div>
                <p class="pw-donate-info-card__text">
                    Dengan voting, kamu membantu <strong>meningkatkan ranking server</strong> di situs toplist sehingga lebih banyak player baru bergabung. Sebagai terima kasih, kamu mendapat <strong>Gold Points gratis</strong> setiap kali vote!
                </p>
            </div>
        </div>

    </div>
</section>

@endsection
