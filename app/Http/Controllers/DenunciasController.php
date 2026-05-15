<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DenunciasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $titulo = 'Denuncias';
        $paginaTitulo = 'Denuncias';
        $paginaSubtitulo = 'Listado de denuncias registradas';
        $denunciasActive = 'active';
        
        return view('modules.denuncias.index', compact('titulo', 'paginaTitulo', 'paginaSubtitulo', 'denunciasActive'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
