<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\Product;
use Illuminate\Http\Request; // Importamos la clase Request

class FamilyController extends Controller
{
    /**
     * Muestra la lista de productos de una familia con opciones de ordenamiento.
     */
    public function show(Request $request, Family $family) // Inyectamos Request para leer el filtro
    {
        // 1. Obtener la consulta base, filtrando por la familia
        $query = Product::whereHas('subcategory.category', function ($q) use ($family) {
            $q->where('family_id', $family->id);
        });

        // 2. Leer el parámetro 'sort' de la URL
        $sort = $request->query('sort');

        // 3. Aplicar el ordenamiento
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'stock_desc':
                // Ordenar por stock de mayor a menor
                $query->orderBy('stock', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                // Ordenamiento por defecto (ej: por ID descendente)
                $query->latest();
                break;
        }

        // 4. Ejecutar la consulta y obtener los productos
        $products = $query->get();

        // 5. Pasar los datos a la vista
        return view('families.show', compact('family', 'products'));
    }
}
