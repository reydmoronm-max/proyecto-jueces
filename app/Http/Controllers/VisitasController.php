<?php

namespace App\Http\Controllers;

use App\Models\Visita;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Persona;

class VisitasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $titulo = 'Visitas';
        $paginaTitulo = 'Visitas';
        $paginaSubtitulo = 'Listado de visitas registradas.';

        $items = Visita::with('persona')->orderBy('created_at', 'desc')->get();
        return view('modules.visitas.index', compact('titulo', 'paginaTitulo', 'paginaSubtitulo', 'items'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => ['required', 'string', 'min:3', 'max:50', 'regex:/^[\p{L}\s]+$/u'],
            'apellido'    => ['required', 'string', 'min:3', 'max:50', 'regex:/^[\p{L}\s]+$/u'],
            'cedula_tipo' => ['required', 'in:V,E'],
            'cedula'      => ['required', 'digits_between:7,8'],
            'proposito'   => ['required', 'string', 'min:5', 'max:255'],
            'de_parte'    => ['nullable', 'string', 'max:255'],
        ]);

        // prevent duplicate visits by the same persona within the same minute
        $existingPersona = Persona::where('cedula', $request->cedula)->first();
        if ($existingPersona) {
            $now = now();
            $start = $now->copy()->startOfMinute();
            $end = $now->copy()->endOfMinute();
            $exists = Visita::where('persona_id', $existingPersona->id)
                ->whereBetween('created_at', [$start, $end])
                ->exists();
            if ($exists) {
                return back()->withErrors(['duplicate' => 'No puede registrar más de una visita en el mismo minuto.'])->withInput();
            }
        }

        // find or create persona
        $persona = \App\Models\Persona::firstOrCreate(
            ['cedula' => $request->cedula],
            [
                'cedula_tipo' => $request->cedula_tipo,
                'nombres' => $request->nombre,
                'apellidos' => $request->apellido,
            ]
        );

        // update persona names if changed
        if ($persona->nombres !== $request->nombre || $persona->apellidos !== $request->apellido) {
            $persona->update(['nombres' => $request->nombre, 'apellidos' => $request->apellido]);
        }

        Visita::create([
            'persona_id' => $persona->id,
            'proposito'  => $request->proposito,
            'de_parte'   => $request->de_parte,
        ]);

        return to_route('visitas.index')->with('success', 'Visita registrada correctamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $visita = Visita::with('persona')->findOrFail($id);
        return response()->json($visita);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // load visita and persona first so we can ignore persona on unique check
        $visita = Visita::findOrFail($id);
        $persona = Persona::findOrFail($visita->persona_id);

        $request->validate([
            'nombre'      => ['required', 'string', 'min:3', 'max:50', 'regex:/^[\p{L}\s]+$/u'],
            'apellido'    => ['required', 'string', 'min:3', 'max:50', 'regex:/^[\p{L}\s]+$/u'],
            'cedula_tipo' => ['required', 'in:V,E'],
            'cedula'      => ['required', 'digits_between:7,8', Rule::unique('personas', 'cedula')->ignore($persona->id)],
            'proposito'   => ['required', 'string', 'min:5', 'max:255'],
            'de_parte'    => ['nullable', 'string', 'max:255'],
        ]);

        $persona->update([
            'cedula_tipo' => $request->cedula_tipo,
            'cedula'      => $request->cedula,
            'nombres'     => $request->nombre,
            'apellidos'   => $request->apellido,
        ]);

        $visita->update([
            'proposito' => $request->proposito,
            'de_parte'  => $request->de_parte,
        ]);

        return to_route('visitas.index')->with('success', 'Visita actualizada correctamente.');
    }

    /**
     * Return tbody partial with visits.
     */
    public function tbody()
    {
        $items = Visita::with('persona')->orderBy('created_at', 'desc')->get();
        return view('modules.visitas.tbody', compact('items'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Visita::findOrFail($id);
        $item->delete();
        return to_route('visitas.index')->with('success', 'Visita eliminada correctamente.');
    }
}