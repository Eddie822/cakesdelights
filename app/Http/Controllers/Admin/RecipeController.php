<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Models\Product;
use App\Models\RawMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class RecipeController extends Controller
{
    /**
     * Muestra una lista de todas las recetas.
     */
    public function index()
    {
        // Precarga las relaciones para mostrar detalles de ingredientes y producto
        $recipes = Recipe::with('product', 'ingredients.material')->latest()->paginate(10);
        return view('admin.recipes.index', compact('recipes'));
    }

    /**
     * Muestra el formulario para crear una nueva receta.
     */
    public function create()
    {
        // Solo mostramos productos que AÚN NO tienen una receta asignada
        // Esto evita duplicar recetas para un mismo producto
        $products = Product::doesntHave('recipe')->orderBy('name')->get();
        $rawMaterials = RawMaterial::orderBy('name')->get();

        return view('admin.recipes.create', compact('products', 'rawMaterials'));
    }

    /**
     * Almacena una nueva receta y sus detalles (ingredientes) en la base de datos.
     */
    public function store(Request $request)
    {
        // 1. Validación de datos
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:recipes,name',
            // Un producto solo puede tener una receta asociada
            'product_id' => 'required|exists:products,id|unique:recipes,product_id',
            'yield' => 'required|integer|min:1',
            'ingredients' => 'required|array|min:1',
            // Asegura que el ingrediente exista y sea distinto en el array
            'ingredients.*.raw_material_id' => 'required|exists:raw_materials,id|distinct',
            'ingredients.*.quantity_required' => 'required|numeric|min:0.001',
        ]);

        try {
            // 2. Uso de Transacción para atomicidad
            DB::transaction(function () use ($validated) {

                // 2.1. Crear el encabezado de la Receta
                $recipe = Recipe::create([
                    'name' => $validated['name'],
                    'product_id' => $validated['product_id'],
                    'yield' => $validated['yield'],
                ]);

                // 2.2. Crear los Detalles de la Receta (Ingredientes)
                // Recorremos el array validado y creamos cada relación
                foreach ($validated['ingredients'] as $ingredient) {
                    $recipe->ingredients()->create([
                        'raw_material_id' => $ingredient['raw_material_id'],
                        'quantity_required' => $ingredient['quantity_required'],
                    ]);
                }
            });
        } catch (Exception $e) {
            // Si falla, redirige con el error y los datos viejos
            return back()->withInput()->with('error', 'Error al guardar la receta: ' . $e->getMessage());
        }

        return redirect()->route('admin.recipes.index')->with('success', 'Receta creada exitosamente.');
    }

    /**
     * Muestra el formulario para editar la receta especificada.
     */
    public function edit(Recipe $recipe)
    {
        // Cargar la relación para obtener los ingredientes actuales
        $recipe->load('ingredients');

        // Obtenemos los productos que NO tienen receta O el producto actual de esta receta
        // (para permitir mantener el mismo producto)
        $products = Product::doesntHave('recipe')
                    ->orWhere('id', $recipe->product_id)
                    ->orderBy('name')
                    ->get();

        $rawMaterials = RawMaterial::orderBy('name')->get();

        // Podrías necesitar una vista 'edit' separada, o reutilizar 'create' si la adaptas.
        // Aquí asumimos que crearás una vista 'admin.recipes.edit'
        return view('admin.recipes.edit', compact('recipe', 'products', 'rawMaterials'));
    }

    /**
     * Actualiza la receta especificada en la base de datos.
     */
    public function update(Request $request, Recipe $recipe)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:recipes,name,' . $recipe->id,
            'product_id' => 'required|exists:products,id|unique:recipes,product_id,' . $recipe->id,
            'yield' => 'required|integer|min:1',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.raw_material_id' => 'required|exists:raw_materials,id|distinct',
            'ingredients.*.quantity_required' => 'required|numeric|min:0.001',
        ]);

        try {
            DB::transaction(function () use ($recipe, $validated) {
                // 1. Actualizar datos básicos de la receta
                $recipe->update([
                    'name' => $validated['name'],
                    'product_id' => $validated['product_id'],
                    'yield' => $validated['yield'],
                ]);

                // 2. Sincronizar ingredientes
                // Estrategia simple: Borrar los anteriores y crear los nuevos.
                // Esto maneja adiciones, eliminaciones y cambios de cantidad fácilmente.
                $recipe->ingredients()->delete();

                foreach ($validated['ingredients'] as $ingredient) {
                    $recipe->ingredients()->create([
                        'raw_material_id' => $ingredient['raw_material_id'],
                        'quantity_required' => $ingredient['quantity_required'],
                    ]);
                }
            });
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Error al actualizar la receta: ' . $e->getMessage());
        }

        return redirect()->route('admin.recipes.index')->with('success', 'Receta actualizada exitosamente.');
    }

    /**
     * Elimina la receta especificada de la base de datos.
     */
    public function destroy(Recipe $recipe)
    {
        try {
            // La eliminación en cascada en la base de datos debería borrar los recipe_details automáticamente.
            // Si no usaste 'onDelete cascade' en la migración, deberías hacer $recipe->ingredients()->delete() primero.
            $recipe->delete();
            return redirect()->route('admin.recipes.index')->with('success', 'Receta eliminada correctamente.');
        } catch (Exception $e) {
            return redirect()->route('admin.recipes.index')->with('error', 'No se pudo eliminar la receta: ' . $e->getMessage());
        }
    }
}
