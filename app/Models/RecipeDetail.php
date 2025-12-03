<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeDetail extends Model
{
    use HasFactory;

    // Nombre de la tabla, si se llamó 'recipe_details' en la migración
    protected $table = 'recipe_details';

    // Columnas que se pueden asignar masivamente
    protected $fillable = [
        'recipe_id',
        'raw_material_id',
        'quantity_required', // Cantidad que se descuenta del almacén
    ];

    /**
     * Relación: Un detalle pertenece a una receta específica.
     */
    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    /**
     * Relación: El ingrediente que se requiere (Materia Prima).
     */
    public function material()
    {
        return $this->belongsTo(RawMaterial::class, 'raw_material_id');
    }
}
