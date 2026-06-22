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
    @endphp

    <div class="container-store py-8 lg:py-12">
        <nav class="mb-6 text-sm text-gray-500" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-brand-red">Home</a>
            <span class="mx-2">/</span>
            <a href="{{ route('shop') }}" class="hover:text-brand-red">Shop</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900">{{ $productName }}</span>
        </nav>

        <div class="grid gap-8 lg:grid-cols-2 lg:gap-16" @if($product) x-data="{ activeImage: @js($mainImage) }" @endif>
            <div>
                <div class="product-gallery-main">
                    <img
                        :src="activeImage"
                        src="{{ $mainImage }}"
                        alt="{{ $productName }}"
                        loading="eager"
                        class="h-full w-full object-contain p-4 sm:object-cover sm:p-0"
                    >
                </div>
                @if ($galleryImages->isNotEmpty())
                    <div class="product-gallery-thumbs">
                        @foreach ($galleryImages->take(6) as $image)
                            @php $thumbUrl = $image->display_url ?? $mainImage; @endphp
                            <button
                                type="button"
                                @click="activeImage = @js($thumbUrl)"
                                :class="activeImage === @js($thumbUrl) ? 'product-gallery-thumb-active' : 'border-gray-200'"
                                class="product-gallery-thumb"
                            >
                                <img src="{{ $thumbUrl }}" alt="{{ $image->alt_text ?? $productName }}" loading="lazy" class="h-full w-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

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
                <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">{{ $productName }}</h1>
                <div class="mt-2 flex flex-wrap items-center gap-3">
                    @if ($product?->sale_price)
                        <p class="text-2xl font-bold text-brand-red">{{ $product->formatted_price }}</p>
                        <p class="text-lg text-gray-400 line-through">${{ number_format((float) $product->price, 2) }}</p>
                        <span class="rounded bg-brand-red px-2 py-1 text-xs font-bold uppercase text-white">Sale</span>
                    @else
                        <p class="text-2xl font-bold text-brand-red">{{ $product?->formatted_price ?? '$94.99' }}</p>
                    @endif
                </div>
                <p class="mt-1 text-sm text-gray-500">SKU: {{ $product?->sku ?? 'MOF-'.strtoupper(substr($slug, 0, 8)) }}</p>

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
                @endif

                @if ($product)
                    <form method="POST" action="{{ route('cart.add') }}" class="mt-8">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="variant_id" :value="variantId">

                        @if ($sizes->isNotEmpty())
                            <div>
                                <h2 class="text-sm font-semibold text-gray-900">Size</h2>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    @foreach ($sizes as $size)
                                        <button type="button" @click="selectedSize = {{ $size->id }}"
                                            :class="selectedSize === {{ $size->id }} ? 'border-brand-black bg-brand-black text-white' : 'border-gray-300 text-gray-700 hover:border-brand-black'"
                                            class="min-h-[44px] min-w-[44px] rounded-lg border px-4 py-2.5 text-sm font-medium">{{ $size->name }}</button>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($colors->isNotEmpty())
                            <div class="mt-6">
                                <h2 class="text-sm font-semibold text-gray-900">Color</h2>
                                <div class="mt-3 flex gap-3">
                                    @foreach ($colors as $color)
                                        <button type="button" @click="selectedColor = {{ $color->id }}"
                                            :class="selectedColor === {{ $color->id }} ? 'ring-2 ring-brand-black ring-offset-2' : ''"
                                            class="h-11 w-11 rounded-full border-2 border-gray-300"
                                            style="background-color: {{ $color->hex_code ?? '#ccc' }}"
                                            aria-label="{{ $color->name }}"></button>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="mt-6">
                            <h2 class="text-sm font-semibold text-gray-900">Quantity</h2>
                            <div class="mt-3 inline-flex items-center rounded-md border border-gray-300">
                                <button type="button" @click="quantity = Math.max(1, quantity - 1)" class="inline-flex h-10 w-10 items-center justify-center text-gray-600 hover:bg-gray-50" aria-label="Decrease quantity">
                                    <x-icons.minus class="h-4 w-4" />
                                </button>
                                <input type="number" name="quantity" x-model="quantity" min="1" max="99" class="w-14 border-x border-gray-300 py-2 text-center text-sm">
                                <button type="button" @click="quantity = Math.min(99, quantity + 1)" class="inline-flex h-10 w-10 items-center justify-center text-gray-600 hover:bg-gray-50" aria-label="Increase quantity">
                                    <x-icons.plus class="h-4 w-4" />
                                </button>
                            </div>
                        </div>

                        <p x-show="variants.length && !canAdd()" class="mt-4 text-sm text-brand-red">Please select size and color.</p>

                        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                            <button type="submit" class="btn-primary icon-label flex-1 justify-center" :disabled="!canAdd()">
                                <x-icons.cart class="h-4 w-4" />
                                Add to Cart
                            </button>
                            @if ($whatsappInquiryUrl)
                                <a href="{{ $whatsappInquiryUrl }}" target="_blank" rel="noopener" class="icon-label inline-flex flex-1 items-center justify-center gap-2 rounded-md border-2 border-green-600 bg-green-600 px-6 py-3 text-sm font-semibold uppercase tracking-wide text-white transition hover:bg-green-700">
                                    <x-icons.whatsapp class="h-5 w-5" />
                                    WhatsApp Inquiry
                                </a>
                            @endif
                        </div>
                    </form>

                    <div class="mt-8 grid gap-3 border-t border-gray-200 pt-8 sm:grid-cols-3">
                        <div class="icon-label text-sm text-gray-600">
                            <x-icons.truck class="h-5 w-5 shrink-0 text-brand-red" />
                            Fast delivery nationwide
                        </div>
                        <div class="icon-label text-sm text-gray-600">
                            <x-icons.shield class="h-5 w-5 shrink-0 text-brand-red" />
                            Premium quality kits
                        </div>
                        <div class="icon-label text-sm text-gray-600">
                            <x-icons.whatsapp class="h-5 w-5 shrink-0 text-green-600" />
                            WhatsApp order support
                        </div>
                    </div>
                @else
                    <div class="mt-8">
                        <p class="text-sm text-gray-500">This product is not available yet.</p>
                        <a href="{{ route('shop') }}" class="btn-primary mt-4 inline-flex">Browse Shop</a>
                    </div>
                @endif

                <div class="mt-10 border-t border-gray-200 pt-8">
                    <h2 class="text-sm font-semibold text-gray-900">Description</h2>
                    <div class="prose prose-sm mt-4 max-w-none text-gray-600">
                        @if ($product?->description)
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
                </div>
            </div>
        </div>

        @if (isset($relatedProducts) && $relatedProducts->isNotEmpty())
            <section class="mt-16 border-t border-gray-200 pt-12" aria-labelledby="related-heading">
                <h2 id="related-heading" class="section-title mb-8">Related Products</h2>
                <div class="home-product-grid">
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
