<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Area;
use App\Models\Ciudad;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;


class EquipoController extends Controller
{

    public function index()
    {
        $equipos = Equipo::with(['area', 'ciudad'])->get();
        return view('equipos.index', compact('equipos'));
    }

    public function create()
    {
        $areas = Area::where('activo', 1)->get();
        $ciudades = Ciudad::where('activo', 1)->get();

        return view('equipos.create', compact('areas', 'ciudades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'area_id' => 'required|exists:areas,id',
            'ciudad_id' => 'required|exists:ciudades,id',
            'tipo_equipo' => 'required',
            'marca' => 'required',
            'modelo' => 'required',
            'numero_serie' => 'required|unique:equipos,numero_serie',
            'estatus' => 'required'
        ]);

        Equipo::create($request->all());

        return redirect()->route('equipos.index')
            ->with('success', 'Equipo registrado correctamente');
    }

    public function edit(Equipo $equipo)
    {
        $areas = Area::all();
        $ciudades = Ciudad::all();

        return view('equipos.edit', compact('equipo', 'areas', 'ciudades'));
    }

    public function update(Request $request, Equipo $equipo)
    {
        $request->validate([
            'area_id' => 'required|exists:areas,id',
            'ciudad_id' => 'required|exists:ciudades,id',
            'tipo_equipo' => 'required',
            'marca' => 'required',
            'modelo' => 'required',
            'numero_serie' => 'required|unique:equipos,numero_serie,' . $equipo->id,
            'estatus' => 'required'
        ]);

        $equipo->update($request->all());

        return redirect()->route('equipos.index')
            ->with('success', 'Equipo actualizado correctamente');
    }

    public function destroy(Equipo $equipo)
    {
        if ($equipo->estatus === 'asignado') {
            return redirect()->route('equipos.index')
                ->with('error', 'No puedes dar de baja un equipo que está asignado. Primero debes liberarlo.');
        }

        if ($equipo->estatus === 'baja') {
            return redirect()->route('equipos.index')
                ->with('error', 'Este equipo ya está dado de baja.');
        }

        $equipo->update([
            'estatus' => 'baja'
        ]);

        return redirect()->route('equipos.index')
            ->with('success', 'Equipo dado de baja correctamente.');
    }

        public function show(Equipo $equipo)
        {
            $equipo->load('asignaciones.colaborador');

            return view('equipos.show', compact('equipo'));
        }

        public function responsiva(Equipo $equipo)
    {
        Carbon::setLocale('es');

        $asignacion = $equipo->asignaciones()
            ->where('activa',1)
            ->with('colaborador')
            ->first();

        if(!$asignacion){
            return back()->with('error','El equipo no tiene colaborador asignado');
        }

        $colaborador = $asignacion->colaborador;

        $template = new TemplateProcessor(
            storage_path('app/templates/responsiva_template.docx')
        );

        $template->setValue('Dia', now()->format('d'));
        $template->setValue('Mes', now()->translatedFormat('F'));
        $template->setValue('Anio', now()->format('Y'));

        $template->setValue('Nombre', $colaborador->nombre);
        $template->setValue('ApellidoMaterno', $colaborador->apellido_materno);
        $template->setValue('ApellidoPaterno', $colaborador->apellido_paterno);
        $template->setValue('Puesto', $colaborador->puesto ?? 'N/A');

        $template->setValue('TIPO', $equipo->tipo_equipo);
        $template->setValue('MARCA', $equipo->marca);
        $template->setValue('MODELO', $equipo->modelo);
        $template->setValue('SERIE', $equipo->numero_serie);

        $fileName = "Responsiva_".$equipo->numero_serie.".docx";

        $tempPath = storage_path('app/temp');

        if(!File::exists($tempPath)){
            File::makeDirectory($tempPath, 0755, true);
        }

        $tempFile = $tempPath.'/'.$fileName;

        $template->saveAs($tempFile);

        return response()->download($tempFile)->deleteFileAfterSend(true);
    }
}
