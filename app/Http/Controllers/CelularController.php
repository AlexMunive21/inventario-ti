<?php

namespace App\Http\Controllers;

use App\Models\Celular;
use App\Models\Area;
use App\Models\Ciudad;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

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

    public function responsiva(Celular $celular)
{
    Carbon::setLocale('es');

    $asignacion = $celular->asignaciones()
        ->whereNull('fecha_devolucion')
        ->with('colaborador')
        ->first();

    if (!$asignacion) {
        return back()->with('error', 'El equipo no tiene colaborador asignado.');
    }

    // Usar el sistema de templates
    $template = \App\Models\DocumentTemplate::where('tipo', 'responsiva_celular')
        ->latest()->first();

    if (!$template) {
        return back()->with('error', 'No hay template de responsiva subido. Ve a Templates y súbelo primero.');
    }

    // Ruta correcta
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
    $t->setValue('TIPO',            $celular->tipo_equipo);
    $t->setValue('MARCA',           $celular->marca);
    $t->setValue('MODELO',          $celular->modelo);
    $t->setValue('IMEI',           $celular->imei);
    $t->setValue('NUMERO',         $celular->numero_telefono ?? 'N/A');

    $tempPath = storage_path('app/temp');
    if (!File::exists($tempPath)) {
        File::makeDirectory($tempPath, 0755, true);
    }

    $fileName = 'Responsiva_' . $celular->imei . '.docx';
    $tempFile = $tempPath . '/' . $fileName;
    $t->saveAs($tempFile);

    return response()->download($tempFile)->deleteFileAfterSend(true);
}
}