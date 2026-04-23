@extends('layouts.admin')

@section('title', 'Role XML: ' . $charName)

@section('content')
<div style="display:grid;gap:1rem;">

    @if(session('success'))
    <div style="background:rgba(22,163,106,.15);border:1px solid rgba(22,163,106,.3);color:#16a36a;padding:.6rem 1rem;border-radius:8px;font-size:.82rem;font-weight:600;">
        ✓ {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div style="background:rgba(220,38,38,.12);border:1px solid rgba(220,38,38,.3);color:#ef4444;padding:.6rem 1rem;border-radius:8px;font-size:.82rem;font-weight:600;">
        ✕ {{ session('error') }}
    </div>
    @endif

    <div class="pw-adm-card" style="padding:.75rem 1rem;">
        <div style="display:flex;align-items:center;flex-wrap:wrap;gap:.6rem;">
            <a href="{{ route('admin.roles.show', $roleId) }}" class="pw-adm-btn pw-adm-btn--ghost" style="font-size:.78rem;">← Detail</a>
            <a href="{{ route('admin.roles.index') }}" class="pw-adm-btn pw-adm-btn--ghost" style="font-size:.78rem;">← List</a>
            @if(!empty($memberCharacterUrl))
            <a href="{{ $memberCharacterUrl }}" class="pw-adm-btn pw-adm-btn--ghost" style="font-size:.78rem;" title="GUI di halaman member">→ GUI (Member)</a>
            @endif
        </div>
    </div>

    @include('admin.partials.role-xml-panel', [
        'formAction' => route('admin.roles.role-xml.save', $roleId),
        'charName' => $charName,
        'roleXml' => $roleXml,
        'roleXmlError' => $roleXmlError,
        'pwadminRolexmlUrl' => $pwadminRolexmlUrl,
    ])
</div>
@include('admin.partials.role-xml-assets', ['roleXml' => $roleXml ?? ''])
@endsection
