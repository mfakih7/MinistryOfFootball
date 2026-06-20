<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\CouponService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartCouponController extends Controller
{
    public function __construct(
        protected CartService $cart,
        protected CouponService $coupons
    ) {}

    public function apply(Request $request): RedirectResponse
    {
        $request->validate(['coupon_code' => ['required', 'string', 'max:50']]);

        if ($this->cart->isEmpty()) {
            return back()->with('error', 'Add items to your cart before applying a coupon.');
        }

        $coupon = $this->coupons->findValid($request->input('coupon_code'));
        $discount = $this->coupons->calculateDiscount($coupon, $this->cart->subtotal());

        if ($discount <= 0) {
            return back()->with('error', 'This coupon cannot be applied to your current cart.');
        }

        $this->cart->applyCoupon($coupon->code, $discount, $coupon->id);

        return back()->with('success', "Coupon {$coupon->code} applied successfully.");
    }

    public function remove(): RedirectResponse
    {
        $this->cart->removeCoupon();

        return back()->with('success', 'Coupon removed.');
    }
}
