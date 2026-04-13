@extends('layouts.gm')
@section('title', 'Artikel / Berita')

@section('content')
<div class="pw-adm-card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.2rem;flex-wrap:wrap;gap:.6rem;">
        <div style="color:var(--pw-text-muted);font-size:.83rem;">
            {{ $articles->total() }} artikel — hanya artikelmu ditampilkan
        </div>
        <a href="{{ route('gm.articles.create') }}" class="pw-adm-btn">+ Tulis Artikel</a>
    </div>

    <div class="pw-table-wrap">
        <table class="pw-table">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $art)
                <tr>
                    <td>
                        <strong>{{ $art->title }}</strong>
                        <div style="font-size:.72rem;color:var(--pw-text-muted);">{{ Str::limit($art->body, 70) }}</div>
                    </td>
                    <td><span class="pw-badge">{{ $art->category }}</span></td>
                    <td>
                        @if($art->is_active)
                            <span class="pw-badge pw-badge--success">Aktif</span>
                        @else
                            <span class="pw-badge pw-badge--warning">Menunggu Persetujuan</span>
                        @endif
                    </td>
                    <td style="font-size:.78rem;color:var(--pw-text-muted);">{{ $art->published_at?->format('d M Y') }}</td>
                    <td style="display:flex;gap:.3rem;">
                        <a href="{{ route('gm.articles.edit', $art->id) }}" class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--ghost">Edit</a>
                        <form action="{{ route('gm.articles.destroy', $art->id) }}" method="POST"
                              data-confirm="Hapus Artikel|Yakin ingin menghapus artikel ini?">
                            @csrf @method('DELETE')
                            <button type="submit" class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--ghost" style="color:#e05252;">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center;color:var(--pw-text-muted);">Belum ada artikel.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:1rem;">{{ $articles->links() }}</div>
</div>

<div class="pw-adm-card" style="margin-top:1rem;font-size:.8rem;color:var(--pw-text-muted);">
    <strong style="color:var(--pw-text);">Info:</strong> Artikel yang kamu buat akan masuk ke review admin sebelum ditampilkan di website. Setelah disetujui, status akan berubah menjadi "Aktif".
</div>
@endsection
