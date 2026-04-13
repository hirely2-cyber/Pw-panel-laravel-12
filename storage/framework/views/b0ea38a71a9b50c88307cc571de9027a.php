

<div id="pw-confirm-overlay"
     style="display:none;position:fixed;inset:0;z-index:9999;background:transparent;align-items:center;justify-content:center;">
    <div id="pw-confirm-box"
         style="background:#1e1e1e;border:1px solid rgba(200,151,42,.25);border-radius:14px;padding:2rem 2rem 1.5rem;width:100%;max-width:400px;margin:1rem;box-shadow:0 25px 60px rgba(0,0,0,.6);transform:scale(.95);transition:transform .15s ease,opacity .15s ease;opacity:0;">

        
        <div id="pw-confirm-icon" style="width:52px;height:52px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.2rem;"></div>

        
        <h3 id="pw-confirm-title"
            style="text-align:center;font-size:1rem;font-weight:700;color:#f1f5f9;margin:0 0 .6rem;letter-spacing:.01em;"></h3>

        
        <p id="pw-confirm-message"
           style="text-align:center;font-size:.85rem;color:#94a3b8;line-height:1.55;margin:0 0 1.6rem;"></p>

        
        <div style="display:flex;gap:.6rem;">
            <button id="pw-confirm-cancel"
                    style="flex:1;padding:.7rem 1rem;border-radius:8px;border:1px solid rgba(255,255,255,.12);background:transparent;color:#94a3b8;font-size:.85rem;font-weight:500;cursor:pointer;transition:background .15s,color .15s;"
                    onmouseover="this.style.background='rgba(255,255,255,.07)';this.style.color='#f1f5f9'"
                    onmouseout="this.style.background='transparent';this.style.color='#94a3b8'">
                Batal
            </button>
            <button id="pw-confirm-ok"
                    style="flex:1;padding:.7rem 1rem;border-radius:8px;border:none;color:#fff;font-size:.85rem;font-weight:600;cursor:pointer;transition:filter .15s;"
                    onmouseover="this.style.filter='brightness(1.15)'"
                    onmouseout="this.style.filter='brightness(1)'">
                OK
            </button>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
(function () {
    const overlay  = document.getElementById('pw-confirm-overlay');
    // Move overlay to body root to avoid stacking context issues
    document.body.appendChild(overlay);

    const box      = document.getElementById('pw-confirm-box');
    const iconEl   = document.getElementById('pw-confirm-icon');
    const titleEl  = document.getElementById('pw-confirm-title');
    const msgEl    = document.getElementById('pw-confirm-message');
    const cancelBtn= document.getElementById('pw-confirm-cancel');
    const okBtn    = document.getElementById('pw-confirm-ok');

    const VARIANTS = {
        danger:  { bg:'rgba(127,29,29,.5)', border:'rgba(239,68,68,.3)', btnBg:'#dc2626', icon:'M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z', stroke:'#ef4444' },
        success: { bg:'rgba(200,151,42,.15)', border:'rgba(200,151,42,.35)', btnBg:'#c8972a', icon:'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', stroke:'#e8b84b' },
        warning: { bg:'rgba(92,55,4,.5)',   border:'rgba(234,179,8,.3)',  btnBg:'#ca8a04', icon:'M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z',   stroke:'#eab308' },
    };

    let _resolve = null;

    function openDialog(title, message, variant, okLabel) {
        const v = VARIANTS[variant] || VARIANTS.danger;

        iconEl.style.background    = v.bg;
        iconEl.style.border        = '1px solid ' + v.border;
        iconEl.innerHTML           = `<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="${v.stroke}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="${v.icon}"/></svg>`;
        titleEl.textContent        = title;
        msgEl.textContent          = message;
        okBtn.textContent          = okLabel || 'Ya, Lanjutkan';
        okBtn.style.background     = v.btnBg;

        overlay.style.display      = 'flex';
        requestAnimationFrame(() => {
            box.style.transform    = 'scale(1)';
            box.style.opacity      = '1';
        });

        return new Promise(res => _resolve = res);
    }

    function closeDialog(result) {
        box.style.transform = 'scale(.95)';
        box.style.opacity   = '0';
        setTimeout(() => { overlay.style.display = 'none'; }, 150);
        if (_resolve) { _resolve(result); _resolve = null; }
    }

    cancelBtn.addEventListener('click', () => closeDialog(false));
    overlay.addEventListener('click', e => { if (e.target === overlay) closeDialog(false); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDialog(false); });
    okBtn.addEventListener('click', () => closeDialog(true));

    // Auto-intercept: forms and buttons with data-confirm="Title|Message"
    document.addEventListener('submit', async function (e) {
        const form = e.target;
        if (!form.dataset.confirm) return;
        e.preventDefault();
        const [title, msg] = (form.dataset.confirm + '|').split('|');
        const variant      = form.dataset.confirmVariant || 'danger';
        const okLabel      = form.dataset.confirmOk || null;
        const ok           = await openDialog(title, msg, variant, okLabel);
        if (ok) form.submit();
    }, true);

    document.addEventListener('click', async function (e) {
        const btn = e.target.closest('[data-confirm]');
        if (!btn || btn.tagName === 'FORM') return;
        // skip if inside a form that handles its own confirm
        if (btn.closest('form[data-confirm]')) return;
        e.preventDefault();
        const [title, msg] = (btn.dataset.confirm + '|').split('|');
        const variant      = btn.dataset.confirmVariant || 'danger';
        const okLabel      = btn.dataset.confirmOk || null;
        const ok           = await openDialog(title, msg, variant, okLabel);
        if (ok && btn.form) btn.form.submit();
    }, true);

    // Global helper: window.pwConfirm('Title','Message','variant').then(ok => ...)
    window.pwConfirm = openDialog;
})();
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH /var/www/pw-panel/resources/views/components/confirm-dialog.blade.php ENDPATH**/ ?>