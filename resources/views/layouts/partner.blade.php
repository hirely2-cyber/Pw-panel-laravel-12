<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php $__siteName = \App\Models\Setting::get('seo_title') ?: config('pw-config.server.name') ?: 'Perfect World'; @endphp
    <title>@yield('title', 'Partner Panel') — {{ $__siteName }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Exo+2:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>!function(){var t=localStorage.getItem('pw-theme')||'dark';document.documentElement.setAttribute('data-theme',t);}()</script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @php $__favicon = \App\Models\Setting::get('site_favicon'); @endphp
    @if($__favicon)
        <link rel="icon" href="{{ Storage::url($__favicon) }}">
    @else
        <link rel="icon" href="/favicon.ico">
    @endif
    @stack('styles')
</head>
<body class="pw-body pw-admin-body">

    {{-- ====== MOBILE SIDEBAR OVERLAY ====== --}}
    <div class="pw-adm-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    {{-- ====== PARTNER SIDEBAR ====== --}}
    <aside class="pw-adm-sidebar" id="adminSidebar">
        <div class="pw-adm-sidebar__head">
            <a href="{{ route('partner.dashboard') }}" class="pw-adm-sidebar__brand">
                <svg viewBox="0 0 24 24" fill="none" width="22" height="22"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="8.5" cy="7" r="4" stroke="currentColor" stroke-width="1.5"/><path d="M20 8v6M23 11h-6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                <span>Partner Panel</span>
            </a>
        </div>

        <nav class="pw-adm-nav">
            <div class="pw-adm-nav__section">Utama</div>
            <a href="{{ route('partner.dashboard') }}" class="pw-adm-nav__item {{ request()->routeIs('partner.dashboard') ? 'is-active' : '' }}">
                <svg viewBox="0 0 20 20" fill="none" width="16"><rect x="2" y="2" width="7" height="7" rx="1" stroke="currentColor" stroke-width="1.5"/><rect x="11" y="2" width="7" height="7" rx="1" stroke="currentColor" stroke-width="1.5"/><rect x="2" y="11" width="7" height="7" rx="1" stroke="currentColor" stroke-width="1.5"/><rect x="11" y="11" width="7" height="7" rx="1" stroke="currentColor" stroke-width="1.5"/></svg>
                Dashboard
            </a>
            <a href="{{ route('partner.bonus') }}" class="pw-adm-nav__item {{ request()->routeIs('partner.bonus*') ? 'is-active' : '' }}">
                <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M10 2v12M6 10l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 16h14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                Cairkan Bonus
            </a>
            <a href="{{ route('partner.referral-history') }}" class="pw-adm-nav__item {{ request()->routeIs('partner.referral-history') ? 'is-active' : '' }}">
                <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M3 5h14M3 10h9M3 15h5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                Riwayat Referral
            </a>
            <a href="{{ route('partner.terms') }}" class="pw-adm-nav__item {{ request()->routeIs('partner.terms') ? 'is-active' : '' }}">
                <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M6 2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4a2 2 0 012-2z" stroke="currentColor" stroke-width="1.5"/><path d="M7 7h6M7 10h6M7 13h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                Peraturan & Ketentuan
            </a>
        </nav>

        <div class="pw-adm-sidebar__foot">
            <a href="{{ route('home') }}" class="pw-adm-nav__item" target="_blank">
                <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M10 2L2 7l8 5 8-5-8-5zM2 12l8 5 8-5" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>
                Lihat Website
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="pw-adm-nav__item pw-adm-nav__item--danger">
                    <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M13 15l4-5-4-5M17 10H7M7 3H4a1 1 0 00-1 1v12a1 1 0 001 1h3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- ====== MAIN AREA ====== --}}
    <div class="pw-adm-main">
        <header class="pw-adm-topbar">
            <div class="pw-adm-topbar__left">
                <button class="pw-adm-hamburger" onclick="toggleSidebar()" aria-label="Toggle sidebar">
                    <svg viewBox="0 0 24 24" fill="none" width="22" height="22"><path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                </button>
                <h1 class="pw-adm-topbar__title">@yield('title', 'Dashboard')</h1>
            </div>
            <div class="pw-adm-topbar__right">
                <button class="pw-theme-toggle" onclick="pwToggleTheme()" title="Toggle Light/Dark Mode" type="button">
                    <svg class="pw-theme-toggle__moon" viewBox="0 0 16 16" fill="none" width="12" height="12"><path d="M13.5 9.5a6 6 0 01-8-8 6 6 0 108 8z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <svg class="pw-theme-toggle__sun" viewBox="0 0 16 16" fill="none" width="12" height="12"><circle cx="8" cy="8" r="3" stroke="currentColor" stroke-width="1.4"/><path d="M8 1v2M8 13v2M1 8h2M13 8h2M3.2 3.2l1.4 1.4M11.4 11.4l1.4 1.4M11.4 3.2l-1.4 1.4M3.2 11.4l1.4-1.4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
                </button>
                <span class="pw-adm-topbar__user">
                    <span style="font-size:.68rem;background:#22c55e22;color:#22c55e;border:1px solid #22c55e44;border-radius:4px;padding:1px 6px;margin-left:.3rem;">Partner</span>
                </span>
            </div>
        </header>

        @if(session('success') || session('error'))
        <div style="padding: 0 1.5rem;">
            @if(session('success'))
            <div class="pw-adm-alert pw-adm-alert--success">✓ {{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div class="pw-adm-alert pw-adm-alert--error">✕ {{ session('error') }}</div>
            @endif
        </div>
        @endif

        <div class="pw-adm-content">
            @yield('content')
        </div>
    </div>

    @include('components.confirm-dialog')
    @stack('scripts')
</body>
</html>
