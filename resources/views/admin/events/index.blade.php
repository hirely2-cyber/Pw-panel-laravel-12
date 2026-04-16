@extends('layouts.admin')
@section('title', 'Manajemen Event')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.2rem;flex-wrap:wrap;gap:.8rem;">
    <h1 style="font-size:1.2rem;font-weight:700;margin:0;">Manajemen Event</h1>
    <a href="{{ route('admin.events.create') }}?type={{ $tab }}" class="pw-adm-btn">+ Buat Event Baru</a>
</div>

{{-- Tab Navigation --}}
<div style="display:flex;gap:.5rem;margin-bottom:1.5rem;border-bottom:2px solid rgba(200,151,42,.15);padding-bottom:0;">
    <a href="{{ route('admin.events.index', ['tab' => 'pre_launch']) }}"
       style="padding:.6rem 1.2rem;font-size:.85rem;font-weight:700;border-radius:8px 8px 0 0;text-decoration:none;transition:all .2s;
       {{ $tab === 'pre_launch' ? 'background:rgba(200,151,42,.15);color:#c8972a;border:1px solid rgba(200,151,42,.3);border-bottom:2px solid #c8972a;' : 'background:transparent;color:var(--pw-text-muted);border:1px solid transparent;' }}">
        🚀 Pre-Launching
    </a>
    <a href="{{ route('admin.events.index', ['tab' => 'grand_launch']) }}"
       style="padding:.6rem 1.2rem;font-size:.85rem;font-weight:700;border-radius:8px 8px 0 0;text-decoration:none;transition:all .2s;
       {{ $tab === 'grand_launch' ? 'background:rgba(200,151,42,.15);color:#c8972a;border:1px solid rgba(200,151,42,.3);border-bottom:2px solid #c8972a;' : 'background:transparent;color:var(--pw-text-muted);border:1px solid transparent;' }}">
        🏆 Grand Launching
    </a>
</div>

@if($events->isEmpty())
<div class="pw-adm-card" style="text-align:center;padding:2rem;color:var(--pw-text-muted);">
    Belum ada event {{ $tab === 'pre_launch' ? 'Pre-Launching' : 'Grand Launching' }}. Klik tombol di atas untuk membuat event baru.
</div>
@else
<div class="pw-table-wrap">
    <table class="pw-table">
        <thead>
            <tr>
                <th>Event</th>
                @if($tab === 'pre_launch')
                <th style="text-align:center;">Syarat Level</th>
                <th style="text-align:center;">Referral Tiers</th>
                <th style="text-align:center;">Registrasi</th>
                @else
                <th style="text-align:center;">Syarat</th>
                <th style="text-align:center;">Hadiah</th>
                <th style="text-align:center;">Pemenang</th>
                @endif
                <th style="text-align:center;">Periode</th>
                <th style="text-align:center;">Status</th>
                <th style="text-align:center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $event)
            <tr>
                <td>
                    <a href="{{ route('admin.events.show', $event) }}" style="font-weight:600;color:var(--pw-gold);">{{ $event->title }}</a>
                </td>

                @if($tab === 'pre_launch')
                <td style="text-align:center;font-size:.82rem;">
                    Lv.{{ $event->referral_req_level }}
                </td>
                <td style="text-align:center;font-size:.78rem;">
                    @foreach($event->referral_tiers ?? [] as $tier)
                    <div>{{ $tier['count'] }} orang → {{ number_format($tier['reward']) }} Cubi</div>
                    @endforeach
                </td>
                <td style="text-align:center;">
                    @php $regCount = \App\Models\User::whereBetween('creatime', [$event->start_at, $event->end_at])->count(); @endphp
                    <span style="font-weight:600;">{{ $regCount }}</span>
                    <span style="color:var(--pw-text-muted);font-size:.75rem;">user</span>
                </td>
                @else
                <td style="text-align:center;font-size:.82rem;">
                    {{ number_format($event->prize_total_cubi) }} Cubi
                    @if($event->hasTieredPrizes())
                    <div style="color:var(--pw-text-muted);font-size:.72rem;line-height:1.5;">
                        🥇{{ number_format($event->prize_rank1) }} 🥈{{ number_format($event->prize_rank2) }} 🥉{{ number_format($event->prize_rank3) }}<br>
                        Lainnya: {{ number_format($event->prizeForRank(4)) }}/orang
                    </div>
                    @else
                    <div style="color:var(--pw-text-muted);font-size:.75rem;">
                        ({{ number_format($event->prizePerWinner()) }}/orang)
                    </div>
                    @endif
                </td>
                <td style="text-align:center;">
                    @php $qCount = $event->participants()->whereNotNull('qualified_at')->count(); @endphp
                    <span style="font-weight:600;">{{ $qCount }}</span>
                    <span style="color:var(--pw-text-muted);font-size:.75rem;">/ {{ $event->prize_winner_count }}</span>
                </td>
                @endif

                <td style="text-align:center;font-size:.78rem;color:var(--pw-text-muted);">
                    {{ $event->start_at?->format('d M Y') }}<br>
                    — {{ $event->end_at?->format('d M Y') }}
                </td>
                <td style="text-align:center;">
                    @if($event->status === 'active')
                        <span class="pw-badge pw-badge--success">Aktif</span>
                    @elseif($event->status === 'ended')
                        <span class="pw-badge pw-badge--warning">Berakhir</span>
                    @elseif($event->status === 'distributed')
                        <span class="pw-badge pw-badge--info" style="background:rgba(56,189,248,.15);color:#38bdf8;">Distributed</span>
                    @else
                        <span class="pw-badge">Draft</span>
                    @endif
                </td>
                <td style="text-align:center;">
                    <div style="display:flex;gap:.4rem;justify-content:center;flex-wrap:wrap;">
                        <a href="{{ route('admin.events.show', $event) }}" class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--ghost">Detail</a>

                        @if($event->status === 'draft')
                        <form method="POST" action="{{ route('admin.events.toggle', $event) }}" style="display:inline;"
                              data-confirm="Aktifkan Event|Aktifkan event '{{ $event->title }}'?"
                              data-confirm-variant="success"
                              data-confirm-ok="Ya, Aktifkan">
                            @csrf
                            <button type="submit" class="pw-adm-btn pw-adm-btn--sm">Start</button>
                        </form>
                        @elseif($event->status === 'active')
                        <form method="POST" action="{{ route('admin.events.toggle', $event) }}" style="display:inline;"
                              data-confirm="Akhiri Event|Akhiri event '{{ $event->title }}'?"
                              data-confirm-variant="danger"
                              data-confirm-ok="Ya, Akhiri">
                            @csrf
                            <button type="submit" class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--danger">End</button>
                        </form>
                        @elseif($event->status === 'ended')
                        @if($event->isPreLaunch())
                        <form method="POST" action="{{ route('admin.events.distribute-referrals', $event) }}" style="display:inline;"
                              data-confirm="Distribute Referral Rewards|Distribusikan hadiah referral ke semua yang memenuhi syarat?"
                              data-confirm-variant="success"
                              data-confirm-ok="Ya, Distribute">
                            @csrf
                            <button type="submit" class="pw-adm-btn pw-adm-btn--sm" style="background:#38bdf8;color:#0a0a0f;">Distribute</button>
                        </form>
                        @else
                        <form method="POST" action="{{ route('admin.events.distribute', $event) }}" style="display:inline;"
                              data-confirm="Distribute Hadiah|Distribusikan hadiah ke pemenang event '{{ $event->title }}'?"
                              data-confirm-variant="success"
                              data-confirm-ok="Ya, Distribute">
                            @csrf
                            <button type="submit" class="pw-adm-btn pw-adm-btn--sm" style="background:#38bdf8;color:#0a0a0f;">Distribute</button>
                        </form>
                        @endif
                        @endif

                        @if($event->status === 'draft')
                        <form method="POST" action="{{ route('admin.events.destroy', $event) }}" style="display:inline;"
                              data-confirm="Hapus Event|Hapus event '{{ $event->title }}'? Data tidak bisa dikembalikan."
                              data-confirm-variant="danger"
                              data-confirm-ok="Ya, Hapus">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--danger">Hapus</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection
