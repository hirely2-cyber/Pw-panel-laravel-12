@extends('layouts.admin')
@section('title', 'Game Mailer')

@section('content')
<div x-data="{ target: '{{ old('target', 'all') }}' }">

    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
        <div>
            <h2 style="font-size:1.15rem;font-weight:700;color:var(--pw-text);margin:0;">Game Mailbox — Kirim Item & Pesan</h2>
            <p style="font-size:.8rem;color:var(--pw-text-muted);margin-top:.2rem;">Kirim pesan, gold, atau item langsung ke Kotak Pos (Mailbox) karakter di dalam game.</p>
        </div>
        <div class="pw-mailer-status" style="display:flex;align-items:center;gap:.5rem;padding:.4rem .8rem;border-radius:6px;">
            <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:{{ $apiAvailable ? '#4ade80' : '#ef4444' }};"></span>
            <span style="font-size:.78rem;color:var(--pw-text-muted);">gdeliveryd: {{ $apiAvailable ? 'Connected' : 'Offline' }}</span>
        </div>
    </div>

    @if(session('success'))
    <div class="pw-adm-alert pw-adm-alert--success" style="margin-bottom:1rem;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="pw-adm-alert pw-adm-alert--error" style="margin-bottom:1rem;">{{ session('error') }}</div>
    @endif

    <form action="{{ route('admin.mailer.send') }}" method="POST">
        @csrf

        {{-- FORM: Single Card Full-Width --}}
        <div class="pw-adm-card" style="margin-bottom:1.25rem;">
            <div class="pw-adm-card__title">
                <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M3 5h14v10H3z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M3 5l7 6 7-6" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Compose Mail
            </div>

            {{-- Row 1: Target + Role ID --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:.8rem;">
                <div>
                    <label class="pw-form__label">Target Pengiriman <span style="color:#e05252;">*</span></label>
                    <select name="target" class="pw-form__input" x-model="target">
                        <option value="all">Semua Karakter ({{ number_format($totalCharacters) }} karakter)</option>
                        <option value="single">Karakter Tertentu (by Role ID)</option>
                    </select>
                </div>
                <div x-show="target === 'single'" x-cloak>
                    <label class="pw-form__label">Role ID Karakter <span style="color:#e05252;">*</span></label>
                    <input type="number" name="role_id" class="pw-form__input" min="1"
                           value="{{ old('role_id') }}" placeholder="Contoh: 16">
                    @error('role_id') <p style="color:#e05252;font-size:.75rem;margin-top:.3rem;">{{ $message }}</p> @enderror
                </div>
                <div x-show="target !== 'single'">
                    <label class="pw-form__label" style="opacity:.4;">Role ID</label>
                    <input type="text" class="pw-form__input" disabled placeholder="—" style="opacity:.3;">
                </div>
            </div>

            {{-- Row 2: Judul --}}
            <div style="margin-bottom:.8rem;">
                <label class="pw-form__label">Judul Mail <span style="color:#e05252;">*</span></label>
                <input type="text" name="title" class="pw-form__input" required maxlength="64"
                       value="{{ old('title') }}" placeholder="Hadiah Event Spesial">
                @error('title') <p style="color:#e05252;font-size:.75rem;margin-top:.3rem;">{{ $message }}</p> @enderror
            </div>

            {{-- Row 3: Pesan --}}
            <div style="margin-bottom:.8rem;">
                <label class="pw-form__label">Isi Pesan <span style="color:#e05252;">*</span></label>
                <textarea name="message" rows="4" class="pw-form__input" required maxlength="512"
                          style="resize:vertical;">{{ old('message') }}</textarea>
                <p style="font-size:.72rem;color:var(--pw-text-muted);margin-top:.2rem;">Pesan yang tampil di Kotak Pos game. Maks 512 karakter.</p>
                @error('message') <p style="color:#e05252;font-size:.75rem;margin-top:.3rem;">{{ $message }}</p> @enderror
            </div>

            {{-- Separator --}}
            <div class="pw-mailer-sep" style="margin:1.25rem 0;"></div>

            {{-- Row 4: Item & Gold --}}
            <p style="font-size:.78rem;font-weight:600;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.6rem;">
                <svg viewBox="0 0 20 20" fill="none" width="13" style="vertical-align:middle;margin-right:.3rem;opacity:.6;"><path d="M10 2l2 4h4l-3 3 1 5-4-2-4 2 1-5-3-3h4l2-4z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/></svg>
                Lampiran (Opsional)
            </p>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;">
                <div>
                    <label class="pw-form__label">Item ID</label>
                    <input type="number" name="item_id" class="pw-form__input" min="0"
                           value="{{ old('item_id', 0) }}">
                    <p style="font-size:.72rem;color:var(--pw-text-muted);margin-top:.3rem;">ID item game. 0 = tanpa item.</p>
                    @error('item_id') <p style="color:#e05252;font-size:.75rem;margin-top:.3rem;">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="pw-form__label">Jumlah Item</label>
                    <input type="number" name="item_count" class="pw-form__input" min="1" max="9999"
                           value="{{ old('item_count', 1) }}">
                    <p style="font-size:.72rem;color:var(--pw-text-muted);margin-top:.3rem;">Qty per pengiriman.</p>
                </div>
                <div>
                    <label class="pw-form__label">Gold (In-Game Coins)</label>
                    <input type="number" name="gold" class="pw-form__input" min="0"
                           value="{{ old('gold', 0) }}">
                    <p style="font-size:.72rem;color:var(--pw-text-muted);margin-top:.3rem;">0 = tanpa gold.</p>
                    @error('gold') <p style="color:#e05252;font-size:.75rem;margin-top:.3rem;">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Separator --}}
            <div class="pw-mailer-sep" style="margin:1.25rem 0;"></div>

            {{-- Submit --}}
            <div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;">
                <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;font-size:.82rem;color:var(--pw-text-muted);">
                    <input type="checkbox" required style="accent-color:var(--pw-gold);">
                    <span>Saya konfirmasi akan mengirim mail ini ke
                        <strong x-show="target === 'all'" style="color:var(--pw-text);">semua {{ number_format($totalCharacters) }} karakter</strong><strong x-show="target === 'single'" style="color:var(--pw-text);" x-cloak>karakter tertentu</strong>.
                    </span>
                </label>
                <button type="submit" class="pw-adm-btn" style="white-space:nowrap;" {{ !$apiAvailable ? 'disabled' : '' }}>
                    <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M3 5h14v10H3z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M3 5l7 6 7-6" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Kirim ke Mailbox Game
                </button>
            </div>

            @if(!$apiAvailable)
            <div class="pw-adm-alert pw-adm-alert--error" style="margin-top:.75rem;font-size:.78rem;">
                Tidak dapat terhubung ke <strong>gdeliveryd</strong> (port {{ config('pw-api.ports.gdeliveryd', 29100) }}). Pastikan game server berjalan.
            </div>
            @endif
        </div>
    </form>

    {{-- Panduan --}}
    <div class="pw-adm-card">
        <div class="pw-adm-card__title">
            <svg viewBox="0 0 20 20" fill="none" width="16"><circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.4"/><path d="M10 9v5M10 6.5v.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            Panduan Game Mailer
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1.5rem;font-size:.82rem;color:var(--pw-text-muted);line-height:1.7;">
            <div>
                <h4 style="color:var(--pw-gold);font-size:.85rem;font-weight:600;margin-bottom:.5rem;">Cara Kerja</h4>
                <ol style="padding-left:1.2rem;margin:0;">
                    <li>Mail dikirim ke <strong style="color:var(--pw-text);">Kotak Pos (Mailbox)</strong> karakter via daemon <code>gdeliveryd</code>.</li>
                    <li>Player membuka mailbox di game untuk mengambil item/gold.</li>
                    <li>Pengiriman <em>semua karakter</em> berjalan satu per satu — butuh waktu jika banyak.</li>
                </ol>
            </div>
            <div>
                <h4 style="color:var(--pw-gold);font-size:.85rem;font-weight:600;margin-bottom:.5rem;">Contoh Penggunaan</h4>
                <div class="pw-mailer-example" style="border-radius:.5rem;padding:.6rem .8rem;margin-bottom:.5rem;">
                    <div style="font-size:.7rem;color:var(--pw-gold);margin-bottom:.15rem;">Event Reward → semua karakter</div>
                    <div>Judul: <strong style="color:var(--pw-text);">Hadiah Grand Opening</strong></div>
                    <div>Item ID: <strong style="color:var(--pw-text);">21652</strong> · Qty: <strong style="color:var(--pw-text);">50</strong> · Gold: <strong style="color:var(--pw-text);">100000</strong></div>
                </div>
                <div class="pw-mailer-example" style="border-radius:.5rem;padding:.6rem .8rem;">
                    <div style="font-size:.7rem;color:var(--pw-gold);margin-bottom:.15rem;">Pengumuman → tanpa item</div>
                    <div>Judul: <strong style="color:var(--pw-text);">Info Maintenance</strong></div>
                    <div>Item ID: <strong style="color:var(--pw-text);">0</strong> · Gold: <strong style="color:var(--pw-text);">0</strong></div>
                </div>
            </div>
            <div>
                <h4 style="color:var(--pw-gold);font-size:.85rem;font-weight:600;margin-bottom:.5rem;">Catatan Penting</h4>
                <ul style="padding-left:1.2rem;margin:0;">
                    <li><strong style="color:var(--pw-text);">"Semua Karakter"</strong> = kirim ke setiap karakter di DB, bukan per akun.</li>
                    <li><strong style="color:var(--pw-text);">Role ID</strong> — lihat di Members → detail akun → daftar karakter.</li>
                    <li><strong style="color:var(--pw-text);">Item ID</strong> — ID item dari database game PW.</li>
                    <li>Player harus <strong style="color:var(--pw-text);">login game</strong> untuk ambil item dari mailbox.</li>
                </ul>
            </div>
        </div>
    </div>

</div>
@endsection
