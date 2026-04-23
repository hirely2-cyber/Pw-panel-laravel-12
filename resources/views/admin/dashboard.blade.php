@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')

{{-- ── ROW 1: Stat Utama ── --}}
<div style="display:grid;grid-template-columns:repeat(6,1fr);gap:.75rem;margin-bottom:1rem;">

    {{-- Total Pemain --}}
    <div class="pw-adm-stat" style="flex-direction:row;align-items:center;gap:.8rem;">
        <div class="pw-adm-stat__icon" style="color:#b89d4f;">
            <svg viewBox="0 0 20 20" fill="none" width="20"><circle cx="10" cy="7" r="3.5" stroke="currentColor" stroke-width="1.5"/><path d="M3 17c0-3 3-5.5 7-5.5s7 2.5 7 5.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        </div>
        <div>
            <div class="pw-adm-stat__value" style="font-size:1.3rem;">{{ number_format($stats['total_players']) }}</div>
            <div class="pw-adm-stat__label">Total Pemain</div>
        </div>
    </div>

    {{-- Online --}}
    <div class="pw-adm-stat" style="flex-direction:row;align-items:center;gap:.8rem;">
        <div class="pw-adm-stat__icon" style="color:#4ade80;">
            <svg viewBox="0 0 20 20" fill="none" width="20"><circle cx="10" cy="10" r="3" stroke="currentColor" stroke-width="1.5"/><circle cx="10" cy="10" r="7" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 2"/></svg>
        </div>
        <div>
            <div class="pw-adm-stat__value" style="font-size:1.3rem;color:#4ade80;">{{ number_format($stats['online_players']) }}</div>
            <div class="pw-adm-stat__label">Online</div>
        </div>
    </div>

    {{-- Total Income --}}
    <div class="pw-adm-stat" style="flex-direction:row;align-items:center;gap:.8rem;">
        <div class="pw-adm-stat__icon" style="color:#22c55e;">
            <svg viewBox="0 0 20 20" fill="none" width="20"><rect x="2" y="5" width="16" height="11" rx="2" stroke="currentColor" stroke-width="1.4"/><path d="M2 9h16" stroke="currentColor" stroke-width="1.4"/></svg>
        </div>
        <div>
            <div class="pw-adm-stat__value" style="font-size:1.1rem;">Rp {{ number_format($stats['total_income']) }}</div>
            <div class="pw-adm-stat__label">Total Income</div>
        </div>
    </div>

    {{-- Cubi Terjual --}}
    <div class="pw-adm-stat" style="flex-direction:row;align-items:center;gap:.8rem;">
        <div class="pw-adm-stat__icon" style="color:#a78bfa;">
            <svg viewBox="0 0 20 20" fill="none" width="20"><path d="M17 10l-4-7H7L3 10l4 7h6l4-7z" stroke="currentColor" stroke-width="1.3"/><circle cx="10" cy="10" r="2.2" stroke="currentColor" stroke-width="1.3"/></svg>
        </div>
        <div>
            <div class="pw-adm-stat__value" style="font-size:1.3rem;">{{ number_format($stats['total_cubi']) }}</div>
            <div class="pw-adm-stat__label">Cubi Terjual</div>
        </div>
    </div>

    {{-- Referral --}}
    <div class="pw-adm-stat" style="flex-direction:row;align-items:center;gap:.8rem;">
        <div class="pw-adm-stat__icon" style="color:#38bdf8;">
            <svg viewBox="0 0 20 20" fill="none" width="20"><path d="M8 9a3 3 0 100-6 3 3 0 000 6zM14 9a2 2 0 100-4 2 2 0 000 4zM3 17c0-2.5 2-4.5 5-4.5M13 13c2.5 0 5 1.5 5 4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
        </div>
        <div>
            <div class="pw-adm-stat__value" style="font-size:1.3rem;">{{ number_format($stats['total_referrals']) }}</div>
            <div class="pw-adm-stat__label">Via Referral</div>
        </div>
    </div>

    {{-- Invoice Pending --}}
    <div class="pw-adm-stat" style="flex-direction:row;align-items:center;gap:.8rem;{{ $stats['pending_invoices'] > 0 ? 'border-color:rgba(220,60,60,.3);' : '' }}">
        <div class="pw-adm-stat__icon" style="color:{{ $stats['pending_invoices'] > 0 ? '#f87171' : 'var(--pw-text-muted)' }};">
            <svg viewBox="0 0 20 20" fill="none" width="20"><circle cx="10" cy="10" r="7" stroke="currentColor" stroke-width="1.4"/><path d="M10 6v4l2 2" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
        </div>
        <div>
            <div class="pw-adm-stat__value" style="font-size:1.3rem;{{ $stats['pending_invoices'] > 0 ? 'color:#f87171;' : '' }}">{{ number_format($stats['pending_invoices']) }}</div>
            <div class="pw-adm-stat__label">Invoice Pending</div>
        </div>
    </div>

</div>

{{-- ── ROW 2: Quick Info ── --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:.75rem;margin-bottom:1.2rem;">

    {{-- Karakter --}}
    <div class="pw-adm-card" style="display:flex;align-items:center;gap:.8rem;padding:.8rem 1rem;">
        <div style="width:34px;height:34px;border-radius:8px;background:rgba(200,151,42,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg viewBox="0 0 20 20" fill="none" width="17" style="color:#c8972a;"><circle cx="10" cy="7" r="3.5" stroke="currentColor" stroke-width="1.4"/><path d="M3 17c0-3 3-5.5 7-5.5s7 2.5 7 5.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
        </div>
        <div>
            <div style="font-size:1.2rem;font-weight:700;color:var(--pw-text-light);">{{ number_format($stats['total_roles']) }}</div>
            <div style="font-size:.68rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.06em;">Karakter Dibuat</div>
        </div>
    </div>

    {{-- Cubi Pending --}}
    <div class="pw-adm-card" style="display:flex;align-items:center;gap:.8rem;padding:.8rem 1rem;">
        <div style="width:34px;height:34px;border-radius:8px;background:rgba(168,85,247,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg viewBox="0 0 20 20" fill="none" width="17" style="color:#a78bfa;"><path d="M10 3v9m0 0l-3-3m3 3l3-3" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 14v1a2 2 0 002 2h8a2 2 0 002-2v-1" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
        </div>
        <div>
            <div style="font-size:1.2rem;font-weight:700;color:var(--pw-text-light);">{{ number_format($stats['pending_cubi']) }}</div>
            <div style="font-size:.68rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.06em;">Cubi Antrian</div>
        </div>
    </div>

    {{-- Active Event --}}
    <div class="pw-adm-card" style="display:flex;align-items:center;gap:.8rem;padding:.8rem 1rem;{{ $stats['active_events'] > 0 ? 'border-color:rgba(229,166,21,.3);' : '' }}">
        <div style="width:34px;height:34px;border-radius:8px;background:rgba(229,166,21,.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg viewBox="0 0 20 20" fill="none" width="17" style="color:#e5a615;"><path d="M4 3h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2z" stroke="currentColor" stroke-width="1.3"/><path d="M2 8h16" stroke="currentColor" stroke-width="1.3"/></svg>
        </div>
        <div>
            <div style="font-size:1.2rem;font-weight:700;color:{{ $stats['active_events'] > 0 ? '#e5a615' : 'var(--pw-text-light)' }};">{{ $stats['active_events'] }}</div>
            <div style="font-size:.68rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.06em;">Event Aktif</div>
        </div>
        @if($activeEvent)
        <div style="margin-left:auto;text-align:right;">
            <div style="font-size:.68rem;color:#e5a615;font-weight:600;max-width:100px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $activeEvent->title }}</div>
            <div style="font-size:.62rem;color:var(--pw-text-muted);">berakhir {{ $activeEvent->end_at?->format('d M') }}</div>
        </div>
        @endif
    </div>

    {{-- Server Status --}}
    <div class="pw-adm-card" style="display:flex;align-items:center;gap:.8rem;padding:.8rem 1rem;">
        <div style="width:34px;height:34px;border-radius:8px;background:rgba(74,222,128,.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg viewBox="0 0 20 20" fill="none" width="17" style="color:#4ade80;"><rect x="2" y="3" width="16" height="12" rx="2" stroke="currentColor" stroke-width="1.4"/><circle cx="7" cy="9" r="1.2" fill="currentColor" opacity=".7"/><circle cx="10" cy="9" r="1.2" fill="currentColor" opacity=".7"/><circle cx="13" cy="9" r="1.2" fill="currentColor" opacity=".7"/></svg>
        </div>
        <div>
            <div style="font-size:.88rem;font-weight:700;color:#4ade80;display:flex;align-items:center;gap:.4rem;">
                <span style="width:7px;height:7px;border-radius:50%;background:#4ade80;display:inline-block;animation:pw-pulse 2s infinite;"></span>
                Online
            </div>
            <div style="font-size:.68rem;color:var(--pw-text-muted);text-transform:uppercase;letter-spacing:.06em;">Status Server</div>
        </div>
        <a href="{{ route('admin.server-control') }}" style="margin-left:auto;" class="pw-adm-btn pw-adm-btn--ghost pw-adm-btn--xs">Kelola</a>
    </div>

</div>

{{-- ── ROW 3: Chart ── --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.2rem;margin-bottom:1.2rem;">

    {{-- Chart Registrasi --}}
    <div class="pw-adm-card" style="padding:0;overflow:hidden;">
        <div style="padding:.85rem 1rem;border-bottom:1px solid var(--pw-border);display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:.5rem;">
                <svg viewBox="0 0 20 20" fill="none" width="15" style="color:var(--pw-text-muted);"><circle cx="10" cy="7" r="3.5" stroke="currentColor" stroke-width="1.4"/><path d="M3 17c0-3 3-5.5 7-5.5s7 2.5 7 5.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
                <span style="font-size:.82rem;font-weight:600;color:var(--pw-text-light);">Registrasi Pemain</span>
            </div>
            <span style="font-size:.68rem;color:var(--pw-text-muted);">30 hari terakhir</span>
        </div>
        <div style="padding:1rem;">
            <canvas id="chartReg" height="130"></canvas>
        </div>
    </div>

    {{-- Chart Income --}}
    <div class="pw-adm-card" style="padding:0;overflow:hidden;">
        <div style="padding:.85rem 1rem;border-bottom:1px solid var(--pw-border);display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:.5rem;">
                <svg viewBox="0 0 20 20" fill="none" width="15" style="color:var(--pw-text-muted);"><rect x="2" y="5" width="16" height="11" rx="2" stroke="currentColor" stroke-width="1.4"/><path d="M2 9h16" stroke="currentColor" stroke-width="1.4"/></svg>
                <span style="font-size:.82rem;font-weight:600;color:var(--pw-text-light);">Income Harian</span>
            </div>
            <span style="font-size:.68rem;color:var(--pw-text-muted);">30 hari terakhir</span>
        </div>
        <div style="padding:1rem;">
            <canvas id="chartIncome" height="130"></canvas>
        </div>
    </div>

</div>

{{-- ── ROW 4: Recent Data ── --}}
<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1.2rem;">

    {{-- User Terbaru --}}
    <div class="pw-adm-card" style="padding:0;overflow:hidden;">
        <div style="padding:.85rem 1rem;border-bottom:1px solid var(--pw-border);display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:.5rem;">
                <svg viewBox="0 0 20 20" fill="none" width="15" style="color:var(--pw-text-muted);"><circle cx="10" cy="7" r="3.5" stroke="currentColor" stroke-width="1.4"/><path d="M3 17c0-3 3-5.5 7-5.5s7 2.5 7 5.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
                <span style="font-size:.82rem;font-weight:600;color:var(--pw-text-light);">Registrasi Terbaru</span>
            </div>
            <a href="{{ route('admin.members.index') }}" style="font-size:.68rem;color:var(--pw-text-muted);">Lihat semua</a>
        </div>
        <div>
            @forelse($recentUsers as $u)
            <div style="display:flex;align-items:center;gap:.7rem;padding:.6rem 1rem;border-bottom:1px solid var(--pw-border);">
                <div style="width:28px;height:28px;border-radius:50%;background:rgba(184,134,11,.15);display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:700;color:var(--pw-gold-light);flex-shrink:0;">
                    {{ strtoupper(substr($u->name, 0, 1)) }}
                </div>
                <div style="min-width:0;">
                    <div style="font-size:.8rem;font-weight:600;color:var(--pw-text-light);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $u->name }}</div>
                    <div style="font-size:.65rem;color:var(--pw-text-muted);">
                        {{ Carbon\Carbon::parse($u->creatime)->diffForHumans() }}
                        @if($u->referred_by)
                            <span style="color:#38bdf8;margin-left:.3rem;">via referral</span>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div style="padding:1.5rem;text-align:center;color:var(--pw-text-muted);font-size:.82rem;">Belum ada data.</div>
            @endforelse
        </div>
    </div>

    {{-- Transaksi Terbaru --}}
    <div class="pw-adm-card" style="padding:0;overflow:hidden;">
        <div style="padding:.85rem 1rem;border-bottom:1px solid var(--pw-border);display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:.5rem;">
                <svg viewBox="0 0 20 20" fill="none" width="15" style="color:var(--pw-text-muted);"><rect x="2" y="5" width="16" height="11" rx="2" stroke="currentColor" stroke-width="1.4"/><path d="M2 9h16" stroke="currentColor" stroke-width="1.4"/></svg>
                <span style="font-size:.82rem;font-weight:600;color:var(--pw-text-light);">Transaksi Terbaru</span>
            </div>
            <a href="{{ route('admin.donate') }}" style="font-size:.68rem;color:var(--pw-text-muted);">Lihat semua</a>
        </div>
        <div>
            @forelse($recentInvoices as $inv)
            <div style="display:flex;align-items:center;gap:.7rem;padding:.55rem 1rem;border-bottom:1px solid var(--pw-border);">
                <div style="min-width:0;flex:1;">
                    <div style="font-size:.78rem;font-weight:600;color:var(--pw-text-light);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $inv->user->name ?? '-' }}</div>
                    <div style="font-size:.65rem;color:var(--pw-text-muted);">{{ $inv->created_at->diffForHumans() }}</div>
                </div>
                <div style="text-align:right;flex-shrink:0;">
                    <div style="font-size:.75rem;font-weight:700;color:#22c55e;">Rp {{ number_format($inv->amount) }}</div>
                    @if($inv->status === 'paid')
                        <span class="pw-badge pw-badge--success" style="font-size:.6rem;">Paid</span>
                    @elseif($inv->status === 'pending')
                        <span class="pw-badge pw-badge--warning" style="font-size:.6rem;">Pending</span>
                    @else
                        <span class="pw-badge pw-badge--danger" style="font-size:.6rem;">Gagal</span>
                    @endif
                </div>
            </div>
            @empty
            <div style="padding:1.5rem;text-align:center;color:var(--pw-text-muted);font-size:.82rem;">Belum ada transaksi.</div>
            @endforelse
        </div>
    </div>

    {{-- Pembelian Shop Terbaru --}}
    <div class="pw-adm-card" style="padding:0;overflow:hidden;">
        <div style="padding:.85rem 1rem;border-bottom:1px solid var(--pw-border);display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:.5rem;">
                <svg viewBox="0 0 20 20" fill="none" width="15" style="color:var(--pw-text-muted);"><path d="M5 5h10l-1.5 8H6.5L5 5zm0 0L4 3H2" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span style="font-size:.82rem;font-weight:600;color:var(--pw-text-light);">Pembelian Shop</span>
            </div>
            <a href="{{ route('admin.shop.index') }}" style="font-size:.68rem;color:var(--pw-text-muted);">Lihat semua</a>
        </div>
        <div>
            @forelse($recentShopLogs as $log)
            <div style="display:flex;align-items:center;gap:.7rem;padding:.55rem 1rem;border-bottom:1px solid var(--pw-border);">
                <div style="min-width:0;flex:1;">
                    <div style="font-size:.78rem;font-weight:600;color:var(--pw-text-light);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $log->user->name ?? '-' }}</div>
                    <div style="font-size:.65rem;color:var(--pw-text-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $log->item_name }}</div>
                </div>
                <div style="text-align:right;flex-shrink:0;">
                    <div style="font-size:.75rem;font-weight:600;color:#e5a615;">{{ number_format($log->price) }} G</div>
                    <div style="font-size:.62rem;color:var(--pw-text-muted);">{{ $log->created_at->diffForHumans() }}</div>
                </div>
            </div>
            @empty
            <div style="padding:1.5rem;text-align:center;color:var(--pw-text-muted);font-size:.82rem;">Belum ada pembelian.</div>
            @endforelse
        </div>
    </div>

</div>

@push('styles')
<style>
.pw-adm-stat {
    background: var(--pw-bg-card, rgba(255,255,255,.04));
    border: 1px solid var(--pw-border, rgba(255,255,255,.08));
    border-radius: 10px; padding: .9rem 1rem;
    display: flex; flex-direction: column; gap: .3rem;
}
.pw-adm-stat__value { font-size: 1.4rem; font-weight: 700; color: var(--pw-text, #e8dfc8); line-height: 1; }
.pw-adm-stat__label { font-size: .68rem; color: var(--pw-text-muted, #7a7a9a); text-transform: uppercase; letter-spacing: .05em; }
.pw-adm-stat__icon { flex-shrink: 0; }
[data-theme="light"] .pw-adm-stat { background: #fff; border-color: rgba(0,0,0,.12); box-shadow: 0 1px 4px rgba(0,0,0,.06); }
[data-theme="light"] .pw-adm-stat__value { color: var(--pw-text-light); }
@keyframes pw-pulse { 0%,100%{opacity:1;} 50%{opacity:.4;} }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {
    const regLabels = @json(array_column($regChart, 'date'));
    const regData   = @json(array_column($regChart, 'total'));
    const incLabels = @json(array_column($incomeChart, 'date'));
    const incData   = @json(array_column($incomeChart, 'total'));

    function getColors() {
        const dark = document.documentElement.getAttribute('data-theme') !== 'light';
        return {
            grid: dark ? 'rgba(255,255,255,.08)' : 'rgba(0,0,0,.13)',
            tick: dark ? '#7a7a9a' : '#555',
        };
    }

    function makeOpts() {
        const c = getColors();
        return {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { display: false }, tooltip: { mode: 'index', intersect: false } },
            scales: {
                x: { ticks: { color: c.tick, font: { size: 10 }, maxTicksLimit: 8, maxRotation: 0 }, grid: { color: c.grid } },
                y: { ticks: { color: c.tick, font: { size: 10 } }, grid: { color: c.grid }, beginAtZero: true },
            },
        };
    }

    const chartReg = new Chart(document.getElementById('chartReg'), {
        type: 'bar',
        data: {
            labels: regLabels,
            datasets: [{
                data: regData,
                backgroundColor: 'rgba(184,134,11,.35)',
                borderColor: 'rgba(212,168,96,.8)',
                borderWidth: 1,
                borderRadius: 3,
            }],
        },
        options: makeOpts(),
    });

    const chartIncome = new Chart(document.getElementById('chartIncome'), {
        type: 'line',
        data: {
            labels: incLabels,
            datasets: [{
                data: incData,
                borderColor: '#22c55e',
                backgroundColor: 'rgba(34,197,94,.1)',
                borderWidth: 2,
                pointRadius: 2,
                pointHoverRadius: 5,
                fill: true,
                tension: 0.4,
            }],
        },
        options: makeOpts(),
    });

    // Update warna chart saat user toggle dark/light mode
    const themeObserver = new MutationObserver(() => {
        const opts = makeOpts();
        [chartReg, chartIncome].forEach(ch => {
            ch.options.scales.x.ticks.color  = opts.scales.x.ticks.color;
            ch.options.scales.x.grid.color   = opts.scales.x.grid.color;
            ch.options.scales.y.ticks.color  = opts.scales.y.ticks.color;
            ch.options.scales.y.grid.color   = opts.scales.y.grid.color;
            ch.update('none');
        });
    });
    themeObserver.observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });
})();
</script>
@endpush

@endsection
