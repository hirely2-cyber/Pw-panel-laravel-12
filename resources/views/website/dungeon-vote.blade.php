@extends('layouts.app')

@php
    $__siteName = \App\Models\Setting::get('seo_title') ?: config('pw-config.server.name') ?: 'Perfect World';
@endphp

@section('title', 'Dungeon Voting — ' . $__siteName)
@section('meta_description', 'Vote dungeon favorit kamu di ' . $__siteName . ' dan lihat dungeon paling populer versi komunitas.')

@section('content')

{{-- ============================================================
     PAGE HERO
============================================================ --}}
<div class="pw-page-hero">
    <div class="pw-page-hero__bg" aria-hidden="true"></div>
    <canvas id="pw-sparkle" style="position:absolute;inset:0;width:100%;height:100%;pointer-events:none;z-index:0;" aria-hidden="true"></canvas>
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
            Dungeon Voting
        </h1>
        <p class="pw-page-hero__sub">Vote dungeon favorit kamu dan tentukan yang paling diminati komunitas</p>
        <nav class="pw-breadcrumb" aria-label="breadcrumb">
            <a href="{{ route('home') }}" class="pw-breadcrumb__item">
                <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true">
                    <path d="M2 7.5L8 2l6 5.5V14a1 1 0 01-1 1h-3.5v-3.5h-3V15H3a1 1 0 01-1-1V7.5z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/>
                </svg>
                Beranda
            </a>
            <span class="pw-breadcrumb__sep" aria-hidden="true">/</span>
            <span class="pw-breadcrumb__item pw-breadcrumb__item--active">Dungeon Voting</span>
        </nav>
    </div>
</div>

{{-- ============================================================
     VOTING SECTION
============================================================ --}}
<section class="pw-section" id="dungeon-vote">
    <div class="pw-section__inner pw-section__inner--narrow">

        @if($poll)
        {{-- === POLL AKTIF === --}}
        <div x-data="dungeonVote({{ $poll->id }}, '{{ $hasVoted ? 'voted' : 'pending' }}', '{{ $votedMapId }}')"
             x-init="init()"
             id="vote-app">

            <div style="text-align:center;margin-bottom:1.5rem;">
                <h2 style="font-size:1.2rem;font-weight:700;color:var(--pw-gold);">{{ $poll->title }}</h2>
                <p style="font-size:.85rem;color:var(--pw-text-muted);margin-top:.3rem;">
                    <span x-text="totalVotes"></span> total vote
                    <template x-if="state === 'voted'">
                        <span style="margin-left:.6rem;color:#5cb85c;font-size:.82rem;">✓ Kamu sudah vote</span>
                    </template>
                </p>
            </div>

            {{-- Notif --}}
            <template x-if="message">
                <div :class="success ? 'pw-alert pw-alert--success' : 'pw-alert pw-alert--danger'"
                     style="margin-bottom:1rem;text-align:center;" x-text="message"></div>
            </template>

            {{-- Kartu dungeon --}}
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1rem;">
                <template x-for="opt in options" :key="opt.map_id">
                    <div @click="doVote(opt.map_id)"
                         :class="getCardClass(opt.map_id)"
                         :style="getCardStyle(opt.map_id)">

                        {{-- Progress bar background --}}
                        <div style="position:absolute;left:0;top:0;height:100%;width:100%;pointer-events:none;">
                            <div style="height:100%;background:rgba(200,151,42,.13);transition:width .4s ease;"
                                 :style="'width:' + getBarPct(opt.votes) + '%'"></div>
                        </div>

                        <div style="position:relative;">
                            {{-- Badge winner --}}
                            <template x-if="isWinner(opt)">
                                <div style="margin-bottom:.5rem;">
                                    <span style="font-size:.72rem;background:#c8972a;color:#1a1005;padding:2px 8px;border-radius:999px;font-weight:700;">🏆 Teratas</span>
                                </div>
                            </template>

                            {{-- Map name --}}
                            <div style="font-weight:700;font-size:.95rem;margin-bottom:.75rem;line-height:1.3;"
                                 x-text="opt.map_name"></div>

                            {{-- Vote count + pct --}}
                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <span style="font-size:.85rem;" :style="isWinner(opt) ? 'color:#c8972a;font-weight:700;' : ''">
                                    <span x-text="opt.votes"></span> vote
                                </span>
                                <span style="font-size:.8rem;color:var(--pw-text-muted);"
                                      x-text="totalVotes > 0 ? Math.round(opt.votes / totalVotes * 100) + '%' : '0%'"></span>
                            </div>

                            {{-- Sudah voted this map --}}
                            <template x-if="votedMapId === opt.map_id">
                                <div style="margin-top:.6rem;">
                                    <span style="font-size:.75rem;color:#5cb85c;font-weight:600;background:rgba(92,184,92,.12);padding:3px 10px;border-radius:999px;border:1px solid rgba(92,184,92,.3);">✓ Pilihan kamu</span>
                                </div>
                            </template>

                            {{-- Vote button (tampil kalau belum vote) --}}
                            <template x-if="state === 'pending'">
                                <button @click.stop="doVote(opt.map_id)"
                                        :disabled="loading"
                                        class="pw-btn pw-btn--gold"
                                        style="margin-top:.9rem;width:100%;font-size:.83rem;"
                                        x-text="loading ? 'Loading...' : 'Vote'">
                                </button>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            <p style="font-size:.75rem;color:var(--pw-text-muted);text-align:center;margin-top:1.2rem;">
                1 vote per orang. Vote tidak bisa diubah setelah dikirim.
            </p>
        </div>

        @elseif($lastPoll)
        {{-- === HASIL POLL TERAKHIR (sudah ditutup) === --}}
        <div style="text-align:center;margin-bottom:1.5rem;">
            <div style="font-size:.85rem;color:var(--pw-text-muted);margin-bottom:.5rem;">Poll voting sedang tidak aktif.</div>
            <h2 style="font-size:1.1rem;font-weight:700;color:var(--pw-gold);">Hasil Terakhir: {{ $lastPoll->title }}</h2>
            <p style="font-size:.8rem;color:var(--pw-text-muted);">
                Ditutup {{ $lastPoll->closed_at->format('d M Y') }}
                · {{ $lastPoll->options->sum('votes') }} total vote
            </p>
        </div>

        @php $totalLast = $lastPoll->options->sum('votes'); $maxLast = $lastPoll->options->max('votes'); @endphp
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1rem;">
            @foreach($lastPoll->options as $opt)
            @php
                $pct = $totalLast > 0 ? round($opt->votes / $totalLast * 100) : 0;
                $isWin = $maxLast > 0 && $opt->votes === $maxLast && $opt->votes > 0;
            @endphp
            <div class="pw-card {{ $isWin ? 'pw-card--gold' : '' }}" style="overflow:hidden;">
                <div style="position:absolute;left:0;top:0;height:100%;width:{{ $pct }}%;background:rgba(200,151,42,.13);pointer-events:none;"></div>
                <div style="position:relative;">
                    @if($isWin)
                        <div style="margin-bottom:.5rem;">
                            <span style="font-size:.72rem;background:#c8972a;color:#1a1005;padding:2px 8px;border-radius:999px;font-weight:700;">🏆 Pemenang</span>
                        </div>
                    @endif
                    <div style="font-weight:700;font-size:.95rem;margin-bottom:.75rem;line-height:1.3;{{ $isWin ? 'color:#c8972a;' : '' }}">{{ $opt->map_name }}</div>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="font-size:.85rem;{{ $isWin ? 'color:#c8972a;font-weight:700;' : '' }}">{{ number_format($opt->votes) }} vote</span>
                        <span style="font-size:.8rem;color:var(--pw-text-muted);">{{ $pct }}%</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @else
        {{-- === TIDAK ADA POLL === --}}
        <div style="text-align:center;padding:3rem 1rem;color:var(--pw-text-muted);">
            <svg viewBox="0 0 64 64" fill="none" width="56" style="margin:0 auto 1rem;display:block;opacity:.4;">
                <path d="M32 4L4 18v28l28 14 28-14V18L32 4z" stroke="#c8972a" stroke-width="2.5" stroke-linejoin="round"/>
                <path d="M32 4v46M4 18l28 14 28-14" stroke="#c8972a" stroke-width="2" stroke-linejoin="round"/>
            </svg>
            <div style="font-size:1rem;font-weight:600;color:var(--pw-gold);margin-bottom:.4rem;">Belum ada voting aktif</div>
            <p style="font-size:.85rem;">Voting dungeon akan segera dibuka. Pantau terus!</p>
        </div>
        @endif

    </div>
</section>

@if($poll)
<script>
function dungeonVote(pollId, initialState, initialVotedMapId) {
    return {
        pollId: pollId,
        state: initialState,      // 'pending' | 'voted'
        votedMapId: initialVotedMapId || null,
        options: @json($poll->options->values()),
        totalVotes: {{ $poll->options->sum('votes') }},
        loading: false,
        message: null,
        success: true,

        init() {
            // Recalculate initial totalVotes
            this.totalVotes = this.options.reduce((s, o) => s + o.votes, 0);
        },

        getBarPct(votes) {
            const max = Math.max(...this.options.map(o => o.votes), 1);
            return Math.round(votes / max * 100);
        },

        isWinner(opt) {
            const max = Math.max(...this.options.map(o => o.votes));
            return max > 0 && opt.votes === max;
        },

        getCardClass(mapId) {
            const classes = ['pw-card'];
            if (this.state === 'pending') classes.push('vote-card-hover');
            if (this.votedMapId === mapId) classes.push('pw-card--gold');
            else if (this.state === 'voted' && this.isWinner(this.options.find(o => o.map_id === mapId))) classes.push('pw-card--gold');
            return classes.join(' ');
        },

        getCardStyle(mapId) {
            return 'overflow:hidden;cursor:' + (this.state === 'voted' ? 'default' : 'pointer') + ';transition:border-color .2s,transform .15s;';
        },

        async doVote(mapId) {
            if (this.state === 'voted' || this.loading) return;
            this.loading = true;
            this.message = null;

            try {
                const resp = await fetch('{{ route("dungeon-vote.submit") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ poll_id: this.pollId, map_id: mapId }),
                });
                const data = await resp.json();
                if (data.success) {
                    this.state = 'voted';
                    this.votedMapId = data.voted_map_id;
                    this.options = data.options;
                    this.totalVotes = data.total_votes;
                    this.success = true;
                    this.message = data.message;
                } else {
                    this.success = false;
                    this.message = data.message || 'Terjadi kesalahan.';
                }
            } catch (e) {
                this.success = false;
                this.message = 'Gagal terhubung ke server. Coba lagi.';
            } finally {
                this.loading = false;
            }
        }
    };
}
</script>
<style>
.vote-card-hover.pw-card:hover {
    border-color: var(--pw-border-gold, #c8972a) !important;
    transform: translateY(-2px);
}
</style>
@endif

@endsection
