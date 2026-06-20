<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_number',
        'customer_id',
        'status',
        'order_source',
        'subtotal',
        'discount_total',
        'delivery_fee',
        'total',
        'customer_name',
        'customer_phone',
        'customer_address',
        'customer_notes',
        'admin_notes',
        'whatsapp_message',
        'whatsapp_opened_at',
        'delivered_at',
        'cancelled_at',
        'inventory_deducted_at',
    ];

    protected function casts(): array
    {
        return [
            'customer_id' => 'integer',
            'status' => OrderStatus::class,
            'subtotal' => 'decimal:2',
            'discount_total' => 'decimal:2',
            'delivery_fee' => 'decimal:2',
            'total' => 'decimal:2',
            'whatsapp_opened_at' => 'datetime',
            'delivered_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'inventory_deducted_at' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
