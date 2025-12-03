<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialOrder extends Model
{
    use HasFactory;

    // Columnas que se pueden asignar masivamente
    protected $fillable = [
        'supplier_id',
        'user_id',
        'status',
        'expected_delivery_date',
        'total_cost',
    ];

    // Castear la fecha a un objeto Carbon
    protected $casts = [
        'expected_delivery_date' => 'date',
    ];

    /**
     * Relación: Un pedido pertenece a un proveedor.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Relación: Un pedido fue creado por un usuario (administrador).
     */
    public function user()
    {
        // Asume que la clase User existe en App\Models\User
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Un pedido tiene muchos ítems (los ingredientes que se pidieron).
     */
    public function items()
    {
        return $this->hasMany(MaterialOrderItem::class);
    }
}
