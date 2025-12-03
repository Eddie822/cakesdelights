<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_person',
        'phone',
        'email',
        'rfc',
        'address',
    ];

    /**
     * Define la relación con los pedidos de materia prima que realiza este proveedor.
     */
    public function materialOrders()
    {
        return $this->hasMany(MaterialOrder::class);
    }

    /**
     * Define la relación con la materia prima que suministra (relación uno a muchos simplificada).
     */
    public function materials()
    {
        return $this->hasMany(RawMaterial::class);
    }
}
