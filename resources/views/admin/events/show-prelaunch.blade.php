@extends('layouts.admin')
@section('title', $event->title . ' — Pre-Launch Event')

@section('content')
<div style="margin-bottom:1rem;">
    <a href="{{ route('admin.events.index', ['tab' => 'pre_launch']) }}" class="pw-adm-btn pw-adm-btn--ghost pw-adm-btn--sm">← Kembali</a>
</div>

{{-- Event Info --}}
<div class="pw-adm-card" style="margin-bottom:1.2rem;">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.8rem;">
        <div>
            <div class="pw-adm-card__title" style="margin-bottom:.3rem;">{{ $event->title }}</div>
            <div style="font-size:.82rem;color:var(--pw-text-muted);">
                {{ $event->start_at?->format('d M Y H:i') }} — {{ $event->end_at?->format('d M Y H:i') }}
            </div>
        </div>
        <div>
            @if($event->status === 'active')
                <span class="pw-badge pw-badge--success">Aktif</span>
            @elseif($event->status === 'ended')
                <span class="pw-badge pw-badge--warning">Berakhir</span>
            @elseif($event->status === 'distributed')
                <span class="pw-badge pw-badge--info" style="background:rgba(56,189,248,.15);color:#38bdf8;">Distributed</span>
            @else
                <span class="pw-badge">Draft</span>
            @endif
        </div>
    </div>
</div>

{{-- Stats Cards --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;margin-bottom:1.5rem;">
    <div class="pw-adm-card" style="text-align:center;padding:1.2rem;">
        <div style="font-size:1.8rem;font-weight:800;color:#c8972a;">{{ number_format($totalRegistered) }}</div>
        <div style="font-size:.82rem;color:var(--pw-text-muted);">Total Registrasi</div>
    </div>
    <div class="pw-adm-card" style="text-align:center;padding:1.2rem;">
        <div style="font-size:1.8rem;font-weight:800;color:#c8972a;">{{ number_format($totalReferrals) }}</div>
        <div style="font-size:.82rem;color:var(--pw-text-muted);">Via Referral</div>
    </div>
    <div class="pw-adm-card" style="text-align:center;padding:1.2rem;">
        <div style="font-size:1.8rem;font-weight:800;color:#c8972a;">Lv.{{ $event->referral_req_level }}</div>
        <div style="font-size:.82rem;color:var(--pw-text-muted);">Syarat Level Karakter</div>
    </div>
</div>

{{-- Referral Tiers --}}
<div class="pw-adm-card" style="margin-bottom:1.5rem;">
    <div class="pw-adm-card__title">Referral Reward Tiers</div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:.8rem;">
        @foreach($event->referral_tiers ?? [] as $tier)
        <div style="background:rgba(200,151,42,.06);border:1px solid rgba(200,151,42,.15);border-radius:8px;padding:1rem;text-align:center;">
            <div style="font-size:1.3rem;font-weight:800;color:#c8972a;">{{ $tier['count'] }}</div>
            <div style="font-size:.78rem;color:var(--pw-text-muted);margin-bottom:.3rem;">Referral</div>
            <div style="font-size:.9rem;font-weight:700;color:var(--pw-text);">{{ number_format($tier['reward']) }} Cubi Gold</div>
        </div>
        @endforeach
    </div>
</div>

{{-- Referral Leaderboard --}}
<div class="pw-adm-card" style="margin-bottom:1.5rem;">
    <div class="pw-adm-card__title">Ranking Referral</div>

    @if($referrers->isEmpty())
    <div style="text-align:center;padding:2rem;color:var(--pw-text-muted);">Belum ada data referral.</div>
    @else
    <div class="pw-table-wrap">
        <table class="pw-table">
            <thead>
                <tr>
                    <th style="text-align:center;width:50px;">#</th>
                    <th>Username</th>
                    <th style="text-align:center;">Referral Code</th>
                    <th style="text-align:center;">Jumlah Referral</th>
                    <th style="text-align:center;">Milestone Tercapai</th>
                </tr>
            </thead>
            <tbody>
                @foreach($referrers as $index => $referrer)
                <tr>
                    <td style="text-align:center;font-weight:700;">
                        @if($referrers->firstItem() + $index <= 3)
                            @php $medals = ['🥇','🥈','🥉']; @endphp
                            {{ $medals[$referrers->firstItem() + $index - 1] }}
                        @else
                            {{ $referrers->firstItem() + $index }}
                        @endif
                    </td>
                    <td style="font-weight:600;">{{ $referrer->name }}</td>
                    <td style="text-align:center;font-family:monospace;font-size:.82rem;">{{ $referrer->referral_code }}</td>
                    <td style="text-align:center;font-weight:700;color:#c8972a;">{{ $referrer->referral_count }}</td>
                    <td style="text-align:center;font-size:.82rem;">
                        @php
                            $tiers = collect($event->referral_tiers ?? []);
                            $reached = $tiers->filter(fn($t) => $referrer->referral_count >= $t['count'])->count();
                        @endphp
                        {{ $reached }} / {{ $tiers->count() }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $referrers->links() }}
    @endif
</div>

{{-- Distributed Milestones --}}
@if($milestones->isNotEmpty())
<div class="pw-adm-card">
    <div class="pw-adm-card__title">Milestone yang Sudah Didistribusikan</div>
    <div class="pw-table-wrap">
        <table class="pw-table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th style="text-align:center;">Milestone</th>
                    <th style="text-align:center;">Reward</th>
                    <th style="text-align:center;">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($milestones as $ms)
                <tr>
                    <td>{{ $ms->user?->name ?? 'ID: '.$ms->user_id }}</td>
                    <td style="text-align:center;">{{ $ms->milestone }} referral</td>
                    <td style="text-align:center;font-weight:700;color:#c8972a;">{{ number_format($ms->reward_amount) }} Cubi</td>
                    <td style="text-align:center;font-size:.82rem;color:var(--pw-text-muted);">{{ $ms->distributed_at?->format('d M Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Actions --}}
<div style="margin-top:1.5rem;display:flex;gap:.5rem;flex-wrap:wrap;">
    <a href="{{ route('admin.events.edit', $event) }}" class="pw-adm-btn pw-adm-btn--ghost">Edit Event</a>

    @if($event->status === 'draft')
    <form method="POST" action="{{ route('admin.events.toggle', $event) }}" style="display:inline;"
          data-confirm="Aktifkan Event|Aktifkan event pre-launch ini?"
          data-confirm-variant="success" data-confirm-ok="Ya, Aktifkan">
        @csrf
        <button type="submit" class="pw-adm-btn">Start Event</button>
    </form>
    @elseif($event->status === 'active')
    <form method="POST" action="{{ route('admin.events.toggle', $event) }}" style="display:inline;"
          data-confirm="Akhiri Event|Akhiri event pre-launch ini?"
          data-confirm-variant="danger" data-confirm-ok="Ya, Akhiri">
        @csrf
        <button type="submit" class="pw-adm-btn pw-adm-btn--danger">End Event</button>
    </form>
    @elseif($event->status === 'ended')
    <form method="POST" action="{{ route('admin.events.distribute-referrals', $event) }}" style="display:inline;"
          data-confirm="Distribute Referral Rewards|Distribusikan Cubi Gold ke semua referrer yang memenuhi milestone?"
          data-confirm-variant="success" data-confirm-ok="Ya, Distribute">
        @csrf
        <button type="submit" class="pw-adm-btn" style="background:#38bdf8;color:#0a0a0f;">Distribute Referral Rewards</button>
    </form>
    @endif
</div>
@endsection
