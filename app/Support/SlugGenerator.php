<?php

namespace App\Support;

use Illuminate\Support\Str;

class SlugGenerator
{
    public static function unique(string $value, string $modelClass, ?int $ignoreId = null, string $column = 'slug'): string
    {
        $slug = Str::slug($value);
        $original = $slug;
        $counter = 1;

        while (static::exists($modelClass, $slug, $ignoreId, $column)) {
            $slug = $original.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    protected static function exists(string $modelClass, string $slug, ?int $ignoreId, string $column): bool
    {
        $usesSoftDeletes = in_array(
            \Illuminate\Database\Eloquent\SoftDeletes::class,
            class_uses_recursive($modelClass)
        );

        $query = $usesSoftDeletes
            ? $modelClass::withTrashed()->where($column, $slug)
            : $modelClass::query()->where($column, $slug);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->exists();
    }
}
