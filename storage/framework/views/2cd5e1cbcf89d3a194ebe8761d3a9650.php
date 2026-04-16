
<?php $__env->startSection('title', 'Riwayat Referral'); ?>

<?php $__env->startSection('content'); ?>


<div style="display:flex;gap:.5rem;margin-bottom:1.25rem;">
    <a href="<?php echo e(route('admin.referral')); ?>" class="pw-btn pw-btn--gold" style="font-size:.8rem;padding:.45rem .9rem;">
        <svg viewBox="0 0 20 20" fill="none" width="14"><path d="M3 5h14M3 10h14M3 15h9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        Riwayat Referral
    </a>
    <a href="<?php echo e(route('admin.referral.partners')); ?>" class="pw-btn pw-btn--muted" style="font-size:.8rem;padding:.45rem .9rem;">
        <svg viewBox="0 0 20 20" fill="none" width="14"><path d="M10 2v6M13 5H7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M15 10a5 5 0 11-10 0" stroke="currentColor" stroke-width="1.5"/><circle cx="5" cy="15" r="2.5" stroke="currentColor" stroke-width="1.3"/><circle cx="15" cy="15" r="2.5" stroke="currentColor" stroke-width="1.3"/></svg>
        Pengaturan Partner
    </a>
    <a href="<?php echo e(route('admin.referral.terms')); ?>" class="pw-btn pw-btn--muted" style="font-size:.8rem;padding:.45rem .9rem;">
        <svg viewBox="0 0 20 20" fill="none" width="14"><path d="M6 2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4a2 2 0 012-2z" stroke="currentColor" stroke-width="1.5"/><path d="M7 7h6M7 10h6M7 13h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        Syarat &amp; Ketentuan
    </a>
</div>


<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;margin-bottom:1.5rem;">
    <div class="pw-adm-card" style="text-align:center;padding:1rem;">
        <div style="font-size:.72rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.04em;">Total Referral</div>
        <div style="font-size:1.6rem;font-weight:700;color:var(--pw-gold-light);margin-top:.2rem;"><?php echo e(number_format($totalReferrals)); ?></div>
    </div>
    <div class="pw-adm-card" style="text-align:center;padding:1rem;">
        <div style="font-size:.72rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.04em;">Sudah Reward</div>
        <div style="font-size:1.6rem;font-weight:700;color:#7deba0;margin-top:.2rem;"><?php echo e(number_format($totalRewarded)); ?></div>
    </div>
    <div class="pw-adm-card" style="text-align:center;padding:1rem;">
        <div style="font-size:.72rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.04em;">Total Gold Diberikan</div>
        <div style="font-size:1.6rem;font-weight:700;color:#b89d4f;margin-top:.2rem;"><?php echo e(number_format($totalGoldGiven)); ?></div>
    </div>
    <div class="pw-adm-card" style="text-align:center;padding:1rem;">
        <div style="font-size:.72rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.04em;">Total Cubi Diberikan</div>
        <div style="font-size:1.6rem;font-weight:700;color:#60d0ff;margin-top:.2rem;"><?php echo e(number_format($totalCubiGiven)); ?></div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 260px;gap:1.25rem;align-items:start;">

    
    <div class="pw-adm-card">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;flex-wrap:wrap;gap:.6rem;">
            <div style="font-weight:600;font-size:.92rem;">Riwayat Reward</div>
            <form method="GET" action="<?php echo e(route('admin.referral')); ?>" style="display:flex;gap:.4rem;align-items:center;">
                <input type="text" name="search" class="pw-adm-input" placeholder="Cari username..." value="<?php echo e(request('search')); ?>" style="width:160px;font-size:.78rem;">
                <button type="submit" class="pw-adm-btn pw-adm-btn--sm">Cari</button>
            </form>
        </div>

        <div class="pw-table-wrap">
            <table class="pw-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pengundang</th>
                        <th>Diundang</th>
                        <th>Tipe Reward</th>
                        <th>Jumlah</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $rewards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rw): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <tr>
                        <td style="color:var(--pw-text-muted);"><?php echo e($rw->id); ?></td>
                        <td>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($rw->referrer): ?>
                            <a href="<?php echo e(route('admin.members.show', $rw->referrer->ID)); ?>" style="color:var(--pw-gold-light);font-weight:600;"><?php echo e($rw->referrer->name); ?></a>
                            <?php else: ?>
                            <span style="color:var(--pw-text-muted);">-</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                        <td>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($rw->referred): ?>
                            <a href="<?php echo e(route('admin.members.show', $rw->referred->ID)); ?>" style="color:var(--pw-text);font-weight:500;"><?php echo e($rw->referred->name); ?></a>
                            <?php else: ?>
                            <span style="color:var(--pw-text-muted);">-</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                        <td>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($rw->type === 'registration'): ?>
                                <span class="pw-badge pw-badge--success">Gold Points</span>
                            <?php elseif($rw->type === 'registration_cubi'): ?>
                                <span class="pw-badge" style="background:rgba(96,208,255,.12);color:#60d0ff;">Cubi Gold</span>
                            <?php else: ?>
                                <span class="pw-badge"><?php echo e($rw->type); ?></span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                        <td><strong style="color:var(--pw-gold-light);"><?php echo e(number_format($rw->reward_amount)); ?></strong></td>
                        <td style="color:var(--pw-text-muted);font-size:.78rem;"><?php echo e($rw->created_at->format('d M Y H:i')); ?></td>
                    </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <tr>
                        <td colspan="6" style="text-align:center;padding:2rem;color:var(--pw-text-muted);">Belum ada riwayat referral reward.</td>
                    </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($rewards->hasPages()): ?>
        <div style="margin-top:1rem;">
            <?php echo e($rewards->links()); ?>

        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
    <div class="pw-adm-card">
        <div style="font-weight:600;font-size:.92rem;margin-bottom:.8rem;">Top Referrers</div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $topReferrers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $tr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
        <div style="display:flex;justify-content:space-between;align-items:center;padding:.4rem 0;border-bottom:1px solid rgba(255,255,255,.04);font-size:.83rem;">
            <div style="display:flex;align-items:center;gap:.5rem;">
                <span style="color:var(--pw-text-muted);font-size:.75rem;width:1.2rem;"><?php echo e($idx + 1); ?>.</span>
                <a href="<?php echo e(route('admin.members.show', $tr->ID)); ?>" style="color:var(--pw-gold-light);font-weight:600;"><?php echo e($tr->name); ?></a>
            </div>
            <span class="pw-badge pw-badge--success"><?php echo e($tr->total_referred); ?> referral</span>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        <p style="color:var(--pw-text-muted);font-size:.82rem;text-align:center;padding:1rem 0;">Belum ada data.</p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.pw-btn--muted {
    background: transparent;
    border: 1px solid var(--pw-border, rgba(255,255,255,.12));
    color: var(--pw-text-muted);
}
.pw-btn--muted:hover { color: var(--pw-text); border-color: var(--pw-gold); }
</style>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/pw-panel/resources/views/admin/referral/index.blade.php ENDPATH**/ ?>