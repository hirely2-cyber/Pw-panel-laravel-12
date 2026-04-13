@extends('layouts.app')

@php
    $__siteName = \App\Models\Setting::get('seo_title') ?: config('pw-config.server.name') ?: 'Perfect World';
@endphp

@section('title', __('main.profile_title') . ' — ' . $__siteName)

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
        <h1 class="pw-page-hero__title">{{ __('main.profile_title') }}</h1>
        <p class="pw-page-hero__sub">{{ __('main.profile_subtitle') }}</p>
        <nav class="pw-breadcrumb" aria-label="breadcrumb">
            <a href="{{ route('home') }}" class="pw-breadcrumb__item">
                <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true">
                    <path d="M2 7.5L8 2l6 5.5V14a1 1 0 01-1 1h-3.5v-3.5h-3V15H3a1 1 0 01-1-1V7.5z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/>
                </svg>
                {{ __('main.profile_breadcrumb_home') }}
            </a>
            <span class="pw-breadcrumb__sep" aria-hidden="true">/</span>
            <span class="pw-breadcrumb__item pw-breadcrumb__item--active">{{ __('main.profile_breadcrumb') }}</span>
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

        {{-- Saldo Bar (full width) --}}
        <div class="pw-profile-card" style="margin-bottom:1.2rem;">
            <div style="display:flex;align-items:center;gap:1.2rem;flex-wrap:wrap;">
                <div style="display:flex;align-items:center;gap:.5rem;flex-shrink:0;">
                    <img src="{{ asset('images/gif_icon/web_coin.gif') }}" alt="points" style="width:22px;height:22px;">
                    <span style="font-family:'Cinzel',serif;font-weight:700;font-size:.95rem;color:var(--pw-text-light);text-transform:uppercase;letter-spacing:.03em;">{{ __('main.profile_balance') }}</span>
                </div>
                <div style="display:flex;align-items:center;gap:1.5rem;flex:1;flex-wrap:wrap;">
                    <div style="display:flex;align-items:center;gap:.5rem;">
                        <span style="font-size:.75rem;color:var(--pw-text-muted);white-space:nowrap;">{{ __('main.profile_gold_points') }}</span>
                        <span style="font-family:'Cinzel',serif;font-weight:700;font-size:1.05rem;color:var(--pw-gold);">{{ number_format($user->money) }}</span>
                        <img src="{{ asset('images/gif_icon/web_coin.gif') }}" alt="points" style="width:18px;height:18px;">
                    </div>
                    <div style="width:1px;height:20px;background:rgba(255,255,255,.1);flex-shrink:0;"></div>
                    <div style="display:flex;align-items:center;gap:.5rem;">
                        <span style="font-size:.75rem;color:var(--pw-text-muted);white-space:nowrap;">{{ __('main.profile_coin_game') }}</span>
                        <span style="font-family:'Cinzel',serif;font-weight:700;font-size:1.05rem;color:#60d0ff;">{{ number_format($cubiCoins) }}</span>
                        <img src="{{ asset('images/gif_icon/gold-icon.gif') }}" alt="coin" style="width:18px;height:18px;">
                    </div>
                </div>
                <a href="{{ route('cubi-shop') }}" class="pw-btn pw-btn--gold pw-btn--sm" style="flex-shrink:0;">
                    {{ __('main.profile_topup') }}
                </a>
            </div>
        </div>

        <div class="pw-profile-layout">

            {{-- LEFT: Account Info --}}
            <div class="pw-profile-account">

                {{-- Account Card --}}
                <div class="pw-profile-card">
                    <div class="pw-profile-card__header">
                        <svg viewBox="0 0 20 20" fill="none" width="16" aria-hidden="true"><circle cx="10" cy="7" r="4" stroke="#c8972a" stroke-width="1.5"/><path d="M3 17c0-3.314 3.134-6 7-6s7 2.686 7 6" stroke="#c8972a" stroke-width="1.5" stroke-linecap="round"/></svg>
                        {{ __('main.profile_account_info') }}
                    </div>

                    <div class="pw-profile-user">
                        <div class="pw-profile-user__avatar">
                            @if($user->profile_photo_path)
                                <img src="{{ Storage::url($user->profile_photo_path) }}" alt="{{ $user->name }}">
                            @else
                                <span>{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            @endif
                        </div>
                        <div class="pw-profile-user__info">
                            <div class="pw-profile-user__name">{{ $user->name }}</div>
                            <div class="pw-profile-user__meta">
                                @if($user->role === 'admin')
                                    <span class="pw-profile-badge pw-profile-badge--admin-front">Admin</span>
                                @elseif($user->role === 'gm')
                                    <span class="pw-profile-badge pw-profile-badge--gm-front">GM</span>
                                @else
                                    <span class="pw-profile-badge">Player</span>
                                @endif
                                <span>ID: {{ $user->ID }}</span>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf @method('PUT')

                        <label class="pw-profile-label">{{ __('main.profile_username') }}</label>
                        <input type="text" class="pw-profile-input pw-profile-input--disabled" value="{{ $user->name }}" disabled>
                        <p class="pw-profile-hint">{{ __('main.profile_username_hint') }}</p>

                        <label class="pw-profile-label">{{ __('main.profile_email') }}</label>
                        <input type="email" name="email" class="pw-profile-input" value="{{ old('email', $user->email) }}" required>
                        @error('email') <p class="pw-profile-error">{{ $message }}</p> @enderror

                        <label class="pw-profile-label">{{ __('main.profile_phone') }}</label>
                        <input type="text" name="mobilenumber" class="pw-profile-input" value="{{ old('mobilenumber', $user->mobilenumber) }}" placeholder="+62 8xx xxxx xxxx">
                        @error('mobilenumber') <p class="pw-profile-error">{{ $message }}</p> @enderror

                        <button type="submit" class="pw-btn pw-btn--gold pw-btn--sm" style="margin-top:.5rem;">
                            <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true"><path d="M13.5 4.5l-8 8L2 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            {{ __('main.profile_save') }}
                        </button>
                    </form>
                </div>

                {{-- Security Card --}}
                <div class="pw-profile-card">
                    <div class="pw-profile-card__header">
                        <svg viewBox="0 0 16 16" fill="none" width="14" aria-hidden="true"><rect x="3" y="7" width="10" height="7" rx="1.5" stroke="#c8972a" stroke-width="1.3"/><path d="M5 7V5a3 3 0 016 0v2" stroke="#c8972a" stroke-width="1.3" stroke-linecap="round"/></svg>
                        {{ __('main.profile_security') }}
                    </div>
                    <p class="pw-profile-hint" style="margin-bottom:.8rem;">{{ __('main.profile_security_hint') }}</p>
                    <button type="button" class="pw-btn pw-btn--ghost pw-btn--sm" onclick="document.getElementById('pwModalPassword').style.display='flex'">
                        <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true"><rect x="3" y="7" width="10" height="7" rx="1.5" stroke="currentColor" stroke-width="1.3"/><path d="M5 7V5a3 3 0 016 0v2" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                        {{ __('main.profile_change_password') }}
                    </button>
                </div>
            </div>

            {{-- RIGHT: Characters --}}
            <div class="pw-profile-characters" x-data="{ selected: null }">
                <div class="pw-profile-card">
                    <div class="pw-profile-card__header">
                        <svg viewBox="0 0 20 20" fill="none" width="15" aria-hidden="true"><path d="M10 2l2.4 5 5.6.8-4 3.9.9 5.5L10 14.5l-4.9 2.7.9-5.5L2 7.8l5.6-.8L10 2z" stroke="#c8972a" stroke-width="1.3" stroke-linejoin="round"/></svg>
                        {{ __('main.profile_characters') }}
                        <span class="pw-profile-card__count">{{ $characters->count() }}</span>
                    </div>

                    @if($characters->isEmpty())
                    <div class="pw-profile-empty">
                        <svg viewBox="0 0 48 48" fill="none" width="40" aria-hidden="true"><circle cx="24" cy="24" r="22" stroke="currentColor" stroke-width="1.5" opacity=".3"/><path d="M24 16v8M24 28v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" opacity=".4"/></svg>
                        <p>{{ __('main.profile_no_characters') }}</p>
                    </div>
                    @else
                    <div class="pw-char-list">
                        @foreach($characters as $char)
                        <button class="pw-char-row" :class="{ 'is-active': selected === {{ $char->role_id }} }" @click="selected = selected === {{ $char->role_id }} ? null : {{ $char->role_id }}" type="button">
                            <div class="pw-char-row__left">
                                <div class="pw-char-row__avatar">
                                    <img src="/images/class/{{ $char->class_icon }}" alt="{{ $char->class }}" width="28" height="28">
                                </div>
                                <div class="pw-char-row__info">
                                    <div class="pw-char-row__name">{{ $char->name }}</div>
                                    <div class="pw-char-row__sub">Lv.{{ $char->level }} {{ $char->class }}</div>
                                </div>
                            </div>
                            <svg class="pw-char-row__arrow" :class="{ 'is-open': selected === {{ $char->role_id }} }" viewBox="0 0 16 16" fill="none" width="14"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                        </button>

                        {{-- Character Detail Panel --}}
                        <div class="pw-char-detail" x-show="selected === {{ $char->role_id }}" x-transition.origin.top x-cloak>

                            @if($char->has_extended)
                            {{-- Radar Chart (pure SVG, server-rendered) --}}
                            @php
                                $radarStats = [
                                    ['name' => 'STR', 'val' => $char->strength ?? 0],
                                    ['name' => 'AGI', 'val' => $char->agility ?? 0],
                                    ['name' => 'INT', 'val' => $char->energy ?? 0],
                                    ['name' => 'CON', 'val' => $char->vitality ?? 0],
                                    ['name' => 'P-Atk', 'val' => intval((($char->p_atk_min ?? 0) + ($char->p_atk_max ?? 0)) / 2)],
                                    ['name' => 'P-Def', 'val' => $char->p_def ?? 0],
                                ];
                                $maxVal = max(array_column($radarStats, 'val')) ?: 1;
                                $cx = 100; $cy = 100; $r = 70; $n = 6;

                                $pointAt = function($i, $scale) use ($cx, $cy, $r, $n) {
                                    $angle = (2 * M_PI * $i / $n) - M_PI / 2;
                                    return [
                                        round($cx + cos($angle) * $r * $scale, 2),
                                        round($cy + sin($angle) * $r * $scale, 2),
                                    ];
                                };

                                $ringPoints = function($scale) use ($n, $pointAt) {
                                    $pts = [];
                                    for ($i = 0; $i < $n; $i++) {
                                        [$x, $y] = $pointAt($i, $scale);
                                        $pts[] = "$x,$y";
                                    }
                                    return implode(' ', $pts);
                                };

                                $dataPoints = [];
                                foreach ($radarStats as $i => $s) {
                                    $v = max($s['val'] / $maxVal, 0.05);
                                    [$x, $y] = $pointAt($i, $v);
                                    $dataPoints[] = "$x,$y";
                                }
                                $dataPoly = implode(' ', $dataPoints);
                            @endphp
                            <div class="pw-radar">
                                <svg viewBox="0 0 200 200" class="pw-radar__svg">
                                    {{-- Grid rings --}}
                                    @foreach([0.2, 0.4, 0.6, 0.8, 1.0] as $ring)
                                        <polygon points="{{ $ringPoints($ring) }}" class="pw-radar__ring"/>
                                    @endforeach
                                    {{-- Axis lines --}}
                                    @for($i = 0; $i < $n; $i++)
                                        @php [$ax, $ay] = $pointAt($i, 1); @endphp
                                        <line x1="{{ $cx }}" y1="{{ $cy }}" x2="{{ $ax }}" y2="{{ $ay }}" class="pw-radar__axis"/>
                                    @endfor
                                    {{-- Data fill + stroke --}}
                                    <polygon points="{{ $dataPoly }}" class="pw-radar__data"/>
                                    <polygon points="{{ $dataPoly }}" class="pw-radar__data-stroke"/>
                                    {{-- Vertex dots --}}
                                    @foreach($dataPoints as $dp)
                                        @php [$dx, $dy] = explode(',', $dp); @endphp
                                        <circle cx="{{ $dx }}" cy="{{ $dy }}" r="2.5" class="pw-radar__dot"/>
                                    @endforeach
                                    {{-- Labels --}}
                                    @foreach($radarStats as $i => $s)
                                        @php [$lx, $ly] = $pointAt($i, 1.25); @endphp
                                        <text x="{{ $lx }}" y="{{ $ly - 4 }}" text-anchor="middle" dominant-baseline="middle" class="pw-radar__label">{{ $s['name'] }}</text>
                                        <text x="{{ $lx }}" y="{{ $ly + 7 }}" text-anchor="middle" dominant-baseline="middle" class="pw-radar__label-val">{{ number_format($s['val']) }}</text>
                                    @endforeach
                                </svg>
                            </div>
                            <div class="pw-char-detail__divider"></div>
                            @endif

                            <div class="pw-char-detail__grid">
                                <div class="pw-char-detail__item">
                                    <span class="pw-char-detail__label">{{ __('main.char_id') }}</span>
                                    <span class="pw-char-detail__val">{{ $char->role_id }}</span>
                                </div>
                                <div class="pw-char-detail__item">
                                    <span class="pw-char-detail__label">Level</span>
                                    <span class="pw-char-detail__val pw-char-detail__val--gold">{{ $char->level }}</span>
                                </div>
                                <div class="pw-char-detail__item">
                                    <span class="pw-char-detail__label">Class</span>
                                    <span class="pw-char-detail__val pw-char-detail__val--class">
                                        <img src="/images/class/{{ $char->class_icon }}" alt="{{ $char->class }}" width="16" height="16">
                                        {{ $char->class }}
                                    </span>
                                </div>
                                <div class="pw-char-detail__item">
                                    <span class="pw-char-detail__label">Race</span>
                                    <span class="pw-char-detail__val">{{ $char->race }}</span>
                                </div>
                                <div class="pw-char-detail__item">
                                    <span class="pw-char-detail__label">Gender</span>
                                    <span class="pw-char-detail__val">{{ $char->gender }}</span>
                                </div>
                                @if($char->cultivation)
                                <div class="pw-char-detail__item">
                                    <span class="pw-char-detail__label">Cultivation</span>
                                    <span class="pw-char-detail__val">{{ $char->cultivation }}</span>
                                </div>
                                @endif
                                <div class="pw-char-detail__item">
                                    <span class="pw-char-detail__label">Guild</span>
                                    <span class="pw-char-detail__val">{{ $char->faction_name ?? '—' }}</span>
                                </div>
                                @if($char->faction_name)
                                <div class="pw-char-detail__item">
                                    <span class="pw-char-detail__label">Guild Level</span>
                                    <span class="pw-char-detail__val">{{ $char->faction_level }}</span>
                                </div>
                                @endif
                                @if($char->spouse)
                                <div class="pw-char-detail__item">
                                    <span class="pw-char-detail__label">Spouse ID</span>
                                    <span class="pw-char-detail__val">{{ $char->spouse }}</span>
                                </div>
                                @endif
                            </div>

                            @if($char->has_extended)
                            {{-- Coins Section --}}
                            <div class="pw-char-detail__divider"></div>
                            <div class="pw-char-detail__title">
                                <svg viewBox="0 0 16 16" fill="none" width="12" aria-hidden="true"><circle cx="8" cy="8" r="6" stroke="currentColor" stroke-width="1.2"/><text x="8" y="11" text-anchor="middle" font-size="7" font-weight="700" fill="currentColor">$</text></svg>
                                {{ __('main.char_coins') }}
                            </div>
                            <div class="pw-char-detail__grid">
                                <div class="pw-char-detail__item">
                                    <span class="pw-char-detail__label">{{ __('main.char_pocket') }}</span>
                                    <span class="pw-char-detail__val pw-char-detail__val--gold">{{ number_format($char->pocket_coins ?? 0) }}</span>
                                </div>
                                <div class="pw-char-detail__item">
                                    <span class="pw-char-detail__label">{{ __('main.char_storehouse') }}</span>
                                    <span class="pw-char-detail__val pw-char-detail__val--gold">{{ number_format($char->store_coins ?? 0) }}</span>
                                </div>
                                <div class="pw-char-detail__item">
                                    <span class="pw-char-detail__label">{{ __('main.char_reputation') }}</span>
                                    <span class="pw-char-detail__val">{{ number_format($char->reputation ?? 0) }}</span>
                                </div>
                                <div class="pw-char-detail__item">
                                    <span class="pw-char-detail__label">{{ __('main.char_spirit') }}</span>
                                    <span class="pw-char-detail__val">{{ number_format($char->sp ?? 0) }}</span>
                                </div>
                            </div>

                            {{-- Stats Section --}}
                            <div class="pw-char-detail__divider"></div>
                            <div class="pw-char-detail__title">
                                <svg viewBox="0 0 16 16" fill="none" width="12" aria-hidden="true"><path d="M8 2v12M2 8h12" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                                {{ __('main.char_stats') }}
                            </div>
                            <div class="pw-char-detail__grid">
                                <div class="pw-char-detail__item">
                                    <span class="pw-char-detail__label">HP (Max)</span>
                                    <span class="pw-char-detail__val pw-char-detail__val--hp">{{ number_format($char->hp ?? 0) }}</span>
                                </div>
                                <div class="pw-char-detail__item">
                                    <span class="pw-char-detail__label">MP (Max)</span>
                                    <span class="pw-char-detail__val pw-char-detail__val--mp">{{ number_format($char->mp ?? 0) }}</span>
                                </div>
                                <div class="pw-char-detail__item">
                                    <span class="pw-char-detail__label">P-Atk</span>
                                    <span class="pw-char-detail__val">{{ $char->p_atk_min ?? 0 }} – {{ $char->p_atk_max ?? 0 }}</span>
                                </div>
                                <div class="pw-char-detail__item">
                                    <span class="pw-char-detail__label">M-Atk</span>
                                    <span class="pw-char-detail__val">{{ $char->m_atk_min ?? 0 }} – {{ $char->m_atk_max ?? 0 }}</span>
                                </div>
                                <div class="pw-char-detail__item">
                                    <span class="pw-char-detail__label">P-Def</span>
                                    <span class="pw-char-detail__val">{{ number_format($char->p_def ?? 0) }}</span>
                                </div>
                                @if($char->vigor)
                                <div class="pw-char-detail__item">
                                    <span class="pw-char-detail__label">Vigor</span>
                                    <span class="pw-char-detail__val">{{ $char->vigor }}</span>
                                </div>
                                @endif
                            </div>

                            {{-- Attributes Section --}}
                            <div class="pw-char-detail__divider"></div>
                            <div class="pw-char-detail__title">
                                <svg viewBox="0 0 16 16" fill="none" width="12" aria-hidden="true"><path d="M4 12V6M8 12V4M12 12V8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                                {{ __('main.char_attributes') }}
                            </div>
                            <div class="pw-char-detail__attrs">
                                <div class="pw-char-detail__attr pw-char-detail__attr--str">
                                    <span class="pw-char-detail__attr-val">{{ $char->strength ?? 0 }}</span>
                                    <span class="pw-char-detail__attr-label">STR</span>
                                </div>
                                <div class="pw-char-detail__attr pw-char-detail__attr--agi">
                                    <span class="pw-char-detail__attr-val">{{ $char->agility ?? 0 }}</span>
                                    <span class="pw-char-detail__attr-label">AGI</span>
                                </div>
                                <div class="pw-char-detail__attr pw-char-detail__attr--con">
                                    <span class="pw-char-detail__attr-val">{{ $char->vitality ?? 0 }}</span>
                                    <span class="pw-char-detail__attr-label">CON</span>
                                </div>
                                <div class="pw-char-detail__attr pw-char-detail__attr--int">
                                    <span class="pw-char-detail__attr-val">{{ $char->energy ?? 0 }}</span>
                                    <span class="pw-char-detail__attr-label">INT</span>
                                </div>
                            </div>
                            @endif

                            <div class="pw-char-detail__divider"></div>

                            <div class="pw-char-detail__title">
                                <svg viewBox="0 0 16 16" fill="none" width="12" aria-hidden="true"><path d="M8 1l2 4.5 5 .7-3.6 3.5.9 5L8 12.3 3.7 14.7l.9-5L1 6.2l5-.7L8 1z" stroke="currentColor" stroke-width="1.2" stroke-linejoin="round"/></svg>
                                {{ __('main.char_pvp_stats') }}
                            </div>
                            <div class="pw-char-detail__pvp">
                                <div class="pw-char-detail__pvp-item">
                                    <span class="pw-char-detail__pvp-val pw-char-detail__pvp-val--kill">{{ number_format($char->pvp_kills) }}</span>
                                    <span class="pw-char-detail__pvp-label">{{ __('main.char_kills') }}</span>
                                </div>
                                <div class="pw-char-detail__pvp-sep"></div>
                                <div class="pw-char-detail__pvp-item">
                                    <span class="pw-char-detail__pvp-val pw-char-detail__pvp-val--dead">{{ number_format($char->pvp_deads) }}</span>
                                    <span class="pw-char-detail__pvp-label">{{ __('main.char_deaths') }}</span>
                                </div>
                                <div class="pw-char-detail__pvp-sep"></div>
                                <div class="pw-char-detail__pvp-item">
                                    <span class="pw-char-detail__pvp-val">{{ $char->pvp_deads > 0 ? number_format($char->pvp_kills / $char->pvp_deads, 2) : '∞' }}</span>
                                    <span class="pw-char-detail__pvp-label">{{ __('main.char_kd') }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

        </div>{{-- /.pw-profile-layout --}}

        {{-- Referral Card (Full Width) --}}
        @if(config('pw-config.referral.enabled') && $referralStats)
        <div class="pw-profile-card" style="margin-top:1.25rem;">
            <div class="pw-profile-card__header">
                <svg viewBox="0 0 16 16" fill="none" width="14" aria-hidden="true"><path d="M8 1v6M11 4H5M13 8a5 5 0 11-10 0" stroke="#c8972a" stroke-width="1.3" stroke-linecap="round"/><circle cx="4" cy="12" r="2" stroke="#c8972a" stroke-width="1.2"/><circle cx="12" cy="12" r="2" stroke="#c8972a" stroke-width="1.2"/></svg>
                {{ __('main.profile_referral') }}
                @if($referralStats->is_partner)
                <span class="pw-badge" style="background:rgba(168,85,247,.15);color:#c084fc;margin-left:.5rem;">{{ $referralStats->partner->label }}</span>
                @endif
            </div>

            @if($referralStats->is_partner)
            <div style="background:rgba(168,85,247,.08);border:1px solid rgba(168,85,247,.2);border-radius:6px;padding:.6rem .8rem;margin-bottom:.8rem;font-size:.8rem;color:#c084fc;">
                <strong>{{ __('main.profile_partner_reward') }}:</strong>
                {{ number_format($referralStats->partner->reward_amount) }}
                {{ $referralStats->partner->reward_type === 'cubi' ? 'Cubi Gold' : config('pw-config.currency.name') }}
                {{ __('main.profile_partner_per_ref') }}
                &middot; {{ __('main.profile_partner_min_level') }} {{ $referralStats->partner->min_char_level }}
                &middot; {{ __('main.profile_partner_max_day') === '/hari' ? 'Maks' : 'Max' }} {{ $referralStats->partner->max_per_day }}{{ __('main.profile_partner_max_day') }}
                @if($referralStats->partner->max_total)
                &middot; {{ __('main.profile_partner_max_total') }} {{ number_format($referralStats->partner->max_total) }}
                @endif
            </div>
            @endif

            <div class="pw-referral-top">
                <div class="pw-referral-top__link">
                    <label class="pw-profile-label">{{ __('main.profile_referral_link') }}</label>
                    <div x-data="{
                            copied: false,
                            toast: '',
                            doCopy() {
                                const text = document.getElementById('referralLink').value;
                                const fallback = (t) => { const ta = document.createElement('textarea'); ta.value=t; ta.style.position='fixed'; ta.style.opacity='0'; document.body.appendChild(ta); ta.focus(); ta.select(); document.execCommand('copy'); document.body.removeChild(ta); };
                                if (navigator.clipboard && window.isSecureContext) { navigator.clipboard.writeText(text).catch(() => fallback(text)); } else { fallback(text); }
                                this.copied = true; setTimeout(() => this.copied = false, 2000);
                                this.toast = '{{ __('main.profile_referral_copied') }}';
                                setTimeout(() => this.toast = '', 2500);
                            }
                         }"
                         style="position:relative;display:flex;flex-direction:row;align-items:center;gap:.5rem;">
                        {{-- Toast --}}
                        <div x-show="toast" x-transition.opacity
                             style="position:absolute;top:-2.4rem;right:0;background:#15803d;color:#fff;
                                    font-size:.72rem;font-weight:600;padding:.3rem .75rem;border-radius:6px;
                                    display:flex;align-items:center;gap:.35rem;z-index:10;pointer-events:none;white-space:nowrap;">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                            <span x-text="toast"></span>
                        </div>
                        <input type="text" class="pw-profile-input pw-profile-input--disabled" value="{{ route('register', ['ref' => $referralStats->code]) }}" readonly id="referralLink" style="flex:1;min-width:0;">
                        <button type="button"
                            :style="copied ? 'color:#22c55e;opacity:1' : 'color:var(--pw-gold);opacity:.85'"
                            style="display:inline-flex;align-items:center;gap:.3rem;background:none;border:none;padding:.4rem .5rem;cursor:pointer;font-size:.75rem;font-weight:600;flex-shrink:0;transition:color .15s;"
                            @mouseenter="!copied && ($el.style.opacity='1')" @mouseleave="!copied && ($el.style.opacity='.85')"
                            @click="doCopy()">
                            <svg x-show="!copied" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                            <svg x-show="copied" x-cloak width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                            <span x-text="copied ? '{{ __('main.profile_referral_copied') }}' : '{{ __('main.profile_referral_copy') }}'"></span>
                        </button>
                    </div>
                    @php
                        $referralRewardType   = $referralStats->is_partner
                            ? ($referralStats->partner->reward_type ?? 'gold')
                            : config('pw-config.referral.reward_type', 'gold');
                        $referralRewardAmount = $referralStats->is_partner
                            ? $referralStats->partner->reward_amount
                            : config('pw-config.referral.reward_gold', 0);
                        $referralRewardLabel  = $referralRewardType === 'cubi'
                            ? 'Cubi Gold'
                            : config('pw-config.currency.name', 'Gold Points');
                        $referralMinLevel     = $referralStats->is_partner
                            ? ($referralStats->partner->min_char_level ?? 1)
                            : config('pw-config.referral.min_char_level', 1);
                        $referralMinCult      = (int) config('pw-config.referral.min_cultivation', 0);
                        $cultivationNames     = [
                            1=>'Autoscopy',2=>'Transform',3=>'Naissance',4=>'Reborn',
                            5=>'Vigilance',6=>'Doom',7=>'Disengage',8=>'Nirvana',
                            20=>'Prime Immortal / Daimon Baresark',
                            21=>'Pure Immortal / Daimon Saint',
                            22=>'Ether Immortal / Daimon Elder',
                        ];
                        // Normalize legacy values 30/31/32 → 20/21/22
                        if (in_array($referralMinCult, [30,31,32])) {
                            $referralMinCult -= 10;
                        }
                        $referralMinCultName  = $cultivationNames[$referralMinCult] ?? null;
                        // Penerima reward (referred user)
                        $referredRType  = config('pw-config.referral.referred_reward_type', 'none');
                        $referredRAmount = (int) config('pw-config.referral.referred_reward_amount', 0);
                        $referredRLabel  = $referredRType === 'cubi' ? 'Cubi Gold' : config('pw-config.currency.name', 'Gold Points');
                        $hasReferredReward = $referredRType !== 'none' && $referredRAmount > 0;
                    @endphp
                    <p class="pw-profile-hint" style="margin-top:.5rem;color:var(--pw-text-light);">
                        {!! __('main.profile_referral_earn', ['amount' => number_format($referralRewardAmount), 'label' => $referralRewardLabel, 'level' => $referralMinLevel]) !!}
                        @if(! $referralStats->is_partner && $referralMinCultName){!! __('main.profile_referral_cult', ['cult' => $referralMinCultName]) !!}@endif.
                    </p>
                    @if(! $referralStats->is_partner && $hasReferredReward)
                    <p class="pw-profile-hint" style="margin-top:.3rem;color:var(--pw-text-muted);">
                        {!! __('main.profile_referral_bonus', ['amount' => number_format($referredRAmount), 'label' => $referredRLabel]) !!}
                    </p>
                    @endif
                </div>
                <div class="pw-referral-stats">
                    <div class="pw-referral-stat">
                        <span class="pw-referral-stat__value">{{ $referralStats->total }}</span>
                        <span class="pw-referral-stat__label">{{ __('main.profile_referral_total') }}</span>
                    </div>
                    <div class="pw-referral-stat">
                        <span class="pw-referral-stat__value pw-referral-stat__value--success">{{ $referralStats->rewarded }}</span>
                        <span class="pw-referral-stat__label">{{ __('main.profile_referral_rewarded') }}</span>
                    </div>
                    <div class="pw-referral-stat">
                        <span class="pw-referral-stat__value pw-referral-stat__value--pending">{{ $referralStats->pending }}</span>
                        <span class="pw-referral-stat__label">{{ __('main.profile_referral_pending') }}</span>
                    </div>
                </div>
                </div>
            </div>

            @if($referralRewardType === 'cubi')
            <div style="margin-top:.9rem;display:flex;gap:.7rem;align-items:flex-start;padding:.85rem 1rem;background:rgba(234,179,8,.07);border:1px solid rgba(234,179,8,.25);border-radius:.6rem;">
                <svg viewBox="0 0 20 20" fill="none" width="18" style="flex-shrink:0;margin-top:.05rem;color:#eab308;"><circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.5"/><path d="M10 6.5v4M10 12.5v.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
                <div style="font-size:.82rem;line-height:1.55;color:var(--pw-text-muted);">
                    <strong style="color:#eab308;display:block;margin-bottom:.2rem;">{{ __('main.profile_referral_cubi_note_title') }}</strong>
                    {!! __('main.profile_referral_cubi_note') !!}
                </div>
            </div>
            @endif

            @if($referralStats->list->isNotEmpty())
            <div x-data="{ open: false }" style="margin-top:.9rem;">
                <button type="button" @click="open = !open"
                    style="width:100%;display:flex;align-items:center;justify-content:space-between;padding:.7rem 1rem;background:var(--pw-card-bg,rgba(255,255,255,.04));border:1px solid var(--pw-border,rgba(255,255,255,.08));border-radius:.6rem;cursor:pointer;font-size:.85rem;color:var(--pw-text);">
                    <span style="display:flex;align-items:center;gap:.5rem;">
                        <svg viewBox="0 0 16 16" fill="none" width="14" aria-hidden="true"><circle cx="8" cy="5" r="3" stroke="currentColor" stroke-width="1.2"/><path d="M2 14c0-2.8 2.7-5 6-5s6 2.2 6 5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/></svg>
                        {{ __('main.profile_referral_list') }}
                        <span style="font-size:.75rem;padding:.1rem .45rem;border-radius:999px;background:rgba(239,68,68,.15);color:#f87171;font-weight:600;">{{ $referralStats->list->count() }}</span>
                    </span>
                    <svg viewBox="0 0 16 16" fill="none" width="14" aria-hidden="true"
                        :style="open ? 'transform:rotate(180deg);transition:.2s' : 'transition:.2s'">
                        <path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>

                <div x-show="open" x-cloak x-transition style="margin-top:.5rem;overflow:hidden;border:1px solid var(--pw-border,rgba(255,255,255,.08));border-radius:.6rem;">
                    <table style="width:100%;border-collapse:collapse;font-size:.82rem;">
                        <thead>
                            <tr style="background:rgba(255,255,255,.04);border-bottom:1px solid var(--pw-border,rgba(255,255,255,.08));">
                                <th style="padding:.55rem .9rem;text-align:left;font-weight:600;color:var(--pw-text-muted);white-space:nowrap;">#</th>
                                <th style="padding:.55rem .9rem;text-align:left;font-weight:600;color:var(--pw-text-muted);">{{ __('main.profile_referral_col_name') }}</th>
                                <th style="padding:.55rem .9rem;text-align:left;font-weight:600;color:var(--pw-text-muted);white-space:nowrap;">{{ __('main.profile_referral_col_joined') }}</th>
                                <th style="padding:.55rem .9rem;text-align:center;font-weight:600;color:var(--pw-text-muted);white-space:nowrap;">{{ __('main.profile_referral_col_level') }}</th>
                                @if($referralStats->req_cult > 0)
                                <th style="padding:.55rem .9rem;text-align:center;font-weight:600;color:var(--pw-text-muted);white-space:nowrap;">{{ __('main.profile_referral_col_cult') }}</th>
                                @endif
                                <th style="padding:.55rem .9rem;text-align:center;font-weight:600;color:var(--pw-text-muted);">{{ __('main.profile_referral_col_status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($referralStats->list as $i => $ref)
                            <tr style="{{ !$loop->last ? 'border-bottom:1px solid var(--pw-border,rgba(255,255,255,.06));' : '' }}">
                                <td style="padding:.55rem .9rem;color:var(--pw-text-muted);">{{ $i + 1 }}</td>
                                <td style="padding:.55rem .9rem;color:var(--pw-text);">{{ $ref->name }}</td>
                                <td style="padding:.55rem .9rem;color:var(--pw-text-muted);white-space:nowrap;">
                                    {{ $ref->joined ? \Carbon\Carbon::parse($ref->joined)->format('d M Y') : '—' }}
                                </td>
                                {{-- Level --}}
                                <td style="padding:.55rem .9rem;text-align:center;white-space:nowrap;">
                                    @if($ref->max_level !== null)
                                        @if($ref->level_ok)
                                        <span style="display:inline-flex;align-items:center;gap:.3rem;font-size:.8rem;color:#4ade80;font-weight:600;">
                                            <svg viewBox="0 0 16 16" fill="none" width="13"><path d="M3 8l4 4 6-6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            {{ $ref->max_level }}
                                        </span>
                                        @else
                                        <span style="display:inline-flex;align-items:center;gap:.3rem;font-size:.8rem;color:#fbbf24;">
                                            <svg viewBox="0 0 16 16" fill="none" width="13"><circle cx="8" cy="8" r="6" stroke="currentColor" stroke-width="1.4"/><path d="M8 5v3.5M8 10.5v.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                                            {{ $ref->max_level }} / {{ $referralStats->req_level }}
                                        </span>
                                        @endif
                                    @else
                                        <span style="color:var(--pw-text-muted);font-size:.78rem;">{{ __('main.profile_referral_not_yet') }}</span>
                                    @endif
                                </td>
                                {{-- Cultivation (only shown if requirement is set) --}}
                                @if($referralStats->req_cult > 0)
                                <td style="padding:.55rem .9rem;text-align:center;white-space:nowrap;">
                                    @if($ref->max_cult !== null && $ref->max_cult_name)
                                        @if($ref->cult_ok)
                                        <span style="display:inline-flex;align-items:center;gap:.3rem;font-size:.78rem;color:#4ade80;font-weight:600;">
                                            <svg viewBox="0 0 16 16" fill="none" width="12"><path d="M3 8l4 4 6-6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            {{ $ref->max_cult_name }}
                                        </span>
                                        @else
                                        <span style="font-size:.78rem;color:#fbbf24;">{{ $ref->max_cult_name }}</span>
                                        @endif
                                    @else
                                        <span style="color:var(--pw-text-muted);font-size:.78rem;">—</span>
                                    @endif
                                </td>
                                @endif
                                {{-- Status --}}
                                <td style="padding:.55rem .9rem;text-align:center;">
                                    @if($ref->rewarded)
                                    <span class="pw-badge pw-badge--success">{{ __('main.profile_referral_status_sent') }}</span>
                                    @elseif($ref->level_ok && $ref->cult_ok)
                                    <span class="pw-badge" style="background:rgba(59,130,246,.15);color:#60a5fa;">{{ __('main.profile_referral_status_met') }}</span>
                                    @else
                                    <span class="pw-badge pw-badge--pending">{{ __('main.profile_referral_status_pending') }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
        @endif

    </div>
</section>

{{-- Modal Ganti Password --}}
<div id="pwModalPassword" style="display:none;position:fixed;inset:0;z-index:9999;align-items:center;justify-content:center;background:rgba(0,0,0,.65);backdrop-filter:blur(4px);" onclick="if(event.target===this)pwClosePasswordModal()">
    <div class="pw-profile-card" style="width:90%;max-width:400px;position:relative;margin:0;box-shadow:0 20px 60px rgba(0,0,0,.5);background:var(--pw-bg-card);">
        {{-- Close button --}}
        <button type="button" onclick="pwClosePasswordModal()" style="position:absolute;top:.75rem;right:.75rem;background:none;border:none;color:var(--pw-text-muted);cursor:pointer;padding:4px;" aria-label="Tutup">
            <svg viewBox="0 0 16 16" fill="none" width="16"><path d="M4 4l8 8M12 4l-8 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        </button>

        {{-- Header --}}
        <div class="pw-profile-card__header" style="justify-content:center;">
            <svg viewBox="0 0 16 16" fill="none" width="14" aria-hidden="true"><rect x="3" y="7" width="10" height="7" rx="1.5" stroke="#c8972a" stroke-width="1.3"/><path d="M5 7V5a3 3 0 016 0v2" stroke="#c8972a" stroke-width="1.3" stroke-linecap="round"/></svg>
            {{ __('main.profile_change_password') }}
        </div>

        {{-- Alert area --}}
        <div id="pwPasswordAlert" style="display:none;padding:.5rem .75rem;border-radius:6px;font-size:.8rem;font-weight:500;margin-bottom:.8rem;"></div>

        {{-- Form --}}
        <form id="pwPasswordForm" method="POST" action="{{ route('profile.change-password') }}">
            @csrf
            <label class="pw-profile-label">{{ __('main.profile_pin_game') }}</label>
            <input type="password" name="pin" id="pwPinInput" class="pw-profile-input" placeholder="{{ __('main.profile_pin_placeholder') }}" required>
            <p id="pwPinError" class="pw-profile-error" style="display:none;"></p>

            <label class="pw-profile-label">{{ __('main.profile_new_password') }}</label>
            <input type="password" name="new_password" id="pwNewPassInput" class="pw-profile-input" placeholder="{{ __('main.profile_new_pass_placeholder') }}" required>
            <p id="pwNewPassError" class="pw-profile-error" style="display:none;"></p>

            <label class="pw-profile-label">{{ __('main.profile_confirm_password') }}</label>
            <input type="password" name="new_password_confirmation" id="pwConfirmPassInput" class="pw-profile-input" placeholder="{{ __('main.profile_confirm_placeholder') }}" required>
            <p id="pwConfirmError" class="pw-profile-error" style="display:none;"></p>

            <button type="submit" id="pwPasswordSubmit" class="pw-btn pw-btn--gold pw-btn--sm" style="width:100%;margin-top:.3rem;justify-content:center;">
                <svg viewBox="0 0 16 16" fill="none" width="13"><rect x="3" y="7" width="10" height="7" rx="1.5" stroke="currentColor" stroke-width="1.3"/><path d="M5 7V5a3 3 0 016 0v2" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                {{ __('main.profile_change_password') }}
            </button>
        </form>
    </div>
</div>

{{-- Modal Sukses --}}
<div id="pwModalSuccess" style="display:none;position:fixed;inset:0;z-index:10000;align-items:center;justify-content:center;background:rgba(0,0,0,.65);backdrop-filter:blur(4px);" onclick="if(event.target===this)this.style.display='none'">
    <div class="pw-profile-card" style="width:90%;max-width:360px;text-align:center;margin:0;box-shadow:0 20px 60px rgba(0,0,0,.5);background:var(--pw-bg-card);">
        <div style="width:56px;height:56px;border-radius:50%;background:rgba(56,161,105,.15);display:flex;align-items:center;justify-content:center;margin:0 auto .8rem;">
            <svg viewBox="0 0 24 24" fill="none" width="28"><path d="M5 13l4 4L19 7" stroke="#48bb78" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </div>
        <div class="pw-profile-card__header" style="justify-content:center;border:none;margin-bottom:.4rem;padding-bottom:0;">{{ __('main.profile_success') }}</div>
        <p class="pw-profile-hint" style="margin:0 0 1.2rem;text-align:center;">{{ __('main.profile_password_changed') }}</p>
        <button type="button" onclick="document.getElementById('pwModalSuccess').style.display='none'" class="pw-btn pw-btn--gold pw-btn--sm">OK</button>
    </div>
</div>

<script>
function pwClosePasswordModal() {
    document.getElementById('pwModalPassword').style.display = 'none';
    document.getElementById('pwPasswordForm').reset();
    document.getElementById('pwPasswordAlert').style.display = 'none';
    ['pwPinError','pwNewPassError','pwConfirmError'].forEach(id => document.getElementById(id).style.display = 'none');
}

document.getElementById('pwPasswordForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // Clear previous errors
    ['pwPinError','pwNewPassError','pwConfirmError'].forEach(id => document.getElementById(id).style.display = 'none');
    document.getElementById('pwPasswordAlert').style.display = 'none';

    var pin = document.getElementById('pwPinInput').value;
    var newPass = document.getElementById('pwNewPassInput').value;
    var confirmPass = document.getElementById('pwConfirmPassInput').value;
    var hasError = false;

    if (!pin) {
        var el = document.getElementById('pwPinError');
        el.textContent = @json(__('main.profile_pin_required'));
        el.style.display = 'block';
        hasError = true;
    }
    if (newPass.length < 6) {
        var el = document.getElementById('pwNewPassError');
        el.textContent = @json(__('main.profile_pass_min'));
        el.style.display = 'block';
        hasError = true;
    }
    if (newPass !== confirmPass) {
        var el = document.getElementById('pwConfirmError');
        el.textContent = @json(__('main.profile_pass_mismatch'));
        el.style.display = 'block';
        hasError = true;
    }
    if (hasError) return;

    var btn = document.getElementById('pwPasswordSubmit');
    btn.disabled = true;
    btn.innerHTML = '<svg class="pw-spinner" viewBox="0 0 16 16" width="13" style="animation:spin .8s linear infinite"><circle cx="8" cy="8" r="6" stroke="currentColor" stroke-width="1.5" fill="none" stroke-dasharray="28" stroke-dashoffset="8"/></svg> ' + @json(__('main.profile_processing'));

    fetch(this.action, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ pin: pin, new_password: newPass, new_password_confirmation: confirmPass })
    })
    .then(function(res) { return res.json().then(function(data) { return { ok: res.ok, status: res.status, data: data }; }); })
    .then(function(result) {
        btn.disabled = false;
        btn.innerHTML = '<svg viewBox="0 0 16 16" fill="none" width="13"><rect x="3" y="7" width="10" height="7" rx="1.5" stroke="currentColor" stroke-width="1.3"/><path d="M5 7V5a3 3 0 016 0v2" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg> ' + @json(__('main.profile_change_password'));

        if (result.ok && result.data.success) {
            pwClosePasswordModal();
            document.getElementById('pwModalSuccess').style.display = 'flex';
        } else if (result.data.errors) {
            var errors = result.data.errors;
            if (errors.pin) {
                var el = document.getElementById('pwPinError');
                el.textContent = errors.pin[0];
                el.style.display = 'block';
            }
            if (errors.new_password) {
                var el = document.getElementById('pwNewPassError');
                el.textContent = errors.new_password[0];
                el.style.display = 'block';
            }
        } else if (result.data.message) {
            var alert = document.getElementById('pwPasswordAlert');
            alert.style.display = 'block';
            alert.style.background = 'rgba(245,101,101,.12)';
            alert.style.border = '1px solid rgba(245,101,101,.35)';
            alert.style.color = '#ff6b6b';
            alert.textContent = result.data.message;
        }
    })
    .catch(function() {
        btn.disabled = false;
        btn.innerHTML = '<svg viewBox="0 0 16 16" fill="none" width="13"><rect x="3" y="7" width="10" height="7" rx="1.5" stroke="currentColor" stroke-width="1.3"/><path d="M5 7V5a3 3 0 016 0v2" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg> ' + @json(__('main.profile_change_password'));
        var alert = document.getElementById('pwPasswordAlert');
        alert.style.display = 'block';
        alert.style.background = 'rgba(245,101,101,.12)';
        alert.style.border = '1px solid rgba(245,101,101,.35)';
        alert.style.color = '#ff6b6b';
        alert.textContent = @json(__('main.profile_error_generic'));
    });
});

@if(session('password_success'))
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('pwModalSuccess').style.display = 'flex';
});
@endif

@if($errors->has('pin') || $errors->has('new_password'))
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('pwModalPassword').style.display = 'flex';
    @if($errors->has('pin'))
    var el = document.getElementById('pwPinError');
    el.textContent = @json($errors->first('pin'));
    el.style.display = 'block';
    @endif
    @if($errors->has('new_password'))
    var el = document.getElementById('pwNewPassError');
    el.textContent = @json($errors->first('new_password'));
    el.style.display = 'block';
    @endif
});
@endif
</script>

@endsection

@push('styles')
<style>
.pw-profile-badge--admin-front {
    background: rgba(147, 51, 234, .18);
    color: #c084fc;
    border: 1px solid rgba(147, 51, 234, .42);
}

.pw-profile-badge--gm-front {
    background: rgba(239, 68, 68, .18);
    color: #f87171;
    border: 1px solid rgba(239, 68, 68, .42);
}
</style>
@endpush
