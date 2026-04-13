<?php
    $__siteName = \App\Models\Setting::get('seo_title') ?: config('pw-config.server.name') ?: 'Perfect World';
?>

<?php $__env->startSection('title', $article->title . ' — ' . $__siteName); ?>
<?php $__env->startSection('meta_description', Str::limit(strip_tags($article->content), 160)); ?>

<?php $__env->startSection('content'); ?>


<div class="pw-article-hero"
    <?php if($article->thumbnail): ?>
        style="--article-thumb: url('<?php echo e(Storage::url($article->thumbnail)); ?>')"
    <?php endif; ?>>
    <div class="pw-article-hero__bg" aria-hidden="true"></div>
    <div class="pw-article-hero__inner">

        
        <nav class="pw-breadcrumb" aria-label="breadcrumb">
            <a href="<?php echo e(route('home')); ?>" class="pw-breadcrumb__item">
                <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true">
                    <path d="M2 7.5L8 2l6 5.5V14a1 1 0 01-1 1h-3.5v-3.5h-3V15H3a1 1 0 01-1-1V7.5z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/>
                </svg>
                Beranda
            </a>
            <span class="pw-breadcrumb__sep" aria-hidden="true">/</span>
            <a href="<?php echo e(route('news.index')); ?>" class="pw-breadcrumb__item"><?php echo e(__('main.news_page_title')); ?></a>
            <span class="pw-breadcrumb__sep" aria-hidden="true">/</span>
            <span class="pw-breadcrumb__item pw-breadcrumb__item--active"><?php echo e(Str::limit($article->title, 45)); ?></span>
        </nav>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($article->category): ?>
        <span class="pw-article-hero__cat"><?php echo e($article->category); ?></span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <h1 class="pw-article-hero__title"><?php echo e($article->title); ?></h1>

        
        <div class="pw-article-hero__meta">
            <span>
                <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true"><circle cx="8" cy="5" r="3" stroke="currentColor" stroke-width="1.3"/><path d="M2 14a6 6 0 0112 0" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                <?php echo e($article->author?->truename ?: ($article->author?->name ?? 'Admin')); ?>

            </span>
            <span>
                <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true"><rect x="2" y="3" width="12" height="11" rx="1.5" stroke="currentColor" stroke-width="1.2"/><path d="M5 1v4M11 1v4M2 7h12" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/></svg>
                <?php echo e($article->created_at->translatedFormat('d F Y')); ?>

            </span>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($article->views): ?>
            <span>
                <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true"><path d="M1 8s3-5 7-5 7 5 7 5-3 5-7 5-7-5-7-5z" stroke="currentColor" stroke-width="1.2"/><circle cx="8" cy="8" r="2" stroke="currentColor" stroke-width="1.2"/></svg>
                <?php echo e(number_format($article->views)); ?> views
            </span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

    </div>
</div>


<section class="pw-section pw-section--article">
    <div class="pw-article-wrap">

        <article class="pw-prose">
            <?php echo $article->content; ?>

        </article>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($article->tags && count($article->tags)): ?>
        <div class="pw-article__tags">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $article->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <span class="pw-tag">#<?php echo e($tag); ?></span>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <div class="pw-article__actions">
            <a href="<?php echo e(route('news.index')); ?>" class="pw-btn pw-btn--ghost">
                <svg viewBox="0 0 16 16" fill="none" width="14" aria-hidden="true"><path d="M10 3L5 8l5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                <?php echo e(__('main.news_all_btn')); ?>

            </a>
            <a href="https://wa.me/?text=<?php echo e(urlencode($article->title . ' — ' . url()->current())); ?>"
               target="_blank" rel="noopener" class="pw-btn-share pw-btn-share--wa">
                <svg viewBox="0 0 24 24" fill="currentColor" width="15" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                Share
            </a>
        </div>

    </div>
</section>


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recent->isNotEmpty()): ?>
<section class="pw-section pw-section--related">
    <div class="pw-section__inner pw-section__inner--narrow">
        <div class="pw-section__head">
            <div class="pw-section__ornament-line"></div>
            <h2 class="pw-section__title"><?php echo e(__('main.news_other')); ?></h2>
            <div class="pw-section__ornament-line"></div>
        </div>
        <div class="pw-news-grid pw-news-grid--full">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $recent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <a href="<?php echo e(route('news.show', $item->slug)); ?>" class="pw-news-card">
                <div class="pw-news-card__thumb">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->thumbnail): ?>
                        <img src="<?php echo e(Storage::url($item->thumbnail)); ?>" alt="<?php echo e($item->title); ?>" loading="lazy">
                    <?php else: ?>
                        <svg viewBox="0 0 400 240" xmlns="http://www.w3.org/2000/svg">
                            <defs><linearGradient id="rng<?php echo e($loop->index); ?>" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="<?php echo e(['#0d1f3c','#1a2010','#2a0d0d','#1a0d2e'][$loop->index % 4]); ?>"/><stop offset="100%" stop-color="#0a0a14"/></linearGradient></defs>
                            <rect width="400" height="240" fill="url(#rng<?php echo e($loop->index); ?>)"/>
                            <circle cx="200" cy="120" r="22" fill="#c8972a" opacity=".2"/>
                        </svg>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <div class="pw-news-card__overlay"></div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->category): ?>
                    <span class="pw-news-card__cat"><?php echo e($item->category); ?></span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="pw-news-card__body">
                    <h3 class="pw-news-card__title"><?php echo e(Str::limit($item->title, 65)); ?></h3>
                    <div class="pw-news-card__meta">
                        <span><?php echo e($item->created_at->diffForHumans()); ?></span>
                    </div>
                </div>
            </a>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/pw-panel/resources/views/website/news-detail.blade.php ENDPATH**/ ?>