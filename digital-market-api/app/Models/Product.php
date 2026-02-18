<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'image_url',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'is_published' => 'boolean',
        ];
    }
}
