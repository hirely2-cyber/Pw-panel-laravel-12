<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <?php $__siteName = \App\Models\Setting::get('seo_title') ?: config('pw-config.server.name') ?: 'Perfect World'; ?>
    <title><?php echo $__env->yieldContent('title', 'Admin'); ?> — <?php echo e($__siteName); ?> Panel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Exo+2:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>!function(){var t=localStorage.getItem('pw-theme')||'light';document.documentElement.setAttribute('data-theme',t);}()</script>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    
    <?php $__favicon = \App\Models\Setting::get('site_favicon'); ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($__favicon): ?>
        <link rel="icon" href="<?php echo e(Storage::url($__favicon)); ?>">
    <?php else: ?>
        <link rel="icon" href="/favicon.ico">
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="pw-body pw-admin-body">

    
    <div class="pw-adm-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    
    <aside class="pw-adm-sidebar" id="adminSidebar">
        <div class="pw-adm-sidebar__head">
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="pw-adm-sidebar__brand">
                <svg viewBox="0 0 24 24" fill="none" width="22" height="22"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>
                <span>Admin Panel</span>
            </a>
        </div>

        <nav class="pw-adm-nav">

            
            <div class="pw-adm-nav__section">Utama</div>
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="pw-adm-nav__item <?php echo e(request()->routeIs('admin.dashboard') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><rect x="2" y="2" width="7" height="7" rx="1" stroke="currentColor" stroke-width="1.5"/><rect x="11" y="2" width="7" height="7" rx="1" stroke="currentColor" stroke-width="1.5"/><rect x="2" y="11" width="7" height="7" rx="1" stroke="currentColor" stroke-width="1.5"/><rect x="11" y="11" width="7" height="7" rx="1" stroke="currentColor" stroke-width="1.5"/></svg>
                Dashboard
            </a>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->isWebAdmin()): ?>
            <a href="<?php echo e(route('admin.members.index')); ?>" class="pw-adm-nav__item <?php echo e(request()->routeIs('admin.members*') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><circle cx="8" cy="7" r="3.5" stroke="currentColor" stroke-width="1.5"/><path d="M2 17c0-3 2.7-5.5 6-5.5s6 2.5 6 5.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M14 8l2 2 3-3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Members
            </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <div class="pw-adm-nav__section">Website</div>
            <a href="<?php echo e(route('admin.news.index')); ?>" class="pw-adm-nav__item <?php echo e(request()->routeIs('admin.news*') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M4 6h12M4 10h8M4 14h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                Berita
            </a>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->isAdministrator()): ?>
            <a href="<?php echo e(route('admin.settings')); ?>" class="pw-adm-nav__item <?php echo e(request()->routeIs('admin.settings.content') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><rect x="2" y="3" width="16" height="14" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M2 13l4-4 3 3 3-3 4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Konten Website
            </a>
            <a href="<?php echo e(route('admin.settings.panel')); ?>" class="pw-adm-nav__item <?php echo e(request()->routeIs('admin.settings.panel*') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><circle cx="10" cy="10" r="3" stroke="currentColor" stroke-width="1.5"/><path d="M10 2v1.5M10 16.5V18M2 10h1.5M16.5 10H18M4.2 4.2l1 1M14.8 14.8l1 1M15.8 4.2l-1 1M5.2 14.8l-1 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                Konfigurasi Panel
            </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <div class="pw-adm-nav__section">Fitur Panel</div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(config('pw-config.features.shop', true)): ?>
            <a href="<?php echo e(route('admin.shop.index')); ?>" class="pw-adm-nav__item <?php echo e(request()->routeIs('admin.shop*') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 5h12m-12 0a1 1 0 100 2 1 1 0 000-2zm10 0a1 1 0 100 2 1 1 0 000-2z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Item Shop
            </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(config('pw-config.features.cubi_shop', true)): ?>
            <a href="<?php echo e(route('admin.cubi-shop')); ?>" class="pw-adm-nav__item <?php echo e(request()->routeIs('admin.cubi-shop*') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M17 10l-4-7H7L3 10l4 7h6l4-7z" stroke="currentColor" stroke-width="1.3"/><circle cx="10" cy="10" r="2.5" stroke="currentColor" stroke-width="1.3"/></svg>
                Cubi Shop
            </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(config('pw-config.features.vote', true)): ?>
            <a href="<?php echo e(route('admin.vote.index')); ?>" class="pw-adm-nav__item <?php echo e(request()->routeIs('admin.vote*') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M10 2l1.8 5.5H17l-4.6 3.4 1.7 5.4L10 13.1l-4.1 3.2 1.7-5.4L3 7.5h5.2L10 2z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>
                Vote Sites
            </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(config('pw-config.features.voucher', true)): ?>
            <a href="<?php echo e(route('admin.voucher.index')); ?>" class="pw-adm-nav__item <?php echo e(request()->routeIs('admin.voucher*') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><rect x="2" y="6" width="16" height="8" rx="1.5" stroke="currentColor" stroke-width="1.5"/><path d="M7 10h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                Voucher
            </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(config('pw-config.features.service', true)): ?>
            <a href="<?php echo e(route('admin.service.index')); ?>" class="pw-adm-nav__item <?php echo e(request()->routeIs('admin.service*') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><circle cx="10" cy="10" r="3" stroke="currentColor" stroke-width="1.5"/><path d="M10 2v2M10 16v2M2 10h2M16 10h2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                Layanan
            </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <a href="<?php echo e(route('admin.events.index')); ?>" class="pw-adm-nav__item <?php echo e(request()->routeIs('admin.events*') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M4 3h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2z" stroke="currentColor" stroke-width="1.3"/><path d="M2 8h16" stroke="currentColor" stroke-width="1.3"/><path d="M7 1v4M13 1v4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/><path d="M6 12h2M9 12h2M12 12h2" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                Event
            </a>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(config('pw-config.features.ranking', true)): ?>
            <a href="<?php echo e(route('admin.ranking')); ?>" class="pw-adm-nav__item <?php echo e(request()->routeIs('admin.ranking*') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M4 14v2M8 10v6M12 7v9M16 4v12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                Ranking
            </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <div class="pw-adm-nav__section">Manajemen Game</div>
            <a href="<?php echo e(route('admin.server-control')); ?>" class="pw-adm-nav__item <?php echo e(request()->routeIs('admin.server-control*') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><rect x="2" y="3" width="16" height="12" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M6 16h8M10 15v1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><circle cx="7" cy="9" r="1.2" fill="currentColor" opacity=".6"/><circle cx="10" cy="9" r="1.2" fill="currentColor" opacity=".6"/><circle cx="13" cy="9" r="1.2" fill="currentColor" opacity=".6"/></svg>
                Server Control
            </a>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('admin.datafile-control')): ?>
            <a href="<?php echo e(route('admin.datafile-control')); ?>" class="pw-adm-nav__item <?php echo e(request()->routeIs('admin.datafile-control*') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M4 3h8l4 4v10H4z" stroke="currentColor" stroke-width="1.3"/><path d="M12 3v4h4" stroke="currentColor" stroke-width="1.3"/><path d="M7 12h6M7 15h6" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                DATAFILE Control
            </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->isAdministrator()): ?>
            <a href="<?php echo e(route('admin.game-config')); ?>" class="pw-adm-nav__item <?php echo e(request()->routeIs('admin.game-config*') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><circle cx="10" cy="10" r="3" stroke="currentColor" stroke-width="1.5"/><path d="M10 2v1.5M10 16.5V18M2 10h1.5M16.5 10H18M4.2 4.2l1 1M14.8 14.8l1 1M15.8 4.2l-1 1M5.2 14.8l-1 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                Game Config
            </a>
            <a href="<?php echo e(route('admin.backup-monitor')); ?>" class="pw-adm-nav__item <?php echo e(request()->routeIs('admin.backup-monitor*') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M10 3v9m0 0l-3-3m3 3l3-3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 14v1a2 2 0 002 2h8a2 2 0 002-2v-1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                Backup Monitor
            </a>
            <a href="<?php echo e(route('admin.mailer')); ?>" class="pw-adm-nav__item <?php echo e(request()->routeIs('admin.mailer*') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><rect x="2" y="5" width="16" height="12" rx="1.5" stroke="currentColor" stroke-width="1.5"/><path d="M2 7l8 5 8-5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Game Mailer
            </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <a href="<?php echo e(route('admin.broadcast')); ?>" class="pw-adm-nav__item <?php echo e(request()->routeIs('admin.broadcast*') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M3 10a7 7 0 1014 0A7 7 0 003 10z" stroke="currentColor" stroke-width="1.5"/><path d="M10 6v4l2.5 2.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                Broadcast
            </a>
            <a href="<?php echo e(route('admin.cubi-monitor')); ?>" class="pw-adm-nav__item <?php echo e(request()->routeIs('admin.cubi-monitor*') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M10 2a8 8 0 110 16 8 8 0 010-16z" stroke="currentColor" stroke-width="1.5"/><path d="M10 6v4l3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M14 2l2 2M6 2L4 4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                Cubi Monitor
            </a>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->isAdministrator()): ?>
            <div class="pw-adm-nav__section">Keuangan & Admin</div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(config('pw-config.features.donate', true)): ?>
            <a href="<?php echo e(route('admin.donate')); ?>" class="pw-adm-nav__item <?php echo e(request()->routeIs('admin.donate*') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M12 2C9.8 2 8 3.8 8 6c0 3.5 4 8 4 8s4-4.5 4-8c0-2.2-1.8-4-4-4zm0 5.5a1.5 1.5 0 110-3 1.5 1.5 0 010 3z" stroke="currentColor" stroke-width="1.3"/><path d="M4 14l-2 4h16l-2-4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Donate/Invoice
            </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <a href="<?php echo e(route('admin.referral')); ?>" class="pw-adm-nav__item <?php echo e(request()->routeIs('admin.referral') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M3 5h14M3 10h14M3 15h9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                Riwayat Referral
            </a>
            <a href="<?php echo e(route('admin.referral.partners')); ?>" class="pw-adm-nav__item <?php echo e(request()->routeIs('admin.referral.partners*') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M10 2v6M13 5H7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M15 10a5 5 0 11-10 0" stroke="currentColor" stroke-width="1.5"/><circle cx="5" cy="15" r="2.5" stroke="currentColor" stroke-width="1.3"/><circle cx="15" cy="15" r="2.5" stroke="currentColor" stroke-width="1.3"/></svg>
                Pengaturan Partner
            </a>
            <a href="<?php echo e(route('admin.bonus-claims')); ?>" class="pw-adm-nav__item <?php echo e(request()->routeIs('admin.bonus-claims*') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M10 2v12M6 10l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 16h14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                Cairkan Bonus
            </a>
            <a href="<?php echo e(route('admin.gm.index')); ?>" class="pw-adm-nav__item <?php echo e(request()->routeIs('admin.gm*') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M10 2l1.5 4.5H16l-3.7 2.7 1.4 4.3L10 11l-3.7 2.5 1.4-4.3L4 6.5h4.5L10 2z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>
                Manajemen Staff
            </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        </nav>

        <div class="pw-adm-sidebar__foot">
            <a href="<?php echo e(route('home')); ?>" class="pw-adm-nav__item" target="_blank">
                <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M10 2L2 7l8 5 8-5-8-5zM2 12l8 5 8-5" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>
                Lihat Website
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
                <button class="pw-adm-hamburger" onclick="toggleSidebar()" aria-label="Toggle sidebar">
                    <svg viewBox="0 0 24 24" fill="none" width="22" height="22"><path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                </button>
                <h1 class="pw-adm-topbar__title"><?php echo $__env->yieldContent('title', 'Dashboard'); ?></h1>
            </div>
            <div class="pw-adm-topbar__right">
                <button class="pw-theme-toggle" onclick="pwToggleTheme()" title="Toggle Light/Dark Mode" type="button">
                    <svg class="pw-theme-toggle__moon" viewBox="0 0 16 16" fill="none" width="12" height="12"><path d="M13.5 9.5a6 6 0 01-8-8 6 6 0 108 8z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <svg class="pw-theme-toggle__sun" viewBox="0 0 16 16" fill="none" width="12" height="12"><circle cx="8" cy="8" r="3" stroke="currentColor" stroke-width="1.4"/><path d="M8 1v2M8 13v2M1 8h2M13 8h2M3.2 3.2l1.4 1.4M11.4 11.4l1.4 1.4M11.4 3.2l-1.4 1.4M3.2 11.4l1.4-1.4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
                </button>
                <span style="width:1px;height:20px;background:var(--pw-border);flex-shrink:0;"></span>
                <span class="pw-adm-topbar__user">
                    <svg viewBox="0 0 20 20" fill="none" width="15" style="flex-shrink:0;opacity:.7"><circle cx="10" cy="7" r="4" stroke="currentColor" stroke-width="1.5"/><path d="M3 17c0-3.314 3.134-6 7-6s7 2.686 7 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    <span style="font-weight:600;color:var(--pw-text-light);"><?php echo e(auth()->user()->name); ?></span>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->role === 'admin'): ?>
                    <span style="font-size:.6rem;background:rgba(239,68,68,.15);color:#f87171;border:1px solid rgba(239,68,68,.3);border-radius:4px;padding:1px 6px;">Admin</span>
                    <?php elseif(auth()->user()->role === 'webadmin'): ?>
                    <span style="font-size:.6rem;background:#3b82f622;color:#3b82f6;border:1px solid #3b82f644;border-radius:4px;padding:1px 6px;">Web Admin</span>
                    <?php elseif(auth()->user()->role === 'gm'): ?>
                    <span style="font-size:.6rem;background:#b89d4f22;color:#b89d4f;border:1px solid #b89d4f44;border-radius:4px;padding:1px 6px;">GM</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </span>
            </div>
        </header>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success') || session('error')): ?>
        <div style="padding: 0 1.5rem;">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
            <div class="pw-adm-alert pw-adm-alert--success">✓ <?php echo e(session('success')); ?></div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
            <div class="pw-adm-alert pw-adm-alert--error">✕ <?php echo e(session('error')); ?></div>
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
<?php /**PATH /var/www/pw-panel/resources/views/layouts/admin.blade.php ENDPATH**/ ?>