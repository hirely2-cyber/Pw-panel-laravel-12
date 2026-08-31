@extends('layouts.admin')
@section('title', 'Character: ' . $character->name)

@section('content')
@php
    $base   = $roleData['base'] ?? [];
    $status = $roleData['status'] ?? [];
    $pocket = $roleData['pocket'] ?? [];
    $equip  = $roleData['equipment'] ?? [];
    $store  = $roleData['storehouse'] ?? [];
    $prop   = $status['property'] ?? [];

    $raceMap = [0 => 'Human', 1 => 'Untamed', 2 => 'Winged Elf', 3 => 'Tideborn', 4 => 'Earthguard', 5 => 'Nightshade'];
    $genderMap = [0 => 'Male', 1 => 'Female'];
    $pkMap = [0 => 'Off', 1 => 'On'];

    $cultivationMap = [
        0 => '0 – Inchoation', 1 => '9 – Autoscopy', 2 => '19 – Transform',
        3 => '29 – Naissance', 4 => '39 – Reborn', 5 => '49 – Vigilance',
        6 => '59 – Doom', 7 => '69 – Disengage', 8 => '79 – Nirvana',
        20 => '89 – Prime Immortal', 30 => '89 – Daimon Baresark',
        21 => '99 – Pure Immortal', 31 => '99 – Daimon Saint',
        22 => '109 – Ether Immortal', 32 => '109 – Daimon Elder',
    ];

    $vigorOptions = [0 => '000', 99 => '099', 199 => '199', 299 => '299', 399 => '399'];

    $cubiData = $cubiData ?? null;
    $isOnline = $user->isOnline();
    $characters = $user->gameCharacters();

    $fmt = fn($v) => number_format($v ?? 0);
    $fmtDate = fn($ts) => ($ts && $ts > 0) ? \Carbon\Carbon::createFromTimestamp($ts)->format('Y-m-d - H:i:s') : '-';

    $breakcol = 8;
    $itemNamesJson = json_encode($itemNames ?? [], JSON_UNESCAPED_UNICODE);
@endphp

@if(!$roleData)
<div class="pw-adm-card">
    <div class="pw-adm-card__title">Error</div>
    <p style="color:#e05252;">Tidak dapat mengambil data karakter. Game server mungkin offline.</p>
</div>
@else

{{-- Flash Messages --}}
@if(session('success'))
<div style="background:rgba(22,163,106,.15);border:1px solid rgba(22,163,106,.3);color:#16a36a;padding:.6rem 1rem;border-radius:8px;margin-bottom:.8rem;font-size:.82rem;font-weight:600;">
    ✓ {{ session('success') }}
</div>
@endif
@if(session('error'))
<div style="background:rgba(220,38,38,.12);border:1px solid rgba(220,38,38,.3);color:#ef4444;padding:.6rem 1rem;border-radius:8px;margin-bottom:.8rem;font-size:.82rem;font-weight:600;">
    ✕ {{ session('error') }}
</div>
@endif

{{-- Nav Bar --}}
<div class="pw-adm-card" style="margin-bottom:.8rem;">
    <div style="display:flex;align-items:center;flex-wrap:wrap;gap:.8rem;padding:.1rem 0;">
        <div style="font-size:.9rem;font-weight:700;color:var(--pw-gold);">
            <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:.25rem;"><path d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5S7 4.3 7 7s2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v2h20v-2c0-3.3-6.7-5-10-5z"/></svg>
            Character ID: {{ $base['id'] ?? $character->role_id }}
        </div>
        <div style="display:flex;align-items:center;gap:.5rem;flex:1;min-width:200px;">
            <span style="font-size:.7rem;color:var(--pw-text-muted);white-space:nowrap;">Switch:</span>
            <select onchange="if(this.value) window.location.href=this.value" class="rl-select" style="max-width:170px;">
                @foreach($characters as $ch)
                <option value="{{ route('admin.members.character', [$user->ID, $ch->role_id]) }}"
                    {{ $ch->role_id == $character->role_id ? 'selected' : '' }}>{{ $ch->name }}</option>
                @endforeach
            </select>
            @if($isOnline)
                <span class="rl-badge rl-badge--online">● Online</span>
            @else
                <span class="rl-badge rl-badge--offline">● Offline</span>
            @endif
        </div>
        <div style="display:flex;gap:.5rem;margin-left:auto;flex-wrap:wrap;">
            @if(request('view') === 'xml' || request('view') === 'raw')
            <a href="{{ route('admin.members.character', [$user->ID, $character->role_id]) }}"
               class="pw-adm-btn pw-adm-btn--ghost" style="font-size:.72rem;padding:.3rem .6rem;">
                ← GUI Editor
            </a>
            @else
            <a href="{{ route('admin.members.character', [$user->ID, $character->role_id]) }}?view=xml"
               class="pw-adm-btn pw-adm-btn--ghost" style="font-size:.72rem;padding:.3rem .6rem;"
               title="Edit Role XML (XmlRole)">
                &lt;/&gt; XML
            </a>
            @endif
            <a href="{{ route('admin.members.show', $user->ID) }}"
               class="pw-adm-btn pw-adm-btn--ghost" style="font-size:.72rem;padding:.3rem .6rem;">
                ← Back
            </a>
        </div>
    </div>
</div>

@if(request('view') === 'xml' || request('view') === 'raw')
@include('admin.partials.role-xml-panel', [
    'formAction' => route('admin.members.character.xml.save', ['user' => $user, 'roleId' => $character->role_id]),
    'charName' => $base['name'] ?? $character->name,
    'roleXml' => $roleXml,
    'roleXmlError' => $roleXmlError,
    'pwadminRolexmlUrl' => $pwadminRolexmlUrl,
])
@else
{{-- ═══════════════ GUI Detail View ═══════════════ --}}

<form method="POST" action="{{ route('admin.members.character.save', [$user->ID, $character->role_id]) }}">
@csrf

{{-- 3-Column Grid --}}
<div class="rl-grid-3" x-data="roleItems()">

    {{-- ═══ Col 1: Character Info ═══ --}}
    <div class="pw-adm-card" style="display:flex;flex-direction:column;">
        <div class="pw-adm-card__title" style="font-size:.78rem;"><svg width="13" height="13" fill="currentColor" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:.2rem;"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg> Character Info</div>

        <div class="rl-section">General</div>
        <div class="rl-row"><span class="rl-key">Status</span><span class="rl-val">{{ $base['status'] ?? 0 }}</span></div>
        <div class="rl-row"><span class="rl-key">Created</span><span class="rl-val" style="font-size:.68rem;">{{ $fmtDate($base['create_time'] ?? 0) }}</span></div>
        <div class="rl-row"><span class="rl-key">Deleted</span><span class="rl-val" style="font-size:.68rem;">{{ $fmtDate($base['delete_time'] ?? 0) }}</span></div>
        <div class="rl-row"><span class="rl-key">Last Login</span><span class="rl-val" style="font-size:.68rem;">{{ $fmtDate($base['lastlogin'] ?? 0) }}</span></div>

        <div class="rl-section">Location</div>
        <div class="rl-field"><span class="rl-flabel">World ID</span><input type="text" name="world" value="{{ $status['world_tag'] ?? 0 }}" class="rl-input"></div>
        <div class="rl-field"><span class="rl-flabel">Position X</span><input type="text" name="pos_x" value="{{ round($status['pos_x'] ?? 0, 2) }}" class="rl-input"></div>
        <div class="rl-field"><span class="rl-flabel">Position Z</span><input type="text" name="pos_z" value="{{ round($status['pos_z'] ?? 0, 2) }}" class="rl-input"></div>
        <div class="rl-field"><span class="rl-flabel">Altitude Y</span><input type="text" name="pos_y" value="{{ round($status['pos_y'] ?? 0, 2) }}" class="rl-input"></div>

        <div class="rl-section">PK</div>
        <div class="rl-row"><span class="rl-key">PK Mode</span><span class="rl-val">{{ $pkMap[$status['invader_state'] ?? 0] ?? 'Off' }}</span></div>
        <div class="rl-row"><span class="rl-key">Invader Time</span><span class="rl-val">{{ $status['invader_time'] ?? 0 }}</span></div>
        <div class="rl-row"><span class="rl-key">Pariah Time</span><span class="rl-val">{{ $status['pariah_time'] ?? 0 }}</span></div>

        <div class="rl-section">Cubi</div>
        @if($cubiData)
        <div class="rl-row"><span class="rl-key">Balance</span><span class="rl-val">{{ number_format(($cubiData['cash'] ?? 0) / 100, 2) }}</span></div>
        <div class="rl-row"><span class="rl-key">Purchased</span><span class="rl-val">{{ number_format($cubiData['cash_add'] / 100, 2) }}</span></div>
        <div class="rl-row"><span class="rl-key">Bought</span><span class="rl-val">{{ number_format($cubiData['cash_buy'] / 100, 2) }}</span></div>
        <div class="rl-row"><span class="rl-key">Used</span><span class="rl-val">{{ number_format($cubiData['cash_used'] / 100, 2) }}</span></div>
        <div class="rl-row"><span class="rl-key">Sold</span><span class="rl-val">{{ number_format($cubiData['cash_sell'] / 100, 2) }}</span></div>
        @else
        <div class="rl-row"><span class="rl-key" style="color:var(--pw-text-muted);">Data unavailable</span></div>
        @endif
    </div>

    {{-- ═══ Col 2: Character Properties ═══ --}}
    <div class="pw-adm-card" style="display:flex;flex-direction:column;">
        <div class="pw-adm-card__title" style="font-size:.78rem;"><svg width="13" height="13" fill="currentColor" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:.2rem;"><path d="M19.14 12.94c.04-.31.06-.63.06-.94 0-.31-.02-.63-.06-.94l2.03-1.58a.49.49 0 00.12-.61l-1.92-3.32a.49.49 0 00-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54a.484.484 0 00-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96a.49.49 0 00-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.04.31-.06.63-.06.94s.02.63.06.94l-2.03 1.58a.49.49 0 00-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6A3.6 3.6 0 1115.6 12 3.611 3.611 0 0112 15.6z"/></svg> Character Properties — {{ $base['name'] ?? $character->name }}</div>

        <div class="rl-row"><span class="rl-key">Level</span><span class="rl-val"><strong>{{ $status['level'] ?? $character->level }}</strong></span></div>
        <div class="rl-row"><span class="rl-key">Race</span><span class="rl-val">{{ $raceMap[$base['race'] ?? 0] ?? 'Unknown' }}</span></div>
        <div class="rl-row"><span class="rl-key">Class</span><span class="rl-val">{{ $character->class }}</span></div>
        <div class="rl-row"><span class="rl-key">Gender</span><span class="rl-val">{{ $genderMap[$base['gender'] ?? 0] ?? 'Male' }}</span></div>
        <div class="rl-row"><span class="rl-key">HP (Max)</span><span class="rl-val">{{ $fmt($prop['max_hp'] ?? 0) }}</span></div>
        <div class="rl-row"><span class="rl-key">MP (Max)</span><span class="rl-val">{{ $fmt($prop['max_mp'] ?? 0) }}</span></div>
        <div class="rl-row"><span class="rl-key">Spouse</span><span class="rl-val">{{ ($base['spouse'] ?? 0) > 0 ? $base['spouse'] : '' }}</span></div>

        <div class="rl-section">Editable Stats</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.4rem .5rem;">
            <div class="rl-field"><span class="rl-flabel">Reputation</span><input type="text" name="reputation" value="{{ $status['reputation'] ?? 0 }}" class="rl-input"></div>
            <div class="rl-field"><span class="rl-flabel">EXP</span><input type="text" name="exp" value="{{ $status['exp'] ?? 0 }}" class="rl-input"></div>
        </div>
        <div class="rl-field"><span class="rl-flabel">SP (Spirit)</span><input type="text" name="sp" value="{{ $status['sp'] ?? 0 }}" class="rl-input"></div>
        <div class="rl-field">
            <span class="rl-flabel">Cultivation</span>
            <select name="cultivation" class="rl-select">
                @foreach($cultivationMap as $cVal => $cLabel)
                <option value="{{ $cVal }}" {{ ($status['cultivation'] ?? 0) == $cVal ? 'selected' : '' }}>{{ $cLabel }}</option>
                @endforeach
            </select>
        </div>
        <div class="rl-field">
            <span class="rl-flabel">Vigor Points</span>
            <select name="vigor" class="rl-select">
                @php
                    $curV = (int) ($prop['max_ap'] ?? 0);
                    $selV = array_key_exists($curV, $vigorOptions) ? $curV : 0;
                @endphp
                @foreach($vigorOptions as $vVal => $vLabel)
                <option value="{{ $vVal }}" {{ $selV === (int) $vVal ? 'selected' : '' }}>{{ $vLabel }}</option>
                @endforeach
            </select>
        </div>

        <div class="rl-section">Attributes</div>
        <div class="rl-row"><span class="rl-key">Free Points</span><span class="rl-val">{{ $status['pp'] ?? 0 }}</span></div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:2px;">
            <div class="rl-row"><span class="rl-key">CON</span><span class="rl-val">{{ $prop['vitality'] ?? 0 }}</span></div>
            <div class="rl-row"><span class="rl-key">INT</span><span class="rl-val">{{ $prop['energy'] ?? 0 }}</span></div>
            <div class="rl-row"><span class="rl-key">STR</span><span class="rl-val">{{ $prop['strength'] ?? 0 }}</span></div>
            <div class="rl-row"><span class="rl-key">AGI</span><span class="rl-val">{{ $prop['agility'] ?? 0 }}</span></div>
        </div>

        <div class="rl-section">Base Stats</div>
        <div class="rl-row"><span class="rl-key">P-Def</span><span class="rl-val">{{ $prop['defense'] ?? 0 }}</span></div>
        <div class="rl-row"><span class="rl-key">P-Atk</span><span class="rl-val">{{ $prop['damage_low'] ?? 0 }} – {{ $prop['damage_high'] ?? 0 }}</span></div>
        <div class="rl-row"><span class="rl-key">M-Def</span><span class="rl-val">{{ $prop['resistance_0'] ?? 0 }}</span></div>
        <div class="rl-row"><span class="rl-key">M-Atk</span><span class="rl-val">{{ $prop['damage_magic_low'] ?? 0 }} – {{ $prop['damage_magic_high'] ?? 0 }}</span></div>
    </div>

    {{-- ═══ Col 3: Items & Coins ═══ --}}
    <div class="pw-adm-card" style="display:flex;flex-direction:column;">
        <div class="pw-adm-card__title" style="font-size:.78rem;"><svg width="13" height="13" fill="currentColor" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:.2rem;"><path d="M20 2H4c-1.1 0-2 .9-2 2v3.01c0 .72.43 1.34 1 1.69V20c0 1.1 1.1 2 2 2h14c.9 0 2-.9 2-2V8.7c.57-.35 1-.97 1-1.69V4c0-1.1-.9-2-2-2zm-5 12H9v-2h6v2zm5-7H4V4h16v3z"/></svg> Items &amp; Coins</div>

        {{-- Selected item name --}}
        <div style="text-align:center;min-height:16px;margin-bottom:.3rem;">
            <span x-show="sel" x-text="sel ? itemName(sel.id) : ''" style="font-size:.75rem;font-weight:600;color:var(--pw-gold);">&nbsp;</span>
        </div>

        {{-- Item Grid: in-game style equipment paperdoll (pw-panel 1.6.5) + inv/store table --}}
        <div class="rl-pw-paper">
            @include('admin.members._character-equipment-paperdoll', ['equip' => $equip, 'base' => $base, 'itemNames' => $itemNames])
        </div>
        <div class="rl-item-scroll">
            <table class="rl-item-table">
                <tr><td colspan="{{ $breakcol }}" class="rl-item-header">Inventory</td></tr>
                @php $invItems = $pocket['items'] ?? []; $br = 0; @endphp
                @foreach($invItems as $item)
                    @if($item['id'] <= 0) @php $br++; @endphp @continue @endif
                    @if($br % $breakcol == 0)<tr>@endif
                    <td class="rl-item-td">
                        <div class="rl-item-slot"
                             @click="select({{ json_encode($item) }}, 'inventory', {{ $br }})"
                             x-bind:title="itemName({{ $item['id'] }})"
                             :class="{ 'rl-item-slot--active': sel && sel.id=={{ $item['id'] }} && sel.pos=={{ $item['pos'] }} && selGroup==='inventory' }">
                            <img class="rl-item-icon" src="/storage/icons/{{ $item['id'] }}.gif" onerror="this.src='/storage/icons/0.gif'" width="40" height="40" alt="">
                        </div>
                    </td>
                    @php $br++; @endphp
                    @if($br % $breakcol == 0)</tr>@endif
                @endforeach
                @if($br > 0 && $br % $breakcol != 0)
                    @for($f = $br % $breakcol; $f < $breakcol; $f++)<td class="rl-item-td rl-item-td--empty"></td>@endfor
                    </tr>
                @endif

                <tr><td colspan="{{ $breakcol }}" class="rl-item-header">Storehouse</td></tr>
                @php $storeItems = $store['items'] ?? []; $br = 0; @endphp
                @foreach($storeItems as $item)
                    @if($item['id'] <= 0) @php $br++; @endphp @continue @endif
                    @if($br % $breakcol == 0)<tr>@endif
                    <td class="rl-item-td">
                        <div class="rl-item-slot"
                             @click="select({{ json_encode($item) }}, 'storage', {{ $br }})"
                             x-bind:title="itemName({{ $item['id'] }})"
                             :class="{ 'rl-item-slot--active': sel && sel.id=={{ $item['id'] }} && sel.pos=={{ $item['pos'] }} && selGroup==='storage' }">
                            <img class="rl-item-icon" src="/storage/icons/{{ $item['id'] }}.gif" onerror="this.src='/storage/icons/0.gif'" width="40" height="40" alt="">
                        </div>
                    </td>
                    @php $br++; @endphp
                    @if($br % $breakcol == 0)</tr>@endif
                @endforeach
                @if($br > 0 && $br % $breakcol != 0)
                    @for($f = $br % $breakcol; $f < $breakcol; $f++)<td class="rl-item-td rl-item-td--empty"></td>@endfor
                    </tr>
                @endif
            </table>
        </div>

        {{-- Selected Item Detail --}}
        <div class="rl-section">Selected Item</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.3rem .5rem;">
            <div class="rl-field"><span class="rl-flabel">Item Name</span><span class="rl-fval" x-text="sel ? itemName(sel.id) : ''" style="color:var(--pw-gold);font-weight:600;"></span></div>
            <div class="rl-field"><span class="rl-flabel">Item ID</span><span class="rl-fval" x-text="sel ? sel.id : ''"></span></div>
            <div class="rl-field"><span class="rl-flabel">Group</span><span class="rl-fval" x-text="selGroup || ''"></span></div>
            <div class="rl-field"><span class="rl-flabel">Index</span><span class="rl-fval" x-text="selIndex >= 0 ? selIndex : ''"></span></div>
            <div class="rl-field"><span class="rl-flabel">GUID 1</span><span class="rl-fval" x-text="sel ? sel.guid1 : ''"></span></div>
            <div class="rl-field"><span class="rl-flabel">GUID 2</span><span class="rl-fval" x-text="sel ? sel.guid2 : ''"></span></div>
            <div class="rl-field"><span class="rl-flabel">Proctype</span><span class="rl-fval" x-text="sel ? sel.proctype : ''"></span></div>
            <div class="rl-field"><span class="rl-flabel">Mask</span><span class="rl-fval" x-text="sel ? sel.mask : ''"></span></div>
            <div class="rl-field"><span class="rl-flabel">Position</span><span class="rl-fval" x-text="sel ? sel.pos : ''"></span></div>
            <div class="rl-field"><span class="rl-flabel">Expire</span><span class="rl-fval" x-text="sel ? (sel.expire_date > 0 ? sel.expire_date : '0') : ''"></span></div>
            <div class="rl-field"><span class="rl-flabel">Stacked</span><span class="rl-fval" x-text="sel ? sel.count : ''"></span></div>
            <div class="rl-field"><span class="rl-flabel">Max Stack</span><span class="rl-fval" x-text="sel ? sel.max_count : ''"></span></div>
        </div>
        <div class="rl-field"><span class="rl-flabel">Hex Data</span><span class="rl-fval" x-text="sel ? sel.data : ''" style="font-family:monospace;font-size:.62rem;word-break:break-all;"></span></div>

        {{-- Coins --}}
        <div class="rl-section">Coins</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.3rem .5rem;">
            <div class="rl-field"><span class="rl-flabel">Pocket</span><input type="text" name="pocket_money" value="{{ $pocket['money'] ?? 0 }}" class="rl-input" style="color:var(--pw-gold);font-weight:600;"></div>
            <div class="rl-field"><span class="rl-flabel">Storehouse</span><input type="text" name="store_money" value="{{ $store['money'] ?? 0 }}" class="rl-input" style="color:var(--pw-gold);font-weight:600;"></div>
        </div>
    </div>

</div>{{-- end rl-grid-3 --}}

{{-- Save Button --}}
<div style="text-align:center;margin-top:1rem;">
    <button type="submit" class="pw-adm-btn" style="padding:.6rem 2.5rem;font-size:.85rem;font-weight:700;background:var(--pw-gold);color:#fff;border-radius:8px;cursor:pointer;border:none;letter-spacing:.3px;">
        <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:.3rem;"><path d="M17 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V7l-4-4zm-5 16a3 3 0 110-6 3 3 0 010 6zm3-10H5V5h10v4z"/></svg> Save Character Data
    </button>
</div>

</form>

@endif
@endif

<style>
/* ── Grid ── */
.rl-grid-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:.8rem; align-items:stretch; }
@media(max-width:1100px) { .rl-grid-3 { grid-template-columns:1fr; } }

/* ── Section title ── */
.rl-section { font-size:.65rem; font-weight:700; text-transform:uppercase; letter-spacing:.6px; color:var(--pw-text-muted); margin:.8rem 0 .3rem; padding-bottom:.2rem; border-bottom:1px solid var(--pw-border); }

/* ── Info rows ── */
.rl-row { display:flex; justify-content:space-between; align-items:center; padding:.2rem 0; font-size:.76rem; }
.rl-key { color:var(--pw-text-muted); }
.rl-val { font-weight:500; text-align:right; }

/* ── Field (label + value/input) ── */
.rl-field { margin-bottom:.4rem; }
.rl-flabel { display:block; font-size:.6rem; font-weight:600; color:var(--pw-text-muted); text-transform:uppercase; letter-spacing:.4px; margin-bottom:.15rem; }
.rl-fval { display:block; padding:.3rem .45rem; background:rgba(255,255,255,.04); border:1px solid var(--pw-border); border-radius:5px; font-size:.76rem; min-height:1.2em; word-break:break-all; }

/* ── Inputs ── */
.rl-input { display:block; width:100%; padding:.3rem .45rem; background:rgba(255,255,255,.06); border:1px solid var(--pw-border); border-radius:5px; font-size:.76rem; color:#fff; box-sizing:border-box; }
.rl-input:focus { outline:none; border-color:var(--pw-gold); background:rgba(255,255,255,.09); }
[data-theme="light"] .rl-input { background:#ffffff; border-color:rgba(0,0,0,.2); color:var(--pw-text); }
[data-theme="light"] .rl-input:focus { background:#ffffff; }
[data-theme="light"] .rl-fval { background:#e8e8e8; border-color:rgba(0,0,0,.12); color:var(--pw-text); }
.rl-select { display:block; width:100%; padding:.3rem .45rem; background:rgba(255,255,255,.06); border:1px solid var(--pw-border); border-radius:5px; font-size:.76rem; color:#fff; box-sizing:border-box; }
.rl-select:focus { outline:none; border-color:var(--pw-gold); }
[data-theme="light"] .rl-select { background:#ffffff; border-color:rgba(0,0,0,.2); color:var(--pw-text); }
[data-theme="light"] .rl-select option { background:#ffffff; color:var(--pw-text); }
[data-theme="light"] .rl-item-header { background:rgba(0,0,0,.04); }

/* ── Badge ── */
.rl-badge { font-size:.65rem; font-weight:600; padding:.12rem .45rem; border-radius:12px; }
.rl-badge--online { background:rgba(22,163,106,.15); color:#16a36a; }
.rl-badge--offline { background:rgba(220,38,38,.12); color:#dc2626; }

/* ── Item scroll box (grid: fixed slot = hover/click, icon scaled up) ── */
.rl-item-scroll { height:220px; overflow:auto; border:1px solid var(--pw-border); border-radius:6px; padding:4px; margin-bottom:.3rem; }
.rl-item-table { width: max-content; max-width: 100%; margin: 0 auto; border-collapse: separate; border-spacing: 2px; }
.rl-item-header { padding:2px 4px; font-weight:700; font-size:.65rem; color:var(--pw-text-muted); text-align:center; background:rgba(255,255,255,.03); }
.rl-item-td { width: 48px; min-width: 48px; max-width: 48px; padding: 0; text-align: center; vertical-align: middle; line-height: 0; }
.rl-item-slot {
    display: inline-flex; align-items: center; justify-content: center; box-sizing: border-box;
    width: 44px; height: 44px; margin: 0 auto; cursor: pointer; border-radius: 5px;
    transition: background .1s, box-shadow .1s;
}
.rl-item-slot:hover { background: rgba(200,151,42,.12); }
.rl-item-slot--active { background: rgba(200,151,42,.2) !important; box-shadow: inset 0 0 0 1px var(--pw-gold); }
.rl-item-td--empty { cursor: default; min-height: 44px; }
.rl-item-icon { display: block; width: 40px; height: 40px; object-fit: contain; image-rendering: pixelated; vertical-align: middle; }
[data-theme="light"] .rl-item-slot:hover { background: rgba(200, 151, 42, .14); }
[data-theme="light"] .rl-item-slot--active { background: rgba(200, 151, 42, .22) !important; }

/* ── PW in-game style equipment paperdoll (assets: /public/pw/ui/) ── */
.rl-pw-paper {
    border: 1px solid var(--pw-border);
    border-radius: 8px;
    background: rgba(0,0,0,.12);
    padding: 8px 10px 10px;
    margin-bottom: .5rem;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
[data-theme="light"] .rl-pw-paper { background: #f1ede6; }
.rl-pw-paper__head { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 4px; padding-bottom: 4px; border-bottom: 1px dashed var(--pw-border); }
.rl-pw-paper__head-title { font-size: .64rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: var(--pw-gold); }
.rl-pw-paper__head-meta { font-size: .58rem; color: var(--pw-text-muted); font-family: ui-monospace, monospace; }
/* Fixed 313×273 — slot coordinates match iweb; do not scale background */
.rl-pw-paper .player__equipment {
    position: relative; margin: 4px auto 0; min-width: 313px; width: 313px; height: 273px;
    background: url('/pw/ui/equipment-bg.png') no-repeat center top;
}
.rl-pw-paper .player__equipment:before {
    content: ''; position: absolute; left: 120px; top: 20px; width: 79px; height: 150px; z-index: 0; pointer-events: none; opacity: .72;
}
.rl-pw-paper .player__equipment.male:before   { background: url('/pw/ui/equipment-male.png') no-repeat; }
.rl-pw-paper .player__equipment.female:before { background: url('/pw/ui/equipment-female.png') no-repeat; }
.rl-pw-paper .player__equipment-item {
    position: absolute; width: 32px; height: 32px; z-index: 2; box-sizing: border-box;
    background: rgba(0,0,0,.2); border: 1px solid rgba(90,70,48,.5);
    cursor: pointer; transition: filter .1s, border-color .1s, box-shadow .1s;
}
.rl-pw-paper .player__equipment-item.has-gear:hover { filter: brightness(1.15); border-color: var(--pw-gold); }
.rl-pw-paper .player__equipment-item.is-sel { box-shadow: inset 0 0 0 2px var(--pw-gold); border-color: var(--pw-gold); }
.rl-pw-paper .player__equipment-item.eq-slot-empty { pointer-events: none; opacity: .35; border-color: rgba(80,60,40,.3); }
.rl-pw-paper .player__equipment-item > img { width: 32px; height: 32px; display: block; image-rendering: pixelated; }
/* Slot layout — from iweb (hirely2-cyber/pw-panel-1.6.5- public/panel.css) */
.rl-pw-paper .player__equipment-item.cell-0  { top: 93px; left: 273px; }
.rl-pw-paper .player__equipment-item.cell-1  { top:  3px; left: 235px; }
.rl-pw-paper .player__equipment-item.cell-2  { top: 48px; left:   8px; }
.rl-pw-paper .player__equipment-item.cell-3  { top: 48px; left:  46px; }
.rl-pw-paper .player__equipment-item.cell-4  { top: 48px; left: 235px; }
.rl-pw-paper .player__equipment-item.cell-5  { top: 93px; left:  46px; }
.rl-pw-paper .player__equipment-item.cell-6  { top: 93px; left: 235px; }
.rl-pw-paper .player__equipment-item.cell-7  { top: 138px; left: 235px; }
.rl-pw-paper .player__equipment-item.cell-8  { top: 93px; left:   8px; }
.rl-pw-paper .player__equipment-item.cell-9  { top: 138px; left:   8px; }
.rl-pw-paper .player__equipment-item.cell-10 { top: 138px; left:  46px; }
.rl-pw-paper .player__equipment-item.cell-11 { top: 138px; left: 273px; }
.rl-pw-paper .player__equipment-item.cell-12 { top:  3px; left:  84px; }
.rl-pw-paper .player__equipment-item.cell-13 { top: 188px; left: 167px; }
.rl-pw-paper .player__equipment-item.cell-14 { top: 188px; left: 220px; }
.rl-pw-paper .player__equipment-item.cell-15 { top: 188px; left: 273px; }
.rl-pw-paper .player__equipment-item.cell-16 { top: 188px; left: 114px; }
.rl-pw-paper .player__equipment-item.cell-17 { top: 48px; left: 273px; }
.rl-pw-paper .player__equipment-item.cell-18 { top: 48px; left:  84px; }
.rl-pw-paper .player__equipment-item.cell-19 { top:  3px; left: 273px; }
.rl-pw-paper .player__equipment-item.cell-20 { top:  3px; left:   8px; }
.rl-pw-paper .player__equipment-item.cell-21 { top:  3px; left:  46px; }
.rl-pw-paper .player__equipment-item.cell-22 { top: 93px; left:  84px; }
.rl-pw-paper .player__equipment-item.cell-23 { top: 138px; left:  84px; }
.rl-pw-paper .player__equipment-item.cell-24 { top:  3px; left: 197px; }
.rl-pw-paper .player__equipment-item.cell-25 { top: 188px; left:  61px; }
.rl-pw-paper .player__equipment-item.cell-26 { top: 48px; left: 197px; }
.rl-pw-paper .player__equipment-item.cell-27 { top: 93px; left: 197px; }
.rl-pw-paper .player__equipment-item.cell-28 { top: 136px; left: 197px; }
.rl-pw-paper .player__equipment-item.cell-29 { top: 188px; left:   8px; }
.rl-pw-paper .player__equipment-item.cell-30 { top:  3px; left: 130px; }
.rl-pw-paper .player__equipment-item.cell-31 { top:  3px; left: 168px; }

/* ── XML pre ── */
.rl-xml-pre {
    background:rgba(0,0,0,.3); border:1px solid var(--pw-border); border-radius:6px;
    padding:.8rem; font-size:.68rem; font-family:'Consolas','Courier New',monospace;
    color:#a8b5c8; overflow:auto; max-height:70vh; line-height:1.4; white-space:pre-wrap;
    word-break:break-word;
}
[data-theme="light"] .rl-xml-pre {
    background:#ffffff; border-color:rgba(0,0,0,.15); color:#1a1a1a;
}
</style>

<script>
function roleItems() {
    return {
        sel: null,
        selGroup: '',
        selIndex: -1,
        itemNames: {!! $itemNamesJson !!},
        itemName(id) {
            return this.itemNames[id] || ('ID: ' + id);
        },
        select(item, group, index) {
            if (this.sel && this.sel.id === item.id && this.sel.pos === item.pos && this.selGroup === group) {
                this.sel = null; this.selGroup = ''; this.selIndex = -1;
            } else {
                this.sel = item; this.selGroup = group; this.selIndex = index;
            }
        }
    };
}
</script>
@if(request('view') === 'xml' || request('view') === 'raw')
@include('admin.partials.role-xml-assets', ['roleXml' => $roleXml ?? ''])
@endif
@endsection
