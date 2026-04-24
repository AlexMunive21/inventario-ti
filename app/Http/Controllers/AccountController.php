<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Colaborador;
use App\Models\Account;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function index()
    {
        $cuentas       = Account::with('colaborador')->orderBy('type')->get()->groupBy('type');
        $colaboradores = Colaborador::where('activo', 1)->get();

        return view('cuentas.index', compact('cuentas', 'colaboradores'));
    }
    public function create()
    {
        $colaboradores = Colaborador::all();

        return view('cuentas.create', compact('colaboradores'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'type' => 'required',
        ]);

        Account::create([
            'name'           => $request->name,
            'username'       => $request->username,
            'password'       => $request->password,
            'type'           => $request->type,
            'colaborador_id' => $request->colaborador_id ?: null,
            'is_ti'          => $request->colaborador_id ? false : true,
            'observaciones'  => $request->observaciones,
        ]);

        return redirect()->route('cuentas.index')
            ->with('success', 'Cuenta creada correctamente.');
    }

    public function edit(Account $cuenta)
    {
        $colaboradores = Colaborador::where('activo', 1)->get();

        return view('cuentas.edit', compact('cuenta', 'colaboradores'));
    }

    public function update(Request $request, Account $cuenta)
    {
        $request->validate([
            'name' => 'required',
            'type' => 'required',
        ]);

        $cuenta->update([
            'name'           => $request->name,
            'username'       => $request->username,
            'password'       => $request->password ?: $cuenta->password,
            'type'           => $request->type,
            'colaborador_id' => $request->colaborador_id ?: null,
            'is_ti'          => $request->colaborador_id ? false : true,
            'observaciones'  => $request->observaciones,
        ]);

        return redirect()->route('cuentas.index')
            ->with('success', 'Cuenta actualizada correctamente.');
    }

    public function destroy(Account $cuenta)
    {
        $cuenta->delete();

        return redirect()->route('cuentas.index')
            ->with('success', 'Cuenta eliminada correctamente.');
    }
}
