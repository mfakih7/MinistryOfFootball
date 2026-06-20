@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Admin Profile" description="Update your account name, email, and password." />

    <form action="{{ route('admin.profile.update') }}" method="POST" class="max-w-2xl space-y-6">
        @csrf
        @method('PUT')

        <div class="admin-card space-y-4">
            <h2 class="text-lg font-bold text-gray-900">Account Details</h2>
            <div>
                <label class="admin-label" for="name">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="admin-input">
                @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="admin-label" for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="admin-input">
                @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="admin-card space-y-4">
            <h2 class="text-lg font-bold text-gray-900">Change Password</h2>
            <p class="text-sm text-gray-500">Leave blank to keep your current password.</p>
            <div>
                <label class="admin-label" for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password" autocomplete="current-password" class="admin-input">
                @error('current_password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="admin-label" for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" autocomplete="new-password" class="admin-input">
                @error('new_password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="admin-label" for="confirm_new_password">Confirm New Password</label>
                <input type="password" id="confirm_new_password" name="confirm_new_password" autocomplete="new-password" class="admin-input">
                @error('confirm_new_password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="admin-btn-primary">Save Profile</button>
        </div>
    </form>
@endsection
