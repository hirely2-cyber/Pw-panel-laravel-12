@extends('layouts.admin')
@section('title', 'Kelola Member')

@section('content')
<div class="pw-adm-card">
    {{-- Toolbar --}}
    <form method="GET" style="display:flex;gap:.6rem;flex-wrap:wrap;margin-bottom:1.2rem;">
        <input type="text" name="search" class="pw-form__input" style="max-width:220px;"
               placeholder="Cari nama / email…" value="{{ request('search') }}">
        <select name="role" class="pw-form__input" style="max-width:180px;">
            <option value="">Semua Role</option>
                <option value="player"       @selected(request('role')=='player')>Player</option>
                <option value="gm"            @selected(request('role')=='gm')>Game Master (Panel)</option>
                <option value="game_gm"       @selected(request('role')=='game_gm')>Game Master (Game)</option>
                <option value="admin"         @selected(request('role')=='admin')>Admin</option>
        </select>
        <button type="submit" class="pw-adm-btn">Cari</button>
        @if(request()->anyFilled(['search','role']))
            <a href="{{ route('admin.members.index') }}" class="pw-adm-btn pw-adm-btn--ghost">Reset</a>
        @endif
    </form>

    <div class="pw-table-wrap">
        <table class="pw-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role Panel</th>
                    <th>Role Game</th>
                    <th>Gold Points</th>
                    <th>Diundang Oleh</th>
                    <th>Bergabung</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $m)
                <tr>
                    <td style="color:var(--pw-text-muted);font-size:.78rem;">{{ $m->ID }}</td>
                    <td><strong>{{ $m->name }}</strong></td>
                    <td style="color:var(--pw-text-muted);">{{ $m->email }}</td>
                    <td>
                        @if($m->role === 'admin')
                            <span class="pw-badge pw-badge--danger">Admin</span>
                        @elseif($m->role === 'gm')
                            <span class="pw-badge pw-badge--warning">Game Master</span>
                        @else
                            <span class="pw-badge">Player</span>
                        @endif
                        @if(in_array($m->ID, $gameGmIds) && $m->role !== 'gm')
                            <span class="pw-badge pw-badge--warning" style="margin-left:.3rem;">Game Master</span>
                        @endif
                    </td>
                    <td>
                        @if(in_array($m->ID, $gameGmIds))
                            <span class="pw-badge pw-badge--warning">Game Master</span>
                        @else
                            <span style="color:var(--pw-text-muted);font-size:.75rem;">-</span>
                        @endif
                    </td>
                    <td>{{ number_format($m->money) }}</td>
                    <td>
                        @if($m->referrer)
                            <a href="{{ route('admin.members.show', $m->referrer->ID) }}"
                               style="font-size:.78rem;color:var(--pw-gold);text-decoration:none;">
                                {{ $m->referrer->name }}
                            </a>
                        @else
                            <span style="color:var(--pw-text-muted);font-size:.75rem;">—</span>
                        @endif
                    </td>
                    <td style="color:var(--pw-text-muted);font-size:.78rem;">{{ $m->creatime?->translatedFormat('d M Y') ?? '-' }}</td>
                    <td>
                        <a href="{{ route('admin.members.show', $m->ID) }}" class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--ghost">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center;color:var(--pw-text-muted);">Tidak ada data.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:1rem;">
        {{ $members->withQueryString()->onEachSide(1)->links() }}
    </div>
</div>
@endsection
