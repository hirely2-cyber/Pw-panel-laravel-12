<?php $__env->startSection('title', 'Detail Event: ' . $event->title); ?>

<?php $__env->startSection('content'); ?>


<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.2rem;flex-wrap:wrap;gap:.6rem;">
    <div style="display:flex;align-items:center;gap:.6rem;">
        <a href="<?php echo e(route('admin.events.index')); ?>" class="pw-adm-btn pw-adm-btn--ghost pw-adm-btn--sm">← Kembali</a>
        <h1 style="font-size:1.05rem;font-weight:700;color:var(--pw-text-light);margin:0;"><?php echo e($event->title); ?></h1>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->status === 'active'): ?> <span class="pw-badge pw-badge--success">Aktif</span>
        <?php elseif($event->status === 'ended'): ?> <span class="pw-badge pw-badge--warning">Berakhir</span>
        <?php elseif($event->status === 'distributed'): ?> <span class="pw-badge" style="background:rgba(56,189,248,.15);color:#38bdf8;">Distributed</span>
        <?php else: ?> <span class="pw-badge">Draft</span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <div style="display:flex;gap:.4rem;">
        <a href="<?php echo e(route('admin.events.edit', $event)); ?>" class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--ghost">Edit</a>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->status === 'draft'): ?>
        <form method="POST" action="<?php echo e(route('admin.events.toggle', $event)); ?>" style="display:inline;"
              data-confirm="Aktifkan Event|Aktifkan event '<?php echo e($event->title); ?>'?"
              data-confirm-variant="success"
              data-confirm-ok="Ya, Aktifkan">
            <?php echo csrf_field(); ?>
            <button type="submit" class="pw-adm-btn pw-adm-btn--sm">Start Event</button>
        </form>
        <?php elseif($event->status === 'active'): ?>
        <form method="POST" action="<?php echo e(route('admin.events.toggle', $event)); ?>" style="display:inline;"
              data-confirm="Akhiri Event|Akhiri event '<?php echo e($event->title); ?>'?"
              data-confirm-variant="danger"
              data-confirm-ok="Ya, Akhiri">
            <?php echo csrf_field(); ?>
            <button type="submit" class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--danger">End Event</button>
        </form>
        <?php elseif($event->status === 'ended'): ?>
        <form method="POST" action="<?php echo e(route('admin.events.distribute', $event)); ?>" style="display:inline;"
              data-confirm="Distribute Hadiah|Distribusikan hadiah Cubi Gold ke <?php echo e($event->prize_winner_count); ?> pemenang?"
              data-confirm-variant="success"
              data-confirm-ok="Ya, Distribute">
            <?php echo csrf_field(); ?>
            <button type="submit" class="pw-adm-btn pw-adm-btn--sm" style="background:#38bdf8;color:#0a0a0f;">Distribute Hadiah</button>
        </form>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>


<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:.75rem;margin-bottom:.75rem;">
    <div class="pw-adm-card" style="padding:.8rem 1rem;margin-bottom:0;">
        <div style="font-size:.62rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.3rem;">Syarat Level</div>
        <div style="font-size:1.15rem;font-weight:700;color:var(--pw-text-light);">Lv. <?php echo e($event->req_level); ?></div>
    </div>
    <div class="pw-adm-card" style="padding:.8rem 1rem;margin-bottom:0;">
        <div style="font-size:.62rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.3rem;">Syarat Cultivation</div>
        <div style="font-size:1.15rem;font-weight:700;color:var(--pw-text-light);"><?php echo e(\App\Models\LaunchEvent::CULTIVATION_MAP[$event->req_cultivation] ?? 'Lv.'.$event->req_cultivation); ?></div>
    </div>
    <div class="pw-adm-card" style="padding:.8rem 1rem;margin-bottom:0;">
        <div style="font-size:.62rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.3rem;">Qualified / Target</div>
        <div style="font-size:1.15rem;font-weight:700;color:#22c55e;"><?php echo e($qualifiedCount); ?> <span style="color:var(--pw-text-muted);font-weight:400;">/ <?php echo e($event->prize_winner_count); ?></span></div>
    </div>
    <div class="pw-adm-card" style="padding:.8rem 1rem;margin-bottom:0;">
        <div style="font-size:.62rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.3rem;">Total Peserta</div>
        <div style="font-size:1.15rem;font-weight:700;color:var(--pw-text-light);"><?php echo e($totalParticipants); ?></div>
    </div>
</div>


<div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;margin-bottom:1.2rem;">
    <div class="pw-adm-card" style="padding:.8rem 1rem;margin-bottom:0;">
        <div style="font-size:.62rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.5rem;">Hadiah Cubi Gold</div>
        <div style="font-size:1.15rem;font-weight:700;color:var(--pw-gold);margin-bottom:.5rem;"><?php echo e(number_format($event->prize_total_cubi)); ?> Cubi</div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->hasTieredPrizes()): ?>
        <div style="display:flex;gap:1rem;font-size:.82rem;">
            <span>🥇 <strong><?php echo e(number_format($event->prize_rank1)); ?></strong></span>
            <span>🥈 <strong><?php echo e(number_format($event->prize_rank2)); ?></strong></span>
            <span>🥉 <strong><?php echo e(number_format($event->prize_rank3)); ?></strong></span>
            <span style="color:var(--pw-text-muted);">Lainnya: <strong><?php echo e(number_format($event->prizeForRank(4))); ?></strong>/orang</span>
        </div>
        <?php else: ?>
        <div style="font-size:.82rem;color:var(--pw-text-muted);"><?php echo e(number_format($event->prizePerWinner())); ?> Cubi per pemenang (rata)</div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <div class="pw-adm-card" style="padding:.8rem 1rem;margin-bottom:0;">
        <div style="font-size:.62rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.5rem;">Periode Event</div>
        <div style="display:flex;gap:1.5rem;align-items:center;">
            <div>
                <div style="font-size:.68rem;color:var(--pw-text-muted);">Mulai</div>
                <div style="font-weight:600;font-size:.9rem;"><?php echo e($event->start_at?->format('d M Y')); ?></div>
                <div style="font-size:.75rem;color:var(--pw-text-muted);"><?php echo e($event->start_at?->format('H:i')); ?> WIB</div>
            </div>
            <div style="color:var(--pw-text-muted);font-size:1.2rem;">→</div>
            <div>
                <div style="font-size:.68rem;color:var(--pw-text-muted);">Berakhir</div>
                <div style="font-weight:600;font-size:.9rem;"><?php echo e($event->end_at?->format('d M Y')); ?></div>
                <div style="font-size:.75rem;color:var(--pw-text-muted);"><?php echo e($event->end_at?->format('H:i')); ?> WIB</div>
            </div>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->description): ?>
        <div style="margin-top:.6rem;padding-top:.6rem;border-top:1px solid var(--pw-border);font-size:.78rem;color:var(--pw-text-muted);"><?php echo e($event->description); ?></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>


<div class="pw-adm-card">
    <div class="pw-adm-card__title">Peserta (<?php echo e($totalParticipants); ?>)</div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($totalParticipants > 0): ?>
    <div class="pw-table-wrap">
        <table class="pw-table">
            <thead>
                <tr>
                    <th style="text-align:center;width:50px;">#</th>
                    <th>Character</th>
                    <th>Class</th>
                    <th style="text-align:center;">Level</th>
                    <th style="text-align:center;">Cultivation</th>
                    <th style="text-align:center;">Status</th>
                    <th style="text-align:center;">Qualified</th>
                    <th style="text-align:center;">Hadiah</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $participants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <tr style="<?php echo e($p->qualified_at ? 'background:rgba(34,197,94,.06);' : ''); ?>">
                    <td style="text-align:center;"><?php echo e($participants->firstItem() + $i); ?></td>
                    <td style="font-weight:600;"><?php echo e($p->character_name); ?></td>
                    <td style="font-size:.82rem;color:var(--pw-text-muted);"><?php echo e($p->class); ?></td>
                    <td style="text-align:center;font-weight:600;<?php echo e($p->level >= $event->req_level ? 'color:#22c55e;' : ''); ?>">
                        <?php echo e($p->level); ?>

                    </td>
                    <td style="text-align:center;font-size:.82rem;<?php echo e($event->meetsCultivation($p->cultivation) ? 'color:#22c55e;font-weight:600;' : 'color:var(--pw-text-muted);'); ?>">
                        <?php echo e($p->cultivation_label ?? $p->cultivation); ?>

                    </td>
                    <td style="text-align:center;">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($p->qualified_at): ?>
                            <span class="pw-badge pw-badge--success" style="font-size:.7rem;">Qualified</span>
                        <?php else: ?>
                            <span class="pw-badge" style="font-size:.7rem;">Progress</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                    <td style="text-align:center;font-size:.78rem;color:var(--pw-text-muted);">
                        <?php echo e($p->qualified_at?->format('d M Y H:i') ?? '—'); ?>

                    </td>
                    <td style="text-align:center;">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($p->prize_distributed): ?>
                            <span class="pw-badge pw-badge--success" style="font-size:.7rem;">Sent</span>
                        <?php else: ?>
                            <span style="color:var(--pw-text-muted);">—</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;"><?php echo e($participants->links()); ?></div>
    <?php else: ?>
    <div style="text-align:center;padding:2rem 1rem;color:var(--pw-text-muted);">
        <svg viewBox="0 0 20 20" fill="none" width="32" style="margin:0 auto .6rem;opacity:.4;display:block;"><path d="M10 2a8 8 0 110 16 8 8 0 010-16z" stroke="currentColor" stroke-width="1.5"/><path d="M7 13s1.5-2 3-2 3 2 3 2" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/><circle cx="7.5" cy="8.5" r="1" fill="currentColor"/><circle cx="12.5" cy="8.5" r="1" fill="currentColor"/></svg>
        <div style="font-size:.85rem;font-weight:600;">Belum ada peserta</div>
        <div style="font-size:.75rem;margin-top:.3rem;">Data akan muncul setelah event aktif dan cron sync berjalan.</div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/pw-panel/resources/views/admin/events/show.blade.php ENDPATH**/ ?>