<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    // Columnas que se pueden asignar masivamente
    protected $fillable = [
        'product_id',
        'name',
        'yield' // Cuántas unidades de producto se producen con esta receta
    ];

    /**
     * Relación: La receta pertenece a un producto final.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relación: Una receta tiene muchos detalles/ingredientes.
     */
    public function ingredients()
    {
        return $this->hasMany(RecipeDetail::class);
    }
}
