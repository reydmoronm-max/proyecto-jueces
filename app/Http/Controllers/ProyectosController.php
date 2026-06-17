<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProyectosController extends Controller
{
    /**
     * Display a listing of community projects.
     */
    public function index(Request $request)
    {
        $titulo = 'Proyectos Comunitarios';
        $paginaTitulo = 'Proyectos Comunitarios';
        $paginaSubtitulo = 'Módulo para el registro y control de proyectos comunitarios.';
        $proyectosActive = 'active';

        $search = $request->input('search');
        $query = Proyecto::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'LIKE', '%' . $search . '%')
                  ->orWhere('sector_productivo', 'LIKE', '%' . $search . '%')
                  ->orWhere('responsable', 'LIKE', '%' . $search . '%');
            });
        }

        $items = $query->orderBy('created_at', 'desc')->get();

        // Calculate card statistics
        $totalProyectos = Proyecto::count();
        $planificadosCount = Proyecto::where('estatus', 'En planificación')->count();
        $completadosCount = Proyecto::where('estatus', 'Completado')->count();
        $paralizadosCount = Proyecto::where('estatus', 'Paralizado')->count();

        return view('modules.proyectos.index', compact(
            'titulo', 'paginaTitulo', 'paginaSubtitulo', 'proyectosActive', 'items',
            'totalProyectos', 'planificadosCount', 'completadosCount', 'paralizadosCount'
        ));
    }

    /**
     * Store a newly created project in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'            => ['required', 'string', 'min:3', 'max:150'],
            'sector_productivo' => ['required', 'string', 'max:100'],
            'presupuesto'       => ['required', 'numeric', 'min:0'],
            'responsable'       => ['required', 'string', 'min:3', 'max:100'],
            'fecha_inicio'      => ['required', 'string'],
            'descripcion'       => ['required', 'string', 'max:2000'],
        ]);

        try {
            $fecha = Carbon::createFromFormat('d-m-Y', $request->fecha_inicio)->format('Y-m-d');

            Proyecto::create([
                'nombre'            => $request->nombre,
                'sector_productivo' => $request->sector_productivo,
                'presupuesto'       => $request->presupuesto,
                'responsable'       => $request->responsable,
                'fecha_inicio'      => $fecha,
                'estatus'           => 'En planificación', // Default status from user request
                'descripcion'       => $request->descripcion,
            ]);

            return redirect()->route('proyectos.index')->with('success', 'Proyecto registrado correctamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Ocurrió un error al registrar el proyecto: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified project details.
     */
    public function show(string $id)
    {
        $proyecto = Proyecto::findOrFail($id);
        $proyecto->fecha_inicio_formateada = Carbon::parse($proyecto->fecha_inicio)->format('d-m-Y');
        $proyecto->presupuesto_formateado = number_format($proyecto->presupuesto, 2, ',', '.');
        return response()->json($proyecto);
    }

    /**
     * Show the edit form (JSON response).
     */
    public function edit(string $id)
    {
        $proyecto = Proyecto::findOrFail($id);
        $proyecto->fecha_inicio_formateada = Carbon::parse($proyecto->fecha_inicio)->format('d-m-Y');
        return response()->json($proyecto);
    }

    /**
     * Update the specified project in storage.
     */
    public function update(Request $request, string $id)
    {
        $proyecto = Proyecto::findOrFail($id);

        $request->validate([
            'nombre'            => ['required', 'string', 'min:3', 'max:150'],
            'sector_productivo' => ['required', 'string', 'max:100'],
            'presupuesto'       => ['required', 'numeric', 'min:0'],
            'responsable'       => ['required', 'string', 'min:3', 'max:100'],
            'fecha_inicio'      => ['required', 'string'],
            'estatus'           => ['required', 'string', 'in:En planificación,Completado,Paralizado'],
            'descripcion'       => ['required', 'string', 'max:2000'],
        ]);

        try {
            $fecha = Carbon::createFromFormat('d-m-Y', $request->fecha_inicio)->format('Y-m-d');

            $proyecto->update([
                'nombre'            => $request->nombre,
                'sector_productivo' => $request->sector_productivo,
                'presupuesto'       => $request->presupuesto,
                'responsable'       => $request->responsable,
                'fecha_inicio'      => $fecha,
                'estatus'           => $request->estatus,
                'descripcion'       => $request->descripcion,
            ]);

            return redirect()->route('proyectos.index')->with('success', 'Proyecto actualizado correctamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Ocurrió un error al actualizar el proyecto: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified project from storage.
     */
    public function destroy(string $id)
    {
        $proyecto = Proyecto::findOrFail($id);
        $proyecto->delete();

        return redirect()->route('proyectos.index')->with('success', 'Proyecto eliminado correctamente.');
    }
}
