<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique(); // url: 'curso-vue-avanzado'
            $table->text('description');

            // Dinero: Siempre en Enteros (céntimos de euro)
            // Ejemplo: 1000 = 10,00 €.
            // Esto es crucial para Stripe y precisión contable.
            $table->unsignedBigInteger('price');

            $table->string('image_url'); // Portada visual
            $table->boolean('is_published')->default(true); // Control de visibilidad

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
