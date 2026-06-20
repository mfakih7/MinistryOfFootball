<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Services\ProductImageService;
use App\Support\StorageUrl;
use Illuminate\Console\Command;

class BackfillProductImages extends Command
{
    protected $signature = 'products:backfill-images {product_id? : Optional product ID to backfill}';

    protected $description = 'Normalize product image paths and sync main image fields from uploaded gallery images';

    public function handle(ProductImageService $productImages): int
    {
        $query = Product::query()->with('images');

        if ($productId = $this->argument('product_id')) {
            $query->whereKey($productId);
        }

        $updated = 0;

        $query->each(function (Product $product) use ($productImages, &$updated) {
            foreach ($product->images as $image) {
                $image->update([
                    'original_path' => StorageUrl::normalizePath($image->original_path),
                    'thumbnail_path' => StorageUrl::normalizePath($image->thumbnail_path),
                    'medium_path' => StorageUrl::normalizePath($image->medium_path),
                    'large_path' => StorageUrl::normalizePath($image->large_path),
                ]);
            }

            if ($productImages->backfillProduct($product->fresh('images'))) {
                $updated++;
                $this->line("Updated product #{$product->id}: {$product->name}");
            }
        });

        $this->info("Backfilled {$updated} product(s).");

        return self::SUCCESS;
    }
}
