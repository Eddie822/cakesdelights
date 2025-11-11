<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cover;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CoverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $covers= Cover::orderBy('order')->get();
        return view('admin.covers.index', compact('covers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.covers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $data = $request->validate([
            'image' => 'required|image|max:1024',
            'title' => 'required|string|max:255',
            'start_at' => 'required|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'is_active' => 'required|boolean'
        ]);


        // ✅ Guardar imagen correctamente en storage/app/public/covers
        $data['image_path'] = $request->file('image')->store('covers', 'public');

        // ❌ Eliminar el archivo original del array para no causar error al insertar
        unset($data['image']);

        // ✅ Crear registro en la BD
        $cover = Cover::create($data);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Portada creada',
            'text' => 'La portada se creo con exito.'
        ]);

        return redirect()->route('admin.covers.index', $cover);
    }

    /**
     * Display the specified resource.
     */
    public function show(Cover $cover)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cover $cover)
    {
        return view('admin.covers.edit', compact('cover'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cover $cover)
    {
        $data = $request->validate([
            'image' => 'nullable|image|max:1024',
            'title' => 'required|string|max:255',
            'start_at' => 'required|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'is_active' => 'required|boolean'
        ]);

        if ($request->hasFile('image')) {
            // Borra la imagen anterior del disco público
            Storage::disk('public')->delete($cover->image_path);

            // Guarda la nueva imagen en el disco público
            $data['image_path'] = $request->file('image')->store('covers', 'public');
        }
        $cover->update($data);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Portada actualizada',
            'text' => 'La portada se edito con exito.'
        ]);

        return redirect()->route('admin.covers.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cover $cover)
    {

        Storage::delete($cover->image_path);
        $cover->delete();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Portada eliminada con éxito',
            'timer' => 3000,
        ]);
        return redirect()->route('admin.covers.index');
    }
}
