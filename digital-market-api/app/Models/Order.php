<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'stripe_session_id',
        'total_price',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'total_price' => 'integer',
        ];
    }

    // una orden pertenece a un usuario
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // una orden tiene muchos items
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
