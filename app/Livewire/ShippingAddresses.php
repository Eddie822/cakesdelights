<?php

namespace App\Livewire;

use App\Livewire\Forms\CreateAddressForm;
use App\Livewire\Forms\Shipping\EditAddressForm;
use App\Models\Address;
use Livewire\Component;

class ShippingAddresses extends Component
{
    public $addresses;
    public EditAddressForm $editAddress;
    public $newAddress = false; // inicia mostrando el listado
    public CreateAddressForm $createAddress;

    public function mount()
    {
        if (auth()->check()) {
            $this->addresses = Address::where('user_id', auth()->id())->get();
        } else {
            $this->addresses = collect(); // evita errores si no hay sesión
        }
    }

    /**
     * Muestra el formulario para crear una nueva dirección.
     */
    public function create()
    {
        $this->newAddress = true;
        $this->createAddress->reset();
    }


    /**
     * Guarda la nueva dirección y recarga la lista.
     */
    public function store()
    {
        $this->createAddress->save();
        $this->addresses = Address::where('user_id', auth()->id())->get();
        $this->newAddress = false; // vuelve al listado después de guardar
    }

    public function edit($id)
    {
        $address = Address::find($id);
        $this->editAddress->edit($address);
        $this->newAddress = false;
    }

    public function update()
    {
        $this->editAddress->update();
        $this->addresses = Address::where('user_id', auth()->id())->get();
    }

    public function deleteAddress($id)
    {
        Address::find($id)->delete();
        $this->addresses = Address::where('user_id', auth()->id())->get();

    }


    public function setDefaultAddress($id)
    {
        $this->addresses->each(function ($address) use ($id) {
            $address->update([
                'default' => $address->id == $id
            ]);
        });
    }
    public function render()
    {
        return view('livewire.shipping-addresses');
    }
}
