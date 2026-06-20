<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\CartService;
use App\Services\WhatsAppOrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrderSuccessController extends Controller
{
    public function __construct(
        protected CartService $cart,
        protected WhatsAppOrderService $whatsapp
    ) {}

    public function show(string $orderNumber): View
    {
        if (session('last_order_number') !== $orderNumber) {
            abort(403);
        }

        $order = Order::query()
            ->where('order_number', $orderNumber)
            ->with('items')
            ->firstOrFail();

        return view('order.success', [
            'title' => 'Order Confirmed',
            'metaDescription' => 'Your order has been placed successfully.',
            'order' => $order,
            'whatsappUrl' => $this->whatsapp->buildUrl($order),
            'currencySymbol' => $this->cart->currencySymbol(),
        ]);
    }

    public function whatsapp(Order $order): RedirectResponse
    {
        if (session('last_order_number') !== $order->order_number) {
            abort(403);
        }

        $order->update(['whatsapp_opened_at' => now()]);
        session()->forget('last_order_number');

        return redirect()->away($this->whatsapp->buildUrl($order->load('items')));
    }
}
