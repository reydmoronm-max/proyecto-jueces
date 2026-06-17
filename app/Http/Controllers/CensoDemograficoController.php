<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\ConsejoComunal;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class CensoDemograficoController extends Controller
{
    /**
     * Display the demographic reports dashboard.
     */
    public function index(Request $request)
    {
        $titulo = 'Reportes Demográficos';
        $paginaTitulo = 'Censo Demográfico y Reportes';
        $paginaSubtitulo = 'Estadísticas del censo ciudadano para la toma de decisiones y reportes.';
        $reportesActive = 'active';

        $data = $this->getDemographicData($request);

        return view('modules.reportes.index', array_merge(
            compact('titulo', 'paginaTitulo', 'paginaSubtitulo', 'reportesActive'),
            $data
        ));
    }

    /**
     * Export the demographic report to a PDF file.
     */
    public function exportPdf(Request $request)
    {
        $data = $this->getDemographicData($request);
        $data['fechaReporte'] = Carbon::now()->format('d-m-Y H:i A');

        $pdf = Pdf::loadView('modules.reportes.pdf', $data);
        return $pdf->download('reporte-censo-demografico.pdf');
    }

    /**
     * Helper to compute all statistics based on request filters.
     */
    private function getDemographicData(Request $request)
    {
        $consejoComunalId = $request->input('consejo_comunal_id');
        $customAge = $request->input('custom_age', 18);
        $customAgeOperator = $request->input('custom_age_operator', 'above'); // 'above' or 'below'

        // Base query
        $query = Persona::query();
        if ($consejoComunalId) {
            $query->where('consejo_comunal_id', $consejoComunalId);
        }

        // 1. Total Citizens
        $totalCiudadanos = (clone $query)->count();

        // 2. Gender stats
        $generoMasculino = (clone $query)->where('genero', 'Masculino')->count();
        $generoFemenino = (clone $query)->where('genero', 'Femenino')->count();
        $generoOtros = $totalCiudadanos - ($generoMasculino + $generoFemenino);

        // 3. Age statistics (Above/Below custom age)
        $customAgeCount = 0;
        if ($customAge !== null && is_numeric($customAge)) {
            $thresholdDate = Carbon::now()->subYears($customAge)->toDateString();
            $ageQuery = clone $query;
            if ($customAgeOperator === 'above') {
                $ageQuery->where('fecha_nacimiento', '<=', $thresholdDate);
            } else {
                $ageQuery->where('fecha_nacimiento', '>', $thresholdDate);
            }
            $customAgeCount = $ageQuery->count();
        }

        // Age Groups breakdown
        $fecha18 = Carbon::now()->subYears(18)->toDateString();
        $fecha60 = Carbon::now()->subYears(60)->toDateString();
        
        $menoresCount = (clone $query)->whereNotNull('fecha_nacimiento')->where('fecha_nacimiento', '>', $fecha18)->count();
        $adultosCount = (clone $query)->whereNotNull('fecha_nacimiento')
            ->where('fecha_nacimiento', '<=', $fecha18)
            ->where('fecha_nacimiento', '>', $fecha60)
            ->count();
        $abuelosCount = (clone $query)->whereNotNull('fecha_nacimiento')->where('fecha_nacimiento', '<=', $fecha60)->count();
        $sinFechaCount = (clone $query)->whereNull('fecha_nacimiento')->count();

        // 4. Study stats
        $estudianCount = (clone $query)->where('estudia', 'Sí')->count();
        $noEstudianCount = (clone $query)->where(function($q) {
            $q->where('estudia', 'No')->orWhereNull('estudia');
        })->count();

        // 5. Disease stats
        $conEnfermedadCount = (clone $query)->whereNotNull('tipo_enfermedad')
            ->where('tipo_enfermedad', '!=', '')
            ->where('tipo_enfermedad', '!=', 'Ninguna')
            ->count();

        $enfermedades = (clone $query)->whereNotNull('tipo_enfermedad')
            ->where('tipo_enfermedad', '!=', '')
            ->where('tipo_enfermedad', '!=', 'Ninguna')
            ->select('tipo_enfermedad', DB::raw('count(*) as total'))
            ->groupBy('tipo_enfermedad')
            ->orderBy('total', 'desc')
            ->take(10)
            ->get();

        // 6. Academic level stats
        $nivelesDb = (clone $query)->select('nivel_academico', DB::raw('count(*) as total'))
            ->whereNotNull('nivel_academico')
            ->groupBy('nivel_academico')
            ->get()
            ->pluck('total', 'nivel_academico')
            ->toArray();

        $niveles = [
            'Ninguno' => $nivelesDb['Ninguno'] ?? 0,
            'Primaria' => $nivelesDb['Primaria'] ?? 0,
            'Secundaria' => $nivelesDb['Secundaria'] ?? 0,
            'Técnico' => $nivelesDb['Técnico'] ?? 0,
            'Universitario' => $nivelesDb['Universitario'] ?? 0,
            'Postgrado' => $nivelesDb['Postgrado'] ?? 0,
        ];

        // 7. Socioeconomic stats
        $viviendaDb = (clone $query)->select('vivienda', DB::raw('count(*) as total'))
            ->whereNotNull('vivienda')
            ->groupBy('vivienda')
            ->get()
            ->pluck('total', 'vivienda')
            ->toArray();

        $viviendas = [
            'Propia' => $viviendaDb['Propia'] ?? 0,
            'Prestada' => $viviendaDb['Prestada'] ?? 0,
            'Alquilada' => $viviendaDb['Alquilada'] ?? 0,
        ];

        $pensionadosCount = (clone $query)->where('pensionado_jubilado', 'Sí')->count();
        $bonoFamiliarCount = (clone $query)->where('bono_unico_familiar', 'Sí')->count();
        $recibeClapCount = (clone $query)->where('clap', 'Sí')->count();
        $casaAlimentacionCount = (clone $query)->where('casa_alimentacion', 'Sí')->count();

        // Extra info
        $consejosComunales = ConsejoComunal::all();
        $comunidadSeleccionada = $consejoComunalId ? ConsejoComunal::find($consejoComunalId) : null;

        return compact(
            'consejoComunalId', 'customAge', 'customAgeOperator', 'totalCiudadanos',
            'generoMasculino', 'generoFemenino', 'generoOtros', 'customAgeCount',
            'menoresCount', 'adultosCount', 'abuelosCount', 'sinFechaCount',
            'estudianCount', 'noEstudianCount', 'conEnfermedadCount', 'enfermedades',
            'niveles', 'viviendas', 'pensionadosCount', 'bonoFamiliarCount',
            'recibeClapCount', 'casaAlimentacionCount', 'consejosComunales', 'comunidadSeleccionada'
        );
    }
}
