@props([
    'image' => null,
])

<a href="{{ route('shop', ['customizable' => 1]) }}" class="home-customize-banner group">
    <div class="home-customize-banner-overlay" aria-hidden="true"></div>

    <div class="home-customize-banner-inner">
        <div class="home-customize-banner-content">
            <h2 class="home-customize-banner-title">Customize Your Jersey</h2>
            <p class="home-customize-banner-subtitle">Add your name and number to selected kits.</p>
            <span class="home-customize-banner-btn">
                Explore Customizable Kits
                <x-icons.arrow-right class="h-4 w-4 transition-transform group-hover:translate-x-0.5" />
            </span>
        </div>

        <div class="home-customize-banner-image-wrap" aria-hidden="true">
            @if ($image)
                <img src="{{ $image }}" alt="" loading="lazy" class="home-customize-banner-image">
            @else
                <div class="home-customize-banner-placeholder">
                    <x-icons.football class="h-16 w-16 text-white/20" />
                </div>
            @endif
        </div>
    </div>
</a>
