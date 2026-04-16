<?php $__env->startSection('title', 'Edit: ' . ($liveData['base']['name'] ?? 'Unknown')); ?>

<?php $__env->startPush('styles'); ?>
<style>
.ed-field { display:flex; flex-direction:column; gap:3px; margin-bottom:.6rem; }
.ed-field:last-child { margin-bottom:0; }
.ed-label { font-size:.72rem; color:var(--pw-text-muted); font-weight:600; text-transform:uppercase; letter-spacing:.04em; }
.ed-input {
    padding:.38rem .6rem; border-radius:5px; font-size:.83rem;
    border:1px solid rgba(255,255,255,.1); background:rgba(255,255,255,.04);
    color:var(--pw-text-light); width:100%; box-sizing:border-box; transition:border-color .15s;
}
.ed-input:focus { outline:none; border-color:rgba(240,165,0,.45); background:rgba(255,255,255,.06); }
.ed-dd-btn {
    width:100%; display:flex; align-items:center; justify-content:space-between;
    background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.12);
    border-radius:6px; padding:.38rem .65rem; color:var(--pw-text-light);
    font-size:.83rem; cursor:pointer; transition:border-color .15s; text-align:left;
}
.ed-dd-btn:hover { border-color:rgba(255,255,255,.25); }
.ed-dd-btn:focus { outline:none; border-color:rgba(240,165,0,.5); box-shadow:0 0 0 2px rgba(240,165,0,.12); }
.ed-dd-menu {
    position:absolute; top:calc(100% + 4px); left:0; right:0; z-index:50;
    background:var(--pw-bg-card); border:1px solid var(--pw-border); border-radius:8px;
    padding:4px; max-height:220px; overflow-y:auto; box-shadow:0 8px 24px rgba(0,0,0,.5);
}
.ed-dd-menu::-webkit-scrollbar { width:5px; }
.ed-dd-menu::-webkit-scrollbar-track { background:transparent; }
.ed-dd-menu::-webkit-scrollbar-thumb { background:rgba(255,255,255,.12); border-radius:3px; }
.ed-dd-item {
    display:block; width:100%; text-align:left; padding:.38rem .65rem;
    background:transparent; border:none; color:#cbd5e1; font-size:.82rem;
    cursor:pointer; border-radius:5px; transition:background .1s, color .1s;
}
.ed-dd-item:hover { background:rgba(240,165,0,.12); color:#fff; }
.ed-dd-item--active { background:rgba(240,165,0,.18); color:#f0a500; font-weight:600; }
[data-theme="light"] .ed-dd-btn { background:#fff; border-color:rgba(0,0,0,.2); color:var(--pw-text); }
[data-theme="light"] .ed-dd-menu { box-shadow:0 8px 24px rgba(0,0,0,.12); }
[data-theme="light"] .ed-dd-item { color:var(--pw-text); }
[data-theme="light"] .ed-dd-item:hover { background:rgba(138,94,0,.1); }
[data-theme="light"] .ed-dd-item--active { background:rgba(138,94,0,.12); color:var(--pw-gold); }
.ed-row2 { display:grid; grid-template-columns:1fr 1fr; gap:.5rem; }
.ed-hint { font-size:.68rem; color:var(--pw-text-muted); }
.ed-stat { display:flex; justify-content:space-between; align-items:center;
    padding:.28rem .4rem; border-radius:4px; background:rgba(255,255,255,.025);
    font-size:.79rem; margin-bottom:.3rem; }
.ed-stat:last-child { margin-bottom:0; }
.ed-stat-k { color:var(--pw-text-muted); font-size:.75rem; }
.ed-stat-v { color:var(--pw-text-light); font-weight:600; }
.ed-sec { font-size:.68rem; font-weight:700; letter-spacing:.07em;
    color:var(--pw-text-muted); text-transform:uppercase; margin:.7rem 0 .35rem; }
.ed-sec:first-child { margin-top:0; }
.ed-btn {
    display:inline-flex; align-items:center; gap:4px;
    padding:.28rem .6rem; border-radius:5px; font-size:.78rem; font-weight:600;
    border:1px solid rgba(255,255,255,.12); background:rgba(255,255,255,.05);
    color:var(--pw-text-muted); cursor:pointer; text-decoration:none; transition:all .15s; line-height:1.4;
}
.ed-btn:hover { background:rgba(255,255,255,.09); color:var(--pw-text-light); }
.ed-btn-save { border-color:rgba(80,200,120,.4); background:rgba(80,200,120,.12); color:#50c878; }
.ed-btn-save:hover { background:rgba(80,200,120,.22); }
.ed-btn-cancel { border-color:rgba(239,68,68,.35); background:rgba(239,68,68,.08); color:#ef4444; }
.ed-btn-cancel:hover { background:rgba(239,68,68,.16); }
[data-theme="light"] .ed-input { background:#fff; border-color:#cbd5e1; color:#0f172a; }
[data-theme="light"] .ed-select { background:#fff; border-color:#cbd5e1; color:#0f172a; }
[data-theme="light"] .ed-stat { background:#f8fafc; }
[data-theme="light"] .ed-btn { background:#f1f5f9; border-color:#cbd5e1; color:#475569; }
[data-theme="light"] .ed-btn:hover { background:#e2e8f0; color:#1e293b; }
[data-theme="light"] .ed-dd-btn:hover { border-color:rgba(0,0,0,.3); }/* Custom dropdown */
.ed-dd-btn {
    width:100%; display:flex; align-items:center; justify-content:space-between;
    padding:.38rem .6rem; border-radius:5px; font-size:.83rem;
    border:1px solid rgba(255,255,255,.1); background:rgba(255,255,255,.04);
    color:var(--pw-text-light); cursor:pointer; transition:border-color .15s; text-align:left;
}
.ed-dd-btn:hover { border-color:rgba(255,255,255,.25); }
.ed-dd-btn:focus { outline:none; border-color:rgba(240,165,0,.45); }
.ed-dd-menu {
    position:absolute; top:calc(100% + 4px); left:0; right:0; z-index:50;
    background:var(--pw-bg-card); border:1px solid var(--pw-border); border-radius:7px;
    padding:4px; max-height:220px; overflow-y:auto;
    box-shadow:0 8px 24px rgba(0,0,0,.5);
}
.ed-dd-item {
    display:block; width:100%; text-align:left; padding:.35rem .6rem;
    background:transparent; border:none; color:#cbd5e1; font-size:.82rem;
    cursor:pointer; border-radius:5px; transition:background .1s,color .1s;
}
.ed-dd-item:hover { background:rgba(240,165,0,.12); color:#fff; }
.ed-dd-item--active { background:rgba(240,165,0,.18); color:#f0a500; font-weight:600; }
[data-theme="light"] .ed-dd-btn { background:#fff; border-color:#cbd5e1; color:#0f172a; }
[data-theme="light"] .ed-dd-menu { box-shadow:0 8px 24px rgba(0,0,0,.12); }
[data-theme="light"] .ed-dd-item { color:var(--pw-text); }
[data-theme="light"] .ed-dd-item:hover { background:rgba(138,94,0,.1); }
[data-theme="light"] .ed-dd-item--active { background:rgba(138,94,0,.12); color:var(--pw-gold); font-weight:600; }</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $base   = $liveData['base'] ?? [];
    $status = $liveData['status'] ?? [];
    $pocket = $liveData['pocket'] ?? [];
    $store  = $liveData['storehouse'] ?? [];
    $prop   = $status['property'] ?? [];

    $roleName = $base['name'] ?? 'Unknown';
    $roleOcc  = $base['cls'] ?? 0;
    $roleRace = $base['race'] ?? 0;
    $roleLevel= $status['level'] ?? '?';

    $cultivationLabels = [
        0  => '0 – Inchoation',
        1  => '9 – Autoscopy',
        2  => '19 – Transform',
        3  => '29 – Naissance',
        4  => '39 – Reborn',
        5  => '49 – Vigilance',
        6  => '59 – Doom',
        7  => '69 – Disengage',
        8  => '79 – Nirvana',
        20 => '89 – Prime Immortal',
        30 => '89 – Daimon Baresark',
        21 => '99 – Pure Immortal',
        31 => '99 – Daimon Saint',
        22 => '109 – Ether Immortal',
        32 => '109 – Daimon Elder',
    ];
?>

<form method="POST" action="<?php echo e(route('admin.roles.update', $roleId)); ?>">
<?php echo csrf_field(); ?>
<div style="display:grid;gap:.75rem;">

    
    <div class="pw-adm-card" style="padding:.6rem 1rem;">
        <div style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;">
            <img src="<?php echo e(asset('images/class/' . ($iconMap[$roleOcc] ?? 'blademaster') . '.png')); ?>" style="width:24px;height:24px;border-radius:4px;flex-shrink:0;" alt="">
            <div style="flex:1;min-width:0;">
                <span style="font-size:.9rem;font-weight:700;color:var(--pw-text-light);">Edit: <?php echo e($roleName); ?></span>
                <span style="font-size:.74rem;color:var(--pw-text-muted);margin-left:.5rem;">ID:<?php echo e($roleId); ?> &middot; Lv.<?php echo e($roleLevel); ?> <?php echo e($classMap[$roleOcc] ?? ''); ?></span>
            </div>
            <div style="display:flex;gap:.5rem;flex-shrink:0;">
                <a href="<?php echo e(route('admin.roles.show', $roleId)); ?>" class="pw-adm-btn" style="font-size:.82rem;background:rgba(239,68,68,.1);border-color:rgba(239,68,68,.35);color:#ef4444;display:inline-flex;align-items:center;gap:5px;">
                    <svg viewBox="0 0 16 16" width="13" fill="none"><path d="M10 6L6 10M6 6l4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><circle cx="8" cy="8" r="6.5" stroke="currentColor" stroke-width="1.3"/></svg>
                    Batal
                </a>
                <button type="submit" class="pw-adm-btn" style="font-size:.82rem;background:rgba(80,200,120,.12);border-color:rgba(80,200,120,.4);color:#50c878;font-weight:700;display:inline-flex;align-items:center;gap:5px;">
                    <svg viewBox="0 0 16 16" width="13" fill="none"><path d="M2 10.5V13h2.5l7-7L9 3.5l-7 7zM13.5 3.5l-1-1a.7.7 0 00-1 0l-1 1 2 2 1-1a.7.7 0 000-1z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/></svg>
                    Simpan
                </button>
            </div>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
        <div style="margin-top:.45rem;padding:.28rem .65rem;border-radius:4px;background:rgba(80,200,120,.08);border:1px solid rgba(80,200,120,.22);color:#50c878;font-size:.77rem;">
            ✔ <?php echo e(session('success')); ?>

        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
        <div style="margin-top:.45rem;padding:.3rem .65rem;border-radius:4px;background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.22);color:#ef4444;font-size:.77rem;">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?><div>✖ <?php echo e($e); ?></div><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:.75rem;align-items:start;">

        
        <div class="pw-adm-card" style="padding:.8rem 1rem;">
            <div style="font-size:.8rem;font-weight:700;color:var(--pw-text-light);margin-bottom:.55rem;">Info Karakter</div>

            <div class="ed-sec">General</div>
            <div class="ed-stat"><span class="ed-stat-k">Level</span><span class="ed-stat-v"><?php echo e($roleLevel); ?></span></div>
            <div class="ed-stat"><span class="ed-stat-k">Race</span><span class="ed-stat-v"><?php echo e($raceMap[$roleRace] ?? 'Unknown'); ?></span></div>
            <div class="ed-stat">
                <span class="ed-stat-k">Class</span>
                <span class="ed-stat-v" style="display:flex;align-items:center;gap:4px;">
                    <img src="<?php echo e(asset('images/class/' . ($iconMap[$roleOcc] ?? 'blademaster') . '.png')); ?>" width="14" height="14" style="border-radius:2px;" alt="">
                    <?php echo e($classMap[$roleOcc] ?? 'Unknown'); ?>

                </span>
            </div>
            <div class="ed-stat"><span class="ed-stat-k">Gender</span><span class="ed-stat-v"><?php echo e(($base['gender'] ?? 0) == 0 ? 'Male' : 'Female'); ?></span></div>

            <div class="ed-sec">Combat Stats</div>
            <div class="ed-stat"><span class="ed-stat-k">HP Max</span><span class="ed-stat-v"><?php echo e(number_format($prop['max_hp'] ?? 0)); ?></span></div>
            <div class="ed-stat"><span class="ed-stat-k">MP Max</span><span class="ed-stat-v"><?php echo e(number_format($prop['max_mp'] ?? 0)); ?></span></div>
            <div class="ed-stat"><span class="ed-stat-k">P-Def</span><span class="ed-stat-v"><?php echo e(number_format($prop['defense'] ?? 0)); ?></span></div>
            <div class="ed-stat"><span class="ed-stat-k">P-Atk</span><span class="ed-stat-v"><?php echo e(number_format($prop['damage_low'] ?? 0)); ?>–<?php echo e(number_format($prop['damage_high'] ?? 0)); ?></span></div>
            <div class="ed-stat"><span class="ed-stat-k">M-Atk</span><span class="ed-stat-v"><?php echo e(number_format($prop['damage_magic_low'] ?? 0)); ?>–<?php echo e(number_format($prop['damage_magic_high'] ?? 0)); ?></span></div>
            <div class="ed-stat"><span class="ed-stat-k">Run Speed</span><span class="ed-stat-v"><?php echo e(number_format($prop['run_speed'] ?? 0, 2)); ?></span></div>

            <div class="ed-sec">Attributes</div>
            <div class="ed-stat"><span class="ed-stat-k">CON</span><span class="ed-stat-v"><?php echo e($prop['vitality'] ?? 0); ?></span></div>
            <div class="ed-stat"><span class="ed-stat-k">INT</span><span class="ed-stat-v"><?php echo e($prop['energy'] ?? 0); ?></span></div>
            <div class="ed-stat"><span class="ed-stat-k">STR</span><span class="ed-stat-v"><?php echo e($prop['strength'] ?? 0); ?></span></div>
            <div class="ed-stat"><span class="ed-stat-k">AGI</span><span class="ed-stat-v"><?php echo e($prop['agility'] ?? 0); ?></span></div>
        </div>

        
        <div class="pw-adm-card" style="padding:.8rem 1rem;">
            <div style="font-size:.8rem;font-weight:700;color:var(--pw-text-light);margin-bottom:.55rem;">Stats</div>

            <div class="ed-sec">Experience & Reputation</div>
            <div class="ed-row2">
                <div class="ed-field">
                    <label class="ed-label">Reputation</label>
                    <input type="number" name="reputation" class="ed-input" value="<?php echo e(old('reputation', $status['reputation'] ?? 0)); ?>" min="0">
                </div>
                <div class="ed-field">
                    <label class="ed-label">EXP</label>
                    <input type="number" name="exp" class="ed-input" value="<?php echo e(old('exp', $status['exp'] ?? 0)); ?>" min="0">
                </div>
            </div>

            <div class="ed-sec">Spirit & Vigor</div>
            <div class="ed-row2">
                <div class="ed-field">
                    <label class="ed-label">SP (Spirit)</label>
                    <input type="number" name="sp" class="ed-input" value="<?php echo e(old('sp', $status['sp'] ?? 0)); ?>" min="0">
                </div>
                <div class="ed-field">
                    <label class="ed-label">Vigor Points</label>
                    <?php ($curVigor = old('vigor', $prop['max_ap'] ?? 0)); ?>
                    <?php ($vigorOpts = [0=>'000',99=>'099',199=>'199',299=>'299',399=>'399']); ?>
                    <div x-data="{ open:false, val:<?php echo e((int)$curVigor); ?> }" style="position:relative;" @click.away="open=false">
                        <input type="hidden" name="vigor" :value="val">
                        <button @click="open=!open" type="button" class="ed-dd-btn">
                            <span x-text="{ <?php $__currentLoopData = $vigorOpts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v=>$l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($v); ?>:'<?php echo e($l); ?>', <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> }[val] || val"></span>
                            <svg viewBox="0 0 12 12" width="10" fill="none" style="opacity:.5;flex-shrink:0;transition:transform .15s" :style="open&&'transform:rotate(180deg)'"><path d="M2 4l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                        <div x-show="open" x-transition.opacity.duration.150ms class="ed-dd-menu">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $vigorOpts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v => $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <button type="button" @click="val=<?php echo e($v); ?>;open=false" class="ed-dd-item" :class="val===<?php echo e($v); ?> && 'ed-dd-item--active'"><?php echo e($l); ?></button>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ed-sec">Cultivation</div>
            <div class="ed-field">
                <?php ($curCultiv = (int)old('cultivation', $status['cultivation'] ?? 0)); ?>
                <div x-data="{ open:false, val:<?php echo e($curCultiv); ?>, labels:{ <?php $__currentLoopData = $cultivationLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v=>$l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($v); ?>:'<?php echo e($l); ?>', <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> } }" style="position:relative;" @click.away="open=false">
                    <input type="hidden" name="cultivation" :value="val">
                    <button @click="open=!open" type="button" class="ed-dd-btn">
                        <span x-text="labels[val] || val"></span>
                        <svg viewBox="0 0 12 12" width="10" fill="none" style="opacity:.5;flex-shrink:0;transition:transform .15s" :style="open&&'transform:rotate(180deg)'"><path d="M2 4l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                    <div x-show="open" x-transition.opacity.duration.150ms class="ed-dd-menu">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $cultivationLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v => $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <button type="button" @click="val=<?php echo e($v); ?>;open=false" class="ed-dd-item" :class="val===<?php echo e($v); ?> && 'ed-dd-item--active'"><?php echo e($l); ?></button>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="ed-sec">Coins</div>
            <div class="ed-row2">
                <div class="ed-field">
                    <label class="ed-label">Pocket</label>
                    <input type="number" name="pocketcoins" class="ed-input" value="<?php echo e(old('pocketcoins', $pocket['money'] ?? 0)); ?>" min="0" max="200000000">
                    <span class="ed-hint">Max 200,000,000</span>
                </div>
                <div class="ed-field">
                    <label class="ed-label">Storehouse</label>
                    <input type="number" name="storehousecoins" class="ed-input" value="<?php echo e(old('storehousecoins', $store['money'] ?? 0)); ?>" min="0" max="200000000">
                    <span class="ed-hint">Max 200,000,000</span>
                </div>
            </div>
        </div>

        
        <div class="pw-adm-card" style="padding:.8rem 1rem;">
            <div style="font-size:.8rem;font-weight:700;color:var(--pw-text-light);margin-bottom:.55rem;">Lokasi</div>

            <div class="ed-sec">World</div>
            <div class="ed-field">
                <label class="ed-label">World ID</label>
                <input type="number" name="world" class="ed-input" value="<?php echo e(old('world', $status['world_tag'] ?? 0)); ?>" min="0">
                <span class="ed-hint">Default world = 1</span>
            </div>

            <div class="ed-sec">Position</div>
            <div class="ed-field">
                <label class="ed-label">Position X</label>
                <input type="number" name="coordinateX" class="ed-input" step="0.01" value="<?php echo e(old('coordinateX', number_format($status['pos_x'] ?? 0, 1, '.', ''))); ?>">
            </div>
            <div class="ed-field">
                <label class="ed-label">Position Z</label>
                <input type="number" name="coordinateZ" class="ed-input" step="0.01" value="<?php echo e(old('coordinateZ', number_format($status['pos_z'] ?? 0, 1, '.', ''))); ?>">
            </div>
            <div class="ed-field">
                <label class="ed-label">Altitude Y</label>
                <input type="number" name="coordinateY" class="ed-input" step="0.01" value="<?php echo e(old('coordinateY', number_format($status['pos_y'] ?? 0, 1, '.', ''))); ?>">
            </div>
        </div>

    </div>
</div>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/pw-panel/resources/views/admin/roles/edit.blade.php ENDPATH**/ ?>