<?php

namespace App\Services;

use App\Models\Audit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuditService
{
    /**
     * Registra un evento de auditoría personalizado (ej. Login, Logout, Base de Datos, Depuración).
     */
    public function logCustom(
        string $event,
        string $modulo,
        string $descripcion,
        array $datos = [],
        ?User $user = null
    ): Audit {
        $usuario = $user ?? Auth::user();

        return Audit::create([
            'user_type' => $usuario ? get_class($usuario) : null,
            'user_id' => $usuario ? $usuario->id : null,
            'event' => $event,
            'auditable_type' => null,
            'auditable_id' => null,
            'old_values' => null,
            'new_values' => array_merge(['descripcion' => $descripcion], $datos),
            'url' => request() ? request()->fullUrl() : 'consola/sistema',
            'ip_address' => request() ? request()->ip() : '127.0.0.1',
            'user_agent' => request() ? substr(request()->userAgent() ?? 'Sistema', 0, 1023) : 'Sistema',
            'tags' => $modulo,
        ]);
    }

    /**
     * Obtiene las estadísticas generales del módulo de auditoría.
     */
    public function getEstadisticas(): array
    {
        $ahora = Carbon::now();
        $unAnoAtras = $ahora->copy()->subYear();

        $totalAuditorias = Audit::count();
        $auditoriasHoy = Audit::whereDate('created_at', Carbon::today())->count();
        $auditoriasAntiguas = Audit::where('created_at', '<', $unAnoAtras)->count();

        // Usuario más activo del mes
        $usuarioMasActivoRegistro = Audit::select('user_id', DB::raw('count(*) as total'))
            ->whereNotNull('user_id')
            ->where('created_at', '>=', $ahora->copy()->startOfMonth())
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->first();

        $usuarioMasActivoNombre = 'N/A';
        $usuarioMasActivoTotal = 0;
        if ($usuarioMasActivoRegistro && $usuarioMasActivoRegistro->user_id) {
            $u = User::find($usuarioMasActivoRegistro->user_id);
            if ($u) {
                $usuarioMasActivoNombre = "{$u->nombre} {$u->apellido}";
                $usuarioMasActivoTotal = $usuarioMasActivoRegistro->total;
            }
        }

        return [
            'total_auditorias' => $totalAuditorias,
            'auditorias_hoy' => $auditoriasHoy,
            'auditorias_antiguas' => $auditoriasAntiguas,
            'fecha_limite_antiguedad' => $unAnoAtras->format('d/m/Y'),
            'usuario_mas_activo_nombre' => $usuarioMasActivoNombre,
            'usuario_mas_activo_total' => $usuarioMasActivoTotal,
        ];
    }

    /**
     * Construye la consulta filtrada de auditorías.
     */
    public function getAuditoriasQuery(Request $request): Builder
    {
        $query = Audit::with('user')->orderBy('created_at', 'desc');

        // Filtro por usuario
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filtro por evento/acción
        if ($request->filled('event')) {
            $evento = $request->event;
            if ($evento === 'auth') {
                $query->whereIn('event', ['login', 'logout']);
            } elseif ($evento === 'database') {
                $query->whereIn('event', ['backup_export', 'backup_import', 'backup_restore']);
            } else {
                $query->where('event', $evento);
            }
        }

        // Filtro por módulo / modelo
        if ($request->filled('modulo')) {
            $modulo = $request->modulo;
            if (str_starts_with($modulo, 'App\\')) {
                $query->where('auditable_type', $modulo);
            } else {
                $query->where('tags', $modulo);
            }
        }

        // Filtro por rango de fechas
        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        // Búsqueda general (por IP, URL o texto en new_values)
        if ($request->filled('buscar')) {
            $buscar = trim($request->buscar);
            $query->where(function ($q) use ($buscar) {
                $q->where('ip_address', 'like', "%{$buscar}%")
                    ->orWhere('auditable_id', 'like', "%{$buscar}%")
                    ->orWhere('new_values', 'like', "%{$buscar}%")
                    ->orWhere('old_values', 'like', "%{$buscar}%")
                    ->orWhere('tags', 'like', "%{$buscar}%")
                    ->orWhereHasMorph('user', [User::class], function ($userQuery) use ($buscar) {
                        $userQuery->where('nombre', 'like', "%{$buscar}%")
                            ->orWhere('apellido', 'like', "%{$buscar}%")
                            ->orWhere('cedula_usuario', 'like', "%{$buscar}%");
                    });
            });
        }

        return $query;
    }

    /**
     * Obtiene las auditorías paginadas con los filtros aplicados.
     */
    public function getAuditoriasPaginadas(Request $request, int $porPagina = 20): LengthAwarePaginator
    {
        return $this->getAuditoriasQuery($request)->paginate($porPagina)->withQueryString();
    }

    /**
     * Depura registros de auditoría anteriores a cierta cantidad de años.
     *
     * @return array ['eliminados' => int, 'fecha_limite' => string]
     */
    public function purgeAuditoriasAntiguas(int $anios = 1, ?User $ejecutadoPor = null): array
    {
        $fechaLimite = Carbon::now()->subYears($anios);
        
        // Contar registros que se van a eliminar
        $totalEliminar = Audit::where('created_at', '<', $fechaLimite)->count();

        if ($totalEliminar > 0) {
            Audit::where('created_at', '<', $fechaLimite)->delete();
        }

        // Registrar la acción de depuración en la propia auditoría
        $this->logCustom(
            'purge',
            'Mantenimiento de Auditoría',
            "Depuración de {$totalEliminar} registros de auditoría anteriores al {$fechaLimite->format('d/m/Y H:i')}.",
            [
                'anios_antiguedad' => $anios,
                'registros_eliminados' => $totalEliminar,
                'fecha_corte' => $fechaLimite->toDateTimeString(),
            ],
            $ejecutadoPor
        );

        return [
            'eliminados' => $totalEliminar,
            'fecha_limite' => $fechaLimite->format('d/m/Y'),
        ];
    }
}
