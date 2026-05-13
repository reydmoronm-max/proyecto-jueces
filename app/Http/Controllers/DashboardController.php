<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $titulo = 'Inicio';
        $home = 'active';
        $paginaTitulo = '¡Hola!';
        $paginaSubtitulo = 'Bienvenido de vuelta, ' . Auth::user()->nombre . '.';
        return view('modules.dashboard.home', compact('titulo', 'paginaTitulo', 'paginaSubtitulo', 'home'));
    }
}
