<?php $__env->startSection('title', $site->exists ? 'Edit Site Vote' : 'Tambah Site Vote'); ?>

<?php $__env->startSection('content'); ?>
<div>
    <div style="margin-bottom:1rem;">
        <a href="<?php echo e(route('admin.vote.index')); ?>" class="pw-adm-btn pw-adm-btn--ghost pw-adm-btn--sm">← Kembali</a>
    </div>

    <div class="pw-adm-card">
        <div class="pw-adm-card__title"><?php echo e($site->exists ? 'Edit' : 'Tambah'); ?> Site Vote</div>

        <form action="<?php echo e($site->exists ? route('admin.vote.update', $site->id) : route('admin.vote.store')); ?>"
              method="POST">
            <?php echo csrf_field(); ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($site->exists): ?> <?php echo method_field('PUT'); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <label class="pw-form__label">Nama Site <span style="color:#e05252;">*</span></label>
            <input type="text" name="name" class="pw-form__input" required
                   value="<?php echo e(old('name', $site->name)); ?>" placeholder="Top100Arena, MPOG, dll."
                   style="margin-bottom:.8rem;">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p style="color:#e05252;font-size:.75rem;margin-top:-.6rem;margin-bottom:.6rem;"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <label class="pw-form__label">URL Vote <span style="color:#e05252;">*</span></label>
            <input type="url" name="url" class="pw-form__input" required
                   value="<?php echo e(old('url', $site->url)); ?>" placeholder="https://top100arena.com/vote/..."
                   style="margin-bottom:.8rem;">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p style="color:#e05252;font-size:.75rem;margin-top:-.6rem;margin-bottom:.6rem;"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.8rem;margin-bottom:.8rem;">
                <div>
                    <label class="pw-form__label">Reward Gold <span style="color:#e05252;">*</span></label>
                    <input type="number" name="reward" class="pw-form__input" required min="1"
                           value="<?php echo e(old('reward', $site->reward)); ?>">
                </div>
                <div>
                    <label class="pw-form__label">Urutan Tampil</label>
                    <input type="number" name="sort_order" class="pw-form__input" min="0"
                           value="<?php echo e(old('sort_order', $site->sort_order ?? 0)); ?>">
                </div>
            </div>

            <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;font-size:.85rem;margin-bottom:1.2rem;">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1"
                       <?php echo e(old('is_active', $site->is_active ?? true) ? 'checked' : ''); ?>>
                Aktifkan site vote
            </label>

            <div style="display:flex;gap:.5rem;">
                <button type="submit" class="pw-adm-btn" style="flex:1;">
                    <?php echo e($site->exists ? 'Simpan' : 'Tambahkan'); ?>

                </button>
                <a href="<?php echo e(route('admin.vote.index')); ?>" class="pw-adm-btn pw-adm-btn--ghost">Batal</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/pw-panel/resources/views/admin/vote/form.blade.php ENDPATH**/ ?>