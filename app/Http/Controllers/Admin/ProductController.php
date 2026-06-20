<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Category;
use App\Models\Color;
use App\Models\Customer;
use App\Models\League;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\ProductVariant;
use App\Models\Size;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::query()
            ->with(['category', 'team', 'images'])
            ->when($request->search, function ($q, $search) {
                $q->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->when($request->category_id, fn ($q, $id) => $q->where('category_id', $id))
            ->when($request->team_id, fn ($q, $id) => $q->where('team_id', $id))
            ->when($request->filled('is_active'), fn ($q) => $q->where('is_active', $request->boolean('is_active')))
            ->when($request->boolean('is_featured'), fn ($q) => $q->where('is_featured', true))
            ->when($request->boolean('is_new_arrival'), fn ($q) => $q->where('is_new_arrival', true))
            ->when($request->boolean('is_best_seller'), fn ($q) => $q->where('is_best_seller', true))
            ->ordered()
            ->paginate(12)
            ->withQueryString();

        $categories = Category::query()->active()->ordered()->get();
        $teams = Team::query()->active()->ordered()->get();

        return view('admin.products.index', compact('products', 'categories', 'teams'));
    }

    public function create(): View
    {
        return view('admin.products.create', $this->formData());
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $product = DB::transaction(function () use ($request) {
            $product = Product::query()->create($request->safe()->except('variants'));
            $this->syncVariants($product, $request->input('variants', []));

            return $product;
        });

        return redirect()->route('admin.products.edit', $product)->with('success', 'Product created successfully.');
    }

    public function edit(Product $product): View
    {
        $product->load(['images', 'variants.size', 'variants.color']);

        return view('admin.products.edit', array_merge(['product' => $product], $this->formData()));
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        DB::transaction(function () use ($request, $product) {
            $product->update($request->safe()->except('variants'));
            $this->syncVariants($product, $request->input('variants', []));
        });

        return redirect()->route('admin.products.edit', $product)->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    protected function formData(): array
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

    protected function syncVariants(Product $product, array $variants): void
    {
        $keptIds = [];

        foreach ($variants as $variant) {
            $sizeId = $variant['size_id'] ?? null;
            $colorId = $variant['color_id'] ?? null;

            if (! $sizeId && ! $colorId) {
                continue;
            }

            $data = [
                'size_id' => $sizeId ?: null,
                'color_id' => $colorId ?: null,
                'sku' => $variant['sku'] ?? null,
                'price_adjustment' => $variant['price_adjustment'] ?? 0,
                'stock_quantity' => $variant['stock_quantity'] ?? 0,
                'is_active' => $variant['is_active'] ?? true,
            ];

            if (! empty($variant['id'])) {
                $existing = $product->variants()->where('id', $variant['id'])->first();
                if ($existing) {
                    $existing->update($data);
                    $keptIds[] = $existing->id;

                    continue;
                }
            }

            $created = $product->variants()->create($data);
            $keptIds[] = $created->id;
        }

        $product->variants()->whereNotIn('id', $keptIds)->delete();
    }
}
