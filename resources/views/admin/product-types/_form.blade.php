@props(['productType' => null, 'action', 'method' => 'POST'])

<form action="{{ $action }}" method="POST" class="space-y-6">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="admin-card grid gap-4 sm:grid-cols-2">
        <div class="sm:col-span-2">
            <label class="admin-label" for="name">Name *</label>
            <input type="text" id="name" name="name" value="{{ old('name', $productType?->name) }}" required class="admin-input">
        </div>
        <div class="sm:col-span-2">
            <label class="admin-label" for="slug">Slug</label>
            <input type="text" id="slug" name="slug" value="{{ old('slug', $productType?->slug) }}" class="admin-input" placeholder="Auto-generated from name if empty">
        </div>
        <div>
            <label class="admin-label" for="sort_order">Sort Order</label>
            <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $productType?->sort_order ?? 0) }}" min="0" class="admin-input">
        </div>
        <div class="sm:col-span-2">
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $productType?->is_active ?? true)) class="rounded border-gray-300 text-brand-red focus:ring-brand-red">
                Active
            </label>
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="admin-btn-primary">Save Product Type</button>
        <a href="{{ route('admin.product-types.index') }}" class="admin-btn-secondary">Cancel</a>
    </div>
</form>
