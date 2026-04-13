@extends('layouts.app')

@php
    $__siteName = \App\Models\Setting::get('seo_title') ?: config('pw-config.server.name') ?: 'Perfect World';
@endphp

@section('title', __('main.news_page_title') . ' — ' . $__siteName)
@section('meta_description', __('main.news_page_subtitle'))

@section('content')

{{-- ============================================================
     PAGE HERO — Judul & Breadcrumb
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
        <h1 class="pw-page-hero__title">{{ __('main.news_page_title') }}</h1>
        <p class="pw-page-hero__sub">{{ __('main.news_page_subtitle') }}</p>
        <nav class="pw-breadcrumb" aria-label="breadcrumb">
            <a href="{{ route('home') }}" class="pw-breadcrumb__item">
                <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true">
                    <path d="M2 7.5L8 2l6 5.5V14a1 1 0 01-1 1h-3.5v-3.5h-3V15H3a1 1 0 01-1-1V7.5z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/>
                </svg>
                {{ __('main.breadcrumb_home') }}
            </a>
            <span class="pw-breadcrumb__sep" aria-hidden="true">/</span>
            <span class="pw-breadcrumb__item pw-breadcrumb__item--active">{{ __('main.news_page_title') }}</span>
        </nav>
    </div>
</div>

{{-- ============================================================
     NEWS SECTION — full width 2-col, no sidebar, no featured
============================================================ --}}
<section class="pw-section" id="news">
    <div class="pw-section__inner pw-section__inner--narrow">

        {{-- Category filter --}}
        @php
            $categories = $news->getCollection()->pluck('category')->filter()->unique()->values();
        @endphp
        @if($categories->count())
        <div class="pw-news-filter">
            <a href="{{ route('news.index') }}"
               class="pw-news-filter__btn {{ !request('cat') ? 'is-active' : '' }}">
                {{ __('main.news_filter_all') }}
            </a>
            @foreach($categories as $cat)
            <a href="{{ route('news.index', ['cat' => $cat]) }}"
               class="pw-news-filter__btn {{ request('cat') === $cat ? 'is-active' : '' }}">
                {{ $cat }}
            </a>
            @endforeach
        </div>
        @endif

        @if($news->count())

        {{-- All articles: uniform 2-col grid --}}
        <div class="pw-news-grid pw-news-grid--full">
            @foreach($news as $article)
            <a href="{{ route('news.show', $article->slug) }}" class="pw-news-card">
                <div class="pw-news-card__thumb">
                    @if($article->thumbnail)
                        <img src="{{ Storage::url($article->thumbnail) }}" alt="{{ $article->title }}" loading="lazy">
                    @else
                        <svg viewBox="0 0 400 240" xmlns="http://www.w3.org/2000/svg">
                            <defs><linearGradient id="ng{{ $loop->index }}" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="{{ ['#0d1f3c','#1a2010','#2a0d0d','#1a0d2e','#0d1a2a','#1a1000'][$loop->index % 6] }}"/><stop offset="100%" stop-color="#0a0a14"/></linearGradient></defs>
                            <rect width="400" height="240" fill="url(#ng{{ $loop->index }})"/>
                            <path d="M200 60 L165 140 L200 200 L235 140 Z" fill="#c8972a" opacity=".2"/>
                            <circle cx="200" cy="130" r="22" fill="#c8972a" opacity=".25"/>
                        </svg>
                    @endif
                    <div class="pw-news-card__overlay"></div>
                    @if($loop->first)
                    <span class="pw-news-card__badge-new">{{ __('main.news_badge_latest') }}</span>
                    @endif
                    @if($article->category)
                    <span class="pw-news-card__cat">{{ $article->category }}</span>
                    @endif
                </div>
                <div class="pw-news-card__body">
                    <h3 class="pw-news-card__title">{{ Str::limit($article->title, 70) }}</h3>
                    <p class="pw-news-card__excerpt">{{ Str::limit($article->excerpt, 100) }}</p>
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

        {{-- Pagination --}}
        @if($news->hasPages())
        <div>
            {{ $news->appends(request()->query())->links() }}
        </div>
        @endif

        @else
        <p class="pw-section__empty">{{ __('main.news_no_articles') }}</p>
        @endif

    </div>
</section>

@endsection