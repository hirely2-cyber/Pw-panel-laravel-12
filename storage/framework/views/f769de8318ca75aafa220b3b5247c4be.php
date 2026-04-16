<?php $__env->startSection('title', 'PW Backup Monitor'); ?>

<?php $__env->startSection('content'); ?>
<div class="pw-adm-card" style="padding:1rem 1.2rem;margin-bottom:1rem;">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;">
        <div>
            <div style="font-size:.82rem;font-weight:700;letter-spacing:.06em;color:var(--pw-text-light);margin-bottom:.2rem;">Backup Files</div>
            <div style="font-size:.75rem;color:var(--pw-text-muted);">Path server: <span style="font-family:monospace;"><?php echo e($serverPath); ?></span></div>
        </div>
        <a href="<?php echo e(route('admin.server-control')); ?>" class="pw-adm-btn pw-adm-btn--ghost">Kembali ke Server Control</a>
    </div>
</div>

<div class="pw-adm-card" style="padding:1rem 1.2rem;">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;margin-bottom:.8rem;">
        <div style="font-size:.82rem;font-weight:700;letter-spacing:.06em;color:var(--pw-text-light);">Daftar Backup PW</div>
        <div style="font-size:.74rem;color:var(--pw-text-muted);">Total: <?php echo e($files->count()); ?> file</div>
    </div>

    <div style="overflow:auto;">
        <table style="width:100%;border-collapse:collapse;min-width:920px;">
            <thead>
                <tr>
                    <th style="text-align:left;padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.72rem;color:var(--pw-text-muted);">File</th>
                    <th style="text-align:right;padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.72rem;color:var(--pw-text-muted);">Ukuran</th>
                    <th style="text-align:left;padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.72rem;color:var(--pw-text-muted);">Tanggal</th>
                    <th style="text-align:left;padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.72rem;color:var(--pw-text-muted);">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <tr>
                        <td style="padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.78rem;font-family:monospace;"><?php echo e($row['name']); ?></td>
                        <td style="padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.78rem;text-align:right;"><?php echo e(number_format((int) $row['size'] / 1048576, 2)); ?> MB</td>
                        <td style="padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.78rem;"><?php echo e(\Carbon\Carbon::createFromTimestamp((int) $row['mtime'])->format('d/m/Y H:i:s')); ?></td>
                        <td style="padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.78rem;">
                            <div style="display:flex;gap:.45rem;align-items:center;flex-wrap:wrap;">
                                <form method="POST" action="<?php echo e(route('admin.backup-monitor.download')); ?>" style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="file" value="<?php echo e($row['name']); ?>">
                                    <button type="submit" class="pw-adm-btn pw-adm-btn--sm">Download</button>
                                </form>
                                <form method="POST" action="<?php echo e(route('admin.backup-monitor.destroy')); ?>" style="display:inline;" onsubmit="return confirm('Hapus file backup ini?');">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="file" value="<?php echo e($row['name']); ?>">
                                    <button type="submit" class="pw-adm-btn pw-adm-btn--sm" style="background:#dc2626;color:#fff;">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <tr>
                        <td colspan="4" style="padding:1rem;border-bottom:1px solid var(--pw-border);font-size:.82rem;color:var(--pw-text-muted);text-align:center;">Belum ada file backup.</td>
                    </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/pw-panel/resources/views/admin/backup-monitor.blade.php ENDPATH**/ ?>