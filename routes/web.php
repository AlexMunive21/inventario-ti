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
use App\Http\Controllers\TabletController;
use App\Http\Controllers\AsignacionTabletController;
use App\Http\Controllers\BajaController;
use App\Http\Controllers\ComponenteController;
use App\Http\Controllers\PerifericoController;
use App\Http\Controllers\EquipoEscritorioController;
use App\Http\Controllers\DocumentTemplateController;
use App\Http\Controllers\DocumentoAsignacionController;


// Dashboard (raíz y /dashboard apuntan al mismo lugar)

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'permission:ver dashboard'])
    ->name('dashboard');
Route::get('/', function () {
    return redirect()->route('dashboard');
})->middleware(['auth', 'permission:ver dashboard']);

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
    Route::resource('cuentas', AccountController::class)
    ->parameters(['cuentas' => 'cuenta']);
    Route::resource('usuarios', App\Http\Controllers\UsuarioController::class);

    Route::resource('tablets', TabletController::class)
    ->except(['destroy']);
    Route::delete('tablets/{tablet}', [TabletController::class, 'destroy'])
        ->middleware('role:GerenteTIDS')
        ->name('tablets.destroy');

    Route::resource('asignaciones-tablets', AsignacionTabletController::class);
    Route::put('asignaciones-tablets/{id}/devolver', [AsignacionTabletController::class, 'devolver'])
        ->name('asignaciones-tablets.devolver');

    // Rutas extra de asignaciones
    Route::get('asignaciones-historial', [AsignacionController::class, 'historial'])
        ->name('asignaciones.historial');
    Route::put('asignaciones-celulares/{id}/devolver', [AsignacionCelularController::class, 'devolver'])
        ->name('asignaciones-celulares.devolver');
        Route::get('asignaciones-celulares-historial', [AsignacionCelularController::class, 'historial'])
    ->name('asignaciones-celulares.historial');

Route::get('asignaciones-tablets-historial', [AsignacionTabletController::class, 'historial'])
    ->name('asignaciones-tablets.historial');

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

    // Primero las rutas específicas
    Route::get('colaboradores/bajas', [ColaboradorController::class, 'bajas'])
        ->name('colaboradores.bajas')
        ->middleware('role:GerenteTIDS');

    Route::put('colaboradores/{colaborador}/baja', [ColaboradorController::class, 'baja'])
        ->name('colaboradores.baja')
        ->middleware('role:GerenteTIDS');

    Route::put('colaboradores/{colaborador}/reactivar', [ColaboradorController::class, 'reactivar'])
        ->name('colaboradores.reactivar')
        ->middleware('role:GerenteTIDS');

    // Después el resource
    Route::resource('colaboradores', ColaboradorController::class)
        ->parameters(['colaboradores' => 'colaborador']);
});
Route::resource('usuarios', App\Http\Controllers\UsuarioController::class)
    ->middleware(['auth', 'role:GerenteTIDS|AnalistaTI']);

Route::get('colaboradores/{colaborador}/ficha-rrhh',
    [ColaboradorController::class, 'fichaRRHH']
)->name('colaboradores.ficha_rrhh');




Route::middleware(['auth', 'permission:ver todo'])->group(function () {
    Route::get('bajas', [BajaController::class, 'index'])->name('bajas.index');
    Route::post('bajas/equipos/{equipo}/reactivar', [BajaController::class, 'reactivarEquipo'])
        ->name('bajas.equipos.reactivar');
    Route::post('bajas/celulares/{celular}/reactivar', [BajaController::class, 'reactivarCelular'])
        ->name('bajas.celulares.reactivar');
    Route::post('bajas/tablets/{tablet}/reactivar', [BajaController::class, 'reactivarTablet'])
        ->name('bajas.tablets.reactivar');
});

Route::middleware(['auth', 'permission:ver todo'])->group(function () {

    Route::resource('componentes', ComponenteController::class);
    Route::resource('perifericos', PerifericoController::class);

    Route::resource('equipos-escritorio', EquipoEscritorioController::class)
        ->parameters(['equipos-escritorio' => 'equipoEscritorio']);

    Route::post('equipos-escritorio/{equipoEscritorio}/cambiar-componente',
        [EquipoEscritorioController::class, 'cambiarComponente'])
        ->name('equipos-escritorio.cambiarComponente');

    Route::post('equipos-escritorio/{equipoEscritorio}/asignar',
        [EquipoEscritorioController::class, 'asignar'])
        ->name('equipos-escritorio.asignar');

    Route::post('equipos-escritorio/{equipoEscritorio}/liberar',
        [EquipoEscritorioController::class, 'liberar'])
        ->name('equipos-escritorio.liberar');
}); 


// Templates — solo AnalistaTI, AnalistaDS y Gerente
Route::middleware(['auth', 'role:AnalistaTI|AnalistaDS|GerenteTIDS'])->group(function () {
    Route::get('templates', [DocumentTemplateController::class, 'index'])
        ->name('templates.index');
    Route::post('templates', [DocumentTemplateController::class, 'store'])
        ->name('templates.store');
    Route::delete('templates/{template}', [DocumentTemplateController::class, 'destroy'])
        ->name('templates.destroy');
});

// Documentos desde asignaciones
Route::middleware(['auth', 'permission:ver todo'])->group(function () {

    // Equipos de cómputo / laptops
    Route::get('asignaciones/{asignacion}/generar/{tipo}',
        [DocumentoAsignacionController::class, 'generarEquipo'])
        ->name('asignaciones.generar');
    Route::post('asignaciones/{asignacion}/subir-pdf',
        [DocumentoAsignacionController::class, 'subirPdfEquipo'])
        ->name('asignaciones.subirPdf');
    Route::get('asignaciones/{asignacion}/descargar-pdf',
        [DocumentoAsignacionController::class, 'descargarPdfEquipo'])
        ->name('asignaciones.descargarPdf');

    // Celulares
    Route::get('asignaciones-celulares/{asignacion}/generar/{tipo}',
        [DocumentoAsignacionController::class, 'generarCelular'])
        ->name('asignaciones-celulares.generar');
    Route::post('asignaciones-celulares/{asignacion}/subir-pdf',
        [DocumentoAsignacionController::class, 'subirPdfCelular'])
        ->name('asignaciones-celulares.subirPdf');
    Route::get('asignaciones-celulares/{asignacion}/descargar-pdf',
        [DocumentoAsignacionController::class, 'descargarPdfCelular'])
        ->name('asignaciones-celulares.descargarPdf');

    // Tablets
    Route::get('asignaciones-tablets/{asignacion}/generar/{tipo}',
        [DocumentoAsignacionController::class, 'generarTablet'])
        ->name('asignaciones-tablets.generar');
    Route::post('asignaciones-tablets/{asignacion}/subir-pdf',
        [DocumentoAsignacionController::class, 'subirPdfTablet'])
        ->name('asignaciones-tablets.subirPdf');
    Route::get('asignaciones-tablets/{asignacion}/descargar-pdf',
        [DocumentoAsignacionController::class, 'descargarPdfTablet'])
        ->name('asignaciones-tablets.descargarPdf');

    // Escritorio
    Route::get('asignaciones-escritorio/{asignacion}/generar/{tipo}',
        [DocumentoAsignacionController::class, 'generarEscritorio'])
        ->name('asignaciones-escritorio.generar');
    Route::post('asignaciones-escritorio/{asignacion}/subir-pdf',
        [DocumentoAsignacionController::class, 'subirPdfEscritorio'])
        ->name('asignaciones-escritorio.subirPdf');
    Route::get('asignaciones-escritorio/{asignacion}/descargar-pdf',
        [DocumentoAsignacionController::class, 'descargarPdfEscritorio'])
        ->name('asignaciones-escritorio.descargarPdf');
        // Componentes — reactivar
    Route::post('componentes/{componente}/reactivar', [ComponenteController::class, 'reactivar'])
        ->name('componentes.reactivar')
        ->middleware('role:GerenteTIDS|AnalistaTI');

    // Periféricos — baja parcial y eliminar
    Route::post('perifericos/{periferico}/dar-de-baja', [PerifericoController::class, 'darDeBaja'])
        ->name('perifericos.darDeBaja');
    Route::delete('perifericos/{periferico}', [PerifericoController::class, 'destroy'])
        ->name('perifericos.destroy')
        ->middleware('role:GerenteTIDS');

    // Escritorio — cambiar periférico
    Route::post('equipos-escritorio/{equipoEscritorio}/cambiar-periferico',
        [EquipoEscritorioController::class, 'cambiarPeriferico'])
        ->name('equipos-escritorio.cambiarPeriferico');
        
    // Escritorio — asignar y liberar
    Route::post('bajas/componentes/{componente}/reactivar',
    [BajaController::class, 'reactivarComponente'])
    ->name('bajas.componentes.reactivar');

    // Celulares — baja lógica solo para Gerente
    Route::delete('equipos-escritorio/{equipoEscritorio}',
    [EquipoEscritorioController::class, 'destroy'])
    ->name('equipos-escritorio.destroy')
    ->middleware('role:GerenteTIDS');

});

require __DIR__.'/auth.php';