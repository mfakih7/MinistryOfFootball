@props(['slide' => null, 'action', 'method' => 'POST'])

@php
    $imageUrl = $slide?->image ? asset('storage/'.$slide->image) : null;
@endphp

<form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="admin-card grid gap-4 sm:grid-cols-2">
        <div class="sm:col-span-2">
            <label class="admin-label" for="title">Title *</label>
            <input type="text" id="title" name="title" value="{{ old('title', $slide?->title) }}" required class="admin-input">
        </div>
        <div class="sm:col-span-2">
            <label class="admin-label" for="subtitle">Subtitle</label>
            <input type="text" id="subtitle" name="subtitle" value="{{ old('subtitle', $slide?->subtitle) }}" class="admin-input" placeholder="e.g. New Season Collection">
            <p class="mt-1 text-xs text-gray-500">Short supporting text shown above the headline on the homepage hero.</p>
        </div>
        <div class="sm:col-span-2">
            <label class="admin-label" for="image">Hero Image {{ $slide ? '' : '*' }}</label>
            <input type="file" id="image" name="image" accept="image/jpeg,image/jpg,image/png,image/webp" @if (! $slide) required @endif class="admin-input">
            <p class="mt-1 text-xs text-gray-500">Recommended size: <strong>1920 × 650 px</strong> full-width banner. Image fills the entire hero as a background (cover). Stored as <code>images/homepage-slides/*.webp</code>.</p>
            @if ($imageUrl)
                <img src="{{ $imageUrl }}" alt="{{ $slide->title }}" class="mt-3 h-20 w-full max-w-md rounded border border-gray-200 object-cover bg-gray-50">
                <p class="mt-1 text-xs text-gray-500">Leave empty to keep the current image.</p>
            @endif
        </div>
        <div>
            <label class="admin-label" for="button_text">Button Text</label>
            <input type="text" id="button_text" name="button_text" value="{{ old('button_text', $slide?->button_text) }}" class="admin-input">
        </div>
        <div>
            <label class="admin-label" for="button_url">Button URL</label>
            <input type="text" id="button_url" name="button_url" value="{{ old('button_url', $slide?->button_url) }}" class="admin-input" placeholder="/shop">
        </div>
        <div>
            <label class="admin-label" for="sort_order">Sort Order</label>
            <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $slide?->sort_order ?? 0) }}" min="0" class="admin-input">
        </div>
        <div class="sm:col-span-2">
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $slide?->is_active ?? true)) class="rounded border-gray-300 text-brand-red focus:ring-brand-red">
                Active
            </label>
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="admin-btn-primary">Save Slide</button>
        <a href="{{ route('admin.homepage-slides.index') }}" class="admin-btn-secondary">Cancel</a>
    </div>
</form>
