<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Muestra una lista paginada de proveedores.
     */
    public function index()
    {
        $suppliers = Supplier::orderBy('name')->paginate(10);
        return view('admin.suppliers.index', compact('suppliers'));
    }

    /**
     * Muestra el formulario para crear un nuevo proveedor.
     */
    public function create()
    {
        return view('admin.suppliers.create');
    }

    /**
     * Almacena un nuevo proveedor en la base de datos.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:suppliers,name',
            'contact_person' => 'required|string|max:255',

            // CAMBIO: Teléfono estricto a 10 dígitos
            'phone' => 'required|string|min:10|max:10|unique:suppliers,phone',

            'email' => 'required|email|max:255|unique:suppliers,email',
            'rfc' => 'required|string|max:20|unique:suppliers,rfc',
            'address' => 'required|string',
        ]);

        Supplier::create($validated);

        return redirect()->route('admin.suppliers.index')->with('success', 'Proveedor creado exitosamente.');
    }

    /**
     * Muestra el formulario para editar un proveedor existente.
     */
    public function edit(Supplier $supplier)
    {
        return view('admin.suppliers.edit', compact('supplier'));
    }

    /**
     * Actualiza un proveedor en la base de datos.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            // REQUERIDO y ÚNICO, excluyendo al proveedor actual
            'name' => 'required|string|max:255|unique:suppliers,name,' . $supplier->id,
            'contact_person' => 'required|string|max:255',

            // CAMBIO: Teléfono estricto a 10 dígitos y ÚNICO, excluyendo al proveedor actual
            'phone' => 'required|string|min:10|max:10|unique:suppliers,phone,' . $supplier->id,

            'email' => 'required|email|max:255|unique:suppliers,email,' . $supplier->id,
            'rfc' => 'required|string|max:20|unique:suppliers,rfc,' . $supplier->id,

            'address' => 'required|string',
        ]);

        $supplier->update($validated);

        return redirect()->route('admin.suppliers.index')->with('success', 'Proveedor actualizado exitosamente.');
    }

    /**
     * Elimina un proveedor de la base de datos.
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()->route('admin.suppliers.index')->with('success', 'Proveedor eliminado correctamente.');
    }
}
