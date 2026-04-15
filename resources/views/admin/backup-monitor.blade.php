@extends('layouts.admin')

@section('title', 'PW Backup Monitor')

@section('content')
<div class="pw-adm-card" style="padding:1rem 1.2rem;margin-bottom:1rem;">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;">
        <div>
            <div style="font-size:.82rem;font-weight:700;letter-spacing:.06em;color:var(--pw-text-light);margin-bottom:.2rem;">Backup Files</div>
            <div style="font-size:.75rem;color:var(--pw-text-muted);">Path server: <span style="font-family:monospace;">{{ $serverPath }}</span></div>
        </div>
        <a href="{{ route('admin.server-control') }}" class="pw-adm-btn pw-adm-btn--ghost">Kembali ke Server Control</a>
    </div>
</div>

<div class="pw-adm-card" style="padding:1rem 1.2rem;">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;margin-bottom:.8rem;">
        <div style="font-size:.82rem;font-weight:700;letter-spacing:.06em;color:var(--pw-text-light);">Daftar Backup PW</div>
        <div style="font-size:.74rem;color:var(--pw-text-muted);">Total: {{ $files->count() }} file</div>
    </div>

    <div style="overflow:auto;">
        <table style="width:100%;border-collapse:collapse;min-width:920px;">
            <thead>
                <tr>
                    <th style="text-align:left;padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.72rem;color:var(--pw-text-muted);">File</th>
                    <th style="text-align:right;padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.72rem;color:var(--pw-text-muted);">Ukuran</th>
                    <th style="text-align:left;padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.72rem;color:var(--pw-text-muted);">Tanggal</th>
                    <th style="text-align:left;padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.72rem;color:var(--pw-text-muted);">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($files as $row)
                    <tr>
                        <td style="padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.78rem;font-family:monospace;">{{ $row['name'] }}</td>
                        <td style="padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.78rem;text-align:right;">{{ number_format((int) $row['size'] / 1048576, 2) }} MB</td>
                        <td style="padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.78rem;">{{ \Carbon\Carbon::createFromTimestamp((int) $row['mtime'])->format('d/m/Y H:i:s') }}</td>
                        <td style="padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.78rem;">
                            <div style="display:flex;gap:.45rem;align-items:center;flex-wrap:wrap;">
                                <form method="POST" action="{{ route('admin.backup-monitor.download') }}" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="file" value="{{ $row['name'] }}">
                                    <button type="submit" class="pw-adm-btn pw-adm-btn--sm">Download</button>
                                </form>
                                <form method="POST" action="{{ route('admin.backup-monitor.destroy') }}" style="display:inline;" onsubmit="return confirm('Hapus file backup ini?');">
                                    @csrf
                                    <input type="hidden" name="file" value="{{ $row['name'] }}">
                                    <button type="submit" class="pw-adm-btn pw-adm-btn--sm" style="background:#dc2626;color:#fff;">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding:1rem;border-bottom:1px solid var(--pw-border);font-size:.82rem;color:var(--pw-text-muted);text-align:center;">Belum ada file backup.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
