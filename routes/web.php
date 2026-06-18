<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CitacionesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DenunciasController;
use App\Http\Controllers\ExpedienteController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\VisitasController;
use App\Http\Controllers\ConsejoComunalController;
use App\Http\Controllers\VocerosController;
use App\Http\Controllers\CensoController;
use App\Http\Controllers\CirculoAbuelosController;
use App\Http\Controllers\ProyectosController;
use App\Http\Controllers\CensoDemograficoController;
use Illuminate\Support\Facades\Route;

Route::get('/crear-admin', [AuthController::class, 'crearAdmin'])->name('crear-admin');


Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/logear', [AuthController::class, 'login'])->name('logear');

Route::middleware('auth')->group(function () {
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    // Rutas para gestión de usuarios
    Route::resource('/usuarios', UsersController::class);
    Route::get('/tbody', [UsersController::class, 'tbody'])->name('tbody');
    Route::get('usuarios/cambiar-estado/{id}/{estado}', [UsersController::class, 'cambiarEstado'])->name('cambiar-estado');
    Route::get('usuarios/cambiar-password/{id}/{password}', [UsersController::class, 'cambiarPassword'])->name('cambiar-password');
    Route::get('/usuarios/{id}/edit', [UsersController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/update/{id}', [UsersController::class, 'update'])->name('usuarios.update');

    // Rutas para gestión de visitas
    Route::resource('/visitas', VisitasController::class);
    Route::get('/tbody-visitas', [VisitasController::class, 'tbody'])->name('tbody.visitas');

    // Rutas para consejos comunales
    Route::get('/consejos-comunales/buscar-persona/{cedula}', [ConsejoComunalController::class, 'buscarPersonaPorCedula'])->name('consejos-comunales.buscar-persona');
    Route::get('/tbody-consejos', [ConsejoComunalController::class, 'tbody'])->name('tbody.consejos');
    Route::resource('consejos-comunales', ConsejoComunalController::class);

    // Rutas para denuncias
    Route::get('/denuncias/buscar-persona', [DenunciasController::class, 'buscarPersona'])->name('denuncias.buscar-persona');
    Route::resource('denuncias', DenunciasController::class);
    Route::post('/denuncias/posponer-cita', [DenunciasController::class, 'posponerCita'])->name('denuncias.posponer-cita');

    // Consulta de expedientes
    Route::get('/consulta-expediente', [ExpedienteController::class, 'consulta'])->name('consulta.index');

    // Rutas para citaciones
    Route::resource('citaciones', CitacionesController::class);
    Route::post('/citaciones/conciliar', [CitacionesController::class, 'conciliar'])->name('citaciones.conciliar');
    Route::get('/expedientes/{id}/tiene-denunciado', [CitacionesController::class, 'tieneDenunciado'])->name('expedientes.tiene-denunciado');
    Route::post('/citaciones/marcar-inasistente', [CitacionesController::class, 'marcarInasistente'])->name('citaciones.marcar-inasistente');

    // Rutas para voceros
    Route::get('/voceros/buscar-persona', [VocerosController::class, 'buscarPersona'])->name('voceros.buscar-persona');
    Route::resource('voceros', VocerosController::class);

    // Rutas para censo
    Route::get('/censo/buscar-persona', [CensoController::class, 'buscarPersona'])->name('censo.buscar-persona');
    Route::post('/censo/integrante/store', [CensoController::class, 'storeIntegrante'])->name('censo.integrante.store');
    Route::get('/censo/integrante/{id}', [CensoController::class, 'showIntegrante'])->name('censo.integrante.show');
    Route::get('/censo/integrante/{id}/edit', [CensoController::class, 'editIntegrante'])->name('censo.integrante.edit');
    Route::put('/censo/integrante/{id}', [CensoController::class, 'updateIntegrante'])->name('censo.integrante.update');
    Route::delete('/censo/integrante/{id}', [CensoController::class, 'destroyIntegrante'])->name('censo.integrante.destroy');
    Route::resource('censo', CensoController::class);

    // Rutas para Círculo de Abuelos
    Route::get('/circulo-abuelos', [CirculoAbuelosController::class, 'index'])->name('circulo-abuelos.index');
    Route::post('/circulo-abuelos/jornada', [CirculoAbuelosController::class, 'storeJornada'])->name('circulo-abuelos.store-jornada');
    Route::get('/circulo-abuelos/jornada/{id}', [CirculoAbuelosController::class, 'showJornada'])->name('circulo-abuelos.show-jornada');
    Route::get('/circulo-abuelos/jornada/{id}/edit', [CirculoAbuelosController::class, 'editJornada'])->name('circulo-abuelos.edit-jornada');
    Route::put('/circulo-abuelos/jornada/{id}', [CirculoAbuelosController::class, 'updateJornada'])->name('circulo-abuelos.update-jornada');
    Route::delete('/circulo-abuelos/jornada/{id}', [CirculoAbuelosController::class, 'destroyJornada'])->name('circulo-abuelos.destroy-jornada');

    // Rutas para Proyectos
    Route::resource('proyectos', ProyectosController::class);

    // Rutas para Censo Demográfico y Reportes PDF
    Route::get('/reportes', [CensoDemograficoController::class, 'index'])->name('reportes.index');
    Route::get('/reportes/pdf', [CensoDemograficoController::class, 'exportPdf'])->name('reportes.pdf');

    // Rutas para el módulo Actas (Generador de Cartas/Constancias)
    Route::get('/actas-cartas', [\App\Http\Controllers\ActasCartasController::class, 'index'])->name('actas.index');
    Route::get('/actas-cartas/buscar-persona', [\App\Http\Controllers\ActasCartasController::class, 'buscarPersona'])->name('actas.buscar-persona');
    Route::post('/actas-cartas/residencia-pdf', [\App\Http\Controllers\ActasCartasController::class, 'exportResidenciaPdf'])->name('actas.residencia-pdf');
    Route::post('/actas-cartas/buena-conducta-pdf', [\App\Http\Controllers\ActasCartasController::class, 'exportBuenaConductaPdf'])->name('actas.buena-conducta-pdf');
});

