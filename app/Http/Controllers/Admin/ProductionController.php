<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Production;
use App\Models\Recipe;
use App\Models\RawMaterial;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class ProductionController extends Controller
{
    /**
     * Muestra una lista de todos los lotes de producción.
     */
    public function index()
    {
        $productions = Production::with('user', 'details.recipe.product')->latest()->paginate(10);
        return view('admin.productions.index', compact('productions'));
    }

    /**
     * Muestra el formulario para iniciar un nuevo lote.
     */
    public function create()
    {
        $recipes = Recipe::with('product')->orderBy('name')->get();
        return view('admin.productions.create', compact('recipes'));
    }

    /**
     * Almacena un nuevo lote de producción como 'in_progress'.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipe_id' => 'required|exists:recipes,id',
            'quantity_produced' => 'required|integer|min:1',
        ]);

        // 1. Crear el encabezado de la Producción
        $production = Production::create([
            'user_id' => Auth::id(),
            'start_date' => now(),
            'status' => 'in_progress',
        ]);

        // 2. Crear el detalle (Qué y Cuánto)
        $production->details()->create([
            'recipe_id' => $validated['recipe_id'],
            'quantity_produced' => $validated['quantity_produced'],
        ]);

        return redirect()->route('admin.productions.index')
            ->with('success', 'Lote de producción iniciado. Cuando termine, márquelo como "Completado" para actualizar el stock.');
    }

    /**
     * Muestra el detalle de un lote de producción específico.
     */
    public function show(Production $production)
    {
        // Cargamos las relaciones profundas para mostrar los ingredientes calculados
        $production->load([
            'user',
            'details.recipe.product',
            'details.recipe.ingredients.material'
        ]);

        return view('admin.productions.show', compact('production'));
    }

    /**
     * Lógica CRUCIAL: Completa la producción, RESTA materia prima y SUMA stock de producto.
     */
    public function complete(Production $production)
    {
        if ($production->status === 'completed' || $production->status === 'canceled') {
            return back()->with('error', 'Este lote ya ha sido procesado.');
        }

        try {
            DB::transaction(function () use ($production) {

                $detail = $production->details->first();
                $recipe = $detail->recipe;
                $quantityToProduce = $detail->quantity_produced;

                // --- PASO 1: Calcular y RESTAR Materia Prima ---

                // Factor de escala
                $scaleFactor = $quantityToProduce / $recipe->yield;

                foreach ($recipe->ingredients as $ingredient) {
                    $material = RawMaterial::find($ingredient->raw_material_id);
                    $requiredAmount = $ingredient->quantity_required * $scaleFactor;

                    if (!$material || $material->current_stock < $requiredAmount) {
                        throw new Exception("Stock insuficiente de: {$material->name}. Requerido: {$requiredAmount}, Disponible: {$material->current_stock}");
                    }

                    // RESTAR del almacén
                    $material->decrement('current_stock', $requiredAmount);
                }

                // --- PASO 2: SUMAR Producto Final ---

                $product = $recipe->product;
                $product->increment('stock', $quantityToProduce);

                // --- PASO 3: Finalizar Orden ---
                $production->update([
                    'status' => 'completed',
                    'end_date' => now(),
                ]);
            });

        } catch (Exception $e) {
            return back()->with('error', 'Error en la producción: ' . $e->getMessage());
        }

        return redirect()->route('admin.productions.index')
            ->with('success', 'Producción completada. Inventario actualizado correctamente.');
    }
}
