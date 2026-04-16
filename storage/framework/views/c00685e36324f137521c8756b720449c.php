<?php $__env->startSection('title', 'Cubi Monitor'); ?>

<?php $__env->startSection('content'); ?>


<div style="margin-bottom:1.2rem;display:flex;align-items:center;gap:.6rem;">
    <svg viewBox="0 0 20 20" fill="none" width="18" style="color:var(--pw-gold);"><path d="M10 2a8 8 0 110 16 8 8 0 010-16z" stroke="currentColor" stroke-width="1.5"/><path d="M10 6v4l3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M14 2l2 2M6 2L4 4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
    <h1 style="font-size:1.05rem;font-weight:700;color:var(--pw-text-light);">Cubi Monitor</h1>
    <span style="font-size:.7rem;color:var(--pw-text-muted);margin-left:auto;">Pantau pengiriman Cubi Gold</span>
</div>


<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:.75rem;margin-bottom:1rem;">
    <div style="background:rgba(184,157,79,.06);border:1px solid rgba(184,157,79,.2);border-radius:8px;padding:.8rem 1rem;">
        <div style="font-size:.68rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.3rem;">Total Cubi Dikirim</div>
        <div style="font-size:1.2rem;font-weight:700;color:var(--pw-gold);"><?php echo e(number_format($stats['totalDelivered'] / 100, 0, ',', '.')); ?></div>
        <div style="font-size:.65rem;color:var(--pw-text-muted);margin-top:.2rem;"><?php echo e(number_format($stats['totalUsers'])); ?> akun</div>
    </div>
    <div style="background:rgba(80,200,120,.06);border:1px solid rgba(80,200,120,.2);border-radius:8px;padding:.8rem 1rem;">
        <div style="font-size:.68rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.3rem;">Pengiriman Pertama (sn=1)</div>
        <div style="font-size:1.2rem;font-weight:700;color:#50c878;"><?php echo e(number_format($stats['regBonus'] / 100, 0, ',', '.')); ?></div>
        <div style="font-size:.65rem;color:var(--pw-text-muted);margin-top:.2rem;"><?php echo e(round($stats['regBonus'] / max($stats['totalDelivered'], 1) * 100, 1)); ?>% dari total</div>
    </div>
    <div style="background:rgba(220,60,60,.06);border:1px solid rgba(220,60,60,.2);border-radius:8px;padding:.8rem 1rem;">
        <div style="font-size:.68rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.3rem;">Extra Top-up (sn&gt;1)</div>
        <div style="font-size:1.2rem;font-weight:700;color:#e05252;"><?php echo e(number_format($stats['extraTopups'] / 100, 0, ',', '.')); ?></div>
        <div style="font-size:.65rem;color:var(--pw-text-muted);margin-top:.2rem;">Semua jalur non-pertama</div>
    </div>
</div>
<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:.75rem;margin-bottom:1.5rem;">
    <div class="pw-cubi-stat--neutral" style="border-radius:8px;padding:.8rem 1rem;">
        <div style="font-size:.68rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.3rem;">Antrian Pending</div>
        <div style="font-size:1.2rem;font-weight:700;color:<?php echo e($stats['pendingCount'] > 0 ? '#ffa500' : 'var(--pw-text-light)'); ?>;"><?php echo e(number_format($stats['pendingCount'])); ?></div>
        <div style="font-size:.65rem;color:var(--pw-text-muted);margin-top:.2rem;">usecashnow — belum diproses daemon</div>
    </div>
    <div class="pw-cubi-stat--neutral" style="border-radius:8px;padding:.8rem 1rem;">
        <div style="font-size:.68rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.3rem;">Riwayat Ditampilkan</div>
        <div style="font-size:1.2rem;font-weight:700;color:var(--pw-text-light);"><?php echo e($recent->count()); ?></div>
        <div style="font-size:.65rem;color:var(--pw-text-muted);margin-top:.2rem;">100 transaksi terakhir</div>
    </div>
</div>


<div x-data="{ tab: 'recent' }">
    <div class="pw-cubi-tab-bar" style="display:flex;flex-wrap:wrap;gap:.4rem;margin-bottom:1.2rem;border-radius:8px;padding:.3rem;">
        <button @click="tab = 'recent'"
                :class="tab === 'recent' ? 'pw-cubi-tab--active pw-cubi-tab--green' : ''"
                class="pw-cubi-tab">
            Riwayat Pengiriman
        </button>
        <button @click="tab = 'pending'"
                :class="tab === 'pending' ? 'pw-cubi-tab--active pw-cubi-tab--orange' : ''"
                class="pw-cubi-tab">
            Antrian Pending
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($stats['pendingCount'] > 0): ?>
            <span style="background:rgba(255,165,0,.25);color:#ffa500;border-radius:999px;padding:.05rem .4rem;font-size:.65rem;margin-left:.3rem;"><?php echo e($stats['pendingCount']); ?></span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </button>
        <button @click="tab = 'sn'"
                :class="tab === 'sn' ? 'pw-cubi-tab--active pw-cubi-tab--blue' : ''"
                class="pw-cubi-tab">
            Distribusi SN
        </button>
    </div>

    
    <div x-show="tab === 'recent'" x-transition>
        <div style="font-size:.72rem;color:var(--pw-text-muted);margin-bottom:.6rem;display:flex;align-items:center;gap:1.2rem;flex-wrap:wrap;">
            <span>100 pengiriman Cubi terakhir dari semua jalur.</span>
            <span style="display:flex;gap:.5rem;flex-wrap:wrap;">
                <span style="background:rgba(99,179,237,.85);color:#ffffff;padding:.1rem .5rem;border-radius:3px;font-size:.68rem;">&#x25cf; Cubi Shop</span>
                <span style="background:rgba(34,197,94,.85);color:#ffffff;padding:.1rem .5rem;border-radius:3px;font-size:.68rem;">&#x25cf; Referral</span>
                <span style="background:rgba(168,85,247,.85);color:#ffffff;padding:.1rem .5rem;border-radius:3px;font-size:.68rem;">&#x25cf; Partner</span>
                <span style="background:rgba(251,191,36,.85);color:#000000;padding:.1rem .5rem;border-radius:3px;font-size:.68rem;">&#x25cf; Event</span>
                <span style="background:rgba(220,38,38,.85);color:#ffffff;padding:.1rem .5rem;border-radius:3px;font-size:.68rem;">&#x25cf; Admin</span>
                <span style="background:rgba(14,165,233,.85);color:#ffffff;padding:.1rem .5rem;border-radius:3px;font-size:.68rem;">&#x25cf; Voucher</span>
                <span style="background:rgba(120,120,120,.85);color:#ffffff;padding:.1rem .5rem;border-radius:3px;font-size:.68rem;">&#x25cf; Unknown</span>
            </span>
        </div>
        <div class="pw-cubi-table-wrap">
            <table style="width:100%;border-collapse:collapse;font-size:.78rem;">
                <thead>
                    <tr style="background:rgba(255,255,255,.03);">
                        <th style="text-align:left;padding:.6rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);white-space:nowrap;">Waktu</th>
                        <th style="text-align:left;padding:.6rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">User ID</th>
                        <th style="text-align:left;padding:.6rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">Username</th>
                        <th style="text-align:left;padding:.6rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">Karakter</th>
                        <th style="text-align:right;padding:.6rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">Cubi</th>
                        <th style="text-align:center;padding:.6rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">SN</th>
                        <th style="text-align:center;padding:.6rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">Sumber</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $recent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php
                        $src = match((int)($row->point ?? 0)) {
                            1 => ['label' => 'Cubi Shop', 'bg' => 'rgba(99,179,237,.85)',  'color' => '#ffffff'],
                            2 => ['label' => 'Referral',  'bg' => 'rgba(34,197,94,.85)',   'color' => '#ffffff'],
                            3 => ['label' => 'Partner',   'bg' => 'rgba(168,85,247,.85)',  'color' => '#ffffff'],
                            4 => ['label' => 'Event',     'bg' => 'rgba(251,191,36,.85)',  'color' => '#000000'],
                            5 => ['label' => 'Admin',     'bg' => 'rgba(220,38,38,.85)',   'color' => '#ffffff'],
                            6 => ['label' => 'Voucher',   'bg' => 'rgba(14,165,233,.85)',  'color' => '#ffffff'],
                            7 => ['label' => 'pwAdmin',   'bg' => 'rgba(255,152,0,.85)',   'color' => '#ffffff'],
                            default => ['label' => 'Unknown', 'bg' => 'rgba(120,120,120,.85)', 'color' => '#ffffff'],
                        };
                    ?>
                    <tr style="border-bottom:1px solid rgba(255,255,255,.04);">
                        <td style="padding:.5rem .8rem;color:var(--pw-text-muted);font-size:.72rem;white-space:nowrap;"><?php echo e(\Carbon\Carbon::parse($row->creatime)->translatedFormat('d M Y H:i')); ?></td>
                        <td style="padding:.5rem .8rem;color:var(--pw-text-light);font-family:monospace;"><?php echo e($row->userid); ?></td>
                        <td style="padding:.5rem .8rem;color:var(--pw-text-light);"><?php echo e($row->username ?? '—'); ?></td>
                        <td style="padding:.5rem .8rem;color:var(--pw-text-muted);"><?php echo e($row->character ?? '—'); ?></td>
                        <td style="text-align:right;padding:.5rem .8rem;font-weight:700;color:var(--pw-gold);"><?php echo e(number_format($row->cash / 100, 0, ',', '.')); ?></td>
                        <td style="text-align:center;padding:.5rem .8rem;">
                            <span class="pw-cubi-sn-chip" style="color:<?php echo e($row->sn == 1 ? '#50c878' : '#ffa500'); ?>;"><?php echo e($row->sn); ?></span>
                        </td>
                        <td style="text-align:center;padding:.5rem .8rem;">
                            <span style="background:<?php echo e($src['bg']); ?>;color:<?php echo e($src['color']); ?>;display:inline-block;width:72px;text-align:center;padding:.15rem 0;border-radius:3px;font-size:.68rem;font-weight:600;white-space:nowrap;"><?php echo e($src['label']); ?></span>
                        </td>
                    </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <tr><td colspan="7" style="padding:2rem;text-align:center;color:var(--pw-text-muted);font-size:.8rem;">Belum ada data pengiriman</td></tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div x-show="tab === 'pending'" x-cloak x-transition>
        <div style="font-size:.72rem;color:var(--pw-text-muted);margin-bottom:.6rem;">
            Antrian <code class="pw-cubi-code">usecashnow</code> — menunggu diproses billing daemon. Biasanya kosong dalam 1–5 menit.
        </div>
        <div class="pw-cubi-table-wrap">
            <table style="width:100%;border-collapse:collapse;font-size:.78rem;">
                <thead>
                    <tr style="background:rgba(255,255,255,.03);">
                        <th style="text-align:left;padding:.6rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);white-space:nowrap;">Dibuat</th>
                        <th style="text-align:left;padding:.6rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">User ID</th>
                        <th style="text-align:left;padding:.6rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">Username</th>
                        <th style="text-align:right;padding:.6rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">Cubi</th>
                        <th style="text-align:center;padding:.6rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">SN</th>
                        <th style="text-align:center;padding:.6rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $pending; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <tr style="border-bottom:1px solid rgba(255,255,255,.04);background:rgba(255,165,0,.03);">
                        <td style="padding:.5rem .8rem;color:var(--pw-text-muted);font-size:.72rem;white-space:nowrap;"><?php echo e(\Carbon\Carbon::parse($row->creatime)->translatedFormat('d M Y H:i')); ?></td>
                        <td style="padding:.5rem .8rem;color:var(--pw-text-light);font-family:monospace;"><?php echo e($row->userid); ?></td>
                        <td style="padding:.5rem .8rem;color:var(--pw-text-light);"><?php echo e($row->username ?? '—'); ?></td>
                        <td style="text-align:right;padding:.5rem .8rem;font-weight:700;color:#ffa500;"><?php echo e(number_format($row->cash / 100, 0, ',', '.')); ?></td>
                        <td style="text-align:center;padding:.5rem .8rem;">
                            <span class="pw-cubi-sn-chip"><?php echo e($row->sn); ?></span>
                        </td>
                        <td style="text-align:center;padding:.5rem .8rem;">
                            <span style="background:rgba(255,165,0,.15);color:#ffa500;padding:.15rem .5rem;border-radius:3px;font-size:.68rem;font-weight:600;">PENDING</span>
                        </td>
                    </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <tr><td colspan="6" style="padding:2rem;text-align:center;color:#50c878;font-size:.8rem;">✓ Antrian kosong — semua Cubi sudah terkirim</td></tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div x-show="tab === 'sn'" x-cloak x-transition>
        <div style="font-size:.72rem;color:var(--pw-text-muted);margin-bottom:.6rem;">
            SN = nomor urut pengiriman Cubi per user. sn=1 pengiriman pertama, sn=2 kedua, dst.
        </div>
        <div class="pw-cubi-table-wrap">
            <table style="width:100%;border-collapse:collapse;font-size:.78rem;">
                <thead>
                    <tr style="background:rgba(255,255,255,.03);">
                        <th style="text-align:center;padding:.6rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">SN</th>
                        <th style="text-align:left;padding:.6rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">Keterangan</th>
                        <th style="text-align:right;padding:.6rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">Transaksi</th>
                        <th style="text-align:right;padding:.6rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">Total Cubi</th>
                        <th style="text-align:right;padding:.6rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">User Unik</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $snDistribution; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <tr style="border-bottom:1px solid rgba(255,255,255,.04);">
                        <td style="text-align:center;padding:.5rem .8rem;">
                            <span style="background:<?php echo e($sn->sn == 1 ? 'rgba(80,200,120,.15)' : 'rgba(255,165,0,.15)'); ?>;color:<?php echo e($sn->sn == 1 ? '#50c878' : '#ffa500'); ?>;padding:.15rem .5rem;border-radius:3px;font-size:.72rem;font-weight:700;font-family:monospace;"><?php echo e($sn->sn); ?></span>
                        </td>
                        <td style="padding:.5rem .8rem;color:var(--pw-text-light);font-size:.75rem;">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sn->sn == 1): ?> Pengiriman pertama (bonus daftar / referral)
                            <?php else: ?> Pengiriman ke-<?php echo e($sn->sn); ?>

                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                        <td style="text-align:right;padding:.5rem .8rem;color:var(--pw-text-muted);"><?php echo e(number_format($sn->cnt)); ?></td>
                        <td style="text-align:right;padding:.5rem .8rem;font-weight:600;color:var(--pw-gold);"><?php echo e(number_format($sn->total_cash / 100, 0, ',', '.')); ?></td>
                        <td style="text-align:right;padding:.5rem .8rem;color:var(--pw-text-muted);"><?php echo e(number_format($sn->unique_users)); ?></td>
                    </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="pw-cubi-legend" style="margin-top:1.5rem;">
    <div style="font-size:.72rem;font-weight:600;color:var(--pw-text-muted);margin-bottom:.4rem;">Keterangan</div>
    <div class="pw-cubi-legend__text">
        <strong style="color:var(--pw-text-muted);">SN (Serial Number)</strong> — Urutan pengiriman Cubi per user. Positif = sudah terkirim (usecashlog). Negatif = antrian pending (usecashnow, belum diproses daemon)<br>
        <strong style="color:#ffa500;">PENDING</strong> — Entry di <code class="pw-cubi-code">usecashnow</code>. Daemon billing memproses ini tiap beberapa menit — player tidak perlu online<br>
        <strong style="color:#50c878;">sn=1</strong> — Pengiriman pertama (source: bonus daftar, referral, reward pertama)<br>
        <strong style="color:var(--pw-text-muted);">sn&gt;1</strong> — Top-up berikutnya via panel, Cubi Shop, reward lanjutan
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.gm', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/pw-panel/resources/views/gm/cubi-monitor.blade.php ENDPATH**/ ?>