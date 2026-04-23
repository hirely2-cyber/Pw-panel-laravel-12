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
        <div style="font-size:.82rem;color:var(--pw-text-muted);">Syarat Referral Level</div>
    </div>
    <div class="pw-adm-card" style="text-align:center;padding:1.2rem;">
        <div style="font-size:1.8rem;font-weight:800;color:#4ade80;">Lv.{{ $event->register_req_level ?? 50 }}</div>
        <div style="font-size:.82rem;color:var(--pw-text-muted);">Syarat Hadiah Register</div>
    </div>
    <div class="pw-adm-card" style="text-align:center;padding:1.2rem;">
        <div style="font-size:1.8rem;font-weight:800;color:#4ade80;">{{ number_format($registerDeliveredCount) }}</div>
        <div style="font-size:.82rem;color:var(--pw-text-muted);">Hadiah Register Terkirim</div>
    </div>
</div>

{{-- Register Rewards Info --}}
@if($event->register_rewards)
<div class="pw-adm-card" style="margin-bottom:1.5rem;border-color:rgba(74,222,128,.2);">
    <div class="pw-adm-card__title" style="color:#4ade80;">Hadiah Register (Daftar Akun)</div>
    <div style="font-size:.82rem;color:var(--pw-text-muted);margin-bottom:.8rem;">
        Diberikan ke player yang terdaftar selama event <strong style="color:var(--pw-text);">DAN</strong> sudah punya karakter minimal
        <strong style="color:#4ade80;">Level {{ $event->register_req_level ?? 50 }}</strong>.
        Sudah terkirim: <strong style="color:#4ade80;">{{ number_format($registerDeliveredCount) }}</strong> player.
    </div>
    <div style="display:flex;flex-wrap:wrap;gap:.7rem;">
        @foreach($event->register_rewards as $reward)
        <div style="display:inline-flex;align-items:center;gap:.5rem;padding:.5rem 1rem;background:rgba(74,222,128,.06);border:1px solid rgba(74,222,128,.15);border-radius:8px;">
            <span style="font-weight:800;color:#4ade80;">{{ number_format($reward['amount']) }}</span>
            <span style="font-size:.82rem;color:var(--pw-text-muted);">{{ $reward['label'] }}</span>
        </div>
        @endforeach
    </div>
</div>
@endif

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
    {{-- INFO: Auto-distribute sudah aktif, tidak perlu klik manual --}}
    <div style="background:rgba(74,222,128,.08);border:1px solid rgba(74,222,128,.25);border-radius:8px;padding:.55rem .9rem;display:inline-flex;align-items:center;gap:.5rem;font-size:.78rem;color:#86efac;">
        <svg viewBox="0 0 20 20" fill="none" width="15" style="flex-shrink:0;">
            <path d="M10 2a8 8 0 110 16A8 8 0 0110 2z" stroke="currentColor" stroke-width="1.3"/>
            <path d="M6.5 10l2.5 2.5 4-5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <span>Distribusi <strong>otomatis</strong> aktif — reward dikirim tiap 5 menit saat user mencapai level syarat</span>
    </div>
    <form method="POST" action="{{ route('admin.events.toggle', $event) }}" style="display:inline;margin-left:.5rem;"
          data-confirm="Akhiri Event|Akhiri event pre-launch ini? Distribusi otomatis akan berhenti."
          data-confirm-variant="danger" data-confirm-ok="Ya, Akhiri">
        @csrf
        <button type="submit" class="pw-adm-btn pw-adm-btn--danger">End Event</button>
    </form>
    {{-- Trigger manual jika admin butuh force-distribute sekarang --}}
    <form method="POST" action="{{ route('admin.events.distribute-referrals', $event) }}" style="display:inline;margin-left:.5rem;"
          data-confirm="Force Distribute Sekarang|Jalankan distribusi referral sekarang (biasanya otomatis tiap 5 menit)?"
          data-confirm-variant="success" data-confirm-ok="Ya, Jalankan">
        @csrf
        <button type="submit" class="pw-adm-btn" style="background:rgba(56,189,248,.15);border:1px solid rgba(56,189,248,.3);color:#38bdf8;font-size:.72rem;display:inline-flex;align-items:center;gap:.3rem;">
            <svg viewBox="0 0 20 20" fill="currentColor" width="12"><path d="M6.3 4.7a1 1 0 011.4 0l5 5a1 1 0 010 1.4l-5 5A1 1 0 016.3 14.7L10.6 10 6.3 5.3a1 1 0 010-1.6z"/></svg>
            Force Referral Sekarang
        </button>
    </form>
    @if($event->register_rewards)
    <form method="POST" action="{{ route('admin.events.distribute-register', $event) }}" style="display:inline;margin-left:.3rem;"
          data-confirm="Force Distribute Sekarang|Jalankan distribusi register reward sekarang (biasanya otomatis tiap 5 menit)?"
          data-confirm-variant="success" data-confirm-ok="Ya, Jalankan">
        @csrf
        <button type="submit" class="pw-adm-btn" style="background:rgba(74,222,128,.15);border:1px solid rgba(74,222,128,.3);color:#4ade80;font-size:.72rem;display:inline-flex;align-items:center;gap:.3rem;">
            <svg viewBox="0 0 20 20" fill="currentColor" width="12"><path d="M6.3 4.7a1 1 0 011.4 0l5 5a1 1 0 010 1.4l-5 5A1 1 0 016.3 14.7L10.6 10 6.3 5.3a1 1 0 010-1.6z"/></svg>
            Force Register Sekarang
        </button>
    </form>
    @endif
    @elseif($event->status === 'ended')
    <form method="POST" action="{{ route('admin.events.distribute-referrals', $event) }}" style="display:inline;"
          data-confirm="Distribute Referral Rewards|Distribusikan Cubi Gold ke semua referrer yang memenuhi milestone?"
          data-confirm-variant="success" data-confirm-ok="Ya, Distribute">
        @csrf
        <button type="submit" class="pw-adm-btn" style="background:#38bdf8;color:#0a0a0f;">Distribute Referral Rewards</button>
    </form>
    @if($event->register_rewards)
    <form method="POST" action="{{ route('admin.events.distribute-register', $event) }}" style="display:inline;"
          data-confirm="Distribute Hadiah Register|Kirim hadiah register ke semua player yang sudah mencapai Level {{ $event->register_req_level ?? 50 }}? Hanya player baru selama event yang belum menerima."
          data-confirm-variant="success" data-confirm-ok="Ya, Distribute">
        @csrf
        <button type="submit" class="pw-adm-btn" style="background:#4ade80;color:#052e16;">Distribute Hadiah Register</button>
    </form>
    @endif
    @endif
</div>
@endsection
