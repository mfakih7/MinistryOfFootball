@props([
    'product' => null,
    'name' => null,
    'price' => null,
    'image' => null,
    'slug' => null,
])

@php
    use App\Support\StorageUrl;

    $name = $product?->name ?? $name ?? 'Product';
    $slug = $product?->slug ?? $slug ?? 'sample-product';
    $url = route('product.show', $slug);

    $hasRealImage = $product && (
        StorageUrl::exists($product->main_image)
        || StorageUrl::exists($product->thumbnail_image)
        || StorageUrl::exists($product->medium_image)
        || StorageUrl::exists($product->large_image)
        || ($product->relationLoaded('images') && $product->images->isNotEmpty())
    );

    $image = $hasRealImage
        ? ($product->thumbnail_url ?? $product->medium_url ?? $product->large_image_url)
        : null;

    $clubLeague = $product
        ? collect([$product->team?->name, $product->league?->name])->filter()->join(' · ')
        : null;

    $hasSale = $product && $product->sale_price;
    $displayPrice = $product ? (float) ($hasSale ? $product->sale_price : $product->price) : null;
    $originalPrice = $product && $hasSale ? (float) $product->price : null;

    $showNew = $product?->is_new_arrival && ! $hasSale;
    $showFeatured = $product?->is_featured && ! $hasSale && ! $showNew;
@endphp

<article class="product-card group flex h-full flex-col">
    <div class="product-card-media">
        <a href="{{ $url }}" class="product-card-image-link" tabindex="-1" aria-hidden="true">
            @if ($hasRealImage && $image)
                <img
                    src="{{ $image }}"
                    alt="{{ $name }}"
                    loading="lazy"
                    class="product-card-image"
                >
            @elseif ($image)
                <img
                    src="{{ $image }}"
                    alt="{{ $name }}"
                    loading="lazy"
                    class="product-card-image"
                >
            @else
                <div class="product-card-placeholder">
                    <x-icons.football class="product-card-placeholder-icon" />
                </div>
            @endif
        </a>

        @if ($hasSale || $showNew || $showFeatured)
            <div class="product-card-badges">
                @if ($hasSale)
                    <span class="product-badge product-badge-sale">Sale</span>
                @elseif ($showNew)
                    <span class="product-badge product-badge-new">New</span>
                @elseif ($showFeatured)
                    <span class="product-badge product-badge-featured">Featured</span>
                @endif
            </div>
        @endif

        <a href="{{ $url }}" class="product-card-quick-action" aria-label="View {{ $name }}">
            <x-icons.arrow-up-right class="h-4 w-4" />
        </a>
    </div>

    <div class="product-card-body">
        @if ($clubLeague)
            <p class="product-card-meta">{{ $clubLeague }}</p>
        @endif

        <h3 class="product-card-title">
            <a href="{{ $url }}">{{ $name }}</a>
        </h3>

        <div class="product-card-price-row">
            @if ($displayPrice !== null)
                <span @class(['product-card-price', 'product-card-price--sale' => $hasSale])>${{ number_format($displayPrice, 2) }}</span>
                @if ($originalPrice)
                    <span class="product-card-price-original">${{ number_format($originalPrice, 2) }}</span>
                @endif
            @else
                <span class="product-card-price">{{ $price ?? '$89.99' }}</span>
            @endif
        </div>
    </div>
</article>
