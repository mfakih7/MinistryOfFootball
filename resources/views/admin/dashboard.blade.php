@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Dashboard" description="Overview of your Ministry Of Football store." />

    <div class="mb-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ([
            ['label' => 'Total Orders', 'value' => $stats['total_orders'], 'color' => 'text-gray-900'],
            ['label' => 'Pending Orders', 'value' => $stats['pending_orders'], 'color' => 'text-amber-600'],
            ['label' => 'Confirmed Orders', 'value' => $stats['confirmed_orders'], 'color' => 'text-blue-600'],
            ['label' => 'Delivered Orders', 'value' => $stats['delivered_orders'], 'color' => 'text-green-600'],
            ['label' => 'Total Customers', 'value' => $stats['total_customers'], 'color' => 'text-gray-900'],
            ['label' => 'Total Revenue', 'value' => '$'.number_format($stats['revenue'], 2), 'color' => 'text-brand-red'],
        ] as $card)
            <div class="admin-card">
                <p class="text-sm font-medium text-gray-500">{{ $card['label'] }}</p>
                <p class="mt-2 text-3xl font-bold {{ $card['color'] }}">{{ $card['value'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="admin-card mb-6">
        <h2 class="mb-4 text-lg font-bold text-gray-900">Recent Orders</h2>
        @forelse ($recentOrders as $order)
            <div class="flex items-center justify-between border-b border-gray-100 py-3 last:border-0">
                <div>
                    <p class="font-medium text-gray-900">{{ $order->order_number }}</p>
                    <p class="text-xs text-gray-500">{{ $order->customer_name }} &mdash; {{ str_replace('_', ' ', $order->status->value) }}</p>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-gray-900">${{ number_format($order->total, 2) }}</p>
                    <a href="{{ route('admin.orders.show', $order) }}" class="text-xs text-brand-red hover:underline">View</a>
                </div>
            </div>
        @empty
            <p class="text-sm text-gray-500">No orders yet.</p>
        @endforelse
    </div>

    <div class="admin-card">
        <h2 class="mb-4 text-lg font-bold text-gray-900">Quick Links</h2>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.orders.index') }}" class="admin-btn-primary">View Orders</a>
            <a href="{{ route('admin.products.create') }}" class="admin-btn-secondary">Add Product</a>
            <a href="{{ route('admin.settings.edit') }}" class="admin-btn-secondary">Store Settings</a>
        </div>
    </div>
@endsection
