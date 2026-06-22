@props([
    'title',
    'subtitle' => null,
    'cta' => 'Shop Now',
    'href' => '#',
    'image' => null,
])

<a href="{{ $href }}" class="home-promo-card group">
    <div class="home-promo-card-content">
        <h3 class="home-promo-card-title">{{ $title }}</h3>
        @if ($subtitle)
            <p class="home-promo-card-subtitle">{{ $subtitle }}</p>
        @endif
        <span class="home-promo-card-cta">
            {{ $cta }}
            <x-icons.arrow-right class="h-3.5 w-3.5 transition-transform group-hover:translate-x-0.5" />
        </span>
    </div>
    <div class="home-promo-card-image-wrap" aria-hidden="true">
        @if ($image)
            <img src="{{ $image }}" alt="" loading="lazy" class="home-promo-card-image">
        @else
            <div class="home-promo-card-image-placeholder"></div>
        @endif
    </div>
</a>
