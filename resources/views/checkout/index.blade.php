@extends('layouts.app')

@section('content')
    <div class="container-store py-6 sm:py-8 lg:py-12">
        <h1 class="section-title icon-label mb-2">
            <x-icons.lock class="h-7 w-7 text-brand-red" />
            Checkout
        </h1>
        <p class="icon-label mb-6 text-sm text-gray-600 sm:mb-8">
            <x-icons.whatsapp class="h-4 w-4 text-green-600" />
            No online payment — your order will be saved and completed via WhatsApp.
        </p>

        <div class="grid gap-8 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <form action="{{ route('checkout.store') }}" method="POST" class="space-y-5 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:space-y-6 sm:p-8">
                    @csrf
                    <h2 class="icon-label text-lg font-bold text-gray-900">
                        <x-icons.user class="h-5 w-5 text-brand-red" />
                        Customer Information
                    </h2>

                    @if ($errors->any())
                        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                            <ul class="list-disc pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif

                    <div>
                        <label for="name" class="icon-label text-sm font-medium text-gray-700">
                            <x-icons.user class="h-4 w-4 text-gray-400" />
                            Full Name <span class="text-brand-red">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required class="checkout-form-input">
                    </div>

                    <div>
                        <label for="phone" class="icon-label text-sm font-medium text-gray-700">
                            <x-icons.phone class="h-4 w-4 text-gray-400" />
                            Phone <span class="text-brand-red">*</span>
                        </label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required class="checkout-form-input">
                    </div>

                    <div>
                        <label for="address" class="icon-label text-sm font-medium text-gray-700">
                            <x-icons.location class="h-4 w-4 text-gray-400" />
                            Address <span class="text-brand-red">*</span>
                        </label>
                        <textarea id="address" name="address" required rows="3" class="checkout-form-input">{{ old('address') }}</textarea>
                    </div>

                    <div>
                        <label for="city" class="icon-label text-sm font-medium text-gray-700">
                            <x-icons.location class="h-4 w-4 text-gray-400" />
                            City
                        </label>
                        <input type="text" id="city" name="city" value="{{ old('city') }}" class="checkout-form-input">
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Notes <span class="text-gray-400">(optional)</span></label>
                        <textarea id="notes" name="notes" rows="2" class="checkout-form-input">{{ old('notes') }}</textarea>
                    </div>

                    <div class="icon-label rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-800">
                        <x-icons.whatsapp class="h-5 w-5 shrink-0 text-green-600" />
                        After placing your order, you will be redirected to WhatsApp to confirm with our team.
                    </div>

                    <button type="submit" class="checkout-whatsapp-btn lg:w-auto">
                        <x-icons.whatsapp class="h-5 w-5" />
                        Place Order via WhatsApp
                    </button>
                </form>
            </div>

            <div>
                <div class="space-y-4 lg:sticky lg:top-24">
                    <x-coupon-box :coupon="$coupon" />
                    <div class="rounded-2xl border border-gray-200 bg-gray-50 p-5 shadow-sm sm:p-6">
                        <h2 class="icon-label text-lg font-bold text-gray-900">
                            <x-icons.receipt class="h-5 w-5 text-brand-red" />
                            Order Summary
                        </h2>
                        <ul class="mt-4 space-y-3 text-sm">
                            @foreach ($items as $item)
                                <li class="flex justify-between gap-4">
                                    <span class="min-w-0 text-gray-600">
                                        {{ $item['product_name'] }}
                                        @if ($item['size_name'] || $item['color_name'])
                                            <span class="block text-xs text-gray-400">
                                                {{ $item['size_name'] }}{{ $item['size_name'] && $item['color_name'] ? ' / ' : '' }}{{ $item['color_name'] }}
                                            </span>
                                        @endif
                                        &times;{{ $item['quantity'] }}
                                    </span>
                                    <span class="shrink-0 font-medium">{{ $currencySymbol }}{{ number_format($item['total_price'], 2) }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <dl class="mt-4 space-y-2 border-t border-gray-200 pt-4 text-sm">
                            <div class="flex justify-between"><dt class="text-gray-600">Subtotal</dt><dd>{{ $currencySymbol }}{{ number_format($subtotal, 2) }}</dd></div>
                            @if ($discountAmount > 0)
                                <div class="flex justify-between text-green-700"><dt>Discount @if($coupon)({{ $coupon['code'] }})@endif</dt><dd>-{{ $currencySymbol }}{{ number_format($discountAmount, 2) }}</dd></div>
                            @endif
                            <div class="flex justify-between"><dt class="text-gray-600">Delivery Fee</dt><dd>{{ $deliveryFee > 0 ? $currencySymbol.number_format($deliveryFee, 2) : 'Free' }}</dd></div>
                            <div class="flex justify-between text-base font-bold"><dt>Total</dt><dd>{{ $currencySymbol }}{{ number_format($total, 2) }}</dd></div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
