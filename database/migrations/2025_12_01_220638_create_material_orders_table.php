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
        Schema::create('material_orders', function (Blueprint $table) {
            $table->id();

            // FK a Proveedores: A quién se le pide la materia prima
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');

            // FK a Usuarios: Quién hizo el pedido (Administrador)
            $table->foreignId('user_id')->constrained();

            // Estado del pedido (dispara la lógica de suma de stock al ser 'received')
            $table->enum('status', ['pending', 'ordered', 'received', 'canceled'])->default('pending');

            $table->date('expected_delivery_date')->nullable();
            $table->decimal('total_cost', 10, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_orders');
    }
};
