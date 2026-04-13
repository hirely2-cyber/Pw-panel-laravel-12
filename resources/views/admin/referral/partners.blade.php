@extends('layouts.admin')
@section('title', 'Pengaturan Partner')

@section('content')

{{-- Sub-nav tabs --}}
<div style="display:flex;gap:.5rem;margin-bottom:1.25rem;">
    <a href="{{ route('admin.referral') }}" class="pw-btn pw-btn--muted" style="font-size:.8rem;padding:.45rem .9rem;">
        <svg viewBox="0 0 20 20" fill="none" width="14"><path d="M3 5h14M3 10h14M3 15h9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        Riwayat Referral
    </a>
    <a href="{{ route('admin.referral.partners') }}" class="pw-btn pw-btn--gold" style="font-size:.8rem;padding:.45rem .9rem;">
        <svg viewBox="0 0 20 20" fill="none" width="14"><path d="M10 2v6M13 5H7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M15 10a5 5 0 11-10 0" stroke="currentColor" stroke-width="1.5"/><circle cx="5" cy="15" r="2.5" stroke="currentColor" stroke-width="1.3"/><circle cx="15" cy="15" r="2.5" stroke="currentColor" stroke-width="1.3"/></svg>
        Pengaturan Partner
    </a>
    <a href="{{ route('admin.referral.terms') }}" class="pw-btn pw-btn--muted" style="font-size:.8rem;padding:.45rem .9rem;">
        <svg viewBox="0 0 20 20" fill="none" width="14"><path d="M6 2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4a2 2 0 012-2z" stroke="currentColor" stroke-width="1.5"/><path d="M7 7h6M7 10h6M7 13h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        Syarat &amp; Ketentuan
    </a>
</div>

{{-- Pengaturan Referral --}}
<div class="pw-adm-card" style="margin-bottom:1.25rem;">

    {{-- Card header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.5rem;margin-bottom:1.25rem;padding-bottom:.75rem;border-bottom:1px solid rgba(255,255,255,.07);">
        <div>
            <div style="font-weight:600;font-size:.95rem;">Pengaturan Referral</div>
            <div style="font-size:.75rem;color:var(--pw-text-muted);margin-top:.15rem;">Konfigurasi reward dan syarat sistem referral global.</div>
        </div>
        <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);border-radius:6px;padding:.45rem .8rem;">
            <input type="checkbox" name="enabled" value="1" form="formReferralSettings"
                {{ config('pw-config.referral.enabled') ? 'checked' : '' }}
                style="accent-color:var(--pw-gold);width:14px;height:14px;">
            <span style="font-size:.82rem;font-weight:600;">Aktifkan Referral System</span>
        </label>
    </div>

    <form id="formReferralSettings" method="POST" action="{{ route('admin.referral.settings') }}">
        @csrf

        {{-- Bagian 1: Reward Pengundang --}}
        <div style="font-size:.72rem;font-weight:700;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.07em;margin-bottom:.6rem;">
            Reward Pengundang
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.25rem;">
            <div>
                <label class="pw-adm-label">Tipe Reward</label>
                <select name="reward_type" class="pw-adm-input" style="margin-top:.25rem;">
                    <option value="gold" {{ config('pw-config.referral.reward_type','gold') === 'gold' ? 'selected' : '' }}>Gold Points (Saldo Panel)</option>
                    <option value="cubi" {{ config('pw-config.referral.reward_type','gold') === 'cubi' ? 'selected' : '' }}>Cubi Gold (In-Game)</option>
                </select>
                <p style="font-size:.7rem;color:var(--pw-text-muted);margin-top:.3rem;">Reward Tunai hanya bisa diatur per-Partner.</p>
            </div>
            <div>
                <label class="pw-adm-label">Jumlah Reward per Referral</label>
                <input type="number" name="reward_amount" class="pw-adm-input" style="margin-top:.25rem;"
                    value="{{ config('pw-config.referral.reward_gold', 10) }}" min="1" max="999999">
                <p style="font-size:.7rem;color:var(--pw-text-muted);margin-top:.3rem;">Diberikan otomatis ke akun yang mengundang.</p>
            </div>
        </div>

        {{-- Bagian 2: Reward Penerima --}}
        <div style="font-size:.72rem;font-weight:700;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.07em;margin-bottom:.6rem;">
            Reward Penerima <span style="font-weight:400;text-transform:none;letter-spacing:0;font-size:.7rem;">(yang mendaftar via link)</span>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.25rem;padding:.85rem;background:rgba(255,255,255,.025);border-radius:.5rem;border:1px solid rgba(255,255,255,.07);">
            <div>
                <label class="pw-adm-label">Tipe Reward Penerima</label>
                <select name="referred_reward_type" class="pw-adm-input" style="margin-top:.25rem;">
                    <option value="none" {{ config('pw-config.referral.referred_reward_type','none') === 'none' ? 'selected' : '' }}>Tidak Ada</option>
                    <option value="gold" {{ config('pw-config.referral.referred_reward_type','none') === 'gold' ? 'selected' : '' }}>Gold Points (Saldo Panel)</option>
                    <option value="cubi" {{ config('pw-config.referral.referred_reward_type','none') === 'cubi' ? 'selected' : '' }}>Cubi Gold (In-Game)</option>
                </select>
                <p style="font-size:.7rem;color:var(--pw-text-muted);margin-top:.3rem;">Diberikan ke akun yang mendaftar menggunakan link referral.</p>
            </div>
            <div>
                <label class="pw-adm-label">Jumlah Reward Penerima</label>
                <input type="number" name="referred_reward_amount" class="pw-adm-input" style="margin-top:.25rem;"
                    value="{{ config('pw-config.referral.referred_reward_amount', 0) }}" min="0" max="999999">
                <p style="font-size:.7rem;color:var(--pw-text-muted);margin-top:.3rem;">Dikirim 1x saja saat syarat terpenuhi. <strong>0</strong> = nonaktif.</p>
            </div>
        </div>

        {{-- Bagian 3: Syarat & Batas --}}
        <div style="font-size:.72rem;font-weight:700;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.07em;margin-bottom:.6rem;">
            Syarat &amp; Batas
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;margin-bottom:1.25rem;">
            <div>
                <label class="pw-adm-label">Min. Level Karakter</label>
                <input type="number" name="min_char_level" class="pw-adm-input" style="margin-top:.25rem;"
                    value="{{ config('pw-config.referral.min_char_level', 1) }}" min="1" max="150">
                <p style="font-size:.7rem;color:var(--pw-text-muted);margin-top:.3rem;">
                    Berlaku untuk pengundang &amp; penerima. <strong>1</strong> = cukup buat karakter.
                </p>
            </div>
            <div>
                <label class="pw-adm-label">Min. Cultivation (Wibawa)</label>
                <select name="min_cultivation" class="pw-adm-input" style="margin-top:.25rem;">
                    @php
                    $cultivations = [
                        0  => 'Tidak Ada Syarat',
                        1  => 'Autoscopy',
                        2  => 'Transform',
                        3  => 'Naissance',
                        4  => 'Reborn',
                        5  => 'Vigilance',
                        6  => 'Doom',
                        7  => 'Disengage',
                        8  => 'Nirvana',
                        20 => 'Prime Immortal / Daimon Baresark',
                        21 => 'Pure Immortal / Daimon Saint',
                        22 => 'Ether Immortal / Daimon Elder (Max)',
                    ];
                    $currentCult = (int) config('pw-config.referral.min_cultivation', 0);
                    if (in_array($currentCult, [30,31,32])) { $currentCult -= 10; }
                    @endphp
                    @foreach($cultivations as $val => $label)
                    <option value="{{ $val }}" {{ $currentCult === $val ? 'selected' : '' }}>{{ $val === 0 ? $label : $val . ' – ' . $label }}</option>
                    @endforeach
                </select>
                <p style="font-size:.7rem;color:var(--pw-text-muted);margin-top:.3rem;">
                    Cultivation minimum karakter penerima. Dicek via gamedbd.
                </p>
            </div>
            <div>
                <label class="pw-adm-label">Maks. Reward per Hari <span style="font-weight:400;">(per Referrer)</span></label>
                <input type="number" name="max_per_day" class="pw-adm-input" style="margin-top:.25rem;"
                    value="{{ config('pw-config.referral.max_per_day', 0) }}" min="0" max="10000">
                <p style="font-size:.7rem;color:var(--pw-text-muted);margin-top:.3rem;">
                    Berapa kali reward bisa diterima dalam sehari. <strong>0</strong> = tidak terbatas.
                </p>
            </div>
        </div>

        {{-- Save button --}}
        <div style="display:flex;justify-content:flex-end;padding-top:.75rem;border-top:1px solid rgba(255,255,255,.06);">
            <button type="submit" class="pw-adm-btn" style="min-width:180px;">Simpan Pengaturan</button>
        </div>

    </form>
</div>

{{-- Partner / Streamer --}}
<div class="pw-adm-card" style="margin-bottom:1.25rem;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;flex-wrap:wrap;gap:.6rem;">
        <div>
            <div style="font-weight:600;font-size:.95rem;">Partner / Streamer</div>
            <div style="font-size:.75rem;color:var(--pw-text-muted);">Kelola akun partner dengan reward kustom &amp; anti-fraud.</div>
        </div>
        <button type="button" class="pw-adm-btn" onclick="document.getElementById('addPartnerModal').style.display='flex'">+ Tambah Partner</button>
    </div>

    <div class="pw-table-wrap">
        <table class="pw-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Label</th>
                    <th>Kode Diskon</th>
                    <th>Reward</th>
                    <th>Min Level</th>
                    <th>Limit/Hari</th>
                    <th>Total Limit</th>
                    <th>IP Unik</th>
                    <th>Referral</th>
                    <th>Total Earned</th>
                    <th>Status</th>
                    <th>Sosmed</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($partners as $p)
                <tr>
                    <td>
                        @if($p->user)
                        <a href="{{ route('admin.members.show', $p->user->ID) }}" style="color:var(--pw-gold-light);font-weight:600;">{{ $p->user->name }}</a>
                        @else
                        <span style="color:var(--pw-text-muted);">-</span>
                        @endif
                    </td>
                    <td><span class="pw-badge" style="background:rgba(168,85,247,.15);color:#c084fc;">{{ $p->label }}</span></td>
                    <td>
                        @if($p->discount_code)
                        <code style="background:rgba(200,151,42,.12);color:var(--pw-gold-light);padding:2px 6px;border-radius:4px;font-size:.78rem;">{{ $p->discount_code }}</code>
                        @else
                        <span style="color:var(--pw-text-muted);font-size:.75rem;">—</span>
                        @endif
                    </td>
                    <td>
                        <strong style="color:var(--pw-gold-light);">{{ number_format($p->reward_amount) }}</strong>
                        <span style="font-size:.7rem;color:var(--pw-text-muted);">{{ $p->reward_type === 'cubi' ? 'Cubi' : ($p->reward_type === 'tunai' ? 'Rp' : 'Gold') }}</span>
                    </td>
                    <td style="text-align:center;">Lv.{{ $p->min_char_level }}</td>
                    <td style="text-align:center;">{{ $p->max_per_day }}</td>
                    <td style="text-align:center;">{{ $p->max_total ? number_format($p->max_total) : '&infin;' }}</td>
                    <td style="text-align:center;">
                        @if($p->ip_unique_only)
                        <span class="pw-badge pw-badge--success">Ya</span>
                        @else
                        <span class="pw-badge pw-badge--danger">Tidak</span>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        <span style="color:#7deba0;font-weight:600;">{{ $p->total_rewarded }}</span>
                        <span style="color:var(--pw-text-muted);">/{{ $p->total_referrals }}</span>
                    </td>
                    <td style="text-align:center;">
                        <strong style="color:var(--pw-gold-light);">{{ number_format($p->total_earned) }}</strong>
                    </td>
                    <td>
                        @if($p->is_active)
                        <span class="pw-badge pw-badge--success">Aktif</span>
                        @else
                        <span class="pw-badge pw-badge--danger">Nonaktif</span>
                        @endif
                    </td>
                    {{-- Sosmed: wrap in div to avoid td flex layout bug --}}
                    <td>
                        <div style="display:flex;gap:.35rem;align-items:center;">
                            @if($p->link_tiktok)
                            <a href="{{ $p->link_tiktok }}" target="_blank" rel="noopener" title="TikTok" style="color:#ee1d52;line-height:1;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1v-3.5a6.37 6.37 0 00-.79-.05A6.34 6.34 0 003.15 15.2a6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.34-6.34V8.84a8.27 8.27 0 003.76 1.14V6.69z"/></svg>
                            </a>
                            @endif
                            @if($p->link_youtube)
                            <a href="{{ $p->link_youtube }}" target="_blank" rel="noopener" title="YouTube" style="color:#ff0000;line-height:1;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M23.5 6.19a3.02 3.02 0 00-2.12-2.14C19.54 3.5 12 3.5 12 3.5s-7.54 0-9.38.55A3.02 3.02 0 00.5 6.19 31.67 31.67 0 000 12a31.67 31.67 0 00.5 5.81 3.02 3.02 0 002.12 2.14c1.84.55 9.38.55 9.38.55s7.54 0 9.38-.55a3.02 3.02 0 002.12-2.14A31.67 31.67 0 0024 12a31.67 31.67 0 00-.5-5.81zM9.55 15.57V8.43L15.82 12l-6.27 3.57z"/></svg>
                            </a>
                            @endif
                            @if($p->link_facebook)
                            <a href="{{ $p->link_facebook }}" target="_blank" rel="noopener" title="Facebook" style="color:#1877f2;line-height:1;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.07C24 5.41 18.63 0 12 0S0 5.41 0 12.07c0 6.02 4.39 11.01 10.13 11.93v-8.44H7.08v-3.49h3.04V9.41c0-3.02 1.79-4.69 4.53-4.69 1.31 0 2.68.24 2.68.24v2.97h-1.51c-1.49 0-1.95.93-1.95 1.89v2.26h3.33l-.53 3.49h-2.8v8.44C19.61 23.08 24 18.09 24 12.07z"/></svg>
                            </a>
                            @endif
                            @if(!$p->link_tiktok && !$p->link_youtube && !$p->link_facebook)
                            <span style="color:var(--pw-text-muted);font-size:.72rem;">—</span>
                            @endif
                        </div>
                    </td>
                    {{-- Aksi: wrap in div to fix alignment --}}
                    <td>
                        <div style="display:flex;gap:.3rem;align-items:center;">
                            <button type="button" class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--ghost"
                                onclick="openEditPartner({{ json_encode($p) }})">Edit</button>
                            <form action="{{ route('admin.referral.partner.delete', $p->id) }}" method="POST"
                                  data-confirm="Hapus Partner|Yakin ingin menghapus partner ini?">
                                @csrf @method('DELETE')
                                <button type="submit" class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--ghost" style="color:#e05252;">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="13" style="text-align:center;padding:2rem;color:var(--pw-text-muted);">Belum ada partner terdaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Permohonan Partner --}}
<div class="pw-adm-card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;flex-wrap:wrap;gap:.6rem;">
        <div style="display:flex;align-items:center;gap:.6rem;">
            <div style="font-weight:600;font-size:.95rem;">Permohonan Partner</div>
            @if($pendingCount > 0)
            <span class="pw-badge pw-badge--warning" style="font-size:.7rem;">{{ $pendingCount }} pending</span>
            @endif
        </div>
    </div>

    <div class="pw-table-wrap">
        <table class="pw-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Channel</th>
                    <th>Platform</th>
                    <th>Followers</th>
                    <th>Alasan</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $app)
                <tr>
                    <td>
                        @if($app->user)
                        <a href="{{ route('admin.members.show', $app->user->ID) }}" style="color:var(--pw-gold-light);font-weight:600;">{{ $app->user->name }}</a>
                        <div style="font-size:.68rem;color:var(--pw-text-muted);">{{ $app->user->email }}</div>
                        @else
                        <span style="color:var(--pw-text-muted);">—</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ $app->channel_url }}" target="_blank" rel="noopener" style="color:#60a5fa;font-size:.82rem;">{{ $app->channel_name }}</a>
                    </td>
                    <td><span class="pw-badge" style="background:rgba(168,85,247,.15);color:#c084fc;text-transform:capitalize;">{{ $app->platform }}</span></td>
                    <td style="text-align:center;">{{ number_format($app->followers_count) }}</td>
                    <td style="max-width:160px;font-size:.78rem;color:var(--pw-text-muted);">{{ Str::limit($app->reason, 60) }}</td>
                    <td style="font-size:.75rem;color:var(--pw-text-muted);white-space:nowrap;">{{ $app->created_at->format('d M Y H:i') }}</td>
                    <td>
                        @if($app->status === 'pending')
                        <span class="pw-badge pw-badge--warning">Pending</span>
                        @elseif($app->status === 'approved')
                        <span class="pw-badge pw-badge--success">Disetujui</span>
                        @else
                        <span class="pw-badge pw-badge--danger">Ditolak</span>
                        @endif
                    </td>
                    <td>
                        @if($app->status === 'pending')
                        <div style="display:flex;gap:.3rem;align-items:center;">
                            <button type="button" class="pw-adm-btn pw-adm-btn--sm" style="background:rgba(34,197,94,.15);color:#22c55e;border-color:rgba(34,197,94,.3);"
                                    onclick="openApproveModal({{ json_encode($app) }})">Terima</button>
                            <button type="button" class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--ghost" style="color:#e05252;"
                                    onclick="openRejectModal({{ $app->id }})">Tolak</button>
                        </div>
                        @else
                        <div style="font-size:.72rem;color:var(--pw-text-muted);">
                            {{ $app->reviewed_at?->format('d M Y') }}
                            @if($app->admin_notes)
                            <div style="margin-top:.2rem;font-style:italic;">{{ Str::limit($app->admin_notes, 40) }}</div>
                            @endif
                        </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:2rem;color:var(--pw-text-muted);">Belum ada permohonan partner.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ── MODALS ─────────────────────────────────────────────────── --}}

{{-- Approve Application Modal --}}
<div id="approveAppModal" style="display:none;position:fixed;inset:0;z-index:999;background:rgba(0,0,0,.6);align-items:center;justify-content:center;">
    <div class="pw-adm-card" style="width:100%;max-width:420px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
            <div style="font-weight:600;font-size:.95rem;">Terima Partner: <span id="approveAppName" style="color:#22c55e;"></span></div>
            <button type="button" onclick="document.getElementById('approveAppModal').style.display='none'"
                style="background:none;border:none;color:var(--pw-text-muted);cursor:pointer;font-size:1.2rem;">&times;</button>
        </div>
        <form method="POST" id="approveAppForm">
            @csrf
            <div style="padding:.65rem;background:rgba(34,197,94,.06);border:1px solid rgba(34,197,94,.15);border-radius:6px;font-size:.78rem;color:var(--pw-text-muted);margin-bottom:.8rem;">
                Menyetujui permohonan ini <strong style="color:var(--pw-text);">tidak</strong> otomatis membuat Partner.
                Setelah perjanjian disepakati, tambahkan via tombol <strong style="color:var(--pw-text);">Tambah Partner</strong>.
            </div>
            <div style="margin-bottom:.8rem;">
                <label class="pw-adm-label">Catatan Admin (opsional)</label>
                <textarea name="admin_notes" class="pw-adm-input" rows="2" placeholder="Catatan internal atau hasil diskusi dengan calon partner..."></textarea>
            </div>
            <button type="submit" class="pw-adm-btn" style="width:100%;background:rgba(34,197,94,.15);color:#22c55e;border-color:rgba(34,197,94,.3);">Setujui Permohonan</button>
        </form>
    </div>
</div>

{{-- Reject Application Modal --}}
<div id="rejectAppModal" style="display:none;position:fixed;inset:0;z-index:999;background:rgba(0,0,0,.6);align-items:center;justify-content:center;">
    <div class="pw-adm-card" style="width:100%;max-width:400px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
            <div style="font-weight:600;font-size:.95rem;color:#ef4444;">Tolak Permohonan</div>
            <button type="button" onclick="document.getElementById('rejectAppModal').style.display='none'"
                style="background:none;border:none;color:var(--pw-text-muted);cursor:pointer;font-size:1.2rem;">&times;</button>
        </div>
        <form method="POST" id="rejectAppForm">
            @csrf
            <div style="margin-bottom:.7rem;">
                <label class="pw-adm-label">Alasan Penolakan (opsional, terlihat oleh user)</label>
                <textarea name="admin_notes" class="pw-adm-input" rows="3" placeholder="Contoh: Followers belum mencukupi, coba lagi nanti..."></textarea>
            </div>
            <button type="submit" class="pw-adm-btn" style="width:100%;background:rgba(239,68,68,.15);color:#ef4444;border-color:rgba(239,68,68,.3);">Tolak Permohonan</button>
        </form>
    </div>
</div>

{{-- Add Partner Modal --}}
<div id="addPartnerModal" style="display:none;position:fixed;inset:0;z-index:999;background:rgba(0,0,0,.6);align-items:center;justify-content:center;">
    <div class="pw-adm-card" style="width:100%;max-width:480px;max-height:90vh;overflow-y:auto;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
            <div style="font-weight:600;font-size:.95rem;">Tambah Partner Baru</div>
            <button type="button" onclick="document.getElementById('addPartnerModal').style.display='none'"
                style="background:none;border:none;color:var(--pw-text-muted);cursor:pointer;font-size:1.2rem;">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.referral.partner.add') }}">
            @csrf
            <div style="margin-bottom:.7rem;position:relative;" x-data="userSearch()">
                <label class="pw-adm-label">Username</label>
                <input type="text" name="username" class="pw-adm-input" required
                    placeholder="Ketik minimal 2 huruf untuk mencari..."
                    x-model="query" @input.debounce.300ms="search()" @focus="open = results.length > 0"
                    @click.away="open = false" autocomplete="off">
                <div x-show="open && results.length > 0" x-cloak
                    style="position:absolute;left:0;right:0;top:100%;z-index:10;background:#1a1a24;border:1px solid rgba(255,255,255,.1);border-radius:6px;max-height:200px;overflow-y:auto;margin-top:2px;box-shadow:0 8px 24px rgba(0,0,0,.5);">
                    <template x-for="u in results" :key="u.ID">
                        <div @click="selectUser(u)"
                            style="padding:.5rem .75rem;cursor:pointer;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.04);"
                            onmouseover="this.style.background='rgba(184,157,79,.1)'" onmouseout="this.style.background='transparent'">
                            <div>
                                <span style="font-weight:600;color:var(--pw-gold-light);" x-text="u.name"></span>
                                <span style="font-size:.72rem;color:var(--pw-text-muted);margin-left:.4rem;" x-text="u.email"></span>
                            </div>
                            <span style="font-size:.65rem;background:rgba(184,157,79,.1);color:var(--pw-gold-light);border:1px solid rgba(184,157,79,.2);padding:1px 6px;border-radius:3px;" x-text="u.role"></span>
                        </div>
                    </template>
                </div>
                <div x-show="loading" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);color:var(--pw-text-muted);font-size:.72rem;">Mencari...</div>
            </div>
            <div style="margin-bottom:.7rem;">
                <label class="pw-adm-label">Label</label>
                <select name="label" class="pw-adm-input">
                    <option value="Streamer">Streamer</option>
                    <option value="Content Creator">Content Creator</option>
                    <option value="Partner">Partner</option>
                    <option value="Influencer">Influencer</option>
                </select>
            </div>
            <div style="margin-bottom:.7rem;">
                <label class="pw-adm-label">Kode Diskon Cubi Shop (opsional)</label>
                <input type="text" name="discount_code" class="pw-adm-input" placeholder="Contoh: THEADISC10" maxlength="30" style="text-transform:uppercase;">
                <div style="font-size:.68rem;color:var(--pw-text-muted);margin-top:.2rem;">4–30 karakter alfanumerik.</div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.6rem;margin-bottom:.7rem;">
                <div>
                    <label class="pw-adm-label">Jumlah Reward</label>
                    <input type="number" name="reward_amount" class="pw-adm-input" value="20000" min="1" max="999999" required>
                </div>
                <div>
                    <label class="pw-adm-label">Tipe Reward</label>
                    <select name="reward_type" class="pw-adm-input">
                        <option value="gold">Gold Points</option>
                        <option value="cubi">Cubi Gold (Game)</option>
                        <option value="tunai">Tunai (Rupiah)</option>
                    </select>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:.6rem;margin-bottom:.7rem;">
                <div>
                    <label class="pw-adm-label">Min Char Level</label>
                    <input type="number" name="min_char_level" class="pw-adm-input" value="20" min="1" max="150" required>
                </div>
                <div>
                    <label class="pw-adm-label">Maks/Hari</label>
                    <input type="number" name="max_per_day" class="pw-adm-input" value="10" min="1" max="1000" required>
                </div>
                <div>
                    <label class="pw-adm-label">Total Maks</label>
                    <input type="number" name="max_total" class="pw-adm-input" placeholder="Kosong = ∞" min="1">
                </div>
            </div>
            <div style="margin-bottom:.7rem;padding:.6rem .8rem;background:rgba(184,157,79,.06);border:1px solid rgba(184,157,79,.15);border-radius:6px;font-size:.72rem;color:var(--pw-text-muted);">
                <strong style="color:var(--pw-gold-light);">Info Rate Komisi:</strong><br>
                Gold Points: 1 Gold = Rp {{ number_format(config('pw-config.currency.rate_idr', 10000), 0, ',', '.') }} &nbsp;&middot;&nbsp;
                Cubi Gold: 1 Cubi = Rp {{ number_format(config('pw-config.currency.cubi_rate_idr', 1000), 0, ',', '.') }} &nbsp;&middot;&nbsp;
                Tunai: dibayar manual.
            </div>
            <div style="margin-bottom:.7rem;">
                <label style="display:flex;align-items:center;gap:.5rem;font-size:.82rem;cursor:pointer;">
                    <input type="checkbox" name="ip_unique_only" value="1" checked style="accent-color:var(--pw-gold);">
                    IP Unique Only (1 IP = 1 referral, cegah multi-akun)
                </label>
            </div>
            <div style="margin-bottom:.7rem;">
                <label class="pw-adm-label">Catatan (opsional)</label>
                <textarea name="notes" class="pw-adm-input" rows="2" placeholder="Info channel, perjanjian, dll."></textarea>
            </div>
            <div style="border-top:1px solid rgba(255,255,255,.06);padding-top:.7rem;margin-bottom:.7rem;">
                <div style="font-size:.78rem;color:var(--pw-text-muted);margin-bottom:.5rem;font-weight:600;">Link Sosial Media</div>
                <div style="margin-bottom:.5rem;">
                    <label class="pw-adm-label">TikTok</label>
                    <input type="url" name="link_tiktok" class="pw-adm-input" placeholder="https://tiktok.com/@username">
                </div>
                <div style="margin-bottom:.5rem;">
                    <label class="pw-adm-label">YouTube</label>
                    <input type="url" name="link_youtube" class="pw-adm-input" placeholder="https://youtube.com/@channel">
                </div>
                <div style="margin-bottom:.5rem;">
                    <label class="pw-adm-label">Facebook</label>
                    <input type="url" name="link_facebook" class="pw-adm-input" placeholder="https://facebook.com/page">
                </div>
            </div>
            <button type="submit" class="pw-adm-btn" style="width:100%;">Tambah Partner</button>
        </form>
    </div>
</div>

{{-- Edit Partner Modal --}}
<div id="editPartnerModal" style="display:none;position:fixed;inset:0;z-index:999;background:rgba(0,0,0,.6);align-items:center;justify-content:center;">
    <div class="pw-adm-card" style="width:100%;max-width:480px;max-height:90vh;overflow-y:auto;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
            <div style="font-weight:600;font-size:.95rem;">Edit Partner: <span id="editPartnerName" style="color:var(--pw-gold-light);"></span></div>
            <button type="button" onclick="document.getElementById('editPartnerModal').style.display='none'"
                style="background:none;border:none;color:var(--pw-text-muted);cursor:pointer;font-size:1.2rem;">&times;</button>
        </div>
        <form method="POST" id="editPartnerForm">
            @csrf @method('PUT')
            <div style="margin-bottom:.7rem;">
                <label class="pw-adm-label">Label</label>
                <select name="label" id="ep_label" class="pw-adm-input">
                    <option value="Streamer">Streamer</option>
                    <option value="Content Creator">Content Creator</option>
                    <option value="Partner">Partner</option>
                    <option value="Influencer">Influencer</option>
                </select>
            </div>
            <div style="margin-bottom:.7rem;">
                <label class="pw-adm-label">Kode Diskon Cubi Shop</label>
                <input type="text" name="discount_code" id="ep_discount_code" class="pw-adm-input" placeholder="Contoh: THEADISC10" maxlength="30" style="text-transform:uppercase;">
                <div style="font-size:.68rem;color:var(--pw-text-muted);margin-top:.2rem;">Kosongkan untuk menghapus kode diskon.</div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.6rem;margin-bottom:.7rem;">
                <div>
                    <label class="pw-adm-label">Jumlah Reward</label>
                    <input type="number" name="reward_amount" id="ep_reward_amount" class="pw-adm-input" min="1" max="999999" required>
                </div>
                <div>
                    <label class="pw-adm-label">Tipe Reward</label>
                    <select name="reward_type" id="ep_reward_type" class="pw-adm-input">
                        <option value="gold">Gold Points</option>
                        <option value="cubi">Cubi Gold (Game)</option>
                        <option value="tunai">Tunai (Rupiah)</option>
                    </select>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:.6rem;margin-bottom:.7rem;">
                <div>
                    <label class="pw-adm-label">Min Char Level</label>
                    <input type="number" name="min_char_level" id="ep_min_char_level" class="pw-adm-input" min="1" max="150" required>
                </div>
                <div>
                    <label class="pw-adm-label">Maks/Hari</label>
                    <input type="number" name="max_per_day" id="ep_max_per_day" class="pw-adm-input" min="1" max="1000" required>
                </div>
                <div>
                    <label class="pw-adm-label">Total Maks</label>
                    <input type="number" name="max_total" id="ep_max_total" class="pw-adm-input" placeholder="Kosong = ∞" min="1">
                </div>
            </div>
            <div style="margin-bottom:.7rem;display:flex;gap:1.5rem;">
                <label style="display:flex;align-items:center;gap:.5rem;font-size:.82rem;cursor:pointer;">
                    <input type="checkbox" name="ip_unique_only" id="ep_ip_unique" value="1" style="accent-color:var(--pw-gold);">
                    IP Unique Only
                </label>
                <label style="display:flex;align-items:center;gap:.5rem;font-size:.82rem;cursor:pointer;">
                    <input type="checkbox" name="is_active" id="ep_is_active" value="1" style="accent-color:var(--pw-gold);">
                    Aktif
                </label>
            </div>
            <div style="margin-bottom:.7rem;">
                <label class="pw-adm-label">Catatan</label>
                <textarea name="notes" id="ep_notes" class="pw-adm-input" rows="2"></textarea>
            </div>
            <div style="border-top:1px solid rgba(255,255,255,.06);padding-top:.7rem;margin-bottom:.7rem;">
                <div style="font-size:.78rem;color:var(--pw-text-muted);margin-bottom:.5rem;font-weight:600;">Link Sosial Media</div>
                <div style="margin-bottom:.5rem;">
                    <label class="pw-adm-label">TikTok</label>
                    <input type="url" name="link_tiktok" id="ep_link_tiktok" class="pw-adm-input" placeholder="https://tiktok.com/@username">
                </div>
                <div style="margin-bottom:.5rem;">
                    <label class="pw-adm-label">YouTube</label>
                    <input type="url" name="link_youtube" id="ep_link_youtube" class="pw-adm-input" placeholder="https://youtube.com/@channel">
                </div>
                <div style="margin-bottom:.5rem;">
                    <label class="pw-adm-label">Facebook</label>
                    <input type="url" name="link_facebook" id="ep_link_facebook" class="pw-adm-input" placeholder="https://facebook.com/page">
                </div>
            </div>
            <button type="submit" class="pw-adm-btn" style="width:100%;">Simpan Perubahan</button>
        </form>
    </div>
</div>

<script>
function userSearch() {
    return {
        query: '',
        results: [],
        open: false,
        loading: false,
        async search() {
            if (this.query.length < 2) { this.results = []; this.open = false; return; }
            this.loading = true;
            try {
                const res = await fetch('{{ route("admin.api.users.search") }}?q=' + encodeURIComponent(this.query));
                this.results = await res.json();
                this.open = this.results.length > 0;
            } catch (e) { this.results = []; }
            this.loading = false;
        },
        selectUser(u) {
            this.query = u.name;
            this.open = false;
            this.results = [];
        }
    };
}

function openApproveModal(app) {
    document.getElementById('approveAppModal').style.display = 'flex';
    document.getElementById('approveAppName').textContent = app.user ? app.user.name : 'User #' + app.user_id;
    document.getElementById('approveAppForm').action = '{{ url("admin/referral/application") }}/' + app.id + '/approve';
}

function openRejectModal(appId) {
    document.getElementById('rejectAppModal').style.display = 'flex';
    document.getElementById('rejectAppForm').action = '{{ url("admin/referral/application") }}/' + appId + '/reject';
}

function openEditPartner(p) {
    document.getElementById('editPartnerModal').style.display = 'flex';
    document.getElementById('editPartnerName').textContent = p.user ? p.user.name : 'Partner #' + p.id;
    document.getElementById('editPartnerForm').action = '{{ url("admin/referral/partner") }}/' + p.id;
    document.getElementById('ep_label').value = p.label;
    document.getElementById('ep_discount_code').value = p.discount_code || '';
    document.getElementById('ep_reward_amount').value = p.reward_amount;
    document.getElementById('ep_reward_type').value = p.reward_type;
    document.getElementById('ep_min_char_level').value = p.min_char_level;
    document.getElementById('ep_max_per_day').value = p.max_per_day;
    document.getElementById('ep_max_total').value = p.max_total || '';
    document.getElementById('ep_ip_unique').checked = !!p.ip_unique_only;
    document.getElementById('ep_is_active').checked = !!p.is_active;
    document.getElementById('ep_notes').value = p.notes || '';
    document.getElementById('ep_link_tiktok').value = p.link_tiktok || '';
    document.getElementById('ep_link_youtube').value = p.link_youtube || '';
    document.getElementById('ep_link_facebook').value = p.link_facebook || '';
}
</script>

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
