<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Product;
use App\Models\RawMaterial;
use App\Models\Recipe;
use App\Models\Family;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CreateRecipe extends Component
{
    // Filtros
    public $familyId = '';

    // Propiedades del Encabezado de la Receta
    public $productId = '';
    public $recipeName;
    public $recipeYield = 1;

    // Propiedad dinámica para los ingredientes de la receta
    public array $ingredients = [];

    // Colecciones de datos
    public Collection $families;
    public Collection $products;
    public Collection $materials;

    public function mount()
    {
        $this->families = Family::orderBy('name')->get();
        $this->products = collect();
        $this->materials = RawMaterial::orderBy('name')->get();

        if (empty($this->ingredients)) {
            $this->ingredients[] = $this->getEmptyIngredient();
        }
    }

    public function updatedFamilyId($value)
    {
        $this->productId = '';
        if ($value) {
            $this->products = Product::whereHas('subcategory.category', function ($query) use ($value) {
                $query->where('family_id', $value);
            })->doesntHave('recipe')->orderBy('name')->get();
        } else {
            $this->products = collect();
        }
    }

    protected function getEmptyIngredient()
    {
        return [
            'raw_material_id' => null,
            'quantity_required' => 0,
            'material_unit' => '',
        ];
    }

    // Hook para mostrar errores de validación
    public function boot()
    {
        $this->withValidator(function ($validator) {
            if ($validator->fails()) {
                $this->dispatch('swal', [
                    'icon' => 'error',
                    'title' => 'Error de Validación',
                    'text' => 'Por favor, revisa los campos marcados en rojo.',
                ]);
            }
        });
    }

    public function updated($field)
    {
        if (preg_match('/^ingredients\.(\d+)\.raw_material_id$/', $field, $matches)) {
            $index = $matches[1];
            $materialId = $this->ingredients[$index]['raw_material_id'];

            if ($materialId) {
                $material = $this->materials->find($materialId);
                if ($material) {
                    $this->ingredients[$index]['material_unit'] = $material->unit;
                }
            }
        }
    }

    public function addIngredient()
    {
        $this->ingredients[] = $this->getEmptyIngredient();
    }

    public function removeIngredient($index)
    {
        unset($this->ingredients[$index]);
        $this->ingredients = array_values($this->ingredients);
    }

    public function saveRecipe()
    {
        // 1. Validaciones
        $this->validate([
            'productId' => 'required|exists:products,id|unique:recipes,product_id',
            'recipeName' => 'required|string|max:255',
            'recipeYield' => 'required|integer|min:1',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.raw_material_id' => [
                'required',
                'exists:raw_materials,id',
                function ($attribute, $value, $fail) {
                    $materialIds = collect($this->ingredients)->pluck('raw_material_id')->filter()->toArray();
                    if (count($materialIds) !== count(array_unique($materialIds))) {
                        $fail('No se puede usar el mismo ingrediente más de una vez en la receta.');
                    }
                }
            ],
            'ingredients.*.quantity_required' => 'required|numeric|min:0.001',
        ]);

        // 2. Guardado Directo con Transacción
        DB::transaction(function () {
            // Crear la Receta
            $recipe = Recipe::create([
                'product_id' => $this->productId,
                'name' => $this->recipeName,
                'yield' => $this->recipeYield,
            ]);

            // Crear los Ingredientes
            foreach ($this->ingredients as $ingredient) {
                $recipe->ingredients()->create([
                    'raw_material_id' => $ingredient['raw_material_id'],
                    'quantity_required' => $ingredient['quantity_required'],
                ]);
            }
        });

        // 3. Notificación y Redirección
        session()->flash('success', 'Receta creada exitosamente.');
        return redirect()->route('admin.recipes.index');
    }

    public function render()
    {
        return view('livewire.admin.create-recipe');
    }
}
