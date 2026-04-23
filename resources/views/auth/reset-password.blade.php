@extends('layouts.app')
@section('title', __("main.auth_reset_title"))

@php
    $__siteName = \App\Models\Setting::get('seo_title') ?: config('pw-config.server.name') ?: 'Perfect World';
    $__authLogo = \App\Models\Setting::get('site_footer_logo');
    $__authBg = \App\Models\Setting::get('site_auth_bg') ?: \App\Models\Setting::get('site_hero_bg');
@endphp

@section('content')
<div class="pw-auth">
    <div class="pw-auth__bg" style="background-image:url('{{ $__authBg ? \Illuminate\Support\Facades\Storage::url($__authBg) : '' }}');{{ $__authBg ? '' : 'background-image:none;' }}"></div>

    @if($__authLogo)
    <div style="display:flex;justify-content:center;margin-bottom:1rem;position:relative;z-index:2;">
        <img src="{{ \Illuminate\Support\Facades\Storage::url($__authLogo) }}" alt="{{ $__siteName }}"
             style="max-height:108px;width:auto;object-fit:contain;filter:drop-shadow(0 4px 10px rgba(0,0,0,.35));">
    </div>
    @endif

    <div class="pw-auth__box">
        <span class="pw-auth__corner pw-auth__corner--tl"></span>
        <span class="pw-auth__corner pw-auth__corner--tr"></span>
        <span class="pw-auth__corner pw-auth__corner--bl"></span>
        <span class="pw-auth__corner pw-auth__corner--br"></span>

        <div class="pw-auth__server">{{ $__siteName }}</div>
        <div class="pw-auth__heading">
            <h1>{{ __('main.auth_reset_title') }}</h1>
            <div class="pw-auth__heading-line"><span class="pw-auth__heading-gem"></span></div>
        </div>

        <p class="pw-auth__desc">{{ __('main.auth_reset_subtitle') }}</p>

        @if($errors->any())
        <div class="pw-auth__alert pw-auth__alert--error">
            <svg viewBox="0 0 20 20" fill="none" width="15" style="flex-shrink:0;margin-top:.1rem"><circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.5"/><path d="M10 6v4M10 13v.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="pw-form__group">
                <label for="email" class="pw-form__label">{{ __('main.auth_email') }}</label>
                <div class="pw-form__input-wrap">
                    <svg class="pw-form__ico" viewBox="0 0 20 20" fill="none" width="16"><path d="M3 5h14a1 1 0 011 1v8a1 1 0 01-1 1H3a1 1 0 01-1-1V6a1 1 0 011-1z" stroke="currentColor" stroke-width="1.5"/><path d="M2 6l8 6 8-6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    <input type="email" id="email" name="email"
                        class="pw-form__input pw-form__input--icon {{ $errors->has('email') ? 'is-invalid' : '' }}"
                        value="{{ old('email', $request->email) }}" required autocomplete="email"
                        placeholder="{{ __('main.auth_email_placeholder') }}" readonly>
                </div>
                @error('email') <p class="pw-form__error">{{ $message }}</p> @enderror
            </div>

            <div class="pw-form__group">
                <label for="password" class="pw-form__label">{{ __('main.auth_reset_new_password') }}</label>
                <div class="pw-form__input-wrap">
                    <svg class="pw-form__ico" viewBox="0 0 20 20" fill="none" width="16"><rect x="4" y="9" width="12" height="9" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M7 9V6a3 3 0 016 0v3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    <input type="password" id="password" name="password"
                        class="pw-form__input pw-form__input--icon {{ $errors->has('password') ? 'is-invalid' : '' }}"
                        required autocomplete="new-password"
                        placeholder="{{ __('main.auth_password_reg_placeholder') }}" autofocus>
                </div>
                @error('password')
                    <p class="pw-form__error">{{ $message }}</p>
                @else
                    <p style="margin:.4rem 0 0;font-size:.75rem;color:var(--pw-text-muted);line-height:1.5;">
                        Gunakan huruf kecil (a-z) dan angka (0-9) saja &mdash; min 6, maks 20 karakter.<br>
                        <span style="color:#f87171;">Huruf besar &amp; simbol tidak diperbolehkan.</span>
                    </p>
                @enderror
            </div>

            <div class="pw-form__group">
                <label for="password_confirmation" class="pw-form__label">{{ __('main.auth_password_confirm') }}</label>
                <div class="pw-form__input-wrap">
                    <svg class="pw-form__ico" viewBox="0 0 20 20" fill="none" width="16"><rect x="4" y="9" width="12" height="9" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M7 9V6a3 3 0 016 0v3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        class="pw-form__input pw-form__input--icon" required
                        autocomplete="new-password"
                        placeholder="{{ __('main.auth_password_confirm_placeholder') }}">
                </div>
            </div>

            <button type="submit" class="pw-btn pw-btn--gold pw-btn--glow" style="width:100%;justify-content:center;">
                <svg viewBox="0 0 20 20" fill="none" width="16"><rect x="4" y="9" width="12" height="9" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M7 9V6a3 3 0 016 0v3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><circle cx="10" cy="13.5" r="1.5" fill="currentColor"/></svg>
                {{ __('main.auth_reset_btn') }}
            </button>
        </form>

        <div class="pw-auth__footer">
            <p><a href="{{ route('login') }}" class="pw-auth__back">{{ __('main.auth_back_login') }}</a></p>
        </div>
    </div>

    @include('auth._footer')
</div>
@endsection
