<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product; // Importamos el modelo
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario admin
        User::factory()->create([
            'name' => 'Nacho',
            'email' => 'nacho@digitalmarket.com',
        ]);

        // Creamos 10 productos digitales con precios en céntimos
        Product::factory(10)->create();
    }
}
