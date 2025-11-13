<?php

namespace App\Livewire\Forms\Shipping;

use App\Models\Address;
use Livewire\Attributes\Validate;
use Livewire\Form;

class EditAddressForm extends Form
{
    public $id;
    public $type = ''; // 1: Envío (shipping), 2: Facturación (billing)
    public $calle = '';
    public $ciudad = '';
    public $estado = '';
    public $codigo_postal = '';
    public $referencias = ''; // Opcional, puede ser null
    public $default = false; // Indica si es la dirección por defecto (true/false)

    /**
     * Reglas de validación
     */
    public function rules(): array
    {
        return [
            'type' => 'required|in:1,2',
            'calle' => 'required|string|max:255',
            'ciudad' => 'required|string|max:100',
            'estado' => 'required|string|max:50',
            'codigo_postal' => 'required|string|max:20',
            'referencias' => 'nullable|string|max:500',
            'default' => 'boolean',
        ];
    }


    /**
     * Nombres legibles para los campos en los mensajes de error
     */
    public function validationAttributes()
    {
        return [
            'type' => 'tipo de dirección',
            'calle' => 'calle',
            'ciudad' => 'ciudad',
            'estado' => 'estado',
            'codigo_postal' => 'código postal',
            'referencias' => 'referencias',
            'default' => 'dirección por defecto',
        ];
    }

    public function edit($address)
    {
        $this->id = $address->id;
        $this->type = $address->type;
        $this->calle = $address->calle;
        $this->ciudad = $address->ciudad;
        $this->estado = $address->estado;
        $this->codigo_postal = $address->codigo_postal;
        $this->referencias = $address->referencias;
        $this->default = $address->default;

    }

    /**
     * Actualiza la dirección en la base de datos
     */
    public function update()
    {
        $this->validate();

        $address = Address::find($this->id);

        $address->update([
            'type' => $this->type,
            'calle' => $this->calle,
            'ciudad' => $this->ciudad,
            'estado' => $this->estado,
            'codigo_postal' => $this->codigo_postal,
            'referencias' => $this->referencias,
            'default' => $this->default,
        ]);
        $this->reset();
    }
}
