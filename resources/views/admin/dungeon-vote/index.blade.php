@extends('layouts.admin')
@section('title', 'Dungeon Voting')

@section('content')

<div class="pw-adm-card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem;flex-wrap:wrap;gap:.6rem;">
        <div class="pw-adm-card__title" style="margin:0;border:none;padding:0;">Dungeon Voting</div>
        <a href="{{ route('admin.dungeon-vote.create') }}" class="pw-adm-btn">+ Buat Poll Baru</a>
    </div>

    @if(session('success'))
        <div class="pw-adm-alert pw-adm-alert--success" style="margin-bottom:1rem;">{{ session('success') }}</div>
    @endif

    @forelse($polls as $poll)

    {{-- Poll row --}}
    <div style="border:1px solid var(--pw-border);border-radius:8px;padding:1rem 1.25rem;margin-bottom:1rem;">

        {{-- Header row --}}
        <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:.75rem;margin-bottom:.75rem;">
            <div>
                <div style="font-weight:700;font-size:.97rem;margin-bottom:.25rem;">{{ $poll->title }}</div>
                <div style="font-size:.75rem;color:var(--pw-text-muted);">
                    Dibuat {{ $poll->created_at->diffForHumans() }}
                    @if($poll->closed_at)
                        · Ditutup {{ $poll->closed_at->format('d M Y H:i') }}
                    @endif
                </div>
                <div style="margin-top:.45rem;display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;">
                    @if($poll->is_active)
                        <span class="pw-badge pw-badge--success">Aktif</span>
                    @elseif($poll->closed_at)
                        <span class="pw-badge pw-badge--warning">Selesai</span>
                    @else
                        <span class="pw-badge">Draft</span>
                    @endif
                    <span style="font-size:.78rem;color:var(--pw-text-muted);">
                        {{ $poll->options_count }} dungeon
                        · {{ $poll->options->sum('votes') }} total vote
                    </span>
                </div>
            </div>

            {{-- Action buttons --}}
            <div style="display:flex;gap:.4rem;flex-wrap:wrap;">
                @if(!$poll->is_active)
                    <form action="{{ route('admin.dungeon-vote.activate', $poll) }}" method="POST">
                        @csrf @method('PATCH')
                        <button class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--gold">Aktifkan</button>
                    </form>
                @else
                    <form action="{{ route('admin.dungeon-vote.deactivate', $poll) }}" method="POST">
                        @csrf @method('PATCH')
                        <button class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--ghost">Tutup</button>
                    </form>
                @endif
                <form action="{{ route('admin.dungeon-vote.reset', $poll) }}" method="POST"
                      data-confirm="Reset Vote|Yakin reset semua vote di poll ini? Tindakan tidak bisa dibatalkan.">
                    @csrf @method('PATCH')
                    <button class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--ghost" style="color:#e09a2a;">Reset Vote</button>
                </form>
                <form action="{{ route('admin.dungeon-vote.destroy', $poll) }}" method="POST"
                      data-confirm="Hapus Poll|Yakin ingin menghapus poll ini beserta semua data vote-nya?">
                    @csrf @method('DELETE')
                    <button class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--ghost" style="color:#e05252;">Hapus</button>
                </form>
            </div>
        </div>

        {{-- Dungeon vote bars --}}
        @if($poll->options->isNotEmpty())
        @php
            $maxVotes   = $poll->options->max('votes');
            $totalVotes = $poll->options->sum('votes');
        @endphp
        <div style="display:flex;flex-direction:column;gap:.4rem;">
            @foreach($poll->options->sortByDesc('votes') as $opt)
            @php
                $pct      = $maxVotes > 0 ? round($opt->votes / $maxVotes * 100) : 0;
                $isWinner = $maxVotes > 0 && $opt->votes === $maxVotes && $opt->votes > 0;
            @endphp
            <div style="display:flex;align-items:center;gap:.6rem;font-size:.82rem;">
                <div style="flex:1;height:24px;border-radius:4px;overflow:hidden;border:1px solid var(--pw-border);position:relative;">
                    <div style="width:{{ $pct }}%;height:100%;background:{{ $isWinner ? 'rgba(200,151,42,.3)' : 'rgba(200,151,42,.1)' }};"></div>
                    <span style="position:absolute;left:.6rem;top:50%;transform:translateY(-50%);white-space:nowrap;overflow:hidden;font-weight:{{ $isWinner ? '700' : '400' }};color:{{ $isWinner ? 'var(--pw-gold)' : 'inherit' }};">
                        {{ $isWinner ? '🏆 ' : '' }}{{ $opt->map_name }}
                    </span>
                </div>
                <div style="min-width:80px;text-align:right;font-weight:700;color:var(--pw-gold);white-space:nowrap;">
                    {{ number_format($opt->votes) }}
                    <span style="font-weight:400;color:var(--pw-text-muted);font-size:.75rem;">
                        ({{ $totalVotes > 0 ? round($opt->votes / $totalVotes * 100) : 0 }}%)
                    </span>
                </div>
            </div>
            @endforeach
        </div>
        @endif

    </div>

    @empty
    <div style="text-align:center;color:var(--pw-text-muted);padding:2.5rem 1rem;">
        Belum ada poll voting. Buat poll baru untuk mulai.
    </div>
    @endforelse

    {{ $polls->links() }}
</div>

@endsection
