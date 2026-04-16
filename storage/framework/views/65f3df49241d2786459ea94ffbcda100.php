<?php
    $__siteName = \App\Models\Setting::get('seo_title') ?: config('pw-config.server.name') ?: 'Perfect World';
?>

<?php $__env->startSection('title', 'Riwayat Pembelian — ' . $__siteName); ?>

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
        <h1 class="pw-page-hero__title">Riwayat Pembelian</h1>
        <p class="pw-page-hero__sub">Semua transaksi item yang pernah kamu lakukan</p>
        <nav class="pw-breadcrumb" aria-label="breadcrumb">
            <a href="<?php echo e(route('home')); ?>" class="pw-breadcrumb__item">
                <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true">
                    <path d="M2 7.5L8 2l6 5.5V14a1 1 0 01-1 1h-3.5v-3.5h-3V15H3a1 1 0 01-1-1V7.5z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/>
                </svg>
                Beranda
            </a>
            <span class="pw-breadcrumb__sep" aria-hidden="true">/</span>
            <a href="<?php echo e(route('shop')); ?>" class="pw-breadcrumb__item">Item Shop</a>
            <span class="pw-breadcrumb__sep" aria-hidden="true">/</span>
            <span class="pw-breadcrumb__item pw-breadcrumb__item--active">Riwayat</span>
        </nav>
    </div>
</div>


<section class="pw-section">
    <div class="pw-section__inner pw-section__inner--narrow">

        
        <div class="pw-shist-topbar">
            <a href="<?php echo e(route('shop')); ?>" class="pw-btn pw-btn--ghost pw-btn--sm">
                <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true">
                    <path d="M10 3L5 8l5 5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Kembali ke Shop
            </a>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$logs->isEmpty()): ?>
            <span class="pw-shist-topbar__total">
                <?php echo e($logs->total()); ?> transaksi
            </span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>

        
        <div class="pw-shist-card">
            
            <div class="pw-shist-card__icon">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($log->item && $log->item->image): ?>
                    <img src="<?php echo e(Storage::url($log->item->image)); ?>" alt="<?php echo e($log->item_name); ?>" loading="lazy">
                <?php else: ?>
                <svg viewBox="0 0 32 32" fill="none" width="22" aria-hidden="true">
                    <rect x="3" y="9" width="26" height="19" rx="2" stroke="#c8972a" stroke-width="1.3" opacity=".4"/>
                    <path d="M11 9V7a5 5 0 0110 0v2" stroke="#c8972a" stroke-width="1.3" stroke-linecap="round" opacity=".4"/>
                    <circle cx="16" cy="18" r="3" stroke="#c8972a" stroke-width="1.2" opacity=".4"/>
                </svg>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            
            <div class="pw-shist-card__info">
                <div class="pw-shist-card__name"><?php echo e($log->item_name); ?></div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($log->recipient): ?>
                <div class="pw-shist-card__meta">
                    <svg viewBox="0 0 16 16" fill="none" width="11" aria-hidden="true">
                        <circle cx="8" cy="5" r="3" stroke="currentColor" stroke-width="1.2"/>
                        <path d="M2 14c0-3.3 2.7-6 6-6s6 2.7 6 6" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                    </svg>
                    <?php echo e($log->recipient); ?>

                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            
            <div class="pw-shist-card__right">
                <div class="pw-shist-card__price">
                    <img src="<?php echo e(asset('images/gif_icon/web_coin.gif')); ?>" alt="Gold Points" width="14" height="14" style="vertical-align:middle;">
                    <?php echo e(number_format($log->price)); ?>

                    <span class="pw-shist-card__price-unit">Gold Points</span>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($log->quantity > 1): ?>
                <div class="pw-shist-card__qty">×<?php echo e($log->quantity); ?></div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <div class="pw-shist-card__date"><?php echo e($log->created_at->translatedFormat('d M Y, H:i')); ?></div>
                <?php
                    $statusMap = [
                        'success'  => ['label' => 'Berhasil',  'class' => 'pw-shist-badge--success'],
                        'pending'  => ['label' => 'Pending',   'class' => 'pw-shist-badge--pending'],
                        'failed'   => ['label' => 'Gagal',     'class' => 'pw-shist-badge--failed'],
                    ];
                    $s = $statusMap[$log->status] ?? ['label' => ucfirst($log->status ?? '-'), 'class' => 'pw-shist-badge--pending'];
                ?>
                <span class="pw-shist-badge <?php echo e($s['class']); ?>"><?php echo e($s['label']); ?></span>
            </div>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        <div class="pw-shop-empty">
            <svg viewBox="0 0 64 64" fill="none" width="52" aria-hidden="true">
                <rect x="8" y="18" width="48" height="38" rx="4" stroke="#c8972a" stroke-width="1.8" opacity=".4"/>
                <path d="M22 18v-4a10 10 0 0120 0v4" stroke="#c8972a" stroke-width="1.8" stroke-linecap="round" opacity=".5"/>
                <circle cx="32" cy="37" r="4" stroke="#c8972a" stroke-width="1.5" opacity=".5"/>
            </svg>
            <p>Belum ada riwayat pembelian.</p>
            <a href="<?php echo e(route('shop')); ?>" class="pw-btn pw-btn--gold pw-btn--sm" style="margin-top:.4rem;">Mulai Belanja</a>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($logs->hasPages()): ?>
        <div style="margin-top:1rem;">
            <?php echo e($logs->links()); ?>

        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/pw-panel/resources/views/front/shop/history.blade.php ENDPATH**/ ?>