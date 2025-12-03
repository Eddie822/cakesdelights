<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RawMaterial;
use App\Models\Supplier; // Necesitas el modelo Supplier para el formulario
use Illuminate\Http\Request;

class RawMaterialController extends Controller
{
    /**
     * Muestra una lista paginada de la materia prima.
     */
    public function index()
    {
        // Carga la relación 'supplier' para mostrar el nombre del proveedor en la tabla
        $materials = RawMaterial::with('supplier')->orderBy('name')->paginate(10);
        return view('admin.raw_materials.index', compact('materials'));
    }

    /**
     * Muestra el formulario para crear una nueva materia prima.
     */
    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        // Definimos una lista de unidades comunes para usar en un <select>
        $units = ['kg', 'g', 'L', 'ml', 'unidad', 'caja'];

        return view('admin.raw_materials.create', compact('suppliers', 'units'));
    }

    /**
     * Almacena una nueva materia prima en la base de datos.
     */
    public function store(Request $request)
    {
        //    dd($request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:raw_materials,name',
            'unit' => 'required|string|max:50',
            // El stock inicial puede ser 0
            'current_stock' => 'required|numeric|min:0',
            // El límite mínimo para alerta
            'min_stock' => 'required|numeric|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id', // Verifica que el proveedor exista
        ]);

        RawMaterial::create($validated);

        return redirect()->route('admin.raw_materials.index')->with('success', 'Materia prima creada y agregada al almacén.');
    }

    /**
     * Muestra el formulario para editar la materia prima especificada.
     */
    public function edit(RawMaterial $raw_material)
    {
        $suppliers = Supplier::orderBy('name')->get();
        $units = ['kg', 'g', 'L', 'ml', 'unidad', 'caja'];

        return view('admin.raw_materials.edit', compact('raw_material', 'suppliers', 'units'));
    }

    /**
     * Actualiza la materia prima especificada en la base de datos.
     */
    public function update(Request $request, RawMaterial $raw_material)
    {
        $validated = $request->validate([
            // Excluye el material actual de la regla 'unique'
            'name' => 'required|string|max:255|unique:raw_materials,name,' . $raw_material->id,
            'unit' => 'required|string|max:50',
            'current_stock' => 'required|numeric|min:0',
            'min_stock' => 'required|numeric|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);

        $raw_material->update($validated);

        return redirect()->route('admin.raw_materials.index')->with('success', 'Materia prima actualizada correctamente.');
    }

    /**
     * Elimina la materia prima especificada.
     */
    public function destroy(RawMaterial $raw_material)
    {
        $raw_material->delete();
        return redirect()->route('admin.raw_materials.index')->with('success', 'Materia prima eliminada del registro.');
    }
}
