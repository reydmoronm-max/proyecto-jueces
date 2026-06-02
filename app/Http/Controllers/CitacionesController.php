<?php

namespace App\Http\Controllers;

use App\Models\Citaciones;
use Illuminate\Http\Request;

class CitacionesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $titulo = 'Citaciones';
        $paginaTitulo = 'Citaciones';
        $paginaSubtitulo = 'Listado de citaciones registradas';
        $citacionesActive = 'active';
        
        // Obtener citaciones con expediente y ordenados por fecha de creación descendente
        $citaciones = Citaciones::with('expediente')->orderBy('created_at', 'desc')->get();

        return view('modules.citaciones.index', compact('titulo', 'paginaTitulo', 'paginaSubtitulo', 'citacionesActive', 'citaciones'));
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

        $data = $request->all();
        $data['fecha_citacion'] = \Carbon\Carbon::createFromFormat('d-m-Y', $data['fecha_citacion'])->format('Y-m-d');
        Citaciones::create($data);

        return redirect()->route('denuncias.index')->with('success', 'Citación creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Citaciones $citaciones)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Citaciones $citaciones)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Citaciones $citaciones)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Citaciones $citaciones)
    {
        //
    }
}
