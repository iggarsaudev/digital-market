<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->words(3, true);

        return [
            'name' => ucfirst($name), // Ej: "Pack de Presets"
            'slug' => Str::slug($name),
            'description' => fake()->paragraph(),

            // Genera precio aleatorio entre 10,00 € (1000) y 90,00 € (9000)
            'price' => fake()->numberBetween(1000, 9000),

            'image_url' => 'https://picsum.photos/seed/' . Str::random(5) . '/640/480',
            'is_published' => true,
        ];
    }
}
