<div class="pw-shop-card" x-data="{ confirm: false }">

    
    <div class="pw-shop-card__img">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->image): ?>
            <img src="<?php echo e(Storage::url($item->image)); ?>" alt="<?php echo e($item->name); ?>" loading="lazy">
        <?php else: ?>
            <img src="<?php echo e(asset('images/gif_icon/web_coin.gif')); ?>" alt="Gold Points" width="72" style="opacity:.75;">
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->stock !== null && $item->stock <= 10): ?>
        <span class="pw-shop-card__badge pw-shop-card__badge--stock">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->stock === 0): ?> Habis <?php else: ?> Sisa <?php echo e($item->stock); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->item_count > 1): ?>
        <span class="pw-shop-card__badge pw-shop-card__badge--qty">×<?php echo e($item->item_count); ?></span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
    <div class="pw-shop-card__body">
        <h3 class="pw-shop-card__name"><?php echo e($item->name); ?></h3>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->description): ?>
        <p class="pw-shop-card__desc"><?php echo e(Str::limit($item->description, 65)); ?></p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <div class="pw-shop-card__price">
            <img src="<?php echo e(asset('images/gif_icon/web_coin.gif')); ?>" alt="Gold Points" width="18" style="vertical-align:middle;">
            <?php echo e(number_format($item->price)); ?>

            <span class="pw-shop-card__price-unit">Gold Points</span>
        </div>
    </div>

    
    <div class="pw-shop-card__foot">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->stock !== null && $item->stock <= 0): ?>
        <button class="pw-shop-card__btn pw-shop-card__btn--disabled" disabled>Habis</button>
        <?php elseif(auth()->user()->money < $item->price): ?>
        <button class="pw-shop-card__btn pw-shop-card__btn--disabled" disabled>Gold Points Kurang</button>
        <?php else: ?>
        
        <div x-show="!confirm">
            <button @click="confirm = true" class="pw-shop-card__btn pw-shop-card__btn--buy">
                Beli Sekarang
            </button>
        </div>
        <div x-show="confirm" x-transition style="display:none;">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('active_character')): ?>
            <p class="pw-shop-card__confirm-text">Beli <strong><?php echo e($item->name); ?></strong> seharga <strong><?php echo e(number_format($item->price)); ?> Gold Points</strong>?
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->item_id && $item->item_id > 0): ?>
                <br><span style="font-size:.75rem;color:#4ade80;">Dikirim ke: <?php echo e(session('active_character')->name); ?></span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </p>
            <?php else: ?>
            <p class="pw-shop-card__confirm-text">Beli <strong><?php echo e($item->name); ?></strong> seharga <strong><?php echo e(number_format($item->price)); ?> Gold Points</strong>?
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->item_id && $item->item_id > 0): ?>
                <br><span style="font-size:.75rem;color:#f59e0b;">Pilih karakter di navbar dulu!</span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <div style="display:flex;gap:.4rem;">
                <form action="<?php echo e(route('shop.buy', $item->id)); ?>" method="POST" style="flex:1;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="pw-shop-card__btn pw-shop-card__btn--buy" style="width:100%;">Ya, Beli</button>
                </form>
                <button @click="confirm = false" class="pw-shop-card__btn pw-shop-card__btn--cancel">Batal</button>
            </div>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

</div><?php /**PATH /var/www/pw-panel/resources/views/front/shop/_item_card.blade.php ENDPATH**/ ?>