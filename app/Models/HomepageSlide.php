<?php

namespace App\Models;

use App\Models\Concerns\HasActiveAndOrderedScopes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class HomepageSlide extends Model
{
    use HasActiveAndOrderedScopes;

    protected $fillable = [
        'title',
        'subtitle',
        'image',
        'button_text',
        'button_url',
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

    public function imageUrl(): Attribute
    {
        return Attribute::get(function () {
            if ($this->image) {
                return asset('storage/'.$this->image);
            }

            return 'https://placehold.co/800x900/111111/ffffff?text='.urlencode($this->title);
        });
    }
}
