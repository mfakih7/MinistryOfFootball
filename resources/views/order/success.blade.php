@extends('layouts.app')

@section('content')
    <div class="container-store py-12 lg:py-20">
        <div class="mx-auto max-w-2xl rounded-xl border border-gray-200 bg-white p-8 text-center shadow-sm sm:p-12">
            <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-green-100 text-green-600">
                <x-icons.check-circle class="h-8 w-8" />
            </div>

            <h1 class="text-3xl font-bold text-gray-900">Order Created Successfully</h1>
            <p class="mt-3 text-gray-600">Your order has been saved. Continue to WhatsApp to confirm with our team.</p>

            <div class="mt-8 rounded-lg bg-gray-50 p-6 text-left text-sm">
                <p class="icon-label font-semibold text-gray-900">
                    <x-icons.receipt class="h-4 w-4 text-brand-red" />
                    Order Number: <span class="text-brand-red">{{ $order->order_number }}</span>
                </p>
                <p class="icon-label mt-2 text-gray-600">
                    <x-icons.user class="h-4 w-4 text-gray-400" />
                    {{ $order->customer_name }}
                </p>
                <p class="icon-label mt-1 text-gray-600">
                    <x-icons.phone class="h-4 w-4 text-gray-400" />
                    {{ $order->customer_phone }}
                </p>
                <dl class="mt-4 space-y-1 border-t border-gray-200 pt-4">
                    <div class="flex justify-between"><dt>Subtotal</dt><dd>{{ $currencySymbol }}{{ number_format($order->subtotal, 2) }}</dd></div>
                    @if ($order->discount_total > 0)
                        <div class="flex justify-between text-green-700"><dt>Discount</dt><dd>-{{ $currencySymbol }}{{ number_format($order->discount_total, 2) }}</dd></div>
                    @endif
                    @if ($order->customization_total > 0)
                        <div class="flex justify-between"><dt>Customization</dt><dd>{{ $currencySymbol }}{{ number_format($order->customization_total, 2) }}</dd></div>
                    @endif
                    <div class="flex justify-between"><dt>Delivery</dt><dd>{{ $currencySymbol }}{{ number_format($order->delivery_fee, 2) }}</dd></div>
                    <div class="flex justify-between font-bold text-base"><dt>Total</dt><dd>{{ $currencySymbol }}{{ number_format($order->total, 2) }}</dd></div>
                </dl>
            </div>

            <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-center">
                <a href="{{ route('order.whatsapp', $order) }}" class="btn-storefront-primary icon-label justify-center">
                    <x-icons.whatsapp class="h-5 w-5" />
                    Continue to WhatsApp
                </a>
                <a href="{{ route('shop') }}" class="btn-storefront-secondary icon-label justify-center">
                    <x-icons.arrow-left class="h-4 w-4" />
                    Back to Shop
                </a>
            </div>
        </div>
    </div>
@endsection
