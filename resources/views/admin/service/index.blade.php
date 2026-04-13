@extends('layouts.admin')
@section('title', 'Kelola Layanan')

@section('content')
<div class="pw-adm-card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.2rem;flex-wrap:wrap;gap:.6rem;">
        <div style="color:var(--pw-text-muted);font-size:.83rem;">Total: {{ $services->total() }} layanan</div>
        <a href="{{ route('admin.service.create') }}" class="pw-adm-btn">+ Tambah Layanan</a>
    </div>

    <div class="pw-table-wrap">
        <table class="pw-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Harga (Gold)</th>
                    <th>Urutan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($services as $service)
                <tr>
                    <td>
                        <strong>{{ $service->name }}</strong>
                        @if($service->description)
                        <div style="font-size:.72rem;color:var(--pw-text-muted);">{{ Str::limit($service->description, 60) }}</div>
                        @endif
                    </td>
                    <td><span class="pw-badge">{{ $service->type }}</span></td>
                    <td><strong style="color:#b89d4f;">{{ number_format($service->price) }}</strong></td>
                    <td style="color:var(--pw-text-muted);">{{ $service->sort_order ?? 0 }}</td>
                    <td>
                        @if($service->is_active)
                            <span class="pw-badge pw-badge--success">Aktif</span>
                        @else
                            <span class="pw-badge pw-badge--danger">Nonaktif</span>
                        @endif
                    </td>
                    <td style="display:flex;gap:.3rem;">
                        <a href="{{ route('admin.service.edit', $service->id) }}" class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--ghost">Edit</a>
                        <form action="{{ route('admin.service.destroy', $service->id) }}" method="POST"
                              data-confirm="Hapus Layanan|Yakin ingin menghapus layanan ini?">
                            @csrf @method('DELETE')
                            <button type="submit" class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--ghost" style="color:#e05252;">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;color:var(--pw-text-muted);">Belum ada layanan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:1rem;">{{ $services->links() }}</div>
</div>
@endsection
