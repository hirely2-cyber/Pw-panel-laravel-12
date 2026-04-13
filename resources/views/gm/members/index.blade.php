@extends('layouts.gm')
@section('title', 'Cari Member')

@section('content')
<div class="pw-adm-card">
    <form method="GET" style="display:flex;gap:.6rem;margin-bottom:1.2rem;">
        <input type="text" name="search" class="pw-form__input" style="max-width:280px;"
               placeholder="Cari nama pemain…" value="{{ request('search') }}">
        <button type="submit" class="pw-adm-btn">Cari</button>
        @if(request('search'))
            <a href="{{ route('gm.members.index') }}" class="pw-adm-btn pw-adm-btn--ghost">Reset</a>
        @endif
    </form>

    <div class="pw-table-wrap">
        <table class="pw-table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Gold Points</th>
                    <th>Diundang Oleh</th>
                    <th>Bergabung</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $m)
                <tr>
                    <td><strong>{{ $m->name }}</strong></td>
                    <td style="color:var(--pw-text-muted);">{{ $m->email }}</td>
                    <td>
                        @if($m->role === 'admin') <span class="pw-badge pw-badge--danger">Admin</span>
                        @elseif($m->role === 'gm') <span class="pw-badge pw-badge--warning">GM</span>
                        @else <span class="pw-badge">Player</span> @endif
                    </td>
                    <td>{{ number_format($m->money) }}</td>
                    <td style="font-size:.78rem;">
                        @if($m->referrer)
                            <span style="color:var(--pw-gold);">{{ $m->referrer->name }}</span>
                        @else
                            <span style="color:var(--pw-text-muted);">—</span>
                        @endif
                    </td>
                    <td style="font-size:.78rem;color:var(--pw-text-muted);">{{ $m->created_at?->format('d M Y') ?? '-' }}</td>
                    <td>
                        @if(auth()->user()->isAdministrator())
                        <a href="{{ route('gm.members.show', $m->ID) }}" class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--ghost">Lihat</a>
                        @else
                        <span style="color:var(--pw-text-muted);font-size:.75rem;">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;color:var(--pw-text-muted);">
                        {{ request('search') ? 'Pemain tidak ditemukan.' : 'Tidak ada data.' }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:1rem;">{{ $members->withQueryString()->links() }}</div>
</div>
@endsection
