@extends('layouts.app')

{{-- Homepage doesn't override title - uses SEO title or site default --}}
@section('meta_description', config('pw-config.server.description', 'Perfect World Private Server'))

@php
    $__siteName = \App\Models\Setting::get('seo_title') ?: config('pw-config.server.name') ?: 'Perfect World';
@endphp

@section('content')

{{-- ============================================================
     HERO SECTION
============================================================ --}}
<section class="pw-hero" id="hero">
    @php $heroBg = \App\Models\Setting::get('site_hero_bg'); @endphp

    {{-- Background --}}
    <div class="pw-hero__bg" aria-hidden="true">
        @if($heroBg)
            <img src="{{ Storage::url($heroBg) }}" class="pw-hero__bg-img" alt="" fetchpriority="high" loading="eager">
        @else
        <svg class="pw-hero__bg-svg" viewBox="0 0 1440 800" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <radialGradient id="hg1" cx="30%" cy="50%" r="60%">
                    <stop offset="0%" stop-color="#1e1e1e" stop-opacity="1"/>
                    <stop offset="100%" stop-color="#111111" stop-opacity="1"/>
                </radialGradient>
            </defs>
            <rect width="1440" height="800" fill="url(#hg1)"/>
            <path d="M0 600 L180 320 L360 480 L540 240 L720 400 L900 180 L1080 360 L1260 280 L1440 420 L1440 800 L0 800Z" fill="#161616" opacity=".8"/>
            <path d="M0 650 L200 430 L400 550 L600 350 L800 490 L1000 290 L1200 440 L1440 360 L1440 800 L0 800Z" fill="#131313" opacity=".7"/>
            <circle cx="720" cy="400" r="120" fill="#c8972a" opacity=".03"/>
        </svg>
        @endif
    </div>

    <div class="pw-hero__overlay" aria-hidden="true"></div>
    <canvas id="hero-embers" aria-hidden="true"></canvas>
    <div class="pw-hero__scanlines" aria-hidden="true"></div>
    <div class="pw-hero__mist" aria-hidden="true"></div>

    <div class="pw-hero__content">

        {{-- Logo --}}
        @php $__heroLogo = \App\Models\Setting::get('site_logo'); @endphp
        @if($__heroLogo)
            <div class="pw-hero__logo-wrap">
                <img src="{{ Storage::url($__heroLogo) }}" alt="{{ $__siteName }}"
                     class="pw-hero__logo-img">
            </div>
        @else
            <div class="pw-hero__logo-wrap">
                <h1 class="pw-hero__title">{{ $__siteName }}</h1>
            </div>
        @endif

        {{-- Stats --}}
        <div class="pw-hero__stats-row">
            <div class="pw-hero__stat-item">
                <span class="pw-hero__stat-label">{{ __('main.hero_stat_accounts') }}:</span>
                <strong id="hero-accounts" class="pw-hero__stat-val" data-target="{{ \App\Models\User::count() }}">0</strong>
            </div>
            <div class="pw-hero__stat-sep" aria-hidden="true"></div>
            <div class="pw-hero__stat-item pw-hero__stat-item--server">
                <div class="pw-hero__srv-status" id="hero-server-chip">
                    <span class="pw-hero__srv-pulse"></span>
                    <span class="pw-hero__srv-label">Server:</span>
                    <span class="pw-hero__srv-text" id="server-status-text">···</span>
                </div>
            </div>
        </div>

        {{-- CTA Button --}}
        @guest
            @if(config('pw-config.features.register', true))
            <a href="{{ route('register') }}" class="pw-btn pw-btn--gold pw-btn--glow pw-hero__btn">
                {{ __('main.hero_play') }}
            </a>
            @else
            <a href="{{ route('login') }}" class="pw-btn pw-btn--gold pw-btn--glow pw-hero__btn">
                {{ __('main.hero_login') }}
            </a>
            @endif
        @else
            <a href="{{ route('dashboard') }}" class="pw-btn pw-btn--gold pw-btn--glow pw-hero__btn">
                {{ __('main.hero_dashboard') }}
            </a>
        @endguest

    </div>

    {{-- Scroll indicator --}}
    <div class="pw-hero__scroll" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" width="24"><path d="M12 5v14M7 14l5 5 5-5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </div>
</section>

{{-- ============================================================
     SERVER STATUS SECTION (2-column: status left, players right)
============================================================ --}}
<section class="pw-srv" id="server-status" x-data="pwServerStatus()" x-init="init()">
    <div class="pw-srv__grid">

        {{-- LEFT COLUMN: Server Status Card --}}
        <div class="pw-srv__card">
            <div class="pw-srv__card-head">
                <svg viewBox="0 0 20 20" fill="none" width="14"><rect x="2" y="4" width="16" height="12" rx="2" stroke="currentColor" stroke-width="1.2"/><path d="M6 8h8M6 12h5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/></svg>
                <span>{{ __('main.server_status') }}</span>
            </div>
            <div class="pw-srv__card-body">
                {{-- Server name + online indicator --}}
                <div class="pw-srv__main">
                    <div class="pw-srv__indicator" :class="server ? 'pw-srv__indicator--on' : 'pw-srv__indicator--off'">
                        <span class="pw-srv__beacon"></span>
                        <span class="pw-srv__beacon-ring"></span>
                    </div>
                    <div class="pw-srv__info">
                        <div class="pw-srv__label">{{ $__siteName }}</div>
                        <div class="pw-srv__state" :class="server ? 'pw-srv__state--on' : 'pw-srv__state--off'" x-text="server ? '{{ __('main.server_online') }}' : '{{ __('main.server_offline') }}'">···</div>
                    </div>
                </div>

                {{-- Stats grid --}}
                <div class="pw-srv__stats">
                    <div class="pw-srv__stat">
                        <div class="pw-srv__stat-icon">
                            <svg viewBox="0 0 20 20" fill="none" width="15"><circle cx="10" cy="7" r="3.5" stroke="currentColor" stroke-width="1.3"/><path d="M3 17c0-3 3.1-5.5 7-5.5s7 2.5 7 5.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                        </div>
                        <div class="pw-srv__stat-body">
                            <span class="pw-srv__stat-val" x-text="online.toLocaleString()">0</span>
                            <span class="pw-srv__stat-label">{{ __('main.srv_online') }}</span>
                        </div>
                    </div>
                    <div class="pw-srv__stat">
                        <div class="pw-srv__stat-icon">
                            <svg viewBox="0 0 20 20" fill="none" width="15"><circle cx="7" cy="7" r="3" stroke="currentColor" stroke-width="1.2"/><circle cx="13" cy="7" r="3" stroke="currentColor" stroke-width="1.2"/><path d="M1 17c0-2.5 2.7-4.5 6-4.5M13 12.5c3.3 0 6 2 6 4.5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/></svg>
                        </div>
                        <div class="pw-srv__stat-body">
                            <span class="pw-srv__stat-val" x-text="accounts.toLocaleString()">0</span>
                            <span class="pw-srv__stat-label">{{ __('main.hero_stat_accounts') }}</span>
                        </div>
                    </div>
                    <div class="pw-srv__stat">
                        <div class="pw-srv__stat-icon">
                            <svg viewBox="0 0 20 20" fill="none" width="15"><circle cx="10" cy="10" r="7" stroke="currentColor" stroke-width="1.3"/><path d="M10 6v4.5l3 2" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <div class="pw-srv__stat-body">
                            <span class="pw-srv__stat-val" x-text="uptime">--</span>
                            <span class="pw-srv__stat-label">{{ __('main.srv_uptime') }}</span>
                        </div>
                    </div>
                    <div class="pw-srv__stat">
                        <div class="pw-srv__stat-icon">
                            <svg viewBox="0 0 20 20" fill="none" width="15"><rect x="2" y="4" width="16" height="12" rx="2" stroke="currentColor" stroke-width="1.2"/><path d="M6 8h8M6 12h5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/></svg>
                        </div>
                        <div class="pw-srv__stat-body">
                            <span class="pw-srv__stat-val" x-text="maps.length">0</span>
                            <span class="pw-srv__stat-label">{{ __('main.srv_maps') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: Online Players Card --}}
        <div class="pw-srv__card pw-srv__card--players">
            <div class="pw-srv__card-head">
                <svg viewBox="0 0 20 20" fill="none" width="14"><circle cx="10" cy="7" r="3.5" stroke="currentColor" stroke-width="1.3"/><path d="M3 17c0-3 3.1-5.5 7-5.5s7 2.5 7 5.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                <span>{{ __('main.srv_online_players') }}</span>
                <span class="pw-srv__badge" x-text="players.length">0</span>
            </div>
            {{-- Table header --}}
            <div class="pw-srv__player-header">
                <span class="pw-srv__ph-no">#</span>
                <span class="pw-srv__ph-cls"></span>
                <span class="pw-srv__ph-name">{{ __('main.srv_character') }}</span>
                <span class="pw-srv__ph-gender"></span>
                <span class="pw-srv__ph-lvl">{{ __('main.srv_level') }}</span>
                <span class="pw-srv__ph-class">{{ __('main.srv_class') }}</span>
                <span class="pw-srv__ph-faction">{{ __('main.srv_faction') }}</span>
            </div>
            <div class="pw-srv__players-list">
                <template x-if="players.length === 0">
                    <div class="pw-srv__players-empty">{{ __('main.srv_no_players') }}</div>
                </template>
                <template x-for="(p, idx) in players" :key="p.id">
                    <div class="pw-srv__player-row">
                        <span class="pw-srv__pr-no" x-text="idx + 1"></span>
                        <img class="pw-srv__player-icon" :src="classIcon(p.class)" :alt="classLabel(p.class)" width="20" height="20">
                        <span class="pw-srv__player-name" x-text="p.name"></span>
                        <span class="pw-srv__pr-gender" :class="p.gender === 0 ? 'pw-srv__pr-gender--m' : 'pw-srv__pr-gender--f'" x-text="p.gender === 0 ? '♂' : '♀'"></span>
                        <span class="pw-srv__pr-lvl" x-text="p.level"></span>
                        <span class="pw-srv__pr-class" x-text="classLabel(p.class)"></span>
                        <span class="pw-srv__pr-faction" x-text="p.faction || '-'"></span>
                    </div>
                </template>
            </div>
        </div>

    </div>
</section>

{{-- ============================================================
     NEWS SECTION
============================================================ --}}
@if(config('pw-config.features.news', true))
<section class="pw-section" id="news">
    <div class="pw-section__inner">
        <div class="pw-section__head">
            <div class="pw-section__ornament" aria-hidden="true">
                <svg viewBox="0 0 160 20" fill="none" width="160"><line x1="0" y1="10" x2="60" y2="10" stroke="#c8972a" stroke-width="1"/><path d="M70 3 L80 10 L70 17 L60 10 Z" fill="#c8972a" opacity=".6"/><path d="M80 3 L90 10 L80 17 L70 10 Z" fill="#c8972a"/><path d="M90 3 L100 10 L90 17 L80 10 Z" fill="#c8972a" opacity=".6"/><line x1="100" y1="10" x2="160" y2="10" stroke="#c8972a" stroke-width="1"/></svg>
            </div>
            <h2>{{ __('main.news_title') }}</h2>
            <p>{{ __('main.news_subtitle') }}</p>
        </div>

        {{-- 3-col wrapper: 2 col news | 1 col gm sidebar --}}
        <div class="pw-news-layout">

            {{-- LEFT: News Grid --}}
            <div class="pw-news-layout__main">
                @if($news->count())
                @php $featured = $news->first(); $rest = $news->skip(1); @endphp

                {{-- Featured / latest article (full width) --}}
                <a href="{{ route('news.show', $featured->slug) }}" class="pw-news-card pw-news-card--featured">
                    <div class="pw-news-card__thumb">
                        @if($featured->thumbnail)
                            <img src="{{ Storage::url($featured->thumbnail) }}" alt="{{ $featured->title }}" loading="lazy">
                        @else
                            <svg viewBox="0 0 800 340" xmlns="http://www.w3.org/2000/svg">
                                <defs><linearGradient id="ngf" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#1a0d2e"/><stop offset="100%" stop-color="#0a0a14"/></linearGradient></defs>
                                <rect width="800" height="340" fill="url(#ngf)"/>
                                <path d="M400 100 L340 220 L400 300 L460 220 Z" fill="#c8972a" opacity=".2"/>
                                <circle cx="400" cy="200" r="28" fill="#c8972a" opacity=".25"/>
                            </svg>
                        @endif
                        <div class="pw-news-card__overlay"></div>
                        <span class="pw-news-card__badge-new">{{ __('main.news_badge_latest') }}</span>
                        @if($featured->category)
                        <span class="pw-news-card__cat">{{ $featured->category }}</span>
                        @endif
                    </div>
                    <div class="pw-news-card__body">
                        <h2 class="pw-news-card__title">{{ Str::limit($featured->title, 90) }}</h2>
                        <p class="pw-news-card__excerpt">{{ Str::limit($featured->excerpt, 180) }}</p>
                        <div class="pw-news-card__meta">
                            <span class="pw-news-card__meta-author">
                                <svg viewBox="0 0 16 16" fill="none" width="12" aria-hidden="true"><circle cx="8" cy="5" r="3" stroke="currentColor" stroke-width="1.3"/><path d="M2 14a6 6 0 0112 0" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                                {{ $featured->author?->truename ?: ($featured->author?->name ?? 'Admin') }}
                            </span>
                            <span>
                                <svg viewBox="0 0 16 16" fill="none" width="12" aria-hidden="true"><rect x="2" y="3" width="12" height="11" rx="2" stroke="currentColor" stroke-width="1.3"/><path d="M5 1v3M11 1v3M2 7h12" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                                {{ $featured->created_at->translatedFormat('d F Y') }}
                            </span>
                            <span class="pw-news-card__read">{{ __('main.news_readmore') }} →</span>
                        </div>
                    </div>
                </a>

                {{-- Remaining articles grid 2 col --}}
                @if($rest->count())
                <div class="pw-news-grid" style="margin-top:1.25rem">
                    @foreach($rest as $article)
                    <a href="{{ route('news.show', $article->slug) }}" class="pw-news-card">
                        <div class="pw-news-card__thumb">
                            @if($article->thumbnail)
                                <img src="{{ Storage::url($article->thumbnail) }}" alt="{{ $article->title }}" loading="lazy">
                            @else
                                <svg viewBox="0 0 400 220" xmlns="http://www.w3.org/2000/svg">
                                    <defs><linearGradient id="ng{{ $loop->index }}" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="{{ ['#0d1f3c','#1a2010','#2a0d0d','#1a0d2e'][$loop->index % 4] }}"/><stop offset="100%" stop-color="#0a0a14"/></linearGradient></defs>
                                    <rect width="400" height="220" fill="url(#ng{{ $loop->index }})"/>
                                    <path d="M200 60 L165 120 L200 180 L235 120 Z" fill="#c8972a" opacity=".25"/>
                                    <circle cx="200" cy="120" r="18" fill="#c8972a" opacity=".3"/>
                                </svg>
                            @endif
                            <div class="pw-news-card__overlay"></div>
                            @if($article->category)
                            <span class="pw-news-card__cat">{{ $article->category }}</span>
                            @endif
                        </div>
                        <div class="pw-news-card__body">
                            <h3 class="pw-news-card__title">{{ Str::limit($article->title, 65) }}</h3>
                            <p class="pw-news-card__excerpt">{{ Str::limit($article->excerpt, 90) }}</p>
                            <div class="pw-news-card__meta">
                                <span class="pw-news-card__meta-author">
                                    <svg viewBox="0 0 16 16" fill="none" width="11" aria-hidden="true"><circle cx="8" cy="5" r="3" stroke="currentColor" stroke-width="1.3"/><path d="M2 14a6 6 0 0112 0" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                                    {{ $article->author?->truename ?: ($article->author?->name ?? 'Admin') }}
                                </span>
                                <span>
                                    <svg viewBox="0 0 16 16" fill="none" width="11" aria-hidden="true"><rect x="2" y="3" width="12" height="11" rx="2" stroke="currentColor" stroke-width="1.3"/><path d="M5 1v3M11 1v3M2 7h12" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                                    {{ $article->created_at->translatedFormat('d F Y') }}
                                </span>
                                <span class="pw-news-card__read">{{ __('main.news_readmore') }} →</span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
                @endif

                <div class="pw-section__footer">
                    <a href="{{ route('news.index') }}" class="pw-btn pw-btn--gold">{{ __('main.news_all') }}</a>
                </div>
                @else
                <p class="pw-section__empty">{{ __('main.news_empty') }}</p>
                @endif
            </div>{{-- /.pw-news-layout__main --}}

            {{-- RIGHT: Staff Sidebar (Discord + Admin Web + GM Game) --}}
            <aside class="pw-gm-sidebar">

                {{-- DISCORD CARD --}}
                <div class="pw-gm-sidebar__card pw-discord-card" x-data="pwDiscord()" x-init="fetchData()">
                    <div class="pw-gm-sidebar__header">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M20.317 4.37a19.8 19.8 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.6 12.6 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.74 19.74 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.08.08 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028 14.1 14.1 0 0 0 1.226-1.994.076.076 0 0 0-.041-.106 13.1 13.1 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10 10 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.3 12.3 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.84 19.84 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.06.06 0 0 0-.031-.03zM8.02 15.33c-1.183 0-2.157-1.086-2.157-2.419 0-1.333.956-2.419 2.157-2.419 1.21 0 2.176 1.095 2.157 2.42 0 1.332-.956 2.418-2.157 2.418zm7.975 0c-1.183 0-2.157-1.086-2.157-2.419 0-1.333.956-2.419 2.157-2.419 1.21 0 2.176 1.095 2.157 2.42 0 1.332-.947 2.418-2.157 2.418z"/></svg>
                        <span>Discord Server</span>
                        <span class="pw-gm-sidebar__count" x-text="onlineCount">0</span>
                    </div>
                    <div class="pw-discord-card__body">
                        <div class="pw-discord-card__server">
                            <span class="pw-discord-card__name" x-text="serverName">···</span>
                            <span class="pw-discord-card__status">
                                <span class="pw-discord-card__dot"></span>
                                <span x-text="onlineCount + ' {{ __('main.discord_online') }}'">0 online</span>
                            </span>
                        </div>
                        {{-- Member list (max 5) --}}
                        <div class="pw-discord-card__members">
                            <template x-for="m in members.slice(0, 5)" :key="m.id">
                                <div class="pw-discord-card__member">
                                    <img :src="m.avatar_url" :alt="m.username" width="24" height="24" loading="lazy">
                                    <span class="pw-discord-card__member-name" x-text="m.username"></span>
                                    <span class="pw-discord-card__member-dot" :class="'pw-discord-card__member-dot--' + m.status"></span>
                                </div>
                            </template>
                            <template x-if="members.length === 0">
                                <p class="pw-discord-card__empty">{{ __('main.discord_no_members') }}</p>
                            </template>
                        </div>
                        {{-- Join button --}}
                        <a :href="inviteUrl || 'https://discord.gg/7xCWyB2NFy'" target="_blank" rel="noopener"
                           class="pw-btn pw-btn--gold pw-discord-card__join">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M20.317 4.37a19.8 19.8 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.6 12.6 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.74 19.74 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.08.08 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028 14.1 14.1 0 0 0 1.226-1.994.076.076 0 0 0-.041-.106 13.1 13.1 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10 10 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.3 12.3 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.84 19.84 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.06.06 0 0 0-.031-.03zM8.02 15.33c-1.183 0-2.157-1.086-2.157-2.419 0-1.333.956-2.419 2.157-2.419 1.21 0 2.176 1.095 2.157 2.42 0 1.332-.956 2.418-2.157 2.418zm7.975 0c-1.183 0-2.157-1.086-2.157-2.419 0-1.333.956-2.419 2.157-2.419 1.21 0 2.176 1.095 2.157 2.42 0 1.332-.947 2.418-2.157 2.418z"/></svg>
                            {{ __('main.discord_join') }}
                        </a>
                    </div>
                </div>

                {{-- CARD 1: Admin Web --}}
                <div class="pw-gm-sidebar__card pw-gm-sidebar__card--admin">
                    <div class="pw-gm-sidebar__header">
                        <img src="/images/admin.png" alt="Admin" width="20" height="20" style="border-radius:4px;object-fit:cover;">
                        <span>Admin Web</span>
                        <span class="pw-gm-sidebar__count">{{ $webAdmins->count() }}</span>
                    </div>
                    <ul class="pw-gm-sidebar__list">
                        @forelse($webAdmins as $gm)
                        @php $online = $gm->isOnline(); @endphp
                        <li class="pw-gm-sidebar__item">
                            <div class="pw-gm-sidebar__avatar pw-gm-sidebar__avatar--admin">
                                @if($gm->profile_photo_path)
                                    <img src="{{ Storage::url($gm->profile_photo_path) }}" alt="{{ $gm->truename ?: 'Admin' }}">
                                @else
                                    <span>{{ $gm->truename ? strtoupper(substr($gm->truename, 0, 1)) : '' }}</span>
                                @endif
                                <span class="pw-gm-sidebar__dot {{ $online ? 'pw-gm-sidebar__dot--online' : 'pw-gm-sidebar__dot--offline' }}"></span>
                            </div>
                            <div class="pw-gm-sidebar__info">
                                <span class="pw-gm-sidebar__name">{{ $gm->truename ?: '' }}</span>
                                <span class="pw-gm-sidebar__role pw-gm-sidebar__role--admin">Administrator</span>
                            </div>
                            <span class="pw-gm-sidebar__status {{ $online ? 'pw-gm-sidebar__status--online' : 'pw-gm-sidebar__status--offline' }}">
                                {{ $online ? 'Online' : 'Offline' }}
                            </span>
                        </li>
                        @empty
                        <li class="pw-gm-sidebar__empty"><span>{{ __('main.sidebar_no_admin') }}</span></li>
                        @endforelse
                    </ul>
                </div>

                {{-- CARD 2: GM Game --}}
                <div class="pw-gm-sidebar__card pw-gm-sidebar__card--gm">
                    <div class="pw-gm-sidebar__header">
                        <img src="/images/gm.png" alt="GM" width="20" height="20" style="border-radius:4px;object-fit:cover;">
                        <span>Game Master</span>
                        <span class="pw-gm-sidebar__count">{{ $gameGms->count() }}</span>
                    </div>
                    <ul class="pw-gm-sidebar__list">
                        @forelse($gameGms as $gm)
                        @php $online = $gm->isOnline(); @endphp
                        <li class="pw-gm-sidebar__item">
                            <div class="pw-gm-sidebar__avatar pw-gm-sidebar__avatar--gm">
                                @if($gm->profile_photo_path)
                                    <img src="{{ Storage::url($gm->profile_photo_path) }}" alt="{{ $gm->truename ?: 'GM' }}">
                                @else
                                    <span>{{ $gm->truename ? strtoupper(substr($gm->truename, 0, 1)) : '' }}</span>
                                @endif
                                <span class="pw-gm-sidebar__dot {{ $online ? 'pw-gm-sidebar__dot--online' : 'pw-gm-sidebar__dot--offline' }}"></span>
                            </div>
                            <div class="pw-gm-sidebar__info">
                                <span class="pw-gm-sidebar__name">{{ $gm->truename ?: '' }}</span>
                                <span class="pw-gm-sidebar__role pw-gm-sidebar__role--gm">Game Master</span>
                            </div>
                            <span class="pw-gm-sidebar__status {{ $online ? 'pw-gm-sidebar__status--online' : 'pw-gm-sidebar__status--offline' }}">
                                {{ $online ? 'Online' : 'Offline' }}
                            </span>
                        </li>
                        @empty
                        <li class="pw-gm-sidebar__empty"><span>{{ __('main.sidebar_no_gm') }}</span></li>
                        @endforelse
                    </ul>
                </div>

                <div class="pw-gm-sidebar__footer">
                    <svg viewBox="0 0 16 16" fill="none" width="12" aria-hidden="true">
                        <circle cx="8" cy="8" r="3" fill="#4ade80"/>
                        <circle cx="8" cy="8" r="6" stroke="#4ade80" stroke-width="1" opacity=".3"/>
                    </svg>
                    <span>{{ __('main.sidebar_server_active') }}</span>
                </div>

            </aside>{{-- /.pw-gm-sidebar --}}

        </div>{{-- /.pw-news-layout --}}
    </div>
</section>
@endif

{{-- RANKING SECTION removed from homepage --}}
@if(false)

        {{-- Tabs --}}
        <div class="pw-ranking" x-data="{ tab: 'players' }">
            <div class="pw-ranking__tabs">
                <button class="pw-ranking__tab" :class="{ 'is-active': tab === 'players' }" @click="tab = 'players'">
                    <svg viewBox="0 0 20 20" fill="none" width="16"><circle cx="10" cy="7" r="4" stroke="currentColor" stroke-width="1.5"/><path d="M3 17c0-3.314 3.134-6 7-6s7 2.686 7 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    {{ __('main.ranking_players') }}
                </button>
                @if(isset($factions) && $factions->count())
                <button class="pw-ranking__tab" :class="{ 'is-active': tab === 'guilds' }" @click="tab = 'guilds'">
                    <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M10 2l2.4 5 5.6.8-4 3.9.9 5.5L10 14.5l-4.9 2.7.9-5.5L2 7.8l5.6-.8L10 2z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>
                    {{ __('main.ranking_guilds') }}
                </button>
                @endif
            </div>

            {{-- Players Tab --}}
            <div x-show="tab === 'players'" x-transition.opacity>
                @if(isset($ranking) && $ranking->count())
                <div class="pw-ranking__table-wrap">
                    <table class="pw-ranking__table">
                        <thead>
                            <tr>
                                <th>{{ __('main.ranking_rank') }}</th>
                                <th>{{ __('main.ranking_name') }}</th>
                                <th>{{ __('main.ranking_class') }}</th>
                                <th>{{ __('main.ranking_level') }}</th>
                                <th>{{ __('main.ranking_exp') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ranking as $i => $player)
                            <tr class="{{ $i < 3 ? 'pw-ranking__top' : '' }}">
                                <td>
                                    @if($i === 0)
                                        <span class="pw-rank pw-rank--1">
                                            <svg viewBox="0 0 20 20" fill="none" width="14"><path d="M10 2l1.8 5.5H17l-4.6 3.4 1.7 5.4L10 13.1l-4.1 3.2 1.7-5.4L3 7.5h5.2L10 2z" fill="#FFD700"/></svg>
                                            1
                                        </span>
                                    @elseif($i === 1)
                                        <span class="pw-rank pw-rank--2">
                                            <svg viewBox="0 0 20 20" fill="none" width="14"><path d="M10 2l1.8 5.5H17l-4.6 3.4 1.7 5.4L10 13.1l-4.1 3.2 1.7-5.4L3 7.5h5.2L10 2z" fill="#C0C0C0"/></svg>
                                            2
                                        </span>
                                    @elseif($i === 2)
                                        <span class="pw-rank pw-rank--3">
                                            <svg viewBox="0 0 20 20" fill="none" width="14"><path d="M10 2l1.8 5.5H17l-4.6 3.4 1.7 5.4L10 13.1l-4.1 3.2 1.7-5.4L3 7.5h5.2L10 2z" fill="#CD7F32"/></svg>
                                            3
                                        </span>
                                    @else
                                        <span class="pw-rank">{{ $i + 1 }}</span>
                                    @endif
                                </td>
                                <td class="pw-ranking__name">{{ $player->char_name }}</td>
                                <td>{{ $player->class_name ?? '—' }}</td>
                                <td><span class="pw-badge pw-badge--level">{{ $player->level }}</span></td>
                                <td class="pw-ranking__exp">{{ number_format($player->exp) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="pw-section__empty">{{ __('main.ranking_empty') }}</p>
                @endif
            </div>

            {{-- Guilds / Factions Tab --}}
            @if(isset($factions) && $factions->count())
            <div x-show="tab === 'guilds'" x-transition.opacity>
                <div class="pw-ranking__table-wrap">
                    <table class="pw-ranking__table">
                        <thead>
                            <tr>
                                <th>{{ __('main.ranking_rank') }}</th>
                                <th>{{ __('main.ranking_name') }}</th>
                                <th>{{ app()->getLocale() === 'id' ? 'Anggota' : 'Members' }}</th>
                                <th>{{ app()->getLocale() === 'id' ? 'Level' : 'Level' }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($factions as $i => $faction)
                            <tr class="{{ $i < 3 ? 'pw-ranking__top' : '' }}">
                                <td>
                                    @if($i === 0) <span class="pw-rank pw-rank--1">1</span>
                                    @elseif($i === 1) <span class="pw-rank pw-rank--2">2</span>
                                    @elseif($i === 2) <span class="pw-rank pw-rank--3">3</span>
                                    @else <span class="pw-rank">{{ $i + 1 }}</span>
                                    @endif
                                </td>
                                <td class="pw-ranking__name">{{ $faction->name }}</td>
                                <td>{{ $faction->member_count ?? '—' }}</td>
                                <td><span class="pw-badge pw-badge--level">{{ $faction->level ?? '—' }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>

        <div class="pw-section__footer">
            <a href="{{ route('ranking') }}" class="pw-btn pw-btn--gold">{{ __('main.ranking_all') }}</a>
        </div>
    </div>
</section>
@endif

{{-- ============================================================
     DOWNLOAD SECTION
============================================================ --}}
@php $downloadUrl = \App\Models\Setting::get('download_url', null); @endphp
@if($downloadUrl)
<section class="pw-download">
    <div class="pw-download__inner">
        <div class="pw-download__text">
            <h2>{{ __('main.download_title') }}</h2>
            <p>{{ __('main.download_subtitle') }}</p>
            <a href="{{ $downloadUrl }}" class="pw-btn pw-btn--gold pw-btn--lg pw-btn--glow" target="_blank" rel="noopener">
                <svg viewBox="0 0 20 20" fill="none" width="18" height="18"><path d="M7 10l3 3 3-3M10 4v9M4 16h12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                {{ __('main.download_btn') }}
            </a>
        </div>
        <div class="pw-download__graphic" aria-hidden="true">
            <svg viewBox="0 0 300 240" fill="none" xmlns="http://www.w3.org/2000/svg" width="300" height="240">
                <defs>
                    <radialGradient id="dlg" cx="50%" cy="50%" r="50%">
                        <stop offset="0%" stop-color="#c8972a" stop-opacity=".15"/>
                        <stop offset="100%" stop-color="transparent" stop-opacity="0"/>
                    </radialGradient>
                    <linearGradient id="dlg2" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#e8b84b"/>
                        <stop offset="100%" stop-color="#9a6820"/>
                    </linearGradient>
                </defs>
                <circle cx="150" cy="120" r="100" fill="url(#dlg)"/>
                <path d="M150 30 L100 120 L150 210 L200 120 Z" fill="url(#dlg2)" opacity=".2"/>
                <path d="M150 55 L117 120 L150 185 L183 120 Z" fill="#0a0a14" opacity=".5"/>
                <path d="M150 75 L128 120 L150 165 L172 120 Z" fill="url(#dlg2)" opacity=".4"/>
                <circle cx="150" cy="120" r="22" fill="url(#dlg2)" opacity=".6"/>
                <path d="M143 113l7 7 7-7M150 106v14" stroke="#0a0a14" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="150" cy="120" r="60" stroke="url(#dlg2)" stroke-width="0.5" opacity=".3" stroke-dasharray="4 4"/>
                <circle cx="150" cy="120" r="80" stroke="url(#dlg2)" stroke-width="0.3" opacity=".2" stroke-dasharray="2 6"/>
            </svg>
        </div>
    </div>
</section>
@endif

@endsection

@push('scripts')
<script>
function pwDiscord() {
    return {
        serverName: '···',
        onlineCount: 0,
        members: [],
        inviteUrl: null,
        fetchData() {
            fetch('https://discord.com/api/guilds/1491257146216812738/widget.json')
                .then(r => r.ok ? r.json() : Promise.reject())
                .then(d => {
                    this.serverName = d.name || 'Discord';
                    this.onlineCount = d.presence_count || 0;
                    this.members = d.members || [];
                    this.inviteUrl = d.instant_invite || null;
                })
                .catch(() => {});
            setTimeout(() => this.fetchData(), 60000);
        }
    };
}

function pwServerStatus() {
    const PW_CLASSES = {0:'Blademaster',1:'Wizard',2:'Psychic',3:'Venomancer',4:'Barbarian',5:'Assassin',6:'Archer',7:'Cleric',8:'Seeker',9:'Mystic'};
    const PW_CLASS_ICONS = {0:'blademaster',1:'wizzard',2:'psychic',3:'venomancer',4:'barbarian',5:'assasin',6:'archer',7:'cleric',8:'seeker',9:'mystic'};
    return {
        server: false,
        online: 0,
        accounts: 0,
        maps: [],
        players: [],
        uptime: '--',
        _uptimeSec: 0,
        _lastFetch: 0,
        _timer: null,
        init() {
            // Defer API call until after page renders to avoid blocking LCP
            setTimeout(() => {
                this.fetch();
                setInterval(() => this.fetch(), 30000);
            }, 500);
            // Tick uptime every second
            this._timer = setInterval(() => this._tick(), 1000);
        },
        classLabel(id) { return PW_CLASSES[id] || 'Unknown'; },
        classIcon(id) { return '/images/class/' + (PW_CLASS_ICONS[id] || 'blademaster') + '.png'; },
        fetch() {
            fetch('{{ route("api.online_count") }}')
                .then(r => r.json())
                .then(d => {
                    this.server = d.server;
                    this.online = d.online || 0;
                    this.accounts = d.accounts || 0;
                    this.maps = d.maps || [];
                    this.players = d.players || [];
                    if (d.server) {
                        this._uptimeSec = d.uptime || 0;
                        this._lastFetch = Date.now();
                        this._formatUptime();
                    } else {
                        this._uptimeSec = 0;
                        this.uptime = '--';
                    }
                })
                .catch(() => {});
        },
        _tick() {
            if (!this.server || !this._lastFetch) return;
            const elapsed = Math.floor((Date.now() - this._lastFetch) / 1000);
            const total = this._uptimeSec + elapsed;
            this._formatUptimeVal(total);
        },
        _formatUptime() { this._formatUptimeVal(this._uptimeSec); },
        _formatUptimeVal(s) {
            if (s <= 0) { this.uptime = '--'; return; }
            const d = Math.floor(s / 86400);
            const h = Math.floor((s % 86400) / 3600);
            const m = Math.floor((s % 3600) / 60);
            if (d > 0) this.uptime = d + 'd ' + h + 'h';
            else if (h > 0) this.uptime = h + 'h ' + m + 'm';
            else this.uptime = m + 'm ' + (s % 60) + 's';
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // ── Count-up animation for hero-accounts ──────────────────
    const acctEl = document.getElementById('hero-accounts');
    if (acctEl) {
        const target = parseInt(acctEl.dataset.target ?? '0', 10);
        let current = 0;
        const step = Math.max(1, Math.floor(target / 60));
        const tick = () => {
            current = Math.min(current + step, target);
            acctEl.textContent = current.toLocaleString('{{ app()->getLocale() === 'id' ? 'id-ID' : 'en-US' }}');
            if (current < target) requestAnimationFrame(tick);
        };
        requestAnimationFrame(tick);
    }

    // ── Magic ember particles ──────────────────────────────────
    const canvas = document.getElementById('hero-embers');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        const hero = document.getElementById('hero');
        let W, H, embers = [];

        const resize = () => {
            W = canvas.width  = hero.offsetWidth;
            H = canvas.height = hero.offsetHeight;
        };
        resize();
        window.addEventListener('resize', resize);

        const COLORS = ['rgba(200,151,42,', 'rgba(255,190,60,', 'rgba(255,120,40,', 'rgba(180,80,255,'];

        const spawn = () => ({
            x: Math.random() * W,
            y: H + 10,
            r: Math.random() * 2.5 + .8,
            speed: Math.random() * .8 + .3,
            drift: (Math.random() - .5) * .4,
            alpha: Math.random() * .7 + .3,
            color: COLORS[Math.floor(Math.random() * COLORS.length)],
            life: 1,
            decay: Math.random() * .003 + .001,
        });

        for (let i = 0; i < 40; i++) {
            const e = spawn();
            e.y = Math.random() * H; // spread initial positions
            embers.push(e);
        }

        const draw = () => {
            ctx.clearRect(0, 0, W, H);
            embers.forEach((e, i) => {
                e.y      -= e.speed;
                e.x      += e.drift;
                e.life   -= e.decay;
                e.alpha   = e.life * .8;

                if (e.life <= 0 || e.y < -10) {
                    embers[i] = spawn();
                    return;
                }

                ctx.save();
                ctx.globalAlpha = e.alpha;
                ctx.beginPath();
                ctx.arc(e.x, e.y, e.r, 0, Math.PI * 2);
                ctx.fillStyle = e.color + e.alpha + ')';
                ctx.shadowBlur = 8;
                ctx.shadowColor = e.color + '0.9)';
                ctx.fill();
                ctx.restore();
            });
            requestAnimationFrame(draw);
        };
        draw();
    }
});
</script>
@endpush
