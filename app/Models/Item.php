<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Item extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
    ];

    // One Item can appear in many order_items records.
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // One Item can belong to many Orders.
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_items')
            ->withPivot('quantity', 'unit_price')
            ->withTimestamps();
    }
}