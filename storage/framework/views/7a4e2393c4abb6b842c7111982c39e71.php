<?php $__env->startSection('title', 'Cari Member'); ?>

<?php $__env->startSection('content'); ?>
<div class="pw-adm-card">
    <form method="GET" style="display:flex;gap:.6rem;margin-bottom:1.2rem;">
        <input type="text" name="search" class="pw-form__input" style="max-width:280px;"
               placeholder="Cari nama pemain…" value="<?php echo e(request('search')); ?>">
        <button type="submit" class="pw-adm-btn">Cari</button>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request('search')): ?>
            <a href="<?php echo e(route('gm.members.index')); ?>" class="pw-adm-btn pw-adm-btn--ghost">Reset</a>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </form>

    <div class="pw-table-wrap">
        <table class="pw-table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Gold Points</th>
                    <th>Diundang Oleh</th>
                    <th>Bergabung</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <tr>
                    <td><strong><?php echo e($m->name); ?></strong></td>
                    <td style="color:var(--pw-text-muted);"><?php echo e($m->email); ?></td>
                    <td>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($m->role === 'admin'): ?> <span class="pw-badge pw-badge--danger">Admin</span>
                        <?php elseif($m->role === 'gm'): ?> <span class="pw-badge pw-badge--warning">GM</span>
                        <?php else: ?> <span class="pw-badge">Player</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                    <td><?php echo e(number_format($m->money)); ?></td>
                    <td style="font-size:.78rem;">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($m->referrer): ?>
                            <span style="color:var(--pw-gold);"><?php echo e($m->referrer->name); ?></span>
                        <?php else: ?>
                            <span style="color:var(--pw-text-muted);">—</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                    <td style="font-size:.78rem;color:var(--pw-text-muted);"><?php echo e($m->created_at?->format('d M Y') ?? '-'); ?></td>
                    <td>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->isAdministrator()): ?>
                        <a href="<?php echo e(route('gm.members.show', $m->ID)); ?>" class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--ghost">Lihat</a>
                        <?php else: ?>
                        <span style="color:var(--pw-text-muted);font-size:.75rem;">—</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <tr>
                    <td colspan="7" style="text-align:center;color:var(--pw-text-muted);">
                        <?php echo e(request('search') ? 'Pemain tidak ditemukan.' : 'Tidak ada data.'); ?>

                    </td>
                </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    </div>

    <div style="margin-top:1rem;"><?php echo e($members->withQueryString()->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.gm', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/pw-panel/resources/views/gm/members/index.blade.php ENDPATH**/ ?>