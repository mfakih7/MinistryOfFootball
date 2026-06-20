<?php

namespace App\Services;

use App\Enums\CouponType;
use App\Models\Coupon;
use Illuminate\Validation\ValidationException;

class CouponService
{
    public function findValid(string $code): Coupon
    {
        $coupon = Coupon::query()
            ->where('code', strtoupper(trim($code)))
            ->first();

        if (! $coupon) {
            throw ValidationException::withMessages(['coupon_code' => 'Invalid coupon code.']);
        }

        if (! $coupon->is_active) {
            throw ValidationException::withMessages(['coupon_code' => 'This coupon is not active.']);
        }

        if ($coupon->starts_at && $coupon->starts_at->isFuture()) {
            throw ValidationException::withMessages(['coupon_code' => 'This coupon is not valid yet.']);
        }

        if ($coupon->expires_at && $coupon->expires_at->isPast()) {
            throw ValidationException::withMessages(['coupon_code' => 'This coupon has expired.']);
        }

        if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
            throw ValidationException::withMessages(['coupon_code' => 'This coupon has reached its usage limit.']);
        }

        return $coupon;
    }

    public function calculateDiscount(Coupon $coupon, float $subtotal): float
    {
        if ($subtotal <= 0) {
            return 0.0;
        }

        $discount = match ($coupon->type) {
            CouponType::Fixed => (float) $coupon->value,
            CouponType::Percentage => round($subtotal * ((float) $coupon->value / 100), 2),
        };

        return min($discount, $subtotal);
    }
}
