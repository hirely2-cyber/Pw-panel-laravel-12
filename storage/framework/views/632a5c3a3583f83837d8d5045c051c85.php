<?php
    $__siteName = \App\Models\Setting::get('seo_title') ?: config('pw-config.server.name') ?: 'Perfect World';
?>

<?php $__env->startSection('title', __('main.donatur_page_title') . ' — ' . $__siteName); ?>
<?php $__env->startSection('meta_description', __('main.donatur_page_title') . ' ' . $__siteName); ?>

<?php $__env->startSection('content'); ?>


<div class="pw-page-hero">
    <div class="pw-page-hero__bg" aria-hidden="true"></div>
    <canvas id="pw-confetti" style="position:absolute;inset:0;width:100%;height:100%;pointer-events:none;z-index:0;"></canvas>
    <div class="pw-page-hero__inner" style="position:relative;z-index:1;">
        <div class="pw-page-hero__ornament" aria-hidden="true">
            <svg viewBox="0 0 160 20" fill="none" width="140">
                <line x1="0" y1="10" x2="55" y2="10" stroke="#c8972a" stroke-width="1"/>
                <path d="M65 3 L75 10 L65 17 L55 10 Z" fill="#c8972a" opacity=".5"/>
                <path d="M75 3 L85 10 L75 17 L65 10 Z" fill="#c8972a"/>
                <path d="M85 3 L95 10 L85 17 L75 10 Z" fill="#c8972a" opacity=".5"/>
                <line x1="95" y1="10" x2="150" y2="10" stroke="#c8972a" stroke-width="1"/>
            </svg>
        </div>
        <?php $topOne = $donatur->first(); ?>
        <div style="font-size:.78rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.15em;margin-bottom:.3rem;"><?php echo e(__('main.donatur_top1_label')); ?></div>
        <div style="font-size:clamp(2rem,6vw,3.5rem);font-weight:900;font-family:'Cinzel',serif;background:linear-gradient(135deg,#fbbf24 0%,#f59e0b 30%,#fcd34d 50%,#f59e0b 70%,#c8972a 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;line-height:1.1;filter:drop-shadow(0 2px 8px rgba(251,191,36,.3));">
            Rp <?php echo e(number_format($topOne->total_amount ?? 0, 0, ',', '.')); ?>

        </div>
        <div style="font-size:clamp(.85rem,2.5vw,1.05rem);color:var(--pw-text-muted);margin-top:.5rem;line-height:1.5;">
            <span style="color:#fbbf24;font-weight:700;"><?php echo e($topOne->display_name ?? '—'); ?></span> &bull;
            <span style="color:var(--pw-text-muted);"><?php echo e($topOne->total_transaksi ?? 0); ?>&times; <?php echo e(__('main.donatur_transactions')); ?></span>
        </div>
        <div style="width:60px;height:1px;background:linear-gradient(90deg,transparent,rgba(200,151,42,.4),transparent);margin:1rem auto .6rem;"></div>
        <nav class="pw-breadcrumb" aria-label="breadcrumb">
            <a href="<?php echo e(route('home')); ?>" class="pw-breadcrumb__item">
                <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true">
                    <path d="M2 7.5L8 2l6 5.5V14a1 1 0 01-1 1h-3.5v-3.5h-3V15H3a1 1 0 01-1-1V7.5z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/>
                </svg>
                <?php echo e(__('main.breadcrumb_home')); ?>

            </a>
            <span class="pw-breadcrumb__sep" aria-hidden="true">/</span>
            <span class="pw-breadcrumb__item pw-breadcrumb__item--active"><?php echo e(__('main.donatur_page_title')); ?></span>
        </nav>
    </div>
</div>


<section class="pw-section" id="donatur" style="padding-top:.5rem;">
    <div class="pw-section__inner pw-section__inner--narrow">

        
        <div class="pw-podium" style="position:relative;z-index:1;margin-top:0;padding-top:4px;">
            <?php
                // Display order: Silver(2nd) left, Gold(1st) center, Bronze(3rd) right
                $dPodiumOrder = [1, 0, 2];
                $dPodiumRank  = [2, 1, 3];
                $dPodiumClass = ['pw-podium__step--silver', 'pw-podium__step--gold', 'pw-podium__step--bronze'];
                $dRankClass   = ['pw-rank--2', 'pw-rank--1', 'pw-rank--3'];
                $dRankColors  = ['#c0c0c0', '#ffd700', '#cd7f32'];
            ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $dPodiumOrder; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $di): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <?php $d = $donatur[$di] ?? null; ?>
            <div class="pw-podium__item <?php echo e($dPodiumClass[$idx]); ?>">
                <div class="pw-podium__avatar" aria-hidden="true">
                    <?php $dIconSize = $dPodiumRank[$idx] === 1 ? 200 : 150; ?>
                    <img src="<?php echo e(asset('images/gif_icon/' . $dPodiumRank[$idx] . '.gif')); ?>" alt="#<?php echo e($dPodiumRank[$idx]); ?>" width="<?php echo e($dIconSize); ?>" height="<?php echo e($dIconSize); ?>" style="display:block;margin:0 auto;">
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($d): ?>
                <div class="pw-podium__name"><?php echo e($d->display_name); ?></div>
                <div class="pw-podium__sub"><?php echo e($d->total_transaksi); ?>&times; <?php echo e(__('main.donatur_transactions')); ?></div>
                <div class="pw-podium__level" style="color:<?php echo e($dRankColors[$idx]); ?>">Rp <?php echo e(number_format($d->total_amount, 0, ',', '.')); ?></div>
                <?php else: ?>
                <div class="pw-podium__name" style="color:var(--pw-text-muted);font-style:italic;"><?php echo e(__('main.donatur_empty_podium')); ?></div>
                <div class="pw-podium__sub" style="opacity:.4;"><?php echo e(__('main.donatur_empty_sub')); ?></div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <div class="pw-podium__step-block">
                    <span class="pw-rank <?php echo e($dRankClass[$idx]); ?>">#<?php echo e($dPodiumRank[$idx]); ?></span>
                </div>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>

        
        <div style="text-align:center;margin:1.5rem 0 .5rem;display:flex;align-items:center;justify-content:center;gap:.6rem;flex-wrap:wrap;">
            <svg viewBox="0 0 16 16" fill="none" width="16" style="color:#c8972a;">
                <rect x="2" y="3" width="12" height="11" rx="2" stroke="currentColor" stroke-width="1.3"/>
                <path d="M2 7h12" stroke="currentColor" stroke-width="1.3"/>
                <path d="M5 1v3M11 1v3" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
            </svg>
            <select onchange="if(this.value) window.location.href='<?php echo e(route('donatur')); ?>?month='+this.value"
                    style="background:var(--pw-bg-card);color:var(--pw-gold);border:1px solid rgba(200,151,42,.3);
                           border-radius:6px;padding:.35rem .7rem;font-size:.92rem;font-weight:700;cursor:pointer;
                           outline:none;appearance:auto;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $availableMonths; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ym): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php
                        $label = \Carbon\Carbon::createFromFormat('Y-m', $ym)->translatedFormat('F Y');
                    ?>
                    <option value="<?php echo e($ym); ?>" <?php echo e($selectedMonth === $ym ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </select>
            <span style="font-size:.78rem;color:var(--pw-text-muted);">&mdash; <?php echo e(__('main.donatur_realtime')); ?></span>
        </div>

        
        <div class="pw-ranking__table-wrap">
            <table class="pw-ranking__table">
                <thead>
                    <tr>
                        <th style="text-align:center;width:50px;">No</th>
                        <th><?php echo e(__('main.donatur_col_truename')); ?></th>
                        <th><?php echo e(__('main.donatur_col_username')); ?></th>
                        <th><?php echo e(__('main.donatur_col_character')); ?></th>
                        <th style="text-align:center;"><?php echo e(__('main.donatur_col_transactions')); ?></th>
                        <th style="text-align:right;"><?php echo e(__('main.donatur_col_total')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $donatur; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rank => $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <tr class="<?php echo e($rank < 3 ? 'pw-ranking__top' : ''); ?>">
                        <td style="text-align:center;">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($rank < 3): ?>
                                <span class="pw-rank pw-rank--<?php echo e($rank + 1); ?>"><?php echo e($rank + 1); ?></span>
                            <?php else: ?>
                                <span class="pw-rank"><?php echo e($rank + 1); ?></span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                        <td>
                            <div class="pw-donatur-table__name"><?php echo e($d->display_truename); ?></div>
                        </td>
                        <td>
                            <div class="pw-donatur-table__name" style="color:var(--pw-text-muted);font-size:.85rem;"><?php echo e($d->display_username); ?></div>
                        </td>
                        <td style="word-break:break-word;max-width:200px;font-size:.82rem;">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($d->characters)): ?>
                                <?php echo e(implode(', ', $d->characters)); ?>

                            <?php else: ?>
                                <span style="color:var(--pw-text-muted);font-style:italic;">—</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                        <td style="text-align:center;color:var(--pw-text-muted);font-size:.82rem;">
                            <?php echo e($d->total_transaksi); ?>&times;
                        </td>
                        <td style="text-align:right;">
                            <span class="pw-donatur-table__amount">Rp <?php echo e(number_format($d->total_amount, 0, ',', '.')); ?></span>
                        </td>
                    </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <tr>
                        <td colspan="6" class="pw-table__empty">
                            <?php echo e(__('main.donatur_no_data')); ?>

                        </td>
                    </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($lastDonatur->isNotEmpty()): ?>
        <div class="pw-donatur-recent">
            <h2 class="pw-donatur-recent__title">
                <svg viewBox="0 0 16 16" fill="none" width="15" aria-hidden="true">
                    <circle cx="8" cy="8" r="6" stroke="currentColor" stroke-width="1.3"/>
                    <path d="M8 5v3.5l2 1.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                </svg>
                <?php echo e(__('main.donatur_recent_title')); ?>

            </h2>
            <div class="pw-donatur-recent__list">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $lastDonatur; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div class="pw-donatur-recent__item">
                    <div class="pw-donatur-recent__dot" aria-hidden="true"></div>
                    <div class="pw-donatur-recent__info">
                        <span class="pw-donatur-recent__who"><?php echo e(!empty($inv->user?->truename) ? $inv->user->truename : 'Anonim'); ?></span>
                        <span class="pw-donatur-recent__rp">Rp <?php echo e(number_format($inv->amount, 0, ',', '.')); ?></span>
                    </div>
                    <div class="pw-donatur-recent__time"><?php echo e($inv->paid_at?->diffForHumans()); ?></div>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    </div>
</section>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
@keyframes pw-shine {
    0% { background-position: -200% center; }
    100% { background-position: 200% center; }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
(function () {
    const canvas = document.getElementById('pw-confetti');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    let W, H, particles = [];

    const COLORS = ['#fbbf24','#f59e0b','#fcd34d','#c8972a','#e8b84b','#fff7c2','#d4a437'];

    function resize() {
        W = canvas.width = canvas.offsetWidth;
        H = canvas.height = canvas.offsetHeight;
    }

    function makeParticle() {
        return {
            x: Math.random() * W,
            y: Math.random() * H - H,
            r: Math.random() * 4 + 2,
            d: Math.random() * 80 + 20,
            color: COLORS[Math.floor(Math.random() * COLORS.length)],
            tilt: Math.random() * 10 - 5,
            tiltAngle: Math.random() * Math.PI,
            tiltSpeed: Math.random() * 0.04 + 0.02,
            speed: Math.random() * 1 + 0.5,
            opacity: Math.random() * 0.6 + 0.4,
            shape: Math.floor(Math.random() * 3)
        };
    }

    function init() {
        resize();
        particles = [];
        for (let i = 0; i < 50; i++) {
            const p = makeParticle();
            p.y = Math.random() * H;
            particles.push(p);
        }
    }

    function draw() {
        ctx.clearRect(0, 0, W, H);
        particles.forEach(p => {
            ctx.save();
            ctx.globalAlpha = p.opacity;
            ctx.fillStyle = p.color;
            ctx.translate(p.x, p.y);
            ctx.rotate(p.tiltAngle);
            if (p.shape === 0) {
                ctx.beginPath();
                ctx.arc(0, 0, p.r, 0, Math.PI * 2);
                ctx.fill();
            } else if (p.shape === 1) {
                ctx.fillRect(-p.r, -p.r / 2, p.r * 2, p.r);
            } else {
                ctx.strokeStyle = p.color;
                ctx.lineWidth = 1.5;
                ctx.beginPath();
                ctx.moveTo(0, -p.r);
                ctx.lineTo(0, p.r);
                ctx.stroke();
            }
            ctx.restore();
        });
    }

    function update() {
        particles.forEach(p => {
            p.y += p.speed;
            p.x += Math.sin(p.d) * 0.3;
            p.tiltAngle += p.tiltSpeed;
            if (p.y > H + 10) {
                p.y = -10;
                p.x = Math.random() * W;
            }
        });
    }

    function loop() {
        draw();
        update();
        requestAnimationFrame(loop);
    }

    window.addEventListener('resize', resize);
    init();
    loop();
})();
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/pw-panel/resources/views/website/donatur.blade.php ENDPATH**/ ?>