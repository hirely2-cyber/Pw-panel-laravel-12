@extends('layouts.gm')
@section('title', $article->exists ? 'Edit Artikel' : 'Tulis Artikel')

@section('content')
<form action="{{ $article->exists ? route('gm.articles.update', $article->id) : route('gm.articles.store') }}"
      method="POST" enctype="multipart/form-data">
    @csrf
    @if($article->exists) @method('PUT') @endif

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;align-items:start;">

        {{-- Content --}}
        <div class="pw-adm-card">
            <div class="pw-adm-card__title">{{ $article->exists ? 'Edit' : 'Tulis' }} Artikel</div>

            <label class="pw-form__label">Judul <span style="color:#e05252;">*</span></label>
            <input type="text" name="title" class="pw-form__input" required
                   value="{{ old('title', $article->title) }}" style="margin-bottom:.8rem;">
            @error('title') <p style="color:#e05252;font-size:.75rem;margin-top:-.6rem;margin-bottom:.6rem;">{{ $message }}</p> @enderror

            <label class="pw-form__label">Konten <span style="color:#e05252;">*</span></label>
            <textarea name="body" rows="16"
                      style="width:100%;background:var(--pw-bg-card,rgba(255,255,255,.04));border:1px solid var(--pw-border,rgba(255,255,255,.1));border-radius:6px;color:var(--pw-text,#e8dfc8);padding:.6rem .8rem;font-size:.85rem;font-family:inherit;resize:vertical;box-sizing:border-box;">{{ old('body', $article->body) }}</textarea>
            @error('body') <p style="color:#e05252;font-size:.75rem;margin-top:.3rem;">{{ $message }}</p> @enderror
        </div>

        {{-- Options --}}
        <div style="display:flex;flex-direction:column;gap:1rem;">
            <div class="pw-adm-card">
                <div class="pw-adm-card__title">Pengaturan</div>

                <label class="pw-form__label">Kategori <span style="color:#e05252;">*</span></label>
                <input type="text" name="category" class="pw-form__input"
                       value="{{ old('category', $article->category) }}" list="cat-list"
                       placeholder="Event, Update, Announcement…"
                       style="margin-bottom:.8rem;">
                <datalist id="cat-list">
                    <option value="Update">
                    <option value="Event">
                    <option value="Maintenance">
                    <option value="Announcement">
                </datalist>

                <label class="pw-form__label">Tanggal Publish</label>
                <input type="text" name="published_at" class="pw-form__input pw-datepicker"
                       value="{{ old('published_at', $article->published_at?->format('Y-m-d\TH:i')) }}"
                       style="margin-bottom:1rem;">

                <div style="background:#b89d4f11;border:1px solid #b89d4f33;border-radius:6px;padding:.6rem .8rem;font-size:.75rem;color:var(--pw-text-muted);margin-bottom:1rem;">
                    Artikel akan masuk review admin sebelum dipublikasikan.
                </div>

                <div style="display:flex;gap:.5rem;">
                    <button type="submit" class="pw-adm-btn" style="flex:1;">
                        {{ $article->exists ? 'Simpan' : 'Kirim untuk Review' }}
                    </button>
                    <a href="{{ route('gm.articles.index') }}" class="pw-adm-btn pw-adm-btn--ghost">Batal</a>
                </div>
            </div>

            <div class="pw-adm-card">
                <div class="pw-adm-card__title">Thumbnail</div>
                <div class="pw-img-upload">
                    <input type="file" name="thumbnail" accept="image/*" id="thumb-input">
                    @if($article->thumbnail)
                        <img src="{{ Storage::url($article->thumbnail) }}" class="pw-img-upload__preview" id="thumb-preview" alt="">
                        <div class="pw-img-upload__label">Klik untuk ganti</div>
                    @else
                        <img src="" class="pw-img-upload__preview" id="thumb-preview" alt="" style="display:none">
                        <svg class="pw-img-upload__icon" viewBox="0 0 40 40" fill="none"><rect x="4" y="8" width="32" height="24" rx="3" stroke="currentColor" stroke-width="1.8"/><path d="M4 26l8-8 6 6 5-5 9 9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <div class="pw-img-upload__label"><strong>Upload Thumbnail</strong></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
document.getElementById('thumb-input').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = ev => {
        const img = document.getElementById('thumb-preview');
        img.src = ev.target.result; img.style.display = 'block';
    };
    reader.readAsDataURL(file);
});
</script>
@endpush
@endsection
