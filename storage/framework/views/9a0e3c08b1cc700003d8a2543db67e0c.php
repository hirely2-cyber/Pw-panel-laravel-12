<?php $__env->startSection('title', 'Detail Pemain: ' . $user->name); ?>

<?php $__env->startSection('content'); ?>
<div style="margin-bottom:1rem;">
    <a href="<?php echo e(route('gm.members.index')); ?>" class="pw-adm-btn pw-adm-btn--ghost pw-adm-btn--sm">← Kembali</a>
</div>

<div class="pw-adm-card" style="max-width:520px;">
    <div class="pw-adm-card__title">Profil Pemain</div>

    <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.2rem;">
        <div style="width:56px;height:56px;border-radius:50%;background:var(--pw-gold-dark,#6b5420);display:flex;align-items:center;justify-content:center;font-size:1.4rem;font-weight:700;color:#b89d4f;flex-shrink:0;">
            <?php echo e(strtoupper(substr($user->name,0,1))); ?>

        </div>
        <div>
            <div style="font-weight:600;font-size:1rem;"><?php echo e($user->name); ?></div>
            <div style="color:var(--pw-text-muted);font-size:.8rem;"><?php echo e($user->email); ?></div>
        </div>
    </div>

    <table style="width:100%;font-size:.83rem;border-collapse:collapse;">
        <tr style="border-bottom:1px solid var(--pw-border,rgba(255,255,255,.07));">
            <td style="padding:.5rem 0;color:var(--pw-text-muted);width:140px;">Role</td>
            <td style="padding:.5rem 0;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->role === 'admin'): ?> <span class="pw-badge pw-badge--danger">Admin</span>
                <?php elseif($user->role === 'gm'): ?> <span class="pw-badge pw-badge--warning">GM</span>
                <?php else: ?> <span class="pw-badge">Player</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </td>
        </tr>
        <tr style="border-bottom:1px solid var(--pw-border,rgba(255,255,255,.07));">
            <td style="padding:.5rem 0;color:var(--pw-text-muted);">Gold Points</td>
            <td style="padding:.5rem 0;font-weight:600;color:#b89d4f;"><?php echo e(number_format($user->money)); ?></td>
        </tr>
        <tr style="border-bottom:1px solid var(--pw-border,rgba(255,255,255,.07));">
            <td style="padding:.5rem 0;color:var(--pw-text-muted);">No HP</td>
            <td style="padding:.5rem 0;"><?php echo e($user->mobilenumber ?: '—'); ?></td>
        </tr>
        <tr style="border-bottom:1px solid var(--pw-border,rgba(255,255,255,.07));">
            <td style="padding:.5rem 0;color:var(--pw-text-muted);">Kode Referral</td>
            <td style="padding:.5rem 0;">
                <code style="font-size:.8rem;background:rgba(200,151,42,.1);color:var(--pw-gold);padding:.15rem .45rem;border-radius:4px;letter-spacing:.06em;"><?php echo e($user->referral_code ?? '—'); ?></code>
            </td>
        </tr>
        <tr style="border-bottom:1px solid var(--pw-border,rgba(255,255,255,.07));">
            <td style="padding:.5rem 0;color:var(--pw-text-muted);">Diundang Oleh</td>
            <td style="padding:.5rem 0;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->referrer): ?>
                    <span style="font-size:.82rem;color:var(--pw-gold);font-weight:600;"><?php echo e($user->referrer->name); ?></span>
                <?php else: ?>
                    <span style="color:var(--pw-text-muted);font-size:.82rem;">— (daftar mandiri)</span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </td>
        </tr>
        <tr style="border-bottom:1px solid var(--pw-border,rgba(255,255,255,.07));">
            <td style="padding:.5rem 0;color:var(--pw-text-muted);">Status</td>
            <td style="padding:.5rem 0;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->banned_at): ?> <span class="pw-badge pw-badge--danger">Banned</span>
                <?php else: ?> <span class="pw-badge pw-badge--success">Aktif</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </td>
        </tr>
        <tr>
            <td style="padding:.5rem 0;color:var(--pw-text-muted);">Bergabung</td>
            <td style="padding:.5rem 0;"><?php echo e($user->created_at?->format('d M Y H:i')); ?></td>
        </tr>
    </table>

    <p style="margin-top:1rem;font-size:.75rem;color:var(--pw-text-muted);">
        Sebagai GM, kamu hanya dapat melihat data pemain. Untuk aksi seperti ban/topup, hubungi Admin.
    </p>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.gm', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/pw-panel/resources/views/gm/members/show.blade.php ENDPATH**/ ?>