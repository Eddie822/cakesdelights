<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'document_type' => 'required|integer',
            'document_number' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string',
            'password' => 'required|string|min=8',
            // Quitamos el role del validation
        ]);

        // 🔥 Asignar rol automáticamente según el email
        if ($validated['email'] === 'admin@gmail.com') {
            $validated['role'] = 'admin';
        } else {
            $validated['role'] = 'user';
        }

        $validated['password'] = bcrypt($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'Usuario creado correctamente');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // 1. Validaciones para la actualización
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            // El email debe ser único, EXCLUYENDO al usuario actual ($user->id)
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',

            // AGREGADO: Validación para el número de documento.
            // Debe ser único, EXCLUYENDO al usuario actual ($user->id)
            'document_number' => 'nullable|string|max:50|unique:users,document_number,' . $user->id,
            // NOTA: 'document_type' no se incluye aquí porque no está en la vista de edición.
        ]);

        // 2. Actualizar el usuario con los campos validados
        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
