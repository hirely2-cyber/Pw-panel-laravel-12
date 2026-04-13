@extends('layouts.app')
@section('title', __('main.privacy_title'))

@section('content')
<div style="max-width:900px;margin:3rem auto;padding:0 1.5rem;">
    <div style="background:var(--pw-bg-card);border:1px solid var(--pw-border);border-radius:12px;padding:2.5rem;">
        <div style="margin-bottom:2rem;">
            <h1 style="font-size:2rem;font-weight:700;margin-bottom:0.5rem;color:var(--pw-gold-light);">{{ __('main.privacy_title') }}</h1>
            <p style="color:var(--pw-text-muted);font-size:0.9rem;">{{ __('main.footer_private') }}</p>
        </div>

        <div style="color:var(--pw-text);line-height:1.8;font-size:0.95rem;">
            <p style="margin-bottom:1.5rem;">{{ __('main.privacy_content') }}</p>

            <div style="background:var(--pw-bg-card2);border-left:4px solid var(--pw-gold);padding:1.5rem;border-radius:6px;margin:2rem 0;">
                <svg viewBox="0 0 20 20" fill="none" width="20" style="margin-bottom:0.5rem;vertical-align:middle;margin-right:0.5rem;opacity:0.7"><circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.5"/><path d="M10 9v5M10 6.5v.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                <p style="margin:0;font-size:0.85rem;color:var(--pw-text-muted);">Halaman ini sedang dalam pengembangan. Admin akan segera mengisi dengan konten lengkap kebijakan privasi server.</p>
            </div>

            <div style="margin-top:2rem;padding-top:2rem;border-top:1px solid var(--pw-border);">
                <a href="{{ route('home') }}" style="display:inline-flex;align-items:center;gap:0.5rem;color:var(--pw-gold-light);text-decoration:none;font-weight:600;transition:color 0.2s;">
                    <svg viewBox="0 0 16 16" fill="none" width="14"><path d="M12 8H4M8 12l-4-4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    {{ __('main.auth_back_home') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
