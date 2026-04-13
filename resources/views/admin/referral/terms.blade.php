@extends('layouts.admin')
@section('title', 'Syarat & Ketentuan Partner')

@section('content')

{{-- Sub-nav tabs --}}
<div style="display:flex;gap:.5rem;margin-bottom:1.25rem;">
    <a href="{{ route('admin.referral') }}" class="pw-btn pw-btn--muted" style="font-size:.8rem;padding:.45rem .9rem;">
        <svg viewBox="0 0 20 20" fill="none" width="14"><path d="M3 5h14M3 10h14M3 15h9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        Riwayat Referral
    </a>
    <a href="{{ route('admin.referral.partners') }}" class="pw-btn pw-btn--muted" style="font-size:.8rem;padding:.45rem .9rem;">
        <svg viewBox="0 0 20 20" fill="none" width="14"><path d="M10 2v6M13 5H7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M15 10a5 5 0 11-10 0" stroke="currentColor" stroke-width="1.5"/><circle cx="5" cy="15" r="2.5" stroke="currentColor" stroke-width="1.3"/><circle cx="15" cy="15" r="2.5" stroke="currentColor" stroke-width="1.3"/></svg>
        Pengaturan Partner
    </a>
    <a href="{{ route('admin.referral.terms') }}" class="pw-btn pw-btn--gold" style="font-size:.8rem;padding:.45rem .9rem;">
        <svg viewBox="0 0 20 20" fill="none" width="14"><path d="M6 2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4a2 2 0 012-2z" stroke="currentColor" stroke-width="1.5"/><path d="M7 7h6M7 10h6M7 13h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        Syarat & Ketentuan
    </a>
</div>

@if(session('success'))
<div class="pw-adm-alert pw-adm-alert--success" style="margin-bottom:1rem;">✓ {{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('admin.referral.terms.update') }}">
    @csrf

    {{-- Tabs for ID/EN --}}
    <div x-data="{ tab: 'id' }">

        <div style="display:flex;gap:.5rem;margin-bottom:1rem;">
            <button type="button" @click="tab='id'"
                    :class="tab==='id' ? 'pw-adm-lang-tab pw-adm-lang-tab--active' : 'pw-adm-lang-tab'">
                🇮🇩 Bahasa Indonesia
            </button>
            <button type="button" @click="tab='en'"
                    :class="tab==='en' ? 'pw-adm-lang-tab pw-adm-lang-tab--active' : 'pw-adm-lang-tab'">
                🇬🇧 English
            </button>
        </div>

        {{-- Indonesian --}}
        <div x-show="tab==='id'" style="display:block;">
            <div class="pw-adm-card" style="margin-bottom:1.25rem;">
                <div class="pw-adm-card__title">
                    <svg viewBox="0 0 20 20" fill="none" width="15"><path d="M6 2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4a2 2 0 012-2z" stroke="currentColor" stroke-width="1.5"/></svg>
                    Syarat & Ketentuan — Bahasa Indonesia
                </div>
                <p style="font-size:.78rem;color:var(--pw-text-muted);margin-bottom:.75rem;line-height:1.5;">
                    Konten ini mendukung HTML. Gunakan tag seperti <code style="background:rgba(255,255,255,.08);padding:1px 5px;border-radius:3px;">&lt;h3&gt;</code>, <code style="background:rgba(255,255,255,.08);padding:1px 5px;border-radius:3px;">&lt;ol type="a"&gt;</code>, <code style="background:rgba(255,255,255,.08);padding:1px 5px;border-radius:3px;">&lt;li&gt;</code>, <code style="background:rgba(255,255,255,.08);padding:1px 5px;border-radius:3px;">&lt;strong&gt;</code>, <code style="background:rgba(255,255,255,.08);padding:1px 5px;border-radius:3px;">&lt;p&gt;</code>.
                </p>
                <textarea name="content_id" rows="28"
                          style="width:100%;background:rgba(0,0,0,.3);border:1px solid rgba(255,255,255,.1);border-radius:7px;
                                 padding:.75rem 1rem;font-size:.82rem;font-family:monospace;color:var(--pw-text);
                                 line-height:1.6;outline:none;resize:vertical;box-sizing:border-box;"
                          placeholder="Masukkan isi Syarat & Ketentuan dalam HTML..."
                          @keydown.tab.prevent="
                              let s = $el.selectionStart;
                              let e = $el.selectionEnd;
                              $el.value = $el.value.substring(0, s) + '  ' + $el.value.substring(e);
                              $el.selectionStart = $el.selectionEnd = s + 2;
                          ">{{ $termsId?->content }}</textarea>
                @error('content_id') <div style="color:#ef4444;font-size:.78rem;margin-top:.3rem;">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- English --}}
        <div x-show="tab==='en'" style="display:none;" x-cloak>
            <div class="pw-adm-card" style="margin-bottom:1.25rem;">
                <div class="pw-adm-card__title">
                    <svg viewBox="0 0 20 20" fill="none" width="15"><path d="M6 2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4a2 2 0 012-2z" stroke="currentColor" stroke-width="1.5"/></svg>
                    Terms & Conditions — English
                </div>
                <p style="font-size:.78rem;color:var(--pw-text-muted);margin-bottom:.75rem;line-height:1.5;">
                    Supports HTML. Use tags like <code style="background:rgba(255,255,255,.08);padding:1px 5px;border-radius:3px;">&lt;h3&gt;</code>, <code style="background:rgba(255,255,255,.08);padding:1px 5px;border-radius:3px;">&lt;ol type="a"&gt;</code>, <code style="background:rgba(255,255,255,.08);padding:1px 5px;border-radius:3px;">&lt;li&gt;</code>, <code style="background:rgba(255,255,255,.08);padding:1px 5px;border-radius:3px;">&lt;strong&gt;</code>, <code style="background:rgba(255,255,255,.08);padding:1px 5px;border-radius:3px;">&lt;p&gt;</code>.
                </p>
                <textarea name="content_en" rows="28"
                          style="width:100%;background:rgba(0,0,0,.3);border:1px solid rgba(255,255,255,.1);border-radius:7px;
                                 padding:.75rem 1rem;font-size:.82rem;font-family:monospace;color:var(--pw-text);
                                 line-height:1.6;outline:none;resize:vertical;box-sizing:border-box;"
                          placeholder="Enter Terms & Conditions in HTML..."
                          @keydown.tab.prevent="
                              let s = $el.selectionStart;
                              let e = $el.selectionEnd;
                              $el.value = $el.value.substring(0, s) + '  ' + $el.value.substring(e);
                              $el.selectionStart = $el.selectionEnd = s + 2;
                          ">{{ $termsEn?->content }}</textarea>
                @error('content_en') <div style="color:#ef4444;font-size:.78rem;margin-top:.3rem;">{{ $message }}</div> @enderror
            </div>
        </div>

    </div>{{-- /x-data --}}

    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem;">
        <div style="font-size:.78rem;color:var(--pw-text-muted);">
            Terakhir diperbarui:
            @if($termsId?->updated_at)
                {{ $termsId->updated_at->format('d M Y H:i') }}
            @else
                —
            @endif
        </div>
        <button type="submit" class="pw-btn pw-btn--gold" style="padding:.55rem 1.4rem;font-size:.85rem;">
            <svg viewBox="0 0 20 20" fill="none" width="15"><path d="M5 10l4 4 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Simpan Semua
        </button>
    </div>
</form>

@endsection

@push('styles')
<style>
/* Sub-nav inactive button — theme-aware */
.pw-btn--muted {
    background: transparent;
    border: 1px solid var(--pw-border, rgba(255,255,255,.12));
    color: var(--pw-text-muted);
}
.pw-btn--muted:hover {
    color: var(--pw-text);
    border-color: var(--pw-gold);
}

/* ID/EN language tab buttons — no background */
.pw-adm-lang-tab {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .4rem .85rem;
    border: 1px solid var(--pw-border, rgba(255,255,255,.12));
    border-radius: 6px;
    font-size: .8rem;
    font-weight: 600;
    cursor: pointer;
    background: transparent;
    color: var(--pw-text-muted);
    transition: color .15s, border-color .15s;
}
.pw-adm-lang-tab--active {
    border-color: rgba(200,151,42,.5);
    color: var(--pw-gold);
}
.pw-adm-lang-tab:not(.pw-adm-lang-tab--active):hover {
    color: var(--pw-text);
    border-color: var(--pw-gold);
}

/* Textarea — light mode */
[data-theme="light"] textarea {
    background: #ffffff !important;
    border-color: rgba(0,0,0,.2) !important;
    color: #333 !important;
}
</style>
@endpush
