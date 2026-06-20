@php
    $storeName = $storeSettings['store_name'] ?? 'Ministry Of Football';
    $year = date('Y');

    $shopLinks = [
        ['label' => 'All Products', 'href' => route('shop')],
        ['label' => 'Football Jerseys', 'href' => route('shop', ['category' => 'football-jerseys'])],
        ['label' => 'Clubs', 'href' => route('shop', ['category' => 'football-jerseys'])],
        ['label' => 'National Teams', 'href' => route('shop', ['league' => 'national-teams'])],
        ['label' => 'NBA Collection', 'href' => route('shop', ['league' => 'nba'])],
        ['label' => 'Accessories', 'href' => route('shop', ['category' => 'accessories'])],
        ['label' => 'Sale', 'href' => route('shop', ['sale' => 1]), 'accent' => true],
    ];

    $careLinks = [
        ['label' => 'Contact Us', 'href' => route('contact')],
        ['label' => 'Track Order', 'href' => route('track-order')],
        ['label' => 'Shipping Policy', 'href' => route('policy.shipping')],
        ['label' => 'Return Policy', 'href' => route('policy.returns')],
    ];

    $legalLinks = [
        ['label' => 'Privacy Policy', 'href' => route('policy.privacy')],
        ['label' => 'Terms of Service', 'href' => route('policy.terms')],
    ];

    $hasSocial = ($storeSettings['instagram_url'] ?? null)
        || ($storeSettings['facebook_url'] ?? null)
        || ($storeSettings['tiktok_url'] ?? null);
@endphp

<footer class="store-footer">
    <div class="store-footer-main">
        <div class="container-store py-10 lg:py-14">
            <div class="grid gap-8 md:grid-cols-2 xl:grid-cols-5 xl:gap-10">
                {{-- Brand --}}
                <div class="md:col-span-2 xl:col-span-1">
                    <a href="{{ route('home') }}" class="group inline-flex items-center gap-2.5">
                        <x-store.logo-mark variant="dark" />
                    </a>
                    <p class="mt-3 max-w-xs text-sm leading-relaxed text-gray-400">
                        Premium football jerseys, NBA shirts, and sportswear accessories delivered with care.
                    </p>

                    @if ($whatsappFloatUrl ?? null)
                        <a href="{{ $whatsappFloatUrl }}" target="_blank" rel="noopener" class="store-footer-whatsapp mt-4 inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-green-500">
                            <x-icons.whatsapp class="h-4 w-4" />
                            Chat on WhatsApp
                        </a>
                    @endif

                    @if ($hasSocial)
                        <div class="mt-4 flex items-center gap-1.5">
                            @if ($storeSettings['instagram_url'] ?? null)
                                <a href="{{ $storeSettings['instagram_url'] }}" target="_blank" rel="noopener" class="store-social-icon" aria-label="Instagram">
                                    <x-icons.instagram class="h-3.5 w-3.5" />
                                </a>
                            @endif
                            @if ($storeSettings['facebook_url'] ?? null)
                                <a href="{{ $storeSettings['facebook_url'] }}" target="_blank" rel="noopener" class="store-social-icon" aria-label="Facebook">
                                    <x-icons.facebook class="h-3.5 w-3.5" />
                                </a>
                            @endif
                            @if ($storeSettings['tiktok_url'] ?? null)
                                <a href="{{ $storeSettings['tiktok_url'] }}" target="_blank" rel="noopener" class="store-social-icon" aria-label="TikTok">
                                    <x-icons.tiktok class="h-3.5 w-3.5" />
                                </a>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Shop --}}
                <div class="store-footer-col">
                    <h3 class="store-footer-heading">Shop</h3>
                    <ul class="store-footer-links">
                        @foreach ($shopLinks as $link)
                            <li>
                                <a href="{{ $link['href'] }}" @class(['store-footer-link', 'text-brand-red hover:text-brand-red' => $link['accent'] ?? false])>
                                    {{ $link['label'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Customer Care --}}
                <div class="store-footer-col">
                    <h3 class="store-footer-heading">Customer Care</h3>
                    <ul class="store-footer-links">
                        @foreach ($careLinks as $link)
                            <li><a href="{{ $link['href'] }}" class="store-footer-link">{{ $link['label'] }}</a></li>
                        @endforeach
                    </ul>
                </div>

                {{-- Legal --}}
                <div class="store-footer-col">
                    <h3 class="store-footer-heading">Legal</h3>
                    <ul class="store-footer-links">
                        @foreach ($legalLinks as $link)
                            <li><a href="{{ $link['href'] }}" class="store-footer-link">{{ $link['label'] }}</a></li>
                        @endforeach
                    </ul>
                </div>

                {{-- Contact --}}
                <div class="store-footer-col">
                    <h3 class="store-footer-heading">Contact</h3>
                    <ul class="store-footer-contact">
                        @if ($storeSettings['store_phone'] ?? null)
                            <li>
                                <a href="tel:{{ $storeSettings['store_phone'] }}" class="store-footer-contact-item">
                                    <x-icons.phone class="store-footer-contact-icon" />
                                    <span class="min-w-0">{{ $storeSettings['store_phone'] }}</span>
                                </a>
                            </li>
                        @endif
                        @if ($storeSettings['store_email'] ?? null)
                            <li>
                                <a href="mailto:{{ $storeSettings['store_email'] }}" class="store-footer-contact-item">
                                    <x-icons.email class="store-footer-contact-icon" />
                                    <span class="min-w-0 break-words">{{ $storeSettings['store_email'] }}</span>
                                </a>
                            </li>
                        @endif
                        @if ($storeSettings['store_address'] ?? null)
                            <li>
                                <span class="store-footer-contact-item">
                                    <x-icons.location class="store-footer-contact-icon" />
                                    <span class="min-w-0 leading-relaxed">{{ $storeSettings['store_address'] }}</span>
                                </span>
                            </li>
                        @endif
                        <li>
                            <span class="store-footer-contact-item">
                                <x-icons.clock class="store-footer-contact-icon" />
                                <span class="min-w-0 leading-relaxed">Mon–Sat, 10:00 AM – 7:00 PM</span>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="store-footer-bottom">
        <div class="container-store flex flex-col items-center gap-2 py-5 text-xs text-gray-500 sm:flex-row sm:justify-between">
            <p>&copy; {{ $year }} {{ $storeName }}. All rights reserved.</p>
            <div class="flex items-center gap-3">
                <a href="{{ route('policy.privacy') }}" class="transition hover:text-white">Privacy</a>
                <span class="text-gray-700" aria-hidden="true">|</span>
                <a href="{{ route('policy.terms') }}" class="transition hover:text-white">Terms</a>
            </div>
        </div>
    </div>
</footer>

<div
    class="store-floating-actions"
    x-data="{ showTop: false }"
    x-init="showTop = window.scrollY > 400"
    @scroll.window="showTop = window.scrollY > 400"
>
    <button
        type="button"
        x-show="showTop"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
        class="store-scroll-top"
        aria-label="Scroll to top"
    >
        <x-icons.arrow-up class="h-5 w-5" />
    </button>

    @if ($whatsappFloatUrl ?? null)
        <a href="{{ $whatsappFloatUrl }}" target="_blank" rel="noopener" class="store-whatsapp-float" aria-label="WhatsApp">
            <x-icons.whatsapp class="h-7 w-7" />
        </a>
    @endif
</div>
