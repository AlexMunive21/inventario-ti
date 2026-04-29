<?php

namespace App\Http\Controllers;

use App\Models\Celular;
use App\Models\Area;
use App\Models\Ciudad;
use Illuminate\Http\Request;

class CelularController extends Controller
{
    public function index()
    {
        $celulares = Celular::with(['area', 'ciudad'])->get();

        return view('celulares.index', compact('celulares'));
    }

    public function create()
    {
        $areas = Area::where('activo', 1)->get();
        $ciudades = Ciudad::where('activo', 1)->get();

        return view('celulares.create', compact('areas', 'ciudades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'area_id' => 'required|exists:areas,id',
            'ciudad_id' => 'required|exists:ciudades,id',
            'marca' => 'required|string|max:100',
            'modelo' => 'required|string|max:100',
            'imei' => 'required|string|unique:celulares,imei',
            'numero_telefono' => 'nullable|string|max:20',
            'estatus' => 'required|in:disponible,asignado,mantenimiento,baja',
            'observaciones' => 'nullable|string'
        ]);

        Celular::create($request->all());

        return redirect()->route('celulares.index')
            ->with('success', 'Celular registrado correctamente.');
    }

    public function show(Celular $celular)
    {
        return view('celulares.show', compact('celular'));
    }

    public function edit(Celular $celular)
    {
        $areas = Area::where('activo', 1)->get();
        $ciudades = Ciudad::where('activo', 1)->get();

        return view('celulares.edit', compact('celular', 'areas', 'ciudades'));
    }

    public function update(Request $request, Celular $celular)
    {
        $request->validate([
            'area_id' => 'required|exists:areas,id',
            'ciudad_id' => 'required|exists:ciudades,id',
            'marca' => 'required|string|max:100',
            'modelo' => 'required|string|max:100',
            'imei' => 'required|string|unique:celulares,imei,' . $celular->id,
            'numero_telefono' => 'nullable|string|max:20',
            'estatus' => 'required|in:disponible,asignado,mantenimiento,baja',
            'observaciones' => 'nullable|string'
        ]);

        $celular->update($request->all());

        return redirect()->route('celulares.index')
            ->with('success', 'Celular actualizado correctamente.');
    }

    public function destroy(Celular $celular)
    {
        $tieneAsignacion = \App\Models\AsignacionCelular::where('celular_id', $celular->id)
            ->whereNull('fecha_devolucion')
            ->exists();

        if ($tieneAsignacion) {
            return redirect()->route('celulares.index')
                ->with('error', 'No puedes dar de baja este celular porque tiene una asignación activa. Ve a Asignaciones Celulares y libéralo primero.');
        }

        if ($celular->estatus === 'baja') {
            return redirect()->route('celulares.index')
                ->with('error', 'Este celular ya está dado de baja.');
        }

        $celular->update(['estatus' => 'baja']);

        return redirect()->route('celulares.index')
            ->with('success', 'Celular dado de baja correctamente.');
    }
}