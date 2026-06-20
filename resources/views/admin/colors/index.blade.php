@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Colors" description="Manage product colors.">
        <x-slot:actions>
            <a href="{{ route('admin.colors.create') }}" class="admin-btn-primary">Add Color</a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.table>
        <thead>
            <tr>
                <th>Color</th>
                <th>Name</th>
                <th>Slug</th>
                <th>Hex</th>
                <th>Sort</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($colors as $color)
                <tr>
                    <td>
                        <span class="inline-block h-8 w-8 rounded-full border border-gray-200 shadow-sm" style="background-color: {{ $color->hex_code ?? '#cccccc' }}" title="{{ $color->hex_code }}"></span>
                    </td>
                    <td class="font-semibold text-gray-900">{{ $color->name }}</td>
                    <td class="text-gray-500">{{ $color->slug }}</td>
                    <td class="font-mono text-xs text-gray-500">{{ $color->hex_code ?? '—' }}</td>
                    <td>{{ $color->sort_order }}</td>
                    <td>
                        <x-admin.badge :variant="$color->is_active ? 'success' : 'default'" :label="$color->is_active ? 'Active' : 'Inactive'" />
                    </td>
                    <td>
                        <x-admin.actions>
                            <x-admin.btn-edit :href="route('admin.colors.edit', $color)" />
                            <x-admin.btn-delete :action="route('admin.colors.destroy', $color)" />
                        </x-admin.actions>
                    </td>
                </tr>
            @empty
                <tr class="admin-table-empty"><td colspan="7">No colors found.</td></tr>
            @endforelse
        </tbody>
        <x-slot:footer>{{ $colors->links() }}</x-slot:footer>
    </x-admin.table>
@endsection
