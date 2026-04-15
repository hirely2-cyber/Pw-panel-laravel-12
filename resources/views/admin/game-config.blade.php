@extends('layouts.admin')

@section('title', 'Game Configuration')
@section('header', 'Game Configuration')
@section('subheader', 'Server Attributes & Settings')

@section('content')
<div x-data="gameConfig()" x-init="init()">

    {{-- Error banner --}}
    <template x-if="error">
        <div style="margin-bottom:1rem;padding:.7rem 1rem;background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.3);border-radius:8px;font-size:.82rem;color:#ef4444;">
            <span x-text="error"></span>
            <button @click="error=null" style="float:right;background:none;border:none;color:#ef4444;cursor:pointer;font-weight:700;">&times;</button>
        </div>
    </template>

    {{-- Success banner --}}
    <template x-if="success">
        <div style="margin-bottom:1rem;padding:.7rem 1rem;background:rgba(80,200,120,.1);border:1px solid rgba(80,200,120,.3);border-radius:8px;font-size:.82rem;color:#50c878;">
            <span x-text="success"></span>
            <button @click="success=null" style="float:right;background:none;border:none;color:#50c878;cursor:pointer;font-weight:700;">&times;</button>
        </div>
    </template>

    {{-- Loading --}}
    <template x-if="loading">
        <div style="text-align:center;padding:3rem;">
            <div class="gc-spinner"></div>
            <p style="margin-top:.8rem;color:var(--pw-text-muted);font-size:.82rem;">Memuat konfigurasi game dari server...</p>
        </div>
    </template>

    <template x-if="!loading">
        <div>
            {{-- ─── SERVER STATUS ───────────────────────────────── --}}
            <div class="pw-adm-card" style="padding:1.2rem;margin-bottom:1rem;">
                <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:1rem;">
                    <svg viewBox="0 0 20 20" fill="none" width="18"><circle cx="10" cy="10" r="7" stroke="currentColor" stroke-width="1.5"/><path d="M10 6v4l3 2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    <span style="font-weight:700;font-size:.95rem;color:var(--pw-text-light);">Server Status</span>
                    <button @click="fetchConfig()" style="background:none;border:none;cursor:pointer;color:var(--pw-text-muted);margin-left:auto;font-size:.7rem;display:flex;align-items:center;gap:.3rem;">
                        <svg viewBox="0 0 16 16" fill="none" width="13" stroke="currentColor" stroke-width="1.6"><path d="M3 3v4h4M13 13V9H9" stroke-linecap="round" stroke-linejoin="round"/><path d="M14 6A6 6 0 004.5 4.5L3 7M2 10a6 6 0 009.5 1.5L13 9" stroke-linecap="round"/></svg>
                        Refresh
                    </button>
                </div>
                <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:.8rem;">
                    {{-- Status --}}
                    <div class="gc-stat-box">
                        <div class="gc-stat-label">STATUS</div>
                        <div style="display:flex;align-items:center;gap:.45rem;">
                            <span :class="serverOnline ? 'gc-dot gc-dot--on' : 'gc-dot gc-dot--off'"></span>
                            <span :style="serverOnline ? 'color:#50c878;font-weight:700;font-size:.9rem;' : 'color:#ef4444;font-weight:700;font-size:.9rem;'" x-text="serverOnline ? 'Online' : 'Offline'"></span>
                        </div>
                    </div>
                    {{-- Players Online --}}
                    <div class="gc-stat-box">
                        <div class="gc-stat-label">PLAYERS ONLINE</div>
                        <div style="font-size:1.3rem;font-weight:800;color:#f0a500;font-family:monospace;" x-text="maxOnline.curnum ?? '—'"></div>
                    </div>
                    {{-- Max Online --}}
                    <div class="gc-stat-box">
                        <div class="gc-stat-label">MAX CAPACITY</div>
                        <div style="font-size:1.3rem;font-weight:800;color:var(--pw-text-light);font-family:monospace;" x-text="maxOnline.maxnum ?? '\u2014'"></div>
                    </div>
                    {{-- Usage --}}
                    <div class="gc-stat-box">
                        <div class="gc-stat-label">USAGE</div>
                        <div style="font-size:1.3rem;font-weight:800;font-family:monospace;"
                             :style="'color:' + (usagePercent > 80 ? '#ef4444' : usagePercent > 50 ? '#f0a500' : '#50c878')"
                             x-text="usagePercent + '%'"></div>
                    </div>
                </div>
            </div>

            {{-- ─── MAX ONLINE USERS ────────────────────────────── --}}
            <div class="pw-adm-card" style="padding:1.2rem;margin-bottom:1rem;">
                <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:1rem;">
                    <svg viewBox="0 0 20 20" fill="none" width="18"><path d="M10 2a4 4 0 014 4c0 2.21-1.79 4-4 4S6 8.21 6 6a4 4 0 014-4zM2 17c0-3.31 3.58-6 8-6s8 2.69 8 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    <span style="font-weight:700;font-size:.95rem;color:var(--pw-text-light);">Max Online Users</span>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr auto;gap:.8rem;align-items:end;">
                    <div>
                        <label class="gc-stat-label" style="margin-bottom:.3rem;">MAX ONLINE</label>
                        <input type="number" x-model.number="maxOnlineForm.maxnum" min="1" max="99999" class="gc-input gc-input--mono">
                    </div>
                    <div>
                        <label class="gc-stat-label" style="margin-bottom:.3rem;">FAKE MAX</label>
                        <input type="number" x-model.number="maxOnlineForm.fake_maxnum" min="0" max="99999" class="gc-input gc-input--mono">
                    </div>
                    <button @click="saveMaxOnline()" :disabled="saving" class="gc-btn gc-btn--primary">
                        <span x-show="!saving">Simpan</span>
                        <span x-show="saving">...</span>
                    </button>
                </div>
            </div>

            {{-- ─── EXP RATE & LAMBDA ───────────────────────────── --}}
            <div class="pw-adm-card" style="padding:1.2rem;margin-bottom:1rem;">
                <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:1rem;">
                    <svg viewBox="0 0 20 20" fill="none" width="18"><path d="M10 2v16M6 6l4-4 4 4M6 14l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <span style="font-weight:700;font-size:.95rem;color:var(--pw-text-light);">Rate & Lambda</span>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.2rem;">
                    {{-- EXP Rate --}}
                    <div>
                        <label class="gc-stat-label" style="margin-bottom:.4rem;">EXP RATE MULTIPLIER</label>
                        <div style="display:flex;gap:.5rem;align-items:center;">
                            <div style="flex:1;position:relative;" x-data="{ open: false }" @click.away="open = false">
                                <button @click="open = !open" type="button" class="gc-dropdown-btn">
                                    <span x-text="expRate + 'x'"></span>
                                    <svg viewBox="0 0 12 12" width="10" fill="currentColor" style="opacity:.5;flex-shrink:0;transition:transform .15s;" :style="open && 'transform:rotate(180deg)'"><path d="M2 4l4 4 4-4"/></svg>
                                </button>
                                <div x-show="open" x-transition.opacity.duration.150ms class="gc-dropdown-menu">
                                    <template x-for="i in [0,1,2,3,4,5,6,7,8,9,10]" :key="i">
                                        <button @click="expRate = i; open = false; setAttr({{ App\Services\DeliveryProtocol::ATTR_DOUBLE_EXP }}, i)"
                                                class="gc-dropdown-item"
                                                :class="expRate === i && 'gc-dropdown-item--active'"
                                                x-text="i + 'x'"></button>
                                    </template>
                                </div>
                            </div>
                            <span style="font-size:.75rem;color:var(--pw-text-muted);white-space:nowrap;" x-text="'Current: ' + (attrs.double_exp?.value ?? '-') + 'x'"></span>
                        </div>
                    </div>
                    {{-- Lambda --}}
                    <div>
                        <label class="gc-stat-label" style="margin-bottom:.4rem;">LAMBDA VALUE</label>
                        <div style="display:flex;gap:.5rem;align-items:center;">
                            <div style="flex:1;position:relative;" x-data="{ open: false }" @click.away="open = false">
                                <button @click="open = !open" type="button" class="gc-dropdown-btn">
                                    <span x-text="lambdaVal"></span>
                                    <svg viewBox="0 0 12 12" width="10" fill="currentColor" style="opacity:.5;flex-shrink:0;transition:transform .15s;" :style="open && 'transform:rotate(180deg)'"><path d="M2 4l4 4 4-4"/></svg>
                                </button>
                                <div x-show="open" x-transition.opacity.duration.150ms class="gc-dropdown-menu">
                                    <template x-for="i in [0,1,2,3,4,5,6,7,8,9,10]" :key="i">
                                        <button @click="lambdaVal = i; open = false; setAttr({{ App\Services\DeliveryProtocol::ATTR_LAMBDA }}, i)"
                                                class="gc-dropdown-item"
                                                :class="lambdaVal === i && 'gc-dropdown-item--active'"
                                                x-text="i"></button>
                                    </template>
                                </div>
                            </div>
                            <span style="font-size:.75rem;color:var(--pw-text-muted);white-space:nowrap;" x-text="'Current: ' + (attrs.lambda?.value ?? '-')"></span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ─── TOGGLE ATTRIBUTES ───────────────────────────── --}}
            <div class="pw-adm-card" style="padding:1.2rem;margin-bottom:1rem;">
                <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:1rem;">
                    <svg viewBox="0 0 20 20" fill="none" width="18"><rect x="1" y="6" width="18" height="8" rx="4" stroke="currentColor" stroke-width="1.5"/><circle cx="14" cy="10" r="2.5" fill="currentColor"/></svg>
                    <span style="font-weight:700;font-size:.95rem;color:var(--pw-text-light);">Server Toggles</span>
                </div>
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:.7rem;">
                    <template x-for="item in toggleItems" :key="item.key">
                        <div class="gc-toggle-card" style="display:flex;align-items:center;justify-content:space-between;padding:.65rem .9rem;border-radius:8px;">
                            <div>
                                <span style="font-size:.82rem;font-weight:600;color:var(--pw-text-light);" x-text="item.label"></span>
                                <template x-if="attrs[item.key]?.error">
                                    <span style="font-size:.65rem;color:#ef4444;margin-left:.4rem;">Error</span>
                                </template>
                            </div>
                            <button @click="toggleAttr(item)" :disabled="saving"
                                    :style="'position:relative;width:42px;height:22px;border-radius:11px;border:1px solid ' + (attrs[item.key]?.value ? 'rgba(80,200,120,.65)' : 'rgba(239,68,68,.55)') + ';cursor:pointer;transition:background .2s, border-color .2s;' +
                                    (attrs[item.key]?.value ? 'background:#50c878;' : 'background:#ef4444;')">
                                <span :style="'position:absolute;top:2px;width:18px;height:18px;border-radius:50%;background:#fff;transition:left .2s;' +
                                      (attrs[item.key]?.value ? 'left:22px;' : 'left:2px;')"></span>
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </template>
</div>

<style>
@keyframes spin { to { transform: rotate(360deg); } }
@keyframes pulse-dot { 0%,100% { opacity:1; } 50% { opacity:.4; } }

.gc-spinner {
    display:inline-block;width:28px;height:28px;
    border:3px solid rgba(255,255,255,.15);border-top-color:#f0a500;
    border-radius:50%;animation:spin .7s linear infinite;
}

/* Status dot */
.gc-dot { display:inline-block;width:10px;height:10px;border-radius:50%; }
.gc-dot--on { background:#50c878;box-shadow:0 0 8px rgba(80,200,120,.5);animation:pulse-dot 2s ease-in-out infinite; }
.gc-dot--off { background:#ef4444;box-shadow:0 0 8px rgba(239,68,68,.4); }

/* Stat box */
.gc-stat-box {
    padding:.7rem .9rem;background:rgba(255,255,255,.03);
    border:1px solid rgba(255,255,255,.06);border-radius:8px;
}
.gc-stat-label {
    display:block;font-size:.68rem;font-weight:700;
    letter-spacing:.07em;color:var(--pw-text-muted);margin-bottom:.35rem;
}

/* Inputs */
.gc-input {
    width:100%;background:rgba(255,255,255,.05);
    border:1px solid rgba(255,255,255,.12);border-radius:6px;
    padding:.45rem .7rem;color:#fff;font-size:.85rem;
    transition:border-color .15s, box-shadow .15s;
}
.gc-input:focus { outline:none;border-color:rgba(240,165,0,.5);box-shadow:0 0 0 2px rgba(240,165,0,.12); }
.gc-input--mono { font-family:monospace; }

/* Button */
.gc-btn {
    border:none;border-radius:6px;padding:.5rem 1.2rem;
    font-weight:700;font-size:.8rem;cursor:pointer;white-space:nowrap;
    transition:opacity .15s;
}
.gc-btn:disabled { opacity:.5;cursor:not-allowed; }
.gc-btn--primary { background:#f0a500;color:#000; }
.gc-btn--primary:hover:not(:disabled) { background:#d49400; }

/* Custom dropdown */
.gc-dropdown-btn {
    width:100%;display:flex;align-items:center;justify-content:space-between;
    background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.12);
    border-radius:6px;padding:.45rem .7rem;color:#fff;font-size:.85rem;
    cursor:pointer;transition:border-color .15s, box-shadow .15s;
    text-align:left;
}
.gc-dropdown-btn:hover { border-color:rgba(255,255,255,.25); }
.gc-dropdown-btn:focus { outline:none;border-color:rgba(240,165,0,.5);box-shadow:0 0 0 2px rgba(240,165,0,.12); }

.gc-dropdown-menu {
    position:absolute;top:calc(100% + 4px);left:0;right:0;z-index:50;
    background:var(--pw-bg-card);border:1px solid var(--pw-border);
    border-radius:8px;padding:4px;max-height:240px;overflow-y:auto;
    box-shadow:0 8px 24px rgba(0,0,0,.5);
}
.gc-dropdown-menu::-webkit-scrollbar { width:5px; }
.gc-dropdown-menu::-webkit-scrollbar-track { background:transparent; }
.gc-dropdown-menu::-webkit-scrollbar-thumb { background:rgba(255,255,255,.12);border-radius:3px; }

.gc-dropdown-item {
    display:block;width:100%;text-align:left;padding:.42rem .7rem;
    background:transparent;border:none;color:#cbd5e1;font-size:.84rem;
    cursor:pointer;border-radius:5px;transition:background .1s, color .1s;
}
.gc-dropdown-item:hover { background:rgba(240,165,0,.12);color:#fff; }
.gc-dropdown-item--active { background:rgba(240,165,0,.18);color:#f0a500;font-weight:600; }

/* Toggle cards */
.gc-toggle-card {
    background: rgba(255,255,255,.04);
    border: 1px solid rgba(255,255,255,.08);
}

/* ── CSS custom prop for toggle off-track (theme-aware) ── */
:root, [data-theme="dark"]  {
    --gc-track-off: rgba(148,163,184,.35);
    --gc-track-border: rgba(203,213,225,.45);
}
[data-theme="light"]        {
    --gc-track-off: rgba(0,0,0,.2);
    --gc-track-border: rgba(0,0,0,.25);
}

/* ── Light mode overrides ── */
[data-theme="light"] .gc-spinner { border-color:rgba(0,0,0,.1);border-top-color:#d49400; }
[data-theme="light"] .gc-stat-box { background:#e8e8e8; border-color:rgba(0,0,0,.14); }
[data-theme="light"] .gc-input {
    background:#fff; border-color:rgba(0,0,0,.2); color:var(--pw-text);
}
[data-theme="light"] .gc-input:focus { border-color:rgba(138,94,0,.5); box-shadow:0 0 0 2px rgba(138,94,0,.1); }
[data-theme="light"] .gc-dropdown-btn {
    background:#fff; border-color:rgba(0,0,0,.2); color:var(--pw-text);
}
[data-theme="light"] .gc-dropdown-btn:hover { border-color:rgba(0,0,0,.35); }
[data-theme="light"] .gc-dropdown-item { color:var(--pw-text); }
[data-theme="light"] .gc-dropdown-item:hover { background:rgba(138,94,0,.1); color:var(--pw-text); }
[data-theme="light"] .gc-dropdown-item--active { background:rgba(138,94,0,.12); color:var(--pw-gold); }
[data-theme="light"] .gc-dropdown-menu { box-shadow:0 8px 24px rgba(0,0,0,.12); }
[data-theme="light"] .gc-dropdown-menu::-webkit-scrollbar-thumb { background:rgba(0,0,0,.15); }
[data-theme="light"] .gc-toggle-card { background:#e8e8e8; border:1px solid rgba(0,0,0,.14); }
</style>

<script>
function gameConfig() {
    return {
        loading: true,
        saving: false,
        error: null,
        success: null,
        serverOnline: false,
        attrs: {},
        maxOnline: {},
        maxOnlineForm: { maxnum: 0, fake_maxnum: 0 },
        expRate: 0,
        lambdaVal: 0,
        toggleItems: [
            { key: 'double_drop', label: 'Double Drop Rate',    attr: {{ App\Services\DeliveryProtocol::ATTR_DOUBLE_DROP }} },
            { key: 'double_coin', label: 'Double Coins',        attr: {{ App\Services\DeliveryProtocol::ATTR_DOUBLE_COIN }} },
            { key: 'double_sp',   label: 'Double SP',           attr: {{ App\Services\DeliveryProtocol::ATTR_DOUBLE_SP }} },
            { key: 'no_mail',     label: 'No-Mail Mode',        attr: {{ App\Services\DeliveryProtocol::ATTR_NO_MAIL }} },
            { key: 'no_faction',  label: 'No-Faction Mode',     attr: {{ App\Services\DeliveryProtocol::ATTR_NO_FACTION }} },
            { key: 'no_trade',    label: 'No-Trade Mode',       attr: {{ App\Services\DeliveryProtocol::ATTR_NO_TRADE }} },
            { key: 'no_shop',     label: 'No-PlayerShop Mode',  attr: {{ App\Services\DeliveryProtocol::ATTR_NO_SHOP }} },
            { key: 'no_auction',  label: 'No-Auction Mode',     attr: {{ App\Services\DeliveryProtocol::ATTR_NO_AUCTION }} },
        ],

        get usagePercent() {
            if (!this.maxOnline.maxnum || !this.serverOnline) return 0;
            return Math.round((this.maxOnline.curnum / this.maxOnline.maxnum) * 100);
        },

        async init() {
            await this.fetchConfig();
        },

        async fetchConfig() {
            this.loading = true;
            this.error = null;
            try {
                const res = await fetch('{{ route("admin.game-config.fetch") }}');
                const data = await res.json();
                if (!data.ok) throw new Error(data.error || 'Unknown error');

                this.serverOnline = true;
                this.attrs = data.attributes;
                this.maxOnline = data.maxOnline;
                this.maxOnlineForm.maxnum = data.maxOnline.maxnum || 0;
                this.maxOnlineForm.fake_maxnum = data.maxOnline.fake_maxnum || 0;
                this.expRate = data.attributes.double_exp?.value ?? 0;
                this.lambdaVal = data.attributes.lambda?.value ?? 0;
            } catch (e) {
                this.serverOnline = false;
                this.error = e.message || 'Gagal memuat konfigurasi';
            }
            this.loading = false;
        },

        async toggleAttr(item) {
            const current = this.attrs[item.key]?.value || 0;
            const newVal = current ? 0 : 1;
            this.saving = true;
            this.error = null;
            try {
                const res = await fetch('{{ route("admin.game-config.toggle") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ attribute: item.attr, value: newVal })
                });
                const data = await res.json();
                if (!data.ok) throw new Error(data.error || 'Failed');
                this.attrs[item.key].value = newVal;
                this.showSuccess(item.label + (newVal ? ' diaktifkan' : ' dinonaktifkan'));
            } catch (e) {
                this.error = e.message;
            }
            this.saving = false;
        },

        async setAttr(attr, value) {
            this.saving = true;
            this.error = null;
            try {
                const res = await fetch('{{ route("admin.game-config.set-attr") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ attribute: attr, value: value })
                });
                const data = await res.json();
                if (!data.ok) throw new Error(data.error || 'Failed');
                this.showSuccess('Nilai berhasil diubah');
                await this.fetchConfig();
            } catch (e) {
                this.error = e.message;
            }
            this.saving = false;
        },

        async saveMaxOnline() {
            this.saving = true;
            this.error = null;
            try {
                const res = await fetch('{{ route("admin.game-config.max-online") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify(this.maxOnlineForm)
                });
                const data = await res.json();
                if (!data.ok) throw new Error(data.error || 'Failed');
                this.showSuccess('Max online berhasil diubah');
                await this.fetchConfig();
            } catch (e) {
                this.error = e.message;
            }
            this.saving = false;
        },

        showSuccess(msg) {
            this.success = msg;
            setTimeout(() => this.success = null, 3000);
        }
    };
}
</script>
@endsection