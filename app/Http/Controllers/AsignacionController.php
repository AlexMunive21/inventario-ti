<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\Equipo;
use App\Models\Colaborador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsignacionController extends Controller
{
    public function index()
    {
        $asignaciones = Asignacion::with(['equipo','colaborador'])
            ->where('activa',1)
            ->orderBy('created_at','desc')
            ->get();

        return view('asignaciones.index', compact('asignaciones'));
    }

    public function create()
    {
        $equipos = Equipo::where('estatus','disponible')->get();
        $colaboradores = Colaborador::where('activo',1)->get();

        return view('asignaciones.create', compact('equipos','colaboradores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'equipo_id' => 'required|exists:equipos,id',
            'colaborador_id' => 'required|exists:colaboradores,id',
            'fecha_asignacion' => 'required|date',
        ]);

        // NUEVO — transacción con bloqueo pesimista
        DB::transaction(function () use ($request) {
            $equipo = Equipo::lockForUpdate()->findOrFail($request->equipo_id);

            if ($equipo->estatus !== 'disponible') {
                throw new \Exception('El equipo ya no está disponible.');
            }

            Asignacion::create([
                'equipo_id' => $request->equipo_id,
                'colaborador_id' => $request->colaborador_id,
                'fecha_asignacion' => $request->fecha_asignacion,
                'observaciones' => $request->observaciones,
                'activa' => 1
            ]);

            $equipo->estatus = 'asignado';
            $equipo->save();
        });

        return redirect()->route('asignaciones.index')
            ->with('success', 'Equipo asignado correctamente');
    }

    public function destroy(Request $request, $id)
    {
        $asignacion = Asignacion::findOrFail($id);

        $equipo = Equipo::find($asignacion->equipo_id);
        if ($equipo) {
            $equipo->estatus = 'disponible';
            $equipo->save();
        }

        $asignacion->activa = 0;
        $asignacion->fecha_devolucion = now();
        $asignacion->observaciones_devolucion = $request->observaciones_devolucion;
        $asignacion->save();

        return redirect()->route('asignaciones.index')
            ->with('success', 'Equipo liberado correctamente.');
    }

    public function historial()
    {
        $asignaciones = Asignacion::with(['equipo','colaborador'])
            ->orderBy('created_at','desc')
            ->get();

        return view('asignaciones.historial', compact('asignaciones'));
    }
}