<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateOrderNotesRequest;
use App\Http\Requests\Admin\UpdateOrderStatusRequest;
use App\Models\Order;
use App\Services\InventoryService;
use App\Services\WhatsAppOrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(
        protected WhatsAppOrderService $whatsapp,
        protected InventoryService $inventory
    ) {}

    public function index(Request $request): View
    {
        $orders = Order::query()
            ->with('customer')
            ->when($request->status, fn ($q, $status) => $q->where('status', $status))
            ->when($request->search, function ($q, $search) {
                $q->where(function ($query) use ($search) {
                    $query->where('order_number', 'like', "%{$search}%")
                        ->orWhere('customer_phone', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%");
                });
            })
            ->when($request->date_from, fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
            ->when($request->date_to, fn ($q, $date) => $q->whereDate('created_at', '<=', $date))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $statuses = OrderStatus::cases();

        return view('admin.orders.index', compact('orders', 'statuses'));
    }

    public function show(Order $order): View
    {
        $order->load(['items', 'customer']);
        $statuses = OrderStatus::cases();
        $customerWhatsappUrl = $this->whatsapp->buildCustomerChatUrl(
            $order->customer_phone,
            "Hello {$order->customer_name}, regarding your order {$order->order_number}..."
        );
        $storeWhatsappUrl = $this->whatsapp->buildUrl($order);

        return view('admin.orders.show', compact('order', 'statuses', 'customerWhatsappUrl', 'storeWhatsappUrl'));
    }

    public function updateStatus(UpdateOrderStatusRequest $request, Order $order): RedirectResponse
    {
        $newStatus = OrderStatus::from($request->validated('status'));

        $updates = ['status' => $newStatus];

        if ($newStatus === OrderStatus::Delivered && ! $order->delivered_at) {
            $updates['delivered_at'] = now();
        }

        if ($newStatus === OrderStatus::Cancelled && ! $order->cancelled_at) {
            $updates['cancelled_at'] = now();
        }

        $order->update($updates);

        $inventoryWarning = $this->inventory->handleStatusChange($order->fresh(), $newStatus);

        $message = 'Order status updated.';
        if ($inventoryWarning) {
            $message .= ' Inventory warning: '.$inventoryWarning;
        }

        return back()->with('success', $message);
    }

    public function updateNotes(UpdateOrderNotesRequest $request, Order $order): RedirectResponse
    {
        $order->update([
            'admin_notes' => $request->validated('admin_notes'),
        ]);

        return back()->with('success', 'Admin notes saved.');
    }
}
