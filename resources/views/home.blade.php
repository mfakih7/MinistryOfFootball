@extends('layouts.app')

@section('content')
    <x-home.hero
        :slides="$slides"
        :fallback-image="$heroFallbackImage ?? null"
        :hero-description="$heroDescription ?? null"
    />

    {{-- Categories --}}
    @if ($categories->isNotEmpty())
        <section class="py-16 lg:py-20" aria-labelledby="categories-heading">
            <div class="container-store">
                <h2 id="categories-heading" class="section-title mb-8 text-center">Shop by Category</h2>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($categories as $category)
                        <x-category-card :name="$category->name" :href="route('shop', ['category' => $category->slug])" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Leagues --}}
    @if ($leagues->isNotEmpty())
        <section class="border-t border-gray-100 py-16 lg:py-20" aria-labelledby="leagues-heading">
            <div class="container-store">
                <h2 id="leagues-heading" class="section-title mb-8 text-center">Shop by League</h2>
                <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-8">
                    @foreach ($leagues as $league)
                        <a href="{{ route('shop', ['league' => $league->slug]) }}" class="rounded-lg border border-gray-200 bg-white p-4 text-center text-sm font-semibold text-gray-900 transition hover:border-brand-red hover:text-brand-red">{{ $league->name }}</a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Best Sellers --}}
    <section class="bg-brand-gray py-16 lg:py-20" aria-labelledby="bestsellers-heading">
        <div class="container-store">
            <div class="mb-8 flex items-end justify-between">
                <h2 id="bestsellers-heading" class="section-title">Best Sellers</h2>
                <a href="{{ route('shop', ['sort' => 'best_selling']) }}" class="text-sm font-semibold text-brand-red hover:text-brand-red-dark">View All &rarr;</a>
            </div>
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @forelse ($bestSellers as $product)
                    <x-product-card :product="$product" />
                @empty
                    <p class="col-span-full text-center text-gray-500">No products yet.</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- New Arrivals --}}
    @if ($newArrivals->isNotEmpty())
        <section class="py-16 lg:py-20" aria-labelledby="new-arrivals-heading">
            <div class="container-store">
                <div class="mb-8 flex items-end justify-between">
                    <h2 id="new-arrivals-heading" class="section-title">New Arrivals</h2>
                    <a href="{{ route('shop', ['sort' => 'newest']) }}" class="text-sm font-semibold text-brand-red">View All &rarr;</a>
                </div>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($newArrivals as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Featured --}}
    @if ($featuredProducts->isNotEmpty())
        <section class="bg-brand-gray py-16 lg:py-20" aria-labelledby="featured-heading">
            <div class="container-store">
                <div class="mb-8 flex items-end justify-between">
                    <h2 id="featured-heading" class="section-title">Featured Products</h2>
                    <a href="{{ route('shop') }}" class="text-sm font-semibold text-brand-red">Shop All &rarr;</a>
                </div>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($featuredProducts as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Recently Viewed --}}
    @if ($recentlyViewed->isNotEmpty())
        <section class="border-t border-gray-100 py-16 lg:py-20" aria-labelledby="recently-viewed-heading">
            <div class="container-store">
                <div class="mb-8 flex items-end justify-between">
                    <h2 id="recently-viewed-heading" class="section-title">Recently Viewed</h2>
                </div>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($recentlyViewed as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Trust Badges --}}
    <section class="border-t border-gray-200 py-12" aria-labelledby="trust-heading">
        <div class="container-store">
            <h2 id="trust-heading" class="sr-only">Why shop with us</h2>
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-5">
                <x-trust-badge icon="shield" title="100% Original" description="Authentic-style quality jerseys" />
                <x-trust-badge icon="truck" title="Fast Delivery" description="Quick shipping nationwide" />
                <x-trust-badge icon="return" title="Easy Returns" description="Hassle-free return policy" />
                <x-trust-badge icon="lock" title="Secure Checkout" description="Safe &amp; simple ordering" />
                <x-trust-badge icon="whatsapp" title="WhatsApp Support" description="Order &amp; pay via WhatsApp" />
            </div>
        </div>
    </section>
@endsection
