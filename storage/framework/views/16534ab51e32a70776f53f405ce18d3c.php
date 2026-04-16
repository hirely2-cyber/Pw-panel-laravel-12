<?php $__env->startSection('title', 'Broadcast'); ?>

<?php $__env->startSection('content'); ?>
<div x-data="{ channel: '9' }">

    
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;flex-wrap:wrap;gap:.75rem;">
        <div>
            <h2 style="font-size:1.15rem;font-weight:700;color:var(--pw-text);margin:0;">World Broadcast — Kirim Pesan ke Semua Pemain</h2>
            <p style="font-size:.8rem;color:var(--pw-text-muted);margin-top:.2rem;">Pesan dikirim ke semua pemain online melalui chat channel in-game.</p>
        </div>
        <div style="display:flex;align-items:center;gap:.5rem;padding:.4rem .8rem;border-radius:6px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);">
            <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:<?php echo e($apiAvailable ? '#4ade80' : '#ef4444'); ?>;"></span>
            <span style="font-size:.78rem;color:var(--pw-text-muted);">gacd: <?php echo e($apiAvailable ? 'Connected' : 'Offline'); ?></span>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
    <div class="pw-adm-alert pw-adm-alert--success" style="margin-bottom:1rem;"><?php echo e(session('success')); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
    <div class="pw-adm-alert pw-adm-alert--error" style="margin-bottom:1rem;"><?php echo e(session('error')); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <form action="<?php echo e(route('admin.broadcast.send')); ?>" method="POST">
        <?php echo csrf_field(); ?>

        <div class="pw-adm-card" style="margin-bottom:1.25rem;">
            <div class="pw-adm-card__title">
                <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M10 2v4M10 14v4M4 10H2M18 10h-2M5.05 5.05L3.64 3.64M14.95 5.05l1.41-1.41M5.05 14.95l-1.41 1.41M14.95 14.95l1.41 1.41" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/><circle cx="10" cy="10" r="3" stroke="currentColor" stroke-width="1.4"/></svg>
                Compose Broadcast
            </div>

            
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:.8rem;">
                <div>
                    <label class="pw-form__label">Channel <span style="color:#e05252;">*</span></label>
                    <select name="channel" class="pw-form__input" x-model="channel">
                        <option value="9">Broadcast (GM Announcement)</option>
                        <option value="1">World Chat</option>
                        <option value="0">Common</option>
                        <option value="12">Horn (Loudspeaker)</option>
                        <option value="7">Trade</option>
                    </select>
                </div>
                <div>
                    <label class="pw-form__label" style="opacity:.6;">Channel Info</label>
                    <div style="padding:.55rem .75rem;border-radius:6px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);font-size:.78rem;color:var(--pw-text-muted);min-height:38px;display:flex;align-items:center;">
                        <span x-show="channel === '9'">Banner besar di atas layar semua pemain</span>
                        <span x-show="channel === '1'">Masuk ke World Chat semua zona</span>
                        <span x-show="channel === '0'">Chat umum / common channel</span>
                        <span x-show="channel === '12'">Tampil seperti pengeras suara (horn)</span>
                        <span x-show="channel === '7'">Masuk ke Trade channel</span>
                    </div>
                </div>
            </div>

            
            <div style="margin-bottom:.8rem;">
                <label class="pw-form__label">Pesan <span style="color:#e05252;">*</span></label>
                <textarea name="message" rows="5" class="pw-form__input" required
                          maxlength="500" id="broadcast-msg"
                          placeholder="Masukkan pesan yang akan dikirim ke semua pemain online…"
                          style="resize:vertical;"><?php echo e(old('message')); ?></textarea>
                <div style="display:flex;justify-content:space-between;margin-top:.3rem;">
                    <p style="font-size:.72rem;color:var(--pw-text-muted);">Pesan ini akan dikirim ke semua pemain yang sedang online.</p>
                    <span style="font-size:.72rem;color:var(--pw-text-muted);"><span id="char-count">0</span>/500</span>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p style="color:#e05252;font-size:.75rem;margin-top:.3rem;"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            
            <div style="border-top:1px solid rgba(255,255,255,.06);margin:1.25rem 0;"></div>

            
            <div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;">
                <p style="font-size:.78rem;color:var(--pw-text-muted);">
                    <svg viewBox="0 0 20 20" fill="none" width="13" style="vertical-align:middle;margin-right:.3rem;opacity:.5;"><path d="M10 3a7 7 0 100 14 7 7 0 000-14zm0 4v3m0 3h.01" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                    Pesan hanya diterima oleh pemain yang sedang online saat ini.
                </p>
                <button type="submit" class="pw-adm-btn" style="white-space:nowrap;" <?php echo e(!$apiAvailable ? 'disabled' : ''); ?>>
                    <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M10 2v4M10 14v4M4 10H2M18 10h-2" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/><circle cx="10" cy="10" r="3" stroke="currentColor" stroke-width="1.4"/></svg>
                    Kirim Broadcast
                </button>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$apiAvailable): ?>
            <div class="pw-adm-alert pw-adm-alert--error" style="margin-top:.75rem;font-size:.78rem;">
                Tidak dapat terhubung ke <strong>gacd</strong> (port <?php echo e(config('pw-api.ports.gacd', 29300)); ?>). Pastikan game server berjalan.
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </form>

    
    <div class="pw-adm-card" style="padding:1rem 1.25rem;">
        <p style="font-size:.82rem;font-weight:600;color:var(--pw-text);margin-bottom:.6rem;">
            <svg viewBox="0 0 20 20" fill="none" width="14" style="vertical-align:middle;margin-right:.3rem;opacity:.6;"><path d="M9 3h2l1 7h-4l1-7zM10 14v1" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Panduan Channel
        </p>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;font-size:.78rem;color:var(--pw-text-muted);">
            <div>
                <p style="font-weight:600;color:var(--pw-text);margin-bottom:.3rem;">Channel 9 - Broadcast</p>
                <p>Banner besar yang tampil di atas layar semua pemain online. Cocok untuk pengumuman penting seperti maintenance, event, atau informasi server.</p>
            </div>
            <div>
                <p style="font-weight:600;color:var(--pw-text);margin-bottom:.3rem;">Channel 1 - World Chat</p>
                <p>Pesan masuk ke tab World Chat yang bisa dibaca semua pemain di semua zona. Cocok untuk informasi umum atau interaksi GM dengan pemain.</p>
            </div>
            <div>
                <p style="font-weight:600;color:var(--pw-text);margin-bottom:.3rem;">Channel 12 - Horn</p>
                <p>Tampil seperti pemain menggunakan Loudspeaker/Horn. Muncul di tengah layar dengan warna khusus. Cocok untuk promosi event atau pengumuman singkat.</p>
            </div>
        </div>
    </div>

</div>

<?php $__env->startPush('scripts'); ?>
<script>
const msg = document.getElementById('broadcast-msg');
const cnt = document.getElementById('char-count');
msg.addEventListener('input', () => cnt.textContent = msg.value.length);
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/pw-panel/resources/views/admin/broadcast/index.blade.php ENDPATH**/ ?>