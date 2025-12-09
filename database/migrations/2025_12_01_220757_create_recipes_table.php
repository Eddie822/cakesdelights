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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();

            // Relación directa con el producto final (Pastel, Postre)
            // Agregamos unique para asegurar que un producto solo tenga una receta principal.
            $table->foreignId('product_id')
                ->constrained()
                ->unique()
                ->onDelete('cascade');

            $table->string('name')->unique(); // Nombre de la receta (ej. Receta Base de Tres Leches)
            $table->unsignedInteger('yield')->default(1); // Rendimiento (Cuántas unidades de producto crea)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
