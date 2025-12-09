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
        Schema::create('production_details', function (Blueprint $table) {
            $table->id();

            // 1. Encabezado: Vincula al registro de la ejecución de la Producción
            $table->foreignId('production_id')->constrained()->onDelete('cascade');

            // 2. RECETA: Vincula a la Receta utilizada (CRUCIAL para saber qué ingredientes restar)
            $table->foreignId('recipe_id')->constrained('recipes')->onDelete('restrict');

            // 3. CANTIDAD: Cuántas unidades del producto final se crearon (SUMA al stock final)
            $table->unsignedInteger('quantity_produced');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_details');
    }
};
