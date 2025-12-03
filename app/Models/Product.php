<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'name',
        'description',
        'image_path',
        'price',
        // 'stock', // NOTA: 'stock' NO SE INCLUYE en fillable porque debe ser manipulado por lógica de producción (increment/decrement).
        'subcategory_id',
    ];

    /**
     * Relación: Un producto está en una subcategoría.
     */
    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    /**
     * Relación: Un producto tiene una receta.
     */
    public function recipe()
    {
        // Un producto tiene UNA receta (relación uno a uno, si no planeas tener múltiples recetas por producto)
        return $this->hasOne(Recipe::class);
    }

    /**
     * Mutador: Accede a la URL completa de la imagen.
     */
    public function image(): Attribute
    {
        return Attribute::make(
            get: fn() => Storage::url($this->image_path),
        );
    }
}
