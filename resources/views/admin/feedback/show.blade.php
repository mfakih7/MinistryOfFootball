@extends('layouts.admin')

@section('content')
    <x-admin.page-header :title="$message->subject ?? 'Contact Message'">
        <x-slot:actions>
            <a href="{{ route('admin.feedback.index') }}" class="admin-btn-secondary">Back to Feedback</a>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <div class="admin-card">
                <h2 class="mb-4 text-lg font-bold text-gray-900">Message</h2>
                <div class="whitespace-pre-wrap text-sm text-gray-700">{{ $message->message }}</div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="admin-card">
                <h2 class="mb-4 text-lg font-bold text-gray-900">Sender</h2>
                <dl class="grid gap-3 text-sm">
                    <div>
                        <dt class="text-gray-500">Name</dt>
                        <dd class="font-medium">{{ $message->name }}</dd>
                    </div>
                    @if ($message->phone)
                        <div>
                            <dt class="text-gray-500">Phone</dt>
                            <dd class="font-medium">{{ $message->phone }}</dd>
                        </div>
                    @endif
                    @if ($message->email)
                        <div>
                            <dt class="text-gray-500">Email</dt>
                            <dd class="font-medium"><a href="mailto:{{ $message->email }}" class="text-brand-red hover:underline">{{ $message->email }}</a></dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-gray-500">Subject</dt>
                        <dd class="font-medium">{{ $message->subject ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Received</dt>
                        <dd class="font-medium">{{ $message->created_at->format('M d, Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Status</dt>
                        <dd>
                            <x-admin.badge :variant="$message->is_read ? 'default' : 'info'" :label="$message->is_read ? 'Read' : 'Unread'" />
                        </dd>
                    </div>
                </dl>
            </div>

            <div class="admin-card space-y-3">
                @if (! $message->is_read)
                    <form method="POST" action="{{ route('admin.feedback.read', $message) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="admin-btn-primary w-full">Mark as Read</button>
                    </form>
                @endif
                <x-admin.btn-delete :action="route('admin.feedback.destroy', $message)" label="Delete Message" class="w-full justify-center" />
            </div>
        </div>
    </div>
@endsection
