@extends('layouts.admin')
@section('title', 'Konfigurasi Panel')

@section('content')

{{-- Sub-nav tabs --}}
<div style="display:flex;gap:.5rem;margin-bottom:1.25rem;">
    <a href="{{ route('admin.settings') }}" class="pw-btn pw-settings-tab--inactive" style="font-size:.8rem;padding:.45rem .9rem;">
        <svg viewBox="0 0 20 20" fill="none" width="14"><rect x="2" y="3" width="16" height="14" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M2 13l4-4 3 3 3-3 4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        Konten Website
    </a>
    <a href="{{ route('admin.settings.panel') }}" class="pw-btn pw-btn--gold" style="font-size:.8rem;padding:.45rem .9rem;">
        <svg viewBox="0 0 20 20" fill="none" width="14"><circle cx="10" cy="10" r="3" stroke="currentColor" stroke-width="1.5"/><path d="M10 2v1.5M10 16.5V18M2 10h1.5M16.5 10H18M4.2 4.2l1 1M14.8 14.8l1 1M15.8 4.2l-1 1M5.2 14.8l-1 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        Konfigurasi Panel
    </a>
</div>

<form action="{{ route('admin.settings.panel.update') }}" method="POST">
    @csrf

    {{-- ============================================================
         SECTION – SERVER INFO
    ============================================================ --}}
    <div class="pw-adm-card" style="margin-bottom:1.25rem;">
        <div class="pw-adm-card__title">
            <svg viewBox="0 0 20 20" fill="none" width="16"><rect x="3" y="3" width="14" height="5" rx="1.5" stroke="currentColor" stroke-width="1.5"/><rect x="3" y="12" width="14" height="5" rx="1.5" stroke="currentColor" stroke-width="1.5"/><circle cx="6" cy="5.5" r="1" fill="currentColor"/><circle cx="6" cy="14.5" r="1" fill="currentColor"/></svg>
            Informasi Server
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <div class="pw-form__group">
                <label class="pw-form__label">
                    <svg viewBox="0 0 20 20" fill="none" width="14" style="vertical-align:middle;margin-right:.3rem;opacity:.6"><path d="M10 2v16M6 6l4-4 4 4M6 14l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Versi Server
                </label>
                <input type="text" name="server_version" class="pw-form__input"
                    value="{{ $settings->get('server_version', '1.5.5') }}"
                    placeholder="Contoh: 1.5.5">
                <p class="pw-form__hint">Versi server yang ditampilkan di topbar website.</p>
                @error('server_version')
                <p style="color:#ff6b6b;font-size:.75rem;margin-top:.35rem;">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    {{-- ============================================================
         SECTION 4 -- PAYMENT GATEWAY (PAYHOOK)
    ============================================================ --}}
    <div class="pw-adm-card" style="margin-bottom:1.25rem;">
        <div class="pw-adm-card__title">
            <svg viewBox="0 0 20 20" fill="none" width="16"><rect x="2" y="5" width="16" height="12" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M2 9h16" stroke="currentColor" stroke-width="1.5"/><path d="M6 13h3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            Payment Gateway -- PayHook
        </div>

        <div style="background:rgba(255,200,50,.06);border:1px solid rgba(255,200,50,.18);border-radius:8px;padding:.75rem 1rem;margin-bottom:1.25rem;font-size:.8rem;color:var(--pw-text-muted);display:flex;gap:.6rem;align-items:flex-start;">
            <svg viewBox="0 0 20 20" fill="none" width="16" style="flex-shrink:0;margin-top:.1rem;color:#e5b742"><circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.5"/><path d="M10 7v4M10 13v.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
            <span>Pengaturan di sini akan menimpa nilai di file <code style="background:rgba(255,255,255,.07);padding:.1rem .35rem;border-radius:3px;font-size:.75rem;">.env</code>. Kosongkan field untuk menggunakan nilai <code style="background:rgba(255,255,255,.07);padding:.1rem .35rem;border-radius:3px;font-size:.75rem;">.env</code>.</span>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">

            <div class="pw-form__group" style="grid-column:1/-1;">
                <label class="pw-form__label">
                    <svg viewBox="0 0 20 20" fill="none" width="14" style="vertical-align:middle;margin-right:.3rem;opacity:.6"><circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.5"/><path d="M10 6v8M6 10h8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    PayHook Server URL
                </label>
                <input type="url" name="payhook_url" class="pw-form__input"
                    value="{{ $settings->get('payhook_url', '') }}"
                    placeholder="http://192.168.1.9:8001">
                <p class="pw-form__hint">URL server PayHook tempat QRIS diproses. Contoh: <code style="opacity:.7;">http://192.168.1.9:8001</code></p>
                @error('payhook_url')
                <p style="color:#ff6b6b;font-size:.75rem;margin-top:.35rem;">{{ $message }}</p>
                @enderror
            </div>

            <div class="pw-form__group">
                <label class="pw-form__label">
                    <svg viewBox="0 0 20 20" fill="none" width="14" style="vertical-align:middle;margin-right:.3rem;opacity:.6"><rect x="3" y="8" width="14" height="10" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M7 8V6a3 3 0 016 0v2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><circle cx="10" cy="13" r="1.2" fill="currentColor"/></svg>
                    API Key
                </label>
                <input type="text" name="payhook_api_key" class="pw-form__input" autocomplete="off"
                    value="{{ $settings->get('payhook_api_key', '') }}"
                    placeholder="Masukkan API Key dari PayHook server">
                @error('payhook_api_key')
                <p style="color:#ff6b6b;font-size:.75rem;margin-top:.35rem;">{{ $message }}</p>
                @enderror
            </div>

            <div class="pw-form__group">
                <label class="pw-form__label">
                    <svg viewBox="0 0 20 20" fill="none" width="14" style="vertical-align:middle;margin-right:.3rem;opacity:.6"><path d="M10 3l1.5 3.5 3.8.5-2.8 2.6.7 3.7L10 11.5l-3.2 1.8.7-3.7L4.7 7l3.8-.5L10 3z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/></svg>
                    Webhook Secret
                </label>
                <input type="text" name="payhook_webhook_secret" class="pw-form__input" autocomplete="off"
                    value="{{ $settings->get('payhook_webhook_secret', '') }}"
                    placeholder="Secret untuk verifikasi signature webhook">
                @error('payhook_webhook_secret')
                <p style="color:#ff6b6b;font-size:.75rem;margin-top:.35rem;">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <div style="display:flex;align-items:center;gap:.75rem;padding:.75rem 1rem;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:8px;">
            <label style="display:flex;align-items:center;gap:.6rem;cursor:pointer;font-size:.85rem;color:var(--pw-text-muted);">
                <input type="checkbox" name="payhook_sandbox" value="1" style="width:16px;height:16px;accent-color:var(--pw-gold);"
                    {{ $settings->get('payhook_sandbox', '1') === '1' ? 'checked' : '' }}>
                <span><strong style="color:var(--pw-text);">Mode Sandbox</strong> -- aktifkan saat testing, nonaktifkan di production</span>
            </label>
        </div>
    </div>

    {{-- ============================================================
         SECTION 5 -- FITUR & LAYANAN
    ============================================================ --}}
    <div class="pw-adm-card" style="margin-bottom:1.25rem;">
        <div class="pw-adm-card__title">
            <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M4 5h12M4 10h12M4 15h7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            Fitur &amp; Layanan
        </div>
        <p style="font-size:.78rem;color:var(--pw-text-muted);margin-bottom:1.1rem;">Aktifkan atau nonaktifkan fitur panel. Fitur yang dinonaktifkan akan disembunyikan dari menu dan tidak dapat diakses player.</p>

        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:.7rem;">

            @php
            $featureList = [
                'shop'     => ['label' => 'Gold Shop',          'desc' => 'Toko item in-game dengan Gold Points'],
                'donate'   => ['label' => 'Top-up Gold Points', 'desc' => 'Donasi / top-up QRIS Gold Points'],
                'voucher'  => ['label' => 'Voucher',            'desc' => 'Penukaran kode voucher hadiah'],
                'ranking'  => ['label' => 'Ranking',            'desc' => 'Leaderboard karakter & faction'],
                'vote'     => ['label' => 'Vote',               'desc' => 'Sistem vote reward harian'],
                'service'  => ['label' => 'Layanan Karakter',   'desc' => 'Level up, teleport, reset, dll'],
                'news'     => ['label' => 'Berita / Update',    'desc' => 'Halaman berita & patch notes'],
                'register' => ['label' => 'Registrasi Akun',    'desc' => 'Pendaftaran akun baru oleh player'],
                'cubi_shop'=> ['label' => 'Cubi Shop',          'desc' => 'Top-up Cubi Gold via QRIS'],
            ];
            @endphp

            @foreach($featureList as $key => $feat)
            @php $isOn = $settings->get('feature_' . $key, '1') === '1'; @endphp
            <label class="pw-feat-toggle {{ $isOn ? 'is-on' : '' }}" style="display:flex;align-items:center;justify-content:space-between;gap:.8rem;padding:.75rem 1rem;border-radius:8px;cursor:pointer;">
                <div>
                    <div style="font-size:.85rem;font-weight:600;color:var(--pw-text);">{{ $feat['label'] }}</div>
                    <div style="font-size:.73rem;color:var(--pw-text-muted);margin-top:.1rem;">{{ $feat['desc'] }}</div>
                </div>
                <div style="position:relative;flex-shrink:0;width:42px;height:24px;">
                    <input type="hidden" name="feature_{{ $key }}" value="0">
                    <input type="checkbox" name="feature_{{ $key }}" value="1"
                        class="pw-feat-cb"
                        style="position:absolute;width:100%;height:100%;opacity:0;margin:0;cursor:pointer;z-index:1;"
                        {{ $isOn ? 'checked' : '' }}>
                    <div class="pw-feat-track" style="position:absolute;inset:0;border-radius:12px;transition:background .2s;pointer-events:none;"></div>
                    <div class="pw-feat-knob" style="position:absolute;top:3px;left:{{ $isOn ? '21px' : '3px' }};width:18px;height:18px;background:#fff;border-radius:50%;transition:left .2s;pointer-events:none;"></div>
                </div>
            </label>
            @endforeach

        </div>
    </div>

    {{-- SAVE --}}
    <div style="display:flex;justify-content:flex-end;">
        <button type="submit" class="pw-btn pw-btn--gold">
            <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M4 10l4 4 8-8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Simpan Pengaturan
        </button>
    </div>

</form>

@endsection

@push('scripts')
<script>
document.querySelectorAll('.pw-feat-cb').forEach(cb => {
    const wrap = cb.closest('.pw-feat-toggle');
    const knob = wrap.querySelector('.pw-feat-knob');
    cb.addEventListener('change', () => {
        const on = cb.checked;
        knob.style.left = on ? '21px' : '3px';
        if (on) wrap.classList.add('is-on'); else wrap.classList.remove('is-on');
    });
});
</script>
@endpush
