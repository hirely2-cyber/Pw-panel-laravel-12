@extends('layouts.admin')
@section('title', 'Cubi Audit — User #' . $userId)

@section('content')

{{-- ── HEADER ── --}}
<div style="margin-bottom:1.2rem;display:flex;align-items:center;gap:.6rem;flex-wrap:wrap;">
    <a href="{{ route('admin.cubi-monitor') }}" style="color:var(--pw-text-muted);font-size:.75rem;text-decoration:none;">← Cubi Monitor</a>
    <span class="pw-cmu-sep">|</span>
    <h1 style="font-size:1rem;font-weight:700;color:var(--pw-text-light);">Audit: {{ $user->name ?? "UID #{$userId}" }}</h1>
    @if($isGM)
    <span style="background:rgba(220,60,60,.15);color:#e05252;padding:.15rem .5rem;border-radius:3px;font-size:.68rem;font-weight:600;">GM ACCOUNT</span>
    @endif
    @if(!$user)
    <span style="background:rgba(160,100,220,.15);color:#a064dc;padding:.15rem .5rem;border-radius:3px;font-size:.68rem;font-weight:600;">GHOST - Tidak ada di tabel users</span>
    @endif
</div>

{{-- ── USER INFO + LIVE DATA ── --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;margin-bottom:1.2rem;">
    <div class="pw-cmu-card" style="border-radius:8px;padding:1rem;">
        <div style="font-size:.7rem;font-weight:600;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.6rem;">Info Akun</div>
        <div style="display:grid;gap:.4rem;font-size:.78rem;">
            <div><span style="color:var(--pw-text-muted);">User ID:</span> <span style="color:var(--pw-text-light);font-family:monospace;">{{ $userId }}</span></div>
            <div><span style="color:var(--pw-text-muted);">Username:</span> <span style="color:var(--pw-text-light);">{{ $user->name ?? 'tidak ditemukan' }}</span></div>
            <div><span style="color:var(--pw-text-muted);">Karakter:</span>
                @if($characters->isNotEmpty())
                    @foreach($characters as $c)
                        <span class="pw-cmu-char-chip">{{ $c->role_name }}</span>
                    @endforeach
                @else
                    <span class="pw-cmu-empty">— tidak ada karakter</span>
                @endif
            </div>
            <div><span style="color:var(--pw-text-muted);">Total Log (usecashlog):</span> <span style="color:var(--pw-gold);font-weight:600;">{{ number_format($logTotal / 100, 0, ',', '.') }} Cubi</span></div>
        </div>
    </div>

    <div style="background:rgba(184,157,79,.04);border:1px solid rgba(184,157,79,.15);border-radius:8px;padding:1rem;">
        <div style="font-size:.7rem;font-weight:600;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.6rem;">Data Real-time (gamedbd)</div>
        @if($liveData)
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.4rem;font-size:.78rem;">
            <div><span style="color:var(--pw-text-muted);">Cash (saldo aktif):</span></div>
            <div style="text-align:right;font-weight:700;color:var(--pw-gold);">{{ number_format($liveData['cash'] / 100, 0, ',', '.') }}</div>
            <div><span style="color:var(--pw-text-muted);">Total Top-up:</span></div>
            <div style="text-align:right;color:#50c878;">{{ number_format($liveData['cash_add'] / 100, 0, ',', '.') }}</div>
            <div><span style="color:var(--pw-text-muted);">Total Belanja:</span></div>
            <div style="text-align:right;color:#e05252;">{{ number_format($liveData['cash_used'] / 100, 0, ',', '.') }}</div>
            <div><span style="color:var(--pw-text-muted);">Beli dari Player:</span></div>
            <div style="text-align:right;color:var(--pw-text-light);">{{ number_format($liveData['cash_buy'] / 100, 0, ',', '.') }}</div>
            <div><span style="color:var(--pw-text-muted);">Jual ke Player:</span></div>
            <div style="text-align:right;color:var(--pw-text-light);">{{ number_format($liveData['cash_sell'] / 100, 0, ',', '.') }}</div>
            <div style="grid-column:span 2;border-top:1px solid var(--pw-border);padding-top:.4rem;margin-top:.2rem;display:flex;justify-content:space-between;">
                <span style="font-weight:600;color:var(--pw-text-muted);">Saldo Seharusnya:</span>
                @php $expected = ($liveData['cash_add'] + $liveData['cash_buy'] - $liveData['cash_used'] - $liveData['cash_sell']) / 100; @endphp
                <span style="font-weight:700;color:var(--pw-gold);font-size:.88rem;">{{ number_format($expected, 0, ',', '.') }} Cubi</span>
            </div>
        </div>
        @else
        <div class="pw-cmu-empty">Tidak bisa mengambil data dari gamedbd</div>
        @endif
    </div>
</div>

{{-- ── PENDING QUEUE ── --}}
@if($pending->isNotEmpty())
<div class="pw-cmu-pending" style="margin-bottom:1.2rem;border-radius:8px;padding:.8rem 1rem;">
    <div style="font-size:.7rem;font-weight:600;color:#ffa500;text-transform:uppercase;letter-spacing:.06em;margin-bottom:.5rem;">⏳ Antrian Pending (usecashnow) — {{ $pending->count() }} entries</div>
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:.75rem;">
            <thead>
                <tr>
                    <th style="text-align:left;padding:.4rem .6rem;color:var(--pw-text-muted);font-size:.68rem;">SN</th>
                    <th style="text-align:right;padding:.4rem .6rem;color:var(--pw-text-muted);font-size:.68rem;">Cash</th>
                    <th style="text-align:center;padding:.4rem .6rem;color:var(--pw-text-muted);font-size:.68rem;">Status</th>
                    <th style="text-align:left;padding:.4rem .6rem;color:var(--pw-text-muted);font-size:.68rem;">Waktu</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pending as $p)
                <tr style="border-top:1px solid var(--pw-border);">
                    <td style="padding:.4rem .6rem;font-family:monospace;">{{ $p->sn }}</td>
                    <td style="text-align:right;padding:.4rem .6rem;color:var(--pw-gold);font-weight:600;">{{ number_format($p->cash / 100, 0, ',', '.') }}</td>
                    <td style="text-align:center;padding:.4rem .6rem;">{{ $p->status }}</td>
                    <td style="padding:.4rem .6rem;color:var(--pw-text-muted);">{{ \Carbon\Carbon::parse($p->creatime)->translatedFormat('d M Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- ── FULL CASH LOG ── --}}
<div class="pw-cubi-table-wrap">
    <div class="pw-cmu-table-header">
        Riwayat Lengkap (usecashlog) — {{ $cashLog->count() }} entries
    </div>
    <table style="width:100%;border-collapse:collapse;font-size:.78rem;">
        <thead>
            <tr style="background:rgba(255,255,255,.02);">
                <th style="text-align:center;padding:.5rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">#</th>
                <th style="text-align:left;padding:.5rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">Dibuat</th>
                <th style="text-align:left;padding:.5rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">Selesai</th>
                <th style="text-align:right;padding:.5rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">Cubi</th>
                <th style="text-align:center;padding:.5rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">SN</th>
                <th style="text-align:center;padding:.5rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">Status</th>
                <th style="text-align:center;padding:.5rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">Tipe</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cashLog as $i => $log)
            <tr style="border-bottom:1px solid var(--pw-border);{{ $log->sn > 1 ? 'background:rgba(255,165,0,.03);' : '' }}">
                <td style="text-align:center;padding:.4rem .8rem;color:var(--pw-text-muted);font-size:.72rem;">{{ $i + 1 }}</td>
                <td style="padding:.4rem .8rem;color:var(--pw-text-muted);font-size:.72rem;white-space:nowrap;">{{ \Carbon\Carbon::parse($log->creatime)->translatedFormat('d M Y H:i') }}</td>
                <td style="padding:.4rem .8rem;color:var(--pw-text-muted);font-size:.72rem;white-space:nowrap;">{{ \Carbon\Carbon::parse($log->fintime)->translatedFormat('d M Y H:i') }}</td>
                <td style="text-align:right;padding:.4rem .8rem;font-weight:700;color:{{ $log->cash >= 10000000 ? '#e05252' : 'var(--pw-gold)' }};">{{ number_format($log->cash / 100, 0, ',', '.') }}</td>
                <td style="text-align:center;padding:.4rem .8rem;">
                    <span style="background:{{ $log->sn == 1 ? 'rgba(80,200,120,.12)' : 'rgba(255,165,0,.12)' }};color:{{ $log->sn == 1 ? '#50c878' : '#ffa500' }};padding:.1rem .4rem;border-radius:3px;font-size:.68rem;font-weight:700;font-family:monospace;">{{ $log->sn }}</span>
                </td>
                <td style="text-align:center;padding:.4rem .8rem;">
                    @if($log->status == 4)
                    <span style="color:#50c878;font-size:.7rem;">✓ Selesai</span>
                    @else
                    <span style="color:var(--pw-text-muted);font-size:.7rem;">{{ $log->status }}</span>
                    @endif
                </td>
                <td style="text-align:center;padding:.4rem .8rem;font-size:.7rem;">
                    @if($log->sn == 1)
                    <span style="color:var(--pw-text-muted);">Pertama</span>
                    @else
                    <span style="color:#ffa500;">Ke-{{ $log->sn }}</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
