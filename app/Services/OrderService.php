<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(
        protected CartService $cart,
        protected WhatsAppOrderService $whatsapp
    ) {}

    public function createFromCart(array $customerData): Order
    {
        if ($this->cart->isEmpty()) {
            throw new \RuntimeException('Cart is empty.');
        }

        return DB::transaction(function () use ($customerData) {
            $customer = $this->resolveCustomer($customerData);
            $subtotal = $this->cart->subtotal();
            $deliveryFee = $this->cart->deliveryFee();
            $discountTotal = $this->cart->discountAmount();
            $customizationFee = $this->cart->customizationFee();
            $couponData = $this->cart->coupon();

            $orderItemsData = [];
            $customizationTotal = 0.0;

            foreach ($this->cart->items() as $item) {
                $submitted = $customerData['customizations'][$item['key']] ?? null;
                $requested = $item['is_customizable'] && ! empty($submitted['requested']);
                $details = $requested ? trim((string) ($submitted['details'] ?? '')) : null;
                $fee = $requested ? $customizationFee : 0.0;
                $customizationTotal += $fee;

                $orderItemsData[] = [
                    'product_id' => $item['product_id'],
                    'product_variant_id' => $item['variant_id'] ?? null,
                    'product_name' => $item['product_name'],
                    'product_sku' => $item['product_sku'] ?? null,
                    'size_name' => $item['size_name'] ?? null,
                    'color_name' => $item['color_name'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['total_price'],
                    'customization_requested' => $requested,
                    'customization_details' => $details,
                    'customization_fee' => $fee,
                ];
            }

            $total = max(0, $subtotal - $discountTotal + $deliveryFee + $customizationTotal);

            $order = Order::query()->create([
                'order_number' => $this->generateOrderNumber(),
                'customer_id' => $customer->id,
                'status' => OrderStatus::Pending,
                'order_source' => 'website',
                'subtotal' => $subtotal,
                'discount_total' => $discountTotal,
                'delivery_fee' => $deliveryFee,
                'customization_total' => $customizationTotal,
                'total' => $total,
                'customer_name' => $customerData['name'],
                'customer_phone' => $customerData['phone'],
                'customer_address' => $customerData['address'],
                'customer_notes' => $customerData['notes'] ?? null,
            ]);

            foreach ($orderItemsData as $itemData) {
                OrderItem::query()->create(['order_id' => $order->id, ...$itemData]);
            }

            $order->load(['items', 'customer']);
            $message = $this->whatsapp->buildMessage($order, $customerData['city'] ?? $customer->city);
            $order->update(['whatsapp_message' => $message]);

            if ($couponData) {
                Coupon::query()->whereKey($couponData['coupon_id'])->increment('used_count');
            }

            $this->cart->clear();

            return $order->fresh(['items', 'customer']);
        });
    }

    public function generateOrderNumber(): string
    {
        $year = now()->year;
        $prefix = "MOF-{$year}-";

        $lastOrder = Order::query()
            ->where('order_number', 'like', $prefix.'%')
            ->orderByDesc('order_number')
            ->lockForUpdate()
            ->first();

        $sequence = 1;

        if ($lastOrder) {
            $sequence = (int) substr($lastOrder->order_number, -4) + 1;
        }

        return $prefix.str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);
    }

    protected function resolveCustomer(array $data): Customer
    {
        $customer = Customer::query()->where('phone', $data['phone'])->first();

        if ($customer) {
            $customer->update([
                'name' => $data['name'],
                'address' => $data['address'],
                'city' => $data['city'] ?? null,
                'notes' => $data['notes'] ?? $customer->notes,
                'last_order_at' => now(),
                'is_whatsapp_subscribed' => true,
            ]);

            return $customer->fresh();
        }

        return Customer::query()->create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'city' => $data['city'] ?? null,
            'notes' => $data['notes'] ?? null,
            'last_order_at' => now(),
            'is_whatsapp_subscribed' => true,
        ]);
    }
}
