@extends('layouts.admin')
@section('title', 'Kelola Berita')

@section('content')
<div class="pw-adm-card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.2rem;flex-wrap:wrap;gap:.6rem;">
        <div style="color:var(--pw-text-muted);font-size:.83rem;">Total: {{ $news->total() }} berita</div>
        <a href="{{ route('admin.news.create') }}" class="pw-adm-btn">+ Tambah Berita</a>
    </div>

    <div class="pw-table-wrap">
        <table class="pw-table">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Penulis</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($news as $item)
                <tr>
                    <td>
                        <strong>{{ $item->title }}</strong>
                        <div style="font-size:.72rem;color:var(--pw-text-muted);">{{ Str::limit($item->slug, 40) }}</div>
                    </td>
                    <td><span class="pw-badge">{{ $item->category }}</span></td>
                    <td style="color:var(--pw-text-muted);">{{ $item->author->truename ?: ($item->author->name ?? 'System') }}</td>
                    <td>
                        @if($item->is_published)
                            <span class="pw-badge pw-badge--success">Aktif</span>
                        @else
                            <span class="pw-badge pw-badge--danger">Nonaktif</span>
                        @endif
                    </td>
                    <td style="font-size:.78rem;color:var(--pw-text-muted);">{{ $item->created_at?->format('d M Y') }}</td>
                    <td style="display:flex;gap:.3rem;flex-wrap:wrap;">
                        <a href="{{ route('admin.news.edit', $item->id) }}" class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--ghost">Edit</a>
                        <form action="{{ route('admin.news.destroy', $item->id) }}" method="POST"
                              data-confirm="Hapus Berita|Yakin ingin menghapus berita ini?">
                            @csrf @method('DELETE')
                            <button type="submit" class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--ghost" style="color:#e05252;">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;color:var(--pw-text-muted);">Belum ada berita.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:1rem;">{{ $news->links() }}</div>
</div>
@endsection
