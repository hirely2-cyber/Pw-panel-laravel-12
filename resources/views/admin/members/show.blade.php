@extends('layouts.admin')
@section('title', 'Detail Member: ' . $user->name)

@section('content')
{{-- Back Button --}}
<div style="margin-bottom:1rem;">
    <a href="{{ route('admin.members.index') }}" class="pw-adm-btn pw-adm-btn--ghost" style="display:inline-flex;align-items:center;gap:.4rem;">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5m0 0l7 7m-7-7l7-7"/></svg>
        Kembali
    </a>
</div>

<div style="display:grid;grid-template-columns:1fr 2fr;gap:1.5rem;align-items:start;">

    {{-- Profile Card --}}
    <div class="pw-adm-card">
        <div class="pw-adm-card__title">Profil</div>
        <div style="text-align:center;margin-bottom:1.2rem;">
            <div style="width:64px;height:64px;border-radius:50%;background:var(--pw-gold-dark,#6b5420);display:inline-flex;align-items:center;justify-content:center;font-size:1.6rem;font-weight:700;color:#b89d4f;margin-bottom:.6rem;">
                {{ strtoupper(substr($user->name,0,1)) }}
            </div>
            <div style="font-weight:600;">{{ $user->name }}</div>
            <div style="color:var(--pw-text-muted);font-size:.8rem;">{{ $user->email }}</div>
            <div style="margin-top:.4rem;">
                @if($user->role === 'admin')
                    <span class="pw-badge pw-badge--danger">Admin</span>
                @elseif($user->role === 'gm')
                    <span class="pw-badge pw-badge--warning">GM</span>
                @else
                    <span class="pw-badge">Player</span>
                @endif
            </div>
        </div>

        <table style="width:100%;font-size:.82rem;border-collapse:collapse;">
            <tr><td style="color:var(--pw-text-muted);padding:.3rem 0;">Gold Points</td><td style="font-weight:600;color:#b89d4f;">{{ number_format($user->money) }}</td></tr>
            <tr>
                <td style="color:var(--pw-text-muted);padding:.3rem 0;">Cubi Gold</td>
                <td style="font-weight:600;color:#4fad84;">
                    @if($cubiData)
                        {{ number_format(($cubiData['cash_add'] + $cubiData['cash_buy'] - $cubiData['cash_used'] - $cubiData['cash_sell']) / 100) }}
                    @else
                        <span style="color:var(--pw-text-muted);font-weight:400;">Offline</span>
                    @endif
                </td>
            </tr>
            @if($cubiData)
            <tr><td style="color:var(--pw-text-muted);padding:.3rem 0;font-size:.75rem;">- Total Top-up</td><td style="font-size:.75rem;">{{ number_format($cubiData['cash_add'] / 100) }}</td></tr>
            <tr><td style="color:var(--pw-text-muted);padding:.3rem 0;font-size:.75rem;">- Digunakan</td><td style="font-size:.75rem;">{{ number_format($cubiData['cash_used'] / 100) }}</td></tr>
            <tr><td style="color:var(--pw-text-muted);padding:.3rem 0;font-size:.75rem;">- Beli (Trade)</td><td style="font-size:.75rem;">{{ number_format($cubiData['cash_buy'] / 100) }}</td></tr>
            <tr><td style="color:var(--pw-text-muted);padding:.3rem 0;font-size:.75rem;">- Jual (Trade)</td><td style="font-size:.75rem;">{{ number_format($cubiData['cash_sell'] / 100) }}</td></tr>
            @endif
            <tr><td style="color:var(--pw-text-muted);padding:.3rem 0;">No HP</td><td>{{ $user->mobilenumber ?: '-' }}</td></tr>
            <tr>
                <td style="color:var(--pw-text-muted);padding:.3rem 0;">Kode Referral</td>
                <td>
                    <code style="font-size:.8rem;background:rgba(200,151,42,.1);color:var(--pw-gold);padding:.15rem .45rem;border-radius:4px;letter-spacing:.06em;">{{ $user->referral_code ?? '—' }}</code>
                </td>
            </tr>
            <tr>
                <td style="color:var(--pw-text-muted);padding:.3rem 0;">Diundang Oleh</td>
                <td>
                    @if($user->referrer)
                        <a href="{{ route('admin.members.show', $user->referrer->ID) }}"
                           style="font-size:.82rem;color:var(--pw-gold);text-decoration:none;font-weight:600;">
                            {{ $user->referrer->name }}
                        </a>
                    @else
                        <span style="color:var(--pw-text-muted);font-size:.82rem;">— (daftar mandiri)</span>
                    @endif
                </td>
            </tr>
            <tr><td style="color:var(--pw-text-muted);padding:.3rem 0;">Bergabung</td><td>{{ $user->creatime?->translatedFormat('d M Y') ?? '-' }}</td></tr>
            <tr>
                <td style="color:var(--pw-text-muted);padding:.3rem 0;">Status</td>
                <td>
                    @if($user->banned_at)
                        <span class="pw-badge pw-badge--danger">Banned</span>
                        @if($user->banned_until)
                            <span style="font-size:.7rem;color:var(--pw-text-muted);"> s/d {{ \Carbon\Carbon::parse($user->banned_until)->format('d M Y') }}</span>
                        @endif
                    @else
                        <span class="pw-badge pw-badge--success">Aktif</span>
                    @endif
                </td>
            </tr>
        </table>

        {{-- Edit Role/Email --}}
        <form action="{{ route('admin.members.update', $user->ID) }}" method="POST" style="margin-top:1.2rem;">
            @csrf @method('PUT')
            <label class="pw-form__label">Role</label>
            <select name="role" class="pw-form__input" style="margin-bottom:.6rem;">
                <option value="player"  @selected($user->role=='player')>Player</option>
                <option value="gm"      @selected($user->role=='gm')>GM</option>
                <option value="admin"   @selected($user->role=='admin')>Admin</option>
            </select>
            <label class="pw-form__label">Email</label>
            <input type="email" name="email" class="pw-form__input" value="{{ $user->email }}" style="margin-bottom:.8rem;">
            <button type="submit" class="pw-adm-btn" style="width:100%;">Simpan Perubahan</button>
        </form>

        {{-- Actions --}}
        <div style="margin-top:.8rem;display:flex;flex-direction:column;gap:.4rem;">
            @if(auth()->user()->isAdministrator())
            {{-- Top-up Gold Points --}}
            <button type="button" class="pw-adm-btn pw-adm-btn--ghost" onclick="document.getElementById('topupModal').style.display='flex'">
                + Top-up Gold Points
            </button>

            {{-- Isi Cubi Gold --}}
            <button type="button" class="pw-adm-btn pw-adm-btn--ghost" style="color:#4fad84;" onclick="document.getElementById('cubiTopupModal').style.display='flex'">
                + Isi Cubi Gold
            </button>
            @endif

            {{-- Reset Password --}}
            <button type="button" class="pw-adm-btn pw-adm-btn--ghost" onclick="document.getElementById('resetPwModal').style.display='flex'">
                Reset Password
            </button>

            @if(auth()->user()->isAdministrator())
            {{-- Ban / Unban --}}
            @if($user->banned_at)
            <form action="{{ route('admin.members.unban', $user->ID) }}" method="POST">
                @csrf
                <button type="submit" class="pw-adm-btn pw-adm-btn--ghost" style="width:100%;color:#4fad84;">Unban</button>
            </form>
            @else
            <button type="button" class="pw-adm-btn pw-adm-btn--ghost"
                    style="color:#e05252;" onclick="document.getElementById('banModal').style.display='flex'">
                Ban User
            </button>
            @endif

            {{-- Delete --}}
            @if($user->ID !== auth()->id())
            <button type="button" class="pw-adm-btn pw-adm-btn--ghost" style="width:100%;color:#e05252;"
                    onclick="document.getElementById('deleteModal').style.display='flex'">
                Hapus Akun
            </button>
            @endif
            @endif
        </div>
    </div>

    {{-- Right Column --}}
    <div>
        {{-- Characters --}}
        @php
            $classIconMap = [
                0 => 'blademaster', 1 => 'wizzard', 2 => 'cleric', 3 => 'archer',
                4 => 'barbarian', 5 => 'venomancer', 6 => 'assasin', 7 => 'psychic',
                8 => 'seeker', 9 => 'mystic', 10 => 'duskblade', 11 => 'stormbringer',
            ];
        @endphp
        <div class="pw-adm-card" style="margin-bottom:1rem;">
            <div class="pw-adm-card__title">Karakter Game ({{ $characters->count() }})</div>
            @if($characters->isEmpty())
                <p style="color:var(--pw-text-muted);font-size:.82rem;">Tidak ada karakter ditemukan.</p>
            @else
            <div class="pw-table-wrap">
                <table class="pw-table">
                    <thead><tr><th>Nama</th><th>Class</th><th>Level</th><th>Gender</th><th>Terakhir Online</th></tr></thead>
                    <tbody>
                        @foreach($characters as $char)
                        @php
                            $lastloginTs = $rolesData[$char->role_id]['base']['lastlogin'] ?? null;
                            $lastloginStr = ($lastloginTs && $lastloginTs > 0)
                                ? \Carbon\Carbon::createFromTimestamp($lastloginTs)->locale('id')->translatedFormat('d F Y, H:i')
                                : '-';
                        @endphp
                        <tr>
                            <td style="font-weight:600;"><a href="{{ route('admin.members.character', [$user->ID, $char->role_id]) }}" style="color:var(--pw-gold);text-decoration:none;">{{ $char->name }}</a></td>
                            <td style="display:flex;align-items:center;gap:.4rem;">
                                <img src="/images/class/{{ ($classIconMap[$char->class_id] ?? 'blademaster') . '.png' }}" alt="{{ $char->class }}" width="22" height="22" style="flex-shrink:0;">
                                {{ $char->class }}
                            </td>
                            <td>{{ $char->level }}</td>
                            <td>{{ $char->gender == 0 ? 'Male' : 'Female' }}</td>
                            <td style="font-size:.78rem;color:var(--pw-text-muted);">{{ $lastloginStr }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
        {{-- Donations --}}
        <div class="pw-adm-card" style="margin-bottom:1rem;">
            <div class="pw-adm-card__title">Riwayat Donate ({{ $user->invoices->count() }})</div>
            @if($user->invoices->isEmpty())
                <p style="color:var(--pw-text-muted);font-size:.82rem;">Belum ada transaksi.</p>
            @else
            <div class="pw-table-wrap">
                <table class="pw-table">
                    <thead><tr><th>Invoice</th><th>Gold Points</th><th>Nominal</th><th>Status</th><th>Tanggal</th></tr></thead>
                    <tbody>
                        @foreach($user->invoices->take(10) as $inv)
                        <tr>
                            <td style="font-size:.75rem;color:var(--pw-text-muted);">{{ $inv->invoice_number }}</td>
                            <td>{{ number_format($inv->gold_amount) }}</td>
                            <td>Rp {{ number_format($inv->unique_amount) }}</td>
                            <td>
                                @if($inv->status==='paid') <span class="pw-badge pw-badge--success">Paid</span>
                                @elseif($inv->status==='pending') <span class="pw-badge pw-badge--warning">Pending</span>
                                @else <span class="pw-badge pw-badge--danger">Gagal</span> @endif
                            </td>
                            <td style="font-size:.75rem;color:var(--pw-text-muted);">{{ $inv->created_at->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        {{-- Shop Logs --}}
        <div class="pw-adm-card" style="margin-bottom:1rem;">
            <div class="pw-adm-card__title">Riwayat Shop ({{ $user->shopLogs->count() }})</div>
            @if($user->shopLogs->isEmpty())
                <p style="color:var(--pw-text-muted);font-size:.82rem;">Belum ada pembelian.</p>
            @else
            <div class="pw-table-wrap">
                <table class="pw-table">
                    <thead><tr><th>Item</th><th>Harga</th><th>Tanggal</th></tr></thead>
                    <tbody>
                        @foreach($user->shopLogs->take(10) as $log)
                        <tr>
                            <td>{{ $log->item_name }}</td>
                            <td>{{ number_format($log->price) }} Gold Points</td>
                            <td style="font-size:.75rem;color:var(--pw-text-muted);">{{ $log->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        {{-- Service Logs --}}
        <div class="pw-adm-card">
            <div class="pw-adm-card__title">Pesanan Layanan ({{ $user->serviceLogs->count() }})</div>
            @if($user->serviceLogs->isEmpty())
                <p style="color:var(--pw-text-muted);font-size:.82rem;">Belum ada pesanan.</p>
            @else
            <div class="pw-table-wrap">
                <table class="pw-table">
                    <thead><tr><th>Layanan</th><th>Harga</th><th>Status</th><th>Tanggal</th></tr></thead>
                    <tbody>
                        @foreach($user->serviceLogs->take(10) as $log)
                        <tr>
                            <td>{{ $log->service_name }}</td>
                            <td>{{ number_format($log->price) }} Gold Points</td>
                            <td><span class="pw-badge @if($log->status==='completed') pw-badge--success @elseif($log->status==='rejected') pw-badge--danger @else pw-badge--warning @endif">{{ ucfirst($log->status) }}</span></td>
                            <td style="font-size:.75rem;color:var(--pw-text-muted);">{{ $log->created_at->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Top-up Gold Points Modal --}}
@if(auth()->user()->isAdministrator())
<div id="topupModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:100;align-items:center;justify-content:center;">
    <div class="pw-adm-card" style="min-width:320px;max-width:420px;width:100%;">
        <div class="pw-adm-card__title">Top-up Gold Points — {{ $user->name }}</div>
        <form action="{{ route('admin.members.topup', $user->ID) }}" method="POST">
            @csrf
            <label class="pw-form__label">Jumlah Gold Points</label>
            <input type="number" name="amount" class="pw-form__input" min="1" max="100000" required style="margin-bottom:.6rem;">
            <label class="pw-form__label">Alasan</label>
            <input type="text" name="reason" class="pw-form__input" placeholder="Event reward, manual topup, dll." required style="margin-bottom:.8rem;">
            <div style="display:flex;gap:.5rem;">
                <button type="submit" class="pw-adm-btn">Tambahkan</button>
                <button type="button" class="pw-adm-btn pw-adm-btn--ghost" onclick="document.getElementById('topupModal').style.display='none'">Batal</button>
            </div>
        </form>
    </div>
</div>

{{-- Isi Cubi Gold Modal --}}
<div id="cubiTopupModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:100;align-items:center;justify-content:center;">
    <div class="pw-adm-card" style="min-width:320px;max-width:420px;width:100%;">
        <div class="pw-adm-card__title" style="color:#4fad84;">Isi Cubi Gold — {{ $user->name }}</div>
        <p style="font-size:.8rem;color:var(--pw-text-muted);margin-bottom:.8rem;">Cubi akan masuk ke akun game saat user login/relog.</p>
        <form action="{{ route('admin.members.cubi-topup', $user->ID) }}" method="POST">
            @csrf
            <label class="pw-form__label">Jumlah Cubi</label>
            <input type="number" name="amount" class="pw-form__input" min="1" max="999999" required style="margin-bottom:.6rem;">
            <label class="pw-form__label">Alasan</label>
            <input type="text" name="reason" class="pw-form__input" placeholder="Kompensasi, event reward, dll." required style="margin-bottom:.8rem;">
            <div style="display:flex;gap:.5rem;">
                <button type="submit" class="pw-adm-btn" style="background:#4fad84;border-color:#4fad84;">Kirim Cubi</button>
                <button type="button" class="pw-adm-btn pw-adm-btn--ghost" onclick="document.getElementById('cubiTopupModal').style.display='none'">Batal</button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- Reset Password Modal --}}
<div id="resetPwModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:100;align-items:center;justify-content:center;">
    <div class="pw-adm-card" style="min-width:320px;max-width:420px;width:100%;">
        <div class="pw-adm-card__title">Reset Password — {{ $user->name }}</div>
        <form action="{{ route('admin.members.reset-password', $user->ID) }}" method="POST">
            @csrf
            <label class="pw-form__label">Password Baru</label>
            <input type="text" name="new_password" class="pw-form__input" required minlength="6" pattern="[a-z0-9]+"
                   placeholder="Huruf kecil & angka saja, min 6 karakter" style="margin-bottom:.4rem;">
            <p style="font-size:.72rem;color:var(--pw-text-muted);margin-bottom:.8rem;">Hanya huruf kecil (a-z) dan angka (0-9). Minimal 6 karakter.</p>
            <div style="display:flex;gap:.5rem;">
                <button type="submit" class="pw-adm-btn">Reset Password</button>
                <button type="button" class="pw-adm-btn pw-adm-btn--ghost" onclick="document.getElementById('resetPwModal').style.display='none'">Batal</button>
            </div>
        </form>
    </div>
</div>

{{-- Delete Account Modal --}}
@if(auth()->user()->isAdministrator())
@if($user->ID !== auth()->id())
<div id="deleteModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:100;align-items:center;justify-content:center;">
    <div class="pw-adm-card" style="min-width:320px;max-width:420px;width:100%;">
        <div class="pw-adm-card__title" style="color:#e05252;">Hapus Akun — {{ $user->name }}</div>
        <p style="font-size:.82rem;color:var(--pw-text-muted);margin-bottom:1rem;">Akun ini akan dihapus secara permanen dan tidak dapat dikembalikan. Yakin ingin melanjutkan?</p>
        <form action="{{ route('admin.members.destroy', $user->ID) }}" method="POST">
            @csrf @method('DELETE')
            <div style="display:flex;gap:.5rem;">
                <button type="submit" class="pw-adm-btn" style="background:#e05252;border-color:#e05252;">Ya, Hapus</button>
                <button type="button" class="pw-adm-btn pw-adm-btn--ghost" onclick="document.getElementById('deleteModal').style.display='none'">Batal</button>
            </div>
        </form>
    </div>
</div>
@endif
@endif

{{-- Ban Modal --}}
@if(auth()->user()->isAdministrator())
<div id="banModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:100;align-items:center;justify-content:center;">
    <div class="pw-adm-card" style="min-width:320px;max-width:420px;width:100%;">
        <div class="pw-adm-card__title" style="color:#e05252;">Ban User — {{ $user->name }}</div>
        <form action="{{ route('admin.members.ban', $user->ID) }}" method="POST">
            @csrf
            <label class="pw-form__label">Alasan Ban</label>
            <input type="text" name="reason" class="pw-form__input" placeholder="Cheating, AFK farming, dll." required style="margin-bottom:.6rem;">
            <label class="pw-form__label">Ban Sampai (kosong = permanen)</label>
            <input type="text" name="banned_until" class="pw-form__input pw-datepicker" placeholder="Pilih tanggal & waktu" style="margin-bottom:.8rem;">
            <div style="display:flex;gap:.5rem;">
                <button type="submit" class="pw-adm-btn" style="background:#e05252;border-color:#e05252;">Ban</button>
                <button type="button" class="pw-adm-btn pw-adm-btn--ghost" onclick="document.getElementById('banModal').style.display='none'">Batal</button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection
