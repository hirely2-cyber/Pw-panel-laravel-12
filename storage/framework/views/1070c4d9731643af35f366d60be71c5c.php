<?php $__env->startSection('title', 'Cairkan Bonus'); ?>

<?php $__env->startSection('content'); ?>


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
<div style="background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.3);border-radius:8px;padding:.6rem 1rem;margin-bottom:1rem;font-size:.82rem;color:#22c55e;">
    <?php echo e(session('success')); ?>

</div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
<div style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);border-radius:8px;padding:.6rem 1rem;margin-bottom:1rem;font-size:.82rem;color:#ef4444;">
    <?php echo e(session('error')); ?>

</div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;margin-bottom:1.5rem;">
    <div class="pw-adm-stat">
        <div class="pw-adm-stat__icon" style="color:#22c55e;">
            <svg viewBox="0 0 20 20" fill="none" width="22"><path d="M10 2a8 8 0 110 16 8 8 0 010-16z" stroke="currentColor" stroke-width="1.5"/><path d="M7 10h6M10 7v6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        </div>
        <div class="pw-adm-stat__value">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currencyLabel === 'IDR'): ?> Rp <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?><?php echo e(number_format($availableDisplay)); ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currencyLabel !== 'IDR'): ?> <span style="font-size:.65rem;color:var(--pw-text-muted);"><?php echo e($currencyLabel); ?></span><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <div class="pw-adm-stat__label">Saldo Tersedia</div>
    </div>
    <div class="pw-adm-stat">
        <div class="pw-adm-stat__icon" style="color:#f59e0b;">
            <svg viewBox="0 0 20 20" fill="none" width="22"><circle cx="10" cy="10" r="7" stroke="currentColor" stroke-width="1.5"/><path d="M10 6v4l2.5 2.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        </div>
        <div class="pw-adm-stat__value"><?php echo e($canClaim ? 'Tgl 1-7' : 'Tertutup'); ?></div>
        <div class="pw-adm-stat__label">Periode Cairkan</div>
    </div>
    <div class="pw-adm-stat">
        <div class="pw-adm-stat__icon" style="color:#a855f7;">
            <svg viewBox="0 0 20 20" fill="none" width="22"><path d="M4 14v2M8 10v6M12 7v9M16 4v12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        </div>
        <div class="pw-adm-stat__value">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($rewardType === 'tunai'): ?> Uang Tunai
            <?php elseif($rewardType === 'cubi'): ?> Cubi Gold
            <?php else: ?> Gold Points
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <div class="pw-adm-stat__label">Tipe Reward</div>
    </div>
</div>


<div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem;">

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($rewardType === 'tunai'): ?>
    <div class="pw-adm-card" style="margin-bottom:0;">
        <div class="pw-adm-card__title">
            <svg viewBox="0 0 20 20" fill="none" width="15"><rect x="2" y="4" width="16" height="12" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M2 8h16" stroke="currentColor" stroke-width="1.5"/></svg>
            Data Pembayaran
        </div>

        <form action="<?php echo e(route('partner.bonus.payment-info')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.05em;color:var(--pw-gold);margin-bottom:.5rem;font-weight:600;">Rekening Bank</div>
            <div style="display:grid;gap:.5rem;margin-bottom:1rem;">
                <input type="text" name="bank_name" value="<?php echo e(old('bank_name', $partner->bank_name)); ?>" placeholder="Nama Bank (BCA, BNI, Mandiri...)"
                       style="background:rgba(255,255,255,.04);border:1px solid var(--pw-border);border-radius:6px;padding:.45rem .65rem;font-size:.8rem;color:var(--pw-text);outline:none;">
                <input type="text" name="bank_account" value="<?php echo e(old('bank_account', $partner->bank_account)); ?>" placeholder="Nomor Rekening"
                       style="background:rgba(255,255,255,.04);border:1px solid var(--pw-border);border-radius:6px;padding:.45rem .65rem;font-size:.8rem;color:var(--pw-text);outline:none;">
                <input type="text" name="bank_holder" value="<?php echo e(old('bank_holder', $partner->bank_holder)); ?>" placeholder="Nama Pemilik Rekening"
                       style="background:rgba(255,255,255,.04);border:1px solid var(--pw-border);border-radius:6px;padding:.45rem .65rem;font-size:.8rem;color:var(--pw-text);outline:none;">
            </div>

            <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.05em;color:var(--pw-gold);margin-bottom:.5rem;font-weight:600;">E-Wallet</div>
            <div style="display:grid;gap:.5rem;margin-bottom:1rem;">
                <div x-data="{ open: false, selected: '<?php echo e(old('ewallet_type', $partner->ewallet_type)); ?>' }" style="position:relative;">
                    <input type="hidden" name="ewallet_type" :value="selected">
                    <button type="button" @click="open=!open" @click.outside="open=false"
                            style="width:100%;background:var(--pw-bg-card);border:1px solid var(--pw-border);border-radius:6px;padding:.45rem .65rem;font-size:.8rem;color:var(--pw-text);outline:none;cursor:pointer;text-align:left;display:flex;justify-content:space-between;align-items:center;">
                        <span x-text="selected || 'Pilih E-Wallet'" :style="!selected && 'color:var(--pw-text-muted)'"></span>
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="opacity:.5;"><path d="M6 9l6 6 6-6"/></svg>
                    </button>
                    <div x-show="open" x-cloak style="position:absolute;top:100%;left:0;right:0;z-index:30;margin-top:4px;background:var(--pw-bg-card);border:1px solid var(--pw-border);border-radius:6px;overflow:hidden;box-shadow:0 8px 20px rgba(0,0,0,.5);">
                        <div @click="selected='';open=false" style="padding:.45rem .65rem;font-size:.8rem;color:var(--pw-text-muted);cursor:pointer;transition:background .15s;" onmouseenter="this.style.background='rgba(255,255,255,.06)'" onmouseleave="this.style.background='transparent'">Pilih E-Wallet</div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['Dana', 'OVO', 'GoPay', 'ShopeePay', 'LinkAja']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ew): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <div @click="selected='<?php echo e($ew); ?>';open=false" style="padding:.45rem .65rem;font-size:.8rem;color:var(--pw-text);cursor:pointer;transition:background .15s;" onmouseenter="this.style.background='rgba(255,255,255,.06)'" onmouseleave="this.style.background='transparent'"><?php echo e($ew); ?></div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </div>
                <input type="text" name="ewallet_number" value="<?php echo e(old('ewallet_number', $partner->ewallet_number)); ?>" placeholder="Nomor E-Wallet"
                       style="background:rgba(255,255,255,.04);border:1px solid var(--pw-border);border-radius:6px;padding:.45rem .65rem;font-size:.8rem;color:var(--pw-text);outline:none;">
            </div>

            <button type="submit" class="pw-adm-btn" style="width:100%;justify-content:center;">Simpan Data Pembayaran</button>
        </form>
    </div>
    <?php else: ?>
    
    <div class="pw-adm-card" style="margin-bottom:0;">
        <div class="pw-adm-card__title">
            <svg viewBox="0 0 20 20" fill="none" width="15"><circle cx="10" cy="10" r="7" stroke="currentColor" stroke-width="1.5"/><path d="M10 7v3l2 2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            Info Pencairan
        </div>
        <div style="display:grid;gap:.7rem;font-size:.82rem;">
            <div style="background:rgba(96,165,250,.06);border:1px solid rgba(96,165,250,.15);border-radius:8px;padding:.8rem 1rem;">
                <div style="font-size:.7rem;text-transform:uppercase;letter-spacing:.05em;color:var(--pw-text-muted);margin-bottom:.3rem;">Tipe Pencairan</div>
                <div style="font-size:1rem;font-weight:700;color:#60a5fa;">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($rewardType === 'cubi'): ?> Cubi Gold (In-Game)
                    <?php else: ?> Gold Points (Panel)
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
            <div style="font-size:.78rem;color:var(--pw-text-muted);line-height:1.5;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($rewardType === 'cubi'): ?>
                    <p>Pencairan bonus dalam bentuk <strong style="color:var(--pw-text);">Cubi Gold</strong> akan otomatis masuk ke akun game kamu setelah disetujui admin.</p>
                    <p style="margin-top:.4rem;">Rate: <strong style="color:var(--pw-text);">1 Cubi = Rp <?php echo e(number_format(config('pw-config.currency.cubi_rate_idr', 1000), 0, ',', '.')); ?></strong></p>
                <?php else: ?>
                    <p>Pencairan bonus dalam bentuk <strong style="color:var(--pw-text);">Gold Points</strong> akan otomatis ditambahkan ke saldo panel kamu setelah disetujui admin.</p>
                    <p style="margin-top:.4rem;">Rate: <strong style="color:var(--pw-text);">1 Gold = Rp <?php echo e(number_format(config('pw-config.currency.rate_idr', 10000), 0, ',', '.')); ?></strong></p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div style="background:rgba(34,197,94,.06);border:1px solid rgba(34,197,94,.15);border-radius:8px;padding:.6rem .8rem;">
                <div style="font-size:.7rem;color:var(--pw-text-muted);margin-bottom:.2rem;">Akan dicairkan</div>
                <div style="font-size:1.1rem;font-weight:700;color:#22c55e;"><?php echo e(number_format($availableDisplay)); ?> <?php echo e($currencyLabel); ?></div>
            </div>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <div class="pw-adm-card" style="margin-bottom:0;">
        <div class="pw-adm-card__title">
            <svg viewBox="0 0 20 20" fill="none" width="15"><path d="M10 2v12M6 10l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 16h14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            Cairkan Bonus
        </div>

        
        <div style="background:rgba(245,158,11,.06);border:1px solid rgba(245,158,11,.15);border-radius:8px;padding:.6rem .8rem;margin-bottom:1rem;font-size:.78rem;">
            <div style="display:flex;align-items:center;gap:.4rem;color:#f59e0b;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l2 2"/></svg>
                Pencairan hanya bisa diajukan pada <strong>tanggal 1-7</strong> setiap bulan.
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($canClaim): ?>
            <div style="color:var(--pw-text-muted);margin-top:.3rem;">Batas: <?php echo e($claimDeadline->format('d M Y')); ?></div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($canClaim && !$alreadyRequested && $availableDisplay >= $minClaim): ?>
        <form action="<?php echo e(route('partner.bonus.claim')); ?>" method="POST" x-data="{ method: 'bank' }"
              data-confirm="Konfirmasi Pencairan|Yakin ingin mengajukan pencairan bonus?"
              data-confirm-variant="success"
              data-confirm-ok="Ya, Ajukan">
            <?php echo csrf_field(); ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($rewardType === 'tunai'): ?>
            
            <div style="display:grid;gap:.5rem;margin-bottom:.8rem;">
                <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.05em;color:var(--pw-gold);font-weight:600;">Metode Pembayaran</div>
                <div style="display:flex;gap:.5rem;">
                    <label style="flex:1;display:flex;align-items:center;gap:.4rem;background:rgba(255,255,255,.04);border:1px solid var(--pw-border);border-radius:6px;padding:.45rem .65rem;font-size:.8rem;color:var(--pw-text);cursor:pointer;"
                           :style="method==='bank' && 'border-color:rgba(34,197,94,.5);background:rgba(34,197,94,.06)'">
                        <input type="radio" name="payment_method" value="bank" x-model="method" style="accent-color:#22c55e;"> Bank
                    </label>
                    <label style="flex:1;display:flex;align-items:center;gap:.4rem;background:rgba(255,255,255,.04);border:1px solid var(--pw-border);border-radius:6px;padding:.45rem .65rem;font-size:.8rem;color:var(--pw-text);cursor:pointer;"
                           :style="method==='ewallet' && 'border-color:rgba(34,197,94,.5);background:rgba(34,197,94,.06)'">
                        <input type="radio" name="payment_method" value="ewallet" x-model="method" style="accent-color:#22c55e;"> E-Wallet
                    </label>
                </div>
                <div style="font-size:.72rem;color:var(--pw-text-muted);" x-show="method==='bank'">
                    Ke: <strong style="color:var(--pw-text);"><?php echo e($partner->bank_name ? $partner->bank_name . ' - ' . $partner->bank_account . ' (' . $partner->bank_holder . ')' : 'Belum diisi'); ?></strong>
                </div>
                <div style="font-size:.72rem;color:var(--pw-text-muted);" x-show="method==='ewallet'">
                    Ke: <strong style="color:var(--pw-text);"><?php echo e($partner->ewallet_type ? $partner->ewallet_type . ' - ' . $partner->ewallet_number : 'Belum diisi'); ?></strong>
                </div>
            </div>

            <div style="margin-bottom:.8rem;">
                <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.05em;color:var(--pw-gold);margin-bottom:.3rem;font-weight:600;">Jumlah (Rp)</div>
                <input type="number" name="amount" min="10000" max="<?php echo e($availableBalanceIdr); ?>" value="<?php echo e($availableBalanceIdr); ?>" required
                       style="width:100%;background:rgba(255,255,255,.04);border:1px solid var(--pw-border);border-radius:6px;padding:.45rem .65rem;font-size:.85rem;color:var(--pw-text);outline:none;">
                <div style="font-size:.68rem;color:var(--pw-text-muted);margin-top:.2rem;">Minimum: Rp 10.000</div>
            </div>
            <?php else: ?>
            
            <div style="background:rgba(34,197,94,.06);border:1px solid rgba(34,197,94,.15);border-radius:8px;padding:.8rem 1rem;margin-bottom:1rem;">
                <div style="font-size:.7rem;text-transform:uppercase;letter-spacing:.05em;color:var(--pw-text-muted);margin-bottom:.2rem;">Jumlah Pencairan</div>
                <div style="font-size:1.3rem;font-weight:700;color:#22c55e;"><?php echo e(number_format($availableDisplay)); ?> <?php echo e($currencyLabel); ?></div>
                <div style="font-size:.68rem;color:var(--pw-text-muted);margin-top:.2rem;">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($rewardType === 'cubi'): ?> Akan dikirim ke akun game (User ID: <?php echo e($user->ID ?? auth()->id()); ?>)
                    <?php else: ?> Akan ditambahkan ke saldo panel
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <button type="submit" class="pw-adm-btn" style="width:100%;justify-content:center;background:rgba(34,197,94,.15);border-color:rgba(34,197,94,.3);color:#22c55e;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle;margin-right:4px;"><path d="M12 2v12M6 10l6 6 6-6"/><path d="M3 20h18"/></svg>
                Ajukan Pencairan Bonus
            </button>
        </form>
        <?php elseif($alreadyRequested): ?>
        <div style="text-align:center;padding:1.5rem 1rem;color:var(--pw-text-muted);font-size:.82rem;">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="1.5" style="margin:0 auto .5rem;display:block;"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l2 2"/></svg>
            Kamu sudah mengajukan pencairan bulan ini.<br>
            <span style="font-size:.75rem;">Tunggu proses dari admin atau ajukan lagi bulan depan.</span>
        </div>
        <?php elseif(!$canClaim): ?>
        <div style="text-align:center;padding:1.5rem 1rem;color:var(--pw-text-muted);font-size:.82rem;">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.15)" stroke-width="1.5" style="margin:0 auto .5rem;display:block;"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
            Pencairan belum tersedia.<br>
            <span style="font-size:.75rem;">Bisa diajukan pada tanggal 1-7 bulan depan.</span>
        </div>
        <?php else: ?>
        <div style="text-align:center;padding:1.5rem 1rem;color:var(--pw-text-muted);font-size:.82rem;">
            Saldo belum mencukupi untuk pencairan.
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>


<div class="pw-adm-card">
    <div class="pw-adm-card__title">
        <svg viewBox="0 0 20 20" fill="none" width="15"><path d="M4 5h12M4 10h12M4 15h8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        Status Pencairan Bonus
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($claims->isEmpty()): ?>
    <div style="text-align:center;padding:2rem 1rem;color:var(--pw-text-muted);font-size:.82rem;">
        Belum ada riwayat pencairan bonus.
    </div>
    <?php else: ?>
    <div class="pw-table-wrap">
        <table class="pw-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th style="text-align:right;">Jumlah</th>
                    <th>Tipe</th>
                    <th>Detail Pembayaran</th>
                    <th style="text-align:center;">Status</th>
                    <th>Catatan Admin</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $claims; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $claim): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <tr>
                    <td style="font-size:.78rem;color:var(--pw-text-muted);"><?php echo e($claim->created_at->format('d M Y H:i')); ?></td>
                    <td style="text-align:right;font-weight:600;">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($claim->payment_method === 'cubi'): ?>
                            <?php echo e(number_format((int)($claim->amount / config('pw-config.currency.cubi_rate_idr', 1000)))); ?> Cubi
                        <?php elseif($claim->payment_method === 'gold'): ?>
                            <?php echo e(number_format((int)($claim->amount / config('pw-config.currency.rate_idr', 10000)))); ?> Gold
                        <?php else: ?>
                            Rp <?php echo e(number_format($claim->amount, 0, ',', '.')); ?>

                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                    <td>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($claim->payment_method === 'cubi'): ?>
                            <span style="color:#60a5fa;">Cubi Gold</span>
                        <?php elseif($claim->payment_method === 'gold'): ?>
                            <span style="color:var(--pw-gold);">Gold Points</span>
                        <?php elseif($claim->payment_method === 'bank'): ?>
                            Bank
                        <?php else: ?>
                            E-Wallet
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                    <td style="font-size:.78rem;"><?php echo e($claim->payment_detail); ?></td>
                    <td style="text-align:center;">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($claim->status === 'approved'): ?>
                        <span class="pw-badge pw-badge--success">Disetujui</span>
                        <?php elseif($claim->status === 'rejected'): ?>
                        <span class="pw-badge pw-badge--danger">Ditolak</span>
                        <?php else: ?>
                        <span class="pw-badge pw-badge--warning">Pending</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                    <td style="font-size:.75rem;color:var(--pw-text-muted);"><?php echo e($claim->admin_note ?? '-'); ?></td>
                </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($claims->hasPages()): ?>
    <div style="margin-top:1rem;">
        <?php echo e($claims->links()); ?>

    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.pw-adm-stat{background:var(--pw-bg-card,rgba(255,255,255,.04));border:1px solid var(--pw-border,rgba(255,255,255,.08));border-radius:10px;padding:1.1rem 1.2rem;display:flex;flex-direction:column;gap:.3rem;}
.pw-adm-stat__icon{margin-bottom:.15rem;}
.pw-adm-stat__value{font-size:1.5rem;font-weight:700;color:var(--pw-text,#e8dfc8);line-height:1;}
.pw-adm-stat__label{font-size:.73rem;color:var(--pw-text-muted,#7a7a9a);text-transform:uppercase;letter-spacing:.05em;}
@media(max-width:768px){
    .pw-adm-stat__value{font-size:1.1rem;}
}
select option{background:var(--pw-bg-card) !important;color:var(--pw-text,#c4c4c4);}
select option:checked,select option:hover{background:var(--pw-bg-card2) !important;}

/* ── Partner bonus light mode ── */
[data-theme="light"] .pw-adm-card input[type="text"] {
    background: #ffffff !important;
}
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.partner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/pw-panel/resources/views/partner/bonus.blade.php ENDPATH**/ ?>