@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Feedback" description="Customer contact messages and feedback." />

    <x-admin.filters>
        <form method="GET" class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Name, email, phone, subject..." class="admin-input">
            <select name="is_read" class="admin-input">
                <option value="">All messages</option>
                <option value="0" @selected(request('is_read') === '0')>Unread</option>
                <option value="1" @selected(request('is_read') === '1')>Read</option>
            </select>
            <button type="submit" class="admin-btn-secondary">Filter</button>
        </form>
    </x-admin.filters>

    <x-admin.table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Subject</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($messages as $message)
                <tr @class(['bg-blue-50/40' => ! $message->is_read])>
                    <td class="font-semibold text-gray-900">{{ $message->name }}</td>
                    <td class="text-gray-500">{{ $message->phone ?? '—' }}</td>
                    <td class="text-gray-500">{{ $message->email ?? '—' }}</td>
                    <td>{{ $message->subject ?? '—' }}</td>
                    <td>
                        <x-admin.badge :variant="$message->is_read ? 'default' : 'info'" :label="$message->is_read ? 'Read' : 'Unread'" />
                    </td>
                    <td class="text-gray-500">{{ $message->created_at->format('M d, Y H:i') }}</td>
                    <td>
                        <x-admin.actions>
                            <x-admin.btn-view :href="route('admin.feedback.show', $message)" />
                            <x-admin.btn-delete :action="route('admin.feedback.destroy', $message)" />
                        </x-admin.actions>
                    </td>
                </tr>
            @empty
                <tr class="admin-table-empty"><td colspan="7">No messages found.</td></tr>
            @endforelse
        </tbody>
        <x-slot:footer>{{ $messages->links() }}</x-slot:footer>
    </x-admin.table>
@endsection
