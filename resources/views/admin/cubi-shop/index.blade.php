@extends('layouts.admin')
@section('title', 'Cubi Shop')

@section('content')

{{-- Stats Cards --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:1rem;margin-bottom:1.5rem;">
    <div class="pw-adm-card" style="text-align:center;padding:1rem;">
        <div style="font-size:.72rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.04em;">Total Penjualan</div>
        <div style="font-size:1.6rem;font-weight:700;color:#60d0ff;margin-top:.2rem;">{{ number_format($totalSales) }}</div>
    </div>
    <div class="pw-adm-card" style="text-align:center;padding:1rem;">
        <div style="font-size:.72rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.04em;">Total Revenue</div>
        <div style="font-size:1.4rem;font-weight:700;color:#7deba0;margin-top:.2rem;">Rp {{ number_format($totalRevenue) }}</div>
    </div>
    <div class="pw-adm-card" style="text-align:center;padding:1rem;">
        <div style="font-size:.72rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.04em;">Total Cubi Terjual</div>
        <div style="font-size:1.6rem;font-weight:700;color:#60d0ff;margin-top:.2rem;">{{ number_format($totalCubiSold) }}</div>
    </div>
    <div class="pw-adm-card" style="text-align:center;padding:1rem;">
        <div style="font-size:.72rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.04em;">Total Diskon</div>
        <div style="font-size:1.4rem;font-weight:700;color:#fbbf24;margin-top:.2rem;">Rp {{ number_format($totalDiscount) }}</div>
    </div>
    <div class="pw-adm-card" style="text-align:center;padding:1rem;">
        <div style="font-size:.72rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.04em;">Total Komisi</div>
        <div style="font-size:1.4rem;font-weight:700;color:#c084fc;margin-top:.2rem;">Rp {{ number_format($totalCommission) }}</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;align-items:stretch;margin-bottom:1.5rem;">

    {{-- LEFT: Pengaturan Cubi Shop --}}
    <div class="pw-adm-card" style="display:flex;flex-direction:column;">
        <div style="font-weight:600;font-size:.92rem;margin-bottom:1rem;">Pengaturan Cubi Shop</div>

        <form method="POST" action="{{ route('admin.cubi-shop.settings') }}" style="display:flex;flex-direction:column;flex:1;">
            @csrf

            <div style="margin-bottom:.8rem;">
                <label class="pw-adm-label" style="display:flex;align-items:center;gap:.5rem;cursor:pointer;">
                    <input type="checkbox" name="enabled" value="1"
                        {{ config('pw-config.cubi_shop.enabled') ? 'checked' : '' }}
                        style="accent-color:#60d0ff;">
                    Aktifkan Cubi Shop
                </label>
            </div>

            <div style="margin-bottom:.8rem;">
                <label class="pw-adm-label">Minimum Pembelian (Rp)</label>
                <input type="number" name="min_purchase" class="pw-adm-input" style="margin-top:.25rem;"
                    value="{{ config('pw-config.cubi_shop.min_purchase', 50000) }}" min="1000" step="1000">
                <p style="font-size:.7rem;color:var(--pw-text-muted);margin-top:.25rem;">
                    Minimum pembelian dalam Rupiah. Default: Rp 50.000 (= 50 Cubi).
                </p>
            </div>

            <div style="margin-bottom:.8rem;display:grid;grid-template-columns:1fr 1fr;gap:.6rem;">
                <div>
                    <label class="pw-adm-label">Kelipatan Bonus (Cubi)</label>
                    <input type="number" name="bonus_multiple" class="pw-adm-input" style="margin-top:.25rem;"
                        value="{{ config('pw-config.cubi_shop.bonus_multiple', 50) }}" min="1">
                    <p style="font-size:.7rem;color:var(--pw-text-muted);margin-top:.25rem;">
                        Setiap X Cubi dibeli.
                    </p>
                </div>
                <div>
                    <label class="pw-adm-label">Bonus per Kelipatan</label>
                    <input type="number" name="bonus_amount" class="pw-adm-input" style="margin-top:.25rem;"
                        value="{{ config('pw-config.cubi_shop.bonus_amount', 5) }}" min="0">
                    <p style="font-size:.7rem;color:var(--pw-text-muted);margin-top:.25rem;">
                        Bonus Cubi diberikan.
                    </p>
                </div>
            </div>

            <div style="background:rgba(96,208,255,.06);border:1px solid rgba(96,208,255,.15);border-radius:.5rem;padding:.6rem .8rem;margin-bottom:1rem;font-size:.75rem;color:var(--pw-text-muted);">
                <strong style="color:#60d0ff;">Contoh:</strong> Kelipatan {{ config('pw-config.cubi_shop.bonus_multiple', 50) }}, Bonus {{ config('pw-config.cubi_shop.bonus_amount', 5) }}<br>
                Beli 1.000 Cubi → {{ floor(1000 / config('pw-config.cubi_shop.bonus_multiple', 50)) }} × {{ config('pw-config.cubi_shop.bonus_amount', 5) }} = <strong style="color:#7deba0;">+{{ floor(1000 / config('pw-config.cubi_shop.bonus_multiple', 50)) * config('pw-config.cubi_shop.bonus_amount', 5) }} bonus</strong>
                → Total: <strong style="color:#60d0ff;">{{ 1000 + floor(1000 / config('pw-config.cubi_shop.bonus_multiple', 50)) * config('pw-config.cubi_shop.bonus_amount', 5) }} Cubi</strong>
            </div>

            <button type="submit" class="pw-adm-btn" style="width:100%;margin-top:auto;">Simpan Pengaturan</button>
        </form>
    </div>

    {{-- RIGHT: Diskon & Komisi --}}
    <div class="pw-adm-card" style="display:flex;flex-direction:column;">
        <div style="font-weight:600;font-size:.92rem;margin-bottom:1rem;">Diskon & Komisi Partner</div>

        <form method="POST" action="{{ route('admin.cubi-shop.settings') }}" style="display:flex;flex-direction:column;flex:1;">
            @csrf

            <div style="margin-bottom:.8rem;">
                <label class="pw-adm-label">Diskon RefCode (%)</label>
                <input type="number" name="discount_percent" class="pw-adm-input" style="margin-top:.25rem;"
                    value="{{ config('pw-config.cubi_shop.discount_percent', 10) }}" min="0" max="100" step="0.01">
                <p style="font-size:.7rem;color:var(--pw-text-muted);margin-top:.25rem;">
                    Potongan harga untuk pembeli yang menggunakan kode referral partner.
                </p>
            </div>

            <div style="margin-bottom:.8rem;">
                <label class="pw-adm-label">Komisi Partner (%)</label>
                <input type="number" name="commission_percent" class="pw-adm-input" style="margin-top:.25rem;"
                    value="{{ config('pw-config.cubi_shop.commission_percent', 10) }}" min="0" max="100" step="0.01">
                <p style="font-size:.7rem;color:var(--pw-text-muted);margin-top:.25rem;">
                    Persentase dari harga jual yang diberikan ke partner sebagai Gold Points.
                </p>
            </div>

            <div style="background:rgba(251,191,36,.06);border:1px solid rgba(251,191,36,.15);border-radius:.5rem;padding:.6rem .8rem;margin-bottom:1rem;font-size:.75rem;color:var(--pw-text-muted);">
                <strong style="color:#fbbf24;">Info:</strong> Diskon diberikan kepada pembeli yang memasukkan kode referral saat checkout.<br>
                Komisi otomatis masuk sebagai Gold Points ke akun partner pemilik kode referral.
            </div>

            <button type="submit" class="pw-adm-btn" style="width:100%;margin-top:auto;">Simpan Diskon & Komisi</button>
        </form>
    </div>
</div>

{{-- Paket Cubi Coin (full width) --}}
<div class="pw-adm-card" style="margin-bottom:1.5rem;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;flex-wrap:wrap;gap:.6rem;">
        <div>
            <div style="font-weight:600;font-size:.95rem;">Paket Cubi Coin</div>
            <div style="font-size:.75rem;color:var(--pw-text-muted);">Kelola paket penjualan Cubi Coin.</div>
        </div>
        <button type="button" class="pw-adm-btn" onclick="document.getElementById('addPackageModal').style.display='flex'">+ Tambah Paket</button>
    </div>

    <div class="pw-table-wrap">
        <table class="pw-table">
            <thead>
                <tr>
                    <th>Nama Paket</th>
                    <th>Cubi</th>
                    <th>Bonus</th>
                    <th>Total</th>
                    <th>Harga (IDR)</th>
                    <th>Status</th>
                    <th>Urutan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($packages as $pkg)
                <tr>
                    <td style="font-weight:600;">{{ $pkg->name }}</td>
                    <td style="color:#60d0ff;font-weight:600;">{{ number_format($pkg->cubi_amount) }}</td>
                    <td>
                        @if($pkg->bonus_cubi > 0)
                        <span style="color:#7deba0;">+{{ number_format($pkg->bonus_cubi) }}</span>
                        @else
                        <span style="color:var(--pw-text-muted);">-</span>
                        @endif
                    </td>
                    <td style="color:#60d0ff;font-weight:700;">{{ number_format($pkg->total_cubi) }}</td>
                    <td><strong>Rp {{ number_format($pkg->price_idr) }}</strong></td>
                    <td>
                        @if($pkg->is_active)
                        <span class="pw-badge pw-badge--success">Aktif</span>
                        @else
                        <span class="pw-badge pw-badge--danger">Nonaktif</span>
                        @endif
                    </td>
                    <td style="text-align:center;color:var(--pw-text-muted);">{{ $pkg->sort_order }}</td>
                    <td style="display:flex;gap:.3rem;">
                        <button type="button" class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--ghost"
                            onclick="openEditPkg({{ json_encode($pkg) }})">Edit</button>
                        <form action="{{ route('admin.cubi-shop.package.delete', $pkg->id) }}" method="POST"
                              data-confirm="Hapus Paket|Yakin ingin menghapus paket ini?">
                            @csrf @method('DELETE')
                            <button type="submit" class="pw-adm-btn pw-adm-btn--sm pw-adm-btn--ghost" style="color:#e05252;">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:2rem;color:var(--pw-text-muted);">Belum ada paket Cubi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Transaction History --}}
<div class="pw-adm-card" style="margin-top:1.5rem;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;flex-wrap:wrap;gap:.6rem;">
        <div style="font-weight:600;font-size:.92rem;">Riwayat Penjualan Cubi</div>
        <form method="GET" action="{{ route('admin.cubi-shop') }}" style="display:flex;gap:.4rem;align-items:center;">
            <input type="text" name="search" class="pw-adm-input" placeholder="Cari username / refcode..." value="{{ request('search') }}" style="width:180px;font-size:.78rem;">
            <button type="submit" class="pw-adm-btn pw-adm-btn--sm">Cari</button>
        </form>
    </div>

    <div class="pw-table-wrap">
        <table class="pw-table">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Pembeli</th>
                    <th>Cubi</th>
                    <th>Harga</th>
                    <th>RefCode</th>
                    <th>Partner</th>
                    <th>Diskon</th>
                    <th>Komisi</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $tx)
                <tr>
                    <td style="font-size:.78rem;color:var(--pw-text-muted);">{{ $tx->invoice_number }}</td>
                    <td>
                        @if($tx->user)
                        <a href="{{ route('admin.members.show', $tx->user->ID) }}" style="color:var(--pw-gold-light);font-weight:600;">{{ $tx->user->name }}</a>
                        @else
                        <span style="color:var(--pw-text-muted);">-</span>
                        @endif
                    </td>
                    <td style="color:#60d0ff;font-weight:600;">{{ number_format($tx->cubi_amount) }}</td>
                    <td><strong>Rp {{ number_format($tx->amount) }}</strong></td>
                    <td>
                        @if($tx->refcode)
                        <span style="color:#60d0ff;font-weight:500;text-transform:uppercase;">{{ $tx->refcode }}</span>
                        @else
                        <span style="color:var(--pw-text-muted);">-</span>
                        @endif
                    </td>
                    <td>
                        @if($tx->partner)
                        <a href="{{ route('admin.members.show', $tx->partner->ID) }}" style="color:#c084fc;font-weight:600;">{{ $tx->partner->name }}</a>
                        @else
                        <span style="color:var(--pw-text-muted);">-</span>
                        @endif
                    </td>
                    <td>
                        @if($tx->discount_amount)
                        <span style="color:#7deba0;">-Rp {{ number_format($tx->discount_amount) }}</span>
                        <span style="font-size:.7rem;color:var(--pw-text-muted);">({{ $tx->discount_percent }}%)</span>
                        @else
                        <span style="color:var(--pw-text-muted);">-</span>
                        @endif
                    </td>
                    <td>
                        @if($tx->commission_amount)
                        <span style="color:#c084fc;">Rp {{ number_format($tx->commission_amount) }}</span>
                        @if($tx->commission_credited)
                        <span class="pw-badge pw-badge--success" style="font-size:.6rem;">Paid</span>
                        @endif
                        @else
                        <span style="color:var(--pw-text-muted);">-</span>
                        @endif
                    </td>
                    <td style="color:var(--pw-text-muted);font-size:.78rem;">{{ $tx->paid_at?->format('d M Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center;padding:2rem;color:var(--pw-text-muted);">Belum ada transaksi Cubi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($transactions->hasPages())
    <div style="margin-top:1rem;">
        {{ $transactions->links() }}
    </div>
    @endif
</div>

{{-- Add Package Modal --}}
<div id="addPackageModal" style="display:none;position:fixed;inset:0;z-index:999;background:rgba(0,0,0,.6);align-items:center;justify-content:center;">
    <div class="pw-adm-card" style="width:100%;max-width:420px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
            <div style="font-weight:600;font-size:.95rem;">Tambah Paket Cubi</div>
            <button type="button" onclick="document.getElementById('addPackageModal').style.display='none'"
                style="background:none;border:none;color:var(--pw-text-muted);cursor:pointer;font-size:1.2rem;">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.cubi-shop.package.store') }}">
            @csrf
            <div style="margin-bottom:.7rem;">
                <label class="pw-adm-label">Nama Paket</label>
                <input type="text" name="name" class="pw-adm-input" required placeholder="Contoh: Cubi 100">
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.6rem;margin-bottom:.7rem;">
                <div>
                    <label class="pw-adm-label">Jumlah Cubi</label>
                    <input type="number" name="cubi_amount" class="pw-adm-input" required min="1" placeholder="100">
                </div>
                <div>
                    <label class="pw-adm-label">Bonus Cubi</label>
                    <input type="number" name="bonus_cubi" class="pw-adm-input" min="0" value="0" placeholder="0">
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.6rem;margin-bottom:.7rem;">
                <div>
                    <label class="pw-adm-label">Harga (IDR)</label>
                    <input type="number" name="price_idr" class="pw-adm-input" required min="1000" placeholder="100000">
                </div>
                <div>
                    <label class="pw-adm-label">Urutan</label>
                    <input type="number" name="sort_order" class="pw-adm-input" min="0" value="0">
                </div>
            </div>
            <button type="submit" class="pw-adm-btn" style="width:100%;">Tambah Paket</button>
        </form>
    </div>
</div>

{{-- Edit Package Modal --}}
<div id="editPackageModal" style="display:none;position:fixed;inset:0;z-index:999;background:rgba(0,0,0,.6);align-items:center;justify-content:center;">
    <div class="pw-adm-card" style="width:100%;max-width:420px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
            <div style="font-weight:600;font-size:.95rem;">Edit Paket: <span id="editPkgName" style="color:#60d0ff;"></span></div>
            <button type="button" onclick="document.getElementById('editPackageModal').style.display='none'"
                style="background:none;border:none;color:var(--pw-text-muted);cursor:pointer;font-size:1.2rem;">&times;</button>
        </div>
        <form method="POST" id="editPkgForm">
            @csrf @method('PUT')
            <div style="margin-bottom:.7rem;">
                <label class="pw-adm-label">Nama Paket</label>
                <input type="text" name="name" id="ep_name" class="pw-adm-input" required>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.6rem;margin-bottom:.7rem;">
                <div>
                    <label class="pw-adm-label">Jumlah Cubi</label>
                    <input type="number" name="cubi_amount" id="ep_cubi_amount" class="pw-adm-input" required min="1">
                </div>
                <div>
                    <label class="pw-adm-label">Bonus Cubi</label>
                    <input type="number" name="bonus_cubi" id="ep_bonus_cubi" class="pw-adm-input" min="0">
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.6rem;margin-bottom:.7rem;">
                <div>
                    <label class="pw-adm-label">Harga (IDR)</label>
                    <input type="number" name="price_idr" id="ep_price_idr" class="pw-adm-input" required min="1000">
                </div>
                <div>
                    <label class="pw-adm-label">Urutan</label>
                    <input type="number" name="sort_order" id="ep_sort_order" class="pw-adm-input" min="0">
                </div>
            </div>
            <div style="margin-bottom:.7rem;">
                <label style="display:flex;align-items:center;gap:.5rem;font-size:.82rem;cursor:pointer;">
                    <input type="checkbox" name="is_active" id="ep_is_active" value="1" style="accent-color:#60d0ff;">
                    Aktif
                </label>
            </div>
            <button type="submit" class="pw-adm-btn" style="width:100%;">Simpan Perubahan</button>
        </form>
    </div>
</div>

<script>
function openEditPkg(pkg) {
    document.getElementById('editPackageModal').style.display = 'flex';
    document.getElementById('editPkgName').textContent = pkg.name;
    document.getElementById('editPkgForm').action = '{{ url("admin/cubi-shop/package") }}/' + pkg.id;
    document.getElementById('ep_name').value = pkg.name;
    document.getElementById('ep_cubi_amount').value = pkg.cubi_amount;
    document.getElementById('ep_bonus_cubi').value = pkg.bonus_cubi;
    document.getElementById('ep_price_idr').value = pkg.price_idr;
    document.getElementById('ep_sort_order').value = pkg.sort_order;
    document.getElementById('ep_is_active').checked = pkg.is_active;
}
</script>

@endsection
