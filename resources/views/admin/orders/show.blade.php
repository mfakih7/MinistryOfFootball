@extends('layouts.admin')

@section('content')
    <x-admin.page-header :title="'Order '.$order->order_number">
        <x-slot:actions>
            <a href="{{ route('admin.orders.index') }}" class="admin-btn-secondary">Back to Orders</a>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <div class="admin-card">
                <h2 class="mb-4 text-lg font-bold text-gray-900">Customer Information</h2>
                <dl class="grid gap-3 text-sm sm:grid-cols-2">
                    <div><dt class="text-gray-500">Name</dt><dd class="font-medium">{{ $order->customer_name }}</dd></div>
                    <div><dt class="text-gray-500">Phone</dt><dd class="font-medium">{{ $order->customer_phone }}</dd></div>
                    <div class="sm:col-span-2"><dt class="text-gray-500">Address</dt><dd class="font-medium">{{ $order->customer_address }}</dd></div>
                    @if ($order->customer?->city)
                        <div><dt class="text-gray-500">City</dt><dd class="font-medium">{{ $order->customer->city }}</dd></div>
                    @endif
                    @if ($order->customer_notes)
                        <div class="sm:col-span-2"><dt class="text-gray-500">Customer Notes</dt><dd>{{ $order->customer_notes }}</dd></div>
                    @endif
                </dl>
                <div class="mt-4 flex flex-wrap gap-3">
                    <a href="{{ $customerWhatsappUrl }}" target="_blank" rel="noopener" class="admin-btn-secondary inline-flex items-center gap-2">
                        <svg class="h-4 w-4 text-green-600" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.435 9.884-9.883 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        Chat with Customer
                    </a>
                    <a href="{{ $storeWhatsappUrl }}" target="_blank" rel="noopener" class="admin-btn-secondary">Open Order WhatsApp Message</a>
                </div>
            </div>

            <div class="admin-card overflow-x-auto">
                <h2 class="mb-4 text-lg font-bold text-gray-900">Order Items</h2>
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b text-left text-xs uppercase text-gray-500">
                            <th class="pb-2 pr-4">Product</th>
                            <th class="pb-2 pr-4">Size</th>
                            <th class="pb-2 pr-4">Color</th>
                            <th class="pb-2 pr-4">Qty</th>
                            <th class="pb-2 pr-4">Unit</th>
                            <th class="pb-2">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach ($order->items as $item)
                            <tr>
                                <td class="py-3 pr-4 font-medium">{{ $item->product_name }}</td>
                                <td class="py-3 pr-4">{{ $item->size_name ?? '—' }}</td>
                                <td class="py-3 pr-4">{{ $item->color_name ?? '—' }}</td>
                                <td class="py-3 pr-4">{{ $item->quantity }}</td>
                                <td class="py-3 pr-4">${{ number_format($item->unit_price, 2) }}</td>
                                <td class="py-3 font-semibold">${{ number_format($item->total_price, 2) }}</td>
                            </tr>
                            @if ($item->customization_requested)
                                <tr class="bg-blue-50/50">
                                    <td colspan="6" class="px-2 py-2 text-xs text-blue-800">
                                        <span class="font-semibold">Customization: Yes</span>
                                        &middot; Details: {{ $item->customization_details }}
                                        &middot; Fee: ${{ number_format($item->customization_fee, 2) }}
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="admin-card">
                <h2 class="mb-4 text-lg font-bold text-gray-900">WhatsApp Message</h2>
                <pre class="whitespace-pre-wrap rounded-md bg-gray-50 p-4 text-xs text-gray-700">{{ $order->whatsapp_message }}</pre>
            </div>
        </div>

        <div class="space-y-6">
            <div class="admin-card">
                <h2 class="mb-4 text-lg font-bold text-gray-900">Totals</h2>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between"><dt>Subtotal</dt><dd>${{ number_format($order->subtotal, 2) }}</dd></div>
                    @if ($order->discount_total > 0)
                        <div class="flex justify-between text-green-700"><dt>Discount</dt><dd>-${{ number_format($order->discount_total, 2) }}</dd></div>
                    @endif
                    @if ($order->customization_total > 0)
                        <div class="flex justify-between"><dt>Customization</dt><dd>${{ number_format($order->customization_total, 2) }}</dd></div>
                    @endif
                    <div class="flex justify-between"><dt>Delivery</dt><dd>${{ number_format($order->delivery_fee, 2) }}</dd></div>
                    <div class="flex justify-between border-t pt-2 font-bold"><dt>Total</dt><dd>${{ number_format($order->total, 2) }}</dd></div>
                </dl>
                <p class="mt-4 text-xs text-gray-500">
                    <x-admin.badge :type="'order-'.$order->status->value" :label="str_replace('_', ' ', $order->status->value)" />
                    &middot; {{ $order->created_at->format('M d, Y H:i') }}
                </p>
                @if ($order->inventory_deducted_at)
                    <p class="mt-2 text-xs text-indigo-600">Inventory deducted: {{ $order->inventory_deducted_at->format('M d, Y H:i') }}</p>
                @endif
                @if ($order->delivered_at)
                    <p class="mt-1 text-xs text-green-600">Delivered: {{ $order->delivered_at->format('M d, Y H:i') }}</p>
                @endif
                @if ($order->cancelled_at)
                    <p class="mt-1 text-xs text-red-600">Cancelled: {{ $order->cancelled_at->format('M d, Y H:i') }}</p>
                @endif
            </div>

            <div class="admin-card">
                <h2 class="mb-4 text-lg font-bold text-gray-900">Update Status</h2>
                <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="space-y-4">
                    @csrf @method('PATCH')
                    <div>
                        <label class="admin-label" for="status">Status</label>
                        <select id="status" name="status" class="admin-input">
                            @foreach ($statuses as $status)
                                <option value="{{ $status->value }}" @selected($order->status === $status)>{{ ucfirst(str_replace('_', ' ', $status->value)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="admin-btn-primary w-full">Update Status</button>
                </form>
            </div>

            <div class="admin-card">
                <h2 class="mb-4 text-lg font-bold text-gray-900">Admin Notes</h2>
                <form method="POST" action="{{ route('admin.orders.notes', $order) }}" class="space-y-4">
                    @csrf @method('PATCH')
                    <div>
                        <label class="admin-label" for="admin_notes">Notes</label>
                        <textarea id="admin_notes" name="admin_notes" rows="4" class="admin-input">{{ old('admin_notes', $order->admin_notes) }}</textarea>
                    </div>
                    <button type="submit" class="admin-btn-secondary w-full">Save Notes</button>
                </form>
            </div>
        </div>
    </div>
@endsection
