@extends('layouts.admin')
@section('title', $item->exists ? 'Edit Item Shop' : 'Tambah Item Shop')

@section('content')
<form action="{{ $item->exists ? route('admin.shop.update', $item->id) : route('admin.shop.store') }}"
      method="POST" enctype="multipart/form-data">
    @csrf
    @if($item->exists) @method('PUT') @endif

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;align-items:start;">

        {{-- Main Fields --}}
        <div class="pw-adm-card">
            <div class="pw-adm-card__title">Detail Item</div>

            <label class="pw-form__label">Nama Item <span style="color:#e05252;">*</span></label>
            <input type="text" name="name" class="pw-form__input" required
                   value="{{ old('name', $item->name) }}" style="margin-bottom:.8rem;">
            @error('name') <p style="color:#e05252;font-size:.75rem;margin-top:-.6rem;margin-bottom:.6rem;">{{ $message }}</p> @enderror

            <label class="pw-form__label">Deskripsi</label>
            <textarea name="description" rows="4" class="pw-form__input" style="resize:vertical;height:auto;">{{ old('description', $item->description) }}</textarea>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.8rem;margin-top:.8rem;">
                <div>
                    <label class="pw-form__label">Kategori <span style="color:#e05252;">*</span></label>
                    <input type="text" name="category" class="pw-form__input" required
                           value="{{ old('category', $item->category) }}" list="cat-list">
                    <datalist id="cat-list">
                        <option value="Weapon">
                        <option value="Armor">
                        <option value="Accessory">
                        <option value="Consumable">
                        <option value="Mount">
                        <option value="Pet">
                        <option value="Fashion">
                    </datalist>
                </div>
                <div>
                    <label class="pw-form__label">Harga (Gold Points) <span style="color:#e05252;">*</span></label>
                    <input type="number" name="price" class="pw-form__input" required min="1"
                           value="{{ old('price', $item->price) }}">
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.8rem;margin-top:.8rem;">
                <div>
                    <label class="pw-form__label">Item ID (Game)</label>
                    <input type="number" name="item_id" class="pw-form__input" min="0"
                           value="{{ old('item_id', $item->item_id ?? 0) }}">
                    <p style="font-size:.72rem;color:var(--pw-text-muted);margin-top:.3rem;">ID item dari game database (octet ID). Kosong / 0 = tanpa pengiriman item.</p>
                </div>
                <div>
                    <label class="pw-form__label">Jumlah Item</label>
                    <input type="number" name="item_count" class="pw-form__input" min="1"
                           value="{{ old('item_count', $item->item_count ?? 1) }}">
                    <p style="font-size:.72rem;color:var(--pw-text-muted);margin-top:.3rem;">Jumlah item yang dikirim per pembelian.</p>
                </div>
            </div>

            <div style="margin-top:.8rem;">
                <label class="pw-form__label">Urutan Tampil</label>
                <input type="number" name="sort_order" class="pw-form__input" min="0"
                       value="{{ old('sort_order', $item->sort_order ?? 0) }}" style="max-width:120px;">
                <p style="font-size:.72rem;color:var(--pw-text-muted);margin-top:.3rem;">Angka lebih kecil tampil lebih awal.</p>
            </div>
        </div>

        {{-- Side Panel --}}
        <div style="display:flex;flex-direction:column;gap:1rem;">
            <div class="pw-adm-card">
                <div class="pw-adm-card__title">Status & Simpan</div>
                <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;font-size:.85rem;margin-bottom:1rem;">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1"
                           {{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }}>
                    Aktifkan item
                </label>
                <div style="display:flex;gap:.5rem;">
                    <button type="submit" class="pw-adm-btn" style="flex:1;">
                        {{ $item->exists ? 'Simpan' : 'Tambahkan' }}
                    </button>
                    <a href="{{ route('admin.shop.index') }}" class="pw-adm-btn pw-adm-btn--ghost">Batal</a>
                </div>
            </div>

            <div class="pw-adm-card">
                <div class="pw-adm-card__title">Gambar Item</div>
                <div class="pw-img-upload" id="img-wrap">
                    <input type="file" name="image" accept="image/*" id="img-input">
                    @if($item->image)
                        <img src="{{ Storage::url($item->image) }}" class="pw-img-upload__preview" id="img-preview" alt="">
                        <div class="pw-img-upload__label">Klik untuk ganti</div>
                    @else
                        <img src="" class="pw-img-upload__preview" id="img-preview" alt="" style="display:none">
                        <svg class="pw-img-upload__icon" viewBox="0 0 40 40" fill="none"><rect x="4" y="8" width="32" height="24" rx="3" stroke="currentColor" stroke-width="1.8"/><path d="M4 26l8-8 6 6 5-5 9 9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <div class="pw-img-upload__label"><strong>Upload Gambar Item</strong></div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Panduan Admin --}}
    <div class="pw-adm-card" style="margin-top:1.5rem;">
        <div class="pw-adm-card__title">
            <svg viewBox="0 0 20 20" fill="none" width="16"><circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.4"/><path d="M10 9v5M10 6.5v.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            Panduan Pengisian Item Shop
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;font-size:.82rem;color:var(--pw-text-muted);line-height:1.7;">
            <div>
                <h4 style="color:var(--pw-gold);font-size:.85rem;font-weight:600;margin-bottom:.5rem;">Cara Kerja Pengiriman Item</h4>
                <ol style="padding-left:1.2rem;margin:0;">
                    <li>Player memilih <strong style="color:var(--pw-text)">karakter aktif</strong> di navbar sebelum membeli.</li>
                    <li>Setelah klik beli, <strong style="color:var(--pw-text)">Gold Points</strong> akan dipotong dari saldo player.</li>
                    <li>Item otomatis terkirim ke <strong style="color:var(--pw-text)">Kotak Pos (Mailbox)</strong> karakter di game.</li>
                    <li>Jika server game offline, status akan <strong style="color:#e8b84b">pending</strong> dan perlu dikirim ulang.</li>
                </ol>

                <h4 style="color:var(--pw-gold);font-size:.85rem;font-weight:600;margin-top:1rem;margin-bottom:.5rem;">Penjelasan Field</h4>
                <ul style="padding-left:1.2rem;margin:0;">
                    <li><strong style="color:var(--pw-text)">Item ID (Game)</strong> — ID item dari database game Perfect World. Bisa dilihat di tabel item game atau tools PW Database.</li>
                    <li><strong style="color:var(--pw-text)">Jumlah Item</strong> — Berapa banyak item yang diterima player per 1x pembelian.</li>
                    <li><strong style="color:var(--pw-text)">Item ID = 0</strong> — Item tidak dikirim ke game (hanya potongan Gold Points saja, misal untuk donasi/jasa).</li>
                </ul>
            </div>
            <div>
                <h4 style="color:var(--pw-gold);font-size:.85rem;font-weight:600;margin-bottom:.5rem;">Contoh Pengisian</h4>
                <div style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:.5rem;padding:.75rem 1rem;margin-bottom:.75rem;">
                    <div style="font-size:.75rem;color:var(--pw-gold);margin-bottom:.3rem;">Item Game (dikirim ke mailbox)</div>
                    <div>Nama: <strong style="color:var(--pw-text)">Teleport Stone ×50</strong></div>
                    <div>Item ID: <strong style="color:var(--pw-text)">21652</strong></div>
                    <div>Jumlah: <strong style="color:var(--pw-text)">50</strong></div>
                    <div>Harga: <strong style="color:var(--pw-text)">500 Gold Points</strong></div>
                </div>
                <div style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:.5rem;padding:.75rem 1rem;">
                    <div style="font-size:.75rem;color:var(--pw-gold);margin-bottom:.3rem;">Jasa / Non-Item (tanpa kiriman)</div>
                    <div>Nama: <strong style="color:var(--pw-text)">VIP Pass 30 Hari</strong></div>
                    <div>Item ID: <strong style="color:var(--pw-text)">0</strong></div>
                    <div>Jumlah: <strong style="color:var(--pw-text)">1</strong></div>
                    <div>Harga: <strong style="color:var(--pw-text)">5000 Gold Points</strong></div>
                </div>

                <h4 style="color:var(--pw-gold);font-size:.85rem;font-weight:600;margin-top:1rem;margin-bottom:.5rem;">Status Transaksi</h4>
                <ul style="padding-left:1.2rem;margin:0;">
                    <li><span style="color:#4ade80;">●</span> <strong style="color:var(--pw-text)">delivered</strong> — Item berhasil terkirim ke mailbox.</li>
                    <li><span style="color:#e8b84b;">●</span> <strong style="color:var(--pw-text)">pending</strong> — Server offline, item belum terkirim.</li>
                    <li><span style="color:#6ba3e8;">●</span> <strong style="color:var(--pw-text)">completed</strong> — Pembelian non-item (Item ID = 0).</li>
                </ul>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
document.getElementById('img-input').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = ev => {
        const img = document.getElementById('img-preview');
        img.src = ev.target.result;
        img.style.display = 'block';
    };
    reader.readAsDataURL(file);
});
</script>
@endpush
@endsection
