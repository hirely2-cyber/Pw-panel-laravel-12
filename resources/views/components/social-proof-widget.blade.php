{{-- ── Social Proof Notification Widget ── --}}
@php
    $__spLogo     = \App\Models\Setting::get('site_logo');
    $__spLogoUrl  = $__spLogo ? \Illuminate\Support\Facades\Storage::url($__spLogo) : null;
    $__spSiteName = \App\Models\Setting::get('seo_title') ?: config('pw-config.server.name') ?: 'Perfect World';
@endphp

<div id="pw-sp-wrap" style="position:fixed;bottom:1.5rem;left:1.5rem;z-index:9997;width:min(296px,calc(100vw - 2rem));pointer-events:none;" aria-live="polite" aria-atomic="true">
    <div id="pw-sp-card" style="
        background: rgba(10,8,6,.96);
        border: 1px solid rgba(166,107,66,.4);
        border-radius: 14px;
        padding: .85rem 1rem .8rem;
        box-shadow: 0 8px 40px rgba(0,0,0,.55), 0 0 0 1px rgba(166,107,66,.08), inset 0 1px 0 rgba(231,218,203,.05);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        opacity: 0;
        transform: translateX(-24px);
        transition: opacity .38s ease, transform .38s ease;
        pointer-events: all;
        position: relative;
        overflow: hidden;
    ">
        {{-- Gold shimmer line top --}}
        <div style="position:absolute;top:0;left:0;right:0;height:1px;background:linear-gradient(90deg,transparent 0%,rgba(166,107,66,.4) 20%,rgba(197,151,104,.7) 50%,rgba(196,157,109,.6) 80%,transparent 100%);"></div>

        {{-- Close button --}}
        <button id="pw-sp-close" style="position:absolute;top:.45rem;right:.55rem;background:none;border:none;color:rgba(255,255,255,.25);cursor:pointer;font-size:1rem;line-height:1;padding:.2rem .3rem;transition:color .2s;" onmouseover="this.style.color='rgba(255,255,255,.6)'" onmouseout="this.style.color='rgba(255,255,255,.25)'" aria-label="Tutup">&times;</button>

        {{-- Main row --}}
        <div style="display:flex;align-items:center;gap:.7rem;padding-right:.8rem;">
            {{-- Icon / Logo --}}
            <div id="pw-sp-icon" style="width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:background .3s,border .3s;">
            </div>
            <div style="flex:1;min-width:0;">
                <div id="pw-sp-name" style="font-size:.83rem;font-weight:700;color:#fff;line-height:1.3;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"></div>
                <div id="pw-sp-action" style="font-size:.75rem;line-height:1.4;margin-top:.1rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"></div>
            </div>
        </div>

        {{-- Footer --}}
        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:.6rem;padding-top:.5rem;border-top:1px solid rgba(166,107,66,.15);">
            <span id="pw-sp-time" style="font-size:.68rem;color:rgba(196,157,109,.45);"></span>
            <span style="display:inline-flex;align-items:center;gap:.25rem;font-size:.68rem;color:#d4a860;font-weight:600;letter-spacing:.02em;">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                Valid Terverifikasi
            </span>
        </div>
    </div>
</div>

<script>
(function () {
    const card   = document.getElementById('pw-sp-card');
    const wrap   = document.getElementById('pw-sp-wrap');
    const elIcon = document.getElementById('pw-sp-icon');
    const elName = document.getElementById('pw-sp-name');
    const elAct  = document.getElementById('pw-sp-action');
    const elTime = document.getElementById('pw-sp-time');
    const elClose= document.getElementById('pw-sp-close');
    if (!card) return;

    let events = [], idx = 0, timer = null, dismissed = false;

    const logoUrl  = @json($__spLogoUrl);
    const siteName = @json($__spSiteName);
    const isId     = (document.documentElement.lang || 'id').startsWith('id');

    const T = {
        reg_action : isId ? 'baru saja mendaftar di'              : 'just registered at',
        buy_action : isId ? 'membeli {n} Cubi Gold di'            : 'purchased {n} Cubi Gold at',
        just_now   : isId ? 'baru saja'                           : 'just now',
        min_ago    : isId ? '{n} menit yang lalu'                 : '{n}m ago',
        hr_ago     : isId ? '{n} jam yang lalu'                   : '{n}h ago',
    };

    function timeAgo(ts) {
        const diff = Math.floor(Date.now() / 1000) - ts;
        if (diff < 90)   return T.just_now;
        if (diff < 3600) return T.min_ago.replace('{n}', Math.floor(diff / 60));
        return T.hr_ago.replace('{n}', Math.floor(diff / 3600));
    }

    function logoImgTag(size) {
        return logoUrl
            ? `<img src="${logoUrl}" style="width:${size}px;height:${size}px;object-fit:contain;border-radius:6px;" alt="">`
            : '';
    }

    function show(ev) {
        if (ev.type === 'register') {
            elIcon.style.background = 'rgba(166,107,66,.18)';
            elIcon.style.border     = '1px solid rgba(166,107,66,.4)';
            elIcon.innerHTML = logoUrl
                ? logoImgTag(26)
                : `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#c49d6d" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4-4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>`;
            elAct.innerHTML = `<span style="color:rgba(197,151,104,.8);">${T.reg_action}</span> <strong style="color:#d4a860;">${siteName}</strong>`;
        } else {
            elIcon.style.background = 'rgba(166,107,66,.18)';
            elIcon.style.border     = '1px solid rgba(166,107,66,.4)';
            elIcon.innerHTML = logoUrl
                ? logoImgTag(26)
                : `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#c49d6d" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M12 2v2M12 12v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/></svg>`;
            const amtFmt = (ev.amount || 0).toLocaleString();
            elAct.innerHTML = `<span style="color:rgba(197,151,104,.8);">${T.buy_action.replace('{n}', `<strong style="color:#d4a860;">${amtFmt}</strong>`)}</span> <strong style="color:#c59768;">${siteName}</strong>`;
        }

        elName.textContent = ev.name;
        elTime.textContent = timeAgo(ev.ts);

        // Animate in
        card.style.opacity   = '1';
        card.style.transform = 'translateX(0)';
    }

    function hide(cb) {
        card.style.opacity   = '0';
        card.style.transform = 'translateX(-24px)';
        setTimeout(cb, 420);
    }

    function cycle() {
        if (dismissed || !events.length) return;
        show(events[idx]);
        idx   = (idx + 1) % events.length;
        timer = setTimeout(() => {
            hide(() => { if (!dismissed) timer = setTimeout(cycle, 9000); });
        }, 6500);
    }

    elClose.addEventListener('click', function () {
        dismissed = true;
        clearTimeout(timer);
        hide(() => { wrap.style.display = 'none'; });
    });

    fetch('/api/social-proof')
        .then(r => r.ok ? r.json() : [])
        .then(data => {
            if (!Array.isArray(data) || !data.length) return;
            // Shuffle slightly so not always same order
            events = data.sort(() => Math.random() - 0.42);
            setTimeout(cycle, 4500);
        })
        .catch(() => {});
})();
</script>
