<?php

namespace App\Http\Controllers;

use App\Models\Componente;
use App\Models\Area;
use App\Models\Ciudad;
use Illuminate\Http\Request;

class ComponenteController extends Controller
{
    public function index()
    {
        $cpus     = Componente::with(['area', 'ciudad'])->where('tipo', 'cpu')->get();
        $monitores = Componente::with(['area', 'ciudad'])->where('tipo', 'monitor')->get();

        return view('componentes.index', compact('cpus', 'monitores'));
    }

    public function create()
    {
        $areas    = Area::where('activo', 1)->get();
        $ciudades = Ciudad::where('activo', 1)->get();

        return view('componentes.create', compact('areas', 'ciudades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo'         => 'required|in:cpu,monitor',
            'marca'        => 'required',
            'modelo'       => 'required',
            'numero_serie' => 'required|unique:componentes,numero_serie',
            'area_id'      => 'required|exists:areas,id',
            'ciudad_id'    => 'required|exists:ciudades,id',
            'estatus'      => 'required',
        ]);

        Componente::create($request->all());

        return redirect()->route('componentes.index')
            ->with('success', ucfirst($request->tipo) . ' registrado correctamente.');
    }

    public function edit(Componente $componente)
    {
        $areas    = Area::where('activo', 1)->get();
        $ciudades = Ciudad::where('activo', 1)->get();

        return view('componentes.edit', compact('componente', 'areas', 'ciudades'));
    }

    public function update(Request $request, Componente $componente)
    {
        $request->validate([
            'marca'        => 'required',
            'modelo'       => 'required',
            'numero_serie' => 'required|unique:componentes,numero_serie,' . $componente->id,
            'area_id'      => 'required|exists:areas,id',
            'ciudad_id'    => 'required|exists:ciudades,id',
            'estatus'      => 'required',
        ]);

        $componente->update($request->all());

        return redirect()->route('componentes.index')
            ->with('success', 'Componente actualizado correctamente.');
    }

    public function destroy(Componente $componente)
    {
        $componente->update(['estatus' => 'baja']);

        return redirect()->route('componentes.index')
            ->with('success', 'Componente dado de baja correctamente.');
    }
}