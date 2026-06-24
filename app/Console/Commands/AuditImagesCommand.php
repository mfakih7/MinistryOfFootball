<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class AuditImagesCommand extends Command
{
    protected $signature = 'app:audit-images {--max-kb=500 : Flag files larger than this size in KB}';

    protected $description = 'Audit product images for missing files, oversized files, and missing thumbnail/medium/large variants';

    public function handle(): int
    {
        $disk = Storage::disk('public');
        $maxBytes = (int) $this->option('max-kb') * 1024;

        $missingFiles = [];
        $largeFiles = [];
        $missingVariants = [];

        $checkPath = function (?string $path, string $label) use ($disk, $maxBytes, &$missingFiles, &$largeFiles) {
            if (! $path) {
                return;
            }

            if (! $disk->exists($path)) {
                $missingFiles[] = "{$label}: {$path}";

                return;
            }

            $size = $disk->size($path);

            if ($size > $maxBytes) {
                $largeFiles[] = sprintf('%s: %s (%s KB)', $label, $path, number_format($size / 1024, 1));
            }
        };

        Product::query()->withTrashed()->chunk(100, function ($products) use ($checkPath, &$missingVariants) {
            foreach ($products as $product) {
                $checkPath($product->main_image, "Product #{$product->id} main_image");
                $checkPath($product->thumbnail_image, "Product #{$product->id} thumbnail_image");
                $checkPath($product->medium_image, "Product #{$product->id} medium_image");
                $checkPath($product->large_image, "Product #{$product->id} large_image");
            }
        });

        ProductImage::query()->chunk(100, function ($images) use ($checkPath, &$missingVariants) {
            foreach ($images as $image) {
                $checkPath($image->original_path, "ProductImage #{$image->id} original_path");
                $checkPath($image->thumbnail_path, "ProductImage #{$image->id} thumbnail_path");
                $checkPath($image->medium_path, "ProductImage #{$image->id} medium_path");
                $checkPath($image->large_path, "ProductImage #{$image->id} large_path");

                $missing = array_filter([
                    ! $image->thumbnail_path ? 'thumbnail' : null,
                    ! $image->medium_path ? 'medium' : null,
                    ! $image->large_path ? 'large' : null,
                ]);

                if ($missing) {
                    $missingVariants[] = "ProductImage #{$image->id} (product_id={$image->product_id}) missing: ".implode(', ', $missing);
                }
            }
        });

        $this->components->info('Image Audit Report');

        $this->outputSection('Missing files', $missingFiles);
        $this->outputSection('Files over '.$this->option('max-kb').'KB', $largeFiles);
        $this->outputSection('Missing thumbnail/medium/large variants', $missingVariants);

        $this->newLine();
        $this->components->twoColumnDetail('Missing files', (string) count($missingFiles));
        $this->components->twoColumnDetail('Oversized files', (string) count($largeFiles));
        $this->components->twoColumnDetail('Incomplete variants', (string) count($missingVariants));

        return self::SUCCESS;
    }

    protected function outputSection(string $title, array $lines): void
    {
        $this->newLine();
        $this->line("<options=bold>{$title}</>");

        if (empty($lines)) {
            $this->line('  None found.');

            return;
        }

        foreach ($lines as $line) {
            $this->line("  - {$line}");
        }
    }
}
