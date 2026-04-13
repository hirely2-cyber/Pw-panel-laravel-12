@extends('layouts.admin')

@section('title', 'Server Control')
@section('header', 'Server Control')
@section('subheader', 'Game Server Management')

@section('content')
<div x-data="serverCtrl()" x-init="init()">

    {{-- â”€â”€ SERVER PATH (bar tipis di atas) â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ --}}
    <div class="pw-adm-card" style="padding:.8rem 1.2rem;margin-bottom:1rem;">
        @if(session('success'))
            <div style="margin-bottom:.6rem;padding:.4rem .8rem;background:rgba(80,200,120,.1);border:1px solid rgba(80,200,120,.3);border-radius:5px;font-size:.78rem;color:#50c878;">
                {{ session('success') }}
            </div>
        @endif
        <form method="POST" action="{{ route('admin.server-control.path') }}" style="display:flex;align-items:center;gap:.8rem;">
            @csrf
            <span style="font-size:.7rem;font-weight:700;letter-spacing:.07em;color:var(--pw-text-muted);white-space:nowrap;">SERVER PATH</span>
            <input type="text" name="server_path" value="{{ rtrim($serverPath, '/') }}"
                   placeholder="/home/pw_server155"
                   class="sc-input"
                   style="flex:1;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.12);border-radius:6px;
                          padding:.45rem .8rem;color:#fff;font-size:.83rem;font-family:monospace;">
            <button type="submit"
                    style="background:#f0a500;color:#000;border:none;border-radius:6px;padding:.45rem 1rem;font-weight:700;font-size:.8rem;cursor:pointer;white-space:nowrap;">
                Simpan
            </button>
            @error('server_path')
                <span style="color:#e05252;font-size:.75rem;">{{ $message }}</span>
            @enderror
        </form>
    </div>

    {{-- ── SERVER SPECS BAR ─────────────────────────────────── --}}
    <div class="pw-adm-card" style="padding:.9rem 1.2rem;margin-bottom:1rem;">
        <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:.6rem;">

            {{-- Hostname / OS --}}
            <div style="display:flex;flex-direction:column;gap:.18rem;">
                <span style="font-size:.58rem;font-weight:700;letter-spacing:.07em;color:var(--pw-text-muted);">HOST</span>
                <span class="sc-val" style="font-size:.82rem;font-weight:700;color:#e2e8f0;">{{ $serverInfo['hostname'] }}</span>
                <span style="font-size:.63rem;color:#94a3b8;">{{ $serverInfo['os'] }}</span>
            </div>

            {{-- CPU --}}
            <div style="display:flex;flex-direction:column;gap:.18rem;">
                <span style="font-size:.58rem;font-weight:700;letter-spacing:.07em;color:var(--pw-text-muted);">CPU</span>
                <span class="sc-val" style="font-size:.82rem;font-weight:700;color:#e2e8f0;">{{ $serverInfo['cpuCores'] }} Core</span>
                <span style="font-size:.63rem;color:#94a3b8;" title="{{ $serverInfo['cpuModel'] }}">{{ Str::limit($serverInfo['cpuModel'], 28) }}</span>
            </div>

            {{-- CPU Load --}}
            <div style="display:flex;flex-direction:column;gap:.18rem;">
                <span style="font-size:.58rem;font-weight:700;letter-spacing:.07em;color:var(--pw-text-muted);">LOAD AVG</span>
                @php $l1 = (float)$serverInfo['load1']; $lc = $serverInfo['cpuCores']; @endphp
                <span style="font-size:.82rem;font-weight:700;color:{{ $l1 > $lc ? '#ef4444' : ($l1 > $lc*0.7 ? '#f59e0b' : '#50c878') }};">
                    {{ $serverInfo['load1'] }}
                </span>
                <span style="font-size:.65rem;color:#94a3b8;">5m: {{ $serverInfo['load5'] }} &bull; 15m: {{ $serverInfo['load15'] }}</span>
            </div>

            {{-- Disk --}}
            <div style="display:flex;flex-direction:column;gap:.18rem;">
                <span style="font-size:.58rem;font-weight:700;letter-spacing:.07em;color:var(--pw-text-muted);">DISK (/)</span>
                <div style="display:flex;align-items:baseline;gap:.3rem;">
                    <span class="sc-val" style="font-size:.82rem;font-weight:700;color:#e2e8f0;">{{ $serverInfo['diskUsed'] }}</span>
                    <span style="font-size:.68rem;color:#94a3b8;">/ {{ $serverInfo['diskTotal'] }}</span>
                </div>
                <div style="height:4px;background:rgba(255,255,255,.08);border-radius:3px;margin-top:.15rem;">
                    <div style="height:100%;border-radius:3px;width:{{ $serverInfo['diskPct'] }}%;background:{{ $serverInfo['diskPct'] > 90 ? '#ef4444' : ($serverInfo['diskPct'] > 75 ? '#f59e0b' : '#3b82f6') }};"></div>
                </div>
                <span style="font-size:.63rem;color:#94a3b8;">{{ $serverInfo['diskPct'] }}% used</span>
            </div>

            {{-- Uptime --}}
            <div style="display:flex;flex-direction:column;gap:.18rem;">
                <span style="font-size:.58rem;font-weight:700;letter-spacing:.07em;color:var(--pw-text-muted);">UPTIME</span>
                <span class="sc-val" style="font-size:.82rem;font-weight:700;color:#e2e8f0;">{{ Str::after($serverInfo['uptime'], 'up ') }}</span>
                <span style="font-size:.63rem;color:#94a3b8;">sistem berjalan</span>
            </div>

        </div>
    </div>

    {{-- â”€â”€ MAIN 2 KOLOM â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">

        {{-- â•â•â• KIRI: SERVICES â•â•â• --}}
        <div class="pw-adm-card" style="padding:1.2rem;display:flex;flex-direction:column;gap:.9rem;">

            {{-- Header + Status --}}
            <div style="display:flex;align-items:center;justify-content:space-between;">
                <span style="font-size:.7rem;font-weight:700;letter-spacing:.08em;color:var(--pw-text-muted);"><svg viewBox="0 0 20 20" fill="none" width="13" stroke="currentColor" stroke-width="1.6" style="display:inline;vertical-align:middle;margin-right:.3rem;opacity:.7"><path d="M10 3v1m0 12v1M3 10h1m12 0h1m-2.05-4.95-.71.71M4.76 15.24l-.71.71m0-11.9.71.71m10.48 10.48.71.71" stroke-linecap="round"/><circle cx="10" cy="10" r="3"/></svg>SERVICES</span>
                <span style="display:flex;align-items:center;gap:.35rem;font-size:.75rem;font-weight:700;"
                      :style="serverRunning ? 'color:#50c878' : 'color:#e05252'">
                    <span style="width:8px;height:8px;border-radius:50%;background:currentColor;display:inline-block;"
                          :style="serverRunning ? 'box-shadow:0 0 6px #50c878' : ''"></span>
                    <span x-text="serverRunning ? 'ONLINE' : 'OFFLINE'"></span>
                    <button @click="refreshStatus()" style="background:none;border:none;cursor:pointer;color:var(--pw-text-muted);font-size:.85rem;padding:0 .2rem;"><svg viewBox="0 0 20 20" fill="none" width="13" stroke="currentColor" stroke-width="1.8"><path d="M4 4v5h5M16 16v-5h-5" stroke-linecap="round" stroke-linejoin="round"/><path d="M20.49 9A9 9 0 005.64 5.64L4 10M3.51 11A9 9 0 0014.36 14.36L16 10" stroke-linecap="round"/></svg></button>
                </span>
            </div>

            {{-- Memory --}}
            <div>
                <div style="font-size:.65rem;font-weight:700;letter-spacing:.07em;color:var(--pw-text-muted);margin-bottom:.5rem;">MEMORY USAGE</div>
                <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:.35rem;margin-bottom:.6rem;">
                    <div class="sc-membox" style="background:rgba(255,255,255,.04);border-radius:6px;padding:.4rem;text-align:center;">
                        <div style="font-size:.55rem;color:var(--pw-text-muted);margin-bottom:.15rem;">Total</div>
                        <div style="font-weight:700;font-size:.82rem;" x-text="memory.ram_total + ' MB'">-</div>
                    </div>
                    <div class="sc-membox" style="background:rgba(59,130,246,.08);border-radius:6px;padding:.4rem;text-align:center;">
                        <div style="font-size:.55rem;color:#60a5fa;margin-bottom:.15rem;">Apps</div>
                        <div style="font-weight:700;font-size:.82rem;color:#93c5fd;" x-text="memory.ram_used + ' MB'">-</div>
                    </div>
                    <div class="sc-membox" style="background:rgba(245,158,11,.08);border-radius:6px;padding:.4rem;text-align:center;">
                        <div style="font-size:.55rem;color:#fbbf24;margin-bottom:.15rem;">Buff/Cache</div>
                        <div style="font-weight:700;font-size:.82rem;color:#fcd34d;" x-text="memory.ram_buff + ' MB'">-</div>
                    </div>
                    <div class="sc-membox" style="background:rgba(80,200,120,.06);border-radius:6px;padding:.4rem;text-align:center;">
                        <div style="font-size:.55rem;color:var(--pw-text-muted);margin-bottom:.15rem;">Tersedia</div>
                        <div style="font-weight:700;font-size:.82rem;color:#50c878;" x-text="memory.ram_avail + ' MB'">-</div>
                    </div>
                </div>
                {{-- Apps bar --}}
                <div style="font-size:.62rem;color:var(--pw-text-muted);display:flex;justify-content:space-between;margin-bottom:.2rem;">
                    <span style="color:#60a5fa;">Apps</span>
                    <span x-text="memory.ram_total > 0 ? Math.round(memory.ram_used/memory.ram_total*100)+'%' : '0%'"></span>
                </div>
                <div class="sc-bar-bg" style="height:5px;background:rgba(255,255,255,.08);border-radius:3px;margin-bottom:.35rem;">
                    <div :style="{ width: (memory.ram_total>0 ? Math.round(memory.ram_used/memory.ram_total*100) : 0) + '%', background: '#3b82f6', height:'100%', borderRadius:'3px', transition:'width .5s' }"></div>
                </div>
                {{-- Buff/Cache bar --}}
                <div style="font-size:.62rem;color:var(--pw-text-muted);display:flex;justify-content:space-between;margin-bottom:.2rem;">
                    <span style="color:#f59e0b;">Buff/Cache</span>
                    <span x-text="memory.ram_total > 0 ? Math.round(memory.ram_buff/memory.ram_total*100)+'%' : '0%'"></span>
                </div>
                <div class="sc-bar-bg" style="height:5px;background:rgba(255,255,255,.08);border-radius:3px;margin-bottom:.35rem;">
                    <div :style="{ width: (memory.ram_total>0 ? Math.round(memory.ram_buff/memory.ram_total*100) : 0) + '%', background: '#f59e0b', height:'100%', borderRadius:'3px', transition:'width .5s' }"></div>
                </div>
                {{-- Swap bar --}}
                <div style="font-size:.62rem;color:var(--pw-text-muted);display:flex;justify-content:space-between;margin-bottom:.2rem;">
                    <span>Swap</span>
                    <span x-text="(memory.swp_total > 0 ? Math.round(memory.swp_used/memory.swp_total*100) : 0) + '% - ' + memory.swp_used + ' / ' + memory.swp_total + ' MB'"></span>
                </div>
                <div class="sc-bar-bg" style="height:5px;background:rgba(255,255,255,.08);border-radius:3px;">
                    <div :style="{ width: (memory.swp_total>0 ? Math.round(memory.swp_used/memory.swp_total*100) : 0) + '%', background: ramColor(memory.swp_total>0 ? Math.round(memory.swp_used/memory.swp_total*100) : 0), height:'100%', borderRadius:'3px', transition:'width .5s' }"></div>
                </div>
            </div>

            {{-- Daemon List --}}
            <div>
                <div style="display:flex;flex-direction:column;gap:.28rem;">
                    <template x-for="(d, key) in daemons" :key="key">
                        <div class="sc-daemon-row" style="display:flex;align-items:center;justify-content:space-between;padding:.38rem .65rem;border-radius:5px;background:rgba(255,255,255,.03);">
                            <div>
                                <span style="font-weight:600;font-size:.8rem;" x-text="d.label"></span>
                                <span style="font-size:.63rem;color:#94a3b8;font-family:monospace;margin-left:.4rem;" x-text="d.process"></span>
                            </div>
                            <div style="display:flex;align-items:center;gap:.4rem;">
                                <span style="font-size:.68rem;color:#94a3b8;" x-text="d.count"></span>
                                <span :style="d.count > 0
                                    ? 'background:rgba(80,200,120,.15);color:#50c878;border:1px solid rgba(80,200,120,.3);padding:.15rem .5rem;border-radius:4px;font-size:.65rem;font-weight:700;'
                                    : (starting ? 'background:rgba(245,158,11,.1);color:#f59e0b;border:1px solid rgba(245,158,11,.25);padding:.15rem .5rem;border-radius:4px;font-size:.65rem;font-weight:700;animation:pwPulse 1s ease-in-out infinite;' : 'background:rgba(220,60,60,.12);color:#e05252;border:1px solid rgba(220,60,60,.2);padding:.15rem .5rem;border-radius:4px;font-size:.65rem;font-weight:700;')"  
                                     x-text="d.count > 0 ? 'Online' : (starting ? 'Loading...' : 'Offline')"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div style="position:relative;margin-top:auto;">
                {{-- Busy overlay --}}
                <div x-show="busy" x-transition
                     style="position:absolute;inset:0;z-index:10;background:rgba(15,18,24,.85);border-radius:8px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:.6rem;">
                    <div class="pw-spinner" style="width:28px;height:28px;border-radius:50%;border:3px solid rgba(255,255,255,.1);border-top-color:#f0a500;"></div>
                    <span style="font-size:.75rem;font-weight:600;color:#f0a500;" x-text="busyMsg"></span>
                </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.5rem;">
                <button @click="confirmAction('start')"
                        :disabled="!pathConfigured || serverRunning"
                        :style="{ opacity: (!pathConfigured||serverRunning) ? 0.4 : 1, cursor: (!pathConfigured||serverRunning) ? 'not-allowed' : 'pointer' }"
                        style="display:flex;align-items:center;justify-content:center;gap:.45rem;padding:.65rem;border-radius:7px;border:none;font-weight:700;font-size:.8rem;background:#16a34a;color:#fff;">
                    <svg viewBox="0 0 20 20" fill="currentColor" width="14"><path d="M6.5 4.5l9 5.5-9 5.5V4.5z"/></svg>
                    Start Server
                </button>
                <button @click="confirmAction('stop')"
                        :disabled="!pathConfigured || !anyDaemonRunning"
                        :style="{ opacity: (!pathConfigured||!anyDaemonRunning) ? 0.4 : 1, cursor: (!pathConfigured||!anyDaemonRunning) ? 'not-allowed' : 'pointer' }"
                        style="display:flex;align-items:center;justify-content:center;gap:.45rem;padding:.65rem;border-radius:7px;border:none;font-weight:700;font-size:.8rem;background:#dc2626;color:#fff;">
                    <svg viewBox="0 0 20 20" fill="currentColor" width="14"><rect x="4" y="4" width="12" height="12" rx="1.5"/></svg>
                    Stop Server
                </button>
                <button @click="confirmAction('clearram')"
                        :disabled="!pathConfigured"
                        :style="{ opacity: !pathConfigured ? 0.4 : 1, cursor: !pathConfigured ? 'not-allowed' : 'pointer' }"
                        style="display:flex;align-items:center;justify-content:center;gap:.45rem;padding:.65rem;border-radius:7px;border:none;font-weight:700;font-size:.8rem;background:#d97706;color:#fff;">
                    <svg viewBox="0 0 20 20" fill="none" width="14" stroke="currentColor" stroke-width="1.8"><path d="M10 3v4M5.5 5.5l2.8 2.8M3 10h4M5.5 14.5l2.8-2.8M10 17v-4M14.5 14.5l-2.8-2.8M17 10h-4M14.5 5.5l-2.8 2.8" stroke-linecap="round"/></svg>
                    Clear RAM
                </button>
                <button @click="confirmAction('backup')"
                        :disabled="!pathConfigured || backupRunning"
                        :style="{ opacity: (!pathConfigured||backupRunning) ? 0.4 : 1, cursor: (!pathConfigured||backupRunning) ? 'not-allowed' : 'pointer' }"
                        style="display:flex;align-items:center;justify-content:center;gap:.45rem;padding:.65rem;border-radius:7px;border:none;font-weight:700;font-size:.8rem;background:#7c3aed;color:#fff;">
                    <svg viewBox="0 0 20 20" fill="none" width="14" stroke="currentColor" stroke-width="1.8"><path d="M10 3v9m0 0l-3-3m3 3l3-3" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 14v1a2 2 0 002 2h8a2 2 0 002-2v-1" stroke-linecap="round"/></svg>
                    <span x-text="backupRunning ? 'Backup...' : 'Backup DB'"></span>
                </button>
            </div>
            </div>{{-- /position:relative wrapper --}}

            {{-- Backup path info --}}
            <div class="sc-info-box" style="font-size:.68rem;color:var(--pw-text-muted);background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:5px;padding:.4rem .7rem;display:flex;align-items:center;gap:.4rem;">
                <svg viewBox="0 0 20 20" fill="none" width="12" stroke="currentColor" stroke-width="1.8" style="flex-shrink:0;opacity:.6"><path d="M10 3v9m0 0l-3-3m3 3l3-3" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 14v1a2 2 0 002 2h8a2 2 0 002-2v-1" stroke-linecap="round"/></svg>
                <span>Backup DB disimpan ke: <code style="font-family:monospace;color:#c4b5fd;">{{ rtrim($serverPath, '/') }}/pw_backup_YYYYMMDD.tar.gz</code></span>
            </div>

            {{-- Alert --}}
            <div x-show="actionMsg" x-transition
                 :style="actionOk
                    ? 'padding:.5rem .8rem;background:rgba(80,200,120,.1);border:1px solid rgba(80,200,120,.3);border-radius:6px;font-size:.78rem;color:#50c878;'
                    : 'padding:.5rem .8rem;background:rgba(220,60,60,.1);border:1px solid rgba(220,60,60,.3);border-radius:6px;font-size:.78rem;color:#e05252;'"
                 x-text="actionMsg">
            </div>
        </div>

        {{-- â•â•â• KANAN: MAPS CONTROL â•â•â• --}}
        <div class="pw-adm-card" style="padding:1.2rem;display:flex;flex-direction:column;gap:.8rem;">

            {{-- Header + Stop All --}}
            <div style="display:flex;align-items:center;justify-content:space-between;">
                <span style="font-size:.7rem;font-weight:700;letter-spacing:.08em;color:var(--pw-text-muted);"><svg viewBox="0 0 20 20" fill="none" width="13" stroke="currentColor" stroke-width="1.6" style="display:inline;vertical-align:middle;margin-right:.3rem;opacity:.7"><path d="M1 4l6-2 6 2 6-2v14l-6 2-6-2-6 2V4z" stroke-linecap="round" stroke-linejoin="round"/><path d="M7 2v14M13 4v14" stroke-linecap="round"/></svg>MAPS CONTROL</span>
                <div style="display:flex;align-items:center;gap:.5rem;">
                    <span style="font-size:.72rem;color:var(--pw-text-muted);">Delay:</span>
                    <input type="number" x-model="stopAllDelay" min="0" max="3600" value="300"
                           class="sc-input"
                           style="width:60px;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);border-radius:5px;
                                  padding:.25rem .4rem;color:#fff;font-size:.75rem;text-align:center;">
                    <span style="font-size:.72rem;color:var(--pw-text-muted);">detik</span>
                    <button @click="confirmAction('stopall')"
                            :disabled="mapsCount === 0"
                            :style="{ opacity: mapsCount === 0 ? 0.4 : 1, cursor: mapsCount === 0 ? 'not-allowed' : 'pointer' }"
                            style="display:flex;align-items:center;gap:.35rem;padding:.35rem .8rem;border-radius:5px;border:none;font-weight:700;font-size:.72rem;background:#dc2626;color:#fff;">
                        <svg viewBox="0 0 20 20" fill="none" width="11" stroke="currentColor" stroke-width="2"><circle cx="10" cy="10" r="8"/><path d="M10 6v4l2.5 2.5" stroke-linecap="round"/></svg>
                        Safe Stop
                    </button>
                </div>
            </div>

            {{-- 2 sub-kolom: Online | Available --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.8rem;">

                {{-- ONLINE MAPS --}}
                <div style="display:flex;flex-direction:column;gap:.4rem;">
                    <div style="font-size:.65rem;font-weight:700;letter-spacing:.07em;color:#50c878;padding-bottom:.3rem;border-bottom:1px solid rgba(80,200,120,.2);display:flex;align-items:center;gap:.3rem;">
                        <svg viewBox="0 0 10 10" width="8" height="8"><circle cx="5" cy="5" r="4" fill="#50c878"/></svg>
                        ONLINE MAPS (<span x-text="mapsCount"></span>)
                    </div>
                    <div style="overflow-y:auto;max-height:600px;display:flex;flex-direction:column;gap:.25rem;">
                        <template x-for="(pid, mapId) in maps" :key="mapId">
                            <div style="display:flex;align-items:center;gap:.35rem;padding:.22rem .4rem;background:rgba(80,200,120,.07);border:1px solid rgba(80,200,120,.18);border-radius:4px;min-width:0;">
                                <input type="checkbox" :value="mapId" x-model="selectedOnline"
                                       style="cursor:pointer;accent-color:#50c878;width:12px;height:12px;flex-shrink:0;">
                                <span style="font-family:monospace;font-size:.75rem;font-weight:700;color:#50c878;flex-shrink:0;" x-text="mapId"></span>
                                <span class="sc-colon" style="font-size:.68rem;color:rgba(255,255,255,.3);flex-shrink:0;">:</span>
                                <span style="font-size:.7rem;color:#94a3b8;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" x-text="allMaps[mapId] ?? ''"></span>
                            </div>
                        </template>
                        <div x-show="mapsCount === 0" style="text-align:center;color:var(--pw-text-muted);font-size:.75rem;padding:1rem 0;">
                            Tidak ada map online
                        </div>
                    </div>
                </div>

                {{-- AVAILABLE MAPS --}}
                <div style="display:flex;flex-direction:column;gap:.4rem;">
                    <div class="sc-avail-header" style="font-size:.65rem;font-weight:700;letter-spacing:.07em;color:var(--pw-text-muted);padding-bottom:.3rem;border-bottom:1px solid rgba(255,255,255,.1);display:flex;align-items:center;gap:.3rem;">
                        <svg viewBox="0 0 10 10" width="8" height="8" style="opacity:.5;"><circle cx="5" cy="5" r="3.5" fill="none" stroke="currentColor" stroke-width="1.5"/></svg>
                        AVAILABLE MAPS
                    </div>
                    <div style="overflow-y:auto;max-height:600px;display:flex;flex-direction:column;gap:.25rem;">
                        <template x-for="[mapId, mapName] in offlineMaps()" :key="mapId">
                            <div class="sc-avail-row" style="display:flex;align-items:center;gap:.35rem;padding:.22rem .4rem;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:4px;min-width:0;">
                                <input type="checkbox" :value="mapId" x-model="selectedAvailable"
                                       style="cursor:pointer;accent-color:#50c878;width:12px;height:12px;flex-shrink:0;">
                                <span style="font-family:monospace;font-size:.75rem;font-weight:600;flex-shrink:0;" x-text="mapId"></span>
                                <span class="sc-colon" style="font-size:.68rem;color:rgba(255,255,255,.3);flex-shrink:0;">:</span>
                                <span style="font-size:.7rem;color:#94a3b8;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" x-text="mapName"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.5rem;margin-top:.4rem;">
                <button @click="stopSelectedMaps()"
                        :disabled="selectedOnline.length === 0"
                        :style="{ opacity: selectedOnline.length === 0 ? 0.4 : 1, cursor: selectedOnline.length === 0 ? 'not-allowed' : 'pointer' }"
                        style="display:flex;align-items:center;justify-content:center;gap:.4rem;padding:.6rem;border-radius:7px;border:none;font-weight:700;font-size:.8rem;background:#dc2626;color:#fff;">
                    <svg viewBox="0 0 20 20" fill="currentColor" width="13"><rect x="4" y="4" width="12" height="12" rx="1.5"/></svg>
                    Stop Selected (<span x-text="selectedOnline.length"></span>)
                </button>
                <button @click="startSelectedMaps()"
                        :disabled="!serverRunning || selectedAvailable.length === 0"
                        :style="{ opacity: (!serverRunning||selectedAvailable.length===0) ? 0.4 : 1, cursor: (!serverRunning||selectedAvailable.length===0) ? 'not-allowed' : 'pointer' }"
                        style="display:flex;align-items:center;justify-content:center;gap:.4rem;padding:.6rem;border-radius:7px;border:none;font-weight:700;font-size:.8rem;background:#16a34a;color:#fff;">
                    <svg viewBox="0 0 20 20" fill="currentColor" width="13"><path d="M6.5 4.5l9 5.5-9 5.5V4.5z"/></svg>
                    Start Selected (<span x-text="selectedAvailable.length"></span>)
                </button>
            </div>
        </div>
    </div>

    {{-- SAFE STOP COUNTDOWN MODAL --}}
    <template x-teleport="body">
    <div x-show="cdModal.active"
         style="position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);z-index:9999;width:360px;text-align:center;background:#1a1d23;border:1px solid rgba(220,60,60,.35);border-radius:16px;padding:2.5rem 2rem;box-shadow:0 0 0 1px rgba(0,0,0,.5),0 24px 60px rgba(0,0,0,.85);">

            {{-- Counting phase --}}
            <div x-show="cdModal.phase === 'counting'">
                <div style="font-size:.68rem;font-weight:800;letter-spacing:.12em;color:#ef4444;margin-bottom:1.5rem;display:flex;align-items:center;justify-content:center;gap:.45rem;">
                    <svg viewBox="0 0 20 20" fill="none" width="14" stroke="currentColor" stroke-width="2"><circle cx="10" cy="10" r="8"/><path d="M10 6v4l2.5 2" stroke-linecap="round"/></svg>
                    SAFE STOP AKTIF
                </div>
                <div style="position:relative;display:inline-flex;align-items:center;justify-content:center;margin-bottom:1.5rem;">
                    <svg width="150" height="150" viewBox="0 0 150 150">
                        <circle cx="75" cy="75" r="62" fill="none" stroke="rgba(255,255,255,.07)" stroke-width="8"/>
                        <circle cx="75" cy="75" r="62" fill="none" stroke="#ef4444" stroke-width="8"
                                stroke-linecap="round"
                                stroke-dasharray="389.56"
                                :stroke-dashoffset="cdModal.total > 0 ? 389.56 * (1 - cdModal.sec / cdModal.total) : 389.56"
                                transform="rotate(-90 75 75)"
                                style="transition:stroke-dashoffset 1s linear;"/>
                    </svg>
                    <div style="position:absolute;text-align:center;">
                        <div style="font-size:3rem;font-weight:900;color:#fff;line-height:1;" x-text="cdModal.sec"></div>
                        <div style="font-size:.62rem;font-weight:600;letter-spacing:.08em;color:var(--pw-text-muted);">DETIK</div>
                    </div>
                </div>
                <div style="font-size:.83rem;color:#94a3b8;line-height:1.6;margin-bottom:.4rem;">Pemain sedang diberi waktu untuk logout.</div>
                <div style="font-size:.75rem;color:var(--pw-text-muted);">Map akan dihentikan setelah hitungan selesai.</div>
            </div>

            {{-- Stopping phase --}}
            <div x-show="cdModal.phase === 'stopping'" style="padding:.5rem 0;">
                <div style="font-size:.68rem;font-weight:800;letter-spacing:.12em;color:#f59e0b;margin-bottom:1.5rem;display:flex;align-items:center;justify-content:center;gap:.45rem;">
                    <svg viewBox="0 0 20 20" fill="none" width="14" stroke="currentColor" stroke-width="1.8"><path d="M10 3v4M5.5 5.5l2.8 2.8M3 10h4M5.5 14.5l2.8-2.8M10 17v-4M14.5 14.5l-2.8-2.8M17 10h-4M14.5 5.5l-2.8 2.8" stroke-linecap="round"/></svg>
                    MENGHENTIKAN MAP...
                </div>
                <div style="display:flex;align-items:center;justify-content:center;margin-bottom:1.5rem;">
                    <div class="pw-spinner" style="width:64px;height:64px;border-radius:50%;border:6px solid rgba(255,255,255,.08);border-top-color:#f59e0b;"></div>
                </div>
                <div style="font-size:.85rem;color:#94a3b8;margin-bottom:.7rem;">Menunggu semua map berhenti...</div>
                <div style="font-size:.95rem;font-weight:700;transition:color .4s;"
                     :style="mapsCount > 0 ? 'color:#f59e0b' : 'color:#50c878'"
                     x-text="mapsCount > 0 ? mapsCount + ' map masih aktif' : 'Semua map berhenti!'"></div>
            </div>

            <div style="display:flex;align-items:center;justify-content:center;gap:.45rem;margin-top:1.8rem;padding:.55rem .9rem;background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.3);border-radius:7px;">
                <svg viewBox="0 0 20 20" fill="currentColor" width="14" style="color:#f59e0b;flex-shrink:0;"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                <span style="font-size:.72rem;font-weight:700;color:#fbbf24;letter-spacing:.01em;">Jangan refresh atau tutup halaman ini!</span>
            </div>
    </div>
    </template>



</div>

<div class="pw-adm-card" style="padding:1rem 1.2rem;margin-top:1rem;">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;margin-bottom:.8rem;">
        <div style="font-size:.82rem;font-weight:700;letter-spacing:.06em;color:var(--pw-text-light);">Audit Log Server Control</div>
        <div style="font-size:.74rem;color:var(--pw-text-muted);">Menampilkan 80 aktivitas terbaru</div>
    </div>

    <div style="overflow:auto;">
        <table style="width:100%;border-collapse:collapse;min-width:980px;">
            <thead>
                <tr>
                    <th style="text-align:left;padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.72rem;color:var(--pw-text-muted);">Waktu</th>
                    <th style="text-align:left;padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.72rem;color:var(--pw-text-muted);">Actor</th>
                    <th style="text-align:left;padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.72rem;color:var(--pw-text-muted);">Role</th>
                    <th style="text-align:left;padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.72rem;color:var(--pw-text-muted);">Panel</th>
                    <th style="text-align:left;padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.72rem;color:var(--pw-text-muted);">Aksi</th>
                    <th style="text-align:left;padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.72rem;color:var(--pw-text-muted);">Map</th>
                    <th style="text-align:right;padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.72rem;color:var(--pw-text-muted);">Delay</th>
                    <th style="text-align:left;padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.72rem;color:var(--pw-text-muted);">Status</th>
                    <th style="text-align:left;padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.72rem;color:var(--pw-text-muted);">Pesan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($actionLogs as $row)
                    <tr>
                        <td style="padding:.55rem;border-bottom:1px solid rgba(255,255,255,.06);font-size:.78rem;white-space:nowrap;">{{ $row->created_at?->format('d/m/Y H:i:s') }}</td>
                        <td style="padding:.55rem;border-bottom:1px solid rgba(255,255,255,.06);font-size:.78rem;">{{ $row->actor_name ?: '-' }}</td>
                        <td style="padding:.55rem;border-bottom:1px solid rgba(255,255,255,.06);font-size:.78rem;text-transform:uppercase;">{{ $row->actor_role ?: '-' }}</td>
                        <td style="padding:.55rem;border-bottom:1px solid rgba(255,255,255,.06);font-size:.78rem;text-transform:uppercase;">{{ $row->panel_area ?: '-' }}</td>
                        <td style="padding:.55rem;border-bottom:1px solid rgba(255,255,255,.06);font-size:.78rem;font-family:monospace;">{{ strtoupper((string) $row->action) }}</td>
                        <td style="padding:.55rem;border-bottom:1px solid rgba(255,255,255,.06);font-size:.78rem;font-family:monospace;">{{ $row->target_map ?: '-' }}</td>
                        <td style="padding:.55rem;border-bottom:1px solid rgba(255,255,255,.06);font-size:.78rem;text-align:right;">{{ (int) $row->delay_seconds }}</td>
                        <td style="padding:.55rem;border-bottom:1px solid rgba(255,255,255,.06);font-size:.78rem;">
                            @if($row->result_ok)
                                <span style="padding:.15rem .45rem;border-radius:999px;background:rgba(80,200,120,.15);color:#50c878;border:1px solid rgba(80,200,120,.35);">OK</span>
                            @else
                                <span style="padding:.15rem .45rem;border-radius:999px;background:rgba(239,68,68,.12);color:#ef4444;border:1px solid rgba(239,68,68,.35);">FAIL</span>
                            @endif
                        </td>
                        <td style="padding:.55rem;border-bottom:1px solid rgba(255,255,255,.06);font-size:.74rem;color:var(--pw-text-muted);max-width:340px;word-break:break-word;">{{ $row->result_message ?: '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="padding:1rem;border-bottom:1px solid rgba(255,255,255,.06);font-size:.82rem;color:var(--pw-text-muted);text-align:center;">Belum ada aktivitas Server Control yang tercatat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
@keyframes pwSpin { to { transform: rotate(360deg); } }
.pw-spinner { animation: pwSpin 1s linear infinite; }
@keyframes pwPulse { 0%,100% { opacity:1; } 50% { opacity:.4; } }
</style>

<script>
function serverCtrl() {
    return {
        pathConfigured: {{ $pathConfigured ? 'true' : 'false' }},
        serverRunning:     {{ $serverRunning ? 'true' : 'false' }},
        anyDaemonRunning:  {{ $anyDaemonRunning ? 'true' : 'false' }},
        backupRunning:  {{ ($processInfo['backupRunning'] ?? false) ? 'true' : 'false' }},
        mapsCount:      {{ count($processInfo['maps'] ?? []) }},
        maps:           {!! json_encode((object)($processInfo['maps'] ?? [])) !!},
        memory:         {!! json_encode($memory) !!},
        allMaps:        {!! json_encode($availableMaps) !!},
        daemons: {!! json_encode(collect($processInfo['daemons'] ?? [])->map(function($d) {
            return ['label' => $d['label'], 'process' => $d['process'], 'count' => $d['count']];
        })) !!},

        stopAllDelay: 300,
        selectedAvailable: [],
        selectedOnline: [],
        busy: false,
        busyMsg: '',
        starting: false,

        // Alert
        actionMsg: '', actionOk: true,
        cdModal: { active: false, sec: 0, total: 0, phase: 'counting' },

        init() {
            setInterval(() => this.refreshStatus(), 5000);
        },

        offlineMaps() {
            return Object.entries(this.allMaps).filter(([id]) => this.maps[id] === undefined);
        },

        ramColor(pct) {
            return pct > 90 ? '#ef4444' : pct > 75 ? '#f59e0b' : '#22c55e';
        },

        launchCountdown(sec) {
            this.cdModal = { active: true, sec: sec, total: sec, phase: 'counting' };
            const tick = setInterval(() => {
                this.cdModal.sec--;
                if (this.cdModal.sec <= 0) {
                    clearInterval(tick);
                    this.cdModal.phase = 'stopping';
                    this.pollUntilStopped();
                }
            }, 1000);
        },

        async pollUntilStopped() {
            await this.refreshStatus();
            if (this.mapsCount === 0) {
                this.cdModal.active = false;
                this.actionMsg = '';
                return;
            }
            const poll = setInterval(async () => {
                await this.refreshStatus();
                if (this.mapsCount === 0) {
                    clearInterval(poll);
                    this.cdModal.active = false;
                    this.actionMsg = '';
                }
            }, 2000);
        },



        async startSelectedMaps() {
            if (this.selectedAvailable.length === 0) return;
            const ids = [...this.selectedAvailable];
            const ok = await window.pwConfirm(`Start ${ids.length} Map?`, `${ids.join(', ')} akan dijalankan.`, 'success');
            if (!ok) return;
            this.selectedAvailable = [];
            for (const id of ids) {
                await this.doAction('startmap', id);
            }
        },

        async stopSelectedMaps() {
            if (this.selectedOnline.length === 0) return;
            const ids = [...this.selectedOnline];
            const ok = await window.pwConfirm(`Stop ${ids.length} Map?`, `${ids.join(', ')} akan dihentikan.`, 'danger');
            if (!ok) return;
            this.selectedOnline = [];
            for (const id of ids) {
                await this.doAction('stopmap', id);
            }
        },

        async confirmAction(action, mapId = null) {
            const variant = ['stop','stopmap','stopall','clearram'].includes(action) ? 'danger'
                          : ['start','startmap'].includes(action) ? 'success' : 'warning';
            const labels = {
                start:    { title: 'Start Server?',       desc: 'Semua daemon game server akan dijalankan.' },
                stop:     { title: 'Stop Server?',        desc: 'Semua daemon dan map akan dihentikan. Pastikan tidak ada pemain!' },
                clearram: { title: 'Clear RAM Cache?',    desc: 'Buffer/cache Linux akan dibersihkan (sync + drop_caches).' },
                backup:   { title: 'Mulai Backup DB?',    desc: 'Backup database + file server berjalan di background.' },
                stopall:  { title: 'Safe Stop Semua Map?', desc: `Timer ${this.stopAllDelay}s akan dikirim. Daemon tetap berjalan, hanya proses gs (map) yang dihentikan.` },
                stopmap:  { title: `Stop Map ${mapId}?`,   desc: `${mapId} — ${this.allMaps[mapId] ?? ''} akan dihentikan paksa.` },
                startmap: { title: `Start Map ${mapId}?`,  desc: `${mapId} — ${this.allMaps[mapId] ?? ''} akan dijalankan.` },
            };
            const l = labels[action] || { title: 'Konfirmasi', desc: '' };
            const ok = await window.pwConfirm(l.title, l.desc, variant);
            if (!ok) return;

            // For stopall: launch countdown IMMEDIATELY after confirm, fire HTTP in background
            if (action === 'stopall') {
                const delay = parseInt(this.stopAllDelay) || 300;
                this.launchCountdown(delay);
                this.doAction(action, mapId); // fire and forget
                return;
            }

            await this.doAction(action, mapId);
        },

        async doAction(action, mapId) {
            this.actionMsg = '';
            this.busy = true;
            this.busyMsg = action === 'stop' ? 'Menghentikan server...'
                         : action === 'clearram' ? 'Membersihkan RAM...'
                         : action === 'backup' ? 'Memulai backup...'
                         : action === 'stopmap' ? `Menghentikan ${mapId}...`
                         : action === 'startmap' ? `Menjalankan ${mapId}...`
                         : 'Memproses...';
            try {
                const body = new URLSearchParams({ action, _token: '{{ csrf_token() }}' });
                if (mapId) body.append('map', mapId);
                if (action === 'stopall') body.append('delay', this.stopAllDelay);

                const res  = await fetch('{{ route("admin.server-control.action") }}', { method: 'POST', body });
                const data = await res.json();
                this.actionOk  = data.ok;
                this.actionMsg = data.message;
                if (action === 'start') {
                    this.starting = true;
                    // Poll until all 8 non-map daemons are online, then clear message
                    const NON_MAP = ['logservice','authd','uniquenamed','gacd','gfactiond','gdeliveryd','glinkd','gamedbd','gs'];
                    let elapsed = 0;
                    const poll = setInterval(async () => {
                        elapsed += 3;
                        await this.refreshStatus();
                        const allOnline = NON_MAP.every(k => (this.daemons[k]?.count ?? 0) > 0);
                        if (allOnline || elapsed >= 180) {
                            clearInterval(poll);
                            this.starting = false;
                            this.actionOk  = true;
                            this.actionMsg = allOnline ? 'Server sudah Online!' : 'Timeout menunggu daemon.';
                            setTimeout(() => { this.actionMsg = ''; }, 4000);
                        }
                    }, 3000);
                } else if (action === 'stop') {
                    // Poll until all daemons offline, then clear message
                    const ALL_D = ['logservice','authd','uniquenamed','gacd','gfactiond','gdeliveryd','glinkd','gamedbd','gs'];
                    let elapsed = 0;
                    const poll = setInterval(async () => {
                        elapsed += 3;
                        await this.refreshStatus();
                        const allOff = ALL_D.every(k => (this.daemons[k]?.count ?? 0) === 0);
                        if (allOff || elapsed >= 60) {
                            clearInterval(poll);
                            this.actionOk  = true;
                            this.actionMsg = allOff ? 'Server sudah Offline.' : 'Perintah stop dikirim.';
                            setTimeout(() => { this.actionMsg = ''; }, 3000);
                        }
                    }, 3000);
                } else if (action !== 'stopall') {
                    setTimeout(() => this.refreshStatus(), 2000);
                    setTimeout(() => { this.actionMsg = ''; }, 5000);
                }
            } catch (e) {
                this.actionOk = false; this.actionMsg = 'Terjadi kesalahan jaringan.';
            } finally {
                this.busy = false;
                this.busyMsg = '';
            }
        },

        async refreshStatus() {
            try {
                const data = await fetch('{{ route("admin.server-control.status") }}').then(r => r.json());
                this.serverRunning = data.server_running;
                this.backupRunning = data.backup_running;
                this.mapsCount     = data.maps_count;
                this.maps          = data.maps;
                this.memory        = data.memory;
                for (const [key, count] of Object.entries(data.daemons)) {
                    if (this.daemons[key] !== undefined) this.daemons[key].count = count;
                }
            } catch (e) {}
        },
    };
}
</script>

@push('styles')
<style>
/* ── Server Control light mode ── */
[data-theme="light"] .sc-input {
  background: #ffffff !important;
  border-color: rgba(0,0,0,.22) !important;
  color: var(--pw-text) !important;
}
[data-theme="light"] .sc-val {
  color: var(--pw-text-light) !important;
}
[data-theme="light"] .sc-membox {
  background: rgba(0,0,0,.06) !important;
}
[data-theme="light"] .sc-bar-bg {
  background: rgba(0,0,0,.1) !important;
}
[data-theme="light"] .sc-daemon-row {
  background: rgba(0,0,0,.04) !important;
  border: 1px solid rgba(0,0,0,.09) !important;
}
[data-theme="light"] .sc-info-box {
  background: rgba(0,0,0,.04) !important;
  border-color: rgba(0,0,0,.12) !important;
}
[data-theme="light"] .sc-avail-row {
  background: rgba(0,0,0,.04) !important;
  border-color: rgba(0,0,0,.1) !important;
}
[data-theme="light"] .sc-avail-header {
  border-bottom-color: rgba(0,0,0,.15) !important;
}
[data-theme="light"] .sc-colon {
  color: rgba(0,0,0,.3) !important;
}
</style>
@endpush
@endsection
