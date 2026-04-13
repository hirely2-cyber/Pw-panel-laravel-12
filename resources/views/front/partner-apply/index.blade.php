@extends('layouts.app')

@php
    $__siteName = \App\Models\Setting::get('seo_title') ?: config('pw-config.server.name') ?: 'Perfect World';
@endphp

@section('title', __('main.pa_title') . ' — ' . $__siteName)
@section('meta_description', __('main.pa_meta_desc'))

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
        <h1 class="pw-page-hero__title">{{ __('main.pa_title') }}</h1>
        <p class="pw-page-hero__sub">{{ __('main.pa_subtitle') }}</p>
        <nav class="pw-breadcrumb" aria-label="breadcrumb">
            <a href="{{ route('home') }}" class="pw-breadcrumb__item">
                <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true">
                    <path d="M2 7.5L8 2l6 5.5V14a1 1 0 01-1 1h-3.5v-3.5h-3V15H3a1 1 0 01-1-1V7.5z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/>
                </svg>
                {{ __('main.nav_home') }}
            </a>
            <span class="pw-breadcrumb__sep" aria-hidden="true">/</span>
            <span class="pw-breadcrumb__item pw-breadcrumb__item--active">{{ __('main.pa_breadcrumb') }}</span>
        </nav>
    </div>
</div>

{{-- MAIN CONTENT --}}
<section class="pw-section">
    <div class="pw-section__inner pw-section__inner--narrow">

        @if(session('success'))
        <div class="pw-alert pw-alert--success" role="alert">
            <svg viewBox="0 0 20 20" fill="none" width="18"><circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.5"/><path d="M6 10l3 3 5-5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="pw-alert pw-alert--danger" role="alert">
            <svg viewBox="0 0 20 20" fill="none" width="18"><circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.5"/><path d="M10 6v5M10 13.5v.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            {{ session('error') }}
        </div>
        @endif

        {{-- Already a partner --}}
        @if($isPartner)
        <div class="pw-card" style="text-align:center;padding:3rem 2rem;">
            <svg viewBox="0 0 48 48" fill="none" width="48" style="margin-bottom:1rem;opacity:.7;">
                <circle cx="24" cy="24" r="20" stroke="#22c55e" stroke-width="2"/>
                <path d="M14 24l7 7 13-13" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <h3 style="color:#22c55e;font-size:1.1rem;margin-bottom:.5rem;">{{ __('main.pa_already_title') }}</h3>
            <p style="color:var(--pw-text-muted);font-size:.85rem;margin-bottom:1.2rem;">{{ __('main.pa_already_desc') }}</p>
            <a href="{{ route('partner.dashboard') }}" class="pw-btn pw-btn--gold">{{ __('main.pa_open_dashboard') }}</a>
        </div>

        {{-- Has pending application --}}
        @elseif($application && $application->status === 'pending')
        <div class="pw-card" style="text-align:center;padding:3rem 2rem;">
            <svg viewBox="0 0 48 48" fill="none" width="48" style="margin-bottom:1rem;opacity:.7;">
                <circle cx="24" cy="24" r="20" stroke="#f59e0b" stroke-width="2"/>
                <path d="M24 14v12M24 30v2" stroke="#f59e0b" stroke-width="2.5" stroke-linecap="round"/>
            </svg>
            <h3 style="color:#f59e0b;font-size:1.1rem;margin-bottom:.5rem;">{{ __('main.pa_pending_title') }}</h3>
            <p style="color:var(--pw-text-muted);font-size:.85rem;margin-bottom:1rem;">
                {{ __('main.pa_pending_sent') }} <strong>{{ $application->created_at->format('d M Y, H:i') }}</strong>.<br>
                {{ __('main.pa_pending_review') }}
            </p>
            <div class="pw-card" style="text-align:left;padding:1rem 1.2rem;background:rgba(245,158,11,.05);border:1px solid rgba(245,158,11,.15);margin-top:.5rem;">
                <div style="font-size:.78rem;color:var(--pw-text-muted);margin-bottom:.3rem;">{{ __('main.pa_details') }}</div>
                <div style="display:grid;gap:.4rem;font-size:.82rem;">
                    <div style="display:flex;justify-content:space-between;"><span style="color:var(--pw-text-muted);">Channel</span><span>{{ $application->channel_name }}</span></div>
                    <div style="display:flex;justify-content:space-between;"><span style="color:var(--pw-text-muted);">Platform</span><span style="text-transform:capitalize;">{{ $application->platform }}</span></div>
                    <div style="display:flex;justify-content:space-between;"><span style="color:var(--pw-text-muted);">Followers</span><span>{{ number_format($application->followers_count) }}</span></div>
                </div>
            </div>
        </div>

        {{-- Rejected — can re-apply --}}
        @elseif($application && $application->status === 'rejected')
        <div class="pw-alert pw-alert--danger" style="margin-bottom:1.5rem;">
            <svg viewBox="0 0 20 20" fill="none" width="18"><circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.5"/><path d="M10 6v5M10 13.5v.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            <div>
                {{ __('main.pa_rejected_prefix') }} <strong>{{ __('main.pa_rejected_word') }}</strong> {{ __('main.pa_rejected_on') }} {{ $application->reviewed_at?->format('d M Y') ?? '—' }}.
                @if($application->admin_notes)
                <br><span style="font-size:.8rem;">{{ __('main.pa_rejected_note') }} {{ $application->admin_notes }}</span>
                @endif
                <br><span style="font-size:.8rem;">{{ __('main.pa_rejected_reapply') }}</span>
            </div>
        </div>
        @include('front.partner-apply._form')

        {{-- Fresh application --}}
        @else
        @include('front.partner-apply._form')
        @endif

    </div>
</section>
@endsection

@push('styles')
<style>
.partner-terms-content h3 {
    font-size: .92rem;
    font-weight: 700;
    color: var(--pw-gold);
    margin: 1.4rem 0 .4rem;
}
.partner-terms-content ol {
    padding-left: 1.6rem;
    margin: 0 0 1rem;
}
.partner-terms-content li {
    margin-bottom: .35rem;
}
.partner-terms-content p {
    margin-top: 1rem;
    color: var(--pw-text-muted);
    font-size: .8rem;
    font-style: italic;
}
.partner-terms-content strong {
    color: var(--pw-text);
    font-weight: 700;
}
</style>
@endpush
