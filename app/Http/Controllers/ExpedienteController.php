<?php

namespace App\Http\Controllers;

use App\Models\Expediente;
use Illuminate\Http\Request;

class ExpedienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Consulta de expedientes por cédula de identidad
     */
    public function consulta(Request $request)
    {
        $titulo = 'Consulta de expedientes';
        $paginaTitulo = 'Consulta de expedientes';
        $paginaSubtitulo = 'Buscar toda la información relacionada a un expediente';
        $consultaActive = 'active';

        $cedulaTipo = "V";
        $cedula = $request->input('cedula');

        $persona = null;
        $expedientes = collect();
        $busquedaRealizada = false;

        if ($cedulaTipo && $cedula) {
            $busquedaRealizada = true;
            // Buscar persona
            $persona = \App\Models\Persona::where('cedula_tipo', $cedulaTipo)
                ->where('cedula', $cedula)
                ->first();

            if ($persona) {
                // Obtener expedientes donde está involucrado, con relaciones asociadas
                $expedientes = $persona->expedientes()
                    ->with([
                        'personas' => function ($q) {
                            $q->withPivot('rol');
                        },
                        'actas',
                        'citaciones' => function ($q) {
                            $q->with('solicitaCambio')
                                ->orderBy('fecha_citacion', 'desc')
                                ->orderBy('hora_citacion', 'desc');
                        }
                    ])
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        }

        return view('modules.expedientes.consulta', compact(
            'titulo',
            'paginaTitulo',
            'paginaSubtitulo',
            'consultaActive',
            'persona',
            'expedientes',
            'busquedaRealizada',
            'cedulaTipo',
            'cedula'
        ));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Expediente $expediente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expediente $expediente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expediente $expediente)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expediente $expediente)
    {
        //
    }
}
