@extends('layouts.admin')
@section('title', 'Detail Event: ' . $event->title)

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;flex-wrap:wrap;gap:.5rem;">
    <a href="{{ route('admin.events.index') }}" class="pw-adm-btn pw-adm-btn--ghost pw-adm-btn--sm">← Kembali</a>
    <div style="display:flex;gap:.4rem;">
        <a href="{{ route('admin.events.edit', $event) }}" class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--ghost">Edit</a>
        @if($event->status === 'draft')
        <form method="POST" action="{{ route('admin.events.toggle', $event) }}" style="display:inline;"
              data-confirm="Aktifkan Event|Aktifkan event '{{ $event->title }}'?"
              data-confirm-variant="success"
              data-confirm-ok="Ya, Aktifkan">
            @csrf
            <button type="submit" class="pw-adm-btn pw-adm-btn--sm">Start Event</button>
        </form>
        @elseif($event->status === 'active')
        <form method="POST" action="{{ route('admin.events.toggle', $event) }}" style="display:inline;"
              data-confirm="Akhiri Event|Akhiri event '{{ $event->title }}'?"
              data-confirm-variant="danger"
              data-confirm-ok="Ya, Akhiri">
            @csrf
            <button type="submit" class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--danger">End Event</button>
        </form>
        @elseif($event->status === 'ended')
        <form method="POST" action="{{ route('admin.events.distribute', $event) }}" style="display:inline;"
              data-confirm="Distribute Hadiah|Distribusikan hadiah Cubi Gold ke {{ $event->prize_winner_count }} pemenang?"
              data-confirm-variant="success"
              data-confirm-ok="Ya, Distribute">
            @csrf
            <button type="submit" class="pw-adm-btn pw-adm-btn--sm" style="background:#38bdf8;color:#0a0a0f;">Distribute Hadiah</button>
        </form>
        @endif
    </div>
</div>

{{-- Event Info --}}
<div class="pw-adm-card" style="margin-bottom:1rem;">
    <div class="pw-adm-card__title">{{ $event->title }}</div>
    @if($event->description)
    <p style="color:var(--pw-text-muted);font-size:.85rem;margin-bottom:1rem;">{{ $event->description }}</p>
    @endif

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:1rem;">
        <div>
            <div style="font-size:.72rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.06em;">Status</div>
            <div style="margin-top:.2rem;">
                @if($event->status === 'active') <span class="pw-badge pw-badge--success">Aktif</span>
                @elseif($event->status === 'ended') <span class="pw-badge pw-badge--warning">Berakhir</span>
                @elseif($event->status === 'distributed') <span class="pw-badge pw-badge--info" style="background:rgba(56,189,248,.15);color:#38bdf8;">Distributed</span>
                @else <span class="pw-badge">Draft</span>
                @endif
            </div>
        </div>
        <div>
            <div style="font-size:.72rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.06em;">Syarat</div>
            <div style="margin-top:.2rem;font-weight:600;">Lv.{{ $event->req_level }} + Cultiv {{ $event->req_cultivation }}</div>
        </div>
        <div>
            <div style="font-size:.72rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.06em;">Hadiah Total</div>
            <div style="margin-top:.2rem;font-weight:600;color:var(--pw-gold);">{{ number_format($event->prize_total_cubi) }} Cubi</div>
        </div>
        <div>
            <div style="font-size:.72rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.06em;">Hadiah Per Rank</div>
            @if($event->hasTieredPrizes())
            <div style="margin-top:.2rem;font-size:.82rem;line-height:1.6;">
                <span style="font-weight:600;">🥇 {{ number_format($event->prize_rank1) }}</span> &bull;
                <span style="font-weight:600;">🥈 {{ number_format($event->prize_rank2) }}</span> &bull;
                <span style="font-weight:600;">🥉 {{ number_format($event->prize_rank3) }}</span><br>
                <span style="color:var(--pw-text-muted);font-size:.75rem;">Lainnya: {{ number_format($event->prizeForRank(4)) }} Cubi/orang</span>
            </div>
            @else
            <div style="margin-top:.2rem;font-weight:600;">{{ number_format($event->prizePerWinner()) }} Cubi</div>
            @endif
        </div>
        <div>
            <div style="font-size:.72rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.06em;">Qualified</div>
            <div style="margin-top:.2rem;font-weight:600;">{{ $qualifiedCount }} / {{ $event->prize_winner_count }}</div>
        </div>
        <div>
            <div style="font-size:.72rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.06em;">Total Peserta</div>
            <div style="margin-top:.2rem;font-weight:600;">{{ $totalParticipants }}</div>
        </div>
        <div>
            <div style="font-size:.72rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.06em;">Periode</div>
            <div style="margin-top:.2rem;font-size:.82rem;">{{ $event->start_at?->format('d M Y H:i') }} — {{ $event->end_at?->format('d M Y H:i') }}</div>
        </div>
    </div>
</div>

{{-- Participants Table --}}
<div class="pw-adm-card">
    <div class="pw-adm-card__title">Peserta ({{ $totalParticipants }})</div>
    <div class="pw-table-wrap">
        <table class="pw-table">
            <thead>
                <tr>
                    <th style="text-align:center;width:50px;">#</th>
                    <th>Character</th>
                    <th>Class</th>
                    <th style="text-align:center;">Level</th>
                    <th style="text-align:center;">Cultivation</th>
                    <th style="text-align:center;">Status</th>
                    <th style="text-align:center;">Qualified</th>
                    <th style="text-align:center;">Hadiah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($participants as $i => $p)
                <tr style="{{ $p->qualified_at ? 'background:rgba(34,197,94,.06);' : '' }}">
                    <td style="text-align:center;">{{ $participants->firstItem() + $i }}</td>
                    <td style="font-weight:600;">{{ $p->character_name }}</td>
                    <td style="font-size:.82rem;color:var(--pw-text-muted);">{{ $p->class }}</td>
                    <td style="text-align:center;font-weight:600;{{ $p->level >= $event->req_level ? 'color:#22c55e;' : '' }}">
                        {{ $p->level }}
                    </td>
                    <td style="text-align:center;font-size:.82rem;{{ $event->meetsCultivation($p->cultivation) ? 'color:#22c55e;font-weight:600;' : 'color:var(--pw-text-muted);' }}">
                        {{ $p->cultivation_label ?? $p->cultivation }}
                    </td>
                    <td style="text-align:center;">
                        @if($p->qualified_at)
                            <span class="pw-badge pw-badge--success" style="font-size:.7rem;">Qualified</span>
                        @else
                            <span class="pw-badge" style="font-size:.7rem;">Progress</span>
                        @endif
                    </td>
                    <td style="text-align:center;font-size:.78rem;color:var(--pw-text-muted);">
                        {{ $p->qualified_at?->format('d M Y H:i') ?? '—' }}
                    </td>
                    <td style="text-align:center;">
                        @if($p->prize_distributed)
                            <span class="pw-badge pw-badge--success" style="font-size:.7rem;">Sent</span>
                        @else
                            <span style="color:var(--pw-text-muted);">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top:1rem;">{{ $participants->links() }}</div>
</div>
@endsection
