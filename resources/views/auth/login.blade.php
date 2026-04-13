@extends('layouts.app')
@section('title', __("main.nav_login"))

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
            <h1>{{ strtoupper(__('main.nav_login')) }}</h1>
            <div class="pw-auth__heading-line"><span class="pw-auth__heading-gem"></span></div>
        </div>

        @if($errors->any())
        <div class="pw-auth__alert pw-auth__alert--error">
            <svg viewBox="0 0 20 20" fill="none" width="15" style="flex-shrink:0;margin-top:.1rem"><circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.5"/><path d="M10 6v4M10 13v.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            {{ $errors->first() }}
        </div>
        @endif

        @if(session('status'))
        <div class="pw-auth__alert pw-auth__alert--success">
            <svg viewBox="0 0 20 20" fill="none" width="15" style="flex-shrink:0;margin-top:.1rem"><circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.5"/><path d="M7 10l2 2 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            {{ session('status') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}" id="login-form">
            @csrf

            <div class="pw-form__group">
                <div class="pw-form__input-wrap">
                    <svg class="pw-form__ico" viewBox="0 0 20 20" fill="none" width="16"><circle cx="10" cy="7" r="4" stroke="currentColor" stroke-width="1.5"/><path d="M3 17c0-3.314 3.134-6 7-6s7 2.686 7 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    <input type="text" id="name" name="name"
                        class="pw-form__input pw-form__input--icon {{ $errors->has('name') ? 'is-invalid' : '' }}"
                        value="{{ old('name') }}" required autocomplete="username"
                        placeholder="{{ __('main.auth_username_placeholder') }}" autofocus>
                </div>
                @error('name') <p class="pw-form__error">{{ $message }}</p> @enderror
            </div>

            <div class="pw-form__group">
                <div class="pw-form__input-wrap">
                    <svg class="pw-form__ico" viewBox="0 0 20 20" fill="none" width="16"><rect x="4" y="9" width="12" height="9" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M7 9V6a3 3 0 016 0v3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    <input type="password" id="password" name="password"
                        class="pw-form__input pw-form__input--icon {{ $errors->has('password') ? 'is-invalid' : '' }}"
                        required autocomplete="current-password"
                        placeholder="{{ __('main.auth_password_placeholder') }}">
                </div>
                @error('password') <p class="pw-form__error">{{ $message }}</p> @enderror
            </div>

            <div class="pw-form__group">
                <div class="pw-captcha-label">
                    <span class="pw-form__label" style="margin:0">{{ __('main.auth_captcha_label') }}</span>
                    <span class="pw-captcha__badge">CAPTCHA</span>
                </div>
                <div class="pw-captcha">
                    @php $captchaChars = \App\Services\CaptchaService::getChars(); @endphp
                    <div class="pw-captcha__visual" id="captcha-visual">
                        @foreach($captchaChars as $ch)
                        <span style="color:{{ $ch['color'] }};transform:rotate({{ $ch['deg'] }}deg)">{{ $ch['c'] }}</span>
                        @endforeach
                    </div>
                    <button type="button" class="pw-captcha__refresh" id="captcha-refresh" title="{{ __('main.auth_captcha_refresh') }}">
                        <svg viewBox="0 0 20 20" fill="none" width="14"><path d="M4 4a7 7 0 0112.14 3M16 16a7 7 0 01-12.14-3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M18 4l-2 3-2-3M2 16l2-3 2 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                </div>
                <input type="text" name="captcha" id="captcha-input"
                    class="pw-form__input {{ $errors->has('captcha') ? 'is-invalid' : '' }}"
                    placeholder="{{ __('main.auth_captcha_answer') }}"
                    required autocomplete="off" maxlength="6" minlength="6" spellcheck="false"
                    style="letter-spacing:.2em;text-transform:uppercase;margin-top:.5rem">
                @error('captcha') <p class="pw-form__error">{{ $message }}</p> @enderror
            </div>

            <div class="pw-auth__row">
                <label class="pw-auth__remember">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span>{{ __('main.auth_remember') }}</span>
                </label>
            </div>

            <button type="submit" class="pw-btn pw-btn--gold pw-btn--glow" style="width:100%;justify-content:center;">
                <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M3 10h14M13 6l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                {{ __('main.auth_login_btn') }}
            </button>
        </form>

        <div class="pw-auth__footer">
            <p><a href="{{ route('password.request') }}" class="pw-auth__forgot">{{ __('main.auth_forgot_password') }}</a></p>
            @if(config('pw-config.features.register', true))
            <p>{{ __('main.auth_no_account') }} <a href="{{ route('register') }}">{{ __('main.auth_register_link') }}</a></p>
            @endif
            <p><a href="{{ route('home') }}" class="pw-auth__back">{{ __('main.auth_back_home') }}</a></p>
        </div>
    </div>

    @include('auth._footer')
</div>
@endsection

@push('scripts')
<script>
(function() {
    const btn    = document.getElementById('captcha-refresh');
    const visual = document.getElementById('captcha-visual');
    const inp    = document.getElementById('captcha-input');
    if (!btn) return;

    function renderChars(chars) {
        visual.innerHTML = chars.map(ch =>
            `<span style="color:${ch.color};transform:rotate(${ch.deg}deg)">${ch.c}</span>`
        ).join('');
    }

    btn.addEventListener('click', () => {
        btn.classList.add('pw-captcha__refresh--loading');
        fetch('{{ route("captcha.refresh") }}')
            .then(r => r.json())
            .then(d => {
                renderChars(d.chars);
                inp.value = '';
                inp.focus();
            })
            .catch(() => {})
            .finally(() => btn.classList.remove('pw-captcha__refresh--loading'));
    });
})();
</script>
@endpush
