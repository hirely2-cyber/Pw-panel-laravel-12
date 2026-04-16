<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <?php $__siteName = \App\Models\Setting::get('seo_title') ?: config('pw-config.server.name') ?: 'Perfect World'; ?>
    <title><?php echo $__env->yieldContent('title', 'Panel'); ?> — <?php echo e($__siteName); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Exo+2:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>!function(){var t=localStorage.getItem('pw-theme')||'light';document.documentElement.setAttribute('data-theme',t);}()</script>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="pw-body pw-admin-body">

    
    <aside class="pw-adm-sidebar" x-data="{ mobileOpen: false }" :class="{ 'is-open': mobileOpen }">

        
        <div class="pw-adm-sidebar__head">
            <a href="<?php echo e(route('home')); ?>" class="pw-adm-sidebar__brand">
                <svg viewBox="0 0 24 24" fill="none" width="22" height="22">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                </svg>
                <span><?php echo e($__siteName); ?></span>
            </a>
        </div>

        
        <div class="pw-panel-user">
            <div class="pw-panel-user__avatar"><?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?></div>
            <div class="pw-panel-user__info">
                <div class="pw-panel-user__name"><?php echo e(auth()->user()->name); ?></div>
                <div class="pw-panel-user__role">
                    <?php echo e(auth()->user()->role ?? 'Player'); ?>

                    &nbsp;·&nbsp;
                    <span style="color:var(--pw-gold-light)"><?php echo e(number_format(auth()->user()->money)); ?> Gold Points</span>
                </div>
            </div>
        </div>

        
        <nav class="pw-adm-nav">

            
            <div class="pw-adm-nav__section">Akun</div>
            <a href="<?php echo e(route('dashboard')); ?>" class="pw-adm-nav__item <?php echo e(request()->routeIs('dashboard') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><rect x="2" y="2" width="7" height="7" rx="1" stroke="currentColor" stroke-width="1.5"/><rect x="11" y="2" width="7" height="7" rx="1" stroke="currentColor" stroke-width="1.5"/><rect x="2" y="11" width="7" height="7" rx="1" stroke="currentColor" stroke-width="1.5"/><rect x="11" y="11" width="7" height="7" rx="1" stroke="currentColor" stroke-width="1.5"/></svg>
                Dashboard
            </a>
            <a href="<?php echo e(route('profile')); ?>" class="pw-adm-nav__item <?php echo e(request()->routeIs('profile') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><circle cx="10" cy="7" r="4" stroke="currentColor" stroke-width="1.5"/><path d="M3 17c0-3.314 3.134-6 7-6s7 2.686 7 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                Profil
            </a>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(config('pw-config.features.donate', true)): ?>
            <div class="pw-adm-nav__section">Transaksi</div>
            <a href="<?php echo e(route('cubi-shop')); ?>" class="pw-adm-nav__item <?php echo e(request()->routeIs('cubi-shop') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M10 2a5 5 0 100 10A5 5 0 0010 2zm0 2v3l2 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M3 16h14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                Donate / Top-up Gold Points
            </a>
            <a href="<?php echo e(route('donate.history')); ?>" class="pw-adm-nav__item <?php echo e(request()->routeIs('donate.history') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M4 6h12M4 10h8M4 14h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                Riwayat Transaksi
            </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(config('pw-config.features.shop', true) || config('pw-config.features.vote', true) || config('pw-config.features.voucher', true) || config('pw-config.features.service', true)): ?>
            <div class="pw-adm-nav__section">Fitur</div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(config('pw-config.features.shop', true)): ?>
            <a href="<?php echo e(route('shop')); ?>" class="pw-adm-nav__item <?php echo e(request()->routeIs('shop*') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M4 4h12l-1.5 7H5.5L4 4zM4 4L3 2m11.5 9l.5 3H5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="7" cy="17" r="1" stroke="currentColor" stroke-width="1.5"/><circle cx="15" cy="17" r="1" stroke="currentColor" stroke-width="1.5"/></svg>
                Cash Shop
            </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(config('pw-config.features.vote', true)): ?>
            <a href="<?php echo e(route('vote')); ?>" class="pw-adm-nav__item <?php echo e(request()->routeIs('vote*') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M10 2l1.8 5.5H17l-4.6 3.4 1.7 5.4L10 13.1l-4.1 3.2 1.7-5.4L3 7.5h5.2L10 2z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>
                Vote
            </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(config('pw-config.features.voucher', true)): ?>
            <a href="<?php echo e(route('voucher')); ?>" class="pw-adm-nav__item <?php echo e(request()->routeIs('voucher*') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><rect x="2" y="6" width="16" height="8" rx="1.5" stroke="currentColor" stroke-width="1.5"/><path d="M7 10h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                Redeem Voucher
            </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(config('pw-config.features.service', true)): ?>
            <a href="<?php echo e(route('services')); ?>" class="pw-adm-nav__item <?php echo e(request()->routeIs('services*') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><circle cx="10" cy="10" r="3" stroke="currentColor" stroke-width="1.5"/><path d="M10 2v2M10 16v2M2 10h2M16 10h2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                Layanan
            </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->isAdministrator() || auth()->user()->role === 'webadmin' || auth()->user()->isPartner() || auth()->user()->isGamemaster()): ?>
            <div class="pw-adm-nav__section" style="color: var(--pw-gold)">Panel Staff</div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->isAdministrator()): ?>
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="pw-adm-nav__item pw-adm-nav__item--staff <?php echo e(request()->routeIs('admin.*') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M10 2l1.8 5.5H17l-4.6 3.4 1.7 5.4L10 13.1l-4.1 3.2 1.7-5.4L3 7.5h5.2L10 2z" fill="rgba(232,184,75,.2)" stroke="var(--pw-gold)" stroke-width="1.5" stroke-linejoin="round"/></svg>
                Admin Panel
            </a>
            <?php elseif(auth()->user()->role === 'webadmin'): ?>
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="pw-adm-nav__item pw-adm-nav__item--staff <?php echo e(request()->routeIs('admin.*') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M10 2l1.8 5.5H17l-4.6 3.4 1.7 5.4L10 13.1l-4.1 3.2 1.7-5.4L3 7.5h5.2L10 2z" fill="rgba(59,130,246,.2)" stroke="#3b82f6" stroke-width="1.5" stroke-linejoin="round"/></svg>
                Web Admin
            </a>
            <?php elseif(auth()->user()->isPartner()): ?>
            <a href="<?php echo e(route('partner.dashboard')); ?>" class="pw-adm-nav__item pw-adm-nav__item--staff <?php echo e(request()->routeIs('partner.*') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M16 17v-1.5a3 3 0 00-3-3H7a3 3 0 00-3 3V17" fill="rgba(34,197,94,.2)" stroke="#22c55e" stroke-width="1.5" stroke-linecap="round"/><circle cx="10" cy="7" r="3" fill="rgba(34,197,94,.2)" stroke="#22c55e" stroke-width="1.5"/></svg>
                Partner Panel
            </a>
            <?php elseif(auth()->user()->isGamemaster()): ?>
            <a href="<?php echo e(route('gm.dashboard')); ?>" class="pw-adm-nav__item pw-adm-nav__item--staff <?php echo e(request()->routeIs('gm.*') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M10 2l1.8 5.5H17l-4.6 3.4 1.7 5.4L10 13.1l-4.1 3.2 1.7-5.4L3 7.5h5.2L10 2z" fill="rgba(99,226,255,.2)" stroke="var(--pw-info)" stroke-width="1.5" stroke-linejoin="round"/></svg>
                GM Panel
            </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        </nav>

        
        <div class="pw-adm-sidebar__foot">
            <a href="<?php echo e(route('home')); ?>" class="pw-adm-nav__item">
                <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M3 9.5L10 3l7 6.5V18H13v-5H7v5H3V9.5z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>
                Kembali ke Website
            </a>
            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="pw-adm-nav__item pw-adm-nav__item--danger">
                    <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M13 15l4-5-4-5M17 10H7M7 3H4a1 1 0 00-1 1v12a1 1 0 001 1h3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Logout
                </button>
            </form>
        </div>

    </aside>

    
    <div class="pw-adm-main">

        
        <header class="pw-adm-topbar">
            <div class="pw-adm-topbar__left">
                
                <button class="pw-panel-hamburger" @click="document.querySelector('.pw-adm-sidebar').classList.toggle('is-open')" style="display:none">
                    <svg viewBox="0 0 20 20" fill="none" width="20"><path d="M3 5h14M3 10h14M3 15h14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                </button>
                <h1 class="pw-adm-topbar__title"><?php echo $__env->yieldContent('title', 'Dashboard'); ?></h1>
            </div>
            <div class="pw-adm-topbar__right" style="display:flex;align-items:center;gap:.6rem">
                
                <button class="pw-theme-toggle" onclick="pwToggleTheme()" title="Toggle Light/Dark Mode" type="button">
                    <svg class="pw-theme-toggle__moon" viewBox="0 0 16 16" fill="none" width="12" height="12"><path d="M13.5 9.5a6 6 0 01-8-8 6 6 0 108 8z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <svg class="pw-theme-toggle__sun" viewBox="0 0 16 16" fill="none" width="12" height="12"><circle cx="8" cy="8" r="3" stroke="currentColor" stroke-width="1.4"/><path d="M8 1v2M8 13v2M1 8h2M13 8h2M3.2 3.2l1.4 1.4M11.4 11.4l1.4 1.4M11.4 3.2l-1.4 1.4M3.2 11.4l1.4-1.4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
                </button>
                
                <span style="width:1px;height:20px;background:var(--pw-border);flex-shrink:0;"></span>
                
                <div class="pw-gold-badge" style="font-size:.78rem">
                    <img src="<?php echo e(asset('images/gif_icon/web_coin.gif')); ?>" alt="Gold Points" width="14" height="14" style="vertical-align:middle;">
                    <?php echo e(number_format(auth()->user()->money)); ?>

                </div>
                
                <span style="width:1px;height:20px;background:var(--pw-border);flex-shrink:0;"></span>
                
                <span class="pw-adm-topbar__user">
                    <svg viewBox="0 0 20 20" fill="none" width="15" style="flex-shrink:0;opacity:.7"><circle cx="10" cy="7" r="4" stroke="currentColor" stroke-width="1.5"/><path d="M3 17c0-3.314 3.134-6 7-6s7 2.686 7 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    <span style="font-weight:600;color:var(--pw-text-light);"><?php echo e(auth()->user()->name); ?></span>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->isAdministrator()): ?>
                        <span class="pw-badge pw-badge--danger" style="font-size:.6rem;padding:.1rem .4rem">Admin</span>
                    <?php elseif(auth()->user()->isGamemaster()): ?>
                        <span class="pw-badge pw-badge--info" style="font-size:.6rem;padding:.1rem .4rem">GM</span>
                    <?php else: ?>
                        <span class="pw-badge" style="font-size:.6rem;padding:.1rem .4rem;background:rgba(200,151,42,.15);color:var(--pw-gold);">Player</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </span>
            </div>
        </header>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success') || session('error') || session('info')): ?>
        <div style="padding: 0 1.5rem; padding-top: 1rem;">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
            <div class="pw-adm-alert pw-adm-alert--success">✓ <?php echo e(session('success')); ?></div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
            <div class="pw-adm-alert pw-adm-alert--error">✕ <?php echo e(session('error')); ?></div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('info')): ?>
            <div class="pw-adm-alert" style="background:rgba(99,226,255,.08);border-color:rgba(99,226,255,.25);color:#63e2ff"><?php echo e(session('info')); ?></div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <div class="pw-adm-content">
            <?php echo $__env->yieldContent('content'); ?>
        </div>

    </div>

    <?php echo $__env->make('components.confirm-dialog', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /var/www/pw-panel/resources/views/layouts/panel.blade.php ENDPATH**/ ?>