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
        $paginaSubtitulo = 'Estadísticas del censo ciudadano para la toma de decisiones, listados de acción y reportes.';
        $reportesActive = 'active';

        $data = $this->getDemographicData($request);

        return view('modules.reportes.index', array_merge(
            compact('titulo', 'paginaTitulo', 'paginaSubtitulo', 'reportesActive'),
            $data
        ));
    }

    /**
     * Export demographic report to PDF (General, Salud, Socio-Productivo).
     */
    public function exportPdf(Request $request)
    {
        $tipoReporte = $request->input('tipo_reporte', 'general');
        $data = $this->getDemographicData($request);
        $data['fechaReporte'] = Carbon::now()->format('d-m-Y H:i A');
        $data['tipoReporte'] = $tipoReporte;

        if ($tipoReporte === 'salud') {
            $pdf = Pdf::loadView('modules.reportes.pdf_salud', $data);
            return $pdf->stream('reporte-salud-comunal.pdf');
        } elseif ($tipoReporte === 'socioproductivo') {
            $pdf = Pdf::loadView('modules.reportes.pdf_socioproductivo', $data);
            return $pdf->stream('reporte-socio-productivo.pdf');
        }

        $pdf = Pdf::loadView('modules.reportes.pdf', $data);
        return $pdf->stream('reporte-censo-demografico.pdf');
    }

    /**
     * Export specialized nominal list (field work sheet) to landscape PDF.
     */
    public function exportListadoPdf(Request $request)
    {
        $listadoTipo = $request->input('listado_tipo', 'electoral');
        $data = $this->getDemographicData($request);
        $data['fechaReporte'] = Carbon::now()->format('d/m/Y h:i A');
        $data['listadoTipo'] = $listadoTipo;

        $query = Persona::with(['familia.consejoComunal']);
        if ($data['consejoComunalId']) {
            $query->whereHas('familia', fn($q) => $q->where('consejo_comunal_id', $data['consejoComunalId']));
        }
        if ($data['genero']) {
            $query->where('genero', $data['genero']);
        }

        $now = Carbon::now();
        $fecha18 = $now->copy()->subYears(18)->toDateString();
        $fecha60 = $now->copy()->subYears(60)->toDateString();

        if ($listadoTipo === 'electoral') {
            $tituloListado = 'Registro Electoral Comunitario (Mayores de 18 años)';
            $subtituloListado = 'Listado nominal para control de centros de votación y movilización ciudadana';
            $personas = $query->whereNotNull('fecha_nacimiento')
                ->where('fecha_nacimiento', '<=', $fecha18)
                ->orderBy('apellidos', 'asc')
                ->get();
        } elseif ($listadoTipo === 'salud') {
            $tituloListado = 'Censo de Salud Comunal y Atención de Patologías';
            $subtituloListado = 'Registro de ciudadanos con condiciones médicas crónicas para entrega de fármacos y jornadas';
            if ($data['enfermedadFiltro']) {
                $enf = $data['enfermedadFiltro'];
                $query->where(function($q) use ($enf) {
                    $q->where('tipo_enfermedad', $enf)
                      ->orWhere('tipo_enfermedad', 'LIKE', $enf . ',%')
                      ->orWhere('tipo_enfermedad', 'LIKE', '%, ' . $enf . '%')
                      ->orWhere('tipo_enfermedad', 'LIKE', '%,' . $enf . '%');
                });
            } else {
                $query->whereNotNull('tipo_enfermedad')
                    ->where('tipo_enfermedad', '!=', '')
                    ->whereNotIn(DB::raw('LOWER(tipo_enfermedad)'), ['ninguna', 'no aplica', 'sano', 'ninguno']);
            }
            $personas = $query->orderBy('apellidos', 'asc')->get();
        } elseif ($listadoTipo === 'abuelos') {
            $tituloListado = 'Registro Comunitario de Adultos Mayores (60+ años)';
            $subtituloListado = 'Censo para asignación de beneficios del Círculo de Abuelos, IVSS y atención social';
            $personas = $query->whereNotNull('fecha_nacimiento')
                ->where('fecha_nacimiento', '<=', $fecha60)
                ->orderBy('fecha_nacimiento', 'asc')
                ->get();
        } elseif ($listadoTipo === 'estudiantes') {
            $tituloListado = 'Registro Infanto-Juvenil y Población Estudiantil';
            $subtituloListado = 'Control de escolaridad, formación técnica-universitaria y niñez comunitaria';
            $personas = $query->where(function($q) use ($fecha18) {
                $q->where('estudia', 'Sí')
                  ->orWhere(function($sub) use ($fecha18) {
                      $sub->whereNotNull('fecha_nacimiento')->where('fecha_nacimiento', '>', $fecha18);
                  });
            })->orderBy('apellidos', 'asc')->get();
        } elseif ($listadoTipo === 'talentos') {
            $tituloListado = 'Banco de Talentos, Profesiones y Oficios Comunitarios';
            $subtituloListado = 'Registro de profesionales, técnicos y trabajadores para proyectos y cuadrillas comunales';
            if ($data['profesionFiltro']) {
                $query->where('profesion', $data['profesionFiltro']);
            } else {
                $query->whereNotNull('profesion')
                    ->where('profesion', '!=', '')
                    ->whereNotIn(DB::raw('LOWER(profesion)'), ['ninguna', 'no aplica', 'ninguno']);
            }
            $personas = $query->orderBy('profesion', 'asc')->orderBy('apellidos', 'asc')->get();
        } else {
            $tituloListado = 'Listado Nominal General del Censo';
            $subtituloListado = 'Registro de ciudadanos censados';
            $personas = $query->orderBy('apellidos', 'asc')->get();
        }

        $data['tituloListado'] = $tituloListado;
        $data['subtituloListado'] = $subtituloListado;
        $data['personas'] = $personas;

        $pdf = Pdf::loadView('modules.reportes.pdf_listado_nominal', $data)->setPaper('a4', 'landscape');
        return $pdf->stream('listado-' . $listadoTipo . '.pdf');
    }

    /**
     * Helper to compute all statistics based on request filters.
     */
    private function getDemographicData(Request $request)
    {
        $consejoComunalId = $request->input('consejo_comunal_id');
        $genero = $request->input('genero');
        $grupoEtario = $request->input('grupo_etario');
        $enfermedadFiltro = $request->input('enfermedad');
        $profesionFiltro = $request->input('profesion');
        $nivelFiltro = $request->input('nivel_academico');
        $viviendaFiltro = $request->input('vivienda');
        $beneficioFiltro = $request->input('beneficio');
        $tabActiva = $request->input('tab_activa', 'graficos');
        $listadoActivo = $request->input('listado_activo', 'electoral');
        $ccComparar1 = $request->input('cc_comparar_1');
        $ccComparar2 = $request->input('cc_comparar_2');

        $now = Carbon::now();
        $fecha18 = $now->copy()->subYears(18)->toDateString();
        $fecha60 = $now->copy()->subYears(60)->toDateString();

        // Base query con filtros dinámicos
        $query = Persona::query();
        if ($consejoComunalId) {
            $query->whereHas('familia', function ($q) use ($consejoComunalId) {
                $q->where('consejo_comunal_id', $consejoComunalId);
            });
        }

        if ($genero && in_array($genero, ['Masculino', 'Femenino'], true)) {
            $query->where('genero', $genero);
        }

        if ($grupoEtario === 'menores') {
            $query->whereNotNull('fecha_nacimiento')->where('fecha_nacimiento', '>', $fecha18);
        } elseif ($grupoEtario === 'adultos') {
            $query->whereNotNull('fecha_nacimiento')
                ->where('fecha_nacimiento', '<=', $fecha18)
                ->where('fecha_nacimiento', '>', $fecha60);
        } elseif ($grupoEtario === 'abuelos') {
            $query->whereNotNull('fecha_nacimiento')->where('fecha_nacimiento', '<=', $fecha60);
        }

        if ($enfermedadFiltro) {
            $query->whereNotNull('tipo_enfermedad')->where(function($q) use ($enfermedadFiltro) {
                $q->where('tipo_enfermedad', $enfermedadFiltro)
                  ->orWhere('tipo_enfermedad', 'LIKE', $enfermedadFiltro . ',%')
                  ->orWhere('tipo_enfermedad', 'LIKE', '%, ' . $enfermedadFiltro . '%')
                  ->orWhere('tipo_enfermedad', 'LIKE', '%,' . $enfermedadFiltro . '%');
            });
        }

        if ($profesionFiltro) {
            $query->where('profesion', $profesionFiltro);
        }

        if ($nivelFiltro) {
            $query->where('nivel_academico', $nivelFiltro);
        }

        if ($viviendaFiltro && in_array($viviendaFiltro, ['Propia', 'Prestada', 'Alquilada'], true)) {
            $query->whereHas('familia', fn($q) => $q->where('vivienda', $viviendaFiltro));
        }

        if ($beneficioFiltro === 'clap') {
            $query->whereHas('familia', fn($q) => $q->where('clap', 'Sí'));
        } elseif ($beneficioFiltro === 'bono_familiar') {
            $query->whereHas('familia', fn($q) => $q->where('bono_unico_familiar', 'Sí'));
        } elseif ($beneficioFiltro === 'ninguno') {
            $query->whereHas('familia', function ($subQ) {
                $subQ->whereIn('clap', ['No', null, ''])
                     ->whereIn('bono_unico_familiar', ['No', null, '']);
            });
        }

        // 1. Total Ciudadanos
        $totalCiudadanos = (clone $query)->count();

        // 2. Gender stats
        $generoMasculino = (clone $query)->where('genero', 'Masculino')->count();
        $generoFemenino = (clone $query)->where('genero', 'Femenino')->count();
        $generoOtros = max(0, $totalCiudadanos - ($generoMasculino + $generoFemenino));

        // 3. Grupos Etarios
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

        // 5. Enfermedades stats
        $catalogoEnfermedades = Enfermedad::orderBy('nombre', 'asc')->get();
        $enfermedadesData = [];
        foreach ($catalogoEnfermedades as $enf) {
            $nombreEnf = trim($enf->nombre);
            if ($nombreEnf === '' || in_array(strtolower($nombreEnf), ['ninguna', 'no aplica', 'sano', 'ninguno'])) {
                continue;
            }
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
            ->whereNotIn(DB::raw('LOWER(tipo_enfermedad)'), ['ninguna', 'no aplica', 'sano', 'ninguno'])
            ->count();

        // 6. Nivel académico
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

        // 7. Socioeconómicos
        $viviendas = [
            'Propia'   => (clone $query)->whereHas('familia', fn($q) => $q->where('vivienda', 'Propia'))->count(),
            'Prestada' => (clone $query)->whereHas('familia', fn($q) => $q->where('vivienda', 'Prestada'))->count(),
            'Alquilada'=> (clone $query)->whereHas('familia', fn($q) => $q->where('vivienda', 'Alquilada'))->count(),
        ];

        $pensionadosCount = (clone $query)->where('pensionado_jubilado', 'Sí')->count();
        $bonoFamiliarCount = (clone $query)->whereHas('familia', fn($q) => $q->where('bono_unico_familiar', 'Sí'))->count();
        $recibeClapCount = (clone $query)->whereHas('familia', fn($q) => $q->where('clap', 'Sí'))->count();
        $misionViviendaCount = (clone $query)->whereHas('familia', fn($q) => $q->where('mision_vivienda', 'Sí'))->count();

        // 8. Profesiones
        $catalogoProfesiones = Profesion::orderBy('nombre', 'asc')->get();
        $profesionesData = [];
        $totalConProfesion = 0;

        foreach ($catalogoProfesiones as $prof) {
            $nombreProf = trim($prof->nombre);
            if ($nombreProf === '' || in_array(strtolower($nombreProf), ['ninguna', 'no aplica', 'ninguno'])) {
                continue;
            }
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

        // 9. Histograma de Edades
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
            } catch (\Throwable $e) {}
        }

        // 10. Género por ciclo de vida
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

        // 11. Datos de Listados Nominales de Acción (Registro Focalizado)
        // 11.1 Registro Electoral (18+ años)
        $electoralQuery = (clone $query)->with(['familia.consejoComunal'])
            ->whereNotNull('fecha_nacimiento')
            ->where('fecha_nacimiento', '<=', $fecha18);
        $totalElectoral = (clone $electoralQuery)->count();
        $listadoElectoral = (clone $electoralQuery)->orderBy('apellidos', 'asc')->take(100)->get();

        // 11.2 Salud Comunal
        $saludQuery = (clone $query)->with(['familia.consejoComunal'])
            ->whereNotNull('tipo_enfermedad')
            ->where('tipo_enfermedad', '!=', '')
            ->whereNotIn(DB::raw('LOWER(tipo_enfermedad)'), ['ninguna', 'no aplica', 'sano', 'ninguno']);
        $totalSalud = (clone $saludQuery)->count();
        $listadoSalud = (clone $saludQuery)->orderBy('apellidos', 'asc')->take(100)->get();

        // 11.3 Adultos Mayores
        $abuelosQuery = (clone $query)->with(['familia.consejoComunal'])
            ->whereNotNull('fecha_nacimiento')
            ->where('fecha_nacimiento', '<=', $fecha60);
        $totalAbuelosLista = (clone $abuelosQuery)->count();
        $listadoAbuelos = (clone $abuelosQuery)->orderBy('fecha_nacimiento', 'asc')->take(100)->get();

        // 11.4 Infanto-Juvenil y Estudiantes
        $estudiantesQuery = (clone $query)->with(['familia.consejoComunal'])
            ->where(function($q) use ($fecha18) {
                $q->where('estudia', 'Sí')
                  ->orWhere(function($sub) use ($fecha18) {
                      $sub->whereNotNull('fecha_nacimiento')->where('fecha_nacimiento', '>', $fecha18);
                  });
            });
        $totalEstudiantes = (clone $estudiantesQuery)->count();
        $listadoEstudiantes = (clone $estudiantesQuery)->orderBy('apellidos', 'asc')->take(100)->get();

        // 11.5 Talentos y Oficios
        $talentosQuery = (clone $query)->with(['familia.consejoComunal'])
            ->whereNotNull('profesion')
            ->where('profesion', '!=', '')
            ->whereNotIn(DB::raw('LOWER(profesion)'), ['ninguna', 'no aplica', 'ninguno']);
        $totalTalentos = (clone $talentosQuery)->count();
        $listadoTalentos = (clone $talentosQuery)->orderBy('profesion', 'asc')->orderBy('apellidos', 'asc')->take(100)->get();

        // 12. Comparativa entre dos Consejos Comunales
        $comparativa = null;
        if ($ccComparar1 && $ccComparar2 && $ccComparar1 != $ccComparar2) {
            $comparativa = [
                'cc1' => $this->getCommunitySnapshot($ccComparar1),
                'cc2' => $this->getCommunitySnapshot($ccComparar2),
            ];
        }

        // Listas generales de selección
        $consejosComunales = ConsejoComunal::orderBy('nombre', 'asc')->get();
        $comunidadSeleccionada = $consejoComunalId ? ConsejoComunal::find($consejoComunalId) : null;

        return compact(
            'consejoComunalId', 'genero', 'grupoEtario', 'enfermedadFiltro',
            'profesionFiltro', 'nivelFiltro', 'viviendaFiltro', 'beneficioFiltro',
            'tabActiva', 'listadoActivo', 'ccComparar1', 'ccComparar2',
            'totalCiudadanos', 'generoMasculino', 'generoFemenino', 'generoOtros',
            'menoresCount', 'adultosCount', 'abuelosCount', 'sinFechaCount',
            'estudianCount', 'noEstudianCount', 'conEnfermedadCount', 'enfermedades',
            'niveles', 'viviendas', 'pensionadosCount', 'bonoFamiliarCount',
            'recibeClapCount', 'misionViviendaCount', 'consejosComunales', 'comunidadSeleccionada',
            'profesiones', 'totalConProfesion', 'sinProfesionCount', 'histogramaEdades', 'generoPorCiclo',
            'catalogoEnfermedades', 'catalogoProfesiones',
            'totalElectoral', 'listadoElectoral',
            'totalSalud', 'listadoSalud',
            'totalAbuelosLista', 'listadoAbuelos',
            'totalEstudiantes', 'listadoEstudiantes',
            'totalTalentos', 'listadoTalentos',
            'comparativa'
        );
    }

    /**
     * Compute a quick snapshot of key metrics for a single community council.
     */
    private function getCommunitySnapshot($ccId)
    {
        $consejo = ConsejoComunal::find($ccId);
        if (!$consejo) {
            return null;
        }

        $query = Persona::whereHas('familia', fn($q) => $q->where('consejo_comunal_id', $ccId));
        $totalPoblacion = (clone $query)->count();

        $now = Carbon::now();
        $fecha18 = $now->copy()->subYears(18)->toDateString();
        $fecha60 = $now->copy()->subYears(60)->toDateString();

        $familiasCount = \App\Models\Familia::where('consejo_comunal_id', $ccId)->count();
        $hombres = (clone $query)->where('genero', 'Masculino')->count();
        $mujeres = (clone $query)->where('genero', 'Femenino')->count();
        $menores = (clone $query)->whereNotNull('fecha_nacimiento')->where('fecha_nacimiento', '>', $fecha18)->count();
        $adultos = (clone $query)->whereNotNull('fecha_nacimiento')
            ->where('fecha_nacimiento', '<=', $fecha18)
            ->where('fecha_nacimiento', '>', $fecha60)
            ->count();
        $abuelos = (clone $query)->whereNotNull('fecha_nacimiento')->where('fecha_nacimiento', '<=', $fecha60)->count();

        $conPatologias = (clone $query)->whereNotNull('tipo_enfermedad')
            ->where('tipo_enfermedad', '!=', '')
            ->whereNotIn(DB::raw('LOWER(tipo_enfermedad)'), ['ninguna', 'no aplica', 'sano', 'ninguno'])
            ->count();

        $recibeClap = (clone $query)->whereHas('familia', fn($q) => $q->where('clap', 'Sí'))->count();
        $viviendaPropia = (clone $query)->whereHas('familia', fn($q) => $q->where('vivienda', 'Propia'))->count();
        $estudiantes = (clone $query)->where('estudia', 'Sí')->count();

        return [
            'id'             => $consejo->id,
            'nombre'         => $consejo->nombre,
            'familias'       => $familiasCount,
            'poblacion'      => $totalPoblacion,
            'hombres'        => $hombres,
            'mujeres'        => $mujeres,
            'menores'        => $menores,
            'adultos'        => $adultos,
            'abuelos'        => $abuelos,
            'patologias'     => $conPatologias,
            'clap'           => $recibeClap,
            'viviendaPropia' => $viviendaPropia,
            'estudiantes'    => $estudiantes,
        ];
    }
}
