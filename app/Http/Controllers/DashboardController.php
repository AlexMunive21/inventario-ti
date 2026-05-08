<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Celular;
use App\Models\Tablet;
use App\Models\Componente;
use App\Models\Colaborador;
use App\Models\Asignacion;
use App\Models\AsignacionCelular;
use App\Models\AsignacionTablet;
use App\Models\Account;
use App\Models\DocumentTemplate;
use Carbon\Carbon;
use App\Models\EquipoEscritorio;
use App\Models\AsignacionEscritorio;


class DashboardController extends Controller
{
    public function index()
    {
        // ── Equipos de cómputo ─────────────────────────
        $disponibles   = Equipo::where('estatus', 'disponible')->count();
        $asignados     = Equipo::where('estatus', 'asignado')->count();
        $mantenimiento = Equipo::where('estatus', 'mantenimiento')->count();
        $baja          = Equipo::where('estatus', 'baja')->count();

        // ── Celulares ──────────────────────────────────
        $celularesDisponibles = Celular::where('estatus', 'disponible')->count();
        $celularesAsignados   = Celular::where('estatus', 'asignado')->count();
        $celularesMantenimiento = Celular::where('estatus', 'mantenimiento')->count();
        $celularesBaja = Celular::where('estatus', 'baja')->count();

        // ── Tablets ────────────────────────────────────
        $tabletsDisponibles = Tablet::where('estatus', 'disponible')->count();
        $tabletsAsignadas   = Tablet::where('estatus', 'asignado')->count();
        $tabletsMantenimiento = Tablet::where('estatus', 'mantenimiento')->count();
        $tabletsBaja = Tablet::where('estatus', 'baja')->count();

        // ── Componentes ────────────────────────────────
        $cpusDisponibles      = Componente::where('tipo', 'cpu')->where('estatus', 'disponible')->count();
        $monitoresDisponibles = Componente::where('tipo', 'monitor')->where('estatus', 'disponible')->count();
        $componentesMantenimiento = Componente::where('estatus', 'mantenimiento')->count();
        $componentesBaja = Componente::where('estatus', 'baja')->count();

        // ── Colaboradores ──────────────────────────────
        $colaboradores = Colaborador::where('activo', 1)->count();
        $bajas         = Colaborador::where('activo', 2)->count();

        // ── Cuentas ────────────────────────────────────
        $totalCuentas = Account::count();

        // ── Templates ─────────────────────────────────
        $totalTemplates  = DocumentTemplate::count();
        $tiposSinTemplate = collect(DocumentTemplate::etiquetas())->keys()
            ->diff(DocumentTemplate::pluck('tipo')->unique())
            ->count();

        // ── Asignaciones recientes ─────────────────────
        $asignacionesRecientes = Asignacion::with(['equipo', 'colaborador'])
            ->where('activa', 1)
            ->latest()
            ->take(5)
            ->get();

        // ── Bajas mensuales colaboradores (últimos 6 meses) ──
        $bajasMensuales = [];
        $meses = [];
        for ($i = 5; $i >= 0; $i--) {
            $fecha = Carbon::now()->subMonths($i);
            $meses[] = $fecha->translatedFormat('M Y');
            $bajasMensuales[] = Colaborador::where('activo', 2)
                ->whereYear('fecha_baja', $fecha->year)
                ->whereMonth('fecha_baja', $fecha->month)
                ->count();
        }

        // ── Escritorios ───────────────────────────────
        $escritoriosDisponibles = EquipoEscritorio::where('estatus', 'disponible')->count();
        $escritoriosAsignados   = EquipoEscritorio::where('estatus', 'asignado')->count();

        // ── PDFs pendientes de firma ───────────────────
        $pdfsPendientes = Asignacion::where('activa', 1)->whereNull('pdf_firmado')->count()
            + AsignacionCelular::whereNull('fecha_devolucion')->whereNull('pdf_firmado')->count()
            + AsignacionTablet::whereNull('fecha_devolucion')->whereNull('pdf_firmado')->count();

        return view('dashboard', compact(
            'disponibles', 'asignados', 'mantenimiento', 'baja',
            'celularesDisponibles', 'celularesAsignados', 'celularesMantenimiento', 'celularesBaja',
            'tabletsDisponibles', 'tabletsAsignadas', 'tabletsMantenimiento', 'tabletsBaja',
            'cpusDisponibles', 'monitoresDisponibles', 'componentesMantenimiento', 'componentesBaja',  
            'colaboradores', 'bajas',
            'totalCuentas', 'totalTemplates', 'tiposSinTemplate',
            'asignacionesRecientes',
            'meses', 'bajasMensuales',
            'escritoriosDisponibles', 'escritoriosAsignados',
            'pdfsPendientes'
        ));
    }
}