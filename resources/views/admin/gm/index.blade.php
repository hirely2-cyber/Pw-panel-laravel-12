@extends('layouts.admin')
@section('title', 'Manajemen Staff')

@section('content')
<div class="pw-adm-cols" style="align-items:flex-start;gap:1.5rem;">

    {{-- LEFT: Promote form --}}
    <div style="flex:0 0 280px;">
        <div class="pw-adm-card">
            <div class="pw-adm-card__title">Angkat Staff Baru</div>
            <form action="{{ route('admin.gm.promote') }}" method="POST">
                @csrf
                <label class="pw-form__label">Username</label>
                <input type="text" name="username" class="pw-form__input" required
                       placeholder="Masukkan username" value="{{ old('username') }}"
                       style="margin-bottom:.8rem;">
                @error('username') <p style="color:#e05252;font-size:.75rem;margin-top:-.5rem;margin-bottom:.6rem;">{{ $message }}</p> @enderror
                <label class="pw-form__label">Role</label>
                <select name="role" class="pw-form__input" required style="margin-bottom:.8rem;">
                    <option value="webadmin">Web Admin</option>
                    <option value="gm" selected>Game Master (GM)</option>
                </select>
                @error('role') <p style="color:#e05252;font-size:.75rem;margin-top:-.5rem;margin-bottom:.6rem;">{{ $message }}</p> @enderror
                <button type="submit" class="pw-adm-btn" style="width:100%;">Angkat Staff</button>
            </form>

            <div style="margin-top:.8rem;padding-top:.8rem;border-top:1px solid var(--pw-border);">
                <div style="font-size:.72rem;color:var(--pw-text-muted);line-height:1.6;">
                    <strong style="color:var(--pw-text);">Web Admin</strong> — Bisa kelola konten panel (berita, shop, vote, voucher, layanan, ranking, member read-only).<br>
                    <strong style="color:var(--pw-text);">GM</strong> — Bisa kelola artikel & lihat member di GM Panel. Permission in-game bisa diatur terpisah.
                </div>
            </div>
        </div>

        @if (!$gameDbOk)
        <div class="pw-adm-alert pw-adm-alert--error" style="margin-top:.8rem;font-size:.78rem;">
            Koneksi ke Game DB gagal.<br>
            Permission in-game tidak dapat dikelola. Pastikan konfigurasi <code>mysql_game</code> benar di <code>.env</code>.
        </div>
        @else
        <div class="pw-adm-alert" style="margin-top:.8rem;font-size:.78rem;">
            Game DB terhubung. Permission in-game aktif.
        </div>
        @endif

        <div class="pw-adm-card" style="margin-top:.8rem;">
            <div class="pw-adm-card__title" style="font-size:.8rem;">Daftar Permission</div>
            @foreach($perms as $rid => $desc)
            <div class="pw-perm-row" style="display:flex;align-items:center;gap:.5rem;font-size:.72rem;padding:.25rem 0;">
                <span class="pw-perm-rid" style="border-radius:4px;padding:1px 5px;min-width:28px;text-align:center;">{{ $rid }}</span>
                <span style="color:var(--pw-text-muted);">{{ $desc }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- RIGHT: Current GMs table --}}
    <div style="flex:1;min-width:0;">
        <div class="pw-adm-card">
            <div class="pw-adm-card__title">Daftar Staff ({{ $gms->count() }})</div>

            @if($gms->isEmpty())
                <p style="color:var(--pw-text-muted);font-size:.82rem;">Belum ada staff terdaftar.</p>
            @else
            <div style="overflow-x:auto;">
                <table class="pw-adm-tbl" style="width:100%;">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Role</th>
                            @if($gameDbOk) <th>Permission In-Game</th> @endif
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($gms as $gm)
                        <tr>
                            <td>
                                <strong>{{ $gm->name }}</strong><br>
                                <span style="font-size:.72rem;color:var(--pw-text-muted);">{{ $gm->email }}</span>
                            </td>
                            <td>
                                @if($gm->role === 'admin')
                                <span class="pw-badge pw-badge--admin">SUPERADMIN</span>
                                @elseif($gm->role === 'webadmin')
                                <span class="pw-badge pw-badge--webadmin">WEB ADMIN</span>
                                @else
                                <span class="pw-badge pw-badge--active">GM</span>
                                @endif
                            </td>

                            @if($gameDbOk)
                            <td>
                                <form action="{{ route('admin.gm.perms', $gm->ID) }}" method="POST" id="perm-form-{{ $gm->ID }}" style="display:none;">
                                    @csrf
                                    @foreach($perms as $rid => $desc)
                                    <label style="display:flex;align-items:center;gap:.4rem;font-size:.73rem;margin-bottom:.2rem;">
                                        <input type="checkbox" name="rids[]" value="{{ $rid }}"
                                            {{ in_array($rid, $authRows[$gm->ID] ?? []) ? 'checked' : '' }}
                                            onchange="document.getElementById('perm-form-{{ $gm->ID }}').dispatchEvent(new Event('change'))">
                                        <span>[{{ $rid }}] {{ $desc }}</span>
                                    </label>
                                    @endforeach
                                </form>

                                <div id="perm-display-{{ $gm->ID }}">
                                    @forelse($authRows[$gm->ID] ?? [] as $rid)
                                        <span class="pw-perm-active-rid" style="border-radius:3px;padding:1px 5px;font-size:.7rem;margin:1px;">{{ $rid }}</span>
                                    @empty
                                        <span style="color:var(--pw-text-muted);font-size:.75rem;">Tidak ada</span>
                                    @endforelse
                                </div>

                                <button type="button" onclick="togglePerms({{ $gm->ID }})"
                                        class="pw-perm-edit-btn" style="margin-top:.4rem;font-size:.72rem;border-radius:4px;padding:2px 8px;cursor:pointer;">
                                    Edit Permission
                                </button>
                                <button type="button" id="save-perm-{{ $gm->ID }}" onclick="savePerms({{ $gm->ID }})"
                                        class="pw-perm-save-btn" style="display:none;margin-top:.4rem;font-size:.72rem;border-radius:4px;padding:2px 8px;cursor:pointer;">
                                    Simpan
                                </button>
                            </td>
                            @endif

                            <td>
                                @if($gm->role !== 'admin')
                                <form action="{{ route('admin.gm.demote', $gm->ID) }}" method="POST"
                                      data-confirm="Demote Staff|Demote {{ $gm->name }} menjadi player biasa?"
                                      data-confirm-ok="Ya, Demote">
                                    @csrf
                                    <button type="submit" class="pw-adm-btn pw-adm-btn--danger" style="font-size:.75rem;padding:.3rem .7rem;">
                                        Demote
                                    </button>
                                </form>
                                @else
                                <span style="font-size:.72rem;color:var(--pw-text-muted);">Superadmin</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>

@if($gameDbOk)
<script>
function togglePerms(id) {
    const form = document.getElementById('perm-form-' + id);
    const display = document.getElementById('perm-display-' + id);
    const saveBtn = document.getElementById('save-perm-' + id);
    const isHidden = form.style.display === 'none';
    form.style.display = isHidden ? 'block' : 'none';
    display.style.display = isHidden ? 'none' : 'block';
    saveBtn.style.display = isHidden ? 'inline-block' : 'none';
}

function savePerms(id) {
    document.getElementById('perm-form-' + id).submit();
}
</script>
@endif
@endsection
