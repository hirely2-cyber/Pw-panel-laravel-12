@extends('layouts.app')

@php
    $__siteName = \App\Models\Setting::get('seo_title') ?: config('pw-config.server.name') ?: 'Perfect World';
@endphp

@section('title', __('main.voucher_page_title') . ' — ' . $__siteName)
@section('meta_description', __('main.voucher_page_meta'))

@section('content')
@php
    $maskUsername = function (?string $username): string {
        $username = (string) ($username ?? '');
        $len = strlen($username);
        if ($len <= 0) return '-';
        return substr($username, 0, 1) . str_repeat('*', max(1, $len - 1));
    };
@endphp

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
        <h1 class="pw-page-hero__title">{{ __('main.voucher_page_title') }}</h1>
        <p class="pw-page-hero__sub">{{ __('main.voucher_page_subtitle') }}</p>
        <nav class="pw-breadcrumb" aria-label="breadcrumb">
            <a href="{{ route('home') }}" class="pw-breadcrumb__item">
                <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true">
                    <path d="M2 7.5L8 2l6 5.5V14a1 1 0 01-1 1h-3.5v-3.5h-3V15H3a1 1 0 01-1-1V7.5z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/>
                </svg>
                {{ __('main.breadcrumb_home') }}
            </a>
            <span class="pw-breadcrumb__sep" aria-hidden="true">/</span>
            <span class="pw-breadcrumb__item pw-breadcrumb__item--active">{{ __('main.voucher_page_title') }}</span>
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

        {{-- Redeem Form --}}
        <div class="pw-card" style="margin-bottom:1.5rem;padding:1.5rem;">
            <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1.2rem;">
                <svg viewBox="0 0 20 20" fill="none" width="20"><rect x="1" y="5" width="18" height="10" rx="2" stroke="#c8972a" stroke-width="1.3"/><path d="M7 5v10M13 5v10" stroke="#c8972a" stroke-width="1.3" stroke-dasharray="2 2"/><circle cx="10" cy="10" r="2" stroke="#c8972a" stroke-width="1.3"/></svg>
                <span style="font-size:1rem;font-weight:700;color:var(--pw-text);">{{ __('main.voucher_form_title') }}</span>
            </div>
            <form action="{{ route('voucher.redeem') }}" method="POST">
                @csrf
                <div style="display:flex;gap:.6rem;">
                    <input type="text" name="code" class="pw-form__input"
                           placeholder="{{ __('main.voucher_code_placeholder') }}" maxlength="16"
                           style="text-transform:uppercase;letter-spacing:.15em;font-size:1rem;flex:1;"
                           value="{{ old('code') }}" required>
                    <button type="submit" class="pw-btn pw-btn--gold">{{ __('main.voucher_btn_redeem') }}</button>
                </div>
                @error('code')
                <p style="color:#e05252;font-size:.75rem;margin-top:.4rem;">{{ $message }}</p>
                @enderror
            </form>
        </div>

        <div class="pw-card" style="padding:1.5rem;">
            <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1rem;">
                <svg viewBox="0 0 20 20" fill="none" width="18"><path d="M4 6h12M4 10h8M4 14h6" stroke="#c8972a" stroke-width="1.3" stroke-linecap="round"/></svg>
                <span style="font-size:.92rem;font-weight:700;color:var(--pw-text);">{{ __('main.voucher_usage_title') }}</span>
            </div>
            <div class="pw-table-wrap">
                <table class="pw-table">
                    <thead>
                        <tr>
                            <th>{{ __('main.voucher_col_name') }}</th>
                            <th>{{ __('main.voucher_col_username') }}</th>
                            <th>{{ __('main.voucher_col_code') }}</th>
                            <th>{{ __('main.voucher_col_type') }}</th>
                            <th>{{ __('main.voucher_col_reward') }}</th>
                            <th>{{ __('main.voucher_col_date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($usageLogs as $item)
                        <tr>
                            <td style="font-size:.82rem;">{{ $item->user->truename ?? __('main.voucher_name_fallback') }}</td>
                            <td style="font-size:.8rem;color:var(--pw-text-muted);">{{ $maskUsername($item->user->name ?? ('UID' . $item->user_id)) }}</td>
                            <td style="font-size:.82rem;"><code style="letter-spacing:.06em;">{{ $item->voucher->code ?? '—' }}</code></td>
                            <td style="font-size:.8rem;color:var(--pw-text-muted);">{{ $item->voucher?->reward_type_label ?? '—' }}</td>
                            <td style="color:#b89d4f;font-weight:600;">+{{ number_format($item->value_received) }} {{ $item->voucher?->normalized_type === 'cubi' ? 'Cubi Gold' : 'Gold Points' }}</td>
                            <td style="font-size:.75rem;color:var(--pw-text-muted);">{{ optional($item->created_at)->format('d M Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align:center;color:var(--pw-text-muted);">{{ __('main.voucher_usage_empty') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="margin-top:1rem;">{{ $usageLogs->appends(request()->except('usage_page'))->links() }}</div>
        </div>

    </div>
</section>
@endsection
