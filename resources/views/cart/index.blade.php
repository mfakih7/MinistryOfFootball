@extends('layouts.app')

@section('content')
    <div class="container-store py-6 sm:py-8 lg:py-12">
        <div class="cart-page-header">
            <div>
                <h1 class="section-title icon-label">
                    <x-icons.cart class="h-7 w-7 text-brand-red" />
                    Shopping Cart
                </h1>
                <p class="mt-2 text-sm text-gray-600 sm:text-base">Review your items and proceed to checkout.</p>
            </div>
            <span class="cart-secure-badge">
                <x-icons.lock class="h-3.5 w-3.5" />
                Secure Checkout
            </span>
        </div>

        @if ($items->isEmpty())
            <div class="rounded-2xl border border-gray-200 bg-white p-10 text-center shadow-sm sm:p-12">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                    <x-icons.cart class="h-8 w-8" />
                </div>
                <p class="text-lg font-medium text-gray-900">Your cart is empty</p>
                <p class="mt-2 text-sm text-gray-500">Browse our shop and add items to get started.</p>
                <a href="{{ route('shop') }}" class="btn-primary icon-label mt-6 inline-flex min-h-[48px]">
                    <x-icons.arrow-left class="h-4 w-4" />
                    Continue Shopping
                </a>
            </div>
        @else
            <div class="cart-trust-bar">
                <div class="cart-trust-item">
                    <span class="cart-trust-icon"><x-icons.truck class="h-5 w-5" /></span>
                    <span>
                        <span class="cart-trust-title block">Free Delivery</span>
                        <span class="cart-trust-subtitle">On qualifying orders</span>
                    </span>
                </div>
                <div class="cart-trust-item">
                    <span class="cart-trust-icon"><x-icons.shield class="h-5 w-5" /></span>
                    <span>
                        <span class="cart-trust-title block">Premium Quality</span>
                        <span class="cart-trust-subtitle">Authentic-style kits</span>
                    </span>
                </div>
                <div class="cart-trust-item">
                    <span class="cart-trust-icon"><x-icons.whatsapp class="h-5 w-5" /></span>
                    <span>
                        <span class="cart-trust-title block">WhatsApp Support</span>
                        <span class="cart-trust-subtitle">Order help anytime</span>
                    </span>
                </div>
            </div>

            <div class="grid gap-8 lg:grid-cols-3">
                <div class="space-y-4 lg:col-span-2">
                    <div class="cart-items-card">
                        <div class="cart-items-header">
                            @php $itemCount = $items->sum('quantity'); @endphp
                            <p class="cart-items-count">{{ $itemCount }} {{ $itemCount === 1 ? 'Item' : 'Items' }} in Cart</p>
                            <form method="POST" action="{{ route('cart.clear') }}" onsubmit="return confirm('Clear entire cart?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="cart-clear-action">
                                    <x-icons.trash class="h-3.5 w-3.5" />
                                    Clear Cart
                                </button>
                            </form>
                        </div>
                        @foreach ($items as $item)
                            <div class="flex gap-3 border-b border-gray-100 p-4 last:border-b-0 sm:gap-4 sm:p-5">
                                <a href="{{ $item['product_slug'] ? route('product.show', $item['product_slug']) : route('shop') }}" class="h-24 w-20 shrink-0 overflow-hidden rounded-xl bg-[#f7f7f7] sm:h-28 sm:w-24">
                                    <img src="{{ $item['thumbnail_url'] }}" alt="{{ $item['product_name'] }}" loading="lazy" class="h-full w-full object-contain p-1">
                                </a>
                                <div class="flex min-w-0 flex-1 flex-col justify-between">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="min-w-0">
                                            <h2 class="line-clamp-2 text-sm font-semibold text-gray-900 sm:text-base">{{ $item['product_name'] }}</h2>
                                            <p class="mt-1 text-xs text-gray-500 sm:text-sm">
                                                @if ($item['size_name']) Size: {{ $item['size_name'] }} @endif
                                                @if ($item['color_name']) @if($item['size_name']) &middot; @endif Color: {{ $item['color_name'] }} @endif
                                            </p>
                                            <p class="mt-1 text-sm font-semibold text-brand-red">{{ $currencySymbol }}{{ number_format($item['unit_price'], 2) }} <span class="font-normal text-gray-500">each</span></p>
                                        </div>
                                        <form method="POST" action="{{ route('cart.remove') }}" class="shrink-0">
                                            @csrf @method('DELETE')
                                            <input type="hidden" name="key" value="{{ $item['key'] }}">
                                            <button type="submit" class="cart-remove-btn" aria-label="Remove item">
                                                <x-icons.trash class="h-5 w-5" />
                                            </button>
                                        </form>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between gap-2">
                                        <form method="POST" action="{{ route('cart.update') }}" class="cart-qty-control">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="key" value="{{ $item['key'] }}">
                                            <button type="submit" name="quantity" value="{{ max(1, $item['quantity'] - 1) }}" class="cart-qty-btn" aria-label="Decrease quantity">
                                                <x-icons.minus class="h-4 w-4" />
                                            </button>
                                            <span class="cart-qty-value">{{ $item['quantity'] }}</span>
                                            <button type="submit" name="quantity" value="{{ $item['quantity'] + 1 }}" class="cart-qty-btn" aria-label="Increase quantity">
                                                <x-icons.plus class="h-4 w-4" />
                                            </button>
                                        </form>
                                        <p class="shrink-0 text-sm font-bold text-gray-900 sm:text-base">{{ $currencySymbol }}{{ number_format($item['total_price'], 2) }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <a href="{{ route('shop') }}" class="icon-label inline-flex min-h-[44px] text-sm font-semibold text-brand-red hover:text-brand-red-dark">
                        <x-icons.arrow-left class="h-4 w-4" />
                        Continue Shopping
                    </a>
                </div>

                <div>
                    <div class="space-y-4 lg:sticky lg:top-24">
                        <x-coupon-box :coupon="$coupon" :discount-amount="$discountAmount" />
                        <div class="cart-summary-card">
                            <h2 class="icon-label text-lg font-bold text-gray-900">
                                <x-icons.receipt class="h-5 w-5 text-brand-red" />
                                Order Summary
                            </h2>
                            <dl class="mt-4 space-y-3 text-sm">
                                <div class="flex justify-between"><dt class="text-gray-600">Subtotal</dt><dd class="font-medium">{{ $currencySymbol }}{{ number_format($subtotal, 2) }}</dd></div>
                                @if ($discountAmount > 0)
                                    <div class="flex justify-between text-green-700"><dt>Discount @if($coupon)({{ $coupon['code'] }})@endif</dt><dd>-{{ $currencySymbol }}{{ number_format($discountAmount, 2) }}</dd></div>
                                @endif
                                <div class="flex justify-between"><dt class="text-gray-600">Delivery</dt><dd class="font-medium">{{ $deliveryFee > 0 ? $currencySymbol.number_format($deliveryFee, 2) : 'Free' }}</dd></div>
                                <div class="flex justify-between border-t border-gray-200 pt-3 text-base font-bold"><dt>Total</dt><dd class="cart-summary-total-value">{{ $currencySymbol }}{{ number_format($total, 2) }}</dd></div>
                            </dl>
                            <a href="{{ route('checkout') }}" class="btn-primary icon-label mt-6 w-full min-h-[48px] justify-center">
                                <x-icons.lock class="h-4 w-4" />
                                Proceed to Checkout
                            </a>
                            <p class="cart-secure-note">
                                <x-icons.shield class="h-4 w-4 shrink-0 text-gray-400" />
                                Your order details are safe and protected.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
