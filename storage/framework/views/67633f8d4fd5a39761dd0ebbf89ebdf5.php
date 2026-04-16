<?php
    $__siteName = \App\Models\Setting::get('seo_title') ?: config('pw-config.server.name') ?: 'Perfect World';
?>

<?php $__env->startSection('title', 'Invoice #' . $invoice->invoice_number . ' — ' . $__siteName); ?>

<?php $__env->startSection('content'); ?>


<div class="pw-page-hero">
    <div class="pw-page-hero__bg" aria-hidden="true"></div>
    <div class="pw-page-hero__inner">
        <div class="pw-page-hero__ornament" aria-hidden="true">
            <svg viewBox="0 0 160 20" fill="none" width="140">
                <line x1="0" y1="10" x2="55" y2="10" stroke="#c8972a" stroke-width="1"/>
                <path d="M65 3 L75 10 L65 17 L55 10 Z" fill="#c8972a" opacity=".5"/>
                <path d="M75 3 L85 10 L75 17 L65 10 Z" fill="#c8972a"/>
                <path d="M85 3 L95 10 L85 17 L75 10 Z" fill="#c8972a" opacity=".5"/>
                <line x1="95" y1="10" x2="150" y2="10" stroke="#c8972a" stroke-width="1"/>
            </svg>
        </div>
        <h1 class="pw-page-hero__title">Invoice Pembayaran</h1>
        <p class="pw-page-hero__sub">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($invoice->type === 'cubi'): ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($invoice->channel_type ?? '') === 'qris'): ?>
                    Scan QRIS & Cubi Coin langsung masuk ke akun game
                <?php else: ?>
                    Transfer sesuai nominal & Cubi Coin masuk otomatis
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php else: ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($invoice->channel_type ?? '') === 'qris'): ?>
                    Scan QRIS & Gold Points langsung masuk otomatis
                <?php else: ?>
                    Transfer sesuai nominal & Gold Points masuk otomatis
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </p>
        <nav class="pw-breadcrumb" aria-label="breadcrumb">
            <a href="<?php echo e(route('home')); ?>" class="pw-breadcrumb__item">
                <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true">
                    <path d="M2 7.5L8 2l6 5.5V14a1 1 0 01-1 1h-3.5v-3.5h-3V15H3a1 1 0 01-1-1V7.5z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/>
                </svg>
                Beranda
            </a>
            <span class="pw-breadcrumb__sep" aria-hidden="true">/</span>
            <a href="<?php echo e(route('donate')); ?>" class="pw-breadcrumb__item">Top-up Gold Points</a>
            <span class="pw-breadcrumb__sep" aria-hidden="true">/</span>
            <span class="pw-breadcrumb__item pw-breadcrumb__item--active">Invoice</span>
        </nav>
    </div>
</div>


<section class="pw-section">
    <div class="pw-section__inner">
        <div class="pw-invoice-layout">

        
        <div class="pw-invoice-layout__main">
        <div class="pw-qris-card">

            
            <div class="pw-qris-card__status-bar" id="status-bar">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($invoice->status === 'paid'): ?>
                <span class="pw-qris-status pw-qris-status--paid">
                    <svg viewBox="0 0 16 16" fill="none" width="14" aria-hidden="true"><circle cx="8" cy="8" r="7" fill="rgba(56,161,105,.2)"/><path d="M5 8l2 2 4-4" stroke="#6ee7b7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Pembayaran Berhasil
                </span>
                <?php elseif($invoice->status === 'pending'): ?>
                <div style="display:flex;flex-direction:column;align-items:center;gap:.45rem;">
                    <span class="pw-qris-status pw-qris-status--pending" id="status-badge">
                        <span class="pw-qris-status__dot"></span>
                        Menunggu Pembayaran
                    </span>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($invoice->expires_at): ?>
                    <div class="pw-countdown" id="countdown-bar">
                        <svg viewBox="0 0 16 16" fill="none" width="12" aria-hidden="true"><circle cx="8" cy="8" r="6.5" stroke="currentColor" stroke-width="1.2"/><path d="M8 5v3l2 2" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <span id="countdown-time">--:--</span>
                        <span style="font-size:.7rem;font-weight:400;opacity:.7;">tersisa</span>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <?php elseif($invoice->status === 'expired'): ?>
                <span class="pw-qris-status pw-qris-status--expired">
                    <svg viewBox="0 0 16 16" fill="none" width="14" aria-hidden="true"><circle cx="8" cy="8" r="6.5" stroke="currentColor" stroke-width="1.2"/><path d="M8 5v3l2 2" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Waktu Habis
                </span>
                <?php else: ?>
                <span class="pw-qris-status pw-qris-status--failed">
                    <svg viewBox="0 0 16 16" fill="none" width="14" aria-hidden="true"><circle cx="8" cy="8" r="7" fill="rgba(229,62,62,.15)"/><path d="M5.5 5.5l5 5M10.5 5.5l-5 5" stroke="#fca5a5" stroke-width="1.5" stroke-linecap="round"/></svg>
                    Gagal / Ditolak
                </span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            
            <div class="pw-qris-card__title">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($invoice->type === 'cubi'): ?>
                +<?php echo e(number_format($invoice->cubi_amount)); ?> Cubi Coin
                <?php else: ?>
                +<?php echo e(number_format($invoice->gold_amount)); ?> <?php echo e(config('pw-config.currency.name')); ?>

                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            
            <div class="pw-qris-card__amount">
                Rp <?php echo e(number_format($invoice->unique_amount)); ?>

            </div>
            <div class="pw-qris-card__note">
                Bayar <strong>tepat Rp <?php echo e(number_format($invoice->unique_amount)); ?></strong> — nominal unik untuk verifikasi otomatis
            </div>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($invoice->channel_type): ?>
            <div style="display:flex;align-items:center;justify-content:center;gap:.6rem;margin-bottom:1.2rem;">
                <img src="<?php echo e(asset('images/' . ($invoice->channel_type === 'qris' ? 'qris' : ($invoice->channel_type === 'dana' ? 'dana' : 'qris')) . '.webp')); ?>"
                     alt="<?php echo e(strtoupper($invoice->channel_type)); ?>"
                     style="height:28px;width:auto;object-fit:contain;opacity:.9;">
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($invoice->status === 'paid'): ?>
            <div class="pw-qris-card__success-msg">
                <svg viewBox="0 0 48 48" fill="none" width="48" aria-hidden="true">
                    <circle cx="24" cy="24" r="22" fill="rgba(56,161,105,.15)" stroke="#6ee7b7" stroke-width="1.5"/>
                    <path d="M14 24l7 7 13-14" stroke="#6ee7b7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($invoice->type === 'cubi'): ?>
                <p>Cubi Coin <strong>+<?php echo e(number_format($invoice->cubi_amount)); ?></strong> sudah masuk ke akun game kamu!</p>
                <?php else: ?>
                <p>Gold Points <strong>+<?php echo e(number_format($invoice->gold_amount)); ?></strong> sudah masuk ke akun kamu!</p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <?php elseif($invoice->status === 'expired'): ?>
            
            <div style="text-align:center;padding:2.2rem 1rem;display:flex;flex-direction:column;align-items:center;gap:.8rem;">
                <svg viewBox="0 0 48 48" fill="none" width="52" aria-hidden="true">
                    <circle cx="24" cy="24" r="22" stroke="#fb923c" stroke-width="1.5" fill="rgba(251,146,60,.08)"/>
                    <circle cx="24" cy="24" r="12" stroke="#fb923c" stroke-width="1" opacity=".35"/>
                    <path d="M24 15v9l5.5 5.5" stroke="#fb923c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <div style="font-size:.9rem;font-weight:600;color:#fb923c;">Waktu Pembayaran Habis</div>
                <p style="font-size:.78rem;color:var(--pw-text-muted);line-height:1.6;max-width:240px;">Invoice ini sudah kadaluarsa. Buat invoice baru untuk melanjutkan top-up Gold Points.</p>
                <a href="<?php echo e(route('donate')); ?>" class="pw-btn pw-btn--gold pw-btn--sm" style="margin-top:.2rem;">Buat Invoice Baru</a>
            </div>
            <?php elseif($invoice->status === 'failed'): ?>
            
            <div style="text-align:center;padding:2.2rem 1rem;display:flex;flex-direction:column;align-items:center;gap:.8rem;">
                <svg viewBox="0 0 48 48" fill="none" width="52" aria-hidden="true">
                    <circle cx="24" cy="24" r="22" fill="rgba(229,62,62,.1)" stroke="#fca5a5" stroke-width="1.5"/>
                    <path d="M16 16l16 16M32 16L16 32" stroke="#fca5a5" stroke-width="2.2" stroke-linecap="round"/>
                </svg>
                <div style="font-size:.9rem;font-weight:600;color:#fca5a5;">Pembayaran Gagal / Ditolak</div>
                <p style="font-size:.78rem;color:var(--pw-text-muted);line-height:1.6;max-width:240px;">Invoice ini telah ditolak atau gagal diproses. Buat invoice baru untuk mencoba lagi.</p>
                <a href="<?php echo e(route('donate')); ?>" class="pw-btn pw-btn--gold pw-btn--sm" style="margin-top:.2rem;">Buat Invoice Baru</a>
            </div>
            <?php elseif(($invoice->channel_type ?? '') === 'qris'): ?>
            
            <div class="pw-qris-card__qr" id="payment-section">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($invoice->qris_url): ?>
                    <?php
                        $qrisRaw     = (string) $invoice->qris_url;
                        $isInlineSvg = str_contains($qrisRaw, '<svg') || str_contains($qrisRaw, '<?xml');
                        $svgOnly     = '';
                        if ($isInlineSvg) {
                            $xmlClose = '?' . '>';
                            $svgOnly  = (string) preg_replace('/<\?xml[^' . $xmlClose . ']*' . $xmlClose . '\s*/i', '', $qrisRaw);
                            $svgOnly  = trim($svgOnly);
                            $svgOnly  = (string) preg_replace('/(<svg\b[^>]*?)\s+width="[^"]*"/i', '$1', $svgOnly);
                            $svgOnly  = (string) preg_replace('/(<svg\b[^>]*?)\s+height="[^"]*"/i', '$1 width="100%" height="100%"', $svgOnly);
                        }
                    ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isInlineSvg): ?>
                        <div id="qris-img" style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;"><?php echo $svgOnly; ?></div>
                    <?php else: ?>
                        <img src="<?php echo e($invoice->qris_url); ?>" alt="QRIS Code" id="qris-img" style="width:100%;height:100%;object-fit:contain;">
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php else: ?>
                <div style="display:flex;flex-direction:column;align-items:center;gap:.5rem;padding:2rem;color:var(--pw-text-muted);">
                    <svg viewBox="0 0 24 24" fill="none" width="36" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h7v7H3zM14 3h7v7h-7zM3 14h7v7H3z"/></svg>
                    <span style="font-size:.8rem;">QR tidak tersedia</span>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <?php else: ?>
            
            <?php
                $instr = $invoice->payment_instruction ?? [];
                $accNum  = $instr['account_number'] ?? '-';
                $accName = $instr['account_name']   ?? '-';
                $provider = strtoupper($instr['provider'] ?? $invoice->channel_type ?? 'Transfer');
            ?>
            <div class="pw-qris-card__transfer" id="payment-section" style="margin:1.2rem 0;padding:1.2rem 1rem;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);border-radius:.75rem;text-align:center;">
                <div style="font-size:.75rem;color:var(--pw-text-muted);margin-bottom:.5rem;text-transform:uppercase;letter-spacing:.05em;">Transfer <?php echo e($provider); ?></div>
                <div style="font-size:1.6rem;font-weight:700;letter-spacing:.08em;color:var(--pw-gold);font-family:monospace;" id="acc-number"><?php echo e($accNum); ?></div>
                <div style="font-size:.85rem;color:var(--pw-text-muted);margin-top:.25rem;">a.n. <strong style="color:var(--pw-text);"><?php echo e($accName); ?></strong></div>
                <button type="button" onclick="copyAccNumber()" style="margin-top:.8rem;padding:.4rem 1rem;background:rgba(200,151,42,.15);border:1px solid rgba(200,151,42,.3);color:var(--pw-gold);border-radius:.4rem;font-size:.75rem;cursor:pointer;">
                    Salin Nomor
                </button>
                <div style="margin-top:.75rem;padding:.6rem;background:rgba(255,180,0,.06);border:1px solid rgba(255,180,0,.15);border-radius:.4rem;font-size:.75rem;color:var(--pw-text-muted);">
                    Pastikan transfer <strong style="color:var(--pw-gold);">tepat Rp <?php echo e(number_format($invoice->unique_amount)); ?></strong> - beda 1 rupiah pun tidak terdeteksi
                </div>
            </div>
            <script>
            function copyAccNumber() {
                var el = document.getElementById('acc-number');
                if (!el) return;
                var tmp = document.createElement('textarea');
                tmp.value = el.textContent.trim();
                document.body.appendChild(tmp); tmp.select();
                try { document.execCommand('copy'); } catch(e) {}
                document.body.removeChild(tmp);
                var btn = event.target; btn.textContent = 'Tersalin ✓';
                setTimeout(function(){ btn.textContent = 'Salin Nomor'; }, 2000);
            }
            </script>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <div class="pw-qris-card__details">
                <div class="pw-qris-card__detail-row">
                    <span class="pw-qris-card__detail-label">No Invoice</span>
                    <span class="pw-qris-card__detail-val"><?php echo e($invoice->invoice_number); ?></span>
                </div>
                <div class="pw-qris-card__detail-row">
                    <span class="pw-qris-card__detail-label">Dibuat</span>
                    <span class="pw-qris-card__detail-val"><?php echo e($invoice->created_at->translatedFormat('d M Y, H:i')); ?></span>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($invoice->expires_at): ?>
                <div class="pw-qris-card__detail-row">
                    <span class="pw-qris-card__detail-label">Batas Bayar</span>
                    <span class="pw-qris-card__detail-val" id="expire-time"><?php echo e($invoice->expires_at->translatedFormat('d M Y, H:i')); ?></span>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($invoice->paid_at): ?>
                <div class="pw-qris-card__detail-row">
                    <span class="pw-qris-card__detail-label">Dibayar</span>
                    <span class="pw-qris-card__detail-val" style="color:#6ee7b7;"><?php echo e($invoice->paid_at->translatedFormat('d M Y, H:i')); ?></span>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

        </div>
        </div>

        
        <aside class="pw-invoice-layout__sidebar">

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($invoice->status !== 'paid'): ?>
        
        <?php
            $guideInstr    = $invoice->payment_instruction ?? [];
            $guideAccNum   = $guideInstr['account_number'] ?? '-';
            $guideAccName  = $guideInstr['account_name']   ?? '-';
            $guideProvider = ucfirst($guideInstr['provider'] ?? $invoice->channel_type ?? 'transfer');
        ?>
        <div class="pw-invoice-guide" id="payment-guide">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($invoice->channel_type ?? '') === 'qris'): ?>
            <div class="pw-invoice-guide__title">Cara Bayar QRIS</div>
            <ol class="pw-invoice-guide__steps">
                <li>Buka aplikasi dompet digital kamu <span class="pw-invoice-guide__hint">(GoPay, OVO, Dana, ShopeePay, dll.)</span></li>
                <li>Pilih menu <strong>Scan QR</strong> atau <strong>Bayar</strong></li>
                <li>Arahkan kamera ke <strong>QR Code</strong> yang tertera di sebelah kiri</li>
                <li>Nominal <strong class="pw-invoice-guide__amount">Rp <?php echo e(number_format($invoice->unique_amount)); ?></strong> sudah otomatis terisi &mdash; <strong>jangan diubah</strong></li>
                <li>Konfirmasi pembayaran &mdash; Gold Points otomatis masuk dalam beberapa detik</li>
            </ol>
            <?php elseif(strtolower($invoice->channel_type ?? '') === 'dana'): ?>
            <div class="pw-invoice-guide__title">Cara Bayar via DANA</div>
            <ol class="pw-invoice-guide__steps">
                <li>Buka aplikasi <strong>DANA</strong> di HP kamu</li>
                <li>Pilih <strong>Kirim Uang</strong> &rarr; <strong>Ke Sesama DANA</strong></li>
                <li>Masukkan nomor <strong class="pw-invoice-guide__amount"><?php echo e($guideAccNum); ?></strong></li>
                <li>Pastikan nama penerima: <strong><?php echo e($guideAccName); ?></strong></li>
                <li>Masukkan nominal <strong class="pw-invoice-guide__amount">tepat Rp <?php echo e(number_format($invoice->unique_amount)); ?></strong></li>
                <li>Konfirmasi &mdash; Gold Points otomatis masuk dalam beberapa detik</li>
            </ol>
            <?php else: ?>
            <div class="pw-invoice-guide__title">Cara Bayar via <?php echo e($guideProvider); ?></div>
            <ol class="pw-invoice-guide__steps">
                <li>Buka aplikasi dompet atau mobile banking kamu</li>
                <li>Pilih menu <strong>Transfer</strong></li>
                <li>Masukkan nomor <strong class="pw-invoice-guide__amount"><?php echo e($guideAccNum); ?></strong> a.n. <strong><?php echo e($guideAccName); ?></strong></li>
                <li>Masukkan nominal <strong class="pw-invoice-guide__amount">tepat Rp <?php echo e(number_format($invoice->unique_amount)); ?></strong></li>
                <li>Konfirmasi &mdash; Gold Points otomatis masuk dalam beberapa detik</li>
            </ol>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <?php else: ?>
        
        <div class="pw-invoice-guide" style="text-align:center;">
            <svg viewBox="0 0 64 64" fill="none" width="56" style="margin:0 auto 1rem;"><circle cx="32" cy="32" r="30" fill="rgba(56,161,105,.1)" stroke="#6ee7b7" stroke-width="1.5"/><path d="M20 32l9 9 16-18" stroke="#6ee7b7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <div class="pw-invoice-guide__title" style="color:#6ee7b7;">Pembayaran Berhasil!</div>
            <p style="font-size:.85rem;color:var(--pw-text-muted);line-height:1.7;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($invoice->type === 'cubi'): ?>
                Cubi Coin <strong style="color:#60d0ff;">+<?php echo e(number_format($invoice->cubi_amount)); ?></strong> sudah berhasil masuk ke akun game kamu.
                <?php else: ?>
                Gold Points <strong style="color:var(--pw-gold);">+<?php echo e(number_format($invoice->gold_amount)); ?></strong> sudah berhasil masuk ke akun kamu.
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </p>
            <a href="<?php echo e(route('donate')); ?>" class="pw-btn pw-btn--gold pw-btn--sm" style="margin-top:1rem;display:inline-flex;">Top-up Lagi</a>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <div class="pw-invoice-guide" style="margin-top:1rem;">
            <div class="pw-invoice-guide__title">Info Penting</div>
            <ul style="margin:0;padding-left:1.2rem;font-size:.83rem;color:var(--pw-text-muted);line-height:1.9;list-style-type:disc;">
                <li>Invoice berlaku selama <strong style="color:var(--pw-text);">10 menit</strong></li>
                <li>Bayar <strong style="color:var(--pw-gold);">tepat sesuai nominal</strong> — beda 1 rupiah tidak terdeteksi</li>
                <li>Gold Points masuk otomatis setelah pembayaran dikonfirmasi</li>
                <li>Butuh bantuan? Hubungi admin server</li>
            </ul>
        </div>

        </aside>

        </div>
        <div class="pw-donate-invoice-actions">
            <a href="<?php echo e(route('donate')); ?>" class="pw-btn pw-btn--ghost pw-btn--sm">
                <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true">
                    <path d="M10 3L5 8l5 5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Buat Invoice Baru
            </a>
            <a href="<?php echo e(route('donate.history')); ?>" class="pw-btn pw-btn--ghost pw-btn--sm">
                <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true"><rect x="2" y="3" width="12" height="11" rx="1.5" stroke="currentColor" stroke-width="1.2"/><path d="M5 1v4M11 1v4M2 7h12" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/></svg>
                Riwayat Transaksi
            </a>
        </div>

    </div>
</section>

<?php $__env->stopSection(); ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($invoice->status === 'pending'): ?>
<?php $__env->startPush('scripts'); ?>
<script>
(function () {
    const invNum     = <?php echo json_encode($invoice->invoice_number, 15, 512) ?>;
    const expiresMs  = <?php echo e($invoice->expires_at ? $invoice->expires_at->timestamp * 1000 : 0); ?>;
    const csrfToken  = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
    let   pollCount  = 0;
    let   timeIsUp   = false;

    /* ── Countdown ─────────────────────────────────────── */
    function tick() {
        if (timeIsUp || !expiresMs) return;
        const diff = expiresMs - Date.now();
        const el   = document.getElementById('countdown-time');
        const bar  = document.getElementById('countdown-bar');

        if (diff <= 0) {
            timeIsUp = true;
            clearInterval(tickInterval);
            clearInterval(pollInterval);
            if (el)  el.textContent = '00:00';
            triggerExpire();
            return;
        }

        const mins = Math.floor(diff / 60000);
        const secs = Math.floor((diff % 60000) / 1000);
        if (el) el.textContent = String(mins).padStart(2,'0') + ':' + String(secs).padStart(2,'0');

        if (bar && diff < 120000) bar.classList.add('pw-countdown--urgent');
    }

    const tickInterval = setInterval(tick, 1000);
    tick();

    /* ── Expire call ────────────────────────────────────── */
    function triggerExpire() {
        fetch(`/donate/invoice/${invNum}/expire`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept':       'application/json',
                'Content-Type': 'application/json',
            }
        }).catch(() => {}).finally(() => showExpiredUI());
    }

    /* ── Show Expired UI ────────────────────────────────── */
    function showExpiredUI() {
        const statusBar = document.getElementById('status-bar');
        if (statusBar) {
            statusBar.innerHTML =
                '<span class="pw-qris-status pw-qris-status--expired">' +
                '<svg viewBox="0 0 16 16" fill="none" width="14" aria-hidden="true">' +
                '<circle cx="8" cy="8" r="6.5" stroke="currentColor" stroke-width="1.2"/>' +
                '<path d="M8 5v3l2 2" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>' +
                '</svg>Waktu Habis</span>';
        }
        const ps = document.getElementById('payment-section');
        if (ps) {
            ps.innerHTML =
                '<div style="text-align:center;padding:2rem 1rem;display:flex;flex-direction:column;align-items:center;gap:.8rem;">' +
                '<svg viewBox="0 0 48 48" fill="none" width="52" aria-hidden="true">' +
                '<circle cx="24" cy="24" r="22" stroke="#fb923c" stroke-width="1.5" fill="rgba(251,146,60,.08)"/>' +
                '<circle cx="24" cy="24" r="12" stroke="#fb923c" stroke-width="1" opacity=".35"/>' +
                '<path d="M24 15v9l5.5 5.5" stroke="#fb923c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>' +
                '</svg>' +
                '<div style="font-size:.9rem;font-weight:600;color:#fb923c;">Waktu Pembayaran Habis</div>' +
                '<p style="font-size:.78rem;color:var(--pw-text-muted);line-height:1.6;max-width:240px;">Invoice ini sudah kadaluarsa. Buat invoice baru untuk melanjutkan top-up Gold.</p>' +
                '<a href="<?php echo e(route('donate')); ?>" class="pw-btn pw-btn--gold pw-btn--sm" style="margin-top:.2rem;">Buat Invoice Baru</a>' +
                '</div>';
        }
        const guide = document.getElementById('payment-guide');
        if (guide) {
            guide.innerHTML =
                '<div class="pw-invoice-guide__title" style="color:#fb923c;">Invoice Kadaluarsa</div>' +
                '<p style="font-size:.82rem;color:var(--pw-text-muted);line-height:1.7;margin:.4rem 0 0;">Batas waktu 10 menit telah habis. Silakan buat invoice baru untuk melanjutkan pembayaran.</p>' +
                '<a href="<?php echo e(route('donate')); ?>" class="pw-btn pw-btn--gold pw-btn--sm" style="margin-top:.9rem;display:inline-flex;">Buat Invoice Baru</a>';
        }
    }

    /* ── Show Failed UI ─────────────────────────────────── */
    function showFailedUI() {
        const statusBar = document.getElementById('status-bar');
        if (statusBar) {
            statusBar.innerHTML =
                '<span class="pw-qris-status pw-qris-status--failed">' +
                '<svg viewBox="0 0 16 16" fill="none" width="14" aria-hidden="true">' +
                '<circle cx="8" cy="8" r="7" fill="rgba(229,62,62,.15)"/>' +
                '<path d="M5.5 5.5l5 5M10.5 5.5l-5 5" stroke="#fca5a5" stroke-width="1.5" stroke-linecap="round"/>' +
                '</svg>Gagal / Ditolak</span>';
        }
        setTimeout(() => location.reload(), 1500);
    }

    /* ── Polling ────────────────────────────────────────── */
    const pollInterval = setInterval(() => {
        if (timeIsUp) { clearInterval(pollInterval); return; }
        if (++pollCount > 72) { clearInterval(pollInterval); return; }

        fetch(`/donate/invoice/${invNum}/status`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'paid') {
                clearInterval(pollInterval);
                clearInterval(tickInterval);
                timeIsUp = true;
                const bar = document.getElementById('countdown-bar');
                if (bar) bar.style.display = 'none';
                const statusBar = document.getElementById('status-bar');
                if (statusBar) {
                    statusBar.innerHTML =
                        '<span class="pw-qris-status pw-qris-status--paid">' +
                        '<svg viewBox="0 0 16 16" fill="none" width="14" aria-hidden="true">' +
                        '<circle cx="8" cy="8" r="7" fill="rgba(56,161,105,.2)"/>' +
                        '<path d="M5 8l2 2 4-4" stroke="#6ee7b7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>' +
                        '</svg>Pembayaran Berhasil</span>';
                }
                setTimeout(() => location.reload(), 1500);
            } else if (data.status === 'expired') {
                clearInterval(pollInterval);
                clearInterval(tickInterval);
                timeIsUp = true;
                showExpiredUI();
            } else if (data.status === 'failed') {
                clearInterval(pollInterval);
                clearInterval(tickInterval);
                timeIsUp = true;
                showFailedUI();
            }
        })
        .catch(() => {});
    }, 5000);
})();
</script>
<?php $__env->stopPush(); ?>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/pw-panel/resources/views/front/donate/invoice.blade.php ENDPATH**/ ?>