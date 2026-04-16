<?php $__env->startSection('title', 'Manajemen Event'); ?>

<?php $__env->startSection('content'); ?>
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.2rem;flex-wrap:wrap;gap:.8rem;">
    <h1 style="font-size:1.2rem;font-weight:700;margin:0;">Manajemen Event</h1>
    <a href="<?php echo e(route('admin.events.create')); ?>?type=<?php echo e($tab); ?>" class="pw-adm-btn">+ Buat Event Baru</a>
</div>


<div style="display:flex;gap:.5rem;margin-bottom:1.5rem;border-bottom:2px solid rgba(200,151,42,.15);padding-bottom:0;">
    <a href="<?php echo e(route('admin.events.index', ['tab' => 'pre_launch'])); ?>"
       style="padding:.6rem 1.2rem;font-size:.85rem;font-weight:700;border-radius:8px 8px 0 0;text-decoration:none;transition:all .2s;
       <?php echo e($tab === 'pre_launch' ? 'background:rgba(200,151,42,.15);color:#c8972a;border:1px solid rgba(200,151,42,.3);border-bottom:2px solid #c8972a;' : 'background:transparent;color:var(--pw-text-muted);border:1px solid transparent;'); ?>">
        🚀 Pre-Launching
    </a>
    <a href="<?php echo e(route('admin.events.index', ['tab' => 'grand_launch'])); ?>"
       style="padding:.6rem 1.2rem;font-size:.85rem;font-weight:700;border-radius:8px 8px 0 0;text-decoration:none;transition:all .2s;
       <?php echo e($tab === 'grand_launch' ? 'background:rgba(200,151,42,.15);color:#c8972a;border:1px solid rgba(200,151,42,.3);border-bottom:2px solid #c8972a;' : 'background:transparent;color:var(--pw-text-muted);border:1px solid transparent;'); ?>">
        🏆 Grand Launching
    </a>
</div>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($events->isEmpty()): ?>
<div class="pw-adm-card" style="text-align:center;padding:2rem;color:var(--pw-text-muted);">
    Belum ada event <?php echo e($tab === 'pre_launch' ? 'Pre-Launching' : 'Grand Launching'); ?>. Klik tombol di atas untuk membuat event baru.
</div>
<?php else: ?>
<div class="pw-table-wrap">
    <table class="pw-table">
        <thead>
            <tr>
                <th>Event</th>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tab === 'pre_launch'): ?>
                <th style="text-align:center;">Syarat Level</th>
                <th style="text-align:center;">Referral Tiers</th>
                <th style="text-align:center;">Registrasi</th>
                <?php else: ?>
                <th style="text-align:center;">Syarat</th>
                <th style="text-align:center;">Hadiah</th>
                <th style="text-align:center;">Pemenang</th>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <th style="text-align:center;">Periode</th>
                <th style="text-align:center;">Status</th>
                <th style="text-align:center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <tr>
                <td>
                    <a href="<?php echo e(route('admin.events.show', $event)); ?>" style="font-weight:600;color:var(--pw-gold);"><?php echo e($event->title); ?></a>
                </td>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tab === 'pre_launch'): ?>
                <td style="text-align:center;font-size:.82rem;">
                    Lv.<?php echo e($event->referral_req_level); ?>

                </td>
                <td style="text-align:center;font-size:.78rem;">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $event->referral_tiers ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <div><?php echo e($tier['count']); ?> orang → <?php echo e(number_format($tier['reward'])); ?> Cubi</div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </td>
                <td style="text-align:center;">
                    <?php $regCount = \App\Models\User::whereBetween('creatime', [$event->start_at, $event->end_at])->count(); ?>
                    <span style="font-weight:600;"><?php echo e($regCount); ?></span>
                    <span style="color:var(--pw-text-muted);font-size:.75rem;">user</span>
                </td>
                <?php else: ?>
                <td style="text-align:center;font-size:.82rem;">
                    <?php echo e(number_format($event->prize_total_cubi)); ?> Cubi
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->hasTieredPrizes()): ?>
                    <div style="color:var(--pw-text-muted);font-size:.72rem;line-height:1.5;">
                        🥇<?php echo e(number_format($event->prize_rank1)); ?> 🥈<?php echo e(number_format($event->prize_rank2)); ?> 🥉<?php echo e(number_format($event->prize_rank3)); ?><br>
                        Lainnya: <?php echo e(number_format($event->prizeForRank(4))); ?>/orang
                    </div>
                    <?php else: ?>
                    <div style="color:var(--pw-text-muted);font-size:.75rem;">
                        (<?php echo e(number_format($event->prizePerWinner())); ?>/orang)
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </td>
                <td style="text-align:center;">
                    <?php $qCount = $event->participants()->whereNotNull('qualified_at')->count(); ?>
                    <span style="font-weight:600;"><?php echo e($qCount); ?></span>
                    <span style="color:var(--pw-text-muted);font-size:.75rem;">/ <?php echo e($event->prize_winner_count); ?></span>
                </td>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <td style="text-align:center;font-size:.78rem;color:var(--pw-text-muted);">
                    <?php echo e($event->start_at?->format('d M Y')); ?><br>
                    — <?php echo e($event->end_at?->format('d M Y')); ?>

                </td>
                <td style="text-align:center;">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->status === 'active'): ?>
                        <span class="pw-badge pw-badge--success">Aktif</span>
                    <?php elseif($event->status === 'ended'): ?>
                        <span class="pw-badge pw-badge--warning">Berakhir</span>
                    <?php elseif($event->status === 'distributed'): ?>
                        <span class="pw-badge pw-badge--info" style="background:rgba(56,189,248,.15);color:#38bdf8;">Distributed</span>
                    <?php else: ?>
                        <span class="pw-badge">Draft</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </td>
                <td style="text-align:center;">
                    <div style="display:flex;gap:.4rem;justify-content:center;flex-wrap:wrap;">
                        <a href="<?php echo e(route('admin.events.show', $event)); ?>" class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--ghost">Detail</a>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->status === 'draft'): ?>
                        <form method="POST" action="<?php echo e(route('admin.events.toggle', $event)); ?>" style="display:inline;"
                              data-confirm="Aktifkan Event|Aktifkan event '<?php echo e($event->title); ?>'?"
                              data-confirm-variant="success"
                              data-confirm-ok="Ya, Aktifkan">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="pw-adm-btn pw-adm-btn--sm">Start</button>
                        </form>
                        <?php elseif($event->status === 'active'): ?>
                        <form method="POST" action="<?php echo e(route('admin.events.toggle', $event)); ?>" style="display:inline;"
                              data-confirm="Akhiri Event|Akhiri event '<?php echo e($event->title); ?>'?"
                              data-confirm-variant="danger"
                              data-confirm-ok="Ya, Akhiri">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--danger">End</button>
                        </form>
                        <?php elseif($event->status === 'ended'): ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->isPreLaunch()): ?>
                        <form method="POST" action="<?php echo e(route('admin.events.distribute-referrals', $event)); ?>" style="display:inline;"
                              data-confirm="Distribute Referral Rewards|Distribusikan hadiah referral ke semua yang memenuhi syarat?"
                              data-confirm-variant="success"
                              data-confirm-ok="Ya, Distribute">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="pw-adm-btn pw-adm-btn--sm" style="background:#38bdf8;color:#0a0a0f;">Distribute</button>
                        </form>
                        <?php else: ?>
                        <form method="POST" action="<?php echo e(route('admin.events.distribute', $event)); ?>" style="display:inline;"
                              data-confirm="Distribute Hadiah|Distribusikan hadiah ke pemenang event '<?php echo e($event->title); ?>'?"
                              data-confirm-variant="success"
                              data-confirm-ok="Ya, Distribute">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="pw-adm-btn pw-adm-btn--sm" style="background:#38bdf8;color:#0a0a0f;">Distribute</button>
                        </form>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->status === 'draft'): ?>
                        <form method="POST" action="<?php echo e(route('admin.events.destroy', $event)); ?>" style="display:inline;"
                              data-confirm="Hapus Event|Hapus event '<?php echo e($event->title); ?>'? Data tidak bisa dikembalikan."
                              data-confirm-variant="danger"
                              data-confirm-ok="Ya, Hapus">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--danger">Hapus</button>
                        </form>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </td>
            </tr>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </tbody>
    </table>
</div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/pw-panel/resources/views/admin/events/index.blade.php ENDPATH**/ ?>