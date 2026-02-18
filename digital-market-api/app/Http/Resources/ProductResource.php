<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,

            // Enviamos el precio en céntimos (para cálculos precisos)
            'price' => $this->price,

            // Y un extra formateado para facilitar la vida al frontend
            'price_formatted' => number_format($this->price / 100, 2, ',', '.') . ' €',

            'image_url' => $this->image_url,
        ];
    }
}
