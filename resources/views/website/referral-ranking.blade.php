@extends('layouts.app')

@php
    $__siteName = \App\Models\Setting::get('seo_title') ?: config('pw-config.server.name') ?: 'Perfect World';
@endphp

@section('title', 'Referral Ranking — ' . $__siteName)
@section('meta_description', 'Lihat ranking referral pre-register ' . $__siteName . '. Ajak teman bermain dan dapatkan Cubi Gold gratis!')

@section('content')

{{-- PAGE HERO --}}
<div class="pw-page-hero">
    <div class="pw-page-hero__bg" aria-hidden="true"></div>
    <canvas id="pw-sparkle" style="position:absolute;inset:0;width:100%;height:100%;pointer-events:none;z-index:0;" aria-hidden="true"></canvas>
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
            Referral Ranking
        </h1>
        <p class="pw-page-hero__sub">{{ $event->title ?? 'Pre-Register Event' }} — {{ $__siteName }}</p>
        <nav class="pw-breadcrumb" aria-label="breadcrumb">
            <a href="{{ route('home') }}" class="pw-breadcrumb__item">
                <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true">
                    <path d="M2 7.5L8 2l6 5.5V14a1 1 0 01-1 1h-3.5v-3.5h-3V15H3a1 1 0 01-1-1V7.5z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/>
                </svg>
                {{ __('main.breadcrumb_home') }}
            </a>
            <span class="pw-breadcrumb__sep" aria-hidden="true">/</span>
            <span class="pw-breadcrumb__item pw-breadcrumb__item--active">Referral Ranking</span>
        </nav>
    </div>
</div>

<section class="pw-section" id="ranking">
    <div class="pw-section__inner pw-section__inner--narrow">

        @if(!$event)
        <div class="pw-card" style="text-align:center;padding:3rem;">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--pw-text-muted)" stroke-width="1.5" style="margin:0 auto 1rem;display:block;"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
            <p style="color:var(--pw-text-muted);font-size:1rem;">Belum ada event pre-launch yang aktif saat ini.</p>
            <a href="{{ route('promo.launch') }}" class="pw-btn pw-btn--gold pw-btn--sm" style="margin-top:1rem;">Lihat Pre-Register</a>
        </div>
        @else

        {{-- ── PODIUM TOP 3 ── --}}
        @if($referrers->count() >= 3 && $referrers->currentPage() === 1)
        <div class="pw-podium">
            @php
                $podiumOrder  = [1, 0, 2];
                $podiumRank   = [2, 1, 3];
                $podiumClass  = ['pw-podium__step--silver', 'pw-podium__step--gold', 'pw-podium__step--bronze'];
                $rankClass    = ['pw-rank--2', 'pw-rank--1', 'pw-rank--3'];
                $rankColors   = ['#c0c0c0', '#ffd700', '#cd7f32'];
            @endphp
            @foreach($podiumOrder as $idx => $di)
            @php $r = $referrers[$di] ?? null; @endphp
            <div class="pw-podium__item {{ $podiumClass[$idx] }}">
                <div class="pw-podium__avatar" aria-hidden="true">
                    @if($podiumRank[$idx] === 1)
                    <svg viewBox="0 0 24 14" fill="currentColor" width="28" style="color:#ffd700;display:block;margin:0 auto .3rem;filter:drop-shadow(0 2px 6px rgba(255,215,0,.4));">
                        <path d="M2 12L5 3l5 5 2-6 2 6 5-5 3 9H2z"/>
                    </svg>
                    @endif
                    <div class="pw-podium__avatar-ring" style="border-color:{{ $rankColors[$idx] }};{{ $podiumRank[$idx] === 1 ? 'width:160px;height:160px;border-width:4px;' : 'width:130px;height:130px;' }}">
                        <svg viewBox="0 0 40 40" fill="none" width="36" aria-hidden="true" style="position:relative;z-index:1;">
                            <circle cx="20" cy="20" r="19" stroke="{{ $rankColors[$idx] }}" stroke-width="1" opacity=".3"/>
                            <circle cx="20" cy="15" r="7" stroke="{{ $rankColors[$idx] }}" stroke-width="1.5" opacity=".8"/>
                            <path d="M6 36c0-7.7 6.3-14 14-14s14 6.3 14 14" stroke="{{ $rankColors[$idx] }}" stroke-width="1.5" opacity=".8" stroke-linecap="round"/>
                        </svg>
                    </div>
                </div>
                @if($r)
                <div class="pw-podium__name">{{ $r->name }}</div>
                <div class="pw-podium__sub" style="color:var(--pw-text-muted);">{{ $r->referral_code }}</div>
                <div class="pw-podium__level" style="color:{{ $rankColors[$idx] }}">{{ $r->referral_count }} Referral</div>
                <div class="pw-podium__exp">
                    <span style="color:#4ade80;">{{ $r->qualified_count }}</span>
                    <span style="color:var(--pw-text-muted);font-size:.75rem;"> qualified</span>
                </div>
                @else
                <div class="pw-podium__name" style="color:var(--pw-text-muted);font-style:italic;">— Kosong —</div>
                <div class="pw-podium__sub" style="opacity:.4;">Belum ada data</div>
                @endif
                <div class="pw-podium__step-block">
                    <span class="pw-rank {{ $rankClass[$idx] }}">#{{ $podiumRank[$idx] }}</span>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- ── RANKING TABLE ── --}}
        <div class="pw-ranking__table-wrap">
            <table class="pw-ranking__table">
                <thead>
                    <tr>
                        <th style="text-align:center;width:50px;">#</th>
                        <th>Username</th>
                        <th style="text-align:center;">Kode Referral</th>
                        <th style="text-align:center;">Total Referral</th>
                        <th style="text-align:center;">Qualified (Lv.{{ $event->referral_req_level }})</th>
                        <th style="text-align:center;">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($referrers as $index => $referrer)
                    @php $rank = $referrers->firstItem() + $index; @endphp
                    <tr class="{{ $rank <= 3 ? 'pw-ranking__top' : '' }}" x-data="{ open: false }">
                        <td style="text-align:center;">
                            @if($rank <= 3)
                                <span class="pw-rank pw-rank--{{ $rank }}">{{ $rank }}</span>
                            @else
                                <span class="pw-rank">{{ $rank }}</span>
                            @endif
                        </td>
                        <td class="pw-ranking__name">{{ $referrer->name }}</td>
                        <td style="text-align:center;font-family:monospace;font-size:.82rem;color:var(--pw-text-muted);">{{ $referrer->referral_code }}</td>
                        <td style="text-align:center;">
                            <span style="font-weight:800;color:#c8972a;font-size:1.05rem;">{{ $referrer->referral_count }}</span>
                        </td>
                        <td style="text-align:center;">
                            <span style="font-weight:700;color:{{ $referrer->qualified_count > 0 ? '#4ade80' : 'var(--pw-text-muted)' }};">{{ $referrer->qualified_count }}</span>
                            <span style="color:var(--pw-text-muted);font-size:.78rem;"> / {{ $referrer->referral_count }}</span>
                        </td>
                        <td style="text-align:center;">
                            @if($referrer->referred_users->count() > 0)
                            <button @click="open = !open" style="background:none;border:1px solid rgba(200,151,42,.2);border-radius:6px;padding:.2rem .6rem;cursor:pointer;color:var(--pw-text-muted);font-size:.75rem;transition:all .2s;" :style="open ? 'background:rgba(200,151,42,.1);color:#c8972a;border-color:#c8972a;' : ''">
                                <svg :style="open ? 'transform:rotate(180deg)' : ''" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:-1px;transition:transform .2s;"><path d="M6 9l6 6 6-6"/></svg>
                                {{ $referrer->referred_users->count() }}
                            </button>
                            @else
                            <span style="color:var(--pw-text-muted);font-size:.75rem;">—</span>
                            @endif
                        </td>
                    </tr>
                    {{-- Expandable: referred users --}}
                    @if($referrer->referred_users->count() > 0)
                    <tr x-show="open" x-collapse style="background:rgba(200,151,42,.02);">
                        <td colspan="6" style="padding:.6rem 1rem .8rem;">
                            <div style="display:flex;flex-wrap:wrap;gap:.4rem;">
                                @foreach($referrer->referred_users as $ru)
                                <span style="display:inline-flex;align-items:center;gap:.3rem;padding:.25rem .6rem;border-radius:6px;font-size:.78rem;
                                    {{ $ru->level_ok ? 'background:rgba(74,222,128,.08);border:1px solid rgba(74,222,128,.15);color:#4ade80;' : 'background:rgba(148,163,184,.05);border:1px solid rgba(148,163,184,.1);color:var(--pw-text-muted);' }}">
                                    @if($ru->level_ok)
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#4ade80" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                    @else
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="var(--pw-text-muted)" stroke-width="2" opacity=".5"><circle cx="12" cy="12" r="10"/></svg>
                                    @endif
                                    {{ $ru->name }}
                                    <span style="font-size:.7rem;opacity:.6;">Lv.{{ $ru->level }}</span>
                                </span>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                    @endif
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:3rem;color:var(--pw-text-muted);">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--pw-text-muted)" stroke-width="1" style="margin:0 auto .8rem;display:block;opacity:.4;">
                                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4-4v2"/><circle cx="9" cy="7" r="4"/>
                                <path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>
                            </svg>
                            Belum ada data referral. Jadilah yang pertama!
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($referrers->hasPages())
        <div style="margin-top:1rem;">
            {{ $referrers->links() }}
        </div>
        @endif

        {{-- ── FOOTER INFO ── --}}
        <div style="text-align:center;margin-top:1.5rem;padding:1rem;font-size:.78rem;color:var(--pw-text-muted);">
            <div>
                {{ $event->start_at?->format('d M Y') }} — {{ $event->end_at?->format('d M Y') }}
                &bull; Syarat: Karakter min. Level {{ $event->referral_req_level }}
                &bull; {{ number_format($totalRegistered) }} user terdaftar
            </div>
            <div style="margin-top:.5rem;">
                @auth
                <a href="{{ route('profile') }}" class="pw-btn pw-btn--gold pw-btn--sm" style="font-size:.78rem;">Lihat Kode Referral Saya</a>
                @else
                <a href="{{ route('register') }}" class="pw-btn pw-btn--gold pw-btn--sm" style="font-size:.78rem;">Daftar & Mulai Invite Teman</a>
                @endauth
            </div>
        </div>

        @endif

    </div>
</section>
@endsection
