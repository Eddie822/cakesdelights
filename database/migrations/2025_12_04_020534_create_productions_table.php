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
        Schema::create('productions', function (Blueprint $table) {
            $table->id();

            // Usuario que inició el proceso de producción
            $table->foreignId('user_id')->constrained()->onDelete('restrict');

            // FECHAS: Seguimiento del proceso
            $table->dateTime('start_date')->default(now()); // Cuándo se inició
            $table->dateTime('end_date')->nullable(); // Cuándo se marcó como completado

            // ESTADO: Crucial para saber cuándo descontar el stock (al pasar a 'completed')
            $table->enum('status', ['pending', 'in_progress', 'completed', 'canceled'])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productions');
    }
};
