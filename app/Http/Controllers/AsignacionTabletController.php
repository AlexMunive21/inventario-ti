<?php

namespace App\Http\Controllers;

use App\Models\AsignacionTablet;
use App\Models\Tablet;
use App\Models\Colaborador;
use Illuminate\Http\Request;

class AsignacionTabletController extends Controller
{
    public function index()
    {
        $asignaciones = AsignacionTablet::with(['tablet', 'colaborador'])
            ->latest()
            ->get();
        return view('asignaciones_tablets.index', compact('asignaciones'));
    }

    public function create()
    {
        $tablets = Tablet::where('estatus', 'disponible')->get();
        $colaboradores = Colaborador::where('activo', 1)->get();
        return view('asignaciones_tablets.create', compact('tablets', 'colaboradores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tablet_id'       => 'required|exists:tablets,id',
            'colaborador_id'  => 'required|exists:colaboradores,id',
            'fecha_asignacion'=> 'required|date',
            'observaciones'   => 'nullable|string'
        ]);

        $tablet = Tablet::findOrFail($request->tablet_id);

        if ($tablet->estatus !== 'disponible') {
            return back()->with('error', 'La tablet no está disponible.');
        }

        $yaAsignado = AsignacionTablet::where('colaborador_id', $request->colaborador_id)
            ->whereNull('fecha_devolucion')
            ->exists();

        if ($yaAsignado) {
            return back()->with('error', 'Este colaborador ya tiene una tablet asignada.');
        }

        AsignacionTablet::create([
            'tablet_id'        => $request->tablet_id,
            'colaborador_id'   => $request->colaborador_id,
            'fecha_asignacion' => $request->fecha_asignacion,
            'observaciones'    => $request->observaciones,
        ]);

        $tablet->update(['estatus' => 'asignado']);

        return redirect()->route('asignaciones-tablets.index')
            ->with('success', 'Tablet asignada correctamente.');
    }

    public function devolver(Request $request, $id)
    {
        $asignacion = AsignacionTablet::findOrFail($id);

        $asignacion->update([
            'fecha_devolucion'         => now(),
            'observaciones_devolucion' => $request->observaciones_devolucion, 
        ]);

        $asignacion->tablet->update(['estatus' => 'disponible']);

        return redirect()->back()
            ->with('success', 'Tablet devuelta correctamente.');
    }

    public function historial()
    {
        $asignaciones = AsignacionTablet::with(['tablet', 'colaborador'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('asignaciones_tablets.historial', compact('asignaciones'));
    }
}
