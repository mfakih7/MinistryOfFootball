<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImage;
use App\Support\StorageUrl;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class ProductImageService
{
    public function __construct(
        protected ImageUploadService $imageUpload
    ) {}

    public function upload(Product $product, UploadedFile $file, ?string $altText = null): ProductImage
    {
        $paths = $this->imageUpload->storeUploadedImage($file, 'products');

        $paths = array_map(
            fn (string $path) => StorageUrl::normalizePath($path),
            $paths
        );

        $sortOrder = (int) $product->images()->max('sort_order') + 1;

        $image = $product->images()->create([
            'original_path' => $paths['original'],
            'thumbnail_path' => $paths['thumbnail'],
            'medium_path' => $paths['medium'],
            'large_path' => $paths['large'],
            'alt_text' => $altText ?? $product->name,
            'sort_order' => $sortOrder,
        ]);

        if (! $product->hasValidMainImage()) {
            $this->setAsMain($product, $image);
        }

        return $image;
    }

    public function setAsMain(Product $product, ProductImage $image): void
    {
        $product->update([
            'main_image' => StorageUrl::normalizePath($image->original_path),
            'thumbnail_image' => StorageUrl::normalizePath($image->thumbnail_path),
            'medium_image' => StorageUrl::normalizePath($image->medium_path),
            'large_image' => StorageUrl::normalizePath($image->large_path),
        ]);
    }

    public function delete(ProductImage $image): void
    {
        $product = $image->product;

        DB::transaction(function () use ($image, $product) {
            $paths = [
                $image->original_path,
                $image->thumbnail_path,
                $image->medium_path,
                $image->large_path,
            ];

            $wasMain = $product->main_image === $image->original_path;

            $image->delete();
            $this->imageUpload->deletePaths($paths);

            if ($wasMain) {
                $next = $product->images()->orderBy('sort_order')->first();

                if ($next) {
                    $this->setAsMain($product, $next);
                } else {
                    $product->update([
                        'main_image' => null,
                        'thumbnail_image' => null,
                        'medium_image' => null,
                        'large_image' => null,
                    ]);
                }
            }
        });
    }

    public function backfillProduct(Product $product): bool
    {
        $images = $product->images()->ordered()->get();

        if ($images->isEmpty()) {
            return false;
        }

        foreach ($images as $image) {
            $image->update([
                'original_path' => StorageUrl::normalizePath($image->original_path),
                'thumbnail_path' => StorageUrl::normalizePath($image->thumbnail_path),
                'medium_path' => StorageUrl::normalizePath($image->medium_path),
                'large_path' => StorageUrl::normalizePath($image->large_path),
            ]);
        }

        $mainImage = $images->first(fn (ProductImage $image) => StorageUrl::exists($image->original_path))
            ?? $images->first();

        if (! $product->hasValidMainImage()) {
            $this->setAsMain($product, $mainImage->fresh());
        }

        return true;
    }
}
