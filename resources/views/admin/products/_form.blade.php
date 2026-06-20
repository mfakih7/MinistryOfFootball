@props([
    'product' => null,
    'action',
    'method' => 'POST',
    'categories',
    'leagues',
    'teams',
    'productTypes',
    'sizes',
    'colors',
])

@php
    $stockStatus = old('stock_status', $product?->stock_status?->value ?? $product?->stock_status ?? 'in_stock');
    $existingVariants = old('variants', isset($product) ? $product->variants->map(fn ($v) => [
        'id' => $v->id,
        'size_id' => $v->size_id,
        'color_id' => $v->color_id,
        'sku' => $v->sku,
        'price_adjustment' => $v->price_adjustment,
        'stock_quantity' => $v->stock_quantity,
        'is_active' => $v->is_active,
    ])->values()->all() : []);
@endphp

<form action="{{ $action }}" method="POST" class="space-y-6">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    {{-- Basic Info --}}
    <div class="admin-card space-y-4">
        <h2 class="text-lg font-bold text-gray-900">Basic Info</h2>
        <div class="grid gap-4 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label class="admin-label" for="name">Name *</label>
                <input type="text" id="name" name="name" value="{{ old('name', $product?->name) }}" required class="admin-input">
            </div>
            <div>
                <label class="admin-label" for="slug">Slug</label>
                <input type="text" id="slug" name="slug" value="{{ old('slug', $product?->slug) }}" class="admin-input" placeholder="Auto-generated from name if empty">
            </div>
            <div>
                <label class="admin-label" for="sku">SKU</label>
                <input type="text" id="sku" name="sku" value="{{ old('sku', $product?->sku) }}" class="admin-input">
            </div>
            <div class="sm:col-span-2">
                <label class="admin-label" for="short_description">Short Description</label>
                <textarea id="short_description" name="short_description" rows="2" class="admin-input">{{ old('short_description', $product?->short_description) }}</textarea>
            </div>
            <div class="sm:col-span-2">
                <label class="admin-label" for="description">Description</label>
                <textarea id="description" name="description" rows="6" class="admin-input">{{ old('description', $product?->description) }}</textarea>
            </div>
        </div>
    </div>

    {{-- Pricing --}}
    <div class="admin-card space-y-4">
        <h2 class="text-lg font-bold text-gray-900">Pricing</h2>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div>
                <label class="admin-label" for="price">Price *</label>
                <input type="number" id="price" name="price" value="{{ old('price', $product?->price) }}" required min="0" step="0.01" class="admin-input">
            </div>
            <div>
                <label class="admin-label" for="sale_price">Sale Price</label>
                <input type="number" id="sale_price" name="sale_price" value="{{ old('sale_price', $product?->sale_price) }}" min="0" step="0.01" class="admin-input">
            </div>
            <div>
                <label class="admin-label" for="cost_price">Cost Price</label>
                <input type="number" id="cost_price" name="cost_price" value="{{ old('cost_price', $product?->cost_price) }}" min="0" step="0.01" class="admin-input">
            </div>
            <div>
                <label class="admin-label" for="stock_status">Stock Status *</label>
                <select id="stock_status" name="stock_status" required class="admin-input">
                    <option value="in_stock" @selected($stockStatus === 'in_stock')>In Stock</option>
                    <option value="out_of_stock" @selected($stockStatus === 'out_of_stock')>Out of Stock</option>
                    <option value="limited_stock" @selected($stockStatus === 'limited_stock')>Limited Stock</option>
                </select>
            </div>
            <div>
                <label class="admin-label" for="weight">Weight (kg)</label>
                <input type="number" id="weight" name="weight" value="{{ old('weight', $product?->weight) }}" min="0" step="0.01" class="admin-input">
            </div>
        </div>
    </div>

    {{-- Classification --}}
    <div class="admin-card space-y-4">
        <h2 class="text-lg font-bold text-gray-900">Classification</h2>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="admin-label" for="category_id">Category</label>
                <select id="category_id" name="category_id" class="admin-input">
                    <option value="">— None —</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $product?->category_id) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="admin-label" for="league_id">League</label>
                <select id="league_id" name="league_id" class="admin-input">
                    <option value="">— None —</option>
                    @foreach ($leagues as $league)
                        <option value="{{ $league->id }}" @selected(old('league_id', $product?->league_id) == $league->id)>{{ $league->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="admin-label" for="team_id">Team</label>
                <select id="team_id" name="team_id" class="admin-input">
                    <option value="">— None —</option>
                    @foreach ($teams as $team)
                        <option value="{{ $team->id }}" @selected(old('team_id', $product?->team_id) == $team->id)>{{ $team->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="admin-label" for="product_type_id">Product Type</label>
                <select id="product_type_id" name="product_type_id" class="admin-input">
                    <option value="">— None —</option>
                    @foreach ($productTypes as $productType)
                        <option value="{{ $productType->id }}" @selected(old('product_type_id', $product?->product_type_id) == $productType->id)>{{ $productType->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- Flags --}}
    <div class="admin-card space-y-4">
        <h2 class="text-lg font-bold text-gray-900">Flags</h2>
        <div class="flex flex-wrap gap-6">
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_customizable" value="1" @checked(old('is_customizable', $product?->is_customizable ?? false)) class="rounded border-gray-300 text-brand-red focus:ring-brand-red">
                Customizable
            </label>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product?->is_featured ?? false)) class="rounded border-gray-300 text-brand-red focus:ring-brand-red">
                Featured
            </label>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_new_arrival" value="1" @checked(old('is_new_arrival', $product?->is_new_arrival ?? false)) class="rounded border-gray-300 text-brand-red focus:ring-brand-red">
                New Arrival
            </label>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_best_seller" value="1" @checked(old('is_best_seller', $product?->is_best_seller ?? false)) class="rounded border-gray-300 text-brand-red focus:ring-brand-red">
                Best Seller
            </label>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product?->is_active ?? true)) class="rounded border-gray-300 text-brand-red focus:ring-brand-red">
                Active
            </label>
        </div>
        <div class="max-w-xs">
            <label class="admin-label" for="sort_order">Sort Order</label>
            <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $product?->sort_order ?? 0) }}" min="0" class="admin-input">
        </div>
    </div>

    {{-- SEO --}}
    <div class="admin-card space-y-4">
        <h2 class="text-lg font-bold text-gray-900">SEO</h2>
        <div class="grid gap-4">
            <div>
                <label class="admin-label" for="meta_title">Meta Title</label>
                <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title', $product?->meta_title) }}" class="admin-input">
            </div>
            <div>
                <label class="admin-label" for="meta_description">Meta Description</label>
                <textarea id="meta_description" name="meta_description" rows="3" class="admin-input">{{ old('meta_description', $product?->meta_description) }}</textarea>
            </div>
        </div>
    </div>

    {{-- Variants --}}
    <div class="admin-card space-y-4" x-data="{
        variants: @js($existingVariants),
        addVariant() {
            this.variants.push({ id: null, size_id: '', color_id: '', sku: '', price_adjustment: 0, stock_quantity: 0, is_active: true });
        },
        removeVariant(index) {
            this.variants.splice(index, 1);
        }
    }">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-900">Variants</h2>
            <button type="button" @click="addVariant()" class="admin-btn-secondary text-sm">Add Variant</button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm" x-show="variants.length > 0">
                <thead>
                    <tr class="border-b border-gray-200 text-left text-xs uppercase tracking-wide text-gray-500">
                        <th class="pb-2 pr-2">Size</th>
                        <th class="pb-2 pr-2">Color</th>
                        <th class="pb-2 pr-2">SKU</th>
                        <th class="pb-2 pr-2">Price Adj.</th>
                        <th class="pb-2 pr-2">Stock</th>
                        <th class="pb-2 pr-2">Active</th>
                        <th class="pb-2">Remove</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(variant, index) in variants" :key="index">
                        <tr class="border-b border-gray-100">
                            <td class="py-2 pr-2">
                                <input type="hidden" :name="`variants[${index}][id]`" x-model="variant.id">
                                <select :name="`variants[${index}][size_id]`" x-model="variant.size_id" class="admin-input min-w-[100px]">
                                    <option value="">—</option>
                                    @foreach ($sizes as $size)
                                        <option value="{{ $size->id }}">{{ $size->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="py-2 pr-2">
                                <select :name="`variants[${index}][color_id]`" x-model="variant.color_id" class="admin-input min-w-[100px]">
                                    <option value="">—</option>
                                    @foreach ($colors as $color)
                                        <option value="{{ $color->id }}">{{ $color->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="py-2 pr-2">
                                <input type="text" :name="`variants[${index}][sku]`" x-model="variant.sku" class="admin-input min-w-[120px]">
                            </td>
                            <td class="py-2 pr-2">
                                <input type="number" :name="`variants[${index}][price_adjustment]`" x-model="variant.price_adjustment" step="0.01" class="admin-input w-24">
                            </td>
                            <td class="py-2 pr-2">
                                <input type="number" :name="`variants[${index}][stock_quantity]`" x-model="variant.stock_quantity" min="0" class="admin-input w-20">
                            </td>
                            <td class="py-2 pr-2">
                                <input type="hidden" :name="`variants[${index}][is_active]`" :value="variant.is_active ? '1' : '0'">
                                <input type="checkbox" x-model="variant.is_active" class="rounded border-gray-300 text-brand-red focus:ring-brand-red">
                            </td>
                            <td class="py-2">
                                <button type="button" @click="removeVariant(index)" class="text-red-600 hover:underline">Remove</button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
            <p x-show="variants.length === 0" class="text-sm text-gray-500">No variants yet. Click "Add Variant" to create size/color combinations.</p>
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="admin-btn-primary">{{ $product ? 'Update Product' : 'Create Product' }}</button>
        <a href="{{ route('admin.products.index') }}" class="admin-btn-secondary">Cancel</a>
    </div>
</form>

@if ($product)
    {{-- Images (edit only) --}}
    <div class="admin-card mt-6 space-y-4">
        <h2 class="text-lg font-bold text-gray-900">Images</h2>

        <form action="{{ route('admin.products.images.store', $product) }}" method="POST" enctype="multipart/form-data" class="flex flex-wrap items-end gap-3">
            @csrf
            <div>
                <label class="admin-label" for="image">Upload Image</label>
                <input type="file" id="image" name="image" accept="image/*" required class="admin-input">
            </div>
            <div>
                <label class="admin-label" for="alt_text">Alt Text</label>
                <input type="text" id="alt_text" name="alt_text" value="{{ $product->name }}" class="admin-input">
            </div>
            <button type="submit" class="admin-btn-secondary">Upload</button>
        </form>

        @if ($product->images->isNotEmpty())
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($product->images as $image)
                    @php $isMain = $product->main_image === $image->original_path; @endphp
                    <div class="relative rounded-lg border border-gray-200 p-3 {{ $isMain ? 'ring-2 ring-brand-red' : '' }}">
                        <img src="{{ $image->display_url }}" alt="{{ $image->alt_text }}" class="mb-3 aspect-square w-full rounded object-cover">
                        @if ($isMain)
                            <span class="mb-2 inline-block rounded-full bg-brand-red px-2 py-0.5 text-xs font-semibold text-white">Main</span>
                        @endif
                        <div class="flex flex-wrap gap-2">
                            @unless ($isMain)
                                <form method="POST" action="{{ route('admin.products.images.main', [$product, $image]) }}">
                                    @csrf
                                    <button type="submit" class="text-sm text-brand-red hover:underline">Set Main</button>
                                </form>
                            @endunless
                            <form method="POST" action="{{ route('admin.products.images.destroy', [$product, $image]) }}" onsubmit="return confirm('Delete this image?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-sm text-red-600 hover:underline">Delete</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500">No images uploaded yet.</p>
        @endif
    </div>
@endif
