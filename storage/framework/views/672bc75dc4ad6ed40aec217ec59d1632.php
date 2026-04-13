<?php
    $__siteName = \App\Models\Setting::get('seo_title') ?: config('pw-config.server.name') ?: 'Perfect World';
?>

<?php $__env->startSection('title', __('main.news_page_title') . ' — ' . $__siteName); ?>
<?php $__env->startSection('meta_description', __('main.news_page_subtitle')); ?>

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
        <h1 class="pw-page-hero__title"><?php echo e(__('main.news_page_title')); ?></h1>
        <p class="pw-page-hero__sub"><?php echo e(__('main.news_page_subtitle')); ?></p>
        <nav class="pw-breadcrumb" aria-label="breadcrumb">
            <a href="<?php echo e(route('home')); ?>" class="pw-breadcrumb__item">
                <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true">
                    <path d="M2 7.5L8 2l6 5.5V14a1 1 0 01-1 1h-3.5v-3.5h-3V15H3a1 1 0 01-1-1V7.5z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/>
                </svg>
                <?php echo e(__('main.breadcrumb_home')); ?>

            </a>
            <span class="pw-breadcrumb__sep" aria-hidden="true">/</span>
            <span class="pw-breadcrumb__item pw-breadcrumb__item--active"><?php echo e(__('main.news_page_title')); ?></span>
        </nav>
    </div>
</div>


<section class="pw-section" id="news">
    <div class="pw-section__inner pw-section__inner--narrow">

        
        <?php
            $categories = $news->getCollection()->pluck('category')->filter()->unique()->values();
        ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($categories->count()): ?>
        <div class="pw-news-filter">
            <a href="<?php echo e(route('news.index')); ?>"
               class="pw-news-filter__btn <?php echo e(!request('cat') ? 'is-active' : ''); ?>">
                <?php echo e(__('main.news_filter_all')); ?>

            </a>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <a href="<?php echo e(route('news.index', ['cat' => $cat])); ?>"
               class="pw-news-filter__btn <?php echo e(request('cat') === $cat ? 'is-active' : ''); ?>">
                <?php echo e($cat); ?>

            </a>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($news->count()): ?>

        
        <div class="pw-news-grid pw-news-grid--full">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $news; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <a href="<?php echo e(route('news.show', $article->slug)); ?>" class="pw-news-card">
                <div class="pw-news-card__thumb">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($article->thumbnail): ?>
                        <img src="<?php echo e(Storage::url($article->thumbnail)); ?>" alt="<?php echo e($article->title); ?>" loading="lazy">
                    <?php else: ?>
                        <svg viewBox="0 0 400 240" xmlns="http://www.w3.org/2000/svg">
                            <defs><linearGradient id="ng<?php echo e($loop->index); ?>" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="<?php echo e(['#0d1f3c','#1a2010','#2a0d0d','#1a0d2e','#0d1a2a','#1a1000'][$loop->index % 6]); ?>"/><stop offset="100%" stop-color="#0a0a14"/></linearGradient></defs>
                            <rect width="400" height="240" fill="url(#ng<?php echo e($loop->index); ?>)"/>
                            <path d="M200 60 L165 140 L200 200 L235 140 Z" fill="#c8972a" opacity=".2"/>
                            <circle cx="200" cy="130" r="22" fill="#c8972a" opacity=".25"/>
                        </svg>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <div class="pw-news-card__overlay"></div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($loop->first): ?>
                    <span class="pw-news-card__badge-new"><?php echo e(__('main.news_badge_latest')); ?></span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($article->category): ?>
                    <span class="pw-news-card__cat"><?php echo e($article->category); ?></span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="pw-news-card__body">
                    <h3 class="pw-news-card__title"><?php echo e(Str::limit($article->title, 70)); ?></h3>
                    <p class="pw-news-card__excerpt"><?php echo e(Str::limit($article->excerpt, 100)); ?></p>
                    <div class="pw-news-card__meta">
                        <span class="pw-news-card__meta-author">
                            <svg viewBox="0 0 16 16" fill="none" width="11" aria-hidden="true"><circle cx="8" cy="5" r="3" stroke="currentColor" stroke-width="1.3"/><path d="M2 14a6 6 0 0112 0" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                            <?php echo e($article->author?->truename ?: ($article->author?->name ?? 'Admin')); ?>

                        </span>
                        <span>
                            <svg viewBox="0 0 16 16" fill="none" width="11" aria-hidden="true"><rect x="2" y="3" width="12" height="11" rx="2" stroke="currentColor" stroke-width="1.3"/><path d="M5 1v3M11 1v3M2 7h12" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                            <?php echo e($article->created_at->translatedFormat('d F Y')); ?>

                        </span>
                        <span class="pw-news-card__read"><?php echo e(__('main.news_readmore')); ?> →</span>
                    </div>
                </div>
            </a>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($news->hasPages()): ?>
        <div>
            <?php echo e($news->appends(request()->query())->links()); ?>

        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php else: ?>
        <p class="pw-section__empty"><?php echo e(__('main.news_no_articles')); ?></p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    </div>
</section>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/pw-panel/resources/views/website/news.blade.php ENDPATH**/ ?>