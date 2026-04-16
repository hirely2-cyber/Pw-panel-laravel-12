<?php $__env->startSection('title', 'Pengaturan Website'); ?>

<?php $__env->startSection('content'); ?>

<form action="<?php echo e(route('admin.settings.update')); ?>" method="POST" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>

    
    <div class="pw-adm-card" style="margin-bottom:1.25rem;">
        <div class="pw-adm-card__title">
            <svg viewBox="0 0 20 20" fill="none" width="16"><rect x="2" y="3" width="16" height="14" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M2 13l4-4 3 3 3-3 4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Gambar Website
        </div>

        
        <div style="margin-bottom:1.25rem;">
            <label class="pw-form__label" style="margin-bottom:.5rem;display:flex;align-items:center;gap:.5rem;">
                Background Hero Section
                <span style="font-size:.7rem;color:var(--pw-text-muted);font-weight:400;">JPG / PNG / WEBP &middot; disarankan 1920x1080 &middot; maks 5 MB</span>
            </label>
            <?php $heroBg = $settings->get('site_hero_bg'); ?>
            <div class="pw-img-upload" id="hero-upload-wrap" style="aspect-ratio:16/5;min-height:unset;">
                <input type="file" name="site_hero_bg" accept="image/*" id="hero-bg-input">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($heroBg): ?>
                    <img src="<?php echo e(Storage::url($heroBg)); ?>" class="pw-img-upload__preview" id="hero-bg-preview" alt="Hero BG">
                    <div class="pw-img-upload__label">Klik untuk ganti gambar</div>
                    <label class="pw-img-upload__remove" onclick="event.stopPropagation()">
                        <input type="checkbox" name="remove_site_hero_bg" value="1" style="display:none" onchange="this.closest('form').submit()">
                        <svg viewBox="0 0 16 16" fill="none" width="12"><path d="M3 4h10M6 4V3h4v1M5 4l.5 9h5L11 4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                        Hapus
                    </label>
                <?php else: ?>
                    <img src="" class="pw-img-upload__preview" id="hero-bg-preview" alt="" style="display:none">
                    <svg class="pw-img-upload__icon" viewBox="0 0 40 40" fill="none"><rect x="4" y="8" width="32" height="24" rx="3" stroke="currentColor" stroke-width="1.8"/><path d="M4 26l8-8 6 6 5-5 9 9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><circle cx="28" cy="16" r="3" stroke="currentColor" stroke-width="1.8"/></svg>
                    <div class="pw-img-upload__label">
                        <strong>Klik atau seret gambar ke sini</strong>
                        Background halaman utama (hero section)
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['site_hero_bg'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <p style="color:#ff6b6b;font-size:.75rem;margin-top:.35rem;"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">

            
            <div>
                <label class="pw-form__label" style="margin-bottom:.5rem;display:flex;align-items:center;gap:.5rem;">
                    Logo Website
                    <span style="font-size:.7rem;color:var(--pw-text-muted);font-weight:400;">PNG transparan &middot; maks 2 MB</span>
                </label>
                <?php $logo = $settings->get('site_logo'); ?>
                <div class="pw-img-upload" id="logo-upload-wrap" style="aspect-ratio:3/1;min-height:unset;">
                    <input type="file" name="site_logo" accept="image/*" id="logo-input">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($logo): ?>
                        <img src="<?php echo e(Storage::url($logo)); ?>" class="pw-img-upload__preview" id="logo-preview" alt="Logo" style="max-height:70px;object-fit:contain;background:rgba(0,0,0,.3);padding:.5rem;border-radius:4px;">
                        <div class="pw-img-upload__label">Klik untuk ganti logo</div>
                        <label class="pw-img-upload__remove" onclick="event.stopPropagation()">
                            <input type="checkbox" name="remove_site_logo" value="1" style="display:none" onchange="this.closest('form').submit()">
                            <svg viewBox="0 0 16 16" fill="none" width="12"><path d="M3 4h10M6 4V3h4v1M5 4l.5 9h5L11 4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                            Hapus
                        </label>
                    <?php else: ?>
                        <img src="" class="pw-img-upload__preview" id="logo-preview" alt="" style="display:none">
                        <svg class="pw-img-upload__icon" viewBox="0 0 40 40" fill="none"><rect x="4" y="8" width="32" height="24" rx="3" stroke="currentColor" stroke-width="1.8"/><path d="M20 14v12M14 20h12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                        <div class="pw-img-upload__label">
                            <strong>Klik atau seret gambar ke sini</strong>
                            Menggantikan SVG logo default di navbar
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['site_logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p style="color:#ff6b6b;font-size:.75rem;margin-top:.35rem;"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            
            <div>
                <label class="pw-form__label" style="margin-bottom:.5rem;display:flex;align-items:center;gap:.5rem;">
                    Favicon
                    <span style="font-size:.7rem;color:var(--pw-text-muted);font-weight:400;">ICO / PNG / SVG &middot; 32x32 atau 64x64 &middot; maks 512 KB</span>
                </label>
                <?php $favicon = $settings->get('site_favicon'); ?>
                <div class="pw-img-upload" id="favicon-upload-wrap" style="aspect-ratio:3/1;min-height:unset;">
                    <input type="file" name="site_favicon" accept=".ico,.png,.svg,image/x-icon" id="favicon-input">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($favicon): ?>
                        <img src="<?php echo e(Storage::url($favicon)); ?>" class="pw-img-upload__preview" id="favicon-preview" alt="Favicon" style="max-height:56px;max-width:56px;object-fit:contain;background:rgba(0,0,0,.3);padding:.5rem;border-radius:4px;">
                        <div class="pw-img-upload__label">Klik untuk ganti favicon</div>
                        <label class="pw-img-upload__remove" onclick="event.stopPropagation()">
                            <input type="checkbox" name="remove_site_favicon" value="1" style="display:none" onchange="this.closest('form').submit()">
                            <svg viewBox="0 0 16 16" fill="none" width="12"><path d="M3 4h10M6 4V3h4v1M5 4l.5 9h5L11 4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                            Hapus
                        </label>
                    <?php else: ?>
                        <img src="" class="pw-img-upload__preview" id="favicon-preview" alt="" style="display:none">
                        <svg class="pw-img-upload__icon" viewBox="0 0 40 40" fill="none"><rect x="8" y="8" width="24" height="24" rx="4" stroke="currentColor" stroke-width="1.8"/><circle cx="20" cy="20" r="6" stroke="currentColor" stroke-width="1.8"/></svg>
                        <div class="pw-img-upload__label">
                            <strong>Klik atau seret file ke sini</strong>
                            Ikon di tab browser & bookmark
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['site_favicon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p style="color:#ff6b6b;font-size:.75rem;margin-top:.35rem;"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

        </div>
    </div>

    
    <div class="pw-adm-card" style="margin-bottom:1.25rem;">
        <div class="pw-adm-card__title">
            <svg viewBox="0 0 20 20" fill="none" width="16"><circle cx="9" cy="9" r="6" stroke="currentColor" stroke-width="1.5"/><path d="M13 13l4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            SEO &amp; Meta Tags
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">

            <div class="pw-form__group">
                <label class="pw-form__label" style="display:flex;justify-content:space-between;">
                    <span>
                        <svg viewBox="0 0 16 16" fill="none" width="13" style="vertical-align:middle;margin-right:.3rem;opacity:.6"><path d="M2 4h12M2 8h8M2 12h5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                        Meta Title
                    </span>
                    <span id="seo-title-count" style="font-size:.68rem;color:var(--pw-text-muted);font-weight:400;">0/60</span>
                </label>
                <input type="text" name="seo_title" id="seo-title-input" class="pw-form__input" maxlength="60"
                    value="<?php echo e($settings->get('seo_title', '')); ?>"
                    placeholder="<?php echo e(config('pw-config.server.name', 'Perfect World')); ?> -- <?php echo e(config('pw-config.server.tagline', 'Private Server')); ?>"
                    oninput="document.getElementById('seo-title-count').textContent=this.value.length+'/60'">
                <p class="pw-form__hint">Judul yang muncul di tab browser & hasil pencarian Google. Maks 60 karakter.</p>
            </div>

            <div class="pw-form__group">
                <label class="pw-form__label">
                    <svg viewBox="0 0 16 16" fill="none" width="13" style="vertical-align:middle;margin-right:.3rem;opacity:.6"><rect x="2" y="2" width="12" height="12" rx="2" stroke="currentColor" stroke-width="1.3"/><path d="M5 6h6M5 9h4" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/></svg>
                    Google Analytics ID
                </label>
                <input type="text" name="seo_google_analytics" class="pw-form__input"
                    value="<?php echo e($settings->get('seo_google_analytics', '')); ?>"
                    placeholder="G-XXXXXXXXXX">
                <p class="pw-form__hint">GA4 Measurement ID. Kosongkan bila tidak digunakan.</p>
            </div>

        </div>

        <div class="pw-form__group" style="margin-bottom:1rem;">
            <label class="pw-form__label" style="display:flex;justify-content:space-between;">
                <span>
                    <svg viewBox="0 0 16 16" fill="none" width="13" style="vertical-align:middle;margin-right:.3rem;opacity:.6"><path d="M2 4h12M2 7h12M2 10h8" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                    Meta Description
                </span>
                <span id="seo-desc-count" style="font-size:.68rem;color:var(--pw-text-muted);font-weight:400;">0/160</span>
            </label>
            <textarea name="seo_description" id="seo-desc-input" class="pw-form__input" rows="3" maxlength="160"
                placeholder="Deskripsi singkat server yang tampil di hasil pencarian Google..."
                oninput="document.getElementById('seo-desc-count').textContent=this.value.length+'/160'"
                style="resize:vertical;"><?php echo e($settings->get('seo_description', '')); ?></textarea>
            <p class="pw-form__hint">Tampil di bawah judul pada hasil pencarian. Maks 160 karakter.</p>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.25rem;">

            <div class="pw-form__group">
                <label class="pw-form__label">
                    <svg viewBox="0 0 16 16" fill="none" width="13" style="vertical-align:middle;margin-right:.3rem;opacity:.6"><path d="M2 8h12M8 2v12" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                    Keywords
                </label>
                <input type="text" name="seo_keywords" class="pw-form__input"
                    value="<?php echo e($settings->get('seo_keywords', '')); ?>"
                    placeholder="perfect world, private server, mmorpg, pw private">
                <p class="pw-form__hint">Pisahkan dengan koma. Tidak terlalu berpengaruh di Google, opsional.</p>
            </div>

            <div>
                <label class="pw-form__label" style="margin-bottom:.5rem;display:flex;align-items:center;gap:.5rem;">
                    OG Image <span style="font-size:.7rem;color:var(--pw-text-muted);font-weight:400;">1200x630 &middot; JPG/PNG &middot; maks 2 MB</span>
                </label>
                <?php $ogImg = $settings->get('seo_og_image'); ?>
                <div class="pw-img-upload" id="og-upload-wrap" style="aspect-ratio:1200/400;min-height:unset;">
                    <input type="file" name="seo_og_image" accept="image/jpeg,image/jpg,image/png" id="og-img-input">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ogImg): ?>
                        <img src="<?php echo e(Storage::url($ogImg)); ?>" class="pw-img-upload__preview" id="og-img-preview" alt="OG Image">
                        <div class="pw-img-upload__label">Klik untuk ganti OG image</div>
                        <label class="pw-img-upload__remove" onclick="event.stopPropagation()">
                            <input type="checkbox" name="remove_seo_og_image" value="1" style="display:none" onchange="this.closest('form').submit()">
                            <svg viewBox="0 0 16 16" fill="none" width="12"><path d="M3 4h10M6 4V3h4v1M5 4l.5 9h5L11 4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                            Hapus
                        </label>
                    <?php else: ?>
                        <img src="" class="pw-img-upload__preview" id="og-img-preview" alt="" style="display:none">
                        <svg class="pw-img-upload__icon" viewBox="0 0 40 40" fill="none"><rect x="4" y="8" width="32" height="24" rx="3" stroke="currentColor" stroke-width="1.8"/><path d="M4 26l8-8 6 6 5-5 9 9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <div class="pw-img-upload__label">
                            <strong>Gambar pratinjau di WhatsApp / Facebook / Twitter</strong>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['seo_og_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p style="color:#ff6b6b;font-size:.75rem;margin-top:.35rem;"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

        </div>

        
        <div class="pw-seo-preview" style="border-radius:8px;padding:1rem;">
            <p style="font-size:.7rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.07em;margin-bottom:.6rem;">Pratinjau Google</p>
            <p id="seo-preview-title" style="color:#8ab4f8;font-size:.95rem;font-weight:500;margin-bottom:.15rem;line-clamp:1;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;">
                <?php echo e($settings->get('seo_title') ?: config('pw-config.server.name', 'Perfect World').' -- '.config('pw-config.server.tagline', 'Private Server')); ?>

            </p>
            <p id="seo-preview-url" style="color:#4caf7d;font-size:.75rem;margin-bottom:.15rem;"><?php echo e(url('/')); ?></p>
            <p id="seo-preview-desc" style="color:var(--pw-text-muted);font-size:.8rem;line-height:1.5;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                <?php echo e($settings->get('seo_description') ?: 'Tidak ada deskripsi.'); ?>

            </p>
        </div>

    </div>

    
    <div class="pw-adm-card" style="margin-bottom:1.25rem;">
        <div class="pw-adm-card__title">
            <svg viewBox="0 0 20 20" fill="none" width="16"><circle cx="15" cy="5" r="2" stroke="currentColor" stroke-width="1.5"/><circle cx="15" cy="15" r="2" stroke="currentColor" stroke-width="1.5"/><circle cx="5" cy="10" r="2" stroke="currentColor" stroke-width="1.5"/><path d="M7 10.8l6 2.7M7 9.2l6-2.7" stroke="currentColor" stroke-width="1.3"/></svg>
            Sosial Media &amp; Download
        </div>

        <div class="pw-adm-grid" style="grid-template-columns:repeat(3,1fr);">
            <div class="pw-form__group">
                <label class="pw-form__label">
                    <svg viewBox="0 0 20 20" fill="none" width="14" style="vertical-align:middle;margin-right:.3rem;opacity:.6"><path d="M2 5.5A2.5 2.5 0 014.5 3h11A2.5 2.5 0 0118 5.5v9a2.5 2.5 0 01-2.5 2.5h-11A2.5 2.5 0 012 14.5v-9z" stroke="currentColor" stroke-width="1.4"/><path d="M6 8.5c0 1.933 1.567 3.5 3.5 3.5a3.5 3.5 0 003.5-3.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
                    WhatsApp
                </label>
                <input type="text" name="social_whatsapp" class="pw-form__input"
                    value="<?php echo e($settings->get('social_whatsapp', '')); ?>"
                    placeholder="628118719377">
                <p class="pw-form__hint">Nomor HP tanpa + (contoh: 6281xxx)</p>
            </div>

            <div class="pw-form__group">
                <label class="pw-form__label">
                    <svg viewBox="0 0 20 20" fill="none" width="14" style="vertical-align:middle;margin-right:.3rem;opacity:.6"><rect x="3" y="3" width="14" height="14" rx="3" stroke="currentColor" stroke-width="1.4"/><circle cx="10" cy="10" r="3" stroke="currentColor" stroke-width="1.4"/><circle cx="14" cy="6" r=".8" fill="currentColor"/></svg>
                    Facebook Group URL
                </label>
                <input type="url" name="social_facebook" class="pw-form__input"
                    value="<?php echo e($settings->get('social_facebook', '')); ?>"
                    placeholder="https://facebook.com/groups/yourgroup">
            </div>

            <div class="pw-form__group">
                <label class="pw-form__label">
                    <svg viewBox="0 0 20 20" fill="none" width="14" style="vertical-align:middle;margin-right:.3rem;opacity:.6"><path d="M3 14.5c1.5-1 3-1.5 5-1.5h4c2 0 3.5.5 5 1.5M7 9a3 3 0 106 0 3 3 0 00-6 0z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
                    Discord Invite URL
                </label>
                <input type="url" name="social_discord" class="pw-form__input"
                    value="<?php echo e($settings->get('social_discord', '')); ?>"
                    placeholder="https://discord.gg/xxxxx">
            </div>

            <div class="pw-form__group">
                <label class="pw-form__label">
                    <svg viewBox="0 0 20 20" fill="none" width="14" style="vertical-align:middle;margin-right:.3rem;opacity:.6"><path d="M10 3v10M6 9l4 4 4-4M4 15h12" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Download — Full Client
                </label>
                <input type="url" name="download_url" class="pw-form__input"
                    value="<?php echo e($settings->get('download_url', '')); ?>"
                    placeholder="https://drive.google.com/...">
                <p class="pw-form__hint">Link download Full Client (file lengkap). Kosongkan jika tidak tersedia.</p>
            </div>

            <div class="pw-form__group">
                <label class="pw-form__label">
                    <svg viewBox="0 0 20 20" fill="none" width="14" style="vertical-align:middle;margin-right:.3rem;opacity:.6"><path d="M4 4h5v5H4zM11 4h5v5h-5zM4 11h5v5H4zM11 11h5v5h-5z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/></svg>
                    Download — Part Client
                </label>
                <input type="url" name="download_url_part" class="pw-form__input"
                    value="<?php echo e($settings->get('download_url_part', '')); ?>"
                    placeholder="https://drive.google.com/...">
                <p class="pw-form__hint">Link download Part Client (file terbagi). Kosongkan jika tidak tersedia.</p>
            </div>

            <div class="pw-form__group">
                <label class="pw-form__label">
                    <svg viewBox="0 0 20 20" fill="none" width="14" style="vertical-align:middle;margin-right:.3rem;opacity:.6"><path d="M4 4v12h12M7 13l3-4 3 2 4-5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Download — Update / Patch
                </label>
                <input type="url" name="download_url_patch" class="pw-form__input"
                    value="<?php echo e($settings->get('download_url_patch', '')); ?>"
                    placeholder="https://drive.google.com/...">
                <p class="pw-form__hint">Link download Patch / Update saja. Kosongkan jika tidak tersedia.</p>
            </div>
        </div>
    </div>

    
    <div class="pw-adm-card" style="margin-bottom:1.25rem;">
        <div class="pw-adm-card__title">
            <svg viewBox="0 0 20 20" fill="none" width="16"><rect x="2" y="5" width="16" height="12" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M2 9h16" stroke="currentColor" stroke-width="1.5"/><path d="M6 13h3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            Payment Gateway — PayHook
        </div>

        <div style="background:rgba(255,200,50,.06);border:1px solid rgba(255,200,50,.18);border-radius:8px;padding:.75rem 1rem;margin-bottom:1.25rem;font-size:.8rem;color:var(--pw-text-muted);display:flex;gap:.6rem;align-items:flex-start;">
            <svg viewBox="0 0 20 20" fill="none" width="16" style="flex-shrink:0;margin-top:.1rem;color:#e5b742"><circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.5"/><path d="M10 7v4M10 13v.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
            <span>Pengaturan di sini akan menimpa nilai di file <code style="background:rgba(255,255,255,.07);padding:.1rem .35rem;border-radius:3px;font-size:.75rem;">.env</code>. Kosongkan field untuk menggunakan nilai <code style="background:rgba(255,255,255,.07);padding:.1rem .35rem;border-radius:3px;font-size:.75rem;">.env</code>.</span>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">

            <div class="pw-form__group" style="grid-column:1/-1;">
                <label class="pw-form__label">
                    <svg viewBox="0 0 20 20" fill="none" width="14" style="vertical-align:middle;margin-right:.3rem;opacity:.6"><circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.5"/><path d="M10 6v8M6 10h8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    PayHook Server URL
                </label>
                <input type="url" name="payhook_url" class="pw-form__input"
                    value="<?php echo e($settings->get('payhook_url', '')); ?>"
                    placeholder="http://192.168.1.9:8001">
                <p class="pw-form__hint">URL server PayHook tempat QRIS diproses. Contoh: <code style="opacity:.7;">http://192.168.1.9:8001</code></p>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['payhook_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p style="color:#ff6b6b;font-size:.75rem;margin-top:.35rem;"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div class="pw-form__group">
                <label class="pw-form__label">
                    <svg viewBox="0 0 20 20" fill="none" width="14" style="vertical-align:middle;margin-right:.3rem;opacity:.6"><rect x="3" y="8" width="14" height="10" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M7 8V6a3 3 0 016 0v2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><circle cx="10" cy="13" r="1.2" fill="currentColor"/></svg>
                    API Key
                </label>
                <input type="text" name="payhook_api_key" class="pw-form__input" autocomplete="off"
                    value="<?php echo e($settings->get('payhook_api_key', '')); ?>"
                    placeholder="Masukkan API Key dari PayHook server">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['payhook_api_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p style="color:#ff6b6b;font-size:.75rem;margin-top:.35rem;"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div class="pw-form__group">
                <label class="pw-form__label">
                    <svg viewBox="0 0 20 20" fill="none" width="14" style="vertical-align:middle;margin-right:.3rem;opacity:.6"><path d="M10 3l1.5 3.5 3.8.5-2.8 2.6.7 3.7L10 11.5l-3.2 1.8.7-3.7L4.7 7l3.8-.5L10 3z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/></svg>
                    Webhook Secret
                </label>
                <input type="text" name="payhook_webhook_secret" class="pw-form__input" autocomplete="off"
                    value="<?php echo e($settings->get('payhook_webhook_secret', '')); ?>"
                    placeholder="Secret untuk verifikasi signature webhook">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['payhook_webhook_secret'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p style="color:#ff6b6b;font-size:.75rem;margin-top:.35rem;"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

        </div>

        <div style="display:flex;align-items:center;gap:.75rem;padding:.75rem 1rem;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:8px;">
            <label style="display:flex;align-items:center;gap:.6rem;cursor:pointer;font-size:.85rem;color:var(--pw-text-muted);">
                <input type="checkbox" name="payhook_sandbox" value="1" style="width:16px;height:16px;accent-color:var(--pw-gold);"
                    <?php echo e($settings->get('payhook_sandbox', '1') === '1' ? 'checked' : ''); ?>>
                <span><strong style="color:var(--pw-text);">Mode Sandbox</strong> — aktifkan saat testing, nonaktifkan di production</span>
            </label>
        </div>
    </div>

    
    <div class="pw-adm-card" style="margin-bottom:1.25rem;">
        <div class="pw-adm-card__title">
            <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M4 5h12M4 10h12M4 15h7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            Fitur & Layanan
        </div>
        <p style="font-size:.78rem;color:var(--pw-text-muted);margin-bottom:1.1rem;">Aktifkan atau nonaktifkan fitur panel. Fitur yang dinonaktifkan akan disembunyikan dari menu dan tidak dapat diakses player.</p>

        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:.7rem;">

            <?php
            $featureList = [
                'shop'     => ['label' => 'Gold Shop',          'desc' => 'Toko item in-game dengan Gold Points'],
                'donate'   => ['label' => 'Top-up Gold Points', 'desc' => 'Donasi / top-up QRIS Gold Points'],
                'voucher'  => ['label' => 'Voucher',            'desc' => 'Penukaran kode voucher hadiah'],
                'ranking'  => ['label' => 'Ranking',            'desc' => 'Leaderboard karakter & faction'],
                'vote'     => ['label' => 'Vote',               'desc' => 'Sistem vote reward harian'],
                'service'  => ['label' => 'Layanan Karakter',   'desc' => 'Level up, teleport, reset, dll'],
                'news'     => ['label' => 'Berita / Update',    'desc' => 'Halaman berita & patch notes'],
                'register' => ['label' => 'Registrasi Akun',    'desc' => 'Pendaftaran akun baru oleh player'],
                'cubi_shop'=> ['label' => 'Cubi Shop',          'desc' => 'Top-up Cubi Gold via QRIS'],
            ];
            ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $featureList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $feat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <?php $isOn = $settings->get('feature_' . $key, '1') === '1'; ?>
            <label class="pw-feat-toggle <?php echo e($isOn ? 'is-on' : ''); ?>" style="display:flex;align-items:center;justify-content:space-between;gap:.8rem;padding:.75rem 1rem;background:rgba(255,255,255,.03);border:1px solid <?php echo e($isOn ? 'rgba(200,151,42,.3)' : 'rgba(255,255,255,.07)'); ?>;border-radius:8px;cursor:pointer;transition:border-color .15s;">
                <div>
                    <div style="font-size:.85rem;font-weight:600;color:var(--pw-text);"><?php echo e($feat['label']); ?></div>
                    <div style="font-size:.73rem;color:var(--pw-text-muted);margin-top:.1rem;"><?php echo e($feat['desc']); ?></div>
                </div>
                <div style="position:relative;flex-shrink:0;width:42px;height:24px;">
                    <input type="hidden" name="feature_<?php echo e($key); ?>" value="0">
                    <input type="checkbox" name="feature_<?php echo e($key); ?>" value="1"
                        class="pw-feat-cb"
                        style="position:absolute;width:100%;height:100%;opacity:0;margin:0;cursor:pointer;z-index:1;"
                        <?php echo e($isOn ? 'checked' : ''); ?>>
                    <div class="pw-feat-track" style="position:absolute;inset:0;border-radius:12px;background:<?php echo e($isOn ? 'var(--pw-gold)' : 'rgba(255,255,255,.12)'); ?>;transition:background .2s;pointer-events:none;"></div>
                    <div class="pw-feat-knob" style="position:absolute;top:3px;left:<?php echo e($isOn ? '21px' : '3px'); ?>;width:18px;height:18px;background:#fff;border-radius:50%;transition:left .2s;pointer-events:none;"></div>
                </div>
            </label>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

        </div>
    </div>

    
    <div style="display:flex;justify-content:flex-end;">
        <button type="submit" class="pw-btn pw-btn--gold">
            <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M4 10l4 4 8-8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Simpan Pengaturan
        </button>
    </div>

</form>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Feature toggle interactivity
document.querySelectorAll('.pw-feat-cb').forEach(cb => {
    const label = cb.closest('label');
    const track = label.querySelector('.pw-feat-track');
    const knob  = label.querySelector('.pw-feat-knob');
    cb.addEventListener('change', () => {
        const on = cb.checked;
        track.style.background = on ? 'var(--pw-gold)' : 'rgba(255,255,255,.12)';
        knob.style.left  = on ? '21px' : '3px';
        label.style.borderColor = on ? 'rgba(200,151,42,.3)' : 'rgba(255,255,255,.07)';
    });
});

function setupPreview(inputId, previewId) {
    const input   = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    if (!input || !preview) return;
    input.addEventListener('change', () => {
        const file = input.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => { preview.src = e.target.result; preview.style.display = 'block'; };
        reader.readAsDataURL(file);
    });
}
setupPreview('hero-bg-input', 'hero-bg-preview');
setupPreview('logo-input',    'logo-preview');
setupPreview('favicon-input', 'favicon-preview');
setupPreview('og-img-input',  'og-img-preview');

// SEO live preview
const titleInput = document.getElementById('seo-title-input');
const descInput  = document.getElementById('seo-desc-input');
if (titleInput) {
    titleInput.dispatchEvent(new Event('input'));
    titleInput.addEventListener('input', () => {
        const v = titleInput.value.trim();
        document.getElementById('seo-preview-title').textContent = v || titleInput.placeholder;
    });
}
if (descInput) {
    descInput.dispatchEvent(new Event('input'));
    descInput.addEventListener('input', () => {
        const v = descInput.value.trim();
        document.getElementById('seo-preview-desc').textContent = v || 'Tidak ada deskripsi.';
    });
}
// Init char counters
document.getElementById('seo-title-count').textContent = (titleInput?.value.length ?? 0) + '/60';
document.getElementById('seo-desc-count').textContent  = (descInput?.value.length  ?? 0) + '/160';
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/pw-panel/resources/views/admin/settings/index.blade.php ENDPATH**/ ?>