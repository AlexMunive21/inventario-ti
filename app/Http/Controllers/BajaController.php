<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Celular;
use App\Models\Tablet;
use App\Models\Componente;
use App\Models\Periferico;
use App\Models\ReactivacionEquipo;
use App\Models\ReactivacionCelular;
use App\Models\ReactivacionTablet;
use Illuminate\Http\Request;

class BajaController extends Controller
{
    public function index()
    {
        $equipos    = Equipo::with('area')->where('estatus', 'baja')->get();
        $celulares  = Celular::with('area')->where('estatus', 'baja')->get();
        $tablets    = Tablet::with('area')->where('estatus', 'baja')->get();
        $cpus       = Componente::with('area')->where('tipo', 'cpu')->where('estatus', 'baja')->get();
        $monitores  = Componente::with('area')->where('tipo', 'monitor')->where('estatus', 'baja')->get();
        $perifericos = Periferico::with('area')
            ->whereRaw('cantidad_total < cantidad_total + 1') // todos
            ->where('cantidad_disponible', 0)
            ->get();

        return view('bajas.index', compact(
            'equipos', 'celulares', 'tablets',
            'cpus', 'monitores', 'perifericos'
        ));
    }

    public function reactivarEquipo(Request $request, Equipo $equipo)
    {
        $request->validate([
            'tipo_reparacion'    => 'required',
            'fecha_reactivacion' => 'required|date',
            'autorizo'           => 'required|string|max:255',
        ]);

        ReactivacionEquipo::create([
            'equipo_id'          => $equipo->id,
            'user_id'            => auth()->id(),
            'tipo_reparacion'    => $request->tipo_reparacion,
            'fecha_reactivacion' => $request->fecha_reactivacion,
            'autorizo'           => $request->autorizo,
        ]);

        $equipo->update(['estatus' => 'disponible']);

        return redirect()->route('bajas.index')
            ->with('success', 'Equipo reactivado correctamente.');
    }

    public function reactivarCelular(Request $request, Celular $celular)
    {
        $request->validate([
            'tipo_reparacion'    => 'required',
            'fecha_reactivacion' => 'required|date',
            'autorizo'           => 'required|string|max:255',
        ]);

        ReactivacionCelular::create([
            'celular_id'         => $celular->id,
            'user_id'            => auth()->id(),
            'tipo_reparacion'    => $request->tipo_reparacion,
            'fecha_reactivacion' => $request->fecha_reactivacion,
            'autorizo'           => $request->autorizo,
        ]);

        $celular->update(['estatus' => 'disponible']);

        return redirect()->route('bajas.index')
            ->with('success', 'Celular reactivado correctamente.');
    }

    public function reactivarTablet(Request $request, Tablet $tablet)
    {
        $request->validate([
            'tipo_reparacion'    => 'required',
            'fecha_reactivacion' => 'required|date',
            'autorizo'           => 'required|string|max:255',
        ]);

        ReactivacionTablet::create([
            'tablet_id'          => $tablet->id,
            'user_id'            => auth()->id(),
            'tipo_reparacion'    => $request->tipo_reparacion,
            'fecha_reactivacion' => $request->fecha_reactivacion,
            'autorizo'           => $request->autorizo,
        ]);

        $tablet->update(['estatus' => 'disponible']);

        return redirect()->route('bajas.index')
            ->with('success', 'Tablet reactivada correctamente.');
    }

    public function reactivarComponente(Request $request, Componente $componente)
    {
        $request->validate([
            'tipo_reparacion'    => 'required',
            'fecha_reactivacion' => 'required|date',
            'autorizo'           => 'required|string|max:255',
        ]);

        $componente->update(['estatus' => 'disponible']);

        return redirect()->route('bajas.index')
            ->with('success', ucfirst($componente->tipo) . ' reactivado correctamente.');
    }
}