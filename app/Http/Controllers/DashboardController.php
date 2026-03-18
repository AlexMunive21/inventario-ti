<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Colaborador;

class DashboardController extends Controller
{
    public function index()
    {
        $disponibles = Equipo::where('estatus','disponible')->count();
        $asignados = Equipo::where('estatus','asignado')->count();
        $mantenimiento = Equipo::where('estatus','mantenimiento')->count();
        $baja = Equipo::where('estatus','baja')->count();

        $colaboradores = Colaborador::count();

        return view('dashboard', compact(
            'disponibles',
            'asignados',
            'mantenimiento',
            'baja',
            'colaboradores'
        ));
    }
}