<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/crear-admin', [AuthController::class, 'crearAdmin'])->name('crear-admin');


Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/logear', [AuthController::class, 'login'])->name('logear');

Route::middleware('auth')->group(function() {
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

