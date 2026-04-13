@extends('layouts.admin')
@section('title', 'Kelola Voucher')

@section('content')
{{-- Bulk Generate Panel --}}
<div class="pw-adm-card" style="margin-bottom:1.5rem;">
    <div class="pw-adm-card__title">Generate Voucher Massal</div>
    <form action="{{ route('admin.voucher.generate') }}" method="POST"
          style="display:grid;grid-template-columns:repeat(12,minmax(0,1fr));gap:.8rem;align-items:end;">
        @csrf
        <div style="grid-column:span 3;">
            <label class="pw-form__label">Deskripsi Batch</label>
            <input type="text" name="description" class="pw-form__input" placeholder="Event Natal 2025" style="width:100%;" value="{{ old('description') }}">
        </div>
        <div style="grid-column:span 1;">
            <label class="pw-form__label">Jumlah</label>
            <input type="number" name="count" class="pw-form__input" required min="1" max="500" value="{{ old('count', 10) }}" style="width:100%;">
        </div>
        <div style="grid-column:span 2;">
            <label class="pw-form__label">Tipe Reward</label>
            <select name="type" class="pw-form__input" required style="width:100%;">
                <option value="gold_points" {{ old('type') === 'gold_points' ? 'selected' : '' }}>Gold Points</option>
                <option value="cubi" {{ old('type') === 'cubi' ? 'selected' : '' }}>Cubi Gold</option>
            </select>
        </div>
        <div style="grid-column:span 1;">
            <label class="pw-form__label">Nilai Reward</label>
            <input type="number" name="value" class="pw-form__input" required min="1" value="{{ old('value', 100) }}" style="width:100%;">
        </div>
        <div style="grid-column:span 2;">
            <label class="pw-form__label">Kuota per Voucher</label>
            <input type="number" name="max_uses" class="pw-form__input" min="1" placeholder="Kosong = tak terbatas" value="{{ old('max_uses') }}" style="width:100%;">
        </div>
        <div style="grid-column:span 2;">
            <label class="pw-form__label">Expired At</label>
            <input type="datetime-local" name="expires_at" class="pw-form__input" value="{{ old('expires_at') }}" style="width:100%;">
        </div>
        <div style="grid-column:span 1;display:flex;align-items:end;">
            <button type="submit" class="pw-adm-btn" style="width:100%;">Generate</button>
        </div>
    </form>
</div>

<div class="pw-adm-card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.2rem;flex-wrap:wrap;gap:.6rem;">
        <div style="color:var(--pw-text-muted);font-size:.83rem;">Total: {{ $vouchers->total() }} voucher</div>
        <a href="{{ route('admin.voucher.create') }}" class="pw-adm-btn">+ Buat Satu</a>
    </div>

    <div class="pw-table-wrap">
        <table class="pw-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Deskripsi</th>
                    <th>Tipe</th>
                    <th>Reward</th>
                    <th>Terpakai</th>
                    <th>Tanggal Digunakan</th>
                    <th>Expired</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vouchers as $v)
                <tr>
                    <td><code style="font-size:.78rem;letter-spacing:.08em;">{{ $v->code }}</code></td>
                    <td style="color:var(--pw-text-muted);">{{ $v->description ?: '—' }}</td>
                    <td>
                        <span class="pw-badge {{ $v->normalized_type === 'cubi' ? 'pw-badge--danger' : 'pw-badge--success' }}">
                            {{ $v->reward_type_label }}
                        </span>
                    </td>
                    <td><strong style="color:#b89d4f;">{{ number_format($v->value) }}</strong></td>
                    <td style="color:var(--pw-text-muted);font-size:.78rem;">
                        {{ number_format($v->used_count) }} / {{ $v->max_uses ? number_format($v->max_uses) : '∞' }}
                    </td>
                    <td style="color:var(--pw-text-muted);font-size:.75rem;">
                        {{ $v->logs_max_created_at ? \Carbon\Carbon::parse($v->logs_max_created_at)->format('d M Y H:i') : 'Belum dipakai' }}
                    </td>
                    <td style="color:var(--pw-text-muted);font-size:.75rem;">{{ $v->expires_at ? $v->expires_at->format('d M Y H:i') : 'Tidak ada' }}</td>
                    <td>
                        @if(!$v->is_active)
                            <span class="pw-badge">Nonaktif</span>
                        @elseif($v->expires_at && $v->expires_at->isPast())
                            <span class="pw-badge pw-badge--danger">Expired</span>
                        @elseif($v->max_uses !== null && $v->used_count >= $v->max_uses)
                            <span class="pw-badge pw-badge--danger">Kuota Habis</span>
                        @else
                            <span class="pw-badge pw-badge--success">Aktif</span>
                        @endif
                    </td>
                    <td style="display:flex;gap:.3rem;">
                        <a href="{{ route('admin.voucher.edit', $v->id) }}" class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--ghost">Edit</a>
                        <form action="{{ route('admin.voucher.destroy', $v->id) }}" method="POST"
                              data-confirm="Hapus Voucher|Yakin ingin menghapus voucher ini?">
                            @csrf @method('DELETE')
                            <button type="submit" class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--ghost" style="color:#e05252;">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" style="text-align:center;color:var(--pw-text-muted);">Belum ada voucher.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:1rem;">{{ $vouchers->links() }}</div>
</div>

<div class="pw-adm-card" style="margin-top:1.5rem;">
    <div class="pw-adm-card__title">Riwayat Pemakaian Voucher</div>
    <div class="pw-table-wrap">
        <table class="pw-table">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Nama User</th>
                    <th>Username (Sensor)</th>
                    <th>Kode Voucher</th>
                    <th>Tipe</th>
                    <th>Nilai</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentLogs as $log)
                <tr>
                    <td style="color:var(--pw-text-muted);font-size:.75rem;">{{ optional($log->created_at)->format('d M Y H:i') }}</td>
                    <td>{{ $log->user->truename ?? 'Tanpa Nama' }}</td>
                    <td>{{ $log->user->name ?? ('UID' . $log->user_id) }}</td>
                    <td><code style="font-size:.78rem;letter-spacing:.08em;">{{ $log->voucher->code ?? '—' }}</code></td>
                    <td>{{ $log->voucher?->reward_type_label ?? '—' }}</td>
                    <td style="color:#b89d4f;font-weight:600;">{{ number_format($log->value_received) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;color:var(--pw-text-muted);">Belum ada pemakaian voucher.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">{{ $recentLogs->appends(request()->except('usage_page'))->links() }}</div>
</div>
@endsection
