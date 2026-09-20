<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\User;
use App\Services\AuditService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditoriaController extends Controller
{
    protected AuditService $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    /**
     * Muestra la lista de auditorías con métricas y filtros.
     */
    public function index(Request $request)
    {
        $titulo = 'Auditoría del Sistema - SGIC';
        $paginaTitulo = 'Módulo de Auditoría';
        $paginaSubtitulo = 'Supervisión y control de cambios, acciones de usuarios y seguridad del sistema.';

        $stats = $this->auditService->getEstadisticas();
        $audits = $this->auditService->getAuditoriasPaginadas($request, 15);
        $usuarios = User::orderBy('nombre')->get();

        // Lista de módulos para el filtro
        $modulos = [
            'App\Models\Persona' => 'Personas / Censo',
            'App\Models\Familia' => 'Núcleos Familiares',
            'App\Models\Expediente' => 'Expedientes de Paz',
            'App\Models\Citaciones' => 'Citaciones',
            'App\Models\Visita' => 'Visitas y Mediaciones',
            'App\Models\Vocero' => 'Voceros Comunales',
            'App\Models\ConsejoComunal' => 'Consejos Comunales',
            'App\Models\CategoriaVoceria' => 'Categorías de Vocería',
            'App\Models\Proyecto' => 'Proyectos Comunitarios',
            'App\Models\JornadaAbuelo' => 'Círculo de Abuelos',
            'App\Models\User' => 'Gestión de Usuarios',
            'App\Models\Actas' => 'Actas y Cartas',
            'Autenticación y Seguridad' => 'Autenticación y Seguridad',
            'Base de Datos' => 'Base de Datos',
            'Mantenimiento de Auditoría' => 'Mantenimiento de Auditoría',
        ];

        return view('modules.auditoria.index', compact(
            'titulo',
            'paginaTitulo',
            'paginaSubtitulo',
            'stats',
            'audits',
            'usuarios',
            'modulos'
        ));
    }

    /**
     * Retorna los detalles de una auditoría en JSON para el modal interactivo de diff.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $audit = Audit::with('user')->findOrFail($id);

            $usuarioNombre = $audit->user ? "{$audit->user->nombre} {$audit->user->apellido}" : 'Usuario del Sistema';
            $usuarioCedula = $audit->user ? $audit->user->cedula_usuario : 'N/A';
            $usuarioRol = $audit->user ? $audit->user->rol : 'Sistema';

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $audit->id,
                    'usuario_nombre' => $usuarioNombre,
                    'usuario_cedula' => $usuarioCedula,
                    'usuario_rol' => $usuarioRol,
                    'modulo' => $audit->modulo_nombre,
                    'accion' => $audit->accion_nombre,
                    'badge_color' => $audit->badge_color,
                    'icono' => $audit->icono,
                    'evento' => $audit->event,
                    'registro_id' => $audit->auditable_id,
                    'fecha' => $audit->created_at ? $audit->created_at->format('d/m/Y h:i:s A') : 'N/A',
                    'tiempo_relativo' => $audit->created_at ? $audit->created_at->diffForHumans() : '',
                    'ip_address' => $audit->ip_address ?? 'No registrada',
                    'user_agent' => $audit->user_agent ?? 'No especificado',
                    'url' => $audit->url ?? 'N/A',
                    'descripcion' => $audit->descripcion_amigable,
                    'cambios' => $audit->getCambiosDetallados(),
                    'valores_nuevos' => $audit->getAtributosFormateados('new'),
                    'valores_anteriores' => $audit->getAtributosFormateados('old'),
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró el registro de auditoría solicitado: ' . $e->getMessage()
            ], 404);
        }
    }

    /**
     * Depura los registros de auditoría antiguos (+1 año).
     */
    public function purge(Request $request)
    {
        $request->validate([
            'anios' => 'nullable|integer|min:1|max:10',
        ]);

        $anios = (int) ($request->input('anios', 1));

        try {
            $resultado = $this->auditService->purgeAuditoriasAntiguas($anios, Auth::user());

            return redirect()->route('auditoria.index')->with(
                'success',
                "Se han depurado satisfactoriamente {$resultado['eliminados']} registros de auditoría anteriores al {$resultado['fecha_limite']}."
            );
        } catch (Exception $e) {
            return redirect()->route('auditoria.index')->with(
                'error',
                'Ocurrió un error al intentar depurar los registros: ' . $e->getMessage()
            );
        }
    }

    /**
     * Exporta los registros filtrados a formato PDF.
     */
    public function exportPdf(Request $request)
    {
        try {
            $query = $this->auditService->getAuditoriasQuery($request);
            // Límite de 300 registros para garantizar rapidez y legibilidad del PDF
            $audits = $query->take(300)->get();

            $fechaImpresion = Carbon::now()->format('d/m/Y h:i A');
            $generadoPor = Auth::user() ? Auth::user()->nombre . ' ' . Auth::user()->apellido : 'Jefe de Comuna';

            $pdf = Pdf::loadView('modules.auditoria.pdf', compact('audits', 'fechaImpresion', 'generadoPor', 'request'))
                ->setPaper('a4', 'landscape');

            $nombreArchivo = 'Auditoria_SGIC_' . Carbon::now()->format('Ymd_His') . '.pdf';

            return $pdf->download($nombreArchivo);
        } catch (Exception $e) {
            return redirect()->route('auditoria.index')->with(
                'error',
                'Error al generar el reporte PDF: ' . $e->getMessage()
            );
        }
    }
}
