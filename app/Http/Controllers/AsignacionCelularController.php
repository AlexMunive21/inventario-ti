<?php

namespace App\Http\Controllers;

use App\Models\AsignacionCelular;
use App\Models\Celular;
use App\Models\Colaborador;
use Illuminate\Http\Request;

class AsignacionCelularController extends Controller
{
    public function index()
    {
        $asignaciones = AsignacionCelular::with(['celular', 'colaborador'])
            ->latest()
            ->get();

        return view('asignaciones_celulares.index', compact('asignaciones'));
    }

    public function create()
    {
        $celulares = Celular::where('estatus', 'disponible')->get();
        $colaboradores = Colaborador::where('activo', 1)->get();

        return view('asignaciones_celulares.create', compact('celulares', 'colaboradores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'celular_id' => 'required|exists:celulares,id',
            'colaborador_id' => 'required|exists:colaboradores,id',
            'fecha_asignacion' => 'required|date',
            'observaciones' => 'nullable|string'
        ]);

        $celular = Celular::findOrFail($request->celular_id);

        if ($celular->estatus !== 'disponible') {
            return redirect()->back()->with('error', 'El celular no está disponible.');
        }

        // NUEVO — evita doble asignación
        $yaAsignado = AsignacionCelular::where('colaborador_id', $request->colaborador_id)
            ->whereNull('fecha_devolucion')
            ->exists();

        if ($yaAsignado) {
            return redirect()->back()->with('error', 'Este colaborador ya tiene un celular asignado.');
        }

        AsignacionCelular::create([
            'celular_id' => $request->celular_id,
            'colaborador_id' => $request->colaborador_id,
            'fecha_asignacion' => $request->fecha_asignacion,
            'observaciones' => $request->observaciones,
        ]);

        $celular->update(['estatus' => 'asignado']);

        return redirect()->route('asignaciones-celulares.index')
            ->with('success', 'Celular asignado correctamente.');
    }

    public function devolver(Request $request, $id)
    {
        $asignacion = AsignacionCelular::findOrFail($id);

        $asignacion->update([
            'fecha_devolucion'         => now(),
            'observaciones_devolucion' => $request->observaciones_devolucion, // ✅
        ]);

        $asignacion->celular->update(['estatus' => 'disponible']);

        return redirect()->back()
            ->with('success', 'Celular devuelto correctamente.');
    }
    public function historial()
    {
        $asignaciones = AsignacionCelular::with(['celular', 'colaborador'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('asignaciones_celulares.historial', compact('asignaciones'));
    }
}