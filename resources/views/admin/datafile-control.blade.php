@extends('layouts.admin')

@section('title', 'DATAFILE Control')

@push('styles')
<style>
.df-input {
    width: 100%;
    border-radius: 6px;
    padding: .5rem .75rem;
    font-size: .84rem;
    border: 1px solid rgba(255,255,255,.12);
    background: rgba(255,255,255,.05);
    color: var(--pw-text-light);
    transition: border-color .15s, box-shadow .15s;
}

.df-input:focus {
    outline: none;
    border-color: rgba(240,165,0,.5);
    box-shadow: 0 0 0 2px rgba(240,165,0,.12);
}

/* Match Game Config dropdown style */
.df-dropdown-btn {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: rgba(255,255,255,.05);
    border: 1px solid rgba(255,255,255,.12);
    border-radius: 6px;
    padding: .45rem .7rem;
    color: #fff;
    font-size: .85rem;
    cursor: pointer;
    transition: border-color .15s, box-shadow .15s;
    text-align: left;
}

.df-dropdown-btn:hover {
    border-color: rgba(255,255,255,.25);
}

.df-dropdown-btn:focus {
    outline: none;
    border-color: rgba(240,165,0,.5);
    box-shadow: 0 0 0 2px rgba(240,165,0,.12);
}

.df-dropdown-menu {
    position: absolute;
    top: calc(100% + 4px);
    left: 0;
    right: 0;
    z-index: 50;
    background: var(--pw-bg-card);
    border: 1px solid var(--pw-border);
    border-radius: 8px;
    padding: 4px;
    max-height: 240px;
    overflow-y: auto;
    box-shadow: 0 8px 24px rgba(0,0,0,.5);
}

.df-dropdown-menu::-webkit-scrollbar {
    width: 5px;
}

.df-dropdown-menu::-webkit-scrollbar-track {
    background: transparent;
}

.df-dropdown-menu::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,.12);
    border-radius: 3px;
}

.df-dropdown-item {
    display: block;
    width: 100%;
    text-align: left;
    padding: .42rem .7rem;
    background: transparent;
    border: none;
    color: #cbd5e1;
    font-size: .84rem;
    cursor: pointer;
    border-radius: 5px;
    transition: background .1s, color .1s;
}

.df-dropdown-item:hover {
    background: rgba(240,165,0,.12);
    color: #fff;
}

.df-dropdown-item--active {
    background: rgba(240,165,0,.18);
    color: #f0a500;
    font-weight: 600;
}

[data-theme="light"] .df-input {
    background: #ffffff;
    border-color: #cbd5e1;
    color: #0f172a;
}

[data-theme="light"] .df-input:focus {
    border-color: rgba(138,94,0,.5);
    box-shadow: 0 0 0 2px rgba(138,94,0,.1);
}

[data-theme="light"] .df-dropdown-btn {
    background: #fff;
    border-color: rgba(0,0,0,.2);
    color: var(--pw-text);
}

[data-theme="light"] .df-dropdown-btn:hover {
    border-color: rgba(0,0,0,.35);
}

[data-theme="light"] .df-dropdown-menu {
    box-shadow: 0 8px 24px rgba(0,0,0,.12);
}

[data-theme="light"] .df-dropdown-menu::-webkit-scrollbar-thumb {
    background: rgba(0,0,0,.15);
}

[data-theme="light"] .df-dropdown-item {
    color: var(--pw-text);
}

[data-theme="light"] .df-dropdown-item:hover {
    background: rgba(138,94,0,.1);
    color: var(--pw-text);
}

[data-theme="light"] .df-dropdown-item--active {
    background: rgba(138,94,0,.12);
    color: var(--pw-gold);
}

[data-theme="light"] .df-input::placeholder {
    color: #64748b;
}

.df-progress-wrap {
    display: none;
    margin-top: .5rem;
}

.df-progress-bar {
    height: 10px;
    border-radius: 999px;
    background: rgba(148,163,184,.25);
    overflow: hidden;
}

.df-progress-fill {
    width: 0%;
    height: 100%;
    background: linear-gradient(90deg, #f59e0b, #fbbf24);
    transition: width .15s linear;
}
</style>
@endpush

@section('content')
<div style="display:grid;gap:1rem;">

    <div class="pw-adm-card" style="padding:1rem 1.2rem;">
        <div style="display:flex;justify-content:space-between;gap:1rem;flex-wrap:wrap;align-items:center;">
            <div>
                <div style="font-size:.76rem;font-weight:700;letter-spacing:.06em;color:var(--pw-text-muted);margin-bottom:.25rem;">PW DATAFILE TARGET</div>
                <div style="font-family:monospace;font-size:.9rem;color:var(--pw-text-light);">{{ $datafilePath }}</div>
                <div style="font-size:.75rem;color:var(--pw-text-muted);margin-top:.2rem;">Script: {{ $replaceScript }}</div>
            </div>
            <div style="padding:.35rem .6rem;border-radius:6px;background:rgba(245,158,11,.12);border:1px solid rgba(245,158,11,.3);font-size:.74rem;color:#f59e0b;">
                Update DATAFILE wajib restart server game
            </div>
        </div>

        @if($canEditPath)
            <form method="POST" action="{{ route('admin.datafile-control.path') }}" style="margin-top:.9rem;display:flex;gap:.6rem;flex-wrap:wrap;align-items:center;">
                @csrf
                <input type="text" name="datafile_path" value="{{ $datafilePath }}"
                       class="df-input"
                       style="flex:1;min-width:280px;font-family:monospace;"
                       placeholder="/home/pw_server155/gamed/config/DATAFILE">
                <button type="submit" class="pw-adm-btn">Simpan Path DATAFILE</button>
            </form>
            @error('datafile_path')
                <div style="font-size:.78rem;color:#ef4444;margin-top:.45rem;">{{ $message }}</div>
            @enderror
        @endif
    </div>

    <div class="pw-adm-card" style="padding:1rem 1.2rem;">
        <div style="font-size:.82rem;font-weight:700;letter-spacing:.06em;color:var(--pw-text-light);margin-bottom:.8rem;">Upload / Replace DATAFILE</div>

        <div id="df-upload-msg" style="display:none;margin-bottom:.7rem;padding:.55rem .7rem;border-radius:6px;font-size:.8rem;"></div>

        <form id="df-upload-form" method="POST" action="{{ route('admin.datafile-control.upload') }}" enctype="multipart/form-data" style="display:grid;gap:.8rem;max-width:760px;">
            @csrf

            <div>
                <label style="font-size:.75rem;color:var(--pw-text-muted);display:block;margin-bottom:.3rem;">Target File</label>
                @php($selectedTarget = old('target_file', $allowedFiles[0] ?? ''))
                <div x-data="{ open: false, targetFile: @js($selectedTarget) }" style="position:relative;" @click.away="open = false">
                    <input type="hidden" name="target_file" :value="targetFile">
                    <button @click="open = !open" type="button" class="df-dropdown-btn">
                        <span x-text="targetFile || 'Pilih target file'"></span>
                        <svg viewBox="0 0 12 12" width="10" fill="currentColor" style="opacity:.5;flex-shrink:0;transition:transform .15s;" :style="open && 'transform:rotate(180deg)'"><path d="M2 4l4 4 4-4"/></svg>
                    </button>
                    <div x-show="open" x-transition.opacity.duration.150ms class="df-dropdown-menu">
                        @foreach($allowedFiles as $name)
                            <button type="button"
                                    @click="targetFile = @js($name); open = false"
                                    class="df-dropdown-item"
                                    :class="targetFile === @js($name) && 'df-dropdown-item--active'">{{ $name }}</button>
                        @endforeach
                    </div>
                </div>
                @error('target_file')
                    <div style="font-size:.78rem;color:#ef4444;margin-top:.35rem;">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label style="font-size:.75rem;color:var(--pw-text-muted);display:block;margin-bottom:.3rem;">File Upload</label>
                <input type="file" name="datafile" required class="df-input" style="padding:.45rem .6rem;font-size:.82rem;">
                @error('datafile')
                    <div style="font-size:.78rem;color:#ef4444;margin-top:.35rem;">{{ $message }}</div>
                @enderror
            </div>

            <div style="display:flex;gap:.6rem;align-items:center;flex-wrap:wrap;">
                <button id="df-upload-btn" type="submit" class="pw-adm-btn">Upload dan Replace</button>
                <span style="font-size:.74rem;color:var(--pw-text-muted);">Backup otomatis dibuat sebelum file diganti.</span>
            </div>

            <div id="df-progress-wrap" class="df-progress-wrap">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.35rem;">
                    <span style="font-size:.74rem;color:var(--pw-text-muted);">Upload progress</span>
                    <span id="df-progress-text" style="font-size:.8rem;font-weight:700;color:var(--pw-text-light);">0%</span>
                </div>
                <div class="df-progress-bar">
                    <div id="df-progress-fill" class="df-progress-fill"></div>
                </div>
            </div>
        </form>
    </div>

    {{-- ====== NPC GEN DATA (WORLD) ====== --}}
    <div class="pw-adm-card" style="padding:1rem 1.2rem;">
        <div style="display:flex;justify-content:space-between;gap:1rem;flex-wrap:wrap;align-items:center;">
            <div>
                <div style="font-size:.76rem;font-weight:700;letter-spacing:.06em;color:var(--pw-text-muted);margin-bottom:.25rem;">NPCGEN.DATA (WORLD)</div>
                <div style="font-family:monospace;font-size:.9rem;color:var(--pw-text-light);">{{ $worldPath }}/npcgen.data</div>
                <div style="font-size:.75rem;color:var(--pw-text-muted);margin-top:.2rem;">Script: {{ $npcgenScript }}</div>
            </div>
            <div style="padding:.35rem .6rem;border-radius:6px;background:rgba(99,102,241,.12);border:1px solid rgba(99,102,241,.3);font-size:.74rem;color:#818cf8;">
                Update npcgen.data wajib restart server game
            </div>
        </div>
    </div>

    <div class="pw-adm-card" style="padding:1rem 1.2rem;">
        <div style="font-size:.82rem;font-weight:700;letter-spacing:.06em;color:var(--pw-text-light);margin-bottom:.8rem;">Upload / Replace npcgen.data</div>

        <div id="ng-upload-msg" style="display:none;margin-bottom:.7rem;padding:.55rem .7rem;border-radius:6px;font-size:.8rem;"></div>

        <form id="ng-upload-form" method="POST" action="{{ route('admin.datafile-control.npcgen') }}" enctype="multipart/form-data" style="display:grid;gap:.8rem;max-width:760px;">
            @csrf
            <div>
                <label style="font-size:.75rem;color:var(--pw-text-muted);display:block;margin-bottom:.3rem;">File Upload (npcgen.data)</label>
                <input type="file" name="npcgen_file" required class="df-input" style="padding:.45rem .6rem;font-size:.82rem;">
                @error('npcgen_file')
                    <div style="font-size:.78rem;color:#ef4444;margin-top:.35rem;">{{ $message }}</div>
                @enderror
            </div>

            <div style="display:flex;gap:.6rem;align-items:center;flex-wrap:wrap;">
                <button id="ng-upload-btn" type="submit" class="pw-adm-btn">Upload dan Replace npcgen.data</button>
                <span style="font-size:.74rem;color:var(--pw-text-muted);">Backup otomatis dibuat sebelum file diganti.</span>
            </div>

            <div id="ng-progress-wrap" class="df-progress-wrap">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.35rem;">
                    <span style="font-size:.74rem;color:var(--pw-text-muted);">Upload progress</span>
                    <span id="ng-progress-text" style="font-size:.8rem;font-weight:700;color:var(--pw-text-light);">0%</span>
                </div>
                <div class="df-progress-bar">
                    <div id="ng-progress-fill" class="df-progress-fill"></div>
                </div>
            </div>
        </form>
    </div>

    {{-- ====== RIWAYAT NPCGEN ====== --}}
    <div class="pw-adm-card" style="padding:1rem 1.2rem;">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;margin-bottom:.8rem;">
            <div style="font-size:.82rem;font-weight:700;letter-spacing:.06em;color:var(--pw-text-light);">Riwayat Update npcgen.data</div>
            <div style="font-size:.74rem;color:var(--pw-text-muted);">Terakhir 30 aktivitas</div>
        </div>
        <div style="overflow:auto;">
            <table style="width:100%;border-collapse:collapse;min-width:700px;">
                <thead>
                    <tr>
                        <th style="text-align:left;padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.72rem;color:var(--pw-text-muted);">Waktu</th>
                        <th style="text-align:left;padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.72rem;color:var(--pw-text-muted);">Actor</th>
                        <th style="text-align:left;padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.72rem;color:var(--pw-text-muted);">Role</th>
                        <th style="text-align:left;padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.72rem;color:var(--pw-text-muted);">File Asal</th>
                        <th style="text-align:right;padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.72rem;color:var(--pw-text-muted);">Size</th>
                        <th style="text-align:left;padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.72rem;color:var(--pw-text-muted);">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($npcgenLogs as $row)
                        <tr>
                            <td style="padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.78rem;white-space:nowrap;">{{ $row->created_at?->format('d/m/Y H:i:s') }}</td>
                            <td style="padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.78rem;">{{ $row->actor_name }}</td>
                            <td style="padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.78rem;text-transform:uppercase;">{{ $row->actor_role }}</td>
                            <td style="padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.78rem;">{{ $row->original_name ?: '-' }}</td>
                            <td style="padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.78rem;text-align:right;">{{ number_format((int) $row->file_size) }}</td>
                            <td style="padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.78rem;">
                                @if($row->status === 'success')
                                    <span style="padding:.15rem .45rem;border-radius:999px;background:rgba(80,200,120,.15);color:#50c878;border:1px solid rgba(80,200,120,.35);">SUCCESS</span>
                                @else
                                    <span style="padding:.15rem .45rem;border-radius:999px;background:rgba(239,68,68,.12);color:#ef4444;border:1px solid rgba(239,68,68,.35);">FAILED</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding:1rem;border-bottom:1px solid var(--pw-border);font-size:.82rem;color:var(--pw-text-muted);text-align:center;">Belum ada riwayat update npcgen.data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="pw-adm-card" style="padding:1rem 1.2rem;">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;margin-bottom:.8rem;">
            <div style="font-size:.82rem;font-weight:700;letter-spacing:.06em;color:var(--pw-text-light);">Riwayat Update DATAFILE</div>
            <div style="font-size:.74rem;color:var(--pw-text-muted);">Terakhir 50 aktivitas</div>
        </div>

        <div style="overflow:auto;">
            <table style="width:100%;border-collapse:collapse;min-width:920px;">
                <thead>
                    <tr>
                        <th style="text-align:left;padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.72rem;color:var(--pw-text-muted);">Waktu</th>
                        <th style="text-align:left;padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.72rem;color:var(--pw-text-muted);">Actor</th>
                        <th style="text-align:left;padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.72rem;color:var(--pw-text-muted);">Role</th>
                        <th style="text-align:left;padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.72rem;color:var(--pw-text-muted);">Target</th>
                        <th style="text-align:left;padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.72rem;color:var(--pw-text-muted);">File Asal</th>
                        <th style="text-align:right;padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.72rem;color:var(--pw-text-muted);">Size</th>
                        <th style="text-align:left;padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.72rem;color:var(--pw-text-muted);">Status</th>
                        <th style="text-align:left;padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.72rem;color:var(--pw-text-muted);">Output</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $row)
                        <tr>
                            <td style="padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.78rem;white-space:nowrap;">{{ $row->created_at?->format('d/m/Y H:i:s') }}</td>
                            <td style="padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.78rem;">{{ $row->actor_name }}</td>
                            <td style="padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.78rem;text-transform:uppercase;">{{ $row->actor_role }}</td>
                            <td style="padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.78rem;font-family:monospace;">{{ $row->target_file }}</td>
                            <td style="padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.78rem;">{{ $row->original_name ?: '-' }}</td>
                            <td style="padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.78rem;text-align:right;">{{ number_format((int) $row->file_size) }}</td>
                            <td style="padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.78rem;">
                                @if($row->status === 'success')
                                    <span style="padding:.15rem .45rem;border-radius:999px;background:rgba(80,200,120,.15);color:#50c878;border:1px solid rgba(80,200,120,.35);">SUCCESS</span>
                                @else
                                    <span style="padding:.15rem .45rem;border-radius:999px;background:rgba(239,68,68,.12);color:#ef4444;border:1px solid rgba(239,68,68,.35);">FAILED</span>
                                @endif
                            </td>
                            <td style="padding:.55rem;border-bottom:1px solid var(--pw-border);font-size:.74rem;color:var(--pw-text-muted);max-width:320px;word-break:break-word;">{{ $row->script_output ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="padding:1rem;border-bottom:1px solid var(--pw-border);font-size:.82rem;color:var(--pw-text-muted);text-align:center;">Belum ada riwayat update DATAFILE.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
(function () {
    const form = document.getElementById('df-upload-form');
    if (!form) return;

    const btn = document.getElementById('df-upload-btn');
    const wrap = document.getElementById('df-progress-wrap');
    const fill = document.getElementById('df-progress-fill');
    const text = document.getElementById('df-progress-text');
    const msg = document.getElementById('df-upload-msg');

    const showMsg = (ok, message) => {
        msg.style.display = 'block';
        msg.textContent = message;
        if (ok) {
            msg.style.background = 'rgba(80,200,120,.12)';
            msg.style.border = '1px solid rgba(80,200,120,.35)';
            msg.style.color = '#16a34a';
        } else {
            msg.style.background = 'rgba(239,68,68,.12)';
            msg.style.border = '1px solid rgba(239,68,68,.35)';
            msg.style.color = '#dc2626';
        }
    };

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const fileInput = form.querySelector('input[name="datafile"]');
        if (!fileInput || !fileInput.files || !fileInput.files.length) {
            showMsg(false, 'Pilih file dulu sebelum upload.');
            return;
        }

        msg.style.display = 'none';
        wrap.style.display = 'block';
        fill.style.width = '0%';
        text.textContent = '0%';
        btn.disabled = true;
        btn.textContent = 'Uploading...';

        const xhr = new XMLHttpRequest();
        xhr.open('POST', form.action, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.setRequestHeader('Accept', 'application/json');

        xhr.upload.onprogress = function (ev) {
            if (!ev.lengthComputable) return;
            const pct = Math.round((ev.loaded / ev.total) * 100);
            fill.style.width = pct + '%';
            text.textContent = pct + '%';
        };

        xhr.onload = function () {
            btn.disabled = false;
            btn.textContent = 'Upload dan Replace';

            let payload = null;
            try { payload = JSON.parse(xhr.responseText); } catch (_) {}

            if (xhr.status >= 200 && xhr.status < 300 && payload && payload.ok) {
                fill.style.width = '100%';
                text.textContent = '100%';
                showMsg(true, payload.message || 'Upload berhasil.');
                setTimeout(() => window.location.reload(), 900);
                return;
            }

            const err = payload?.message || payload?.error || 'Upload gagal. Silakan cek log server.';
            showMsg(false, err);
        };

        xhr.onerror = function () {
            btn.disabled = false;
            btn.textContent = 'Upload dan Replace';
            showMsg(false, 'Koneksi error saat upload. Coba lagi.');
        };

        xhr.send(new FormData(form));
    });
})();

/* ---- NPC Gen upload handler ---- */
(function () {
    const form = document.getElementById('ng-upload-form');
    if (!form) return;

    const btn = document.getElementById('ng-upload-btn');
    const wrap = document.getElementById('ng-progress-wrap');
    const fill = document.getElementById('ng-progress-fill');
    const text = document.getElementById('ng-progress-text');
    const msg = document.getElementById('ng-upload-msg');

    const showMsg = (ok, message) => {
        msg.style.display = 'block';
        msg.textContent = message;
        if (ok) {
            msg.style.background = 'rgba(80,200,120,.12)';
            msg.style.border = '1px solid rgba(80,200,120,.35)';
            msg.style.color = '#16a34a';
        } else {
            msg.style.background = 'rgba(239,68,68,.12)';
            msg.style.border = '1px solid rgba(239,68,68,.35)';
            msg.style.color = '#dc2626';
        }
    };

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const fileInput = form.querySelector('input[name="npcgen_file"]');
        if (!fileInput || !fileInput.files || !fileInput.files.length) {
            showMsg(false, 'Pilih file npcgen.data dulu sebelum upload.');
            return;
        }

        msg.style.display = 'none';
        wrap.style.display = 'block';
        fill.style.width = '0%';
        text.textContent = '0%';
        btn.disabled = true;
        btn.textContent = 'Uploading...';

        const xhr = new XMLHttpRequest();
        xhr.open('POST', form.action, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.setRequestHeader('Accept', 'application/json');

        xhr.upload.onprogress = function (ev) {
            if (!ev.lengthComputable) return;
            const pct = Math.round((ev.loaded / ev.total) * 100);
            fill.style.width = pct + '%';
            text.textContent = pct + '%';
        };

        xhr.onload = function () {
            btn.disabled = false;
            btn.textContent = 'Upload dan Replace npcgen.data';

            let payload = null;
            try { payload = JSON.parse(xhr.responseText); } catch (_) {}

            if (xhr.status >= 200 && xhr.status < 300 && payload && payload.ok) {
                fill.style.width = '100%';
                text.textContent = '100%';
                showMsg(true, payload.message || 'Upload berhasil.');
                setTimeout(() => window.location.reload(), 900);
                return;
            }

            const err = payload?.message || payload?.error || 'Upload gagal. Silakan cek log server.';
            showMsg(false, err);
        };

        xhr.onerror = function () {
            btn.disabled = false;
            btn.textContent = 'Upload dan Replace npcgen.data';
            showMsg(false, 'Koneksi error saat upload. Coba lagi.');
        };

        xhr.send(new FormData(form));
    });
})();
</script>
@endpush
