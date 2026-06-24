<?php

namespace App\Models;

use App\Support\StorageUrl;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'original_path',
        'thumbnail_path',
        'medium_path',
        'large_path',
        'alt_text',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'product_id' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function originalUrl(): Attribute
    {
        return Attribute::get(fn () => $this->urlForPath($this->original_path));
    }

    public function thumbnailUrl(): Attribute
    {
        return Attribute::get(fn () => $this->urlForPath($this->thumbnail_path));
    }

    public function mediumUrl(): Attribute
    {
        return Attribute::get(fn () => $this->urlForPath($this->medium_path));
    }

    public function largeUrl(): Attribute
    {
        return Attribute::get(fn () => $this->urlForPath($this->large_path));
    }

    public function displayUrl(): Attribute
    {
        return Attribute::get(function () {
            foreach ([$this->thumbnail_path, $this->medium_path, $this->large_path, $this->original_path] as $path) {
                $url = StorageUrl::publicUrl($path);

                if ($url !== null) {
                    return $url;
                }
            }

            return StorageUrl::placeholder($this->alt_text ?? 'Product');
        });
    }

    /**
     * Highest-resolution variant available, for use as the product page hero image.
     */
    public function heroUrl(): Attribute
    {
        return Attribute::get(function () {
            foreach ([$this->large_path, $this->original_path, $this->medium_path, $this->thumbnail_path] as $path) {
                $url = StorageUrl::publicUrl($path);

                if ($url !== null) {
                    return $url;
                }
            }

            return StorageUrl::placeholder($this->alt_text ?? 'Product');
        });
    }

    protected function urlForPath(?string $path): string
    {
        return StorageUrl::publicUrl($path)
            ?? StorageUrl::placeholder($this->alt_text ?? 'Product');
    }
}
