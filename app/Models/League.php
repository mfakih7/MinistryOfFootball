<?php

namespace App\Models;

use App\Models\Concerns\HasActiveAndOrderedScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class League extends Model
{
    use HasActiveAndOrderedScopes;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'country',
        'logo',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
