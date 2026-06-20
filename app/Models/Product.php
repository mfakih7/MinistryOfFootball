<?php

namespace App\Models;

use App\Enums\StockStatus;
use App\Models\Concerns\HasActiveAndOrderedScopes;
use App\Support\StorageUrl;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasActiveAndOrderedScopes;
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'league_id',
        'team_id',
        'product_type_id',
        'name',
        'slug',
        'sku',
        'short_description',
        'description',
        'price',
        'sale_price',
        'cost_price',
        'weight',
        'main_image',
        'thumbnail_image',
        'medium_image',
        'large_image',
        'is_featured',
        'is_new_arrival',
        'is_best_seller',
        'is_customizable',
        'is_active',
        'stock_status',
        'sort_order',
        'meta_title',
        'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'category_id' => 'integer',
            'league_id' => 'integer',
            'team_id' => 'integer',
            'product_type_id' => 'integer',
            'price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'cost_price' => 'decimal:2',
            'weight' => 'decimal:2',
            'is_featured' => 'boolean',
            'is_new_arrival' => 'boolean',
            'is_best_seller' => 'boolean',
            'is_customizable' => 'boolean',
            'is_active' => 'boolean',
            'stock_status' => StockStatus::class,
            'sort_order' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function league(): BelongsTo
    {
        return $this->belongsTo(League::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeBestSeller(Builder $query): Builder
    {
        return $query->where('is_best_seller', true);
    }

    public function scopeNewArrival(Builder $query): Builder
    {
        return $query->where('is_new_arrival', true);
    }

    public function displayPrice(): Attribute
    {
        return Attribute::get(fn () => $this->sale_price ?? $this->price);
    }

    public function formattedPrice(): Attribute
    {
        return Attribute::get(fn () => '$'.number_format((float) $this->display_price, 2));
    }

    public function badgeLabel(): Attribute
    {
        return Attribute::get(function () {
            if ($this->is_best_seller) {
                return 'Bestseller';
            }

            if ($this->is_new_arrival) {
                return 'New';
            }

            if ($this->sale_price) {
                return 'Sale';
            }

            if ($this->is_featured) {
                return 'Featured';
            }

            return null;
        });
    }

    public function thumbnailUrl(): Attribute
    {
        return Attribute::get(fn () => $this->resolveImageUrl(
            [$this->thumbnail_image, $this->main_image],
            'thumbnail_path',
            400,
            533,
        ));
    }

    public function mediumUrl(): Attribute
    {
        return Attribute::get(fn () => $this->resolveImageUrl(
            [$this->medium_image, $this->main_image],
            'medium_path',
            600,
            800,
        ));
    }

    public function largeImageUrl(): Attribute
    {
        return Attribute::get(fn () => $this->resolveImageUrl(
            [$this->large_image, $this->main_image],
            'large_path',
            800,
            800,
        ));
    }

    public function mainImageUrl(): Attribute
    {
        return Attribute::get(fn () => $this->resolveImageUrl(
            [$this->main_image, $this->large_image, $this->medium_image, $this->thumbnail_image],
            'original_path',
            800,
            800,
        ));
    }

    public function hasValidMainImage(): bool
    {
        return StorageUrl::exists($this->main_image);
    }

    protected function resolveImageUrl(array $productPaths, string $imageField, int $width, int $height): string
    {
        foreach ($productPaths as $path) {
            $url = StorageUrl::publicUrl($path);

            if ($url !== null) {
                return $url;
            }
        }

        $image = $this->firstRelatedImage();

        if ($image) {
            $path = $image->{$imageField} ?? $image->original_path;
            $url = StorageUrl::publicUrl($path);

            if ($url !== null) {
                return $url;
            }

            return $image->display_url;
        }

        return StorageUrl::placeholder($this->name, $width, $height);
    }

    protected function firstRelatedImage(): ?ProductImage
    {
        $images = $this->relationLoaded('images')
            ? $this->images->sortBy('sort_order')
            : $this->images()->ordered()->get();

        return $images->first(fn (ProductImage $image) => StorageUrl::exists($image->original_path))
            ?? $images->first();
    }
}
