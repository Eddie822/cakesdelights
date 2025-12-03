<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialOrderItem extends Model
{
    use HasFactory;

    // Nombre de la tabla, ajustado si usaste 'material_orders_items' en la migración
    protected $table = 'material_orders_items';

    // Columnas que se pueden asignar masivamente
    protected $fillable = [
        'material_order_id',
        'raw_material_id',
        'quantity_ordered',
        'unit_cost',
    ];

    /**
     * Relación: Un ítem pertenece a un pedido de materia prima.
     */
    public function order()
    {
        return $this->belongsTo(MaterialOrder::class, 'material_order_id');
    }

    /**
     * Relación: Un ítem hace referencia a una materia prima específica (el ingrediente).
     */
    public function material()
    {
        return $this->belongsTo(RawMaterial::class, 'raw_material_id');
    }
}
