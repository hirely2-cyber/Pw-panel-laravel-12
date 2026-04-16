<?php
    $__siteName = \App\Models\Setting::get('seo_title') ?: config('pw-config.server.name') ?: 'Perfect World';
?>

<?php $__env->startSection('title', 'Riwayat Transaksi — ' . $__siteName); ?>

<?php $__env->startSection('content'); ?>


<div class="pw-page-hero">
    <div class="pw-page-hero__bg" aria-hidden="true"></div>
    <div class="pw-page-hero__inner">
        <div class="pw-page-hero__ornament" aria-hidden="true">
            <svg viewBox="0 0 160 20" fill="none" width="140">
                <line x1="0" y1="10" x2="55" y2="10" stroke="#c8972a" stroke-width="1"/>
                <path d="M65 3 L75 10 L65 17 L55 10 Z" fill="#c8972a" opacity=".5"/>
                <path d="M75 3 L85 10 L75 17 L65 10 Z" fill="#c8972a"/>
                <path d="M85 3 L95 10 L85 17 L75 10 Z" fill="#c8972a" opacity=".5"/>
                <line x1="95" y1="10" x2="150" y2="10" stroke="#c8972a" stroke-width="1"/>
            </svg>
        </div>
        <h1 class="pw-page-hero__title">Riwayat Transaksi</h1>
        <p class="pw-page-hero__sub">Semua riwayat top-up Gold Points kamu</p>
        <nav class="pw-breadcrumb" aria-label="breadcrumb">
            <a href="<?php echo e(route('home')); ?>" class="pw-breadcrumb__item">
                <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true">
                    <path d="M2 7.5L8 2l6 5.5V14a1 1 0 01-1 1h-3.5v-3.5h-3V15H3a1 1 0 01-1-1V7.5z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/>
                </svg>
                Beranda
            </a>
            <span class="pw-breadcrumb__sep" aria-hidden="true">/</span>
            <a href="<?php echo e(route('donate')); ?>" class="pw-breadcrumb__item">Top-up Gold Points</a>
            <span class="pw-breadcrumb__sep" aria-hidden="true">/</span>
            <span class="pw-breadcrumb__item pw-breadcrumb__item--active">Riwayat</span>
        </nav>
    </div>
</div>


<section class="pw-section">
    <div class="pw-section__inner pw-section__inner--narrow">

        <div class="pw-shist-topbar">
            <a href="<?php echo e(route('donate')); ?>" class="pw-btn pw-btn--ghost pw-btn--sm">
                <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true">
                    <path d="M10 3L5 8l5 5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Top-up Gold Points
            </a>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$invoices->isEmpty()): ?>
            <span class="pw-shist-topbar__total"><?php echo e($invoices->total()); ?> transaksi</span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($invoices->isEmpty()): ?>
        <div class="pw-shop-empty">
            <svg viewBox="0 0 64 64" fill="none" width="52" aria-hidden="true">
                <rect x="8" y="18" width="48" height="38" rx="4" stroke="#c8972a" stroke-width="1.8" opacity=".4"/>
                <path d="M22 18v-4a10 10 0 0120 0v4" stroke="#c8972a" stroke-width="1.8" stroke-linecap="round" opacity=".5"/>
                <circle cx="32" cy="37" r="4" stroke="#c8972a" stroke-width="1.5" opacity=".5"/>
            </svg>
            <p>Belum ada riwayat transaksi.</p>
            <a href="<?php echo e(route('donate')); ?>" class="pw-btn pw-btn--gold pw-btn--sm" style="margin-top:.4rem;">Top-up Gold Points Sekarang</a>
        </div>
        <?php else: ?>
        <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:.83rem;">
            <thead>
                <tr style="border-bottom:1px solid rgba(255,255,255,.08);">
                    <th style="padding:.6rem .8rem;text-align:left;color:var(--pw-text-muted);font-weight:600;font-size:.72rem;text-transform:uppercase;letter-spacing:.04em;white-space:nowrap;">#</th>
                    <th style="padding:.6rem .8rem;text-align:left;color:var(--pw-text-muted);font-weight:600;font-size:.72rem;text-transform:uppercase;letter-spacing:.04em;white-space:nowrap;">Invoice</th>
                    <th style="padding:.6rem .8rem;text-align:right;color:var(--pw-text-muted);font-weight:600;font-size:.72rem;text-transform:uppercase;letter-spacing:.04em;white-space:nowrap;">Gold Points</th>
                    <th style="padding:.6rem .8rem;text-align:right;color:var(--pw-text-muted);font-weight:600;font-size:.72rem;text-transform:uppercase;letter-spacing:.04em;white-space:nowrap;">Nominal</th>
                    <th style="padding:.6rem .8rem;text-align:center;color:var(--pw-text-muted);font-weight:600;font-size:.72rem;text-transform:uppercase;letter-spacing:.04em;white-space:nowrap;">Status</th>
                    <th style="padding:.6rem .8rem;text-align:left;color:var(--pw-text-muted);font-weight:600;font-size:.72rem;text-transform:uppercase;letter-spacing:.04em;white-space:nowrap;">Tanggal</th>
                    <th style="padding:.6rem .8rem;"></th>
                </tr>
            </thead>
            <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <?php
                $map = [
                    'paid'    => ['label' => 'Sukses',     'class' => 'pw-shist-badge--success'],
                    'pending' => ['label' => 'Menunggu',   'class' => 'pw-shist-badge--pending'],
                    'expired' => ['label' => 'Waktu Habis','class' => 'pw-shist-badge--expired'],
                    'failed'  => ['label' => 'Gagal',      'class' => 'pw-shist-badge--failed'],
                ];
                $s = $map[$inv->status] ?? ['label' => ucfirst($inv->status), 'class' => 'pw-shist-badge--pending'];
            ?>
            <tr style="border-bottom:1px solid rgba(255,255,255,.05);transition:background .12s;" onmouseover="this.style.background='rgba(255,255,255,.03)'" onmouseout="this.style.background=''">
                <td style="padding:.6rem .8rem;color:var(--pw-text-muted);"><?php echo e($invoices->firstItem() + $i); ?></td>
                <td style="padding:.6rem .8rem;font-family:monospace;font-size:.78rem;color:var(--pw-text-muted);"><?php echo e($inv->invoice_number); ?></td>
                <td style="padding:.6rem .8rem;text-align:right;font-weight:700;color:var(--pw-gold);">+<?php echo e(number_format($inv->gold_amount)); ?></td>
                <td style="padding:.6rem .8rem;text-align:right;font-weight:600;color:var(--pw-text);">Rp <?php echo e(number_format($inv->unique_amount)); ?></td>
                <td style="padding:.6rem .8rem;text-align:center;">
                    <span class="pw-shist-badge <?php echo e($s['class']); ?>"><?php echo e($s['label']); ?></span>
                </td>
                <td style="padding:.6rem .8rem;color:var(--pw-text-muted);white-space:nowrap;font-size:.78rem;"><?php echo e($inv->created_at->format('d M Y, H:i')); ?></td>
                <td style="padding:.6rem .8rem;text-align:right;">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($inv->status === 'pending'): ?>
                    <a href="<?php echo e(route('donate.invoice.show', $inv->invoice_number)); ?>"
                       class="pw-btn pw-btn--gold pw-btn--sm">Bayar</a>
                    <?php elseif($inv->status === 'paid'): ?>
                    <a href="<?php echo e(route('donate.invoice.show', $inv->invoice_number)); ?>"
                       class="pw-btn pw-btn--ghost pw-btn--sm">Detail</a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </td>
            </tr>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </tbody>
        </table>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($invoices->hasPages()): ?>
        <div style="margin-top:1rem;"><?php echo e($invoices->links()); ?></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/pw-panel/resources/views/front/donate/history.blade.php ENDPATH**/ ?>