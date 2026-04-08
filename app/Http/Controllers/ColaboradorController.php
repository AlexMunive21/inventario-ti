<?php

namespace App\Http\Controllers;

use App\Models\Colaborador;
use App\Models\Area;
use App\Models\Ciudad;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Models\Asignacion;
use App\Models\AsignacionCelular;


class ColaboradorController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [

            // Todos pueden ver
            new Middleware(
                'role:AnalistaDS|GerenteTIDS|rh|AnalistaTI',
                only: ['index','show']
            ),

            // Crear y editar: AnalistaTI y Gerente
            new Middleware(
                'role:AnalistaTI|GerenteTIDS',
                only: ['create','store','edit','update']
            ),

            // Eliminar: SOLO Gerente
            new Middleware(
                'role:GerenteTIDS',
                only: ['destroy']
            ),
        ];
    }
    public function index()
    {
        $colaboradores = Colaborador::with(['area','ciudad'])
            ->where('activo', 1)
            ->get();

        return view('colaboradores.index', compact('colaboradores'));
    }

    public function create()
    {
        $areas = Area::where('activo',1)->get();
        $ciudades = Ciudad::where('activo',1)->get();

        return view('colaboradores.create', compact('areas','ciudades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'apellido_paterno' => 'required',
            'genero' => 'required',
            'puesto' => 'required',
            'area_id' => 'required|exists:areas,id',
            'ciudad_id' => 'required|exists:ciudades,id'
        ]);

        Colaborador::create($request->all());

        return redirect()->route('colaboradores.index')
            ->with('success','Colaborador registrado correctamente');
    }

    public function edit(Colaborador $colaborador)
    {
        $areas = Area::all();
        $ciudades = Ciudad::all();

        return view('colaboradores.edit', compact('colaborador','areas','ciudades'));
    }

    public function update(Request $request, Colaborador $colaborador)
    {
        $request->validate([
            'nombre' => 'required',
            'apellido_paterno' => 'required',
            'genero' => 'required',
            'puesto' => 'required',
            'area_id' => 'required|exists:areas,id',
            'ciudad_id' => 'required|exists:ciudades,id'
        ]);

        $colaborador->update($request->all());

        return redirect()->route('colaboradores.index')
            ->with('success','Colaborador actualizado correctamente');
    }

    public function destroy(Colaborador $colaborador)
    {
        $colaborador->update([
            'activo' => 2
        ]);

        return redirect()->route('colaboradores.index')
            ->with('success','Colaborador dado de baja correctamente');
    }

    public function show(Colaborador $colaborador)
    {
        $colaborador->load([
            'area',
            'ciudad',
            'asignacionesCelulares.celular',
            'asignacionesEquipos.equipo'
        ]);

        return view('colaboradores.show', compact('colaborador'));
    }
    public function baja(Colaborador $colaborador)
    {
        // Verificar celulares asignados
        if ($colaborador->asignacionesCelulares()->exists()) {
            return back()->with('error','El colaborador tiene un celular asignado. Debe liberarlo primero.');
        }

        // Verificar equipos asignados
        if ($colaborador->asignacionesEquipos()->exists()) {
            return back()->with('error','El colaborador tiene un equipo asignado. Debe liberarlo primero.');
        }

        // Baja lógica
        $colaborador->update([
            'activo' => 2
        ]);

        return redirect()->route('colaboradores.index')
            ->with('success','Colaborador dado de baja correctamente.');
    }
    public function bajas()
    {
        $colaboradores = Colaborador::where('activo',2)->get();

        return view('colaboradores.bajas', compact('colaboradores'));
    }
    public function reactivar(Colaborador $colaborador)
    {
        $colaborador->update([
            'activo' => 1
        ]);

        return redirect()->route('colaboradores.bajas')
            ->with('success','Colaborador reactivado.');
    }
    public function fichaRRHH($id)
    {

    $colaborador = Colaborador::findOrFail($id);

    $equipo = Asignacion::where('colaborador_id',$id)
                ->whereNull('fecha_devolucion')
                ->with('equipo')
                ->first();

    $celular = AsignacionCelular::where('colaborador_id',$id)
                ->whereNull('fecha_devolucion')
                ->with('celular')
                ->first();

    return view('colaboradores.ficha_rrhh',compact(
        'colaborador',
        'equipo',
        'celular'
    ));
    }
}