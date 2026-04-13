@extends('layouts.partner')
@section('title', 'Riwayat Referral')

@section('content')

@if(! $partner)
<div style="text-align:center;padding:3rem 1rem;">
    <h2 style="font-size:1.1rem;color:var(--pw-text);margin-bottom:.5rem;">Akun Partner Tidak Aktif</h2>
    <p style="font-size:.82rem;color:var(--pw-text-muted);">Hubungi Administrator untuk informasi lebih lanjut.</p>
</div>
@else

{{-- ── SUMMARY STATS ──────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1rem;margin-bottom:1.5rem;">
    <div class="pw-adm-stat">
        <div class="pw-adm-stat__icon" style="color:#60a5fa;">
            <svg viewBox="0 0 20 20" fill="none" width="22"><circle cx="8" cy="7" r="3.5" stroke="currentColor" stroke-width="1.5"/><path d="M2 17c0-3 2.7-5.5 6-5.5s6 2.5 6 5.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M14 8l2 2 3-3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </div>
        <div class="pw-adm-stat__value">{{ number_format($referrals->total()) }}</div>
        <div class="pw-adm-stat__label">Total Pengguna</div>
    </div>
    <div class="pw-adm-stat">
        <div class="pw-adm-stat__icon" style="color:#22c55e;">
            <svg viewBox="0 0 20 20" fill="none" width="22"><path d="M10 2a8 8 0 110 16 8 8 0 010-16z" stroke="currentColor" stroke-width="1.5"/><path d="M7 10h6M10 7v6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        </div>
        <div class="pw-adm-stat__value">{{ number_format($commissionByUser->sum()) ?? 0 }}</div>
        <div class="pw-adm-stat__label">Total Komisi IDR</div>
    </div>
</div>

{{-- ── REFERRAL TABLE ─────────────────────────────────────── --}}
<div class="pw-adm-card">
    <div class="pw-adm-card__title">
        <svg viewBox="0 0 20 20" fill="none" width="15"><circle cx="8" cy="7" r="3.5" stroke="currentColor" stroke-width="1.5"/><path d="M2 17c0-3 2.7-5.5 6-5.5s6 2.5 6 5.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        Pengguna yang Mendaftar via Referral Kamu
    </div>

    @if($referrals->isEmpty())
    <div style="text-align:center;padding:2.5rem 1rem;color:var(--pw-text-muted);font-size:.82rem;">
        Belum ada pengguna yang mendaftar menggunakan referral kamu.
    </div>
    @else
    <div class="pw-table-wrap">
        <table class="pw-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th style="text-align:center;">Tanggal Daftar</th>
                    <th style="text-align:right;">Komisi Dihasilkan (IDR)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($referrals as $index => $user)
                <tr>
                    <td style="color:var(--pw-text-muted);font-size:.78rem;">{{ $referrals->firstItem() + $loop->index }}</td>
                    <td style="font-weight:600;">{{ $user->name }}</td>
                    <td style="color:var(--pw-text-muted);font-size:.8rem;">{{ $user->email }}</td>
                    <td style="text-align:center;color:var(--pw-text-muted);font-size:.78rem;">
                        {{ \Carbon\Carbon::createFromTimestamp($user->creatime)->format('d M Y') }}
                    </td>
                    <td style="text-align:right;color:#22c55e;font-weight:600;">
                        @if(isset($commissionByUser[$user->ID]) && $commissionByUser[$user->ID] > 0)
                            Rp {{ number_format($commissionByUser[$user->ID], 0, ',', '.') }}
                        @else
                            <span style="color:var(--pw-text-muted);">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($referrals->hasPages())
    <div style="margin-top:1rem;">
        {{ $referrals->links() }}
    </div>
    @endif
    @endif
</div>

@endif

@endsection

@push('styles')
<style>
.pw-adm-stat{background:var(--pw-bg-card,rgba(255,255,255,.04));border:1px solid var(--pw-border,rgba(255,255,255,.08));border-radius:10px;padding:1.1rem 1.2rem;display:flex;flex-direction:column;gap:.3rem;}
.pw-adm-stat__icon{margin-bottom:.15rem;}
.pw-adm-stat__value{font-size:1.5rem;font-weight:700;color:var(--pw-text,#e8dfc8);line-height:1;}
.pw-adm-stat__label{font-size:.73rem;color:var(--pw-text-muted,#7a7a9a);text-transform:uppercase;letter-spacing:.05em;}
</style>
@endpush
