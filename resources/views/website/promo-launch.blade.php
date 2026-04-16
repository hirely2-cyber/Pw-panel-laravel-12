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

        <div class="pw-landing-hero__badge">🔥 PRE-REGISTER EVENT</div>

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
        <p class="pw-landing-hero__note">✅ Gratis selamanya &bull; ⚡ Langsung main saat launch</p>
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
                <div class="pw-landing-tier__icon">🥉</div>
                <div class="pw-landing-tier__badge">Bronze</div>
                <h3 class="pw-landing-tier__title">Register</h3>
                <p class="pw-landing-tier__desc">Daftar akun gratis</p>
                <ul class="pw-landing-tier__rewards">
                    <li>💰 500.000 Gold</li>
                    <li>💎 50 Cubi</li>
                    <li>📦 Starter Pack Box</li>
                </ul>
                <a href="{{ route('register') }}" class="pw-landing-tier__btn">Daftar Sekarang</a>
            </div>

            {{-- SILVER --}}
            <div class="pw-landing-tier pw-landing-tier--silver">
                <div class="pw-landing-tier__icon">🥈</div>
                <div class="pw-landing-tier__badge">Silver</div>
                <h3 class="pw-landing-tier__title">Share & Follow</h3>
                <p class="pw-landing-tier__desc">Share ke social media & join Discord</p>
                <ul class="pw-landing-tier__rewards">
                    <li>🐴 Exclusive Mount</li>
                    <li>💎 100 Cubi</li>
                    <li>⚔️ Weapon Charm (7 hari)</li>
                </ul>
                @php $discord = \App\Models\Setting::get('social_discord'); @endphp
                <a href="{{ $discord ?: 'https://discord.gg/7xCWyB2NFy' }}" target="_blank" rel="noopener" class="pw-landing-tier__btn">Join Discord</a>
            </div>

            {{-- GOLD --}}
            <div class="pw-landing-tier pw-landing-tier--gold">
                <div class="pw-landing-tier__popular">⭐ BEST REWARD</div>
                <div class="pw-landing-tier__icon">🥇</div>
                <div class="pw-landing-tier__badge">Gold</div>
                <h3 class="pw-landing-tier__title">Invite 3 Teman</h3>
                <p class="pw-landing-tier__desc">Ajak 3 teman register pakai referral code kamu</p>
                <ul class="pw-landing-tier__rewards">
                    <li>👗 Exclusive Fashion Set</li>
                    <li>💎 300 Cubi</li>
                    <li>🏆 Title "Founding Player"</li>
                    <li>🎁 Mystery Box Legendary</li>
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
                <div class="pw-landing-feature__icon">⚡</div>
                <h3>Server Stabil</h3>
                <p>Uptime 99.9%, anti lag, anti rollback. Bermain nyaman tanpa gangguan.</p>
            </div>
            <div class="pw-landing-feature">
                <div class="pw-landing-feature__icon">⚔️</div>
                <h3>Balanced Gameplay</h3>
                <p>Rate yang seimbang, PvP fair, dan update rutin dari tim developer.</p>
            </div>
            <div class="pw-landing-feature">
                <div class="pw-landing-feature__icon">🎁</div>
                <h3>Event Seru</h3>
                <p>Event mingguan dengan hadiah menarik. Territory War, PK Tournament & lebih banyak lagi!</p>
            </div>
            <div class="pw-landing-feature">
                <div class="pw-landing-feature__icon">👥</div>
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
