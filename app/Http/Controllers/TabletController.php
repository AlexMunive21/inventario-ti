<?php

namespace App\Http\Controllers;

use App\Models\Tablet;
use App\Models\Area;
use App\Models\Ciudad;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\File;

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

 public function responsiva(Tablet $tablet)
{
    Carbon::setLocale('es');

    // ✅ Faltaba .first()
    $asignacion = $tablet->asignaciones()
        ->whereNull('fecha_devolucion')
        ->with('colaborador')
        ->first();

    if (!$asignacion) {
        return back()->with('error', 'La tablet no tiene colaborador asignado.');
    }

    $template = \App\Models\DocumentTemplate::where('tipo', 'responsiva_tablet')
        ->latest()->first();

    if (!$template) {
        return back()->with('error', 'No hay template de responsiva subido. Ve a Templates y súbelo primero.');
    }

    $rutaTemplate = storage_path('app/templates/' . $template->archivo);

    if (!File::exists($rutaTemplate)) {
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
    $t->setValue('MARCA',           $tablet->marca);
    $t->setValue('MODELO',          $tablet->modelo);
    $t->setValue('SERIE',           $tablet->numero_serie);

    $tempPath = storage_path('app/temp');
    if (!File::exists($tempPath)) {
        File::makeDirectory($tempPath, 0755, true);
    }

    $fileName = 'Responsiva_' . $tablet->numero_serie . '.docx';
    $tempFile = $tempPath . '/' . $fileName;
    $t->saveAs($tempFile);

    return response()->download($tempFile)->deleteFileAfterSend(true);
}

public function pagare(Tablet $tablet)
{
    Carbon::setLocale('es');

    // ✅ Faltaba .first()
    $asignacion = $tablet->asignaciones()
        ->whereNull('fecha_devolucion')
        ->with('colaborador')
        ->first();

    if (!$asignacion) {
        return back()->with('error', 'La tablet no tiene colaborador asignado.');
    }

    $template = \App\Models\DocumentTemplate::where('tipo', 'pagare_tablet')
        ->latest()->first();

    if (!$template) {
        return back()->with('error', 'No hay template de pagaré subido. Ve a Templates y súbelo primero.');
    }

    $rutaTemplate = storage_path('app/templates/' . $template->archivo);

    if (!File::exists($rutaTemplate)) {
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
    $t->setValue('MARCA',           $tablet->marca);
    $t->setValue('MODELO',          $tablet->modelo);
    $t->setValue('SERIE',           $tablet->numero_serie);

    // ✅ Tipo correcto pagare_tablet
    $folio = \App\Models\FolioPagare::firstOrCreate(
        ['asignacion_id' => $asignacion->id, 'tipo' => 'pagare_tablet'],
        ['folio' => (\App\Models\FolioPagare::max('folio') ?? 0) + 1]
    );
    $t->setValue('PAGARE_NUM', str_pad($folio->folio, 4, '0', STR_PAD_LEFT));

    $tempPath = storage_path('app/temp');
    if (!File::exists($tempPath)) {
        File::makeDirectory($tempPath, 0755, true);
    }

    $fileName = 'Pagare_' . $tablet->numero_serie . '.docx';
    $tempFile = $tempPath . '/' . $fileName;
    $t->saveAs($tempFile);

    return response()->download($tempFile)->deleteFileAfterSend(true);
}
}
