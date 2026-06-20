<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class StorageUrl
{
    public static function normalizePath(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        $path = str_replace('\\', '/', trim($path));

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $path = ltrim($path, '/');

        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, strlen('storage/'));
        }

        if (str_starts_with($path, 'app/public/')) {
            $path = substr($path, strlen('app/public/'));
        }

        return $path;
    }

    public static function exists(?string $path): bool
    {
        $normalized = self::normalizePath($path);

        if ($normalized === null) {
            return false;
        }

        if (str_starts_with($normalized, 'http://') || str_starts_with($normalized, 'https://')) {
            return true;
        }

        return Storage::disk('public')->exists($normalized);
    }

    public static function publicUrl(?string $path, ?string $placeholder = null): ?string
    {
        $normalized = self::normalizePath($path);

        if ($normalized === null) {
            return $placeholder;
        }

        if (str_starts_with($normalized, 'http://') || str_starts_with($normalized, 'https://')) {
            return $normalized;
        }

        if (! Storage::disk('public')->exists($normalized)) {
            return $placeholder;
        }

        return asset('storage/'.$normalized);
    }

    public static function placeholder(string $label = 'Product', int $width = 400, int $height = 533): string
    {
        return 'https://placehold.co/'.$width.'x'.$height.'/e5e5e5/737373?text='.urlencode($label);
    }
}
