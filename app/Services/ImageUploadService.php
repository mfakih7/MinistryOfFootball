<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Format;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Laravel\Facades\Image;
use Throwable;

class ImageUploadService
{
    public function storeUploadedImage(UploadedFile $file, string $directory = 'uploads'): array
    {
        $extension = $this->resolveOutputExtension($file);
        $filename = Str::uuid().'.'.$extension;
        $basePath = "images/{$directory}";
        $sourcePath = $file->getPathname();

        $paths = [
            'original' => "{$basePath}/original/{$filename}",
            'thumbnail' => "{$basePath}/thumbnails/{$filename}",
            'medium' => "{$basePath}/medium/{$filename}",
            'large' => "{$basePath}/large/{$filename}",
        ];

        $this->saveVariant(Image::decodePath($sourcePath), $paths['original'], null, $extension);
        $this->saveVariant(Image::decodePath($sourcePath), $paths['thumbnail'], 400, $extension);
        $this->saveVariant(Image::decodePath($sourcePath), $paths['medium'], 900, $extension);
        $this->saveVariant(Image::decodePath($sourcePath), $paths['large'], 1600, $extension);

        return $paths;
    }

    public function storeSimpleImage(UploadedFile $file, string $directory = 'uploads', ?int $maxWidth = 800): string
    {
        $extension = $this->resolveOutputExtension($file);
        $path = "images/{$directory}/".Str::uuid().'.'.$extension;

        $this->saveVariant(Image::decodePath($file->getPathname()), $path, $maxWidth, $extension);

        return $path;
    }

    public function deletePaths(array $paths): void
    {
        foreach ($paths as $path) {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }

    protected function saveVariant(ImageInterface $image, string $path, ?int $width, string $extension): void
    {
        if ($width) {
            $image->scale(width: $width);
        }

        $encoded = $this->encodeImage($image, $extension);
        Storage::disk('public')->put($path, $encoded->toString());
    }

    protected function encodeImage(ImageInterface $image, string $extension)
    {
        if ($extension === 'webp') {
            try {
                return $image->encodeUsingFormat(Format::WEBP, quality: 90);
            } catch (Throwable) {
                // Fall through to JPEG if WebP encoding is unavailable.
            }
        }

        return $image->encodeUsingFormat(Format::JPEG, quality: 90);
    }

    protected function resolveOutputExtension(UploadedFile $file): string
    {
        try {
            Image::decodePath($file->getPathname())
                ->encodeUsingFormat(Format::WEBP, quality: 90);

            return 'webp';
        } catch (Throwable) {
            return 'jpg';
        }
    }
}
