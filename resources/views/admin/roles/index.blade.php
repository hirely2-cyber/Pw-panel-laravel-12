@extends('layouts.admin')

@section('title', 'Character Roles')

@push('styles')
<style>
.rl-search-wrap {
    display:flex; gap:.6rem; flex-wrap:wrap; align-items:center;
}
.rl-input {
    padding:.45rem .7rem; border-radius:6px; font-size:.84rem;
    border:1px solid rgba(255,255,255,.12); background:rgba(255,255,255,.05);
    color:var(--pw-text-light); transition:border-color .15s;
}
.rl-input:focus { outline:none; border-color:rgba(240,165,0,.5); }
.rl-dd-btn {
    display:flex; align-items:center; justify-content:space-between;
    padding:.42rem .65rem; border-radius:6px; font-size:.82rem;
    border:1px solid rgba(255,255,255,.12); background:rgba(255,255,255,.05);
    color:var(--pw-text-light); cursor:pointer; white-space:nowrap; min-width:140px;
    transition:border-color .15s;
}
.rl-dd-btn:hover { border-color:rgba(255,255,255,.25); }
.rl-dd-btn:focus { outline:none; border-color:rgba(240,165,0,.5); }
.rl-dd-menu {
    position:absolute; top:calc(100% + 4px); left:0; right:0; z-index:50;
    background:var(--pw-bg-card); border:1px solid var(--pw-border); border-radius:7px;
    padding:4px; max-height:220px; overflow-y:auto;
    box-shadow:0 8px 24px rgba(0,0,0,.5);
}
.rl-dd-item {
    display:block; width:100%; text-align:left; padding:.38rem .65rem;
    background:transparent; border:none; color:#cbd5e1; font-size:.82rem;
    cursor:pointer; border-radius:5px; transition:background .1s,color .1s;
}
.rl-dd-item:hover { background:rgba(240,165,0,.12); color:#fff; }
.rl-dd-item--active { background:rgba(240,165,0,.18); color:#f0a500; font-weight:600; }
[data-theme="light"] .rl-dd-btn { background:#fff; border-color:#cbd5e1; color:#0f172a; }
[data-theme="light"] .rl-dd-menu { box-shadow:0 8px 24px rgba(0,0,0,.12); }
[data-theme="light"] .rl-dd-item { color:var(--pw-text); }
[data-theme="light"] .rl-dd-item:hover { background:rgba(138,94,0,.1); }
[data-theme="light"] .rl-dd-item--active { background:rgba(138,94,0,.12); color:var(--pw-gold); }
.rl-badge {
    display:inline-flex; align-items:center; gap:4px;
    padding:.15rem .45rem; border-radius:999px; font-size:.72rem; font-weight:600;
    border:1px solid rgba(240,165,0,.3); background:rgba(240,165,0,.1); color:#f0a500;
}
.rl-sync-btn {
    padding:.45rem .8rem; border-radius:6px; font-size:.82rem; font-weight:600;
    border:1px solid rgba(80,200,120,.35); background:rgba(80,200,120,.12); color:#50c878;
    cursor:pointer; transition:all .15s; display:inline-flex; align-items:center; gap:6px;
}
.rl-sync-btn:hover { background:rgba(80,200,120,.2); }
.rl-sync-btn:disabled { opacity:.5; cursor:wait; }
.rl-class-icon { width:22px; height:22px; border-radius:4px; vertical-align:middle; }
.rl-sort-link { color:var(--pw-text-muted); text-decoration:none; font-size:.72rem; }
.rl-sort-link:hover { color:var(--pw-text-light); }
.rl-sort-active { color:#f0a500; font-weight:700; }
[data-theme="light"] .rl-input { background:#fff; border-color:#cbd5e1; color:#0f172a; }
</style>
@endpush

@section('content')
@php
    $sortUrl   = fn($field) => route('admin.roles.index', array_merge(request()->query(), ['sort' => $field, 'dir' => ($sort === $field && $dir === 'asc') ? 'desc' : 'asc']));
    $sortIcon  = fn($field) => $sort === $field ? ($dir === 'asc' ? '▲' : '▼') : '';
    $sortClass = fn($field) => $sort === $field ? 'rl-sort-active' : '';
@endphp
<div style="display:grid;gap:1rem;">

    {{-- Header + Sync --}}
    <div class="pw-adm-card" style="padding:1rem 1.2rem;">
        <div style="display:flex;justify-content:space-between;gap:1rem;flex-wrap:wrap;align-items:center;">
            <div>
                <div style="font-size:1rem;font-weight:700;color:var(--pw-text-light);">Character Roles</div>
                <div style="font-size:.78rem;color:var(--pw-text-muted);margin-top:.2rem;">
                    Total <strong style="color:var(--pw-text-light);">{{ number_format($totalRoles) }}</strong> character terdaftar di database
                </div>
            </div>
            <div style="display:flex;gap:.6rem;align-items:center;flex-wrap:wrap;">
                <div id="sync-msg" style="font-size:.78rem;color:var(--pw-text-muted);"></div>
                <button id="sync-btn" class="rl-sync-btn" onclick="syncRoles()">
                    <svg viewBox="0 0 16 16" width="14" fill="none"><path d="M2 8a6 6 0 0110.9-3.5M14 8a6 6 0 01-10.9 3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M13 1v4h-4M3 15v-4h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Sync Roles
                </button>
            </div>
        </div>
        <div style="font-size:.74rem;color:var(--pw-text-muted);margin-top:.5rem;padding:.35rem .6rem;border-radius:6px;background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.2);">
            Sync akan mengambil data character dari game server dan menyimpan ke MySQL. Server game harus running, maps harus stopped.
        </div>
    </div>

    {{-- Character Templates (Default Characters) --}}
    <div class="pw-adm-card" style="padding:1rem 1.2rem;">
        <div style="font-size:.88rem;font-weight:700;color:var(--pw-text-light);margin-bottom:.7rem;display:flex;align-items:center;gap:8px;">
            <svg viewBox="0 0 16 16" width="14" fill="currentColor" style="color:#f0a500;"><path d="M8 1.5a.5.5 0 01.5.5v2.5H11a.5.5 0 010 1H8.5V8a.5.5 0 01-1 0V5.5H5a.5.5 0 010-1h2.5V2a.5.5 0 01.5-.5z"/><path d="M3 3.5h3V4H3a1 1 0 00-1 1v7a1 1 0 001 1h10a1 1 0 001-1V5a1 1 0 00-1-1h-3v-.5h3A1.5 1.5 0 0114.5 5v7a1.5 1.5 0 01-1.5 1.5H3A1.5 1.5 0 011.5 12V5A1.5 1.5 0 013 3.5z"/></svg>
            Character Templates
        </div>
        <div style="display:grid;grid-template-columns:repeat(6,1fr);gap:8px;">
            @php
                $templates = [
                    16 => ['class' => 'Blademaster', 'race' => 'Human', 'icon' => 'blademaster'],
                    19 => ['class' => 'Wizard', 'race' => 'Human', 'icon' => 'wizzard'],
                    20 => ['class' => 'Psychic', 'race' => 'Tideborn', 'icon' => 'psychic'],
                    27 => ['class' => 'Assassin', 'race' => 'Tideborn', 'icon' => 'assasin'],
                    23 => ['class' => 'Venomancer', 'race' => 'Untamed', 'icon' => 'venomancer'],
                    24 => ['class' => 'Barbarian', 'race' => 'Untamed', 'icon' => 'barbarian'],
                    28 => ['class' => 'Archer', 'race' => 'Winged Elf', 'icon' => 'archer'],
                    31 => ['class' => 'Cleric', 'race' => 'Winged Elf', 'icon' => 'cleric'],
                    18 => ['class' => 'Seeker', 'race' => 'Earthguard', 'icon' => 'seeker'],
                    17 => ['class' => 'Mystic', 'race' => 'Earthguard', 'icon' => 'mystic'],
                    21 => ['class' => 'Duskblade', 'race' => 'Nightshade', 'icon' => 'duskblade'],
                    22 => ['class' => 'Stormbringer', 'race' => 'Nightshade', 'icon' => 'stormbringer'],
                ];
            @endphp
            @foreach($templates as $tplId => $tpl)
                <a href="{{ route('admin.roles.show', $tplId) }}" style="display:flex;align-items:center;gap:8px;padding:8px 12px;border-radius:6px;border:1px solid rgba(255,255,255,.08);background:rgba(255,255,255,.03);color:var(--pw-text-light);text-decoration:none;font-size:.8rem;transition:all .15s;" onmouseover="this.style.background='rgba(240,165,0,.1)';this.style.borderColor='rgba(240,165,0,.3)'" onmouseout="this.style.background='rgba(255,255,255,.03)';this.style.borderColor='rgba(255,255,255,.08)'">
                    <img src="{{ asset('images/class/' . $tpl['icon'] . '.png') }}" style="width:22px;height:22px;border-radius:4px;" alt="">
                    <span style="font-weight:600;">{{ $tpl['class'] }}</span>
                    <span style="margin-left:auto;opacity:.5;font-size:.72rem;">{{ $tpl['race'] }}</span>
                </a>
            @endforeach
        </div>
    </div>

    {{-- Search & Filter --}}
    <div class="pw-adm-card" style="padding:.8rem 1.2rem;">
        <form method="GET" action="{{ route('admin.roles.index') }}" class="rl-search-wrap">
            <input type="text" name="search" value="{{ $search }}" class="rl-input" style="flex:1;min-width:200px;" placeholder="Cari nama character...">
@php($selectedClassName = $classFilter !== '' && $classFilter !== null ? ($classMap[(int)$classFilter] ?? 'Semua Class') : 'Semua Class')
            <div x-data="{ open: false, cls: '{{ $classFilter }}', label: '{{ $selectedClassName }}' }" style="position:relative;" @@click.away="open = false">
                <input type="hidden" name="class" :value="cls">
                <button @@click="open = !open" type="button" class="rl-dd-btn">
                    <span x-text="label"></span>
                    <svg viewBox="0 0 12 12" width="10" fill="none" style="opacity:.5;margin-left:6px;flex-shrink:0;transition:transform .15s;" :style="open && 'transform:rotate(180deg)'"><path d="M2 4l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                </button>
                <div x-show="open" x-transition.opacity.duration.150ms class="rl-dd-menu">
                    <button type="button" @@click="cls=''; label='Semua Class'; open=false" class="rl-dd-item" :class="cls==='' && 'rl-dd-item--active'">Semua Class</button>
                    @foreach($classMap as $id => $name)
                    <button type="button" @@click="cls='{{ $id }}'; label='{{ $name }}'; open=false" class="rl-dd-item" :class="cls==='{{ $id }}' && 'rl-dd-item--active'">{{ $name }}</button>
                    @endforeach
                </div>
            </div>
            <button type="submit" class="pw-adm-btn" style="font-size:.82rem;">Cari</button>
            @if($search || $classFilter)
                <a href="{{ route('admin.roles.index') }}" style="font-size:.78rem;color:var(--pw-text-muted);text-decoration:none;">&times; Reset</a>
            @endif
        </form>
    </div>

    {{-- Role Table --}}
    <div class="pw-adm-card" style="padding:1rem 1.2rem;">
        <div style="overflow:auto;">
            <table style="width:100%;border-collapse:collapse;min-width:900px;">
                <thead>
                    <tr>
                        <th style="text-align:left;padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.72rem;color:var(--pw-text-muted);">
                            <a href="{{ $sortUrl('role_id') }}" class="rl-sort-link {{ $sortClass('role_id') }}">Role ID {{ $sortIcon('role_id') }}</a>
                        </th>
                        <th style="text-align:left;padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.72rem;color:var(--pw-text-muted);">
                            <a href="{{ $sortUrl('role_name') }}" class="rl-sort-link {{ $sortClass('role_name') }}">Character {{ $sortIcon('role_name') }}</a>
                        </th>
                        <th style="text-align:center;padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.72rem;color:var(--pw-text-muted);">
                            <a href="{{ $sortUrl('role_level') }}" class="rl-sort-link {{ $sortClass('role_level') }}">Level {{ $sortIcon('role_level') }}</a>
                        </th>
                        <th style="text-align:left;padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.72rem;color:var(--pw-text-muted);">
                            <a href="{{ $sortUrl('role_occupation') }}" class="rl-sort-link {{ $sortClass('role_occupation') }}">Class {{ $sortIcon('role_occupation') }}</a>
                        </th>
                        <th style="text-align:left;padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.72rem;color:var(--pw-text-muted);">Race</th>
                        <th style="text-align:left;padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.72rem;color:var(--pw-text-muted);">Gender</th>
                        <th style="text-align:left;padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.72rem;color:var(--pw-text-muted);">
                            <a href="{{ $sortUrl('faction_name') }}" class="rl-sort-link {{ $sortClass('faction_name') }}">Faction {{ $sortIcon('faction_name') }}</a>
                        </th>
                        <th style="text-align:center;padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.72rem;color:var(--pw-text-muted);">
                            <a href="{{ $sortUrl('pvp_kills') }}" class="rl-sort-link {{ $sortClass('pvp_kills') }}">PvP K/D {{ $sortIcon('pvp_kills') }}</a>
                        </th>
                        <th style="text-align:center;padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.72rem;color:var(--pw-text-muted);">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $r)
                        <tr>
                            <td style="padding:.55rem;border-bottom:1px solid rgba(255,255,255,.06);font-size:.78rem;font-family:monospace;">{{ $r->role_id }}</td>
                            <td style="padding:.55rem;border-bottom:1px solid rgba(255,255,255,.06);font-size:.82rem;font-weight:600;">{{ $r->role_name }}</td>
                            <td style="padding:.55rem;border-bottom:1px solid rgba(255,255,255,.06);font-size:.82rem;text-align:center;font-weight:700;">{{ $r->role_level }}</td>
                            <td style="padding:.55rem;border-bottom:1px solid rgba(255,255,255,.06);font-size:.78rem;">
                                <div style="display:flex;align-items:center;gap:6px;">
                                    <img src="{{ asset('images/class/' . ($iconMap[$r->role_occupation] ?? 'blademaster') . '.png') }}" class="rl-class-icon" alt="">
                                    {{ $classMap[$r->role_occupation] ?? 'Unknown' }}
                                </div>
                            </td>
                            <td style="padding:.55rem;border-bottom:1px solid rgba(255,255,255,.06);font-size:.78rem;">{{ $raceMap[$r->role_race] ?? 'Unknown' }}</td>
                            <td style="padding:.55rem;border-bottom:1px solid rgba(255,255,255,.06);font-size:.78rem;">{{ $r->role_gender == 0 ? 'Male' : 'Female' }}</td>
                            <td style="padding:.55rem;border-bottom:1px solid rgba(255,255,255,.06);font-size:.78rem;">
                                @if($r->faction_name)
                                    <span class="rl-badge">{{ $r->faction_name }} Lv.{{ $r->faction_level }}</span>
                                @else
                                    <span style="color:var(--pw-text-muted);">—</span>
                                @endif
                            </td>
                            <td style="padding:.55rem;border-bottom:1px solid rgba(255,255,255,.06);font-size:.78rem;text-align:center;">
                                <span style="color:#50c878;">{{ $r->pvp_kills }}</span> / <span style="color:#ef4444;">{{ $r->pvp_deads }}</span>
                            </td>
                            <td style="padding:.55rem;border-bottom:1px solid rgba(255,255,255,.06);text-align:center;">
                                <a href="{{ route('admin.roles.show', $r->role_id) }}" class="pw-adm-btn" style="font-size:.74rem;padding:.25rem .5rem;">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="padding:2rem;border-bottom:1px solid rgba(255,255,255,.06);font-size:.85rem;color:var(--pw-text-muted);text-align:center;">
                                Belum ada data character. Klik <strong>Sync Roles</strong> untuk mengambil data dari game server.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($roles->hasPages())
            <div style="margin-top:1rem;">
                {{ $roles->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
function syncRoles() {
    const btn = document.getElementById('sync-btn');
    const msg = document.getElementById('sync-msg');

    if (!confirm('Sync roles dari game server ke MySQL?\nPastikan server game running dan maps stopped.')) return;

    btn.disabled = true;
    btn.innerHTML = '<svg class="spin" viewBox="0 0 16 16" width="14" fill="none"><path d="M2 8a6 6 0 0110.9-3.5M14 8a6 6 0 01-10.9 3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M13 1v4h-4M3 15v-4h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg> Syncing...';
    msg.textContent = '';

    fetch('{{ route("admin.roles.sync") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
    })
    .then(r => r.json())
    .then(data => {
        msg.textContent = data.message || 'Done';
        msg.style.color = data.ok ? '#50c878' : '#ef4444';
        if (data.ok) {
            setTimeout(() => location.reload(), 1500);
        }
    })
    .catch(err => {
        msg.textContent = 'Request gagal: ' + err.message;
        msg.style.color = '#ef4444';
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<svg viewBox="0 0 16 16" width="14" fill="none"><path d="M2 8a6 6 0 0110.9-3.5M14 8a6 6 0 01-10.9 3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M13 1v4h-4M3 15v-4h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg> Sync Roles';
    });
}
</script>
<style>.spin{animation:spin 1s linear infinite}@keyframes spin{to{transform:rotate(360deg)}}</style>
@endpush
