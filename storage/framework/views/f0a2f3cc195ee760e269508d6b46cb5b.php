<?php $__env->startSection('title', 'Kelola Voucher'); ?>

<?php $__env->startSection('content'); ?>

<div class="pw-adm-card" style="margin-bottom:1.5rem;">
    <div class="pw-adm-card__title">Generate Voucher Massal</div>
    <form action="<?php echo e(route('admin.voucher.generate')); ?>" method="POST"
          style="display:grid;grid-template-columns:repeat(12,minmax(0,1fr));gap:.8rem;align-items:end;">
        <?php echo csrf_field(); ?>
        <div style="grid-column:span 3;">
            <label class="pw-form__label">Deskripsi Batch</label>
            <input type="text" name="description" class="pw-form__input" placeholder="Event Natal 2025" style="width:100%;" value="<?php echo e(old('description')); ?>">
        </div>
        <div style="grid-column:span 1;">
            <label class="pw-form__label">Jumlah</label>
            <input type="number" name="count" class="pw-form__input" required min="1" max="500" value="<?php echo e(old('count', 10)); ?>" style="width:100%;">
        </div>
        <div style="grid-column:span 2;">
            <label class="pw-form__label">Tipe Reward</label>
            <select name="type" class="pw-form__input" required style="width:100%;">
                <option value="gold_points" <?php echo e(old('type') === 'gold_points' ? 'selected' : ''); ?>>Gold Points</option>
                <option value="cubi" <?php echo e(old('type') === 'cubi' ? 'selected' : ''); ?>>Cubi Gold</option>
            </select>
        </div>
        <div style="grid-column:span 1;">
            <label class="pw-form__label">Nilai Reward</label>
            <input type="number" name="value" class="pw-form__input" required min="1" value="<?php echo e(old('value', 100)); ?>" style="width:100%;">
        </div>
        <div style="grid-column:span 2;">
            <label class="pw-form__label">Kuota per Voucher</label>
            <input type="number" name="max_uses" class="pw-form__input" min="1" placeholder="Kosong = tak terbatas" value="<?php echo e(old('max_uses')); ?>" style="width:100%;">
        </div>
        <div style="grid-column:span 2;">
            <label class="pw-form__label">Expired At</label>
            <input type="datetime-local" name="expires_at" class="pw-form__input" value="<?php echo e(old('expires_at')); ?>" style="width:100%;">
        </div>
        <div style="grid-column:span 1;display:flex;align-items:end;">
            <button type="submit" class="pw-adm-btn" style="width:100%;">Generate</button>
        </div>
    </form>
</div>

<div class="pw-adm-card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.2rem;flex-wrap:wrap;gap:.6rem;">
        <div style="color:var(--pw-text-muted);font-size:.83rem;">Total: <?php echo e($vouchers->total()); ?> voucher</div>
        <a href="<?php echo e(route('admin.voucher.create')); ?>" class="pw-adm-btn">+ Buat Satu</a>
    </div>

    <div class="pw-table-wrap">
        <table class="pw-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Deskripsi</th>
                    <th>Tipe</th>
                    <th>Reward</th>
                    <th>Terpakai</th>
                    <th>Tanggal Digunakan</th>
                    <th>Expired</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $vouchers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <tr>
                    <td><code style="font-size:.78rem;letter-spacing:.08em;"><?php echo e($v->code); ?></code></td>
                    <td style="color:var(--pw-text-muted);"><?php echo e($v->description ?: '—'); ?></td>
                    <td>
                        <span class="pw-badge <?php echo e($v->normalized_type === 'cubi' ? 'pw-badge--danger' : 'pw-badge--success'); ?>">
                            <?php echo e($v->reward_type_label); ?>

                        </span>
                    </td>
                    <td><strong style="color:#b89d4f;"><?php echo e(number_format($v->value)); ?></strong></td>
                    <td style="color:var(--pw-text-muted);font-size:.78rem;">
                        <?php echo e(number_format($v->used_count)); ?> / <?php echo e($v->max_uses ? number_format($v->max_uses) : '∞'); ?>

                    </td>
                    <td style="color:var(--pw-text-muted);font-size:.75rem;">
                        <?php echo e($v->logs_max_created_at ? \Carbon\Carbon::parse($v->logs_max_created_at)->format('d M Y H:i') : 'Belum dipakai'); ?>

                    </td>
                    <td style="color:var(--pw-text-muted);font-size:.75rem;"><?php echo e($v->expires_at ? $v->expires_at->format('d M Y H:i') : 'Tidak ada'); ?></td>
                    <td>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$v->is_active): ?>
                            <span class="pw-badge">Nonaktif</span>
                        <?php elseif($v->expires_at && $v->expires_at->isPast()): ?>
                            <span class="pw-badge pw-badge--danger">Expired</span>
                        <?php elseif($v->max_uses !== null && $v->used_count >= $v->max_uses): ?>
                            <span class="pw-badge pw-badge--danger">Kuota Habis</span>
                        <?php else: ?>
                            <span class="pw-badge pw-badge--success">Aktif</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                    <td style="display:flex;gap:.3rem;">
                        <a href="<?php echo e(route('admin.voucher.edit', $v->id)); ?>" class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--ghost">Edit</a>
                        <form action="<?php echo e(route('admin.voucher.destroy', $v->id)); ?>" method="POST"
                              data-confirm="Hapus Voucher|Yakin ingin menghapus voucher ini?">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--ghost" style="color:#e05252;">Hapus</button>
                        </form>
                    </td>
                </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <tr>
                    <td colspan="10" style="text-align:center;color:var(--pw-text-muted);">Belum ada voucher.</td>
                </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    </div>

    <div style="margin-top:1rem;"><?php echo e($vouchers->links()); ?></div>
</div>

<div class="pw-adm-card" style="margin-top:1.5rem;">
    <div class="pw-adm-card__title">Riwayat Pemakaian Voucher</div>
    <div class="pw-table-wrap">
        <table class="pw-table">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Nama User</th>
                    <th>Username (Sensor)</th>
                    <th>Kode Voucher</th>
                    <th>Tipe</th>
                    <th>Nilai</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $recentLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <tr>
                    <td style="color:var(--pw-text-muted);font-size:.75rem;"><?php echo e(optional($log->created_at)->format('d M Y H:i')); ?></td>
                    <td><?php echo e($log->user->truename ?? 'Tanpa Nama'); ?></td>
                    <td><?php echo e($log->user->name ?? ('UID' . $log->user_id)); ?></td>
                    <td><code style="font-size:.78rem;letter-spacing:.08em;"><?php echo e($log->voucher->code ?? '—'); ?></code></td>
                    <td><?php echo e($log->voucher?->reward_type_label ?? '—'); ?></td>
                    <td style="color:#b89d4f;font-weight:600;"><?php echo e(number_format($log->value_received)); ?></td>
                </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <tr>
                    <td colspan="6" style="text-align:center;color:var(--pw-text-muted);">Belum ada pemakaian voucher.</td>
                </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;"><?php echo e($recentLogs->appends(request()->except('usage_page'))->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/pw-panel/resources/views/admin/voucher/index.blade.php ENDPATH**/ ?>