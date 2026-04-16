<?php $__env->startSection('content'); ?>
<div style="min-height:calc(100vh - 200px);display:flex;align-items:center;justify-content:center;padding:2rem;">
    <div style="text-align:center;max-width:600px;animation:fadeIn .6s ease-out;">
        
        <div style="margin-bottom:2rem;position:relative;">
            <style>
                @keyframes bounce404 {
                    0%, 100% { transform: translateY(0); }
                    50% { transform: translateY(-20px); }
                }
                @keyframes fadeIn {
                    from { opacity: 0; transform: translateY(20px); }
                    to { opacity: 1; transform: translateY(0); }
                }
                .error-404 {
                    font-size: 8rem;
                    font-weight: 700;
                    font-family: 'Cinzel', serif;
                    background:
                        repeating-linear-gradient(90deg, rgba(255,255,255,.04) 0px, transparent 2px, transparent 4px),
                        radial-gradient(55% 125% at 46% 13%, #ffe4c2 0%, rgba(196,157,109,0) 100%),
                        linear-gradient(268deg, #e7dacb 0%, #c59768 24%, #7f4f2c 51%, #a66b42 78%, #c49d6d 100%);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                    background-clip: text;
                    animation: bounce404 3s ease-in-out infinite;
                    margin: 0;
                    line-height: 1;
                    filter: drop-shadow(0 0 12px rgba(184,134,11,.3));
                }
            </style>
            <h1 class="error-404">404</h1>
        </div>

        
        <h1 style="color:#c49d6d;font-size:2rem;font-weight:700;margin:1rem 0;font-family:'Cinzel',serif;text-shadow:0 1px 0 rgba(255,255,255,.15);">
            <?php echo e(__('main.error_404_title')); ?>

        </h1>

        
        <p style="color:var(--pw-text-muted);font-size:1rem;margin-bottom:1.5rem;line-height:1.6;">
            <?php echo e(__('main.error_404_subtitle')); ?>

        </p>

        
        <div style="background:rgba(196,157,109,.06);border-left:3px solid #a66b42;padding:1.2rem;margin:1.5rem 0;border-radius:4px;text-align:left;">
            <p style="color:var(--pw-text-muted);margin:0;font-size:0.9rem;">
                <strong style="color:#c49d6d;">⚠ Error 404</strong><br>
                <?php echo e(__('main.error_404_description')); ?>

            </p>
        </div>

        
        <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;margin-top:2rem;">
            <a href="<?php echo e(route('home')); ?>" class="pw-btn pw-btn--gold pw-btn--glow">
                <svg viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                <?php echo e(__('main.nav_home') ?? 'Kembali ke Beranda'); ?>

            </a>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(config('pw-config.features.ranking', true)): ?>
            <a href="<?php echo e(route('ranking')); ?>" class="pw-btn pw-btn--ghost">
                <svg viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path d="M7 21h10v-9h3L12 3 4 12h3v9z"/></svg>
                <?php echo e(__('main.nav_ranking') ?? 'Ranking'); ?>

            </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
            <a href="<?php echo e(route('dashboard')); ?>" class="pw-btn pw-btn--ghost">
                <svg viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
                <?php echo e(__('main.nav_dashboard') ?? 'Dashboard'); ?>

            </a>
            <?php else: ?>
            <a href="<?php echo e(route('login')); ?>" class="pw-btn pw-btn--ghost">
                <svg viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                <?php echo e(__('main.nav_login') ?? 'Login'); ?>

            </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <p style="color:var(--pw-text-muted);font-size:0.82rem;margin-top:2.5rem;line-height:1.8;">
            <?php echo e(__('main.error_404_help')); ?><br>
            <a href="https://wa.me/<?php echo e(\App\Models\Setting::get('social_whatsapp')); ?>" style="color:#c49d6d;text-decoration:none;" target="_blank" rel="noopener">
                <?php echo e(__('main.error_404_contact')); ?> →
            </a>
        </p>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/pw-panel/resources/views/errors/404.blade.php ENDPATH**/ ?>