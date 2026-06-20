<?php

namespace App\Http\Requests\Admin;

use App\Enums\StockStatus;
use App\Models\Product;
use App\Support\SlugGenerator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('product')?->id;

        return [
            'category_id' => ['nullable', 'exists:categories,id'],
            'league_id' => ['nullable', 'exists:leagues,id'],
            'team_id' => ['nullable', 'exists:teams,id'],
            'product_type_id' => ['nullable', 'exists:product_types,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($id)],
            'sku' => ['nullable', 'string', 'max:255', Rule::unique('products', 'sku')->ignore($id)],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'stock_status' => ['required', Rule::enum(StockStatus::class)],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'is_customizable' => ['boolean'],
            'is_featured' => ['boolean'],
            'is_new_arrival' => ['boolean'],
            'is_best_seller' => ['boolean'],
            'is_active' => ['boolean'],
            'sort_order' => ['integer', 'min:0'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'variants' => ['nullable', 'array'],
            'variants.*.id' => ['nullable', 'integer', 'exists:product_variants,id'],
            'variants.*.size_id' => ['nullable', 'exists:sizes,id'],
            'variants.*.color_id' => ['nullable', 'exists:colors,id'],
            'variants.*.sku' => ['nullable', 'string', 'max:255'],
            'variants.*.price_adjustment' => ['nullable', 'numeric'],
            'variants.*.stock_quantity' => ['nullable', 'integer', 'min:0'],
            'variants.*.is_active' => ['boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_customizable' => $this->boolean('is_customizable'),
            'is_featured' => $this->boolean('is_featured'),
            'is_new_arrival' => $this->boolean('is_new_arrival'),
            'is_best_seller' => $this->boolean('is_best_seller'),
            'is_active' => $this->boolean('is_active'),
            'sort_order' => $this->input('sort_order', 0),
        ]);

        if (! $this->filled('slug') && $this->filled('name')) {
            $this->merge([
                'slug' => SlugGenerator::unique($this->input('name'), Product::class, $this->route('product')?->id),
            ]);
        }

        $variants = collect($this->input('variants', []))->map(function ($variant) {
            return array_merge($variant, [
                'is_active' => filter_var($variant['is_active'] ?? true, FILTER_VALIDATE_BOOLEAN),
            ]);
        })->all();

        $this->merge(['variants' => $variants]);
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $combinations = [];

            foreach ($this->input('variants', []) as $index => $variant) {
                $sizeId = $variant['size_id'] ?? null;
                $colorId = $variant['color_id'] ?? null;

                if (! $sizeId && ! $colorId) {
                    continue;
                }

                $key = ($sizeId ?? 'null').'-'.($colorId ?? 'null');

                if (in_array($key, $combinations, true)) {
                    $validator->errors()->add("variants.{$index}.size_id", 'Duplicate size/color combination for this product.');
                }

                $combinations[] = $key;
            }
        });
    }
}
