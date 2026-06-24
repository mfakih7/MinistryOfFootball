@props([
    'products' => null,
    'filters' => [],
    'filterOptions' => [],
    'activeFilterCount' => 0,
])

@php
    $categories = $filterOptions['categories'] ?? collect();
    $leagues = $filterOptions['leagues'] ?? collect();

    $selectedCategory = !empty($filters['category']) ? $categories->firstWhere('slug', $filters['category']) : null;
    $selectedLeague = !empty($filters['league']) ? $leagues->firstWhere('slug', $filters['league']) : null;

    $categoryLabel = $selectedCategory?->name ?? 'All Categories';
    $categorySublabel = $selectedCategory ? 'Selected category' : $categories->count().' available';

    $leagueLabel = $selectedLeague?->name ?? 'All Leagues';
    $leagueSublabel = $selectedLeague ? 'Selected league' : $leagues->count().' available';

    $sortOptions = [
        'newest' => 'Newest',
        'price_low_high' => 'Price: Low to High',
        'price_high_low' => 'Price: High to Low',
        'best_selling' => 'Best Selling',
        'name' => 'Name A–Z',
    ];
    $currentSort = $filters['sort'] ?? 'newest';
@endphp

{{-- Desktop premium toolbar --}}
<div class="hidden lg:block">
    <div class="shop-toolbar">
        <div class="shop-toolbar-segments">
            <button type="button" @click="filtersOpen = true" class="shop-toolbar-segment shop-toolbar-segment--action text-left">
                <span class="shop-toolbar-icon"><x-icons.sliders class="h-5 w-5" /></span>
                <span class="shop-toolbar-text">
                    <span class="shop-toolbar-label">
                        Filter &amp; Refine
                        @if ($activeFilterCount > 0)
                            <span class="shop-toolbar-count">({{ $activeFilterCount }})</span>
                        @endif
                    </span>
                    <span class="shop-toolbar-sublabel">Narrow your results</span>
                </span>
            </button>

            <div class="shop-toolbar-segment">
                <span class="shop-toolbar-icon"><x-icons.shopping-bag class="h-5 w-5" /></span>
                <span class="shop-toolbar-text">
                    <span class="shop-toolbar-label"><span class="shop-toolbar-count">{{ $products->total() }}</span> Products</span>
                    <span class="shop-toolbar-sublabel">In this collection</span>
                </span>
            </div>

            <div class="shop-toolbar-segment">
                <span class="shop-toolbar-icon"><x-icons.layers class="h-5 w-5" /></span>
                <span class="shop-toolbar-text">
                    <span class="shop-toolbar-label">{{ $categoryLabel }}</span>
                    <span class="shop-toolbar-sublabel">{{ $categorySublabel }}</span>
                </span>
            </div>

            <div class="shop-toolbar-segment">
                <span class="shop-toolbar-icon"><x-icons.globe class="h-5 w-5" /></span>
                <span class="shop-toolbar-text">
                    <span class="shop-toolbar-label">{{ $leagueLabel }}</span>
                    <span class="shop-toolbar-sublabel">{{ $leagueSublabel }}</span>
                </span>
            </div>

            <div class="shop-toolbar-segment">
                <form method="GET" class="flex w-full items-center gap-3">
                    @foreach (collect($filters)->except('sort', 'page') as $key => $value)
                        @if ($value !== null && $value !== '')
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach
                    <span class="shop-toolbar-icon shrink-0"><x-icons.sliders class="h-5 w-5" /></span>
                    <span class="w-full">
                        <label for="sort-desktop" class="shop-toolbar-sublabel mb-1 block">Sort by</label>
                        <span class="shop-toolbar-sort-wrap block">
                            <select id="sort-desktop" name="sort" onchange="this.form.submit()" class="shop-toolbar-sort-select w-full">
                                @foreach ($sortOptions as $value => $label)
                                    <option value="{{ $value }}" @selected($currentSort === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <x-icons.arrow-up class="shop-toolbar-sort-chevron" />
                        </span>
                    </span>
                </form>
            </div>
        </div>

        <div class="shop-toolbar-search-row">
            <form method="GET" action="{{ route('search') }}" class="shop-toolbar-search-form">
                <x-icons.search class="shop-toolbar-search-icon" />
                <input
                    type="search"
                    name="q"
                    value="{{ $filters['q'] ?? '' }}"
                    placeholder="Search products, teams, or leagues..."
                    class="shop-toolbar-search-input"
                >
            </form>
            <p class="shop-toolbar-found"><span class="font-semibold text-gray-900">{{ $products->total() }}</span> products found</p>
        </div>
    </div>
</div>

{{-- Mobile compact toolbar --}}
<div class="mb-6 lg:hidden">
    <div class="shop-toolbar-mobile-row">
        <button type="button" @click="filtersOpen = true" class="shop-toolbar-pill">
            <span class="shop-toolbar-pill-icon"><x-icons.sliders class="h-4 w-4" /></span>
            <span>
                <span class="shop-toolbar-pill-label">
                    Filter
                    @if ($activeFilterCount > 0)
                        <span class="shop-toolbar-count">({{ $activeFilterCount }})</span>
                    @endif
                </span>
            </span>
        </button>

        <div class="shop-toolbar-pill">
            <span class="shop-toolbar-pill-icon"><x-icons.shopping-bag class="h-4 w-4" /></span>
            <span>
                <span class="shop-toolbar-pill-label"><span class="shop-toolbar-count">{{ $products->total() }}</span> Products</span>
            </span>
        </div>

        <div class="shop-toolbar-pill">
            <span class="shop-toolbar-pill-icon"><x-icons.layers class="h-4 w-4" /></span>
            <span>
                <span class="shop-toolbar-pill-label">Categories</span>
                <span class="shop-toolbar-pill-value block">{{ $categoryLabel }}</span>
            </span>
        </div>

        <div class="shop-toolbar-pill">
            <span class="shop-toolbar-pill-icon"><x-icons.globe class="h-4 w-4" /></span>
            <span>
                <span class="shop-toolbar-pill-label">Leagues</span>
                <span class="shop-toolbar-pill-value block">{{ $leagueLabel }}</span>
            </span>
        </div>

        <form method="GET" class="shop-toolbar-pill">
            @foreach (collect($filters)->except('sort', 'page') as $key => $value)
                @if ($value !== null && $value !== '')
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endif
            @endforeach
            <span class="shop-toolbar-pill-icon"><x-icons.sliders class="h-4 w-4" /></span>
            <label for="sort-mobile" class="sr-only">Sort products</label>
            <select id="sort-mobile" name="sort" onchange="this.form.submit()" class="shop-toolbar-pill-select">
                @foreach ($sortOptions as $value => $label)
                    <option value="{{ $value }}" @selected($currentSort === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <form method="GET" action="{{ route('search') }}" class="shop-toolbar-search-form mt-3">
        <x-icons.search class="shop-toolbar-search-icon" />
        <input
            type="search"
            name="q"
            value="{{ $filters['q'] ?? '' }}"
            placeholder="Search products, teams, or leagues..."
            class="shop-toolbar-search-input"
        >
    </form>
    <p class="mt-2 text-xs text-gray-500"><span class="font-semibold text-gray-900">{{ $products->total() }}</span> products found</p>
</div>
