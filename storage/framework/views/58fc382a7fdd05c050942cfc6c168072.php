<?php $__env->startSection('title', 'Detail Member: ' . $user->name); ?>

<?php $__env->startSection('content'); ?>

<div style="margin-bottom:1rem;">
    <a href="<?php echo e(route('admin.members.index')); ?>" class="pw-adm-btn pw-adm-btn--ghost" style="display:inline-flex;align-items:center;gap:.4rem;">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5m0 0l7 7m-7-7l7-7"/></svg>
        Kembali
    </a>
</div>

<div style="display:grid;grid-template-columns:1fr 2fr;gap:1.5rem;align-items:start;">

    
    <div class="pw-adm-card">
        <div class="pw-adm-card__title">Profil</div>
        <div style="text-align:center;margin-bottom:1.2rem;">
            <div style="width:64px;height:64px;border-radius:50%;background:var(--pw-gold-dark,#6b5420);display:inline-flex;align-items:center;justify-content:center;font-size:1.6rem;font-weight:700;color:#b89d4f;margin-bottom:.6rem;">
                <?php echo e(strtoupper(substr($user->name,0,1))); ?>

            </div>
            <div style="font-weight:600;"><?php echo e($user->name); ?></div>
            <div style="color:var(--pw-text-muted);font-size:.8rem;"><?php echo e($user->email); ?></div>
            <div style="margin-top:.4rem;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->role === 'admin'): ?>
                    <span class="pw-badge pw-badge--danger">Admin</span>
                <?php elseif($user->role === 'gm'): ?>
                    <span class="pw-badge pw-badge--warning">GM</span>
                <?php else: ?>
                    <span class="pw-badge">Player</span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <table style="width:100%;font-size:.82rem;border-collapse:collapse;">
            <tr><td style="color:var(--pw-text-muted);padding:.3rem 0;">Gold Points</td><td style="font-weight:600;color:#b89d4f;"><?php echo e(number_format($user->money)); ?></td></tr>
            <tr>
                <td style="color:var(--pw-text-muted);padding:.3rem 0;">Cubi Gold</td>
                <td style="font-weight:600;color:#4fad84;">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($cubiData): ?>
                        <?php echo e(number_format(($cubiData['cash_add'] + $cubiData['cash_buy'] - $cubiData['cash_used'] - $cubiData['cash_sell']) / 100)); ?>

                    <?php else: ?>
                        <span style="color:var(--pw-text-muted);font-weight:400;">Offline</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </td>
            </tr>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($cubiData): ?>
            <tr><td style="color:var(--pw-text-muted);padding:.3rem 0;font-size:.75rem;">- Total Top-up</td><td style="font-size:.75rem;"><?php echo e(number_format($cubiData['cash_add'] / 100)); ?></td></tr>
            <tr><td style="color:var(--pw-text-muted);padding:.3rem 0;font-size:.75rem;">- Digunakan</td><td style="font-size:.75rem;"><?php echo e(number_format($cubiData['cash_used'] / 100)); ?></td></tr>
            <tr><td style="color:var(--pw-text-muted);padding:.3rem 0;font-size:.75rem;">- Beli (Trade)</td><td style="font-size:.75rem;"><?php echo e(number_format($cubiData['cash_buy'] / 100)); ?></td></tr>
            <tr><td style="color:var(--pw-text-muted);padding:.3rem 0;font-size:.75rem;">- Jual (Trade)</td><td style="font-size:.75rem;"><?php echo e(number_format($cubiData['cash_sell'] / 100)); ?></td></tr>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <tr><td style="color:var(--pw-text-muted);padding:.3rem 0;">No HP</td><td><?php echo e($user->mobilenumber ?: '-'); ?></td></tr>
            <tr>
                <td style="color:var(--pw-text-muted);padding:.3rem 0;">Kode Referral</td>
                <td>
                    <code style="font-size:.8rem;background:rgba(200,151,42,.1);color:var(--pw-gold);padding:.15rem .45rem;border-radius:4px;letter-spacing:.06em;"><?php echo e($user->referral_code ?? '—'); ?></code>
                </td>
            </tr>
            <tr>
                <td style="color:var(--pw-text-muted);padding:.3rem 0;">Diundang Oleh</td>
                <td>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->referrer): ?>
                        <a href="<?php echo e(route('admin.members.show', $user->referrer->ID)); ?>"
                           style="font-size:.82rem;color:var(--pw-gold);text-decoration:none;font-weight:600;">
                            <?php echo e($user->referrer->name); ?>

                        </a>
                    <?php else: ?>
                        <span style="color:var(--pw-text-muted);font-size:.82rem;">— (daftar mandiri)</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </td>
            </tr>
            <tr><td style="color:var(--pw-text-muted);padding:.3rem 0;">Bergabung</td><td><?php echo e($user->creatime?->translatedFormat('d M Y') ?? '-'); ?></td></tr>
            <tr>
                <td style="color:var(--pw-text-muted);padding:.3rem 0;">Status</td>
                <td>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->banned_at): ?>
                        <span class="pw-badge pw-badge--danger">Banned</span>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->banned_until): ?>
                            <span style="font-size:.7rem;color:var(--pw-text-muted);"> s/d <?php echo e(\Carbon\Carbon::parse($user->banned_until)->format('d M Y')); ?></span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php else: ?>
                        <span class="pw-badge pw-badge--success">Aktif</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </td>
            </tr>
        </table>

        
        <form action="<?php echo e(route('admin.members.update', $user->ID)); ?>" method="POST" style="margin-top:1.2rem;">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <label class="pw-form__label">Role</label>
            <select name="role" class="pw-form__input" style="margin-bottom:.6rem;">
                <option value="player"  <?php if($user->role=='player'): echo 'selected'; endif; ?>>Player</option>
                <option value="gm"      <?php if($user->role=='gm'): echo 'selected'; endif; ?>>GM</option>
                <option value="admin"   <?php if($user->role=='admin'): echo 'selected'; endif; ?>>Admin</option>
            </select>
            <label class="pw-form__label">Email</label>
            <input type="email" name="email" class="pw-form__input" value="<?php echo e($user->email); ?>" style="margin-bottom:.8rem;">
            <button type="submit" class="pw-adm-btn" style="width:100%;">Simpan Perubahan</button>
        </form>

        
        <div style="margin-top:.8rem;display:flex;flex-direction:column;gap:.4rem;">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->isAdministrator()): ?>
            
            <button type="button" class="pw-adm-btn pw-adm-btn--ghost" onclick="document.getElementById('topupModal').style.display='flex'">
                + Top-up Gold Points
            </button>

            
            <button type="button" class="pw-adm-btn pw-adm-btn--ghost" style="color:#4fad84;" onclick="document.getElementById('cubiTopupModal').style.display='flex'">
                + Isi Cubi Gold
            </button>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <button type="button" class="pw-adm-btn pw-adm-btn--ghost" onclick="document.getElementById('resetPwModal').style.display='flex'">
                Reset Password
            </button>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->isAdministrator()): ?>
            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->banned_at): ?>
            <form action="<?php echo e(route('admin.members.unban', $user->ID)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" class="pw-adm-btn pw-adm-btn--ghost" style="width:100%;color:#4fad84;">Unban</button>
            </form>
            <?php else: ?>
            <button type="button" class="pw-adm-btn pw-adm-btn--ghost"
                    style="color:#e05252;" onclick="document.getElementById('banModal').style.display='flex'">
                Ban User
            </button>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->ID !== auth()->id()): ?>
            <button type="button" class="pw-adm-btn pw-adm-btn--ghost" style="width:100%;color:#e05252;"
                    onclick="document.getElementById('deleteModal').style.display='flex'">
                Hapus Akun
            </button>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    
    <div>
        
        <?php
            $classIconMap = [
                0 => 'blademaster', 1 => 'wizzard', 2 => 'cleric', 3 => 'archer',
                4 => 'barbarian', 5 => 'venomancer', 6 => 'assasin', 7 => 'psychic',
                8 => 'seeker', 9 => 'mystic', 10 => 'duskblade', 11 => 'stormbringer',
            ];
        ?>
        <div class="pw-adm-card" style="margin-bottom:1rem;">
            <div class="pw-adm-card__title">Karakter Game (<?php echo e($characters->count()); ?>)</div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($characters->isEmpty()): ?>
                <p style="color:var(--pw-text-muted);font-size:.82rem;">Tidak ada karakter ditemukan.</p>
            <?php else: ?>
            <div class="pw-table-wrap">
                <table class="pw-table">
                    <thead><tr><th>Nama</th><th>Class</th><th>Level</th><th>Gender</th><th>Terakhir Online</th></tr></thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $characters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $char): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <?php
                            $lastloginTs = $rolesData[$char->role_id]['base']['lastlogin'] ?? null;
                            $lastloginStr = ($lastloginTs && $lastloginTs > 0)
                                ? \Carbon\Carbon::createFromTimestamp($lastloginTs)->locale('id')->translatedFormat('d F Y, H:i')
                                : '-';
                        ?>
                        <tr>
                            <td style="font-weight:600;"><a href="<?php echo e(route('admin.members.character', [$user->ID, $char->role_id])); ?>" style="color:var(--pw-gold);text-decoration:none;"><?php echo e($char->name); ?></a></td>
                            <td style="display:flex;align-items:center;gap:.4rem;">
                                <img src="/images/class/<?php echo e(($classIconMap[$char->class_id] ?? 'blademaster') . '.png'); ?>" alt="<?php echo e($char->class); ?>" width="22" height="22" style="flex-shrink:0;">
                                <?php echo e($char->class); ?>

                            </td>
                            <td><?php echo e($char->level); ?></td>
                            <td><?php echo e($char->gender == 0 ? 'Male' : 'Female'); ?></td>
                            <td style="font-size:.78rem;color:var(--pw-text-muted);"><?php echo e($lastloginStr); ?></td>
                        </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        
        <div class="pw-adm-card" style="margin-bottom:1rem;">
            <div class="pw-adm-card__title">Riwayat Donate (<?php echo e($user->invoices->count()); ?>)</div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->invoices->isEmpty()): ?>
                <p style="color:var(--pw-text-muted);font-size:.82rem;">Belum ada transaksi.</p>
            <?php else: ?>
            <div class="pw-table-wrap">
                <table class="pw-table">
                    <thead><tr><th>Invoice</th><th>Gold Points</th><th>Nominal</th><th>Status</th><th>Tanggal</th></tr></thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $user->invoices->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <tr>
                            <td style="font-size:.75rem;color:var(--pw-text-muted);"><?php echo e($inv->invoice_number); ?></td>
                            <td><?php echo e(number_format($inv->gold_amount)); ?></td>
                            <td>Rp <?php echo e(number_format($inv->unique_amount)); ?></td>
                            <td>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($inv->status==='paid'): ?> <span class="pw-badge pw-badge--success">Paid</span>
                                <?php elseif($inv->status==='pending'): ?> <span class="pw-badge pw-badge--warning">Pending</span>
                                <?php else: ?> <span class="pw-badge pw-badge--danger">Gagal</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td style="font-size:.75rem;color:var(--pw-text-muted);"><?php echo e($inv->created_at->format('d M Y')); ?></td>
                        </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div class="pw-adm-card" style="margin-bottom:1rem;">
            <div class="pw-adm-card__title">Riwayat Shop (<?php echo e($user->shopLogs->count()); ?>)</div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->shopLogs->isEmpty()): ?>
                <p style="color:var(--pw-text-muted);font-size:.82rem;">Belum ada pembelian.</p>
            <?php else: ?>
            <div class="pw-table-wrap">
                <table class="pw-table">
                    <thead><tr><th>Item</th><th>Harga</th><th>Tanggal</th></tr></thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $user->shopLogs->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <tr>
                            <td><?php echo e($log->item_name); ?></td>
                            <td><?php echo e(number_format($log->price)); ?> Gold Points</td>
                            <td style="font-size:.75rem;color:var(--pw-text-muted);"><?php echo e($log->created_at->format('d M Y H:i')); ?></td>
                        </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div class="pw-adm-card">
            <div class="pw-adm-card__title">Pesanan Layanan (<?php echo e($user->serviceLogs->count()); ?>)</div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->serviceLogs->isEmpty()): ?>
                <p style="color:var(--pw-text-muted);font-size:.82rem;">Belum ada pesanan.</p>
            <?php else: ?>
            <div class="pw-table-wrap">
                <table class="pw-table">
                    <thead><tr><th>Layanan</th><th>Harga</th><th>Status</th><th>Tanggal</th></tr></thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $user->serviceLogs->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <tr>
                            <td><?php echo e($log->service_name); ?></td>
                            <td><?php echo e(number_format($log->price)); ?> Gold Points</td>
                            <td><span class="pw-badge <?php if($log->status==='completed'): ?> pw-badge--success <?php elseif($log->status==='rejected'): ?> pw-badge--danger <?php else: ?> pw-badge--warning <?php endif; ?>"><?php echo e(ucfirst($log->status)); ?></span></td>
                            <td style="font-size:.75rem;color:var(--pw-text-muted);"><?php echo e($log->created_at->format('d M Y')); ?></td>
                        </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div>


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->isAdministrator()): ?>
<div id="topupModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:100;align-items:center;justify-content:center;">
    <div class="pw-adm-card" style="min-width:320px;max-width:420px;width:100%;">
        <div class="pw-adm-card__title">Top-up Gold Points — <?php echo e($user->name); ?></div>
        <form action="<?php echo e(route('admin.members.topup', $user->ID)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <label class="pw-form__label">Jumlah Gold Points</label>
            <input type="number" name="amount" class="pw-form__input" min="1" max="100000" required style="margin-bottom:.6rem;">
            <label class="pw-form__label">Alasan</label>
            <input type="text" name="reason" class="pw-form__input" placeholder="Event reward, manual topup, dll." required style="margin-bottom:.8rem;">
            <div style="display:flex;gap:.5rem;">
                <button type="submit" class="pw-adm-btn">Tambahkan</button>
                <button type="button" class="pw-adm-btn pw-adm-btn--ghost" onclick="document.getElementById('topupModal').style.display='none'">Batal</button>
            </div>
        </form>
    </div>
</div>


<div id="cubiTopupModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:100;align-items:center;justify-content:center;">
    <div class="pw-adm-card" style="min-width:320px;max-width:420px;width:100%;">
        <div class="pw-adm-card__title" style="color:#4fad84;">Isi Cubi Gold — <?php echo e($user->name); ?></div>
        <p style="font-size:.8rem;color:var(--pw-text-muted);margin-bottom:.8rem;">Cubi akan masuk ke akun game saat user login/relog.</p>
        <form action="<?php echo e(route('admin.members.cubi-topup', $user->ID)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <label class="pw-form__label">Jumlah Cubi</label>
            <input type="number" name="amount" class="pw-form__input" min="1" max="999999" required style="margin-bottom:.6rem;">
            <label class="pw-form__label">Alasan</label>
            <input type="text" name="reason" class="pw-form__input" placeholder="Kompensasi, event reward, dll." required style="margin-bottom:.8rem;">
            <div style="display:flex;gap:.5rem;">
                <button type="submit" class="pw-adm-btn" style="background:#4fad84;border-color:#4fad84;">Kirim Cubi</button>
                <button type="button" class="pw-adm-btn pw-adm-btn--ghost" onclick="document.getElementById('cubiTopupModal').style.display='none'">Batal</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


<div id="resetPwModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:100;align-items:center;justify-content:center;">
    <div class="pw-adm-card" style="min-width:320px;max-width:420px;width:100%;">
        <div class="pw-adm-card__title">Reset Password — <?php echo e($user->name); ?></div>
        <form action="<?php echo e(route('admin.members.reset-password', $user->ID)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <label class="pw-form__label">Password Baru</label>
            <input type="text" name="new_password" class="pw-form__input" required minlength="6" pattern="[a-z0-9]+"
                   placeholder="Huruf kecil & angka saja, min 6 karakter" style="margin-bottom:.4rem;">
            <p style="font-size:.72rem;color:var(--pw-text-muted);margin-bottom:.8rem;">Hanya huruf kecil (a-z) dan angka (0-9). Minimal 6 karakter.</p>
            <div style="display:flex;gap:.5rem;">
                <button type="submit" class="pw-adm-btn">Reset Password</button>
                <button type="button" class="pw-adm-btn pw-adm-btn--ghost" onclick="document.getElementById('resetPwModal').style.display='none'">Batal</button>
            </div>
        </form>
    </div>
</div>


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->isAdministrator()): ?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->ID !== auth()->id()): ?>
<div id="deleteModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:100;align-items:center;justify-content:center;">
    <div class="pw-adm-card" style="min-width:320px;max-width:420px;width:100%;">
        <div class="pw-adm-card__title" style="color:#e05252;">Hapus Akun — <?php echo e($user->name); ?></div>
        <p style="font-size:.82rem;color:var(--pw-text-muted);margin-bottom:1rem;">Akun ini akan dihapus secara permanen dan tidak dapat dikembalikan. Yakin ingin melanjutkan?</p>
        <form action="<?php echo e(route('admin.members.destroy', $user->ID)); ?>" method="POST">
            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
            <div style="display:flex;gap:.5rem;">
                <button type="submit" class="pw-adm-btn" style="background:#e05252;border-color:#e05252;">Ya, Hapus</button>
                <button type="button" class="pw-adm-btn pw-adm-btn--ghost" onclick="document.getElementById('deleteModal').style.display='none'">Batal</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->isAdministrator()): ?>
<div id="banModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:100;align-items:center;justify-content:center;">
    <div class="pw-adm-card" style="min-width:320px;max-width:420px;width:100%;">
        <div class="pw-adm-card__title" style="color:#e05252;">Ban User — <?php echo e($user->name); ?></div>
        <form action="<?php echo e(route('admin.members.ban', $user->ID)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <label class="pw-form__label">Alasan Ban</label>
            <input type="text" name="reason" class="pw-form__input" placeholder="Cheating, AFK farming, dll." required style="margin-bottom:.6rem;">
            <label class="pw-form__label">Ban Sampai (kosong = permanen)</label>
            <input type="text" name="banned_until" class="pw-form__input pw-datepicker" placeholder="Pilih tanggal & waktu" style="margin-bottom:.8rem;">
            <div style="display:flex;gap:.5rem;">
                <button type="submit" class="pw-adm-btn" style="background:#e05252;border-color:#e05252;">Ban</button>
                <button type="button" class="pw-adm-btn pw-adm-btn--ghost" onclick="document.getElementById('banModal').style.display='none'">Batal</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/pw-panel/resources/views/admin/members/show.blade.php ENDPATH**/ ?>