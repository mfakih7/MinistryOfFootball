@extends('layouts.app')

@section('content')
    <div class="container-store py-6 sm:py-8 lg:py-12">
        <div class="mb-6 sm:mb-8">
            <h1 class="section-title icon-label">
                <x-icons.lock class="h-7 w-7 text-brand-red" />
                Checkout
                <x-icons.whatsapp class="h-6 w-6 text-green-600" />
            </h1>
            <p class="mt-2 text-sm text-gray-600 sm:text-base">No online payment — we'll save your order and confirm everything with you on WhatsApp.</p>
            <span class="icon-label mt-3 inline-flex rounded-full border border-green-200 bg-green-50 px-3 py-1.5 text-xs font-semibold text-green-800">
                <x-icons.check-circle class="h-3.5 w-3.5" />
                Trusted by fans nationwide
            </span>
        </div>

        <div
            class="grid gap-6 lg:grid-cols-3 lg:gap-8"
            x-data="{
                summaryOpen: true,
                customize: {},
                fee: {{ $customizationFee }},
                get customizationTotal() { return Object.values(this.customize).filter(Boolean).length * this.fee }
            }"
        >
            {{-- Order Summary: shown first on mobile, right column on desktop --}}
            <div class="order-1 lg:order-2">
                <div class="space-y-4 lg:sticky lg:top-24">
                    <div class="checkout-card">
                        <button type="button" @click="summaryOpen = !summaryOpen" class="checkout-collapse-toggle">
                            <span class="checkout-card-title">
                                <x-icons.receipt class="h-5 w-5 text-brand-red" />
                                Order Summary ({{ $items->count() }} {{ $items->count() === 1 ? 'Item' : 'Items' }})
                            </span>
                            <x-icons.arrow-up class="h-4 w-4 text-gray-400 transition" ::class="summaryOpen ? '' : 'rotate-180'" />
                        </button>

                        <div x-show="summaryOpen" x-transition class="mt-4">
                            <ul>
                                @foreach ($items as $item)
                                    <li class="checkout-summary-item flex-col items-stretch sm:flex-row sm:items-start">
                                        <div class="flex gap-3">
                                            <div class="checkout-summary-thumb">
                                                <img src="{{ $item['thumbnail_url'] }}" alt="{{ $item['product_name'] }}" loading="lazy" class="h-full w-full object-contain p-1">
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="line-clamp-1 text-sm font-semibold text-gray-900">{{ $item['product_name'] }}</p>
                                                <p class="mt-0.5 text-xs text-gray-500">
                                                    @if ($item['size_name']) Size: {{ $item['size_name'] }} @endif
                                                    @if ($item['color_name']) @if($item['size_name']) &middot; @endif Color: {{ $item['color_name'] }} @endif
                                                    @if ($item['size_name'] || $item['color_name']) &middot; @endif Qty: {{ $item['quantity'] }}
                                                </p>
                                                @if ($item['is_customizable'])
                                                    <span class="checkout-customizable-badge">Customizable</span>
                                                @endif
                                            </div>
                                            <p class="shrink-0 text-sm font-bold text-gray-900">{{ $currencySymbol }}{{ number_format($item['total_price'], 2) }}</p>
                                        </div>

                                        @if ($item['is_customizable'])
                                            <div class="checkout-customize-block">
                                                <label class="checkout-customize-checkbox">
                                                    <input
                                                        type="checkbox"
                                                        form="checkout-form"
                                                        name="customizations[{{ $item['key'] }}][requested]"
                                                        value="1"
                                                        x-model="customize['{{ $item['key'] }}']"
                                                    >
                                                    Customize this item (+{{ $currencySymbol }}{{ number_format($customizationFee, 2) }})
                                                </label>
                                                <div x-show="customize['{{ $item['key'] }}']" x-cloak x-transition class="mt-2">
                                                    <label class="sr-only" for="customization-{{ $item['key'] }}">Customization Details</label>
                                                    <textarea
                                                        id="customization-{{ $item['key'] }}"
                                                        form="checkout-form"
                                                        name="customizations[{{ $item['key'] }}][details]"
                                                        rows="2"
                                                        maxlength="500"
                                                        placeholder="Example: Name: RONALDO, Number: 7"
                                                        class="checkout-customize-textarea"
                                                    >{{ old("customizations.{$item['key']}.details") }}</textarea>
                                                    @error("customizations.{$item['key']}.details")
                                                        <p class="mt-1 text-xs text-brand-red">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <dl class="mt-2 space-y-2 border-t border-gray-200 pt-4 text-sm">
                            <div class="flex justify-between"><dt class="text-gray-600">Subtotal</dt><dd class="font-medium">{{ $currencySymbol }}{{ number_format($subtotal, 2) }}</dd></div>
                            @if ($discountAmount > 0)
                                <div class="flex justify-between text-green-700"><dt>Discount @if($coupon)({{ $coupon['code'] }})@endif</dt><dd>-{{ $currencySymbol }}{{ number_format($discountAmount, 2) }}</dd></div>
                            @endif
                            <div class="flex justify-between"><dt class="text-gray-600">Delivery Fee</dt><dd class="font-medium">{{ $deliveryFee > 0 ? $currencySymbol.number_format($deliveryFee, 2) : 'Free' }}</dd></div>
                            <div class="flex justify-between" x-show="customizationTotal > 0" x-cloak>
                                <dt class="text-gray-600">Customization</dt>
                                <dd class="font-medium" x-text="'{{ $currencySymbol }}' + customizationTotal.toFixed(2)"></dd>
                            </div>
                        </dl>
                        <div class="checkout-summary-total-row">
                            <span class="text-base font-bold text-gray-900">Total</span>
                            <span class="checkout-summary-total-value" x-text="'{{ $currencySymbol }}' + ({{ $total }} + customizationTotal).toFixed(2)"></span>
                        </div>

                        <div class="checkout-trust-row">
                            <div class="checkout-trust-item">
                                <x-icons.truck class="h-4 w-4 text-brand-red" />
                                Fast Delivery
                            </div>
                            <div class="checkout-trust-item">
                                <x-icons.shield class="h-4 w-4 text-brand-red" />
                                Premium Quality
                            </div>
                            <div class="checkout-trust-item">
                                <x-icons.whatsapp class="h-4 w-4 text-green-600" />
                                WhatsApp Support
                            </div>
                        </div>
                    </div>

                    <x-coupon-box :coupon="$coupon" :discount-amount="$discountAmount" />
                </div>
            </div>

            {{-- Customer Information: below summary on mobile, left column on desktop --}}
            <div class="order-2 lg:order-1 lg:col-span-2">
                <form id="checkout-form" action="{{ route('checkout.store') }}" method="POST" class="checkout-card space-y-5 sm:space-y-6">
                    @csrf
                    <div>
                        <h2 class="checkout-card-title">
                            <x-icons.user class="h-5 w-5 text-brand-red" />
                            Customer Information
                        </h2>
                        <p class="checkout-card-subtitle">Please provide your details to complete your order.</p>
                    </div>

                    @if ($errors->any())
                        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                            <ul class="list-disc pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif

                    <div>
                        <label for="name" class="text-sm font-medium text-gray-700">Full Name <span class="text-brand-red">*</span></label>
                        <div class="checkout-input-group">
                            <x-icons.user class="checkout-input-icon h-4 w-4" />
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required class="checkout-form-input">
                        </div>
                    </div>

                    <div>
                        <label for="phone" class="text-sm font-medium text-gray-700">Phone Number <span class="text-brand-red">*</span></label>
                        <div class="checkout-input-group">
                            <x-icons.phone class="checkout-input-icon h-4 w-4" />
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required class="checkout-form-input">
                        </div>
                    </div>

                    <div>
                        <label for="address" class="text-sm font-medium text-gray-700">Address <span class="text-brand-red">*</span></label>
                        <div class="checkout-input-group">
                            <x-icons.location class="checkout-input-icon checkout-input-icon--top h-4 w-4" />
                            <textarea id="address" name="address" required rows="3" class="checkout-form-input">{{ old('address') }}</textarea>
                        </div>
                    </div>

                    <div>
                        <label for="city" class="text-sm font-medium text-gray-700">City</label>
                        <div class="checkout-input-group">
                            <x-icons.location class="checkout-input-icon h-4 w-4" />
                            <input type="text" id="city" name="city" value="{{ old('city') }}" class="checkout-form-input">
                        </div>
                    </div>

                    <div>
                        <label for="notes" class="text-sm font-medium text-gray-700">Order Notes <span class="text-gray-400">(optional)</span></label>
                        <div class="checkout-input-group">
                            <x-icons.pencil-square class="checkout-input-icon checkout-input-icon--top h-4 w-4" />
                            <textarea id="notes" name="notes" rows="2" class="checkout-form-input">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="checkout-info-box">
                        <x-icons.whatsapp class="h-5 w-5 shrink-0 text-green-600" />
                        After placing your order, you'll be redirected to WhatsApp so our team can confirm your order and assist you.
                    </div>

                    <div>
                        <button type="submit" class="checkout-whatsapp-btn">
                            <x-icons.whatsapp class="h-5 w-5" />
                            Place Order via WhatsApp
                        </button>
                        <p class="mt-3 text-center text-xs text-gray-500">We'll save your order and continue the conversation on WhatsApp.</p>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
