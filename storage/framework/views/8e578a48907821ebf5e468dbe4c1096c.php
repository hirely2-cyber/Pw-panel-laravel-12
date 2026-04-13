<?php $__env->startSection('content'); ?>
<div style="min-height:calc(100vh - 200px);display:flex;align-items:center;justify-content:center;padding:2rem;">
    <div style="text-align:center;max-width:600px;animation:fadeIn .6s ease-out;">
        
        <div style="margin-bottom:3rem;position:relative;">
            <style>
                @keyframes bounce {
                    0%, 100% { transform: translateY(0); }
                    50% { transform: translateY(-20px); }
                }
                @keyframes fadeIn {
                    from { opacity: 0; transform: translateY(20px); }
                    to { opacity: 1; transform: translateY(0); }
                }
                @keyframes glow {
                    0%, 100% { text-shadow: 0 0 10px rgba(232, 184, 75, 0.5), 0 0 20px rgba(232, 184, 75, 0.3); }
                    50% { text-shadow: 0 0 20px rgba(232, 184, 75, 0.8), 0 0 30px rgba(232, 184, 75, 0.5); }
                }
                .error-404 {
                    font-size: 8rem;
                    font-weight: 700;
                    font-family: 'Cinzel', serif;
                    background: linear-gradient(135deg, #e8b84b 0%, #9a6820 100%);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                    background-clip: text;
                    animation: glow 2s ease-in-out infinite, bounce 3s ease-in-out infinite;
                    margin: 0;
                    line-height: 1;
                }
            </style>
            <h1 class="error-404">404</h1>
        </div>

        
        <h1 style="color:var(--pw-gold);font-size:3.5rem;font-weight:700;margin:1rem 0;font-family:'Cinzel',serif;text-shadow:0 0 10px rgba(232, 184, 75, 0.5);">
            <?php echo e(__('main.error_404_title')); ?>

        </h1>

        
        <p style="color:var(--pw-text-muted);font-size:1.1rem;margin-bottom:1.5rem;line-height:1.6;">
            <?php echo e(__('main.error_404_subtitle')); ?>

        </p>

        
        <div style="background:rgba(232, 184, 75, 0.05);border-left:3px solid var(--pw-gold);padding:1.5rem;margin:2rem 0;border-radius:4px;text-align:left;">
            <p style="color:var(--pw-text-muted);margin:0;font-size:0.95rem;">
                <strong style="color:var(--pw-gold);">⚠ Error 404</strong><br>
                <?php echo e(__('main.error_404_description')); ?>

            </p>
        </div>

        
        <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;margin-top:2.5rem;">
            <a href="<?php echo e(route('home')); ?>" style="
                display:inline-flex;
                align-items:center;
                gap:.5rem;
                padding:0.8rem 2rem;
                background:linear-gradient(135deg, #e8b84b 0%, #9a6820 100%);
                color:#141414;
                text-decoration:none;
                border-radius:6px;
                font-weight:600;
                font-size:0.95rem;
                transition:all .3s ease;
                box-shadow:0 4px 15px rgba(232, 184, 75, 0.3);
            " onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 20px rgba(232, 184, 75, 0.4)';" 
               onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 15px rgba(232, 184, 75, 0.3)';">
                <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
                    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                </svg>
                <?php echo e(__('main.nav_home') ?? 'Kembali ke Beranda'); ?>

            </a>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(config('pw-config.features.ranking', true)): ?>
            <a href="<?php echo e(route('ranking')); ?>" style="
                display:inline-flex;
                align-items:center;
                gap:.5rem;
                padding:0.8rem 2rem;
                background:transparent;
                color:var(--pw-gold);
                border:2px solid var(--pw-gold);
                text-decoration:none;
                border-radius:6px;
                font-weight:600;
                font-size:0.95rem;
                transition:all .3s ease;
            " onmouseover="this.style.background='rgba(232, 184, 75, 0.1)';this.style.transform='translateY(-2px)';" 
               onmouseout="this.style.background='transparent';this.style.transform='translateY(0)';">
                <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/>
                </svg>
                <?php echo e(__('main.nav_ranking') ?? 'Ranking'); ?>

            </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
            <a href="<?php echo e(route('dashboard')); ?>" style="
                display:inline-flex;
                align-items:center;
                gap:.5rem;
                padding:0.8rem 2rem;
                background:transparent;
                color:var(--pw-gold);
                border:2px solid var(--pw-gold);
                text-decoration:none;
                border-radius:6px;
                font-weight:600;
                font-size:0.95rem;
                transition:all .3s ease;
            " onmouseover="this.style.background='rgba(232, 184, 75, 0.1)';this.style.transform='translateY(-2px)';" 
               onmouseout="this.style.background='transparent';this.style.transform='translateY(0)';">
                <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
                    <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
                </svg>
                <?php echo e(__('main.nav_dashboard') ?? 'Dashboard'); ?>

            </a>
            <?php else: ?>
            <a href="<?php echo e(route('login')); ?>" style="
                display:inline-flex;
                align-items:center;
                gap:.5rem;
                padding:0.8rem 2rem;
                background:transparent;
                color:var(--pw-gold);
                border:2px solid var(--pw-gold);
                text-decoration:none;
                border-radius:6px;
                font-weight:600;
                font-size:0.95rem;
                transition:all .3s ease;
            " onmouseover="this.style.background='rgba(232, 184, 75, 0.1)';this.style.transform='translateY(-2px)';" 
               onmouseout="this.style.background='transparent';this.style.transform='translateY(0)';">
                <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
                <?php echo e(__('main.nav_login') ?? 'Login'); ?>

            </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <p style="color:var(--pw-text-muted);font-size:0.85rem;margin-top:3rem;line-height:1.8;">
            <?php echo e(__('main.error_404_help')); ?><br>
            <a href="https://wa.me/<?php echo e(\App\Models\Setting::get('social_whatsapp')); ?>" style="color:var(--pw-gold);text-decoration:none;" target="_blank" rel="noopener">
                <?php echo e(__('main.error_404_contact')); ?> →
            </a>
        </p>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/pw-panel/resources/views/errors/404.blade.php ENDPATH**/ ?>