@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Categories" description="Manage product categories.">
        <x-slot:actions>
            <a href="{{ route('admin.categories.create') }}" class="admin-btn-primary">Add Category</a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.filters>
        <form method="GET" class="flex flex-wrap items-end gap-3">
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Search categories..." class="admin-input max-w-xs">
            <button type="submit" class="admin-btn-secondary">Search</button>
        </form>
    </x-admin.filters>

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
            @forelse ($categories as $category)
                <tr>
                    <td class="font-semibold text-gray-900">{{ $category->name }}</td>
                    <td class="text-gray-500">{{ $category->slug }}</td>
                    <td>
                        <x-admin.badge :variant="$category->is_active ? 'success' : 'default'" :label="$category->is_active ? 'Active' : 'Inactive'" />
                    </td>
                    <td>{{ $category->sort_order }}</td>
                    <td>
                        <x-admin.actions>
                            <x-admin.btn-edit :href="route('admin.categories.edit', $category)" />
                            <x-admin.btn-delete :action="route('admin.categories.destroy', $category)" />
                        </x-admin.actions>
                    </td>
                </tr>
            @empty
                <tr class="admin-table-empty"><td colspan="5">No categories found.</td></tr>
            @endforelse
        </tbody>
        <x-slot:footer>{{ $categories->links() }}</x-slot:footer>
    </x-admin.table>
@endsection
