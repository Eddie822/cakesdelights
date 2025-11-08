<?php

namespace App\Livewire\Admin\Subcategories;

use App\Models\Family;
use App\Models\Category;
use App\Models\Subcategory;
use Livewire\Attributes\Computed;
use Livewire\Component;

class SubcategoryCreate extends Component
{
    public $families;

    public $subcategory = [
        'family_id' => '',
        'category_id' => '',
        'name' => '',
    ];

    public function mount()
    {
        $this->families = Family::all();
    }

    public function updatedSubcategoryFamilyId()
    {
        // Reiniciamos la categoría seleccionada cuando cambia la familia
        $this->subcategory['category_id'] = '';
    }

    #[Computed]
    public function categories()
    {
        // Si no hay familia seleccionada, devolvemos colección vacía
        if (empty($this->subcategory['family_id'])) {
            return collect();
        }

        // Filtramos las categorías por la familia seleccionada
        return Category::where('family_id', $this->subcategory['family_id'])->get();
    }

    public function save()
    {
        $this->validate([
            'subcategory.family_id' => 'required|exists:families,id',
            'subcategory.category_id' => 'required',
            'subcategory.name' => 'required|string|max:255',
        ],[],[
            'subcategory.family_id' => 'familia',
            'subcategory.category_id' => 'categoría',
            'subcategory.name' => 'nombre',
        ]);

        Subcategory::create($this->subcategory);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '',
            'text' => 'Subcategoria creada con exito',
        ]);
        return redirect()->route('admin.subcategories.index');
    }

    public function render()
    {
        return view('livewire.admin.subcategories.subcategory-create', [
            'categories' => $this->categories(), // 👈 Aquí se pasa correctamente
        ]);
    }
}
