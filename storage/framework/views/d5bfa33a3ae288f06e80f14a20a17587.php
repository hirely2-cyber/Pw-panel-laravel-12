<?php
    $__siteName = \App\Models\Setting::get('seo_title') ?: config('pw-config.server.name') ?: 'Perfect World';
?>

<?php $__env->startSection('title', __('main.ranking_page_title') . ' — ' . $__siteName); ?>
<?php $__env->startSection('meta_description', __('main.ranking_page_subtitle') . ' ' . $__siteName); ?>

<?php $__env->startSection('content'); ?>


<div class="pw-page-hero">
    <div class="pw-page-hero__bg" aria-hidden="true"></div>
    <canvas id="pw-sparkle" style="position:absolute;inset:0;width:100%;height:100%;pointer-events:none;z-index:0;" aria-hidden="true"></canvas>
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
            <?php echo e(__('main.ranking_page_title')); ?>

        </h1>
        <p class="pw-page-hero__sub"><?php echo e(__('main.ranking_page_subtitle')); ?> <?php echo e($__siteName); ?></p>
        <nav class="pw-breadcrumb" aria-label="breadcrumb">
            <a href="<?php echo e(route('home')); ?>" class="pw-breadcrumb__item">
                <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true">
                    <path d="M2 7.5L8 2l6 5.5V14a1 1 0 01-1 1h-3.5v-3.5h-3V15H3a1 1 0 01-1-1V7.5z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/>
                </svg>
                <?php echo e(__('main.breadcrumb_home')); ?>

            </a>
            <span class="pw-breadcrumb__sep" aria-hidden="true">/</span>
            <span class="pw-breadcrumb__item pw-breadcrumb__item--active"><?php echo e(__('main.nav_ranking')); ?></span>
        </nav>
    </div>
</div>


<section class="pw-section" id="ranking">
    <div class="pw-section__inner pw-section__inner--narrow">

        <?php
            $classIconMap = [
                'Blademaster' => 'blademaster', 'Wizard' => 'wizzard', 'Cleric' => 'cleric',
                'Archer' => 'archer', 'Barbarian' => 'barbarian', 'Venomancer' => 'venomancer',
                'Assassin' => 'assasin', 'Psychic' => 'psychic', 'Seeker' => 'seeker',
                'Mystic' => 'mystic', 'Duskblade' => 'duskblade', 'Stormbringer' => 'stormbringer',
            ];
        ?>

        <div x-data="{ tab: 'players' }">

            
            <div x-show="tab==='players'" x-transition>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($players->currentPage() === 1 && $players->count() >= 3): ?>
                <div class="pw-podium">
                    <?php
                        $podiumOrder  = [1, 0, 2]; // 2nd, 1st, 3rd
                        $podiumRank   = [2, 1, 3];
                        $podiumClass  = ['pw-podium__step--silver', 'pw-podium__step--gold', 'pw-podium__step--bronze'];
                        $rankClass    = ['pw-rank--2', 'pw-rank--1', 'pw-rank--3'];
                        $rankColors   = ['#c0c0c0', '#ffd700', '#cd7f32'];
                    ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $podiumOrder; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $pi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php $p = $players[$pi]; ?>
                    <div class="pw-podium__item <?php echo e($podiumClass[$idx]); ?>">
                        <div class="pw-podium__avatar" aria-hidden="true">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($podiumRank[$idx] === 1): ?>
                            <svg viewBox="0 0 24 14" fill="currentColor" width="28" style="color:#ffd700;display:block;margin:0 auto .3rem;filter:drop-shadow(0 2px 6px rgba(255,215,0,.4));">
                                <path d="M2 12L5 3l5 5 2-6 2 6 5-5 3 9H2z"/>
                            </svg>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <div class="pw-podium__avatar-ring" style="border-color:<?php echo e($rankColors[$idx]); ?>;<?php echo e($podiumRank[$idx] === 1 ? 'width:160px;height:160px;border-width:4px;' : 'width:130px;height:130px;'); ?>">
                                <?php $classImg = $classIconMap[$p->class] ?? null; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($classImg): ?>
                                <img src="<?php echo e(asset('images/job_class/' . $classImg . '.png')); ?>" alt="<?php echo e($p->class); ?>" style="width:100%;height:100%;object-fit:cover;border-radius:50%;position:relative;z-index:1;">
                                <?php else: ?>
                                <svg viewBox="0 0 40 40" fill="none" width="36" aria-hidden="true" style="position:relative;z-index:1;">
                                    <circle cx="20" cy="20" r="19" stroke="<?php echo e($rankColors[$idx]); ?>" stroke-width="1" opacity=".3"/>
                                    <circle cx="20" cy="15" r="7" stroke="<?php echo e($rankColors[$idx]); ?>" stroke-width="1.5" opacity=".8"/>
                                    <path d="M6 36c0-7.7 6.3-14 14-14s14 6.3 14 14" stroke="<?php echo e($rankColors[$idx]); ?>" stroke-width="1.5" opacity=".8" stroke-linecap="round"/>
                                </svg>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                        <div class="pw-podium__name"><?php echo e($p->character_name); ?></div>
                        <div class="pw-podium__sub"><?php echo e($p->class ?? '—'); ?></div>
                        <div class="pw-podium__level" style="color:<?php echo e($rankColors[$idx]); ?>">Lv <?php echo e($p->level); ?></div>
                        <div class="pw-podium__exp"><?php echo e(number_format($p->exp)); ?> EXP</div>
                        <div class="pw-podium__step-block">
                            <span class="pw-rank <?php echo e($rankClass[$idx]); ?>">#<?php echo e($podiumRank[$idx]); ?></span>
                        </div>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            
            <div x-show="tab==='guilds'" x-transition style="display:none;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($factions->currentPage() === 1 && $factions->count() >= 3): ?>
                <div class="pw-podium">
                    <?php
                        $gPodiumOrder = [1, 0, 2];
                        $gPodiumRank  = [2, 1, 3];
                        $gPodiumClass = ['pw-podium__step--silver', 'pw-podium__step--gold', 'pw-podium__step--bronze'];
                        $gRankClass   = ['pw-rank--2', 'pw-rank--1', 'pw-rank--3'];
                        $gRankColors  = ['#c0c0c0', '#ffd700', '#cd7f32'];
                    ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $gPodiumOrder; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $fi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php $f = $factions[$fi]; ?>
                    <div class="pw-podium__item <?php echo e($gPodiumClass[$idx]); ?>">
                        <div class="pw-podium__avatar" aria-hidden="true">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($gPodiumRank[$idx] === 1): ?>
                            <svg viewBox="0 0 24 14" fill="currentColor" width="20" style="color:#ffd700;display:block;margin:0 auto .3rem;">
                                <path d="M2 12L5 3l5 5 2-6 2 6 5-5 3 9H2z"/>
                            </svg>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <div class="pw-podium__avatar-ring" style="border-color:<?php echo e($gRankColors[$idx]); ?>">
                                <svg viewBox="0 0 40 40" fill="none" width="36" aria-hidden="true">
                                    <circle cx="20" cy="20" r="19" stroke="<?php echo e($gRankColors[$idx]); ?>" stroke-width="1" opacity=".3"/>
                                    <path d="M20 6L9 11v7c0 6 4.6 11 11 12 6.4-.9 11-6 11-12v-7L20 6z" stroke="<?php echo e($gRankColors[$idx]); ?>" stroke-width="1.5" opacity=".8" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </div>
                        <div class="pw-podium__name"><?php echo e($f->name); ?></div>
                        <div class="pw-podium__sub"><?php echo e($f->leader_name ?? '—'); ?></div>
                        <div class="pw-podium__level" style="color:<?php echo e($gRankColors[$idx]); ?>"><?php echo e($f->members_count ?? 0); ?> <?php echo e(__('main.ranking_members_count')); ?></div>
                        <div class="pw-podium__exp"><?php echo e($f->territory_count ?? 0); ?> <?php echo e(__('main.ranking_territory_count')); ?></div>
                        <div class="pw-podium__step-block">
                            <span class="pw-rank <?php echo e($gRankClass[$idx]); ?>">#<?php echo e($gPodiumRank[$idx]); ?></span>
                        </div>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            
            <div class="pw-ranking__tabs">
                <button @click="tab='players'"
                        :class="tab==='players' ? 'is-active' : ''"
                        class="pw-ranking__tab">
                    <svg viewBox="0 0 20 20" fill="none" width="14" aria-hidden="true">
                        <circle cx="8" cy="6" r="3.5" stroke="currentColor" stroke-width="1.4"/>
                        <path d="M1 17c0-3.3 3.1-6 7-6s7 2.7 7 6" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                    </svg>
                    <?php echo e(__('main.ranking_tab_players')); ?>

                </button>
                <button @click="tab='guilds'"
                        :class="tab==='guilds' ? 'is-active' : ''"
                        class="pw-ranking__tab">
                    <svg viewBox="0 0 20 20" fill="none" width="14" aria-hidden="true">
                        <path d="M10 2L3 6v5c0 4 3.2 7.4 7 8 3.8-.6 7-4 7-8V6L10 2z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
                    </svg>
                    <?php echo e(__('main.ranking_tab_guilds')); ?>

                </button>
            </div>

            
            <div x-show="tab==='players'" x-transition>
                <div class="pw-ranking__table-wrap">
                    <table class="pw-ranking__table">
                        <thead>
                            <tr>
                                <th style="text-align:center;width:50px;">#</th>
                                <th><?php echo e(__('main.ranking_col_character')); ?></th>
                                <th><?php echo e(__('main.ranking_col_class')); ?></th>
                                <th style="text-align:center;"><?php echo e(__('main.ranking_col_level')); ?></th>
                                <th style="text-align:right;"><?php echo e(__('main.ranking_col_exp')); ?></th>
                                <th><?php echo e(__('main.ranking_col_guild')); ?></th>
                                <th style="text-align:center;"><?php echo e(__('main.ranking_col_pk')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $players; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rank => $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <?php $playerRank = $players->firstItem() + $rank; ?>
                            <tr class="<?php echo e($playerRank <= 3 ? 'pw-ranking__top' : ''); ?>">
                                <td style="text-align:center;">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($playerRank <= 3): ?>
                                        <span class="pw-rank pw-rank--<?php echo e($playerRank); ?>"><?php echo e($playerRank); ?></span>
                                    <?php else: ?>
                                        <span class="pw-rank"><?php echo e($playerRank); ?></span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                                <td class="pw-ranking__name"><?php echo e($p->character_name); ?></td>
                                <td style="color:var(--pw-text-muted);font-size:.8rem;">
                                    <span style="display:inline-flex;align-items:center;gap:.35rem;">
                                        <img src="/images/class/<?php echo e($classIconMap[$p->class] ?? 'blademaster'); ?>.png" alt="<?php echo e($p->class); ?>" width="18" height="18" style="flex-shrink:0;">
                                        <?php echo e($p->class ?? '—'); ?>

                                    </span>
                                </td>
                                <td style="text-align:center;color:#7ec8c8;font-weight:600;"><?php echo e($p->level); ?></td>
                                <td class="pw-ranking__exp" style="text-align:right;"><?php echo e(number_format($p->exp)); ?></td>
                                <td style="color:var(--pw-text-muted);font-size:.8rem;"><?php echo e($p->faction_name ?: '—'); ?></td>
                                <td style="text-align:center;color:var(--pw-text-muted);"><?php echo e($p->pk_points ?? 0); ?></td>
                            </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <tr>
                                <td colspan="7" class="pw-table__empty"><?php echo e(__('main.ranking_no_data')); ?></td>
                            </tr>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($players->hasPages()): ?>
                <div style="margin-top:1rem;"><?php echo e($players->onEachSide(1)->links()); ?></div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($players->isNotEmpty() && $players->first()->updated_at): ?>
                <p style="text-align:right;font-size:.7rem;color:var(--pw-text-muted);margin-top:.6rem;">
                    <svg viewBox="0 0 16 16" fill="none" width="11" style="vertical-align:middle;margin-right:.2rem;" aria-hidden="true">
                        <circle cx="8" cy="8" r="6" stroke="currentColor" stroke-width="1.2"/>
                        <path d="M8 5v3.5l2 1.5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                    </svg>
                    <?php echo e(__('main.ranking_last_update')); ?>: <?php echo e($players->first()->updated_at->diffForHumans()); ?>

                </p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            
            <div x-show="tab==='guilds'" x-transition style="display:none;">
                <div class="pw-ranking__table-wrap">
                    <table class="pw-ranking__table">
                        <thead>
                            <tr>
                                <th style="text-align:center;width:50px;">#</th>
                                <th><?php echo e(__('main.ranking_col_guild_name')); ?></th>
                                <th><?php echo e(__('main.ranking_col_leader')); ?></th>
                                <th style="text-align:center;"><?php echo e(__('main.ranking_col_members')); ?></th>
                                <th style="text-align:center;"><?php echo e(__('main.ranking_col_territory')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $factions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rank => $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <?php $factionRank = $factions->firstItem() + $rank; ?>
                            <tr class="<?php echo e($factionRank <= 3 ? 'pw-ranking__top' : ''); ?>">
                                <td style="text-align:center;">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($factionRank <= 3): ?>
                                        <span class="pw-rank pw-rank--<?php echo e($factionRank); ?>"><?php echo e($factionRank); ?></span>
                                    <?php else: ?>
                                        <span class="pw-rank"><?php echo e($factionRank); ?></span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                                <td class="pw-ranking__name"><?php echo e($f->name); ?></td>
                                <td style="color:var(--pw-text-muted);font-size:.8rem;"><?php echo e($f->leader_name ?? '—'); ?></td>
                                <td style="text-align:center;color:#7ec8c8;font-weight:600;"><?php echo e($f->members_count ?? 0); ?></td>
                                <td style="text-align:center;color:#b89d4f;font-weight:600;"><?php echo e($f->territory_count ?? 0); ?></td>
                            </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <tr>
                                <td colspan="5" class="pw-table__empty"><?php echo e(__('main.ranking_guild_no_data')); ?></td>
                            </tr>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($factions->hasPages()): ?>
                <div style="margin-top:1rem;"><?php echo e($factions->onEachSide(1)->links()); ?></div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

        </div>

    </div>
</section>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
(function () {
    const canvas = document.getElementById('pw-sparkle');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    let W, H, stars = [];

    const GOLD = ['#fbbf24','#f59e0b','#fcd34d','#c8972a','#e8b84b','#fff7c2'];

    function resize() {
        W = canvas.width = canvas.offsetWidth;
        H = canvas.height = canvas.offsetHeight;
    }

    function makeStar() {
        return {
            x: Math.random() * W,
            y: Math.random() * H - H,
            size: Math.random() * 3 + 1.5,
            speed: Math.random() * 0.4 + 0.15,
            drift: Math.random() * 0.3 - 0.15,
            pulse: Math.random() * Math.PI * 2,
            pulseSpeed: Math.random() * 0.06 + 0.02,
            color: GOLD[Math.floor(Math.random() * GOLD.length)],
            opacity: Math.random() * 0.6 + 0.2,
            type: Math.floor(Math.random() * 3)
        };
    }

    function drawStar4(cx, cy, r) {
        ctx.beginPath();
        for (let i = 0; i < 4; i++) {
            const a = (i * Math.PI / 2) - Math.PI / 2;
            ctx.moveTo(cx, cy);
            ctx.lineTo(cx + Math.cos(a) * r, cy + Math.sin(a) * r);
        }
        ctx.stroke();
        ctx.beginPath();
        ctx.arc(cx, cy, r * 0.25, 0, Math.PI * 2);
        ctx.fill();
    }

    function drawCross(cx, cy, r) {
        ctx.beginPath();
        ctx.moveTo(cx - r, cy); ctx.lineTo(cx + r, cy);
        ctx.moveTo(cx, cy - r); ctx.lineTo(cx, cy + r);
        ctx.moveTo(cx - r * 0.6, cy - r * 0.6); ctx.lineTo(cx + r * 0.6, cy + r * 0.6);
        ctx.moveTo(cx + r * 0.6, cy - r * 0.6); ctx.lineTo(cx - r * 0.6, cy + r * 0.6);
        ctx.stroke();
    }

    function drawDiamond(cx, cy, r) {
        ctx.beginPath();
        ctx.moveTo(cx, cy - r);
        ctx.lineTo(cx + r * 0.5, cy);
        ctx.lineTo(cx, cy + r);
        ctx.lineTo(cx - r * 0.5, cy);
        ctx.closePath();
        ctx.fill();
    }

    function init() {
        resize();
        stars = [];
        for (let i = 0; i < 35; i++) {
            const s = makeStar();
            s.y = Math.random() * H;
            stars.push(s);
        }
    }

    function draw() {
        ctx.clearRect(0, 0, W, H);
        stars.forEach(s => {
            ctx.save();
            const pulse = (Math.sin(s.pulse) + 1) / 2;
            ctx.globalAlpha = s.opacity * (0.4 + pulse * 0.6);
            ctx.strokeStyle = s.color;
            ctx.fillStyle = s.color;
            ctx.lineWidth = 0.8;
            const r = s.size * (0.7 + pulse * 0.3);
            if (s.type === 0) drawStar4(s.x, s.y, r);
            else if (s.type === 1) drawCross(s.x, s.y, r);
            else drawDiamond(s.x, s.y, r);
            ctx.restore();
        });
    }

    function update() {
        stars.forEach(s => {
            s.y += s.speed;
            s.x += s.drift;
            s.pulse += s.pulseSpeed;
            if (s.y > H + 10) { s.y = -10; s.x = Math.random() * W; }
        });
    }

    function loop() { draw(); update(); requestAnimationFrame(loop); }
    window.addEventListener('resize', resize);
    init();
    loop();
})();
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/pw-panel/resources/views/website/ranking.blade.php ENDPATH**/ ?>