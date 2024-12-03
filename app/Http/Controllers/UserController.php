<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // Obtener todos los usuarios
        $users = User::all();
        return view('dashboard', compact('users'));
    }

    public function create()
    {
        // Mostrar formulario de creación de usuario
        return view('users.create');
    }

    public function store(Request $request)
    {
        // Validar y almacenar un nuevo usuario
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario creado con éxito');
    }

    public function edit(User $user)
    {
        // Mostrar formulario de edición de usuario
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // Validar y actualizar un usuario
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado con éxito');
    }

    public function destroy(User $user)
    {
        // Eliminar usuario
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado con éxito');
    }
}
