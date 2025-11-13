<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'calle',
        'ciudad',
        'estado',
        'codigo_postal',
        'referencias',
        'receiver',
        'receiver_info',
        'default',
    ];

    protected $casts = [
        'receiver_info' => 'array',
        'default' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
