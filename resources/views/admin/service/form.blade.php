@extends('layouts.admin')
@section('title', $service->exists ? 'Edit Layanan' : 'Tambah Layanan')

@section('content')
<div style="max-width:560px;">
    <div style="margin-bottom:1rem;">
        <a href="{{ route('admin.service.index') }}" class="pw-adm-btn pw-adm-btn--ghost pw-adm-btn--sm">← Kembali</a>
    </div>

    <div class="pw-adm-card">
        <div class="pw-adm-card__title">{{ $service->exists ? 'Edit' : 'Tambah' }} Layanan</div>

        <form action="{{ $service->exists ? route('admin.service.update', $service->id) : route('admin.service.store') }}"
              method="POST">
            @csrf
            @if($service->exists) @method('PUT') @endif

            <label class="pw-form__label">Nama Layanan <span style="color:#e05252;">*</span></label>
            <input type="text" name="name" class="pw-form__input" required
                   value="{{ old('name', $service->name) }}" placeholder="Ganti Nama Karakter, Pindah Guild, dll."
                   style="margin-bottom:.8rem;">
            @error('name') <p style="color:#e05252;font-size:.75rem;margin-top:-.6rem;margin-bottom:.6rem;">{{ $message }}</p> @enderror

            <label class="pw-form__label">Deskripsi</label>
            <textarea name="description" rows="3" class="pw-form__input" style="resize:vertical;height:auto;margin-bottom:.8rem;">{{ old('description', $service->description) }}</textarea>

            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:.8rem;margin-bottom:.8rem;">
                <div>
                    <label class="pw-form__label">Kategori <span style="color:#e05252;">*</span></label>
                    <input type="text" name="type" class="pw-form__input" required
                           value="{{ old('type', $service->type) }}" list="cat-list">
                    <datalist id="cat-list">
                        <option value="karakter">
                        <option value="custom">
                        <option value="bantuan">
                        <option value="broadcast">
                        <option value="general">
                    </datalist>
                </div>
                <div>
                    <label class="pw-form__label">Harga (Gold) <span style="color:#e05252;">*</span></label>
                    <input type="number" name="price" class="pw-form__input" required min="0"
                           value="{{ old('price', (int) $service->price) }}">
                    @error('price') <p style="color:#e05252;font-size:.72rem;margin-top:.2rem;">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="pw-form__label">Urutan</label>
                    <input type="number" name="sort_order" class="pw-form__input" min="0"
                           value="{{ old('sort_order', $service->sort_order ?? 0) }}">
                </div>
            </div>

            <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;font-size:.85rem;margin-bottom:1.2rem;">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1"
                       {{ old('is_active', $service->is_active ?? true) ? 'checked' : '' }}>
                Aktifkan layanan
            </label>

            <div style="display:flex;gap:.5rem;">
                <button type="submit" class="pw-adm-btn" style="flex:1;">
                    {{ $service->exists ? 'Simpan' : 'Tambahkan' }}
                </button>
                <a href="{{ route('admin.service.index') }}" class="pw-adm-btn pw-adm-btn--ghost">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
