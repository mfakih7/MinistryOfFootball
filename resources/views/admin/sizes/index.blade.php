@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Sizes" description="Manage product sizes.">
        <x-slot:actions>
            <a href="{{ route('admin.sizes.create') }}" class="admin-btn-primary">Add Size</a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Slug</th>
                <th>Sort</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($sizes as $size)
                <tr>
                    <td class="font-semibold text-gray-900">{{ $size->name }}</td>
                    <td class="text-gray-500">{{ $size->slug }}</td>
                    <td>{{ $size->sort_order }}</td>
                    <td>
                        <x-admin.badge :variant="$size->is_active ? 'success' : 'default'" :label="$size->is_active ? 'Active' : 'Inactive'" />
                    </td>
                    <td>
                        <x-admin.actions>
                            <x-admin.btn-edit :href="route('admin.sizes.edit', $size)" />
                            <x-admin.btn-delete :action="route('admin.sizes.destroy', $size)" />
                        </x-admin.actions>
                    </td>
                </tr>
            @empty
                <tr class="admin-table-empty"><td colspan="5">No sizes found.</td></tr>
            @endforelse
        </tbody>
        <x-slot:footer>{{ $sizes->links() }}</x-slot:footer>
    </x-admin.table>
@endsection
