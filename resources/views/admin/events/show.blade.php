@extends('layouts.admin')
@section('title', 'Detail Event: ' . $event->title)

@section('content')

{{-- Header --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.2rem;flex-wrap:wrap;gap:.6rem;">
    <div style="display:flex;align-items:center;gap:.6rem;">
        <a href="{{ route('admin.events.index') }}" class="pw-adm-btn pw-adm-btn--ghost pw-adm-btn--sm">← Kembali</a>
        <h1 style="font-size:1.05rem;font-weight:700;color:var(--pw-text-light);margin:0;">{{ $event->title }}</h1>
        @if($event->status === 'active') <span class="pw-badge pw-badge--success">Aktif</span>
        @elseif($event->status === 'ended') <span class="pw-badge pw-badge--warning">Berakhir</span>
        @elseif($event->status === 'distributed') <span class="pw-badge" style="background:rgba(56,189,248,.15);color:#38bdf8;">Distributed</span>
        @else <span class="pw-badge">Draft</span>
        @endif
    </div>
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

{{-- Stat Cards Row --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:.75rem;margin-bottom:.75rem;">
    <div class="pw-adm-card" style="padding:.8rem 1rem;margin-bottom:0;">
        <div style="font-size:.62rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.3rem;">Syarat Level</div>
        <div style="font-size:1.15rem;font-weight:700;color:var(--pw-text-light);">Lv. {{ $event->req_level }}</div>
    </div>
    <div class="pw-adm-card" style="padding:.8rem 1rem;margin-bottom:0;">
        <div style="font-size:.62rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.3rem;">Syarat Cultivation</div>
        <div style="font-size:1.15rem;font-weight:700;color:var(--pw-text-light);">{{ \App\Models\LaunchEvent::CULTIVATION_MAP[$event->req_cultivation] ?? 'Lv.'.$event->req_cultivation }}</div>
    </div>
    <div class="pw-adm-card" style="padding:.8rem 1rem;margin-bottom:0;">
        <div style="font-size:.62rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.3rem;">Qualified / Target</div>
        <div style="font-size:1.15rem;font-weight:700;color:#22c55e;">{{ $qualifiedCount }} <span style="color:var(--pw-text-muted);font-weight:400;">/ {{ $event->prize_winner_count }}</span></div>
    </div>
    <div class="pw-adm-card" style="padding:.8rem 1rem;margin-bottom:0;">
        <div style="font-size:.62rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.3rem;">Total Peserta</div>
        <div style="font-size:1.15rem;font-weight:700;color:var(--pw-text-light);">{{ $totalParticipants }}</div>
    </div>
</div>

{{-- Info Row: Prize + Period --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;margin-bottom:1.2rem;">
    <div class="pw-adm-card" style="padding:.8rem 1rem;margin-bottom:0;">
        <div style="font-size:.62rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.5rem;">Hadiah Cubi Gold</div>
        <div style="font-size:1.15rem;font-weight:700;color:var(--pw-gold);margin-bottom:.5rem;">{{ number_format($event->prize_total_cubi) }} Cubi</div>
        @if($event->hasTieredPrizes())
        <div style="display:flex;gap:1rem;font-size:.82rem;">
            <span>🥇 <strong>{{ number_format($event->prize_rank1) }}</strong></span>
            <span>🥈 <strong>{{ number_format($event->prize_rank2) }}</strong></span>
            <span>🥉 <strong>{{ number_format($event->prize_rank3) }}</strong></span>
            <span style="color:var(--pw-text-muted);">Lainnya: <strong>{{ number_format($event->prizeForRank(4)) }}</strong>/orang</span>
        </div>
        @else
        <div style="font-size:.82rem;color:var(--pw-text-muted);">{{ number_format($event->prizePerWinner()) }} Cubi per pemenang (rata)</div>
        @endif
    </div>
    <div class="pw-adm-card" style="padding:.8rem 1rem;margin-bottom:0;">
        <div style="font-size:.62rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.5rem;">Periode Event</div>
        <div style="display:flex;gap:1.5rem;align-items:center;">
            <div>
                <div style="font-size:.68rem;color:var(--pw-text-muted);">Mulai</div>
                <div style="font-weight:600;font-size:.9rem;">{{ $event->start_at?->format('d M Y') }}</div>
                <div style="font-size:.75rem;color:var(--pw-text-muted);">{{ $event->start_at?->format('H:i') }} WIB</div>
            </div>
            <div style="color:var(--pw-text-muted);font-size:1.2rem;">→</div>
            <div>
                <div style="font-size:.68rem;color:var(--pw-text-muted);">Berakhir</div>
                <div style="font-weight:600;font-size:.9rem;">{{ $event->end_at?->format('d M Y') }}</div>
                <div style="font-size:.75rem;color:var(--pw-text-muted);">{{ $event->end_at?->format('H:i') }} WIB</div>
            </div>
        </div>
        @if($event->description)
        <div style="margin-top:.6rem;padding-top:.6rem;border-top:1px solid var(--pw-border);font-size:.78rem;color:var(--pw-text-muted);">{{ $event->description }}</div>
        @endif
    </div>
</div>

{{-- Participants Table --}}
<div class="pw-adm-card">
    <div class="pw-adm-card__title">Peserta ({{ $totalParticipants }})</div>
    @if($totalParticipants > 0)
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
    @else
    <div style="text-align:center;padding:2rem 1rem;color:var(--pw-text-muted);">
        <svg viewBox="0 0 20 20" fill="none" width="32" style="margin:0 auto .6rem;opacity:.4;display:block;"><path d="M10 2a8 8 0 110 16 8 8 0 010-16z" stroke="currentColor" stroke-width="1.5"/><path d="M7 13s1.5-2 3-2 3 2 3 2" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/><circle cx="7.5" cy="8.5" r="1" fill="currentColor"/><circle cx="12.5" cy="8.5" r="1" fill="currentColor"/></svg>
        <div style="font-size:.85rem;font-weight:600;">Belum ada peserta</div>
        <div style="font-size:.75rem;margin-top:.3rem;">Data akan muncul setelah event aktif dan cron sync berjalan.</div>
    </div>
    @endif
</div>
@endsection
