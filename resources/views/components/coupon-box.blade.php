@props(['coupon' => null, 'action' => null])

<div class="rounded-lg border border-gray-200 bg-white p-4">
    <h3 class="icon-label text-sm font-bold text-gray-900">
        <x-icons.ticket class="h-4 w-4 text-brand-red" />
        Coupon Code
    </h3>
    @if ($coupon)
        <div class="mt-3 flex items-center justify-between rounded-md bg-green-50 px-3 py-2 text-sm text-green-800">
            <span class="icon-label">
                <x-icons.check-circle class="h-4 w-4" />
                <strong>{{ $coupon['code'] }}</strong> applied
            </span>
            <form method="POST" action="{{ route('cart.coupon.remove') }}">
                @csrf @method('DELETE')
                <button type="submit" class="icon-label font-semibold text-green-900 hover:underline">
                    <x-icons.x class="h-3.5 w-3.5" />
                    Remove
                </button>
            </form>
        </div>
    @else
        <form method="POST" action="{{ $action ?? route('cart.coupon.apply') }}" class="mt-3 flex gap-2">
            @csrf
            <input type="text" name="coupon_code" value="{{ old('coupon_code') }}" placeholder="Enter code" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm uppercase focus:border-brand-red focus:outline-none focus:ring-1 focus:ring-brand-red">
            <button type="submit" class="icon-label shrink-0 rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">
                <x-icons.ticket class="h-4 w-4" />
                Apply
            </button>
        </form>
        @error('coupon_code')
            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
        @enderror
    @endif
</div>
