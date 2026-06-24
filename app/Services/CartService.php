<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Setting;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class CartService
{
    protected const SESSION_KEY = 'cart';

    protected const COUPON_KEY = 'cart_coupon';

    public function items(): Collection
    {
        return collect(session(self::SESSION_KEY, []))
            ->map(fn (array $item) => $this->normalizeItem($item));
    }

    public function count(): int
    {
        return (int) $this->items()->sum('quantity');
    }

    public function subtotal(): float
    {
        return (float) $this->items()->sum('total_price');
    }

    public function deliveryFee(): float
    {
        $subtotal = $this->subtotal();
        $threshold = (float) Setting::getValue('free_shipping_threshold', 0);
        $fee = (float) Setting::getValue('delivery_fee', 0);

        if ($threshold > 0 && $subtotal >= $threshold) {
            return 0.0;
        }

        return $fee;
    }

    public function discountAmount(): float
    {
        return (float) ($this->coupon()['discount_amount'] ?? 0);
    }

    public function customizationFee(): float
    {
        return (float) Setting::getValue('customization_fee', 0);
    }

    public function coupon(): ?array
    {
        return session(self::COUPON_KEY);
    }

    public function applyCoupon(string $code, float $discountAmount, int $couponId): void
    {
        session([self::COUPON_KEY => [
            'code' => strtoupper($code),
            'coupon_id' => $couponId,
            'discount_amount' => round($discountAmount, 2),
        ]]);
    }

    public function removeCoupon(): void
    {
        session()->forget(self::COUPON_KEY);
    }

    public function syncCouponDiscount(CouponService $couponService): void
    {
        $couponData = $this->coupon();

        if (! $couponData) {
            return;
        }

        try {
            $coupon = $couponService->findValid($couponData['code']);
            $discount = $couponService->calculateDiscount($coupon, $this->subtotal());
            $this->applyCoupon($coupon->code, $discount, $coupon->id);
        } catch (ValidationException) {
            $this->removeCoupon();
        }
    }

    public function total(): float
    {
        return max(0, $this->subtotal() - $this->discountAmount() + $this->deliveryFee());
    }

    public function isEmpty(): bool
    {
        return $this->items()->isEmpty();
    }

    public function currencySymbol(): string
    {
        return (string) Setting::getValue('currency_symbol', '$');
    }

    public function formatMoney(float $amount): string
    {
        return $this->currencySymbol().number_format($amount, 2);
    }

    public function add(int $productId, ?int $variantId, int $quantity = 1): void
    {
        if ($quantity < 1) {
            throw ValidationException::withMessages(['quantity' => 'Quantity must be at least 1.']);
        }

        $product = Product::query()->active()->find($productId);

        if (! $product) {
            throw ValidationException::withMessages(['product_id' => 'Product is not available.']);
        }

        $variant = null;

        if ($variantId) {
            $variant = ProductVariant::query()
                ->where('product_id', $product->id)
                ->active()
                ->with(['size', 'color'])
                ->find($variantId);

            if (! $variant) {
                throw ValidationException::withMessages(['variant_id' => 'Selected variant is not available.']);
            }
        } elseif ($product->variants()->active()->exists()) {
            throw ValidationException::withMessages(['variant_id' => 'Please select a size and color.']);
        }

        $unitPrice = (float) $product->display_price + (float) ($variant?->price_adjustment ?? 0);
        $key = $this->makeKey($product->id, $variant?->id);
        $items = collect(session(self::SESSION_KEY, []));
        $existing = $items->get($key);

        if ($existing) {
            $quantity = (int) ($existing['quantity'] ?? 0) + $quantity;
        }

        $items->put($key, $this->normalizeItem([
            'key' => $key,
            'product_id' => $product->id,
            'variant_id' => $variant?->id,
            'product_name' => $product->name,
            'product_slug' => $product->slug,
            'product_sku' => $product->sku,
            'thumbnail_url' => $product->thumbnail_url,
            'is_customizable' => (bool) $product->is_customizable,
            'size_name' => $variant?->size?->name,
            'color_name' => $variant?->color?->name,
            'unit_price' => $unitPrice,
            'quantity' => $quantity,
            'total_price' => round($unitPrice * $quantity, 2),
        ]));

        session([self::SESSION_KEY => $items->all()]);
        $this->syncCouponDiscount(app(CouponService::class));
    }

    public function update(string $key, int $quantity): void
    {
        $items = collect(session(self::SESSION_KEY, []));

        if (! $items->has($key)) {
            throw ValidationException::withMessages(['cart' => 'Cart item not found.']);
        }

        if ($quantity < 1) {
            $this->remove($key);

            return;
        }

        $item = $this->normalizeItem($items->get($key));
        $item['quantity'] = $quantity;
        $item['total_price'] = round($item['unit_price'] * $quantity, 2);
        $items->put($key, $item);

        session([self::SESSION_KEY => $items->all()]);
        $this->syncCouponDiscount(app(CouponService::class));
    }

    public function remove(string $key): void
    {
        $items = collect(session(self::SESSION_KEY, []));
        $items->forget($key);
        session([self::SESSION_KEY => $items->all()]);
        $this->syncCouponDiscount(app(CouponService::class));
    }

    public function clear(): void
    {
        session()->forget([self::SESSION_KEY, self::COUPON_KEY]);
    }

    protected function makeKey(int $productId, ?int $variantId): string
    {
        return $productId.'_'.($variantId ?? '0');
    }

    protected function normalizeItem(array $item): array
    {
        $quantity = (int) ($item['quantity'] ?? 1);
        $unitPrice = (float) ($item['unit_price'] ?? 0);

        return [
            'key' => $item['key'] ?? '',
            'product_id' => (int) ($item['product_id'] ?? 0),
            'variant_id' => isset($item['variant_id']) ? (int) $item['variant_id'] : null,
            'product_name' => (string) ($item['product_name'] ?? ''),
            'product_slug' => (string) ($item['product_slug'] ?? ''),
            'product_sku' => $item['product_sku'] ?? null,
            'thumbnail_url' => (string) ($item['thumbnail_url'] ?? $item['thumbnail'] ?? ''),
            'is_customizable' => (bool) ($item['is_customizable'] ?? false),
            'size_name' => $item['size_name'] ?? $item['size'] ?? null,
            'color_name' => $item['color_name'] ?? $item['color'] ?? null,
            'unit_price' => $unitPrice,
            'quantity' => $quantity,
            'total_price' => (float) ($item['total_price'] ?? $item['total'] ?? round($unitPrice * $quantity, 2)),
        ];
    }
}
