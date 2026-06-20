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
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100 antialiased" x-data="{ sidebarOpen: false }">
    <div class="flex min-h-screen">
        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black/50 lg:hidden"></div>

        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col bg-brand-black text-gray-300 transition-transform duration-200 lg:static lg:translate-x-0"
        >
            <div class="flex h-16 items-center border-b border-gray-800 px-6">
                <a href="{{ route('admin.dashboard') }}" class="flex min-w-0 items-center gap-2">
                    @if ($adminLogoUrl ?? null)
                        <img src="{{ $adminLogoUrl }}" alt="{{ $brandingStoreName ?? 'Admin' }}" class="h-8 max-w-[140px] object-contain object-left">
                    @else
                        <span class="text-lg font-bold text-white">
                            MOF <span class="text-brand-red">Admin</span>
                        </span>
                    @endif
                </a>
            </div>

            <nav class="flex-1 overflow-y-auto p-4 pb-24" aria-label="Admin navigation">
                <p class="mb-2 px-3 text-xs font-bold uppercase tracking-wider text-gray-500">Overview</p>
                <div class="mb-6 space-y-1">
                    <x-admin-sidebar-item label="Dashboard" :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" />
                    <x-admin-sidebar-item label="Reports" :href="route('admin.reports.index')" :active="request()->routeIs('admin.reports.*')" />
                </div>

                <p class="mb-2 px-3 text-xs font-bold uppercase tracking-wider text-gray-500">Sales</p>
                <div class="mb-6 space-y-1">
                    <x-admin-sidebar-item label="Orders" :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')" />
                    <x-admin-sidebar-item label="Coupons" :href="route('admin.coupons.index')" :active="request()->routeIs('admin.coupons.*')" />
                </div>

                <p class="mb-2 px-3 text-xs font-bold uppercase tracking-wider text-gray-500">Marketing</p>
                <div class="mb-6 space-y-1">
                    <x-admin-sidebar-item label="Homepage Slides" :href="route('admin.homepage-slides.index')" :active="request()->routeIs('admin.homepage-slides.*')" />
                    <x-admin-sidebar-item label="Feedback" :href="route('admin.feedback.index')" :active="request()->routeIs('admin.feedback.*')" />
                </div>

                <p class="mb-2 px-3 text-xs font-bold uppercase tracking-wider text-gray-500">Catalog</p>
                <div class="mb-6 space-y-1">
                    <x-admin-sidebar-item label="Products" :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*')" />
                    <x-admin-sidebar-item label="Categories" :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')" />
                    <x-admin-sidebar-item label="Product Types" :href="route('admin.product-types.index')" :active="request()->routeIs('admin.product-types.*')" />
                    <x-admin-sidebar-item label="Teams / Clubs" :href="route('admin.teams.index')" :active="request()->routeIs('admin.teams.*')" />
                    <x-admin-sidebar-item label="Leagues" :href="route('admin.leagues.index')" :active="request()->routeIs('admin.leagues.*')" />
                    <x-admin-sidebar-item label="Sizes" :href="route('admin.sizes.index')" :active="request()->routeIs('admin.sizes.*')" />
                    <x-admin-sidebar-item label="Colors" :href="route('admin.colors.index')" :active="request()->routeIs('admin.colors.*')" />
                </div>

                <p class="mb-2 px-3 text-xs font-bold uppercase tracking-wider text-gray-500">System</p>
                <div class="space-y-1">
                    <x-admin-sidebar-item label="Profile" :href="route('admin.profile.edit')" :active="request()->routeIs('admin.profile.*')" />
                    <x-admin-sidebar-item label="Branding" :href="route('admin.branding.edit')" :active="request()->routeIs('admin.branding.*')" />
                    <x-admin-sidebar-item label="Settings" :href="route('admin.settings.edit')" :active="request()->routeIs('admin.settings.*')" />
                </div>
            </nav>

            <div class="absolute bottom-0 left-0 right-0 border-t border-gray-800 bg-brand-black p-4">
                <a href="{{ route('home') }}" class="mb-3 flex items-center gap-2 text-sm text-gray-400 hover:text-white">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to Store
                </a>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-gray-400 hover:text-brand-red">Logout</button>
                </form>
            </div>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="flex h-16 items-center justify-between border-b border-gray-200 bg-white px-4 sm:px-6">
                <button type="button" @click="sidebarOpen = true" class="rounded-md p-2 text-gray-600 lg:hidden" aria-label="Open sidebar">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
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
