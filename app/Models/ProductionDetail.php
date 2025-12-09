<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionDetail extends Model
{
    use HasFactory;

    // Especificamos la tabla si el nombre no sigue la convención estándar (opcional si es production_details)
    protected $table = 'production_details';

    // CAMPOS PERMITIDOS PARA ASIGNACIÓN MASIVA (CRUCIAL PARA CORREGIR EL ERROR)
    protected $fillable = [
        'production_id',     // Vincula con el lote
        'recipe_id',         // Vincula con la receta usada
        'quantity_produced', // Cantidad fabricada
    ];

    /**
     * El detalle pertenece al encabezado de la Producción.
     */
    public function production(): BelongsTo
    {
        return $this->belongsTo(Production::class);
    }

    /**
     * El detalle usa una Receta específica.
     */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}
