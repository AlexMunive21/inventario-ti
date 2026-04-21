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
        $cuentas = Account::with('colaborador')->get();
        $colaboradores = \App\Models\Colaborador::all();

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
            'name' => $request->name,
            'username' => $request->username,
            'password' => $request->password,
            'type' => $request->type,
            'colaborador_id' => $request->colaborador_id,
            'is_ti' => $request->colaborador_id ? false : true,
        ]);

        return redirect()->route('cuentas.index')
            ->with('success', 'Cuenta creada correctamente');
    }
}
