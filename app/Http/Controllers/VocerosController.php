<?php

namespace App\Http\Controllers;

use App\Models\Vocero;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VocerosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $titulo = 'Voceros';
        $paginaTitulo = 'Voceros';
        $paginaSubtitulo = 'Listado de voceros registrados en el sistema.';
        $vocerosActive = 'active';

        $search = $request->input('search');
        $query = Vocero::with('persona');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('categoria_vocero', 'LIKE', '%' . $search . '%')
                  ->orWhereHas('persona', function ($qp) use ($search) {
                      $qp->where('cedula', 'LIKE', '%' . $search . '%')
                         ->orWhere('nombres', 'LIKE', '%' . $search . '%')
                         ->orWhere('apellidos', 'LIKE', '%' . $search . '%');
                  });
            });
        }

        $items = $query->orderBy('created_at', 'desc')->get();

        return view('modules.voceros.index', compact('titulo', 'paginaTitulo', 'paginaSubtitulo', 'vocerosActive', 'items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Not used, handled in modal
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cedula'           => ['required', 'digits_between:7,8'],
            'nombres'          => ['required', 'string', 'min:3', 'max:50', 'regex:/^[\p{L}\s]+$/u'],
            'apellidos'        => ['required', 'string', 'min:3', 'max:50', 'regex:/^[\p{L}\s]+$/u'],
            'categoria_vocero' => ['required', 'string'],
            'fecha_eleccion'   => ['required', 'string'],
        ]);

        DB::beginTransaction();
        try {
            // Find or create persona
            $persona = Persona::firstOrCreate(
                ['cedula' => $request->cedula],
                [
                    'cedula_tipo' => 'V',
                    'nombres'     => $request->nombres,
                    'apellidos'   => $request->apellidos,
                ]
            );

            // Update names if they were changed
            if ($persona->nombres !== $request->nombres || $persona->apellidos !== $request->apellidos) {
                $persona->update([
                    'nombres'   => $request->nombres,
                    'apellidos' => $request->apellidos,
                ]);
            }

            // Check if already registered as a vocero
            $alreadyVocero = Vocero::where('persona_id', $persona->id)->exists();
            if ($alreadyVocero) {
                DB::rollBack();
                return back()->withErrors(['duplicate' => 'Esta persona ya está registrada como vocera.'])->withInput();
            }

            // Parse date from d-m-Y to Y-m-d
            $fecha = \Carbon\Carbon::createFromFormat('d-m-Y', $request->fecha_eleccion)->format('Y-m-d');

            Vocero::create([
                'persona_id'       => $persona->id,
                'categoria_vocero' => $request->categoria_vocero,
                'fecha_eleccion'   => $fecha,
                'activo'           => true,
            ]);

            DB::commit();
            return to_route('voceros.index')->with('success', 'Vocero registrado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Ocurrió un error al registrar el vocero.'])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $vocero = Vocero::with('persona')->findOrFail($id);
        $vocero->fecha_eleccion_formateada = \Carbon\Carbon::parse($vocero->fecha_eleccion)->format('d-m-Y');
        return response()->json($vocero);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $vocero = Vocero::with('persona')->findOrFail($id);
        $vocero->fecha_eleccion_formateada = \Carbon\Carbon::parse($vocero->fecha_eleccion)->format('d-m-Y');
        return response()->json($vocero);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $vocero = Vocero::findOrFail($id);

        $request->validate([
            'cedula'           => ['required', 'digits_between:7,8'],
            'nombres'          => ['required', 'string', 'min:3', 'max:50', 'regex:/^[\p{L}\s]+$/u'],
            'apellidos'        => ['required', 'string', 'min:3', 'max:50', 'regex:/^[\p{L}\s]+$/u'],
            'categoria_vocero' => ['required', 'string'],
            'fecha_eleccion'   => ['required', 'string'],
        ]);

        DB::beginTransaction();
        try {
            // Find or create the target persona for this cédula
            $persona = Persona::firstOrCreate(
                ['cedula' => $request->cedula],
                [
                    'cedula_tipo' => 'V',
                    'nombres'     => $request->nombres,
                    'apellidos'   => $request->apellidos,
                ]
            );

            // Update names if they changed
            if ($persona->nombres !== $request->nombres || $persona->apellidos !== $request->apellidos) {
                $persona->update([
                    'nombres'   => $request->nombres,
                    'apellidos' => $request->apellidos,
                ]);
            }

            // Check if this new persona is already a vocero on another record
            $alreadyVocero = Vocero::where('persona_id', $persona->id)
                ->where('id', '!=', $id)
                ->exists();

            if ($alreadyVocero) {
                DB::rollBack();
                return back()->withErrors(['duplicate' => 'Esta persona ya está registrada como vocera en otro registro.'])->withInput();
            }

            $fecha = \Carbon\Carbon::createFromFormat('d-m-Y', $request->fecha_eleccion)->format('Y-m-d');

            $vocero->update([
                'persona_id'       => $persona->id,
                'categoria_vocero' => $request->categoria_vocero,
                'fecha_eleccion'   => $fecha,
            ]);

            DB::commit();
            return to_route('voceros.index')->with('success', 'Vocero actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Ocurrió un error al actualizar el vocero.'])->withInput();
        }
    }

    /**
     * Activate or deactivate the specified resource.
     */
    public function cambiarEstado(string $id, int $estado)
    {
        $vocero = Vocero::findOrFail($id);
        $vocero->activo = (bool) $estado;
        $vocero->save();

        return response()->json(['activo' => $vocero->activo]);
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

        return response()->json([
            'cedula'    => $persona->cedula,
            'nombres'   => $persona->nombres,
            'apellidos' => $persona->apellidos,
        ]);
    }
}
