<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'address_id',
        'subtotal',
        'tax',
        'total',
        'status',
        'delivery_date'
    ];

    // CORRECCIÓN CLAVE: Casteamos la fecha para que se maneje como objeto Carbon
    protected $casts = [
        'delivery_date' => 'date',
        // Asegúrate de castear created_at si lo necesitas como fecha de objeto
        'created_at' => 'datetime',
    ];


    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shippingAddress()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
