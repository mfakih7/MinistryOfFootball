@props([
    'filters' => [],
    'filterOptions' => [],
])

<form method="GET" action="{{ route('shop') }}" class="shop-filters-form space-y-1">
    @if (! empty($filters['sort']))
        <input type="hidden" name="sort" value="{{ $filters['sort'] }}">
    @endif

    <div x-data="{ open: true }" class="shop-filter-section">
        <button type="button" @click="open = !open" class="shop-filter-section-toggle">
            <span>Category</span>
            <x-icons.arrow-up class="h-4 w-4 transition" ::class="open ? '' : 'rotate-180'" />
        </button>
        <div x-show="open" class="shop-filter-section-body">
            <label class="shop-filter-option">
                <input type="radio" name="category" value="" @checked(empty($filters['category']))>
                <span>All categories</span>
            </label>
            @foreach ($filterOptions['categories'] as $category)
                <label class="shop-filter-option">
                    <input type="radio" name="category" value="{{ $category->slug }}" @checked(($filters['category'] ?? '') === $category->slug)>
                    <span>{{ $category->name }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <div x-data="{ open: true }" class="shop-filter-section">
        <button type="button" @click="open = !open" class="shop-filter-section-toggle">
            <span>League</span>
            <x-icons.arrow-up class="h-4 w-4 transition" ::class="open ? '' : 'rotate-180'" />
        </button>
        <div x-show="open" class="shop-filter-section-body">
            <label class="shop-filter-option">
                <input type="radio" name="league" value="" @checked(empty($filters['league']))>
                <span>All leagues</span>
            </label>
            @foreach ($filterOptions['leagues'] as $league)
                <label class="shop-filter-option">
                    <input type="radio" name="league" value="{{ $league->slug }}" @checked(($filters['league'] ?? '') === $league->slug)>
                    <span>{{ $league->name }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <div x-data="{ open: false }" class="shop-filter-section">
        <button type="button" @click="open = !open" class="shop-filter-section-toggle">
            <span>Team</span>
            <x-icons.arrow-up class="h-4 w-4 transition" ::class="open ? '' : 'rotate-180'" />
        </button>
        <div x-show="open" class="shop-filter-section-body shop-filter-section-body--scroll">
            <label class="shop-filter-option">
                <input type="radio" name="team" value="" @checked(empty($filters['team']))>
                <span>All teams</span>
            </label>
            @foreach ($filterOptions['teams'] as $team)
                <label class="shop-filter-option">
                    <input type="radio" name="team" value="{{ $team->slug }}" @checked(($filters['team'] ?? '') === $team->slug)>
                    <span>{{ $team->name }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <div x-data="{ open: false }" class="shop-filter-section">
        <button type="button" @click="open = !open" class="shop-filter-section-toggle">
            <span>Price Range</span>
            <x-icons.arrow-up class="h-4 w-4 transition" ::class="open ? '' : 'rotate-180'" />
        </button>
        <div x-show="open" class="shop-filter-section-body">
            <div class="shop-filter-price-grid">
                <div>
                    <label class="shop-filter-label" for="price_min">Min</label>
                    <input type="number" id="price_min" name="price_min" value="{{ $filters['price_min'] ?? '' }}" min="0" step="1" placeholder="0" class="shop-filter-input">
                </div>
                <div>
                    <label class="shop-filter-label" for="price_max">Max</label>
                    <input type="number" id="price_max" name="price_max" value="{{ $filters['price_max'] ?? '' }}" min="0" step="1" placeholder="200" class="shop-filter-input">
                </div>
            </div>
        </div>
    </div>

    <div x-data="{ open: false }" class="shop-filter-section">
        <button type="button" @click="open = !open" class="shop-filter-section-toggle">
            <span>Size</span>
            <x-icons.arrow-up class="h-4 w-4 transition" ::class="open ? '' : 'rotate-180'" />
        </button>
        <div x-show="open" class="shop-filter-section-body">
            <div class="shop-filter-chips">
                <label class="shop-filter-chip">
                    <input type="radio" name="size" value="" @checked(empty($filters['size'])) class="sr-only">
                    <span>All</span>
                </label>
                @foreach ($filterOptions['sizes'] as $size)
                    <label class="shop-filter-chip">
                        <input type="radio" name="size" value="{{ $size->id }}" @checked(($filters['size'] ?? '') == $size->id) class="sr-only">
                        <span>{{ $size->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>
    </div>

    <div x-data="{ open: false }" class="shop-filter-section">
        <button type="button" @click="open = !open" class="shop-filter-section-toggle">
            <span>Color</span>
            <x-icons.arrow-up class="h-4 w-4 transition" ::class="open ? '' : 'rotate-180'" />
        </button>
        <div x-show="open" class="shop-filter-section-body">
            <div class="shop-filter-colors">
                <label class="shop-filter-color" title="All colors">
                    <input type="radio" name="color" value="" @checked(empty($filters['color'])) class="sr-only">
                    <span class="shop-filter-color-swatch shop-filter-color-swatch--all">All</span>
                </label>
                @foreach ($filterOptions['colors'] as $color)
                    <label class="shop-filter-color" title="{{ $color->name }}">
                        <input type="radio" name="color" value="{{ $color->id }}" @checked(($filters['color'] ?? '') == $color->id) class="sr-only">
                        <span
                            class="shop-filter-color-swatch"
                            style="background-color: {{ $color->hex_code ?? '#ccc' }}"
                        ></span>
                    </label>
                @endforeach
            </div>
        </div>
    </div>

    <div class="shop-filter-section">
        <label class="shop-filter-toggle">
            <span>Sale only</span>
            <span class="shop-filter-switch">
                <input type="checkbox" name="sale" value="1" @checked(! empty($filters['sale']))>
                <span class="shop-filter-switch-track" aria-hidden="true"></span>
            </span>
        </label>
    </div>

    <div class="shop-filter-actions">
        <button type="submit" class="btn-primary w-full min-h-[44px]">Apply Filters</button>
        <a href="{{ route('shop') }}" class="btn-secondary w-full min-h-[44px] text-center">Clear All</a>
    </div>
</form>
