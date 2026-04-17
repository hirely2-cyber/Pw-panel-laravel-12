@extends('layouts.app')

@php
    $__siteName = \App\Models\Setting::get('seo_title') ?: config('pw-config.server.name') ?: 'Perfect World';
    $__heroLogo = \App\Models\Setting::get('site_logo');
@endphp

@section('title', ($event->localizedTitle() ?? 'Event') . ' — ' . $__siteName)
@section('meta_description', $event->localizedDescription() ?? 'Event Pre-Register ' . config('pw-config.server.name'))

@section('content')

{{-- PAGE HERO --}}
<div class="pw-page-hero">
    <div class="pw-page-hero__bg" aria-hidden="true"></div>
    <canvas id="pw-event-particles" style="position:absolute;inset:0;width:100%;height:100%;pointer-events:none;z-index:0;" aria-hidden="true"></canvas>
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
            {{ $event->localizedTitle() }}
        </h1>
        <p class="pw-page-hero__sub">{{ $event->localizedDescription() }}</p>
        <nav class="pw-breadcrumb" aria-label="breadcrumb">
            <a href="{{ route('home') }}" class="pw-breadcrumb__item">
                <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true">
                    <path d="M2 7.5L8 2l6 5.5V14a1 1 0 01-1 1h-3.5v-3.5h-3V15H3a1 1 0 01-1-1V7.5z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/>
                </svg>
                {{ __('main.breadcrumb_home') }}
            </a>
            <span class="pw-breadcrumb__sep" aria-hidden="true">/</span>
            <span class="pw-breadcrumb__item pw-breadcrumb__item--active">{{ __('main.nav_event') }}</span>
        </nav>
    </div>
</div>

<section class="pw-section" id="event" style="padding-top:.5rem;">
    <div class="pw-section__inner pw-section__inner--narrow">

        {{-- ── PRIZE SHOWCASE ── --}}
        <div style="position:relative;text-align:center;padding:2.5rem 1rem;background:radial-gradient(ellipse at center,rgba(200,151,42,.08) 0%,transparent 70%);border-radius:14px;margin-bottom:1.5rem;">
            <div style="font-size:.78rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.15em;margin-bottom:.3rem;">{{ __('main.prelaunch_total_prize') }}</div>

            <div style="font-size:clamp(2rem,6vw,3.5rem);font-weight:900;font-family:'Cinzel',serif;background:linear-gradient(135deg,#fbbf24 0%,#f59e0b 30%,#fcd34d 50%,#f59e0b 70%,#c8972a 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;line-height:1.1;filter:drop-shadow(0 2px 8px rgba(251,191,36,.3));">
                Rp {{ number_format($totalRupiah, 0, ',', '.') }}
            </div>

            <div style="font-size:clamp(.85rem,2.5vw,1.05rem);color:var(--pw-text-muted);margin-top:.5rem;line-height:1.5;">
                <span style="color:#fbbf24;font-weight:700;">{{ number_format($totalCubi) }} Cubi Gold</span> {{ __('main.prelaunch_per_person') }}
            </div>

            <div style="width:60px;height:1px;background:linear-gradient(90deg,transparent,rgba(200,151,42,.4),transparent);margin:1rem auto;"></div>

            <div style="font-size:.72rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.1em;margin-bottom:.3rem;">{{ __('main.prelaunch_status') }}</div>
            @if($event->status === 'active')
                <div style="display:inline-flex;align-items:center;gap:.4rem;padding:.4rem 1.2rem;border-radius:20px;background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.25);">
                    <span style="width:8px;height:8px;border-radius:50%;background:#22c55e;display:inline-block;animation:pw-pulse-dot 1.5s infinite;"></span>
                    <span style="font-size:.85rem;font-weight:700;color:#22c55e;">{{ __('main.prelaunch_active') }}</span>
                </div>
            @else
                <div style="display:inline-flex;align-items:center;gap:.4rem;padding:.4rem 1.2rem;border-radius:20px;background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.25);">
                    <span style="font-size:.85rem;font-weight:700;color:#f59e0b;">{{ __('main.prelaunch_ended') }}</span>
                </div>
            @endif

            <div style="margin-top:.6rem;font-size:.78rem;color:var(--pw-text-muted);">
                {{ $event->start_at?->format('d M Y') }} — {{ $event->end_at?->format('d M Y') }}
            </div>
        </div>

        {{-- ── REGISTER REWARD BANNER ── --}}
        @if(!empty($event->register_rewards))
        <div class="pw-reg-reward-banner">
            <div class="pw-reg-reward-banner__glow" aria-hidden="true"></div>
            {{-- Left: Label + Amount --}}
            <div class="pw-reg-reward-banner__left">
                <div class="pw-reg-reward-banner__heading">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    {{ __('main.prelaunch_reg_reward_heading') }}
                </div>
                @foreach($event->register_rewards as $rw)
                <div class="pw-reg-reward-banner__amount">
                    <span class="pw-reg-reward-banner__number">{{ number_format($rw['amount']) }}</span>
                    <span class="pw-reg-reward-banner__item">{{ $rw['label'] }}</span>
                </div>
                @endforeach
            </div>
            {{-- Divider --}}
            <div class="pw-reg-reward-banner__divider" aria-hidden="true"></div>
            {{-- Right: Description --}}
            <div class="pw-reg-reward-banner__right">
                <div class="pw-reg-reward-banner__desc">
                    {{ __('main.prelaunch_register_reward_also') }}
                </div>
                <div class="pw-reg-reward-banner__note">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:-1px;flex-shrink:0;"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                    {{ __('main.prelaunch_register_reward_note') }}
                </div>
            </div>
        </div>
        @endif

        {{-- ── STAT CARDS ── --}}
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:1rem;margin-bottom:2rem;">
            <div class="pw-card" style="text-align:center;padding:1.2rem;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#c8972a" stroke-width="1.5" style="margin:0 auto .5rem;display:block;">
                    <path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4-4v2"/><circle cx="8.5" cy="7" r="4"/>
                    <line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/>
                </svg>
                <div style="font-size:1.6rem;font-weight:800;color:#c8972a;">{{ number_format($totalRegistered) }}</div>
                <div style="font-size:.78rem;color:var(--pw-text-muted);">{{ __('main.prelaunch_total_reg') }}</div>
            </div>
            <div class="pw-card" style="text-align:center;padding:1.2rem;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#c8972a" stroke-width="1.5" style="margin:0 auto .5rem;display:block;">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4-4v2"/><circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>
                </svg>
                <div style="font-size:1.6rem;font-weight:800;color:#c8972a;">{{ number_format($totalReferrals) }}</div>
                <div style="font-size:.78rem;color:var(--pw-text-muted);">{{ __('main.prelaunch_via_referral') }}</div>
            </div>
            <div class="pw-card" style="text-align:center;padding:1.2rem;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#c8972a" stroke-width="1.5" style="margin:0 auto .5rem;display:block;">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                </svg>
                <div style="font-size:1.6rem;font-weight:800;color:#c8972a;">Lv.{{ $reqLevel }}</div>
                <div style="font-size:.78rem;color:var(--pw-text-muted);">{{ __('main.prelaunch_req_level') }}</div>
            </div>
        </div>

        {{-- ── REFERRAL REWARD TIERS (5 kolom) ── --}}
        <div class="pw-card" style="padding:1.5rem;margin-bottom:2rem;">
            <h2 style="font-family:'Cinzel',serif;font-size:1.1rem;font-weight:700;color:var(--pw-text-light);margin:0 0 .5rem;text-align:center;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#c8972a" stroke-width="2" style="vertical-align:-3px;margin-right:.3rem;">
                    <path d="M20 12V8H6a2 2 0 01-2-2c0-1.1.9-2 2-2h12v4"/><path d="M4 6v12c0 1.1.9 2 2 2h14v-4"/>
                    <path d="M18 12a2 2 0 000 4h4v-4h-4z"/>
                </svg>
                {{ __('main.prelaunch_reward_title') }}
            </h2>
            <div style="font-size:.82rem;color:var(--pw-text-muted);text-align:center;margin-bottom:1rem;">
                {{ __('main.prelaunch_reward_sub') }}
            </div>
            <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:.8rem;">
                @foreach($tiers as $tier)
                <div style="background:rgba(200,151,42,.06);border:1px solid rgba(200,151,42,.15);border-radius:10px;padding:1rem .5rem;text-align:center;">
                    <div style="font-size:1.2rem;font-weight:800;color:#c8972a;">{{ $tier['count'] }}</div>
                    <div style="font-size:.7rem;color:var(--pw-text-muted);margin-bottom:.3rem;">{{ __('main.prelaunch_reward_referral') }}</div>
                    <div style="font-size:.82rem;font-weight:700;color:var(--pw-text-light);">{{ number_format($tier['reward']) }} Cubi</div>
                    <div style="font-size:.68rem;color:var(--pw-text-muted);">Rp {{ number_format($tier['reward'] * 1000, 0, ',', '.') }}</div>
                </div>
                @endforeach
            </div>
            <div style="text-align:center;margin-top:1rem;font-size:.78rem;color:var(--pw-text-muted);">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:-2px;">
                    <circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>
                </svg>
                {!! __('main.prelaunch_convert_note', ['level' => $reqLevel]) !!}
            </div>
        </div>

        {{-- ── TOP 3 REFERRER (Truename) ── --}}
        <div class="pw-card" style="padding:1.5rem;margin-bottom:2rem;">
            <h2 style="font-family:'Cinzel',serif;font-size:1.1rem;font-weight:700;color:var(--pw-text-light);margin:0 0 1rem;text-align:center;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#c8972a" stroke-width="2" style="vertical-align:-3px;margin-right:.3rem;">
                    <path d="M6 9H4.5a2.5 2.5 0 010-5C6 4 8 6 8 6"/><path d="M18 9h1.5a2.5 2.5 0 000-5C18 4 16 6 16 6"/>
                    <path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20 7 22"/>
                    <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20 17 22"/><path d="M18 2H6v7a6 6 0 0012 0V2z"/>
                </svg>
                {{ __('main.prelaunch_top_referrer') }}
            </h2>

            @if($referrers->isEmpty())
            <div style="text-align:center;padding:2rem;color:var(--pw-text-muted);font-size:.9rem;">
                {{ __('main.prelaunch_no_referral') }}
            </div>
            @else
            @php
                $podiumOrder  = [1, 0, 2];
                $podiumRank   = [2, 1, 3];
                $podiumClass  = ['pw-podium__step--silver', 'pw-podium__step--gold', 'pw-podium__step--bronze'];
                $rankClass    = ['pw-rank--2', 'pw-rank--1', 'pw-rank--3'];
                $rankColors   = ['#c0c0c0', '#ffd700', '#cd7f32'];
            @endphp

            @if($referrers->count() >= 3)
            <div class="pw-podium">
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
                    <div class="pw-podium__name">{{ $r->truename ?: $r->name }}</div>
                    <div class="pw-podium__sub" style="color:var(--pw-text-muted);">{{ $r->referral_code }}</div>
                    <div class="pw-podium__level" style="color:{{ $rankColors[$idx] }}">{{ $r->referral_count }} Referral</div>
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
            @else
            {{-- Kurang dari 3 referrer --}}
            <div style="display:flex;justify-content:center;gap:2rem;flex-wrap:wrap;padding:1rem 0;">
                @foreach($referrers as $index => $r)
                <div style="text-align:center;">
                    <div style="width:60px;height:60px;border-radius:50%;background:linear-gradient(135deg,{{ ['#fbbf24','#94a3b8','#cd7f32'][$index] }},{{ ['#f59e0b','#cbd5e1','#daa06d'][$index] }});display:flex;align-items:center;justify-content:center;font-size:1.4rem;font-weight:800;color:#1e293b;margin:0 auto .5rem;">
                        {{ strtoupper(substr($r->truename ?: $r->name, 0, 1)) }}
                    </div>
                    <div style="font-weight:700;color:var(--pw-text-light);">{{ $r->truename ?: $r->name }}</div>
                    <div style="font-size:.85rem;color:#c8972a;font-weight:700;">{{ $r->referral_count }} referral</div>
                </div>
                @endforeach
            </div>
            @endif
            @endif

            <div style="text-align:center;margin-top:1.2rem;">
                <a href="{{ route('referral.ranking') }}" class="pw-btn pw-btn--gold pw-btn--sm">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:-2px;margin-right:.2rem;">
                        <path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/>
                    </svg>
                    {{ __('main.prelaunch_view_ranking') }}
                </a>
            </div>
        </div>

        {{-- ── CARA MENDAPATKAN HADIAH (Collapsible) ── --}}
        <div class="pw-card" style="margin-bottom:2rem;padding:0;" x-data="{ open: false }">
            <button type="button" @click="open = !open" class="pw-event-tnc-btn"
                    style="width:100%;display:flex;align-items:center;justify-content:space-between;gap:.5rem;padding:1.2rem 1.5rem;background:none;border:none;cursor:pointer;text-align:left;">
                <div style="display:flex;align-items:center;gap:.5rem;">
                    <svg viewBox="0 0 20 20" fill="none" width="20" style="flex-shrink:0;"><path d="M4 3h12a1 1 0 011 1v12a1 1 0 01-1 1H4a1 1 0 01-1-1V4a1 1 0 011-1z" stroke="#c8972a" stroke-width="1.3"/><path d="M7 7h6M7 10h6M7 13h4" stroke="#c8972a" stroke-width="1.3" stroke-linecap="round"/></svg>
                    <span style="font-size:1.05rem;font-weight:700;color:var(--pw-text-light);">{{ __('main.prelaunch_how_title') }}</span>
                </div>
                <svg viewBox="0 0 16 16" fill="none" width="14" style="flex-shrink:0;transition:transform .2s;" :style="open ? 'transform:rotate(180deg)' : ''"><path d="M4 6l4 4 4-4" stroke="var(--pw-gold-light)" stroke-width="1.5" stroke-linecap="round"/></svg>
            </button>

            <div x-show="open" x-collapse x-cloak style="padding:0 1.5rem 1.5rem;">
                <div style="font-size:.88rem;color:var(--pw-text-light);line-height:1.8;text-align:left;">

                    {{-- Langkah 1 --}}
                    <div style="margin-bottom:1.2rem;">
                        <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.3rem;">
                            <span style="flex-shrink:0;width:28px;height:28px;border-radius:50%;background:rgba(200,151,42,.15);border:1px solid rgba(200,151,42,.3);display:flex;align-items:center;justify-content:center;font-size:.78rem;font-weight:800;color:#c8972a;">1</span>
                            <strong style="color:var(--pw-gold-light);">{{ __('main.prelaunch_step1_title') }}</strong>
                        </div>
                        <div style="padding-left:2.5rem;">
                            {!! __('main.prelaunch_step1_desc', ['site' => $__siteName]) !!}
                        </div>
                    </div>

                    {{-- Langkah 2 --}}
                    <div style="margin-bottom:1.2rem;">
                        <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.3rem;">
                            <span style="flex-shrink:0;width:28px;height:28px;border-radius:50%;background:rgba(200,151,42,.15);border:1px solid rgba(200,151,42,.3);display:flex;align-items:center;justify-content:center;font-size:.78rem;font-weight:800;color:#c8972a;">2</span>
                            <strong style="color:var(--pw-gold-light);">{{ __('main.prelaunch_step2_title') }}</strong>
                        </div>
                        <div style="padding-left:2.5rem;">
                            {!! __('main.prelaunch_step2_desc', ['url' => route('profile')]) !!}
                        </div>
                    </div>

                    {{-- Langkah 3 --}}
                    <div style="margin-bottom:1.2rem;">
                        <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.3rem;">
                            <span style="flex-shrink:0;width:28px;height:28px;border-radius:50%;background:rgba(200,151,42,.15);border:1px solid rgba(200,151,42,.3);display:flex;align-items:center;justify-content:center;font-size:.78rem;font-weight:800;color:#c8972a;">3</span>
                            <strong style="color:var(--pw-gold-light);">{{ __('main.prelaunch_step3_title') }}</strong>
                        </div>
                        <div style="padding-left:2.5rem;">
                            {{ __('main.prelaunch_step3_desc') }}
                        </div>
                    </div>

                    {{-- Langkah 4 --}}
                    <div style="margin-bottom:1.2rem;">
                        <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.3rem;">
                            <span style="flex-shrink:0;width:28px;height:28px;border-radius:50%;background:rgba(200,151,42,.15);border:1px solid rgba(200,151,42,.3);display:flex;align-items:center;justify-content:center;font-size:.78rem;font-weight:800;color:#c8972a;">4</span>
                            <strong style="color:var(--pw-gold-light);">{{ __('main.prelaunch_step4_title') }}</strong>
                        </div>
                        <div style="padding-left:2.5rem;">
                            {!! __('main.prelaunch_step4_desc', ['level' => $reqLevel]) !!}
                        </div>
                    </div>

                    {{-- Langkah 5 --}}
                    <div style="margin-bottom:1.2rem;">
                        <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.3rem;">
                            <span style="flex-shrink:0;width:28px;height:28px;border-radius:50%;background:rgba(200,151,42,.15);border:1px solid rgba(200,151,42,.3);display:flex;align-items:center;justify-content:center;font-size:.78rem;font-weight:800;color:#c8972a;">5</span>
                            <strong style="color:var(--pw-gold-light);">{{ __('main.prelaunch_step5_title') }}</strong>
                        </div>
                        <div style="padding-left:2.5rem;">
                            {!! __('main.prelaunch_step5_desc') !!}
                        </div>
                    </div>

                    {{-- Langkah 6 --}}
                    <div style="margin-bottom:1.2rem;">
                        <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.3rem;">
                            <span style="flex-shrink:0;width:28px;height:28px;border-radius:50%;background:rgba(200,151,42,.15);border:1px solid rgba(200,151,42,.3);display:flex;align-items:center;justify-content:center;font-size:.78rem;font-weight:800;color:#c8972a;">6</span>
                            <strong style="color:var(--pw-gold-light);">{{ __('main.prelaunch_step6_title') }}</strong>
                        </div>
                        <div style="padding-left:2.5rem;">
                            {{ __('main.prelaunch_step6_desc') }}
                        </div>
                    </div>

                    {{-- Logo + Admin Name --}}
                    @if(!empty($adminNames))
                    <div style="text-align:center;margin:1.2rem 0 0;padding:1rem 1rem .8rem;background:rgba(200,151,42,.06);border:1px solid rgba(200,151,42,.12);border-radius:8px;">
                        @if($__heroLogo)
                        <img src="{{ asset('storage/' . $__heroLogo) }}" alt="{{ $__siteName }}" style="max-height:70px;width:auto;display:block;margin:0 auto .6rem;">
                        @else
                        <div style="font-family:'Cinzel',serif;font-size:1.3rem;font-weight:900;background:linear-gradient(135deg,#fbbf24,#c8972a);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;margin-bottom:.6rem;">{{ $__siteName }}</div>
                        @endif
                        <div style="font-size:.72rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.1em;margin-bottom:.2rem;">{{ __('main.prelaunch_admin_label') }}</div>
                        <div style="font-weight:700;color:var(--pw-gold-light);">{{ $adminNames }}</div>
                    </div>
                    @endif

                    {{-- Divider --}}
                    <div style="width:100%;height:1px;background:linear-gradient(90deg,transparent,rgba(200,151,42,.2),transparent);margin:1.2rem 0;"></div>

                    {{-- Notes --}}
                    <div style="background:rgba(245,158,11,.06);border:1px solid rgba(245,158,11,.15);border-radius:8px;padding:1rem;">
                        <div style="display:flex;align-items:flex-start;gap:.5rem;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2" style="flex-shrink:0;margin-top:2px;">
                                <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                            </svg>
                            <div>
                                <strong style="color:#f59e0b;font-size:.85rem;">{{ __('main.prelaunch_notes_title') }}</strong>
                                <div style="font-size:.82rem;color:var(--pw-text-muted);margin-top:.2rem;line-height:1.7;">
                                    {!! __('main.prelaunch_notes_desc') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── CTA ── --}}
        <div style="text-align:center;padding:1rem 0 2rem;">
            @auth
            <a href="{{ route('profile') }}" class="pw-btn pw-btn--gold">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:-2px;margin-right:.3rem;">
                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/>
                </svg>
                {{ __('main.prelaunch_btn_my_referral') }}
            </a>
            @else
            <a href="{{ route('register') }}" class="pw-btn pw-btn--gold">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:-2px;margin-right:.3rem;">
                    <path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4-4v2"/><circle cx="8.5" cy="7" r="4"/>
                    <line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/>
                </svg>
                {{ __('main.prelaunch_btn_register') }}
            </a>
            @endauth
        </div>

    </div>
</section>

@push('scripts')
<script>
(function () {
    const canvas = document.getElementById('pw-event-particles');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    let W, H, particles = [];
    const COLORS = ['#fbbf24','#f59e0b','#fcd34d','#c8972a','#86efac','#4ade80','#e8b84b'];

    function resize() {
        W = canvas.width = canvas.offsetWidth;
        H = canvas.height = canvas.offsetHeight;
    }

    function makePart() {
        const isCoin = Math.random() > 0.35;
        return {
            x: Math.random() * W,
            y: Math.random() * H - H,
            r: isCoin ? Math.random() * 5 + 3 : Math.random() * 2.5 + 1,
            speed: Math.random() * 0.7 + 0.25,
            wobble: Math.random() * Math.PI * 2,
            wobbleSpeed: Math.random() * 0.025 + 0.008,
            squeeze: Math.random() * Math.PI * 2,
            squeezeSpeed: Math.random() * 0.04 + 0.015,
            color: COLORS[Math.floor(Math.random() * COLORS.length)],
            opacity: Math.random() * 0.45 + 0.2,
            isCoin: isCoin,
        };
    }

    function init() {
        resize();
        particles = [];
        for (let i = 0; i < 50; i++) {
            const p = makePart();
            p.y = Math.random() * H;
            particles.push(p);
        }
    }

    function draw() {
        ctx.clearRect(0, 0, W, H);
        particles.forEach(p => {
            ctx.save();
            ctx.globalAlpha = p.opacity;
            const cx = p.x + Math.sin(p.wobble) * 12;
            if (p.isCoin) {
                const scaleX = Math.abs(Math.cos(p.squeeze));
                ctx.beginPath();
                ctx.ellipse(cx, p.y, p.r * scaleX, p.r, 0, 0, Math.PI * 2);
                ctx.fillStyle = p.color;
                ctx.fill();
                if (scaleX > 0.15) {
                    ctx.strokeStyle = 'rgba(0,0,0,.15)';
                    ctx.lineWidth = 0.5;
                    ctx.stroke();
                }
            } else {
                // star / sparkle
                ctx.beginPath();
                ctx.arc(cx, p.y, p.r, 0, Math.PI * 2);
                ctx.fillStyle = p.color;
                ctx.fill();
            }
            ctx.restore();
        });
    }

    function update() {
        particles.forEach(p => {
            p.y += p.speed;
            p.wobble += p.wobbleSpeed;
            p.squeeze += p.squeezeSpeed;
            if (p.y > H + 15) { p.y = -15; p.x = Math.random() * W; }
        });
    }

    function loop() { draw(); update(); requestAnimationFrame(loop); }
    window.addEventListener('resize', resize);
    init();
    loop();
})();
</script>
@endpush
@endsection
