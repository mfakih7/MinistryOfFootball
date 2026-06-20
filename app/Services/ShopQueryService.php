<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Color;
use App\Models\League;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Size;
use App\Models\Team;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ShopQueryService
{
    public function paginate(Request $request): LengthAwarePaginator
    {
        $query = Product::query()->active()->with(['images', 'category', 'team', 'league']);

        $this->applyFilters($query, $request);
        $this->applySort($query, $request);

        return $query->paginate(12)->withQueryString();
    }

    public function filterOptions(): array
    {
        return [
            'categories' => Category::query()->active()->ordered()->get(),
            'leagues' => League::query()->active()->ordered()->get(),
            'teams' => Team::query()->active()->ordered()->get(),
            'productTypes' => ProductType::query()->active()->ordered()->get(),
            'sizes' => Size::query()->active()->ordered()->get(),
            'colors' => Color::query()->active()->ordered()->get(),
        ];
    }

    public function activeFilters(Request $request): array
    {
        $filters = [];
        $options = $this->filterOptions();

        if ($request->filled('category')) {
            $item = $options['categories']->firstWhere('slug', $request->category);
            $filters[] = ['key' => 'category', 'label' => 'Category: '.($item?->name ?? $request->category)];
        }

        if ($request->filled('league')) {
            $item = $options['leagues']->firstWhere('slug', $request->league);
            $filters[] = ['key' => 'league', 'label' => 'League: '.($item?->name ?? $request->league)];
        }

        if ($request->filled('team')) {
            $item = $options['teams']->firstWhere('slug', $request->team);
            $filters[] = ['key' => 'team', 'label' => 'Team: '.($item?->name ?? $request->team)];
        }

        if ($request->filled('product_type')) {
            $item = $options['productTypes']->firstWhere('slug', $request->product_type);
            $filters[] = ['key' => 'product_type', 'label' => 'Type: '.($item?->name ?? $request->product_type)];
        }

        if ($request->filled('size')) {
            $item = $options['sizes']->firstWhere('id', (int) $request->size);
            $filters[] = ['key' => 'size', 'label' => 'Size: '.($item?->name ?? $request->size)];
        }

        if ($request->filled('color')) {
            $item = $options['colors']->firstWhere('id', (int) $request->color);
            $filters[] = ['key' => 'color', 'label' => 'Color: '.($item?->name ?? $request->color)];
        }

        if ($request->filled('price_min')) {
            $filters[] = ['key' => 'price_min', 'label' => 'Min: $'.$request->price_min];
        }

        if ($request->filled('price_max')) {
            $filters[] = ['key' => 'price_max', 'label' => 'Max: $'.$request->price_max];
        }

        if ($request->boolean('sale')) {
            $filters[] = ['key' => 'sale', 'label' => 'On Sale'];
        }

        if ($request->filled('sort') && $request->sort !== 'newest') {
            $sortLabels = [
                'price_low_high' => 'Price: Low to High',
                'price_high_low' => 'Price: High to Low',
                'best_selling' => 'Best Selling',
                'name' => 'Name A–Z',
            ];
            $filters[] = ['key' => 'sort', 'label' => 'Sort: '.($sortLabels[$request->sort] ?? $request->sort)];
        }

        return $filters;
    }

    public function pageTitle(Request $request): string
    {
        if ($request->filled('category')) {
            $category = Category::query()->where('slug', $request->category)->value('name');

            return $category ? "Shop — {$category}" : 'Shop';
        }

        if ($request->filled('league')) {
            $league = League::query()->where('slug', $request->league)->value('name');

            return $league ? "Shop — {$league}" : 'Shop';
        }

        if ($request->boolean('sale')) {
            return 'Shop — Sale';
        }

        return 'Shop';
    }

    protected function applyFilters(Builder $query, Request $request): void
    {
        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('league')) {
            $query->whereHas('league', fn ($q) => $q->where('slug', $request->league));
        }

        if ($request->filled('team')) {
            $query->whereHas('team', fn ($q) => $q->where('slug', $request->team));
        }

        if ($request->filled('product_type')) {
            $query->whereHas('productType', fn ($q) => $q->where('slug', $request->product_type));
        }

        if ($request->filled('size')) {
            $query->whereHas('variants', fn ($q) => $q->active()->where('size_id', $request->size));
        }

        if ($request->filled('color')) {
            $query->whereHas('variants', fn ($q) => $q->active()->where('color_id', $request->color));
        }

        if ($request->filled('price_min')) {
            $query->whereRaw('COALESCE(sale_price, price) >= ?', [(float) $request->price_min]);
        }

        if ($request->filled('price_max')) {
            $query->whereRaw('COALESCE(sale_price, price) <= ?', [(float) $request->price_max]);
        }

        if ($request->boolean('sale')) {
            $query->whereNotNull('sale_price')->where('sale_price', '>', 0);
        }
    }

    protected function applySort(Builder $query, Request $request): void
    {
        match ($request->input('sort', 'newest')) {
            'price_low_high' => $query->orderByRaw('COALESCE(sale_price, price) ASC'),
            'price_high_low' => $query->orderByRaw('COALESCE(sale_price, price) DESC'),
            'best_selling' => $query->orderByDesc('is_best_seller')->orderBy('sort_order'),
            'name' => $query->orderBy('name'),
            default => $query->latest(),
        };
    }
}
