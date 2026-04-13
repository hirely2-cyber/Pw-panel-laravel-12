@extends('layouts.admin')
@section('title', 'Riwayat Referral')

@section('content')

{{-- Sub-nav tabs --}}
<div style="display:flex;gap:.5rem;margin-bottom:1.25rem;">
    <a href="{{ route('admin.referral') }}" class="pw-btn pw-btn--gold" style="font-size:.8rem;padding:.45rem .9rem;">
        <svg viewBox="0 0 20 20" fill="none" width="14"><path d="M3 5h14M3 10h14M3 15h9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        Riwayat Referral
    </a>
    <a href="{{ route('admin.referral.partners') }}" class="pw-btn pw-btn--muted" style="font-size:.8rem;padding:.45rem .9rem;">
        <svg viewBox="0 0 20 20" fill="none" width="14"><path d="M10 2v6M13 5H7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M15 10a5 5 0 11-10 0" stroke="currentColor" stroke-width="1.5"/><circle cx="5" cy="15" r="2.5" stroke="currentColor" stroke-width="1.3"/><circle cx="15" cy="15" r="2.5" stroke="currentColor" stroke-width="1.3"/></svg>
        Pengaturan Partner
    </a>
    <a href="{{ route('admin.referral.terms') }}" class="pw-btn pw-btn--muted" style="font-size:.8rem;padding:.45rem .9rem;">
        <svg viewBox="0 0 20 20" fill="none" width="14"><path d="M6 2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4a2 2 0 012-2z" stroke="currentColor" stroke-width="1.5"/><path d="M7 7h6M7 10h6M7 13h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        Syarat &amp; Ketentuan
    </a>
</div>

{{-- Stats Cards --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;margin-bottom:1.5rem;">
    <div class="pw-adm-card" style="text-align:center;padding:1rem;">
        <div style="font-size:.72rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.04em;">Total Referral</div>
        <div style="font-size:1.6rem;font-weight:700;color:var(--pw-gold-light);margin-top:.2rem;">{{ number_format($totalReferrals) }}</div>
    </div>
    <div class="pw-adm-card" style="text-align:center;padding:1rem;">
        <div style="font-size:.72rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.04em;">Sudah Reward</div>
        <div style="font-size:1.6rem;font-weight:700;color:#7deba0;margin-top:.2rem;">{{ number_format($totalRewarded) }}</div>
    </div>
    <div class="pw-adm-card" style="text-align:center;padding:1rem;">
        <div style="font-size:.72rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.04em;">Total Gold Diberikan</div>
        <div style="font-size:1.6rem;font-weight:700;color:#b89d4f;margin-top:.2rem;">{{ number_format($totalGoldGiven) }}</div>
    </div>
    <div class="pw-adm-card" style="text-align:center;padding:1rem;">
        <div style="font-size:.72rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.04em;">Total Cubi Diberikan</div>
        <div style="font-size:1.6rem;font-weight:700;color:#60d0ff;margin-top:.2rem;">{{ number_format($totalCubiGiven) }}</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 260px;gap:1.25rem;align-items:start;">

    {{-- Reward History --}}
    <div class="pw-adm-card">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;flex-wrap:wrap;gap:.6rem;">
            <div style="font-weight:600;font-size:.92rem;">Riwayat Reward</div>
            <form method="GET" action="{{ route('admin.referral') }}" style="display:flex;gap:.4rem;align-items:center;">
                <input type="text" name="search" class="pw-adm-input" placeholder="Cari username..." value="{{ request('search') }}" style="width:160px;font-size:.78rem;">
                <button type="submit" class="pw-adm-btn pw-adm-btn--sm">Cari</button>
            </form>
        </div>

        <div class="pw-table-wrap">
            <table class="pw-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pengundang</th>
                        <th>Diundang</th>
                        <th>Tipe Reward</th>
                        <th>Jumlah</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rewards as $rw)
                    <tr>
                        <td style="color:var(--pw-text-muted);">{{ $rw->id }}</td>
                        <td>
                            @if($rw->referrer)
                            <a href="{{ route('admin.members.show', $rw->referrer->ID) }}" style="color:var(--pw-gold-light);font-weight:600;">{{ $rw->referrer->name }}</a>
                            @else
                            <span style="color:var(--pw-text-muted);">-</span>
                            @endif
                        </td>
                        <td>
                            @if($rw->referred)
                            <a href="{{ route('admin.members.show', $rw->referred->ID) }}" style="color:var(--pw-text);font-weight:500;">{{ $rw->referred->name }}</a>
                            @else
                            <span style="color:var(--pw-text-muted);">-</span>
                            @endif
                        </td>
                        <td>
                            @if($rw->type === 'registration')
                                <span class="pw-badge pw-badge--success">Gold Points</span>
                            @elseif($rw->type === 'registration_cubi')
                                <span class="pw-badge" style="background:rgba(96,208,255,.12);color:#60d0ff;">Cubi Gold</span>
                            @else
                                <span class="pw-badge">{{ $rw->type }}</span>
                            @endif
                        </td>
                        <td><strong style="color:var(--pw-gold-light);">{{ number_format($rw->reward_amount) }}</strong></td>
                        <td style="color:var(--pw-text-muted);font-size:.78rem;">{{ $rw->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:2rem;color:var(--pw-text-muted);">Belum ada riwayat referral reward.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($rewards->hasPages())
        <div style="margin-top:1rem;">
            {{ $rewards->links() }}
        </div>
        @endif
    </div>

    {{-- Top Referrers --}}
    <div class="pw-adm-card">
        <div style="font-weight:600;font-size:.92rem;margin-bottom:.8rem;">Top Referrers</div>
        @forelse($topReferrers as $idx => $tr)
        <div style="display:flex;justify-content:space-between;align-items:center;padding:.4rem 0;border-bottom:1px solid rgba(255,255,255,.04);font-size:.83rem;">
            <div style="display:flex;align-items:center;gap:.5rem;">
                <span style="color:var(--pw-text-muted);font-size:.75rem;width:1.2rem;">{{ $idx + 1 }}.</span>
                <a href="{{ route('admin.members.show', $tr->ID) }}" style="color:var(--pw-gold-light);font-weight:600;">{{ $tr->name }}</a>
            </div>
            <span class="pw-badge pw-badge--success">{{ $tr->total_referred }} referral</span>
        </div>
        @empty
        <p style="color:var(--pw-text-muted);font-size:.82rem;text-align:center;padding:1rem 0;">Belum ada data.</p>
        @endforelse
    </div>

</div>

@endsection

@push('styles')
<style>
.pw-btn--muted {
    background: transparent;
    border: 1px solid var(--pw-border, rgba(255,255,255,.12));
    color: var(--pw-text-muted);
}
.pw-btn--muted:hover { color: var(--pw-text); border-color: var(--pw-gold); }
</style>
@endpush

