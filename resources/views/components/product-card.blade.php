@props([
    'product' => null,
    'name' => null,
    'price' => null,
    'image' => null,
    'slug' => null,
])

@php
    $name = $product?->name ?? $name ?? 'Product';
    $slug = $product?->slug ?? $slug ?? 'sample-product';
    $image = $product?->thumbnail_url ?? $image ?? 'https://placehold.co/600x750/e5e5e5/737373?text=Jersey';
    $url = route('product.show', $slug);

    $clubLeague = $product
        ? collect([$product->team?->name, $product->league?->name])->filter()->join(' · ')
        : null;

    $hasSale = $product && $product->sale_price;
    $displayPrice = $product ? (float) ($hasSale ? $product->sale_price : $product->price) : null;
    $originalPrice = $product && $hasSale ? (float) $product->price : null;

    $stockStatus = $product?->stock_status->value ?? $product?->stock_status ?? 'in_stock';
    $stockConfig = match ($stockStatus) {
        'in_stock' => ['label' => 'In Stock', 'dot' => 'bg-green-500', 'text' => 'text-green-700'],
        'out_of_stock' => ['label' => 'Out of Stock', 'dot' => 'bg-red-500', 'text' => 'text-red-600'],
        'limited_stock' => ['label' => 'Low Stock', 'dot' => 'bg-amber-500', 'text' => 'text-amber-700'],
        default => ['label' => ucfirst(str_replace('_', ' ', $stockStatus)), 'dot' => 'bg-gray-400', 'text' => 'text-gray-600'],
    };

    $marketingTag = null;
    if ($product) {
        if ($product->is_best_seller) {
            $marketingTag = 'Best Seller';
        } elseif ($product->is_new_arrival) {
            $marketingTag = 'New Arrival';
        } elseif ($product->is_featured) {
            $marketingTag = 'Featured';
        }
    }
@endphp

<article class="product-card group flex h-full flex-col">
    <div class="product-card-media">
        <a href="{{ $url }}" class="product-card-image-link" tabindex="-1" aria-hidden="true">
            <img
                src="{{ $image }}"
                alt="{{ $name }}"
                loading="lazy"
                class="product-card-image"
            >
        </a>

        <div class="product-card-badges">
            @if ($hasSale)
                <span class="product-badge product-badge-sale">Sale</span>
            @endif
            @if ($product?->is_customizable)
                <span class="product-badge product-badge-custom">Customizable</span>
            @endif
        </div>

        <button
            type="button"
            class="product-card-wishlist"
            aria-label="Add to wishlist (coming soon)"
            disabled
            @click.stop
        >
            <x-icons.heart class="h-[18px] w-[18px]" />
        </button>
    </div>

    <div class="product-card-body">
        @if ($clubLeague)
            <p class="product-card-meta">{{ $clubLeague }}</p>
        @endif

        <h3 class="product-card-title">
            <a href="{{ $url }}">{{ $name }}</a>
        </h3>

        @if ($marketingTag)
            <span class="product-card-tag">{{ $marketingTag }}</span>
        @endif

        <div class="product-card-price-row">
            @if ($displayPrice !== null)
                <span class="product-card-price">${{ number_format($displayPrice, 2) }}</span>
                @if ($originalPrice)
                    <span class="product-card-price-original">${{ number_format($originalPrice, 2) }}</span>
                @endif
            @else
                <span class="product-card-price">{{ $price ?? '$89.99' }}</span>
            @endif
        </div>

        @if ($product)
            <p class="product-card-stock {{ $stockConfig['text'] }}">
                <span class="inline-block h-1.5 w-1.5 rounded-full {{ $stockConfig['dot'] }}"></span>
                {{ $stockConfig['label'] }}
            </p>
        @endif

        <a href="{{ $url }}" class="product-card-cta">
            View Product
            <svg class="h-4 w-4 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>
</article>
