@extends('layouts.admin')
@section('title', $news->exists ? 'Edit Berita' : 'Tambah Berita')

@section('content')

<div style="margin-bottom:1rem;">
    <a href="{{ route('admin.news.index') }}" class="pw-adm-btn pw-adm-btn--ghost" style="font-size:.78rem;padding:.35rem .8rem;">
        ← Kembali
    </a>
</div>

<form action="{{ $news->exists ? route('admin.news.update', $news->id) : route('admin.news.store') }}"
      method="POST" enctype="multipart/form-data">
    @csrf
    @if($news->exists) @method('PUT') @endif

    @if($errors->any())
    <div style="background:rgba(224,82,82,.12);border:1px solid rgba(224,82,82,.3);border-radius:6px;padding:.8rem 1rem;margin-bottom:1rem;">
        <p style="color:#e05252;font-weight:600;margin-bottom:.4rem;">Terdapat kesalahan:</p>
        <ul style="color:#e05252;font-size:.8rem;margin:0;padding-left:1.2rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;align-items:start;">

        {{-- Main Content --}}
        <div class="pw-adm-card">
            <div class="pw-adm-card__title">{{ $news->exists ? 'Edit' : 'Tulis' }} Berita</div>

            <label class="pw-form__label">Judul <span style="color:#e05252;">*</span></label>
            <input type="text" name="title" class="pw-form__input" required
                   value="{{ old('title', $news->title) }}" style="margin-bottom:.8rem;">
            @error('title') <p style="color:#e05252;font-size:.75rem;margin-top:-.6rem;margin-bottom:.6rem;">{{ $message }}</p> @enderror

            <label class="pw-form__label">Konten <span style="color:#e05252;">*</span></label>
            {{-- Hidden textarea for form submission --}}
            <textarea name="content" id="news-content" style="display:none;">{{ old('content', $news->content) }}</textarea>
            {{-- Quill editor container --}}
            <div id="quill-editor" style="min-height:320px;"></div>
            @error('content') <p style="color:#e05252;font-size:.75rem;margin-top:.4rem;">{{ $message }}</p> @enderror
        </div>

        {{-- Sidebar Options --}}
        <div style="display:flex;flex-direction:column;gap:1rem;">
            <div class="pw-adm-card">
                <div class="pw-adm-card__title">Pengaturan</div>

                <label class="pw-form__label">Kategori <span style="color:#e05252;">*</span></label>
                <input type="text" name="category" class="pw-form__input"
                       value="{{ old('category', $news->category) }}" placeholder="Update, Event, Maintenance…"
                       list="category-suggestions" style="margin-bottom:.8rem;">
                <datalist id="category-suggestions">
                    <option value="Update">
                    <option value="Event">
                    <option value="Maintenance">
                    <option value="Announcement">
                    <option value="Patch Notes">
                </datalist>
                @error('category') <p style="color:#e05252;font-size:.75rem;margin-top:-.4rem;margin-bottom:.6rem;">{{ $message }}</p> @enderror

                <label class="pw-form__label">Tags</label>
                <input type="text" name="tags" class="pw-form__input"
                       value="{{ old('tags', is_array($news->tags) ? implode(', ', $news->tags) : '') }}"
                       placeholder="PVE, Server, Event, Update (pisahkan dengan koma)"
                       style="margin-bottom:.8rem;">
                <p style="font-size:.72rem;color:rgba(255,255,255,.5);margin-top:-.6rem;margin-bottom:.8rem;">Pisahkan tags dengan koma. Contoh: PVE, Server, Update</p>

                <label class="pw-form__label">Tanggal Publish</label>
                <input type="text" class="pw-form__input" readonly
                       value="{{ $news->exists ? $news->created_at->format('d M Y, H:i') : 'Otomatis saat disimpan' }}"
                       style="margin-bottom:.8rem;opacity:.6;">

                <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;font-size:.85rem;margin-bottom:1rem;">
                    <input type="hidden" name="is_published" value="0">
                    <input type="checkbox" name="is_published" value="1"
                           {{ old('is_published', $news->is_published ?? true) ? 'checked' : '' }}>
                    Aktifkan (tampil di website)
                </label>

                <div style="display:flex;gap:.5rem;">
                    <button type="submit" class="pw-adm-btn" style="flex:1;">
                        {{ $news->exists ? 'Simpan' : 'Publish' }}
                    </button>
                    <a href="{{ route('admin.news.index') }}" class="pw-adm-btn pw-adm-btn--ghost">Batal</a>
                </div>
            </div>

            <div class="pw-adm-card">
                <div class="pw-adm-card__title">Thumbnail</div>
                <div class="pw-img-upload" id="thumb-wrap">
                    <input type="file" name="thumbnail" accept="image/*" id="thumb-input">
                    @if($news->thumbnail)
                        <img src="{{ Storage::url($news->thumbnail) }}" class="pw-img-upload__preview" id="thumb-preview" alt="Thumbnail">
                        <div class="pw-img-upload__label">Klik untuk ganti gambar</div>
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

@push('styles')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
.ql-toolbar.ql-snow {
    background: rgba(255,255,255,.06);
    border: 1px solid rgba(255,255,255,.14) !important;
    border-bottom: 1px solid rgba(255,255,255,.08) !important;
    border-radius: 6px 6px 0 0;
}
.ql-snow.ql-toolbar button:hover,
.ql-snow .ql-toolbar button:hover,
.ql-snow.ql-toolbar button.ql-active,
.ql-snow .ql-toolbar button.ql-active {
    background: rgba(184,134,11,.15) !important;
}
.ql-snow.ql-toolbar button:hover .ql-stroke,
.ql-snow.ql-toolbar button.ql-active .ql-stroke {
    stroke: #d4a860 !important;
}
.ql-snow.ql-toolbar button:hover .ql-fill,
.ql-snow.ql-toolbar button.ql-active .ql-fill {
    fill: #d4a860 !important;
}
.ql-container.ql-snow {
    background: rgba(255,255,255,.04);
    border: 1px solid rgba(255,255,255,.14) !important;
    border-top: none !important;
    border-radius: 0 0 6px 6px;
    font-size: .88rem;
}
.ql-editor {
    color: #f5f5f5;
    min-height: 280px;
    line-height: 1.7;
    font-family: inherit;
}
.ql-editor strong { color: #ffffff; font-weight: 700; }
.ql-editor h1, .ql-editor h2, .ql-editor h3 { color: #ffffff; }
.ql-editor a { color: #d4a860; }
.ql-editor blockquote { border-left-color: rgba(184,134,11,.5); color: rgba(245,245,245,.85); }
.ql-editor code, .ql-editor pre { background: rgba(0,0,0,.3); color: #f5f5f5; }
.ql-editor.ql-blank::before { color: rgba(245,245,245,.3); font-style: normal; }
.ql-snow .ql-stroke { stroke: #d4a860 !important; }
.ql-snow .ql-fill { fill: #d4a860 !important; }
.ql-snow .ql-picker { color: #d4a860 !important; }
.ql-snow .ql-picker-options { background: #1a1610 !important; border-color: rgba(184,134,11,.3) !important; }
.ql-snow .ql-tooltip { background: #1a1610; border-color: rgba(184,134,11,.3); color: #f5f5f5; }
.ql-snow .ql-tooltip input[type=text] { background: rgba(255,255,255,.06); border-color: rgba(255,255,255,.15); color: #f5f5f5; }

/* Light Mode Styles for Quill Editor */
[data-theme="light"] .ql-toolbar.ql-snow {
    background: #f9f9f9;
    border-color: rgba(0,0,0,.15) !important;
}
[data-theme="light"] .ql-container.ql-snow {
    background: #ffffff;
    border-color: rgba(0,0,0,.15) !important;
}
[data-theme="light"] .ql-editor {
    color: #1a1a1a;
}
[data-theme="light"] .ql-editor p {
    color: #1a1a1a;
}
[data-theme="light"] .ql-editor strong { 
    color: #111111; 
}
[data-theme="light"] .ql-editor h1, 
[data-theme="light"] .ql-editor h2, 
[data-theme="light"] .ql-editor h3 { 
    color: #111111; 
}
[data-theme="light"] .ql-editor a { 
    color: #8a6020; 
}
[data-theme="light"] .ql-editor blockquote { 
    border-left-color: rgba(184,134,11,.5); 
    color: rgba(0,0,0,.75); 
    background: rgba(184,134,11,.06);
}
[data-theme="light"] .ql-editor code, 
[data-theme="light"] .ql-editor pre { 
    background: rgba(0,0,0,.06); 
    color: #2d6a4f; 
}
[data-theme="light"] .ql-editor.ql-blank::before { 
    color: rgba(0,0,0,.4); 
}
[data-theme="light"] .ql-snow .ql-stroke { 
    stroke: #8a6020 !important; 
}
[data-theme="light"] .ql-snow .ql-fill { 
    fill: #8a6020 !important; 
}
[data-theme="light"] .ql-snow .ql-picker { 
    color: #8a6020 !important; 
}
[data-theme="light"] .ql-snow .ql-picker-options { 
    background: #ffffff !important; 
    border-color: rgba(0,0,0,.15) !important; 
}
[data-theme="light"] .ql-snow .ql-tooltip { 
    background: #ffffff; 
    border-color: rgba(0,0,0,.15); 
    color: #1a1a1a; 
}
[data-theme="light"] .ql-snow .ql-tooltip input[type=text] { 
    background: #ffffff; 
    border-color: rgba(0,0,0,.2); 
    color: #1a1a1a; 
}
[data-theme="light"] .ql-snow.ql-toolbar button:hover,
[data-theme="light"] .ql-snow .ql-toolbar button:hover {
    background: rgba(184,134,11,.12) !important;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
(function() {
    var textarea = document.getElementById('news-content');
    var quill = new Quill('#quill-editor', {
        theme: 'snow',
        placeholder: 'Tulis konten berita di sini...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                [{ 'indent': '-1' }, { 'indent': '+1' }],
                ['blockquote', 'code-block'],
                ['link'],
                ['clean']
            ]
        }
    });
    // Populate editor with existing content
    if (textarea.value) {
        quill.root.innerHTML = textarea.value;
    }
    // Sync Quill content to hidden textarea on every change
    quill.on('text-change', function() {
        var html = quill.root.innerHTML;
        if (html === '<p><br></p>' || html.trim() === '') {
            textarea.value = '';
        } else {
            textarea.value = html;
        }
    });

    // Also sync on form submit as safety net
    var form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        var html = quill.root.innerHTML;
        if (html === '<p><br></p>' || html.trim() === '') {
            textarea.value = '';
        } else {
            textarea.value = html;
        }
    });
})();
</script>

<script>
document.getElementById('thumb-input').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = ev => {
        const img = document.getElementById('thumb-preview');
        img.src = ev.target.result;
        img.style.display = 'block';
    };
    reader.readAsDataURL(file);
});
</script>
@endpush
@endsection
