<?php $__env->startSection('title', 'Manajemen Staff'); ?>

<?php $__env->startSection('content'); ?>
<div class="pw-adm-cols" style="align-items:flex-start;gap:1.5rem;">

    
    <div style="flex:0 0 280px;">
        <div class="pw-adm-card">
            <div class="pw-adm-card__title">Angkat Staff Baru</div>
            <form action="<?php echo e(route('admin.gm.promote')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <label class="pw-form__label">Username</label>
                <input type="text" name="username" class="pw-form__input" required
                       placeholder="Masukkan username" value="<?php echo e(old('username')); ?>"
                       style="margin-bottom:.8rem;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p style="color:#e05252;font-size:.75rem;margin-top:-.5rem;margin-bottom:.6rem;"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <label class="pw-form__label">Role</label>
                <select name="role" class="pw-form__input" required style="margin-bottom:.8rem;">
                    <option value="webadmin">Web Admin</option>
                    <option value="gm" selected>Game Master (GM)</option>
                </select>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p style="color:#e05252;font-size:.75rem;margin-top:-.5rem;margin-bottom:.6rem;"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <button type="submit" class="pw-adm-btn" style="width:100%;">Angkat Staff</button>
            </form>

            <div style="margin-top:.8rem;padding-top:.8rem;border-top:1px solid var(--pw-border);">
                <div style="font-size:.72rem;color:var(--pw-text-muted);line-height:1.6;">
                    <strong style="color:var(--pw-text);">Web Admin</strong> — Bisa kelola konten panel (berita, shop, vote, voucher, layanan, ranking, member read-only).<br>
                    <strong style="color:var(--pw-text);">GM</strong> — Bisa kelola artikel & lihat member di GM Panel. Permission in-game bisa diatur terpisah.
                </div>
            </div>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$gameDbOk): ?>
        <div class="pw-adm-alert pw-adm-alert--error" style="margin-top:.8rem;font-size:.78rem;">
            Koneksi ke Game DB gagal.<br>
            Permission in-game tidak dapat dikelola. Pastikan konfigurasi <code>mysql_game</code> benar di <code>.env</code>.
        </div>
        <?php else: ?>
        <div class="pw-adm-alert" style="margin-top:.8rem;font-size:.78rem;">
            Game DB terhubung. Permission in-game aktif.
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="pw-adm-card" style="margin-top:.8rem;">
            <div class="pw-adm-card__title" style="font-size:.8rem;">Daftar Permission</div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $perms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rid => $desc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <div class="pw-perm-row" style="display:flex;align-items:center;gap:.5rem;font-size:.72rem;padding:.25rem 0;">
                <span class="pw-perm-rid" style="border-radius:4px;padding:1px 5px;min-width:28px;text-align:center;"><?php echo e($rid); ?></span>
                <span style="color:var(--pw-text-muted);"><?php echo e($desc); ?></span>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
    </div>

    
    <div style="flex:1;min-width:0;">
        <div class="pw-adm-card">
            <div class="pw-adm-card__title">Daftar Staff (<?php echo e($gms->count()); ?>)</div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($gms->isEmpty()): ?>
                <p style="color:var(--pw-text-muted);font-size:.82rem;">Belum ada staff terdaftar.</p>
            <?php else: ?>
            <div style="overflow-x:auto;">
                <table class="pw-adm-tbl" style="width:100%;">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Role</th>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($gameDbOk): ?> <th>Permission In-Game</th> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $gms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <tr>
                            <td>
                                <strong><?php echo e($gm->name); ?></strong><br>
                                <span style="font-size:.72rem;color:var(--pw-text-muted);"><?php echo e($gm->email); ?></span>
                            </td>
                            <td>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($gm->role === 'admin'): ?>
                                <span class="pw-badge pw-badge--admin">SUPERADMIN</span>
                                <?php elseif($gm->role === 'webadmin'): ?>
                                <span class="pw-badge pw-badge--webadmin">WEB ADMIN</span>
                                <?php else: ?>
                                <span class="pw-badge pw-badge--active">GM</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($gameDbOk): ?>
                            <td>
                                <form action="<?php echo e(route('admin.gm.perms', $gm->ID)); ?>" method="POST" id="perm-form-<?php echo e($gm->ID); ?>" style="display:none;">
                                    <?php echo csrf_field(); ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $perms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rid => $desc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <label style="display:flex;align-items:center;gap:.4rem;font-size:.73rem;margin-bottom:.2rem;">
                                        <input type="checkbox" name="rids[]" value="<?php echo e($rid); ?>"
                                            <?php echo e(in_array($rid, $authRows[$gm->ID] ?? []) ? 'checked' : ''); ?>

                                            onchange="document.getElementById('perm-form-<?php echo e($gm->ID); ?>').dispatchEvent(new Event('change'))">
                                        <span>[<?php echo e($rid); ?>] <?php echo e($desc); ?></span>
                                    </label>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </form>

                                <div id="perm-display-<?php echo e($gm->ID); ?>">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $authRows[$gm->ID] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rid): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <span class="pw-perm-active-rid" style="border-radius:3px;padding:1px 5px;font-size:.7rem;margin:1px;"><?php echo e($rid); ?></span>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        <span style="color:var(--pw-text-muted);font-size:.75rem;">Tidak ada</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <button type="button" onclick="togglePerms(<?php echo e($gm->ID); ?>)"
                                        class="pw-perm-edit-btn" style="margin-top:.4rem;font-size:.72rem;border-radius:4px;padding:2px 8px;cursor:pointer;">
                                    Edit Permission
                                </button>
                                <button type="button" id="save-perm-<?php echo e($gm->ID); ?>" onclick="savePerms(<?php echo e($gm->ID); ?>)"
                                        class="pw-perm-save-btn" style="display:none;margin-top:.4rem;font-size:.72rem;border-radius:4px;padding:2px 8px;cursor:pointer;">
                                    Simpan
                                </button>
                            </td>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <td>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($gm->role !== 'admin'): ?>
                                <form action="<?php echo e(route('admin.gm.demote', $gm->ID)); ?>" method="POST"
                                      data-confirm="Demote Staff|Demote <?php echo e($gm->name); ?> menjadi player biasa?"
                                      data-confirm-ok="Ya, Demote">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="pw-adm-btn pw-adm-btn--danger" style="font-size:.75rem;padding:.3rem .7rem;">
                                        Demote
                                    </button>
                                </form>
                                <?php else: ?>
                                <span style="font-size:.72rem;color:var(--pw-text-muted);">Superadmin</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                        </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($gameDbOk): ?>
<script>
function togglePerms(id) {
    const form = document.getElementById('perm-form-' + id);
    const display = document.getElementById('perm-display-' + id);
    const saveBtn = document.getElementById('save-perm-' + id);
    const isHidden = form.style.display === 'none';
    form.style.display = isHidden ? 'block' : 'none';
    display.style.display = isHidden ? 'none' : 'block';
    saveBtn.style.display = isHidden ? 'inline-block' : 'none';
}

function savePerms(id) {
    document.getElementById('perm-form-' + id).submit();
}
</script>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/pw-panel/resources/views/admin/gm/index.blade.php ENDPATH**/ ?>