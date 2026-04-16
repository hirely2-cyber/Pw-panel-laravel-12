<?php $__env->startSection('title', 'Item Shop'); ?>

<?php $__env->startSection('content'); ?>
<div class="pw-adm-card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.2rem;flex-wrap:wrap;gap:.6rem;">
        <div style="color:var(--pw-text-muted);font-size:.83rem;">Total: <?php echo e($items->total()); ?> item</div>
        <a href="<?php echo e(route('admin.shop.create')); ?>" class="pw-adm-btn">+ Tambah Item</a>
    </div>

    <div class="pw-table-wrap">
        <table class="pw-table">
            <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Harga (Gold)</th>
                    <th>Urutan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <tr>
                    <td>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->image): ?>
                            <img src="<?php echo e(Storage::url($item->image)); ?>" alt="<?php echo e($item->name); ?>"
                                 style="width:42px;height:42px;object-fit:cover;border-radius:6px;border:1px solid var(--pw-border);">
                        <?php else: ?>
                            <div style="width:42px;height:42px;background:var(--pw-surface-2);border-radius:6px;display:flex;align-items:center;justify-content:center;color:var(--pw-text-muted);font-size:.6rem;">IMG</div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                    <td>
                        <strong><?php echo e($item->name); ?></strong>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->description): ?>
                        <div style="font-size:.72rem;color:var(--pw-text-muted);"><?php echo e(Str::limit($item->description, 50)); ?></div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                    <td><span class="pw-badge"><?php echo e($item->category); ?></span></td>
                    <td><strong style="color:#b89d4f;"><?php echo e(number_format($item->price)); ?></strong></td>
                    <td style="color:var(--pw-text-muted);"><?php echo e($item->sort_order ?? 0); ?></td>
                    <td>
                        <form action="<?php echo e(route('admin.shop.toggle', $item->id)); ?>" method="POST" style="display:inline;">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="pw-badge <?php if($item->is_active): ?> pw-badge--success <?php else: ?> pw-badge--danger <?php endif; ?>"
                                    style="cursor:pointer;border:none;background:transparent;padding:2px 8px;">
                                <?php echo e($item->is_active ? 'Aktif' : 'Nonaktif'); ?>

                            </button>
                        </form>
                    </td>
                    <td style="display:flex;gap:.3rem;">
                        <a href="<?php echo e(route('admin.shop.edit', $item->id)); ?>" class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--ghost">Edit</a>
                        <form action="<?php echo e(route('admin.shop.destroy', $item->id)); ?>" method="POST"
                              data-confirm="Hapus Item|Yakin ingin menghapus item ini?">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--ghost" style="color:#e05252;">Hapus</button>
                        </form>
                    </td>
                </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <tr>
                    <td colspan="7" style="text-align:center;color:var(--pw-text-muted);">Belum ada item.</td>
                </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    </div>

    <div style="margin-top:1rem;"><?php echo e($items->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/pw-panel/resources/views/admin/shop/index.blade.php ENDPATH**/ ?>