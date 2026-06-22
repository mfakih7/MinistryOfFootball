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
@endphp

<footer class="store-footer">
    <div class="store-footer-main">
        <div class="container-store py-8 md:py-14">
            {{-- Mobile footer: brand + social + contact card only --}}
            <div class="store-footer-mobile md:hidden">
                <div class="store-footer-brand-mobile">
                    <a href="{{ route('home') }}" class="group inline-flex items-center gap-2.5">
                        <x-store.logo-mark variant="dark" />
                    </a>
                    <p class="mt-3 text-sm leading-relaxed text-gray-400">
                        Premium football jerseys, NBA shirts, and sportswear for fans who wear their passion.
                    </p>
                </div>

                <div class="store-footer-social-row">
                    @if ($storeSettings['instagram_url'] ?? null)
                        <a href="{{ $storeSettings['instagram_url'] }}" target="_blank" rel="noopener" class="store-footer-social-btn" aria-label="Instagram">
                            <x-icons.instagram class="h-4 w-4" />
                        </a>
                    @endif
                    @if ($storeSettings['tiktok_url'] ?? null)
                        <a href="{{ $storeSettings['tiktok_url'] }}" target="_blank" rel="noopener" class="store-footer-social-btn" aria-label="TikTok">
                            <x-icons.tiktok class="h-4 w-4" />
                        </a>
                    @endif
                    @if ($storeSettings['facebook_url'] ?? null)
                        <a href="{{ $storeSettings['facebook_url'] }}" target="_blank" rel="noopener" class="store-footer-social-btn" aria-label="Facebook">
                            <x-icons.facebook class="h-4 w-4" />
                        </a>
                    @endif
                    @if ($whatsappFloatUrl ?? null)
                        <a href="{{ $whatsappFloatUrl }}" target="_blank" rel="noopener" class="store-footer-social-btn" aria-label="WhatsApp">
                            <x-icons.whatsapp class="h-4 w-4" />
                        </a>
                    @endif
                </div>

                <div class="store-footer-contact-card">
                    <h3 class="store-footer-contact-card-title">Get in Touch</h3>
                    <ul class="store-footer-contact-card-list">
                        @if ($storeSettings['store_phone'] ?? null)
                            <li>
                                <a href="tel:{{ $storeSettings['store_phone'] }}" class="store-footer-contact-card-item">
                                    <span class="store-footer-contact-card-icon">
                                        <x-icons.phone class="h-4 w-4" />
                                    </span>
                                    <span class="store-footer-contact-card-text">{{ $storeSettings['store_phone'] }}</span>
                                </a>
                            </li>
                        @endif
                        @if ($storeSettings['store_email'] ?? null)
                            <li>
                                <a href="mailto:{{ $storeSettings['store_email'] }}" class="store-footer-contact-card-item">
                                    <span class="store-footer-contact-card-icon">
                                        <x-icons.email class="h-4 w-4" />
                                    </span>
                                    <span class="store-footer-contact-card-text break-words">{{ $storeSettings['store_email'] }}</span>
                                </a>
                            </li>
                        @endif
                        @if ($storeSettings['store_address'] ?? null)
                            <li>
                                <span class="store-footer-contact-card-item">
                                    <span class="store-footer-contact-card-icon">
                                        <x-icons.location class="h-4 w-4" />
                                    </span>
                                    <span class="store-footer-contact-card-text">{{ $storeSettings['store_address'] }}</span>
                                </span>
                            </li>
                        @endif
                        <li>
                            <span class="store-footer-contact-card-item">
                                <span class="store-footer-contact-card-icon">
                                    <x-icons.clock class="h-4 w-4" />
                                </span>
                                <span class="store-footer-contact-card-text">Mon–Sat, 10:00 AM – 7:00 PM</span>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Desktop footer: unchanged column layout --}}
            <div class="store-footer-desktop">
                <div>
                    <a href="{{ route('home') }}" class="group inline-flex items-center gap-2.5">
                        <x-store.logo-mark variant="dark" />
                    </a>
                    <p class="mt-3 max-w-xs text-sm leading-relaxed text-gray-400">
                        Premium football jerseys, NBA shirts, and sportswear for fans who wear their passion.
                    </p>
                    <div class="mt-4 flex items-center gap-1.5">
                        @if ($whatsappFloatUrl ?? null)
                            <a href="{{ $whatsappFloatUrl }}" target="_blank" rel="noopener" class="store-social-icon" aria-label="WhatsApp">
                                <x-icons.whatsapp class="h-3.5 w-3.5" />
                            </a>
                        @endif
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
                </div>

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

                <div class="store-footer-col">
                    <h3 class="store-footer-heading">Customer Care</h3>
                    <ul class="store-footer-links">
                        @foreach ($careLinks as $link)
                            <li><a href="{{ $link['href'] }}" class="store-footer-link">{{ $link['label'] }}</a></li>
                        @endforeach
                    </ul>
                </div>

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
        <div class="container-store py-5 text-center text-xs text-gray-500 md:text-left">
            <p>&copy; {{ $year }} {{ $storeName }}. All rights reserved.</p>
            <p class="mt-0.5 text-gray-600">Orders confirmed via WhatsApp</p>
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
</div>
