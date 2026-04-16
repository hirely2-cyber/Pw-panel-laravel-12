<?php $__env->startSection('title', $article->exists ? 'Edit Artikel' : 'Tulis Artikel'); ?>

<?php $__env->startSection('content'); ?>
<form action="<?php echo e($article->exists ? route('gm.articles.update', $article->id) : route('gm.articles.store')); ?>"
      method="POST" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($article->exists): ?> <?php echo method_field('PUT'); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;align-items:start;">

        
        <div class="pw-adm-card">
            <div class="pw-adm-card__title"><?php echo e($article->exists ? 'Edit' : 'Tulis'); ?> Artikel</div>

            <label class="pw-form__label">Judul <span style="color:#e05252;">*</span></label>
            <input type="text" name="title" class="pw-form__input" required
                   value="<?php echo e(old('title', $article->title)); ?>" style="margin-bottom:.8rem;">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p style="color:#e05252;font-size:.75rem;margin-top:-.6rem;margin-bottom:.6rem;"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <label class="pw-form__label">Konten <span style="color:#e05252;">*</span></label>
            <textarea name="body" rows="16"
                      style="width:100%;background:var(--pw-bg-card,rgba(255,255,255,.04));border:1px solid var(--pw-border,rgba(255,255,255,.1));border-radius:6px;color:var(--pw-text,#e8dfc8);padding:.6rem .8rem;font-size:.85rem;font-family:inherit;resize:vertical;box-sizing:border-box;"><?php echo e(old('body', $article->body)); ?></textarea>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['body'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p style="color:#e05252;font-size:.75rem;margin-top:.3rem;"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div style="display:flex;flex-direction:column;gap:1rem;">
            <div class="pw-adm-card">
                <div class="pw-adm-card__title">Pengaturan</div>

                <label class="pw-form__label">Kategori <span style="color:#e05252;">*</span></label>
                <input type="text" name="category" class="pw-form__input"
                       value="<?php echo e(old('category', $article->category)); ?>" list="cat-list"
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
                       value="<?php echo e(old('published_at', $article->published_at?->format('Y-m-d\TH:i'))); ?>"
                       style="margin-bottom:1rem;">

                <div style="background:#b89d4f11;border:1px solid #b89d4f33;border-radius:6px;padding:.6rem .8rem;font-size:.75rem;color:var(--pw-text-muted);margin-bottom:1rem;">
                    Artikel akan masuk review admin sebelum dipublikasikan.
                </div>

                <div style="display:flex;gap:.5rem;">
                    <button type="submit" class="pw-adm-btn" style="flex:1;">
                        <?php echo e($article->exists ? 'Simpan' : 'Kirim untuk Review'); ?>

                    </button>
                    <a href="<?php echo e(route('gm.articles.index')); ?>" class="pw-adm-btn pw-adm-btn--ghost">Batal</a>
                </div>
            </div>

            <div class="pw-adm-card">
                <div class="pw-adm-card__title">Thumbnail</div>
                <div class="pw-img-upload">
                    <input type="file" name="thumbnail" accept="image/*" id="thumb-input">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($article->thumbnail): ?>
                        <img src="<?php echo e(Storage::url($article->thumbnail)); ?>" class="pw-img-upload__preview" id="thumb-preview" alt="">
                        <div class="pw-img-upload__label">Klik untuk ganti</div>
                    <?php else: ?>
                        <img src="" class="pw-img-upload__preview" id="thumb-preview" alt="" style="display:none">
                        <svg class="pw-img-upload__icon" viewBox="0 0 40 40" fill="none"><rect x="4" y="8" width="32" height="24" rx="3" stroke="currentColor" stroke-width="1.8"/><path d="M4 26l8-8 6 6 5-5 9 9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <div class="pw-img-upload__label"><strong>Upload Thumbnail</strong></div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</form>

<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.gm', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/pw-panel/resources/views/gm/articles/form.blade.php ENDPATH**/ ?>