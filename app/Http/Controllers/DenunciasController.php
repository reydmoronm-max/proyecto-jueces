<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Persona;
use App\Models\Expediente;
use App\Models\Involucrados;
use App\Models\Actas;

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
        // Validación básica (puedes ajustarla según tus necesidades)
        $request->validate([
            'cedula_tipo' => 'required',
            'cedula' => 'required',
            'nombres' => 'required',
            'apellidos' => 'required',
            'telefono' => 'required',
            'direccion' => 'required',
            'motivo_denuncia' => 'required',
        ]);

        DB::beginTransaction();
        try {
            // 1. Guardar persona (si no existe)
            $persona = Persona::firstOrCreate(
                [
                    'cedula_tipo' => $request->cedula_tipo,
                    'cedula' => $request->cedula,
                ],
                [
                    'nombres' => $request->nombres,
                    'apellidos' => $request->apellidos,
                    'telefono' => $request->telefono,
                    'direccion' => $request->direccion,
                ]
            );

            // 2. Guardar expediente
            $expediente = Expediente::create([
                'motivo_denuncia' => $request->motivo_denuncia,
                'estatus' => 'Abierto', // Puedes ajustar el estatus según tu lógica
            ]);

            // 3. Relacionar persona y expediente en involucrados
            Involucrados::create([
                'persona_id' => $persona->id,
                'expediente_id' => $expediente->id,
                'rol' => 'denunciante', // Puedes ajustar el rol si lo necesitas
            ]);

            // 4. Guardar acta
            $contenido = "Requirente: " . ($request->requirente ?? '') . "\n" .
                         "Receptor: " . ($request->receptor ?? '') . "\n" .
                         "Acuerdos: " . ($request->acuerdos ?? '');

            Actas::create([
                'expediente_id' => $expediente->id,
                'tipo_acta' => 'recepcion',
                'contenido' => $contenido,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Denuncia registrada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Ocurrió un error al registrar la denuncia.');
        }
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
