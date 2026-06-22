@props([
    'name',
    'href' => '#',
    'image' => null,
    'slug' => null,
])

@php
    use App\Support\StorageUrl;

    $imageUrl = StorageUrl::publicUrl($image);
    $icon = match ($slug ?? '') {
        'football-jerseys' => 'shirt',
        'nba-shirts' => 'basketball',
        'accessories' => 'accessories',
        'sale' => 'sale',
        default => 'default',
    };
    $gradient = match ($slug ?? '') {
        'football-jerseys' => 'home-hero-category-gradient--football',
        'nba-shirts' => 'home-hero-category-gradient--nba',
        'accessories' => 'home-hero-category-gradient--accessories',
        'sale' => 'home-hero-category-gradient--sale',
        default => 'home-hero-category-gradient--default',
    };
    $subtitle = match ($slug ?? '') {
        'football-jerseys' => 'Latest club kits',
        'nba-shirts' => 'Official-style shirts',
        'accessories' => 'Scarves, caps & more',
        'sale' => 'Limited-time deals',
        default => 'Explore collection',
    };
@endphp

<a href="{{ $href }}" class="home-hero-category-card group">
    <div class="home-hero-category-card-glow" aria-hidden="true"></div>

    <div class="home-hero-category-card-content">
        <h3 class="home-hero-category-card-title">{{ $name }}</h3>
        <p class="home-hero-category-card-subtitle">{{ $subtitle }}</p>
        <span class="home-hero-category-card-action" aria-hidden="true">
            <x-icons.arrow-up-right class="h-4 w-4" />
        </span>
    </div>

    <div class="home-hero-category-card-visual" aria-hidden="true">
        @if ($imageUrl)
            <img src="{{ $imageUrl }}" alt="" loading="lazy" class="home-hero-category-card-image">
            <div class="home-hero-category-card-image-shade"></div>
        @else
            <div class="home-hero-category-gradient {{ $gradient }}">
                <div class="home-hero-category-icon">
                    @if ($icon === 'shirt')
                        <svg class="h-full w-full" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.25" d="M6.5 3.5l2.2 1.1a2 2 0 001.8 0L12.7 3.5 16 5v4.2a6 6 0 01-3.1 5.2L12 15.5l-.9-.3A6 6 0 018 9.2V5l-1.5-.5z"/>
                        </svg>
                    @elseif ($icon === 'basketball')
                        <svg class="h-full w-full" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="12" cy="12" r="8.25" stroke-width="1.25"/>
                            <path stroke-linecap="round" stroke-width="1.25" d="M4.2 8.5h15.6M4.2 15.5h15.6M12 3.75v16.5"/>
                        </svg>
                    @elseif ($icon === 'accessories')
                        <svg class="h-full w-full" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.25" d="M8 8V6.5A2.5 2.5 0 0110.5 4h3A2.5 2.5 0 0116 6.5V8"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.25" d="M6.5 8h11l-.8 11.5a1.5 1.5 0 01-1.5 1.5H8.8a1.5 1.5 0 01-1.5-1.5L6.5 8z"/>
                        </svg>
                    @elseif ($icon === 'sale')
                        <svg class="h-full w-full" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.25" d="M8.5 16.5l7-7"/>
                            <circle cx="9" cy="9" r="1.25" stroke-width="1.25"/>
                            <circle cx="15" cy="15" r="1.25" stroke-width="1.25"/>
                        </svg>
                    @else
                        <x-icons.football class="h-full w-full" />
                    @endif
                </div>
            </div>
        @endif
    </div>
</a>
