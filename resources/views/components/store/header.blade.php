@php
    use App\Support\StoreNavigation;
    use App\Services\CartService;

    $storeName = $storeSettings['store_name'] ?? 'Ministry Of Football';
    $freeShipping = $storeSettings['free_shipping_threshold'] ?? null;
    $currencySymbol = $storeSettings['currency_symbol'] ?? '$';
    $cartCount = $cartCount ?? app(CartService::class)->count();

    $navItems = [
        ['key' => 'home', 'label' => 'Home', 'href' => route('home')],
        ['key' => 'shop', 'label' => 'Shop', 'href' => route('shop')],
        ['key' => 'clubs', 'label' => 'Clubs', 'href' => route('shop', ['category' => 'football-jerseys'])],
        ['key' => 'national-teams', 'label' => 'National Teams', 'href' => route('shop', ['league' => 'national-teams'])],
        ['key' => 'nba', 'label' => 'NBA', 'href' => route('shop', ['league' => 'nba'])],
        ['key' => 'accessories', 'label' => 'Accessories', 'href' => route('shop', ['category' => 'accessories'])],
        ['key' => 'new-arrivals', 'label' => 'New Arrivals', 'href' => route('shop', ['sort' => 'newest'])],
        ['key' => 'sale', 'label' => 'Sale', 'href' => route('shop', ['sale' => 1])],
        ['key' => 'contact', 'label' => 'Contact', 'href' => route('contact')],
    ];
@endphp

<div class="store-header" x-data="{ mobileOpen: false, searchOpen: false }" @keydown.escape.window="mobileOpen = false; searchOpen = false">
    {{-- Top announcement bar --}}
    <div class="store-topbar">
        <div class="container-store flex h-9 items-center justify-between gap-4 text-xs">
            <p class="flex items-center gap-2 truncate text-gray-300">
                <x-icons.football class="hidden h-3.5 w-3.5 shrink-0 text-brand-red sm:block" />
                @if ($freeShipping)
                    <span>Free delivery on orders over {{ $currencySymbol }}{{ number_format((float) $freeShipping, 0) }}</span>
                @else
                    <span>Free delivery on selected orders</span>
                @endif
            </p>
            <div class="flex shrink-0 items-center gap-4 font-medium text-gray-200">
                @if ($whatsappFloatUrl ?? null)
                    <a href="{{ $whatsappFloatUrl }}" target="_blank" rel="noopener" class="store-topbar-link inline-flex items-center gap-1.5 hover:text-white">
                        <x-icons.whatsapp class="h-3.5 w-3.5" />
                        <span class="hidden sm:inline">WhatsApp Support</span>
                        <span class="sm:hidden">WhatsApp</span>
                    </a>
                @endif
                <a href="{{ route('track-order') }}" @class(['store-topbar-link icon-label hover:text-white', 'text-brand-red' => StoreNavigation::isActive('track-order')])>
                    <x-icons.package class="h-3.5 w-3.5" />
                    <span>Track Order</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Main header --}}
    <header class="store-header-main">
        <div class="container-store">
            <div class="flex h-16 items-center justify-between gap-4 lg:h-[4.5rem]">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="group flex min-w-0 shrink-0 items-center gap-2.5">
                    <x-store.logo-mark />
                </a>

                {{-- Desktop navigation --}}
                <nav class="hidden flex-1 items-center justify-center gap-1 xl:flex" aria-label="Main navigation">
                    @foreach ($navItems as $item)
                        <x-store.nav-link
                            :href="$item['href']"
                            :active="StoreNavigation::isActive($item['key'])"
                        >
                            {{ $item['label'] }}
                        </x-store.nav-link>
                    @endforeach
                </nav>

                {{-- Header actions --}}
                <div class="flex shrink-0 items-center gap-1 sm:gap-2">
                    <button
                        type="button"
                        @click="searchOpen = !searchOpen; mobileOpen = false"
                        :class="searchOpen ? 'bg-gray-100 text-brand-red' : 'text-gray-600 hover:bg-gray-100 hover:text-brand-black'"
                        class="store-icon-btn"
                        aria-label="Search"
                        :aria-expanded="searchOpen"
                    >
                        <x-icons.search />
                    </button>

                    <button type="button" class="store-icon-btn hidden text-gray-600 hover:bg-gray-100 hover:text-brand-black sm:inline-flex" aria-label="Wishlist (coming soon)" disabled>
                        <x-icons.heart />
                    </button>

                    <a
                        href="{{ route('cart') }}"
                        @class(['store-icon-btn relative', 'bg-gray-100 text-brand-red' => StoreNavigation::isActive('cart')])
                        aria-label="Cart"
                    >
                        <x-icons.cart />
                        @if ($cartCount > 0)
                            <span class="store-cart-badge">
                                {{ $cartCount > 99 ? '99+' : $cartCount }}
                            </span>
                        @endif
                    </a>

                    <button
                        type="button"
                        @click="mobileOpen = !mobileOpen; searchOpen = false"
                        class="store-icon-btn text-gray-600 hover:bg-gray-100 xl:hidden"
                        aria-label="Toggle menu"
                        :aria-expanded="mobileOpen"
                    >
                        <x-icons.menu x-show="!mobileOpen" class="h-6 w-6" />
                        <x-icons.x x-show="mobileOpen" x-cloak class="h-6 w-6" />
                    </button>
                </div>
            </div>

            {{-- Search panel --}}
            <div
                x-show="searchOpen"
                x-cloak
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-1"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="border-t border-gray-100 py-4"
            >
                <form method="GET" action="{{ route('search') }}" class="flex gap-3">
                    <div class="relative flex-1">
                        <x-icons.search class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" />
                        <input
                            type="search"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="Search jerseys, teams, leagues..."
                            class="w-full rounded-lg border border-gray-300 py-2.5 pl-10 pr-4 text-sm focus:border-brand-red focus:outline-none focus:ring-2 focus:ring-brand-red/20"
                            x-ref="searchInput"
                            x-init="$watch('searchOpen', value => value && $nextTick(() => $refs.searchInput.focus()))"
                        >
                    </div>
                    <button type="submit" class="btn-primary icon-label shrink-0 px-6">
                        <x-icons.search class="h-4 w-4" />
                        Search
                    </button>
                </form>
            </div>

            {{-- Mobile navigation --}}
            <nav
                x-show="mobileOpen"
                x-cloak
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="border-t border-gray-100 py-4 xl:hidden"
                aria-label="Mobile navigation"
            >
                <div class="grid gap-1">
                    @foreach ($navItems as $item)
                        <x-store.nav-link
                            :href="$item['href']"
                            :active="StoreNavigation::isActive($item['key'])"
                            mobile
                        >
                            {{ $item['label'] }}
                        </x-store.nav-link>
                    @endforeach
                </div>
            </nav>
        </div>
    </header>
</div>
