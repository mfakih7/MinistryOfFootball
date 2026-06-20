<?php

namespace App\Http\Controllers;

use App\Http\Requests\TrackOrderRequest;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TrackOrderController extends Controller
{
    public function index(): View
    {
        return view('track-order.index');
    }

    public function lookup(TrackOrderRequest $request): View|RedirectResponse
    {
        $phone = $this->normalizePhone($request->validated('phone'));
        $orderNumber = $request->validated('order_number');

        $digits = $this->digitsOnly($phone);

        $query = Order::query()
            ->with('items')
            ->where(function ($q) use ($phone, $digits) {
                $q->where('customer_phone', 'like', '%'.$phone.'%');
                if ($digits !== '') {
                    $q->orWhere('customer_phone', 'like', '%'.$digits.'%');
                }
            });

        if ($orderNumber) {
            $query->where('order_number', strtoupper(trim($orderNumber)));
        }

        $orders = $query->latest()->get();

        return view('track-order.index', [
            'orders' => $orders,
            'phone' => $request->validated('phone'),
            'order_number' => $orderNumber,
        ]);
    }

    protected function normalizePhone(string $phone): string
    {
        return preg_replace('/\s+/', '', trim($phone));
    }

    protected function digitsOnly(string $phone): string
    {
        return preg_replace('/\D/', '', $phone);
    }
}
