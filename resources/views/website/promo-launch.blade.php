@extends('layouts.app')

@php
    $__siteName = \App\Models\Setting::get('seo_title') ?: config('pw-config.server.name') ?: 'Perfect World';
    $__heroLogo = \App\Models\Setting::get('site_logo');
    $__heroBg   = \App\Models\Setting::get('site_hero_bg');
@endphp

@section('title', 'Pre-Register & Claim Exclusive Rewards — ' . $__siteName)
@section('meta_description', 'Daftar sekarang di ' . $__siteName . '! Dapatkan hadiah eksklusif untuk pemain baru — mount langka, fashion set, gold & cubi gratis.')

@section('content')

{{-- ============================================================
     LANDING HERO
============================================================ --}}
<section class="pw-landing-hero">
    <div class="pw-landing-hero__bg" aria-hidden="true">
        @if($__heroBg)
            <img src="{{ Storage::url($__heroBg) }}" alt="" loading="eager" fetchpriority="high">
        @endif
    </div>
    <div class="pw-landing-hero__overlay" aria-hidden="true"></div>
    <canvas id="landing-embers" aria-hidden="true"></canvas>

    <div class="pw-landing-hero__content">
        @if($__heroLogo)
            <img src="{{ Storage::url($__heroLogo) }}" alt="{{ $__siteName }}" class="pw-landing-hero__logo">
        @endif

        <div class="pw-landing-hero__badge">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="vertical-align:-2px;"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
            PRE-REGISTER EVENT
        </div>

        <h1 class="pw-landing-hero__title">
            Daftar Sekarang &<br>Klaim Hadiah Eksklusif!
        </h1>
        <p class="pw-landing-hero__sub">
            Jadilah bagian dari petualangan epik di <strong>{{ $__siteName }}</strong>.<br>
            Pre-register sekarang dan dapatkan reward spesial saat server launch!
        </p>

        {{-- COUNTDOWN --}}
        <div class="pw-landing-countdown" x-data="landingCountdown()" x-init="start()">
            <div class="pw-landing-countdown__label">Server Launch In</div>
            <div class="pw-landing-countdown__boxes">
                <div class="pw-landing-countdown__box">
                    <span class="pw-landing-countdown__num" x-text="days">00</span>
                    <span class="pw-landing-countdown__unit">Hari</span>
                </div>
                <div class="pw-landing-countdown__sep">:</div>
                <div class="pw-landing-countdown__box">
                    <span class="pw-landing-countdown__num" x-text="hours">00</span>
                    <span class="pw-landing-countdown__unit">Jam</span>
                </div>
                <div class="pw-landing-countdown__sep">:</div>
                <div class="pw-landing-countdown__box">
                    <span class="pw-landing-countdown__num" x-text="minutes">00</span>
                    <span class="pw-landing-countdown__unit">Menit</span>
                </div>
                <div class="pw-landing-countdown__sep">:</div>
                <div class="pw-landing-countdown__box">
                    <span class="pw-landing-countdown__num" x-text="seconds">00</span>
                    <span class="pw-landing-countdown__unit">Detik</span>
                </div>
            </div>
        </div>

        <a href="{{ route('register') }}" class="pw-landing-cta">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4-4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
            DAFTAR SEKARANG — GRATIS!
        </a>
        <p class="pw-landing-hero__note">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="#4ade80" style="vertical-align:-2px;"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
            Gratis selamanya &bull;
            <svg width="14" height="14" viewBox="0 0 24 24" fill="#fbbf24" style="vertical-align:-2px;"><path d="M11 21h-1l1-7H7.5c-.88 0-.33-.75-.31-.78C8.48 10.94 10.42 7.54 13.01 3h1l-1 7h3.51c.4 0 .62.19.4.66C12.97 17.55 11 21 11 21z"/></svg>
            Langsung main saat launch
        </p>
    </div>
</section>

{{-- ============================================================
     REWARD TIERS
============================================================ --}}
<section class="pw-landing-rewards" id="rewards">
    <div class="pw-landing-section-inner">
        <h2 class="pw-landing-section-title">
            <svg viewBox="0 0 160 20" fill="none" width="120" style="display:block;margin:0 auto .8rem;">
                <line x1="0" y1="10" x2="55" y2="10" stroke="#c8972a" stroke-width="1"/>
                <path d="M65 3 L75 10 L65 17 L55 10 Z" fill="#c8972a" opacity=".5"/>
                <path d="M75 3 L85 10 L75 17 L65 10 Z" fill="#c8972a"/>
                <path d="M85 3 L95 10 L85 17 L75 10 Z" fill="#c8972a" opacity=".5"/>
                <line x1="95" y1="10" x2="150" y2="10" stroke="#c8972a" stroke-width="1"/>
            </svg>
            Hadiah Pre-Register
        </h2>
        <p class="pw-landing-section-sub">Semakin aktif, semakin besar hadiahnya!</p>

        <div class="pw-landing-tiers">
            {{-- BRONZE --}}
            <div class="pw-landing-tier pw-landing-tier--bronze">
                <div class="pw-landing-tier__icon">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#cd7f32" stroke-width="1.5"><circle cx="12" cy="8" r="6"/><path d="M8.21 13.89L7 23l5-3 5 3-1.21-9.12"/><path d="M12 2v2M9 4l1.5 2M15 4l-1.5 2"/></svg>
                </div>
                <div class="pw-landing-tier__badge">Bronze</div>
                <h3 class="pw-landing-tier__title">Register</h3>
                <p class="pw-landing-tier__desc">Daftar akun gratis</p>
                <ul class="pw-landing-tier__rewards">
                    <li>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="#c8972a" style="vertical-align:-2px;"><path d="M20 7H4c-1.1 0-2 .9-2 2v6c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V9c0-1.1-.9-2-2-2zm0 8H4V9h16v6zm-8-1c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3z"/></svg>
                        50 Cubi Gold
                    </li>
                    <li>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="#c8972a" style="vertical-align:-2px;"><path d="M21 5H3a1 1 0 00-1 1v12a1 1 0 001 1h18a1 1 0 001-1V6a1 1 0 00-1-1zm-1 12H4V7h16v10zm-8-1a4 4 0 100-8 4 4 0 000 8z"/></svg>
                        Mystery Box Bronze x1
                    </li>
                    <li>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="#c8972a" style="vertical-align:-2px;"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                        Altar Eksklusif Bronze
                    </li>
                </ul>
                <a href="{{ route('register') }}" class="pw-landing-tier__btn">Daftar Sekarang</a>
            </div>

            {{-- SILVER --}}
            <div class="pw-landing-tier pw-landing-tier--silver">
                <div class="pw-landing-tier__icon">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#c0c0c0" stroke-width="1.5"><circle cx="12" cy="8" r="6"/><path d="M8.21 13.89L7 23l5-3 5 3-1.21-9.12"/><path d="M12 2v2M9 4l1.5 2M15 4l-1.5 2"/></svg>
                </div>
                <div class="pw-landing-tier__badge">Silver</div>
                <h3 class="pw-landing-tier__title">Share & Follow</h3>
                <p class="pw-landing-tier__desc">Share ke social media & join Discord</p>
                <ul class="pw-landing-tier__rewards">
                    <li>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="#c8972a" style="vertical-align:-2px;"><path d="M20 7H4c-1.1 0-2 .9-2 2v6c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V9c0-1.1-.9-2-2-2zm0 8H4V9h16v6zm-8-1c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3z"/></svg>
                        100 Cubi Gold
                    </li>
                    <li>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="#c8972a" style="vertical-align:-2px;"><path d="M21 5H3a1 1 0 00-1 1v12a1 1 0 001 1h18a1 1 0 001-1V6a1 1 0 00-1-1zm-1 12H4V7h16v10zm-8-1a4 4 0 100-8 4 4 0 000 8z"/></svg>
                        Mystery Box Silver x1
                    </li>
                    <li>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="#c8972a" style="vertical-align:-2px;"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                        Altar Eksklusif Silver
                    </li>
                </ul>
                @php $discord = \App\Models\Setting::get('social_discord'); @endphp
                <a href="{{ $discord ?: 'https://discord.gg/7xCWyB2NFy' }}" target="_blank" rel="noopener" class="pw-landing-tier__btn">Join Discord</a>
            </div>

            {{-- GOLD --}}
            <div class="pw-landing-tier pw-landing-tier--gold">
                <div class="pw-landing-tier__popular">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" style="vertical-align:-2px;"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    BEST REWARD
                </div>
                <div class="pw-landing-tier__icon">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="1.5"><circle cx="12" cy="8" r="6"/><path d="M8.21 13.89L7 23l5-3 5 3-1.21-9.12"/><path d="M12 2v2M9 4l1.5 2M15 4l-1.5 2"/></svg>
                </div>
                <div class="pw-landing-tier__badge">Gold</div>
                <h3 class="pw-landing-tier__title">Invite 3 Teman</h3>
                <p class="pw-landing-tier__desc">Ajak 3 teman register pakai referral code kamu</p>
                <ul class="pw-landing-tier__rewards">
                    <li>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="#c8972a" style="vertical-align:-2px;"><path d="M20 7H4c-1.1 0-2 .9-2 2v6c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V9c0-1.1-.9-2-2-2zm0 8H4V9h16v6zm-8-1c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3z"/></svg>
                        300 Cubi Gold
                    </li>
                    <li>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="#c8972a" style="vertical-align:-2px;"><path d="M21 5H3a1 1 0 00-1 1v12a1 1 0 001 1h18a1 1 0 001-1V6a1 1 0 00-1-1zm-1 12H4V7h16v10zm-8-1a4 4 0 100-8 4 4 0 000 8z"/></svg>
                        Mystery Box Gold x1
                    </li>
                    <li>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="#c8972a" style="vertical-align:-2px;"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                        Altar Eksklusif Gold
                    </li>
                </ul>
                <a href="{{ route('register') }}" class="pw-landing-tier__btn">Daftar & Invite</a>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
     WHY JOIN
============================================================ --}}
<section class="pw-landing-features">
    <div class="pw-landing-section-inner">
        <h2 class="pw-landing-section-title">Kenapa {{ $__siteName }}?</h2>
        <div class="pw-landing-features__grid">
            <div class="pw-landing-feature">
                <div class="pw-landing-feature__icon"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
                <h3>GM Tidak Ikut Campur</h3>
                <p>GM tidak akan campur tangan urusan dalam game. Tidak ada donasi equip — semua equipment didapat murni dari bermain.</p>
            </div>
            <div class="pw-landing-feature">
                <div class="pw-landing-feature__icon"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 17.5L3 6V3h3l11.5 11.5"/><path d="M13 19l6-6"/><path d="M16 16l4 4"/><path d="M19 21l2-2"/></svg></div>
                <h3>PvP & PvE Seimbang</h3>
                <p>Gameplay balance antara PvP dan PvE agar tidak membosankan. Semua class punya peran penting!</p>
            </div>
            <div class="pw-landing-feature">
                <div class="pw-landing-feature__icon"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="12" rx="2"/><path d="M12 12h.01"/><path d="M17 12h.01"/><path d="M7 10v4"/><path d="M5 12h4"/></svg></div>
                <h3>Semua Item Dari Game</h3>
                <p>Seluruh item bisa didapatkan dari dalam game. Donasi hanya untuk item penunjang & kosmetik langka — bukan core gameplay.</p>
            </div>
            <div class="pw-landing-feature">
                <div class="pw-landing-feature__icon"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg></div>
                <h3>Server Stabil</h3>
                <p>Uptime 99.9%, anti lag, anti rollback. Bermain nyaman tanpa gangguan.</p>
            </div>
            <div class="pw-landing-feature">
                <div class="pw-landing-feature__icon"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="8" width="18" height="4" rx="1"/><path d="M12 8v13"/><path d="M19 12v7a2 2 0 01-2 2H7a2 2 0 01-2-2v-7"/><path d="M7.5 8a2.5 2.5 0 010-5C9 3 12 8 12 8"/><path d="M16.5 8a2.5 2.5 0 000-5C15 3 12 8 12 8"/></svg></div>
                <h3>Event Seru</h3>
                <p>Event mingguan dengan hadiah menarik. Territory War, PK Tournament & lebih banyak lagi!</p>
            </div>
            <div class="pw-landing-feature">
                <div class="pw-landing-feature__icon"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4-4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg></div>
                <h3>Komunitas Aktif</h3>
                <p>Ribuan pemain aktif, guild war seru, dan komunitas Discord yang ramah.</p>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
     FINAL CTA
============================================================ --}}
<section class="pw-landing-final-cta">
    <div class="pw-landing-section-inner" style="text-align:center;">
        <h2 class="pw-landing-section-title" style="margin-bottom:.5rem;">Jangan Sampai Ketinggalan!</h2>
        <p class="pw-landing-section-sub" style="max-width:500px;margin:0 auto 2rem;">Slot pre-register terbatas. Daftar sekarang dan jadilah yang pertama merasakan petualangan epik!</p>
        <a href="{{ route('register') }}" class="pw-landing-cta pw-landing-cta--large">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4-4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
            DAFTAR SEKARANG — GRATIS!
        </a>
    </div>
</section>

@endsection

@push('scripts')
<script>
function landingCountdown() {
    return {
        days: '00', hours: '00', minutes: '00', seconds: '00',
        // ← GANTI TANGGAL LAUNCH DI SINI
        target: new Date('2026-05-01T12:00:00+07:00'),
        start() {
            this.tick();
            setInterval(() => this.tick(), 1000);
        },
        tick() {
            const diff = Math.max(0, this.target - Date.now());
            this.days    = String(Math.floor(diff / 86400000)).padStart(2, '0');
            this.hours   = String(Math.floor((diff % 86400000) / 3600000)).padStart(2, '0');
            this.minutes = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0');
            this.seconds = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');
        }
    };
}

// Ember particles (reuse from home)
(function(){
    const c = document.getElementById('landing-embers');
    if (!c) return;
    const ctx = c.getContext('2d');
    let W, H, embers = [];
    function resize() { W = c.width = c.offsetWidth; H = c.height = c.offsetHeight; }
    resize(); window.addEventListener('resize', resize);
    function Ember() {
        this.x = Math.random() * W;
        this.y = H + 10;
        this.r = Math.random() * 2 + 1;
        this.speed = Math.random() * 1.5 + .5;
        this.drift = (Math.random() - .5) * .8;
        this.alpha = Math.random() * .6 + .4;
    }
    for (let i = 0; i < 30; i++) { const e = new Ember(); e.y = Math.random() * H; embers.push(e); }
    (function draw() {
        ctx.clearRect(0, 0, W, H);
        embers.forEach(e => {
            e.y -= e.speed; e.x += e.drift;
            if (e.y < -10) { Object.assign(e, new Ember()); }
            ctx.beginPath();
            ctx.arc(e.x, e.y, e.r, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(200,151,42,${e.alpha})`;
            ctx.fill();
        });
        requestAnimationFrame(draw);
    })();
})();
</script>
@endpush
