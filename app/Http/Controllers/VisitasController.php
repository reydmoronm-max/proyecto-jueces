<?php

namespace App\Http\Controllers;

use App\Models\Visita;
use Illuminate\Http\Request;

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
