@extends('layouts.admin')
@section('title', 'Event Bonus Distribution')

@section('content')

{{-- Header --}}
<div style="margin-bottom:1.2rem;display:flex;align-items:center;gap:.6rem;">
    <svg viewBox="0 0 20 20" fill="none" width="18" style="color:#e5a615;flex-shrink:0;">
        <path d="M10 2l2.09 4.26 4.71.68-3.4 3.32.8 4.67L10 12.77l-4.2 2.16.8-4.67-3.4-3.32 4.71-.68L10 2z" fill="currentColor"/>
    </svg>
    <h1 style="font-size:1.05rem;font-weight:700;color:var(--pw-text-light);">Event Bonus Distribution</h1>
    <span style="font-size:.7rem;color:var(--pw-text-muted);margin-left:auto;">
        Kirim Cubi ke user — sumber tercatat sebagai <strong style="color:#e5a615;">Event</strong> di Cubi Monitor
    </span>
</div>

{{-- Flash messages --}}
@if(session('success'))
<div style="margin-bottom:1rem;padding:.8rem 1rem;background:rgba(80,200,100,.15);border:1px solid rgba(80,200,100,.4);border-radius:8px;color:#4ade80;font-size:.85rem;">
    {{ session('success') }}
</div>
@endif
@if(session('warning'))
<div style="margin-bottom:1rem;padding:.8rem 1rem;background:rgba(245,158,11,.15);border:1px solid rgba(245,158,11,.4);border-radius:8px;color:#fcd34d;font-size:.85rem;">
    {{ session('warning') }}
</div>
@endif
@if(session('error'))
<div style="margin-bottom:1rem;padding:.8rem 1rem;background:rgba(220,60,60,.15);border:1px solid rgba(220,60,60,.4);border-radius:8px;color:#e05252;font-size:.85rem;">
    {{ session('error') }}
</div>
@endif

{{-- Stat Cards --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem;">
    <div class="pw-adm-card" style="text-align:center;">
        <div style="font-size:1.6rem;font-weight:800;color:#e5a615;">{{ number_format($preview['count']) }}</div>
        <div style="font-size:.72rem;color:var(--pw-text-muted);text-transform:uppercase;margin-top:.3rem;">
            {{ $onlineOnly ? 'User Online' : 'Total User' }}
        </div>
    </div>
    <div class="pw-adm-card" style="text-align:center;">
        <div style="font-size:1.6rem;font-weight:800;color:#e5a615;">{{ number_format($preview['total_cubi']) }}</div>
        <div style="font-size:.72rem;color:var(--pw-text-muted);text-transform:uppercase;margin-top:.3rem;">Total Cubi</div>
    </div>
    <div class="pw-adm-card" style="text-align:center;">
        <div style="font-size:1.6rem;font-weight:800;color:#4ade80;">{{ count($onlineUserIds) }}</div>
        <div style="font-size:.72rem;color:var(--pw-text-muted);text-transform:uppercase;margin-top:.3rem;">Sedang Online</div>
    </div>
    <div class="pw-adm-card" style="text-align:center;">
        <div style="font-size:1.6rem;font-weight:800;color:var(--pw-text-light);">{{ $history->where('status','distributed')->count() }}</div>
        <div style="font-size:.72rem;color:var(--pw-text-muted);text-transform:uppercase;margin-top:.3rem;">Distribusi Lalu</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:340px 1fr;gap:1.2rem;align-items:start;">

    {{-- Kolom kiri: Form --}}
    <div>

        {{-- Form konfigurasi (GET untuk live preview) --}}
        <div class="pw-adm-card" style="margin-bottom:1rem;">
            <div class="pw-adm-card__title" style="margin-bottom:1rem;">Konfigurasi Bonus</div>
            <form method="GET" action="{{ route('admin.event-bonus.index') }}" id="previewForm">

                {{-- Toggle Target --}}
                <div style="margin-bottom:1rem;">
                    <label class="pw-form__label">Target Penerima</label>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:.5rem;margin-top:.3rem;">
                        <label style="display:flex;align-items:center;gap:.5rem;padding:.55rem .8rem;border-radius:6px;cursor:pointer;border:1px solid {{ !$onlineOnly ? 'rgba(229,166,21,.5)' : 'var(--pw-border)' }};background:{{ !$onlineOnly ? 'rgba(229,166,21,.08)' : 'transparent' }};transition:all .15s;">
                            <input type="radio" name="online_only" value="0" {{ !$onlineOnly ? 'checked' : '' }}
                                   onchange="this.form.submit()" style="accent-color:#e5a615;">
                            <span style="font-size:.78rem;color:{{ !$onlineOnly ? '#fcd34d' : 'var(--pw-text-muted)' }};font-weight:{{ !$onlineOnly ? '600' : '400' }};">
                                Semua User
                            </span>
                        </label>
                        <label style="display:flex;align-items:center;gap:.5rem;padding:.55rem .8rem;border-radius:6px;cursor:pointer;border:1px solid {{ $onlineOnly ? 'rgba(74,222,128,.5)' : 'var(--pw-border)' }};background:{{ $onlineOnly ? 'rgba(74,222,128,.08)' : 'transparent' }};transition:all .15s;">
                            <input type="radio" name="online_only" value="1" {{ $onlineOnly ? 'checked' : '' }}
                                   onchange="this.form.submit()" style="accent-color:#4ade80;">
                            <span style="font-size:.78rem;color:{{ $onlineOnly ? '#4ade80' : 'var(--pw-text-muted)' }};font-weight:{{ $onlineOnly ? '600' : '400' }};">
                                Online Saja
                                <span style="display:block;font-size:.65rem;font-weight:400;color:var(--pw-text-muted);">({{ count($onlineUserIds) }} user)</span>
                            </span>
                        </label>
                    </div>
                    @if($onlineOnly)
                    <div style="font-size:.7rem;color:var(--pw-text-muted);margin-top:.35rem;">
                        User yang aktif di panel dalam 30 menit terakhir.
                    </div>
                    @endif
                </div>

                <div style="margin-bottom:.85rem;">
                    <label class="pw-form__label">Base Cubi (per user)</label>
                    <input type="number" name="base_cubi" value="{{ $baseCubi }}" min="1" max="100000"
                           class="pw-form__input" onchange="this.form.submit()">
                    <div style="font-size:.7rem;color:var(--pw-text-muted);margin-top:.25rem;">
                        Jumlah Cubi yang diterima setiap user.
                    </div>
                </div>

                <div style="margin-bottom:.85rem;">
                    <label class="pw-form__label">
                        Bonus per Referral
                        <span style="color:var(--pw-text-muted);font-weight:400;">(opsional)</span>
                    </label>
                    <input type="number" name="referral_cubi_per_ref" value="{{ $referralCubiPerRef }}" min="0" max="100000"
                           class="pw-form__input" onchange="this.form.submit()">
                    <div style="font-size:.7rem;color:var(--pw-text-muted);margin-top:.25rem;">
                        Cubi tambahan per referral yang dimiliki. 0 = tidak ada.
                    </div>
                </div>

                <div style="margin-bottom:1rem;">
                    <label class="pw-form__label">
                        Maksimal Bonus Referral
                        <span style="color:var(--pw-text-muted);font-weight:400;">(opsional)</span>
                    </label>
                    <input type="number" name="referral_max_bonus" value="{{ $referralMaxBonus }}" min="0" max="1000000"
                           class="pw-form__input" onchange="this.form.submit()">
                    <div style="font-size:.7rem;color:var(--pw-text-muted);margin-top:.25rem;">
                        Batas maksimum bonus dari referral. 0 = tidak dibatasi.
                    </div>
                </div>

                <button type="submit" class="pw-adm-btn pw-adm-btn--ghost pw-adm-btn--sm" style="width:100%;justify-content:center;">
                    <svg viewBox="0 0 20 20" fill="none" width="13" style="margin-right:.3rem;">
                        <path d="M4 10h12M13 6l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Refresh Preview
                </button>
            </form>
        </div>

        {{-- Form Distribute (POST) --}}
        <div class="pw-adm-card">
            <div class="pw-adm-card__title" style="margin-bottom:1rem;">Jalankan Distribusi</div>
            <form method="POST" action="{{ route('admin.event-bonus.distribute') }}" id="distributeForm"
                  onsubmit="return confirmDistribute();">
                @csrf
                <div style="margin-bottom:.85rem;">
                    <label class="pw-form__label">
                        Nama Event <span style="color:#e05252;">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title', 'Grand Opening Bonus') }}"
                           required maxlength="255" class="pw-form__input"
                           placeholder="Contoh: Grand Opening Bonus">
                </div>
                <div style="margin-bottom:1rem;">
                    <label class="pw-form__label">
                        Deskripsi
                        <span style="color:var(--pw-text-muted);font-weight:400;">(opsional)</span>
                    </label>
                    <textarea name="description" rows="2" maxlength="1000" class="pw-form__input"
                              placeholder="Catatan internal admin..." style="resize:vertical;"></textarea>
                </div>

                <input type="hidden" name="base_cubi" value="{{ $baseCubi }}">
                <input type="hidden" name="referral_cubi_per_ref" value="{{ $referralCubiPerRef }}">
                <input type="hidden" name="referral_max_bonus" value="{{ $referralMaxBonus }}">
                <input type="hidden" name="online_only" value="{{ $onlineOnly ? '1' : '0' }}">

                {{-- Peringatan --}}
                <div style="background:rgba(220,60,60,.07);border:1px solid rgba(220,60,60,.2);border-radius:6px;padding:.65rem .9rem;margin-bottom:1rem;display:flex;gap:.55rem;align-items:flex-start;">
                    <svg viewBox="0 0 20 20" fill="none" width="15" style="color:#f87171;flex-shrink:0;margin-top:.1rem;">
                        <path d="M10 3a7 7 0 100 14A7 7 0 0010 3z" stroke="currentColor" stroke-width="1.3"/>
                        <path d="M10 7v4M10 13h.01" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <span style="font-size:.73rem;color:#fca5a5;line-height:1.5;">
                        Distribusi ini <strong>tidak bisa di-undo</strong>. Cubi masuk antrian game dan diterima pemain saat server online.
                    </span>
                </div>

                {{-- Tombol distribute — teks putih, hover hanya pada background --}}
                <button type="submit"
                        style="width:100%;display:inline-flex;align-items:center;justify-content:center;gap:.4rem;padding:.6rem 1rem;border-radius:6px;font-size:.82rem;font-weight:600;cursor:pointer;border:1px solid rgba(217,119,6,.5);background:linear-gradient(135deg,#d97706,#b45309);color:#ffffff;letter-spacing:.02em;transition:filter .15s;white-space:nowrap;"
                        onmouseover="this.style.filter='brightness(1.12)'" onmouseout="this.style.filter='none'">
                    <svg viewBox="0 0 20 20" fill="none" width="14">
                        <path d="M10 2l2.09 4.26 4.71.68-3.4 3.32.8 4.67L10 12.77l-4.2 2.16.8-4.67-3.4-3.32 4.71-.68L10 2z" fill="currentColor"/>
                    </svg>
                    @if($onlineOnly)
                        Kirim ke {{ $preview['count'] }} User Online
                    @else
                        Kirim ke {{ $preview['count'] }} User
                    @endif
                    <span style="opacity:.7;font-weight:400;">&nbsp;·&nbsp; {{ number_format($preview['total_cubi']) }} Cubi</span>
                </button>
            </form>
        </div>

    </div>

    {{-- Kolom kanan --}}
    <div>

        {{-- Preview tabel --}}
        <div class="pw-adm-card" style="margin-bottom:1.2rem;padding:0;overflow:hidden;">
            <div style="padding:.85rem 1rem;border-bottom:1px solid var(--pw-border);display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;">
                <svg viewBox="0 0 20 20" fill="none" width="15" style="color:var(--pw-text-muted);flex-shrink:0;">
                    <path d="M3 5h14M3 10h14M3 15h14" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                </svg>
                <span style="font-size:.82rem;font-weight:600;color:var(--pw-text-light);">Preview Distribusi</span>

                @if($onlineOnly)
                <span class="pw-badge pw-badge--success" style="font-size:.65rem;">Online Only</span>
                @else
                <span class="pw-badge" style="font-size:.65rem;">Semua User</span>
                @endif

                <div style="margin-left:auto;font-size:.72rem;color:var(--pw-text-muted);display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;">
                    Base: <strong style="color:#fcd34d;">{{ $baseCubi }} Cubi</strong>
                    @if($referralCubiPerRef > 0)
                        <span>+<strong style="color:#a78bfa;">{{ $referralCubiPerRef }}</strong>/referral</span>
                        @if($referralMaxBonus > 0)
                            <span style="color:var(--pw-text-muted);">(max {{ number_format($referralMaxBonus) }})</span>
                        @endif
                    @endif
                </div>
            </div>

            <div style="overflow-x:auto;max-height:580px;overflow-y:auto;">
                <table class="pw-table">
                    <thead style="position:sticky;top:0;z-index:1;background:var(--pw-surface-2,var(--pw-surface));">
                        <tr>
                            <th style="width:60px;">ID</th>
                            <th>Username</th>
                            @if($onlineOnly)
                            <th style="text-align:center;width:70px;">Status</th>
                            @endif
                            <th style="text-align:center;width:80px;">Base</th>
                            @if($referralCubiPerRef > 0)
                            <th style="text-align:center;width:60px;">Ref</th>
                            <th style="text-align:center;width:90px;">Bonus Ref</th>
                            @endif
                            <th style="text-align:center;width:90px;">Total Cubi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($preview['rows'] as $row)
                        <tr>
                            <td style="color:var(--pw-text-muted);font-size:.78rem;">{{ $row['id'] }}</td>
                            <td style="font-weight:600;">{{ $row['name'] }}</td>
                            @if($onlineOnly)
                            <td style="text-align:center;">
                                <span class="pw-badge pw-badge--success" style="font-size:.62rem;">Online</span>
                            </td>
                            @endif
                            <td style="text-align:center;color:#fcd34d;font-weight:600;">{{ number_format($row['base']) }}</td>
                            @if($referralCubiPerRef > 0)
                            <td style="text-align:center;color:var(--pw-text-muted);">{{ $row['ref_count'] }}</td>
                            <td style="text-align:center;{{ $row['ref_bonus'] > 0 ? 'color:#a78bfa;font-weight:600;' : 'color:var(--pw-text-muted);' }}">
                                {{ $row['ref_bonus'] > 0 ? '+'.number_format($row['ref_bonus']) : '—' }}
                            </td>
                            @endif
                            <td style="text-align:center;font-weight:700;color:#e5a615;">{{ number_format($row['total']) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ 3 + ($referralCubiPerRef > 0 ? 2 : 0) + ($onlineOnly ? 1 : 0) }}"
                                style="text-align:center;padding:2rem;color:var(--pw-text-muted);">
                                @if($onlineOnly)
                                    Tidak ada user yang sedang online saat ini.
                                @else
                                    Belum ada user terdaftar.
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Riwayat distribusi --}}
        @if($history->isNotEmpty())
        <div class="pw-adm-card" style="padding:0;overflow:hidden;">
            <div style="padding:.85rem 1rem;border-bottom:1px solid var(--pw-border);display:flex;align-items:center;gap:.5rem;">
                <svg viewBox="0 0 20 20" fill="none" width="15" style="color:var(--pw-text-muted);flex-shrink:0;">
                    <path d="M10 2a8 8 0 110 16A8 8 0 0110 2z" stroke="currentColor" stroke-width="1.3"/>
                    <path d="M10 6v4l3 2" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                </svg>
                <span style="font-size:.82rem;font-weight:600;color:var(--pw-text-light);">Riwayat Distribusi</span>
            </div>
            <div class="pw-table-wrap">
                <table class="pw-table">
                    <thead>
                        <tr>
                            <th>Nama Event</th>
                            <th style="text-align:center;width:70px;">User</th>
                            <th style="text-align:center;width:100px;">Total Cubi</th>
                            <th style="text-align:center;width:120px;">Tanggal</th>
                            <th style="text-align:center;width:80px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($history as $h)
                        <tr>
                            <td>
                                <div style="font-weight:600;color:var(--pw-text-light);">{{ $h->title }}</div>
                                @if($h->description)
                                    <div style="font-size:.72rem;color:var(--pw-text-muted);">{{ Str::limit($h->description, 70) }}</div>
                                @endif
                                <div style="font-size:.68rem;color:var(--pw-text-muted);margin-top:.15rem;">
                                    Base {{ number_format($h->base_cubi) }} Cubi
                                    @if($h->referral_cubi_per_ref > 0)
                                        &nbsp;+&nbsp;{{ number_format($h->referral_cubi_per_ref) }}/ref
                                        @if($h->referral_max_bonus > 0)
                                            (max {{ number_format($h->referral_max_bonus) }})
                                        @endif
                                    @endif
                                </div>
                            </td>
                            <td style="text-align:center;color:var(--pw-text-muted);">{{ number_format($h->total_recipients) }}</td>
                            <td style="text-align:center;font-weight:700;color:#e5a615;">{{ number_format($h->total_cubi_distributed) }}</td>
                            <td style="text-align:center;font-size:.75rem;color:var(--pw-text-muted);">
                                {{ $h->distributed_at ? \Carbon\Carbon::parse($h->distributed_at)->format('d/m/Y H:i') : '—' }}
                            </td>
                            <td style="text-align:center;">
                                @if($h->status === 'distributed')
                                    <span class="pw-badge pw-badge--success">Selesai</span>
                                @else
                                    <span class="pw-badge">Draft</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>
</div>

<script>
function confirmDistribute() {
    const total  = {{ $preview['total_cubi'] }};
    const count  = {{ $preview['count'] }};
    const base   = {{ $baseCubi }};
    const target = '{{ $onlineOnly ? "user yang sedang online" : "semua user" }}';
    return confirm(
        'KONFIRMASI DISTRIBUSI\n\n' +
        'Target  : ' + target + '\n' +
        'Penerima: ' + count + ' user\n' +
        'Total   : ' + total.toLocaleString('id-ID') + ' Cubi Gold\n' +
        'Base    : ' + base + ' Cubi per user\n' +
        'Sumber  : EVENT (Cubi Monitor)\n\n' +
        'Distribusi ini tidak bisa di-undo. Lanjutkan?'
    );
}
</script>

@endsection
