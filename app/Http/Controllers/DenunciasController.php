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
        
        // Obtener expedientes con persona denunciante (relación involucrados) y ordenados por fecha de creación descendente
        $expedientes = \App\Models\Expediente::with(['personas' => function($q) {
            $q->wherePivot('rol', 'denunciante');
        }])->orderBy('created_at', 'desc')->get();

        return view('modules.denuncias.index', compact('titulo', 'paginaTitulo', 'paginaSubtitulo', 'denunciasActive', 'expedientes'));
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
        // Validación básica
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
                'estatus' => 'abierto',
            ]);

            // 3. Relacionar persona y expediente en tabla involucrados
            Involucrados::create([
                'persona_id' => $persona->id,
                'expediente_id' => $expediente->id,
                'rol' => 'denunciante',
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
    public function show($id)
    {
        $expediente = \App\Models\Expediente::with([
            'personas' => function($q) {
                $q->wherePivot('rol', 'denunciante');
            },
            'actas'
        ])->findOrFail($id);

        $denunciante = $expediente->personas->first();
        $acta = $expediente->actas->first();

        return response()->json([
            'cedula_tipo' => $denunciante->cedula_tipo ?? '',
            'cedula' => $denunciante->cedula ?? '',
            'nombres' => $denunciante->nombres ?? '',
            'apellidos' => $denunciante->apellidos ?? '',
            'telefono' => $denunciante->telefono ?? '',
            'direccion' => $denunciante->direccion ?? '',
            'motivo_denuncia' => $expediente->motivo_denuncia,
            'requirente' => $acta ? $this->extraerCampoActa($acta->contenido, 'Requirente') : '',
            'receptor' => $acta ? $this->extraerCampoActa($acta->contenido, 'Receptor') : '',
            'acuerdos' => $acta ? $this->extraerCampoActa($acta->contenido, 'Acuerdos') : '',
        ]);
    }

    public function buscarPersona(Request $request)
    {
        $request->validate([
            'cedula_tipo' => ['required', 'in:V,E'],
            'cedula' => ['required', 'digits_between:7,8'],
        ]);

        $persona = Persona::where('cedula', $request->cedula)
            ->where('cedula_tipo', $request->cedula_tipo)
            ->whereHas('visitas')
            ->first();

        if (!$persona) {
            return response()->json(['message' => 'Persona no encontrada en visitas'], 404);
        }

        return response()->json([
            'cedula_tipo' => $persona->cedula_tipo,
            'cedula' => $persona->cedula,
            'nombres' => $persona->nombres,
            'apellidos' => $persona->apellidos,
            'telefono' => $persona->telefono,
            'direccion' => $persona->direccion,
        ]);
    }

    // Helper para extraer los campos del contenido del acta
    private function extraerCampoActa($contenido, $campo)
    {
        preg_match('/' . $campo . ': (.*?)(\\n|$)/', $contenido, $matches);
        return $matches[1] ?? '';
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
