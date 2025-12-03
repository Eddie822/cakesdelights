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
        Schema::create('recipe_details', function (Blueprint $table) {
            $table->id();

            // FK a Recetas: A qué receta pertenece este ingrediente
            $table->foreignId('recipe_id')->constrained('recipes')->onDelete('cascade');

            // FK a Materia Prima: El ingrediente específico (Harina, Leche)
            $table->foreignId('raw_material_id')->constrained()->onDelete('cascade');

            // Cantidad necesaria para UNA unidad de esta receta
            $table->decimal('quantity_required', 8, 2);

            // Asegura que no se duplique el mismo ingrediente en la misma receta
            $table->unique(['recipe_id', 'raw_material_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_details');
    }
};
