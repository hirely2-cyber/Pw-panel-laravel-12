{{--
  Equipment paperdoll (in-game style) — layout from pw-panel-1.6.5- / iweb;
  default art: /public/pw/ui/equipment-*.png
  @var $equip, $base, $itemNames (keyed item id)
--}}
@php
    $bySlot = [];
    foreach (($equip['items'] ?? []) as $it) {
        $bySlot[(int) ($it['pos'])] = $it;
    }
    $nEquipped = 0;
    foreach ($bySlot as $it) {
        if ((int) ($it['id'] ?? 0) > 0) {
            $nEquipped++;
        }
    }
    $gClass = (int) ($base['gender'] ?? 0) === 1 ? 'female' : 'male';
    $itemNames = $itemNames ?? [];
@endphp
<div class="rl-pw-paper__head">
    <span class="rl-pw-paper__head-title">Equipment</span>
    <span class="rl-pw-paper__head-meta">equipped: {{ $nEquipped }}</span>
</div>
<div class="player__equipment {{ $gClass }}">
    @foreach (range(0, 31) as $slot)
        @php
            $it = $bySlot[$slot] ?? null;
            $ok = $it && (int) ($it['id'] ?? 0) > 0;
            $name = $ok ? ($itemNames[$it['id']] ?? ('#' . $it['id'])) : '';
        @endphp
        <div class="player__equipment-item cell-{{ $slot }}{{ $ok ? ' has-gear' : ' eq-slot-empty' }}"
            @if ($ok) title="{{ $name }}"
            @else title="Slot {{ $slot }}"
            @endif
            @if ($ok)
                @click="select({{ json_encode($it) }}, 'equipment', {{ $slot }})"
                :class="{ 'is-sel': sel && selGroup==='equipment' && sel && sel.id=={{ (int) $it['id'] }} && sel.pos=={{ (int) $it['pos'] }} }"
            @endif>
            @if ($ok)
                <img src="/storage/icons/{{ $it['id'] }}.gif" onerror="this.src='/storage/icons/0.gif'" width="32" height="32" alt="">
            @endif
        </div>
    @endforeach
</div>
