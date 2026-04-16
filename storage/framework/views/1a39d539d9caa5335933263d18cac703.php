<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <?php $__siteName = \App\Models\Setting::get('seo_title') ?: config('pw-config.server.name') ?: 'Perfect World'; ?>
    <title><?php echo $__env->yieldContent('title', 'GM Panel'); ?> — <?php echo e($__siteName); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Exo+2:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>!function(){var t=localStorage.getItem('pw-theme')||'light';document.documentElement.setAttribute('data-theme',t);}()</script>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="pw-body pw-admin-body">

    
    <div class="pw-adm-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    
    <aside class="pw-adm-sidebar" id="adminSidebar">
        <div class="pw-adm-sidebar__head">
            <a href="<?php echo e(route('gm.dashboard')); ?>" class="pw-adm-sidebar__brand">
                <svg viewBox="0 0 24 24" fill="none" width="22" height="22"><path d="M12 2l2 6h6l-5 3.6 1.9 5.9L12 14l-4.9 3.5L9 11.6 4 8h6L12 2z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>
                <span>GM Panel</span>
            </a>
        </div>

        <nav class="pw-adm-nav">
            <div class="pw-adm-nav__section">Utama</div>
            <a href="<?php echo e(route('gm.dashboard')); ?>" class="pw-adm-nav__item <?php echo e(request()->routeIs('gm.dashboard') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><rect x="2" y="2" width="7" height="7" rx="1" stroke="currentColor" stroke-width="1.5"/><rect x="11" y="2" width="7" height="7" rx="1" stroke="currentColor" stroke-width="1.5"/><rect x="2" y="11" width="7" height="7" rx="1" stroke="currentColor" stroke-width="1.5"/><rect x="11" y="11" width="7" height="7" rx="1" stroke="currentColor" stroke-width="1.5"/></svg>
                Dashboard
            </a>

            <div class="pw-adm-nav__section">Konten</div>
            <a href="<?php echo e(route('gm.articles.index')); ?>" class="pw-adm-nav__item <?php echo e(request()->routeIs('gm.articles*') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M4 6h12M4 10h8M4 14h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                Artikel / Berita
            </a>

            <div class="pw-adm-nav__section">Sistem</div>
            <a href="<?php echo e(route('gm.server-control')); ?>" class="pw-adm-nav__item <?php echo e(request()->routeIs('gm.server-control*') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><rect x="2" y="4" width="16" height="12" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M6 8h8M6 12h5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                Server Control
            </a>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('gm.datafile-control')): ?>
            <a href="<?php echo e(route('gm.datafile-control')); ?>" class="pw-adm-nav__item <?php echo e(request()->routeIs('gm.datafile-control*') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M4 3h8l4 4v10H4z" stroke="currentColor" stroke-width="1.3"/><path d="M12 3v4h4" stroke="currentColor" stroke-width="1.3"/><path d="M7 12h6M7 15h6" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                DATAFILE Control
            </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <a href="<?php echo e(route('gm.ranking')); ?>" class="pw-adm-nav__item <?php echo e(request()->routeIs('gm.ranking*') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M4 14v2M8 10v6M12 7v9M16 4v12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                Ranking
            </a>

            <div class="pw-adm-nav__section">Pemain</div>
            <a href="<?php echo e(route('gm.members.index')); ?>" class="pw-adm-nav__item <?php echo e(request()->routeIs('gm.members*') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><circle cx="8" cy="7" r="3.5" stroke="currentColor" stroke-width="1.5"/><path d="M2 17c0-3 2.7-5.5 6-5.5s6 2.5 6 5.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                Cari Member
            </a>
            <a href="<?php echo e(route('gm.cubi-monitor')); ?>" class="pw-adm-nav__item <?php echo e(request()->routeIs('gm.cubi-monitor*') ? 'is-active' : ''); ?>">
                <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M10 2a8 8 0 110 16 8 8 0 010-16z" stroke="currentColor" stroke-width="1.5"/><path d="M10 6v4l3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                Cubi Monitor
            </a>
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
                <span class="pw-adm-topbar__user">
                    <span style="font-size:.68rem;background:#b89d4f22;color:#b89d4f;border:1px solid #b89d4f44;border-radius:4px;padding:1px 6px;margin-left:.3rem;">GM</span>
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
<?php /**PATH /var/www/pw-panel/resources/views/layouts/gm.blade.php ENDPATH**/ ?>