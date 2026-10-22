<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\ConsejoComunal;
use App\Models\Profesion;
use App\Models\Enfermedad;
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
            $query->whereHas('familia', function ($q) use ($consejoComunalId) {
                $q->where('consejo_comunal_id', $consejoComunalId);
            });
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

        // 5. Disease stats (Directamente desde la tabla 'enfermedades')
        // Asegurar que las patologías reportadas existan en la tabla del catálogo 'enfermedades'
        $enfermedadesPersonas = (clone $query)->whereNotNull('tipo_enfermedad')
            ->where('tipo_enfermedad', '!=', '')
            ->pluck('tipo_enfermedad');
        foreach ($enfermedadesPersonas as $enfTexto) {
            foreach (explode(',', $enfTexto) as $eItem) {
                $eItem = trim($eItem);
                if ($eItem !== '' && !in_array(strtolower($eItem), ['ninguna', 'no aplica', 'sano', 'ninguno'])) {
                    Enfermedad::firstOrCreate(['nombre' => $eItem]);
                }
            }
        }

        // Consultar directamente la tabla 'enfermedades'
        $catalogoEnfermedades = Enfermedad::orderBy('nombre', 'asc')->get();
        $enfermedadesData = [];
        foreach ($catalogoEnfermedades as $enf) {
            $nombreEnf = trim($enf->nombre);
            if ($nombreEnf === '' || in_array(strtolower($nombreEnf), ['ninguna', 'no aplica', 'sano', 'ninguno'])) {
                continue;
            }
            // Contar personas de la consulta filtrada que posean esta enfermedad registrada
            $count = (clone $query)->whereNotNull('tipo_enfermedad')
                ->where(function ($subQ) use ($nombreEnf) {
                    $subQ->where('tipo_enfermedad', $nombreEnf)
                         ->orWhere('tipo_enfermedad', 'LIKE', $nombreEnf . ',%')
                         ->orWhere('tipo_enfermedad', 'LIKE', '%, ' . $nombreEnf . '%')
                         ->orWhere('tipo_enfermedad', 'LIKE', '%,' . $nombreEnf . '%');
                })
                ->count();

            if ($count > 0) {
                $enfermedadesData[] = (object) [
                    'id' => $enf->id,
                    'tipo_enfermedad' => $nombreEnf,
                    'total' => $count,
                ];
            }
        }
        usort($enfermedadesData, fn($a, $b) => $b->total <=> $a->total ?: strcmp($a->tipo_enfermedad, $b->tipo_enfermedad));
        $enfermedades = collect($enfermedadesData)->take(15);

        $conEnfermedadCount = (clone $query)->whereNotNull('tipo_enfermedad')
            ->where('tipo_enfermedad', '!=', '')
            ->where('tipo_enfermedad', '!=', 'Ninguna')
            ->where('tipo_enfermedad', '!=', 'No aplica')
            ->count();

        // 6. Academic level stats (Categorías oficiales del censo)
        $nivelesOficiales = [
            'Ninguno' => ['Ninguno'],
            'Preescolar' => ['Preescolar'],
            'Primaria' => ['Primaria'],
            'Secundaria con título de bachiller' => [
                'Secundaria con titulo de bachiller',
                'Secundaria con título de bachiller',
                'Secundaria con titulo de bachíller',
                'Secundaria',
                'Bachiller'
            ],
            'Superior con certificación de técnico superior' => [
                'Superior con certificacion de tecnico superior',
                'Superior con certificación de técnico superior',
                'Técnico',
                'Técnico Superior',
                'Tecnico'
            ],
            'Pregrado universitario (licenciatura, ingeniería)' => [
                'Pregrado universitario (licenciatura, ingenieria)',
                'Pregrado universitario (licenciatura, ingeniería)',
                'Universitario'
            ],
            'Posgrado universitario (maestría, doctorado, especialización)' => [
                'Posgrado universitario (maestria, doctorado, especializacion)',
                'Posgrado universitario (maestría, doctorado, especialización)',
                'Postgrado',
                'Posgrado'
            ],
            'No sabe/no responde' => [
                'No sabe/no responde',
                'No sabe',
                'No responde'
            ],
        ];

        $niveles = [];
        foreach ($nivelesOficiales as $nombreNivel => $aliases) {
            $niveles[$nombreNivel] = (clone $query)->whereIn('nivel_academico', $aliases)->count();
        }

        // 7. Socioeconomic stats
        $viviendas = [
            'Propia'   => (clone $query)->whereHas('familia', fn($q) => $q->where('vivienda', 'Propia'))->count(),
            'Prestada' => (clone $query)->whereHas('familia', fn($q) => $q->where('vivienda', 'Prestada'))->count(),
            'Alquilada'=> (clone $query)->whereHas('familia', fn($q) => $q->where('vivienda', 'Alquilada'))->count(),
        ];

        $pensionadosCount = (clone $query)->where('pensionado_jubilado', 'Sí')->count();
        $bonoFamiliarCount = (clone $query)->whereHas('familia', function ($q) {
            $q->where('bono_unico_familiar', 'Sí');
        })->count();
        $recibeClapCount = (clone $query)->whereHas('familia', function ($q) {
            $q->where('clap', 'Sí');
        })->count();
        $misionViviendaCount = (clone $query)->whereHas('familia', function ($q) {
            $q->where('mision_vivienda', 'Sí');
        })->count();

        // 8. Professions / Trades stats (Directamente desde la tabla 'profesiones')
        // Asegurar que las profesiones reportadas existan en la tabla del catálogo 'profesiones'
        $profesionesPersonas = (clone $query)->whereNotNull('profesion')
            ->where('profesion', '!=', '')
            ->pluck('profesion')
            ->unique();
        foreach ($profesionesPersonas as $profTexto) {
            $profTexto = trim($profTexto);
            if ($profTexto !== '' && !in_array(strtolower($profTexto), ['ninguna', 'no aplica', 'ninguno'])) {
                Profesion::firstOrCreate(['nombre' => $profTexto]);
            }
        }

        // Consultar directamente la tabla 'profesiones'
        $catalogoProfesiones = Profesion::orderBy('nombre', 'asc')->get();
        $profesionesData = [];
        $totalConProfesion = 0;

        foreach ($catalogoProfesiones as $prof) {
            $nombreProf = trim($prof->nombre);
            if ($nombreProf === '' || in_array(strtolower($nombreProf), ['ninguna', 'no aplica', 'ninguno'])) {
                continue;
            }
            // Contar personas de la consulta filtrada que posean esta profesión
            $count = (clone $query)->whereNotNull('profesion')
                ->where('profesion', $nombreProf)
                ->count();

            if ($count > 0) {
                $profesionesData[] = (object) [
                    'id' => $prof->id,
                    'profesion' => $nombreProf,
                    'total' => $count,
                ];
                $totalConProfesion += $count;
            }
        }
        usort($profesionesData, fn($a, $b) => $b->total <=> $a->total ?: strcmp($a->profesion, $b->profesion));
        $profesiones = collect($profesionesData)->take(15);

        $sinProfesionCount = max(0, $totalCiudadanos - $totalConProfesion);

        // 9. Age Histogram Distribution (Intervals of 10 years)
        $personasFechas = (clone $query)->whereNotNull('fecha_nacimiento')->pluck('fecha_nacimiento');
        $histogramaEdades = [
            '0-9' => 0,
            '10-19' => 0,
            '20-29' => 0,
            '30-39' => 0,
            '40-49' => 0,
            '50-59' => 0,
            '60-69' => 0,
            '70+' => 0,
        ];
        foreach ($personasFechas as $fn) {
            try {
                $edad = Carbon::parse($fn)->age;
                if ($edad < 10) {
                    $histogramaEdades['0-9']++;
                } elseif ($edad < 20) {
                    $histogramaEdades['10-19']++;
                } elseif ($edad < 30) {
                    $histogramaEdades['20-29']++;
                } elseif ($edad < 40) {
                    $histogramaEdades['30-39']++;
                } elseif ($edad < 50) {
                    $histogramaEdades['40-49']++;
                } elseif ($edad < 60) {
                    $histogramaEdades['50-59']++;
                } elseif ($edad < 70) {
                    $histogramaEdades['60-69']++;
                } else {
                    $histogramaEdades['70+']++;
                }
            } catch (\Throwable $e) {
                // Ignore parse errors
            }
        }

        // 10. Gender per life-cycle for comparative histogram
        $generoPorCiclo = [
            'menores' => [
                'masculino' => (clone $query)->whereNotNull('fecha_nacimiento')->where('fecha_nacimiento', '>', $fecha18)->where('genero', 'Masculino')->count(),
                'femenino' => (clone $query)->whereNotNull('fecha_nacimiento')->where('fecha_nacimiento', '>', $fecha18)->where('genero', 'Femenino')->count(),
            ],
            'adultos' => [
                'masculino' => (clone $query)->whereNotNull('fecha_nacimiento')->where('fecha_nacimiento', '<=', $fecha18)->where('fecha_nacimiento', '>', $fecha60)->where('genero', 'Masculino')->count(),
                'femenino' => (clone $query)->whereNotNull('fecha_nacimiento')->where('fecha_nacimiento', '<=', $fecha18)->where('fecha_nacimiento', '>', $fecha60)->where('genero', 'Femenino')->count(),
            ],
            'abuelos' => [
                'masculino' => (clone $query)->whereNotNull('fecha_nacimiento')->where('fecha_nacimiento', '<=', $fecha60)->where('genero', 'Masculino')->count(),
                'femenino' => (clone $query)->whereNotNull('fecha_nacimiento')->where('fecha_nacimiento', '<=', $fecha60)->where('genero', 'Femenino')->count(),
            ],
        ];

        // Extra info
        $consejosComunales = ConsejoComunal::all();
        $comunidadSeleccionada = $consejoComunalId ? ConsejoComunal::find($consejoComunalId) : null;

        return compact(
            'consejoComunalId', 'customAge', 'customAgeOperator', 'totalCiudadanos',
            'generoMasculino', 'generoFemenino', 'generoOtros', 'customAgeCount',
            'menoresCount', 'adultosCount', 'abuelosCount', 'sinFechaCount',
            'estudianCount', 'noEstudianCount', 'conEnfermedadCount', 'enfermedades',
            'niveles', 'viviendas', 'pensionadosCount', 'bonoFamiliarCount',
            'recibeClapCount', 'misionViviendaCount', 'consejosComunales', 'comunidadSeleccionada',
            'profesiones', 'totalConProfesion', 'sinProfesionCount', 'histogramaEdades', 'generoPorCiclo'
        );
    }
}
