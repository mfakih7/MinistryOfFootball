@props(['team' => null, 'action', 'method' => 'POST', 'leagues'])

<form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="admin-card grid gap-4 sm:grid-cols-2">
        <div class="sm:col-span-2">
            <label class="admin-label" for="league_id">League</label>
            <select id="league_id" name="league_id" class="admin-input">
                <option value="">— No league —</option>
                @foreach ($leagues as $league)
                    <option value="{{ $league->id }}" @selected(old('league_id', $team?->league_id) == $league->id)>{{ $league->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="sm:col-span-2">
            <label class="admin-label" for="name">Name *</label>
            <input type="text" id="name" name="name" value="{{ old('name', $team?->name) }}" required class="admin-input">
        </div>
        <div class="sm:col-span-2">
            <label class="admin-label" for="slug">Slug</label>
            <input type="text" id="slug" name="slug" value="{{ old('slug', $team?->slug) }}" class="admin-input" placeholder="Auto-generated from name if empty">
        </div>
        <div>
            <label class="admin-label" for="country">Country</label>
            <input type="text" id="country" name="country" value="{{ old('country', $team?->country) }}" class="admin-input">
        </div>
        <div>
            <label class="admin-label" for="logo">Logo</label>
            <input type="file" id="logo" name="logo" accept="image/*" class="admin-input">
            @if ($team?->logo)
                <p class="mt-1 text-xs text-gray-500">Current logo uploaded</p>
            @endif
        </div>
        <div>
            <label class="admin-label" for="sort_order">Sort Order</label>
            <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $team?->sort_order ?? 0) }}" min="0" class="admin-input">
        </div>
        <div class="sm:col-span-2">
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $team?->is_active ?? true)) class="rounded border-gray-300 text-brand-red focus:ring-brand-red">
                Active
            </label>
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="admin-btn-primary">Save Team</button>
        <a href="{{ route('admin.teams.index') }}" class="admin-btn-secondary">Cancel</a>
    </div>
</form>
