@extends('layouts.app')

@section('content')
    @php
        $sizes = $product?->variants->pluck('size')->filter()->unique('id') ?? collect();
        $colors = $product?->variants->pluck('color')->filter()->unique('id') ?? collect();
        $mainImage = $mainImage ?? $product?->large_image_url ?? $product?->main_image_url ?? 'https://placehold.co/800x800/e5e5e5/737373?text='.urlencode($productName);
        $galleryImages = $galleryImages ?? collect();
        $variantData = $product?->variants->map(fn ($v) => [
            'id' => $v->id,
            'size_id' => $v->size_id,
            'color_id' => $v->color_id,
        ])->values() ?? collect();
        $discountPercent = $product?->sale_price
            ? (int) round((((float) $product->price - (float) $product->sale_price) / (float) $product->price) * 100)
            : null;

        $thumbItems = collect();
        if ($product) {
            $thumbItems->push(['thumb' => $mainImage, 'hero' => $mainImage, 'alt' => $productName]);
            foreach ($galleryImages as $galleryImage) {
                $thumbItems->push([
                    'thumb' => $galleryImage->display_url ?? $mainImage,
                    'hero' => $galleryImage->hero_url ?? $mainImage,
                    'alt' => $galleryImage->alt_text ?? $productName,
                ]);
            }
        }
        $lightboxImages = $thumbItems->pluck('hero')->values();
    @endphp

    <div class="container-store py-8 lg:py-12">
        <nav class="mb-6 text-sm text-gray-500" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-brand-red">Home</a>
            <span class="mx-2">/</span>
            <a href="{{ route('shop') }}" class="hover:text-brand-red">Shop</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900">{{ $productName }}</span>
        </nav>

        <div class="grid gap-8 lg:grid-cols-2 lg:gap-12">
            {{-- Gallery --}}
            <div
                @if($product) x-data="{
                    images: @js($lightboxImages),
                    activeIndex: 0,
                    lightboxOpen: false,
                    get activeImage() { return this.images[this.activeIndex] ?? @js($mainImage) },
                    next() { this.activeIndex = (this.activeIndex + 1) % this.images.length },
                    prev() { this.activeIndex = (this.activeIndex - 1 + this.images.length) % this.images.length }
                }" @endif
            >
                <div class="product-gallery-wrap">
                    @if ($thumbItems->count() > 1)
                        <div class="product-gallery-rail">
                            @foreach ($thumbItems as $index => $item)
                                <button
                                    type="button"
                                    @click="activeIndex = {{ $index }}"
                                    :class="activeIndex === {{ $index }} ? 'product-gallery-thumb-active' : 'border-gray-200'"
                                    class="product-gallery-thumb"
                                >
                                    <img src="{{ $item['thumb'] }}" alt="{{ $item['alt'] }}" loading="lazy" class="h-full w-full object-cover">
                                </button>
                            @endforeach
                        </div>
                    @endif

                    <div class="min-w-0 flex-1">
                        <button type="button" @click="lightboxOpen = true" class="product-gallery-main block w-full">
                            <img
                                :src="activeImage"
                                src="{{ $mainImage }}"
                                alt="{{ $productName }}"
                                loading="eager"
                                fetchpriority="high"
                                decoding="async"
                                width="800"
                                height="800"
                                class="h-full w-full object-contain p-8"
                            >
                            @if ($discountPercent)
                                <span class="product-gallery-badge-sale">-{{ $discountPercent }}% Off</span>
                            @endif
                            @if ($thumbItems->count() > 1)
                                <span class="product-gallery-counter" x-text="(activeIndex + 1) + ' / ' + images.length"></span>
                            @endif
                            <span class="product-gallery-zoom-btn" aria-label="Zoom image">
                                <x-icons.search class="h-4 w-4" />
                            </span>
                        </button>

                        @if ($thumbItems->count() > 1)
                            <div class="product-gallery-thumb-row">
                                @foreach ($thumbItems as $index => $item)
                                    <button
                                        type="button"
                                        @click="activeIndex = {{ $index }}"
                                        :class="activeIndex === {{ $index }} ? 'product-gallery-thumb-active' : 'border-gray-200'"
                                        class="product-gallery-thumb"
                                    >
                                        <img src="{{ $item['thumb'] }}" alt="{{ $item['alt'] }}" loading="lazy" class="h-full w-full object-cover">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                @if ($product)
                    <div x-show="lightboxOpen" x-cloak x-transition.opacity @keydown.escape.window="lightboxOpen = false" class="product-lightbox-overlay" role="dialog" aria-modal="true" aria-label="Product image">
                        <button type="button" @click="lightboxOpen = false" class="product-lightbox-close" aria-label="Close">
                            <x-icons.x class="h-5 w-5" />
                        </button>
                        <template x-if="images.length > 1">
                            <button type="button" @click="prev()" class="product-lightbox-nav-btn left-2 sm:left-6" aria-label="Previous image">
                                <x-icons.arrow-left class="h-5 w-5" />
                            </button>
                        </template>
                        <img :src="activeImage" alt="{{ $productName }}" class="max-h-full max-w-full object-contain">
                        <template x-if="images.length > 1">
                            <button type="button" @click="next()" class="product-lightbox-nav-btn right-2 sm:right-6" aria-label="Next image">
                                <x-icons.arrow-right class="h-5 w-5" />
                            </button>
                        </template>
                        <template x-if="images.length > 1">
                            <p class="product-lightbox-counter" x-text="(activeIndex + 1) + ' / ' + images.length"></p>
                        </template>
                    </div>
                @endif
            </div>

            {{-- Info --}}
            <div @if($product) x-data="{
                variants: @js($variantData),
                selectedSize: null,
                selectedColor: null,
                quantity: 1,
                get variantId() {
                    const match = this.variants.find(v =>
                        String(v.size_id ?? '') === String(this.selectedSize ?? '') &&
                        String(v.color_id ?? '') === String(this.selectedColor ?? '')
                    );
                    return match ? match.id : null;
                },
                canAdd() {
                    if (!this.variants.length) return true;
                    return this.variantId !== null;
                }
            }" @endif>
                @if ($product?->league || $product?->team || $product?->category)
                    <div class="flex flex-wrap items-center gap-2">
                        @if ($product->league)
                            <span class="product-meta-badge"><x-icons.globe class="h-3 w-3" />{{ $product->league->name }}</span>
                        @endif
                        @if ($product->team)
                            <span class="product-meta-badge"><x-icons.shield class="h-3 w-3" />{{ $product->team->name }}</span>
                        @endif
                        @if (! $product->league && ! $product->team && $product->category)
                            <span class="product-meta-badge"><x-icons.tag class="h-3 w-3" />{{ $product->category->name }}</span>
                        @endif
                    </div>
                @endif

                <h1 class="product-title mt-3">{{ $productName }}</h1>

                <div class="product-price-row">
                    @if ($product?->sale_price)
                        <p class="product-price-main">{{ $product->formatted_price }}</p>
                        <p class="product-price-old">${{ number_format((float) $product->price, 2) }}</p>
                    @else
                        <p class="product-price-main">{{ $product?->formatted_price ?? '$94.99' }}</p>
                    @endif
                </div>
                <p class="product-sku-line">SKU: <span class="font-mono">{{ $product?->sku ?? 'MOF-'.strtoupper(substr($slug, 0, 8)) }}</span></p>

                @if ($product)
                    @php
                        $stockStatus = $product->stock_status->value ?? $product->stock_status;
                        $stockLabel = match ($stockStatus) {
                            'in_stock' => 'In Stock',
                            'out_of_stock' => 'Out of Stock',
                            'limited_stock' => 'Limited Stock',
                            default => ucfirst(str_replace('_', ' ', $stockStatus)),
                        };
                        $stockClass = match ($stockStatus) {
                            'in_stock' => 'bg-green-100 text-green-800',
                            'out_of_stock' => 'bg-red-100 text-red-800',
                            'limited_stock' => 'bg-amber-100 text-amber-800',
                            default => 'bg-gray-100 text-gray-800',
                        };
                    @endphp
                    <div class="mt-4 flex flex-wrap items-center gap-3">
                        <span class="icon-label rounded-full px-3 py-1 text-xs font-semibold {{ $stockClass }}">
                            @if ($stockStatus === 'in_stock')
                                <x-icons.check-circle class="h-3.5 w-3.5" />
                            @elseif ($stockStatus === 'out_of_stock')
                                <x-icons.x class="h-3.5 w-3.5" />
                            @else
                                <x-icons.clock class="h-3.5 w-3.5" />
                            @endif
                            {{ $stockLabel }}
                        </span>
                        @if ($product->is_customizable)
                            <span class="icon-label rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800">
                                <x-icons.pencil-square class="h-3.5 w-3.5" />
                                Customize Available
                            </span>
                        @endif
                    </div>
                    @if ($product->is_customizable)
                        <p class="icon-label mt-3 rounded-md border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-800">
                            <x-icons.pencil-square class="h-4 w-4 shrink-0" />
                            This product can be customized with name and number. Add your details in the order notes at checkout.
                        </p>
                    @endif

                    <form method="POST" action="{{ route('cart.add') }}" class="product-buybox">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="variant_id" :value="variantId">

                        @if ($sizes->isNotEmpty())
                            <div class="product-buybox-section">
                                <div class="product-buybox-label">
                                    Select Size
                                    <span class="icon-label text-xs font-normal text-gray-400">
                                        <x-icons.ruler class="h-3.5 w-3.5" />
                                        Size Guide
                                    </span>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($sizes as $size)
                                        <button type="button" @click="selectedSize = {{ $size->id }}"
                                            :class="selectedSize === {{ $size->id }} ? 'product-size-btn-active' : 'product-size-btn-inactive'"
                                            class="product-size-btn">{{ $size->name }}</button>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($colors->isNotEmpty())
                            <div class="product-buybox-section">
                                <div class="product-buybox-label">Select Color</div>
                                <div class="flex gap-3">
                                    @foreach ($colors as $color)
                                        <button type="button" @click="selectedColor = {{ $color->id }}"
                                            :class="selectedColor === {{ $color->id }} ? 'product-color-swatch-active' : ''"
                                            class="product-color-swatch"
                                            style="background-color: {{ $color->hex_code ?? '#ccc' }}"
                                            aria-label="{{ $color->name }}"></button>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="product-buybox-section">
                            <div class="product-buybox-label">Quantity</div>
                            <div class="product-qty-control">
                                <button type="button" @click="quantity = Math.max(1, quantity - 1)" class="product-qty-btn" aria-label="Decrease quantity">
                                    <x-icons.minus class="h-4 w-4" />
                                </button>
                                <input type="number" name="quantity" x-model="quantity" min="1" max="99" class="w-14 border-x border-gray-300 py-2 text-center text-sm font-semibold">
                                <button type="button" @click="quantity = Math.min(99, quantity + 1)" class="product-qty-btn" aria-label="Increase quantity">
                                    <x-icons.plus class="h-4 w-4" />
                                </button>
                            </div>
                            <p x-show="variants.length && !canAdd()" class="mt-4 text-sm text-brand-red">Please select size and color.</p>

                            <div class="mt-6 space-y-3">
                                <button type="submit" class="product-add-to-cart-btn" :disabled="!canAdd()">
                                    <x-icons.cart class="h-5 w-5" />
                                    Add to Cart
                                </button>
                                @if ($whatsappInquiryUrl)
                                    <a href="{{ $whatsappInquiryUrl }}" target="_blank" rel="noopener" class="product-whatsapp-btn">
                                        <x-icons.whatsapp class="h-5 w-5" />
                                        WhatsApp Inquiry
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                @else
                    <div class="mt-8">
                        <p class="text-sm text-gray-500">This product is not available yet.</p>
                        <a href="{{ route('shop') }}" class="btn-storefront-primary mt-4 inline-flex">Browse Shop</a>
                    </div>
                @endif
            </div>
        </div>

        @if ($product)
            <section class="product-feature-grid" aria-label="Why shop with us">
                <div class="product-feature-card">
                    <span class="product-feature-icon"><x-icons.truck class="h-5 w-5" /></span>
                    <div>
                        <p class="product-feature-title">Fast Delivery</p>
                        <p class="product-feature-subtitle">Nationwide shipping on every order</p>
                    </div>
                </div>
                <div class="product-feature-card">
                    <span class="product-feature-icon"><x-icons.shield class="h-5 w-5" /></span>
                    <div>
                        <p class="product-feature-title">Premium Quality</p>
                        <p class="product-feature-subtitle">Authentic-style fabric and printing</p>
                    </div>
                </div>
                <div class="product-feature-card">
                    <span class="product-feature-icon text-green-600 bg-green-50"><x-icons.whatsapp class="h-5 w-5" /></span>
                    <div>
                        <p class="product-feature-title">WhatsApp Support</p>
                        <p class="product-feature-subtitle">Order help and updates anytime</p>
                    </div>
                </div>
            </section>

            <div class="product-tabs-card" x-data="{ tab: 'description' }">
                <div class="flex flex-wrap gap-2">
                    <button type="button" @click="tab = 'description'" :class="tab === 'description' ? 'product-tab-btn-active' : 'product-tab-btn-inactive'" class="product-tab-btn">Description</button>
                    <button type="button" @click="tab = 'details'" :class="tab === 'details' ? 'product-tab-btn-active' : 'product-tab-btn-inactive'" class="product-tab-btn">Details</button>
                </div>

                <div x-show="tab === 'description'" class="prose prose-sm mt-5 max-w-none text-gray-600">
                    @if ($product->description)
                        <p>{{ $product->short_description }}</p>
                        <p class="mt-4">{{ $product->description }}</p>
                    @else
                        <p>Premium quality replica jersey featuring official-style club branding, breathable fabric, and a comfortable athletic fit. Perfect for match days, training, or everyday wear.</p>
                        <ul class="mt-4 list-disc pl-5">
                            <li>100% polyester performance fabric</li>
                            <li>Official-style club crest and sponsor print</li>
                            <li>Available in all standard sizes</li>
                            <li>Customization available (name &amp; number)</li>
                        </ul>
                    @endif
                </div>

                <div x-show="tab === 'details'" x-cloak class="mt-5">
                    <dl>
                        @if ($product->sku)
                            <div class="product-spec-row"><dt class="text-gray-500">SKU</dt><dd class="font-medium text-gray-900">{{ $product->sku }}</dd></div>
                        @endif
                        @if ($product->productType)
                            <div class="product-spec-row"><dt class="text-gray-500">Type</dt><dd class="font-medium text-gray-900">{{ $product->productType->name }}</dd></div>
                        @endif
                        @if ($product->category)
                            <div class="product-spec-row"><dt class="text-gray-500">Category</dt><dd class="font-medium text-gray-900">{{ $product->category->name }}</dd></div>
                        @endif
                        @if ($product->team)
                            <div class="product-spec-row"><dt class="text-gray-500">Team</dt><dd class="font-medium text-gray-900">{{ $product->team->name }}</dd></div>
                        @endif
                        @if ($product->league)
                            <div class="product-spec-row"><dt class="text-gray-500">League</dt><dd class="font-medium text-gray-900">{{ $product->league->name }}</dd></div>
                        @endif
                        <div class="product-spec-row"><dt class="text-gray-500">Customization</dt><dd class="font-medium text-gray-900">{{ $product->is_customizable ? 'Available' : 'Not available' }}</dd></div>
                    </dl>
                </div>
            </div>
        @endif

        @if (isset($relatedProducts) && $relatedProducts->isNotEmpty())
            <section class="related-products-section" aria-labelledby="related-heading">
                <h2 id="related-heading" class="section-title">You Might Also Like</h2>
                <p class="related-products-subtitle">Complete the look with these picks</p>
                <div class="home-product-grid mt-8">
                    @foreach ($relatedProducts as $related)
                        <x-product-card :product="$related" />
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection

@if ($product)
    @push('head')
        <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'image' => [$mainImage],
            'description' => strip_tags($product->short_description ?? $product->description ?? $productName),
            'sku' => $product->sku,
            'offers' => [
                '@type' => 'Offer',
                'price' => (float) $product->display_price,
                'priceCurrency' => 'USD',
                'availability' => $product->stock_status->value === 'out_of_stock'
                    ? 'https://schema.org/OutOfStock'
                    : 'https://schema.org/InStock',
                'url' => route('product.show', $product->slug),
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
        </script>
    @endpush
@endif
