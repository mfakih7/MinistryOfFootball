@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Homepage Slides" description="Manage hero carousel slides on the homepage.">
        <x-slot:actions>
            <a href="{{ route('admin.homepage-slides.create') }}" class="admin-btn-primary">Add Slide</a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Subtitle</th>
                <th>Sort</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($slides as $slide)
                @php
                    $imageUrl = $slide->image ? asset('storage/'.$slide->image) : null;
                @endphp
                <tr>
                    <td>
                        @if ($imageUrl)
                            <img src="{{ $imageUrl }}" alt="{{ $slide->title }}" class="admin-table-thumb-wide">
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </td>
                    <td class="font-semibold text-gray-900">{{ $slide->title }}</td>
                    <td class="text-gray-500">{{ $slide->subtitle ?? '—' }}</td>
                    <td>{{ $slide->sort_order }}</td>
                    <td>
                        <x-admin.badge :variant="$slide->is_active ? 'success' : 'default'" :label="$slide->is_active ? 'Active' : 'Inactive'" />
                    </td>
                    <td>
                        <x-admin.actions>
                            <x-admin.btn-edit :href="route('admin.homepage-slides.edit', $slide)" />
                            <x-admin.btn-delete :action="route('admin.homepage-slides.destroy', $slide)" />
                        </x-admin.actions>
                    </td>
                </tr>
            @empty
                <tr class="admin-table-empty"><td colspan="6">No slides found.</td></tr>
            @endforelse
        </tbody>
        <x-slot:footer>{{ $slides->links() }}</x-slot:footer>
    </x-admin.table>
@endsection
