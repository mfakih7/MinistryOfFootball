@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Product Types" description="Manage product types (e.g. Home Kit, Away Kit).">
        <x-slot:actions>
            <a href="{{ route('admin.product-types.create') }}" class="admin-btn-primary">Add Product Type</a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Slug</th>
                <th>Status</th>
                <th>Sort</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($productTypes as $productType)
                <tr>
                    <td class="font-semibold text-gray-900">{{ $productType->name }}</td>
                    <td class="text-gray-500">{{ $productType->slug }}</td>
                    <td>
                        <x-admin.badge :variant="$productType->is_active ? 'success' : 'default'" :label="$productType->is_active ? 'Active' : 'Inactive'" />
                    </td>
                    <td>{{ $productType->sort_order }}</td>
                    <td>
                        <x-admin.actions>
                            <x-admin.btn-edit :href="route('admin.product-types.edit', $productType)" />
                            <x-admin.btn-delete :action="route('admin.product-types.destroy', $productType)" />
                        </x-admin.actions>
                    </td>
                </tr>
            @empty
                <tr class="admin-table-empty"><td colspan="5">No product types found.</td></tr>
            @endforelse
        </tbody>
        <x-slot:footer>{{ $productTypes->links() }}</x-slot:footer>
    </x-admin.table>
@endsection
