<?php $__env->startSection('title', $event->title . ' — Pre-Launch Event'); ?>

<?php $__env->startSection('content'); ?>
<div style="margin-bottom:1rem;">
    <a href="<?php echo e(route('admin.events.index', ['tab' => 'pre_launch'])); ?>" class="pw-adm-btn pw-adm-btn--ghost pw-adm-btn--sm">← Kembali</a>
</div>


<div class="pw-adm-card" style="margin-bottom:1.2rem;">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.8rem;">
        <div>
            <div class="pw-adm-card__title" style="margin-bottom:.3rem;"><?php echo e($event->title); ?></div>
            <div style="font-size:.82rem;color:var(--pw-text-muted);">
                <?php echo e($event->start_at?->format('d M Y H:i')); ?> — <?php echo e($event->end_at?->format('d M Y H:i')); ?>

            </div>
        </div>
        <div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->status === 'active'): ?>
                <span class="pw-badge pw-badge--success">Aktif</span>
            <?php elseif($event->status === 'ended'): ?>
                <span class="pw-badge pw-badge--warning">Berakhir</span>
            <?php elseif($event->status === 'distributed'): ?>
                <span class="pw-badge pw-badge--info" style="background:rgba(56,189,248,.15);color:#38bdf8;">Distributed</span>
            <?php else: ?>
                <span class="pw-badge">Draft</span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div>


<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;margin-bottom:1.5rem;">
    <div class="pw-adm-card" style="text-align:center;padding:1.2rem;">
        <div style="font-size:1.8rem;font-weight:800;color:#c8972a;"><?php echo e(number_format($totalRegistered)); ?></div>
        <div style="font-size:.82rem;color:var(--pw-text-muted);">Total Registrasi</div>
    </div>
    <div class="pw-adm-card" style="text-align:center;padding:1.2rem;">
        <div style="font-size:1.8rem;font-weight:800;color:#c8972a;"><?php echo e(number_format($totalReferrals)); ?></div>
        <div style="font-size:.82rem;color:var(--pw-text-muted);">Via Referral</div>
    </div>
    <div class="pw-adm-card" style="text-align:center;padding:1.2rem;">
        <div style="font-size:1.8rem;font-weight:800;color:#c8972a;">Lv.<?php echo e($event->referral_req_level); ?></div>
        <div style="font-size:.82rem;color:var(--pw-text-muted);">Syarat Level Karakter</div>
    </div>
</div>


<div class="pw-adm-card" style="margin-bottom:1.5rem;">
    <div class="pw-adm-card__title">Referral Reward Tiers</div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:.8rem;">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $event->referral_tiers ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
        <div style="background:rgba(200,151,42,.06);border:1px solid rgba(200,151,42,.15);border-radius:8px;padding:1rem;text-align:center;">
            <div style="font-size:1.3rem;font-weight:800;color:#c8972a;"><?php echo e($tier['count']); ?></div>
            <div style="font-size:.78rem;color:var(--pw-text-muted);margin-bottom:.3rem;">Referral</div>
            <div style="font-size:.9rem;font-weight:700;color:var(--pw-text);"><?php echo e(number_format($tier['reward'])); ?> Cubi Gold</div>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </div>
</div>


<div class="pw-adm-card" style="margin-bottom:1.5rem;">
    <div class="pw-adm-card__title">Ranking Referral</div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($referrers->isEmpty()): ?>
    <div style="text-align:center;padding:2rem;color:var(--pw-text-muted);">Belum ada data referral.</div>
    <?php else: ?>
    <div class="pw-table-wrap">
        <table class="pw-table">
            <thead>
                <tr>
                    <th style="text-align:center;width:50px;">#</th>
                    <th>Username</th>
                    <th style="text-align:center;">Referral Code</th>
                    <th style="text-align:center;">Jumlah Referral</th>
                    <th style="text-align:center;">Milestone Tercapai</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $referrers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $referrer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <tr>
                    <td style="text-align:center;font-weight:700;">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($referrers->firstItem() + $index <= 3): ?>
                            <?php $medals = ['🥇','🥈','🥉']; ?>
                            <?php echo e($medals[$referrers->firstItem() + $index - 1]); ?>

                        <?php else: ?>
                            <?php echo e($referrers->firstItem() + $index); ?>

                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                    <td style="font-weight:600;"><?php echo e($referrer->name); ?></td>
                    <td style="text-align:center;font-family:monospace;font-size:.82rem;"><?php echo e($referrer->referral_code); ?></td>
                    <td style="text-align:center;font-weight:700;color:#c8972a;"><?php echo e($referrer->referral_count); ?></td>
                    <td style="text-align:center;font-size:.82rem;">
                        <?php
                            $tiers = collect($event->referral_tiers ?? []);
                            $reached = $tiers->filter(fn($t) => $referrer->referral_count >= $t['count'])->count();
                        ?>
                        <?php echo e($reached); ?> / <?php echo e($tiers->count()); ?>

                    </td>
                </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php echo e($referrers->links()); ?>

    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($milestones->isNotEmpty()): ?>
<div class="pw-adm-card">
    <div class="pw-adm-card__title">Milestone yang Sudah Didistribusikan</div>
    <div class="pw-table-wrap">
        <table class="pw-table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th style="text-align:center;">Milestone</th>
                    <th style="text-align:center;">Reward</th>
                    <th style="text-align:center;">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $milestones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <tr>
                    <td><?php echo e($ms->user?->name ?? 'ID: '.$ms->user_id); ?></td>
                    <td style="text-align:center;"><?php echo e($ms->milestone); ?> referral</td>
                    <td style="text-align:center;font-weight:700;color:#c8972a;"><?php echo e(number_format($ms->reward_amount)); ?> Cubi</td>
                    <td style="text-align:center;font-size:.82rem;color:var(--pw-text-muted);"><?php echo e($ms->distributed_at?->format('d M Y H:i')); ?></td>
                </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


<div style="margin-top:1.5rem;display:flex;gap:.5rem;flex-wrap:wrap;">
    <a href="<?php echo e(route('admin.events.edit', $event)); ?>" class="pw-adm-btn pw-adm-btn--ghost">Edit Event</a>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->status === 'draft'): ?>
    <form method="POST" action="<?php echo e(route('admin.events.toggle', $event)); ?>" style="display:inline;"
          data-confirm="Aktifkan Event|Aktifkan event pre-launch ini?"
          data-confirm-variant="success" data-confirm-ok="Ya, Aktifkan">
        <?php echo csrf_field(); ?>
        <button type="submit" class="pw-adm-btn">Start Event</button>
    </form>
    <?php elseif($event->status === 'active'): ?>
    <form method="POST" action="<?php echo e(route('admin.events.toggle', $event)); ?>" style="display:inline;"
          data-confirm="Akhiri Event|Akhiri event pre-launch ini?"
          data-confirm-variant="danger" data-confirm-ok="Ya, Akhiri">
        <?php echo csrf_field(); ?>
        <button type="submit" class="pw-adm-btn pw-adm-btn--danger">End Event</button>
    </form>
    <?php elseif($event->status === 'ended'): ?>
    <form method="POST" action="<?php echo e(route('admin.events.distribute-referrals', $event)); ?>" style="display:inline;"
          data-confirm="Distribute Referral Rewards|Distribusikan Cubi Gold ke semua referrer yang memenuhi milestone?"
          data-confirm-variant="success" data-confirm-ok="Ya, Distribute">
        <?php echo csrf_field(); ?>
        <button type="submit" class="pw-adm-btn" style="background:#38bdf8;color:#0a0a0f;">Distribute Referral Rewards</button>
    </form>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/pw-panel/resources/views/admin/events/show-prelaunch.blade.php ENDPATH**/ ?>