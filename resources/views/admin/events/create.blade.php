@extends('layouts.admin')
@section('title', 'Buat Event Baru')

@section('content')
<div style="margin-bottom:1rem;">
    <a href="{{ route('admin.events.index') }}" class="pw-adm-btn pw-adm-btn--ghost pw-adm-btn--sm">← Kembali</a>
</div>

<div class="pw-adm-card">
    <div class="pw-adm-card__title">Buat Event Baru</div>

    <form method="POST" action="{{ route('admin.events.store') }}">
        @csrf

        <div style="margin-bottom:1rem;">
            <label class="pw-adm-label">Nama Event (ID)</label>
            <input type="text" name="title" class="pw-adm-input" value="{{ old('title', 'Grand Launching Event') }}" required>
            @error('title') <div style="color:#ef4444;font-size:.78rem;margin-top:.3rem;">{{ $message }}</div> @enderror
        </div>

        <div style="margin-bottom:1rem;">
            <label class="pw-adm-label">Nama Event (EN)</label>
            <input type="text" name="title_en" class="pw-adm-input" value="{{ old('title_en', 'Grand Launching Event') }}" placeholder="English title">
            @error('title_en') <div style="color:#ef4444;font-size:.78rem;margin-top:.3rem;">{{ $message }}</div> @enderror
        </div>

        <div style="margin-bottom:1rem;">
            <label class="pw-adm-label">Deskripsi (ID)</label>
            <textarea name="description" class="pw-adm-input" rows="3" style="resize:vertical;">{{ old('description', 'Jadilah yang tercepat mencapai target level & cultivation! 50 pemain tercepat akan mendapatkan hadiah Cubi Gold.') }}</textarea>
        </div>

        <div style="margin-bottom:1rem;">
            <label class="pw-adm-label">Deskripsi (EN)</label>
            <textarea name="description_en" class="pw-adm-input" rows="3" style="resize:vertical;" placeholder="English description">{{ old('description_en', 'Be the fastest to reach the target level & cultivation! The 50 fastest players will receive Cubi Gold prizes.') }}</textarea>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
            <div>
                <label class="pw-adm-label">Syarat Level</label>
                <input type="number" name="req_level" class="pw-adm-input" value="{{ old('req_level', 105) }}" min="1" max="150" required>
            </div>
            <div>
                <label class="pw-adm-label">Syarat Cultivation</label>
                <select name="req_cultivation" class="pw-adm-input">
                    @foreach(\App\Models\LaunchEvent::CULTIVATION_OPTIONS as $val => $label)
                    <option value="{{ $val }}" {{ old('req_cultivation', 22) == $val ? 'selected' : '' }}>{{ $val }} — {{ $label }}</option>
                    @endforeach
                </select>
                <div style="font-size:.72rem;color:var(--pw-text-muted);margin-top:.3rem;">
                    Light & Dark path otomatis diterima di tier yang sama
                </div>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
            <div>
                <label class="pw-adm-label">Total Hadiah (Cubi Gold)</label>
                <input type="number" name="prize_total_cubi" class="pw-adm-input" value="{{ old('prize_total_cubi', 20000) }}" min="1" required>
                <div style="font-size:.72rem;color:var(--pw-text-muted);margin-top:.3rem;">
                    20.000 Cubi = Rp 20.000.000
                </div>
            </div>
            <div>
                <label class="pw-adm-label">Jumlah Pemenang</label>
                <input type="number" name="prize_winner_count" class="pw-adm-input" value="{{ old('prize_winner_count', 100) }}" min="4" required>
            </div>
        </div>

        <div style="background:rgba(200,151,42,.06);border:1px solid rgba(200,151,42,.15);border-radius:8px;padding:1rem;margin-bottom:1rem;">
            <div style="font-size:.85rem;font-weight:700;color:#c8972a;margin-bottom:.8rem;">Hadiah Bertingkat (Cubi Gold)</div>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:.8rem;margin-bottom:.8rem;">
                <div>
                    <label class="pw-adm-label">🥇 Juara 1</label>
                    <input type="number" name="prize_rank1" class="pw-adm-input" value="{{ old('prize_rank1', 2000) }}" min="0">
                </div>
                <div>
                    <label class="pw-adm-label">🥈 Juara 2</label>
                    <input type="number" name="prize_rank2" class="pw-adm-input" value="{{ old('prize_rank2', 1500) }}" min="0">
                </div>
                <div>
                    <label class="pw-adm-label">🥉 Juara 3</label>
                    <input type="number" name="prize_rank3" class="pw-adm-input" value="{{ old('prize_rank3', 1000) }}" min="0">
                </div>
            </div>
            <div style="font-size:.78rem;color:var(--pw-text-muted);" x-data x-init="
                const fields = ['prize_total_cubi','prize_winner_count','prize_rank1','prize_rank2','prize_rank3'];
                const els = Object.fromEntries(fields.map(f => [f, document.querySelector('[name='+f+']')]));
                const upd = () => {
                    const total = parseInt(els.prize_total_cubi?.value||0);
                    const winners = parseInt(els.prize_winner_count?.value||0);
                    const r1 = parseInt(els.prize_rank1?.value||0);
                    const r2 = parseInt(els.prize_rank2?.value||0);
                    const r3 = parseInt(els.prize_rank3?.value||0);
                    const sisa = total - r1 - r2 - r3;
                    const restCount = Math.max(1, winners - 3);
                    const perPerson = sisa > 0 ? Math.floor(sisa / restCount) : 0;
                    $el.innerHTML = 'Sisa: <strong>' + sisa.toLocaleString() + ' Cubi</strong> &divide; ' + restCount + ' orang = <strong>' + perPerson.toLocaleString() + ' Cubi/orang</strong> (rank 4 dst)';
                };
                fields.forEach(f => els[f]?.addEventListener('input', upd));
                upd();
            ">—</div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem;">
            <div>
                <label class="pw-adm-label">Tanggal Mulai</label>
                <input type="datetime-local" name="start_at" class="pw-adm-input" value="{{ old('start_at') }}" required>
            </div>
            <div>
                <label class="pw-adm-label">Tanggal Berakhir</label>
                <input type="datetime-local" name="end_at" class="pw-adm-input" value="{{ old('end_at') }}" required>
            </div>
        </div>

        <button type="submit" class="pw-adm-btn" style="width:100%;">Simpan Event</button>
    </form>
</div>
@endsection
