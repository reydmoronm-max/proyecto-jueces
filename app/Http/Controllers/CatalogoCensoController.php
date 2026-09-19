<?php

namespace App\Http\Controllers;

use App\Models\Enfermedad;
use App\Models\Profesion;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatalogoCensoController extends Controller
{
    /**
     * Get all catalog items as JSON.
     */
    public function index()
    {
        $enfermedades = Enfermedad::orderBy('nombre', 'asc')->get();
        $profesiones = Profesion::orderBy('nombre', 'asc')->get();

        return response()->json([
            'enfermedades' => $enfermedades,
            'profesiones'  => $profesiones,
        ]);
    }

    /**
     * Store a new disease.
     */
    public function storeEnfermedad(Request $request)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'min:2', 'max:100', 'unique:enfermedades,nombre'],
        ], [
            'nombre.required' => 'El nombre de la enfermedad es obligatorio.',
            'nombre.unique'   => 'Esta enfermedad ya se encuentra registrada.',
            'nombre.max'      => 'El nombre no puede superar los 100 caracteres.',
        ]);

        $item = Enfermedad::create([
            'nombre' => trim($request->nombre),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Enfermedad agregada correctamente al catálogo.',
            'item'    => $item,
        ]);
    }

    /**
     * Update a disease.
     */
    public function updateEnfermedad(Request $request, $id)
    {
        $enfermedad = Enfermedad::findOrFail($id);
        $oldNombre = $enfermedad->nombre;

        $request->validate([
            'nombre' => ['required', 'string', 'min:2', 'max:100', 'unique:enfermedades,nombre,' . $id],
        ], [
            'nombre.required' => 'El nombre de la enfermedad es obligatorio.',
            'nombre.unique'   => 'Esta enfermedad ya se encuentra registrada.',
        ]);

        $newNombre = trim($request->nombre);

        DB::transaction(function () use ($enfermedad, $oldNombre, $newNombre) {
            $enfermedad->update(['nombre' => $newNombre]);

            // Actualizar personas que tengan esta enfermedad
            $personas = Persona::where('tipo_enfermedad', 'LIKE', '%' . $oldNombre . '%')->get();
            foreach ($personas as $persona) {
                if ($persona->tipo_enfermedad) {
                    $items = array_map('trim', explode(',', $persona->tipo_enfermedad));
                    $updated = false;
                    foreach ($items as $k => $item) {
                        if ($item === $oldNombre) {
                            $items[$k] = $newNombre;
                            $updated = true;
                        }
                    }
                    if ($updated) {
                        $persona->update(['tipo_enfermedad' => implode(', ', array_unique($items))]);
                    }
                }
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Enfermedad actualizada correctamente.',
            'item'    => $enfermedad,
        ]);
    }

    /**
     * Delete a disease.
     */
    public function destroyEnfermedad($id)
    {
        $enfermedad = Enfermedad::findOrFail($id);
        $enfermedad->delete();

        return response()->json([
            'success' => true,
            'message' => 'Enfermedad eliminada del catálogo.',
        ]);
    }

    /**
     * Store a new profession.
     */
    public function storeProfesion(Request $request)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'min:2', 'max:100', 'unique:profesiones,nombre'],
        ], [
            'nombre.required' => 'El nombre de la profesión es obligatorio.',
            'nombre.unique'   => 'Esta profesión ya se encuentra registrada.',
            'nombre.max'      => 'El nombre no puede superar los 100 caracteres.',
        ]);

        $item = Profesion::create([
            'nombre' => trim($request->nombre),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profesión agregada correctamente al catálogo.',
            'item'    => $item,
        ]);
    }

    /**
     * Update a profession.
     */
    public function updateProfesion(Request $request, $id)
    {
        $profesion = Profesion::findOrFail($id);
        $oldNombre = $profesion->nombre;

        $request->validate([
            'nombre' => ['required', 'string', 'min:2', 'max:100', 'unique:profesiones,nombre,' . $id],
        ], [
            'nombre.required' => 'El nombre de la profesión es obligatorio.',
            'nombre.unique'   => 'Esta profesión ya se encuentra registrada.',
        ]);

        $newNombre = trim($request->nombre);

        DB::transaction(function () use ($profesion, $oldNombre, $newNombre) {
            $profesion->update(['nombre' => $newNombre]);

            // Actualizar personas que tengan esta profesión
            Persona::where('profesion', $oldNombre)
                ->update(['profesion' => $newNombre]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Profesión actualizada correctamente.',
            'item'    => $profesion,
        ]);
    }

    /**
     * Delete a profession.
     */
    public function destroyProfesion($id)
    {
        $profesion = Profesion::findOrFail($id);
        $profesion->delete();

        return response()->json([
            'success' => true,
            'message' => 'Profesión eliminada del catálogo.',
        ]);
    }
}
