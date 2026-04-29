<?php

namespace App\Http\Controllers;

use App\Models\EquipoEscritorio;
use App\Models\Componente;
use App\Models\Periferico;
use App\Models\Area;
use App\Models\Ciudad;
use App\Models\Colaborador;
use App\Models\AsignacionEscritorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EquipoEscritorioController extends Controller
{
    public function index()
    {
        $equipos = EquipoEscritorio::with(['cpu', 'monitores', 'perifericos', 'area', 'ciudad'])
            ->get();

        return view('equipos_escritorio.index', compact('equipos'));
    }

    public function create()
    {
        $cpus      = Componente::where('tipo', 'cpu')->where('estatus', 'disponible')->get();
        $monitores = Componente::where('tipo', 'monitor')->where('estatus', 'disponible')->get();
        $teclados  = Periferico::where('tipo', 'teclado')->where('cantidad_disponible', '>', 0)->get();
        $mouses    = Periferico::where('tipo', 'mouse')->where('cantidad_disponible', '>', 0)->get();
        $areas     = Area::where('activo', 1)->get();
        $ciudades  = Ciudad::where('activo', 1)->get();

        return view('equipos_escritorio.create', compact(
            'cpus', 'monitores', 'teclados', 'mouses', 'areas', 'ciudades'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'        => 'required|string|max:255',
            'cpu_id'        => 'required|exists:componentes,id',
            'monitores'     => 'required|array|min:1',
            'monitores.*'   => 'exists:componentes,id',
            'teclado_id'    => 'required|exists:perifericos,id',
            'mouse_id'      => 'required|exists:perifericos,id',
            'area_id'       => 'required|exists:areas,id',
            'ciudad_id'     => 'required|exists:ciudades,id',
        ]);

        DB::transaction(function () use ($request) {
            // Crear el equipo
            $equipo = EquipoEscritorio::create([
                'nombre'    => $request->nombre,
                'cpu_id'    => $request->cpu_id,
                'area_id'   => $request->area_id,
                'ciudad_id' => $request->ciudad_id,
                'estatus'   => 'disponible',
                'observaciones' => $request->observaciones,
            ]);

            // Asociar monitores
            $equipo->monitores()->attach($request->monitores);

            // Asociar teclado y mouse
            $equipo->perifericos()->attach([
                $request->teclado_id => ['cantidad' => 1],
                $request->mouse_id   => ['cantidad' => 1],
            ]);

            // Marcar componentes como en_uso
            Componente::whereIn('id', array_merge(
                [$request->cpu_id],
                $request->monitores
            ))->update(['estatus' => 'en_uso']);

            // Reducir disponibles de periféricos
            Periferico::find($request->teclado_id)->decrement('cantidad_disponible');
            Periferico::find($request->mouse_id)->decrement('cantidad_disponible');
        });

        return redirect()->route('equipos-escritorio.index')
            ->with('success', 'Equipo de escritorio armado correctamente.');
    }

    public function show(EquipoEscritorio $equipoEscritorio)
    {
        $equipoEscritorio->load([
            'cpu', 'monitores', 'perifericos',
            'area', 'ciudad',
            'asignaciones.colaborador'
        ]);

        $asignacionActiva = $equipoEscritorio->asignaciones()
            ->where('activa', 1)
            ->with('colaborador')
            ->first();

        return view('equipos_escritorio.show', compact('equipoEscritorio', 'asignacionActiva'));
    }

    // Cambiar un componente (CPU o monitor)
    public function cambiarComponente(Request $request, EquipoEscritorio $equipoEscritorio)
    {
        $request->validate([
            'tipo'              => 'required|in:cpu,monitor',
            'componente_viejo_id' => 'required|exists:componentes,id',
            'componente_nuevo_id' => 'required|exists:componentes,id',
        ]);

        DB::transaction(function () use ($request, $equipoEscritorio) {
            $viejo = Componente::findOrFail($request->componente_viejo_id);
            $nuevo = Componente::findOrFail($request->componente_nuevo_id);

            if ($request->tipo === 'cpu') {
                $equipoEscritorio->update(['cpu_id' => $nuevo->id]);
            } else {
                $equipoEscritorio->monitores()->detach($viejo->id);
                $equipoEscritorio->monitores()->attach($nuevo->id);
            }

            $viejo->update(['estatus' => 'disponible']);
            $nuevo->update(['estatus' => 'en_uso']);
        });

        return redirect()->back()
            ->with('success', 'Componente reemplazado correctamente.');
    }

    // Asignar a colaborador
    public function asignar(Request $request, EquipoEscritorio $equipoEscritorio)
    {
        $request->validate([
            'colaborador_id'   => 'required|exists:colaboradores,id',
            'fecha_asignacion' => 'required|date',
        ]);

        if ($equipoEscritorio->estatus !== 'disponible') {
            return back()->with('error', 'Este equipo no está disponible.');
        }

        AsignacionEscritorio::create([
            'equipo_escritorio_id' => $equipoEscritorio->id,
            'colaborador_id'       => $request->colaborador_id,
            'fecha_asignacion'     => $request->fecha_asignacion,
            'observaciones'        => $request->observaciones,
            'activa'               => 1,
        ]);

        $equipoEscritorio->update(['estatus' => 'asignado']);

        return redirect()->route('equipos-escritorio.show', $equipoEscritorio)
            ->with('success', 'Equipo asignado correctamente.');
    }

    // Liberar asignación
    public function liberar(Request $request, EquipoEscritorio $equipoEscritorio)
    {
        $asignacion = $equipoEscritorio->asignaciones()
            ->where('activa', 1)
            ->firstOrFail();

        $asignacion->update([
            'activa'                   => 0,
            'fecha_devolucion'         => now(),
            'observaciones_devolucion' => $request->observaciones_devolucion,
        ]);

        $equipoEscritorio->update(['estatus' => 'disponible']);

        return redirect()->route('equipos-escritorio.show', $equipoEscritorio)
            ->with('success', 'Equipo liberado correctamente.');
    }

    // Cambiar un periférico (teclado o mouse)
    public function cambiarPeriferico(Request $request, EquipoEscritorio $equipoEscritorio)
    {
        $request->validate([
            'periferico_viejo_id' => 'required|exists:perifericos,id',
            'periferico_nuevo_id' => 'required|exists:perifericos,id',
        ]);

        DB::transaction(function () use ($request, $equipoEscritorio) {
            $viejo = Periferico::findOrFail($request->periferico_viejo_id);
            $nuevo = Periferico::findOrFail($request->periferico_nuevo_id);

            // Desvincula el viejo y devuelve cantidad
            $equipoEscritorio->perifericos()->detach($viejo->id);
            $viejo->increment('cantidad_disponible');

            // Vincula el nuevo y descuenta cantidad
            $equipoEscritorio->perifericos()->attach($nuevo->id, ['cantidad' => 1]);
            $nuevo->decrement('cantidad_disponible');
        });

        return redirect()->back()
            ->with('success', 'Periférico reemplazado correctamente.');
    }
    public function destroy(EquipoEscritorio $equipoEscritorio)
    {
        // Verificar si tiene asignación activa
        $tieneAsignacion = $equipoEscritorio->asignaciones()
            ->where('activa', 1)
            ->exists();

        if ($tieneAsignacion) {
            return redirect()->route('equipos-escritorio.show', $equipoEscritorio)
                ->with('error', 'No puedes dar de baja este equipo porque tiene una asignación activa. Libéralo primero.');
        }

        if ($equipoEscritorio->estatus === 'baja') {
            return redirect()->route('equipos-escritorio.index')
                ->with('error', 'Este equipo ya está dado de baja.');
        }

        // Liberar componentes al dar de baja
        $equipoEscritorio->cpu()->update(['estatus' => 'disponible']);
        $equipoEscritorio->monitores()->update(['estatus' => 'disponible']);

        // Devolver periféricos
        foreach ($equipoEscritorio->perifericos as $periferico) {
            $periferico->increment('cantidad_disponible');
        }

        $equipoEscritorio->update(['estatus' => 'baja']);

        return redirect()->route('equipos-escritorio.index')
            ->with('success', 'Equipo de escritorio dado de baja correctamente.');
    }
}