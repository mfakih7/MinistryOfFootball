<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    public function handleStatusChange(Order $order, OrderStatus $newStatus): ?string
    {
        $order->loadMissing('items');

        if ($newStatus === OrderStatus::Confirmed && ! $order->inventory_deducted_at) {
            return $this->deduct($order);
        }

        if ($newStatus === OrderStatus::Cancelled && $order->inventory_deducted_at) {
            $this->restore($order);

            return null;
        }

        return null;
    }

    public function deduct(Order $order): ?string
    {
        return DB::transaction(function () use ($order) {
            $warnings = [];

            foreach ($order->items as $item) {
                $variant = $this->resolveVariant($item);

                if (! $variant) {
                    continue;
                }

                if ($variant->stock_quantity < $item->quantity) {
                    $warnings[] = "{$item->product_name} ({$item->size_name}/{$item->color_name}): requested {$item->quantity}, only {$variant->stock_quantity} in stock.";
                }

                $variant->decrement('stock_quantity', $item->quantity);
            }

            $order->update(['inventory_deducted_at' => now()]);

            return $warnings ? implode(' ', $warnings) : null;
        });
    }

    public function restore(Order $order): void
    {
        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                $variant = $this->resolveVariant($item);

                if ($variant) {
                    $variant->increment('stock_quantity', $item->quantity);
                }
            }

            $order->update(['inventory_deducted_at' => null]);
        });
    }

    protected function resolveVariant(OrderItem $item): ?ProductVariant
    {
        if ($item->product_variant_id) {
            return ProductVariant::query()->find($item->product_variant_id);
        }

        if (! $item->product_id) {
            return null;
        }

        return ProductVariant::query()
            ->where('product_id', $item->product_id)
            ->when($item->size_name, fn ($q) => $q->whereHas('size', fn ($s) => $s->where('name', $item->size_name)))
            ->when($item->color_name, fn ($q) => $q->whereHas('color', fn ($c) => $c->where('name', $item->color_name)))
            ->first();
    }
}
