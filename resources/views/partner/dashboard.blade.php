@extends('layouts.partner')
@section('title', 'Dashboard Partner')

@section('content')

@if(! $partner)
{{-- Partner record not found / inactive --}}
<div style="text-align:center;padding:3rem 1rem;">
    <svg viewBox="0 0 60 60" fill="none" width="60" height="60" style="margin:0 auto 1rem;">
        <circle cx="30" cy="30" r="28" stroke="rgba(255,255,255,.1)" stroke-width="2"/>
        <path d="M20 35c1.5 3 5 5 10 5s8.5-2 10-5" stroke="rgba(255,255,255,.2)" stroke-width="2" stroke-linecap="round"/>
        <circle cx="22" cy="24" r="2" fill="rgba(255,255,255,.2)"/>
        <circle cx="38" cy="24" r="2" fill="rgba(255,255,255,.2)"/>
    </svg>
    <h2 style="font-size:1.1rem;color:var(--pw-text);margin-bottom:.5rem;">Akun Partner Tidak Aktif</h2>
    <p style="font-size:.82rem;color:var(--pw-text-muted);max-width:360px;margin:0 auto;">
        Akun partner kamu belum diaktifkan atau belum terdaftar. Hubungi Administrator untuk informasi lebih lanjut.
    </p>
</div>
@else

{{-- ── REFERRAL CODE & LINK ──────────────────────────────────── --}}
<div id="partner-referral-card"
     x-data="{
        copiedCode: false,
        copiedLink: false,
        toast: '',
        copyText(text, type) {
            const fallback = (t) => {
                const ta = document.createElement('textarea');
                ta.value = t; ta.style.position='fixed'; ta.style.opacity='0';
                document.body.appendChild(ta); ta.focus(); ta.select();
                document.execCommand('copy');
                document.body.removeChild(ta);
            };
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).catch(() => fallback(text));
            } else { fallback(text); }
            if (type === 'code') { this.copiedCode = true; setTimeout(() => this.copiedCode = false, 2000); }
            else { this.copiedLink = true; setTimeout(() => this.copiedLink = false, 2000); }
            this.toast = type === 'code' ? 'Kode referral disalin!' : 'Link referral disalin!';
            setTimeout(() => this.toast = '', 2500);
        }
     }"
     style="background:linear-gradient(135deg,rgba(34,197,94,.08),rgba(34,197,94,.02));
            border:1px solid rgba(34,197,94,.2);border-radius:10px;padding:1.2rem 1.5rem;margin-bottom:1.5rem;position:relative;">

    {{-- Toast --}}
    <div x-show="toast" x-transition.opacity
         style="position:absolute;top:.75rem;right:.75rem;background:#15803d;color:#fff;
                font-size:.75rem;font-weight:600;padding:.35rem .85rem;border-radius:6px;
                display:flex;align-items:center;gap:.4rem;z-index:10;pointer-events:none;">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
        <span x-text="toast"></span>
    </div>

    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem;">
        <div>
            <div style="font-size:.7rem;text-transform:uppercase;letter-spacing:.08em;color:var(--pw-text-muted);margin-bottom:.3rem;">Kode Referral Kamu</div>
            <div style="display:flex;align-items:center;gap:.6rem;">
                <div class="ref-code" style="font-family:monospace;font-size:1.4rem;font-weight:700;color:#22c55e;letter-spacing:.1em;">
                    {{ auth()->user()->referral_code }}
                </div>
                <button @click="copyText('{{ auth()->user()->referral_code }}', 'code')"
                        class="ref-copy-btn"
                        style="display:inline-flex;align-items:center;gap:.25rem;background:none;
                               border:none;padding:.2rem .1rem;
                               color:#22c55e;font-size:.72rem;font-weight:600;cursor:pointer;opacity:.8;"
                        @mouseenter="$el.style.opacity='1'" @mouseleave="$el.style.opacity='.8'">
                    <svg x-show="!copiedCode" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                    <svg x-show="copiedCode" x-cloak width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                    <span x-text="copiedCode ? 'Copied!' : 'Copy'"></span>
                </button>
            </div>
        </div>
        <div style="text-align:right;">
            <div style="font-size:.7rem;color:var(--pw-text-muted);margin-bottom:.2rem;">Label Partner</div>
            <span style="font-size:.85rem;color:var(--pw-gold);font-weight:600;">{{ $partner->label }}</span>
        </div>
    </div>

    {{-- Referral Link --}}
    <div class="ref-divider" style="margin-top:1rem;padding-top:.8rem;border-top:1px solid rgba(34,197,94,.15);">
        <div style="font-size:.7rem;text-transform:uppercase;letter-spacing:.08em;color:var(--pw-text-muted);margin-bottom:.4rem;">Link Referral</div>
        <div style="display:flex;align-items:center;gap:.5rem;">
            <input type="text" readonly
                   value="{{ route('register', ['ref' => auth()->user()->referral_code]) }}"
                   class="ref-link-input"
                   style="flex:1;background:rgba(0,0,0,.3);border:1px solid rgba(34,197,94,.2);border-radius:6px;
                          padding:.5rem .75rem;font-size:.8rem;font-family:monospace;color:#22c55e;outline:none;cursor:default;"
                   id="referralLinkInput" readonly>
            <button @click="copyText('{{ route('register', ['ref' => auth()->user()->referral_code]) }}', 'link')"
                    class="ref-copy-btn"
                    style="display:inline-flex;align-items:center;gap:.35rem;background:none;
                           border:none;padding:.4rem .1rem;
                           color:#22c55e;font-size:.75rem;font-weight:600;cursor:pointer;
                           white-space:nowrap;flex-shrink:0;opacity:.8;"
                    @mouseenter="$el.style.opacity='1'" @mouseleave="$el.style.opacity='.8'">
                <svg x-show="!copiedLink" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                <svg x-show="copiedLink" x-cloak width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                <span x-text="copiedLink ? 'Copied!' : 'Copy'"></span>
            </button>
        </div>
    </div>
</div>

{{-- ── DISCOUNT CODE (CUBI SHOP) ─────────────────────────────── --}}
<div style="background:linear-gradient(135deg,rgba(200,151,42,.08),rgba(200,151,42,.02));
            border:1px solid rgba(200,151,42,.2);border-radius:10px;padding:1.2rem 1.5rem;margin-bottom:1.5rem;"
     x-data="{
        editing: false,
        code: '{{ $partner->discount_code ?? '' }}',
        saving: false,
        message: '',
        msgType: '',
        copiedDC: false,
        save() {
            this.saving = true;
            this.message = '';
            fetch('{{ route('partner.discount-code.update') }}', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: JSON.stringify({ discount_code: this.code })
            })
            .then(r => r.json().then(d => ({ ok: r.ok, data: d })))
            .then(({ ok, data }) => {
                this.saving = false;
                if (ok) {
                    this.editing = false;
                    this.message = data.message || 'Berhasil disimpan!';
                    this.msgType = 'success';
                } else {
                    this.message = data.errors?.discount_code?.[0] || data.message || 'Gagal menyimpan.';
                    this.msgType = 'error';
                }
                setTimeout(() => this.message = '', 4000);
            })
            .catch(() => { this.saving = false; this.message = 'Terjadi kesalahan.'; this.msgType = 'error'; setTimeout(() => this.message = '', 4000); });
        }
     }">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem;">
        <div>
            <div style="font-size:.7rem;text-transform:uppercase;letter-spacing:.08em;color:var(--pw-text-muted);margin-bottom:.3rem;">Kode Diskon Cubi Shop</div>
            <template x-if="!editing">
                <div style="display:flex;align-items:center;gap:.75rem;">
                    <div style="font-family:monospace;font-size:1.4rem;font-weight:700;color:var(--pw-gold);letter-spacing:.1em;"
                         x-text="code || '—'"></div>
                    <template x-if="code">
                        <button @click="(function(t){if(navigator.clipboard&&window.isSecureContext){navigator.clipboard.writeText(t).catch(()=>{var ta=document.createElement('textarea');ta.value=t;ta.style.position='fixed';ta.style.opacity='0';document.body.appendChild(ta);ta.focus();ta.select();document.execCommand('copy');document.body.removeChild(ta);})}else{var ta=document.createElement('textarea');ta.value=t;ta.style.position='fixed';ta.style.opacity='0';document.body.appendChild(ta);ta.focus();ta.select();document.execCommand('copy');document.body.removeChild(ta);}copiedDC=true;setTimeout(()=>copiedDC=false,2000)})(code)"
                                style="display:inline-flex;align-items:center;gap:.25rem;background:none;
                                       border:none;padding:.2rem .1rem;
                                       color:var(--pw-gold);font-size:.7rem;font-weight:600;cursor:pointer;opacity:.8;"
                                @mouseenter="$el.style.opacity='1'" @mouseleave="$el.style.opacity='.8'">
                            <svg x-show="!copiedDC" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                            <svg x-show="copiedDC" x-cloak width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                            <span x-text="copiedDC ? 'Copied!' : 'Copy'"></span>
                        </button>
                    </template>
                </div>
            </template>
        </div>
        <button @click="editing=!editing"
                style="background:rgba(200,151,42,.12);border:1px solid rgba(200,151,42,.25);border-radius:6px;
                       padding:.4rem .8rem;color:var(--pw-gold);font-size:.75rem;font-weight:600;cursor:pointer;transition:all .2s;"
                x-text="editing ? 'Batal' : (code ? 'Ubah Kode' : 'Set Kode Diskon')"></button>
    </div>

    <template x-if="editing">
        <div style="margin-top:1rem;padding-top:.8rem;border-top:1px solid rgba(200,151,42,.15);">
            <div style="font-size:.7rem;color:var(--pw-text-muted);margin-bottom:.4rem;">Masukkan kode diskon (4–30 karakter, huruf & angka saja)</div>
            <div style="display:flex;align-items:center;gap:.5rem;">
                <input type="text" x-model="code" maxlength="30" placeholder="Contoh: THEADISC10"
                       @keydown.enter.prevent="save()"
                       class="dc-code-input"
                       style="flex:1;background:rgba(0,0,0,.3);border:1px solid rgba(200,151,42,.2);border-radius:6px;
                              padding:.5rem .75rem;font-size:.85rem;font-family:monospace;color:var(--pw-gold);
                              text-transform:uppercase;outline:none;">
                <button @click="save()" :disabled="saving || code.length < 4"
                        class="pw-btn pw-btn--gold pw-btn--sm"
                        style="white-space:nowrap;padding:.55rem 1.2rem;font-size:.78rem;"
                        :style="(saving || code.length < 4) && 'opacity:.5;cursor:not-allowed'">
                    <span x-show="!saving">Simpan</span>
                    <span x-show="saving" x-cloak>Menyimpan...</span>
                </button>
            </div>
        </div>
    </template>

    <div x-show="message" x-cloak
         style="margin-top:.6rem;font-size:.78rem;font-weight:500;padding:.4rem .7rem;border-radius:6px;"
         :style="msgType === 'success'
            ? 'color:#22c55e;background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.2)'
            : 'color:#ef4444;background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.2)'"
         x-text="message"></div>

    <div style="margin-top:.7rem;font-size:.72rem;color:var(--pw-text-muted);line-height:1.4;">
        Kode diskon digunakan oleh viewer di <strong>Cubi Shop</strong> untuk mendapat potongan harga.
        Berbeda dari <em>Link Referral</em> yang digunakan untuk mengajak user baru mendaftar.
    </div>
</div>

{{-- ── STATS CARDS ─────────────────────────────────────────── --}}
<div class="pw-partner-stats-grid" style="display:grid;grid-template-columns:repeat(6,1fr);gap:1rem;margin-bottom:1.5rem;">
    <div class="pw-adm-stat">
        <div class="pw-adm-stat__icon" style="color:#60a5fa;">
            <svg viewBox="0 0 20 20" fill="none" width="22"><circle cx="8" cy="7" r="3.5" stroke="currentColor" stroke-width="1.5"/><path d="M2 17c0-3 2.7-5.5 6-5.5s6 2.5 6 5.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M14 8l2 2 3-3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </div>
        <div class="pw-adm-stat__value">{{ number_format($totalReferrals) }}</div>
        <div class="pw-adm-stat__label">Jumlah Pengguna</div>
    </div>
    <div class="pw-adm-stat">
        <div class="pw-adm-stat__icon" style="color:#22c55e;">
            <svg viewBox="0 0 20 20" fill="none" width="22"><path d="M10 2a8 8 0 110 16 8 8 0 010-16z" stroke="currentColor" stroke-width="1.5"/><path d="M7 10h6M10 7v6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        </div>
        <div class="pw-adm-stat__value">{{ $currencyLabel === 'IDR' ? 'Rp ' : '' }}{{ number_format($totalCommission) }} <span style="font-size:.65rem;color:var(--pw-text-muted);">{{ $currencyLabel !== 'IDR' ? $currencyLabel : '' }}</span></div>
        <div class="pw-adm-stat__label">Penghasilan</div>
    </div>
    <div class="pw-adm-stat">
        <div class="pw-adm-stat__icon" style="color:#f59e0b;">
            <svg viewBox="0 0 20 20" fill="none" width="22"><circle cx="10" cy="10" r="7" stroke="currentColor" stroke-width="1.5"/><path d="M10 6v4l2.5 2.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        </div>
        <div class="pw-adm-stat__value">{{ $currencyLabel === 'IDR' ? 'Rp ' : '' }}{{ number_format($pendingCommission) }} <span style="font-size:.65rem;color:var(--pw-text-muted);">{{ $currencyLabel !== 'IDR' ? $currencyLabel : '' }}</span></div>
        <div class="pw-adm-stat__label">Komisi Pending</div>
    </div>
    <div class="pw-adm-stat">
        <div class="pw-adm-stat__icon" style="color:#a855f7;">
            <svg viewBox="0 0 20 20" fill="none" width="22"><path d="M4 14v2M8 10v6M12 7v9M16 4v12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        </div>
        <div class="pw-adm-stat__value">{{ $currencyLabel === 'IDR' ? 'Rp ' : '' }}{{ number_format($monthCommission) }} <span style="font-size:.65rem;color:var(--pw-text-muted);">{{ $currencyLabel !== 'IDR' ? $currencyLabel : '' }}</span></div>
        <div class="pw-adm-stat__label">Komisi Bulan Ini</div>
    </div>
    {{-- Card 5: Count of users using referral link --}}
    <div class="pw-adm-stat">
        <div class="pw-adm-stat__icon" style="color:#38bdf8;">
            <svg viewBox="0 0 20 20" fill="none" width="22"><path d="M13 7H7a4 4 0 000 8h1M7 13h6a4 4 0 000-8h-1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        </div>
        <div class="pw-adm-stat__value" style="color:#38bdf8;">{{ number_format($totalReferrals) }}</div>
        <div class="pw-adm-stat__label">Pengguna via Link</div>
    </div>
    {{-- Card 6: Count of transactions using discount code --}}
    <div class="pw-adm-stat">
        <div class="pw-adm-stat__icon" style="color:#f472b6;">
            <svg viewBox="0 0 20 20" fill="none" width="22"><rect x="2" y="5" width="16" height="11" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M7 9h2M11 9h2M7 12h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        </div>
        <div class="pw-adm-stat__value" style="color:#f472b6;">{{ number_format($totalTransactions) }}</div>
        <div class="pw-adm-stat__label">Transaksi Kode Diskon</div>
    </div>
</div>

{{-- ── PARTNER INFO ────────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem;">
    <div class="pw-adm-card" style="margin-bottom:0;">
        <div class="pw-adm-card__title">
            <svg viewBox="0 0 20 20" fill="none" width="15"><circle cx="10" cy="10" r="7" stroke="currentColor" stroke-width="1.5"/><path d="M10 7v3l2 2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            Info Partner
        </div>
        <div style="display:grid;gap:.5rem;font-size:.82rem;">
            <div style="display:flex;justify-content:space-between;">
                <span style="color:var(--pw-text-muted);">Status</span>
                <span style="color:{{ $partner->is_active ? '#22c55e' : '#ef4444' }};">{{ $partner->is_active ? 'Aktif' : 'Nonaktif' }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;">
                <span style="color:var(--pw-text-muted);">Reward per Referral</span>
                <span style="color:var(--pw-text);">{{ number_format($partner->reward_amount) }} {{ $partner->reward_type === 'gold' ? 'Gold' : ($partner->reward_type === 'cubi' ? 'Cubi' : 'Rupiah') }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;">
                <span style="color:var(--pw-text-muted);">Min Level Karakter</span>
                <span style="color:var(--pw-text);">Lv. {{ $partner->min_char_level }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;">
                <span style="color:var(--pw-text-muted);">Maks per Hari</span>
                <span style="color:var(--pw-text);">{{ $partner->max_per_day }}</span>
            </div>
            @if($partner->max_total)
            <div style="display:flex;justify-content:space-between;">
                <span style="color:var(--pw-text-muted);">Maks Total</span>
                <span style="color:var(--pw-text);">{{ number_format($partner->max_total) }}</span>
            </div>
            @endif
        </div>
    </div>

    <div class="pw-adm-card" style="margin-bottom:0;"
         x-data="{
             editing: false,
             tiktok: '{{ addslashes($partner->link_tiktok ?? '') }}',
             youtube: '{{ addslashes($partner->link_youtube ?? '') }}',
             facebook: '{{ addslashes($partner->link_facebook ?? '') }}',
             saving: false,
             message: '',
             msgType: '',
             save() {
                 this.saving = true; this.message = '';
                 fetch('{{ route('partner.social-media.update') }}', {
                     method: 'PUT',
                     headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                     body: JSON.stringify({ link_tiktok: this.tiktok, link_youtube: this.youtube, link_facebook: this.facebook })
                 })
                 .then(r => r.json().then(d => ({ ok: r.ok, data: d })))
                 .then(({ ok, data }) => {
                     this.saving = false;
                     if (ok) { this.editing = false; this.message = data.message || 'Tersimpan!'; this.msgType = 'success'; }
                     else { this.message = data.message || 'Gagal menyimpan.'; this.msgType = 'error'; }
                     setTimeout(() => this.message = '', 4000);
                 })
                 .catch(() => { this.saving = false; this.message = 'Terjadi kesalahan.'; this.msgType = 'error'; setTimeout(() => this.message = '', 4000); });
             }
         }">
        <div class="pw-adm-card__title" style="justify-content:space-between;">
            <span style="display:flex;align-items:center;gap:.4rem;">
                <svg viewBox="0 0 20 20" fill="none" width="15"><path d="M10 2a8 8 0 100 16 8 8 0 000-16z" stroke="currentColor" stroke-width="1.5"/><path d="M14 10a4 4 0 11-8 0 4 4 0 018 0z" stroke="currentColor" stroke-width="1.5"/></svg>
                Social Media
            </span>
            <button @click="editing=!editing"
                    style="background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);border-radius:5px;
                           padding:.25rem .6rem;color:var(--pw-text-muted);font-size:.7rem;font-weight:600;cursor:pointer;"
                    x-text="editing ? 'Batal' : 'Edit'"></button>
        </div>

        {{-- Display mode --}}
        <template x-if="!editing">
            <div style="display:grid;gap:.5rem;font-size:.82rem;">
                <a :href="tiktok || '#'" x-show="tiktok" target="_blank" rel="noopener" style="display:flex;align-items:center;gap:.5rem;color:#60a5fa;text-decoration:none;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.88-2.88 2.89 2.89 0 012.88-2.88c.3 0 .58.04.85.11v-3.5a6.37 6.37 0 00-.85-.06A6.34 6.34 0 003.15 15.2a6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.34-6.34V9.27a8.16 8.16 0 004.76 1.52V7.35a4.82 4.82 0 01-1-.66z"/></svg>
                    TikTok
                </a>
                <a :href="youtube || '#'" x-show="youtube" target="_blank" rel="noopener" style="display:flex;align-items:center;gap:.5rem;color:#ef4444;text-decoration:none;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    YouTube
                </a>
                <a :href="facebook || '#'" x-show="facebook" target="_blank" rel="noopener" style="display:flex;align-items:center;gap:.5rem;color:#3b82f6;text-decoration:none;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    Facebook
                </a>
                <span x-show="!tiktok && !youtube && !facebook" style="color:var(--pw-text-muted);font-size:.78rem;">Belum ada link social media.</span>
            </div>
        </template>

        {{-- Edit mode --}}
        <template x-if="editing">
            <div style="display:grid;gap:.7rem;margin-top:.5rem;">
                <div>
                    <label style="font-size:.7rem;color:var(--pw-text-muted);display:block;margin-bottom:.3rem;">TikTok URL</label>
                    <input type="url" x-model="tiktok" placeholder="https://tiktok.com/@username" class="sm-input"
                           style="width:100%;background:rgba(0,0,0,.3);border:1px solid rgba(255,255,255,.12);border-radius:6px;
                                  padding:.45rem .7rem;font-size:.8rem;color:var(--pw-text);outline:none;box-sizing:border-box;">
                </div>
                <div>
                    <label style="font-size:.7rem;color:var(--pw-text-muted);display:block;margin-bottom:.3rem;">YouTube URL</label>
                    <input type="url" x-model="youtube" placeholder="https://youtube.com/@channel" class="sm-input"
                           style="width:100%;background:rgba(0,0,0,.3);border:1px solid rgba(255,255,255,.12);border-radius:6px;
                                  padding:.45rem .7rem;font-size:.8rem;color:var(--pw-text);outline:none;box-sizing:border-box;">
                </div>
                <div>
                    <label style="font-size:.7rem;color:var(--pw-text-muted);display:block;margin-bottom:.3rem;">Facebook URL</label>
                    <input type="url" x-model="facebook" placeholder="https://facebook.com/page" class="sm-input"
                           style="width:100%;background:rgba(0,0,0,.3);border:1px solid rgba(255,255,255,.12);border-radius:6px;
                                  padding:.45rem .7rem;font-size:.8rem;color:var(--pw-text);outline:none;box-sizing:border-box;">
                </div>
                <button @click="save()" :disabled="saving"
                        class="pw-btn pw-btn--gold pw-btn--sm" style="width:100%;font-size:.78rem;"
                        :style="saving && 'opacity:.6;cursor:not-allowed'">
                    <span x-show="!saving">Simpan</span>
                    <span x-show="saving" x-cloak>Menyimpan...</span>
                </button>
            </div>
        </template>

        <div x-show="message" x-cloak
             style="margin-top:.6rem;font-size:.78rem;font-weight:500;padding:.4rem .7rem;border-radius:6px;"
             :style="msgType === 'success'
                ? 'color:#22c55e;background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.2)'
                : 'color:#ef4444;background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.2)'"
             x-text="message"></div>

        @if($partner->notes)
        <div style="margin-top:1rem;padding-top:.8rem;border-top:1px solid var(--pw-border);">
            <div style="font-size:.7rem;color:var(--pw-text-muted);margin-bottom:.3rem;">Catatan</div>
            <div style="font-size:.78rem;color:var(--pw-text);">{{ $partner->notes }}</div>
        </div>
        @endif
    </div>
</div>

{{-- ── TRANSACTION HISTORY (Cubi Sales) ────────────────────── --}}
<div class="pw-adm-card">
    <div class="pw-adm-card__title">
        <svg viewBox="0 0 20 20" fill="none" width="15"><path d="M4 14v2M8 10v6M12 7v9M16 4v12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        Riwayat Komisi dari Cubi Shop
    </div>

    @if($transactions->isEmpty())
    <div style="text-align:center;padding:2rem 1rem;color:var(--pw-text-muted);font-size:.82rem;">
        Belum ada transaksi komisi.
    </div>
    @else
    <div class="pw-table-wrap">
        <table class="pw-table">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Pembeli</th>
                    <th>Kode Referral</th>
                    <th style="text-align:right;">Nominal</th>
                    <th style="text-align:right;">Komisi (%)</th>
                    <th style="text-align:right;">Komisi ({{ $currencyLabel }})</th>
                    <th style="text-align:center;">Status</th>
                    <th style="text-align:right;">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $tx)
                <tr>
                    <td style="font-family:monospace;">{{ $tx->invoice_number }}</td>
                    <td>{{ $tx->user->name ?? '-' }}</td>
                    <td style="font-family:monospace;font-size:.78rem;color:var(--pw-gold);">{{ $tx->refcode ?: '—' }}</td>
                    <td style="text-align:right;">Rp {{ number_format($tx->amount, 0, ',', '.') }}</td>
                    <td style="text-align:right;color:var(--pw-text-muted);">{{ $tx->commission_percent }}%</td>
                    <td style="text-align:right;color:#22c55e;font-weight:600;">
                        @if($currencyLabel === 'IDR')
                            Rp {{ number_format($tx->commission_amount, 0, ',', '.') }}
                        @elseif($currencyLabel === 'Cubi')
                            {{ number_format((int) floor($tx->commission_amount / config('pw-config.currency.cubi_rate_idr', 1000))) }}
                        @else
                            {{ number_format((int) floor($tx->commission_amount / config('pw-config.currency.rate_idr', 10000))) }}
                        @endif
                    </td>
                    <td style="text-align:center;">
                        @if($tx->commission_credited)
                        <span class="pw-badge pw-badge--success">Dikirim</span>
                        @else
                        <span class="pw-badge pw-badge--warning">Pending</span>
                        @endif
                    </td>
                    <td style="text-align:right;font-size:.78rem;color:var(--pw-text-muted);">{{ $tx->paid_at?->format('d M Y H:i') ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($transactions->hasPages())
    <div style="margin-top:1rem;">
        {{ $transactions->links() }}
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
@media(max-width:1100px){
    .pw-partner-stats-grid{grid-template-columns:repeat(3,1fr) !important;}
}
@media(max-width:640px){
    .pw-partner-stats-grid{grid-template-columns:repeat(2,1fr) !important;}
    .pw-adm-stat__value{font-size:1.1rem;}
}

/* ── Partner referral card – light mode ── */
[data-theme="light"] #partner-referral-card {
    background: linear-gradient(135deg, rgba(22,163,74,.1), rgba(22,163,74,.04)) !important;
    border-color: rgba(22,163,74,.4) !important;
}
[data-theme="light"] #partner-referral-card .ref-code {
    color: #15803d !important;
}
[data-theme="light"] #partner-referral-card .ref-link-input {
    background: #ffffff !important;
    border-color: rgba(22,163,74,.45) !important;
    color: #15803d !important;
}
[data-theme="light"] #partner-referral-card .ref-copy-btn {
    background: none !important;
    border: none !important;
    color: #15803d !important;
}
[data-theme="light"] #partner-referral-card .ref-divider {
    border-top-color: rgba(22,163,74,.3) !important;
}

/* ── Discount code edit form – light mode ── */
[data-theme="light"] .dc-code-input {
    background: #ffffff !important;
    border-color: rgba(140,95,10,.45) !important;
    color: #7a5000 !important;
}
[data-theme="light"] .dc-code-input::placeholder {
    color: #b08040 !important;
    opacity: .7;
}

/* ── Social media inputs – light mode ── */
[data-theme="light"] .sm-input {
    background: #ffffff !important;
    border-color: rgba(0,0,0,.18) !important;
    color: #333 !important;
}

/* ── New stat cards light mode ── */
[data-theme="light"] .stat-link-card .pw-adm-stat__value {
    color: #1e6fa0 !important;
}
[data-theme="light"] .stat-refcode-card .pw-adm-stat__value {
    color: #9d3084 !important;
}
</style>
@endpush
