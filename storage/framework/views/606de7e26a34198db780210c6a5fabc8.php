
<div class="pw-card" style="margin-bottom:1.5rem;padding:0;" x-data="{ open: false }">
    <button type="button" @click="open = !open"
            style="width:100%;display:flex;align-items:center;justify-content:space-between;gap:.5rem;padding:1.2rem 1.5rem;background:none;border:none;cursor:pointer;text-align:left;">
        <div style="display:flex;align-items:center;gap:.5rem;">
            <svg viewBox="0 0 20 20" fill="none" width="20"><path d="M4 3h12a1 1 0 011 1v12a1 1 0 01-1 1H4a1 1 0 01-1-1V4a1 1 0 011-1z" stroke="#c8972a" stroke-width="1.3"/><path d="M7 7h6M7 10h6M7 13h4" stroke="#c8972a" stroke-width="1.3" stroke-linecap="round"/></svg>
            <span style="font-size:1.05rem;font-weight:700;color:var(--pw-text-light);"><?php echo e(__('main.pa_terms_title')); ?></span>
        </div>
        <svg viewBox="0 0 16 16" fill="none" width="14" style="flex-shrink:0;transition:transform .2s;" :style="open ? 'transform:rotate(180deg)' : ''"><path d="M4 6l4 4 4-4" stroke="var(--pw-gold-light)" stroke-width="1.5" stroke-linecap="round"/></svg>
    </button>

    <div x-show="open" x-collapse x-cloak style="padding:0 1.5rem 1.2rem;">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($terms): ?>
        <div class="partner-terms-content" style="font-size:.88rem;color:var(--pw-text);line-height:1.8;text-align:left;">
            <?php echo $terms->content; ?>

        </div>
        <?php else: ?>
        <p style="color:var(--pw-text-muted);font-size:.85rem;"><?php echo e(__('main.pa_terms_empty')); ?></p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>


<div class="pw-card" style="padding:1.5rem;">
    <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1.2rem;">
        <svg viewBox="0 0 20 20" fill="none" width="20"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" stroke="#c8972a" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/><circle cx="8.5" cy="7" r="4" stroke="#c8972a" stroke-width="1.3"/><path d="M20 8v6M23 11h-6" stroke="#c8972a" stroke-width="1.3" stroke-linecap="round"/></svg>
        <span style="font-size:1rem;font-weight:700;color:var(--pw-text);"><?php echo e(__('main.pa_form_title')); ?></span>
    </div>

    <form method="POST" action="<?php echo e(route('partner-apply.store')); ?>">
        <?php echo csrf_field(); ?>

        <div style="margin-bottom:1rem;">
            <label style="display:block;font-size:.78rem;font-weight:600;color:var(--pw-text-muted);margin-bottom:.3rem;"><?php echo e(__('main.pa_username_label')); ?></label>
            <input type="text" class="pw-form__input" value="<?php echo e(auth()->user()->name); ?>" readonly
                   style="width:100%;opacity:.7;cursor:not-allowed;">
            <p style="font-size:.7rem;color:var(--pw-text-muted);margin-top:.2rem;"><?php echo e(__('main.pa_username_hint')); ?></p>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
            <div>
                <label style="display:block;font-size:.78rem;font-weight:600;color:var(--pw-text-muted);margin-bottom:.3rem;"><?php echo e(__('main.pa_channel_label')); ?></label>
                <input type="text" name="channel_name" class="pw-form__input" placeholder="<?php echo e(__('main.pa_channel_ph')); ?>"
                       value="<?php echo e(old('channel_name')); ?>" required style="width:100%;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['channel_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p style="color:#ef4444;font-size:.72rem;margin-top:.2rem;"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div>
                <label style="display:block;font-size:.78rem;font-weight:600;color:var(--pw-text-muted);margin-bottom:.3rem;"><?php echo e(__('main.pa_platform_label')); ?></label>
                <select name="platform" class="pw-form__input" required style="width:100%;">
                    <option value=""><?php echo e(__('main.pa_platform_default')); ?></option>
                    <option value="tiktok"     <?php echo e(old('platform') === 'tiktok'     ? 'selected' : ''); ?>>TikTok</option>
                    <option value="youtube"    <?php echo e(old('platform') === 'youtube'    ? 'selected' : ''); ?>>YouTube</option>
                    <option value="facebook"   <?php echo e(old('platform') === 'facebook'   ? 'selected' : ''); ?>>Facebook Gaming</option>
                    <option value="instagram"  <?php echo e(old('platform') === 'instagram'  ? 'selected' : ''); ?>>Instagram</option>
                    <option value="twitter"    <?php echo e(old('platform') === 'twitter'    ? 'selected' : ''); ?>>Twitter / X</option>
                    <option value="other"      <?php echo e(old('platform') === 'other'      ? 'selected' : ''); ?>><?php echo e(__('main.pa_platform_other')); ?></option>
                </select>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['platform'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p style="color:#ef4444;font-size:.72rem;margin-top:.2rem;"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:2fr 1fr;gap:1rem;margin-bottom:1rem;">
            <div>
                <label style="display:block;font-size:.78rem;font-weight:600;color:var(--pw-text-muted);margin-bottom:.3rem;"><?php echo e(__('main.pa_link_label')); ?></label>
                <input type="url" name="channel_url" class="pw-form__input" placeholder="https://tiktok.com/@username"
                       value="<?php echo e(old('channel_url')); ?>" required style="width:100%;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['channel_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p style="color:#ef4444;font-size:.72rem;margin-top:.2rem;"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div>
                <label style="display:block;font-size:.78rem;font-weight:600;color:var(--pw-text-muted);margin-bottom:.3rem;"><?php echo e(__('main.pa_followers_label')); ?></label>
                <input type="number" name="followers_count" class="pw-form__input" placeholder="0" min="0"
                       value="<?php echo e(old('followers_count')); ?>" required style="width:100%;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['followers_count'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p style="color:#ef4444;font-size:.72rem;margin-top:.2rem;"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <div style="margin-bottom:1.2rem;">
            <label style="display:block;font-size:.78rem;font-weight:600;color:var(--pw-text-muted);margin-bottom:.3rem;"><?php echo e(__('main.pa_reason_label')); ?></label>
            <textarea name="reason" class="pw-form__input" rows="3" placeholder="<?php echo e(__('main.pa_reason_ph')); ?>"
                      style="width:100%;resize:vertical;"><?php echo e(old('reason')); ?></textarea>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <p style="color:#ef4444;font-size:.72rem;margin-top:.2rem;"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <div style="margin-bottom:1.5rem;padding:1rem;background:rgba(200,151,42,.05);border:1px solid rgba(200,151,42,.12);border-radius:8px;">
            <label style="display:flex;align-items:flex-start;gap:.6rem;font-size:.82rem;cursor:pointer;color:var(--pw-text);">
                <input type="checkbox" name="agree_terms" value="1" style="accent-color:var(--pw-gold);margin-top:3px;flex-shrink:0;" <?php echo e(old('agree_terms') ? 'checked' : ''); ?>>
                <span><?php echo e(__('main.pa_agree_pre')); ?> <strong style="color:var(--pw-gold-light);"><?php echo e(__('main.pa_agree_strong')); ?></strong> <?php echo e(__('main.pa_agree_post')); ?></span>
            </label>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['agree_terms'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <p style="color:#ef4444;font-size:.72rem;margin-top:.4rem;margin-left:1.6rem;"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <button type="submit" class="pw-btn pw-btn--gold pw-btn--lg" style="width:100%;justify-content:center;">
            <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M5 12l5 5 10-10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <?php echo e(__('main.pa_submit')); ?>

        </button>
    </form>
</div>
<?php /**PATH /var/www/pw-panel/resources/views/front/partner-apply/_form.blade.php ENDPATH**/ ?>