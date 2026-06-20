<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'city',
        'notes',
        'is_whatsapp_subscribed',
        'last_order_at',
    ];

    protected function casts(): array
    {
        return [
            'last_order_at' => 'datetime',
            'is_whatsapp_subscribed' => 'boolean',
        ];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
