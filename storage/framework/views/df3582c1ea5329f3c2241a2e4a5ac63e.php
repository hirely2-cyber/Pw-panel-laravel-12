<?php
    $__siteName = \App\Models\Setting::get('seo_title') ?: config('pw-config.server.name') ?: 'Perfect World';
?>

<?php $__env->startSection('title', 'Top-up Gold Points — ' . $__siteName); ?>
<?php $__env->startSection('meta_description', 'Top-up Gold Points dengan QRIS, bayar otomatis & instan.'); ?>

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
        <h1 class="pw-page-hero__title">Top-up Gold Points</h1>
        <p class="pw-page-hero__sub">Pilih paket Gold Points & bayar via QRIS — dikonfirmasi otomatis</p>
        <nav class="pw-breadcrumb" aria-label="breadcrumb">
            <a href="<?php echo e(route('home')); ?>" class="pw-breadcrumb__item">
                <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true">
                    <path d="M2 7.5L8 2l6 5.5V14a1 1 0 01-1 1h-3.5v-3.5h-3V15H3a1 1 0 01-1-1V7.5z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/>
                </svg>
                Beranda
            </a>
            <span class="pw-breadcrumb__sep" aria-hidden="true">/</span>
            <span class="pw-breadcrumb__item pw-breadcrumb__item--active">Top-up Gold Points</span>
        </nav>
    </div>
</div>


<section class="pw-section">
    <div class="pw-section__inner pw-section__inner--narrow">

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
        <div class="pw-alert pw-alert--error" role="alert">
            <svg viewBox="0 0 16 16" fill="none" width="16" aria-hidden="true"><circle cx="8" cy="8" r="7" stroke="currentColor" stroke-width="1.3"/><path d="M8 5v3M8 10.5v.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
            <?php echo e(session('error')); ?>

        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <form method="POST" action="<?php echo e(route('donate.invoice')); ?>" id="donate-form">
            <?php echo csrf_field(); ?>

            
            <div class="pw-shop-balance" style="margin-bottom:2rem;">
                <div class="pw-shop-balance__left">
                    <div class="pw-shop-balance__label">Gold Points Kamu Saat Ini</div>
                    <div class="pw-shop-balance__amount">
                        <img src="<?php echo e(asset('images/gif_icon/web_coin.gif')); ?>" alt="Gold Points" width="22" style="vertical-align:middle;">
                        <?php echo e(number_format(auth()->user()->money)); ?>

                        <span class="pw-shop-balance__unit">Gold Points</span>
                    </div>
                </div>
                <div class="pw-shop-balance__actions">
                    <a href="<?php echo e(route('donate.history')); ?>" class="pw-btn pw-btn--ghost pw-btn--sm">
                        <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true"><rect x="2" y="3" width="12" height="11" rx="1.5" stroke="currentColor" stroke-width="1.2"/><path d="M5 1v4M11 1v4M2 7h12" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/></svg>
                        Riwayat Transaksi
                    </a>
                </div>
            </div>

            
            <div class="pw-donate-section-title">
                <img src="<?php echo e(asset('images/gif_icon/web_coin.gif')); ?>" alt="" width="20">
                Pilih Paket Gold Points
            </div>

            
            <div class="pw-donate-grid" x-data="{ selected: null }">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $pkg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <label class="pw-donate-pkg-wrap">
                    <input type="radio" name="package" value="<?php echo e($key); ?>" required
                           id="pkg-<?php echo e($key); ?>"
                           x-model="selected"
                           @change="selected = '<?php echo e($key); ?>'">
                    <div class="pw-donate-pkg" :class="selected === '<?php echo e($key); ?>' ? 'is-selected' : ''">
                        <div class="pw-donate-pkg__coin" aria-hidden="true">
                            <img src="<?php echo e(asset('images/gif_icon/web_coin.gif')); ?>" alt="Gold Points" width="36">
                        </div>
                        <div class="pw-donate-pkg__row">
                            <span class="pw-donate-pkg__row-label">Donasi Gold Points</span>
                            <span class="pw-donate-pkg__row-val"><?php echo e(number_format($pkg['gold'])); ?></span>
                        </div>
                        <div class="pw-donate-pkg__row pw-donate-pkg__row--bonus">
                            <span class="pw-donate-pkg__row-label">Bonus</span>
                            <span class="pw-donate-pkg__row-bonus">+<?php echo e(number_format($pkg['bonus'])); ?> Gold Points</span>
                        </div>
                        <div class="pw-donate-pkg__row pw-donate-pkg__row--total">
                            <span class="pw-donate-pkg__row-label">Total Diterima</span>
                            <span class="pw-donate-pkg__row-total"><?php echo e(number_format($pkg['gold'] + $pkg['bonus'])); ?> Gold Points</span>
                        </div>
                        <div class="pw-donate-pkg__row" style="margin-top:.9rem;padding-top:.75rem;border-top:1px solid rgba(255,255,255,.06);">
                            <span class="pw-donate-pkg__row-label">Harga</span>
                            <span class="pw-donate-pkg__row-price">Rp <?php echo e(number_format($pkg['price_idr'])); ?></span>
                        </div>
                        <div class="pw-donate-pkg__check" aria-hidden="true">
                            <svg viewBox="0 0 14 14" fill="none" width="13"><circle cx="7" cy="7" r="6" fill="#c8972a"/><path d="M4 7l2 2 4-4" stroke="#1a1008" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                    </div>
                </label>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>

            
            <div class="pw-donate-section-title" style="margin-top:2rem;">
                <svg viewBox="0 0 20 20" fill="none" width="20" aria-hidden="true"><rect x="2" y="5" width="16" height="11" rx="2" stroke="#c8972a" stroke-width="1.3"/><path d="M2 9h16" stroke="#c8972a" stroke-width="1.3"/></svg>
                Metode Pembayaran
            </div>
            <div class="pw-donate-grid pw-donate-channel-grid" x-data="{ channel: '' }" style="grid-template-columns:1fr 1fr;gap:.75rem;">

                
                <label class="pw-donate-pkg-wrap">
                    <input type="radio" name="channel_type" value="qris" x-model="channel" required>
                    <div class="pw-donate-pkg pw-donate-channel" :class="channel === 'qris' ? 'is-selected' : ''">
                        <img src="<?php echo e(asset('images/qris.webp')); ?>" alt="QRIS" style="width:52px;height:auto;flex-shrink:0;object-fit:contain;">
                        <div class="pw-donate-channel__info">
                            <div class="pw-donate-channel__name">QRIS</div>
                            <div class="pw-donate-channel__desc">GoPay, OVO, ShopeePay, BCA, Mandiri, semua m-banking</div>
                        </div>
                        <div class="pw-donate-pkg__check" aria-hidden="true">
                            <svg viewBox="0 0 14 14" fill="none" width="13"><circle cx="7" cy="7" r="6" fill="#c8972a"/><path d="M4 7l2 2 4-4" stroke="#1a1008" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                    </div>
                </label>

                
                <label class="pw-donate-pkg-wrap">
                    <input type="radio" name="channel_type" value="dana" x-model="channel" required>
                    <div class="pw-donate-pkg pw-donate-channel" :class="channel === 'dana' ? 'is-selected' : ''">
                        <img src="<?php echo e(asset('images/dana.webp')); ?>" alt="DANA" style="width:52px;height:auto;flex-shrink:0;object-fit:contain;">
                        <div class="pw-donate-channel__info">
                            <div class="pw-donate-channel__name">DANA</div>
                            <div class="pw-donate-channel__desc">Transfer langsung ke nomor DANA</div>
                        </div>
                        <div class="pw-donate-pkg__check" aria-hidden="true">
                            <svg viewBox="0 0 14 14" fill="none" width="13"><circle cx="7" cy="7" r="6" fill="#c8972a"/><path d="M4 7l2 2 4-4" stroke="#1a1008" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                    </div>
                </label>

            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['channel_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <p style="color:#fca5a5;font-size:.8rem;margin-top:.4rem;"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <div style="margin-top:1.8rem;">
                <button type="submit" class="pw-btn pw-btn--gold pw-btn--lg" style="width:100%;justify-content:center;">
                    <img src="<?php echo e(asset('images/gif_icon/web_coin.gif')); ?>" alt="" width="16" style="vertical-align:middle;">
                    Donate Gold Points
                </button>
            </div>

            
            <div class="pw-donate-info-bar">
                
                <div class="pw-donate-info-card">
                    <div class="pw-donate-info-card__title">
                        <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true">
                            <circle cx="8" cy="8" r="7" stroke="#c8972a" stroke-width="1.2"/>
                            <path d="M8 7v4M8 5v.5" stroke="#c8972a" stroke-width="1.3" stroke-linecap="round"/>
                        </svg>
                        Cara Pembayaran
                    </div>
                    <ol class="pw-donate-steps">
                        <li>Pilih paket Gold yang diinginkan</li>
                        <li>Klik <strong>"Donate Gold"</strong> untuk mendapatkan QRIS</li>
                        <li>Scan QR dengan e-wallet (GoPay, OVO, Dana, BCA, dll)</li>
                        <li>Bayar <strong>tepat sesuai nominal</strong> yang tertera</li>
                        <li>Gold masuk otomatis dalam hitungan detik</li>
                    </ol>
                </div>

                
                <div class="pw-donate-info-card">
                    <div class="pw-donate-info-card__title">
                        <svg viewBox="0 0 24 24" fill="none" width="13" aria-hidden="true">
                            <path d="M3 3h7v7H3zM14 3h7v7h-7zM3 14h7v7H3z" stroke="#c8972a" stroke-width="1.5" stroke-linejoin="round"/>
                            <rect x="14" y="14" width="3" height="3" fill="#c8972a" opacity=".7"/>
                            <rect x="18" y="14" width="3" height="3" fill="#c8972a" opacity=".4"/>
                            <rect x="14" y="18" width="3" height="3" fill="#c8972a" opacity=".4"/>
                            <rect x="18" y="18" width="3" height="3" fill="#c8972a"/>
                        </svg>
                        Metode Pembayaran
                    </div>
                    <p class="pw-donate-info-card__text">
                        Pembayaran menggunakan <strong>QRIS</strong> — mendukung semua e-wallet & m-banking (GoPay, OVO, Dana, ShopeePay, BCA, Mandiri, dll).
                        Konfirmasi otomatis, Gold langsung masuk.
                    </p>
                </div>
            </div>

        </form>

    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/pw-panel/resources/views/front/donate/index.blade.php ENDPATH**/ ?>