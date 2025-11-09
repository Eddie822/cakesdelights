<?php

namespace App\Livewire\Admin\Product;

use App\Models\Category;
use App\Models\Family;
use App\Models\Product;
use App\Models\Subcategory;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductCreate extends Component
{

    use WithFileUploads;

    public $families;
    public $family_id = '';
    public $category_id = '';
    public $image;
    public $product = [
        'sku' => '',
        'name' => '',
        'description' => '',
        'image_path' => '',
        'price' => '',
        'subcategory_id' => '',
    ];

    public function mount()
    {
        $this->families = Family::with('categories')->get();
    }

    public function boot()
    {
        $this->withValidator(function ($validator) {
            if ($validator->fails()) {
                $this->dispatch('swal', [
                    'icon' => 'error',
                    'title' => 'Error',
                    'text' => 'Por favor, corrige los errores en el formulario.',
                ]);
            };
        });

    }

    public function updatedFamilyId($value)
    {

        $this->category_id = '';
        $this->product['subcategory_id'] = '';
    }

    public function updatedCategoryId($value)
    {
        $this->product['subcategory_id'] = '';
    }


    #[Computed()]
    public function categories()
    {
        return Category::where('family_id', $this->family_id)->get();
    }

    #[Computed()]
    public function subcategories()
    {
        return Subcategory::where('category_id', $this->category_id)->get();
    }

    public function store()
    {
        $this->validate([
            'image' => 'required|image|max:2048', // max 2MB
            'product.sku' => 'required|unique:products,sku',
            'product.name' => 'required|max:255',
            'product.description' => 'nullable|string',
            'product.subcategory_id' => 'required|exists:subcategories,id',
            'product.price' => 'required|numeric|min:0',
        ]);

        $this->product['image_path'] = $this->image->store('products');

        // Create the product
        $product = Product::create($this->product);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Producto creado',
            'text' => 'El producto se ha creado correctamente.',
        ]);

        // Redirect or reset form
        return redirect()->route('admin.products.edit', $product);
    }

    public function render()
    {
        return view('livewire.admin.product.product-create');
    }
}
