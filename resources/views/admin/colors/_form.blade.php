@props(['color' => null, 'action', 'method' => 'POST'])

@php
    $hexValue = old('hex_code', $color?->hex_code ?? '#000000');
@endphp

<form action="{{ $action }}" method="POST" class="space-y-6">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="admin-card grid gap-4 sm:grid-cols-2">
        <div class="sm:col-span-2">
            <label class="admin-label" for="name">Name *</label>
            <input type="text" id="name" name="name" value="{{ old('name', $color?->name) }}" required class="admin-input">
        </div>
        <div class="sm:col-span-2">
            <label class="admin-label" for="slug">Slug</label>
            <input type="text" id="slug" name="slug" value="{{ old('slug', $color?->slug) }}" class="admin-input" placeholder="Auto-generated from name if empty">
        </div>
        <div>
            <label class="admin-label" for="hex_code">Hex Code *</label>
            <div class="flex items-center gap-3">
                <input type="color" id="hex_code_picker" value="{{ $hexValue }}" class="h-10 w-14 cursor-pointer rounded border border-gray-300">
                <input type="text" id="hex_code" name="hex_code" value="{{ $hexValue }}" required pattern="^#[0-9A-Fa-f]{6}$" class="admin-input font-mono uppercase" placeholder="#000000">
            </div>
        </div>
        <div>
            <label class="admin-label" for="sort_order">Sort Order</label>
            <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $color?->sort_order ?? 0) }}" min="0" class="admin-input">
        </div>
        <div class="sm:col-span-2">
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $color?->is_active ?? true)) class="rounded border-gray-300 text-brand-red focus:ring-brand-red">
                Active
            </label>
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="admin-btn-primary">Save Color</button>
        <a href="{{ route('admin.colors.index') }}" class="admin-btn-secondary">Cancel</a>
    </div>
</form>

@push('scripts')
<script>
    const picker = document.getElementById('hex_code_picker');
    const hexInput = document.getElementById('hex_code');
    if (picker && hexInput) {
        picker.addEventListener('input', () => { hexInput.value = picker.value; });
        hexInput.addEventListener('input', () => {
            if (/^#[0-9A-Fa-f]{6}$/.test(hexInput.value)) picker.value = hexInput.value;
        });
    }
</script>
@endpush
