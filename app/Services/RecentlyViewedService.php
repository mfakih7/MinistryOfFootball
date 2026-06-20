<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class RecentlyViewedService
{
    protected const SESSION_KEY = 'recently_viewed';

    protected const MAX_ITEMS = 8;

    public function add(int $productId): void
    {
        $items = collect(session(self::SESSION_KEY, []))
            ->reject(fn ($id) => (int) $id === $productId)
            ->prepend($productId)
            ->take(self::MAX_ITEMS)
            ->values()
            ->all();

        session([self::SESSION_KEY => $items]);
    }

    public function products(int $limit = 4): Collection
    {
        $ids = collect(session(self::SESSION_KEY, []))->take($limit);

        if ($ids->isEmpty()) {
            return collect();
        }

        return Product::query()
            ->active()
            ->with(['images', 'team', 'league'])
            ->whereIn('id', $ids)
            ->get()
            ->sortBy(fn ($product) => $ids->search($product->id))
            ->values();
    }
}
