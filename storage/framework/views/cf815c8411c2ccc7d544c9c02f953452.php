<?php
    $__siteName = \App\Models\Setting::get('seo_title') ?: config('pw-config.server.name') ?: 'Perfect World';
?>

<?php $__env->startSection('title', __('main.cubi_title') . ' &mdash; ' . $__siteName); ?>
<?php $__env->startSection('meta_description', __('main.cubi_meta')); ?>

<?php $__env->startSection('content'); ?>


<div class="pw-page-hero">
    <div class="pw-page-hero__bg" aria-hidden="true"></div>
    <canvas id="pw-coin-rain" style="position:absolute;inset:0;width:100%;height:100%;pointer-events:none;z-index:0;" aria-hidden="true"></canvas>
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
            <?php echo e(__('main.cubi_title')); ?>

        </h1>
        <p class="pw-page-hero__sub"><?php echo __('main.cubi_subtitle'); ?></p>
        <nav class="pw-breadcrumb" aria-label="breadcrumb">
            <a href="<?php echo e(route('home')); ?>" class="pw-breadcrumb__item">
                <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true">
                    <path d="M2 7.5L8 2l6 5.5V14a1 1 0 01-1 1h-3.5v-3.5h-3V15H3a1 1 0 01-1-1V7.5z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/>
                </svg>
                <?php echo e(__('main.cubi_breadcrumb_home')); ?>

            </a>
            <span class="pw-breadcrumb__sep" aria-hidden="true">/</span>
            <span class="pw-breadcrumb__item pw-breadcrumb__item--active"><?php echo e(__('main.cubi_title')); ?></span>
        </nav>
    </div>
</div>


<section class="pw-section">
    <div class="pw-section__inner pw-section__inner--narrow">

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
        <div class="pw-alert pw-alert--error" role="alert">
            <svg viewBox="0 0 16 16" fill="none" width="16" aria-hidden="true"><circle cx="8" cy="8" r="7" stroke="currentColor" stroke-width="1.3"/><path d="M8 5v3M8 10.5v.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
            <?php echo e(session('error')); ?>

        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <form method="POST" action="<?php echo e(route('cubi-shop.invoice')); ?>" id="cubi-form"
              x-data="cubiShop()" @submit.prevent="submitForm()">
            <?php echo csrf_field(); ?>

            
            <div class="pw-card" style="margin-bottom:2rem;padding:1.2rem 1.5rem;border:1px solid rgba(200,151,42,.15);background:rgba(200,151,42,.04);border-radius:.75rem;">
                <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:.8rem;">
                    <svg viewBox="0 0 20 20" fill="none" width="18"><path d="M17 10l-4-7H7L3 10l4 7h6l4-7z" stroke="#c8972a" stroke-width="1.3"/><circle cx="10" cy="10" r="2.5" stroke="#c8972a" stroke-width="1.3"/></svg>
                    <span style="font-weight:600;font-size:.92rem;color:#c8972a;"><?php echo e(__('main.cubi_has_discount')); ?></span>
                    <span style="font-size:.75rem;color:var(--pw-text-muted);"><?php echo __('main.cubi_get_discount', ['percent' => $discountPercent]); ?></span>
                </div>
                <div style="display:flex;gap:.5rem;align-items:stretch;">
                    <input type="text" name="refcode" class="pw-form__input" placeholder="Masukkan kode diskon partner..."
                           x-model="refcode" maxlength="30"
                           style="flex:1;font-size:.88rem;text-transform:uppercase;letter-spacing:.05em;">
                    <button type="button" class="pw-btn pw-btn--ghost pw-btn--sm" @click="validateRefcode()"
                            :disabled="refcodeLoading || !refcode"
                            style="white-space:nowrap;padding:.5rem 1rem;">
                        <span x-show="!refcodeLoading"><?php echo e(__('main.cubi_check_code')); ?></span>
                        <span x-show="refcodeLoading"><?php echo e(__('main.cubi_checking')); ?></span>
                    </button>
                </div>
                
                <div x-show="refcodeResult" x-cloak style="margin-top:.6rem;font-size:.82rem;">
                    <template x-if="refcodeValid">
                        <div style="color:#7deba0;display:flex;align-items:center;gap:.4rem;">
                            <svg viewBox="0 0 14 14" fill="none" width="13"><circle cx="7" cy="7" r="6" fill="#4ade80"/><path d="M4 7l2 2 4-4" stroke="#0a0a14" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <span x-text="'<?php echo e(__('main.cubi_code_valid')); ?>' + refcodePartner + '<?php echo e(__('main.cubi_discount_label')); ?>' + refcodeDiscount + '%'"></span>
                        </div>
                    </template>
                    <template x-if="!refcodeValid && refcodeResult">
                        <div style="color:#fca5a5;display:flex;align-items:center;gap:.4rem;">
                            <svg viewBox="0 0 14 14" fill="none" width="13"><circle cx="7" cy="7" r="6" stroke="#fca5a5" stroke-width="1.3"/><path d="M5 5l4 4M9 5l-4 4" stroke="#fca5a5" stroke-width="1.3" stroke-linecap="round"/></svg>
                            <span x-text="refcodeMessage"></span>
                        </div>
                    </template>
                </div>
            </div>

            
            <div class="pw-donate-section-title">
                <svg viewBox="0 0 20 20" fill="none" width="20"><path d="M17 10l-4-7H7L3 10l4 7h6l4-7z" stroke="#c8972a" stroke-width="1.3"/><circle cx="10" cy="10" r="2.5" stroke="#c8972a" stroke-width="1.3"/></svg>
                <?php echo e(__('main.cubi_buy_cubi')); ?>

            </div>

            
            <div style="text-align:center;padding:.5rem 0;">

                
                <div style="margin-bottom:.3rem;">
                    <img :src="coinIcon" alt="Cubi Coin" width="200" height="200" style="display:inline-block;">
                </div>

                
                <div x-show="cubiInput > 0" x-cloak>
                    <div style="font-size:1.6rem;font-weight:700;color:#c8972a;font-family:'Cinzel',serif;line-height:1.2;" x-text="cubiInput.toLocaleString('id-ID')"></div>
                    <div style="font-size:.78rem;color:var(--pw-text-muted);margin-top:.1rem;"><?php echo e(__('main.cubi_coin_label')); ?></div>
                </div>
                <div x-show="!cubiInput || cubiInput <= 0" style="font-size:.88rem;color:var(--pw-text-muted);"><?php echo e(__('main.cubi_enter_amount')); ?></div>

                
                <div style="max-width:360px;margin:1.2rem auto 0;">
                    <label style="font-weight:600;font-size:.82rem;color:var(--pw-text);display:block;margin-bottom:.4rem;text-align:left;"><?php echo e(__('main.cubi_amount_label')); ?></label>
                    <div style="display:flex;align-items:stretch;gap:0;border:1px solid rgba(200,151,42,.3);border-radius:.6rem;overflow:hidden;background:transparent;">
                        
                        <button type="button" @click="cubiInput = Math.max((cubiInput || 0) - 1, 0); calcFromCubi()"
                                style="width:52px;flex-shrink:0;background:rgba(200,151,42,.08);border:none;color:#c8972a;font-size:1.4rem;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background .15s;"
                                onmouseover="this.style.background='rgba(200,151,42,.18)'" onmouseout="this.style.background='rgba(200,151,42,.08)'">&minus;</button>
                        
                        <input type="number" name="cubi_input" class="pw-no-spinner" x-model.number="cubiInput"
                               min="<?php echo e(intdiv($minPurchase, $cubiRate)); ?>" step="1" required
                               @input="calcFromCubi()"
                               placeholder="<?php echo e(intdiv($minPurchase, $cubiRate)); ?>"
                               style="flex:1;font-size:1.1rem;font-weight:700;letter-spacing:.02em;text-align:center;border:none;border-radius:0;background:transparent;color:var(--pw-text,#fff);outline:none;padding:.6rem .5rem;appearance:none;-webkit-appearance:none;-moz-appearance:textfield;">
                        
                        <button type="button" @click="cubiInput = (cubiInput || 0) + 1; calcFromCubi()"
                                style="width:52px;flex-shrink:0;background:rgba(200,151,42,.08);border:none;color:#c8972a;font-size:1.4rem;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background .15s;"
                                onmouseover="this.style.background='rgba(200,151,42,.18)'" onmouseout="this.style.background='rgba(200,151,42,.08)'">+</button>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:.35rem;">
                        <span style="font-size:.72rem;color:var(--pw-text-muted);"><?php echo e(__('main.cubi_min_label', ['amount' => number_format(intdiv($minPurchase, $cubiRate))])); ?></span>
                        <span style="font-size:.72rem;color:var(--pw-text-muted);"><?php echo e(__('main.cubi_rate_label', ['rate' => number_format($cubiRate)])); ?></span>
                    </div>
                </div>

                
                <div x-show="cubiInput >= minCubi" x-cloak style="margin-top:1.2rem;">
                    <div x-show="bonusCubi > 0" style="margin-bottom:.5rem;">
                        <span style="font-size:1rem;font-weight:700;color:#6ee7b7;background:rgba(56,161,105,.12);border:1px solid rgba(56,161,105,.2);border-radius:20px;padding:4px 14px;">
                            +<span x-text="bonusCubi.toLocaleString('id-ID')"></span> <?php echo e(__('main.cubi_bonus')); ?>

                        </span>
                    </div>
                    <div style="font-size:1.2rem;font-weight:700;color:#c8972a;">
                        <?php echo e(__('main.cubi_total')); ?>: <span x-text="totalCubi.toLocaleString('id-ID')"></span> Cubi
                    </div>
                    <div style="font-size:1.05rem;color:var(--pw-text-muted);margin-top:.3rem;">
                        <?php echo e(__('main.cubi_price')); ?>: <span style="font-weight:700;color:var(--pw-text);" x-text="formatPrice(amount)"></span>
                    </div>
                </div>

                <div x-show="cubiInput > 0 && cubiInput < minCubi" x-cloak
                     style="color:#fca5a5;font-size:.82rem;margin-top:.8rem;display:inline-flex;align-items:center;gap:.4rem;">
                    <svg viewBox="0 0 14 14" fill="none" width="13"><circle cx="7" cy="7" r="6" stroke="#fca5a5" stroke-width="1.3"/><path d="M7 4.5v3M7 9.5v.5" stroke="#fca5a5" stroke-width="1.3" stroke-linecap="round"/></svg>
                    <?php echo e(__('main.cubi_min_purchase', ['amount' => number_format(intdiv($minPurchase, $cubiRate))])); ?>

                </div>
            </div>
            <input type="hidden" name="amount" :value="amount">

            
            <div style="margin-top:1.5rem;text-align:center;">
                <div class="pw-donate-section-title" style="justify-content:center;">
                    <svg viewBox="0 0 20 20" fill="none" width="20" aria-hidden="true"><circle cx="10" cy="10" r="8" stroke="#c8972a" stroke-width="1.3"/><path d="M6 8.5c.5-2 3.5-2.5 4-.5.5 2-2.5 1.5-2 3.5M10 14v.5" stroke="#c8972a" stroke-width="1.3" stroke-linecap="round"/></svg>
                    <?php echo e(__('main.cubi_currency')); ?>

                </div>
                <div style="display:flex;flex-wrap:wrap;gap:.5rem;justify-content:center;margin-bottom:.6rem;">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $currencyRates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <button type="button"
                            @click="setCurrency('<?php echo e($code); ?>')"
                            class="pw-currency-btn"
                            style="display:inline-flex;flex-direction:column;align-items:center;justify-content:center;gap:.3rem;padding:.5rem .8rem;border-radius:.6rem;border:1px solid rgba(200,151,42,.15);background:rgba(200,151,42,.03);color:var(--pw-text-muted);font-size:.82rem;cursor:pointer;transition:all .2s;min-width:52px;"
                            :style="currency === '<?php echo e($code); ?>' ? 'border-color:#c8972a;background:rgba(200,151,42,.15);color:#c8972a;font-weight:700;box-shadow:0 0 12px rgba(200,151,42,.15);' : ''">
                        <img src="https://flagcdn.com/w40/<?php echo e($info['country']); ?>.png"
                             srcset="https://flagcdn.com/w80/<?php echo e($info['country']); ?>.png 2x"
                             alt="<?php echo e($code); ?>"
                             style="width:28px;height:19px;object-fit:cover;border-radius:2px;box-shadow:0 0 0 1px rgba(255,255,255,.12);pointer-events:none;">
                        <span style="letter-spacing:.03em;font-size:.72rem;text-align:center;pointer-events:none;"><?php echo e($code); ?></span>
                    </button>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
                <div style="font-size:.72rem;color:var(--pw-text-muted);display:inline-flex;align-items:center;gap:.3rem;justify-content:center;">
                    <svg viewBox="0 0 12 12" fill="none" width="10"><circle cx="6" cy="6" r="5" stroke="currentColor" stroke-width="1"/><path d="M6 4v3M6 8.5v.5" stroke="currentColor" stroke-width="1" stroke-linecap="round"/></svg>
                    <?php echo __('main.cubi_currency_note'); ?>

                    <template x-if="currency !== 'IDR'">
                        <span>&mdash; <span x-text="rateInfoText"></span></span>
                    </template>
                </div>
            </div>

            
            <div class="pw-donate-section-title" style="margin-top:2rem;">
                <svg viewBox="0 0 20 20" fill="none" width="20" aria-hidden="true"><rect x="2" y="5" width="16" height="11" rx="2" stroke="#c8972a" stroke-width="1.3"/><path d="M2 9h16" stroke="#c8972a" stroke-width="1.3"/></svg>
                <?php echo e(__('main.cubi_payment_method')); ?>

            </div>
            <div class="pw-donate-grid pw-donate-channel-grid" style="grid-template-columns:1fr 1fr;gap:.75rem;">
                
                <label class="pw-donate-pkg-wrap">
                    <input type="radio" name="channel_type" value="qris" x-model="channel" required>
                    <div class="pw-donate-pkg pw-donate-channel" :class="channel === 'qris' ? 'is-selected' : ''">
                        <img src="<?php echo e(asset('images/qris.webp')); ?>" alt="QRIS" style="width:52px;height:auto;flex-shrink:0;object-fit:contain;">
                        <div class="pw-donate-channel__info">
                            <div class="pw-donate-channel__name">QRIS</div>
                            <div class="pw-donate-channel__desc"><?php echo e(__('main.cubi_qris_desc')); ?></div>
                        </div>
                        <div class="pw-donate-pkg__check" aria-hidden="true">
                            <svg viewBox="0 0 14 14" fill="none" width="13"><circle cx="7" cy="7" r="6" fill="#c8972a"/><path d="M4 7l2 2 4-4" stroke="#0a0a14" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                    </div>
                </label>
                
                <label class="pw-donate-pkg-wrap">
                    <input type="radio" name="channel_type" value="dana" x-model="channel" required>
                    <div class="pw-donate-pkg pw-donate-channel" :class="channel === 'dana' ? 'is-selected' : ''">
                        <img src="<?php echo e(asset('images/dana.webp')); ?>" alt="DANA" style="width:52px;height:auto;flex-shrink:0;object-fit:contain;">
                        <div class="pw-donate-channel__info">
                            <div class="pw-donate-channel__name">DANA</div>
                            <div class="pw-donate-channel__desc"><?php echo e(__('main.cubi_dana_desc')); ?></div>
                        </div>
                        <div class="pw-donate-pkg__check" aria-hidden="true">
                            <svg viewBox="0 0 14 14" fill="none" width="13"><circle cx="7" cy="7" r="6" fill="#c8972a"/><path d="M4 7l2 2 4-4" stroke="#0a0a14" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                    </div>
                </label>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['channel_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <p style="color:#fca5a5;font-size:.8rem;margin-top:.4rem;"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <div x-show="cubiInput >= minCubi" x-cloak style="margin-top:1.5rem;">
                <div class="pw-card" style="padding:1rem 1.2rem;border:1px solid rgba(200,151,42,.12);background:rgba(200,151,42,.03);border-radius:.75rem;">
                    <div style="font-weight:600;font-size:.85rem;color:#c8972a;margin-bottom:.6rem;"><?php echo e(__('main.cubi_order_summary')); ?></div>
                    <div style="display:flex;justify-content:space-between;font-size:.84rem;padding:.25rem 0;color:var(--pw-text-muted);">
                        <span><?php echo e(__('main.cubi_coin_label')); ?></span>
                        <span style="color:#60d0ff;font-weight:600;" x-text="cubiInput.toLocaleString('id-ID')"></span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:.84rem;padding:.25rem 0;color:#7deba0;">
                        <span><?php echo e(__('main.cubi_bonus')); ?></span>
                        <span style="font-weight:600;" x-text="'+' + bonusCubi.toLocaleString('id-ID')"></span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:.84rem;padding:.25rem 0;color:var(--pw-text-muted);font-weight:700;">
                        <span style="color:#c8972a;"><?php echo e(__('main.cubi_total')); ?> Cubi</span>
                        <span style="color:#c8972a;" x-text="totalCubi.toLocaleString('id-ID') + ' Cubi'"></span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:.84rem;padding:.35rem 0;margin-top:.3rem;border-top:1px solid rgba(255,255,255,.06);color:var(--pw-text-muted);">
                        <span><?php echo e(__('main.cubi_price')); ?></span>
                        <span x-text="formatPrice(amount)"></span>
                    </div>
                    <template x-if="refcodeValid">
                        <div>
                            <div style="display:flex;justify-content:space-between;font-size:.84rem;padding:.25rem 0;color:#7deba0;">
                                <span><?php echo e(__('main.cubi_discount')); ?> (<span x-text="refcodeDiscount"></span>%)</span>
                                <span x-text="'- ' + formatPrice(discountAmount)"></span>
                            </div>
                            <div style="display:flex;justify-content:space-between;font-size:.84rem;padding:.25rem 0;color:var(--pw-text-muted);font-size:.75rem;">
                                <span><?php echo e(__('main.cubi_referral_code')); ?></span>
                                <span x-text="refcode" style="text-transform:uppercase;color:#c8972a;"></span>
                            </div>
                        </div>
                    </template>
                    <div style="display:flex;justify-content:space-between;font-size:.95rem;padding:.5rem 0 0;margin-top:.4rem;border-top:1px solid rgba(255,255,255,.08);font-weight:700;">
                        <span><?php echo e(__('main.cubi_total_pay')); ?></span>
                        <span style="color:#c8972a;" x-text="formatPrice(finalPrice)"></span>
                    </div>
                </div>
            </div>

            
            <div style="margin-top:1.8rem;">
                <button type="submit" class="pw-btn pw-btn--gold pw-btn--lg"
                        :disabled="!cubiInput || cubiInput < minCubi || !channel"
                        style="width:100%;justify-content:center;">
                    <svg viewBox="0 0 20 20" fill="none" width="16"><path d="M17 10l-4-7H7L3 10l4 7h6l4-7z" stroke="currentColor" stroke-width="1.3"/><circle cx="10" cy="10" r="2.5" stroke="currentColor" stroke-width="1.3"/></svg>
                    <?php echo e(__('main.cubi_buy_btn')); ?>

                </button>
            </div>

            
            <div style="margin-top:1.2rem;display:flex;gap:.7rem;align-items:flex-start;padding:.85rem 1rem;background:rgba(234,179,8,.07);border:1px solid rgba(234,179,8,.25);border-radius:.6rem;">
                <svg viewBox="0 0 20 20" fill="none" width="18" style="flex-shrink:0;margin-top:.05rem;color:#eab308;"><circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.5"/><path d="M10 6.5v4M10 12.5v.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
                <div style="font-size:.82rem;line-height:1.55;color:var(--pw-text-muted);">
                    <strong style="color:#eab308;display:block;margin-bottom:.2rem;"><?php echo __('main.cubi_notice_title'); ?></strong>
                    <?php echo __('main.cubi_notice_text'); ?>

                </div>
            </div>

            
            <div class="pw-donate-info-bar">
                <div class="pw-donate-info-card">
                    <div class="pw-donate-info-card__title">
                        <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true">
                            <circle cx="8" cy="8" r="7" stroke="#c8972a" stroke-width="1.2"/>
                            <path d="M8 7v4M8 5v.5" stroke="#c8972a" stroke-width="1.3" stroke-linecap="round"/>
                        </svg>
                        <?php echo e(__('main.cubi_how_to_buy')); ?>

                    </div>
                    <ol class="pw-donate-steps">
                        <li><?php echo __('main.cubi_step_1'); ?></li>
                        <li><?php echo __('main.cubi_step_2', ['min' => number_format($minPurchase)]); ?></li>
                        <li><?php echo __('main.cubi_step_3'); ?></li>
                        <li><?php echo __('main.cubi_step_4'); ?></li>
                        <li><?php echo __('main.cubi_step_5'); ?></li>
                        <li><?php echo __('main.cubi_step_6'); ?></li>
                    </ol>
                </div>
                <div class="pw-donate-info-card">
                    <div class="pw-donate-info-card__title">
                        <svg viewBox="0 0 16 16" fill="none" width="13" aria-hidden="true">
                            <path d="M8 1l1.4 4.3H14l-3.6 2.6 1.4 4.3L8 9.6l-3.8 2.6 1.4-4.3L2 5.3h4.6L8 1z" stroke="#c8972a" stroke-width="1.2" stroke-linejoin="round"/>
                        </svg>
                        <?php echo e(__('main.cubi_bonus_title')); ?>

                    </div>
                    <ul class="pw-donate-steps" style="list-style:disc;padding-left:1.2rem;">
                        <li><?php echo __('main.cubi_bonus_info', ['multiple' => number_format($bonusMultiple), 'amount' => number_format($bonusAmount)]); ?></li>
                        <li><?php echo __('main.cubi_bonus_multiply'); ?></li>
                        <li><?php echo e(__('main.cubi_bonus_refcode')); ?></li>
                    </ul>
                </div>
            </div>
        </form>

    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<style>
/* Hide native number input spinners */
input.pw-no-spinner::-webkit-outer-spin-button,
input.pw-no-spinner::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
input.pw-no-spinner { -moz-appearance: textfield; }
/* Currency buttons */
.pw-currency-btn { cursor: pointer !important; }
.pw-currency-btn:hover { border-color: #c8972a !important; background: rgba(200,151,42,.1) !important; }
</style>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('cubiShop', () => ({
        cubiInput: null,
        amount: 0,
        channel: '',
        refcode: '',
        refcodeLoading: false,
        refcodeResult: false,
        refcodeValid: false,
        refcodePartner: '',
        refcodeDiscount: 0,
        refcodeMessage: '',

        // Currency conversion
        currency: 'IDR',
        currencyRates: <?php echo json_encode($currencyRates, 15, 512) ?>,

        // Config from server
        cubiRate: <?php echo e($cubiRate); ?>,
        bonusMultiple: <?php echo e($bonusMultiple); ?>,
        bonusPerMultiple: <?php echo e($bonusAmount); ?>,
        minCubi: <?php echo e(intdiv($minPurchase, $cubiRate)); ?>,

        // Computed
        multiples: 0,
        bonusCubi: 0,
        totalCubi: 0,

        get coinIcon() {
            const c = this.cubiInput || 0;
            const base = '<?php echo e(asset("images/gif_icon")); ?>/';
            if (c >= 1000) return base + '1000.gif';
            if (c >= 500)  return base + '500.gif';
            if (c >= 100)  return base + '100.gif';
            return base + '50.gif';
        },

        setCurrency(code) {
            this.currency = code;
        },

        convertFromIDR(idrAmount) {
            if (this.currency === 'IDR' || !idrAmount) return idrAmount;
            const info = this.currencyRates[this.currency];
            if (!info || !info.rate) return idrAmount;
            return idrAmount / info.rate;
        },

        formatPrice(idrAmount) {
            if (!idrAmount) return 'Rp 0';
            if (this.currency === 'IDR') {
                return 'Rp ' + idrAmount.toLocaleString('id-ID');
            }
            const info = this.currencyRates[this.currency];
            if (!info) return 'Rp ' + idrAmount.toLocaleString('id-ID');
            const converted = idrAmount / info.rate;
            const decimals = info.decimals || 0;
            const formatted = converted.toLocaleString('en-US', {
                minimumFractionDigits: decimals,
                maximumFractionDigits: decimals
            });
            return info.symbol + ' ' + formatted;
        },

        get rateInfoText() {
            if (this.currency === 'IDR') return '';
            const info = this.currencyRates[this.currency];
            if (!info) return '';
            return '1 ' + this.currency + ' ≈ Rp ' + info.rate.toLocaleString('id-ID');
        },

        calcFromCubi() {
            if (!this.cubiInput || this.cubiInput < this.minCubi) {
                this.amount = 0;
                this.multiples = 0;
                this.bonusCubi = 0;
                this.totalCubi = 0;
                return;
            }
            this.amount = this.cubiInput * this.cubiRate;
            this.multiples = Math.floor(this.cubiInput / this.bonusMultiple);
            this.bonusCubi = this.multiples * this.bonusPerMultiple;
            this.totalCubi = this.cubiInput + this.bonusCubi;
        },

        get discountAmount() {
            if (!this.refcodeValid || !this.amount) return 0;
            return Math.floor(this.amount * this.refcodeDiscount / 100);
        },

        get finalPrice() {
            if (!this.amount) return 0;
            return parseInt(this.amount) - this.discountAmount;
        },

        async validateRefcode() {
            if (!this.refcode) return;
            this.refcodeLoading = true;
            this.refcodeResult = false;

            try {
                const res = await fetch('<?php echo e(route("cubi-shop.validate-refcode")); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ refcode: this.refcode })
                });
                const data = await res.json();
                this.refcodeResult = true;
                this.refcodeValid = data.valid;
                this.refcodePartner = data.partner || '';
                this.refcodeDiscount = data.discount || 0;
                this.refcodeMessage = data.message || '';
            } catch (err) {
                this.refcodeResult = true;
                this.refcodeValid = false;
                this.refcodeMessage = <?php echo json_encode(__('main.cubi_validate_fail'), 15, 512) ?>;
            } finally {
                this.refcodeLoading = false;
            }
        },

        submitForm() {
            if (!this.cubiInput || this.cubiInput < this.minCubi) return;
            if (!this.channel) return;
            if (!this.refcodeValid) {
                const refInput = this.$el.querySelector('input[name=refcode]');
                if (refInput) refInput.value = '';
            }
            this.$el.submit();
        }
    }));
});
</script>


<script>
(function () {
    const canvas = document.getElementById('pw-coin-rain');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    let W, H, coins = [];

    const GOLD = ['#fbbf24','#f59e0b','#fcd34d','#c8972a','#e8b84b','#d4a437'];

    function resize() {
        W = canvas.width = canvas.offsetWidth;
        H = canvas.height = canvas.offsetHeight;
    }

    function makeCoin() {
        return {
            x: Math.random() * W,
            y: Math.random() * H - H,
            r: Math.random() * 5 + 4,
            speed: Math.random() * 0.8 + 0.3,
            wobble: Math.random() * Math.PI * 2,
            wobbleSpeed: Math.random() * 0.03 + 0.01,
            squeeze: Math.random() * Math.PI * 2,
            squeezeSpeed: Math.random() * 0.04 + 0.02,
            color: GOLD[Math.floor(Math.random() * GOLD.length)],
            opacity: Math.random() * 0.5 + 0.3,
            shine: Math.random() * 0.3
        };
    }

    function init() {
        resize();
        coins = [];
        for (let i = 0; i < 40; i++) {
            const c = makeCoin();
            c.y = Math.random() * H;
            coins.push(c);
        }
    }

    function draw() {
        ctx.clearRect(0, 0, W, H);
        coins.forEach(c => {
            ctx.save();
            ctx.globalAlpha = c.opacity;
            const scaleX = Math.abs(Math.cos(c.squeeze));
            const cx = c.x + Math.sin(c.wobble) * 15;

            // Coin body (ellipse to simulate 3D rotation)
            ctx.beginPath();
            ctx.ellipse(cx, c.y, c.r * scaleX, c.r, 0, 0, Math.PI * 2);
            ctx.fillStyle = c.color;
            ctx.fill();

            // Coin edge (thin line for 3D depth)
            if (scaleX > 0.15) {
                ctx.beginPath();
                ctx.ellipse(cx, c.y, c.r * scaleX, c.r, 0, 0, Math.PI * 2);
                ctx.strokeStyle = 'rgba(0,0,0,.2)';
                ctx.lineWidth = 0.5;
                ctx.stroke();

                // Shine highlight
                ctx.beginPath();
                ctx.ellipse(cx - c.r * scaleX * 0.2, c.y - c.r * 0.2, c.r * scaleX * 0.3, c.r * 0.35, -0.3, 0, Math.PI * 2);
                ctx.fillStyle = 'rgba(255,255,255,' + c.shine + ')';
                ctx.fill();
            }

            ctx.restore();
        });
    }

    function update() {
        coins.forEach(c => {
            c.y += c.speed;
            c.wobble += c.wobbleSpeed;
            c.squeeze += c.squeezeSpeed;
            if (c.y > H + 15) {
                c.y = -15;
                c.x = Math.random() * W;
            }
        });
    }

    function loop() { draw(); update(); requestAnimationFrame(loop); }
    window.addEventListener('resize', resize);
    init();
    loop();
})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/pw-panel/resources/views/front/cubi-shop/index.blade.php ENDPATH**/ ?>