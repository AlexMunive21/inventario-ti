<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\AsignacionCelular;
use App\Models\AsignacionTablet;
use App\Models\AsignacionEscritorio;
use App\Models\DocumentTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

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
        $request->file('pdf_firmado')->storeAs('documentos_firmados', $nombre);

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
        return Storage::download('documentos_firmados/' . $asignacion->pdf_firmado);
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
}