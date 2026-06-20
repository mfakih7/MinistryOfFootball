@extends('layouts.app')

@section('content')
    <div class="container-store py-8 lg:py-12" x-data="{ filtersOpen: false }">
        <header class="mb-8">
            <h1 class="section-title">{{ $title ?? 'Shop' }}</h1>
            <p class="mt-2 text-gray-600">Browse our collection of football jerseys, NBA shirts, and accessories.</p>
        </header>

        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-3">
                <button type="button" @click="filtersOpen = true" class="inline-flex items-center gap-2 rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 lg:hidden">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18M3 12h18M3 20h18"/></svg>
                    Filters
                </button>
                <p class="text-sm text-gray-600"><span class="font-semibold">{{ $products->total() }}</span> products</p>
            </div>
            <form method="GET" class="flex items-center gap-2">
                @foreach (collect($filters)->except('sort', 'page') as $key => $value)
                    @if ($value !== null && $value !== '')
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach
                <label for="sort" class="text-sm text-gray-600">Sort:</label>
                <select id="sort" name="sort" onchange="this.form.submit()" class="rounded-md border border-gray-300 bg-white px-3 py-2 text-sm">
                    <option value="newest" @selected(($filters['sort'] ?? 'newest') === 'newest')>Newest</option>
                    <option value="price_low_high" @selected(($filters['sort'] ?? '') === 'price_low_high')>Price: Low to High</option>
                    <option value="price_high_low" @selected(($filters['sort'] ?? '') === 'price_high_low')>Price: High to Low</option>
                    <option value="best_selling" @selected(($filters['sort'] ?? '') === 'best_selling')>Best Selling</option>
                    <option value="name" @selected(($filters['sort'] ?? '') === 'name')>Name A–Z</option>
                </select>
            </form>
        </div>

        @if ($activeFilters)
            <div class="mb-6 flex flex-wrap items-center gap-2">
                @foreach ($activeFilters as $filter)
                    @php
                        $clearParams = collect($filters)->except($filter['key'], 'page')->filter(fn ($v) => $v !== null && $v !== '')->all();
                    @endphp
                    <a href="{{ route('shop', $clearParams) }}" class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700 hover:bg-gray-200">
                        {{ $filter['label'] }} &times;
                    </a>
                @endforeach
                <a href="{{ route('shop') }}" class="text-xs font-semibold text-brand-red hover:underline">Clear all</a>
            </div>
        @endif

        <div class="flex flex-col gap-8 lg:flex-row">
            {{-- Mobile drawer backdrop --}}
            <div x-show="filtersOpen" x-cloak @click="filtersOpen = false" class="fixed inset-0 z-40 bg-black/50 lg:hidden"></div>

            <aside :class="filtersOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-80 overflow-y-auto border-r border-gray-200 bg-white p-6 transition-transform duration-200 lg:static lg:z-auto lg:w-72 lg:translate-x-0 lg:rounded-xl lg:border lg:p-6 lg:shadow-sm" aria-label="Product filters">
                <div class="mb-4 flex items-center justify-between lg:hidden">
                    <h2 class="text-sm font-bold uppercase tracking-wide text-gray-900">Filters</h2>
                    <button type="button" @click="filtersOpen = false" class="text-gray-500">&times;</button>
                </div>

                <form method="GET" action="{{ route('shop') }}" class="space-y-6">
                    @if (! empty($filters['sort']))
                        <input type="hidden" name="sort" value="{{ $filters['sort'] }}">
                    @endif

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700">Category</label>
                        <select name="category" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                            <option value="">All</option>
                            @foreach ($filterOptions['categories'] as $category)
                                <option value="{{ $category->slug }}" @selected(($filters['category'] ?? '') === $category->slug)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700">League</label>
                        <select name="league" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                            <option value="">All</option>
                            @foreach ($filterOptions['leagues'] as $league)
                                <option value="{{ $league->slug }}" @selected(($filters['league'] ?? '') === $league->slug)>{{ $league->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700">Team</label>
                        <select name="team" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                            <option value="">All</option>
                            @foreach ($filterOptions['teams'] as $team)
                                <option value="{{ $team->slug }}" @selected(($filters['team'] ?? '') === $team->slug)>{{ $team->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700">Product Type</label>
                        <select name="product_type" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                            <option value="">All</option>
                            @foreach ($filterOptions['productTypes'] as $type)
                                <option value="{{ $type->slug }}" @selected(($filters['product_type'] ?? '') === $type->slug)>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700">Size</label>
                        <select name="size" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                            <option value="">All</option>
                            @foreach ($filterOptions['sizes'] as $size)
                                <option value="{{ $size->id }}" @selected(($filters['size'] ?? '') == $size->id)>{{ $size->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700">Color</label>
                        <select name="color" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                            <option value="">All</option>
                            @foreach ($filterOptions['colors'] as $color)
                                <option value="{{ $color->id }}" @selected(($filters['color'] ?? '') == $color->id)>{{ $color->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700">Min Price</label>
                            <input type="number" name="price_min" value="{{ $filters['price_min'] ?? '' }}" min="0" step="0.01" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700">Max Price</label>
                            <input type="number" name="price_max" value="{{ $filters['price_max'] ?? '' }}" min="0" step="0.01" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                        </div>
                    </div>

                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="sale" value="1" @checked(! empty($filters['sale'])) class="rounded border-gray-300 text-brand-red">
                        On sale only
                    </label>

                    <div class="flex gap-2">
                        <button type="submit" class="btn-primary flex-1">Apply Filters</button>
                        <a href="{{ route('shop') }}" class="btn-secondary flex-1 text-center">Clear</a>
                    </div>
                </form>
            </aside>

            <div class="flex-1">
                @if ($products->isEmpty())
                    <div class="rounded-xl border border-gray-200 bg-white p-12 text-center">
                        <p class="text-lg text-gray-600">No products match your filters.</p>
                        <a href="{{ route('shop') }}" class="btn-primary mt-6 inline-flex">Clear Filters</a>
                    </div>
                @else
                    <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach ($products as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>
                    <nav class="mt-10">{{ $products->links() }}</nav>
                @endif
            </div>
        </div>
    </div>
@endsection
