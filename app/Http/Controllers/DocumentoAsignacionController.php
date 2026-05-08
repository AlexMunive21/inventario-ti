<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\AsignacionCelular;
use App\Models\AsignacionTablet;
use App\Models\AsignacionEscritorio;
use App\Models\DocumentTemplate;
use App\Models\FolioPagare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class DocumentoAsignacionController extends Controller
{
    // ── Helpers privados ──────────────────────────────────

    private function prepararTemp(): string
    {
        $path = storage_path('app/temp');
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }
        return $path;
    }

    private function llenarColaborador(TemplateProcessor $p, $colaborador): void
    {
        Carbon::setLocale('es');
        $p->setValue('Dia',             now()->format('d'));
        $p->setValue('Mes',             now()->translatedFormat('F'));
        $p->setValue('Anio',            now()->format('Y'));
        $p->setValue('Nombre',          $colaborador->nombre);
        $p->setValue('ApellidoPaterno', $colaborador->apellido_paterno);
        $p->setValue('ApellidoMaterno', $colaborador->apellido_materno ?? '');
        $p->setValue('Puesto',          $colaborador->puesto ?? 'N/A');
    }

    private function descargarDocx(TemplateProcessor $p, string $nombre): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $temp = $this->prepararTemp() . '/' . $nombre;
        $p->saveAs($temp);
        return response()->download($temp)->deleteFileAfterSend(true);
    }

    private function getTemplate(string $tipo)
    {
        return DocumentTemplate::where('tipo', $tipo)->latest()->first();
    }

    // ── Generar documentos ────────────────────────────────

    public function generarEquipo(Asignacion $asignacion, string $tipo)
    {
        $template = $this->getTemplate($tipo);
        if (!$template) {
            return back()->with('error', 'No hay template de ese tipo subido aún. Contacta al administrador.');
        }

        $colaborador = $asignacion->colaborador;
        $equipo      = $asignacion->equipo;

        $p = new TemplateProcessor(storage_path('app/templates/' . $template->archivo));
        $this->llenarColaborador($p, $colaborador);
        $p->setValue('TIPO',   $equipo->tipo_equipo ?? '');
        $p->setValue('MARCA',  $equipo->marca);
        $p->setValue('MODELO', $equipo->modelo);
        $p->setValue('SERIE',  $equipo->numero_serie);

        // Folio de pagaré — solo si es tipo pagaré
        if (in_array($tipo, ['pagare_laptop', 'pagare_tablet'])) {
            $folio = \App\Models\FolioPagare::firstOrCreate(
                ['asignacion_id' => $asignacion->id, 'tipo' => $tipo],
                ['folio' => (\App\Models\FolioPagare::max('folio') ?? 0) + 1]
            );
            $p->setValue('PAGARE_NUM', str_pad($folio->folio, 4, '0', STR_PAD_LEFT));
        }

        return $this->descargarDocx($p, $tipo . '_' . $equipo->numero_serie . '.docx');
    }

    public function generarCelular(AsignacionCelular $asignacion, string $tipo)
    {
        $template = $this->getTemplate($tipo);
        if (!$template) {
            return back()->with('error', 'No hay template de ese tipo subido aún.');
        }

        $colaborador = $asignacion->colaborador;
        $celular     = $asignacion->celular;

        $p = new TemplateProcessor(storage_path('app/templates/' . $template->archivo));
        $this->llenarColaborador($p, $colaborador);
        $p->setValue('MARCA',  $celular->marca);
        $p->setValue('MODELO', $celular->modelo);
        $p->setValue('IMEI',   $celular->imei);

        return $this->descargarDocx($p, $tipo . '_' . $celular->imei . '.docx');
    }

    public function generarTablet(AsignacionTablet $asignacion, string $tipo)
    {
        $template = $this->getTemplate($tipo);
        if (!$template) {
            return back()->with('error', 'No hay template de ese tipo subido aún.');
        }

        $colaborador = $asignacion->colaborador;
        $tablet      = $asignacion->tablet;

        $p = new TemplateProcessor(storage_path('app/templates/' . $template->archivo));
        $this->llenarColaborador($p, $colaborador);
        $p->setValue('MARCA',  $tablet->marca);
        $p->setValue('MODELO', $tablet->modelo);
        $p->setValue('SERIE',  $tablet->numero_serie);

        return $this->descargarDocx($p, $tipo . '_' . $tablet->numero_serie . '.docx');
    }

    public function generarEscritorio(AsignacionEscritorio $asignacion, string $tipo)
    {
        $template = $this->getTemplate($tipo);
        if (!$template) {
            return back()->with('error', 'No hay template de ese tipo subido aún.');
        }

        $colaborador    = $asignacion->colaborador;
        $equipo         = $asignacion->equipoEscritorio;

        $p = new TemplateProcessor(storage_path('app/templates/' . $template->archivo));
        $this->llenarColaborador($p, $colaborador);
        $p->setValue('MARCA',  $equipo->cpu->marca ?? '');
        $p->setValue('MODELO', $equipo->cpu->modelo ?? '');
        $p->setValue('SERIE',  $equipo->cpu->numero_serie ?? '');
        $p->setValue('NOMBRE_EQUIPO', $equipo->nombre);

        // Monitores — lista separada por comas
        $monitores = $equipo->monitores->map(fn($m) => $m->marca . ' ' . $m->modelo)->implode(', ');
        $p->setValue('MONITORES', $monitores ?: 'N/A');

        return $this->descargarDocx($p, $tipo . '_' . $equipo->id . '.docx');
    }

    // ── Subir PDF firmado ─────────────────────────────────

    private function subirPdf(Request $request, $asignacion, string $prefijo): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'pdf_firmado' => 'required|file|mimes:pdf|max:10240',
        ]);

        // Eliminar PDF anterior si existe
        if ($asignacion->pdf_firmado) {
            Storage::delete('documentos_firmados/' . $asignacion->pdf_firmado);
        }

        $nombre = $prefijo . '_' . $asignacion->id . '_' . now()->format('Ymd_His') . '.pdf';
        // Después
        $destino = storage_path('app/documentos_firmados');
        if (!\Illuminate\Support\Facades\File::exists($destino)) {
            \Illuminate\Support\Facades\File::makeDirectory($destino, 0755, true);
        }
        $request->file('pdf_firmado')->move($destino, $nombre);

        $asignacion->update(['pdf_firmado' => $nombre]);

        return redirect()->back()->with('success', 'PDF firmado subido correctamente.');
    }

    public function subirPdfEquipo(Request $request, Asignacion $asignacion)
    {
        return $this->subirPdf($request, $asignacion, 'equipo');
    }

    public function subirPdfCelular(Request $request, AsignacionCelular $asignacion)
    {
        return $this->subirPdf($request, $asignacion, 'celular');
    }

    public function subirPdfTablet(Request $request, AsignacionTablet $asignacion)
    {
        return $this->subirPdf($request, $asignacion, 'tablet');
    }

    public function subirPdfEscritorio(Request $request, AsignacionEscritorio $asignacion)
    {
        return $this->subirPdf($request, $asignacion, 'escritorio');
    }

    // ── Descargar PDF firmado ─────────────────────────────

    private function descargarPdf($asignacion): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        if (!$asignacion->pdf_firmado) {
            abort(404, 'No hay PDF firmado para esta asignación.');
        }
        return response()->download(storage_path('app/documentos_firmados/' . $asignacion->pdf_firmado));
    }

    public function descargarPdfEquipo(Asignacion $asignacion)
    {
        return $this->descargarPdf($asignacion);
    }

    public function descargarPdfCelular(AsignacionCelular $asignacion)
    {
        return $this->descargarPdf($asignacion);
    }

    public function descargarPdfTablet(AsignacionTablet $asignacion)
    {
        return $this->descargarPdf($asignacion);
    }

    public function descargarPdfEscritorio(AsignacionEscritorio $asignacion)
    {
        return $this->descargarPdf($asignacion);
    }

    // ── Subir PDF de pagaré ───────────────────────

    public function subirPdfPagareEquipo(Request $request, Asignacion $asignacion)
    {
        $request->validate(['pdf_pagare' => 'required|file|mimes:pdf|max:10240']);

        if ($asignacion->pdf_pagare) {
            Storage::delete('documentos_firmados/' . $asignacion->pdf_pagare);
        }

        $nombre = 'pagare_equipo_' . $asignacion->id . '_' . now()->format('Ymd_His') . '.pdf';
        $request->file('pdf_pagare')->move(storage_path('app/documentos_firmados'), $nombre);

        $asignacion->update(['pdf_pagare' => $nombre]);

        return redirect()->back()->with('success', 'PDF de pagaré subido correctamente.');
    }

    public function subirPdfPagareTablet(Request $request, AsignacionTablet $asignacion)
    {
        $request->validate(['pdf_pagare' => 'required|file|mimes:pdf|max:10240']);

        if ($asignacion->pdf_pagare) {
            Storage::delete('documentos_firmados/' . $asignacion->pdf_pagare);
        }

        $nombre = 'pagare_tablet_' . $asignacion->id . '_' . now()->format('Ymd_His') . '.pdf';
        $request->file('pdf_pagare')->move(storage_path('app/documentos_firmados'), $nombre);

        $asignacion->update(['pdf_pagare' => $nombre]);

        return redirect()->back()->with('success', 'PDF de pagaré subido correctamente.');
    }

    // ── Descargar PDF de pagaré ──────────────────

    public function descargarPdfPagareEquipo(Asignacion $asignacion)
    {
        if (!$asignacion->pdf_pagare) abort(404);
        return response()->download(storage_path('app/documentos_firmados/' . $asignacion->pdf_pagare));
    }

    public function descargarPdfPagareTablet(AsignacionTablet $asignacion)
    {
        if (!$asignacion->pdf_pagare) abort(404);
        return response()->download(storage_path('app/documentos_firmados/' . $asignacion->pdf_pagare));
    }

public function generarFichaTecnica(Asignacion $asignacion)
{
    $template = $this->getTemplate('ficha_tecnica');
    if (!$template) {
        return back()->with('error', 'No hay template de ficha técnica subido. Ve a Templates.');
    }

    $rutaTemplate = storage_path('app/templates/' . $template->archivo);
    if (!File::exists($rutaTemplate)) {
        return back()->with('error', 'El archivo del template no existe. Vuelve a subirlo.');
    }

    $colaborador = $asignacion->colaborador;
    $equipo      = $asignacion->equipo;

    // Periféricos si es escritorio armado
    $teclado   = null;
    $mouse     = null;
    $monitores = '—';

    $escritorio = \App\Models\EquipoEscritorio::where('cpu_id', $equipo->id)->first();
    if ($escritorio) {
        $teclado   = $escritorio->perifericos->where('tipo', 'teclado')->first();
        $mouse     = $escritorio->perifericos->where('tipo', 'mouse')->first();
        $monitores = $escritorio->monitores
            ->map(fn($m) => $m->marca . ' ' . $m->modelo . ' — ' . $m->numero_serie)
            ->implode(' | ');
    }

    // Mapa de reemplazos
    $reemplazos = [
        '${MARCA}'             => $equipo->marca,
        '${MODELO}'            => $equipo->modelo,
        '${SERIE}'             => $equipo->numero_serie,
        '${NombreEquipo}'      => $equipo->nombre_equipo ?? 'N/A',
        '${Correo}'            => $equipo->correo ?? $colaborador->correo ?? 'N/A',
        '${Estado}'            => $equipo->estado ?? 'N/A',
        '${MarcaTPeriferico}'  => $teclado ? $teclado->marca  : 'N/A',
        '${ModeloTPeriferico}' => $teclado ? $teclado->modelo : 'N/A',
        '${MarcaMPeriferico}'  => $mouse   ? $mouse->marca    : 'N/A',
        '${ModeloMPeriferico}' => $mouse   ? $mouse->modelo   : 'N/A',
        '${Nombre}'            => $colaborador->nombre,
        '${ApellidoPaterno}'   => $colaborador->apellido_paterno,
        '${ApellidoMaterno}'   => $colaborador->apellido_materno ?? '',
        '${Puesto}'            => $colaborador->puesto ?? 'N/A',
        '${Area}'              => $colaborador->area->nombre ?? 'N/A',
        '${Ciudad}'            => $colaborador->ciudad->nombre ?? 'N/A',
        '${Monitores}'         => $monitores,
    ];

    // Cargar el Excel template
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($rutaTemplate);

    // Reemplazar en todas las hojas
    foreach ($spreadsheet->getAllSheets() as $sheet) {
        foreach ($sheet->getRowIterator() as $row) {
            foreach ($row->getCellIterator() as $cell) {
                $valor = $cell->getValue();
                if (is_string($valor)) {
                    $nuevoValor = str_replace(
                        array_keys($reemplazos),
                        array_values($reemplazos),
                        $valor
                    );
                    if ($nuevoValor !== $valor) {
                        $cell->setValue($nuevoValor);
                    }
                }
            }
        }
    }

    // Guardar en temp y descargar
    $tempPath = storage_path('app/temp');
    if (!File::exists($tempPath)) {
        File::makeDirectory($tempPath, 0755, true);
    }

    $fileName = 'FichaTecnica_' . $equipo->numero_serie . '.xlsx';
    $tempFile = $tempPath . '/' . $fileName;

    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save($tempFile);

    return response()->download($tempFile)->deleteFileAfterSend(true);
}
}