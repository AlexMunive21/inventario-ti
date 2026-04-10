<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{


    public function index()
    {
        $usuarios = User::with('roles')->get();
        return view('usuarios.index', compact('usuarios'));
    }
    public function create()
{
    $roles = Role::all();
    return view('usuarios.create', compact('roles'));
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6',
        'role' => 'required'
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    $user->assignRole($request->role);

    return redirect()->route('usuarios.index')
        ->with('success','Usuario creado correctamente');
}

public function edit($id)
{
    $usuario = User::findOrFail($id);
    $roles = Role::all();

    return view('usuarios.edit', compact('usuario','roles'));
}

public function update(\Illuminate\Http\Request $request, $id)
{
    $usuario = User::findOrFail($id);

    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email,'.$usuario->id,
    ]);

    $usuario->update([
        'name' => $request->name,
        'email' => $request->email,
    ]);

    if($request->password){
        $usuario->update([
            'password' => Hash::make($request->password)
        ]);
    }

    // sincroniza roles
    $usuario->syncRoles([$request->role]);

    return redirect()->route('usuarios.index')
        ->with('success','Usuario actualizado');
}
public function destroy($id)
{
    $usuario = User::findOrFail($id);

    // Evitar que se borre a sí mismo
    if ($usuario->id === auth()->id()) {
        return redirect()->route('usuarios.index')
            ->with('error', 'No puedes eliminarte a ti mismo.');
    }

    $usuario->delete();

    return redirect()->route('usuarios.index')
        ->with('success', 'Usuario eliminado correctamente.');
}
}
