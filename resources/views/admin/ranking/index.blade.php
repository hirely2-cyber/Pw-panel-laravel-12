@extends('layouts.admin')
@section('title', 'Ranking')

@section('content')
<div style="display:flex;justify-content:flex-end;margin-bottom:1rem;">
    <form action="{{ route('admin.ranking.refresh') }}" method="POST">
        @csrf
        <button type="submit" class="pw-adm-btn pw-adm-btn--ghost">↺ Sync dari Game DB</button>
    </form>
</div>

@if(session('success'))
<div class="pw-adm-alert pw-adm-alert--success" style="margin-bottom:1rem;">{{ session('success') }}</div>
@endif

{{-- ROW 1: Player + Faction Ranking --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:1.5rem;">

    {{-- Player Ranking --}}
    <div class="pw-adm-card">
        <div class="pw-adm-card__title">Top 100 Pemain (by PK Kills)</div>
        <div class="pw-table-wrap" style="max-height:420px;overflow-y:auto;">
            <table class="pw-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th style="text-align:center;">Lv</th>
                        <th style="text-align:center;">PK Kills</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($players as $i => $p)
                    <tr>
                        <td style="font-weight:700;color:{{ $i < 3 ? '#b89d4f' : 'var(--pw-text-muted)' }};">{{ $i + 1 }}</td>
                        <td><strong>{{ $p->character_name ?? '—' }}</strong></td>
                        <td style="color:var(--pw-text-muted);font-size:.78rem;">{{ $p->class ?? '—' }}</td>
                        <td style="text-align:center;">{{ $p->level }}</td>
                        <td style="text-align:center;color:#f87171;font-weight:600;">{{ $p->pk_points ?? 0 }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align:center;color:var(--pw-text-muted);">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Faction Ranking --}}
    <div class="pw-adm-card">
        <div class="pw-adm-card__title">Ranking Guild (by Wilayah)</div>
        <div class="pw-table-wrap" style="max-height:420px;overflow-y:auto;">
            <table class="pw-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Guild</th>
                        <th>Pemimpin</th>
                        <th style="text-align:center;">Member</th>
                        <th style="text-align:center;">Wilayah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($factions as $i => $f)
                    <tr>
                        <td style="font-weight:700;color:{{ $i < 3 ? '#b89d4f' : 'var(--pw-text-muted)' }};">{{ $i + 1 }}</td>
                        <td><strong>{{ $f->name }}</strong></td>
                        <td style="color:var(--pw-text-muted);font-size:.78rem;">{{ $f->leader_name ?? '—' }}</td>
                        <td style="text-align:center;color:#7ec8c8;">{{ $f->members_count }}</td>
                        <td style="text-align:center;color:#b89d4f;font-weight:600;">{{ $f->territory_count }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align:center;color:var(--pw-text-muted);">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ROW 2: Faction Name Editor --}}
<div class="pw-adm-card">
    <div class="pw-adm-card__title">Edit Nama Guild/Faction</div>
    <p style="font-size:.78rem;color:var(--pw-text-muted);margin-bottom:1rem;">
        Nama guild tidak tersimpan di DB game (dikelola daemon <code>uniquenamed</code>). Input nama manual di sini berdasarkan ID faction. Sorted by jumlah member.
    </p>
    <div class="pw-table-wrap">
        <table class="pw-table">
            <thead>
                <tr>
                    <th>Faction ID</th>
                    <th>Nama Tersimpan</th>
                    <th>Pemimpin (dari DB)</th>
                    <th style="text-align:center;">Member</th>
                    <th style="text-align:center;">Wilayah</th>
                    <th>Input Nama</th>
                </tr>
            </thead>
            <tbody>
                @foreach($gameFactions as $gf)
                <tr>
                    <td style="color:var(--pw-text-muted);font-size:.78rem;">#{{ $gf->faction_id }}</td>
                    <td style="color:{{ $savedNames->has($gf->faction_id) ? '#4ade80' : 'var(--pw-text-muted)' }};font-size:.78rem;">
                        {{ $savedNames->get($gf->faction_id) ?? '—' }}
                    </td>
                    <td style="font-size:.78rem;color:var(--pw-text-muted);">{{ $gf->leader_name ?? '—' }}</td>
                    <td style="text-align:center;font-size:.78rem;">{{ $gf->members_count }}</td>
                    <td style="text-align:center;font-size:.78rem;color:#b89d4f;font-weight:600;">{{ $gf->territory_count }}</td>
                    <td>
                        <form action="{{ route('admin.ranking.faction.name') }}" method="POST" style="display:flex;gap:.4rem;">
                            @csrf
                            <input type="hidden" name="faction_id" value="{{ $gf->faction_id }}">
                            <input type="text" name="name" class="pw-adm-input" style="font-size:.75rem;padding:.25rem .5rem;width:130px;"
                                placeholder="Nama guild" value="{{ $savedNames->get($gf->faction_id, '') }}">
                            <button type="submit" class="pw-adm-btn pw-adm-btn--xs pw-adm-btn--gold">Simpan</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
