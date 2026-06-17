<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\JornadaAbuelo;
use App\Models\ConsejoComunal;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CirculoAbuelosController extends Controller
{
    /**
     * Display the index view containing Abuelos and Jornadas tabs.
     */
    public function index(Request $request)
    {
        $titulo = 'Círculo de Abuelos';
        $paginaTitulo = 'Círculo de Abuelos';
        $paginaSubtitulo = 'Módulo para la atención social y registro de adultos mayores.';
        $circuloActive = 'active';

        $search = $request->input('search');
        $consejoComunalId = $request->input('consejo_comunal_id');

        // Age threshold for 60 years or older
        $thresholdDate = Carbon::now()->subYears(60)->toDateString();

        $queryAbuelos = Persona::with(['consejoComunal', 'familia'])
            ->whereNotNull('fecha_nacimiento')
            ->where('fecha_nacimiento', '<=', $thresholdDate);

        if ($search) {
            $queryAbuelos->where(function($q) use ($search) {
                $q->where('cedula', 'LIKE', '%' . $search . '%')
                  ->orWhere('nombres', 'LIKE', '%' . $search . '%')
                  ->orWhere('apellidos', 'LIKE', '%' . $search . '%');
            });
        }

        if ($consejoComunalId) {
            $queryAbuelos->where('consejo_comunal_id', $consejoComunalId);
        }

        $abuelos = $queryAbuelos->orderBy('nombres', 'asc')->get();

        // Calculate Age dynamically for display helper
        foreach ($abuelos as $abuelo) {
            $abuelo->edad = Carbon::parse($abuelo->fecha_nacimiento)->age;
        }

        // Fetch Jornadas
        $jornadas = JornadaAbuelo::with('consejoComunal')
            ->orderBy('fecha_programada', 'desc')
            ->get();

        // Stats
        $totalAbuelos = Persona::whereNotNull('fecha_nacimiento')
            ->where('fecha_nacimiento', '<=', $thresholdDate)
            ->count();
            
        $jornadasRealizadas = JornadaAbuelo::where('estatus', 'Completada')->count();
        $jornadasAgendadas = JornadaAbuelo::where('estatus', 'Planificada')->count();

        $consejosComunales = ConsejoComunal::all();

        return view('modules.circulo-abuelos.index', compact(
            'titulo', 'paginaTitulo', 'paginaSubtitulo', 'circuloActive',
            'abuelos', 'jornadas', 'totalAbuelos', 'jornadasRealizadas', 'jornadasAgendadas',
            'consejosComunales'
        ));
    }

    /**
     * Store a new Jornada.
     */
    public function storeJornada(Request $request)
    {
        $request->validate([
            'nombre_jornada'     => ['required', 'string', 'min:3', 'max:150'],
            'fecha_programada'   => ['required', 'string'],
            'consejo_comunal_id' => ['required', 'exists:consejos_comunales,id'],
            'detalles'           => ['nullable', 'string', 'max:1000'],
        ]);

        try {
            $fecha = Carbon::createFromFormat('d-m-Y', $request->fecha_programada)->format('Y-m-d');

            JornadaAbuelo::create([
                'nombre_jornada'     => $request->nombre_jornada,
                'fecha_programada'   => $fecha,
                'estatus'            => 'Planificada', // Default status
                'consejo_comunal_id' => $request->consejo_comunal_id,
                'detalles'           => $request->detalles,
            ]);

            return redirect()->route('circulo-abuelos.index', ['tab' => 'jornadas'])->with('success', 'Jornada planificada correctamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Ocurrió un error al planificar la jornada: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Show a specific Jornada.
     */
    public function showJornada(string $id)
    {
        $jornada = JornadaAbuelo::with('consejoComunal')->findOrFail($id);
        $jornada->fecha_programada_formateada = Carbon::parse($jornada->fecha_programada)->format('d-m-Y');
        return response()->json($jornada);
    }

    /**
     * Show the edit form (JSON response).
     */
    public function editJornada(string $id)
    {
        $jornada = JornadaAbuelo::findOrFail($id);
        $jornada->fecha_programada_formateada = Carbon::parse($jornada->fecha_programada)->format('d-m-Y');
        return response()->json($jornada);
    }

    /**
     * Update a specific Jornada.
     */
    public function updateJornada(Request $request, string $id)
    {
        $jornada = JornadaAbuelo::findOrFail($id);

        $request->validate([
            'nombre_jornada'     => ['required', 'string', 'min:3', 'max:150'],
            'fecha_programada'   => ['required', 'string'],
            'estatus'            => ['required', 'string', 'in:Planificada,Completada,Suspendida'],
            'consejo_comunal_id' => ['required', 'exists:consejos_comunales,id'],
            'detalles'           => ['nullable', 'string', 'max:1000'],
        ]);

        try {
            $fecha = Carbon::createFromFormat('d-m-Y', $request->fecha_programada)->format('Y-m-d');

            $jornada->update([
                'nombre_jornada'     => $request->nombre_jornada,
                'fecha_programada'   => $fecha,
                'estatus'            => $request->estatus,
                'consejo_comunal_id' => $request->consejo_comunal_id,
                'detalles'           => $request->detalles,
            ]);

            return redirect()->route('circulo-abuelos.index', ['tab' => 'jornadas'])->with('success', 'Jornada actualizada correctamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Ocurrió un error al actualizar la jornada: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Delete a specific Jornada.
     */
    public function destroyJornada(string $id)
    {
        $jornada = JornadaAbuelo::findOrFail($id);
        $jornada->delete();

        return redirect()->route('circulo-abuelos.index', ['tab' => 'jornadas'])->with('success', 'Jornada eliminada correctamente.');
    }
}
