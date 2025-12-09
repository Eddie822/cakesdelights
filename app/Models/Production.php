<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    use HasFactory;

    // Estos son los campos reales de tu tabla 'productions'
    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'status',
    ];

    // Aseguramos que las fechas se traten como objetos Carbon
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Relación: Quién inició la producción.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Los detalles de este lote (aquí está la cantidad y la receta).
     */
    public function details()
    {
        return $this->hasMany(ProductionDetail::class);
    }
}
