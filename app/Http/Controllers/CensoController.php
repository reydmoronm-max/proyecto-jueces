<?php

namespace App\Http\Controllers;

use App\Models\Familia;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CensoController extends Controller
{
    /**
     * Display a listing of the families.
     */
    public function index(Request $request)
    {
        $titulo = 'Censo';
        $paginaTitulo = 'Censo de Ciudadanos';
        $paginaSubtitulo = 'Módulo para la gestión y censo de núcleos familiares.';
        $censoActive = 'active';

        $search = $request->input('search');
        $query = Familia::with('personas');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('numero_familia', 'LIKE', '%' . $search . '%')
                  ->orWhereHas('personas', function ($qp) use ($search) {
                      $qp->where('cedula', 'LIKE', '%' . $search . '%')
                         ->orWhere('nombres', 'LIKE', '%' . $search . '%')
                         ->orWhere('apellidos', 'LIKE', '%' . $search . '%');
                  });
            });
        }

        $items = $query->orderBy('created_at', 'desc')->get();

        return view('modules.censo.index', compact('titulo', 'paginaTitulo', 'paginaSubtitulo', 'censoActive', 'items'));
    }

    /**
     * Store a newly created family.
     */
    public function store(Request $request)
    {
        $request->validate([
            'numero_familia' => ['required', 'string', 'unique:familias,numero_familia', 'max:100'],
        ], [
            'numero_familia.required' => 'El número de familia es obligatorio.',
            'numero_familia.unique' => 'Esta familia ya está registrada.',
        ]);

        Familia::create([
            'numero_familia' => $request->numero_familia
        ]);

        return to_route('censo.index')->with('success', 'Familia registrada correctamente.');
    }

    /**
     * Update the specified family.
     */
    public function update(Request $request, string $id)
    {
        $familia = Familia::findOrFail($id);

        $request->validate([
            'numero_familia' => ['required', 'string', 'unique:familias,numero_familia,' . $id, 'max:100'],
        ], [
            'numero_familia.required' => 'El número de familia es obligatorio.',
            'numero_familia.unique' => 'Esta familia ya está registrada.',
        ]);

        $familia->update([
            'numero_familia' => $request->numero_familia
        ]);

        return to_route('censo.index')->with('success', 'Familia actualizada correctamente.');
    }

    /**
     * Remove the specified family.
     */
    public function destroy(string $id)
    {
        $familia = Familia::findOrFail($id);
        
        // Note: DB foreign key cascade set null will handle setting persona's familia_id to null,
        // but we'll manually ensure they're disassociated if needed or let DB handle it.
        $familia->delete();

        return to_route('censo.index')->with('success', 'Familia eliminada correctamente.');
    }

    /**
     * Search for a person globally by cédula.
     */
    public function buscarPersona(Request $request)
    {
        $request->validate([
            'cedula' => ['required', 'digits_between:7,8'],
        ]);

        $persona = Persona::where('cedula', $request->cedula)->first();

        if (!$persona) {
            return response()->json(['message' => 'Persona no encontrada'], 404);
        }

        if ($persona->fecha_nacimiento) {
            $persona->fecha_nacimiento_formateada = \Carbon\Carbon::parse($persona->fecha_nacimiento)->format('d-m-Y');
        }

        return response()->json($persona);
    }

    /**
     * Display the specified family details with its members.
     */
    public function show(string $id)
    {
        $familia = Familia::with('personas')->findOrFail($id);
        return response()->json($familia);
    }

    /**
     * Store a newly created/updated member associated to a family.
     */
    public function storeIntegrante(Request $request)
    {
        $request->validate([
            'familia_id'          => ['required', 'exists:familias,id'],
            'cedula'              => ['required', 'digits_between:7,8'],
            'nombres'             => ['required', 'string', 'min:3', 'max:50', 'regex:/^[\p{L}\s]+$/u'],
            'apellidos'           => ['required', 'string', 'min:3', 'max:50', 'regex:/^[\p{L}\s]+$/u'],
            'telefono'            => ['nullable', 'string', 'max:20'],
            'fecha_nacimiento'    => ['required', 'string'],
            'cantidad_integrantes'=> ['required', 'integer', 'min:1'],
            'centro_votacion'     => ['nullable', 'string', 'max:150'],
            'carnet_patria'       => ['nullable', 'string', 'max:50'],
            'nivel_academico'     => ['required', 'string'],
            'profesion'           => ['nullable', 'string', 'max:100'],
            'situacion_laboral'   => ['nullable', 'string', 'max:100'],
            'vivienda'            => ['required', 'string'],
            'tipo_enfermedad'     => ['nullable', 'string', 'max:150'],
            'bono_unico_familiar' => ['required', 'string'],
            'pensionado_jubilado' => ['required', 'string'],
            'ayuda_tecnica'       => ['nullable', 'string', 'max:150'],
            'mision_vivienda'     => ['required', 'string'],
            'clap'                => ['required', 'string'],
            'casa_alimentacion'   => ['required', 'string'],
        ]);

        DB::beginTransaction();
        try {
            $fecha = \Carbon\Carbon::createFromFormat('d-m-Y', $request->fecha_nacimiento)->format('Y-m-d');

            $persona = Persona::firstOrCreate(
                ['cedula' => $request->cedula],
                [
                    'cedula_tipo' => 'V',
                    'nombres'     => $request->nombres,
                    'apellidos'   => $request->apellidos,
                    'telefono'    => $request->telefono,
                ]
            );

            // Update all census and basic fields
            $persona->update([
                'nombres'             => $request->nombres,
                'apellidos'           => $request->apellidos,
                'telefono'            => $request->telefono,
                'familia_id'          => $request->familia_id,
                'fecha_nacimiento'    => $fecha,
                'cantidad_integrantes'=> $request->cantidad_integrantes,
                'centro_votacion'     => $request->centro_votacion,
                'carnet_patria'       => $request->carnet_patria,
                'nivel_academico'     => $request->nivel_academico,
                'profesion'           => $request->profesion,
                'situacion_laboral'   => $request->situacion_laboral,
                'vivienda'            => $request->vivienda,
                'tipo_enfermedad'     => $request->tipo_enfermedad,
                'bono_unico_familiar' => $request->bono_unico_familiar,
                'pensionado_jubilado' => $request->pensionado_jubilado,
                'ayuda_tecnica'       => $request->ayuda_tecnica,
                'mision_vivienda'     => $request->mision_vivienda,
                'clap'                => $request->clap,
                'casa_alimentacion'   => $request->casa_alimentacion,
            ]);

            DB::commit();
            return to_route('censo.index')->with('success', 'Integrante registrado correctamente en la familia.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Ocurrió un error al registrar el integrante: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Show a member's details.
     */
    public function showIntegrante(string $id)
    {
        $persona = Persona::with('familia')->findOrFail($id);
        if ($persona->fecha_nacimiento) {
            $persona->fecha_nacimiento_formateada = \Carbon\Carbon::parse($persona->fecha_nacimiento)->format('d-m-Y');
        }
        return response()->json($persona);
    }

    /**
     * Edit a member's details.
     */
    public function editIntegrante(string $id)
    {
        $persona = Persona::findOrFail($id);
        if ($persona->fecha_nacimiento) {
            $persona->fecha_nacimiento_formateada = \Carbon\Carbon::parse($persona->fecha_nacimiento)->format('d-m-Y');
        }
        return response()->json($persona);
    }

    /**
     * Update a member's details.
     */
    public function updateIntegrante(Request $request, string $id)
    {
        $persona = Persona::findOrFail($id);

        $request->validate([
            'cedula'              => ['required', 'digits_between:7,8'],
            'nombres'             => ['required', 'string', 'min:3', 'max:50', 'regex:/^[\p{L}\s]+$/u'],
            'apellidos'           => ['required', 'string', 'min:3', 'max:50', 'regex:/^[\p{L}\s]+$/u'],
            'telefono'            => ['nullable', 'string', 'max:20'],
            'fecha_nacimiento'    => ['required', 'string'],
            'cantidad_integrantes'=> ['required', 'integer', 'min:1'],
            'centro_votacion'     => ['nullable', 'string', 'max:150'],
            'carnet_patria'       => ['nullable', 'string', 'max:50'],
            'nivel_academico'     => ['required', 'string'],
            'profesion'           => ['nullable', 'string', 'max:100'],
            'situacion_laboral'   => ['nullable', 'string', 'max:100'],
            'vivienda'            => ['required', 'string'],
            'tipo_enfermedad'     => ['nullable', 'string', 'max:150'],
            'bono_unico_familiar' => ['required', 'string'],
            'pensionado_jubilado' => ['required', 'string'],
            'ayuda_tecnica'       => ['nullable', 'string', 'max:150'],
            'mision_vivienda'     => ['required', 'string'],
            'clap'                => ['required', 'string'],
            'casa_alimentacion'   => ['required', 'string'],
        ]);

        DB::beginTransaction();
        try {
            $fecha = \Carbon\Carbon::createFromFormat('d-m-Y', $request->fecha_nacimiento)->format('Y-m-d');

            $persona->update([
                'cedula'              => $request->cedula,
                'nombres'             => $request->nombres,
                'apellidos'           => $request->apellidos,
                'telefono'            => $request->telefono,
                'fecha_nacimiento'    => $fecha,
                'cantidad_integrantes'=> $request->cantidad_integrantes,
                'centro_votacion'     => $request->centro_votacion,
                'carnet_patria'       => $request->carnet_patria,
                'nivel_academico'     => $request->nivel_academico,
                'profesion'           => $request->profesion,
                'situacion_laboral'   => $request->situacion_laboral,
                'vivienda'            => $request->vivienda,
                'tipo_enfermedad'     => $request->tipo_enfermedad,
                'bono_unico_familiar' => $request->bono_unico_familiar,
                'pensionado_jubilado' => $request->pensionado_jubilado,
                'ayuda_tecnica'       => $request->ayuda_tecnica,
                'mision_vivienda'     => $request->mision_vivienda,
                'clap'                => $request->clap,
                'casa_alimentacion'   => $request->casa_alimentacion,
            ]);

            DB::commit();
            return to_route('censo.index')->with('success', 'Datos del integrante actualizados correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Ocurrió un error al actualizar los datos del integrante: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Disassociate a member from their family.
     */
    public function destroyIntegrante(string $id)
    {
        $persona = Persona::findOrFail($id);
        
        $persona->update([
            'familia_id' => null
        ]);

        return to_route('censo.index')->with('success', 'Integrante desvinculado de la familia.');
    }
}
