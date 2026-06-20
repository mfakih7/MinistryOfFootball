@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Leagues" description="Manage football leagues.">
        <x-slot:actions>
            <a href="{{ route('admin.leagues.create') }}" class="admin-btn-primary">Add League</a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.filters>
        <form method="GET" class="flex flex-wrap items-end gap-3">
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Search leagues..." class="admin-input max-w-xs">
            <button type="submit" class="admin-btn-secondary">Search</button>
        </form>
    </x-admin.filters>

    <x-admin.table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Slug</th>
                <th>Country</th>
                <th>Status</th>
                <th>Sort</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($leagues as $league)
                <tr>
                    <td class="font-semibold text-gray-900">{{ $league->name }}</td>
                    <td class="text-gray-500">{{ $league->slug }}</td>
                    <td class="text-gray-500">{{ $league->country ?? '—' }}</td>
                    <td>
                        <x-admin.badge :variant="$league->is_active ? 'success' : 'default'" :label="$league->is_active ? 'Active' : 'Inactive'" />
                    </td>
                    <td>{{ $league->sort_order }}</td>
                    <td>
                        <x-admin.actions>
                            <x-admin.btn-edit :href="route('admin.leagues.edit', $league)" />
                            <x-admin.btn-delete :action="route('admin.leagues.destroy', $league)" />
                        </x-admin.actions>
                    </td>
                </tr>
            @empty
                <tr class="admin-table-empty"><td colspan="6">No leagues found.</td></tr>
            @endforelse
        </tbody>
        <x-slot:footer>{{ $leagues->links() }}</x-slot:footer>
    </x-admin.table>
@endsection
