<?php $__env->startSection('title', 'GM Dashboard'); ?>

<?php $__env->startSection('content'); ?>


<div class="pw-adm-stats-row" style="display:grid;grid-template-columns:repeat(5,1fr);gap:1rem;margin-bottom:1.5rem;">
    <div class="pw-adm-stat">
        <div class="pw-adm-stat__icon" style="color:#b89d4f;">
            <svg viewBox="0 0 20 20" fill="none" width="22"><circle cx="10" cy="7" r="4" stroke="currentColor" stroke-width="1.5"/><path d="M3 17c0-3.314 3.134-6 7-6s7 2.686 7 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        </div>
        <div class="pw-adm-stat__value"><?php echo e(number_format($stats['total_players'])); ?></div>
        <div class="pw-adm-stat__label">Total Pemain</div>
    </div>
    <div class="pw-adm-stat">
        <div class="pw-adm-stat__icon" style="color:#4fad84;">
            <svg viewBox="0 0 20 20" fill="none" width="22"><circle cx="10" cy="10" r="3" stroke="currentColor" stroke-width="1.5"/><circle cx="10" cy="10" r="7" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 2"/></svg>
        </div>
        <div class="pw-adm-stat__value"><?php echo e(number_format($stats['online_players'])); ?></div>
        <div class="pw-adm-stat__label">Online Sekarang</div>
    </div>
    <div class="pw-adm-stat">
        <div class="pw-adm-stat__icon" style="color:#6d5cc7;">
            <svg viewBox="0 0 20 20" fill="none" width="22"><path d="M17 10l-4-7H7L3 10l4 7h6l4-7z" stroke="currentColor" stroke-width="1.3"/><circle cx="10" cy="10" r="2.5" stroke="currentColor" stroke-width="1.3"/></svg>
        </div>
        <div class="pw-adm-stat__value"><?php echo e(number_format($stats['total_cubi'])); ?></div>
        <div class="pw-adm-stat__label">Total Donate Cubi Coin</div>
    </div>
    <div class="pw-adm-stat">
        <div class="pw-adm-stat__icon" style="color:#c17d3c;">
            <svg viewBox="0 0 20 20" fill="none" width="22"><path d="M4 14v2M8 10v6M12 7v9M16 4v12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        </div>
        <div class="pw-adm-stat__value"><?php echo e(number_format($stats['total_donate'])); ?></div>
        <div class="pw-adm-stat__label">Total Donate (Gold Points)</div>
    </div>
    <div class="pw-adm-stat">
        <div class="pw-adm-stat__icon" style="color:#e05252;">
            <svg viewBox="0 0 20 20" fill="none" width="22"><circle cx="10" cy="10" r="7" stroke="currentColor" stroke-width="1.5"/><path d="M10 6v4l2 2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        </div>
        <div class="pw-adm-stat__value"><?php echo e(number_format($stats['pending_invoices'])); ?></div>
        <div class="pw-adm-stat__label">Invoice Pending</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">

    
    <div class="pw-adm-card">
        <div class="pw-adm-card__title">
            <svg viewBox="0 0 20 20" fill="none" width="15"><circle cx="10" cy="10" r="3" stroke="currentColor" stroke-width="1.5"/><path d="M10 2v2M10 16v2M2 10h2M16 10h2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            Pesanan Layanan Pending
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendingServices->isEmpty()): ?>
            <p style="color:var(--pw-text-muted);font-size:.83rem;">Tidak ada pesanan yang perlu diproses.</p>
        <?php else: ?>
        <div class="pw-table-wrap">
            <table class="pw-table">
                <thead>
                    <tr><th>User</th><th>Layanan</th><th>Karakter</th><th>Waktu</th></tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $pendingServices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <tr>
                        <td><?php echo e($log->user->name ?? '—'); ?></td>
                        <td><?php echo e($log->service->name ?? $log->service_name); ?></td>
                        <td style="font-size:.78rem;color:var(--pw-text-muted);"><?php echo e($log->data['character_name'] ?? '—'); ?></td>
                        <td style="font-size:.75rem;color:var(--pw-text-muted);"><?php echo e($log->created_at->diffForHumans()); ?></td>
                    </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
    <div class="pw-adm-card">
        <div class="pw-adm-card__title">
            <svg viewBox="0 0 20 20" fill="none" width="15"><path d="M3 3h2l.4 2M7 13h10l3-7H5.4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Pembelian Shop Terbaru
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recentShopLogs->isEmpty()): ?>
            <p style="color:var(--pw-text-muted);font-size:.85rem;">Belum ada pembelian.</p>
        <?php else: ?>
        <div class="pw-table-wrap">
            <table class="pw-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Item</th>
                        <th>Harga</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $recentShopLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <tr>
                        <td><?php echo e($log->user->name ?? '-'); ?></td>
                        <td><?php echo e($log->item_name); ?></td>
                        <td><?php echo e(number_format($log->price)); ?> G</td>
                        <td style="color:var(--pw-text-muted);font-size:.78rem;"><?php echo e($log->created_at->diffForHumans()); ?></td>
                    </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

</div>


<div class="pw-adm-card" style="margin-top:1.5rem;">
    <div class="pw-adm-card__title">
        <svg viewBox="0 0 20 20" fill="none" width="15"><path d="M4 6h12M4 10h8M4 14h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        Artikel Terbaru
    </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recentNews->isEmpty()): ?>
        <p style="color:var(--pw-text-muted);font-size:.83rem;">Belum ada artikel.</p>
    <?php else: ?>
    <div style="display:flex;flex-direction:column;gap:.6rem;">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $recentNews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
        <div style="display:flex;justify-content:space-between;align-items:center;gap:.5rem;padding:.5rem 0;border-bottom:1px solid var(--pw-border,rgba(255,255,255,.07));">
            <div>
                <div style="font-size:.83rem;font-weight:500;"><?php echo e(Str::limit($n->title, 45)); ?></div>
                <div style="font-size:.72rem;color:var(--pw-text-muted);"><?php echo e($n->author->truename ?: ($n->author->name ?? 'System')); ?></div>
            </div>
            <div style="display:flex;gap:.3rem;flex-shrink:0;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($n->is_active): ?> <span class="pw-badge pw-badge--success" style="font-size:.65rem;">Aktif</span>
                <?php else: ?> <span class="pw-badge pw-badge--danger" style="font-size:.65rem;">Draft</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($n->author_id === auth()->id()): ?>
                <a href="<?php echo e(route('gm.articles.edit', $n->id)); ?>" class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--ghost">Edit</a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </div>
    <div style="margin-top:.8rem;">
        <a href="<?php echo e(route('gm.articles.index')); ?>" class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--ghost">Lihat Semua →</a>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>

<?php $__env->startPush('styles'); ?>
<style>
.pw-adm-stat {
    background: var(--pw-bg-card, rgba(255,255,255,.04));
    border: 1px solid var(--pw-border, rgba(255,255,255,.08));
    border-radius: 10px;
    padding: 1.1rem 1.2rem;
    display: flex;
    flex-direction: column;
    gap: .3rem;
}
.pw-adm-stat__value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--pw-text, #e8dfc8);
    line-height: 1;
}
.pw-adm-stat__label {
    font-size: .73rem;
    color: var(--pw-text-muted, #7a7a9a);
    text-transform: uppercase;
    letter-spacing: .05em;
}
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.gm', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/pw-panel/resources/views/gm/dashboard.blade.php ENDPATH**/ ?>