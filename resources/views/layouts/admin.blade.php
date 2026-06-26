<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} | Admin | {{ config('app.name') }}</title>
    <meta name="robots" content="noindex, nofollow">
    @if (! empty($faviconUrl))
        <link rel="icon" href="{{ $faviconUrl }}">
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" referrerpolicy="no-referrer">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100 antialiased" x-data="{ sidebarOpen: false }">
    <x-global-loader />

    <div class="flex min-h-screen">
        <div
            x-show="sidebarOpen"
            x-cloak
            @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-black/50 lg:hidden"
        ></div>

        <aside
            class="admin-sidebar fixed inset-y-0 left-0 z-50 flex w-64 flex-col bg-brand-black text-gray-300 transition-transform duration-200 lg:static lg:translate-x-0"
            x-bind:class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        >
            <div class="admin-sidebar-brand">
                <a href="{{ route('admin.dashboard') }}" class="admin-sidebar-brand-link">
                    @if ($adminLogoUrl ?? null)
                        <img src="{{ $adminLogoUrl }}" alt="{{ $brandingStoreName ?? 'Admin' }}" class="admin-sidebar-brand-logo">
                        <span class="admin-sidebar-brand-name">{{ $brandingStoreName ?? config('app.name') }}</span>
                    @else
                        <span class="admin-sidebar-brand-fallback">
                            MOF <span class="text-brand-red">Admin</span>
                        </span>
                    @endif
                </a>
            </div>

            <nav class="admin-sidebar-nav" aria-label="Admin navigation">
                <p class="admin-sidebar-section-title">Overview</p>
                <div class="admin-sidebar-section">
                    <x-admin-sidebar-item icon="layout-grid" label="Dashboard" :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" />
                    <x-admin-sidebar-item icon="chart-bar" label="Reports" :href="route('admin.reports.index')" :active="request()->routeIs('admin.reports.*')" />
                </div>

                <p class="admin-sidebar-section-title">Sales</p>
                <div class="admin-sidebar-section">
                    <x-admin-sidebar-item icon="receipt" label="Orders" :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')" />
                    <x-admin-sidebar-item icon="ticket" label="Coupons" :href="route('admin.coupons.index')" :active="request()->routeIs('admin.coupons.*')" />
                </div>

                <p class="admin-sidebar-section-title">Marketing</p>
                <div class="admin-sidebar-section">
                    <x-admin-sidebar-item icon="image" label="Homepage Slides" :href="route('admin.homepage-slides.index')" :active="request()->routeIs('admin.homepage-slides.*')" />
                    <x-admin-sidebar-item icon="message-square" label="Feedback" :href="route('admin.feedback.index')" :active="request()->routeIs('admin.feedback.*')" />
                </div>

                <p class="admin-sidebar-section-title">Catalog</p>
                <div class="admin-sidebar-section">
                    <x-admin-sidebar-item icon="package" label="Products" :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*')" />
                    <x-admin-sidebar-item icon="layers" label="Categories" :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')" />
                    <x-admin-sidebar-item icon="tag" label="Product Types" :href="route('admin.product-types.index')" :active="request()->routeIs('admin.product-types.*')" />
                    <x-admin-sidebar-item icon="shield" label="Teams / Clubs" :href="route('admin.teams.index')" :active="request()->routeIs('admin.teams.*')" />
                    <x-admin-sidebar-item icon="globe" label="Leagues" :href="route('admin.leagues.index')" :active="request()->routeIs('admin.leagues.*')" />
                    <x-admin-sidebar-item icon="ruler" label="Sizes" :href="route('admin.sizes.index')" :active="request()->routeIs('admin.sizes.*')" />
                    <x-admin-sidebar-item icon="palette" label="Colors" :href="route('admin.colors.index')" :active="request()->routeIs('admin.colors.*')" />
                </div>

                <p class="admin-sidebar-section-title">System</p>
                <div class="admin-sidebar-section">
                    <x-admin-sidebar-item icon="user" label="Profile" :href="route('admin.profile.edit')" :active="request()->routeIs('admin.profile.*')" />
                    <x-admin-sidebar-item icon="paintbrush" label="Branding" :href="route('admin.branding.edit')" :active="request()->routeIs('admin.branding.*')" />
                    <x-admin-sidebar-item icon="cog" label="Settings" :href="route('admin.settings.edit')" :active="request()->routeIs('admin.settings.*')" />
                </div>
            </nav>

            <div class="admin-sidebar-footer">
                <a href="{{ route('home') }}" class="admin-sidebar-footer-link">
                    <x-icons.arrow-left class="h-4 w-4" />
                    Back to Store
                </a>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="admin-sidebar-logout">Logout</button>
                </form>
            </div>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="flex h-16 items-center justify-between border-b border-gray-200 bg-white px-4 sm:px-6">
                <button type="button" @click="sidebarOpen = true" class="rounded-md p-2 text-gray-600 lg:hidden" aria-label="Open sidebar">
                    <x-icons.menu class="h-6 w-6" />
                </button>
                <h1 class="text-lg font-semibold text-gray-900">{{ $title ?? 'Dashboard' }}</h1>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.profile.edit') }}" class="hidden text-sm text-gray-500 transition hover:text-brand-red sm:inline">{{ auth()->user()->name ?? 'Admin' }}</a>
                    <a href="{{ route('admin.profile.edit') }}" class="flex h-8 w-8 items-center justify-center rounded-full bg-brand-black text-xs font-bold text-white transition hover:ring-2 hover:ring-brand-red/40" title="Edit profile">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</a>
                </div>
            </header>

            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                <x-admin.flash />
                @yield('content')
            </main>
        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
    @stack('scripts')
</body>
</html>
