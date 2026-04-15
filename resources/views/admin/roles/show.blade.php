@extends('layouts.admin')

@section('title', 'Character: ' . ($role->role_name ?? ($liveData['base']['name'] ?? 'Unknown')))

@push('styles')
<style>
.rd-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(340px,1fr)); gap:1rem; }
.rd-section { font-size:.72rem; font-weight:700; letter-spacing:.06em; color:var(--pw-text-muted); margin:1rem 0 .5rem; text-transform:uppercase; }
.rd-section:first-child { margin-top:0; }
.rd-row { display:flex; justify-content:space-between; align-items:center; padding:.4rem 0; border-bottom:1px solid rgba(255,255,255,.06); font-size:.82rem; }
.rd-key { color:var(--pw-text-muted); }
.rd-val { color:var(--pw-text-light); font-weight:500; }
.rd-class-icon { width:28px; height:28px; border-radius:5px; }
.rd-badge { display:inline-flex; align-items:center; gap:4px; padding:.15rem .5rem; border-radius:999px; font-size:.74rem; font-weight:600; }
.rd-badge-green { border:1px solid rgba(80,200,120,.35); background:rgba(80,200,120,.12); color:#50c878; }
.rd-badge-amber { border:1px solid rgba(240,165,0,.3); background:rgba(240,165,0,.1); color:#f0a500; }
.rd-badge-red { border:1px solid rgba(239,68,68,.35); background:rgba(239,68,68,.12); color:#ef4444; }
[data-theme="light"] .rd-row { border-bottom-color:rgba(0,0,0,.08); }
</style>
@endpush

@section('content')
<div style="display:grid;gap:1rem;">

    {{-- Header --}}
    <div class="pw-adm-card" style="padding:1rem 1.2rem;">
        @php
            $roleName = $role->role_name ?? ($liveData['base']['name'] ?? 'Unknown');
            $roleLevel = $role->role_level ?? ($liveData['status']['level'] ?? '?');
            $roleOcc = $role->role_occupation ?? ($liveData['base']['cls'] ?? 0);
            $roleRace = $role->role_race ?? ($liveData['base']['race'] ?? 0);
        @endphp
        <div style="display:flex;gap:1rem;align-items:center;flex-wrap:wrap;">
            <img src="{{ asset('images/class/' . ($iconMap[$roleOcc] ?? 'blademaster') . '.png') }}" class="rd-class-icon" alt="">
            <div style="flex:1;">
                <div style="font-size:1.1rem;font-weight:700;color:var(--pw-text-light);">{{ $roleName }}</div>
                <div style="font-size:.78rem;color:var(--pw-text-muted);">
                    Lv.{{ $roleLevel }} {{ $classMap[$roleOcc] ?? 'Unknown' }} — {{ $raceMap[$roleRace] ?? 'Unknown' }}
                </div>
            </div>
            <a href="{{ route('admin.roles.index') }}" class="pw-adm-btn" style="font-size:.78rem;">&larr; Kembali ke List</a>
            @if($liveData)
            <a href="{{ route('admin.roles.edit', $roleId) }}" class="pw-adm-btn" style="font-size:.78rem;background:rgba(80,200,120,.12);border-color:rgba(80,200,120,.35);color:#50c878;">✏ Edit</a>
            @endif
        </div>
    </div>

    <div class="rd-grid">

        {{-- Left: Character Info dari MySQL --}}
        <div class="pw-adm-card" style="padding:1rem 1.2rem;">
            <div style="font-size:.85rem;font-weight:700;color:var(--pw-text-light);margin-bottom:.8rem;">Database Info (MySQL)</div>

            @if($role)
            <div class="rd-section">General</div>
            <div class="rd-row"><span class="rd-key">Role ID</span><span class="rd-val" style="font-family:monospace;">{{ $role->role_id }}</span></div>
            <div class="rd-row"><span class="rd-key">Account ID</span><span class="rd-val" style="font-family:monospace;">{{ $role->account_id }}</span></div>
            <div class="rd-row"><span class="rd-key">Name</span><span class="rd-val">{{ $role->role_name }}</span></div>
            <div class="rd-row"><span class="rd-key">Level</span><span class="rd-val" style="font-weight:700;font-size:.9rem;">{{ $role->role_level }}</span></div>
            <div class="rd-row">
                <span class="rd-key">Class</span>
                <span class="rd-val" style="display:flex;align-items:center;gap:5px;">
                    <img src="{{ asset('images/class/' . ($iconMap[$role->role_occupation] ?? 'blademaster') . '.png') }}" width="18" height="18" style="border-radius:3px;" alt="">
                    {{ $classMap[$role->role_occupation] ?? 'Unknown' }}
                </span>
            </div>
            <div class="rd-row"><span class="rd-key">Race</span><span class="rd-val">{{ $raceMap[$role->role_race] ?? 'Unknown' }}</span></div>
            <div class="rd-row"><span class="rd-key">Gender</span><span class="rd-val">{{ $role->role_gender == 0 ? 'Male' : 'Female' }}</span></div>
            <div class="rd-row">
                <span class="rd-key">Spouse</span>
                <span class="rd-val">
                    @if($role->role_spouse > 0)
                        <a href="{{ route('admin.roles.show', $role->role_spouse) }}" style="color:#3b82f6;">ID: {{ $role->role_spouse }}</a>
                    @else
                        <span style="color:var(--pw-text-muted);">—</span>
                    @endif
                </span>
            </div>

            <div class="rd-section">Faction</div>
            <div class="rd-row">
                <span class="rd-key">Faction</span>
                <span class="rd-val">
                    @if($role->faction_name)
                        <span class="rd-badge rd-badge-amber">{{ $role->faction_name }}</span>
                    @else
                        <span style="color:var(--pw-text-muted);">None</span>
                    @endif
                </span>
            </div>
            <div class="rd-row"><span class="rd-key">Faction ID</span><span class="rd-val">{{ $role->faction_id ?: '—' }}</span></div>
            <div class="rd-row"><span class="rd-key">Faction Level</span><span class="rd-val">{{ $role->faction_level ?: '—' }}</span></div>
            <div class="rd-row"><span class="rd-key">Rank</span><span class="rd-val">{{ $rankMap[$role->role_faction_rank] ?? $role->role_faction_rank }}</span></div>
            <div class="rd-row"><span class="rd-key">Domains</span><span class="rd-val">{{ $role->faction_domains ?: '—' }}</span></div>

            <div class="rd-section">PvP</div>
            <div class="rd-row"><span class="rd-key">PvP Time</span><span class="rd-val">{{ $role->pvp_time }}</span></div>
            <div class="rd-row">
                <span class="rd-key">Kills</span>
                <span class="rd-val"><span class="rd-badge rd-badge-green">{{ number_format($role->pvp_kills) }}</span></span>
            </div>
            <div class="rd-row">
                <span class="rd-key">Deaths</span>
                <span class="rd-val"><span class="rd-badge rd-badge-red">{{ number_format($role->pvp_deads) }}</span></span>
            </div>
            @else
            <div style="padding:2rem;text-align:center;color:var(--pw-text-muted);font-size:.85rem;">
                Karakter ini belum ada di MySQL.<br>
                <span style="font-size:.78rem;">Klik <strong>Sync Roles</strong> di halaman list untuk sinkronisasi.</span>
            </div>
            @endif
        </div>

        {{-- Right: Live Data dari GameDBD --}}
        <div class="pw-adm-card" style="padding:1rem 1.2rem;">
            <div style="font-size:.85rem;font-weight:700;color:var(--pw-text-light);margin-bottom:.8rem;">
                Live Data (GameDBD)
                @if($liveData)
                    <span class="rd-badge rd-badge-green" style="margin-left:.5rem;">Connected</span>
                @else
                    <span class="rd-badge rd-badge-red" style="margin-left:.5rem;">Unavailable</span>
                @endif
            </div>

            @if($liveData)
                @php
                    $base = $liveData['base'] ?? [];
                    $status = $liveData['status'] ?? [];
                    $pocket = $liveData['pocket'] ?? [];
                    $store = $liveData['storehouse'] ?? [];
                    $prop = $status['property'] ?? [];
                    $cultivationMap = [
                        0 => 'Inchoation', 1 => 'Autoscopy', 2 => 'Transform',
                        3 => 'Naissance', 4 => 'Reborn', 5 => 'Vigilance',
                        6 => 'Doom', 7 => 'Disengage', 8 => 'Nirvana',
                        20 => 'Prime Immortal', 21 => 'Pure Immortal', 22 => 'Ether Immortal',
                        30 => 'Daimon Baresark', 31 => 'Daimon Saint', 32 => 'Daimon Elder',
                    ];
                @endphp

                <div class="rd-section">Status</div>
                <div class="rd-row"><span class="rd-key">Level</span><span class="rd-val" style="font-weight:700;">{{ $status['level'] ?? '—' }}</span></div>
                <div class="rd-row"><span class="rd-key">Cultivation</span><span class="rd-val">{{ $cultivationMap[$status['cultivation'] ?? 0] ?? $status['cultivation'] ?? '—' }}</span></div>
                <div class="rd-row"><span class="rd-key">EXP</span><span class="rd-val">{{ number_format($status['exp'] ?? 0) }}</span></div>
                <div class="rd-row"><span class="rd-key">SP (Spirit)</span><span class="rd-val">{{ number_format($status['sp'] ?? 0) }}</span></div>
                <div class="rd-row"><span class="rd-key">Reputation</span><span class="rd-val">{{ number_format($status['reputation'] ?? 0) }}</span></div>
                <div class="rd-row"><span class="rd-key">HP (Max)</span><span class="rd-val">{{ number_format($status['hp'] ?? 0) }}</span></div>
                <div class="rd-row"><span class="rd-key">MP (Max)</span><span class="rd-val">{{ number_format($status['mp'] ?? 0) }}</span></div>

                <div class="rd-section">Location</div>
                <div class="rd-row"><span class="rd-key">World Tag</span><span class="rd-val">{{ $status['world_tag'] ?? '—' }}</span></div>
                <div class="rd-row"><span class="rd-key">Position</span><span class="rd-val" style="font-family:monospace;font-size:.78rem;">
                    X:{{ number_format($status['pos_x'] ?? 0, 1) }}
                    Y:{{ number_format($status['pos_y'] ?? 0, 1) }}
                    Z:{{ number_format($status['pos_z'] ?? 0, 1) }}
                </span></div>

                <div class="rd-section">PK</div>
                <div class="rd-row"><span class="rd-key">Invader Time</span><span class="rd-val">{{ $status['invader_time'] ?? 0 }}</span></div>
                <div class="rd-row"><span class="rd-key">Pariah Time</span><span class="rd-val">{{ $status['pariah_time'] ?? 0 }}</span></div>

                @if(!empty($prop))
                <div class="rd-section">Attributes</div>
                    @if(isset($prop['vitality']))
                    <div class="rd-row"><span class="rd-key">VIT (Constitution)</span><span class="rd-val">{{ $prop['vitality'] ?? '—' }}</span></div>
                    @endif
                    @if(isset($prop['energy']))
                    <div class="rd-row"><span class="rd-key">MAG (Intelligence)</span><span class="rd-val">{{ $prop['energy'] ?? '—' }}</span></div>
                    @endif
                    @if(isset($prop['strength']))
                    <div class="rd-row"><span class="rd-key">STR (Strength)</span><span class="rd-val">{{ $prop['strength'] ?? '—' }}</span></div>
                    @endif
                    @if(isset($prop['agility']))
                    <div class="rd-row"><span class="rd-key">DEX (Agility)</span><span class="rd-val">{{ $prop['agility'] ?? '—' }}</span></div>
                    @endif
                @endif

                <div class="rd-section">Coins</div>
                <div class="rd-row"><span class="rd-key">Pocket (Coins)</span><span class="rd-val">{{ number_format($pocket['money'] ?? 0) }}</span></div>
                <div class="rd-row"><span class="rd-key">Storehouse (Coins)</span><span class="rd-val">{{ number_format($store['money'] ?? 0) }}</span></div>

                <div class="rd-section">Timestamps</div>
                <div class="rd-row"><span class="rd-key">Created</span><span class="rd-val" style="font-size:.78rem;">{{ isset($base['create_time']) && $base['create_time'] > 0 ? date('d/m/Y H:i', $base['create_time']) : '—' }}</span></div>
                <div class="rd-row"><span class="rd-key">Last Login</span><span class="rd-val" style="font-size:.78rem;">{{ isset($base['lastlogin']) && $base['lastlogin'] > 0 ? date('d/m/Y H:i', $base['lastlogin']) : '—' }}</span></div>
            @else
                <div style="padding:2rem;text-align:center;color:var(--pw-text-muted);font-size:.85rem;">
                    Game server tidak tersedia atau role tidak ditemukan.<br>
                    <span style="font-size:.78rem;">Data live hanya tersedia saat game server running.</span>
                </div>
            @endif
        </div>

    </div>

</div>
@endsection
