<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\CiudadController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\ColaboradorController;
use App\Http\Controllers\AsignacionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CelularController;
use App\Http\Controllers\AsignacionCelularController;
use App\Http\Controllers\AccountController;

// Dashboard (raíz y /dashboard apuntan al mismo lugar)
Route::get('/', [DashboardController::class, 'index'])
    ->middleware(['auth', 'permission:ver dashboard'])
    ->name('dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'permission:ver dashboard']);

// Perfil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Recursos principales — requieren auth + permiso general
Route::middleware(['auth', 'permission:ver todo'])->group(function () {
    Route::resource('areas', AreaController::class);
    Route::resource('ciudades', CiudadController::class)
        ->parameters(['ciudades' => 'ciudad']);
    Route::resource('asignaciones', AsignacionController::class);
    Route::resource('asignaciones-celulares', AsignacionCelularController::class);
    Route::resource('cuentas', AccountController::class);

    // Rutas extra de asignaciones
    Route::get('asignaciones-historial', [AsignacionController::class, 'historial'])
        ->name('asignaciones.historial');
    Route::put('asignaciones-celulares/{id}/devolver', [AsignacionCelularController::class, 'devolver'])
        ->name('asignaciones-celulares.devolver');

    // Equipos — baja lógica solo para Gerente
    Route::resource('equipos', EquipoController::class)
        ->parameters(['equipos' => 'equipo'])
        ->except(['destroy']);
    Route::delete('equipos/{equipo}', [EquipoController::class, 'destroy'])
        ->middleware('role:GerenteTIDS')
        ->name('equipos.destroy');
    Route::get('/equipos/{equipo}/responsiva', [EquipoController::class, 'responsiva'])
        ->name('equipos.responsiva');

    // Celulares — baja lógica solo para Gerente
    Route::resource('celulares', CelularController::class)
        ->parameters(['celulares' => 'celular'])
        ->except(['destroy']);
    Route::delete('celulares/{celular}', [CelularController::class, 'destroy'])
        ->middleware('role:GerenteTIDS')
        ->name('celulares.destroy');
});

// Colaboradores — permiso propio
Route::middleware(['auth', 'permission:ver colaboradores'])->group(function () {
    Route::resource('colaboradores', ColaboradorController::class)
        ->parameters(['colaboradores' => 'colaborador']);

    Route::get('colaboradores/bajas', [ColaboradorController::class, 'bajas'])
        ->name('colaboradores.bajas')
        ->middleware('role:GerenteTIDS');
    Route::put('colaboradores/{colaborador}/baja', [ColaboradorController::class, 'baja'])
        ->name('colaboradores.baja')
        ->middleware('role:GerenteTIDS');
    Route::put('colaboradores/{colaborador}/reactivar', [ColaboradorController::class, 'reactivar'])
        ->name('colaboradores.reactivar')
        ->middleware('role:GerenteTIDS');
});

Route::resource('usuarios', App\Http\Controllers\UsuarioController::class);

require __DIR__.'/auth.php';