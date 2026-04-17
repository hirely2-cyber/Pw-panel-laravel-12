@extends('layouts.admin')
@section('title', 'Edit Event: ' . $event->title)

@section('content')
<div style="margin-bottom:1rem;">
    <a href="{{ route('admin.events.index', ['tab' => $event->isPreLaunch() ? 'pre_launch' : 'grand_launch']) }}" class="pw-adm-btn pw-adm-btn--ghost pw-adm-btn--sm">← Kembali</a>
</div>

<div class="pw-adm-card">
    <div class="pw-adm-card__title">
        Edit Event
        @if($event->isPreLaunch())
        <span class="pw-badge" style="background:rgba(56,189,248,.15);color:#38bdf8;margin-left:.5rem;">Pre-Launch</span>
        @else
        <span class="pw-badge" style="background:rgba(200,151,42,.15);color:#c8972a;margin-left:.5rem;">Grand Launch</span>
        @endif
    </div>

    <form method="POST" action="{{ route('admin.events.update', $event) }}">
        @csrf
        @method('PUT')

        <div style="margin-bottom:1rem;">
            <label class="pw-adm-label">Nama Event (ID)</label>
            <input type="text" name="title" class="pw-adm-input" value="{{ old('title', $event->title) }}" required>
        </div>

        <div style="margin-bottom:1rem;">
            <label class="pw-adm-label">Nama Event (EN)</label>
            <input type="text" name="title_en" class="pw-adm-input" value="{{ old('title_en', $event->title_en) }}" placeholder="English title">
        </div>

        <div style="margin-bottom:1rem;">
            <label class="pw-adm-label">Deskripsi (ID)</label>
            <textarea name="description" class="pw-adm-input" rows="3" style="resize:vertical;">{{ old('description', $event->description) }}</textarea>
        </div>

        <div style="margin-bottom:1rem;">
            <label class="pw-adm-label">Deskripsi (EN)</label>
            <textarea name="description_en" class="pw-adm-input" rows="3" style="resize:vertical;" placeholder="English description">{{ old('description_en', $event->description_en) }}</textarea>
        </div>

        @if($event->isPreLaunch())
        {{-- PRE-LAUNCH FIELDS --}}
        <div style="margin-bottom:1rem;">
            <label class="pw-adm-label">Syarat Level Karakter</label>
            <input type="number" name="referral_req_level" class="pw-adm-input" value="{{ old('referral_req_level', $event->referral_req_level) }}" min="1" max="150" required>
            <div style="font-size:.72rem;color:var(--pw-text-muted);margin-top:.3rem;">
                Setiap ID yang di-refer harus punya minimal 1 karakter di level ini
            </div>
        </div>

        <div x-data="editTiers()" style="background:rgba(200,151,42,.06);border:1px solid rgba(200,151,42,.15);border-radius:8px;padding:1rem;margin-bottom:1rem;">
            <div style="font-size:.85rem;font-weight:700;color:#c8972a;margin-bottom:.8rem;">Referral Reward Tiers</div>
            <template x-for="(tier, index) in tiers" :key="index">
                <div style="display:grid;grid-template-columns:1fr 1fr auto;gap:.5rem;margin-bottom:.5rem;align-items:end;">
                    <div>
                        <label class="pw-adm-label" x-show="index === 0">Jumlah Referral</label>
                        <input type="number" :name="'referral_tiers['+index+'][count]'" class="pw-adm-input" x-model.number="tier.count" min="1" required placeholder="Jumlah">
                    </div>
                    <div>
                        <label class="pw-adm-label" x-show="index === 0">Reward (Cubi Gold)</label>
                        <input type="number" :name="'referral_tiers['+index+'][reward]'" class="pw-adm-input" x-model.number="tier.reward" min="1" required placeholder="Reward">
                    </div>
                    <button type="button" @click="tiers.splice(index, 1)" class="pw-adm-btn pw-adm-btn--danger pw-adm-btn--sm" x-show="tiers.length > 1" style="margin-bottom:2px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
                    </button>
                </div>
            </template>
            <button type="button" @click="tiers.push({count:'', reward:''})" class="pw-adm-btn pw-adm-btn--ghost pw-adm-btn--sm" style="margin-top:.5rem;">
                + Tambah Tier
            </button>
        </div>

        {{-- REGISTER REWARDS (Pre-Launch) --}}
        <div x-data="editRegisterRewards()" style="background:rgba(74,222,128,.05);border:1px solid rgba(74,222,128,.15);border-radius:8px;padding:1rem;margin-bottom:1rem;">
            <div style="font-size:.85rem;font-weight:700;color:#4ade80;margin-bottom:.3rem;">Hadiah Register (Daftar Akun)</div>
            <div style="font-size:.72rem;color:var(--pw-text-muted);margin-bottom:.8rem;">Item yang diterima pemain yang sudah daftar <strong>dan mencapai level minimal</strong>. Kosongkan jika tidak ada hadiah register.</div>

            <div style="margin-bottom:.8rem;">
                <label class="pw-adm-label">Syarat Minimal Level Karakter</label>
                <input type="number" name="register_req_level" class="pw-adm-input" value="{{ old('register_req_level', $event->register_req_level ?? 50) }}" min="1" max="150" style="max-width:120px;">
                <div style="font-size:.72rem;color:var(--pw-text-muted);margin-top:.3rem;">Player harus punya karakter yang sudah mencapai level ini agar dapat hadiah register.</div>
            </div>
            <template x-for="(item, index) in items" :key="index">
                <div style="display:grid;grid-template-columns:1fr 80px auto;gap:.5rem;margin-bottom:.5rem;align-items:end;">
                    <div>
                        <label class="pw-adm-label" x-show="index === 0">Nama Hadiah</label>
                        <input type="text" :name="'register_rewards['+index+'][label]'" class="pw-adm-input" x-model="item.label" required placeholder="Contoh: Cubi Gold, Mystery Box, Fashion Set">
                    </div>
                    <div>
                        <label class="pw-adm-label" x-show="index === 0">Jumlah</label>
                        <input type="number" :name="'register_rewards['+index+'][amount]'" class="pw-adm-input" x-model.number="item.amount" min="1" required placeholder="50">
                    </div>
                    <button type="button" @click="items.splice(index, 1)" class="pw-adm-btn pw-adm-btn--danger pw-adm-btn--sm" x-show="items.length > 1" style="margin-bottom:2px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
                    </button>
                </div>
            </template>
            <button type="button" @click="items.push({label:'', amount:1})" class="pw-adm-btn pw-adm-btn--ghost pw-adm-btn--sm" style="margin-top:.5rem;">
                + Tambah Hadiah
            </button>
        </div>

        @else
        {{-- GRAND LAUNCH FIELDS --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
            <div>
                <label class="pw-adm-label">Syarat Level</label>
                <input type="number" name="req_level" class="pw-adm-input" value="{{ old('req_level', $event->req_level) }}" min="1" max="150" required>
            </div>
            <div>
                <label class="pw-adm-label">Syarat Cultivation</label>
                <select name="req_cultivation" class="pw-adm-input">
                    @foreach(\App\Models\LaunchEvent::CULTIVATION_OPTIONS as $val => $label)
                    <option value="{{ $val }}" {{ old('req_cultivation', $event->req_cultivation) == $val ? 'selected' : '' }}>{{ $val }} — {{ $label }}</option>
                    @endforeach
                </select>
                <div style="font-size:.72rem;color:var(--pw-text-muted);margin-top:.3rem;">
                    Light & Dark path otomatis diterima di tier yang sama
                </div>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
            <div>
                <label class="pw-adm-label">Total Hadiah (Cubi Gold)</label>
                <input type="number" name="prize_total_cubi" class="pw-adm-input" value="{{ old('prize_total_cubi', $event->prize_total_cubi) }}" min="1" required>
            </div>
            <div>
                <label class="pw-adm-label">Jumlah Pemenang</label>
                <input type="number" name="prize_winner_count" class="pw-adm-input" value="{{ old('prize_winner_count', $event->prize_winner_count) }}" min="4" required>
            </div>
        </div>

        <div style="background:rgba(200,151,42,.06);border:1px solid rgba(200,151,42,.15);border-radius:8px;padding:1rem;margin-bottom:1rem;">
            <div style="font-size:.85rem;font-weight:700;color:#c8972a;margin-bottom:.8rem;">Hadiah Bertingkat (Cubi Gold)</div>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:.8rem;margin-bottom:.8rem;">
                <div>
                    <label class="pw-adm-label">Juara 1</label>
                    <input type="number" name="prize_rank1" class="pw-adm-input" value="{{ old('prize_rank1', $event->prize_rank1) }}" min="0">
                </div>
                <div>
                    <label class="pw-adm-label">Juara 2</label>
                    <input type="number" name="prize_rank2" class="pw-adm-input" value="{{ old('prize_rank2', $event->prize_rank2) }}" min="0">
                </div>
                <div>
                    <label class="pw-adm-label">Juara 3</label>
                    <input type="number" name="prize_rank3" class="pw-adm-input" value="{{ old('prize_rank3', $event->prize_rank3) }}" min="0">
                </div>
            </div>
            <div style="font-size:.78rem;color:var(--pw-text-muted);" x-data x-init="
                const fields = ['prize_total_cubi','prize_winner_count','prize_rank1','prize_rank2','prize_rank3'];
                const els = Object.fromEntries(fields.map(f => [f, document.querySelector('[name='+f+']')]));
                const upd = () => {
                    const total = parseInt(els.prize_total_cubi?.value||0);
                    const winners = parseInt(els.prize_winner_count?.value||0);
                    const r1 = parseInt(els.prize_rank1?.value||0);
                    const r2 = parseInt(els.prize_rank2?.value||0);
                    const r3 = parseInt(els.prize_rank3?.value||0);
                    const sisa = total - r1 - r2 - r3;
                    const restCount = Math.max(1, winners - 3);
                    const perPerson = sisa > 0 ? Math.floor(sisa / restCount) : 0;
                    $el.innerHTML = 'Sisa: <strong>' + sisa.toLocaleString() + ' Cubi</strong> &divide; ' + restCount + ' orang = <strong>' + perPerson.toLocaleString() + ' Cubi/orang</strong> (rank 4 dst)';
                };
                fields.forEach(f => els[f]?.addEventListener('input', upd));
                upd();
            ">—</div>
        </div>
        @endif

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem;">
            <div>
                <label class="pw-adm-label">Tanggal Mulai</label>
                <input type="datetime-local" name="start_at" class="pw-adm-input" value="{{ old('start_at', $event->start_at?->format('Y-m-d\TH:i')) }}" required>
            </div>
            <div>
                <label class="pw-adm-label">Tanggal Berakhir</label>
                <input type="datetime-local" name="end_at" class="pw-adm-input" value="{{ old('end_at', $event->end_at?->format('Y-m-d\TH:i')) }}" required>
            </div>
        </div>

        <button type="submit" class="pw-adm-btn" style="width:100%;">Update Event</button>
    </form>
</div>
@endsection

@if($event->isPreLaunch())
@push('scripts')
<script>
function editTiers() {
    return {
        tiers: @json(old('referral_tiers', $event->referral_tiers ?? [['count' => 10, 'reward' => 50]])),
    };
}
function editRegisterRewards() {
    return {
        items: @json(old('register_rewards', $event->register_rewards ?? [['label' => 'Cubi Gold', 'amount' => 50]])),
    };
}
</script>
@endpush
@endif
