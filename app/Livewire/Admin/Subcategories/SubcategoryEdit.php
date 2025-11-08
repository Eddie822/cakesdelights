<?php

namespace App\Livewire\Admin\Subcategories;

use App\Models\Category;
use App\Models\Family;
use App\Models\Subcategory;
use Livewire\Attributes\Computed;
use Livewire\Component;

class SubcategoryEdit extends Component
{
    public $subcategory;

    public $families;

    public $subcategoryEdit = [
        'family_id' => '',
        'category_id' => '',
        'name' => '',
    ];

    public function mount(Subcategory $subcategory)
    {
        $this->subcategory = $subcategory;
        $this->families = Family::all();

        $this->subcategoryEdit = [
            'family_id' => $subcategory->category->family_id ?? '',
            'category_id' => $subcategory->category_id ?? '',
            'name' => $subcategory->name ?? '',
        ];
    }

    public function updatedSubcategoryEditFamilyId()
    {
        // Reiniciamos la categoría seleccionada cuando cambia la familia
        $this->subcategoryEdit['category_id'] = '';
    }

    #[Computed]
    public function categories()
    {
        if (empty($this->subcategoryEdit['family_id'])) {
            return collect();
        }

        return Category::where('family_id', $this->subcategoryEdit['family_id'])->get();
    }

    public function save()
    {
        $this->validate([
            'subcategoryEdit.family_id' => 'required|exists:families,id',
            'subcategoryEdit.category_id' => 'required|exists:categories,id',
            'subcategoryEdit.name' => 'required|string|max:255',
        ],
        [
            'subcategoryEdit.family_id.required' => 'La familia es obligatoria.',
            'subcategoryEdit.family_id.exists' => 'La familia seleccionada no es válida.',
            'subcategoryEdit.category_id.required' => 'La categoría es obligatoria.',
            'subcategoryEdit.category_id.exists' => 'La categoría seleccionada no es válida.',
            'subcategoryEdit.name.required' => 'El nombre es obligatorio.',
            'subcategoryEdit.name.string' => 'El nombre debe ser una cadena de texto.',
            'subcategoryEdit.name.max' => 'El nombre no debe exceder los 255 caracteres.',
        ]);

        $this->subcategory->update([
            'category_id' => $this->subcategoryEdit['category_id'],
            'name' => $this->subcategoryEdit['name'],
        ]);

        $this->dispatch('swal',
            [
                'icon' => 'success',
                'title' => '¡Subcategoría actualizada!',
                'text' => 'La subcategoría ha sido actualizada exitosamente.',
            ]);

        // return redirect()->route('admin.subcategories.index');
    }

    public function render()
    {
        return view('livewire.admin.subcategories.subcategory-edit', [
            'categories' => $this->categories(), // 👈 Aquí está el FIX
        ]);
    }
}
