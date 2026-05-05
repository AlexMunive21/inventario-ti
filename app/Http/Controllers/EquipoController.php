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
        // Verificar por asignación activa en BD, no solo por estatus
        $tieneAsignacion = \App\Models\Asignacion::where('equipo_id', $equipo->id)
            ->where('activa', 1)
            ->exists();

        if ($tieneAsignacion) {
            return redirect()->route('equipos.index')
                ->with('error', 'No puedes dar de baja este equipo porque tiene una asignación activa. Ve a Asignaciones y libéralo primero.');
        }

        if ($equipo->estatus === 'baja') {
            return redirect()->route('equipos.index')
                ->with('error', 'Este equipo ya está dado de baja.');
        }

        $equipo->update(['estatus' => 'baja']);

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
        ->where('activa', 1)
        ->with('colaborador')
        ->first();

    if (!$asignacion) {
        return back()->with('error', 'El equipo no tiene colaborador asignado.');
    }

    // ✅ Usar el sistema de templates
    $template = \App\Models\DocumentTemplate::where('tipo', 'responsiva_equipo')
        ->latest()->first();

    if (!$template) {
        return back()->with('error', 'No hay template de responsiva subido. Ve a Templates y súbelo primero.');
    }

    // ✅ Ruta correcta
    $rutaTemplate = storage_path('app/templates/' . $template->archivo);

    if (!\Illuminate\Support\Facades\File::exists($rutaTemplate)) {
        return back()->with('error', 'El archivo del template no existe. Vuelve a subirlo en Templates.');
    }

    $colaborador = $asignacion->colaborador;
    $t = new TemplateProcessor($rutaTemplate);

    $t->setValue('Dia',             now()->format('d'));
    $t->setValue('Mes',             now()->translatedFormat('F'));
    $t->setValue('Anio',            now()->format('Y'));
    $t->setValue('Nombre',          $colaborador->nombre);
    $t->setValue('ApellidoMaterno', $colaborador->apellido_materno ?? '');
    $t->setValue('ApellidoPaterno', $colaborador->apellido_paterno);
    $t->setValue('Puesto',          $colaborador->puesto ?? 'N/A');
    $t->setValue('TIPO',            $equipo->tipo_equipo);
    $t->setValue('MARCA',           $equipo->marca);
    $t->setValue('MODELO',          $equipo->modelo);
    $t->setValue('SERIE',           $equipo->numero_serie);

    $tempPath = storage_path('app/temp');
    if (!File::exists($tempPath)) {
        File::makeDirectory($tempPath, 0755, true);
    }

    $fileName = 'Responsiva_' . $equipo->numero_serie . '.docx';
    $tempFile = $tempPath . '/' . $fileName;
    $t->saveAs($tempFile);

    return response()->download($tempFile)->deleteFileAfterSend(true);
}

public function pagare(Equipo $equipo)
{
    Carbon::setLocale('es');

    $asignacion = $equipo->asignaciones()
        ->where('activa', 1)
        ->with('colaborador')
        ->first();

    if (!$asignacion) {
        return back()->with('error', 'El equipo no tiene colaborador asignado.');
    }

    // Verificar que sea laptop
    if (strtolower($equipo->tipo_equipo) !== 'laptop') {
        return back()->with('error', 'Solo se generan pagarés para laptops.');
    }

    // ✅ Usar el sistema de templates
    $template = \App\Models\DocumentTemplate::where('tipo', 'pagare_laptop')
        ->latest()->first();

    if (!$template) {
        return back()->with('error', 'No hay template de pagaré subido. Ve a Templates y súbelo primero.');
    }

    // ✅ Ruta correcta
    $rutaTemplate = storage_path('app/templates/' . $template->archivo);

    if (!\Illuminate\Support\Facades\File::exists($rutaTemplate)) {
        return back()->with('error', 'El archivo del template no existe. Vuelve a subirlo en Templates.');
    }

    $colaborador = $asignacion->colaborador;
    $t = new TemplateProcessor($rutaTemplate);

    $t->setValue('Dia',             now()->format('d'));
    $t->setValue('Mes',             now()->translatedFormat('F'));
    $t->setValue('Anio',            now()->format('Y'));
    $t->setValue('Nombre',          $colaborador->nombre);
    $t->setValue('ApellidoMaterno', $colaborador->apellido_materno ?? '');
    $t->setValue('ApellidoPaterno', $colaborador->apellido_paterno);
    $t->setValue('Puesto',          $colaborador->puesto ?? 'N/A');
    $t->setValue('TIPO',            $equipo->tipo_equipo);
    $t->setValue('MARCA',           $equipo->marca);
    $t->setValue('MODELO',          $equipo->modelo);
    $t->setValue('SERIE',           $equipo->numero_serie);

    // ✅ Folio consecutivo
    $folio = \App\Models\FolioPagare::firstOrCreate(
        ['asignacion_id' => $asignacion->id, 'tipo' => 'pagare_laptop'],
        ['folio' => (\App\Models\FolioPagare::max('folio') ?? 0) + 1]
    );
    $t->setValue('PAGARE_NUM', str_pad($folio->folio, 4, '0', STR_PAD_LEFT));

    $tempPath = storage_path('app/temp');
    if (!File::exists($tempPath)) {
        File::makeDirectory($tempPath, 0755, true);
    }

    $fileName = 'Pagare_' . $equipo->numero_serie . '.docx';
    $tempFile = $tempPath . '/' . $fileName;
    $t->saveAs($tempFile);

    return response()->download($tempFile)->deleteFileAfterSend(true);
}
}
