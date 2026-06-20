@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Reports & Analytics" description="Store performance overview with date filters." />

    <form method="GET" class="admin-card mb-6">
        <div class="grid gap-4 sm:grid-cols-3">
            <div>
                <label class="admin-label" for="date_from">From</label>
                <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}" class="admin-input">
            </div>
            <div>
                <label class="admin-label" for="date_to">To</label>
                <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}" class="admin-input">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="admin-btn-primary">Apply</button>
                <a href="{{ route('admin.reports.index') }}" class="admin-btn-secondary">Reset</a>
            </div>
        </div>
    </form>

    <div class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ([
            ['Total Revenue', '$'.number_format($summary['total_revenue'], 2), 'text-brand-red'],
            ['Total Orders', number_format($summary['total_orders']), 'text-gray-900'],
            ['Pending Orders', number_format($summary['pending_orders']), 'text-amber-600'],
            ['Delivered Orders', number_format($summary['delivered_orders']), 'text-green-600'],
            ['Cancelled Orders', number_format($summary['cancelled_orders']), 'text-red-600'],
            ['Total Customers', number_format($summary['total_customers']), 'text-gray-900'],
            ['Average Order Value', '$'.number_format($summary['average_order_value'], 2), 'text-gray-900'],
            ['Coupon Discounts', '$'.number_format($summary['coupon_discounts_total'], 2), 'text-indigo-600'],
        ] as [$label, $value, $color])
            <div class="admin-card">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">{{ $label }}</p>
                <p class="mt-2 text-2xl font-bold {{ $color }}">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    <div class="mb-6 grid gap-6 lg:grid-cols-2">
        <div class="admin-card">
            <h2 class="mb-4 text-lg font-bold text-gray-900">Sales (Last 30 Days)</h2>
            <canvas id="salesChart" height="220"></canvas>
        </div>
        <div class="admin-card">
            <h2 class="mb-4 text-lg font-bold text-gray-900">Orders by Status</h2>
            <canvas id="statusChart" height="220"></canvas>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="admin-card overflow-x-auto">
            <h2 class="mb-4 text-lg font-bold text-gray-900">Top Selling Products</h2>
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b text-left text-xs uppercase text-gray-500">
                        <th class="pb-2 pr-4">Product</th>
                        <th class="pb-2 pr-4">Qty</th>
                        <th class="pb-2">Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($topProducts as $row)
                        <tr>
                            <td class="py-2 pr-4">{{ $row->product_name }}</td>
                            <td class="py-2 pr-4">{{ $row->total_qty }}</td>
                            <td class="py-2 font-semibold">${{ number_format($row->total_revenue, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="py-6 text-center text-gray-500">No sales data yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="admin-card overflow-x-auto">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-900">Low Stock Variants</h2>
                <p class="text-sm text-gray-500">New customers this month: <strong>{{ $newCustomers }}</strong></p>
            </div>
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b text-left text-xs uppercase text-gray-500">
                        <th class="pb-2 pr-4">Product</th>
                        <th class="pb-2 pr-4">Variant</th>
                        <th class="pb-2">Stock</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($lowStock as $variant)
                        <tr>
                            <td class="py-2 pr-4">{{ $variant->product?->name ?? '—' }}</td>
                            <td class="py-2 pr-4">{{ collect([$variant->size?->name, $variant->color?->name])->filter()->join(' / ') ?: '—' }}</td>
                            <td class="py-2">
                                <x-admin.badge type="stock-{{ $variant->stock_quantity <= 0 ? 'out_of_stock' : ($variant->stock_quantity <= 5 ? 'limited_stock' : 'in_stock') }}" :label="(string) $variant->stock_quantity" />
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="py-6 text-center text-gray-500">All variants are well stocked.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        @php
            $statusChartLabels = $ordersByStatus->map(function ($row) {
                $status = $row->status instanceof \BackedEnum ? $row->status->value : $row->status;

                return ucfirst(str_replace('_', ' ', $status));
            })->values();
        @endphp
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
        <script>
            const salesLabels = @json($salesByDay->pluck('day'));
            const salesRevenue = @json($salesByDay->pluck('revenue'));
            const statusLabels = @json($statusChartLabels);
            const statusCounts = @json($ordersByStatus->pluck('count'));

            new Chart(document.getElementById('salesChart'), {
                type: 'line',
                data: {
                    labels: salesLabels,
                    datasets: [{
                        label: 'Revenue ($)',
                        data: salesRevenue,
                        borderColor: '#dc2626',
                        backgroundColor: 'rgba(220, 38, 38, 0.1)',
                        fill: true,
                        tension: 0.3,
                    }],
                },
                options: { responsive: true, scales: { y: { beginAtZero: true } } },
            });

            new Chart(document.getElementById('statusChart'), {
                type: 'doughnut',
                data: {
                    labels: statusLabels,
                    datasets: [{
                        data: statusCounts,
                        backgroundColor: ['#f59e0b', '#3b82f6', '#6366f1', '#a855f7', '#22c55e', '#ef4444'],
                    }],
                },
                options: { responsive: true },
            });
        </script>
    @endpush
@endsection
