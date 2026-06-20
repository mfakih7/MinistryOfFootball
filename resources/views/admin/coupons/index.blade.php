@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Coupons" description="Manage discount codes for the store.">
        <x-slot:actions>
            <a href="{{ route('admin.coupons.create') }}" class="admin-btn-primary">Add Coupon</a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Type</th>
                <th>Value</th>
                <th>Dates</th>
                <th>Usage</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($coupons as $coupon)
                <tr>
                    <td class="font-mono font-semibold text-gray-900">{{ $coupon->code }}</td>
                    <td>
                        <x-admin.badge :type="'coupon-'.$coupon->type->value" :label="$coupon->type->value" />
                    </td>
                    <td class="font-semibold">
                        @if ($coupon->type->value === 'percentage')
                            {{ number_format($coupon->value, 0) }}%
                        @else
                            ${{ number_format($coupon->value, 2) }}
                        @endif
                    </td>
                    <td class="text-gray-500">
                        @if ($coupon->starts_at || $coupon->expires_at)
                            <div>{{ $coupon->starts_at?->format('M d, Y') ?? '—' }}</div>
                            <div class="text-xs">to {{ $coupon->expires_at?->format('M d, Y') ?? '—' }}</div>
                        @else
                            —
                        @endif
                    </td>
                    <td>
                        {{ $coupon->used_count }}@if ($coupon->usage_limit) / {{ $coupon->usage_limit }}@else / ∞@endif
                    </td>
                    <td>
                        <x-admin.badge :variant="$coupon->is_active ? 'success' : 'default'" :label="$coupon->is_active ? 'Active' : 'Inactive'" />
                    </td>
                    <td>
                        <x-admin.actions>
                            <x-admin.btn-edit :href="route('admin.coupons.edit', $coupon)" />
                            <x-admin.btn-delete :action="route('admin.coupons.destroy', $coupon)" />
                        </x-admin.actions>
                    </td>
                </tr>
            @empty
                <tr class="admin-table-empty"><td colspan="7">No coupons found.</td></tr>
            @endforelse
        </tbody>
        <x-slot:footer>{{ $coupons->links() }}</x-slot:footer>
    </x-admin.table>
@endsection
