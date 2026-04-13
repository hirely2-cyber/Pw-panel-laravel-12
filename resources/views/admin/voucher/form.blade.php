@extends('layouts.admin')
@section('title', $voucher->exists ? 'Edit Voucher' : 'Buat Voucher')

@section('content')
<div style="max-width:440px;">
    <div style="margin-bottom:1rem;">
        <a href="{{ route('admin.voucher.index') }}" class="pw-adm-btn pw-adm-btn--ghost pw-adm-btn--sm">← Kembali</a>
    </div>

    <div class="pw-adm-card">
        <div class="pw-adm-card__title">{{ $voucher->exists ? 'Edit' : 'Buat' }} Voucher</div>

        <form action="{{ $voucher->exists ? route('admin.voucher.update', $voucher->id) : route('admin.voucher.store') }}"
              method="POST">
            @csrf
            @if($voucher->exists) @method('PUT') @endif

            @if($voucher->exists)
            <label class="pw-form__label">Kode</label>
            <input type="text" class="pw-form__input" value="{{ $voucher->code }}" disabled
                   style="margin-bottom:.8rem;opacity:.6;cursor:not-allowed;">
            @endif

                 <label class="pw-form__label">Deskripsi / Label</label>
                 <input type="text" name="description" class="pw-form__input"
                     value="{{ old('description', $voucher->description) }}" placeholder="Event Hari Kemerdekaan"
                   style="margin-bottom:.8rem;">
                 @error('description') <p style="color:#e05252;font-size:.75rem;margin-top:-.6rem;margin-bottom:.6rem;">{{ $message }}</p> @enderror

                 <label class="pw-form__label">Tipe Reward <span style="color:#e05252;">*</span></label>
                 <select name="type" class="pw-form__input" required style="margin-bottom:.8rem;">
                  <option value="gold_points" {{ old('type', $voucher->normalized_type) === 'gold_points' ? 'selected' : '' }}>Gold Points</option>
                  <option value="cubi" {{ old('type', $voucher->normalized_type) === 'cubi' ? 'selected' : '' }}>Cubi Gold</option>
                 </select>
                 @error('type') <p style="color:#e05252;font-size:.75rem;margin-top:-.6rem;margin-bottom:.6rem;">{{ $message }}</p> @enderror

                 <label class="pw-form__label">Nilai Reward <span style="color:#e05252;">*</span></label>
                 <input type="number" name="value" class="pw-form__input" required min="1"
                     value="{{ old('value', $voucher->value) }}"
                   style="margin-bottom:.8rem;">
                 @error('value') <p style="color:#e05252;font-size:.75rem;margin-top:-.6rem;margin-bottom:.6rem;">{{ $message }}</p> @enderror

                 <label class="pw-form__label">Maksimal Pemakaian</label>
                 <input type="number" name="max_uses" class="pw-form__input" min="1"
                     value="{{ old('max_uses', $voucher->max_uses) }}" placeholder="Kosong = tidak terbatas"
                     style="margin-bottom:.8rem;">
                 @error('max_uses') <p style="color:#e05252;font-size:.75rem;margin-top:-.6rem;margin-bottom:.6rem;">{{ $message }}</p> @enderror

                 <label class="pw-form__label">Expired At</label>
                 <input type="datetime-local" name="expires_at" class="pw-form__input"
                     value="{{ old('expires_at', $voucher->expires_at ? $voucher->expires_at->format('Y-m-d\TH:i') : '') }}"
                     style="margin-bottom:.8rem;">
                 @error('expires_at') <p style="color:#e05252;font-size:.75rem;margin-top:-.6rem;margin-bottom:.6rem;">{{ $message }}</p> @enderror

            @if($voucher->exists)
            <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;font-size:.85rem;margin-bottom:1rem;">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1"
                       {{ old('is_active', $voucher->is_active) ? 'checked' : '' }}>
                Aktifkan voucher
            </label>
            @endif

            <p style="font-size:.72rem;color:var(--pw-text-muted);margin-bottom:1rem;">
                @if(!$voucher->exists)
                    Kode 16 karakter akan digenerate otomatis.
                @endif
                Voucher bisa dibatasi kuota pemakaian dan tanggal kedaluwarsa.
            </p>

            <div style="display:flex;gap:.5rem;">
                <button type="submit" class="pw-adm-btn" style="flex:1;">
                    {{ $voucher->exists ? 'Simpan' : 'Buat Voucher' }}
                </button>
                <a href="{{ route('admin.voucher.index') }}" class="pw-adm-btn pw-adm-btn--ghost">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
