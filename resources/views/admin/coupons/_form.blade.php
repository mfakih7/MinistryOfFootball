@props(['coupon' => null, 'action', 'method' => 'POST'])

@php
    $type = old('type', $coupon?->type?->value ?? 'percentage');
@endphp

<form action="{{ $action }}" method="POST" class="space-y-6">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="admin-card grid gap-4 sm:grid-cols-2">
        <div>
            <label class="admin-label" for="code">Code *</label>
            <input type="text" id="code" name="code" value="{{ old('code', $coupon?->code) }}" required maxlength="50" class="admin-input font-mono uppercase" placeholder="SAVE10">
        </div>
        <div>
            <label class="admin-label" for="type">Type *</label>
            <select id="type" name="type" required class="admin-input">
                <option value="fixed" @selected($type === 'fixed')>Fixed amount</option>
                <option value="percentage" @selected($type === 'percentage')>Percentage</option>
            </select>
        </div>
        <div>
            <label class="admin-label" for="value">Value *</label>
            <input type="number" id="value" name="value" value="{{ old('value', $coupon?->value) }}" required min="0.01" step="0.01" class="admin-input">
            <p class="mt-1 text-xs text-gray-500">Dollar amount for fixed, or percentage (e.g. 10 for 10%).</p>
        </div>
        @if ($coupon)
            <div>
                <label class="admin-label" for="used_count">Times Used</label>
                <input type="number" id="used_count" name="used_count" value="{{ $coupon->used_count }}" readonly class="admin-input bg-gray-50">
            </div>
        @endif
        <div>
            <label class="admin-label" for="starts_at">Starts At</label>
            <input type="datetime-local" id="starts_at" name="starts_at" value="{{ old('starts_at', $coupon?->starts_at?->format('Y-m-d\TH:i')) }}" class="admin-input">
        </div>
        <div>
            <label class="admin-label" for="expires_at">Expires At</label>
            <input type="datetime-local" id="expires_at" name="expires_at" value="{{ old('expires_at', $coupon?->expires_at?->format('Y-m-d\TH:i')) }}" class="admin-input">
        </div>
        <div>
            <label class="admin-label" for="usage_limit">Usage Limit</label>
            <input type="number" id="usage_limit" name="usage_limit" value="{{ old('usage_limit', $coupon?->usage_limit) }}" min="1" class="admin-input" placeholder="Unlimited if empty">
        </div>
        <div class="sm:col-span-2">
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $coupon?->is_active ?? true)) class="rounded border-gray-300 text-brand-red focus:ring-brand-red">
                Active
            </label>
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="admin-btn-primary">Save Coupon</button>
        <a href="{{ route('admin.coupons.index') }}" class="admin-btn-secondary">Cancel</a>
    </div>
</form>
