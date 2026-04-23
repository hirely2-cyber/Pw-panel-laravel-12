@extends('layouts.admin')
@section('title', 'Buat Poll Dungeon Voting')

@section('content')
<div class="pw-adm-card">
    <div style="margin-bottom:1.2rem;">
        <a href="{{ route('admin.dungeon-vote.index') }}" style="color:var(--pw-text-muted);font-size:.85rem;">← Kembali ke Dungeon Voting</a>
        <h2 style="font-size:1.1rem;font-weight:700;margin-top:.5rem;color:var(--pw-gold);">Buat Poll Baru</h2>
    </div>

    <form action="{{ route('admin.dungeon-vote.store') }}" method="POST">
        @csrf

        <div style="display:grid;grid-template-columns:320px 1fr;gap:1.5rem;align-items:start;">

            {{-- Kolom kiri: judul + tombol --}}
            <div>
                <div style="margin-bottom:1.25rem;">
                    <label style="display:block;font-weight:600;margin-bottom:.4rem;">Judul Poll</label>
                    <input type="text" name="title" value="{{ old('title', 'Dungeon Favorit Minggu Ini') }}"
                        class="pw-adm-input" style="width:100%;"
                        placeholder="Contoh: Dungeon Favorit Minggu Ini" required>
                    @error('title')
                        <div style="color:#e05252;font-size:.8rem;margin-top:.3rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="display:flex;gap:.6rem;">
                    <button type="submit" class="pw-adm-btn pw-adm-btn--gold">Buat Poll</button>
                    <a href="{{ route('admin.dungeon-vote.index') }}" class="pw-adm-btn pw-adm-btn--ghost">Batal</a>
                </div>
            </div>

            {{-- Kolom kanan: pilih dungeon --}}
            <div>
                <label style="display:block;font-weight:600;margin-bottom:.4rem;">
                    Pilih Dungeon
                    <span style="font-weight:400;color:var(--pw-text-muted);font-size:.82rem;">(minimal 2)</span>
                </label>

                @error('map_ids')
                    <div style="color:#e05252;font-size:.8rem;margin-bottom:.5rem;">{{ $message }}</div>
                @enderror

                <div style="margin-bottom:.7rem;">
                    <input type="text" id="mapSearch" placeholder="Cari nama dungeon..."
                        class="pw-adm-input" style="width:100%;"
                        oninput="filterMaps(this.value)">
                </div>

                <div id="mapGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(190px,1fr));gap:.4rem;max-height:65vh;overflow-y:auto;padding:.3rem;">
                    @foreach($availableMaps as $mapId => $mapName)
                    <label class="map-option" data-name="{{ strtolower($mapName) }}"
                        style="display:flex;align-items:center;gap:.5rem;padding:.5rem .7rem;border:1px solid var(--pw-border);border-radius:.4rem;cursor:pointer;transition:border-color .2s;">
                        <input type="checkbox" name="map_ids[]" value="{{ $mapId }}"
                            {{ in_array($mapId, old('map_ids', [])) ? 'checked' : '' }}
                            style="accent-color:var(--pw-gold);width:15px;height:15px;">
                        <span style="font-size:.83rem;line-height:1.3;">
                            <span style="color:var(--pw-text-muted);font-size:.72rem;">{{ $mapId }}</span><br>
                            {{ $mapName }}
                        </span>
                    </label>
                    @endforeach
                </div>
                <div style="font-size:.78rem;color:var(--pw-text-muted);margin-top:.4rem;" id="selectedCount">
                    0 dungeon dipilih
                </div>
            </div>

        </div>
    </form>
</div>

<script>
function filterMaps(q) {
    q = q.toLowerCase().trim();
    document.querySelectorAll('.map-option').forEach(el => {
        el.style.display = (!q || el.dataset.name.includes(q)) ? '' : 'none';
    });
}

function updateCount() {
    const n = document.querySelectorAll('input[name="map_ids[]"]:checked').length;
    document.getElementById('selectedCount').textContent = n + ' dungeon dipilih';
}

document.querySelectorAll('input[name="map_ids[]"]').forEach(cb => {
    cb.addEventListener('change', updateCount);
    // Highlight label saat checked
    cb.addEventListener('change', function() {
        this.closest('label').style.borderColor = this.checked ? 'var(--pw-gold)' : 'var(--pw-border)';
    });
    if (cb.checked) cb.closest('label').style.borderColor = 'var(--pw-gold)';
});
updateCount();
</script>
@endsection
