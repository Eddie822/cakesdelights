<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    use HasFactory;

    // Columnas que se pueden asignar masivamente
    protected $fillable = [
        'name',
        'unit',
        'current_stock',
        'min_stock',
        'supplier_id', // FK al proveedor único
    ];

    /**
     * Relación: La materia prima pertenece a un único proveedor (simplificado).
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Relación: La materia prima aparece en muchos detalles de recetas.
     */
    public function recipeDetails()
    {
        return $this->hasMany(RecipeDetail::class);
    }

    /**
     * Relación: La materia prima ha sido comprada en muchos ítems de pedidos.
     */
    public function orderItems()
    {
        return $this->hasMany(MaterialOrderItem::class);
    }
}
