<div class="pw-shop-card" x-data="{ confirm: false }">

    {{-- Image --}}
    <div class="pw-shop-card__img">
        @if($item->image)
            <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}" loading="lazy">
        @else
            <img src="{{ asset('images/gif_icon/web_coin.gif') }}" alt="Gold Points" width="72" style="opacity:.75;">
        @endif
        {{-- Badges --}}
        @if($item->stock !== null && $item->stock <= 10)
        <span class="pw-shop-card__badge pw-shop-card__badge--stock">
            @if($item->stock === 0) Habis @else Sisa {{ $item->stock }} @endif
        </span>
        @endif
        @if($item->item_count > 1)
        <span class="pw-shop-card__badge pw-shop-card__badge--qty">×{{ $item->item_count }}</span>
        @endif
    </div>

    {{-- Body --}}
    <div class="pw-shop-card__body">
        <h3 class="pw-shop-card__name">{{ $item->name }}</h3>
        @if($item->description)
        <p class="pw-shop-card__desc">{{ Str::limit($item->description, 65) }}</p>
        @endif
        <div class="pw-shop-card__price">
            <img src="{{ asset('images/gif_icon/web_coin.gif') }}" alt="Gold Points" width="18" style="vertical-align:middle;">
            {{ number_format($item->price) }}
            <span class="pw-shop-card__price-unit">Gold Points</span>
        </div>
    </div>

    {{-- Action --}}
    <div class="pw-shop-card__foot">
        @if($item->stock !== null && $item->stock <= 0)
        <button class="pw-shop-card__btn pw-shop-card__btn--disabled" disabled>Habis</button>
        @elseif(auth()->user()->money < $item->price)
        <button class="pw-shop-card__btn pw-shop-card__btn--disabled" disabled>Gold Points Kurang</button>
        @else
        {{-- Confirm flow --}}
        <div x-show="!confirm">
            <button @click="confirm = true" class="pw-shop-card__btn pw-shop-card__btn--buy">
                Beli Sekarang
            </button>
        </div>
        <div x-show="confirm" x-transition style="display:none;">
            @if(session('active_character'))
            <p class="pw-shop-card__confirm-text">Beli <strong>{{ $item->name }}</strong> seharga <strong>{{ number_format($item->price) }} Gold Points</strong>?
                @if($item->item_id && $item->item_id > 0)
                <br><span style="font-size:.75rem;color:#4ade80;">Dikirim ke: {{ session('active_character')->name }}</span>
                @endif
            </p>
            @else
            <p class="pw-shop-card__confirm-text">Beli <strong>{{ $item->name }}</strong> seharga <strong>{{ number_format($item->price) }} Gold Points</strong>?
                @if($item->item_id && $item->item_id > 0)
                <br><span style="font-size:.75rem;color:#f59e0b;">Pilih karakter di navbar dulu!</span>
                @endif
            </p>
            @endif
            <div style="display:flex;gap:.4rem;">
                <form action="{{ route('shop.buy', $item->id) }}" method="POST" style="flex:1;">
                    @csrf
                    <button type="submit" class="pw-shop-card__btn pw-shop-card__btn--buy" style="width:100%;">Ya, Beli</button>
                </form>
                <button @click="confirm = false" class="pw-shop-card__btn pw-shop-card__btn--cancel">Batal</button>
            </div>
        </div>
        @endif
    </div>

</div>