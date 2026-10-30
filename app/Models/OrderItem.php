<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'item_id',
        'quantity',
        'unit_price',
    ];

    // Each order item belongs to one order.
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // Each order item belongs to one item.
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}