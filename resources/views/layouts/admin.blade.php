<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php $__siteName = \App\Models\Setting::get('seo_title') ?: config('pw-config.server.name') ?: 'Perfect World'; @endphp
    <title>@yield('title', 'Admin') — {{ $__siteName }} Panel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Exo+2:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>!function(){var t=localStorage.getItem('pw-theme')||'dark';document.documentElement.setAttribute('data-theme',t);}()</script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Favicon --}}
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

    {{-- ====== ADMIN SIDEBAR ====== --}}
    <aside class="pw-adm-sidebar" id="adminSidebar">
        <div class="pw-adm-sidebar__head">
            <a href="{{ route('admin.dashboard') }}" class="pw-adm-sidebar__brand">
                <svg viewBox="0 0 24 24" fill="none" width="22" height="22"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>
                <span>Admin Panel</span>
            </a>
        </div>

        @php
        // Tentukan group mana yang aktif berdasarkan route saat ini
        $navActiveGroup = match(true) {
            request()->routeIs('admin.news*', 'admin.settings*')
                => 'konten',
            request()->routeIs('admin.shop*','admin.cubi-shop*','admin.vote*','admin.dungeon-vote*','admin.voucher*','admin.service*')
                => 'toko',
            request()->routeIs('admin.events*','admin.event-bonus*','admin.ranking*')
                => 'event',
            request()->routeIs('admin.roles*','admin.server-control*','admin.datafile-control*','admin.game-config*','admin.backup-monitor*','admin.mailer*','admin.broadcast*','admin.cubi-monitor*')
                => 'game',
            request()->routeIs('admin.donate*','admin.referral*','admin.bonus-claims*','admin.gm*')
                => 'keuangan',
            default => 'utama',
        };
        @endphp

        <nav class="pw-adm-nav" x-data="pwNav('{{ $navActiveGroup }}')" x-cloak>

            {{-- ── UTAMA (selalu terlihat, tanpa collapse) ── --}}
            <a href="{{ route('admin.dashboard') }}" class="pw-adm-nav__item {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">
                <svg viewBox="0 0 20 20" fill="none" width="16"><rect x="2" y="2" width="7" height="7" rx="1" stroke="currentColor" stroke-width="1.4"/><rect x="11" y="2" width="7" height="7" rx="1" stroke="currentColor" stroke-width="1.4"/><rect x="2" y="11" width="7" height="7" rx="1" stroke="currentColor" stroke-width="1.4"/><rect x="11" y="11" width="7" height="7" rx="1" stroke="currentColor" stroke-width="1.4"/></svg>
                Dashboard
            </a>
            @if(auth()->user()->isWebAdmin())
            <a href="{{ route('admin.members.index') }}" class="pw-adm-nav__item {{ request()->routeIs('admin.members*') ? 'is-active' : '' }}">
                <svg viewBox="0 0 20 20" fill="none" width="16"><circle cx="8" cy="7" r="3.5" stroke="currentColor" stroke-width="1.4"/><path d="M2 17c0-3 2.7-5.5 6-5.5s6 2.5 6 5.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/><path d="M14 8l2 2 3-3" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Player
            </a>
            @endif

            {{-- ── KONTEN WEBSITE ── --}}
            <button class="pw-adm-nav__group-toggle" @click="toggle('konten')" :class="{ 'is-open': isOpen('konten') }">
                <span class="pw-adm-nav__group-label">
                    <svg viewBox="0 0 20 20" fill="none" width="13"><path d="M4 5h12M4 10h8M4 15h6" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
                    Website
                </span>
                <svg class="pw-adm-nav__chevron" viewBox="0 0 16 16" fill="none" width="12" :style="isOpen('konten') ? 'transform:rotate(180deg)' : ''"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
            <div class="pw-adm-nav__group-items" x-show="isOpen('konten')" x-transition:enter="pw-nav-enter" x-transition:leave="pw-nav-leave">
                <a href="{{ route('admin.news.index') }}" class="pw-adm-nav__item pw-adm-nav__item--child {{ request()->routeIs('admin.news*') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 20 20" fill="none" width="15"><path d="M4 6h12M4 10h8M4 14h6" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
                    Berita
                </a>
                @if(auth()->user()->isAdministrator())
                <a href="{{ route('admin.settings.panel') }}" class="pw-adm-nav__item pw-adm-nav__item--child {{ request()->routeIs('admin.settings*') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 20 20" fill="none" width="15"><circle cx="10" cy="10" r="2.8" stroke="currentColor" stroke-width="1.4"/><path d="M10 2v1.5M10 16.5V18M2 10h1.5M16.5 10H18M4.2 4.2l1 1M14.8 14.8l1 1M15.8 4.2l-1 1M5.2 14.8l-1 1" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
                    Konfigurasi Panel
                </a>
                @endif
            </div>

            {{-- ── TOKO & FITUR ── --}}
            <button class="pw-adm-nav__group-toggle" @click="toggle('toko')" :class="{ 'is-open': isOpen('toko') }">
                <span class="pw-adm-nav__group-label">
                    <svg viewBox="0 0 20 20" fill="none" width="13"><path d="M5 5h10l-1.5 8H6.5L5 5zm0 0L4 3H2" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Toko & Fitur
                </span>
                <svg class="pw-adm-nav__chevron" viewBox="0 0 16 16" fill="none" width="12" :style="isOpen('toko') ? 'transform:rotate(180deg)' : ''"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
            <div class="pw-adm-nav__group-items" x-show="isOpen('toko')" x-transition:enter="pw-nav-enter" x-transition:leave="pw-nav-leave">
                @if(config('pw-config.features.shop', true))
                <a href="{{ route('admin.shop.index') }}" class="pw-adm-nav__item pw-adm-nav__item--child {{ request()->routeIs('admin.shop*') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 20 20" fill="none" width="15"><path d="M5 5h10l-1.5 8H6.5L5 5zm0 0L4 3H2" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/><circle cx="8" cy="16" r="1.2" fill="currentColor"/><circle cx="14" cy="16" r="1.2" fill="currentColor"/></svg>
                    Item Shop
                </a>
                @endif
                @if(config('pw-config.features.cubi_shop', true))
                <a href="{{ route('admin.cubi-shop') }}" class="pw-adm-nav__item pw-adm-nav__item--child {{ request()->routeIs('admin.cubi-shop*') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 20 20" fill="none" width="15"><path d="M17 10l-4-7H7L3 10l4 7h6l4-7z" stroke="currentColor" stroke-width="1.3"/><circle cx="10" cy="10" r="2.2" stroke="currentColor" stroke-width="1.3"/></svg>
                    Cubi Shop
                </a>
                @endif
                @if(config('pw-config.features.vote', true))
                <a href="{{ route('admin.vote.index') }}" class="pw-adm-nav__item pw-adm-nav__item--child {{ request()->routeIs('admin.vote*') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 20 20" fill="none" width="15"><path d="M10 2l1.8 5.5H17l-4.6 3.4 1.7 5.4L10 13.1l-4.1 3.2 1.7-5.4L3 7.5h5.2L10 2z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/></svg>
                    Vote Sites
                </a>
                @endif
                <a href="{{ route('admin.dungeon-vote.index') }}" class="pw-adm-nav__item pw-adm-nav__item--child {{ request()->routeIs('admin.dungeon-vote*') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 20 20" fill="none" width="15"><path d="M10 2L3 6v4c0 4 3.1 7.7 7 9 3.9-1.3 7-5 7-9V6l-7-4z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M7.5 10l1.5 1.5L13 8" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Dungeon Vote
                </a>
                @if(config('pw-config.features.voucher', true))
                <a href="{{ route('admin.voucher.index') }}" class="pw-adm-nav__item pw-adm-nav__item--child {{ request()->routeIs('admin.voucher*') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 20 20" fill="none" width="15"><rect x="2" y="6" width="16" height="8" rx="1.5" stroke="currentColor" stroke-width="1.4"/><path d="M7 10h6" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
                    Voucher
                </a>
                @endif
                @if(config('pw-config.features.service', true))
                <a href="{{ route('admin.service.index') }}" class="pw-adm-nav__item pw-adm-nav__item--child {{ request()->routeIs('admin.service*') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 20 20" fill="none" width="15"><circle cx="10" cy="10" r="2.8" stroke="currentColor" stroke-width="1.4"/><path d="M10 2v2M10 16v2M2 10h2M16 10h2M4.6 4.6l1.4 1.4M14 14l1.4 1.4M15.4 4.6L14 6M5 15.4L3.6 14" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
                    Layanan
                </a>
                @endif
            </div>

            {{-- ── EVENT ── --}}
            <button class="pw-adm-nav__group-toggle" @click="toggle('event')" :class="{ 'is-open': isOpen('event') }">
                <span class="pw-adm-nav__group-label">
                    <svg viewBox="0 0 20 20" fill="none" width="13"><path d="M4 3h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2z" stroke="currentColor" stroke-width="1.3"/><path d="M2 8h16" stroke="currentColor" stroke-width="1.3"/><path d="M7 1v4M13 1v4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                    Event & Ranking
                </span>
                <svg class="pw-adm-nav__chevron" viewBox="0 0 16 16" fill="none" width="12" :style="isOpen('event') ? 'transform:rotate(180deg)' : ''"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
            <div class="pw-adm-nav__group-items" x-show="isOpen('event')" x-transition:enter="pw-nav-enter" x-transition:leave="pw-nav-leave">
                <a href="{{ route('admin.events.index') }}" class="pw-adm-nav__item pw-adm-nav__item--child {{ request()->routeIs('admin.events*') && !request()->routeIs('admin.event-bonus*') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 20 20" fill="none" width="15"><path d="M4 3h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2z" stroke="currentColor" stroke-width="1.3"/><path d="M2 8h16" stroke="currentColor" stroke-width="1.3"/><path d="M7 1v4M13 1v4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                    Event
                </a>
                <a href="{{ route('admin.event-bonus.index') }}" class="pw-adm-nav__item pw-adm-nav__item--child {{ request()->routeIs('admin.event-bonus*') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 20 20" fill="none" width="15"><path d="M10 2l2.09 4.26 4.71.68-3.4 3.32.8 4.67L10 12.77l-4.2 2.16.8-4.67-3.4-3.32 4.71-.68L10 2z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/></svg>
                    Event Bonus
                </a>
                @if(config('pw-config.features.ranking', true))
                <a href="{{ route('admin.ranking') }}" class="pw-adm-nav__item pw-adm-nav__item--child {{ request()->routeIs('admin.ranking*') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 20 20" fill="none" width="15"><path d="M4 14v2M8 10v6M12 7v9M16 4v12" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
                    Ranking
                </a>
                @endif
            </div>

            {{-- ── MANAJEMEN GAME ── --}}
            <button class="pw-adm-nav__group-toggle" @click="toggle('game')" :class="{ 'is-open': isOpen('game') }">
                <span class="pw-adm-nav__group-label">
                    <svg viewBox="0 0 20 20" fill="none" width="13"><rect x="2" y="5" width="16" height="10" rx="2" stroke="currentColor" stroke-width="1.4"/><circle cx="7" cy="10" r="1.5" stroke="currentColor" stroke-width="1.2"/><path d="M13 8v4M11 10h4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                    Manajemen Game
                </span>
                <svg class="pw-adm-nav__chevron" viewBox="0 0 16 16" fill="none" width="12" :style="isOpen('game') ? 'transform:rotate(180deg)' : ''"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
            <div class="pw-adm-nav__group-items" x-show="isOpen('game')" x-transition:enter="pw-nav-enter" x-transition:leave="pw-nav-leave">
                <a href="{{ route('admin.roles.index') }}" class="pw-adm-nav__item pw-adm-nav__item--child {{ request()->routeIs('admin.roles*') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 20 20" fill="none" width="15"><circle cx="10" cy="7" r="3.5" stroke="currentColor" stroke-width="1.4"/><path d="M3 17c0-3 3-5.5 7-5.5s7 2.5 7 5.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
                    Character Roles
                </a>
                <a href="{{ route('admin.server-control') }}" class="pw-adm-nav__item pw-adm-nav__item--child {{ request()->routeIs('admin.server-control*') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 20 20" fill="none" width="15"><rect x="2" y="3" width="16" height="12" rx="2" stroke="currentColor" stroke-width="1.4"/><path d="M6 16h8M10 15v1" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/><circle cx="7" cy="9" r="1.1" fill="currentColor" opacity=".6"/><circle cx="10" cy="9" r="1.1" fill="currentColor" opacity=".6"/><circle cx="13" cy="9" r="1.1" fill="currentColor" opacity=".6"/></svg>
                    Server Control
                </a>
                @if(Route::has('admin.datafile-control'))
                <a href="{{ route('admin.datafile-control') }}" class="pw-adm-nav__item pw-adm-nav__item--child {{ request()->routeIs('admin.datafile-control*') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 20 20" fill="none" width="15"><path d="M4 3h8l4 4v10H4z" stroke="currentColor" stroke-width="1.3"/><path d="M12 3v4h4" stroke="currentColor" stroke-width="1.3"/><path d="M7 12h6M7 15h6" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                    DATAFILE Control
                </a>
                @endif
                @if(auth()->user()->isAdministrator())
                <a href="{{ route('admin.game-config') }}" class="pw-adm-nav__item pw-adm-nav__item--child {{ request()->routeIs('admin.game-config*') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 20 20" fill="none" width="15"><circle cx="10" cy="10" r="2.8" stroke="currentColor" stroke-width="1.4"/><path d="M10 2v1.5M10 16.5V18M2 10h1.5M16.5 10H18M4.2 4.2l1 1M14.8 14.8l1 1M15.8 4.2l-1 1M5.2 14.8l-1 1" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
                    Game Config
                </a>
                <a href="{{ route('admin.backup-monitor') }}" class="pw-adm-nav__item pw-adm-nav__item--child {{ request()->routeIs('admin.backup-monitor*') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 20 20" fill="none" width="15"><path d="M10 3v9m0 0l-3-3m3 3l3-3" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 14v1a2 2 0 002 2h8a2 2 0 002-2v-1" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
                    Backup Monitor
                </a>
                <a href="{{ route('admin.mailer') }}" class="pw-adm-nav__item pw-adm-nav__item--child {{ request()->routeIs('admin.mailer*') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 20 20" fill="none" width="15"><rect x="2" y="5" width="16" height="12" rx="1.5" stroke="currentColor" stroke-width="1.4"/><path d="M2 7l8 5 8-5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Game Mailer
                </a>
                @endif
                <a href="{{ route('admin.broadcast') }}" class="pw-adm-nav__item pw-adm-nav__item--child {{ request()->routeIs('admin.broadcast*') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 20 20" fill="none" width="15"><path d="M3.5 7C5 4.5 7.3 3 10 3s5 1.5 6.5 4M5 9.5C6 7.8 7.9 6.5 10 6.5s4 1.3 5 3" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/><circle cx="10" cy="13" r="2" stroke="currentColor" stroke-width="1.4"/><path d="M10 15v2" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
                    Broadcast
                </a>
                <a href="{{ route('admin.cubi-monitor') }}" class="pw-adm-nav__item pw-adm-nav__item--child {{ request()->routeIs('admin.cubi-monitor*') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 20 20" fill="none" width="15"><path d="M10 2a8 8 0 110 16 8 8 0 010-16z" stroke="currentColor" stroke-width="1.4"/><path d="M10 6v4l3 2" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
                    Cubi Monitor
                </a>
            </div>

            {{-- ── KEUANGAN & ADMIN ── --}}
            @if(auth()->user()->isAdministrator())
            <button class="pw-adm-nav__group-toggle" @click="toggle('keuangan')" :class="{ 'is-open': isOpen('keuangan') }">
                <span class="pw-adm-nav__group-label">
                    <svg viewBox="0 0 20 20" fill="none" width="13"><rect x="2" y="5" width="16" height="11" rx="2" stroke="currentColor" stroke-width="1.4"/><path d="M2 9h16" stroke="currentColor" stroke-width="1.4"/><path d="M6 13h2M9 13h5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                    Keuangan & Admin
                </span>
                <svg class="pw-adm-nav__chevron" viewBox="0 0 16 16" fill="none" width="12" :style="isOpen('keuangan') ? 'transform:rotate(180deg)' : ''"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
            <div class="pw-adm-nav__group-items" x-show="isOpen('keuangan')" x-transition:enter="pw-nav-enter" x-transition:leave="pw-nav-leave">
                @if(config('pw-config.features.donate', true))
                <a href="{{ route('admin.donate') }}" class="pw-adm-nav__item pw-adm-nav__item--child {{ request()->routeIs('admin.donate*') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 20 20" fill="none" width="15"><rect x="2" y="5" width="16" height="11" rx="2" stroke="currentColor" stroke-width="1.4"/><path d="M2 9h16" stroke="currentColor" stroke-width="1.4"/></svg>
                    Donate/Invoice
                </a>
                @endif
                <a href="{{ route('admin.referral') }}" class="pw-adm-nav__item pw-adm-nav__item--child {{ request()->routeIs('admin.referral') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 20 20" fill="none" width="15"><path d="M3 5h14M3 10h14M3 15h9" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
                    Riwayat Referral
                </a>
                <a href="{{ route('admin.referral.partners') }}" class="pw-adm-nav__item pw-adm-nav__item--child {{ request()->routeIs('admin.referral.partners*') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 20 20" fill="none" width="15"><circle cx="6" cy="8" r="2.5" stroke="currentColor" stroke-width="1.3"/><circle cx="14" cy="8" r="2.5" stroke="currentColor" stroke-width="1.3"/><path d="M2 17c0-2.5 1.8-4 4-4M12 13c2.2 0 4 1.5 4 4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/><path d="M8 17c0-3 1.3-4.5 4-5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                    Pengaturan Partner
                </a>
                <a href="{{ route('admin.bonus-claims') }}" class="pw-adm-nav__item pw-adm-nav__item--child {{ request()->routeIs('admin.bonus-claims*') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 20 20" fill="none" width="15"><path d="M10 2v12M6 10l4 4 4-4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 16h14" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
                    Cairkan Bonus
                </a>
                <a href="{{ route('admin.gm.index') }}" class="pw-adm-nav__item pw-adm-nav__item--child {{ request()->routeIs('admin.gm*') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 20 20" fill="none" width="15"><path d="M10 2l1.5 4.5H16l-3.7 2.7 1.4 4.3L10 11l-3.7 2.5 1.4-4.3L4 6.5h4.5L10 2z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/></svg>
                    Manajemen Staff
                </a>
            </div>
            @endif

        </nav>

        <script>
        function pwNav(activeGroup) {
            const KEY = 'pw-nav-groups';
            const hasSaved = !!localStorage.getItem(KEY);
            const saved = (() => { try { return JSON.parse(localStorage.getItem(KEY) || '{}'); } catch { return {}; } })();
            // Default: game group terbuka jika belum ada state tersimpan
            const initial = { konten: false, toko: false, event: false, game: !hasSaved, keuangan: false };
            initial[activeGroup] = true;
            const state = Object.assign(initial, saved);
            // Group halaman aktif selalu terbuka
            state[activeGroup] = true;
            return {
                groups: state,
                isOpen(g) { return !!this.groups[g]; },
                toggle(g) {
                    this.groups[g] = !this.groups[g];
                    try { localStorage.setItem(KEY, JSON.stringify(this.groups)); } catch {}
                },
            };
        }
        </script>

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
        {{-- Topbar --}}
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
                <span style="width:1px;height:20px;background:var(--pw-border);flex-shrink:0;"></span>
                <span class="pw-adm-topbar__user">
                    <svg viewBox="0 0 20 20" fill="none" width="15" style="flex-shrink:0;opacity:.7"><circle cx="10" cy="7" r="4" stroke="currentColor" stroke-width="1.5"/><path d="M3 17c0-3.314 3.134-6 7-6s7 2.686 7 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    <span style="font-weight:600;color:var(--pw-text-light);">{{ auth()->user()->name }}</span>
                    @if(auth()->user()->role === 'admin')
                    <span style="font-size:.6rem;background:rgba(239,68,68,.15);color:#f87171;border:1px solid rgba(239,68,68,.3);border-radius:4px;padding:1px 6px;">Admin</span>
                    @elseif(auth()->user()->role === 'webadmin')
                    <span style="font-size:.6rem;background:#3b82f622;color:#3b82f6;border:1px solid #3b82f644;border-radius:4px;padding:1px 6px;">Web Admin</span>
                    @elseif(auth()->user()->role === 'gm')
                    <span style="font-size:.6rem;background:#b89d4f22;color:#b89d4f;border:1px solid #b89d4f44;border-radius:4px;padding:1px 6px;">GM</span>
                    @endif
                </span>
            </div>
        </header>

        {{-- Flash --}}
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

        {{-- Page content --}}
        <div class="pw-adm-content">
            @yield('content')
        </div>
    </div>

    @include('components.confirm-dialog')
    @stack('scripts')
</body>
</html>
