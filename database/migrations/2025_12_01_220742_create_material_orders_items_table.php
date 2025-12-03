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
        Schema::create('material_orders_items', function (Blueprint $table) {
            $table->id();

            // FK al Encabezado del Pedido
            $table->foreignId('material_order_id')->constrained('material_orders')->onDelete('cascade');

            // FK a la Materia Prima (el ingrediente)
            $table->foreignId('raw_material_id')->constrained()->onDelete('cascade');

            // Cantidad que se suma al stock al recibir el pedido
            $table->decimal('quantity_ordered', 8, 2);

            // Costo unitario de compra
            $table->decimal('unit_cost', 8, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_orders_items');
    }
};
