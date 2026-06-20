@props(['category' => null, 'action', 'method' => 'POST'])

<form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="admin-card grid gap-4 sm:grid-cols-2">
        <div class="sm:col-span-2">
            <label class="admin-label" for="name">Name *</label>
            <input type="text" id="name" name="name" value="{{ old('name', $category?->name) }}" required class="admin-input">
        </div>
        <div class="sm:col-span-2">
            <label class="admin-label" for="slug">Slug</label>
            <input type="text" id="slug" name="slug" value="{{ old('slug', $category?->slug) }}" class="admin-input" placeholder="Auto-generated from name if empty">
        </div>
        <div class="sm:col-span-2">
            <label class="admin-label" for="description">Description</label>
            <textarea id="description" name="description" rows="4" class="admin-input">{{ old('description', $category?->description) }}</textarea>
        </div>
        <div>
            <label class="admin-label" for="image">Image</label>
            <input type="file" id="image" name="image" accept="image/*" class="admin-input">
            @if ($category?->image)
                <p class="mt-1 text-xs text-gray-500">Current image uploaded</p>
            @endif
        </div>
        <div>
            <label class="admin-label" for="sort_order">Sort Order</label>
            <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $category?->sort_order ?? 0) }}" min="0" class="admin-input">
        </div>
        <div class="sm:col-span-2">
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category?->is_active ?? true)) class="rounded border-gray-300 text-brand-red focus:ring-brand-red">
                Active
            </label>
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="admin-btn-primary">Save Category</button>
        <a href="{{ route('admin.categories.index') }}" class="admin-btn-secondary">Cancel</a>
    </div>
</form>
