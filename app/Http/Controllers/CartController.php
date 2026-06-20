<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartAddRequest;
use App\Http\Requests\CartUpdateRequest;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cart
    ) {}

    public function index(): View
    {
        return view('cart.index', [
            'title' => 'Shopping Cart',
            'metaDescription' => 'Review your cart and proceed to checkout at Ministry Of Football.',
            'items' => $this->cart->items(),
            'subtotal' => $this->cart->subtotal(),
            'discountAmount' => $this->cart->discountAmount(),
            'deliveryFee' => $this->cart->deliveryFee(),
            'total' => $this->cart->total(),
            'coupon' => $this->cart->coupon(),
            'currencySymbol' => $this->cart->currencySymbol(),
        ]);
    }

    public function add(CartAddRequest $request): RedirectResponse
    {
        $this->cart->add(
            (int) $request->input('product_id'),
            $request->input('variant_id') ? (int) $request->input('variant_id') : null,
            (int) $request->input('quantity', 1)
        );

        return redirect()
            ->back()
            ->with('success', 'Product added to cart.');
    }

    public function update(CartUpdateRequest $request): RedirectResponse
    {
        $this->cart->update(
            $request->input('key'),
            (int) $request->input('quantity')
        );

        return redirect()->route('cart')->with('success', 'Cart updated.');
    }

    public function remove(Request $request): RedirectResponse
    {
        $request->validate(['key' => ['required', 'string']]);

        $this->cart->remove($request->input('key'));

        return redirect()->route('cart')->with('success', 'Item removed from cart.');
    }

    public function clear(): RedirectResponse
    {
        $this->cart->clear();

        return redirect()->route('cart')->with('success', 'Cart cleared.');
    }
}
