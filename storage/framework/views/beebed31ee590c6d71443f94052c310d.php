<?php $__env->startSection('title', $voucher->exists ? 'Edit Voucher' : 'Buat Voucher'); ?>

<?php $__env->startSection('content'); ?>
<div style="max-width:440px;">
    <div style="margin-bottom:1rem;">
        <a href="<?php echo e(route('admin.voucher.index')); ?>" class="pw-adm-btn pw-adm-btn--ghost pw-adm-btn--sm">← Kembali</a>
    </div>

    <div class="pw-adm-card">
        <div class="pw-adm-card__title"><?php echo e($voucher->exists ? 'Edit' : 'Buat'); ?> Voucher</div>

        <form action="<?php echo e($voucher->exists ? route('admin.voucher.update', $voucher->id) : route('admin.voucher.store')); ?>"
              method="POST">
            <?php echo csrf_field(); ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($voucher->exists): ?> <?php echo method_field('PUT'); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($voucher->exists): ?>
            <label class="pw-form__label">Kode</label>
            <input type="text" class="pw-form__input" value="<?php echo e($voucher->code); ?>" disabled
                   style="margin-bottom:.8rem;opacity:.6;cursor:not-allowed;">
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                 <label class="pw-form__label">Deskripsi / Label</label>
                 <input type="text" name="description" class="pw-form__input"
                     value="<?php echo e(old('description', $voucher->description)); ?>" placeholder="Event Hari Kemerdekaan"
                   style="margin-bottom:.8rem;">
                 <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p style="color:#e05252;font-size:.75rem;margin-top:-.6rem;margin-bottom:.6rem;"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                 <label class="pw-form__label">Tipe Reward <span style="color:#e05252;">*</span></label>
                 <select name="type" class="pw-form__input" required style="margin-bottom:.8rem;">
                  <option value="gold_points" <?php echo e(old('type', $voucher->normalized_type) === 'gold_points' ? 'selected' : ''); ?>>Gold Points</option>
                  <option value="cubi" <?php echo e(old('type', $voucher->normalized_type) === 'cubi' ? 'selected' : ''); ?>>Cubi Gold</option>
                 </select>
                 <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p style="color:#e05252;font-size:.75rem;margin-top:-.6rem;margin-bottom:.6rem;"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                 <label class="pw-form__label">Nilai Reward <span style="color:#e05252;">*</span></label>
                 <input type="number" name="value" class="pw-form__input" required min="1"
                     value="<?php echo e(old('value', $voucher->value)); ?>"
                   style="margin-bottom:.8rem;">
                 <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['value'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p style="color:#e05252;font-size:.75rem;margin-top:-.6rem;margin-bottom:.6rem;"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                 <label class="pw-form__label">Maksimal Pemakaian</label>
                 <input type="number" name="max_uses" class="pw-form__input" min="1"
                     value="<?php echo e(old('max_uses', $voucher->max_uses)); ?>" placeholder="Kosong = tidak terbatas"
                     style="margin-bottom:.8rem;">
                 <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['max_uses'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p style="color:#e05252;font-size:.75rem;margin-top:-.6rem;margin-bottom:.6rem;"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                 <label class="pw-form__label">Expired At</label>
                 <input type="datetime-local" name="expires_at" class="pw-form__input"
                     value="<?php echo e(old('expires_at', $voucher->expires_at ? $voucher->expires_at->format('Y-m-d\TH:i') : '')); ?>"
                     style="margin-bottom:.8rem;">
                 <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['expires_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p style="color:#e05252;font-size:.75rem;margin-top:-.6rem;margin-bottom:.6rem;"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($voucher->exists): ?>
            <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;font-size:.85rem;margin-bottom:1rem;">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1"
                       <?php echo e(old('is_active', $voucher->is_active) ? 'checked' : ''); ?>>
                Aktifkan voucher
            </label>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <p style="font-size:.72rem;color:var(--pw-text-muted);margin-bottom:1rem;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$voucher->exists): ?>
                    Kode 16 karakter akan digenerate otomatis.
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                Voucher bisa dibatasi kuota pemakaian dan tanggal kedaluwarsa.
            </p>

            <div style="display:flex;gap:.5rem;">
                <button type="submit" class="pw-adm-btn" style="flex:1;">
                    <?php echo e($voucher->exists ? 'Simpan' : 'Buat Voucher'); ?>

                </button>
                <a href="<?php echo e(route('admin.voucher.index')); ?>" class="pw-adm-btn pw-adm-btn--ghost">Batal</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/pw-panel/resources/views/admin/voucher/form.blade.php ENDPATH**/ ?>