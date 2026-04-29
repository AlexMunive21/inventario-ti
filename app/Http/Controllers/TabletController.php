<?php

namespace App\Http\Controllers;

use App\Models\Tablet;
use App\Models\Area;
use App\Models\Ciudad;
use Illuminate\Http\Request;

class TabletController extends Controller
{
    public function index()
    {
        $tablets = Tablet::with(['area', 'ciudad'])->get();
        return view('tablets.index', compact('tablets'));
    }

    public function create()
    {
        $areas = Area::where('activo', 1)->get();
        $ciudades = Ciudad::where('activo', 1)->get();
        return view('tablets.create', compact('areas', 'ciudades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'area_id'      => 'required|exists:areas,id',
            'ciudad_id'    => 'required|exists:ciudades,id',
            'marca'        => 'required',
            'modelo'       => 'required',
            'numero_serie' => 'required|unique:tablets,numero_serie',
            'estatus'      => 'required'
        ]);

        Tablet::create($request->all());

        return redirect()->route('tablets.index')
            ->with('success', 'Tablet registrada correctamente.');
    }

    public function edit(Tablet $tablet)
    {
        $areas = Area::where('activo', 1)->get();
        $ciudades = Ciudad::where('activo', 1)->get();
        return view('tablets.edit', compact('tablet', 'areas', 'ciudades'));
    }

    public function update(Request $request, Tablet $tablet)
    {
        $request->validate([
            'area_id'      => 'required|exists:areas,id',
            'ciudad_id'    => 'required|exists:ciudades,id',
            'marca'        => 'required',
            'modelo'       => 'required',
            'numero_serie' => 'required|unique:tablets,numero_serie,' . $tablet->id,
            'estatus'      => 'required'
        ]);

        $tablet->update($request->all());

        return redirect()->route('tablets.index')
            ->with('success', 'Tablet actualizada correctamente.');
    }

    public function destroy(Tablet $tablet)
    {
        $tieneAsignacion = \App\Models\AsignacionTablet::where('tablet_id', $tablet->id)
            ->whereNull('fecha_devolucion')
            ->exists();

        if ($tieneAsignacion) {
            return redirect()->route('tablets.index')
                ->with('error', 'No puedes dar de baja esta tablet porque tiene una asignación activa. Ve a Asignaciones Tablets y libérala primero.');
        }

        if ($tablet->estatus === 'baja') {
            return redirect()->route('tablets.index')
                ->with('error', 'Esta tablet ya está dada de baja.');
        }

        $tablet->update(['estatus' => 'baja']);

        return redirect()->route('tablets.index')
            ->with('success', 'Tablet dada de baja correctamente.');
    }

    public function show(Tablet $tablet)
    {
        $tablet->load('asignaciones.colaborador');
        return view('tablets.show', compact('tablet'));
    }
}
