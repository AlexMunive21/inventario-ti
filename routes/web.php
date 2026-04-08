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



Route::get('/', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('areas', AreaController::class)->middleware('auth');

Route::resource('ciudades', CiudadController::class)
    ->parameters(['ciudades' => 'ciudad'])
    ->middleware('auth');

Route::middleware(['auth'])->group(function () {

    Route::resource('equipos', EquipoController::class)
        ->parameters(['equipos' => 'equipo']);

    Route::delete('equipos/{equipo}', [EquipoController::class, 'destroy'])
        ->middleware('role:GerenteTIDS')
        ->name('equipos.destroy');

});

Route::resource('colaboradores', ColaboradorController::class)
    ->parameters(['colaboradores' => 'colaborador'])
    ->middleware('auth');

Route::resource('asignaciones', AsignacionController::class)
    ->middleware('auth');

Route::middleware(['auth'])->group(function () {

    Route::resource('celulares', CelularController::class)
        ->parameters(['celulares' => 'celular']);

    Route::delete('celulares/{celular}', [CelularController::class, 'destroy'])
    ->middleware('role:GerenteTIDS')
    ->name('celulares.destroy');

});

Route::resource('asignaciones-celulares', AsignacionCelularController::class);
Route::put('asignaciones-celulares/{id}/devolver', 
    [AsignacionCelularController::class, 'devolver']
)->name('asignaciones-celulares.devolver');

Route::middleware(['auth'])->group(function () {

    Route::get('colaboradores/bajas', [ColaboradorController::class, 'bajas'])
        ->name('colaboradores.bajas')
        ->middleware('role:GerenteTIDS');

    Route::put('colaboradores/{colaborador}/baja',
        [ColaboradorController::class,'baja'])
        ->name('colaboradores.baja')
        ->middleware('role:GerenteTIDS');

    Route::put('colaboradores/{colaborador}/reactivar',
        [ColaboradorController::class,'reactivar'])
        ->name('colaboradores.reactivar')
        ->middleware('role:GerenteTIDS');

    Route::resource('colaboradores', ColaboradorController::class)
        ->parameters(['colaboradores' => 'colaborador']);

});

Route::get('asignaciones-historial',
    [AsignacionController::class,'historial'])
    ->name('asignaciones.historial');

Route::get('/equipos/{equipo}/responsiva',
    [App\Http\Controllers\EquipoController::class,'responsiva'])
    ->name('equipos.responsiva');



Route::resource('cuentas', AccountController::class);

Route::resource('usuarios', App\Http\Controllers\UsuarioController::class);

Route::middleware(['auth','permission:ver todo'])->group(function () {

    Route::resource('areas', AreaController::class);
    Route::resource('equipos', EquipoController::class);
    // Route::resource('cuentas', CuentaController::class);
    Route::resource('celulares', CelularController::class);
    Route::resource('ciudades', CiudadController::class);
    Route::resource('asignaciones', AsignacionController::class);
    Route::resource('asignaciones-celulares', AsignacionCelularController::class);
    // Route::resource('usuarios', UsuarioController::class);

});


Route::middleware(['auth','permission:ver colaboradores'])->group(function () {

    Route::resource('colaboradores', ColaboradorController::class);

});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth','permission:ver dashboard'])->name('dashboard');



require __DIR__.'/auth.php';
