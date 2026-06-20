<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cart,
        protected OrderService $orders
    ) {}

    public function index(): View|RedirectResponse
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        return view('checkout.index', [
            'title' => 'Checkout',
            'metaDescription' => 'Complete your order at Ministry Of Football. No online payment — order via WhatsApp.',
            'items' => $this->cart->items(),
            'subtotal' => $this->cart->subtotal(),
            'discountAmount' => $this->cart->discountAmount(),
            'deliveryFee' => $this->cart->deliveryFee(),
            'total' => $this->cart->total(),
            'coupon' => $this->cart->coupon(),
            'currencySymbol' => $this->cart->currencySymbol(),
        ]);
    }

    public function store(CheckoutRequest $request): RedirectResponse
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $order = $this->orders->createFromCart($request->validated());

        session(['last_order_number' => $order->order_number]);

        return redirect()->route('order.success', $order->order_number);
    }
}
