<?php
    $__siteName = \App\Models\Setting::get('seo_title') ?: config('pw-config.server.name') ?: 'Perfect World';
?>

<?php $__env->startSection('title', __('main.profile_title') . ' — ' . $__siteName); ?>

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
        <h1 class="pw-page-hero__title"><?php echo e(__('main.profile_title')); ?></h1>
        <p class="pw-page-hero__sub"><?php echo e(__('main.profile_subtitle')); ?></p>
        <nav class="pw-breadcrumb" aria-label="breadcrumb">
            <a href="<?php echo e(route('home')); ?>" class="pw-breadcrumb__item">
                <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true">
                    <path d="M2 7.5L8 2l6 5.5V14a1 1 0 01-1 1h-3.5v-3.5h-3V15H3a1 1 0 01-1-1V7.5z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/>
                </svg>
                <?php echo e(__('main.profile_breadcrumb_home')); ?>

            </a>
            <span class="pw-breadcrumb__sep" aria-hidden="true">/</span>
            <span class="pw-breadcrumb__item pw-breadcrumb__item--active"><?php echo e(__('main.profile_breadcrumb')); ?></span>
        </nav>
    </div>
</div>


<section class="pw-section">
    <div class="pw-section__inner pw-section__inner--narrow">

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
        <div class="pw-alert pw-alert--success" role="alert">
            <svg viewBox="0 0 16 16" fill="none" width="16" aria-hidden="true"><circle cx="8" cy="8" r="7" stroke="currentColor" stroke-width="1.3"/><path d="M5 8l2 2 4-4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <?php echo e(session('success')); ?>

        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <div class="pw-profile-card" style="margin-bottom:1.2rem;">
            <div style="display:flex;align-items:center;gap:1.2rem;flex-wrap:wrap;">
                <div style="display:flex;align-items:center;gap:.5rem;flex-shrink:0;">
                    <img src="<?php echo e(asset('images/gif_icon/web_coin.gif')); ?>" alt="points" style="width:22px;height:22px;">
                    <span style="font-family:'Cinzel',serif;font-weight:700;font-size:.95rem;color:var(--pw-text-light);text-transform:uppercase;letter-spacing:.03em;"><?php echo e(__('main.profile_balance')); ?></span>
                </div>
                <div style="display:flex;align-items:center;gap:1.5rem;flex:1;flex-wrap:wrap;">
                    <div style="display:flex;align-items:center;gap:.5rem;">
                        <span style="font-size:.75rem;color:var(--pw-text-muted);white-space:nowrap;"><?php echo e(__('main.profile_gold_points')); ?></span>
                        <span style="font-family:'Cinzel',serif;font-weight:700;font-size:1.05rem;color:var(--pw-gold);"><?php echo e(number_format($user->money)); ?></span>
                        <img src="<?php echo e(asset('images/gif_icon/web_coin.gif')); ?>" alt="points" style="width:18px;height:18px;">
                    </div>
                    <div style="width:1px;height:20px;background:rgba(255,255,255,.1);flex-shrink:0;"></div>
                    <div style="display:flex;align-items:center;gap:.5rem;">
                        <span style="font-size:.75rem;color:var(--pw-text-muted);white-space:nowrap;"><?php echo e(__('main.profile_coin_game')); ?></span>
                        <span style="font-family:'Cinzel',serif;font-weight:700;font-size:1.05rem;color:#60d0ff;"><?php echo e(number_format($cubiCoins)); ?></span>
                        <img src="<?php echo e(asset('images/gif_icon/gold-icon.gif')); ?>" alt="coin" style="width:18px;height:18px;">
                    </div>
                </div>
                <a href="<?php echo e(route('cubi-shop')); ?>" class="pw-btn pw-btn--gold pw-btn--sm" style="flex-shrink:0;">
                    <?php echo e(__('main.profile_topup')); ?>

                </a>
            </div>
        </div>

        <div class="pw-profile-layout">

            
            <div class="pw-profile-account">

                
                <div class="pw-profile-card">
                    <div class="pw-profile-card__header">
                        <svg viewBox="0 0 20 20" fill="none" width="16" aria-hidden="true"><circle cx="10" cy="7" r="4" stroke="#c8972a" stroke-width="1.5"/><path d="M3 17c0-3.314 3.134-6 7-6s7 2.686 7 6" stroke="#c8972a" stroke-width="1.5" stroke-linecap="round"/></svg>
                        <?php echo e(__('main.profile_account_info')); ?>

                    </div>

                    <div class="pw-profile-user">
                        <div class="pw-profile-user__avatar">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->profile_photo_path): ?>
                                <img src="<?php echo e(Storage::url($user->profile_photo_path)); ?>" alt="<?php echo e($user->name); ?>">
                            <?php else: ?>
                                <span><?php echo e(strtoupper(substr($user->name, 0, 1))); ?></span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div class="pw-profile-user__info">
                            <div class="pw-profile-user__name"><?php echo e($user->name); ?></div>
                            <div class="pw-profile-user__meta">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->role === 'admin'): ?>
                                    <span class="pw-profile-badge pw-profile-badge--admin-front">Admin</span>
                                <?php elseif($user->role === 'gm'): ?>
                                    <span class="pw-profile-badge pw-profile-badge--gm-front">GM</span>
                                <?php else: ?>
                                    <span class="pw-profile-badge">Player</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <span>ID: <?php echo e($user->ID); ?></span>
                            </div>
                        </div>
                    </div>

                    <form action="<?php echo e(route('profile.update')); ?>" method="POST">
                        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

                        <label class="pw-profile-label"><?php echo e(__('main.profile_username')); ?></label>
                        <input type="text" class="pw-profile-input pw-profile-input--disabled" value="<?php echo e($user->name); ?>" disabled>
                        <p class="pw-profile-hint"><?php echo e(__('main.profile_username_hint')); ?></p>

                        <label class="pw-profile-label"><?php echo e(__('main.profile_email')); ?></label>
                        <input type="email" name="email" class="pw-profile-input" value="<?php echo e(old('email', $user->email)); ?>" required>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="pw-profile-error"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <label class="pw-profile-label"><?php echo e(__('main.profile_phone')); ?></label>
                        <input type="text" name="mobilenumber" class="pw-profile-input" value="<?php echo e(old('mobilenumber', $user->mobilenumber)); ?>" placeholder="+62 8xx xxxx xxxx">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['mobilenumber'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="pw-profile-error"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <button type="submit" class="pw-btn pw-btn--gold pw-btn--sm" style="margin-top:.5rem;">
                            <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true"><path d="M13.5 4.5l-8 8L2 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <?php echo e(__('main.profile_save')); ?>

                        </button>
                    </form>
                </div>

                
                <div class="pw-profile-card">
                    <div class="pw-profile-card__header">
                        <svg viewBox="0 0 16 16" fill="none" width="14" aria-hidden="true"><rect x="3" y="7" width="10" height="7" rx="1.5" stroke="#c8972a" stroke-width="1.3"/><path d="M5 7V5a3 3 0 016 0v2" stroke="#c8972a" stroke-width="1.3" stroke-linecap="round"/></svg>
                        <?php echo e(__('main.profile_security')); ?>

                    </div>
                    <p class="pw-profile-hint" style="margin-bottom:.8rem;"><?php echo e(__('main.profile_security_hint')); ?></p>
                    <button type="button" class="pw-btn pw-btn--ghost pw-btn--sm" onclick="document.getElementById('pwModalPassword').style.display='flex'">
                        <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true"><rect x="3" y="7" width="10" height="7" rx="1.5" stroke="currentColor" stroke-width="1.3"/><path d="M5 7V5a3 3 0 016 0v2" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                        <?php echo e(__('main.profile_change_password')); ?>

                    </button>
                </div>
            </div>

            
            <div class="pw-profile-characters" x-data="{ selected: null }">
                <div class="pw-profile-card">
                    <div class="pw-profile-card__header">
                        <svg viewBox="0 0 20 20" fill="none" width="15" aria-hidden="true"><path d="M10 2l2.4 5 5.6.8-4 3.9.9 5.5L10 14.5l-4.9 2.7.9-5.5L2 7.8l5.6-.8L10 2z" stroke="#c8972a" stroke-width="1.3" stroke-linejoin="round"/></svg>
                        <?php echo e(__('main.profile_characters')); ?>

                        <span class="pw-profile-card__count"><?php echo e($characters->count()); ?></span>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($characters->isEmpty()): ?>
                    <div class="pw-profile-empty">
                        <svg viewBox="0 0 48 48" fill="none" width="40" aria-hidden="true"><circle cx="24" cy="24" r="22" stroke="currentColor" stroke-width="1.5" opacity=".3"/><path d="M24 16v8M24 28v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" opacity=".4"/></svg>
                        <p><?php echo e(__('main.profile_no_characters')); ?></p>
                    </div>
                    <?php else: ?>
                    <div class="pw-char-list">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $characters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $char): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <button class="pw-char-row" :class="{ 'is-active': selected === <?php echo e($char->role_id); ?> }" @click="selected = selected === <?php echo e($char->role_id); ?> ? null : <?php echo e($char->role_id); ?>" type="button">
                            <div class="pw-char-row__left">
                                <div class="pw-char-row__avatar">
                                    <img src="/images/class/<?php echo e($char->class_icon); ?>" alt="<?php echo e($char->class); ?>" width="28" height="28">
                                </div>
                                <div class="pw-char-row__info">
                                    <div class="pw-char-row__name"><?php echo e($char->name); ?></div>
                                    <div class="pw-char-row__sub">Lv.<?php echo e($char->level); ?> <?php echo e($char->class); ?></div>
                                </div>
                            </div>
                            <svg class="pw-char-row__arrow" :class="{ 'is-open': selected === <?php echo e($char->role_id); ?> }" viewBox="0 0 16 16" fill="none" width="14"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                        </button>

                        
                        <div class="pw-char-detail" x-show="selected === <?php echo e($char->role_id); ?>" x-transition.origin.top x-cloak>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($char->has_extended): ?>
                            
                            <?php
                                $radarStats = [
                                    ['name' => 'STR', 'val' => $char->strength ?? 0],
                                    ['name' => 'AGI', 'val' => $char->agility ?? 0],
                                    ['name' => 'INT', 'val' => $char->energy ?? 0],
                                    ['name' => 'CON', 'val' => $char->vitality ?? 0],
                                    ['name' => 'P-Atk', 'val' => intval((($char->p_atk_min ?? 0) + ($char->p_atk_max ?? 0)) / 2)],
                                    ['name' => 'P-Def', 'val' => $char->p_def ?? 0],
                                ];
                                $maxVal = max(array_column($radarStats, 'val')) ?: 1;
                                $cx = 100; $cy = 100; $r = 70; $n = 6;

                                $pointAt = function($i, $scale) use ($cx, $cy, $r, $n) {
                                    $angle = (2 * M_PI * $i / $n) - M_PI / 2;
                                    return [
                                        round($cx + cos($angle) * $r * $scale, 2),
                                        round($cy + sin($angle) * $r * $scale, 2),
                                    ];
                                };

                                $ringPoints = function($scale) use ($n, $pointAt) {
                                    $pts = [];
                                    for ($i = 0; $i < $n; $i++) {
                                        [$x, $y] = $pointAt($i, $scale);
                                        $pts[] = "$x,$y";
                                    }
                                    return implode(' ', $pts);
                                };

                                $dataPoints = [];
                                foreach ($radarStats as $i => $s) {
                                    $v = max($s['val'] / $maxVal, 0.05);
                                    [$x, $y] = $pointAt($i, $v);
                                    $dataPoints[] = "$x,$y";
                                }
                                $dataPoly = implode(' ', $dataPoints);
                            ?>
                            <div class="pw-radar">
                                <svg viewBox="0 0 200 200" class="pw-radar__svg">
                                    
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [0.2, 0.4, 0.6, 0.8, 1.0]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ring): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <polygon points="<?php echo e($ringPoints($ring)); ?>" class="pw-radar__ring"/>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php for($i = 0; $i < $n; $i++): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <?php [$ax, $ay] = $pointAt($i, 1); ?>
                                        <line x1="<?php echo e($cx); ?>" y1="<?php echo e($cy); ?>" x2="<?php echo e($ax); ?>" y2="<?php echo e($ay); ?>" class="pw-radar__axis"/>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    
                                    <polygon points="<?php echo e($dataPoly); ?>" class="pw-radar__data"/>
                                    <polygon points="<?php echo e($dataPoly); ?>" class="pw-radar__data-stroke"/>
                                    
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $dataPoints; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <?php [$dx, $dy] = explode(',', $dp); ?>
                                        <circle cx="<?php echo e($dx); ?>" cy="<?php echo e($dy); ?>" r="2.5" class="pw-radar__dot"/>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $radarStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <?php [$lx, $ly] = $pointAt($i, 1.25); ?>
                                        <text x="<?php echo e($lx); ?>" y="<?php echo e($ly - 4); ?>" text-anchor="middle" dominant-baseline="middle" class="pw-radar__label"><?php echo e($s['name']); ?></text>
                                        <text x="<?php echo e($lx); ?>" y="<?php echo e($ly + 7); ?>" text-anchor="middle" dominant-baseline="middle" class="pw-radar__label-val"><?php echo e(number_format($s['val'])); ?></text>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </svg>
                            </div>
                            <div class="pw-char-detail__divider"></div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <div class="pw-char-detail__grid">
                                <div class="pw-char-detail__item">
                                    <span class="pw-char-detail__label"><?php echo e(__('main.char_id')); ?></span>
                                    <span class="pw-char-detail__val"><?php echo e($char->role_id); ?></span>
                                </div>
                                <div class="pw-char-detail__item">
                                    <span class="pw-char-detail__label"><?php echo e(__('main.char_level')); ?></span>
                                    <span class="pw-char-detail__val pw-char-detail__val--gold"><?php echo e($char->level); ?></span>
                                </div>
                                <div class="pw-char-detail__item">
                                    <span class="pw-char-detail__label"><?php echo e(__('main.char_class')); ?></span>
                                    <span class="pw-char-detail__val pw-char-detail__val--class">
                                        <img src="/images/class/<?php echo e($char->class_icon); ?>" alt="" width="16" height="16">
                                        <?php echo e($char->class); ?>

                                    </span>
                                </div>
                                <div class="pw-char-detail__item">
                                    <span class="pw-char-detail__label"><?php echo e(__('main.char_race')); ?></span>
                                    <span class="pw-char-detail__val"><?php echo e($char->race); ?></span>
                                </div>
                                <div class="pw-char-detail__item">
                                    <span class="pw-char-detail__label"><?php echo e(__('main.char_gender')); ?></span>
                                    <span class="pw-char-detail__val"><?php echo e($char->gender); ?></span>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($char->cultivation): ?>
                                <div class="pw-char-detail__item">
                                    <span class="pw-char-detail__label"><?php echo e(__('main.char_cultivation')); ?></span>
                                    <span class="pw-char-detail__val"><?php echo e($char->cultivation); ?></span>
                                </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <div class="pw-char-detail__item">
                                    <span class="pw-char-detail__label"><?php echo e(__('main.char_guild')); ?></span>
                                    <span class="pw-char-detail__val"><?php echo e($char->faction_name ?? '—'); ?></span>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($char->faction_name): ?>
                                <div class="pw-char-detail__item">
                                    <span class="pw-char-detail__label"><?php echo e(__('main.char_guild_level')); ?></span>
                                    <span class="pw-char-detail__val"><?php echo e($char->faction_level); ?></span>
                                </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($char->spouse): ?>
                                <div class="pw-char-detail__item">
                                    <span class="pw-char-detail__label"><?php echo e(__('main.char_spouse')); ?></span>
                                    <span class="pw-char-detail__val"><?php echo e($char->spouse); ?></span>
                                </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($char->has_extended): ?>
                            
                            <div class="pw-char-detail__divider"></div>
                            <div class="pw-char-detail__title">
                                <svg viewBox="0 0 16 16" fill="none" width="12" aria-hidden="true"><circle cx="8" cy="8" r="6" stroke="currentColor" stroke-width="1.2"/><text x="8" y="11" text-anchor="middle" font-size="7" font-weight="700" fill="currentColor">$</text></svg>
                                <?php echo e(__('main.char_coins')); ?>

                            </div>
                            <div class="pw-char-detail__grid">
                                <div class="pw-char-detail__item">
                                    <span class="pw-char-detail__label"><?php echo e(__('main.char_pocket')); ?></span>
                                    <span class="pw-char-detail__val pw-char-detail__val--gold"><?php echo e(number_format($char->pocket_coins ?? 0)); ?></span>
                                </div>
                                <div class="pw-char-detail__item">
                                    <span class="pw-char-detail__label"><?php echo e(__('main.char_storehouse')); ?></span>
                                    <span class="pw-char-detail__val pw-char-detail__val--gold"><?php echo e(number_format($char->store_coins ?? 0)); ?></span>
                                </div>
                                <div class="pw-char-detail__item">
                                    <span class="pw-char-detail__label"><?php echo e(__('main.char_reputation')); ?></span>
                                    <span class="pw-char-detail__val"><?php echo e(number_format($char->reputation ?? 0)); ?></span>
                                </div>
                                <div class="pw-char-detail__item">
                                    <span class="pw-char-detail__label"><?php echo e(__('main.char_spirit')); ?></span>
                                    <span class="pw-char-detail__val"><?php echo e(number_format($char->sp ?? 0)); ?></span>
                                </div>
                            </div>

                            
                            <div class="pw-char-detail__divider"></div>
                            <div class="pw-char-detail__title">
                                <svg viewBox="0 0 16 16" fill="none" width="12" aria-hidden="true"><path d="M8 2v12M2 8h12" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                                <?php echo e(__('main.char_stats')); ?>

                            </div>
                            <div class="pw-char-detail__grid">
                                <div class="pw-char-detail__item">
                                    <span class="pw-char-detail__label">HP (Max)</span>
                                    <span class="pw-char-detail__val pw-char-detail__val--hp"><?php echo e(number_format($char->hp ?? 0)); ?></span>
                                </div>
                                <div class="pw-char-detail__item">
                                    <span class="pw-char-detail__label">MP (Max)</span>
                                    <span class="pw-char-detail__val pw-char-detail__val--mp"><?php echo e(number_format($char->mp ?? 0)); ?></span>
                                </div>
                                <div class="pw-char-detail__item">
                                    <span class="pw-char-detail__label">P-Atk</span>
                                    <span class="pw-char-detail__val"><?php echo e($char->p_atk_min ?? 0); ?> – <?php echo e($char->p_atk_max ?? 0); ?></span>
                                </div>
                                <div class="pw-char-detail__item">
                                    <span class="pw-char-detail__label">M-Atk</span>
                                    <span class="pw-char-detail__val"><?php echo e($char->m_atk_min ?? 0); ?> – <?php echo e($char->m_atk_max ?? 0); ?></span>
                                </div>
                                <div class="pw-char-detail__item">
                                    <span class="pw-char-detail__label">P-Def</span>
                                    <span class="pw-char-detail__val"><?php echo e(number_format($char->p_def ?? 0)); ?></span>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($char->vigor): ?>
                                <div class="pw-char-detail__item">
                                    <span class="pw-char-detail__label">Vigor</span>
                                    <span class="pw-char-detail__val"><?php echo e($char->vigor); ?></span>
                                </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            
                            <div class="pw-char-detail__divider"></div>
                            <div class="pw-char-detail__title">
                                <svg viewBox="0 0 16 16" fill="none" width="12" aria-hidden="true"><path d="M4 12V6M8 12V4M12 12V8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                                <?php echo e(__('main.char_attributes')); ?>

                            </div>
                            <div class="pw-char-detail__attrs">
                                <div class="pw-char-detail__attr pw-char-detail__attr--str">
                                    <span class="pw-char-detail__attr-val"><?php echo e($char->strength ?? 0); ?></span>
                                    <span class="pw-char-detail__attr-label">STR</span>
                                </div>
                                <div class="pw-char-detail__attr pw-char-detail__attr--agi">
                                    <span class="pw-char-detail__attr-val"><?php echo e($char->agility ?? 0); ?></span>
                                    <span class="pw-char-detail__attr-label">AGI</span>
                                </div>
                                <div class="pw-char-detail__attr pw-char-detail__attr--con">
                                    <span class="pw-char-detail__attr-val"><?php echo e($char->vitality ?? 0); ?></span>
                                    <span class="pw-char-detail__attr-label">CON</span>
                                </div>
                                <div class="pw-char-detail__attr pw-char-detail__attr--int">
                                    <span class="pw-char-detail__attr-val"><?php echo e($char->energy ?? 0); ?></span>
                                    <span class="pw-char-detail__attr-label">INT</span>
                                </div>
                            </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <div class="pw-char-detail__divider"></div>

                            <div class="pw-char-detail__title">
                                <svg viewBox="0 0 16 16" fill="none" width="12" aria-hidden="true"><path d="M8 1l2 4.5 5 .7-3.6 3.5.9 5L8 12.3 3.7 14.7l.9-5L1 6.2l5-.7L8 1z" stroke="currentColor" stroke-width="1.2" stroke-linejoin="round"/></svg>
                                <?php echo e(__('main.char_pvp_stats')); ?>

                            </div>
                            <div class="pw-char-detail__pvp">
                                <div class="pw-char-detail__pvp-item">
                                    <span class="pw-char-detail__pvp-val pw-char-detail__pvp-val--kill"><?php echo e(number_format($char->pvp_kills)); ?></span>
                                    <span class="pw-char-detail__pvp-label"><?php echo e(__('main.char_kills')); ?></span>
                                </div>
                                <div class="pw-char-detail__pvp-sep"></div>
                                <div class="pw-char-detail__pvp-item">
                                    <span class="pw-char-detail__pvp-val pw-char-detail__pvp-val--dead"><?php echo e(number_format($char->pvp_deads)); ?></span>
                                    <span class="pw-char-detail__pvp-label"><?php echo e(__('main.char_deaths')); ?></span>
                                </div>
                                <div class="pw-char-detail__pvp-sep"></div>
                                <div class="pw-char-detail__pvp-item">
                                    <span class="pw-char-detail__pvp-val"><?php echo e($char->pvp_deads > 0 ? number_format($char->pvp_kills / $char->pvp_deads, 2) : '∞'); ?></span>
                                    <span class="pw-char-detail__pvp-label"><?php echo e(__('main.char_kd')); ?></span>
                                </div>
                            </div>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

        </div>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(config('pw-config.referral.enabled') && $referralStats): ?>
        <div class="pw-profile-card" style="margin-top:1.25rem;">
            <div class="pw-profile-card__header">
                <svg viewBox="0 0 16 16" fill="none" width="14" aria-hidden="true"><path d="M8 1v6M11 4H5M13 8a5 5 0 11-10 0" stroke="#c8972a" stroke-width="1.3" stroke-linecap="round"/><circle cx="4" cy="12" r="2" stroke="#c8972a" stroke-width="1.2"/><circle cx="12" cy="12" r="2" stroke="#c8972a" stroke-width="1.2"/></svg>
                <?php echo e(__('main.profile_referral')); ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($referralStats->is_partner): ?>
                <span class="pw-badge" style="background:rgba(168,85,247,.15);color:#c084fc;margin-left:.5rem;"><?php echo e($referralStats->partner->label); ?></span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($referralStats->is_partner): ?>
            <div style="background:rgba(168,85,247,.08);border:1px solid rgba(168,85,247,.2);border-radius:6px;padding:.6rem .8rem;margin-bottom:.8rem;font-size:.8rem;color:#c084fc;">
                <strong><?php echo e(__('main.profile_partner_reward')); ?>:</strong>
                <?php echo e(number_format($referralStats->partner->reward_amount)); ?>

                <?php echo e($referralStats->partner->reward_type === 'cubi' ? 'Cubi Gold' : config('pw-config.currency.name')); ?>

                <?php echo e(__('main.profile_partner_per_ref')); ?>

                &middot; <?php echo e(__('main.profile_partner_min_level')); ?> <?php echo e($referralStats->partner->min_char_level); ?>

                &middot; <?php echo e(__('main.profile_partner_max_day') === '/hari' ? 'Maks' : 'Max'); ?> <?php echo e($referralStats->partner->max_per_day); ?><?php echo e(__('main.profile_partner_max_day')); ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($referralStats->partner->max_total): ?>
                &middot; <?php echo e(__('main.profile_partner_max_total')); ?> <?php echo e(number_format($referralStats->partner->max_total)); ?>

                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div class="pw-referral-top">
                <div class="pw-referral-top__link">
                    <label class="pw-profile-label"><?php echo e(__('main.profile_referral_link')); ?></label>
                    <div x-data="{
                            copied: false,
                            toast: '',
                            doCopy() {
                                const text = document.getElementById('referralLink').value;
                                const fallback = (t) => { const ta = document.createElement('textarea'); ta.value=t; ta.style.position='fixed'; ta.style.opacity='0'; document.body.appendChild(ta); ta.focus(); ta.select(); document.execCommand('copy'); document.body.removeChild(ta); };
                                if (navigator.clipboard && window.isSecureContext) { navigator.clipboard.writeText(text).catch(() => fallback(text)); } else { fallback(text); }
                                this.copied = true; setTimeout(() => this.copied = false, 2000);
                                this.toast = '<?php echo e(__('main.profile_referral_copied')); ?>';
                                setTimeout(() => this.toast = '', 2500);
                            }
                         }"
                         style="position:relative;display:flex;flex-direction:row;align-items:center;gap:.5rem;">
                        
                        <div x-show="toast" x-transition.opacity
                             style="position:absolute;top:-2.4rem;right:0;background:#15803d;color:#fff;
                                    font-size:.72rem;font-weight:600;padding:.3rem .75rem;border-radius:6px;
                                    display:flex;align-items:center;gap:.35rem;z-index:10;pointer-events:none;white-space:nowrap;">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                            <span x-text="toast"></span>
                        </div>
                        <input type="text" class="pw-profile-input pw-profile-input--disabled" value="<?php echo e(route('register', ['ref' => $referralStats->code])); ?>" readonly id="referralLink" style="flex:1;min-width:0;">
                        <button type="button"
                            :style="copied ? 'color:#22c55e;opacity:1' : 'color:var(--pw-gold);opacity:.85'"
                            style="display:inline-flex;align-items:center;gap:.3rem;background:none;border:none;padding:.4rem .5rem;cursor:pointer;font-size:.75rem;font-weight:600;flex-shrink:0;transition:color .15s;"
                            @mouseenter="!copied && ($el.style.opacity='1')" @mouseleave="!copied && ($el.style.opacity='.85')"
                            @click="doCopy()">
                            <svg x-show="!copied" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                            <svg x-show="copied" x-cloak width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                            <span x-text="copied ? '<?php echo e(__('main.profile_referral_copied')); ?>' : '<?php echo e(__('main.profile_referral_copy')); ?>'"></span>
                        </button>
                    </div>
                    <?php
                        $referralRewardType   = $referralStats->is_partner
                            ? ($referralStats->partner->reward_type ?? 'gold')
                            : config('pw-config.referral.reward_type', 'gold');
                        $referralRewardAmount = $referralStats->is_partner
                            ? $referralStats->partner->reward_amount
                            : config('pw-config.referral.reward_gold', 0);
                        $referralRewardLabel  = $referralRewardType === 'cubi'
                            ? 'Cubi Gold'
                            : config('pw-config.currency.name', 'Gold Points');
                        $referralMinLevel     = $referralStats->is_partner
                            ? ($referralStats->partner->min_char_level ?? 1)
                            : config('pw-config.referral.min_char_level', 1);
                        $referralMinCult      = (int) config('pw-config.referral.min_cultivation', 0);
                        $cultivationNames     = [
                            1=>'Autoscopy',2=>'Transform',3=>'Naissance',4=>'Reborn',
                            5=>'Vigilance',6=>'Doom',7=>'Disengage',8=>'Nirvana',
                            20=>'Prime Immortal / Daimon Baresark',
                            21=>'Pure Immortal / Daimon Saint',
                            22=>'Ether Immortal / Daimon Elder',
                        ];
                        // Normalize legacy values 30/31/32 → 20/21/22
                        if (in_array($referralMinCult, [30,31,32])) {
                            $referralMinCult -= 10;
                        }
                        $referralMinCultName  = $cultivationNames[$referralMinCult] ?? null;
                        // Penerima reward (referred user)
                        $referredRType  = config('pw-config.referral.referred_reward_type', 'none');
                        $referredRAmount = (int) config('pw-config.referral.referred_reward_amount', 0);
                        $referredRLabel  = $referredRType === 'cubi' ? 'Cubi Gold' : config('pw-config.currency.name', 'Gold Points');
                        $hasReferredReward = $referredRType !== 'none' && $referredRAmount > 0;
                    ?>
                    <p class="pw-profile-hint" style="margin-top:.5rem;color:var(--pw-text-light);">
                        <?php echo __('main.profile_referral_earn', ['amount' => number_format($referralRewardAmount), 'label' => $referralRewardLabel, 'level' => $referralMinLevel]); ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! $referralStats->is_partner && $referralMinCultName): ?><?php echo __('main.profile_referral_cult', ['cult' => $referralMinCultName]); ?><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>.
                    </p>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! $referralStats->is_partner && $hasReferredReward): ?>
                    <p class="pw-profile-hint" style="margin-top:.3rem;color:var(--pw-text-muted);">
                        <?php echo __('main.profile_referral_bonus', ['amount' => number_format($referredRAmount), 'label' => $referredRLabel]); ?>

                    </p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="pw-referral-stats">
                    <div class="pw-referral-stat">
                        <span class="pw-referral-stat__value"><?php echo e($referralStats->total); ?></span>
                        <span class="pw-referral-stat__label"><?php echo e(__('main.profile_referral_total')); ?></span>
                    </div>
                    <div class="pw-referral-stat">
                        <span class="pw-referral-stat__value pw-referral-stat__value--success"><?php echo e($referralStats->rewarded); ?></span>
                        <span class="pw-referral-stat__label"><?php echo e(__('main.profile_referral_rewarded')); ?></span>
                    </div>
                    <div class="pw-referral-stat">
                        <span class="pw-referral-stat__value pw-referral-stat__value--pending"><?php echo e($referralStats->pending); ?></span>
                        <span class="pw-referral-stat__label"><?php echo e(__('main.profile_referral_pending')); ?></span>
                    </div>
                </div>
                </div>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($referralRewardType === 'cubi'): ?>
            <div style="margin-top:.9rem;display:flex;gap:.7rem;align-items:flex-start;padding:.85rem 1rem;background:rgba(234,179,8,.07);border:1px solid rgba(234,179,8,.25);border-radius:.6rem;">
                <svg viewBox="0 0 20 20" fill="none" width="18" style="flex-shrink:0;margin-top:.05rem;color:#eab308;"><circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.5"/><path d="M10 6.5v4M10 12.5v.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
                <div style="font-size:.82rem;line-height:1.55;color:var(--pw-text-muted);">
                    <strong style="color:#eab308;display:block;margin-bottom:.2rem;"><?php echo e(__('main.profile_referral_cubi_note_title')); ?></strong>
                    <?php echo __('main.profile_referral_cubi_note'); ?>

                </div>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($referralStats->list->isNotEmpty()): ?>
            <div x-data="{ open: false }" style="margin-top:.9rem;">
                <button type="button" @click="open = !open"
                    style="width:100%;display:flex;align-items:center;justify-content:space-between;padding:.7rem 1rem;background:var(--pw-card-bg,rgba(255,255,255,.04));border:1px solid var(--pw-border,rgba(255,255,255,.08));border-radius:.6rem;cursor:pointer;font-size:.85rem;color:var(--pw-text);">
                    <span style="display:flex;align-items:center;gap:.5rem;">
                        <svg viewBox="0 0 16 16" fill="none" width="14" aria-hidden="true"><circle cx="8" cy="5" r="3" stroke="currentColor" stroke-width="1.2"/><path d="M2 14c0-2.8 2.7-5 6-5s6 2.2 6 5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/></svg>
                        <?php echo e(__('main.profile_referral_list')); ?>

                        <span style="font-size:.75rem;padding:.1rem .45rem;border-radius:999px;background:rgba(239,68,68,.15);color:#f87171;font-weight:600;"><?php echo e($referralStats->list->count()); ?></span>
                    </span>
                    <svg viewBox="0 0 16 16" fill="none" width="14" aria-hidden="true"
                        :style="open ? 'transform:rotate(180deg);transition:.2s' : 'transition:.2s'">
                        <path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>

                <div x-show="open" x-cloak x-transition style="margin-top:.5rem;overflow:hidden;border:1px solid var(--pw-border,rgba(255,255,255,.08));border-radius:.6rem;">
                    <table style="width:100%;border-collapse:collapse;font-size:.82rem;">
                        <thead>
                            <tr style="background:rgba(255,255,255,.04);border-bottom:1px solid var(--pw-border,rgba(255,255,255,.08));">
                                <th style="padding:.55rem .9rem;text-align:left;font-weight:600;color:var(--pw-text-muted);white-space:nowrap;">#</th>
                                <th style="padding:.55rem .9rem;text-align:left;font-weight:600;color:var(--pw-text-muted);"><?php echo e(__('main.profile_referral_col_name')); ?></th>
                                <th style="padding:.55rem .9rem;text-align:left;font-weight:600;color:var(--pw-text-muted);white-space:nowrap;"><?php echo e(__('main.profile_referral_col_joined')); ?></th>
                                <th style="padding:.55rem .9rem;text-align:center;font-weight:600;color:var(--pw-text-muted);white-space:nowrap;"><?php echo e(__('main.profile_referral_col_level')); ?></th>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($referralStats->req_cult > 0): ?>
                                <th style="padding:.55rem .9rem;text-align:center;font-weight:600;color:var(--pw-text-muted);white-space:nowrap;"><?php echo e(__('main.profile_referral_col_cult')); ?></th>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <th style="padding:.55rem .9rem;text-align:center;font-weight:600;color:var(--pw-text-muted);"><?php echo e(__('main.profile_referral_col_status')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $referralStats->list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $ref): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <tr style="<?php echo e(!$loop->last ? 'border-bottom:1px solid var(--pw-border,rgba(255,255,255,.06));' : ''); ?>">
                                <td style="padding:.55rem .9rem;color:var(--pw-text-muted);"><?php echo e($i + 1); ?></td>
                                <td style="padding:.55rem .9rem;color:var(--pw-text);"><?php echo e($ref->name); ?></td>
                                <td style="padding:.55rem .9rem;color:var(--pw-text-muted);white-space:nowrap;">
                                    <?php echo e($ref->joined ? \Carbon\Carbon::parse($ref->joined)->format('d M Y') : '—'); ?>

                                </td>
                                
                                <td style="padding:.55rem .9rem;text-align:center;white-space:nowrap;">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ref->max_level !== null): ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ref->level_ok): ?>
                                        <span style="display:inline-flex;align-items:center;gap:.3rem;font-size:.8rem;color:#4ade80;font-weight:600;">
                                            <svg viewBox="0 0 16 16" fill="none" width="13"><path d="M3 8l4 4 6-6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            <?php echo e($ref->max_level); ?>

                                        </span>
                                        <?php else: ?>
                                        <span style="display:inline-flex;align-items:center;gap:.3rem;font-size:.8rem;color:#fbbf24;">
                                            <svg viewBox="0 0 16 16" fill="none" width="13"><circle cx="8" cy="8" r="6" stroke="currentColor" stroke-width="1.4"/><path d="M8 5v3.5M8 10.5v.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                                            <?php echo e($ref->max_level); ?> / <?php echo e($referralStats->req_level); ?>

                                        </span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php else: ?>
                                        <span style="color:var(--pw-text-muted);font-size:.78rem;"><?php echo e(__('main.profile_referral_not_yet')); ?></span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                                
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($referralStats->req_cult > 0): ?>
                                <td style="padding:.55rem .9rem;text-align:center;white-space:nowrap;">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ref->max_cult !== null && $ref->max_cult_name): ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ref->cult_ok): ?>
                                        <span style="display:inline-flex;align-items:center;gap:.3rem;font-size:.78rem;color:#4ade80;font-weight:600;">
                                            <svg viewBox="0 0 16 16" fill="none" width="12"><path d="M3 8l4 4 6-6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            <?php echo e($ref->max_cult_name); ?>

                                        </span>
                                        <?php else: ?>
                                        <span style="font-size:.78rem;color:#fbbf24;"><?php echo e($ref->max_cult_name); ?></span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php else: ?>
                                        <span style="color:var(--pw-text-muted);font-size:.78rem;">—</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                
                                <td style="padding:.55rem .9rem;text-align:center;">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ref->rewarded): ?>
                                    <span class="pw-badge pw-badge--success"><?php echo e(__('main.profile_referral_status_sent')); ?></span>
                                    <?php elseif($ref->level_ok && $ref->cult_ok): ?>
                                    <span class="pw-badge" style="background:rgba(59,130,246,.15);color:#60a5fa;"><?php echo e(__('main.profile_referral_status_met')); ?></span>
                                    <?php else: ?>
                                    <span class="pw-badge pw-badge--pending"><?php echo e(__('main.profile_referral_status_pending')); ?></span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                            </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($preLaunchEvent) && $preLaunchEvent): ?>
        <div class="pw-profile-card" style="margin-top:1.25rem;">
            <div class="pw-profile-card__header">
                <svg viewBox="0 0 16 16" fill="none" width="14" aria-hidden="true"><path d="M8 1l2.09 4.26L15 6.27l-3.5 3.41.82 4.82L8 12.27l-4.32 2.23.82-4.82L1 6.27l4.91-.71L8 1z" stroke="#c8972a" stroke-width="1.2" stroke-linejoin="round"/></svg>
                Pre-Launch Referral Milestones
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($preLaunchEvent->status === 'active'): ?>
                <span class="pw-badge pw-badge--success" style="margin-left:.5rem;">Aktif</span>
                <?php elseif($preLaunchEvent->status === 'ended'): ?>
                <span class="pw-badge pw-badge--warning" style="margin-left:.5rem;">Berakhir</span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div style="background:rgba(200,151,42,.06);border:1px solid rgba(200,151,42,.12);border-radius:8px;padding:.8rem 1rem;margin-bottom:1rem;">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.5rem;">
                    <div>
                        <div style="font-size:.85rem;color:var(--pw-text-light);font-weight:600;"><?php echo e($preLaunchEvent->title); ?></div>
                        <div style="font-size:.75rem;color:var(--pw-text-muted);">
                            Syarat: Setiap ID harus punya karakter Level <?php echo e($preLaunchEvent->referral_req_level); ?>

                        </div>
                    </div>
                    <div style="text-align:center;">
                        <div style="font-size:1.3rem;font-weight:800;color:#c8972a;"><?php echo e($preLaunchQualified); ?></div>
                        <div style="font-size:.7rem;color:var(--pw-text-muted);">Referral Qualified</div>
                    </div>
                </div>
            </div>

            
            <div style="max-width:600px;margin:0 auto;">
                <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:.6rem;text-align:center;">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $preLaunchEvent->referral_tiers ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php
                        $reached = $preLaunchQualified >= $tier['count'];
                        $distributed = $preLaunchMilestones->contains('milestone', $tier['count']);
                    ?>
                    <div style="background:rgba(0,0,0,.35);border:1px solid <?php echo e($distributed ? 'rgba(74,222,128,.25)' : ($reached ? 'rgba(251,191,36,.25)' : 'var(--pw-border)')); ?>;border-radius:6px;padding:.6rem .5rem;min-width:90px;">
                        <div style="display:block;font-size:1.3rem;font-weight:700;color:<?php echo e($distributed ? '#7deba0' : ($reached ? '#d4a860' : 'var(--pw-gold-light)')); ?>;">
                            <?php echo e($tier['count']); ?>

                        </div>
                        <div style="display:block;font-size:.7rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.04em;margin-top:.15rem;">referral</div>
                        <div style="font-size:.8rem;font-weight:600;color:var(--pw-text-light);margin-top:.25rem;">
                            <?php echo e(number_format($tier['reward'])); ?> Cubi
                        </div>
                        <div style="margin-top:.35rem;">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($distributed): ?>
                            <span class="pw-badge pw-badge--success" style="font-size:.68rem;padding:.15rem .5rem;">Diterima</span>
                            <?php elseif($reached): ?>
                            <span class="pw-badge pw-badge--pending" style="font-size:.68rem;padding:.15rem .5rem;">Tercapai</span>
                            <?php else: ?>
                            <span style="font-size:.68rem;color:var(--pw-text-muted);"><?php echo e($preLaunchQualified); ?>/<?php echo e($tier['count']); ?></span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>

            <div style="margin-top:.8rem;text-align:center;">
                <a href="<?php echo e(route('referral.ranking')); ?>" style="font-size:.82rem;color:#c8972a;text-decoration:none;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:-2px;"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4-4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                    Lihat Referral Ranking →
                </a>
            </div>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    </div>
</section>
<div id="pwModalPassword" style="display:none;position:fixed;inset:0;z-index:9999;align-items:center;justify-content:center;background:rgba(0,0,0,.65);backdrop-filter:blur(4px);" onclick="if(event.target===this)pwClosePasswordModal()">
    <div class="pw-profile-card" style="width:90%;max-width:400px;position:relative;margin:0;box-shadow:0 20px 60px rgba(0,0,0,.5);background:var(--pw-bg-card);">
        
        <button type="button" onclick="pwClosePasswordModal()" style="position:absolute;top:.75rem;right:.75rem;background:none;border:none;color:var(--pw-text-muted);cursor:pointer;padding:4px;" aria-label="Tutup">
            <svg viewBox="0 0 16 16" fill="none" width="16"><path d="M4 4l8 8M12 4l-8 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        </button>

        
        <div class="pw-profile-card__header" style="justify-content:center;">
            <svg viewBox="0 0 16 16" fill="none" width="14" aria-hidden="true"><rect x="3" y="7" width="10" height="7" rx="1.5" stroke="#c8972a" stroke-width="1.3"/><path d="M5 7V5a3 3 0 016 0v2" stroke="#c8972a" stroke-width="1.3" stroke-linecap="round"/></svg>
            <?php echo e(__('main.profile_change_password')); ?>

        </div>

        
        <div id="pwPasswordAlert" style="display:none;padding:.5rem .75rem;border-radius:6px;font-size:.8rem;font-weight:500;margin-bottom:.8rem;"></div>

        
        <form id="pwPasswordForm" method="POST" action="<?php echo e(route('profile.change-password')); ?>">
            <?php echo csrf_field(); ?>
            <label class="pw-profile-label"><?php echo e(__('main.profile_pin_game')); ?></label>
            <input type="password" name="pin" id="pwPinInput" class="pw-profile-input" placeholder="<?php echo e(__('main.profile_pin_placeholder')); ?>" required>
            <p id="pwPinError" class="pw-profile-error" style="display:none;"></p>

            <label class="pw-profile-label"><?php echo e(__('main.profile_new_password')); ?></label>
            <input type="password" name="new_password" id="pwNewPassInput" class="pw-profile-input" placeholder="<?php echo e(__('main.profile_new_pass_placeholder')); ?>" required>
            <p id="pwNewPassError" class="pw-profile-error" style="display:none;"></p>

            <label class="pw-profile-label"><?php echo e(__('main.profile_confirm_password')); ?></label>
            <input type="password" name="new_password_confirmation" id="pwConfirmPassInput" class="pw-profile-input" placeholder="<?php echo e(__('main.profile_confirm_placeholder')); ?>" required>
            <p id="pwConfirmError" class="pw-profile-error" style="display:none;"></p>

            <button type="submit" id="pwPasswordSubmit" class="pw-btn pw-btn--gold pw-btn--sm" style="width:100%;margin-top:.3rem;justify-content:center;">
                <svg viewBox="0 0 16 16" fill="none" width="13"><rect x="3" y="7" width="10" height="7" rx="1.5" stroke="currentColor" stroke-width="1.3"/><path d="M5 7V5a3 3 0 016 0v2" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                <?php echo e(__('main.profile_change_password')); ?>

            </button>
        </form>
    </div>
</div>


<div id="pwModalSuccess" style="display:none;position:fixed;inset:0;z-index:10000;align-items:center;justify-content:center;background:rgba(0,0,0,.65);backdrop-filter:blur(4px);" onclick="if(event.target===this)this.style.display='none'">
    <div class="pw-profile-card" style="width:90%;max-width:360px;text-align:center;margin:0;box-shadow:0 20px 60px rgba(0,0,0,.5);background:var(--pw-bg-card);">
        <div style="width:56px;height:56px;border-radius:50%;background:rgba(56,161,105,.15);display:flex;align-items:center;justify-content:center;margin:0 auto .8rem;">
            <svg viewBox="0 0 24 24" fill="none" width="28"><path d="M5 13l4 4L19 7" stroke="#48bb78" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </div>
        <div class="pw-profile-card__header" style="justify-content:center;border:none;margin-bottom:.4rem;padding-bottom:0;"><?php echo e(__('main.profile_success')); ?></div>
        <p class="pw-profile-hint" style="margin:0 0 1.2rem;text-align:center;"><?php echo e(__('main.profile_password_changed')); ?></p>
        <button type="button" onclick="document.getElementById('pwModalSuccess').style.display='none'" class="pw-btn pw-btn--gold pw-btn--sm">OK</button>
    </div>
</div>

<script>
function pwClosePasswordModal() {
    document.getElementById('pwModalPassword').style.display = 'none';
    document.getElementById('pwPasswordForm').reset();
    document.getElementById('pwPasswordAlert').style.display = 'none';
    ['pwPinError','pwNewPassError','pwConfirmError'].forEach(id => document.getElementById(id).style.display = 'none');
}

document.getElementById('pwPasswordForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // Clear previous errors
    ['pwPinError','pwNewPassError','pwConfirmError'].forEach(id => document.getElementById(id).style.display = 'none');
    document.getElementById('pwPasswordAlert').style.display = 'none';

    var pin = document.getElementById('pwPinInput').value;
    var newPass = document.getElementById('pwNewPassInput').value;
    var confirmPass = document.getElementById('pwConfirmPassInput').value;
    var hasError = false;

    if (!pin) {
        var el = document.getElementById('pwPinError');
        el.textContent = <?php echo json_encode(__('main.profile_pin_required'), 15, 512) ?>;
        el.style.display = 'block';
        hasError = true;
    }
    if (newPass.length < 6) {
        var el = document.getElementById('pwNewPassError');
        el.textContent = <?php echo json_encode(__('main.profile_pass_min'), 15, 512) ?>;
        el.style.display = 'block';
        hasError = true;
    }
    if (newPass !== confirmPass) {
        var el = document.getElementById('pwConfirmError');
        el.textContent = <?php echo json_encode(__('main.profile_pass_mismatch'), 15, 512) ?>;
        el.style.display = 'block';
        hasError = true;
    }
    if (hasError) return;

    var btn = document.getElementById('pwPasswordSubmit');
    btn.disabled = true;
    btn.innerHTML = '<svg class="pw-spinner" viewBox="0 0 16 16" width="13" style="animation:spin .8s linear infinite"><circle cx="8" cy="8" r="6" stroke="currentColor" stroke-width="1.5" fill="none" stroke-dasharray="28" stroke-dashoffset="8"/></svg> ' + <?php echo json_encode(__('main.profile_processing'), 15, 512) ?>;

    fetch(this.action, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ pin: pin, new_password: newPass, new_password_confirmation: confirmPass })
    })
    .then(function(res) { return res.json().then(function(data) { return { ok: res.ok, status: res.status, data: data }; }); })
    .then(function(result) {
        btn.disabled = false;
        btn.innerHTML = '<svg viewBox="0 0 16 16" fill="none" width="13"><rect x="3" y="7" width="10" height="7" rx="1.5" stroke="currentColor" stroke-width="1.3"/><path d="M5 7V5a3 3 0 016 0v2" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg> ' + <?php echo json_encode(__('main.profile_change_password'), 15, 512) ?>;

        if (result.ok && result.data.success) {
            pwClosePasswordModal();
            document.getElementById('pwModalSuccess').style.display = 'flex';
        } else if (result.data.errors) {
            var errors = result.data.errors;
            if (errors.pin) {
                var el = document.getElementById('pwPinError');
                el.textContent = errors.pin[0];
                el.style.display = 'block';
            }
            if (errors.new_password) {
                var el = document.getElementById('pwNewPassError');
                el.textContent = errors.new_password[0];
                el.style.display = 'block';
            }
        } else if (result.data.message) {
            var alert = document.getElementById('pwPasswordAlert');
            alert.style.display = 'block';
            alert.style.background = 'rgba(245,101,101,.12)';
            alert.style.border = '1px solid rgba(245,101,101,.35)';
            alert.style.color = '#ff6b6b';
            alert.textContent = result.data.message;
        }
    })
    .catch(function() {
        btn.disabled = false;
        btn.innerHTML = '<svg viewBox="0 0 16 16" fill="none" width="13"><rect x="3" y="7" width="10" height="7" rx="1.5" stroke="currentColor" stroke-width="1.3"/><path d="M5 7V5a3 3 0 016 0v2" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg> ' + <?php echo json_encode(__('main.profile_change_password'), 15, 512) ?>;
        var alert = document.getElementById('pwPasswordAlert');
        alert.style.display = 'block';
        alert.style.background = 'rgba(245,101,101,.12)';
        alert.style.border = '1px solid rgba(245,101,101,.35)';
        alert.style.color = '#ff6b6b';
        alert.textContent = <?php echo json_encode(__('main.profile_error_generic'), 15, 512) ?>;
    });
});

<?php if(session('password_success')): ?>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('pwModalSuccess').style.display = 'flex';
});
<?php endif; ?>

<?php if($errors->has('pin') || $errors->has('new_password')): ?>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('pwModalPassword').style.display = 'flex';
    <?php if($errors->has('pin')): ?>
    var el = document.getElementById('pwPinError');
    el.textContent = <?php echo json_encode($errors->first('pin'), 15, 512) ?>;
    el.style.display = 'block';
    <?php endif; ?>
    <?php if($errors->has('new_password')): ?>
    var el = document.getElementById('pwNewPassError');
    el.textContent = <?php echo json_encode($errors->first('new_password'), 15, 512) ?>;
    el.style.display = 'block';
    <?php endif; ?>
});
<?php endif; ?>
</script>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.pw-profile-badge--admin-front {
    background: rgba(147, 51, 234, .18);
    color: #c084fc;
    border: 1px solid rgba(147, 51, 234, .42);
}

.pw-profile-badge--gm-front {
    background: rgba(239, 68, 68, .18);
    color: #f87171;
    border: 1px solid rgba(239, 68, 68, .42);
}
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/pw-panel/resources/views/front/profile.blade.php ENDPATH**/ ?>