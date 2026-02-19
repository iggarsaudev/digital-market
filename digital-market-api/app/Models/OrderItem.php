<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'price',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
        ];
    }

    // un item pertenece a una orden
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // un item pertenece a un producto específico
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
