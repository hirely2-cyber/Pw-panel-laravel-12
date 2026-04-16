<?php $__env->startSection('title', 'Kelola Member'); ?>

<?php $__env->startSection('content'); ?>
<div class="pw-adm-card">
    
    <form method="GET" style="display:flex;gap:.6rem;flex-wrap:wrap;margin-bottom:1.2rem;">
        <input type="text" name="search" class="pw-form__input" style="max-width:220px;"
               placeholder="Cari nama / email…" value="<?php echo e(request('search')); ?>">
        <select name="role" class="pw-form__input" style="max-width:180px;">
            <option value="">Semua Role</option>
                <option value="player"       <?php if(request('role')=='player'): echo 'selected'; endif; ?>>Player</option>
                <option value="gm"            <?php if(request('role')=='gm'): echo 'selected'; endif; ?>>Game Master (Panel)</option>
                <option value="game_gm"       <?php if(request('role')=='game_gm'): echo 'selected'; endif; ?>>Game Master (Game)</option>
                <option value="admin"         <?php if(request('role')=='admin'): echo 'selected'; endif; ?>>Admin</option>
        </select>
        <button type="submit" class="pw-adm-btn">Cari</button>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request()->anyFilled(['search','role'])): ?>
            <a href="<?php echo e(route('admin.members.index')); ?>" class="pw-adm-btn pw-adm-btn--ghost">Reset</a>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </form>

    <div class="pw-table-wrap">
        <table class="pw-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role Panel</th>
                    <th>Role Game</th>
                    <th>Gold Points</th>
                    <th>Diundang Oleh</th>
                    <th>Bergabung</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <tr>
                    <td style="color:var(--pw-text-muted);font-size:.78rem;"><?php echo e($m->ID); ?></td>
                    <td><strong><?php echo e($m->name); ?></strong></td>
                    <td style="color:var(--pw-text-muted);"><?php echo e($m->email); ?></td>
                    <td>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($m->role === 'admin'): ?>
                            <span class="pw-badge pw-badge--danger">Admin</span>
                        <?php elseif($m->role === 'gm'): ?>
                            <span class="pw-badge pw-badge--warning">Game Master</span>
                        <?php else: ?>
                            <span class="pw-badge">Player</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array($m->ID, $gameGmIds) && $m->role !== 'gm'): ?>
                            <span class="pw-badge pw-badge--warning" style="margin-left:.3rem;">Game Master</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                    <td>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array($m->ID, $gameGmIds)): ?>
                            <span class="pw-badge pw-badge--warning">Game Master</span>
                        <?php else: ?>
                            <span style="color:var(--pw-text-muted);font-size:.75rem;">-</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                    <td><?php echo e(number_format($m->money)); ?></td>
                    <td>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($m->referrer): ?>
                            <a href="<?php echo e(route('admin.members.show', $m->referrer->ID)); ?>"
                               style="font-size:.78rem;color:var(--pw-gold);text-decoration:none;">
                                <?php echo e($m->referrer->name); ?>

                            </a>
                        <?php else: ?>
                            <span style="color:var(--pw-text-muted);font-size:.75rem;">—</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                    <td style="color:var(--pw-text-muted);font-size:.78rem;"><?php echo e($m->creatime?->translatedFormat('d M Y') ?? '-'); ?></td>
                    <td>
                        <a href="<?php echo e(route('admin.members.show', $m->ID)); ?>" class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--ghost">Detail</a>
                    </td>
                </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <tr>
                    <td colspan="9" style="text-align:center;color:var(--pw-text-muted);">Tidak ada data.</td>
                </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    </div>

    <div style="margin-top:1rem;">
        <?php echo e($members->withQueryString()->onEachSide(1)->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/pw-panel/resources/views/admin/members/index.blade.php ENDPATH**/ ?>