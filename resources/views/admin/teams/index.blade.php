@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Teams / Clubs" description="Manage football teams and clubs.">
        <x-slot:actions>
            <a href="{{ route('admin.teams.create') }}" class="admin-btn-primary">Add Team</a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.filters>
        <form method="GET" class="flex flex-wrap items-end gap-3">
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Search teams..." class="admin-input max-w-xs">
            <button type="submit" class="admin-btn-secondary">Search</button>
        </form>
    </x-admin.filters>

    <x-admin.table>
        <thead>
            <tr>
                <th>Name</th>
                <th>League</th>
                <th>Slug</th>
                <th>Country</th>
                <th>Status</th>
                <th>Sort</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($teams as $team)
                <tr>
                    <td class="font-semibold text-gray-900">{{ $team->name }}</td>
                    <td class="text-gray-500">{{ $team->league?->name ?? '—' }}</td>
                    <td class="text-gray-500">{{ $team->slug }}</td>
                    <td class="text-gray-500">{{ $team->country ?? '—' }}</td>
                    <td>
                        <x-admin.badge :variant="$team->is_active ? 'success' : 'default'" :label="$team->is_active ? 'Active' : 'Inactive'" />
                    </td>
                    <td>{{ $team->sort_order }}</td>
                    <td>
                        <x-admin.actions>
                            <x-admin.btn-edit :href="route('admin.teams.edit', $team)" />
                            <x-admin.btn-delete :action="route('admin.teams.destroy', $team)" />
                        </x-admin.actions>
                    </td>
                </tr>
            @empty
                <tr class="admin-table-empty"><td colspan="7">No teams found.</td></tr>
            @endforelse
        </tbody>
        <x-slot:footer>{{ $teams->links() }}</x-slot:footer>
    </x-admin.table>
@endsection
