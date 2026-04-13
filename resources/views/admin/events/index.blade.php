@extends('layouts.admin')
@section('title', 'Manajemen Event')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.2rem;flex-wrap:wrap;gap:.8rem;">
    <h1 style="font-size:1.2rem;font-weight:700;margin:0;">Manajemen Event</h1>
    <a href="{{ route('admin.events.create') }}" class="pw-adm-btn">+ Buat Event Baru</a>
</div>

@if($events->isEmpty())
<div class="pw-adm-card" style="text-align:center;padding:2rem;color:var(--pw-text-muted);">
    Belum ada event. Klik tombol di atas untuk membuat event baru.
</div>
@else
<div class="pw-table-wrap">
    <table class="pw-table">
        <thead>
            <tr>
                <th>Event</th>
                <th style="text-align:center;">Syarat</th>
                <th style="text-align:center;">Hadiah</th>
                <th style="text-align:center;">Pemenang</th>
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
                <td style="text-align:center;font-size:.82rem;">
                    Lv.{{ $event->req_level }} &bull; Cultiv {{ $event->req_cultivation }}
                </td>
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
                        <form method="POST" action="{{ route('admin.events.distribute', $event) }}" style="display:inline;"
                              data-confirm="Distribute Hadiah|Distribusikan hadiah ke pemenang event '{{ $event->title }}'?"
                              data-confirm-variant="success"
                              data-confirm-ok="Ya, Distribute">
                            @csrf
                            <button type="submit" class="pw-adm-btn pw-adm-btn--sm" style="background:#38bdf8;color:#0a0a0f;">Distribute</button>
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
