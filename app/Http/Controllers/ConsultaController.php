<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\Familia;
use App\Models\ConsejoComunal;
use App\Models\CategoriaVoceria;
use App\Models\Vocero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ConsultaController extends Controller
{
    /**
     * Vista principal del módulo de consulta
     */
    public function index(Request $request)
    {
        $userRol = Auth::user()->rol;
        $esJuez = ($userRol === 'Juez');
        $esComunal = ($userRol === 'Jefe de comuna' || $userRol === 'Jefe de Comando');

        $titulo = 'Consulta';
        $paginaTitulo = $esJuez ? 'Consulta de expedientes' : 'Consulta Ciudadana y Búsqueda Avanzada';
        $paginaSubtitulo = $esJuez 
            ? 'Buscar expedientes, citaciones y actas relacionadas a un ciudadano' 
            : 'Consulta del censo, núcleos familiares y búsqueda avanzada multicriterio';
        $consultaActive = 'active';

        $cedulaTipo = $request->input('cedula_tipo', 'V');
        $cedula = $request->input('cedula');

        $persona = null;
        $expedientes = collect();
        $busquedaRealizada = false;

        // Búsqueda individual por cédula
        if ($cedula) {
            $busquedaRealizada = true;

            if ($esJuez) {
                // Para Juez: buscar persona y sus expedientes judiciales
                $persona = Persona::where('cedula', $cedula)
                    ->when($cedulaTipo, function ($q) use ($cedulaTipo) {
                        $q->where('cedula_tipo', $cedulaTipo);
                    })
                    ->first();

                if ($persona) {
                    $expedientes = $persona->expedientes()
                        ->with([
                            'personas' => function ($q) {
                                $q->withPivot('rol');
                            },
                            'actas',
                            'citaciones' => function ($q) {
                                $q->with('solicitaCambio')
                                    ->orderBy('fecha_citacion', 'desc')
                                    ->orderBy('hora_citacion', 'desc');
                            }
                        ])
                        ->orderBy('created_at', 'desc')
                        ->get();
                }
            } else {
                // Para Gestión Comunal: buscar persona con todas las relaciones censales y comunitarias
                $persona = Persona::with([
                    'familia.personas',
                    'familia.consejoComunal',
                    'consejosComunales', // donde figura como jefe de comando
                    'vocerias',
                ])
                ->where('cedula', $cedula)
                ->when($cedulaTipo, function ($q) use ($cedulaTipo) {
                    $q->where('cedula_tipo', $cedulaTipo);
                })
                ->first();
            }
        }

        // Datos para Búsqueda Avanzada (Solo para Gestión Comunal)
        $consejosComunales = collect();
        $profesiones = collect();
        $categoriasVoceria = collect();
        $resultadosAvanzados = collect();
        $metricasAvanzadas = [];
        $filtrosAplicados = [];
        $busquedaAvanzadaRealizada = false;
        $tabActiva = $request->input('tab', 'individual');

        if ($esComunal) {
            $consejosComunales = ConsejoComunal::orderBy('nombre')->get();
            $profesiones = Persona::whereNotNull('profesion')
                ->where('profesion', '!=', '')
                ->where('profesion', '!=', 'No aplica')
                ->distinct()
                ->orderBy('profesion')
                ->pluck('profesion');
            $categoriasVoceria = CategoriaVoceria::where('activo', true)->orderBy('nombre')->get();

            // Verificar si se enviaron parámetros de búsqueda avanzada
            if ($request->has('buscar_avanzado') || $tabActiva === 'avanzada') {
                $tabActiva = 'avanzada';
                if ($request->has('buscar_avanzado')) {
                    $busquedaAvanzadaRealizada = true;
                    $datosBusqueda = $this->procesarBusquedaAvanzada($request);
                    $resultadosAvanzados = $datosBusqueda['resultados'];
                    $metricasAvanzadas = $datosBusqueda['metricas'];
                    $filtrosAplicados = $datosBusqueda['filtros'];
                }
            }
        }

        return view('modules.expedientes.consulta', compact(
            'titulo',
            'paginaTitulo',
            'paginaSubtitulo',
            'consultaActive',
            'esJuez',
            'esComunal',
            'persona',
            'expedientes',
            'busquedaRealizada',
            'cedulaTipo',
            'cedula',
            'tabActiva',
            'consejosComunales',
            'profesiones',
            'categoriasVoceria',
            'resultadosAvanzados',
            'metricasAvanzadas',
            'filtrosAplicados',
            'busquedaAvanzadaRealizada'
        ));
    }

    /**
     * Retorna los datos y miembros del núcleo familiar en formato JSON para la modal interactiva
     */
    public function getFamiliaModal(string $id)
    {
        $familia = Familia::with([
            'personas' => function ($q) {
                $q->orderByRaw("CASE WHEN parentesco = 'Jefe de familia' THEN 0 ELSE 1 END")
                  ->orderBy('nombres');
            },
            'consejoComunal'
        ])->findOrFail($id);

        // Añadir edad calculada a cada integrante
        $personasFormateadas = $familia->personas->map(function ($p) {
            return [
                'id' => $p->id,
                'cedula' => $p->cedula,
                'cedula_tipo' => $p->cedula_tipo,
                'nombres' => $p->nombres,
                'apellidos' => $p->apellidos,
                'parentesco' => $p->parentesco ?? 'No registrado',
                'telefono' => $p->telefono ?? 'No registrado',
                'genero' => $p->genero ?? 'No registrado',
                'edad' => $p->edad ?? ($p->fecha_nacimiento ? Carbon::parse($p->fecha_nacimiento)->age : null),
                'profesion' => $p->profesion ?? 'No aplica',
                'pensionado_jubilado' => $p->pensionado_jubilado ?? 'No',
                'estudia' => $p->estudia ?? 'No',
            ];
        });

        return response()->json([
            'id' => $familia->id,
            'numero_familia' => $familia->numero_familia,
            'vivienda' => $familia->vivienda ?? 'No registrada',
            'mision_vivienda' => $familia->mision_vivienda ?? 'No',
            'bono_unico_familiar' => $familia->bono_unico_familiar ?? 'No',
            'clap' => $familia->clap ?? 'No',
            'consejo_comunal' => $familia->consejoComunal ? [
                'id' => $familia->consejoComunal->id,
                'nombre' => $familia->consejoComunal->nombre,
                'rif' => $familia->consejoComunal->rif,
                'direccion' => $familia->consejoComunal->direccion,
            ] : null,
            'personas' => $personasFormateadas,
        ]);
    }

    /**
     * Exporta los resultados de la Búsqueda Avanzada a un archivo PDF
     */
    public function exportarPdf(Request $request)
    {
        $userRol = Auth::user()->rol;
        if ($userRol !== 'Jefe de comuna' && $userRol !== 'Jefe de Comando') {
            abort(403, 'No tienes permisos para generar este reporte.');
        }

        $datosBusqueda = $this->procesarBusquedaAvanzada($request, false); // Sin límite/paginación
        $resultados = $datosBusqueda['resultados'];
        $metricas = $datosBusqueda['metricas'];
        $filtros = $datosBusqueda['filtros'];
        $fechaReporte = Carbon::now()->format('d/m/Y h:i A');
        $usuarioGenerador = Auth::user()->nombre . ' ' . Auth::user()->apellido . ' (' . $userRol . ')';

        $pdf = Pdf::loadView('modules.expedientes.pdf_busqueda_avanzada', compact(
            'resultados',
            'metricas',
            'filtros',
            'fechaReporte',
            'usuarioGenerador'
        ));

        $pdf->setPaper('letter', 'portrait');

        return $pdf->download('reporte-busqueda-censo-' . date('Ymd_His') . '.pdf');
    }

    /**
     * Construye y ejecuta la consulta multicriterio para la Búsqueda Avanzada
     */
    private function procesarBusquedaAvanzada(Request $request, bool $conLimite = true): array
    {
        $query = Persona::with([
            'familia.consejoComunal',
            'vocerias',
            'consejosComunales'
        ]);

        $filtros = [];

        // 1. Comunidad / Consejo Comunal
        if ($request->filled('consejo_comunal_id')) {
            $ccId = $request->consejo_comunal_id;
            $query->whereHas('familia', function ($q) use ($ccId) {
                $q->where('consejo_comunal_id', $ccId);
            });
            $cc = ConsejoComunal::find($ccId);
            $filtros[] = 'Comunidad: ' . ($cc ? $cc->nombre : 'ID #' . $ccId);
        }

        // 2. Género
        if ($request->filled('genero') && in_array($request->genero, ['Masculino', 'Femenino'])) {
            $query->where('genero', $request->genero);
            $filtros[] = 'Género: ' . $request->genero;
        }

        // 3. Rango de Edad u Operador Etario
        if ($request->filled('edad_operador') && $request->filled('edad_limite')) {
            $limite = (int)$request->edad_limite;
            $fechaLimite = Carbon::now()->subYears($limite)->toDateString();

            if ($request->edad_operador === 'menor') {
                $query->whereNotNull('fecha_nacimiento')->where('fecha_nacimiento', '>', $fechaLimite);
                $filtros[] = 'Edad: Menores de ' . $limite . ' años';
            } elseif ($request->edad_operador === 'menor_o_igual') {
                $fechaComp = Carbon::now()->subYears($limite + 1)->addDay()->toDateString();
                $query->whereNotNull('fecha_nacimiento')->where('fecha_nacimiento', '>=', $fechaComp);
                $filtros[] = 'Edad: Hasta ' . $limite . ' años (inclusive)';
            } elseif ($request->edad_operador === 'mayor') {
                $query->whereNotNull('fecha_nacimiento')->where('fecha_nacimiento', '<', $fechaLimite);
                $filtros[] = 'Edad: Mayores de ' . $limite . ' años';
            } elseif ($request->edad_operador === 'mayor_o_igual') {
                $query->whereNotNull('fecha_nacimiento')->where('fecha_nacimiento', '<=', $fechaLimite);
                $filtros[] = 'Edad: De ' . $limite . ' años en adelante';
            }
        } else {
            if ($request->filled('edad_min')) {
                $min = (int)$request->edad_min;
                $fechaMin = Carbon::now()->subYears($min)->toDateString();
                $query->whereNotNull('fecha_nacimiento')->where('fecha_nacimiento', '<=', $fechaMin);
                $filtros[] = 'Edad mínima: ' . $min . ' años';
            }
            if ($request->filled('edad_max')) {
                $max = (int)$request->edad_max;
                $fechaMax = Carbon::now()->subYears($max + 1)->addDay()->toDateString();
                $query->whereNotNull('fecha_nacimiento')->where('fecha_nacimiento', '>=', $fechaMax);
                $filtros[] = 'Edad máxima: ' . $max . ' años';
            }
        }

        // 4. Pensionado / Jubilado
        if ($request->filled('pensionado_jubilado') && in_array($request->pensionado_jubilado, ['Sí', 'No'])) {
            $query->where('pensionado_jubilado', $request->pensionado_jubilado);
            $filtros[] = 'Pensionado/Jubilado: ' . $request->pensionado_jubilado;
        }

        // 5. Estudia
        if ($request->filled('estudia') && in_array($request->estudia, ['Sí', 'No'])) {
            $query->where('estudia', $request->estudia);
            $filtros[] = 'Estudia: ' . $request->estudia;
        }

        // 6. Nivel Académico
        if ($request->filled('nivel_academico') && $request->nivel_academico !== 'Todos') {
            $query->where('nivel_academico', $request->nivel_academico);
            $filtros[] = 'Nivel Académico: ' . $request->nivel_academico;
        }

        // 7. Profesión / Ocupación
        if ($request->filled('profesion')) {
            $prof = trim($request->profesion);
            $query->where('profesion', 'LIKE', '%' . $prof . '%');
            $filtros[] = 'Profesión: ' . $prof;
        }

        // 8. Situación Laboral
        if ($request->filled('situacion_laboral')) {
            $sit = trim($request->situacion_laboral);
            $query->where('situacion_laboral', 'LIKE', '%' . $sit . '%');
            $filtros[] = 'Situación Laboral: ' . $sit;
        }

        // 9. Condición de Salud
        if ($request->filled('con_enfermedad')) {
            if ($request->con_enfermedad === 'Sí') {
                $query->whereNotNull('tipo_enfermedad')
                    ->where('tipo_enfermedad', '!=', '')
                    ->where('tipo_enfermedad', '!=', 'Ninguna')
                    ->where('tipo_enfermedad', '!=', 'No aplica');
                $filtros[] = 'Con patología médica registrada';
            } elseif ($request->con_enfermedad === 'No') {
                $query->where(function ($q) {
                    $q->whereNull('tipo_enfermedad')
                      ->orWhere('tipo_enfermedad', '')
                      ->orWhere('tipo_enfermedad', 'Ninguna')
                      ->orWhere('tipo_enfermedad', 'No aplica');
                });
                $filtros[] = 'Sin patología médica registrada';
            }
        }
        if ($request->filled('tipo_enfermedad')) {
            $enf = trim($request->tipo_enfermedad);
            $query->where('tipo_enfermedad', 'LIKE', '%' . $enf . '%');
            $filtros[] = 'Enfermedad: ' . $enf;
        }

        // 10. Vivienda
        if ($request->filled('vivienda') && in_array($request->vivienda, ['Propia', 'Prestada', 'Alquilada'])) {
            $viv = $request->vivienda;
            $query->whereHas('familia', function ($q) use ($viv) {
                $q->where('vivienda', $viv);
            });
            $filtros[] = 'Vivienda: ' . $viv;
        }

        // 11. Misión Vivienda
        if ($request->filled('mision_vivienda') && in_array($request->mision_vivienda, ['Sí', 'No'])) {
            $mv = $request->mision_vivienda;
            $query->whereHas('familia', function ($q) use ($mv) {
                $q->where('mision_vivienda', $mv);
            });
            $filtros[] = 'Misión Vivienda: ' . $mv;
        }

        // 12. CLAP
        if ($request->filled('clap') && in_array($request->clap, ['Sí', 'No'])) {
            $clapVal = $request->clap;
            $query->whereHas('familia', function ($q) use ($clapVal) {
                $q->where('clap', $clapVal);
            });
            $filtros[] = 'Recibe CLAP: ' . $clapVal;
        }

        // 13. Bono Único Familiar
        if ($request->filled('bono_unico_familiar') && in_array($request->bono_unico_familiar, ['Sí', 'No'])) {
            $bonoVal = $request->bono_unico_familiar;
            $query->whereHas('familia', function ($q) use ($bonoVal) {
                $q->where('bono_unico_familiar', $bonoVal);
            });
            $filtros[] = 'Bono Único Familiar: ' . $bonoVal;
        }

        // 14. Parentesco / Rol familiar
        if ($request->filled('parentesco')) {
            $query->where('parentesco', $request->parentesco);
            $filtros[] = 'Parentesco: ' . $request->parentesco;
        }

        // 15. Liderazgo Comunitario
        if ($request->filled('es_jefe_comando') && $request->es_jefe_comando === 'Sí') {
            $query->whereHas('consejosComunales');
            $filtros[] = 'Es Jefe de Comando de Comunidad';
        }

        if ($request->filled('es_vocero') && $request->es_vocero === 'Sí') {
            $query->whereHas('vocerias');
            $filtros[] = 'Es Vocero Comunal';
        }

        if ($request->filled('categoria_voceria')) {
            $catVoc = trim($request->categoria_voceria);
            $query->whereHas('vocerias', function ($q) use ($catVoc) {
                $q->where('categoria_vocero', 'LIKE', '%' . $catVoc . '%');
            });
            $filtros[] = 'Vocería: ' . $catVoc;
        }

        // Si no hay filtros aplicados
        if (empty($filtros)) {
            $filtros[] = 'Todos los ciudadanos censados (Sin filtros específicos)';
        }

        // Clonar query para calcular métricas rápidas
        $total = (clone $query)->count();
        $masculinos = (clone $query)->where('genero', 'Masculino')->count();
        $femeninos = (clone $query)->where('genero', 'Femenino')->count();

        $fecha18 = Carbon::now()->subYears(18)->toDateString();
        $fecha60 = Carbon::now()->subYears(60)->toDateString();
        $menores18 = (clone $query)->whereNotNull('fecha_nacimiento')->where('fecha_nacimiento', '>', $fecha18)->count();
        $adultosMayores60 = (clone $query)->whereNotNull('fecha_nacimiento')->where('fecha_nacimiento', '<=', $fecha60)->count();

        $familiasIds = (clone $query)->whereNotNull('familia_id')->distinct()->pluck('familia_id');
        $totalFamilias = $familiasIds->count();

        $metricas = [
            'total' => $total,
            'masculinos' => $masculinos,
            'femeninos' => $femeninos,
            'menores18' => $menores18,
            'adultosMayores60' => $adultosMayores60,
            'totalFamilias' => $totalFamilias,
        ];

        // Obtener los resultados
        $resultados = (clone $query)
            ->orderBy('apellidos')
            ->orderBy('nombres')
            ->get();

        return [
            'resultados' => $resultados,
            'metricas' => $metricas,
            'filtros' => $filtros,
        ];
    }
}
