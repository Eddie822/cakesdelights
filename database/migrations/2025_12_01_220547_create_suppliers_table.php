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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Nombre de la empresa proveedora
            $table->string('contact_person')->nullable(); // Persona de contacto
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('rfc')->unique()->nullable(); // RFC (Registro Federal de Contribuyentes)
            $table->text('address')->nullable(); // Dirección
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
