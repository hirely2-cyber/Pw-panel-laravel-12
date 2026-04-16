<?php $__env->startSection('title', 'Artikel / Berita'); ?>

<?php $__env->startSection('content'); ?>
<div class="pw-adm-card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.2rem;flex-wrap:wrap;gap:.6rem;">
        <div style="color:var(--pw-text-muted);font-size:.83rem;">
            <?php echo e($articles->total()); ?> artikel — hanya artikelmu ditampilkan
        </div>
        <a href="<?php echo e(route('gm.articles.create')); ?>" class="pw-adm-btn">+ Tulis Artikel</a>
    </div>

    <div class="pw-table-wrap">
        <table class="pw-table">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $art): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <tr>
                    <td>
                        <strong><?php echo e($art->title); ?></strong>
                        <div style="font-size:.72rem;color:var(--pw-text-muted);"><?php echo e(Str::limit($art->body, 70)); ?></div>
                    </td>
                    <td><span class="pw-badge"><?php echo e($art->category); ?></span></td>
                    <td>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($art->is_active): ?>
                            <span class="pw-badge pw-badge--success">Aktif</span>
                        <?php else: ?>
                            <span class="pw-badge pw-badge--warning">Menunggu Persetujuan</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                    <td style="font-size:.78rem;color:var(--pw-text-muted);"><?php echo e($art->published_at?->format('d M Y')); ?></td>
                    <td style="display:flex;gap:.3rem;">
                        <a href="<?php echo e(route('gm.articles.edit', $art->id)); ?>" class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--ghost">Edit</a>
                        <form action="<?php echo e(route('gm.articles.destroy', $art->id)); ?>" method="POST"
                              data-confirm="Hapus Artikel|Yakin ingin menghapus artikel ini?">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--ghost" style="color:#e05252;">Hapus</button>
                        </form>
                    </td>
                </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <tr>
                    <td colspan="5" style="text-align:center;color:var(--pw-text-muted);">Belum ada artikel.</td>
                </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    </div>

    <div style="margin-top:1rem;"><?php echo e($articles->links()); ?></div>
</div>

<div class="pw-adm-card" style="margin-top:1rem;font-size:.8rem;color:var(--pw-text-muted);">
    <strong style="color:var(--pw-text);">Info:</strong> Artikel yang kamu buat akan masuk ke review admin sebelum ditampilkan di website. Setelah disetujui, status akan berubah menjadi "Aktif".
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.gm', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/pw-panel/resources/views/gm/articles/index.blade.php ENDPATH**/ ?>