@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Products" description="Manage your product catalog.">
        <x-slot:actions>
            <a href="{{ route('admin.products.create') }}" class="admin-btn-primary">Add Product</a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.filters>
        <form method="GET" class="space-y-3">
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Search name or SKU..." class="admin-input">
                <select name="category_id" class="admin-input">
                    <option value="">All Categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
                <select name="team_id" class="admin-input">
                    <option value="">All Teams</option>
                    @foreach ($teams as $team)
                        <option value="{{ $team->id }}" @selected(request('team_id') == $team->id)>{{ $team->name }}</option>
                    @endforeach
                </select>
                <select name="is_active" class="admin-input">
                    <option value="">All Statuses</option>
                    <option value="1" @selected(request('is_active') === '1')>Active</option>
                    <option value="0" @selected(request('is_active') === '0')>Inactive</option>
                </select>
            </div>
            <div class="flex flex-wrap items-center gap-4">
                <label class="flex items-center gap-2 text-sm text-gray-700">
                    <input type="checkbox" name="is_featured" value="1" @checked(request()->boolean('is_featured')) class="rounded border-gray-300 text-brand-red focus:ring-brand-red">
                    Featured
                </label>
                <label class="flex items-center gap-2 text-sm text-gray-700">
                    <input type="checkbox" name="is_new_arrival" value="1" @checked(request()->boolean('is_new_arrival')) class="rounded border-gray-300 text-brand-red focus:ring-brand-red">
                    New Arrival
                </label>
                <label class="flex items-center gap-2 text-sm text-gray-700">
                    <input type="checkbox" name="is_best_seller" value="1" @checked(request()->boolean('is_best_seller')) class="rounded border-gray-300 text-brand-red focus:ring-brand-red">
                    Best Seller
                </label>
                <button type="submit" class="admin-btn-secondary">Filter</button>
                @if (request()->hasAny(['search', 'category_id', 'team_id', 'is_active', 'is_featured', 'is_new_arrival', 'is_best_seller']))
                    <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-500 hover:text-brand-red">Clear</a>
                @endif
            </div>
        </form>
    </x-admin.filters>

    <x-admin.table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>SKU</th>
                <th>Price</th>
                <th>Sale</th>
                <th>Stock</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                @php
                    $stockStatus = $product->stock_status->value ?? $product->stock_status;
                    $stockLabel = match ($stockStatus) {
                        'in_stock' => 'In Stock',
                        'out_of_stock' => 'Out of Stock',
                        'limited_stock' => 'Limited Stock',
                        default => ucfirst(str_replace('_', ' ', $stockStatus)),
                    };
                @endphp
                <tr>
                    <td>
                        <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}" class="admin-table-thumb">
                    </td>
                    <td>
                        <p class="font-semibold text-gray-900">{{ $product->name }}</p>
                        <div class="mt-1 flex flex-wrap gap-1">
                            @if ($product->is_featured)
                                <x-admin.badge variant="info" label="Featured" />
                            @endif
                            @if ($product->is_new_arrival)
                                <x-admin.badge variant="info" label="New" />
                            @endif
                            @if ($product->is_best_seller)
                                <x-admin.badge variant="info" label="Best Seller" />
                            @endif
                            @if ($product->sale_price)
                                <x-admin.badge variant="danger" label="Sale" />
                            @endif
                        </div>
                    </td>
                    <td class="font-mono text-xs text-gray-500">{{ $product->sku ?? '—' }}</td>
                    <td class="font-medium">${{ number_format((float) $product->price, 2) }}</td>
                    <td>
                        @if ($product->sale_price)
                            <span class="font-semibold text-brand-red">${{ number_format((float) $product->sale_price, 2) }}</span>
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </td>
                    <td>
                        <x-admin.badge :type="'stock-'.$stockStatus" :label="$stockLabel" />
                    </td>
                    <td>
                        <x-admin.badge :variant="$product->is_active ? 'success' : 'default'" :label="$product->is_active ? 'Active' : 'Inactive'" />
                    </td>
                    <td>
                        <x-admin.actions>
                            <x-admin.btn-edit :href="route('admin.products.edit', $product)" />
                            <x-admin.btn-delete :action="route('admin.products.destroy', $product)" />
                        </x-admin.actions>
                    </td>
                </tr>
            @empty
                <tr class="admin-table-empty"><td colspan="8">No products found.</td></tr>
            @endforelse
        </tbody>
        <x-slot:footer>{{ $products->links() }}</x-slot:footer>
    </x-admin.table>
@endsection
