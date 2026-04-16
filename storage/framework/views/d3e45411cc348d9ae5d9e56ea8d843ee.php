<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="pw-adm-content-inner">

    
        <div class="pw-card pw-card--gold" style="margin-bottom:1.5rem;">
            <div style="display:flex; align-items:center; gap:1.5rem; flex-wrap:wrap;">
                <div style="width:64px; height:64px; border-radius:50%; background:linear-gradient(135deg, var(--pw-gold-dark), var(--pw-gold)); display:flex; align-items:center; justify-content:center; font-family:'Cinzel',serif; font-size:1.5rem; font-weight:700; color:#1a1200; flex-shrink:0;">
                    <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                </div>
                <div style="flex:1;">
                    <div style="font-family:'Cinzel',serif; font-size:1.25rem; color:var(--pw-text-light);"><?php echo e($user->name); ?></div>
                    <div style="font-size:0.8rem; color:var(--pw-text-muted); margin-top:0.2rem;"><?php echo e($user->email); ?></div>
                    <div style="margin-top:0.5rem; display:flex; gap:0.75rem; flex-wrap:wrap;">
                        <span class="pw-badge pw-badge--warning">
                            <?php echo e(number_format($user->money)); ?> <?php echo e(config('pw-config.currency.name')); ?>

                        </span>
                        <span class="pw-badge <?php echo e($user->isAdministrator() ? 'pw-front-role-badge pw-front-role-badge--admin' : ($user->isGamemaster() ? 'pw-front-role-badge pw-front-role-badge--gm' : 'pw-badge--muted')); ?>">
                            <?php echo e($user->role ?? 'Player'); ?>

                        </span>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->isOnline()): ?>
                        <span class="pw-badge pw-badge--success">Online</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(config('pw-config.features.donate')): ?>
                <a href="<?php echo e(route('cubi-shop')); ?>" class="pw-btn pw-btn--gold pw-btn--sm">+ Top-up Gold Points</a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        
        <div class="pw-stats-grid">
            <div class="pw-stat-card">
                <div class="pw-stat-card__label">Saldo Gold Points</div>
                <div class="pw-stat-card__value"><?php echo e(number_format($user->money)); ?></div>
            </div>
            <div class="pw-stat-card">
                <div class="pw-stat-card__label">Total Donate</div>
                <div class="pw-stat-card__value"><?php echo e($recentInvoices->where('status', 'paid')->sum('gold_amount')); ?></div>
            </div>
            <div class="pw-stat-card">
                <div class="pw-stat-card__label">Total Vote</div>
                <div class="pw-stat-card__value"><?php echo e($recentVoteLogs->count()); ?></div>
            </div>
            <div class="pw-stat-card">
                <div class="pw-stat-card__label">Pembelian Shop</div>
                <div class="pw-stat-card__value"><?php echo e($recentShopLogs->count()); ?></div>
            </div>
        </div>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recentInvoices->count()): ?>
        <div class="pw-card" style="margin-bottom:1.5rem;">
            <div class="pw-card__title">Transaksi Terakhir</div>
            <div class="pw-table-wrap">
                <table class="pw-table">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Gold Points</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $recentInvoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <tr>
                            <td><code style="font-size:0.78rem; color:var(--pw-text-muted);"><?php echo e($inv->invoice_number); ?></code></td>
                            <td><?php echo e(number_format($inv->gold_amount)); ?></td>
                            <td>Rp <?php echo e(number_format($inv->unique_amount)); ?></td>
                            <td>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($inv->status === 'paid'): ?>
                                    <span class="pw-badge pw-badge--success">Sukses</span>
                                <?php elseif($inv->status === 'pending'): ?>
                                    <span class="pw-badge pw-badge--warning">Menunggu</span>
                                <?php else: ?>
                                    <span class="pw-badge pw-badge--danger">Gagal</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td style="color:var(--pw-text-muted);"><?php echo e($inv->created_at->format('d M Y H:i')); ?></td>
                        </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recentShopLogs->count()): ?>
        <div class="pw-card">
            <div class="pw-card__title">Pembelian Shop Terakhir</div>
            <div class="pw-table-wrap">
                <table class="pw-table">
                    <thead>
                        <tr><th>Item</th><th>Harga</th><th>Tanggal</th></tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $recentShopLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <tr>
                            <td><?php echo e($log->item_name); ?></td>
                            <td><?php echo e(number_format($log->price)); ?> Gold Points</td>
                            <td style="color:var(--pw-text-muted);"><?php echo e($log->created_at->format('d M Y H:i')); ?></td>
                        </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.pw-front-role-badge--admin {
    background: rgba(147, 51, 234, .18);
    border-color: rgba(147, 51, 234, .42);
    color: #c084fc;
}

.pw-front-role-badge--gm {
    background: rgba(239, 68, 68, .18);
    border-color: rgba(239, 68, 68, .42);
    color: #f87171;
}
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/pw-panel/resources/views/front/dashboard.blade.php ENDPATH**/ ?>