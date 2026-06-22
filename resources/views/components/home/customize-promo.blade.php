@props([
    'image' => null,
])

<a href="{{ route('shop') }}" class="home-customize-promo group">
    <div class="home-customize-promo-content">
        <h3 class="home-customize-promo-title">Customize Your Jersey</h3>
        <p class="home-customize-promo-subtitle">Add your name &amp; number</p>
        <span class="home-customize-promo-btn">
            Customize Now
            <x-icons.arrow-right class="h-4 w-4 transition-transform group-hover:translate-x-0.5" />
        </span>
    </div>
    <div class="home-customize-promo-image-wrap" aria-hidden="true">
        @if ($image)
            <img src="{{ $image }}" alt="" loading="lazy" class="home-customize-promo-image">
        @else
            <div class="home-customize-promo-image-placeholder">
                <svg class="h-24 w-24 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M6.5 3.5l2.2 1.1a2 2 0 001.8 0L12.7 3.5 16 5v4.2a6 6 0 01-3.1 5.2L12 15.5l-.9-.3A6 6 0 018 9.2V5l-1.5-.5z"/>
                </svg>
            </div>
        @endif
    </div>
</a>
