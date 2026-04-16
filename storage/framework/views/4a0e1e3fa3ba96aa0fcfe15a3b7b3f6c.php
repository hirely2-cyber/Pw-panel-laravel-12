<?php $__env->startSection('title', __("main.auth_forgot_title")); ?>

<?php
    $__siteName = \App\Models\Setting::get('seo_title') ?: config('pw-config.server.name') ?: 'Perfect World';
    $__authLogo = \App\Models\Setting::get('site_footer_logo');
    $__authBg = \App\Models\Setting::get('site_auth_bg') ?: \App\Models\Setting::get('site_hero_bg');
?>

<?php $__env->startSection('content'); ?>
<div class="pw-auth">
    <div class="pw-auth__bg" style="background-image:url('<?php echo e($__authBg ? \Illuminate\Support\Facades\Storage::url($__authBg) : ''); ?>');<?php echo e($__authBg ? '' : 'background-image:none;'); ?>"></div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($__authLogo): ?>
    <div style="display:flex;justify-content:center;margin-bottom:1rem;position:relative;z-index:2;">
        <img src="<?php echo e(\Illuminate\Support\Facades\Storage::url($__authLogo)); ?>" alt="<?php echo e($__siteName); ?>"
             style="max-height:108px;width:auto;object-fit:contain;filter:drop-shadow(0 4px 10px rgba(0,0,0,.35));">
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="pw-auth__box">
        <span class="pw-auth__corner pw-auth__corner--tl"></span>
        <span class="pw-auth__corner pw-auth__corner--tr"></span>
        <span class="pw-auth__corner pw-auth__corner--bl"></span>
        <span class="pw-auth__corner pw-auth__corner--br"></span>

        <div class="pw-auth__server"><?php echo e($__siteName); ?></div>
        <div class="pw-auth__heading">
            <h1><?php echo e(__('main.auth_forgot_title')); ?></h1>
            <div class="pw-auth__heading-line"><span class="pw-auth__heading-gem"></span></div>
        </div>

        <p class="pw-auth__desc"><?php echo e(__('main.auth_forgot_subtitle')); ?></p>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
        <div class="pw-auth__alert pw-auth__alert--error">
            <svg viewBox="0 0 20 20" fill="none" width="15" style="flex-shrink:0;margin-top:.1rem"><circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.5"/><path d="M10 6v4M10 13v.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            <?php echo e($errors->first()); ?>

        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('status')): ?>
        <div class="pw-auth__alert pw-auth__alert--success">
            <svg viewBox="0 0 20 20" fill="none" width="15" style="flex-shrink:0;margin-top:.1rem"><circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.5"/><path d="M7 10l2 2 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <?php echo e(session('status')); ?>

        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <form method="POST" action="<?php echo e(route('password.email')); ?>">
            <?php echo csrf_field(); ?>

            <div class="pw-form__group">
                <div class="pw-form__input-wrap">
                    <svg class="pw-form__ico" viewBox="0 0 20 20" fill="none" width="16"><path d="M3 5h14a1 1 0 011 1v8a1 1 0 01-1 1H3a1 1 0 01-1-1V6a1 1 0 011-1z" stroke="currentColor" stroke-width="1.5"/><path d="M2 6l8 6 8-6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    <input type="email" id="email" name="email"
                        class="pw-form__input pw-form__input--icon <?php echo e($errors->has('email') ? 'is-invalid' : ''); ?>"
                        value="<?php echo e(old('email')); ?>" required autocomplete="email"
                        placeholder="<?php echo e(__('main.auth_email_placeholder')); ?>" autofocus>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="pw-form__error"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <button type="submit" class="pw-btn pw-btn--gold pw-btn--glow" style="width:100%;justify-content:center;">
                <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M3 5h14a1 1 0 011 1v8a1 1 0 01-1 1H3a1 1 0 01-1-1V6a1 1 0 011-1z" stroke="currentColor" stroke-width="1.5"/><path d="M2 6l8 6 8-6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                <?php echo e(__('main.auth_forgot_btn')); ?>

            </button>
        </form>

        <div class="pw-auth__footer">
            <p><a href="<?php echo e(route('login')); ?>" class="pw-auth__back"><?php echo e(__('main.auth_back_login')); ?></a></p>
        </div>
    </div>

    <?php echo $__env->make('auth._footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/pw-panel/resources/views/auth/forgot-password.blade.php ENDPATH**/ ?>