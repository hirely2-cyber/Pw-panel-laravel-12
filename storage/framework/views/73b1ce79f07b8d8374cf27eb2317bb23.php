

<?php
    $__siteName = \App\Models\Setting::get('seo_title') ?: config('pw-config.server.name') ?: 'Perfect World';
?>

<?php $__env->startSection('title', __('main.download_title') . ' — ' . $__siteName); ?>
<?php $__env->startSection('meta_description', __('main.download_title') . ' ' . $__siteName); ?>

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
        <h1 class="pw-page-hero__title"><?php echo e(__('main.download_title')); ?></h1>
        <p class="pw-page-hero__sub"><?php echo e(__('main.download_subtitle')); ?></p>
        <nav class="pw-breadcrumb" aria-label="breadcrumb">
            <a href="<?php echo e(route('home')); ?>" class="pw-breadcrumb__item">
                <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true">
                    <path d="M2 7.5L8 2l6 5.5V14a1 1 0 01-1 1h-3.5v-3.5h-3V15H3a1 1 0 01-1-1V7.5z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/>
                </svg>
                <?php echo e(__('main.nav_home')); ?>

            </a>
            <span class="pw-breadcrumb__sep" aria-hidden="true">/</span>
            <span class="pw-breadcrumb__item pw-breadcrumb__item--active">Download</span>
        </nav>
    </div>
</div>


<section class="pw-section" id="download">
    <div class="pw-section__inner">

        
        <?php
            $downloads = [
                [
                    'key'   => 'full',
                    'url'   => $downloadUrl,
                    'title' => __('main.download_full_title'),
                    'desc'  => __('main.download_full_desc'),
                    'icon'  => '<path d="M10 3v10M6 9l4 4 4-4M4 16h12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>',
                    'badge' => __('main.download_recommended'),
                    'color' => '#e8b84b',
                ],
                [
                    'key'   => 'part',
                    'url'   => $downloadUrlPart,
                    'title' => __('main.download_part_title'),
                    'desc'  => __('main.download_part_desc'),
                    'icon'  => '<path d="M4 4h5v5H4zM11 4h5v5h-5zM4 11h5v5H4zM11 11h5v5h-5z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/>',
                    'badge' => null,
                    'color' => '#6ba3e8',
                ],
                [
                    'key'   => 'patch',
                    'url'   => $downloadUrlPatch,
                    'title' => __('main.download_patch_title'),
                    'desc'  => __('main.download_patch_desc'),
                    'icon'  => '<path d="M4 4v12h12M7 13l3-4 3 2 4-5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>',
                    'badge' => null,
                    'color' => '#6be87a',
                ],
            ];
        ?>

        <div class="pw-download-grid">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $downloads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <div class="pw-card pw-download-card" style="text-align:center;display:flex;flex-direction:column;align-items:center;position:relative;<?php echo e($dl['key'] === 'full' ? 'border-color:rgba(232,184,75,.25);' : ''); ?>">

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($dl['badge']): ?>
                <div style="position:absolute;top:-.6rem;left:50%;transform:translateX(-50%);background:<?php echo e($dl['color']); ?>;color:#0a0a14;font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;padding:.2rem .75rem;border-radius:20px;">
                    <?php echo e($dl['badge']); ?>

                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                
                <div class="pw-download-icon-circle" style="width:56px;height:56px;border-radius:50%;background:rgba(255,255,255,.04);border:1px solid <?php echo e($dl['color']); ?>33;display:flex;align-items:center;justify-content:center;margin-bottom:1.25rem;">
                    <svg viewBox="0 0 20 20" fill="none" width="24" height="24" style="color:<?php echo e($dl['color']); ?>">
                        <?php echo $dl['icon']; ?>

                    </svg>
                </div>

                <h3 style="font-size:1.15rem;font-weight:700;color:<?php echo e($dl['color']); ?>;margin-bottom:.4rem;"><?php echo e($dl['title']); ?></h3>
                <p style="font-size:.82rem;color:var(--pw-text-muted,#8a8a9a);margin-bottom:1.5rem;flex:1;"><?php echo e($dl['desc']); ?></p>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($dl['url']): ?>
                <a href="<?php echo e($dl['url']); ?>" class="pw-btn <?php echo e($dl['key'] === 'full' ? 'pw-btn--gold pw-btn--glow' : 'pw-btn--outline'); ?>" target="_blank" rel="noopener" style="display:inline-flex;align-items:center;gap:.4rem;width:100%;justify-content:center;">
                    <svg viewBox="0 0 20 20" fill="none" width="16" height="16"><path d="M10 3v10M6 9l4 4 4-4M4 16h12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <?php echo e(__('main.download_btn')); ?>

                </a>
                <?php else: ?>
                <div class="pw-download-na" style="padding:.6rem 1rem;border-radius:.5rem;background:rgba(255,255,255,.03);border:1px dashed rgba(255,255,255,.1);color:var(--pw-text-muted,#6a6a7a);font-size:.8rem;width:100%;text-align:center;">
                    <?php echo e(__('main.download_not_available')); ?>

                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>

        
        <div class="pw-sysreq" style="max-width:960px;margin:2.5rem auto 0;">
            <div class="pw-sysreq__header">
                <svg viewBox="0 0 20 20" fill="none" width="18" height="18">
                    <rect x="3" y="2" width="14" height="11" rx="1.5" stroke="currentColor" stroke-width="1.4"/>
                    <path d="M7 17h6M10 13v4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                </svg>
                <h3><?php echo e(__('main.download_req')); ?></h3>
            </div>

            <div class="pw-sysreq__table-wrap">
                <table class="pw-sysreq__table">
                    <thead>
                        <tr>
                            <th class="pw-sysreq__col-label"></th>
                            <th class="pw-sysreq__col-min">
                                <span class="pw-sysreq__badge pw-sysreq__badge--min">Minimum</span>
                            </th>
                            <th class="pw-sysreq__col-rec">
                                <span class="pw-sysreq__badge pw-sysreq__badge--rec">Recommended</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $specs = [
                                ['icon' => '<path d="M3 5h14v8H3z" stroke="currentColor" stroke-width="1.3"/><path d="M7 17h6M10 13v4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>', 'label' => 'Operating System', 'min' => 'Windows 7 (32/64-bit)', 'rec' => 'Windows 10 / 11 (64-bit)'],
                                ['icon' => '<rect x="4" y="6" width="12" height="8" rx="1" stroke="currentColor" stroke-width="1.3"/><path d="M7 6V4h6v2" stroke="currentColor" stroke-width="1.3"/>', 'label' => 'Processor (CPU)', 'min' => 'Intel Core 2 Duo / AMD Athlon X2', 'rec' => 'Intel Core i3 / AMD Ryzen 3'],
                                ['icon' => '<rect x="3" y="5" width="4" height="10" rx=".5" stroke="currentColor" stroke-width="1.2"/><rect x="8" y="5" width="4" height="10" rx=".5" stroke="currentColor" stroke-width="1.2"/><rect x="13" y="5" width="4" height="10" rx=".5" stroke="currentColor" stroke-width="1.2"/>', 'label' => 'Memory (RAM)', 'min' => '4 GB RAM', 'rec' => '8 GB RAM'],
                                ['icon' => '<rect x="3" y="4" width="14" height="12" rx="1.5" stroke="currentColor" stroke-width="1.3"/><path d="M6 10l3-3 2 2 3-3" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>', 'label' => 'Graphics (GPU)', 'min' => 'NVIDIA GeForce GT 730 / AMD Radeon HD 7750 (1 GB VRAM)', 'rec' => 'NVIDIA GeForce GTX 1050 / AMD Radeon RX 560 (2 GB VRAM)'],
                                ['icon' => '<rect x="4" y="6" width="12" height="8" rx="1.5" stroke="currentColor" stroke-width="1.3"/><circle cx="10" cy="10" r="2" stroke="currentColor" stroke-width="1.2"/><circle cx="10" cy="10" r="4" stroke="currentColor" stroke-width="1" opacity=".4"/>', 'label' => 'Storage', 'min' => '17 GB free space (HDD)', 'rec' => '20 GB free space (SSD)'],
                                ['icon' => '<circle cx="10" cy="10" r="6" stroke="currentColor" stroke-width="1.3"/><path d="M10 4v2M10 14v2M4 10h2M14 10h2" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>', 'label' => 'Network', 'min' => 'Broadband Internet', 'rec' => 'Broadband Internet (10 Mbps+)'],
                            ];
                        ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $specs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $spec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <tr>
                            <td class="pw-sysreq__label">
                                <svg viewBox="0 0 20 20" fill="none" width="16" height="16" class="pw-sysreq__icon"><?php echo $spec['icon']; ?></svg>
                                <span><?php echo e($spec['label']); ?></span>
                            </td>
                            <td class="pw-sysreq__val pw-sysreq__val--min"><?php echo e($spec['min']); ?></td>
                            <td class="pw-sysreq__val pw-sysreq__val--rec"><?php echo e($spec['rec']); ?></td>
                        </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="pw-sysreq__note">
                <svg viewBox="0 0 16 16" fill="none" width="14" height="14"><circle cx="8" cy="8" r="6" stroke="currentColor" stroke-width="1.2"/><path d="M8 5v.5M8 7v4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                <span>Pastikan driver graphics sudah diperbarui ke versi terbaru untuk performa optimal.</span>
            </div>
        </div>

    </div>
</section>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.pw-download-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
    max-width: 960px;
    margin: 0 auto;
}
.pw-download-card {
    padding: 2rem 1.5rem;
}
.pw-download-req {
    padding: 2rem;
}
/* ── System Requirements Table ── */
.pw-sysreq__header {
    display: flex;
    align-items: center;
    gap: .6rem;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid rgba(184,134,11,.25);
    color: #d4a860;
}
.pw-sysreq__header h3 {
    font-family: 'Cinzel', serif;
    font-size: 1.15rem;
    font-weight: 700;
    color: #d4a860;
    margin: 0;
}
.pw-sysreq__table-wrap {
    overflow-x: auto;
    border: 1px solid rgba(255,255,255,.08);
    border-radius: 8px;
}
.pw-sysreq__table {
    width: 100%;
    border-collapse: collapse;
    font-size: .88rem;
}
.pw-sysreq__table thead tr {
    background: rgba(184,134,11,.08);
    border-bottom: 2px solid rgba(184,134,11,.2);
}
.pw-sysreq__table th {
    padding: .9rem 1.2rem;
    text-align: left;
    font-weight: 600;
}
.pw-sysreq__badge {
    display: inline-block;
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .1em;
    padding: .25rem .7rem;
    border-radius: 4px;
}
.pw-sysreq__badge--min {
    background: rgba(255,255,255,.06);
    border: 1px solid rgba(255,255,255,.12);
    color: rgba(255,255,255,.7);
}
.pw-sysreq__badge--rec {
    background: rgba(184,134,11,.15);
    border: 1px solid rgba(184,134,11,.35);
    color: #d4a860;
}
.pw-sysreq__table tbody tr {
    border-bottom: 1px solid rgba(255,255,255,.05);
    transition: background .2s;
}
.pw-sysreq__table tbody tr:last-child {
    border-bottom: none;
}
.pw-sysreq__table tbody tr:hover {
    background: rgba(184,134,11,.04);
}
.pw-sysreq__table td {
    padding: .8rem 1.2rem;
    vertical-align: middle;
}
.pw-sysreq__label {
    display: flex;
    align-items: center;
    gap: .6rem;
    font-weight: 600;
    color: rgba(255,255,255,.95);
    white-space: nowrap;
    min-width: 180px;
}
.pw-sysreq__icon {
    color: #d4a860;
    flex-shrink: 0;
}
.pw-sysreq__val {
    color: rgba(255,255,255,.75);
    font-size: .85rem;
}
.pw-sysreq__val--rec {
    color: rgba(255,255,255,.9);
    font-weight: 500;
}
.pw-sysreq__note {
    display: flex;
    align-items: flex-start;
    gap: .5rem;
    margin-top: 1rem;
    padding: .8rem 1rem;
    border-radius: 6px;
    background: rgba(184,134,11,.06);
    border: 1px solid rgba(184,134,11,.15);
    color: rgba(255,255,255,.6);
    font-size: .78rem;
    line-height: 1.5;
}
.pw-sysreq__note svg {
    color: #d4a860;
    flex-shrink: 0;
    margin-top: 1px;
}

/* Light theme */
[data-theme="light"] .pw-sysreq__header {
    color: #8a6020;
    border-bottom-color: rgba(184,134,11,.2);
}
[data-theme="light"] .pw-sysreq__header h3 { color: #8a6020; }
[data-theme="light"] .pw-sysreq__table-wrap { border-color: rgba(0,0,0,.1); }
[data-theme="light"] .pw-sysreq__table thead tr {
    background: rgba(184,134,11,.06);
    border-bottom-color: rgba(184,134,11,.15);
}
[data-theme="light"] .pw-sysreq__badge--min {
    background: rgba(0,0,0,.04);
    border-color: rgba(0,0,0,.12);
    color: rgba(0,0,0,.6);
}
[data-theme="light"] .pw-sysreq__badge--rec {
    background: rgba(184,134,11,.1);
    border-color: rgba(184,134,11,.3);
    color: #8a6020;
}
[data-theme="light"] .pw-sysreq__table tbody tr { border-bottom-color: rgba(0,0,0,.06); }
[data-theme="light"] .pw-sysreq__table tbody tr:hover { background: rgba(184,134,11,.04); }
[data-theme="light"] .pw-sysreq__label { color: #1a1a1a; }
[data-theme="light"] .pw-sysreq__icon { color: #8a6020; }
[data-theme="light"] .pw-sysreq__val { color: rgba(0,0,0,.65); }
[data-theme="light"] .pw-sysreq__val--rec { color: rgba(0,0,0,.85); }
[data-theme="light"] .pw-sysreq__note {
    background: rgba(184,134,11,.05);
    border-color: rgba(184,134,11,.15);
    color: rgba(0,0,0,.55);
}
[data-theme="light"] .pw-sysreq__note svg { color: #8a6020; }
@media (max-width: 768px) {
    .pw-download-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
        padding: 0 .25rem;
    }
    .pw-download-card {
        padding: 1.5rem 1rem;
    }
    .pw-download-req {
        padding: 1.25rem 1rem;
    }
    .pw-sysreq__table { font-size: .8rem; }
    .pw-sysreq__table th,
    .pw-sysreq__table td { padding: .6rem .8rem; }
    .pw-sysreq__label { min-width: 140px; font-size: .82rem; }
    .pw-sysreq__val { font-size: .8rem; }
}
@media (max-width: 420px) {
    .pw-sysreq__label span { display: none; }
    .pw-sysreq__table th,
    .pw-sysreq__table td { padding: .5rem .6rem; }
}
[data-theme="light"] .pw-download-icon-circle {
    background: rgba(0,0,0,.06) !important;
}
[data-theme="light"] .pw-download-na {
    background: #e0e0e0 !important;
    border-color: rgba(0,0,0,.18) !important;
    color: var(--pw-text-muted) !important;
}
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/pw-panel/resources/views/website/download.blade.php ENDPATH**/ ?>