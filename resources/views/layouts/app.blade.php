<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-5P4CCK62');</script>
    <!-- End Google Tag Manager -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Favicon + SEO meta (dynamic from DB settings) --}}
    @php
        $__favicon = \App\Models\Setting::get('site_favicon');
        $__seoTitle = \App\Models\Setting::get('seo_title');
        $__seoDesc  = \App\Models\Setting::get('seo_description');
        $__seoKw    = \App\Models\Setting::get('seo_keywords');
        $__seoOg    = \App\Models\Setting::get('seo_og_image');
        $__seoGa    = \App\Models\Setting::get('seo_google_analytics');
        $__seoGoogleVerify = \App\Models\Setting::get('seo_google_verification');
        // Site name: Priority 1) SEO Title, 2) Config, 3) Default
        $__siteName = $__seoTitle ?: config('pw-config.server.name') ?: 'Perfect World';
        $__tagline  = config('pw-config.server.tagline', 'Private Server');
        $__pageTitle = trim(\Illuminate\Support\Facades\View::yieldContent('title'));
        $__metaTitle = $__pageTitle ?: ($__seoTitle ?: $__siteName.' — '.$__tagline);
        $__metaDesc  = trim(\Illuminate\Support\Facades\View::yieldContent('meta_description')) ?: ($__seoDesc ?: config('pw-config.server.description', ''));
    @endphp
    <title>{{ $__metaTitle }}</title>
    <meta name="description" content="{{ $__metaDesc }}">
    @if($__seoKw)
    <meta name="keywords" content="{{ $__seoKw }}">
    @endif
    @if($__seoGoogleVerify)
    <meta name="google-site-verification" content="{{ $__seoGoogleVerify }}">
    @endif
    @if($__favicon)
    <link rel="icon" type="image/png" href="{{ Storage::url($__favicon) }}">
    @else
    <link rel="icon" href="/favicon.ico">
    @endif
    {{-- Open Graph --}}
    <meta property="og:type"        content="website">
    <meta property="og:site_name"   content="{{ $__siteName }}">
    <meta property="og:title"       content="{{ $__metaTitle }}">
    <meta property="og:description" content="{{ $__metaDesc }}">
    @if($__seoOg)
    <meta property="og:image"       content="{{ Storage::url($__seoOg) }}">
    @endif
    {{-- Twitter Card --}}
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="{{ $__metaTitle }}">
    <meta name="twitter:description" content="{{ $__metaDesc }}">
    @if($__seoOg)
    <meta name="twitter:image"       content="{{ Storage::url($__seoOg) }}">
    @endif
    {{-- Google Analytics --}}
    @if($__seoGa)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $__seoGa }}"></script>
    <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','{{ $__seoGa }}');</script>
    @endif

    {{-- Fonts (non-blocking preload) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Exo+2:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Exo+2:wght@300;400;500;600;700&display=swap" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Exo+2:wght@300;400;500;600;700&display=swap"></noscript>

    {{-- Theme: apply before paint to prevent FOUC --}}
    <script>!function(){var t=localStorage.getItem('pw-theme')||'dark';document.documentElement.setAttribute('data-theme',t);}()</script>
    {{-- Styles compiled via Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Server status badge styles --}}
    <style>
    .pw-server-badge{display:inline-flex;align-items:center;gap:.25rem;padding:.1rem .45rem;border-radius:12px;font-size:.65rem;font-weight:700;letter-spacing:.03em;line-height:1;transition:all .3s ease;}
    .pw-server-badge--loading{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);color:var(--pw-text-muted,#8a8a9a);}
    .pw-server-badge--online{background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.25);color:#22c55e;}
    .pw-server-badge--offline{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);color:#ef4444;}
    .pw-server-badge__dot{width:5px;height:5px;border-radius:50%;flex-shrink:0;transition:background .3s ease;}
    .pw-server-badge--loading .pw-server-badge__dot{background:#6a6a7a;}
    .pw-server-badge--online .pw-server-badge__dot{background:#22c55e;box-shadow:0 0 5px rgba(34,197,94,.7);animation:pw-pulse-dot-green 1.5s ease-in-out infinite;}
    .pw-server-badge--offline .pw-server-badge__dot{background:#ef4444;box-shadow:0 0 5px rgba(239,68,68,.7);animation:pw-pulse-dot-red 1.2s ease-in-out infinite;}
    @keyframes pw-pulse-dot-green{0%,100%{opacity:1;transform:scale(1);}50%{opacity:.4;transform:scale(1.45);box-shadow:0 0 8px rgba(34,197,94,.9);}}
    @keyframes pw-pulse-dot-red{0%,100%{opacity:1;transform:scale(1);}50%{opacity:.35;transform:scale(1.45);box-shadow:0 0 8px rgba(239,68,68,.9);}}
    /* Light mode overrides */
    [data-theme="light"] .pw-server-badge--loading{background:rgba(0,0,0,.07);border-color:rgba(0,0,0,.18);color:#666;}
    [data-theme="light"] .pw-server-badge--online{background:rgba(22,163,74,.13);border-color:rgba(22,163,74,.45);color:#166534;}
    [data-theme="light"] .pw-server-badge--offline{background:rgba(220,38,38,.12);border-color:rgba(220,38,38,.45);color:#b91c1c;}
    [data-theme="light"] .pw-server-badge--loading .pw-server-badge__dot{background:#999;}
    [data-theme="light"] .pw-server-badge--online .pw-server-badge__dot{background:#16a34a;box-shadow:0 0 5px rgba(22,163,74,.6);}
    [data-theme="light"] .pw-server-badge--offline .pw-server-badge__dot{background:#dc2626;box-shadow:0 0 5px rgba(220,38,38,.6);}
    </style>

    @stack('styles')
</head>
<body class="pw-body">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5P4CCK62"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    {{-- Animated star particles background --}}
    <div class="pw-particles" aria-hidden="true">
        @for($i = 0; $i < 12; $i++)<span></span>@endfor
    </div>

    {{-- ==================== TOP STATS BAR ==================== --}}
    <div class="pw-topbar">
        <div class="pw-topbar__inner">
            <div class="pw-topbar__stats">
                <span class="pw-topbar__stat">
                    <svg class="pw-icon-xs" viewBox="0 0 20 20" fill="none"><circle cx="10" cy="7" r="4" stroke="currentColor" stroke-width="1.5"/><path d="M3 17c0-3.314 3.134-6 7-6s7 2.686 7 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    {{ __('main.stat_accounts') }}: <strong id="stat-accounts">—</strong>
                </span>
                <span class="pw-topbar__sep">·</span>
                <span class="pw-topbar__stat" id="topbar-server-status">
                    {{ __('main.server_status') }}:
                    <span class="pw-server-badge pw-server-badge--loading" id="topbar-badge">
                        <span class="pw-server-badge__dot" id="topbar-dot"></span>
                        <span id="topbar-status-text">...</span>
                    </span>
                </span>
                <span class="pw-topbar__sep">·</span>
                <span class="pw-topbar__stat">
                    {{ __('main.stat_version') }}: <strong>{{ \App\Models\Setting::get('server_version', config('pw-config.server.version', '1.5.5')) }}</strong>
                </span>
            </div>
            <div class="pw-topbar__right">
                {{-- Theme Toggle --}}
                <button class="pw-theme-toggle" onclick="pwToggleTheme()" title="Toggle Light/Dark Mode" type="button">
                    <svg class="pw-theme-toggle__moon" viewBox="0 0 16 16" fill="none" width="12" height="12"><path d="M13.5 9.5a6 6 0 01-8-8 6 6 0 108 8z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <svg class="pw-theme-toggle__sun" viewBox="0 0 16 16" fill="none" width="12" height="12"><circle cx="8" cy="8" r="3" stroke="currentColor" stroke-width="1.4"/><path d="M8 1v2M8 13v2M1 8h2M13 8h2M3.2 3.2l1.4 1.4M11.4 11.4l1.4 1.4M11.4 3.2l-1.4 1.4M3.2 11.4l1.4-1.4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
                </button>
                {{-- Language Toggle --}}
                @php $nextLocale = app()->getLocale() === 'id' ? 'en' : 'id'; @endphp
                <a href="{{ route('lang.switch', $nextLocale) }}" class="pw-lang__toggle" title="Switch to {{ strtoupper($nextLocale) }}">
                    @if(app()->getLocale() === 'id')
                        <svg viewBox="0 0 30 20" width="20" height="13"><rect width="30" height="10" fill="#CE1126"/><rect y="10" width="30" height="10" fill="#FFF"/></svg>
                        <span>ID</span>
                        <svg class="pw-lang__arrow" viewBox="0 0 16 16" fill="none" width="10"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    @else
                        <svg viewBox="0 0 60 30" width="20" height="13"><rect width="60" height="30" fill="#012169"/><path d="M0 0l60 30M60 0L0 30" stroke="#fff" stroke-width="6"/><path d="M0 0l60 30M60 0L0 30" stroke="#C8102E" stroke-width="4"/><path d="M30 0v30M0 15h60" stroke="#fff" stroke-width="10"/><path d="M30 0v30M0 15h60" stroke="#C8102E" stroke-width="6"/></svg>
                        <span>EN</span>
                        <svg class="pw-lang__arrow" viewBox="0 0 16 16" fill="none" width="10"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    @endif
                </a>
                @auth
                <a href="{{ route('cubi-shop') }}" class="pw-topbar__cta"><svg viewBox="0 0 20 20" fill="none" width="12" height="12" style="vertical-align:middle;margin-right:.25rem;"><path d="M11 1L5 11h4l-1 8 7-10h-5l1-8z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>Top-up</a>
                @endauth
            </div>
        </div>
    </div>

    {{-- ==================== NAVBAR ==================== --}}
    <nav class="pw-nav" id="pw-nav" x-data="{ menuOpen: false, userOpen: false }">
        <div class="pw-nav__inner">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="pw-nav__brand">
                @php $logoSetting = \App\Models\Setting::get('site_logo', null); @endphp
                @if($logoSetting)
                    <img src="{{ Storage::url($logoSetting) }}" alt="{{ $__siteName }}" class="pw-nav__logo-img">
                @else
                <svg class="pw-nav__logo-svg" viewBox="0 0 160 44" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="lg1" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#e8b84b"/>
                            <stop offset="100%" stop-color="#9a6820"/>
                        </linearGradient>
                    </defs>
                    <path d="M22 4 L10 22 L22 40 L34 22 Z" fill="url(#lg1)" opacity=".9"/>
                    <path d="M22 11 L16 22 L22 33 L28 22 Z" fill="#1e1e1e"/>
                    <circle cx="22" cy="22" r="5" fill="url(#lg1)"/>
                    <text x="42" y="19" font-family="Cinzel,serif" font-size="13" font-weight="700" fill="url(#lg1)">{{ $__siteName }}</text>
                    <text x="42" y="33" font-family="Exo 2,sans-serif" font-size="8" fill="#6a8099" letter-spacing="3">PRIVATE SERVER</text>
                </svg>
                @endif
            </a>

            {{-- Mobile-only center logo (footer logo) --}}
            <a href="{{ route('home') }}" class="pw-nav__brand-mobile">
                @php $__mFooterLogo = \App\Models\Setting::get('site_footer_logo'); @endphp
                @if($__mFooterLogo)
                    <img src="{{ Storage::url($__mFooterLogo) }}" alt="{{ $__siteName }}" class="pw-nav__logo-mobile-img">
                @else
                    <svg viewBox="0 0 140 50" fill="none" width="100" xmlns="http://www.w3.org/2000/svg">
                        <defs><linearGradient id="mlg" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#e8b84b"/><stop offset="100%" stop-color="#9a6820"/></linearGradient></defs>
                        <path d="M20 6 L10 22 L20 38 L30 22 Z" fill="url(#mlg)" opacity=".8"/>
                        <path d="M20 12 L15 22 L20 32 L25 22 Z" fill="#141414"/>
                        <circle cx="20" cy="22" r="4.5" fill="url(#mlg)"/>
                        <text x="38" y="20" font-family="Cinzel,serif" font-size="12" font-weight="700" fill="url(#mlg)">{{ $__siteName }}</text>
                        <text x="38" y="33" font-family="Exo 2,sans-serif" font-size="7.5" fill="#4a6070" letter-spacing="2">PRIVATE SERVER</text>
                    </svg>
                @endif
            </a>

            {{-- Desktop Nav Links --}}
            <ul class="pw-nav__links">
                <li><a href="{{ route('home') }}" class="pw-nav__link {{ request()->routeIs('home') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 16 16" fill="none" width="14"><path d="M2 7.5L8 2l6 5.5V14a1 1 0 01-1 1h-3.5v-3.5h-3V15H3a1 1 0 01-1-1V7.5z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/></svg>
                    {{ __('main.nav_home') }}</a></li>
                @if(config('pw-config.features.ranking', true))
                <li><a href="{{ route('ranking') }}" class="pw-nav__link {{ request()->routeIs('ranking') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 16 16" fill="none" width="14"><path d="M4 14V8h3v6M6.5 14V5h3v9M9.5 14V7h3v7" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Ranking</a></li>
                @endif
                <li><a href="{{ route('donatur') }}" class="pw-nav__link {{ request()->routeIs('donatur') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 16 16" fill="none" width="14"><path d="M8 1.5l1.5 4.5H14l-3.7 2.8 1.4 4.5L8 10.5l-3.7 2.8 1.4-4.5L2 6h4.5L8 1.5z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/></svg>
                    {{ __('main.nav_top_spender') }}</a></li>
                @if(\App\Models\LaunchEvent::whereIn('status', ['active', 'ended', 'distributed'])->exists())
                <li><a href="{{ route('event') }}" class="pw-nav__link {{ request()->routeIs('event') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 16 16" fill="none" width="14"><path d="M5 2h6v3a3 3 0 11-6 0V2z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/><path d="M5 4H3a2 2 0 002 2M11 4h2a2 2 0 01-2 2M6.5 8v1.5M9.5 8v1.5M5 9.5h6a1 1 0 011 1V11H4v-.5a1 1 0 011-1zM6 11v2h4v-2" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    {{ __('main.nav_event') }}</a></li>
                @endif
                {{-- Shop dihilangkan dari navbar, sudah ada link di hero homepage --}}
                @if(config('pw-config.cubi_shop.enabled', true))
                <li><a href="{{ route('cubi-shop') }}" class="pw-nav__link {{ request()->routeIs('cubi-shop*') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 16 16" fill="none" width="14"><path d="M8 1v14M4.5 3.5h5a2 2 0 010 4H5M5 7.5h5.5a2 2 0 010 4h-6" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Top Up</a></li>
                @endif
                @if(config('pw-config.features.service', true))
                <li><a href="{{ route('services') }}" class="pw-nav__link {{ request()->routeIs('services*') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 16 16" fill="none" width="14"><path d="M6.5 1.5l-1 2.5L2 5l2.5 2-0.5 3L8 8.5 11.5 10 11 7l2.5-2-3.5-1-1-2.5z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/><path d="M4 12.5l-1.5 2M12 12.5l1.5 2M8 13v2" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                    {{ __('main.nav_service') }}</a></li>
                @endif
                <li><a href="{{ route('partner-apply') }}" class="pw-nav__link {{ request()->routeIs('partner-apply*') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 16 16" fill="none" width="14"><path d="M6 7a3 3 0 100-6 3 3 0 000 6zM1 14c0-2.8 2.2-5 5-5s5 2.2 5 5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/><path d="M12 5v4M10 7h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    {{ __('main.nav_partner') }}</a></li>
                <li><a href="{{ route('download') }}" class="pw-nav__link {{ request()->routeIs('download') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 16 16" fill="none" width="14"><path d="M8 2v8M5 7.5L8 10.5 11 7.5M3 12.5h10" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    {{ __('main.nav_download') }}</a></li>
                <li><a href="{{ route('dungeon-vote') }}" class="pw-nav__link {{ request()->routeIs('dungeon-vote*') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 16 16" fill="none" width="14"><path d="M8 1L2 4.5v4c0 3.2 2.5 6.2 6 7 3.5-0.8 6-3.8 6-7v-4L8 1z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/><path d="M5.5 8l1.5 1.5L11 6" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Dungeon Vote</a></li>
            </ul>

            {{-- Right: User / Auth --}}
            <div class="pw-nav__right">
                @auth
                    <div class="pw-gold-badge">
                        <img src="{{ asset('images/gif_icon/web_coin.gif') }}" alt="Gold Points" width="16" height="16" style="vertical-align:middle;">
                        {{ number_format(auth()->user()->money) }}
                    </div>

                    {{-- Character Selector --}}
                    @php
                        $__chars = auth()->user()->gameCharacters();
                        $__activeChar = session('active_character');
                    @endphp
                    @if($__chars->isNotEmpty())
                    <div class="pw-char-selector" x-data="charSelector()" @click.outside="open = false">
                        <button class="pw-char-btn" @click="open = !open" type="button">
                            <svg viewBox="0 0 16 16" fill="none" width="14" aria-hidden="true"><path d="M8 1.5a3.5 3.5 0 100 7 3.5 3.5 0 000-7zM3 13.5c0-2.485 2.239-4.5 5-4.5s5 2.015 5 4.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                            <span x-text="activeName">{{ $__activeChar->name ?? $__chars->first()->name }}</span>
                            <svg class="pw-chevron" :class="{ 'pw-chevron--up': open }" viewBox="0 0 16 16" fill="none" width="11"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                        </button>
                        <div class="pw-char-dropdown" x-show="open" x-transition.opacity x-cloak>
                            <div class="pw-char-dropdown__label">{{ __('main.nav_select_char') }}</div>
                            @foreach($__chars as $char)
                            <button class="pw-char-dropdown__item"
                                    :class="{ 'is-active': activeId === {{ $char->role_id }} }"
                                    @click="select({{ $char->role_id }}, '{{ e($char->name) }}')"
                                    type="button">
                                <div class="pw-char-dropdown__info">
                                    <span class="pw-char-dropdown__name">{{ $char->name }}</span>
                                    <span class="pw-char-dropdown__meta">Lv.{{ $char->level }} {{ $char->class }}</span>
                                </div>
                                <svg x-show="activeId === {{ $char->role_id }}" viewBox="0 0 14 14" fill="none" width="13" class="pw-char-dropdown__check"><circle cx="7" cy="7" r="6" fill="var(--pw-gold)"/><path d="M4 7l2 2 4-4" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    <div class="pw-user-menu" x-data="{ open: false }">
                        <button class="pw-user-btn" @click="open = !open" @click.outside="open = false">
                            <svg viewBox="0 0 20 20" fill="none" width="18" height="18"><circle cx="10" cy="7" r="4" stroke="currentColor" stroke-width="1.5"/><path d="M3 17c0-3.314 3.134-6 7-6s7 2.686 7 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                            <span>{{ Str::limit(auth()->user()->name, 12) }}</span>
                            <svg class="pw-chevron" :class="{ 'pw-chevron--up': open }" viewBox="0 0 16 16" fill="none" width="12"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                        </button>
                        <div class="pw-dropdown" x-show="open" x-transition.opacity x-cloak>
                            <a href="{{ route('profile') }}" class="pw-dropdown__item">
                                <svg viewBox="0 0 20 20" fill="none" width="16"><circle cx="10" cy="7" r="4" stroke="currentColor" stroke-width="1.5"/><path d="M3 17c0-3.314 3.134-6 7-6s7 2.686 7 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                                {{ __('main.nav_profile') }}
                            </a>
                            @if(config('pw-config.features.donate', true))
                            <a href="{{ route('donate.history') }}" class="pw-dropdown__item">
                                <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M4 6h12M4 10h8M4 14h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                                {{ __('main.nav_history') }}
                            </a>
                            @endif
                            @if(auth()->user()->isAdministrator())
                            <div class="pw-dropdown__divider"></div>
                            <a href="{{ route('admin.dashboard') }}" class="pw-dropdown__item pw-dropdown__item--special">
                                <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M10 2l1.8 5.5H17l-4.6 3.4 1.7 5.4L10 13.1l-4.1 3.2 1.7-5.4L3 7.5h5.2L10 2z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>
                                {{ __('main.nav_admin') }}
                            </a>
                            @elseif(auth()->user()->role === 'webadmin')
                            <div class="pw-dropdown__divider"></div>
                            <a href="{{ route('admin.dashboard') }}" class="pw-dropdown__item pw-dropdown__item--special">
                                <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M10 2l1.8 5.5H17l-4.6 3.4 1.7 5.4L10 13.1l-4.1 3.2 1.7-5.4L3 7.5h5.2L10 2z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>
                                Web Admin
                            </a>
                            @elseif(auth()->user()->isPartner())
                            <div class="pw-dropdown__divider"></div>
                            <a href="{{ route('partner.dashboard') }}" class="pw-dropdown__item pw-dropdown__item--special">
                                <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="8.5" cy="7" r="4" stroke="currentColor" stroke-width="1.5"/><path d="M20 8v6M23 11h-6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                                Partner Panel
                            </a>
                            @elseif(auth()->user()->isGamemaster())
                            <div class="pw-dropdown__divider"></div>
                            <a href="{{ route('gm.dashboard') }}" class="pw-dropdown__item pw-dropdown__item--special">
                                <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M10 2l1.8 5.5H17l-4.6 3.4 1.7 5.4L10 13.1l-4.1 3.2 1.7-5.4L3 7.5h5.2L10 2z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>
                                {{ __('main.nav_gm') }}
                            </a>
                            @endif
                            <div class="pw-dropdown__divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="pw-dropdown__item pw-dropdown__item--danger">
                                    <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M13 15l4-5-4-5M17 10H7M7 3H4a1 1 0 00-1 1v12a1 1 0 001 1h3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    {{ __('main.nav_logout') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    @if(request()->routeIs('login'))
                        {{-- Di halaman login: hanya tampilkan tombol Register --}}
                        @if(config('pw-config.features.register', true))
                        <a href="{{ route('register') }}" class="pw-btn pw-btn--gold">{{ __('main.nav_register') }}</a>
                        @endif
                    @elseif(request()->routeIs('register'))
                        {{-- Di halaman register: hanya tampilkan tombol Login --}}
                        <a href="{{ route('login') }}" class="pw-btn pw-btn--ghost">{{ __('main.nav_login') }}</a>
                    @else
                        {{-- Halaman lain: tampilkan Fast-Login Hover Panel --}}
                        @php
                            $__isLoginRoute = request()->routeIs('login') || request()->is('auth/login');
                            $hasLoginError = $__isLoginRoute && $errors->hasAny(['name','password','captcha','email']);
                        @endphp
                        <div class="pw-fastlogin" x-data="fastLogin()"
                             x-init="if({{ $hasLoginError ? 'true' : 'false' }}) { open = true; loadCaptcha(); }"
                             @mouseenter="show()" @mouseleave="hide()">

                            <button class="pw-btn pw-btn--ghost" @click="open = !open; if(open) loadCaptcha()" aria-haspopup="true" :aria-expanded="open">
                                {{ __('main.nav_login') }}
                                <svg class="pw-chevron" :class="{ 'pw-chevron--up': open }" viewBox="0 0 16 16" fill="none" width="11" style="margin-left:.25rem"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                            </button>

                            <div class="pw-fastlogin__panel" x-show="open" x-cloak x-transition.opacity
                                 @mouseenter="keep()" @mouseleave="hide()">

                                <div class="pw-fastlogin__title">{{ strtoupper(__('main.nav_login')) }}</div>

                                @if($hasLoginError)
                                <div class="pw-fastlogin__error">
                                    <svg viewBox="0 0 20 20" fill="none" width="13" style="flex-shrink:0"><circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.5"/><path d="M10 6v4M10 13v.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                                    {{ $errors->first() }}
                                </div>
                                @endif

                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="pw-form__group">
                                        <div class="pw-form__input-wrap">
                                            <svg class="pw-form__ico" viewBox="0 0 20 20" fill="none" width="14"><circle cx="10" cy="7" r="4" stroke="currentColor" stroke-width="1.5"/><path d="M3 17c0-3.314 3.134-6 7-6s7 2.686 7 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                                            <input type="text" name="name" class="pw-form__input pw-form__input--icon"
                                                placeholder="{{ __('main.auth_username_placeholder') }}"
                                                value="{{ old('name') }}"
                                                autocomplete="username" required>
                                        </div>
                                    </div>
                                    <div class="pw-form__group">
                                        <div class="pw-form__input-wrap">
                                            <svg class="pw-form__ico" viewBox="0 0 20 20" fill="none" width="14"><rect x="4" y="9" width="12" height="9" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M7 9V6a3 3 0 016 0v3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                                            <input type="password" name="password" class="pw-form__input pw-form__input--icon"
                                                placeholder="{{ __('main.auth_password_placeholder') }}"
                                                autocomplete="current-password" required>
                                        </div>
                                    </div>
                                    <div class="pw-form__group">
                                        <div class="pw-captcha">
                                            <div class="pw-captcha__visual" x-html="captchaHTML" style="min-width:0;flex:1"></div>
                                            <button type="button" class="pw-captcha__refresh" @click="loadCaptcha()" title="{{ __('main.auth_captcha_refresh') }}">
                                                <svg viewBox="0 0 20 20" fill="none" width="13"><path d="M4 4a7 7 0 0112.14 3M16 16a7 7 0 01-12.14-3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M18 4l-2 3-2-3M2 16l2-3 2 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            </button>
                                        </div>
                                        <input type="text" name="captcha" class="pw-form__input"
                                            placeholder="{{ __('main.auth_captcha_answer') }}"
                                            required autocomplete="off" maxlength="6" minlength="6" spellcheck="false"
                                            style="letter-spacing:.2em;text-transform:uppercase;margin-top:.4rem;font-size:.82rem">
                                    </div>
                                    <button type="submit" class="pw-btn pw-btn--gold pw-btn--glow pw-fastlogin__submit">
                                        <svg viewBox="0 0 20 20" fill="none" width="14"><path d="M3 10h14M13 6l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        {{ __('main.auth_login_btn') }}
                                    </button>
                                </form>

                                <div class="pw-fastlogin__footer">
                                    <a href="{{ route('login') }}">{{ __('main.auth_login_subtitle') }}</a>
                                    @if(config('pw-config.features.register', true))
                                    <a href="{{ route('register') }}">{{ __('main.nav_register') }} →</a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if(config('pw-config.features.register', true))
                        <a href="{{ route('register') }}" class="pw-btn pw-btn--gold">{{ __('main.nav_register') }}</a>
                        @endif
                    @endif
                @endauth

                {{-- Hamburger --}}
                <button class="pw-hamburger" @click="menuOpen = !menuOpen" aria-label="Toggle menu">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div class="pw-mobile-menu" x-show="menuOpen" x-transition x-cloak>
            <a href="{{ route('home') }}" class="pw-mobile-link">
                <svg viewBox="0 0 16 16" fill="none" width="16"><path d="M2 7.5L8 2l6 5.5V14a1 1 0 01-1 1h-3.5v-3.5h-3V15H3a1 1 0 01-1-1V7.5z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/></svg>
                {{ __('main.nav_home') }}</a>
            @if(config('pw-config.features.ranking', true))
            <a href="{{ route('ranking') }}" class="pw-mobile-link">
                <svg viewBox="0 0 16 16" fill="none" width="16"><path d="M4 14V8h3v6M6.5 14V5h3v9M9.5 14V7h3v7" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Ranking</a>
            @endif
            <a href="{{ route('donatur') }}" class="pw-mobile-link">
                <svg viewBox="0 0 16 16" fill="none" width="16"><path d="M8 1.5l1.5 4.5H14l-3.7 2.8 1.4 4.5L8 10.5l-3.7 2.8 1.4-4.5L2 6h4.5L8 1.5z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/></svg>
                {{ __('main.nav_top_spender') }}</a>
            @if(\App\Models\LaunchEvent::whereIn('status', ['active', 'ended', 'distributed'])->exists())
            <a href="{{ route('event') }}" class="pw-mobile-link {{ request()->routeIs('event') ? 'is-active' : '' }}">
                <svg viewBox="0 0 16 16" fill="none" width="16"><path d="M5 2h6v3a3 3 0 11-6 0V2z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/><path d="M5 4H3a2 2 0 002 2M11 4h2a2 2 0 01-2 2M6.5 8v1.5M9.5 8v1.5M5 9.5h6a1 1 0 011 1V11H4v-.5a1 1 0 011-1zM6 11v2h4v-2" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                {{ __('main.nav_event') }}</a>
            @endif
            @if(config('pw-config.features.service', true))
            <a href="{{ route('services') }}" class="pw-mobile-link">
                <svg viewBox="0 0 16 16" fill="none" width="16"><path d="M6.5 1.5l-1 2.5L2 5l2.5 2-0.5 3L8 8.5 11.5 10 11 7l2.5-2-3.5-1-1-2.5z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/><path d="M4 12.5l-1.5 2M12 12.5l1.5 2M8 13v2" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                {{ __('main.nav_service') }}</a>
            @endif
            {{-- Shop dihilangkan dari mobile nav, sudah ada link di hero homepage --}}
            @if(config('pw-config.cubi_shop.enabled', true))
            <a href="{{ route('cubi-shop') }}" class="pw-mobile-link">
                <svg viewBox="0 0 16 16" fill="none" width="16"><path d="M8 1v14M4.5 3.5h5a2 2 0 010 4H5M5 7.5h5.5a2 2 0 010 4h-6" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Top Up</a>
            @endif
            <a href="{{ route('partner-apply') }}" class="pw-mobile-link">
                <svg viewBox="0 0 16 16" fill="none" width="16"><path d="M6 7a3 3 0 100-6 3 3 0 000 6zM1 14c0-2.8 2.2-5 5-5s5 2.2 5 5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/><path d="M12 5v4M10 7h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                {{ __('main.nav_partner') }}</a>
            <a href="{{ route('download') }}" class="pw-mobile-link">
                <svg viewBox="0 0 16 16" fill="none" width="16"><path d="M8 2v8M5 7.5L8 10.5 11 7.5M3 12.5h10" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                {{ __('main.nav_download') }}</a>
            <a href="{{ route('dungeon-vote') }}" class="pw-mobile-link {{ request()->routeIs('dungeon-vote*') ? 'is-active' : '' }}">
                <svg viewBox="0 0 16 16" fill="none" width="16"><path d="M8 1L2 4.5v4c0 3.2 2.5 6.2 6 7 3.5-0.8 6-3.8 6-7v-4L8 1z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/><path d="M5.5 8l1.5 1.5L11 6" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Dungeon Vote</a>
            <div class="pw-mobile-divider"></div>
            @auth
                @php $__mobileGold = auth()->user()->money; @endphp
                <div class="pw-mobile-link" style="color:var(--pw-gold-light);font-size:.8rem;cursor:default;">
                    <img src="{{ asset('images/gif_icon/web_coin.gif') }}" alt="" width="14" height="14" style="vertical-align:middle;">
                    {{ number_format($__mobileGold) }} Gold Points
                </div>
                <a href="{{ route('dashboard') }}" class="pw-mobile-link">
                    <svg viewBox="0 0 16 16" fill="none" width="16"><rect x="1" y="1" width="6.5" height="6.5" rx="1" stroke="currentColor" stroke-width="1.3"/><rect x="8.5" y="1" width="6.5" height="6.5" rx="1" stroke="currentColor" stroke-width="1.3"/><rect x="1" y="8.5" width="6.5" height="6.5" rx="1" stroke="currentColor" stroke-width="1.3"/><rect x="8.5" y="8.5" width="6.5" height="6.5" rx="1" stroke="currentColor" stroke-width="1.3"/></svg>
                    {{ __('main.nav_dashboard') }}</a>
                <a href="{{ route('profile') }}" class="pw-mobile-link">
                    <svg viewBox="0 0 16 16" fill="none" width="16"><circle cx="8" cy="5.5" r="3.5" stroke="currentColor" stroke-width="1.3"/><path d="M1.5 14.5c0-3.04 2.91-5.5 6.5-5.5s6.5 2.46 6.5 5.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                    {{ __('main.nav_profile') }}</a>
                @if(config('pw-config.features.donate', true))
                <a href="{{ route('donate.history') }}" class="pw-mobile-link">
                    <svg viewBox="0 0 16 16" fill="none" width="16"><path d="M2 4h12M2 8h8M2 12h6" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                    {{ __('main.nav_history') }}</a>
                @endif
                @if(config('pw-config.cubi_shop.enabled', true))
                <a href="{{ route('cubi-shop') }}" class="pw-mobile-link">
                    <svg viewBox="0 0 16 16" fill="none" width="16"><path d="M8 1v14M4.5 3.5h5a2 2 0 010 4H5M5 7.5h5.5a2 2 0 010 4h-6" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Top Up</a>
                @endif
                @if(auth()->user()->isAdministrator() || auth()->user()->role === 'webadmin')
                <a href="{{ route('admin.dashboard') }}" class="pw-mobile-link pw-mobile-link--cta">
                    <svg viewBox="0 0 16 16" fill="none" width="16"><path d="M8 1.5l1.4 4.2H13l-3.5 2.6 1.3 4.1L8 9.8l-3.9 2.6 1.3-4.1L2 5.7h3.6L8 1.5z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/></svg>
                    {{ __('main.nav_admin') }}</a>
                @elseif(auth()->user()->isPartner())
                <a href="{{ route('partner.dashboard') }}" class="pw-mobile-link pw-mobile-link--cta">
                    <svg viewBox="0 0 16 16" fill="none" width="16"><path d="M6 7a3 3 0 100-6 3 3 0 000 6zM1 14c0-2.8 2.2-5 5-5s5 2.2 5 5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/><path d="M12 5v4M10 7h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    Partner Panel</a>
                @elseif(auth()->user()->isGamemaster())
                <a href="{{ route('gm.dashboard') }}" class="pw-mobile-link pw-mobile-link--cta">
                    <svg viewBox="0 0 16 16" fill="none" width="16"><path d="M8 1.5l1.4 4.2H13l-3.5 2.6 1.3 4.1L8 9.8l-3.9 2.6 1.3-4.1L2 5.7h3.6L8 1.5z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/></svg>
                    {{ __('main.nav_gm') }}</a>
                @endif
                <div class="pw-mobile-divider"></div>
                <form method="POST" action="{{ route('logout') }}" style="display:contents;">
                    @csrf
                    <button type="submit" class="pw-mobile-link" style="background:none;border:none;width:100%;text-align:left;cursor:pointer;color:#f08080;">
                        <svg viewBox="0 0 16 16" fill="none" width="16"><path d="M10.5 11l3-3-3-3M13.5 8H6M6 2.5H3a1 1 0 00-1 1v9a1 1 0 001 1h3" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        {{ __('main.nav_logout') }}
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="pw-mobile-link">{{ __('main.nav_login') }}</a>
                @if(config('pw-config.features.register', true))
                <a href="{{ route('register') }}" class="pw-mobile-link pw-mobile-link--cta">{{ __('main.nav_register') }}</a>
                @endif
            @endauth
        </div>
    </nav>

    {{-- ==================== FLASH MESSAGES ==================== --}}
    @if(session('success') || session('error') || session('warning') || session('info'))
    <div class="pw-flash-wall">
        @foreach(['success','error','warning','info'] as $type)
            @if(session($type))
            <div class="pw-flash pw-flash--{{ $type }}" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)">
                @if($type === 'success') âœ“ @elseif($type === 'error') âœ• @elseif($type === 'warning') âš  @else â„¹ @endif
                {{ session($type) }}
                <button class="pw-flash__close" @click="show = false">Ã—</button>
            </div>
            @endif
        @endforeach
    </div>
    @endif

    {{-- ==================== MAIN CONTENT ==================== --}}
    <main class="pw-main">
        @yield('content')
    </main>

    {{-- ==================== FOOTER ==================== --}}
    <footer class="pw-footer">
        @php
            $waLink      = \App\Models\Setting::get('social_whatsapp', null);
            $fbLink      = \App\Models\Setting::get('social_facebook', null);
            $discordLink = \App\Models\Setting::get('social_discord', null);
        @endphp
        <div class="pw-footer__glow" aria-hidden="true"></div>
        <div class="pw-footer__inner">
            <div class="pw-footer__brand">
                @php
                    $__footerLogo = \App\Models\Setting::get('site_footer_logo');
                @endphp
                @if($__footerLogo)
                    <img src="{{ Storage::url($__footerLogo) }}" alt="{{ $__siteName }}" style="max-width:140px;height:auto;">
                @else
                <svg viewBox="0 0 140 50" fill="none" width="140" xmlns="http://www.w3.org/2000/svg">
                    <defs><linearGradient id="flg" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#e8b84b"/><stop offset="100%" stop-color="#9a6820"/></linearGradient></defs>
                    <path d="M20 6 L10 22 L20 38 L30 22 Z" fill="url(#flg)" opacity=".8"/>
                    <path d="M20 12 L15 22 L20 32 L25 22 Z" fill="#141414"/>
                    <circle cx="20" cy="22" r="4.5" fill="url(#flg)"/>
                    <text x="38" y="20" font-family="Cinzel,serif" font-size="12" font-weight="700" fill="url(#flg)">{{ $__siteName }}</text>
                    <text x="38" y="33" font-family="Exo 2,sans-serif" font-size="7.5" fill="#4a6070" letter-spacing="2">Private Server v{{ config('pw-config.server.version', '1.5.5') }}</text>
                </svg>
                @endif
                <p>{{ __('main.footer_private') }}</p>
            </div>

            <div class="pw-footer__col">
                <h3>{{ __('main.footer_links') }}</h3>
                <ul>
                    <li><a href="{{ route('home') }}">{{ __('main.nav_home') }}</a></li>
                    <li><a href="{{ route('home') }}#news">{{ __('main.nav_news') }}</a></li>
                    @if(config('pw-config.features.ranking', true))
                    <li><a href="{{ route('ranking') }}">{{ __('main.nav_ranking') }}</a></li>
                    @endif
                    @if(config('pw-config.features.donate', true))
                    <li><a href="{{ route('cubi-shop') }}">{{ __('main.nav_donate') }}</a></li>
                    @endif
                    @if(config('pw-config.features.vote', true))
                    <li><a href="{{ route('vote') }}">{{ __('main.nav_vote') }}</a></li>
                    @endif
                </ul>
            </div>

            <div class="pw-footer__col">
                <h3>{{ __('main.footer_support') }}</h3>
                <ul>
                    @auth
                    <li><a href="{{ route('dashboard') }}">{{ __('main.nav_dashboard') }}</a></li>
                    @else
                    <li><a href="{{ route('login') }}">{{ __('main.nav_login') }}</a></li>
                    @if(config('pw-config.features.register', true))
                    <li><a href="{{ route('register') }}">{{ __('main.nav_register') }}</a></li>
                    @endif
                    @endauth
                    @if($waLink)
                    <li><a href="https://wa.me/{{ $waLink }}" target="_blank" rel="noopener">WhatsApp</a></li>
                    @endif
                    @if($discordLink)
                    <li><a href="{{ $discordLink }}" target="_blank" rel="noopener">Discord</a></li>
                    @endif
                </ul>
            </div>

            <div class="pw-footer__col" style="grid-column: 1 / -1;">
                <div style="display:flex;align-items:center;justify-content:center;gap:1.5rem;font-size:.85rem;">
                    <a href="{{ route('tos') }}" style="color:var(--pw-text-muted);text-decoration:none;">{{ __('main.footer_tos') }}</a>
                    <span style="color:var(--pw-text-muted);">|</span>
                    <a href="{{ route('privacy') }}" style="color:var(--pw-text-muted);text-decoration:none;">{{ __('main.footer_privacy') }}</a>
                    <span style="color:var(--pw-text-muted);">|</span>
                    <a href="{{ route('terms') }}" style="color:var(--pw-text-muted);text-decoration:none;">{{ __('main.footer_terms') }}</a>
                </div>
            </div>
        </div>

        <div class="pw-footer__bottom">
            <p>&copy; {{ date('Y') }} {{ $__siteName }}. {{ __('main.footer_rights') }}</p>
            <div class="pw-footer__social" style="display:flex;gap:.75rem;">
                @if($waLink)
                <a href="https://wa.me/{{ $waLink }}" class="pw-social-btn" target="_blank" rel="noopener" title="WhatsApp" style="width:24px;height:24px;display:flex;align-items:center;justify-content:center;">
                    <svg viewBox="0 0 24 24" fill="currentColor" width="18"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.122 1.532 5.855L0 24l6.335-1.658A11.94 11.94 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.859 0-3.597-.5-5.097-1.371l-.366-.217-3.756.984 1.002-3.656-.238-.376A10 10 0 1122 12a10 10 0 01-10 10z"/></svg>
                </a>
                @endif
                @if($fbLink)
                <a href="{{ $fbLink }}" class="pw-social-btn" target="_blank" rel="noopener" title="Facebook" style="width:24px;height:24px;display:flex;align-items:center;justify-content:center;">
                    <svg viewBox="0 0 24 24" fill="currentColor" width="18"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                </a>
                @endif
                @if($discordLink)
                <a href="{{ $discordLink }}" class="pw-social-btn" target="_blank" rel="noopener" title="Discord" style="width:24px;height:24px;display:flex;align-items:center;justify-content:center;">
                    <svg viewBox="0 0 24 24" fill="currentColor" width="18"><path d="M20.317 4.492c-1.53-.69-3.17-1.2-4.885-1.49a.075.075 0 00-.079.036c-.21.369-.444.85-.608 1.23a18.566 18.566 0 00-5.487 0 12.36 12.36 0 00-.617-1.23A.077.077 0 008.562 3c-1.714.29-3.354.8-4.885 1.491a.07.07 0 00-.032.027C.533 9.093-.32 13.555.099 17.961a.08.08 0 00.031.055 20.03 20.03 0 005.993 2.98.078.078 0 00.084-.026 13.83 13.83 0 001.226-1.963.074.074 0 00-.041-.104 13.175 13.175 0 01-1.872-.878.075.075 0 01-.008-.125c.126-.093.252-.19.372-.287a.075.075 0 01.078-.01c3.927 1.764 8.18 1.764 12.061 0a.075.075 0 01.079.009c.12.098.245.195.372.288a.075.075 0 01-.006.125c-.598.344-1.22.635-1.873.877a.075.075 0 00-.041.105c.36.687.772 1.341 1.225 1.962a.077.077 0 00.084.028 19.963 19.963 0 006.002-2.981.076.076 0 00.032-.054c.5-5.094-.838-9.52-3.549-13.442a.06.06 0 00-.031-.028z"/></svg>
                </a>
                @endif
            </div>
            <p>{{ __('main.footer_private') }}</p>
        </div>
    </footer>

    @include('components.confirm-dialog')
    @include('components.social-proof-widget')
    @stack('scripts')
    <script>
    document.addEventListener('alpine:init', () => {
        @auth
        Alpine.data('charSelector', () => ({
            open: false,
            activeId: {{ session('active_character') ? session('active_character')->role_id : (auth()->user()->gameCharacters()->first() ? auth()->user()->gameCharacters()->first()->role_id : 0) }},
            activeName: '{{ e(session("active_character")?->name ?? (auth()->user()->gameCharacters()->first()?->name ?? "")) }}',
            select(roleId, name) {
                this.activeId = roleId;
                this.activeName = name;
                this.open = false;
                fetch('{{ route("api.character.select") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ role_id: roleId })
                });
            }
        }));
        @endauth

        Alpine.data('fastLogin', () => ({
            open: false,
            captchaHTML: '',
            timer: null,
            captchaUrl: '{{ route('captcha.refresh') }}',
            loadCaptcha() {
                fetch(this.captchaUrl)
                    .then(r => r.json())
                    .then(d => {
                        this.captchaHTML = d.chars.map(ch => {
                            var s = 'color:' + ch.color + ';transform:rotate(' + ch.deg + 'deg)';
                            return '<span style="' + s + '">' + ch.c + '</span>';
                        }).join('');
                    })
                    .catch(() => {});
            },
            show() { clearTimeout(this.timer); if (!this.open) { this.open = true; this.loadCaptcha(); } },
            hide() { this.timer = setTimeout(() => { this.open = false; }, 220); },
            keep() { clearTimeout(this.timer); }
        }));
    });
    </script>

    {{-- Global server status checker (runs on ALL pages) --}}
    <script>
    (function(){
        var txtOnline  = @json(__('main.server_online'));
        var txtOffline = @json(__('main.server_offline'));
        var locale     = '{{ app()->getLocale() === 'id' ? 'id-ID' : 'en-US' }}';
        var fmt        = function(n){ return Number(n||0).toLocaleString(locale); };

        function updateServerStatus(){
            fetch('/api/online-count',{headers:{'X-Requested-With':'XMLHttpRequest'}})
            .then(function(r){ return r.ok ? r.json() : null; })
            .then(function(d){
                if(!d) return;
                var badge = document.getElementById('topbar-badge');
                var text  = document.getElementById('topbar-status-text');
                var acct  = document.getElementById('stat-accounts');

                if(acct && d.accounts !== undefined) acct.textContent = fmt(d.accounts);

                if(badge && text){
                    if(d.server){
                        badge.className = 'pw-server-badge pw-server-badge--online';
                        text.textContent = txtOnline;
                    } else {
                        badge.className = 'pw-server-badge pw-server-badge--offline';
                        text.textContent = txtOffline;
                    }
                }

                // Also update hero status if exists (homepage)
                var heroChip  = document.getElementById('hero-server-chip');
                var heroText  = document.getElementById('server-status-text');
                if(heroChip && heroText){
                    if(d.server){
                        heroChip.classList.add('is-online');
                        heroText.textContent = 'ONLINE';
                    } else {
                        heroChip.classList.remove('is-online');
                        heroText.textContent = 'OFFLINE';
                    }
                }
            })
            .catch(function(){
                var badge = document.getElementById('topbar-badge');
                var text  = document.getElementById('topbar-status-text');
                if(badge) badge.className = 'pw-server-badge pw-server-badge--offline';
                if(text)  text.textContent = txtOffline;
            });
        }
        updateServerStatus();
        setInterval(updateServerStatus, 30000);
    })();
    </script>

    {{-- ============ FLOATING WIDGETS ============ --}}
    @php $waFloat = \App\Models\Setting::get('social_whatsapp', null); @endphp

    {{-- WhatsApp / Livechat --}}
    @if($waFloat)
    <a href="https://wa.me/{{ $waFloat }}" target="_blank" rel="noopener" class="pw-livechat" aria-label="Livechat">
        <span class="pw-livechat__pulse"></span>
        <span class="pw-livechat__icon">
            <svg viewBox="0 0 24 24" width="28" height="28" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347zM12.05 21.785h-.01a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.981.998-3.648-.235-.374A9.86 9.86 0 0 1 2.15 12.01C2.15 6.558 6.587 2.122 12.05 2.122c2.647 0 5.137 1.033 7.007 2.908a9.85 9.85 0 0 1 2.893 7.012c-.004 5.452-4.44 9.888-9.9 9.888v-.145zm8.413-18.3A11.82 11.82 0 0 0 12.05.002C5.495.002.16 5.335.157 11.892c-.001 2.096.547 4.142 1.588 5.946L.057 24l6.305-1.654a11.88 11.88 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.82 11.82 0 0 0-3.48-8.414z"/></svg>
        </span>
        <span class="pw-livechat__label">Livechat</span>
    </a>
    @endif

    {{-- Back to Top --}}
    <button class="pw-back-to-top" id="backToTop" aria-label="Back to top" onclick="window.scrollTo({top:0,behavior:'smooth'})">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
    </button>
    <script>
    (function(){
        var btn = document.getElementById('backToTop');
        window.addEventListener('scroll', function(){
            btn.classList.toggle('pw-back-to-top--show', window.scrollY > 400);
        }, {passive:true});
    })();
    </script>

    {{-- Mobile Bottom Nav --}}
    <nav class="pw-mobile-nav" aria-label="Mobile navigation">
        <a href="{{ route('home') }}" class="pw-mobile-nav__item {{ request()->routeIs('home') ? 'pw-mobile-nav__item--active' : '' }}">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            <span>{{ __('main.nav_home') }}</span>
        </a>
        <a href="{{ route('cubi-shop') }}" class="pw-mobile-nav__item {{ request()->routeIs('cubi-shop*') ? 'pw-mobile-nav__item--active' : '' }}">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
            <span>{{ __('main.nav_topup') }}</span>
        </a>
        @auth
        <a href="{{ route('dashboard') }}" class="pw-mobile-nav__item {{ request()->routeIs('dashboard') ? 'pw-mobile-nav__item--active' : '' }}">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <span>{{ __('main.nav_dashboard') }}</span>
        </a>
        @else
        <a href="{{ route('login') }}" class="pw-mobile-nav__item {{ request()->routeIs('login') ? 'pw-mobile-nav__item--active' : '' }}">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
            <span>{{ __('main.nav_login') }}</span>
        </a>
        @endauth
        <a href="{{ route('event') }}" class="pw-mobile-nav__item {{ request()->routeIs('event') ? 'pw-mobile-nav__item--active' : '' }}">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><path d="M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01"/></svg>
            <span>{{ __('main.nav_event') }}</span>
        </a>
        <a href="{{ route('news.index') }}" class="pw-mobile-nav__item {{ request()->routeIs('news.*') ? 'pw-mobile-nav__item--active' : '' }}">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><line x1="10" y1="6" x2="18" y2="6"/><line x1="10" y1="10" x2="18" y2="10"/><line x1="10" y1="14" x2="14" y2="14"/></svg>
            <span>{{ __('main.nav_news') }}</span>
        </a>
    </nav>
</body>
</html>
