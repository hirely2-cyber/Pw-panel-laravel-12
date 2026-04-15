@extends('layouts.admin')
@section('title', 'Cairkan Bonus Partner')

@push('styles')
<style>
.pw-adm-stat{background:var(--pw-bg-card,rgba(255,255,255,.04));border:1px solid var(--pw-border,rgba(255,255,255,.08));border-radius:10px;padding:1.1rem 1.2rem;display:flex;flex-direction:column;gap:.3rem;}
.pw-adm-stat__icon{margin-bottom:.15rem;}
.pw-adm-stat__value{font-size:1.5rem;font-weight:700;color:var(--pw-text,#e8dfc8);line-height:1;}
.pw-adm-stat__label{font-size:.73rem;color:var(--pw-text-muted,#7a7a9a);text-transform:uppercase;letter-spacing:.05em;}
@media(max-width:640px){.pw-adm-stat__value{font-size:1.1rem;}}
</style>
@endpush

@section('content')

{{-- ── FLASH MESSAGES ──────────────────────────────────────── --}}
@if(session('success'))
<div style="background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.3);border-radius:8px;padding:.6rem 1rem;margin-bottom:1rem;font-size:.82rem;color:#22c55e;">
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);border-radius:8px;padding:.6rem 1rem;margin-bottom:1rem;font-size:.82rem;color:#ef4444;">
    {{ session('error') }}
</div>
@endif

{{-- ── STATS ───────────────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem;">
    <div class="pw-adm-stat">
        <div class="pw-adm-stat__icon" style="color:#f59e0b;">
            <svg viewBox="0 0 20 20" fill="none" width="22"><circle cx="10" cy="10" r="7" stroke="currentColor" stroke-width="1.5"/><path d="M10 6v4l2.5 2.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        </div>
        <div class="pw-adm-stat__value">{{ $pendingCount }}</div>
        <div class="pw-adm-stat__label">Menunggu</div>
    </div>
    <div class="pw-adm-stat">
        <div class="pw-adm-stat__icon" style="color:#22c55e;">
            <svg viewBox="0 0 20 20" fill="none" width="22"><path d="M6 10l3 3 5-6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.5"/></svg>
        </div>
        <div class="pw-adm-stat__value">{{ $approvedCount }}</div>
        <div class="pw-adm-stat__label">Disetujui</div>
    </div>
    <div class="pw-adm-stat">
        <div class="pw-adm-stat__icon" style="color:#ef4444;">
            <svg viewBox="0 0 20 20" fill="none" width="22"><circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.5"/><path d="M7 7l6 6M13 7l-6 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        </div>
        <div class="pw-adm-stat__value">{{ $rejectedCount }}</div>
        <div class="pw-adm-stat__label">Ditolak</div>
    </div>
    <div class="pw-adm-stat">
        <div class="pw-adm-stat__icon" style="color:#60a5fa;">
            <svg viewBox="0 0 20 20" fill="none" width="22"><path d="M10 2a8 8 0 110 16 8 8 0 010-16z" stroke="currentColor" stroke-width="1.5"/><path d="M7 10h6M10 7v6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        </div>
        <div class="pw-adm-stat__value">Rp {{ number_format($totalPaidOut, 0, ',', '.') }}</div>
        <div class="pw-adm-stat__label">Total Dicairkan</div>
    </div>
</div>

{{-- ── FILTER TABS ─────────────────────────────────────────── --}}
<div style="display:flex;gap:.4rem;margin-bottom:1rem;">
    <a href="{{ route('admin.bonus-claims') }}"
       style="padding:.35rem .8rem;font-size:.75rem;border-radius:5px;text-decoration:none;
              {{ !request('status') ? 'background:var(--pw-gold-faint);color:var(--pw-gold);border:1px solid var(--pw-border-gold);font-weight:600;' : 'background:rgba(255,255,255,.04);color:var(--pw-text-muted);border:1px solid var(--pw-border);' }}">
        Semua
    </a>
    @foreach(['pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak'] as $key => $label)
    <a href="{{ route('admin.bonus-claims', ['status' => $key]) }}"
       style="padding:.35rem .8rem;font-size:.75rem;border-radius:5px;text-decoration:none;
              {{ request('status') === $key ? 'background:var(--pw-gold-faint);color:var(--pw-gold);border:1px solid var(--pw-border-gold);font-weight:600;' : 'background:rgba(255,255,255,.04);color:var(--pw-text-muted);border:1px solid var(--pw-border);' }}">
        {{ $label }}
    </a>
    @endforeach
</div>

{{-- ── TABLE ───────────────────────────────────────────────── --}}
<div class="pw-adm-card">
    <div class="pw-adm-card__title">
        <svg viewBox="0 0 20 20" fill="none" width="15"><path d="M4 5h12M4 10h12M4 15h8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        Permintaan Pencairan Bonus
    </div>

    @if($claims->isEmpty())
    <div style="text-align:center;padding:2rem 1rem;color:var(--pw-text-muted);font-size:.82rem;">
        Belum ada permintaan pencairan bonus.
    </div>
    @else
    <div class="pw-table-wrap">
        <table class="pw-table">
            <thead>
                <tr>
                    <th>Partner</th>
                    <th>Tanggal</th>
                    <th>Tipe</th>
                    <th style="text-align:right;">Jumlah</th>
                    <th>Detail Pembayaran</th>
                    <th style="text-align:center;">Status</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($claims as $claim)
                <tr>
                    <td>
                        <div style="font-weight:600;">{{ $claim->user->name ?? 'Unknown' }}</div>
                        <div style="font-size:.7rem;color:var(--pw-text-muted);">ID: {{ $claim->user_id }}</div>
                    </td>
                    <td style="font-size:.78rem;color:var(--pw-text-muted);">{{ $claim->created_at->format('d M Y H:i') }}</td>
                    <td>
                        @if($claim->payment_method === 'cubi')
                            <span style="color:#60a5fa;font-weight:600;font-size:.8rem;">Cubi Gold</span>
                            <div style="font-size:.68rem;color:var(--pw-text-muted);">Otomatis ke game</div>
                        @elseif($claim->payment_method === 'gold')
                            <span style="color:var(--pw-gold);font-weight:600;font-size:.8rem;">Gold Points</span>
                            <div style="font-size:.68rem;color:var(--pw-text-muted);">Otomatis ke panel</div>
                        @elseif($claim->payment_method === 'bank')
                            <span style="font-size:.8rem;">Bank Transfer</span>
                            <div style="font-size:.68rem;color:var(--pw-text-muted);">Manual transfer</div>
                        @else
                            <span style="font-size:.8rem;">E-Wallet</span>
                            <div style="font-size:.68rem;color:var(--pw-text-muted);">Manual transfer</div>
                        @endif
                    </td>
                    <td style="text-align:right;font-weight:600;">
                        @if($claim->payment_method === 'cubi')
                            {{ number_format((int)($claim->amount / config('pw-config.currency.cubi_rate_idr', 1000))) }} Cubi
                            <div style="font-size:.68rem;color:var(--pw-text-muted);">Rp {{ number_format($claim->amount, 0, ',', '.') }}</div>
                        @elseif($claim->payment_method === 'gold')
                            {{ number_format((int)($claim->amount / config('pw-config.currency.rate_idr', 10000))) }} Gold
                            <div style="font-size:.68rem;color:var(--pw-text-muted);">Rp {{ number_format($claim->amount, 0, ',', '.') }}</div>
                        @else
                            Rp {{ number_format($claim->amount, 0, ',', '.') }}
                        @endif
                    </td>
                    <td style="font-size:.78rem;">{{ $claim->payment_detail }}</td>
                    <td style="text-align:center;">
                        @if($claim->status === 'approved')
                        <span class="pw-badge pw-badge--success">Disetujui</span>
                        @elseif($claim->status === 'rejected')
                        <span class="pw-badge pw-badge--danger">Ditolak</span>
                        @else
                        <span class="pw-badge pw-badge--warning">Pending</span>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        @if($claim->status === 'pending')
                        <div style="display:flex;gap:.3rem;justify-content:center;">
                            {{-- Approve --}}
                            <form action="{{ route('admin.bonus-claims.approve', $claim) }}" method="POST"
                                  data-confirm="Setujui Pencairan|{{ $claim->payment_method === 'cubi' ? 'Cubi Gold akan otomatis dikirim ke game.' : ($claim->payment_method === 'gold' ? 'Gold Points akan otomatis ditambahkan.' : 'Pastikan sudah transfer manual.') }}"
                                  data-confirm-variant="success"
                                  data-confirm-ok="Ya, Setujui">
                                @csrf
                                @if(in_array($claim->payment_method, ['bank', 'ewallet']))
                                <input type="hidden" name="admin_note" value="Disetujui — sudah ditransfer.">
                                @endif
                                <button type="submit" class="pw-adm-btn pw-adm-btn--sm" style="background:rgba(34,197,94,.15);border-color:rgba(34,197,94,.3);color:#22c55e;font-size:.7rem;padding:.25rem .5rem;">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                                    Setujui
                                </button>
                            </form>
                            {{-- Reject --}}
                            <button type="button" class="pw-adm-btn pw-adm-btn--sm" style="background:rgba(239,68,68,.15);border-color:rgba(239,68,68,.3);color:#ef4444;font-size:.7rem;padding:.25rem .5rem;"
                                    onclick="pwReject('{{ route('admin.bonus-claims.reject', $claim) }}')">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6L6 18M6 6l12 12"/></svg>
                                Tolak
                            </button>
                        </div>
                        @else
                        <div style="font-size:.72rem;color:var(--pw-text-muted);">
                            {{ $claim->processed_at?->format('d/m/Y') ?? '-' }}
                        </div>
                        @if($claim->admin_note)
                        <div style="font-size:.68rem;color:var(--pw-text-muted);max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $claim->admin_note }}">
                            {{ $claim->admin_note }}
                        </div>
                        @endif
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($claims->hasPages())
    <div style="margin-top:1rem;">
        {{ $claims->links() }}
    </div>
    @endif
    @endif
</div>

@endsection

{{-- Reject Modal --}}
<div id="pw-reject-overlay"
     style="display:none;position:fixed;inset:0;z-index:9999;background:transparent;align-items:center;justify-content:center;">
    <div style="background:var(--pw-bg-card);border:1px solid rgba(239,68,68,.25);border-radius:14px;padding:2rem 2rem 1.5rem;width:100%;max-width:400px;margin:1rem;box-shadow:0 25px 60px rgba(0,0,0,.6);transform:scale(.95);transition:transform .15s ease,opacity .15s ease;opacity:0;" id="pw-reject-box">
        <div style="width:52px;height:52px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.2rem;background:rgba(127,29,29,.5);border:1px solid rgba(239,68,68,.3);">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
        </div>
        <h3 style="text-align:center;font-size:1rem;font-weight:700;color:var(--pw-text-light);margin:0 0 .6rem;">Tolak Pencairan</h3>
        <p style="text-align:center;font-size:.85rem;color:var(--pw-text-muted);line-height:1.55;margin:0 0 1rem;">Tuliskan alasan penolakan pencairan bonus.</p>
        <form id="pw-reject-form" method="POST">
            @csrf
            <textarea name="admin_note" id="pw-reject-note" required rows="3" placeholder="Tulis alasan..."
                      style="width:100%;background:var(--pw-bg-card2);border:1px solid var(--pw-border);border-radius:8px;padding:.6rem .8rem;font-size:.82rem;color:var(--pw-text-light);outline:none;resize:vertical;margin-bottom:1rem;box-sizing:border-box;"></textarea>
            <div style="display:flex;gap:.6rem;">
                <button type="button" id="pw-reject-cancel"
                        style="flex:1;padding:.7rem 1rem;border-radius:8px;border:1px solid var(--pw-border);background:transparent;color:var(--pw-text-muted);font-size:.85rem;font-weight:500;cursor:pointer;"
                        onmouseover="this.style.background='var(--pw-bg-card2)';this.style.color='var(--pw-text-light)'"
                        onmouseout="this.style.background='transparent';this.style.color='var(--pw-text-muted)'">
                    Batal
                </button>
                <button type="submit"
                        style="flex:1;padding:.7rem 1rem;border-radius:8px;border:none;background:#dc2626;color:#fff;font-size:.85rem;font-weight:600;cursor:pointer;transition:filter .15s;"
                        onmouseover="this.style.filter='brightness(1.15)'"
                        onmouseout="this.style.filter='brightness(1)'">
                    Ya, Tolak
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
(function(){
    const overlay = document.getElementById('pw-reject-overlay');
    const box = document.getElementById('pw-reject-box');
    const form = document.getElementById('pw-reject-form');
    const note = document.getElementById('pw-reject-note');
    const cancel = document.getElementById('pw-reject-cancel');
    document.body.appendChild(overlay);

    window.pwReject = function(actionUrl) {
        form.action = actionUrl;
        note.value = '';
        overlay.style.display = 'flex';
        requestAnimationFrame(() => { box.style.transform='scale(1)'; box.style.opacity='1'; });
        setTimeout(() => note.focus(), 200);
    };

    function close() {
        box.style.transform='scale(.95)'; box.style.opacity='0';
        setTimeout(() => overlay.style.display='none', 150);
    }

    cancel.addEventListener('click', close);
    overlay.addEventListener('click', e => { if(e.target===overlay) close(); });
    document.addEventListener('keydown', e => { if(e.key==='Escape' && overlay.style.display==='flex') close(); });
})();
</script>
@endpush
