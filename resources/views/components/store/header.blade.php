@php
    use App\Support\StoreNavigation;
    use App\Services\CartService;
    use App\Services\WhatsAppOrderService;

    $storeName = $storeSettings['store_name'] ?? 'Ministry Of Football';
    $cartCount = $cartCount ?? app(CartService::class)->count();
    $drawerWhatsappUrl = app(WhatsAppOrderService::class)->buildInquiryUrl('Hello, I have a question about your products.');

    $desktopNavItems = [
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

    $drawerNavItems = [
        ['key' => 'home', 'label' => 'Home', 'href' => route('home'), 'icon' => 'home'],
        ['key' => 'shop', 'label' => 'Shop', 'href' => route('shop'), 'icon' => 'shopping-bag'],
        ['key' => 'clubs', 'label' => 'Football Jerseys', 'href' => route('shop', ['category' => 'football-jerseys']), 'icon' => 'football'],
        ['key' => 'nba', 'label' => 'NBA', 'href' => route('shop', ['league' => 'nba']), 'icon' => 'basketball'],
        ['key' => 'accessories', 'label' => 'Accessories', 'href' => route('shop', ['category' => 'accessories']), 'icon' => 'package'],
        ['key' => 'new-arrivals', 'label' => 'New Arrivals', 'href' => route('shop', ['sort' => 'newest']), 'icon' => 'sparkles'],
        ['key' => 'sale', 'label' => 'Sale', 'href' => route('shop', ['sale' => 1]), 'icon' => 'flame'],
        ['key' => 'contact', 'label' => 'Contact', 'href' => route('contact'), 'icon' => 'phone'],
    ];
@endphp

<div
    class="store-header"
    x-data="{ mobileOpen: false, searchOpen: false }"
    x-effect="document.body.classList.toggle('overflow-hidden', mobileOpen || searchOpen)"
    @keydown.escape.window="mobileOpen = false; searchOpen = false"
>
    <div class="store-topbar hidden sm:block">
        <div class="container-store h-full">
            <ul class="store-topbar-list">
                <li class="store-topbar-item">
                    <x-icons.truck class="store-topbar-icon" />
                    <span>Free delivery on selected orders</span>
                </li>
                <li class="store-topbar-item">
                    <x-icons.whatsapp class="store-topbar-icon" />
                    <span>Order via WhatsApp</span>
                </li>
                <li class="store-topbar-item">
                    <x-icons.package class="store-topbar-icon" />
                    <span>New kits weekly</span>
                </li>
                <li class="store-topbar-item">
                    <x-icons.clock class="store-topbar-icon" />
                    <span>Support available</span>
                </li>
            </ul>
        </div>
    </div>

    <header class="store-header-main">
        <div class="container-store store-header-inner">
            <button
                type="button"
                @click="mobileOpen = true; searchOpen = false"
                class="store-icon-btn store-header-burger xl:hidden"
                aria-label="Open menu"
                ::aria-expanded="mobileOpen"
            >
                <x-icons.menu class="h-6 w-6" />
            </button>

            <a href="{{ route('home') }}" class="store-header-logo group">
                <x-store.logo-mark :show-text="false" />
                <span class="store-logo-wordmark">{{ strtoupper($storeName) }}</span>
            </a>

            <nav class="hidden flex-1 items-center justify-center gap-0.5 xl:flex" aria-label="Main navigation">
                @foreach ($desktopNavItems as $item)
                    <x-store.nav-link
                        :href="$item['href']"
                        :active="StoreNavigation::isActive($item['key'])"
                        :accent="$item['key'] === 'sale'"
                    >
                        {{ $item['label'] }}
                    </x-store.nav-link>
                @endforeach
            </nav>

            <div class="store-header-actions">
                <button
                    type="button"
                    @click="searchOpen = true; mobileOpen = false"
                    class="store-icon-btn"
                    aria-label="Search"
                    ::aria-expanded="searchOpen"
                >
                    <x-icons.search />
                </button>

                <a
                    href="{{ route('cart') }}"
                    @class(['store-icon-btn', 'bg-gray-100 text-brand-red' => StoreNavigation::isActive('cart')])
                    aria-label="Cart"
                >
                    <x-icons.cart />
                    @if ($cartCount > 0)
                        <span class="store-cart-badge">
                            {{ $cartCount > 99 ? '99+' : $cartCount }}
                        </span>
                    @endif
                </a>
            </div>
        </div>
    </header>

    {{-- Mobile drawer backdrop --}}
    <div
        x-show="mobileOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="mobileOpen = false"
        class="store-drawer-backdrop xl:hidden"
        aria-hidden="true"
    ></div>

    {{-- Mobile slide-out drawer --}}
    <nav
        x-show="mobileOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="store-drawer xl:hidden"
        aria-label="Mobile navigation"
    >
        <div class="store-drawer-header">
            <a href="{{ route('home') }}" class="group flex min-w-0 items-center gap-2.5" @click="mobileOpen = false">
                <x-store.logo-mark :show-text="true" />
            </a>
            <button type="button" @click="mobileOpen = false" class="store-icon-btn" aria-label="Close menu">
                <x-icons.x class="h-6 w-6" />
            </button>
        </div>
        <div class="store-drawer-nav">
            @foreach ($drawerNavItems as $item)
                <a
                    href="{{ $item['href'] }}"
                    @class([
                        'store-drawer-link',
                        'store-drawer-link-active' => StoreNavigation::isActive($item['key']),
                        'store-drawer-link-sale' => $item['key'] === 'sale' && ! StoreNavigation::isActive($item['key']),
                    ])
                    @if(StoreNavigation::isActive($item['key'])) aria-current="page" @endif
                >
                    <span class="store-drawer-link-icon">
                        <x-dynamic-component :component="'icons.'.$item['icon']" class="h-5 w-5" />
                    </span>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </div>
        <div class="store-drawer-footer">
            <a href="{{ $drawerWhatsappUrl }}" target="_blank" rel="noopener" class="store-drawer-whatsapp">
                <x-icons.whatsapp class="h-5 w-5 shrink-0" />
                Chat with us on WhatsApp
            </a>
        </div>
    </nav>

    {{-- Search overlay --}}
    <div
        x-show="searchOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="store-search-overlay"
        role="dialog"
        aria-modal="true"
        aria-label="Search"
    >
        <div class="store-search-overlay-inner">
            <div class="container-store">
                <div class="store-search-overlay-header">
                    <form method="GET" action="{{ route('search') }}" class="store-search-form">
                        <x-icons.search class="store-search-form-icon" />
                        <input
                            type="search"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="Search jerseys, teams, leagues..."
                            class="store-search-input"
                            x-ref="searchInput"
                            x-init="$watch('searchOpen', value => value && $nextTick(() => $refs.searchInput.focus()))"
                        >
                        <button type="submit" class="store-search-submit">Search</button>
                    </form>
                    <button type="button" @click="searchOpen = false" class="store-icon-btn" aria-label="Close search">
                        <x-icons.x class="h-6 w-6" />
                    </button>
                </div>
                <p class="store-search-hint">Try &ldquo;Arsenal&rdquo;, &ldquo;Premier League&rdquo;, or &ldquo;NBA&rdquo;</p>
            </div>
        </div>
    </div>
</div>
