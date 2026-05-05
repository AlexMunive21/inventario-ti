<?php

namespace App\Http\Controllers;

use App\Models\DocumentTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentTemplateController extends Controller
{
    public function index()
    {
        $templates = DocumentTemplate::with('user')
            ->orderBy('tipo')
            ->get()
            ->groupBy('tipo');

        $etiquetas = DocumentTemplate::etiquetas();

        return view('templates.index', compact('templates', 'etiquetas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'  => 'required|string|max:255',
            'tipo'    => 'required|in:responsiva_equipo,responsiva_celular,responsiva_tablet,pagare_laptop,pagare_tablet,ficha_tecnica',
            'archivo' => 'required|file|max:5120',
        ]);

        $nombre = $request->tipo . '_' . now()->format('Ymd_His') . '.docx';
        // Después — ruta absoluta explícita
        $destino = storage_path('app/templates');
        if (!\Illuminate\Support\Facades\File::exists($destino)) {
            \Illuminate\Support\Facades\File::makeDirectory($destino, 0755, true);
        }
        $request->file('archivo')->move($destino, $nombre);

        DocumentTemplate::create([
            'nombre'  => $request->nombre,
            'tipo'    => $request->tipo,
            'archivo' => $nombre,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('templates.index')
            ->with('success', 'Template subido correctamente.');
    }

    public function destroy(DocumentTemplate $template)
    {
        \Illuminate\Support\Facades\File::delete(storage_path('app/templates/' . $template->archivo));
        $template->delete();

        return redirect()->route('templates.index')
            ->with('success', 'Template eliminado.');
    }
}