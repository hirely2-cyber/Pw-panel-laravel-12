@extends('layouts.app')

@php
    $__siteName = \App\Models\Setting::get('seo_title') ?: config('pw-config.server.name') ?: 'Perfect World';
@endphp

@section('title', 'Item Shop — ' . $__siteName)
@section('meta_description', 'Beli item eksklusif menggunakan Gold Points di Item Shop ' . $__siteName)

@section('content')

{{-- ============================================================
     PAGE HERO
============================================================ --}}
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
        <h1 class="pw-page-hero__title">Item Shop</h1>
        <p class="pw-page-hero__sub">Beli item eksklusif menggunakan Gold Points kamu</p>
        <nav class="pw-breadcrumb" aria-label="breadcrumb">
            <a href="{{ route('home') }}" class="pw-breadcrumb__item">
                <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true">
                    <path d="M2 7.5L8 2l6 5.5V14a1 1 0 01-1 1h-3.5v-3.5h-3V15H3a1 1 0 01-1-1V7.5z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/>
                </svg>
                Beranda
            </a>
            <span class="pw-breadcrumb__sep" aria-hidden="true">/</span>
            <span class="pw-breadcrumb__item pw-breadcrumb__item--active">Item Shop</span>
        </nav>
    </div>
</div>

{{-- ============================================================
     SHOP CONTENT
============================================================ --}}
<section class="pw-section" id="shop">
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

        {{-- Gold Points Balance Bar --}}
        <div class="pw-shop-balance">
            <div class="pw-shop-balance__left">
                <div class="pw-shop-balance__label">Gold Points Kamu</div>
                <div class="pw-shop-balance__amount">
                    <img src="{{ asset('images/gif_icon/web_coin.gif') }}" alt="Gold Points" width="18" style="vertical-align:middle;">
                    {{ number_format(auth()->user()->money) }}
                    <span class="pw-shop-balance__unit">Gold Points</span>
                </div>
            </div>
            <div class="pw-shop-balance__actions">
                <a href="{{ route('shop.history') }}" class="pw-btn pw-btn--ghost pw-btn--sm">
                    <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true"><rect x="2" y="3" width="12" height="11" rx="1.5" stroke="currentColor" stroke-width="1.2"/><path d="M5 1v4M11 1v4M2 7h12" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/></svg>
                    Riwayat
                </a>
                <a href="{{ route('cubi-shop') }}" class="pw-btn pw-btn--gold pw-btn--sm">
                    <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true"><path d="M8 2v12M2 8h12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    Top-up Gold Points
                </a>
            </div>
        </div>

        @if($items->isEmpty())
        {{-- Empty --}}
        <div class="pw-shop-empty">
            <svg viewBox="0 0 64 64" fill="none" width="52" aria-hidden="true">
                <rect x="8" y="18" width="48" height="38" rx="4" stroke="#c8972a" stroke-width="1.8" opacity=".4"/>
                <path d="M22 18v-4a10 10 0 0120 0v4" stroke="#c8972a" stroke-width="1.8" stroke-linecap="round" opacity=".5"/>
                <circle cx="32" cy="37" r="4" stroke="#c8972a" stroke-width="1.5" opacity=".5"/>
            </svg>
            <p>Item shop sedang kosong. Cek lagi nanti!</p>
        </div>
        @else

        {{-- Category Tabs --}}
        @php $allCategories = $items->keys(); @endphp
        <div x-data="{ activeTab: '{{ $allCategories->first() }}' }">

            <div class="pw-shop-tabs">
                <button @click="activeTab = 'all'"
                        :class="activeTab === 'all' ? 'is-active' : ''"
                        class="pw-shop-tab">
                    <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true"><rect x="2" y="2" width="5" height="5" rx="1" stroke="currentColor" stroke-width="1.3"/><rect x="9" y="2" width="5" height="5" rx="1" stroke="currentColor" stroke-width="1.3"/><rect x="2" y="9" width="5" height="5" rx="1" stroke="currentColor" stroke-width="1.3"/><rect x="9" y="9" width="5" height="5" rx="1" stroke="currentColor" stroke-width="1.3"/></svg>
                    Semua
                </button>
                @foreach($allCategories as $cat)
                <button @click="activeTab = '{{ $cat }}'"
                        :class="activeTab === '{{ $cat }}' ? 'is-active' : ''"
                        class="pw-shop-tab">
                    {{ $cat }}
                </button>
                @endforeach
            </div>

            {{-- All Items (shown when activeTab = 'all') --}}
            <div x-show="activeTab === 'all'" x-transition>
                @foreach($items as $category => $categoryItems)
                <div class="pw-shop-section">
                    <div class="pw-shop-section__head">
                        <div class="pw-shop-section__line"></div>
                        <h2 class="pw-shop-section__title">{{ $category }}</h2>
                        <div class="pw-shop-section__line"></div>
                    </div>
                    <div class="pw-shop-grid">
                        @foreach($categoryItems as $item)
                            @include('front.shop._item_card', ['item' => $item])
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Per-category tabs --}}
            @foreach($items as $category => $categoryItems)
            <div x-show="activeTab === '{{ $category }}'" x-transition style="display:none;">
                <div class="pw-shop-grid" style="margin-top:1.5rem;">
                    @foreach($categoryItems as $item)
                        @include('front.shop._item_card', ['item' => $item])
                    @endforeach
                </div>
            </div>
            @endforeach

        </div>
        @endif

    </div>
</section>

@endsection