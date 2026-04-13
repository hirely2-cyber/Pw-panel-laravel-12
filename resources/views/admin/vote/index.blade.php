@extends('layouts.admin')
@section('title', 'Vote Sites')

@section('content')
<div class="pw-adm-card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.2rem;flex-wrap:wrap;gap:.6rem;">
        <div style="color:var(--pw-text-muted);font-size:.83rem;">{{ $sites->count() }} site terdaftar</div>
        <a href="{{ route('admin.vote.create') }}" class="pw-adm-btn">+ Tambah Site</a>
    </div>

    <div class="pw-table-wrap">
        <table class="pw-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>URL</th>
                    <th>Reward (Gold)</th>
                    <th>Urutan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sites as $site)
                <tr>
                    <td><strong>{{ $site->name }}</strong></td>
                    <td>
                        <a href="{{ $site->url }}" target="_blank" rel="noopener"
                           style="color:#b89d4f;font-size:.78rem;word-break:break-all;">{{ Str::limit($site->url, 50) }}</a>
                    </td>
                    <td><strong style="color:#b89d4f;">{{ number_format($site->reward) }}</strong></td>
                    <td style="color:var(--pw-text-muted);">{{ $site->sort_order ?? 0 }}</td>
                    <td>
                        @if($site->is_active)
                            <span class="pw-badge pw-badge--success">Aktif</span>
                        @else
                            <span class="pw-badge pw-badge--danger">Nonaktif</span>
                        @endif
                    </td>
                    <td style="display:flex;gap:.3rem;">
                        <a href="{{ route('admin.vote.edit', $site->id) }}" class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--ghost">Edit</a>
                        <form action="{{ route('admin.vote.destroy', $site->id) }}" method="POST"
                              data-confirm="Hapus Site|Yakin ingin menghapus site ini?">
                            @csrf @method('DELETE')
                            <button type="submit" class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--ghost" style="color:#e05252;">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;color:var(--pw-text-muted);">Belum ada site vote.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
