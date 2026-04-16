<?php $__env->startSection('title', 'Cubi Monitor'); ?>

<?php $__env->startSection('content'); ?>


<div style="margin-bottom:1.2rem;display:flex;align-items:center;gap:.6rem;">
    <svg viewBox="0 0 20 20" fill="none" width="18" style="color:var(--pw-gold);"><path d="M10 2a8 8 0 110 16 8 8 0 010-16z" stroke="currentColor" stroke-width="1.5"/><path d="M10 6v4l3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M14 2l2 2M6 2L4 4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
    <h1 style="font-size:1.05rem;font-weight:700;color:var(--pw-text-light);">Cubi Monitor</h1>
    <span style="font-size:.7rem;color:var(--pw-text-muted);margin-left:auto;">Security & Audit Dashboard</span>
</div>



<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:.75rem;margin-bottom:.75rem;">
    
    <div style="background:rgba(99,179,237,.06);border:1px solid rgba(99,179,237,.2);border-radius:8px;padding:.8rem 1rem;">
        <div style="font-size:.62rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.3rem;display:flex;align-items:center;gap:.35rem;">
            <span style="width:6px;height:6px;border-radius:50%;background:#63b3ed;display:inline-block;flex-shrink:0;"></span>Cubi Shop
        </div>
        <div style="font-size:1.15rem;font-weight:700;color:#63b3ed;"><?php echo e(number_format($stats['shop_cubi'], 0, ',', '.')); ?></div>
        <div style="font-size:.65rem;color:var(--pw-text-muted);margin-top:.2rem;"><?php echo e(number_format($stats['shop_users'])); ?> pembeli</div>
    </div>
    
    <div style="background:rgba(80,200,120,.06);border:1px solid rgba(80,200,120,.2);border-radius:8px;padding:.8rem 1rem;">
        <div style="font-size:.62rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.3rem;display:flex;align-items:center;gap:.35rem;">
            <span style="width:6px;height:6px;border-radius:50%;background:#50c878;display:inline-block;flex-shrink:0;"></span>Referral
        </div>
        <div style="font-size:1.15rem;font-weight:700;color:#50c878;"><?php echo e(number_format($stats['referral_cubi'], 0, ',', '.')); ?></div>
        <div style="font-size:.65rem;color:var(--pw-text-muted);margin-top:.2rem;"><?php echo e(number_format($stats['referral_users'])); ?> pengundang</div>
    </div>
    
    <div style="background:rgba(168,85,247,.06);border:1px solid rgba(168,85,247,.2);border-radius:8px;padding:.8rem 1rem;">
        <div style="font-size:.62rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.3rem;display:flex;align-items:center;gap:.35rem;">
            <span style="width:6px;height:6px;border-radius:50%;background:#c084fc;display:inline-block;flex-shrink:0;"></span>Partner
        </div>
        <div style="font-size:1.15rem;font-weight:700;color:#c084fc;"><?php echo e(number_format($stats['partner_cubi'], 0, ',', '.')); ?></div>
        <div style="font-size:.65rem;color:var(--pw-text-muted);margin-top:.2rem;"><?php echo e(number_format($stats['partner_users'])); ?> partner</div>
    </div>
    
    <div style="background:rgba(220,60,60,.06);border:1px solid rgba(220,60,60,.2);border-radius:8px;padding:.8rem 1rem;">
        <div style="font-size:.62rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.3rem;display:flex;align-items:center;gap:.35rem;">
            <span style="width:6px;height:6px;border-radius:50%;background:#e05252;display:inline-block;flex-shrink:0;"></span>Unknown
        </div>
        <div style="font-size:1.15rem;font-weight:700;color:#e05252;"><?php echo e(number_format($stats['unknown_cubi'], 0, ',', '.')); ?></div>
        <div style="font-size:.65rem;color:var(--pw-text-muted);margin-top:.2rem;">GM/manual — perlu dicek</div>
    </div>
</div>


<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:.75rem;margin-bottom:1.5rem;">
    <div style="background:rgba(184,157,79,.06);border:1px solid rgba(184,157,79,.15);border-radius:8px;padding:.8rem 1rem;">
        <div style="font-size:.62rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.3rem;">Total Dikirim</div>
        <div style="font-size:1.15rem;font-weight:700;color:var(--pw-gold);"><?php echo e(number_format($stats['total_delivered'], 0, ',', '.')); ?></div>
        <div style="font-size:.65rem;color:var(--pw-text-muted);margin-top:.2rem;"><?php echo e(number_format($stats['total_users'])); ?> akun penerima</div>
    </div>
    <div class="pw-cubi-stat--neutral" style="border-radius:8px;padding:.8rem 1rem;">
        <div style="font-size:.62rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.3rem;">Antrian Pending</div>
        <div style="font-size:1.15rem;font-weight:700;color:var(--pw-text-light);"><?php echo e(number_format($stats['pending_queue'])); ?></div>
        <div style="font-size:.65rem;color:var(--pw-text-muted);margin-top:.2rem;">usecashnow entries</div>
    </div>
    <div class="pw-cubi-stat--neutral" style="border-radius:8px;padding:.8rem 1rem;">
        <div style="font-size:.62rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.3rem;">Akun GM</div>
        <div style="font-size:1.15rem;font-weight:700;color:var(--pw-text-light);"><?php echo e($stats['gm_count']); ?></div>
        <div style="font-size:.65rem;color:var(--pw-text-muted);margin-top:.2rem;">di tabel auth</div>
    </div>
    <div style="background:rgba(255,165,0,.06);border:1px solid rgba(255,165,0,.2);border-radius:8px;padding:.8rem 1rem;">
        <div style="font-size:.62rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.3rem;">Akun Hantu</div>
        <div style="font-size:1.15rem;font-weight:700;color:#ffa500;"><?php echo e($ghostAccounts->count()); ?></div>
        <div style="font-size:.65rem;color:var(--pw-text-muted);margin-top:.2rem;">ada di cashlog, tidak di users</div>
    </div>
</div>


<div x-data="{ tab: 'suspicious' }">
    <div class="pw-cubi-tab-bar" style="display:flex;gap:.4rem;margin-bottom:1.2rem;border-radius:8px;padding:.3rem;">
        <button @click="tab = 'suspicious'"
                :class="tab === 'suspicious' ? 'pw-cubi-tab--active pw-cubi-tab--red' : ''"
                class="pw-cubi-tab">
            Semua Transaksi Cubi
        </button>
        <button @click="tab = 'large'"
                :class="tab === 'large' ? 'pw-cubi-tab--active pw-cubi-tab--orange' : ''"
                class="pw-cubi-tab">
            Transaksi Besar
        </button>
        <button @click="tab = 'ghosts'"
                :class="tab === 'ghosts' ? 'pw-cubi-tab--active pw-cubi-tab--purple' : ''"
                class="pw-cubi-tab">
            Akun Hantu
        </button>
        <button @click="tab = 'sn'"
                :class="tab === 'sn' ? 'pw-cubi-tab--active pw-cubi-tab--green' : ''"
                class="pw-cubi-tab">
            Distribusi Sumber
        </button>
    </div>

    
    <div x-show="tab === 'suspicious'" x-transition>
        <div style="font-size:.72rem;color:var(--pw-text-muted);margin-bottom:.6rem;display:flex;align-items:center;gap:1.2rem;flex-wrap:wrap;">
            <span>Semua pengiriman Cubi (sn &gt; 1). Kolom <strong style="color:var(--pw-text);">Sumber</strong> menunjukkan asal transaksi — perhatikan baris <strong style="color:#e05252;">Unknown</strong> yang perlu dicek.</span>
            <span style="display:flex;gap:.5rem;flex-wrap:wrap;">
                <span style="background:rgba(99,179,237,.85);color:#ffffff;padding:.1rem .5rem;border-radius:3px;font-size:.68rem;">● Cubi Shop</span>
                <span style="background:rgba(34,197,94,.85);color:#ffffff;padding:.1rem .5rem;border-radius:3px;font-size:.68rem;">● Referral</span>
                <span style="background:rgba(168,85,247,.85);color:#ffffff;padding:.1rem .5rem;border-radius:3px;font-size:.68rem;">● Partner</span>
                <span style="background:rgba(251,191,36,.85);color:#000000;padding:.1rem .5rem;border-radius:3px;font-size:.68rem;">● Event</span>
                <span style="background:rgba(220,38,38,.85);color:#ffffff;padding:.1rem .5rem;border-radius:3px;font-size:.68rem;">● Admin</span>
                <span style="background:rgba(14,165,233,.85);color:#ffffff;padding:.1rem .5rem;border-radius:3px;font-size:.68rem;">● Voucher</span>
                <span style="background:rgba(255,152,0,.85);color:#ffffff;padding:.1rem .5rem;border-radius:3px;font-size:.68rem;">● pwAdmin</span>
                <span style="background:rgba(120,120,120,.85);color:#ffffff;padding:.1rem .5rem;border-radius:3px;font-size:.68rem;">● Unknown</span>
            </span>
        </div>
        <div class="pw-cubi-table-wrap">
            <table style="width:100%;border-collapse:collapse;font-size:.78rem;">
                <thead>
                    <tr style="background:rgba(255,255,255,.03);">
                        <th style="text-align:left;padding:.6rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">Waktu</th>
                        <th style="text-align:left;padding:.6rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">User ID</th>
                        <th style="text-align:left;padding:.6rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">Username</th>
                        <th style="text-align:left;padding:.6rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">Karakter</th>
                        <th style="text-align:right;padding:.6rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">Cubi</th>
                        <th style="text-align:center;padding:.6rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">SN</th>
                        <th style="text-align:center;padding:.6rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">Sumber</th>
                        <th style="text-align:center;padding:.6rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $suspicious; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php
                        $src = match((int)($s->point ?? 0)) {
                            1 => ['label' => 'Cubi Shop',  'bg' => 'rgba(99,179,237,.85)',  'color' => '#ffffff'],
                            2 => ['label' => 'Referral',   'bg' => 'rgba(34,197,94,.85)',   'color' => '#ffffff'],
                            3 => ['label' => 'Partner',    'bg' => 'rgba(168,85,247,.85)',  'color' => '#ffffff'],
                            4 => ['label' => 'Event',      'bg' => 'rgba(251,191,36,.85)',  'color' => '#000000'],
                            5 => ['label' => 'Admin',      'bg' => 'rgba(220,38,38,.85)',   'color' => '#ffffff'],
                            6 => ['label' => 'Voucher',    'bg' => 'rgba(14,165,233,.85)',  'color' => '#ffffff'],
                            7 => ['label' => 'pwAdmin',    'bg' => 'rgba(255,152,0,.85)',   'color' => '#ffffff'],
                            default => ['label' => 'Unknown', 'bg' => 'rgba(120,120,120,.85)', 'color' => '#ffffff'],
                        };
                    ?>
                    <tr style="border-bottom:1px solid rgba(255,255,255,.04);<?php echo e(($s->point ?? 0) == 0 && $s->cash >= 10000000 ? 'background:rgba(220,60,60,.04);' : ''); ?>">
                        <td style="padding:.5rem .8rem;color:var(--pw-text-muted);font-size:.72rem;white-space:nowrap;"><?php echo e(\Carbon\Carbon::parse($s->creatime)->translatedFormat('d M Y H:i')); ?></td>
                        <td style="padding:.5rem .8rem;color:var(--pw-text-light);font-family:monospace;"><?php echo e($s->userid); ?></td>
                        <td style="padding:.5rem .8rem;color:var(--pw-text-light);"><?php echo e($s->username ?? '? tidak ada'); ?></td>
                        <td style="padding:.5rem .8rem;color:var(--pw-text-muted);"><?php echo e($s->character ?? '—'); ?></td>
                        <td style="text-align:right;padding:.5rem .8rem;font-weight:700;color:<?php echo e(($s->point ?? 0) == 0 && $s->cash >= 10000000 ? '#e05252' : 'var(--pw-gold)'); ?>;"><?php echo e(number_format($s->cash / 100, 0, ',', '.')); ?></td>
                        <td style="text-align:center;padding:.5rem .8rem;">
                            <span class="pw-cubi-sn-chip"><?php echo e($s->sn); ?></span>
                        </td>
                        <td style="text-align:center;padding:.5rem .8rem;">
                            <span style="background:<?php echo e($src['bg']); ?>;color:<?php echo e($src['color']); ?>;display:inline-block;width:72px;text-align:center;padding:.15rem 0;border-radius:3px;font-size:.68rem;font-weight:600;white-space:nowrap;"><?php echo e($src['label']); ?></span>
                        </td>
                        <td style="text-align:center;padding:.5rem .8rem;">
                            <a href="<?php echo e(route('admin.cubi-monitor.user', $s->userid)); ?>" style="color:var(--pw-gold);font-size:.72rem;text-decoration:none;">Detail →</a>
                        </td>
                    </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div x-show="tab === 'large'" x-cloak x-transition>
        <div style="font-size:.72rem;color:var(--pw-text-muted);margin-bottom:.6rem;">
            Transaksi tunggal &gt; 10,000 Cubi — urutkan dari terbesar
        </div>
        <div class="pw-cubi-table-wrap">
            <table style="width:100%;border-collapse:collapse;font-size:.78rem;">
                <thead>
                    <tr style="background:rgba(255,255,255,.03);">
                        <th style="text-align:left;padding:.6rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">Waktu</th>
                        <th style="text-align:left;padding:.6rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">User ID</th>
                        <th style="text-align:left;padding:.6rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">Username</th>
                        <th style="text-align:right;padding:.6rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">Cubi</th>
                        <th style="text-align:center;padding:.6rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">SN</th>
                        <th style="text-align:center;padding:.6rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">Sumber</th>
                        <th style="text-align:center;padding:.6rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $largeTx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php
                        $lsrc = match((int)($l->point ?? 0)) {
                            1 => ['label' => 'Cubi Shop',  'bg' => 'rgba(99,179,237,.85)',  'color' => '#ffffff'],
                            2 => ['label' => 'Referral',   'bg' => 'rgba(34,197,94,.85)',   'color' => '#ffffff'],
                            3 => ['label' => 'Partner',    'bg' => 'rgba(168,85,247,.85)',  'color' => '#ffffff'],
                            4 => ['label' => 'Event',      'bg' => 'rgba(251,191,36,.85)',  'color' => '#000000'],
                            5 => ['label' => 'Admin',      'bg' => 'rgba(220,38,38,.85)',   'color' => '#ffffff'],
                            6 => ['label' => 'Voucher',    'bg' => 'rgba(14,165,233,.85)',  'color' => '#ffffff'],
                            7 => ['label' => 'pwAdmin',    'bg' => 'rgba(255,152,0,.85)',   'color' => '#ffffff'],
                            default => ['label' => 'Unknown', 'bg' => 'rgba(120,120,120,.85)', 'color' => '#ffffff'],
                        };
                    ?>
                    <tr style="border-bottom:1px solid rgba(255,255,255,.04);<?php echo e($l->cash >= 50000000 ? 'background:rgba(255,165,0,.04);' : ''); ?>">
                        <td style="padding:.5rem .8rem;color:var(--pw-text-muted);font-size:.72rem;white-space:nowrap;"><?php echo e(\Carbon\Carbon::parse($l->creatime)->translatedFormat('d M Y H:i')); ?></td>
                        <td style="padding:.5rem .8rem;color:var(--pw-text-light);font-family:monospace;"><?php echo e($l->userid); ?></td>
                        <td style="padding:.5rem .8rem;color:var(--pw-text-light);"><?php echo e($l->username ?? '? tidak ada'); ?></td>
                        <td style="text-align:right;padding:.5rem .8rem;font-weight:700;color:<?php echo e($l->cash >= 50000000 ? '#e05252' : '#ffa500'); ?>;"><?php echo e(number_format($l->cash / 100, 0, ',', '.')); ?></td>
                        <td style="text-align:center;padding:.5rem .8rem;">
                            <span class="pw-cubi-sn-chip"><?php echo e($l->sn); ?></span>
                        </td>
                        <td style="text-align:center;padding:.5rem .8rem;">
                            <span style="background:<?php echo e($lsrc['bg']); ?>;color:<?php echo e($lsrc['color']); ?>;display:inline-block;width:72px;text-align:center;padding:.15rem 0;border-radius:3px;font-size:.68rem;font-weight:600;white-space:nowrap;"><?php echo e($lsrc['label']); ?></span>
                        </td>
                        <td style="text-align:center;padding:.5rem .8rem;">
                            <a href="<?php echo e(route('admin.cubi-monitor.user', $l->userid)); ?>" style="color:var(--pw-gold);font-size:.72rem;text-decoration:none;">Detail →</a>
                        </td>
                    </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div x-show="tab === 'ghosts'" x-cloak x-transition>
        <div style="font-size:.72rem;color:var(--pw-text-muted);margin-bottom:.6rem;">
            User ID yang ada di usecashlog tapi TIDAK ada di tabel users — kemungkinan backdoor atau akun terhapus
        </div>
        <div class="pw-cubi-table-wrap">
            <table style="width:100%;border-collapse:collapse;font-size:.78rem;">
                <thead>
                    <tr style="background:rgba(255,255,255,.03);">
                        <th style="text-align:left;padding:.6rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">User ID</th>
                        <th style="text-align:right;padding:.6rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">Total Cubi</th>
                        <th style="text-align:center;padding:.6rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">Transaksi</th>
                        <th style="text-align:center;padding:.6rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">Status</th>
                        <th style="text-align:center;padding:.6rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $ghostAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <tr style="border-bottom:1px solid rgba(255,255,255,.04);background:rgba(160,100,220,.03);">
                        <td style="padding:.5rem .8rem;color:var(--pw-text-light);font-family:monospace;font-weight:600;"><?php echo e($g->userid); ?></td>
                        <td style="text-align:right;padding:.5rem .8rem;font-weight:700;color:#e05252;"><?php echo e(number_format($g->total_cash / 100, 0, ',', '.')); ?></td>
                        <td style="text-align:center;padding:.5rem .8rem;color:var(--pw-text-muted);"><?php echo e($g->tx_count); ?>×</td>
                        <td style="text-align:center;padding:.5rem .8rem;">
                            <span style="background:rgba(220,60,60,.15);color:#e05252;padding:.15rem .5rem;border-radius:3px;font-size:.68rem;font-weight:600;">GHOST</span>
                        </td>
                        <td style="text-align:center;padding:.5rem .8rem;">
                            <a href="<?php echo e(route('admin.cubi-monitor.user', $g->userid)); ?>" style="color:var(--pw-gold);font-size:.72rem;text-decoration:none;">Detail →</a>
                        </td>
                    </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div x-show="tab === 'sn'" x-cloak x-transition>
        <div style="font-size:.72rem;color:var(--pw-text-muted);margin-bottom:.6rem;">
            Total Cubi yang terdistribusi per sumber — dihitung dari data panel (pw_invoices &amp; pw_referral_rewards). Unknown = selisih game DB vs panel.
        </div>
        <div class="pw-cubi-table-wrap">
            <table style="width:100%;border-collapse:collapse;font-size:.78rem;">
                <thead>
                    <tr style="background:rgba(255,255,255,.03);">
                        <th style="text-align:left;padding:.6rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">Sumber</th>
                        <th style="text-align:right;padding:.6rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">Jumlah Transaksi</th>
                        <th style="text-align:right;padding:.6rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">Total Cubi</th>
                        <th style="text-align:right;padding:.6rem .8rem;color:var(--pw-text-muted);font-size:.7rem;border-bottom:1px solid rgba(255,255,255,.06);">User Unik</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $sourceDistribution; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $src): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <tr style="border-bottom:1px solid rgba(255,255,255,.04);">
                        <td style="padding:.5rem .8rem;">
                            <span style="background:<?php echo e($src['bg']); ?>;color:<?php echo e($src['color']); ?>;padding:.2rem .65rem;border-radius:3px;font-size:.72rem;font-weight:600;"><?php echo e($src['label']); ?></span>
                        </td>
                        <td style="text-align:right;padding:.5rem .8rem;color:var(--pw-text-muted);">
                            <?php echo e($src['count'] !== null ? number_format($src['count']) : '—'); ?>

                        </td>
                        <td style="text-align:right;padding:.5rem .8rem;font-weight:600;color:var(--pw-text-light);">
                            <?php echo e(number_format($src['total'], 0, ',', '.')); ?>

                        </td>
                        <td style="text-align:right;padding:.5rem .8rem;color:var(--pw-text-muted);">
                            <?php echo e($src['users'] !== null ? number_format($src['users']) : '—'); ?>

                        </td>
                    </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="pw-cubi-legend" style="margin-top:1.5rem;">
    <div style="font-size:.72rem;font-weight:600;color:var(--pw-text-muted);margin-bottom:.4rem;">Keterangan Sumber Cubi</div>
    <div class="pw-cubi-legend__text">
        <strong style="color:#63b3ed;">Cubi Shop</strong> — Pembelian Cubi melalui toko (pw_invoices type=cubi)<br>
        <strong style="color:#50c878;">Referral</strong> — Reward Cubi untuk pengundang biasa (bukan partner), dari pw_referral_rewards type=registration_cubi<br>
        <strong style="color:#c084fc;">Partner</strong> — Reward Cubi untuk akun partner terdaftar (pw_referral_partners)<br>
        <strong style="color:#0ea5e9;">Voucher</strong> — Cubi dari redeem voucher (tercatat di pw_admin_cubi_topups reason=Voucher:...)<br>
        <strong style="color:#ff9800;">pwAdmin</strong> — Cubi yang ditambahkan melalui pwAdmin (tercatat di pwadmin_cubi_log)<br>
        <strong style="color:#e05252;">Unknown</strong> — Selisih antara total di game DB dan total panel — kemungkinan pemberian via GM tool atau data lama<br>
        <strong style="color:#ffa500;">Ghost Account</strong> — User ID ada di log Cubi tapi tidak ada di tabel users
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/pw-panel/resources/views/admin/cubi-monitor.blade.php ENDPATH**/ ?>