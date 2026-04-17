@extends('layouts.app')
@section('title', __("main.nav_register"))

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

    <div class="pw-register">
        <span class="pw-auth__corner pw-auth__corner--tl"></span>
        <span class="pw-auth__corner pw-auth__corner--tr"></span>
        <span class="pw-auth__corner pw-auth__corner--bl"></span>
        <span class="pw-auth__corner pw-auth__corner--br"></span>

        {{-- LEFT: Form --}}
        <div class="pw-register__form">

            <div class="pw-auth__brand" style="margin-bottom:1.25rem;">
                <div>
                    <div class="pw-auth__title" style="text-align:left;font-size:1.15rem;margin:0;">{{ $__siteName }}</div>
                    <div style="font-size:.78rem;color:var(--pw-text-muted);">{{ __('main.auth_register_subtitle') }}</div>
                </div>
            </div>

            @if($errors->any())
            <div class="pw-auth__alert pw-auth__alert--error" style="margin-bottom:.8rem;">
                <svg viewBox="0 0 20 20" fill="none" width="15"><circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.5"/><path d="M10 6v4M10 13v.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <input type="hidden" name="referral_code" value="{{ request('ref') }}">

                {{-- Row 1: Nickname + Username --}}
                <div class="pw-register__row">
                    <div class="pw-form__group">
                        <label for="truename" class="pw-form__label">{{ __('main.auth_truename') }}</label>
                        <div class="pw-form__input-wrap">
                            <svg class="pw-form__ico" viewBox="0 0 20 20" fill="none" width="16"><path d="M4 17v-1a4 4 0 014-4h4a4 4 0 014 4v1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><circle cx="10" cy="6" r="3" stroke="currentColor" stroke-width="1.5"/></svg>
                            <input type="text" id="truename" name="truename"
                                class="pw-form__input pw-form__input--icon {{ $errors->has('truename') ? 'is-invalid' : '' }}"
                                value="{{ old('truename') }}" required autocomplete="name"
                                placeholder="{{ __('main.auth_truename_placeholder') }}" autofocus>
                        </div>
                        @error('truename') <p class="pw-form__error">{{ $message }}</p> @enderror
                    </div>
                    <div class="pw-form__group">
                        <label for="name" class="pw-form__label">{{ __('main.auth_username') }}</label>
                        <div class="pw-form__input-wrap">
                            <svg class="pw-form__ico" viewBox="0 0 20 20" fill="none" width="16"><circle cx="10" cy="7" r="4" stroke="currentColor" stroke-width="1.5"/><path d="M3 17c0-3.314 3.134-6 7-6s7 2.686 7 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                            <input type="text" id="name" name="name"
                                class="pw-form__input pw-form__input--icon {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                value="{{ old('name') }}" required autocomplete="username"
                                placeholder="{{ __('main.auth_username_reg_placeholder') }}">
                        </div>
                        @error('name') <p class="pw-form__error">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Row 2: Email + Telepon --}}
                <div class="pw-register__row">
                    <div class="pw-form__group">
                        <label for="email" class="pw-form__label">{{ __('main.auth_email') }}</label>
                        <div class="pw-form__input-wrap">
                            <svg class="pw-form__ico" viewBox="0 0 20 20" fill="none" width="16"><path d="M3 5h14a1 1 0 011 1v8a1 1 0 01-1 1H3a1 1 0 01-1-1V6a1 1 0 011-1z" stroke="currentColor" stroke-width="1.5"/><path d="M2 6l8 6 8-6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                            <input type="email" id="email" name="email"
                                class="pw-form__input pw-form__input--icon {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                value="{{ old('email') }}" required autocomplete="email"
                                placeholder="{{ __('main.auth_email_placeholder') }}">
                        </div>
                        @error('email') <p class="pw-form__error">{{ $message }}</p> @enderror
                    </div>
                    <div class="pw-form__group">
                        <label for="phonenumber" class="pw-form__label">{{ __('main.auth_phone') }}</label>
                        <div class="pw-form__input-wrap">
                            <svg class="pw-form__ico" viewBox="0 0 20 20" fill="none" width="16"><rect x="5" y="2" width="10" height="16" rx="2" stroke="currentColor" stroke-width="1.5"/><circle cx="10" cy="15" r="1" fill="currentColor"/></svg>
                            <input type="tel" id="phonenumber" name="phonenumber"
                                class="pw-form__input pw-form__input--icon {{ $errors->has('phonenumber') ? 'is-invalid' : '' }}"
                                value="{{ old('phonenumber') }}" required autocomplete="tel"
                                placeholder="{{ __('main.auth_phone_placeholder') }}">
                        </div>
                        @error('phonenumber') <p class="pw-form__error">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Row 3: Password + Konfirmasi --}}
                <div class="pw-register__row">
                    <div class="pw-form__group">
                        <label for="password" class="pw-form__label">{{ __('main.auth_password') }}</label>
                        <div class="pw-form__input-wrap">
                            <svg class="pw-form__ico" viewBox="0 0 20 20" fill="none" width="16"><rect x="4" y="9" width="12" height="9" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M7 9V6a3 3 0 016 0v3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                            <input type="password" id="password" name="password"
                                class="pw-form__input pw-form__input--icon {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                required autocomplete="new-password" placeholder="{{ __('main.auth_password_reg_placeholder') }}">
                        </div>
                        @error('password') <p class="pw-form__error">{{ $message }}</p> @enderror
                    </div>
                    <div class="pw-form__group">
                        <label for="password_confirmation" class="pw-form__label">{{ __('main.auth_password_confirm') }}</label>
                        <div class="pw-form__input-wrap">
                            <svg class="pw-form__ico" viewBox="0 0 20 20" fill="none" width="16"><rect x="4" y="9" width="12" height="9" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M7 9V6a3 3 0 016 0v3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="pw-form__input pw-form__input--icon" required
                                autocomplete="new-password" placeholder="{{ __('main.auth_password_confirm_placeholder') }}">
                        </div>
                    </div>
                </div>

                {{-- Row 4: PIN + CAPTCHA --}}
                <div class="pw-register__row">
                    <div class="pw-form__group">
                        <label for="pin" class="pw-form__label">{{ __('main.auth_pin') }}</label>
                        <div class="pw-form__input-wrap">
                            <svg class="pw-form__ico" viewBox="0 0 20 20" fill="none" width="16"><path d="M10 2v6M6 6l4 2 4-2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><rect x="3" y="8" width="14" height="10" rx="2" stroke="currentColor" stroke-width="1.5"/><circle cx="10" cy="13" r="1.5" fill="currentColor"/></svg>
                            <input type="password" id="pin" name="pin"
                                class="pw-form__input pw-form__input--icon {{ $errors->has('pin') ? 'is-invalid' : '' }}"
                                value="{{ old('pin') }}" required autocomplete="off"
                                maxlength="6" minlength="4" inputmode="numeric"
                                placeholder="{{ __('main.auth_pin_placeholder') }}">
                        </div>
                        @error('pin') <p class="pw-form__error">{{ $message }}</p> @enderror
                        <p class="pw-form__hint">{{ __('main.auth_pin_hint') }}</p>
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
                            style="letter-spacing:.2em;text-transform:uppercase;margin-top:.4rem">
                        @error('captcha') <p class="pw-form__error">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Terms + Submit --}}
                <div style="border-top:1px solid rgba(255,255,255,.06);margin:.6rem 0 .8rem;"></div>

                <div class="pw-form__group" style="margin-bottom:.6rem;">
                    <label style="display:flex;align-items:flex-start;gap:.5rem;cursor:pointer;font-size:.78rem;color:var(--pw-text-muted);line-height:1.5;">
                        <input type="checkbox" name="terms" required style="accent-color:var(--pw-gold);margin-top:2px;flex-shrink:0;"
                            {{ old('terms') ? 'checked' : '' }}>
                        <span>
                            @if(app()->getLocale() == 'id')
                                Saya menyetujui <a href="{{ route('tos') }}" target="_blank" rel="noopener" style="color:var(--pw-gold);text-decoration:none;">syarat & ketentuan</a> server dan bertanggung jawab penuh atas akun yang didaftarkan.
                            @else
                                I agree to the server <a href="{{ route('tos') }}" target="_blank" rel="noopener" style="color:var(--pw-gold);text-decoration:none;">terms & conditions</a> and take full responsibility for the registered account.
                            @endif
                        </span>
                    </label>
                    @error('terms') <p class="pw-form__error">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="pw-btn pw-btn--gold pw-btn--glow" style="width:100%;justify-content:center;">
                    <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M10 3v14M3 10h14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    {{ __('main.auth_register_btn') }}
                </button>
            </form>

            {{-- IP & Timezone Info --}}
            <div style="margin-top:.9rem;padding:.65rem .85rem;background:rgba(166,107,66,.08);border:1px solid rgba(166,107,66,.2);border-radius:8px;display:flex;flex-direction:column;gap:.3rem;text-align:center;">
                <div style="display:flex;flex-direction:column;align-items:center;gap:.15rem;">
                    <span style="font-size:.72rem;color:rgba(196,157,109,.6);letter-spacing:.04em;text-transform:uppercase;font-weight:600;">
                        {{ app()->getLocale() === 'id' ? 'IP Anda' : 'Your IP' }}
                    </span>
                    <span style="font-size:.82rem;font-weight:700;color:#d4a860;font-family:monospace;letter-spacing:.06em;">
                        {{ request()->ip() }}
                    </span>
                </div>
                <div style="width:100%;height:1px;background:rgba(166,107,66,.15);"></div>
                <div style="display:flex;flex-direction:column;align-items:center;gap:.15rem;">
                    <span style="font-size:.72rem;color:rgba(196,157,109,.6);letter-spacing:.04em;text-transform:uppercase;font-weight:600;">
                        Timezone
                    </span>
                    <span id="pw-reg-tz" style="font-size:.82rem;font-weight:600;color:rgba(212,168,96,.85);font-family:monospace;">
                        {{ date_default_timezone_get() }}
                    </span>
                </div>
            </div>
            <script>
            (function(){
                var el = document.getElementById('pw-reg-tz');
                if (!el) return;
                try {
                    var tz = Intl.DateTimeFormat().resolvedOptions().timeZone;
                    if (tz) el.textContent = tz;
                } catch(e) {}
            })();
            </script>

            <div class="pw-auth__footer" style="margin-top:1rem;">
                <p>{{ __('main.auth_has_account') }} <a href="{{ route('login') }}">{{ __('main.auth_login_link') }}</a></p>
            </div>
        </div>

        {{-- RIGHT: Info Sidebar --}}
        <div class="pw-register__info">

            {{-- Informasi --}}
            <div>
                <p class="pw-register__info-title">
                    <svg viewBox="0 0 20 20" fill="none" width="15"><circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.3"/><path d="M10 9v5M10 6.5v.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                    {{ __('main.register_info_section') }}
                </p>
                <ul class="pw-register__info-list">
                    <li>{!! __('main.register_info_instant') !!}</li>
                    <li>{!! __('main.register_info_immutable') !!}</li>
                    <li>{!! __('main.register_info_memorable') !!}</li>
                </ul>
            </div>

            <div class="pw-register__info-divider"></div>

            {{-- Penjelasan Field --}}
            <div>
                <p class="pw-register__info-title">
                    <svg viewBox="0 0 20 20" fill="none" width="15"><path d="M4 5h12M4 10h12M4 15h8" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                    {{ __('main.register_explain_section') }}
                </p>
                <div class="pw-register__info-desc">
                    <p>{!! __('main.register_explain_truename') !!}</p>
                    <p>{!! __('main.register_explain_username') !!}</p>
                    <p>{!! __('main.register_explain_pin') !!}</p>
                    <p>{!! __('main.register_explain_password') !!}</p>
                </div>
            </div>

            <div class="pw-register__info-divider"></div>

            {{-- Back to Home --}}
            <div style="margin-top:auto;text-align:center;">
                <a href="{{ route('home') }}" style="font-size:.78rem;color:var(--pw-text-muted);text-decoration:none;">{{ __('main.auth_back_home') }}</a>
            </div>
        </div>


    </div>

    @include('auth._footer')
</div>
@endsection

@push('scripts')
<script>
(function() {
    var btn    = document.getElementById('captcha-refresh');
    var visual = document.getElementById('captcha-visual');
    var inp    = document.getElementById('captcha-input');
    if (!btn) return;

    function renderChars(chars) {
        visual.innerHTML = chars.map(function(ch) {
            return '<span style="color:' + ch.color + ';transform:rotate(' + ch.deg + 'deg)">' + ch.c + '</span>';
        }).join('');
    }

    btn.addEventListener('click', function() {
        btn.classList.add('pw-captcha__refresh--loading');
        fetch('{{ route("captcha.refresh") }}')
            .then(function(r) { return r.json(); })
            .then(function(data) {
                renderChars(data.chars);
                inp.value = '';
                inp.focus();
            })
            .finally(function() { btn.classList.remove('pw-captcha__refresh--loading'); });
    });
})();
</script>
@endpush
