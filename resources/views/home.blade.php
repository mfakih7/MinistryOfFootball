@extends('layouts.app')

@section('content')
    {{-- Hero + Category Panel --}}
    <section class="home-top-section" aria-label="Featured hero and categories">
        <div class="container-store">
            <div class="home-top-grid">
                <div class="home-hero-wrap">
                    <x-home.hero
                        :slides="$slides"
                        :fallback-image="$heroFallbackImage ?? null"
                        :hero-description="$heroDescription ?? null"
                    />
                </div>

                @if ($categories->isNotEmpty())
                    <div class="home-categories-stack">
                        @foreach ($categories->take(4) as $category)
                            <x-home.hero-category-card
                                :name="$category->name"
                                :href="route('shop', ['category' => $category->slug])"
                                :image="$category->image"
                                :slug="$category->slug"
                            />
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- League Collection --}}
    @if ($leagues->isNotEmpty())
        <section class="league-collection-section" aria-labelledby="leagues-heading">
            <div class="container-store">
                <h2 id="leagues-heading" class="league-collection-title">Shop by League</h2>
                <div class="league-collection-grid">
                    @foreach ($leagues as $league)
                        <x-league-chip
                            :name="$league->name"
                            :href="route('shop', ['league' => $league->slug])"
                            :logo="$league->logo"
                        />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Featured Kits --}}
    @if ($featuredProducts->isNotEmpty())
        <section class="home-product-section" aria-labelledby="featured-kits-heading">
            <div class="container-store">
                <div class="home-product-section-header">
                    <div>
                        <h2 id="featured-kits-heading" class="home-product-section-title">Featured Kits</h2>
                        <p class="home-product-section-subtitle">Top football jerseys selected for true fans.</p>
                    </div>
                    <a href="{{ route('shop', ['featured' => 1]) }}" class="home-section-pill">
                        View Collection
                        <x-icons.arrow-up-right class="h-4 w-4" />
                    </a>
                </div>

                <div class="home-product-grid">
                    @foreach ($featuredProducts as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- New Arrivals --}}
    @if ($newArrivals->isNotEmpty())
        <section class="home-product-section home-product-section--muted" aria-labelledby="new-arrivals-heading">
            <div class="container-store">
                <div class="home-product-section-header">
                    <div>
                        <h2 id="new-arrivals-heading" class="home-product-section-title">New Arrivals</h2>
                        <p class="home-product-section-subtitle">Fresh kits just dropped this season.</p>
                    </div>
                    <a href="{{ route('shop', ['new_arrival' => 1]) }}" class="home-section-pill">
                        View Collection
                        <x-icons.arrow-up-right class="h-4 w-4" />
                    </a>
                </div>

                <div class="home-product-grid">
                    @foreach ($newArrivals as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
