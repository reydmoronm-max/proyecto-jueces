<?php

namespace App\Http\Controllers;

use App\Models\Familia;
use App\Models\Persona;
use App\Models\ConsejoComunal;
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
        $query = Familia::with(['personas', 'consejoComunal']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('numero_familia', 'LIKE', '%' . $search . '%')
                    ->orWhereHas('personas', function ($qp) use ($search) {
                        $qp->where('cedula', 'LIKE', '%' . $search . '%')
                            ->orWhere('nombres', 'LIKE', '%' . $search . '%')
                            ->orWhere('apellidos', 'LIKE', '%' . $search . '%');
                    })
                    ->orWhereHas('consejoComunal', function ($qc) use ($search) {
                        $qc->where('nombre', 'LIKE', '%' . $search . '%');
                    });
            });
        }

        $items = $query->orderBy('created_at', 'desc')->get();
        $consejosComunales = ConsejoComunal::all();

        return view('modules.censo.index', compact('titulo', 'paginaTitulo', 'paginaSubtitulo', 'censoActive', 'items', 'consejosComunales'));
    }

    /**
     * Store a newly created family.
     */
    public function store(Request $request)
    {
        $request->validate([
            'numero_familia'      => ['required', 'string', 'unique:familias,numero_familia', 'max:100'],
            'consejo_comunal_id'  => ['nullable', 'exists:consejos_comunales,id'],
            'vivienda'            => ['required', 'string', 'in:Propia,Prestada,Alquilada'],
            'mision_vivienda'     => ['required', 'string', 'in:Sí,No'],
            'bono_unico_familiar' => ['required', 'string', 'in:Sí,No'],
            'clap'                => ['required', 'string', 'in:Sí,No'],
        ], [
            'numero_familia.required'      => 'El número de familia es obligatorio.',
            'numero_familia.unique'        => 'Esta familia ya está registrada.',
            'vivienda.required'            => 'El tipo de vivienda es obligatorio.',
            'mision_vivienda.required'     => 'Indique si recibió vivienda por Misión Vivienda.',
            'bono_unico_familiar.required' => 'Indique si la familia recibe Bono Único Familiar.',
            'clap.required'                => 'Indique si la familia recibe CLAP.',
        ]);

        Familia::create([
            'numero_familia'      => $request->numero_familia,
            'consejo_comunal_id'  => $request->consejo_comunal_id,
            'vivienda'            => $request->vivienda,
            'mision_vivienda'     => $request->mision_vivienda,
            'bono_unico_familiar' => $request->bono_unico_familiar,
            'clap'                => $request->clap,
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
            'numero_familia'      => ['required', 'string', 'unique:familias,numero_familia,' . $id, 'max:100'],
            'consejo_comunal_id'  => ['nullable', 'exists:consejos_comunales,id'],
            'vivienda'            => ['required', 'string', 'in:Propia,Prestada,Alquilada'],
            'mision_vivienda'     => ['required', 'string', 'in:Sí,No'],
            'bono_unico_familiar' => ['required', 'string', 'in:Sí,No'],
            'clap'                => ['required', 'string', 'in:Sí,No'],
        ], [
            'numero_familia.required'      => 'El número de familia es obligatorio.',
            'numero_familia.unique'        => 'Esta familia ya está registrada.',
            'vivienda.required'            => 'El tipo de vivienda es obligatorio.',
            'mision_vivienda.required'     => 'Indique si recibió vivienda por Misión Vivienda.',
            'bono_unico_familiar.required' => 'Indique si la familia recibe Bono Único Familiar.',
            'clap.required'                => 'Indique si la familia recibe CLAP.',
        ]);

        $familia->update([
            'numero_familia'      => $request->numero_familia,
            'consejo_comunal_id'  => $request->consejo_comunal_id,
            'vivienda'            => $request->vivienda,
            'mision_vivienda'     => $request->mision_vivienda,
            'bono_unico_familiar' => $request->bono_unico_familiar,
            'clap'                => $request->clap,
        ]);

        return to_route('censo.index')->with('success', 'Familia actualizada correctamente.');
    }

    /**
     * Remove the specified family.
     */
    public function destroy(string $id)
    {
        $familia = Familia::findOrFail($id);

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
        $familia = Familia::with(['personas', 'consejoComunal'])->findOrFail($id);
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
            'centro_votacion'     => ['nullable', 'string', 'max:150'],
            'carnet_patria'       => ['nullable', 'string', 'max:50'],
            'nivel_academico'     => ['required', 'string'],
            'profesion'           => ['nullable', 'string', 'max:100'],
            'situacion_laboral'   => ['nullable', 'string', 'max:100'],
            'tipo_enfermedad'     => ['nullable', 'string', 'max:150'],
            'pensionado_jubilado' => ['required', 'string', 'in:Sí,No'],
            'direccion'           => ['nullable', 'string', 'max:500'],
            'estudia'             => ['required', 'string', 'in:Sí,No'],
            'genero'              => ['required', 'string', 'in:Masculino,Femenino'],
            'parentesco'          => ['required', 'string', 'in:Jefe de familia,Hijo/a,Padre,Madre,Abuelo/a,Tío/a,Primo/a'],
        ]);

        if ($request->parentesco === 'Jefe de familia') {
            $jefeExiste = Persona::where('familia_id', $request->familia_id)
                ->where('parentesco', 'Jefe de familia')
                ->exists();
            if ($jefeExiste) {
                return back()->withErrors(['parentesco' => 'Ya existe un Jefe de familia registrado en esta familia.'])->withInput();
            }
        }

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

            // Update all member census and basic fields
            $persona->update([
                'nombres'             => $request->nombres,
                'apellidos'           => $request->apellidos,
                'telefono'            => $request->telefono,
                'familia_id'          => $request->familia_id,
                'fecha_nacimiento'    => $fecha,
                'centro_votacion'     => $request->centro_votacion,
                'carnet_patria'       => $request->carnet_patria,
                'nivel_academico'     => $request->nivel_academico,
                'profesion'           => $request->profesion,
                'situacion_laboral'   => $request->situacion_laboral,
                'tipo_enfermedad'     => $request->tipo_enfermedad,
                'pensionado_jubilado' => $request->pensionado_jubilado,
                'direccion'           => $request->direccion,
                'estudia'             => $request->estudia,
                'genero'              => $request->genero,
                'parentesco'          => $request->parentesco,
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
        $persona = Persona::with(['familia.consejoComunal'])->findOrFail($id);
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
        $persona = Persona::with(['familia.consejoComunal'])->findOrFail($id);
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
            'centro_votacion'     => ['nullable', 'string', 'max:150'],
            'carnet_patria'       => ['nullable', 'string', 'max:50'],
            'nivel_academico'     => ['required', 'string'],
            'profesion'           => ['nullable', 'string', 'max:100'],
            'situacion_laboral'   => ['nullable', 'string', 'max:100'],
            'tipo_enfermedad'     => ['nullable', 'string', 'max:150'],
            'pensionado_jubilado' => ['required', 'string', 'in:Sí,No'],
            'direccion'           => ['nullable', 'string', 'max:500'],
            'estudia'             => ['required', 'string', 'in:Sí,No'],
            'genero'              => ['required', 'string', 'in:Masculino,Femenino'],
            'parentesco'          => ['required', 'string', 'in:Jefe de familia,Hijo/a,Padre,Madre,Abuelo/a,Tío/a,Primo/a'],
        ]);

        if ($request->parentesco === 'Jefe de familia') {
            $jefeExiste = Persona::where('familia_id', $persona->familia_id)
                ->where('parentesco', 'Jefe de familia')
                ->where('id', '!=', $id)
                ->exists();
            if ($jefeExiste) {
                return back()->withErrors(['parentesco' => 'Ya existe un Jefe de familia registrado en esta familia.'])->withInput();
            }
        }

        DB::beginTransaction();
        try {
            $fecha = \Carbon\Carbon::createFromFormat('d-m-Y', $request->fecha_nacimiento)->format('Y-m-d');

            $persona->update([
                'cedula'              => $request->cedula,
                'nombres'             => $request->nombres,
                'apellidos'           => $request->apellidos,
                'telefono'            => $request->telefono,
                'fecha_nacimiento'    => $fecha,
                'centro_votacion'     => $request->centro_votacion,
                'carnet_patria'       => $request->carnet_patria,
                'nivel_academico'     => $request->nivel_academico,
                'profesion'           => $request->profesion,
                'situacion_laboral'   => $request->situacion_laboral,
                'tipo_enfermedad'     => $request->tipo_enfermedad,
                'pensionado_jubilado' => $request->pensionado_jubilado,
                'direccion'           => $request->direccion,
                'estudia'             => $request->estudia,
                'genero'              => $request->genero,
                'parentesco'          => $request->parentesco,
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
