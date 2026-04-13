@extends('layouts.app')

@php
    $__siteName = \App\Models\Setting::get('seo_title') ?: config('pw-config.server.name') ?: 'Perfect World';
@endphp

@section('title', ($event->localizedTitle() ?? 'Event') . ' — ' . $__siteName)
@section('meta_description', $event->localizedDescription() ?? 'Event launching server ' . config('pw-config.server.name'))

@section('content')

{{-- ============================================================
     PAGE HERO
============================================================ --}}
<div class="pw-page-hero">
    <div class="pw-page-hero__bg" aria-hidden="true"></div>
    <canvas id="pw-hero-confetti" style="position:absolute;inset:0;width:100%;height:100%;pointer-events:none;z-index:0;" aria-hidden="true"></canvas>
    <div class="pw-page-hero__inner" style="position:relative;z-index:1;">
        <div class="pw-page-hero__ornament" aria-hidden="true">
            <svg viewBox="0 0 160 20" fill="none" width="140">
                <line x1="0" y1="10" x2="55" y2="10" stroke="#c8972a" stroke-width="1"/>
                <path d="M65 3 L75 10 L65 17 L55 10 Z" fill="#c8972a" opacity=".5"/>
                <path d="M75 3 L85 10 L75 17 L65 10 Z" fill="#c8972a"/>
                <path d="M85 3 L95 10 L85 17 L75 10 Z" fill="#c8972a" opacity=".5"/>
                <line x1="95" y1="10" x2="150" y2="10" stroke="#c8972a" stroke-width="1"/>
            </svg>
        </div>
        <h1 style="font-family:'Cinzel',serif;font-size:clamp(1.8rem,4vw,2.8rem);font-weight:900;background:linear-gradient(135deg,#fbbf24 0%,#f59e0b 30%,#fcd34d 50%,#f59e0b 70%,#c8972a 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;line-height:1.1;filter:drop-shadow(0 2px 8px rgba(251,191,36,.3));margin:0;">
            {{ $event->localizedTitle() }}
        </h1>
        <p class="pw-page-hero__sub">{{ $event->localizedDescription() }}</p>
        <nav class="pw-breadcrumb" aria-label="breadcrumb">
            <a href="{{ route('home') }}" class="pw-breadcrumb__item">
                <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true">
                    <path d="M2 7.5L8 2l6 5.5V14a1 1 0 01-1 1h-3.5v-3.5h-3V15H3a1 1 0 01-1-1V7.5z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/>
                </svg>
                {{ __('main.breadcrumb_home') }}
            </a>
            <span class="pw-breadcrumb__sep" aria-hidden="true">/</span>
            <span class="pw-breadcrumb__item pw-breadcrumb__item--active">{{ __('main.nav_event') }}</span>
        </nav>
    </div>
</div>

{{-- ============================================================
     MAIN CONTENT
============================================================ --}}
<section class="pw-section" id="event" style="padding-top:.5rem;">
    <div class="pw-section__inner pw-section__inner--narrow">

        {{-- ── CONFETTI WRAPPER (covers showcase + podium) ── --}}
        <div style="position:relative;overflow:hidden;border-radius:14px;">
            <canvas id="pw-confetti" style="position:absolute;inset:0;width:100%;height:100%;pointer-events:none;z-index:0;"></canvas>

        {{-- ── PRIZE SHOWCASE + STATUS ── --}}
        <div style="position:relative;text-align:center;padding:2.5rem 1rem;background:radial-gradient(ellipse at center,rgba(200,151,42,.08) 0%,transparent 70%);">

            <div style="position:relative;z-index:1;">
                {{-- Label --}}
                <div style="font-size:.78rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.15em;margin-bottom:.3rem;">{{ __('main.event_total_prize') }}</div>

                {{-- Big IDR (main display) --}}
                <div style="font-size:clamp(2rem,6vw,3.5rem);font-weight:900;font-family:'Cinzel',serif;background:linear-gradient(135deg,#fbbf24 0%,#f59e0b 30%,#fcd34d 50%,#f59e0b 70%,#c8972a 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;line-height:1.1;filter:drop-shadow(0 2px 8px rgba(251,191,36,.3));">
                    Rp {{ number_format($event->prize_total_cubi * config('pw-config.currency.cubi_rate_idr', 1000), 0, ',', '.') }}
                </div>

                {{-- Explanation --}}
                <div style="font-size:clamp(.85rem,2.5vw,1.05rem);color:var(--pw-text-muted);margin-top:.5rem;line-height:1.5;">
                    {{ __('main.event_in_form_of') }} <span style="color:#fbbf24;font-weight:700;">{{ number_format($event->prize_total_cubi) }} {{ __('main.event_cubi_coin') }}</span> {{ __('main.event_in_cubi_shop') }}
                </div>

                {{-- Divider --}}
                <div style="width:60px;height:1px;background:linear-gradient(90deg,transparent,rgba(200,151,42,.4),transparent);margin:1rem auto;"></div>

                {{-- Status --}}
                <div style="font-size:.72rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.1em;margin-bottom:.3rem;">{{ __('main.event_status') }}</div>
                @if($event->status === 'active')
                    <div style="display:inline-flex;align-items:center;gap:.4rem;padding:.4rem 1.2rem;border-radius:20px;background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.25);">
                        <span style="width:8px;height:8px;border-radius:50%;background:#22c55e;display:inline-block;animation:pw-pulse-dot 1.5s infinite;"></span>
                        <span style="font-size:.85rem;font-weight:700;color:#22c55e;">{{ __('main.event_active') }}</span>
                    </div>
                @elseif($event->status === 'ended' || $event->status === 'distributed')
                    <div style="display:inline-flex;align-items:center;gap:.4rem;padding:.4rem 1.2rem;border-radius:20px;background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.25);">
                        <span style="font-size:.85rem;font-weight:700;color:#f59e0b;">{{ __('main.event_ended') }}</span>
                    </div>
                @else
                    <div style="display:inline-flex;align-items:center;gap:.4rem;padding:.4rem 1.2rem;border-radius:20px;background:rgba(200,151,42,.08);border:1px solid rgba(200,151,42,.2);">
                        <span style="font-size:.85rem;font-weight:700;color:var(--pw-gold);">{{ __('main.event_coming_soon') }}</span>
                    </div>
                @endif

                {{-- Periode + Qualified --}}
                <div style="margin-top:.6rem;font-size:.78rem;color:var(--pw-text-muted);">
                    {{ $event->start_at?->format('d M Y') }} — {{ $event->end_at?->format('d M Y') }}
                </div>
                <div style="margin-top:.3rem;font-size:.82rem;">
                    <span style="color:#22c55e;font-weight:700;">{{ $qualifiedCount }}</span>
                    <span style="color:var(--pw-text-muted);">/ {{ $event->prize_winner_count }} {{ __('main.event_qualified') }}</span>
                </div>
            </div>
        </div>

        {{-- ── PODIUM TOP 3 ── --}}
        <div class="pw-podium" style="position:relative;z-index:1;margin-top:0;padding-top:4px;">
            @php
                $podiumOrder = [1, 0, 2];
                $podiumRank  = [2, 1, 3];
                $podiumClass = ['pw-podium__step--silver', 'pw-podium__step--gold', 'pw-podium__step--bronze'];
                $rankColors  = ['#c0c0c0', '#ffd700', '#cd7f32'];
                $rankClass   = ['pw-rank--2', 'pw-rank--1', 'pw-rank--3'];
            @endphp
            @foreach($podiumOrder as $idx => $di)
            @php $p = $topThree[$di] ?? null; @endphp
            <div class="pw-podium__item {{ $podiumClass[$idx] }}">
                <div class="pw-podium__avatar" aria-hidden="true">
                    @php $iconSize = $podiumRank[$idx] === 1 ? 200 : 150; @endphp
                    <img src="{{ asset('images/gif_icon/1.gif') }}" alt="#{{ $podiumRank[$idx] }}" width="{{ $iconSize }}" height="{{ $iconSize }}" style="display:block;margin:0 auto;">
                </div>
                @if($p)
                <div class="pw-podium__name">{{ $p->character_name }}</div>
                <div class="pw-podium__sub">{{ $p->class }} &bull; Lv.{{ $p->level }}</div>
                <div class="pw-podium__level" style="color:{{ $rankColors[$idx] }}">{{ $p->cultivation_label }}</div>
                @if($p->qualified_at)
                <div style="font-size:.68rem;color:var(--pw-text-muted);margin-top:.2rem;">✓ {{ $p->qualified_at->format('d M Y H:i') }}</div>
                @endif
                @else
                <div class="pw-podium__name" style="color:var(--pw-text-muted);font-style:italic;">{{ __('main.event_empty_podium') }}</div>
                <div class="pw-podium__sub" style="opacity:.4;">{{ __('main.event_empty_sub') }}</div>
                @endif
                <div class="pw-podium__step-block">
                    <span class="pw-rank {{ $rankClass[$idx] }}">#{{ $podiumRank[$idx] }}</span>
                </div>
            </div>
            @endforeach
        </div>
        </div>{{-- end confetti wrapper --}}

        {{-- ── AUTO REFRESH INFO ── --}}
        <div style="text-align:center;margin:1.2rem 0 .8rem;display:flex;align-items:center;justify-content:center;gap:.5rem;">
            <svg viewBox="0 0 16 16" fill="none" width="14" style="color:#22c55e;">
                <path d="M14 8A6 6 0 114.8 3.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                <path d="M10 3.5H4.8V8.7" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span style="font-size:.78rem;color:var(--pw-text-muted);">{{ __('main.event_refresh_info') }} &bull; <a href="" style="color:#c8972a;text-decoration:underline;" onclick="event.preventDefault();location.reload();">{{ __('main.event_refresh_now') }}</a></span>
        </div>

        {{-- ── LEADERBOARD TABLE ── --}}
        <div class="pw-ranking__table-wrap">
            <table class="pw-ranking__table">
                <thead>
                    <tr>
                        <th style="text-align:center;width:50px;">#</th>
                        <th>{{ __('main.event_col_character') }}</th>
                        <th style="text-align:center;">{{ __('main.event_col_class') }}</th>
                        <th style="text-align:center;">{{ __('main.event_col_level') }}</th>
                        <th style="text-align:center;">{{ __('main.event_col_cultivation') }}</th>
                        <th style="text-align:center;">{{ __('main.event_col_regis_date') }}</th>
                        <th style="text-align:center;">{{ __('main.event_col_status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($participants as $rank => $p)
                    @php $isQualified = $p->qualified_at !== null; @endphp
                    <tr class="{{ $rank < 3 ? 'pw-ranking__top' : '' }}">
                        <td style="text-align:center;">
                            @if($rank < 3)
                                <span class="pw-rank pw-rank--{{ $rank + 1 }}">{{ $rank + 1 }}</span>
                            @else
                                <span class="pw-rank">{{ $rank + 1 }}</span>
                            @endif
                        </td>
                        <td>
                            <div style="font-weight:600;">{{ $p->character_name }}</div>
                        </td>
                        <td style="text-align:center;font-size:.82rem;color:var(--pw-text-muted);">
                            {{ $p->class }}
                        </td>
                        <td style="text-align:center;font-weight:700;{{ $p->level >= $event->req_level ? 'color:#22c55e;' : '' }}">
                            {{ $p->level }}
                        </td>
                        <td style="text-align:center;font-size:.85rem;{{ $isQualified ? 'color:#22c55e;font-weight:600;' : 'color:var(--pw-text-muted);' }}">
                            {{ $p->cultivation_label ?? 'Inchoation' }}
                        </td>
                        <td style="text-align:center;font-size:.75rem;color:var(--pw-text-muted);">
                            {{ $p->created_at?->format('d M Y H:i') ?? '—' }}
                        </td>
                        <td style="text-align:center;">
                            @if($isQualified)
                                <span style="display:inline-flex;align-items:center;gap:.3rem;font-size:.75rem;color:#22c55e;font-weight:600;">
                                    <svg viewBox="0 0 16 16" fill="none" width="12"><path d="M3 8.5l3 3 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    {{ __('main.event_status_qualified') }}
                                </span>
                                @if($p->prize_distributed)
                                <div style="font-size:.65rem;color:#38bdf8;">🏆 {{ __('main.event_status_rewarded') }}</div>
                                @endif
                            @else
                                <span style="font-size:.75rem;color:var(--pw-text-muted);">
                                    @php
                                        $progress = 0;
                                        if ($event->req_level > 0) {
                                            $levelProgress = min(100, ($p->level / $event->req_level) * 100);
                                            $progress = $levelProgress;
                                        }
                                    @endphp
                                    <div style="width:60px;height:5px;background:rgba(255,255,255,.08);border-radius:3px;margin:0 auto;">
                                        <div style="width:{{ $progress }}%;height:100%;background:linear-gradient(90deg,#c8972a,#fbbf24);border-radius:3px;"></div>
                                    </div>
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="pw-table__empty">
                            {{ __('main.event_no_participants') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($participants->hasPages())
        <div style="margin-top:1rem;display:flex;justify-content:center;">
            {{ $participants->links() }}
        </div>
        @endif

        {{-- ── SYARAT & KETENTUAN (below table) ── --}}
        <div class="pw-card" style="margin-top:1.5rem;padding:0;" x-data="{ open: false }">
            <button type="button" @click="open = !open" class="pw-event-tnc-btn"
                    style="width:100%;display:flex;align-items:center;justify-content:space-between;gap:.5rem;padding:1.2rem 1.5rem;background:none;border:none;cursor:pointer;text-align:left;">
                <div style="display:flex;align-items:center;gap:.5rem;">
                    <svg viewBox="0 0 20 20" fill="none" width="20" style="flex-shrink:0;"><path d="M4 3h12a1 1 0 011 1v12a1 1 0 01-1 1H4a1 1 0 01-1-1V4a1 1 0 011-1z" stroke="#c8972a" stroke-width="1.3"/><path d="M7 7h6M7 10h6M7 13h4" stroke="#c8972a" stroke-width="1.3" stroke-linecap="round"/></svg>
                    <span style="font-size:1.05rem;font-weight:700;color:#fff;">{{ __('main.event_tnc_title') }}</span>
                </div>
                <svg viewBox="0 0 16 16" fill="none" width="14" style="flex-shrink:0;transition:transform .2s;" :style="open ? 'transform:rotate(180deg)' : ''"><path d="M4 6l4 4 4-4" stroke="var(--pw-gold-light)" stroke-width="1.5" stroke-linecap="round"/></svg>
            </button>

            <div x-show="open" x-collapse x-cloak style="padding:0 1.5rem 1.2rem;">
            <div class="pw-event-tnc-body" style="font-size:.88rem;color:#e0e0e0;line-height:1.8;text-align:left;">

                {{-- Informasi Event --}}
                <div class="pw-tnc-section-title" style="font-weight:700;color:var(--pw-gold-light);margin-bottom:.4rem;font-size:.9rem;">{{ __('main.event_info_title') }}</div>
                <ul style="list-style:none;padding:0;margin:0 0 1.3rem;">
                    <li style="padding:.35rem 0;padding-left:1.4rem;position:relative;">
                        <span style="position:absolute;left:0;color:var(--pw-gold);">&#9679;</span>
                        <strong style="color:var(--pw-gold-light);">{{ __('main.event_info_status') }}:</strong>
                        @if($event->status === 'active')
                            <span style="color:#22c55e;">{{ __('main.event_active') }}</span>
                        @elseif($event->status === 'ended' || $event->status === 'distributed')
                            <span style="color:#f59e0b;">{{ __('main.event_ended') }}</span>
                        @else
                            <span style="color:var(--pw-text-muted);">{{ __('main.event_coming_soon') }}</span>
                        @endif
                    </li>
                    <li style="padding:.35rem 0;padding-left:1.4rem;position:relative;">
                        <span style="position:absolute;left:0;color:var(--pw-gold);">&#9679;</span>
                        <strong style="color:var(--pw-gold-light);">{{ __('main.event_info_period') }}:</strong> {{ $event->start_at?->format('d M Y') }} {{ __('main.event_period_to') }} {{ $event->end_at?->format('d M Y') }}
                    </li>
                    <li style="padding:.35rem 0;padding-left:1.4rem;position:relative;">
                        <span style="position:absolute;left:0;color:var(--pw-gold);">&#9679;</span>
                        <strong style="color:var(--pw-gold-light);">{{ __('main.event_info_total_prize') }}:</strong> <span style="color:#fbbf24;font-weight:700;">{{ number_format($event->prize_total_cubi) }} Cubi Gold</span>
                        <span style="color:var(--pw-text-muted);">(≈ Rp {{ number_format($event->prize_total_cubi * config('pw-config.currency.cubi_rate_idr', 1000), 0, ',', '.') }})</span>
                    </li>
                    <li style="padding:.35rem 0;padding-left:1.4rem;position:relative;">
                        <span style="position:absolute;left:0;color:var(--pw-gold);">&#9679;</span>
                        <strong style="color:var(--pw-gold-light);">{{ __('main.event_info_winners') }}:</strong> {{ $event->prize_winner_count }} {{ __('main.event_info_fastest') }}
                    </li>
                    <li style="padding:.35rem 0;padding-left:1.4rem;position:relative;">
                        <span style="position:absolute;left:0;color:var(--pw-gold);">&#9679;</span>
                        <strong style="color:var(--pw-gold-light);">{{ __('main.event_info_qualified_now') }}:</strong> <span style="color:#22c55e;font-weight:700;">{{ $qualifiedCount }}</span> / {{ $event->prize_winner_count }}
                    </li>
                </ul>

                {{-- Syarat --}}
                <div class="pw-tnc-section-title" style="font-weight:700;color:var(--pw-gold-light);margin-bottom:.4rem;font-size:.9rem;">{{ __('main.event_req_title') }}</div>
                <ul style="list-style:none;padding:0;margin:0 0 1.3rem;">
                    <li style="padding:.35rem 0;padding-left:1.4rem;position:relative;">
                        <span style="position:absolute;left:0;color:#22c55e;">&#10003;</span>
                        {{ __('main.event_req_level') }} <strong style="color:var(--pw-gold-light);">Level {{ $event->req_level }}</strong> {{ __('main.event_req_and_cultivation') }} <strong style="color:var(--pw-gold-light);">{{ \App\Models\LaunchEvent::CULTIVATION_MAP[$event->req_cultivation] ?? '' }}</strong>
                    </li>
                    <li style="padding:.35rem 0;padding-left:1.4rem;position:relative;">
                        <span style="position:absolute;left:0;color:#22c55e;">&#10003;</span>
                        {{ __('main.event_req_both_paths') }}
                    </li>
                    <li style="padding:.35rem 0;padding-left:1.4rem;position:relative;">
                        <span style="position:absolute;left:0;color:#22c55e;">&#10003;</span>
                        {{ __('main.event_req_order') }}
                    </li>
                </ul>

                {{-- Hadiah --}}
                <div class="pw-tnc-section-title" style="font-weight:700;color:var(--pw-gold-light);margin-bottom:.4rem;font-size:.9rem;">{{ __('main.event_prize_title') }}</div>
                <ul style="list-style:none;padding:0;margin:0 0 1.3rem;">
                    @if($event->hasTieredPrizes())
                    <li style="padding:.35rem 0;padding-left:1.4rem;position:relative;">
                        <span style="position:absolute;left:0;color:#ffd700;">🥇</span>
                        <strong style="color:#fbbf24;">{{ __('main.event_prize_rank1') }}</strong> &mdash; {{ number_format($event->prize_rank1) }} Cubi Gold
                    </li>
                    <li style="padding:.35rem 0;padding-left:1.4rem;position:relative;">
                        <span style="position:absolute;left:0;color:#c0c0c0;">🥈</span>
                        <strong style="color:#c0c0c0;">{{ __('main.event_prize_rank2') }}</strong> &mdash; {{ number_format($event->prize_rank2) }} Cubi Gold
                    </li>
                    <li style="padding:.35rem 0;padding-left:1.4rem;position:relative;">
                        <span style="position:absolute;left:0;color:#cd7f32;">🥉</span>
                        <strong style="color:#cd7f32;">{{ __('main.event_prize_rank3') }}</strong> &mdash; {{ number_format($event->prize_rank3) }} Cubi Gold
                    </li>
                    <li style="padding:.35rem 0;padding-left:1.4rem;position:relative;">
                        <span style="position:absolute;left:0;color:var(--pw-gold);">&#9679;</span>
                        <strong style="color:var(--pw-gold-light);">{{ __('main.event_prize_rank_rest') }} {{ $event->prize_winner_count }}</strong> &mdash; {{ __('main.event_prize_each') }} {{ number_format($event->prizeForRank(4)) }} Cubi Gold
                    </li>
                    @else
                    <li style="padding:.35rem 0;padding-left:1.4rem;position:relative;">
                        <span style="position:absolute;left:0;color:var(--pw-gold);">&#9679;</span>
                        {{ __('main.event_prize_equal') }} <strong style="color:#fbbf24;">{{ number_format($event->prizePerWinner()) }} Cubi Gold</strong>
                    </li>
                    @endif
                </ul>

                {{-- Ketentuan --}}
                <div class="pw-tnc-section-title" style="font-weight:700;color:var(--pw-gold-light);margin-bottom:.4rem;font-size:.9rem;">{{ __('main.event_other_title') }}</div>
                <ul style="list-style:none;padding:0;margin:0;">
                    <li style="padding:.35rem 0;padding-left:1.4rem;position:relative;">
                        <span style="position:absolute;left:0;color:var(--pw-gold);">&#9679;</span>
                        {{ __('main.event_other_auto_dist') }}
                    </li>
                    <li style="padding:.35rem 0;padding-left:1.4rem;position:relative;">
                        <span style="position:absolute;left:0;color:var(--pw-gold);">&#9679;</span>
                        {{ __('main.event_other_sync') }}
                    </li>
                    <li style="padding:.35rem 0;padding-left:1.4rem;position:relative;">
                        <span style="position:absolute;left:0;color:var(--pw-gold);">&#9679;</span>
                        {{ __('main.event_other_final') }}
                    </li>
                </ul>

            </div>
            </div>
        </div>

    </div>
</section>

@endsection

@push('styles')
<style>
@keyframes pw-pulse-dot {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: .5; transform: scale(1.4); }
}

/* ── Light mode: Syarat & Ketentuan collapsible ── */
[data-theme="light"] .pw-event-tnc-btn span {
    color: var(--pw-text) !important;
}
[data-theme="light"] .pw-event-tnc-body {
    color: var(--pw-text) !important;
}
[data-theme="light"] .pw-event-tnc-body li {
    color: var(--pw-text) !important;
}
[data-theme="light"] .pw-event-tnc-body li strong {
    color: var(--pw-gold-darker) !important;
}
[data-theme="light"] .pw-tnc-section-title {
    color: var(--pw-gold-darker) !important;
}
</style>
@endpush

@push('scripts')
<script>
(function () {
    const canvas = document.getElementById('pw-confetti');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    let W, H, particles = [];

    const COLORS = ['#fbbf24','#f59e0b','#fcd34d','#c8972a','#e8b84b','#fff7c2','#d4a437'];

    function resize() {
        W = canvas.width = canvas.offsetWidth;
        H = canvas.height = canvas.offsetHeight;
    }

    function makeParticle() {
        return {
            x: Math.random() * W,
            y: Math.random() * H - H,
            r: Math.random() * 4 + 2,
            d: Math.random() * 80 + 20,
            color: COLORS[Math.floor(Math.random() * COLORS.length)],
            tilt: Math.random() * 10 - 5,
            tiltAngle: Math.random() * Math.PI,
            tiltSpeed: Math.random() * 0.04 + 0.02,
            speed: Math.random() * 1 + 0.5,
            opacity: Math.random() * 0.6 + 0.4,
            shape: Math.floor(Math.random() * 3)
        };
    }

    function init() {
        resize();
        particles = [];
        for (let i = 0; i < 50; i++) {
            const p = makeParticle();
            p.y = Math.random() * H;
            particles.push(p);
        }
    }

    function draw() {
        ctx.clearRect(0, 0, W, H);
        particles.forEach(p => {
            ctx.save();
            ctx.globalAlpha = p.opacity;
            ctx.fillStyle = p.color;
            ctx.translate(p.x, p.y);
            ctx.rotate(p.tiltAngle);
            if (p.shape === 0) {
                ctx.beginPath();
                ctx.arc(0, 0, p.r, 0, Math.PI * 2);
                ctx.fill();
            } else if (p.shape === 1) {
                ctx.fillRect(-p.r, -p.r / 2, p.r * 2, p.r);
            } else {
                ctx.strokeStyle = p.color;
                ctx.lineWidth = 1.5;
                ctx.beginPath();
                ctx.moveTo(0, -p.r);
                ctx.lineTo(0, p.r);
                ctx.stroke();
            }
            ctx.restore();
        });
    }

    function update() {
        particles.forEach(p => {
            p.y += p.speed;
            p.x += Math.sin(p.d) * 0.3;
            p.tiltAngle += p.tiltSpeed;
            if (p.y > H + 10) {
                p.y = -10;
                p.x = Math.random() * W;
            }
        });
    }

    function loop() {
        draw();
        update();
        requestAnimationFrame(loop);
    }

    window.addEventListener('resize', resize);
    init();
    loop();
})();

// Hero confetti (same effect on hero section)
(function () {
    const canvas = document.getElementById('pw-hero-confetti');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    let W, H, particles = [];
    const COLORS = ['#fbbf24','#f59e0b','#fcd34d','#c8972a','#e8b84b','#fff7c2','#d4a437'];

    function resize() { W = canvas.width = canvas.offsetWidth; H = canvas.height = canvas.offsetHeight; }
    function makeP() {
        return { x: Math.random()*W, y: Math.random()*H-H, r: Math.random()*3+1.5, d: Math.random()*80+20,
            color: COLORS[Math.floor(Math.random()*COLORS.length)], tilt: Math.random()*10-5,
            tiltAngle: Math.random()*Math.PI, tiltSpeed: Math.random()*0.04+0.02,
            speed: Math.random()*0.8+0.3, opacity: Math.random()*0.5+0.3, shape: Math.floor(Math.random()*3) };
    }
    function init() { resize(); particles=[]; for(let i=0;i<35;i++){const p=makeP();p.y=Math.random()*H;particles.push(p);} }
    function draw() {
        ctx.clearRect(0,0,W,H);
        particles.forEach(p=>{ctx.save();ctx.globalAlpha=p.opacity;ctx.fillStyle=p.color;ctx.translate(p.x,p.y);ctx.rotate(p.tiltAngle);
            if(p.shape===0){ctx.beginPath();ctx.arc(0,0,p.r,0,Math.PI*2);ctx.fill();}
            else if(p.shape===1){ctx.fillRect(-p.r,-p.r/2,p.r*2,p.r);}
            else{ctx.strokeStyle=p.color;ctx.lineWidth=1.2;ctx.beginPath();ctx.moveTo(0,-p.r);ctx.lineTo(0,p.r);ctx.stroke();}
            ctx.restore();});
    }
    function update(){particles.forEach(p=>{p.y+=p.speed;p.x+=Math.sin(p.d)*0.2;p.tiltAngle+=p.tiltSpeed;if(p.y>H+10){p.y=-10;p.x=Math.random()*W;}});}
    function loop(){draw();update();requestAnimationFrame(loop);}
    window.addEventListener('resize',resize);
    init(); loop();
})();
</script>
@endpush
