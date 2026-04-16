<?php $__env->startSection('title', 'Ranking'); ?>

<?php $__env->startSection('content'); ?>
<div style="display:flex;justify-content:flex-end;margin-bottom:1rem;">
    <form action="<?php echo e(route('admin.ranking.refresh')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <button type="submit" class="pw-adm-btn pw-adm-btn--ghost">↺ Sync dari Game DB</button>
    </form>
</div>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
<div class="pw-adm-alert pw-adm-alert--success" style="margin-bottom:1rem;"><?php echo e(session('success')); ?></div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:1.5rem;">

    
    <div class="pw-adm-card">
        <div class="pw-adm-card__title">Top 100 Pemain (by PK Kills)</div>
        <div class="pw-table-wrap" style="max-height:420px;overflow-y:auto;">
            <table class="pw-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th style="text-align:center;">Lv</th>
                        <th style="text-align:center;">PK Kills</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $players; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <tr>
                        <td style="font-weight:700;color:<?php echo e($i < 3 ? '#b89d4f' : 'var(--pw-text-muted)'); ?>;"><?php echo e($i + 1); ?></td>
                        <td><strong><?php echo e($p->character_name ?? '—'); ?></strong></td>
                        <td style="color:var(--pw-text-muted);font-size:.78rem;"><?php echo e($p->class ?? '—'); ?></td>
                        <td style="text-align:center;"><?php echo e($p->level); ?></td>
                        <td style="text-align:center;color:#f87171;font-weight:600;"><?php echo e($p->pk_points ?? 0); ?></td>
                    </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <tr><td colspan="5" style="text-align:center;color:var(--pw-text-muted);">Belum ada data.</td></tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="pw-adm-card">
        <div class="pw-adm-card__title">Ranking Guild (by Wilayah)</div>
        <div class="pw-table-wrap" style="max-height:420px;overflow-y:auto;">
            <table class="pw-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Guild</th>
                        <th>Pemimpin</th>
                        <th style="text-align:center;">Member</th>
                        <th style="text-align:center;">Wilayah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $factions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <tr>
                        <td style="font-weight:700;color:<?php echo e($i < 3 ? '#b89d4f' : 'var(--pw-text-muted)'); ?>;"><?php echo e($i + 1); ?></td>
                        <td><strong><?php echo e($f->name); ?></strong></td>
                        <td style="color:var(--pw-text-muted);font-size:.78rem;"><?php echo e($f->leader_name ?? '—'); ?></td>
                        <td style="text-align:center;color:#7ec8c8;"><?php echo e($f->members_count); ?></td>
                        <td style="text-align:center;color:#b89d4f;font-weight:600;"><?php echo e($f->territory_count); ?></td>
                    </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <tr><td colspan="5" style="text-align:center;color:var(--pw-text-muted);">Belum ada data.</td></tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>


<div class="pw-adm-card">
    <div class="pw-adm-card__title">Edit Nama Guild/Faction</div>
    <p style="font-size:.78rem;color:var(--pw-text-muted);margin-bottom:1rem;">
        Nama guild tidak tersimpan di DB game (dikelola daemon <code>uniquenamed</code>). Input nama manual di sini berdasarkan ID faction. Sorted by jumlah member.
    </p>
    <div class="pw-table-wrap">
        <table class="pw-table">
            <thead>
                <tr>
                    <th>Faction ID</th>
                    <th>Nama Tersimpan</th>
                    <th>Pemimpin (dari DB)</th>
                    <th style="text-align:center;">Member</th>
                    <th style="text-align:center;">Wilayah</th>
                    <th>Input Nama</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $gameFactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gf): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <tr>
                    <td style="color:var(--pw-text-muted);font-size:.78rem;">#<?php echo e($gf->faction_id); ?></td>
                    <td style="color:<?php echo e($savedNames->has($gf->faction_id) ? '#4ade80' : 'var(--pw-text-muted)'); ?>;font-size:.78rem;">
                        <?php echo e($savedNames->get($gf->faction_id) ?? '—'); ?>

                    </td>
                    <td style="font-size:.78rem;color:var(--pw-text-muted);"><?php echo e($gf->leader_name ?? '—'); ?></td>
                    <td style="text-align:center;font-size:.78rem;"><?php echo e($gf->members_count); ?></td>
                    <td style="text-align:center;font-size:.78rem;color:#b89d4f;font-weight:600;"><?php echo e($gf->territory_count); ?></td>
                    <td>
                        <form action="<?php echo e(route('admin.ranking.faction.name')); ?>" method="POST" style="display:flex;gap:.4rem;">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="faction_id" value="<?php echo e($gf->faction_id); ?>">
                            <input type="text" name="name" class="pw-adm-input" style="font-size:.75rem;padding:.25rem .5rem;width:130px;"
                                placeholder="Nama guild" value="<?php echo e($savedNames->get($gf->faction_id, '')); ?>">
                            <button type="submit" class="pw-adm-btn pw-adm-btn--xs pw-adm-btn--gold">Simpan</button>
                        </form>
                    </td>
                </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/pw-panel/resources/views/admin/ranking/index.blade.php ENDPATH**/ ?>