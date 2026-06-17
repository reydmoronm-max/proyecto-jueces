<?php

namespace App\Http\Controllers;

use App\Models\ConsejoComunal;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ConsejoComunalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $titulo = 'Consejos Comunales';
        $paginaTitulo = 'Consejos Comunales';
        $paginaSubtitulo = 'Listado de consejos comunales registrados.';

        $items = ConsejoComunal::with('jefe')->orderBy('created_at', 'desc')->get();
        return view('modules.consejos_comunales.index', compact('titulo', 'paginaTitulo', 'paginaSubtitulo', 'items'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Normalizar RIF: si viene sin la 'C' al inicio, anteponerla
        $rif = strtoupper($request->input('rif', ''));
        if (!Str::startsWith($rif, 'C')) {
            $rif = 'C' . $rif;
        }
        $request->merge(['rif' => $rif]);

        $request->validate([
            'nombre'       => ['required', 'string', 'max:255', 'unique:consejos_comunales,nombre'],
            'rif'          => ['required', 'string', 'size:10', 'regex:/^C\d{9}$/i', 'unique:consejos_comunales,rif'],
            'jefe_comando' => ['required', 'exists:personas,id', 'unique:consejos_comunales,jefe_comando'],
            'direccion'    => ['required', 'string', 'max:500'],
        ], [
            'rif.regex' => 'El RIF debe comenzar obligatoriamente con la letra "C" seguido de 9 dígitos (ej. C123456789).',
            'rif.size'  => 'El RIF debe tener exactamente 10 caracteres en total.',
            'jefe_comando.unique' => 'Esta persona ya se encuentra registrada como Jefe de Comando en otro Consejo Comunal.',
        ]);

        ConsejoComunal::create([
            'nombre'       => $request->nombre,
            'rif'          => $request->rif,
            'jefe_comando' => $request->jefe_comando,
            'direccion'    => $request->direccion,
        ]);

        return to_route('consejos-comunales.index')->with('success', 'Consejo Comunal registrado correctamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $consejo = ConsejoComunal::with('jefe')->findOrFail($id);
        
        return response()->json([
            'id'           => $consejo->id,
            'nombre'       => $consejo->nombre,
            'rif'          => $consejo->rif,
            'jefe_comando' => $consejo->jefe,
            'direccion'    => $consejo->direccion,
            'persona'      => $consejo->jefe ? [
                'id'        => $consejo->jefe->id,
                'cedula'    => $consejo->jefe->cedula,
                'nombres'   => $consejo->jefe->nombres,
                'apellidos' => $consejo->jefe->apellidos,
            ] : null
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $consejo = ConsejoComunal::findOrFail($id);
        // Normalizar RIF antes de validar/guardar
        $rif = strtoupper($request->input('rif', ''));
        if (!Str::startsWith($rif, 'C')) {
            $rif = 'C' . $rif;
        }
        $request->merge(['rif' => $rif]);

        $request->validate([
            'nombre'       => ['required', 'string', 'max:255', Rule::unique('consejos_comunales', 'nombre')->ignore($consejo->id)],
            'rif'          => ['required', 'string', 'size:10', 'regex:/^C\d{9}$/i', Rule::unique('consejos_comunales', 'rif')->ignore($consejo->id)],
            'jefe_comando' => ['required', 'exists:personas,id', Rule::unique('consejos_comunales', 'jefe_comando')->ignore($consejo->id)],
            'direccion'    => ['required', 'string', 'max:500'],
        ], [
            'rif.regex' => 'El RIF debe comenzar obligatoriamente con la letra "C" seguido de 9 dígitos (ej. C123456789).',
            'rif.size'  => 'El RIF debe tener exactamente 10 caracteres en total.',
            'jefe_comando.unique' => 'Esta persona ya se encuentra registrada como Jefe de Comando en otro Consejo Comunal.',
        ]);

        $consejo->update([
            'nombre'       => $request->nombre,
            'rif'          => $request->rif,
            'jefe_comando' => $request->jefe_comando,
            'direccion'    => $request->direccion,
        ]);

        return to_route('consejos-comunales.index')->with('success', 'Consejo Comunal actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $consejo = ConsejoComunal::findOrFail($id);
        $consejo->delete();

        return to_route('consejos-comunales.index')->with('success', 'Consejo Comunal eliminado correctamente.');
    }

    /**
     * Return tbody partial with items.
     */
    public function tbody()
    {
        $items = ConsejoComunal::with('jefe')->orderBy('created_at', 'desc')->get();
        return view('modules.consejos_comunales.tbody', compact('items'));
    }

    /**
     * Custom search person by cédula.
     */
    public function buscarPersonaPorCedula($cedula)
    {
        $persona = Persona::where('cedula', $cedula)->first();
        $currentConsejoId = request()->query('current_consejo');

        if (!$persona) {
            return response()->json(['message' => 'Persona no encontrada'], 404);
        }

        $existingConsejo = ConsejoComunal::where('jefe_comando', $persona->id)
            ->when($currentConsejoId, function ($query, $currentConsejoId) {
                return $query->where('id', '!=', $currentConsejoId);
            })
            ->first();

        if ($existingConsejo) {
            return response()->json([
                'message' => 'Esta persona ya se encuentra registrada como Jefe de Comando en otro Consejo Comunal.'
            ], 409);
        }

        return response()->json([
            'id'       => $persona->id,
            'nombre'   => $persona->nombres,
            'apellido' => $persona->apellidos,
        ]);
    }
}
