@extends('layouts.landing')

@php
    $__siteName = \App\Models\Setting::get('seo_title') ?: config('pw-config.server.name') ?: 'Perfect World';
    $__heroLogo = \App\Models\Setting::get('site_logo');
    $__heroBg   = \App\Models\Setting::get('site_hero_bg');
    $__discord  = \App\Models\Setting::get('social_discord') ?: 'https://discord.gg/7xCWyB2NFy';
    $__facebook = \App\Models\Setting::get('social_facebook');
    $__waLink   = \App\Models\Setting::get('social_whatsapp');
    $registeredCount = $registeredCount ?? 0;
    $todayCount      = $todayCount ?? 0;
    $tiers           = collect($event->referral_tiers ?? []);
    $launchDate      = $event?->end_at ? \Carbon\Carbon::parse($event->end_at)->toIso8601String() : '2026-05-30T12:00:00+07:00';
@endphp

@section('title', __('main.promo_page_title') . ' — ' . $__siteName)
@section('meta_description', __('main.promo_page_meta', ['site' => $__siteName]))

@section('content')

{{-- Floating lang switcher (no topbar on landing page) --}}
@php $nextLocale = app()->getLocale() === 'id' ? 'en' : 'id'; @endphp
<div style="position:fixed;top:1rem;right:1rem;z-index:9999;">
    <a href="{{ route('lang.switch', $nextLocale) }}"
       class="pw-lang__toggle"
       style="box-shadow:0 2px 12px rgba(0,0,0,.35);font-size:.78rem;padding:.3rem .65rem .3rem .45rem;"
       title="Switch to {{ strtoupper($nextLocale) }}">
        @if(app()->getLocale() === 'id')
            <svg viewBox="0 0 30 20" width="20" height="13"><rect width="30" height="10" fill="#CE1126"/><rect y="10" width="30" height="10" fill="#FFF"/></svg>
            <span>ID</span>
        @else
            <svg viewBox="0 0 60 30" width="20" height="13"><rect width="60" height="30" fill="#012169"/><path d="M0 0l60 30M60 0L0 30" stroke="#fff" stroke-width="6"/><path d="M0 0l60 30M60 0L0 30" stroke="#C8102E" stroke-width="4"/><path d="M30 0v30M0 15h60" stroke="#fff" stroke-width="10"/><path d="M30 0v30M0 15h60" stroke="#C8102E" stroke-width="6"/></svg>
            <span>EN</span>
        @endif
        <svg class="pw-lang__arrow" viewBox="0 0 16 16" fill="none" width="10"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
    </a>
</div>

{{-- ============================================================
     HERO — Full screen, above the fold
============================================================ --}}
<section class="pw-landing-hero">
    <div class="pw-landing-hero__bg" aria-hidden="true">
        @if($__heroBg)
            <img src="{{ Storage::url($__heroBg) }}" alt="" loading="eager" fetchpriority="high">
        @endif
    </div>
    <div class="pw-landing-hero__overlay" aria-hidden="true" style="background:linear-gradient(180deg,rgba(8,6,4,.88) 0%,rgba(15,10,5,.75) 45%,rgba(8,6,4,.92) 100%);"></div>
    <canvas id="landing-embers" aria-hidden="true"></canvas>

    <div class="pw-landing-hero__content" style="max-width:780px;">
        @if($__heroLogo)
            <img src="{{ Storage::url($__heroLogo) }}" alt="{{ $__siteName }}" class="pw-landing-hero__logo" style="display:block;margin:0 auto;">
        @endif

        {{-- Live badge --}}
        <div style="display:flex;align-items:center;justify-content:center;gap:.5rem;margin-bottom:1rem;">
            <span class="pw-landing-hero__badge" style="margin:0;">
                <span style="display:inline-block;width:7px;height:7px;border-radius:50%;background:#4ade80;margin-right:.4rem;animation:livePulse 1.5s ease-in-out infinite;vertical-align:1px;"></span>
                {{ __('main.promo_hero_badge') }}
            </span>
        </div>

        <h1 class="pw-landing-hero__title" style="font-size:clamp(1.9rem,5.5vw,3.5rem);">
            {{ __('main.promo_hero_title_line1') }}<br>
            <span style="-webkit-text-fill-color:transparent;background:linear-gradient(135deg,#fff 0%,#fcd34d 40%,#f59e0b 100%);-webkit-background-clip:text;background-clip:text;">{{ __('main.promo_hero_title_line2') }}</span>
        </h1>

        <p class="pw-landing-hero__sub" style="font-size:1.05rem;max-width:560px;margin:0 auto 1.5rem;">
            {{ __('main.promo_hero_sub') }}<br>
            <strong style="color:#fbbf24;">{{ __('main.promo_hero_nogm') }}</strong> &bull; {{ __('main.promo_hero_gameplay') }} &bull; {{ __('main.promo_hero_antip2w') }}
        </p>

        {{-- Social proof micro --}}
        <div style="display:flex;align-items:center;justify-content:center;gap:1.2rem;margin-bottom:1.8rem;flex-wrap:wrap;">
            <div style="display:flex;align-items:center;gap:.4rem;font-size:.82rem;color:rgba(255,255,255,.7);">
                <svg viewBox="0 0 16 16" fill="none" width="14"><path d="M13 13c0-2.761-2.239-5-5-5S3 10.239 3 13" stroke="#4ade80" stroke-width="1.4"/><circle cx="8" cy="5" r="3" stroke="#4ade80" stroke-width="1.4"/></svg>
                <strong style="color:#4ade80;">{{ number_format($registeredCount) }}</strong> {{ __('main.promo_social_registered') }}
            </div>
            <div style="width:1px;height:14px;background:rgba(255,255,255,.15);"></div>
            <div style="display:flex;align-items:center;gap:.4rem;font-size:.82rem;color:rgba(255,255,255,.7);">
                <svg viewBox="0 0 16 16" fill="none" width="14"><circle cx="8" cy="8" r="6.5" stroke="#fbbf24" stroke-width="1.4"/><path d="M8 5v3.5l2 1.5" stroke="#fbbf24" stroke-width="1.4" stroke-linecap="round"/></svg>
                <strong style="color:#fbbf24;">{{ $todayCount }}</strong> {{ __('main.promo_social_today') }}
            </div>
            <div style="width:1px;height:14px;background:rgba(255,255,255,.15);"></div>
            <div style="display:flex;align-items:center;gap:.4rem;font-size:.82rem;color:rgba(255,255,255,.7);">
                <svg viewBox="0 0 16 16" fill="none" width="14"><path d="M2 5h12v8a1 1 0 01-1 1H3a1 1 0 01-1-1V5z" stroke="#c8972a" stroke-width="1.3"/><path d="M1 5l7-3 7 3" stroke="#c8972a" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <strong style="color:#c8972a;">{{ __('main.promo_social_free') }}</strong>
            </div>
        </div>

        {{-- COUNTDOWN --}}
        <div class="pw-landing-countdown" x-data="landingCountdown()" x-init="start()" style="margin-bottom:1.8rem;">
            <div class="pw-landing-countdown__label">{!! __('main.promo_countdown_label') !!}</div>
            <div style="font-size:.72rem;color:rgba(255,255,255,.4);margin-bottom:.6rem;letter-spacing:.04em;">
                Pre-Register:
                <strong style="color:rgba(200,151,42,.7);">{{ \Carbon\Carbon::parse($event?->start_at)->translatedFormat('d M Y') ?? '-' }}</strong>
                &mdash;
                <strong style="color:rgba(200,151,42,.7);">{{ \Carbon\Carbon::parse($event?->end_at)->translatedFormat('d M Y') ?? '-' }}</strong>
            </div>
            <div class="pw-landing-countdown__boxes">
                <div class="pw-landing-countdown__box">
                    <span class="pw-landing-countdown__num" x-text="days">00</span>
                    <span class="pw-landing-countdown__unit">{{ __('main.promo_day') }}</span>
                </div>
                <div class="pw-landing-countdown__sep">:</div>
                <div class="pw-landing-countdown__box">
                    <span class="pw-landing-countdown__num" x-text="hours">00</span>
                    <span class="pw-landing-countdown__unit">{{ __('main.promo_hour') }}</span>
                </div>
                <div class="pw-landing-countdown__sep">:</div>
                <div class="pw-landing-countdown__box">
                    <span class="pw-landing-countdown__num" x-text="minutes">00</span>
                    <span class="pw-landing-countdown__unit">{{ __('main.promo_minute') }}</span>
                </div>
                <div class="pw-landing-countdown__sep">:</div>
                <div class="pw-landing-countdown__box">
                    <span class="pw-landing-countdown__num" x-text="seconds">00</span>
                    <span class="pw-landing-countdown__unit">{{ __('main.promo_second') }}</span>
                </div>
            </div>
        </div>

        {{-- PRIMARY CTA --}}
        <a href="{{ route('register') }}" class="pw-landing-cta" style="font-size:1.1rem;padding:1rem 2.8rem;letter-spacing:.06em;">
            {{ __('main.promo_cta_primary') }}
        </a>

        <p class="pw-landing-hero__note" style="margin-top:.85rem;font-size:.82rem;">
            {{ __('main.promo_cta_note') }}
        </p>

        {{-- Scroll indicator --}}
        <div style="margin-top:1rem;animation:scrollBounce 1.8s ease-in-out infinite;opacity:.5;display:flex;justify-content:center;">
            <svg viewBox="0 0 24 24" fill="none" width="22" stroke="#c8972a" stroke-width="1.5" stroke-linecap="round"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
        </div>
    </div>
</section>

{{-- ============================================================
     STATS BAR — Social proof strip
============================================================ --}}
<div style="background:linear-gradient(90deg,rgba(200,151,42,.12),rgba(200,151,42,.06),rgba(200,151,42,.12));border-top:1px solid rgba(200,151,42,.2);border-bottom:1px solid rgba(200,151,42,.2);padding:.9rem 1rem;">
    <div style="max-width:900px;margin:0 auto;display:flex;align-items:center;justify-content:center;gap:2rem;flex-wrap:wrap;">
        <div class="pw-promo-stat">
            <svg viewBox="0 0 16 16" fill="none" width="16"><path d="M13 13c0-2.761-2.239-5-5-5S3 10.239 3 13" stroke="#4ade80" stroke-width="1.5" stroke-linecap="round"/><circle cx="8" cy="5" r="3" stroke="#4ade80" stroke-width="1.5"/></svg>
            <span><strong style="color:#4ade80;">{{ number_format($registeredCount) }}+</strong> {{ __('main.promo_stat_players') }}</span>
        </div>
        <div class="pw-promo-stat__sep"></div>
        <div class="pw-promo-stat">
            <svg viewBox="0 0 16 16" fill="none" width="16"><path d="M13.5 4.5l-8 8L2 9" stroke="#c8972a" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <span><strong style="color:#c8972a;">99.9%</strong> {{ __('main.promo_stat_uptime') }}</span>
        </div>
        <div class="pw-promo-stat__sep"></div>
        <div class="pw-promo-stat">
            <svg viewBox="0 0 16 16" fill="none" width="16"><path d="M8 1l1.8 3.6L14 5.3l-3 2.9.7 4.1L8 10.5l-3.7 1.8.7-4.1L2 5.3l4.2-.7L8 1z" stroke="#fbbf24" stroke-width="1.3" stroke-linejoin="round"/></svg>
            <span><strong style="color:#fbbf24;">100%</strong> {{ __('main.promo_stat_antip2w') }}</span>
        </div>
        <div class="pw-promo-stat__sep"></div>
        <div class="pw-promo-stat">
            <svg viewBox="0 0 16 16" fill="none" width="16"><rect x="2" y="2" width="12" height="12" rx="2" stroke="#818cf8" stroke-width="1.4"/><path d="M5.5 8.5l2 2 3-4" stroke="#818cf8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <span><strong style="color:#818cf8;">{{ __('main.promo_stat_nogm') }}</strong></span>
        </div>
    </div>
</div>

{{-- ============================================================
     HOW IT WORKS — 3 simple steps
============================================================ --}}
<section style="padding:2.5rem 1rem;background:var(--pw-bg);">
        <p class="pw-landing-section-sub">{{ __('main.promo_steps_sub') }}</p>

        <div class="pw-promo-steps">
            <div class="pw-promo-step">
                <div class="pw-promo-step__num">1</div>
                <div class="pw-promo-step__icon">
                    <svg viewBox="0 0 24 24" fill="none" width="32" stroke="#c8972a" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4-4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                </div>
                <h3 class="pw-promo-step__title">{{ __('main.promo_step1_title') }}</h3>
                <p class="pw-promo-step__desc">{{ __('main.promo_step1_desc', ['url' => request()->getHost()]) }}</p>
            </div>

            <div class="pw-promo-step">
                <div class="pw-promo-step__num">2</div>
                <div class="pw-promo-step__icon">
                    <svg viewBox="0 0 24 24" fill="none" width="32" stroke="#c8972a" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12v8a2 2 0 002 2h12a2 2 0 002-2v-8"/><polyline points="16 6 12 2 8 6"/><line x1="12" y1="2" x2="12" y2="15"/></svg>
                </div>
                <h3 class="pw-promo-step__title">{{ __('main.promo_step2_title') }}</h3>
                <p class="pw-promo-step__desc">{{ __('main.promo_step2_desc') }}</p>
            </div>

            <div class="pw-promo-step">
                <div class="pw-promo-step__num">3</div>
                <div class="pw-promo-step__icon">
                    <svg viewBox="0 0 24 24" fill="none" width="32" stroke="#c8972a" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                </div>
                <h3 class="pw-promo-step__title">{{ __('main.promo_step3_title') }}</h3>
                <p class="pw-promo-step__desc">{{ __('main.promo_step3_desc') }}</p>
            </div>
        </div>

        <div style="text-align:center;margin-top:2.5rem;">
            <a href="{{ route('register') }}" class="pw-landing-cta">{{ __('main.promo_step_cta') }}</a>
        </div>
    </div>
</section>

{{-- ============================================================
     REGISTER REWARDS — Hadiah hanya dengan daftar
============================================================ --}}
@php $registerRewards = collect($event->register_rewards ?? []); @endphp
@if($registerRewards->isNotEmpty())
<section style="padding:2.5rem 1rem;background:linear-gradient(180deg,rgba(74,222,128,.04) 0%,var(--pw-bg) 100%);border-top:1px solid rgba(74,222,128,.1);">
    <div class="pw-landing-section-inner">
        <h2 class="pw-landing-section-title" style="color:#4ade80;">
            <svg viewBox="0 0 24 24" fill="none" width="28" stroke="#4ade80" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="display:block;margin:0 auto .5rem;"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            {{ __('main.promo_reg_reward_title') }}
        </h2>
        <p class="pw-landing-section-sub">{!! __('main.promo_reg_reward_sub', ['level' => $event->register_req_level ?? 50]) !!}</p>

        <div style="text-align:center;">
            <div style="display:inline-flex;align-items:center;gap:.5rem;padding:.5rem 1.1rem;background:rgba(74,222,128,.08);border:1px solid rgba(74,222,128,.25);border-radius:50px;margin-bottom:1.8rem;font-size:.82rem;color:var(--pw-text-muted);">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#4ade80" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <span>{!! __('main.promo_reg_req_note', ['level' => $event->register_req_level ?? 50]) !!}</span>
            </div>
        </div>

        <div style="text-align:center;margin-bottom:2.2rem;">
            @foreach($registerRewards as $reward)
            <div style="line-height:1;margin-bottom:.4rem;">
                <span style="font-family:'Cinzel',serif;font-size:clamp(3.5rem,10vw,5.5rem);font-weight:900;background:linear-gradient(135deg,#fbbf24 0%,#f59e0b 35%,#fcd34d 55%,#c8972a 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;filter:drop-shadow(0 2px 12px rgba(251,191,36,.4));">{{ number_format($reward['amount']) }}</span>
                <span style="font-family:'Cinzel',serif;font-size:clamp(1.4rem,4vw,2.2rem);font-weight:700;color:#c8972a;margin-left:.4rem;">{{ $reward['label'] }}</span>
            </div>
            @endforeach
            <div style="font-size:.82rem;color:rgba(200,151,42,.7);letter-spacing:.12em;text-transform:uppercase;margin-top:.3rem;">{{ __('main.promo_social_free') }}</div>
        </div>

        <div style="text-align:center;">
            <a href="{{ route('register') }}" class="pw-landing-cta" style="background:linear-gradient(135deg,#166534,#15803d,#16a34a);color:#fff;box-shadow:0 4px 24px rgba(74,222,128,.45),inset 0 1px 0 rgba(255,255,255,.15);animation:none;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4-4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                {{ __('main.promo_reg_reward_cta') }}
            </a>
        </div>
    </div>
</section>
@endif

{{-- ============================================================
     REFERRAL MILESTONE REWARDS — Dynamic dari event
============================================================ --}}
@if($tiers->isNotEmpty())
<section style="padding:2.5rem 1rem;background:linear-gradient(180deg,var(--pw-bg-2) 0%,var(--pw-bg) 100%);">
    <div class="pw-landing-section-inner">
        <h2 class="pw-landing-section-title">
            <svg viewBox="0 0 160 20" fill="none" width="110" style="display:block;margin:0 auto .8rem;"><line x1="0" y1="10" x2="55" y2="10" stroke="#c8972a" stroke-width="1"/><path d="M65 3 L75 10 L65 17 L55 10 Z" fill="#c8972a" opacity=".5"/><path d="M75 3 L85 10 L75 17 L65 10 Z" fill="#c8972a"/><path d="M85 3 L95 10 L85 17 L75 10 Z" fill="#c8972a" opacity=".5"/><line x1="95" y1="10" x2="150" y2="10" stroke="#c8972a" stroke-width="1"/></svg>
            {{ __('main.promo_referral_title') }}
        </h2>
        <p class="pw-landing-section-sub">{{ __('main.promo_referral_sub') }}</p>

        <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:1rem;margin-bottom:2rem;" class="pw-promo-milestone-grid">
            @foreach($tiers as $i => $tier)
            @php $isHighlight = $i === $tiers->count() - 1 || ($tier['count'] ?? 0) >= 100; @endphp
            <div class="pw-promo-milestone {{ $isHighlight ? 'pw-promo-milestone--highlight' : '' }}">
                @if($isHighlight)
                <div class="pw-promo-milestone__best">LEGENDARY</div>
                @endif
                <div class="pw-promo-milestone__count">{{ $tier['count'] ?? '?' }}</div>
                <div class="pw-promo-milestone__label">{{ __('main.promo_milestone_referral') }}</div>
                <div class="pw-promo-milestone__reward">
                    <img src="{{ asset('images/gif_icon/web_coin.gif') }}" alt="" width="14" height="14" style="vertical-align:-2px;margin-right:2px;">
                    {{ number_format($tier['reward'] ?? 0) }} Cubi
                </div>
            </div>
            @endforeach
        </div>

        <div style="text-align:center;padding:1rem 1.5rem;background:rgba(200,151,42,.06);border:1px solid rgba(200,151,42,.15);border-radius:10px;max-width:550px;margin:0 auto;">
            <p style="font-size:.85rem;color:var(--pw-text-muted);margin:0;">
                {!! __('main.promo_referral_req_note', ['level' => $event->referral_req_level ?? 50]) !!}
            </p>
        </div>

        <div style="text-align:center;margin-top:2rem;">
            <a href="{{ route('register') }}" class="pw-landing-cta">{{ __('main.promo_referral_cta') }}</a>
        </div>
    </div>
</section>
@endif

{{-- ============================================================
     WHY JOIN — Key features
============================================================ --}}
<section style="padding:2.5rem 1rem;background:var(--pw-bg);">
    <div class="pw-landing-section-inner">
        <h2 class="pw-landing-section-title">{{ __('main.promo_why_title', ['site' => $__siteName]) }}</h2>
        <p class="pw-landing-section-sub">{{ __('main.promo_why_sub') }}</p>

        <div class="pw-landing-features__grid" style="grid-template-columns:repeat(auto-fit,minmax(240px,1fr));">
            <div class="pw-landing-feature">
                <div class="pw-landing-feature__icon" style="color:#4ade80;">
                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/></svg>
                </div>
                <h3>{{ __('main.promo_feat1_title') }}</h3>
                <p>{{ __('main.promo_feat1_desc') }}</p>
            </div>
            <div class="pw-landing-feature">
                <div class="pw-landing-feature__icon" style="color:#818cf8;">
                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                </div>
                <h3>{{ __('main.promo_feat2_title') }}</h3>
                <p>{{ __('main.promo_feat2_desc') }}</p>
            </div>
            <div class="pw-landing-feature">
                <div class="pw-landing-feature__icon" style="color:#f59e0b;">
                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                </div>
                <h3>{{ __('main.promo_feat3_title') }}</h3>
                <p>{{ __('main.promo_feat3_desc') }}</p>
            </div>
            <div class="pw-landing-feature">
                <div class="pw-landing-feature__icon" style="color:#f87171;">
                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="12" rx="2"/><path d="M12 12h.01M17 12h.01M7 10v4M5 12h4"/></svg>
                </div>
                <h3>{{ __('main.promo_feat4_title') }}</h3>
                <p>{{ __('main.promo_feat4_desc') }}</p>
            </div>
            <div class="pw-landing-feature">
                <div class="pw-landing-feature__icon" style="color:#34d399;">
                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4-4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                </div>
                <h3>{{ __('main.promo_feat5_title') }}</h3>
                <p>{{ __('main.promo_feat5_desc') }}</p>
            </div>
            <div class="pw-landing-feature">
                <div class="pw-landing-feature__icon" style="color:#c8972a;">
                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 12l2 2 4-4M12 7v1"/></svg>
                </div>
                <h3>{{ __('main.promo_feat6_title') }}</h3>
                <p>{{ __('main.promo_feat6_desc') }}</p>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
     COMMUNITY CTA — Discord & Social
============================================================ --}}
<section style="padding:3rem 1rem;background:linear-gradient(135deg,rgba(88,101,242,.08) 0%,rgba(200,151,42,.08) 100%);border-top:1px solid rgba(200,151,42,.1);border-bottom:1px solid rgba(200,151,42,.1);">
    <div class="pw-landing-section-inner">
        <div class="pw-promo-community">
            <div>
                <h2 style="font-family:'Cinzel',serif;font-size:clamp(1.2rem,2.5vw,1.7rem);font-weight:800;color:var(--pw-text-light);margin:0 0 .4rem;">{{ __('main.promo_community_title') }}</h2>
                <p style="font-size:.9rem;color:var(--pw-text-muted);margin:0;">{{ __('main.promo_community_sub') }}</p>
            </div>
            <div class="pw-promo-community__btns">
                <a href="{{ $__discord }}" target="_blank" rel="noopener" class="pw-promo-community__btn pw-promo-community__btn--discord">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028c.462-.63.874-1.295 1.226-1.994a.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03z"/></svg>
                    {{ __('main.promo_btn_discord') }}
                </a>
                @if($__facebook)
                <a href="{{ $__facebook }}" target="_blank" rel="noopener" class="pw-promo-community__btn pw-promo-community__btn--facebook">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    {{ __('main.promo_btn_facebook') }}
                </a>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
     FINAL CTA — Urgency close
============================================================ --}}
<section class="pw-landing-final-cta" style="padding:3rem 1rem;">
    <div class="pw-landing-section-inner" style="text-align:center;">
        <div style="display:inline-flex;align-items:center;gap:.5rem;padding:.35rem 1rem;border-radius:99px;background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.25);margin-bottom:1.2rem;">
            <span style="display:inline-block;width:7px;height:7px;border-radius:50%;background:#f87171;animation:livePulse 1.2s ease-in-out infinite;"></span>
            <span style="font-size:.78rem;font-weight:700;color:#f87171;letter-spacing:.05em;">{{ __('main.promo_urgency_badge') }}</span>
        </div>
        <h2 class="pw-landing-section-title" style="margin-bottom:.5rem;font-size:clamp(1.6rem,3.5vw,2.4rem);">{{ __('main.promo_final_title') }}</h2>
        <p class="pw-landing-section-sub" style="max-width:480px;margin:0 auto 2rem;font-size:1rem;">
            {{ __('main.promo_final_sub') }}
        </p>
        <a href="{{ route('register') }}" class="pw-landing-cta pw-landing-cta--large">
            {{ __('main.promo_final_cta') }}
        </a>
        <div style="display:flex;align-items:center;justify-content:center;gap:1.5rem;margin-top:1.2rem;flex-wrap:wrap;">
            <span style="font-size:.8rem;color:rgba(255,255,255,.45);">{{ __('main.promo_note1') }}</span>
            <span style="font-size:.8rem;color:rgba(255,255,255,.45);">{{ __('main.promo_note2') }}</span>
            <span style="font-size:.8rem;color:rgba(255,255,255,.45);">{{ __('main.promo_note3') }}</span>
        </div>
    </div>
</section>

{{-- Sticky CTA mobile --}}
<div id="pw-sticky-cta" style="position:fixed;bottom:0;left:0;right:0;z-index:999;padding:.75rem 1rem;background:linear-gradient(0deg,rgba(10,8,6,.98) 0%,rgba(10,8,6,.9) 100%);border-top:1px solid rgba(200,151,42,.3);transform:translateY(100%);transition:transform .3s ease;display:none;" aria-hidden="true">
    <a href="{{ route('register') }}" style="display:flex;align-items:center;justify-content:center;gap:.6rem;width:100%;padding:.8rem 1rem;border-radius:10px;font-family:'Cinzel',serif;font-size:.95rem;font-weight:800;color:#1a0f00;text-decoration:none;background:linear-gradient(268deg,#e7dacb 0%,#c59768 24%,#7f4f2c 51%,#a66b42 78%,#c49d6d 100%);box-shadow:0 -4px 20px rgba(200,151,42,.3);">
        {{ __('main.promo_cta_primary') }}
    </a>
</div>

@endsection

@push('scripts')
<script>
function landingCountdown() {
    return {
        days: '00', hours: '00', minutes: '00', seconds: '00',
        target: new Date('{{ $launchDate }}'),
        start() { this.tick(); setInterval(() => this.tick(), 1000); },
        tick() {
            const diff = Math.max(0, this.target - Date.now());
            this.days    = String(Math.floor(diff / 86400000)).padStart(2, '0');
            this.hours   = String(Math.floor((diff % 86400000) / 3600000)).padStart(2, '0');
            this.minutes = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0');
            this.seconds = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');
        }
    };
}

// Sticky CTA on mobile scroll
(function(){
    const el = document.getElementById('pw-sticky-cta');
    if (!el || window.innerWidth > 768) return;
    el.style.display = 'block';
    let shown = false;
    window.addEventListener('scroll', function() {
        if (window.scrollY > 400 && !shown) {
            el.style.transform = 'translateY(0)';
            el.removeAttribute('aria-hidden');
            shown = true;
        } else if (window.scrollY < 200 && shown) {
            el.style.transform = 'translateY(100%)';
            el.setAttribute('aria-hidden', 'true');
            shown = false;
        }
    }, { passive: true });
})();

// Ember particles
(function(){
    const c = document.getElementById('landing-embers');
    if (!c) return;
    const ctx = c.getContext('2d');
    let W, H, embers = [];
    function resize() { W = c.width = c.offsetWidth; H = c.height = c.offsetHeight; }
    resize(); window.addEventListener('resize', resize);
    function Ember() {
        this.x = Math.random() * W;
        this.y = H + 10;
        this.r = Math.random() * 2 + .8;
        this.speed = Math.random() * 1.2 + .4;
        this.drift = (Math.random() - .5) * .6;
        this.alpha = Math.random() * .5 + .3;
    }
    for (let i = 0; i < 35; i++) { const e = new Ember(); e.y = Math.random() * H; embers.push(e); }
    (function draw() {
        ctx.clearRect(0, 0, W, H);
        embers.forEach(e => {
            e.y -= e.speed; e.x += e.drift;
            if (e.y < -10) Object.assign(e, new Ember());
            ctx.beginPath();
            ctx.arc(e.x, e.y, e.r, 0, Math.PI * 2);
            ctx.fillStyle = 'rgba(200,151,42,' + e.alpha + ')';
            ctx.fill();
        });
        requestAnimationFrame(draw);
    })();
})();
</script>
@endpush
