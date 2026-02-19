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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();

            // no hacemos cascadeOnDelete aquí para no perder el historial 
            // si en el futuro borramos un producto del catálogo
            $table->foreignId('product_id')->constrained();

            // guardamos el precio histórico. Si el producto sube de precio mañana,
            // esta orden debe reflejar lo que el usuario pagó HOY.
            $table->unsignedBigInteger('price');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
