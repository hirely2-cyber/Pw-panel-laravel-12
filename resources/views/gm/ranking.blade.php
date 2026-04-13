@extends('layouts.gm')
@section('title', 'Ranking')

@section('content')

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

@endsection
