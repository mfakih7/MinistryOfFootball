@extends('layouts.app')

@section('content')
    @php
        $activeFilterCount = count($activeFilters ?? []);
    @endphp

    <div
        x-data="{ filtersOpen: false }"
        x-effect="document.body.classList.toggle('overflow-hidden', filtersOpen && window.innerWidth < 1024)"
        @keydown.escape.window="filtersOpen = false"
    >
        <div class="container-store py-6 sm:py-8 lg:py-12">
            <header class="mb-6 sm:mb-8">
                <h1 class="section-title">{{ $title ?? 'Shop' }}</h1>
                <p class="mt-2 text-sm text-gray-600 sm:text-base">Browse our collection of football jerseys, NBA shirts, and accessories.</p>
            </header>

            <x-shop.toolbar :products="$products" :filters="$filters" :filter-options="$filterOptions" :active-filter-count="$activeFilterCount" />

            @if ($activeFilters)
                <div class="shop-active-filters">
                    @foreach ($activeFilters as $filter)
                        @php
                            $clearParams = collect($filters)->except($filter['key'], 'page')->filter(fn ($v) => $v !== null && $v !== '')->all();
                        @endphp
                        <a href="{{ route('shop', $clearParams) }}" class="shop-filter-chip-active">
                            {{ $filter['label'] }}
                            <x-icons.x class="h-3.5 w-3.5" />
                        </a>
                    @endforeach
                    <a href="{{ route('shop') }}" class="shop-filter-clear-all">Clear all</a>
                </div>
            @endif

            <div class="shop-layout">
                {{-- Desktop sidebar filters --}}
                <aside class="shop-filters-panel-desktop" aria-label="Product filters">
                    <x-shop.filters :filters="$filters" :filter-options="$filterOptions" />
                </aside>

                <div class="shop-results">
                    @if ($products->isEmpty())
                        <div class="shop-empty-state">
                            <p class="text-lg font-medium text-gray-700">No products match your filters.</p>
                            <a href="{{ route('shop') }}" class="btn-primary mt-6 inline-flex min-h-[44px]">Clear Filters</a>
                        </div>
                    @else
                        <div class="shop-product-grid">
                            @foreach ($products as $product)
                                <x-product-card :product="$product" />
                            @endforeach
                        </div>
                        <nav class="mt-10">{{ $products->links() }}</nav>
                    @endif
                </div>
            </div>
        </div>

        {{-- Mobile filter overlay (outside container-store to avoid clipping) --}}
        <div
            x-show="filtersOpen"
            x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="filtersOpen = false"
            class="fixed inset-0 z-40 bg-black/60 lg:hidden"
            aria-hidden="true"
        ></div>

        {{-- Mobile filter drawer (slides from left) --}}
        <aside
            class="shop-filters-drawer fixed inset-y-0 left-0 z-50 w-[86%] max-w-sm transform bg-white shadow-2xl transition-transform duration-300 lg:hidden"
            x-bind:class="filtersOpen ? 'translate-x-0' : '-translate-x-full'"
            role="dialog"
            aria-modal="true"
            aria-label="Product filters"
        >
            <div class="shop-filters-drawer-header">
                <h2 class="shop-filters-panel-title">Filters</h2>
                <button type="button" @click="filtersOpen = false" class="store-icon-btn" aria-label="Close filters">
                    <x-icons.x class="h-6 w-6" />
                </button>
            </div>
            <div class="shop-filters-drawer-body">
                <x-shop.filters :filters="$filters" :filter-options="$filterOptions" />
            </div>
        </aside>
    </div>
@endsection
