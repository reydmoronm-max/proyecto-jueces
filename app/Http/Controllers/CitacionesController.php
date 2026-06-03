<?php

namespace App\Http\Controllers;

use App\Models\Citaciones;
use App\Models\Expediente;
use Illuminate\Http\Request;

class CitacionesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $titulo = 'Citaciones';
        $paginaTitulo = 'Citaciones';
        $paginaSubtitulo = 'Listado de citaciones registradas';
        $citacionesActive = 'active';
        $mostrarHoy = $request->boolean('hoy');
        
        $citacionesQuery = Citaciones::with('expediente.personas')->orderBy('created_at', 'desc');

        if ($mostrarHoy) {
            $citacionesQuery->whereDate('fecha_citacion', now()->toDateString())
                ->where('estatus', true);
            $paginaSubtitulo = 'Citaciones activas para hoy';
        }

        $citaciones = $citacionesQuery->get();
        // Obtener expedientes (denuncias) para la modal de selección
        $expedientes = Expediente::with('personas')->orderBy('created_at', 'desc')->get();

        return view('modules.citaciones.index', compact('titulo', 'paginaTitulo', 'paginaSubtitulo', 'citacionesActive', 'citaciones', 'expedientes', 'mostrarHoy'));
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


        $validarHora = Citaciones::where('fecha_citacion', $data['fecha_citacion'])
            ->where('hora_citacion', $data['hora_citacion'])
            ->first();

        if ($validarHora) {
            return redirect()->back()->with('validar', 'Ya existe una citación para esa fecha y hora.');
        }

        Citaciones::create($data);

        $expediente = Expediente::find($data['expediente_id']);
        if ($expediente->estatus === 'Abierto') {
            $expediente->estatus = 'En proceso';
            $expediente->save();
        }

        return redirect()->route('citaciones.index')->with('success', 'Citación creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Citaciones $citacion)
    {
        $citacion->load('expediente.involucrados.persona');

        return view('citaciones.show', compact('citacion'));
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

    public function marcarInasistente(Request $request)
    {
        $citacion = Citaciones::where('expediente_id', $request->expediente_id)->where('estatus', true)->first();
        
        if($citacion) {
            $citacion->asistio = 'No';
            $citacion->observaciones = $request->observaciones;
            $citacion->estatus = false;
            $citacion->save();
        }

        $data = $request->all();
        $data['fecha_citacion'] = \Carbon\Carbon::createFromFormat('d-m-Y', $data['fecha_citacion'])->format('Y-m-d');

        $validarHora = Citaciones::where('fecha_citacion', $data['fecha_citacion'])
            ->where('hora_citacion', $data['hora_citacion'])
            ->where('estatus', true)
            ->first();

        if ($validarHora) {
            return redirect()->back()->with('validar', 'Ya existe una citación para esa fecha y hora.');
        }

        Citaciones::create([
            'expediente_id' => $request->expediente_id,
            'fecha_citacion' => $request->fecha_citacion,
            'hora_citacion' => $request->hora_citacion,
            'estatus' => true,
        ]);

        return redirect()->back()->with('success', 'Citación agendada correctamente.');
    }

    
}


