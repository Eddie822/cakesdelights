<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\Product;
use Illuminate\Http\Request;

class FamilyController extends Controller
{
    public function show(Family $family){
        // Obtener todos los productos relacionados a la familia (vía categorías y subcategorías)
        $products = Product::whereHas('subcategory.category', function ($query) use ($family) {
            $query->where('family_id', $family->id);
        })->latest()->get();
        return view('families.show', compact('family', 'products'));
    }
}
