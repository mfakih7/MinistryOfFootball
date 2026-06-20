@extends('layouts.admin')

@section('content')
    @php
        use App\Support\StorageUrl;

        $storeLogoUrl = StorageUrl::publicUrl($settings['store_logo'] ?? null);
        $adminLogoUrl = StorageUrl::publicUrl($settings['admin_logo'] ?? null);
        $faviconUrl = StorageUrl::publicUrl($settings['favicon'] ?? null);
    @endphp

    <x-admin.page-header title="Branding" description="Upload logos, favicon, and set your store identity." />

    <form action="{{ route('admin.branding.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="admin-card space-y-4">
            <h2 class="text-lg font-bold text-gray-900">Store Identity</h2>
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="admin-label" for="store_name">Store Name</label>
                    <input type="text" id="store_name" name="store_name" value="{{ old('store_name', $settings['store_name'] ?? '') }}" class="admin-input">
                    @error('store_name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="admin-label" for="tagline">Tagline</label>
                    <input type="text" id="tagline" name="tagline" value="{{ old('tagline', $settings['tagline'] ?? '') }}" class="admin-input" placeholder="Premium Sportswear">
                    @error('tagline')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="admin-card space-y-4">
            <h2 class="text-lg font-bold text-gray-900">Logos &amp; Favicon</h2>
            <p class="text-sm text-gray-500">PNG, JPG, or WebP. Recommended: store/admin logo 400×120 px, favicon 128×128 px.</p>

            <div class="grid gap-6 lg:grid-cols-3">
                <div>
                    <label class="admin-label" for="store_logo">Store Logo (public site)</label>
                    @if ($storeLogoUrl)
                        <img src="{{ $storeLogoUrl }}" alt="Current store logo" class="mb-3 h-14 max-w-[200px] rounded border border-gray-200 bg-white object-contain p-2">
                    @endif
                    <input type="file" id="store_logo" name="store_logo" accept="image/jpeg,image/jpg,image/png,image/webp" class="admin-input">
                    @error('store_logo')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="admin-label" for="admin_logo">Admin Logo</label>
                    @if ($adminLogoUrl)
                        <img src="{{ $adminLogoUrl }}" alt="Current admin logo" class="mb-3 h-14 max-w-[200px] rounded border border-gray-200 bg-gray-900 object-contain p-2">
                    @endif
                    <input type="file" id="admin_logo" name="admin_logo" accept="image/jpeg,image/jpg,image/png,image/webp" class="admin-input">
                    @error('admin_logo')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="admin-label" for="favicon">Favicon</label>
                    @if ($faviconUrl)
                        <img src="{{ $faviconUrl }}" alt="Current favicon" class="mb-3 h-10 w-10 rounded border border-gray-200 bg-white object-contain p-1">
                    @endif
                    <input type="file" id="favicon" name="favicon" accept="image/jpeg,image/jpg,image/png,image/webp" class="admin-input">
                    @error('favicon')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="admin-btn-primary">Save Branding</button>
        </div>
    </form>
@endsection
