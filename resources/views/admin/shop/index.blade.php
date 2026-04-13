@extends('layouts.admin')
@section('title', 'Item Shop')

@section('content')
<div class="pw-adm-card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.2rem;flex-wrap:wrap;gap:.6rem;">
        <div style="color:var(--pw-text-muted);font-size:.83rem;">Total: {{ $items->total() }} item</div>
        <a href="{{ route('admin.shop.create') }}" class="pw-adm-btn">+ Tambah Item</a>
    </div>

    <div class="pw-table-wrap">
        <table class="pw-table">
            <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Harga (Gold)</th>
                    <th>Urutan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td>
                        @if($item->image)
                            <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}"
                                 style="width:42px;height:42px;object-fit:cover;border-radius:6px;border:1px solid var(--pw-border);">
                        @else
                            <div style="width:42px;height:42px;background:var(--pw-surface-2);border-radius:6px;display:flex;align-items:center;justify-content:center;color:var(--pw-text-muted);font-size:.6rem;">IMG</div>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $item->name }}</strong>
                        @if($item->description)
                        <div style="font-size:.72rem;color:var(--pw-text-muted);">{{ Str::limit($item->description, 50) }}</div>
                        @endif
                    </td>
                    <td><span class="pw-badge">{{ $item->category }}</span></td>
                    <td><strong style="color:#b89d4f;">{{ number_format($item->price) }}</strong></td>
                    <td style="color:var(--pw-text-muted);">{{ $item->sort_order ?? 0 }}</td>
                    <td>
                        <form action="{{ route('admin.shop.toggle', $item->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="pw-badge @if($item->is_active) pw-badge--success @else pw-badge--danger @endif"
                                    style="cursor:pointer;border:none;background:transparent;padding:2px 8px;">
                                {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </form>
                    </td>
                    <td style="display:flex;gap:.3rem;">
                        <a href="{{ route('admin.shop.edit', $item->id) }}" class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--ghost">Edit</a>
                        <form action="{{ route('admin.shop.destroy', $item->id) }}" method="POST"
                              data-confirm="Hapus Item|Yakin ingin menghapus item ini?">
                            @csrf @method('DELETE')
                            <button type="submit" class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--ghost" style="color:#e05252;">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;color:var(--pw-text-muted);">Belum ada item.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:1rem;">{{ $items->links() }}</div>
</div>
@endsection
