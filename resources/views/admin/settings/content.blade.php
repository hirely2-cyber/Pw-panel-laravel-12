@extends('layouts.admin')
@section('title', 'Konten Website')

@section('content')

{{-- Sub-nav tabs --}}
<div style="display:flex;gap:.5rem;margin-bottom:1.25rem;">
    <a href="{{ route('admin.settings') }}" class="pw-btn pw-btn--gold" style="font-size:.8rem;padding:.45rem .9rem;">
        <svg viewBox="0 0 20 20" fill="none" width="14"><rect x="2" y="3" width="16" height="14" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M2 13l4-4 3 3 3-3 4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        Konten Website
    </a>
    <a href="{{ route('admin.settings.panel') }}" class="pw-btn pw-settings-tab--inactive" style="font-size:.8rem;padding:.45rem .9rem;">
        <svg viewBox="0 0 20 20" fill="none" width="14"><circle cx="10" cy="10" r="3" stroke="currentColor" stroke-width="1.5"/><path d="M10 2v1.5M10 16.5V18M2 10h1.5M16.5 10H18M4.2 4.2l1 1M14.8 14.8l1 1M15.8 4.2l-1 1M5.2 14.8l-1 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        Konfigurasi Panel
    </a>
</div>

<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf

    {{-- ===========================================
         SECTION 1 -- GAMBAR WEBSITE
    =========================================== --}}
    <div class="pw-adm-card" style="margin-bottom:1.25rem;">
        <div class="pw-adm-card__title">
            <svg viewBox="0 0 20 20" fill="none" width="16"><rect x="2" y="3" width="16" height="14" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M2 13l4-4 3 3 3-3 4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Gambar Website
        </div>

        {{-- Hero + Auth BG -- full width row --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.25rem;">
            <div>
                <label class="pw-form__label" style="margin-bottom:.5rem;display:flex;align-items:center;gap:.5rem;">
                    Background Hero Section
                    <span style="font-size:.7rem;color:var(--pw-text-muted);font-weight:400;">JPG / PNG / WEBP &middot; disarankan 1920x1080 &middot; maks 5 MB</span>
                </label>
                @php $heroBg = $settings->get('site_hero_bg'); @endphp
                <div class="pw-img-upload" id="hero-upload-wrap" style="aspect-ratio:16/5;min-height:unset;">
                    <input type="file" name="site_hero_bg" accept="image/*" id="hero-bg-input">
                    @if($heroBg)
                        <img src="{{ Storage::url($heroBg) }}" class="pw-img-upload__preview" id="hero-bg-preview" alt="Hero BG">
                        <div class="pw-img-upload__label">Klik untuk ganti gambar</div>
                        <label class="pw-img-upload__remove" onclick="event.stopPropagation()">
                            <input type="checkbox" name="remove_site_hero_bg" value="1" style="display:none" onchange="this.closest('form').submit()">
                            <svg viewBox="0 0 16 16" fill="none" width="12"><path d="M3 4h10M6 4V3h4v1M5 4l.5 9h5L11 4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                            Hapus
                        </label>
                    @else
                        <img src="" class="pw-img-upload__preview" id="hero-bg-preview" alt="" style="display:none">
                        <svg class="pw-img-upload__icon" viewBox="0 0 40 40" fill="none"><rect x="4" y="8" width="32" height="24" rx="3" stroke="currentColor" stroke-width="1.8"/><path d="M4 26l8-8 6 6 5-5 9 9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><circle cx="28" cy="16" r="3" stroke="currentColor" stroke-width="1.8"/></svg>
                        <div class="pw-img-upload__label">
                            <strong>Klik atau seret gambar ke sini</strong>
                            Background halaman utama (hero section)
                        </div>
                    @endif
                </div>
                @error('site_hero_bg')
                <p style="color:#ff6b6b;font-size:.75rem;margin-top:.35rem;">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="pw-form__label" style="margin-bottom:.5rem;display:flex;align-items:center;gap:.5rem;">
                    Background Halaman Auth
                    <span style="font-size:.7rem;color:var(--pw-text-muted);font-weight:400;">JPG / PNG / WEBP &middot; disarankan 1920x1080 &middot; maks 5 MB</span>
                </label>
                @php $authBg = $settings->get('site_auth_bg'); @endphp
                <div class="pw-img-upload" id="auth-upload-wrap" style="aspect-ratio:16/5;min-height:unset;">
                    <input type="file" name="site_auth_bg" accept="image/*" id="auth-bg-input">
                    @if($authBg)
                        <img src="{{ Storage::url($authBg) }}" class="pw-img-upload__preview" id="auth-bg-preview" alt="Auth BG">
                        <div class="pw-img-upload__label">Klik untuk ganti gambar</div>
                        <label class="pw-img-upload__remove" onclick="event.stopPropagation()">
                            <input type="checkbox" name="remove_site_auth_bg" value="1" style="display:none" onchange="this.closest('form').submit()">
                            <svg viewBox="0 0 16 16" fill="none" width="12"><path d="M3 4h10M6 4V3h4v1M5 4l.5 9h5L11 4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                            Hapus
                        </label>
                    @else
                        <img src="" class="pw-img-upload__preview" id="auth-bg-preview" alt="" style="display:none">
                        <svg class="pw-img-upload__icon" viewBox="0 0 40 40" fill="none"><rect x="4" y="8" width="32" height="24" rx="3" stroke="currentColor" stroke-width="1.8"/><path d="M4 26l8-8 6 6 5-5 9 9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><circle cx="28" cy="16" r="3" stroke="currentColor" stroke-width="1.8"/></svg>
                        <div class="pw-img-upload__label">
                            <strong>Klik atau seret gambar ke sini</strong>
                            Background halaman login, register, dan reset password
                        </div>
                    @endif
                </div>
                @error('site_auth_bg')
                <p style="color:#ff6b6b;font-size:.75rem;margin-top:.35rem;">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Logo + Favicon -- 2 columns --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">

            {{-- Site Logo --}}
            <div>
                <label class="pw-form__label" style="margin-bottom:.5rem;display:flex;align-items:center;gap:.5rem;">
                    Logo Website
                    <span style="font-size:.7rem;color:var(--pw-text-muted);font-weight:400;">PNG transparan &middot; maks 2 MB</span>
                </label>
                @php $logo = $settings->get('site_logo'); @endphp
                <div class="pw-img-upload" id="logo-upload-wrap" style="aspect-ratio:3/1;min-height:unset;">
                    <input type="file" name="site_logo" accept="image/*" id="logo-input">
                    @if($logo)
                        <img src="{{ Storage::url($logo) }}" class="pw-img-upload__preview" id="logo-preview" alt="Logo" style="max-height:70px;object-fit:contain;background:rgba(0,0,0,.3);padding:.5rem;border-radius:4px;">
                        <div class="pw-img-upload__label">Klik untuk ganti logo</div>
                        <label class="pw-img-upload__remove" onclick="event.stopPropagation()">
                            <input type="checkbox" name="remove_site_logo" value="1" style="display:none" onchange="this.closest('form').submit()">
                            <svg viewBox="0 0 16 16" fill="none" width="12"><path d="M3 4h10M6 4V3h4v1M5 4l.5 9h5L11 4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                            Hapus
                        </label>
                    @else
                        <img src="" class="pw-img-upload__preview" id="logo-preview" alt="" style="display:none">
                        <svg class="pw-img-upload__icon" viewBox="0 0 40 40" fill="none"><rect x="4" y="8" width="32" height="24" rx="3" stroke="currentColor" stroke-width="1.8"/><path d="M20 14v12M14 20h12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                        <div class="pw-img-upload__label">
                            <strong>Klik atau seret gambar ke sini</strong>
                            Menggantikan SVG logo default di navbar
                        </div>
                    @endif
                </div>
                @error('site_logo')
                <p style="color:#ff6b6b;font-size:.75rem;margin-top:.35rem;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Favicon --}}
            <div>
                <label class="pw-form__label" style="margin-bottom:.5rem;display:flex;align-items:center;gap:.5rem;">
                    Favicon
                    <span style="font-size:.7rem;color:var(--pw-text-muted);font-weight:400;">ICO / PNG / SVG &middot; 32x32 atau 64x64 &middot; maks 512 KB</span>
                </label>
                @php $favicon = $settings->get('site_favicon'); @endphp
                <div class="pw-img-upload" id="favicon-upload-wrap" style="aspect-ratio:3/1;min-height:unset;">
                    <input type="file" name="site_favicon" accept=".ico,.png,.svg,image/x-icon" id="favicon-input">
                    @if($favicon)
                        <img src="{{ Storage::url($favicon) }}" class="pw-img-upload__preview" id="favicon-preview" alt="Favicon" style="max-height:56px;max-width:56px;object-fit:contain;background:rgba(0,0,0,.3);padding:.5rem;border-radius:4px;">
                        <div class="pw-img-upload__label">Klik untuk ganti favicon</div>
                        <label class="pw-img-upload__remove" onclick="event.stopPropagation()">
                            <input type="checkbox" name="remove_site_favicon" value="1" style="display:none" onchange="this.closest('form').submit()">
                            <svg viewBox="0 0 16 16" fill="none" width="12"><path d="M3 4h10M6 4V3h4v1M5 4l.5 9h5L11 4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                            Hapus
                        </label>
                    @else
                        <img src="" class="pw-img-upload__preview" id="favicon-preview" alt="" style="display:none">
                        <svg class="pw-img-upload__icon" viewBox="0 0 40 40" fill="none"><rect x="8" y="8" width="24" height="24" rx="4" stroke="currentColor" stroke-width="1.8"/><circle cx="20" cy="20" r="6" stroke="currentColor" stroke-width="1.8"/></svg>
                        <div class="pw-img-upload__label">
                            <strong>Klik atau seret file ke sini</strong>
                            Ikon di tab browser &amp; bookmark
                        </div>
                    @endif
                </div>
                @error('site_favicon')
                <p style="color:#ff6b6b;font-size:.75rem;margin-top:.35rem;">{{ $message }}</p>
                @enderror
            </div>

        </div>

        {{-- Logo Footer - full width --}}
        <div style="margin-top:1.25rem;">
            <label class="pw-form__label" style="margin-bottom:.5rem;display:flex;align-items:center;gap:.5rem;">
                Logo Footer
                <span style="font-size:.7rem;color:var(--pw-text-muted);font-weight:400;">PNG transparan &middot; disarankan 140x50 &middot; maks 2 MB</span>
            </label>
            @php $footerLogo = $settings->get('site_footer_logo'); @endphp
            <div class="pw-img-upload" id="footer-logo-upload-wrap" style="aspect-ratio:4/1;min-height:unset;">
                <input type="file" name="site_footer_logo" accept="image/*" id="footer-logo-input">
                @if($footerLogo)
                    <img src="{{ Storage::url($footerLogo) }}" class="pw-img-upload__preview" id="footer-logo-preview" alt="Footer Logo" style="max-height:70px;object-fit:contain;background:rgba(0,0,0,.3);padding:.5rem;border-radius:4px;">
                    <div class="pw-img-upload__label">Klik untuk ganti logo footer</div>
                    <label class="pw-img-upload__remove" onclick="event.stopPropagation()">
                        <input type="checkbox" name="remove_site_footer_logo" value="1" style="display:none" onchange="this.closest('form').submit()">
                        <svg viewBox="0 0 16 16" fill="none" width="12"><path d="M3 4h10M6 4V3h4v1M5 4l.5 9h5L11 4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                        Hapus
                    </label>
                @else
                    <img src="" class="pw-img-upload__preview" id="footer-logo-preview" alt="" style="display:none">
                    <svg class="pw-img-upload__icon" viewBox="0 0 40 40" fill="none"><rect x="4" y="8" width="32" height="24" rx="3" stroke="currentColor" stroke-width="1.8"/><path d="M20 14v12M14 20h12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    <div class="pw-img-upload__label">
                        <strong>Klik atau seret gambar ke sini</strong>
                        Logo yang tampil di bagian footer website
                    </div>
                @endif
            </div>
            @error('site_footer_logo')
            <p style="color:#ff6b6b;font-size:.75rem;margin-top:.35rem;">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- ===========================================
         SECTION 2 -- SEO & META TAGS
    =========================================== --}}
    <div class="pw-adm-card" style="margin-bottom:1.25rem;">
        <div class="pw-adm-card__title">
            <svg viewBox="0 0 20 20" fill="none" width="16"><circle cx="9" cy="9" r="6" stroke="currentColor" stroke-width="1.5"/><path d="M13 13l4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            SEO &amp; Meta Tags
        </div>

        {{-- Site Name & Tagline --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
            <div class="pw-form__group">
                <label class="pw-form__label">
                    <svg viewBox="0 0 16 16" fill="none" width="13" style="vertical-align:middle;margin-right:.3rem;opacity:.6"><path d="M2 4h12M2 8h8M2 12h5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                    Nama Website/Server
                </label>
                <input type="text" name="site_name" class="pw-form__input" maxlength="50"
                    value="{{ $settings->get('site_name', '') }}"
                    placeholder="PVE SEA Perfect World">
                <p class="pw-form__hint">Nama website/server yang digunakan di seluruh panel. Prioritas lebih tinggi dari config.</p>
            </div>

            <div class="pw-form__group">
                <label class="pw-form__label">
                    <svg viewBox="0 0 16 16" fill="none" width="13" style="vertical-align:middle;margin-right:.3rem;opacity:.6"><path d="M8 2l1 3h3l-2.5 2 1 3L8 8.5 5.5 10l1-3L4 5h3l1-3z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/></svg>
                    Tagline
                </label>
                <input type="text" name="site_tagline" class="pw-form__input" maxlength="50"
                    value="{{ $settings->get('site_tagline', '') }}"
                    placeholder="Private Server PvE Asia Tenggara">
                <p class="pw-form__hint">Tagline/slogan server (contoh: Private Server, Server PvE, dll).</p>
            </div>
        </div>

        <div class="pw-form__group" style="margin-bottom:1rem;">
            <label class="pw-form__label" style="display:flex;justify-content:space-between;">
                <span>
                    <svg viewBox="0 0 16 16" fill="none" width="13" style="vertical-align:middle;margin-right:.3rem;opacity:.6"><path d="M2 4h12M2 7h12M2 10h8" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                    Deskripsi Server
                </span>
                <span id="site-desc-count" style="font-size:.68rem;color:var(--pw-text-muted);font-weight:400;">0/200</span>
            </label>
            <textarea name="site_description" id="site-desc-input" class="pw-form__input" rows="3" maxlength="200"
                placeholder="Deskripsi lengkap tentang server kamu..."
                oninput="document.getElementById('site-desc-count').textContent=this.value.length+'/200'"
                style="resize:vertical;">{{ $settings->get('site_description', '') }}</textarea>
            <p class="pw-form__hint">Deskripsi server yang digunakan sebagai fallback jika Meta Description kosong.</p>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">

            <div class="pw-form__group">
                <label class="pw-form__label" style="display:flex;justify-content:space-between;">
                    <span>
                        <svg viewBox="0 0 16 16" fill="none" width="13" style="vertical-align:middle;margin-right:.3rem;opacity:.6"><path d="M2 4h12M2 8h8M2 12h5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                        Meta Title
                    </span>
                    <span id="seo-title-count" style="font-size:.68rem;color:var(--pw-text-muted);font-weight:400;">0/60</span>
                </label>
                <input type="text" name="seo_title" id="seo-title-input" class="pw-form__input" maxlength="60"
                    value="{{ $settings->get('seo_title', '') }}"
                    placeholder="{{ config('pw-config.server.name', 'Perfect World') }} -- {{ config('pw-config.server.tagline', 'Private Server') }}"
                    oninput="document.getElementById('seo-title-count').textContent=this.value.length+'/60'">
                <p class="pw-form__hint">Judul yang muncul di tab browser & hasil pencarian Google. Maks 60 karakter.</p>
            </div>

            <div class="pw-form__group">
                <label class="pw-form__label">
                    <svg viewBox="0 0 16 16" fill="none" width="13" style="vertical-align:middle;margin-right:.3rem;opacity:.6"><rect x="2" y="2" width="12" height="12" rx="2" stroke="currentColor" stroke-width="1.3"/><path d="M5 6h6M5 9h4" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/></svg>
                    Google Analytics ID
                </label>
                <input type="text" name="seo_google_analytics" class="pw-form__input"
                    value="{{ $settings->get('seo_google_analytics', '') }}"
                    placeholder="G-XXXXXXXXXX">
                <p class="pw-form__hint">GA4 Measurement ID. Kosongkan bila tidak digunakan.</p>
            </div>

        </div>

        <div style="display:grid;grid-template-columns:1fr;gap:1rem;margin-bottom:1rem;">

            <div class="pw-form__group">
                <label class="pw-form__label">
                    <svg viewBox="0 0 16 16" fill="none" width="13" style="vertical-align:middle;margin-right:.3rem;opacity:.6"><circle cx="8" cy="8" r="6" stroke="currentColor" stroke-width="1.3"/><path d="M8 5v3l2 2" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                    Google Site Verification
                </label>
                <input type="text" name="seo_google_verification" class="pw-form__input"
                    value="{{ $settings->get('seo_google_verification', '') }}"
                    placeholder="xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx">
                <p class="pw-form__hint">Kode verifikasi Google Search Console (tanpa tag HTML, hanya content value). Kosongkan bila tidak digunakan.</p>
            </div>

        </div>

        <div class="pw-form__group" style="margin-bottom:1rem;">
            <label class="pw-form__label" style="display:flex;justify-content:space-between;">
                <span>
                    <svg viewBox="0 0 16 16" fill="none" width="13" style="vertical-align:middle;margin-right:.3rem;opacity:.6"><path d="M2 4h12M2 7h12M2 10h8" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                    Meta Description
                </span>
                <span id="seo-desc-count" style="font-size:.68rem;color:var(--pw-text-muted);font-weight:400;">0/160</span>
            </label>
            <textarea name="seo_description" id="seo-desc-input" class="pw-form__input" rows="3" maxlength="160"
                placeholder="Deskripsi singkat server yang tampil di hasil pencarian Google..."
                oninput="document.getElementById('seo-desc-count').textContent=this.value.length+'/160'"
                style="resize:vertical;">{{ $settings->get('seo_description', '') }}</textarea>
            <p class="pw-form__hint">Tampil di bawah judul pada hasil pencarian. Maks 160 karakter.</p>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.25rem;">

            <div class="pw-form__group">
                <label class="pw-form__label">
                    <svg viewBox="0 0 16 16" fill="none" width="13" style="vertical-align:middle;margin-right:.3rem;opacity:.6"><path d="M2 8h12M8 2v12" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                    Keywords
                </label>
                <input type="text" name="seo_keywords" class="pw-form__input"
                    value="{{ $settings->get('seo_keywords', '') }}"
                    placeholder="perfect world, private server, mmorpg, pw private">
                <p class="pw-form__hint">Pisahkan dengan koma. Tidak terlalu berpengaruh di Google, opsional.</p>
            </div>

            <div>
                <label class="pw-form__label" style="margin-bottom:.5rem;display:flex;align-items:center;gap:.5rem;">
                    OG Image <span style="font-size:.7rem;color:var(--pw-text-muted);font-weight:400;">1200x630 &middot; JPG/PNG &middot; maks 2 MB</span>
                </label>
                @php $ogImg = $settings->get('seo_og_image'); @endphp
                <div class="pw-img-upload" id="og-upload-wrap" style="aspect-ratio:1200/400;min-height:unset;">
                    <input type="file" name="seo_og_image" accept="image/jpeg,image/jpg,image/png" id="og-img-input">
                    @if($ogImg)
                        <img src="{{ Storage::url($ogImg) }}" class="pw-img-upload__preview" id="og-img-preview" alt="OG Image">
                        <div class="pw-img-upload__label">Klik untuk ganti OG image</div>
                        <label class="pw-img-upload__remove" onclick="event.stopPropagation()">
                            <input type="checkbox" name="remove_seo_og_image" value="1" style="display:none" onchange="this.closest('form').submit()">
                            <svg viewBox="0 0 16 16" fill="none" width="12"><path d="M3 4h10M6 4V3h4v1M5 4l.5 9h5L11 4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                            Hapus
                        </label>
                    @else
                        <img src="" class="pw-img-upload__preview" id="og-img-preview" alt="" style="display:none">
                        <svg class="pw-img-upload__icon" viewBox="0 0 40 40" fill="none"><rect x="4" y="8" width="32" height="24" rx="3" stroke="currentColor" stroke-width="1.8"/><path d="M4 26l8-8 6 6 5-5 9 9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <div class="pw-img-upload__label">
                            <strong>Gambar pratinjau di WhatsApp / Facebook / Twitter</strong>
                        </div>
                    @endif
                </div>
                @error('seo_og_image')
                <p style="color:#ff6b6b;font-size:.75rem;margin-top:.35rem;">{{ $message }}</p>
                @enderror
            </div>

        </div>

        {{-- SEO Preview --}}
        <div class="pw-seo-preview" style="border-radius:8px;padding:1rem;">
            <p style="font-size:.7rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.07em;margin-bottom:.6rem;">Pratinjau Google</p>
            <p id="seo-preview-title" style="color:#8ab4f8;font-size:.95rem;font-weight:500;margin-bottom:.15rem;line-clamp:1;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;">
                {{ $settings->get('seo_title') ?: config('pw-config.server.name', 'Perfect World').' -- '.config('pw-config.server.tagline', 'Private Server') }}
            </p>
            <p id="seo-preview-url" style="color:#4caf7d;font-size:.75rem;margin-bottom:.15rem;">{{ url('/') }}</p>
            <p id="seo-preview-desc" style="color:var(--pw-text-muted);font-size:.8rem;line-height:1.5;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                {{ $settings->get('seo_description') ?: 'Tidak ada deskripsi.' }}
            </p>
        </div>

    </div>

    {{-- ===========================================
         SECTION 3 -- SOSIAL MEDIA & DOWNLOAD
    =========================================== --}}
    <div class="pw-adm-card" style="margin-bottom:1.25rem;">
        <div class="pw-adm-card__title">
            <svg viewBox="0 0 20 20" fill="none" width="16"><circle cx="15" cy="5" r="2" stroke="currentColor" stroke-width="1.5"/><circle cx="15" cy="15" r="2" stroke="currentColor" stroke-width="1.5"/><circle cx="5" cy="10" r="2" stroke="currentColor" stroke-width="1.5"/><path d="M7 10.8l6 2.7M7 9.2l6-2.7" stroke="currentColor" stroke-width="1.3"/></svg>
            Sosial Media &amp; Download
        </div>

        <div class="pw-adm-grid" style="grid-template-columns:repeat(3,1fr);">
            <div class="pw-form__group">
                <label class="pw-form__label">
                    <svg viewBox="0 0 20 20" fill="none" width="14" style="vertical-align:middle;margin-right:.3rem;opacity:.6"><path d="M2 5.5A2.5 2.5 0 014.5 3h11A2.5 2.5 0 0118 5.5v9a2.5 2.5 0 01-2.5 2.5h-11A2.5 2.5 0 012 14.5v-9z" stroke="currentColor" stroke-width="1.4"/><path d="M6 8.5c0 1.933 1.567 3.5 3.5 3.5a3.5 3.5 0 003.5-3.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
                    WhatsApp
                </label>
                <input type="text" name="social_whatsapp" class="pw-form__input"
                    value="{{ $settings->get('social_whatsapp', '') }}"
                    placeholder="628118719377">
                <p class="pw-form__hint">Nomor HP tanpa + (contoh: 6281xxx)</p>
            </div>

            <div class="pw-form__group">
                <label class="pw-form__label">
                    <svg viewBox="0 0 20 20" fill="none" width="14" style="vertical-align:middle;margin-right:.3rem;opacity:.6"><rect x="3" y="3" width="14" height="14" rx="3" stroke="currentColor" stroke-width="1.4"/><circle cx="10" cy="10" r="3" stroke="currentColor" stroke-width="1.4"/><circle cx="14" cy="6" r=".8" fill="currentColor"/></svg>
                    Facebook Group URL
                </label>
                <input type="url" name="social_facebook" class="pw-form__input"
                    value="{{ $settings->get('social_facebook', '') }}"
                    placeholder="https://facebook.com/groups/yourgroup">
            </div>

            <div class="pw-form__group">
                <label class="pw-form__label">
                    <svg viewBox="0 0 20 20" fill="none" width="14" style="vertical-align:middle;margin-right:.3rem;opacity:.6"><path d="M3 14.5c1.5-1 3-1.5 5-1.5h4c2 0 3.5.5 5 1.5M7 9a3 3 0 106 0 3 3 0 00-6 0z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
                    Discord Invite URL
                </label>
                <input type="url" name="social_discord" class="pw-form__input"
                    value="{{ $settings->get('social_discord', '') }}"
                    placeholder="https://discord.gg/xxxxx">
            </div>

            <div class="pw-form__group">
                <label class="pw-form__label">
                    <svg viewBox="0 0 20 20" fill="none" width="14" style="vertical-align:middle;margin-right:.3rem;opacity:.6"><path d="M10 3v10M6 9l4 4 4-4M4 15h12" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Download -- Full Client
                </label>
                <input type="url" name="download_url" class="pw-form__input"
                    value="{{ $settings->get('download_url', '') }}"
                    placeholder="https://drive.google.com/...">
                <p class="pw-form__hint">Link download Full Client (file lengkap). Kosongkan jika tidak tersedia.</p>
            </div>

            <div class="pw-form__group">
                <label class="pw-form__label">
                    <svg viewBox="0 0 20 20" fill="none" width="14" style="vertical-align:middle;margin-right:.3rem;opacity:.6"><path d="M4 4h5v5H4zM11 4h5v5h-5zM4 11h5v5H4zM11 11h5v5h-5z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/></svg>
                    Download -- Part Client
                </label>
                <input type="url" name="download_url_part" class="pw-form__input"
                    value="{{ $settings->get('download_url_part', '') }}"
                    placeholder="https://drive.google.com/...">
                <p class="pw-form__hint">Link download Part Client (file terbagi). Kosongkan jika tidak tersedia.</p>
            </div>

            <div class="pw-form__group">
                <label class="pw-form__label">
                    <svg viewBox="0 0 20 20" fill="none" width="14" style="vertical-align:middle;margin-right:.3rem;opacity:.6"><path d="M4 4v12h12M7 13l3-4 3 2 4-5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Download -- Update / Patch
                </label>
                <input type="url" name="download_url_patch" class="pw-form__input"
                    value="{{ $settings->get('download_url_patch', '') }}"
                    placeholder="https://drive.google.com/...">
                <p class="pw-form__hint">Link download Patch / Update saja. Kosongkan jika tidak tersedia.</p>
            </div>
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
// Image upload preview
function setupPreview(inputId, previewId) {
    const input   = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    if (!input || !preview) return;
    input.addEventListener('change', () => {
        const file = input.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => { preview.src = e.target.result; preview.style.display = 'block'; };
        reader.readAsDataURL(file);
    });
}
setupPreview('hero-bg-input', 'hero-bg-preview');
setupPreview('auth-bg-input', 'auth-bg-preview');
setupPreview('logo-input',    'logo-preview');
setupPreview('favicon-input', 'favicon-preview');
setupPreview('footer-logo-input', 'footer-logo-preview');
setupPreview('og-img-input',  'og-img-preview');

// SEO live preview
const titleInput = document.getElementById('seo-title-input');
const descInput  = document.getElementById('seo-desc-input');
if (titleInput) {
    titleInput.addEventListener('input', () => {
        const v = titleInput.value.trim();
        const el = document.getElementById('seo-preview-title');
        if (el) el.textContent = v || titleInput.placeholder;
    });
}
if (descInput) {
    descInput.addEventListener('input', () => {
        const v = descInput.value.trim();
        const el = document.getElementById('seo-preview-desc');
        if (el) el.textContent = v || descInput.placeholder;
    });
}

// Init character counter for site description
const siteDescInput = document.getElementById('site-desc-input');
const siteDescCount = document.getElementById('site-desc-count');
if (siteDescInput && siteDescCount) {
    siteDescCount.textContent = siteDescInput.value.length + '/200';
}
</script>
@endpush
