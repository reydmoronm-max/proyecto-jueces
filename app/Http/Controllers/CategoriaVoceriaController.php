<?php

namespace App\Http\Controllers;

use App\Models\CategoriaVoceria;
use App\Models\Vocero;
use Illuminate\Http\Request;

class CategoriaVoceriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $titulo = 'Categorías de Vocerías';
        $paginaTitulo = 'Categorías de Vocerías';
        $paginaSubtitulo = 'Gestión de categorías para las vocerías comunitarias.';
        $categoriaVoceriasActive = 'active';

        $search = trim((string) $request->input('search', ''));
        $estadoCategoria = (string) $request->input('estado_categoria', '');
        $query = CategoriaVoceria::query();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'LIKE', '%' . $search . '%')
                  ->orWhere('descripcion', 'LIKE', '%' . $search . '%');
            });
        }

        if (in_array($estadoCategoria, ['0', '1'], true)) {
            $query->where('activo', $estadoCategoria === '1');
        } else {
            $estadoCategoria = '';
        }

        $items = $query->orderBy('created_at', 'desc')->get();

        return view('modules.categoria_vocerias.index', compact(
            'titulo',
            'paginaTitulo',
            'paginaSubtitulo',
            'categoriaVoceriasActive',
            'items',
            'search',
            'estadoCategoria'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => ['required', 'string', 'min:3', 'max:100', 'unique:categoria_vocerias,nombre'],
            'descripcion' => ['nullable', 'string', 'max:255'],
        ], [
            'nombre.unique' => 'Ya existe una categoría con este nombre.',
        ]);

        CategoriaVoceria::create([
            'nombre'      => trim($request->nombre),
            'descripcion' => $request->descripcion ? trim($request->descripcion) : null,
            'activo'      => true,
        ]);

        return to_route('categoria-vocerias.index')->with('success', 'Categoría registrada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $categoria = CategoriaVoceria::findOrFail($id);
        return response()->json($categoria);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $categoria = CategoriaVoceria::findOrFail($id);
        return response()->json($categoria);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $categoria = CategoriaVoceria::findOrFail($id);

        $request->validate([
            'nombre'      => ['required', 'string', 'min:3', 'max:100', 'unique:categoria_vocerias,nombre,' . $id],
            'descripcion' => ['nullable', 'string', 'max:255'],
        ], [
            'nombre.unique' => 'Ya existe otra categoría con este nombre.',
        ]);

        $nombreAnterior = $categoria->nombre;
        $nuevoNombre = trim($request->nombre);

        $categoria->update([
            'nombre'      => $nuevoNombre,
            'descripcion' => $request->descripcion ? trim($request->descripcion) : null,
        ]);

        // If category name was updated, update matching string in voceros table to maintain integrity
        if ($nombreAnterior !== $nuevoNombre) {
            Vocero::where('categoria_vocero', $nombreAnterior)->update(['categoria_vocero' => $nuevoNombre]);
        }

        return to_route('categoria-vocerias.index')->with('success', 'Categoría actualizada correctamente.');
    }

    /**
     * Activate or deactivate the specified resource.
     */
    public function cambiarEstado(string $id, int $estado)
    {
        $categoria = CategoriaVoceria::findOrFail($id);
        $categoria->activo = (bool) $estado;
        $categoria->save();

        return response()->json(['activo' => $categoria->activo]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $categoria = CategoriaVoceria::findOrFail($id);

        // Check if any vocero uses this category
        $inUse = Vocero::where('categoria_vocero', $categoria->nombre)->exists();
        if ($inUse) {
            return back()->withErrors(['in_use' => 'No se puede eliminar la categoría porque está asignada a uno o más voceros.']);
        }

        $categoria->delete();
        return to_route('categoria-vocerias.index')->with('success', 'Categoría eliminada correctamente.');
    }
}
