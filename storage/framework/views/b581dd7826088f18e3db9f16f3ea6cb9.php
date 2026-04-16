<?php
    $__siteName = \App\Models\Setting::get('seo_title') ?: config('pw-config.server.name') ?: 'Perfect World';
    $__heroLogo = \App\Models\Setting::get('site_logo');
?>

<?php $__env->startSection('title', ($event->localizedTitle() ?? 'Event') . ' — ' . $__siteName); ?>
<?php $__env->startSection('meta_description', $event->localizedDescription() ?? 'Event Pre-Register ' . config('pw-config.server.name')); ?>

<?php $__env->startSection('content'); ?>


<div class="pw-page-hero">
    <div class="pw-page-hero__bg" aria-hidden="true"></div>
    <div class="pw-page-hero__inner" style="position:relative;z-index:1;">
        <div class="pw-page-hero__ornament" aria-hidden="true">
            <svg viewBox="0 0 160 20" fill="none" width="140">
                <line x1="0" y1="10" x2="55" y2="10" stroke="#c8972a" stroke-width="1"/>
                <path d="M65 3 L75 10 L65 17 L55 10 Z" fill="#c8972a" opacity=".5"/>
                <path d="M75 3 L85 10 L75 17 L65 10 Z" fill="#c8972a"/>
                <path d="M85 3 L95 10 L85 17 L75 10 Z" fill="#c8972a" opacity=".5"/>
                <line x1="95" y1="10" x2="150" y2="10" stroke="#c8972a" stroke-width="1"/>
            </svg>
        </div>
        <h1 style="font-family:'Cinzel',serif;font-size:clamp(1.8rem,4vw,2.8rem);font-weight:900;background:linear-gradient(135deg,#fbbf24 0%,#f59e0b 30%,#fcd34d 50%,#f59e0b 70%,#c8972a 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;line-height:1.1;filter:drop-shadow(0 2px 8px rgba(251,191,36,.3));margin:0;">
            <?php echo e($event->localizedTitle()); ?>

        </h1>
        <p class="pw-page-hero__sub"><?php echo e($event->localizedDescription()); ?></p>
        <nav class="pw-breadcrumb" aria-label="breadcrumb">
            <a href="<?php echo e(route('home')); ?>" class="pw-breadcrumb__item">
                <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true">
                    <path d="M2 7.5L8 2l6 5.5V14a1 1 0 01-1 1h-3.5v-3.5h-3V15H3a1 1 0 01-1-1V7.5z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/>
                </svg>
                <?php echo e(__('main.breadcrumb_home')); ?>

            </a>
            <span class="pw-breadcrumb__sep" aria-hidden="true">/</span>
            <span class="pw-breadcrumb__item pw-breadcrumb__item--active"><?php echo e(__('main.nav_event')); ?></span>
        </nav>
    </div>
</div>

<section class="pw-section" id="event" style="padding-top:.5rem;">
    <div class="pw-section__inner pw-section__inner--narrow">

        
        <div style="position:relative;text-align:center;padding:2.5rem 1rem;background:radial-gradient(ellipse at center,rgba(200,151,42,.08) 0%,transparent 70%);border-radius:14px;margin-bottom:1.5rem;">
            <div style="font-size:.78rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.15em;margin-bottom:.3rem;">Total Hadiah Referral</div>

            <div style="font-size:clamp(2rem,6vw,3.5rem);font-weight:900;font-family:'Cinzel',serif;background:linear-gradient(135deg,#fbbf24 0%,#f59e0b 30%,#fcd34d 50%,#f59e0b 70%,#c8972a 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;line-height:1.1;filter:drop-shadow(0 2px 8px rgba(251,191,36,.3));">
                Rp <?php echo e(number_format($totalRupiah, 0, ',', '.')); ?>

            </div>

            <div style="font-size:clamp(.85rem,2.5vw,1.05rem);color:var(--pw-text-muted);margin-top:.5rem;line-height:1.5;">
                Berupa <span style="color:#fbbf24;font-weight:700;"><?php echo e(number_format($totalCubi)); ?> Cubi Gold</span> per orang (maks.)
            </div>

            <div style="width:60px;height:1px;background:linear-gradient(90deg,transparent,rgba(200,151,42,.4),transparent);margin:1rem auto;"></div>

            <div style="font-size:.72rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.1em;margin-bottom:.3rem;">Status Event</div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->status === 'active'): ?>
                <div style="display:inline-flex;align-items:center;gap:.4rem;padding:.4rem 1.2rem;border-radius:20px;background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.25);">
                    <span style="width:8px;height:8px;border-radius:50%;background:#22c55e;display:inline-block;animation:pw-pulse-dot 1.5s infinite;"></span>
                    <span style="font-size:.85rem;font-weight:700;color:#22c55e;">Aktif</span>
                </div>
            <?php else: ?>
                <div style="display:inline-flex;align-items:center;gap:.4rem;padding:.4rem 1.2rem;border-radius:20px;background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.25);">
                    <span style="font-size:.85rem;font-weight:700;color:#f59e0b;">Berakhir</span>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div style="margin-top:.6rem;font-size:.78rem;color:var(--pw-text-muted);">
                <?php echo e($event->start_at?->format('d M Y')); ?> — <?php echo e($event->end_at?->format('d M Y')); ?>

            </div>
        </div>

        
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:1rem;margin-bottom:2rem;">
            <div class="pw-card" style="text-align:center;padding:1.2rem;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#c8972a" stroke-width="1.5" style="margin:0 auto .5rem;display:block;">
                    <path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4-4v2"/><circle cx="8.5" cy="7" r="4"/>
                    <line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/>
                </svg>
                <div style="font-size:1.6rem;font-weight:800;color:#c8972a;"><?php echo e(number_format($totalRegistered)); ?></div>
                <div style="font-size:.78rem;color:var(--pw-text-muted);">Total Registrasi</div>
            </div>
            <div class="pw-card" style="text-align:center;padding:1.2rem;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#c8972a" stroke-width="1.5" style="margin:0 auto .5rem;display:block;">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4-4v2"/><circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>
                </svg>
                <div style="font-size:1.6rem;font-weight:800;color:#c8972a;"><?php echo e(number_format($totalReferrals)); ?></div>
                <div style="font-size:.78rem;color:var(--pw-text-muted);">Via Referral</div>
            </div>
            <div class="pw-card" style="text-align:center;padding:1.2rem;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#c8972a" stroke-width="1.5" style="margin:0 auto .5rem;display:block;">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                </svg>
                <div style="font-size:1.6rem;font-weight:800;color:#c8972a;">Lv.<?php echo e($reqLevel); ?></div>
                <div style="font-size:.78rem;color:var(--pw-text-muted);">Syarat Level</div>
            </div>
        </div>

        
        <div class="pw-card" style="padding:1.5rem;margin-bottom:2rem;">
            <h2 style="font-family:'Cinzel',serif;font-size:1.1rem;font-weight:700;color:var(--pw-text-light);margin:0 0 .5rem;text-align:center;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#c8972a" stroke-width="2" style="vertical-align:-3px;margin-right:.3rem;">
                    <path d="M20 12V8H6a2 2 0 01-2-2c0-1.1.9-2 2-2h12v4"/><path d="M4 6v12c0 1.1.9 2 2 2h14v-4"/>
                    <path d="M18 12a2 2 0 000 4h4v-4h-4z"/>
                </svg>
                Hadiah Referral
            </h2>
            <div style="font-size:.82rem;color:var(--pw-text-muted);text-align:center;margin-bottom:1rem;">
                Ajak teman mendaftar dengan kode referral kamu. Semakin banyak, semakin besar hadiahnya!
            </div>
            <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:.8rem;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $tiers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div style="background:rgba(200,151,42,.06);border:1px solid rgba(200,151,42,.15);border-radius:10px;padding:1rem .5rem;text-align:center;">
                    <div style="font-size:1.2rem;font-weight:800;color:#c8972a;"><?php echo e($tier['count']); ?></div>
                    <div style="font-size:.7rem;color:var(--pw-text-muted);margin-bottom:.3rem;">Referral</div>
                    <div style="font-size:.82rem;font-weight:700;color:var(--pw-text-light);"><?php echo e(number_format($tier['reward'])); ?> Cubi</div>
                    <div style="font-size:.68rem;color:var(--pw-text-muted);">Rp <?php echo e(number_format($tier['reward'] * 1000, 0, ',', '.')); ?></div>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
            <div style="text-align:center;margin-top:1rem;font-size:.78rem;color:var(--pw-text-muted);">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:-2px;">
                    <circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>
                </svg>
                Konversi: 1 Cubi Gold = Rp 1.000 &bull; Syarat: Karakter min. Level <?php echo e($reqLevel); ?>

            </div>
        </div>

        
        <div class="pw-card" style="padding:1.5rem;margin-bottom:2rem;">
            <h2 style="font-family:'Cinzel',serif;font-size:1.1rem;font-weight:700;color:var(--pw-text-light);margin:0 0 1rem;text-align:center;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#c8972a" stroke-width="2" style="vertical-align:-3px;margin-right:.3rem;">
                    <path d="M6 9H4.5a2.5 2.5 0 010-5C6 4 8 6 8 6"/><path d="M18 9h1.5a2.5 2.5 0 000-5C18 4 16 6 16 6"/>
                    <path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20 7 22"/>
                    <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20 17 22"/><path d="M18 2H6v7a6 6 0 0012 0V2z"/>
                </svg>
                Top Referrer
            </h2>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($referrers->isEmpty()): ?>
            <div style="text-align:center;padding:2rem;color:var(--pw-text-muted);font-size:.9rem;">
                Belum ada data referral. Jadilah yang pertama!
            </div>
            <?php else: ?>
            <?php
                $podiumOrder  = [1, 0, 2];
                $podiumRank   = [2, 1, 3];
                $podiumClass  = ['pw-podium__step--silver', 'pw-podium__step--gold', 'pw-podium__step--bronze'];
                $rankClass    = ['pw-rank--2', 'pw-rank--1', 'pw-rank--3'];
                $rankColors   = ['#c0c0c0', '#ffd700', '#cd7f32'];
            ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($referrers->count() >= 3): ?>
            <div class="pw-podium">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $podiumOrder; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $di): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <?php $r = $referrers[$di] ?? null; ?>
                <div class="pw-podium__item <?php echo e($podiumClass[$idx]); ?>">
                    <div class="pw-podium__avatar" aria-hidden="true">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($podiumRank[$idx] === 1): ?>
                        <svg viewBox="0 0 24 14" fill="currentColor" width="28" style="color:#ffd700;display:block;margin:0 auto .3rem;filter:drop-shadow(0 2px 6px rgba(255,215,0,.4));">
                            <path d="M2 12L5 3l5 5 2-6 2 6 5-5 3 9H2z"/>
                        </svg>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <div class="pw-podium__avatar-ring" style="border-color:<?php echo e($rankColors[$idx]); ?>;<?php echo e($podiumRank[$idx] === 1 ? 'width:160px;height:160px;border-width:4px;' : 'width:130px;height:130px;'); ?>">
                            <svg viewBox="0 0 40 40" fill="none" width="36" aria-hidden="true" style="position:relative;z-index:1;">
                                <circle cx="20" cy="20" r="19" stroke="<?php echo e($rankColors[$idx]); ?>" stroke-width="1" opacity=".3"/>
                                <circle cx="20" cy="15" r="7" stroke="<?php echo e($rankColors[$idx]); ?>" stroke-width="1.5" opacity=".8"/>
                                <path d="M6 36c0-7.7 6.3-14 14-14s14 6.3 14 14" stroke="<?php echo e($rankColors[$idx]); ?>" stroke-width="1.5" opacity=".8" stroke-linecap="round"/>
                            </svg>
                        </div>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($r): ?>
                    <div class="pw-podium__name"><?php echo e($r->truename ?: $r->name); ?></div>
                    <div class="pw-podium__sub" style="color:var(--pw-text-muted);"><?php echo e($r->referral_code); ?></div>
                    <div class="pw-podium__level" style="color:<?php echo e($rankColors[$idx]); ?>"><?php echo e($r->referral_count); ?> Referral</div>
                    <?php else: ?>
                    <div class="pw-podium__name" style="color:var(--pw-text-muted);font-style:italic;">— Kosong —</div>
                    <div class="pw-podium__sub" style="opacity:.4;">Belum ada data</div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <div class="pw-podium__step-block">
                        <span class="pw-rank <?php echo e($rankClass[$idx]); ?>">#<?php echo e($podiumRank[$idx]); ?></span>
                    </div>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
            <?php else: ?>
            
            <div style="display:flex;justify-content:center;gap:2rem;flex-wrap:wrap;padding:1rem 0;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $referrers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div style="text-align:center;">
                    <div style="width:60px;height:60px;border-radius:50%;background:linear-gradient(135deg,<?php echo e(['#fbbf24','#94a3b8','#cd7f32'][$index]); ?>,<?php echo e(['#f59e0b','#cbd5e1','#daa06d'][$index]); ?>);display:flex;align-items:center;justify-content:center;font-size:1.4rem;font-weight:800;color:#1e293b;margin:0 auto .5rem;">
                        <?php echo e(strtoupper(substr($r->truename ?: $r->name, 0, 1))); ?>

                    </div>
                    <div style="font-weight:700;color:var(--pw-text-light);"><?php echo e($r->truename ?: $r->name); ?></div>
                    <div style="font-size:.85rem;color:#c8972a;font-weight:700;"><?php echo e($r->referral_count); ?> referral</div>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div style="text-align:center;margin-top:1.2rem;">
                <a href="<?php echo e(route('referral.ranking')); ?>" class="pw-btn pw-btn--gold pw-btn--sm">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:-2px;margin-right:.2rem;">
                        <path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/>
                    </svg>
                    Lihat Ranking Lengkap
                </a>
            </div>
        </div>

        
        <div class="pw-card" style="margin-bottom:2rem;padding:0;" x-data="{ open: false }">
            <button type="button" @click="open = !open" class="pw-event-tnc-btn"
                    style="width:100%;display:flex;align-items:center;justify-content:space-between;gap:.5rem;padding:1.2rem 1.5rem;background:none;border:none;cursor:pointer;text-align:left;">
                <div style="display:flex;align-items:center;gap:.5rem;">
                    <svg viewBox="0 0 20 20" fill="none" width="20" style="flex-shrink:0;"><path d="M4 3h12a1 1 0 011 1v12a1 1 0 01-1 1H4a1 1 0 01-1-1V4a1 1 0 011-1z" stroke="#c8972a" stroke-width="1.3"/><path d="M7 7h6M7 10h6M7 13h4" stroke="#c8972a" stroke-width="1.3" stroke-linecap="round"/></svg>
                    <span style="font-size:1.05rem;font-weight:700;color:var(--pw-text-light);">Cara Mendapatkan Hadiah Referral</span>
                </div>
                <svg viewBox="0 0 16 16" fill="none" width="14" style="flex-shrink:0;transition:transform .2s;" :style="open ? 'transform:rotate(180deg)' : ''"><path d="M4 6l4 4 4-4" stroke="var(--pw-gold-light)" stroke-width="1.5" stroke-linecap="round"/></svg>
            </button>

            <div x-show="open" x-collapse x-cloak style="padding:0 1.5rem 1.5rem;">
                <div style="font-size:.88rem;color:var(--pw-text-light);line-height:1.8;text-align:left;">

                    
                    <div style="margin-bottom:1.2rem;">
                        <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.3rem;">
                            <span style="flex-shrink:0;width:28px;height:28px;border-radius:50%;background:rgba(200,151,42,.15);border:1px solid rgba(200,151,42,.3);display:flex;align-items:center;justify-content:center;font-size:.78rem;font-weight:800;color:#c8972a;">1</span>
                            <strong style="color:var(--pw-gold-light);">Langkah 1</strong>
                        </div>
                        <div style="padding-left:2.5rem;">
                            Daftar Akun kamu di Website Resmi <strong style="color:#fbbf24;"><?php echo e($__siteName); ?></strong> pada saat masa Event berlangsung.
                        </div>
                    </div>

                    
                    <div style="margin-bottom:1.2rem;">
                        <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.3rem;">
                            <span style="flex-shrink:0;width:28px;height:28px;border-radius:50%;background:rgba(200,151,42,.15);border:1px solid rgba(200,151,42,.3);display:flex;align-items:center;justify-content:center;font-size:.78rem;font-weight:800;color:#c8972a;">2</span>
                            <strong style="color:var(--pw-gold-light);">Langkah 2</strong>
                        </div>
                        <div style="padding-left:2.5rem;">
                            Salin kode referral kamu yang dapat kamu temukan di <a href="<?php echo e(route('profile')); ?>" style="color:#fbbf24;text-decoration:underline;">halaman profil</a>.
                        </div>
                    </div>

                    
                    <div style="margin-bottom:1.2rem;">
                        <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.3rem;">
                            <span style="flex-shrink:0;width:28px;height:28px;border-radius:50%;background:rgba(200,151,42,.15);border:1px solid rgba(200,151,42,.3);display:flex;align-items:center;justify-content:center;font-size:.78rem;font-weight:800;color:#c8972a;">3</span>
                            <strong style="color:var(--pw-gold-light);">Langkah 3</strong>
                        </div>
                        <div style="padding-left:2.5rem;">
                            Bagikan link referral kamu ke teman-teman dan genk PW kamu.
                        </div>
                    </div>

                    
                    <div style="margin-bottom:1.2rem;">
                        <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.3rem;">
                            <span style="flex-shrink:0;width:28px;height:28px;border-radius:50%;background:rgba(200,151,42,.15);border:1px solid rgba(200,151,42,.3);display:flex;align-items:center;justify-content:center;font-size:.78rem;font-weight:800;color:#c8972a;">4</span>
                            <strong style="color:var(--pw-gold-light);">Langkah 4</strong>
                        </div>
                        <div style="padding-left:2.5rem;">
                            Teman kamu mendaftar menggunakan link referral kamu dan membuat karakter di ID yang terdaftar pada saat CBT, lalu mencapai <strong style="color:#fbbf24;">Level <?php echo e($reqLevel); ?></strong>. Maka secara otomatis akan valid ke dalam sistem.
                        </div>
                    </div>

                    
                    <div style="margin-bottom:1.2rem;">
                        <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.3rem;">
                            <span style="flex-shrink:0;width:28px;height:28px;border-radius:50%;background:rgba(200,151,42,.15);border:1px solid rgba(200,151,42,.3);display:flex;align-items:center;justify-content:center;font-size:.78rem;font-weight:800;color:#c8972a;">5</span>
                            <strong style="color:var(--pw-gold-light);">Langkah 5</strong>
                        </div>
                        <div style="padding-left:2.5rem;">
                            Setelah Event selesai dan Admin mendistribusikan Reward, maka <strong style="color:#fbbf24;">Cubi Gold</strong> otomatis dikirim ke akun kamu saat event berakhir.
                        </div>
                    </div>

                    
                    <div style="margin-bottom:1.2rem;">
                        <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.3rem;">
                            <span style="flex-shrink:0;width:28px;height:28px;border-radius:50%;background:rgba(200,151,42,.15);border:1px solid rgba(200,151,42,.3);display:flex;align-items:center;justify-content:center;font-size:.78rem;font-weight:800;color:#c8972a;">6</span>
                            <strong style="color:var(--pw-gold-light);">Langkah 6</strong>
                        </div>
                        <div style="padding-left:2.5rem;">
                            Jika ada hadiah selain Cubi Gold, akan dikirimkan secara bertahap oleh Admin.
                        </div>
                    </div>

                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($adminNames)): ?>
                    <div style="text-align:center;margin:1.2rem 0 0;padding:1rem 1rem .8rem;background:rgba(200,151,42,.06);border:1px solid rgba(200,151,42,.12);border-radius:8px;">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($__heroLogo): ?>
                        <img src="<?php echo e(asset('storage/' . $__heroLogo)); ?>" alt="<?php echo e($__siteName); ?>" style="max-height:70px;width:auto;display:block;margin:0 auto .6rem;">
                        <?php else: ?>
                        <div style="font-family:'Cinzel',serif;font-size:1.3rem;font-weight:900;background:linear-gradient(135deg,#fbbf24,#c8972a);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;margin-bottom:.6rem;"><?php echo e($__siteName); ?></div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <div style="font-size:.72rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.1em;margin-bottom:.2rem;">Administrator</div>
                        <div style="font-weight:700;color:var(--pw-gold-light);"><?php echo e($adminNames); ?></div>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    
                    <div style="width:100%;height:1px;background:linear-gradient(90deg,transparent,rgba(200,151,42,.2),transparent);margin:1.2rem 0;"></div>

                    
                    <div style="background:rgba(245,158,11,.06);border:1px solid rgba(245,158,11,.15);border-radius:8px;padding:1rem;">
                        <div style="display:flex;align-items:flex-start;gap:.5rem;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2" style="flex-shrink:0;margin-top:2px;">
                                <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                            </svg>
                            <div>
                                <strong style="color:#f59e0b;font-size:.85rem;">Notes:</strong>
                                <div style="font-size:.82rem;color:var(--pw-text-muted);margin-top:.2rem;line-height:1.7;">
                                    Sistem akan mendeteksi secara otomatis. Jika ada syarat &amp; ketentuan yang tidak terpenuhi, maka referral tidak akan terhitung ke dalam sistem.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div style="text-align:center;padding:1rem 0 2rem;">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
            <a href="<?php echo e(route('profile')); ?>" class="pw-btn pw-btn--gold">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:-2px;margin-right:.3rem;">
                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/>
                </svg>
                Lihat Kode Referral Saya
            </a>
            <?php else: ?>
            <a href="<?php echo e(route('register')); ?>" class="pw-btn pw-btn--gold">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:-2px;margin-right:.3rem;">
                    <path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4-4v2"/><circle cx="8.5" cy="7" r="4"/>
                    <line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/>
                </svg>
                Daftar Sekarang
            </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/pw-panel/resources/views/website/event-prelaunch.blade.php ENDPATH**/ ?>