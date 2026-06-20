@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Orders" description="Manage customer orders placed via the website." />

    <x-admin.filters>
        <form method="GET" class="grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Order #, phone, name..." class="admin-input">
            <select name="status" class="admin-input">
                <option value="">All statuses</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}" @selected(request('status') === $status->value)>{{ ucfirst(str_replace('_', ' ', $status->value)) }}</option>
                @endforeach
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="admin-input">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="admin-input">
            <button type="submit" class="admin-btn-secondary">Filter</button>
        </form>
    </x-admin.filters>

    <x-admin.table>
        <thead>
            <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Phone</th>
                <th>Total</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td class="font-semibold text-gray-900">{{ $order->order_number }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td class="text-gray-500">{{ $order->customer_phone }}</td>
                    <td class="font-semibold text-gray-900">${{ number_format($order->total, 2) }}</td>
                    <td>
                        <x-admin.badge :type="'order-'.$order->status->value" :label="str_replace('_', ' ', $order->status->value)" />
                    </td>
                    <td class="text-gray-500">{{ $order->created_at->format('M d, Y H:i') }}</td>
                    <td>
                        <x-admin.actions>
                            <x-admin.btn-view :href="route('admin.orders.show', $order)" />
                        </x-admin.actions>
                    </td>
                </tr>
            @empty
                <tr class="admin-table-empty"><td colspan="7">No orders found.</td></tr>
            @endforelse
        </tbody>
        <x-slot:footer>{{ $orders->links() }}</x-slot:footer>
    </x-admin.table>
@endsection
