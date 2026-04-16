<?php $__env->startSection('title', $lang === 'en' ? 'Terms & Conditions' : 'Peraturan & Ketentuan'); ?>

<?php $__env->startSection('content'); ?>

<div class="pw-adm-card">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem;margin-bottom:1.25rem;padding-bottom:.75rem;border-bottom:1px solid var(--pw-border);">
        <div class="pw-adm-card__title" style="font-size:1rem;font-weight:700;margin:0;">
            <svg viewBox="0 0 20 20" fill="none" width="15"><path d="M6 2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4a2 2 0 012-2z" stroke="currentColor" stroke-width="1.5"/><path d="M7 7h6M7 10h6M7 13h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            <?php echo e($lang === 'en' ? 'Terms & Conditions — Partner Program' : 'Peraturan & Ketentuan Program Partner'); ?>

        </div>
        <div style="display:flex;gap:.4rem;">
            <a href="<?php echo e(route('partner.terms')); ?>?lang=id"
               style="display:flex;align-items:center;gap:.3rem;padding:.35rem .75rem;border-radius:6px;font-size:.78rem;font-weight:600;text-decoration:none;border:1px solid;
                      <?php echo e($lang === 'id' ? 'background:rgba(200,151,42,.15);border-color:rgba(200,151,42,.4);color:var(--pw-gold);' : 'background:rgba(255,255,255,.04);border-color:rgba(255,255,255,.1);color:var(--pw-text-muted);'); ?>">
                🇮🇩 Indonesia
            </a>
            <a href="<?php echo e(route('partner.terms')); ?>?lang=en"
               style="display:flex;align-items:center;gap:.3rem;padding:.35rem .75rem;border-radius:6px;font-size:.78rem;font-weight:600;text-decoration:none;border:1px solid;
                      <?php echo e($lang === 'en' ? 'background:rgba(200,151,42,.15);border-color:rgba(200,151,42,.4);color:var(--pw-gold);' : 'background:rgba(255,255,255,.04);border-color:rgba(255,255,255,.1);color:var(--pw-text-muted);'); ?>">
                🇬🇧 English
            </a>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($terms): ?>
    <div class="partner-terms-content" style="font-size:.85rem;line-height:1.75;color:var(--pw-text);">
        <?php echo $terms->content; ?>

    </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($terms->updated_at): ?>
    <div style="margin-top:1.5rem;padding-top:.75rem;border-top:1px solid var(--pw-border);font-size:.72rem;color:var(--pw-text-muted);">
        <?php echo e($lang === 'en' ? 'Last updated:' : 'Terakhir diperbarui:'); ?> <?php echo e($terms->updated_at->format('d M Y H:i')); ?>

    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php else: ?>
    <div style="text-align:center;padding:2.5rem 1rem;color:var(--pw-text-muted);font-size:.85rem;">
        <?php echo e($lang === 'en' ? 'Terms & Conditions have not been set yet.' : 'Syarat & Ketentuan belum diisi oleh Administrator.'); ?>

    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.partner-terms-content h3 {
    font-size: .92rem;
    font-weight: 700;
    color: var(--pw-gold);
    margin: 1.4rem 0 .5rem;
}
.partner-terms-content ol {
    padding-left: 1.6rem;
    margin: 0;
}
.partner-terms-content li {
    margin-bottom: .35rem;
}
.partner-terms-content p {
    margin-top: 1rem;
    color: var(--pw-text-muted);
    font-size: .8rem;
    font-style: italic;
}
.partner-terms-content strong {
    color: var(--pw-text);
    font-weight: 700;
}
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.partner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/pw-panel/resources/views/partner/terms.blade.php ENDPATH**/ ?>