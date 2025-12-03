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
        Schema::create('raw_materials', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Nombre del ingrediente (ej. Harina, Leche)
            $table->string('unit')->default('kg'); // Unidad de medida (kg, L, unidad)
            $table->decimal('current_stock', 10, 2)->default(0); // Cantidad actual en almacén
            $table->decimal('min_stock', 10, 2)->default(10); // Límite para alerta de bajo stock

            // FK al proveedor único para simplificar la compra
            // Si el proveedor se elimina, este campo se pone a NULL (set null)
            $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raw_materials');
    }
};
