<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CitacionesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DenunciasController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\VisitasController;
use Illuminate\Support\Facades\Route;

Route::get('/crear-admin', [AuthController::class, 'crearAdmin'])->name('crear-admin');


Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/logear', [AuthController::class, 'login'])->name('logear');

Route::middleware('auth')->group(function() {
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

    // Rutas para denuncias
    Route::get('/denuncias/buscar-persona', [DenunciasController::class, 'buscarPersona'])->name('denuncias.buscar-persona');
    Route::resource('denuncias', DenunciasController::class);
    Route::post('/denuncias/posponer-cita', [DenunciasController::class, 'posponerCita'])->name('denuncias.posponer-cita');

    // Rutas para citaciones
    Route::resource('citaciones', CitacionesController::class);



});

