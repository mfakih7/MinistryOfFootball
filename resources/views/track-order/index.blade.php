@extends('layouts.app')

@section('content')
    <div class="container-store py-12 lg:py-16">
        <div class="mx-auto max-w-2xl">
            <h1 class="section-title text-center">Track Your Order</h1>
            <p class="mt-3 text-center text-gray-600">Enter the phone number used at checkout. Optionally add your order number for a specific order.</p>

            <form method="POST" action="{{ route('track-order.lookup') }}" class="mt-8 space-y-4 rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                @csrf
                <div>
                    <label for="phone" class="block text-sm font-semibold text-gray-900">Phone Number *</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone', $phone ?? '') }}" required class="mt-2 w-full rounded-md border border-gray-300 px-4 py-3 text-sm focus:border-brand-red focus:outline-none focus:ring-1 focus:ring-brand-red" placeholder="+961...">
                    @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="order_number" class="block text-sm font-semibold text-gray-900">Order Number (optional)</label>
                    <input type="text" id="order_number" name="order_number" value="{{ old('order_number', $order_number ?? '') }}" class="mt-2 w-full rounded-md border border-gray-300 px-4 py-3 text-sm focus:border-brand-red focus:outline-none focus:ring-1 focus:ring-brand-red" placeholder="MOF-2026-0001">
                    @error('order_number')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="btn-primary w-full">Track Order</button>
            </form>

            @isset($orders)
                @if ($orders->isEmpty())
                    <div class="mt-8 rounded-lg border border-amber-200 bg-amber-50 px-6 py-8 text-center">
                        <p class="font-semibold text-amber-900">No orders found</p>
                        <p class="mt-2 text-sm text-amber-800">Check your phone number and order number, then try again.</p>
                    </div>
                @else
                    <div class="mt-10 space-y-6">
                        <p class="text-sm text-gray-600">{{ $orders->count() }} order(s) found</p>
                        @foreach ($orders as $order)
                            <article class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                                <div class="border-b border-gray-100 bg-gray-50 px-6 py-4">
                                    <div class="flex flex-wrap items-center justify-between gap-3">
                                        <div>
                                            <h2 class="text-lg font-bold text-gray-900">{{ $order->order_number }}</h2>
                                            <p class="text-sm text-gray-500">{{ $order->created_at->format('M d, Y H:i') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="inline-block rounded-full bg-brand-black px-3 py-1 text-xs font-semibold uppercase tracking-wide text-white">
                                                {{ str_replace('_', ' ', $order->status->value) }}
                                            </span>
                                            <p class="mt-2 text-lg font-bold text-brand-red">${{ number_format($order->total, 2) }}</p>
                                        </div>
                                    </div>
                                    @if ($order->delivered_at)
                                        <p class="mt-2 text-sm text-green-700">Delivered on {{ $order->delivered_at->format('M d, Y') }}</p>
                                    @elseif ($order->cancelled_at)
                                        <p class="mt-2 text-sm text-red-700">Cancelled on {{ $order->cancelled_at->format('M d, Y') }}</p>
                                    @endif
                                </div>
                                <ul class="divide-y divide-gray-100 px-6">
                                    @foreach ($order->items as $item)
                                        <li class="flex items-center justify-between py-3 text-sm">
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $item->product_name }}</p>
                                                @if ($item->size_name || $item->color_name)
                                                    <p class="text-gray-500">{{ collect([$item->size_name, $item->color_name])->filter()->join(' / ') }}</p>
                                                @endif
                                            </div>
                                            <div class="text-right text-gray-600">
                                                <p>Qty: {{ $item->quantity }}</p>
                                                <p class="font-semibold text-gray-900">${{ number_format($item->total_price, 2) }}</p>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </article>
                        @endforeach
                    </div>
                @endif
            @endisset
        </div>
    </div>
@endsection
