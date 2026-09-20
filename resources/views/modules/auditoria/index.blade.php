@extends('layouts.main')
@section('titulo', $titulo)
@section('auditoriaActive', 'active')
@section('paginaTitulo', $paginaTitulo)
@section('paginaSubtitulo', $paginaSubtitulo)

@section('contenido')
    <div class="conatiner-fluid content-inner mt-n5 py-0">

        {{-- Alertas de éxito y error --}}
        @if (session('success'))
            <div class="alert alert-solid alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
                <div class="d-flex align-items-center">
                    <i class="ri-checkbox-circle-fill fs-4 me-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-solid alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert">
                <div class="d-flex align-items-center">
                    <i class="ri-error-warning-fill fs-4 me-2"></i>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Tarjetas de Estadísticas Rápidas --}}
        <div class="row mb-4">
            <div class="col-12 col-md-6 col-lg-3 mb-3">
                <div class="card h-100 mb-0 shadow-sm border-0">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-3 p-3 bg-soft-primary text-primary me-3">
                            <i class="ri-shield-check-line fs-3"></i>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Total de Auditorías</span>
                            <h4 class="fw-bold mb-0">{{ number_format($stats['total_auditorias']) }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3 mb-3">
                <div class="card h-100 mb-0 shadow-sm border-0">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-3 p-3 bg-soft-success text-success me-3">
                            <i class="ri-calendar-check-line fs-3"></i>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Acciones Hoy</span>
                            <h4 class="fw-bold mb-0">{{ number_format($stats['auditorias_hoy']) }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3 mb-3">
                <div class="card h-100 mb-0 shadow-sm border-0">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-3 p-3 bg-soft-warning text-warning me-3">
                            <i class="ri-history-line fs-3"></i>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Registros > 1 Año</span>
                            <h4 class="fw-bold mb-0 {{ $stats['auditorias_antiguas'] > 0 ? 'text-warning' : 'text-muted' }}">
                                {{ number_format($stats['auditorias_antiguas']) }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3 mb-3">
                <div class="card h-100 mb-0 shadow-sm border-0">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-3 p-3 bg-soft-info text-info me-3">
                            <i class="ri-user-star-line fs-3"></i>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Más Activo (Mes)</span>
                            <h6 class="fw-bold mb-0 text-truncate" style="max-width: 160px;" title="{{ $stats['usuario_mas_activo_nombre'] }}">
                                {{ $stats['usuario_mas_activo_nombre'] }}
                            </h6>
                            <small class="text-muted">{{ $stats['usuario_mas_activo_total'] }} acciones</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Banner informativo si hay registros para depuración --}}
        @if ($stats['auditorias_antiguas'] > 0)
            <div class="alert alert-warning d-flex align-items-center justify-content-between p-3 rounded-3 shadow-sm mb-4 border-0">
                <div class="d-flex align-items-center">
                    <i class="ri-information-fill fs-3 text-warning me-3"></i>
                    <div>
                        <h6 class="fw-bold mb-1 text-dark">Registros antiguos pendientes de depuración</h6>
                        <span class="text-secondary small">
                            Hay <strong>{{ $stats['auditorias_antiguas'] }}</strong> registros de auditoría con más de 1 año de antigüedad (anteriores al {{ $stats['fecha_limite_antiguedad'] }}).
                        </span>
                    </div>
                </div>
                <button type="button" class="btn btn-warning btn-sm fw-bold px-3 text-white" data-bs-toggle="modal" data-bs-target="#modalDepurar">
                    <i class="ri-delete-bin-line me-1"></i> Depurar Ahora
                </button>
            </div>
        @endif

        {{-- Barra de Acciones y Filtros --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center">
                    <i class="ri-filter-3-fill fs-4 text-primary me-2"></i>
                    <h5 class="card-title mb-0 fw-bold">Filtros y Búsqueda de Auditoría</h5>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    {{-- Botón Depuración --}}
                    <button type="button" class="btn btn-outline-danger btn-sm d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modalDepurar">
                        <i class="ri-delete-bin-line me-1"></i> Depurar (+1 Año)
                    </button>

                    {{-- Botón Exportar PDF --}}
                    <a href="{{ route('auditoria.pdf', request()->query()) }}" class="btn btn-outline-primary btn-sm d-flex align-items-center" target="_blank">
                        <i class="ri-file-pdf-line me-1"></i> Exportar a PDF
                    </a>
                </div>
            </div>

            <div class="card-body">
                <form action="{{ route('auditoria.index') }}" method="GET" id="formFiltros">
                    <div class="row g-3">
                        {{-- Filtro por Usuario --}}
                        <div class="col-12 col-md-6 col-lg-3">
                            <label class="form-label small fw-bold text-secondary">Usuario</label>
                            <select name="user_id" class="form-select form-select-sm">
                                <option value="">Todos los usuarios</option>
                                @foreach ($usuarios as $u)
                                    <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                        {{ $u->nombre }} {{ $u->apellido }} ({{ $u->rol }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Filtro por Módulo --}}
                        <div class="col-12 col-md-6 col-lg-3">
                            <label class="form-label small fw-bold text-secondary">Módulo / Sección</label>
                            <select name="modulo" class="form-select form-select-sm">
                                <option value="">Todos los módulos</option>
                                @foreach ($modulos as $valor => $etiqueta)
                                    <option value="{{ $valor }}" {{ request('modulo') == $valor ? 'selected' : '' }}>
                                        {{ $etiqueta }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Filtro por Tipo de Acción --}}
                        <div class="col-12 col-md-6 col-lg-2">
                            <label class="form-label small fw-bold text-secondary">Tipo de Acción</label>
                            <select name="event" class="form-select form-select-sm">
                                <option value="">Todas las acciones</option>
                                <option value="created" {{ request('event') == 'created' ? 'selected' : '' }}>Creación</option>
                                <option value="updated" {{ request('event') == 'updated' ? 'selected' : '' }}>Modificación</option>
                                <option value="deleted" {{ request('event') == 'deleted' ? 'selected' : '' }}>Eliminación</option>
                                <option value="auth" {{ request('event') == 'auth' ? 'selected' : '' }}>Inicio / Cierre Sesión</option>
                                <option value="database" {{ request('event') == 'database' ? 'selected' : '' }}>Base de Datos</option>
                                <option value="purge" {{ request('event') == 'purge' ? 'selected' : '' }}>Depuración</option>
                            </select>
                        </div>

                        {{-- Fecha Desde --}}
                        <div class="col-12 col-md-3 col-lg-2">
                            <label class="form-label small fw-bold text-secondary">Fecha Desde</label>
                            <input type="date" name="fecha_desde" class="form-control form-control-sm" value="{{ request('fecha_desde') }}">
                        </div>

                        {{-- Fecha Hasta --}}
                        <div class="col-12 col-md-3 col-lg-2">
                            <label class="form-label small fw-bold text-secondary">Fecha Hasta</label>
                            <input type="date" name="fecha_hasta" class="form-control form-control-sm" value="{{ request('fecha_hasta') }}">
                        </div>

                        {{-- Búsqueda textual --}}
                        <div class="col-12 col-lg-8">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white border-end-0"><i class="ri-search-line text-muted"></i></span>
                                <input type="text" name="buscar" class="form-control border-start-0" placeholder="Buscar por cédula, ID de registro, dirección IP o contenido..." value="{{ request('buscar') }}">
                            </div>
                        </div>

                        {{-- Botones de Filtrado --}}
                        <div class="col-12 col-lg-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm flex-fill d-flex align-items-center justify-content-center">
                                <i class="ri-search-2-line me-1"></i> Aplicar Filtros
                            </button>
                            <a href="{{ route('auditoria.index') }}" class="btn btn-soft-secondary btn-sm d-flex align-items-center justify-content-center" title="Limpiar todos los filtros">
                                <i class="ri-refresh-line me-1"></i> Limpiar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabla de Registros de Auditoría --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="ri-table-line fs-4 text-primary me-2"></i>
                    <h5 class="card-title mb-0 fw-bold">Registro Cronológico de Auditoría</h5>
                </div>
                <span class="badge bg-soft-primary text-primary px-3 py-2">
                    Mostrando {{ $audits->firstItem() ?? 0 }} - {{ $audits->lastItem() ?? 0 }} de {{ $audits->total() }} registros
                </span>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="ps-4">Fecha y Hora</th>
                                <th>Usuario / Rol</th>
                                <th>Módulo</th>
                                <th class="text-center">Acción</th>
                                <th>Descripción de la Acción</th>
                                <th>Dirección IP</th>
                                <th class="text-center pe-4">Detalle</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($audits as $audit)
                                <tr>
                                    {{-- Fecha y Hora --}}
                                    <td class="ps-4 text-nowrap">
                                        <div class="fw-bold text-dark">
                                            {{ $audit->created_at ? $audit->created_at->format('d/m/Y h:i A') : 'N/A' }}
                                        </div>
                                        <small class="text-muted">
                                            <i class="ri-time-line me-1"></i>{{ $audit->created_at ? $audit->created_at->diffForHumans() : '' }}
                                        </small>
                                    </td>

                                    {{-- Usuario / Rol --}}
                                    <td>
                                        @if ($audit->user)
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-35 rounded-circle bg-soft-primary text-primary d-flex align-items-center justify-content-center me-2 fw-bold small">
                                                    {{ strtoupper(substr($audit->user->nombre, 0, 1)) }}{{ strtoupper(substr($audit->user->apellido, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <span class="fw-bold text-dark d-block">{{ $audit->user->nombre }} {{ $audit->user->apellido }}</span>
                                                    <span class="badge bg-soft-dark text-muted" style="font-size: 0.75rem;">
                                                        {{ $audit->user->rol ?? 'Usuario' }}
                                                    </span>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted fst-italic">Sistema Automático</span>
                                        @endif
                                    </td>

                                    {{-- Módulo --}}
                                    <td>
                                        <span class="badge bg-soft-info text-info border border-info border-opacity-25 px-2 py-1">
                                            <i class="ri-folder-line me-1"></i>{{ $audit->modulo_nombre }}
                                        </span>
                                        @if ($audit->auditable_id)
                                            <small class="text-muted d-block mt-1">ID #{{ $audit->auditable_id }}</small>
                                        @endif
                                    </td>

                                    {{-- Acción --}}
                                    <td class="text-center">
                                        <span class="badge bg-{{ $audit->badge_color }} text-white px-2 py-1 text-uppercase" style="font-size: 0.75rem;">
                                            <i class="{{ $audit->icono }} me-1"></i>{{ $audit->accion_nombre }}
                                        </span>
                                    </td>

                                    {{-- Descripción --}}
                                    <td>
                                        <span class="text-secondary small d-block text-wrap" style="max-width: 320px;">
                                            {{ $audit->descripcion_amigable }}
                                        </span>
                                    </td>

                                    {{-- Dirección IP --}}
                                    <td class="text-nowrap">
                                        <span class="badge bg-light text-secondary border">
                                            <i class="ri-global-line me-1"></i>{{ $audit->ip_address ?? '127.0.0.1' }}
                                        </span>
                                    </td>

                                    {{-- Botón Ver Detalle --}}
                                    <td class="text-center pe-4 text-nowrap">
                                        <button type="button" class="btn btn-soft-primary btn-sm btn-icon rounded-circle btn-ver-detalle" data-id="{{ $audit->id }}" title="Ver detalles y cambios del registro">
                                            <i class="ri-eye-line fs-5"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="ri-inbox-line fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                            <h6 class="fw-bold mb-1">No se encontraron registros de auditoría</h6>
                                            <p class="small mb-0">No hay movimientos que coincidan con los filtros seleccionados.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Paginación --}}
            @if ($audits->hasPages())
                <div class="card-footer bg-white border-top py-3 d-flex justify-content-center">
                    {{ $audits->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>

    {{-- ========================================================================= --}}
    {{-- MODAL INTERACTIVO DE DETALLE Y COMPARATIVA (DIFF: ANTES VS DESPUÉS)      --}}
    {{-- ========================================================================= --}}
    <div class="modal fade" id="modalDetalleAuditoria" tabindex="-1" aria-labelledby="modalDetalleLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light border-bottom py-3">
                    <div class="d-flex align-items-center">
                        <div id="modalIconoContenedor" class="avatar avatar-40 rounded-circle bg-soft-primary text-primary d-flex align-items-center justify-content-center me-3">
                            <i id="modalIcono" class="ri-history-line fs-4"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold mb-0" id="modalDetalleLabel">Detalle de Auditoría</h5>
                            <small class="text-muted" id="modalSubtitulo">Información detallada del movimiento</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="modal-body p-4" id="modalCuerpoDetalle">
                    {{-- Spinner de Carga --}}
                    <div id="modalSpinner" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="text-muted small mt-2">Cargando información del registro...</p>
                    </div>

                    {{-- Contenido Dinámico --}}
                    <div id="modalContenido" style="display: none;">
                        {{-- Resumen de Metadatos --}}
                        <div class="row g-3 p-3 bg-light rounded-3 mb-4">
                            <div class="col-12 col-md-6">
                                <span class="text-muted small d-block">Usuario Responsable:</span>
                                <strong class="text-dark" id="modalDetalleUsuario">—</strong>
                                <span class="badge bg-secondary ms-1" id="modalDetalleRol">—</span>
                            </div>
                            <div class="col-12 col-md-6">
                                <span class="text-muted small d-block">Fecha y Hora:</span>
                                <strong class="text-dark" id="modalDetalleFecha">—</strong>
                                <small class="text-muted" id="modalDetalleTiempo">—</small>
                            </div>
                            <div class="col-12 col-md-6">
                                <span class="text-muted small d-block">Módulo Afectado:</span>
                                <span class="badge bg-soft-info text-info border border-info border-opacity-25" id="modalDetalleModulo">—</span>
                                <span class="text-muted small ms-1" id="modalDetalleRegistroId"></span>
                            </div>
                            <div class="col-12 col-md-6">
                                <span class="text-muted small d-block">Tipo de Acción:</span>
                                <span class="badge text-white text-uppercase" id="modalDetalleAccion">—</span>
                            </div>
                            <div class="col-12 col-md-6">
                                <span class="text-muted small d-block">Dirección IP:</span>
                                <code class="text-dark" id="modalDetalleIp">—</code>
                            </div>
                            <div class="col-12 col-md-6">
                                <span class="text-muted small d-block">Dispositivo / Navegador:</span>
                                <span class="text-secondary small text-truncate d-block" id="modalDetalleUserAgent" title="">—</span>
                            </div>
                        </div>

                        {{-- Descripción de la acción --}}
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-2">
                                <i class="ri-chat-1-line text-primary me-1"></i> Resumen de la Acción
                            </h6>
                            <p class="p-3 bg-soft-primary text-primary rounded-3 mb-0 small" id="modalDetalleDescripcion">
                                —
                            </p>
                        </div>

                        {{-- SECCIÓN A: TABLA DE COMPARACIÓN DIFF (Para Modificaciones) --}}
                        <div id="seccionModificaciones" style="display: none;" class="mb-3">
                            <h6 class="fw-bold text-dark mb-3">
                                <i class="ri-git-commit-line text-primary me-1"></i> Campos Afectados (Antes vs. Después)
                            </h6>
                            <div class="table-responsive border rounded-3">
                                <table class="table table-bordered mb-0 align-middle">
                                    <thead class="bg-light text-muted small">
                                        <tr>
                                            <th style="width: 30%;">Campo Modificado</th>
                                            <th style="width: 35%; background-color: #fff5f5;" class="text-danger">
                                                <i class="ri-close-circle-line me-1"></i> Valor Anterior
                                            </th>
                                            <th style="width: 35%; background-color: #f0fdf4;" class="text-success">
                                                <i class="ri-checkbox-circle-line me-1"></i> Valor Nuevo
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="tablaComparativaBody" class="small">
                                        {{-- Inserción dinámica --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- SECCIÓN B: VALORES NUEVOS (Para Creaciones) --}}
                        <div id="seccionCreacion" style="display: none;" class="mb-3">
                            <h6 class="fw-bold text-dark mb-3">
                                <i class="ri-add-box-line text-success me-1"></i> Datos Iniciales Registrados
                            </h6>
                            <div class="table-responsive border rounded-3">
                                <table class="table table-sm table-striped mb-0 align-middle">
                                    <thead class="bg-light text-muted small">
                                        <tr>
                                            <th style="width: 40%;">Campo</th>
                                            <th style="width: 60%;">Valor Registrado</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tablaCreacionBody" class="small">
                                        {{-- Inserción dinámica --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- SECCIÓN C: VALORES RESPALDADOS (Para Eliminaciones) --}}
                        <div id="seccionEliminacion" style="display: none;" class="mb-3">
                            <div class="alert alert-danger p-2 small mb-3">
                                <i class="ri-alert-line me-1"></i> Este registro fue eliminado del sistema. A continuación se muestran los datos que contenía antes de su eliminación:
                            </div>
                            <div class="table-responsive border rounded-3">
                                <table class="table table-sm table-striped mb-0 align-middle">
                                    <thead class="bg-light text-muted small">
                                        <tr>
                                            <th style="width: 40%;">Campo</th>
                                            <th style="width: 60%;">Valor al momento de eliminar</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tablaEliminacionBody" class="small">
                                        {{-- Inserción dinámica --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- SECCIÓN D: EVENTOS DE SISTEMA / SIN DATOS --}}
                        <div id="seccionEventoSimple" style="display: none;" class="mb-3">
                            <div class="p-3 bg-light rounded-3 text-center text-muted small">
                                <i class="ri-information-line fs-4 d-block mb-1 text-secondary"></i>
                                Este es un evento de sistema o seguridad sin atributos Eloquent modificados directamente.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ========================================================================= --}}
    {{-- MODAL DE CONFIRMACIÓN DE DEPURACIÓN (+1 AÑO DE ANTIGÜEDAD)                --}}
    {{-- ========================================================================= --}}
    <div class="modal fade" id="modalDepurar" tabindex="-1" aria-labelledby="modalDepurarLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form action="{{ route('auditoria.purge') }}" method="POST" id="formDepurarAuditoria">
                    @csrf
                    <div class="modal-header bg-danger text-white py-3">
                        <div class="d-flex align-items-center">
                            <i class="ri-error-warning-fill fs-4 me-2"></i>
                            <h5 class="modal-title fw-bold text-white mb-0" id="modalDepurarLabel">Depuración de Registros Antiguos</h5>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>

                    <div class="modal-body p-4">
                        <p class="text-secondary mb-3">
                            Esta operación eliminará de forma <strong>permanente</strong> del historial de auditoría los registros que superen el límite de antigüedad seleccionado, optimizando el espacio y rendimiento de la base de datos.
                        </p>

                        <div class="p-3 bg-soft-warning rounded-3 mb-4 border border-warning border-opacity-25">
                            <div class="d-flex align-items-center mb-2">
                                <i class="ri-alert-line fs-4 text-warning me-2"></i>
                                <h6 class="fw-bold text-dark mb-0">Atención Jefe de Comuna</h6>
                            </div>
                            <ul class="small text-secondary mb-0 ps-3">
                                <li>Registros candidatos a depurar (+1 año): <strong>{{ $stats['auditorias_antiguas'] }} registros</strong>.</li>
                                <li>Fecha de corte actual: <strong>Anteriores al {{ $stats['fecha_limite_antiguedad'] }}</strong>.</li>
                                <li>Esta acción dejará un registro automático de auditoría con la fecha, cantidad de eliminaciones y el usuario responsable.</li>
                            </ul>
                        </div>

                        {{-- Selector de Años --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-dark">Antigüedad a depurar:</label>
                            <select name="anios" class="form-select" id="selectAniosDepurar">
                                <option value="1" selected>Más de 1 año de antigüedad (Recomendado)</option>
                                <option value="2">Más de 2 años de antigüedad</option>
                                <option value="3">Más de 3 años de antigüedad</option>
                            </select>
                        </div>

                        {{-- Confirmación obligatoria mediante Checkbox --}}
                        <div class="form-check p-3 bg-light rounded-3 border">
                            <input class="form-check-input ms-0 me-2" type="checkbox" id="checkConfirmarDepuracion" required>
                            <label class="form-check-label small text-dark fw-bold" for="checkConfirmarDepuracion">
                                Confirmo que deseo depurar definitivamente estos registros de auditoría.
                            </label>
                        </div>
                    </div>

                    <div class="modal-footer bg-light border-top py-2">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger btn-sm fw-bold px-3" id="btnEjecutarDepuracion" disabled>
                            <i class="ri-delete-bin-line me-1"></i> Confirmar y Depurar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ========================================================================= --}}
    {{-- SCRIPTS PARA MODAL INTERACTIVO DE DETALLE                                  --}}
    {{-- ========================================================================= --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Modal de detalle y elementos dinámicos
            const modalDetalle = new bootstrap.Modal(document.getElementById('modalDetalleAuditoria'));
            const modalSpinner = document.getElementById('modalSpinner');
            const modalContenido = document.getElementById('modalContenido');

            // Habilitar botón de depuración solo cuando el checkbox esté marcado
            const checkConfirmar = document.getElementById('checkConfirmarDepuracion');
            const btnEjecutarDepuracion = document.getElementById('btnEjecutarDepuracion');
            if (checkConfirmar && btnEjecutarDepuracion) {
                checkConfirmar.addEventListener('change', function () {
                    btnEjecutarDepuracion.disabled = !this.checked;
                });
            }

            // Manejo de botones "Ver Detalle"
            document.querySelectorAll('.btn-ver-detalle').forEach(btn => {
                btn.addEventListener('click', function () {
                    const auditId = this.getAttribute('data-id');
                    abrirDetalleAuditoria(auditId);
                });
            });

            function abrirDetalleAuditoria(id) {
                // Mostrar spinner y abrir modal
                modalSpinner.style.display = 'block';
                modalContenido.style.display = 'none';
                modalDetalle.show();

                // Petición AJAX al endpoint show
                fetch(`{{ url('/auditoria/detalle') }}/${id}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Error al consultar el registro');
                        return response.json();
                    })
                    .then(res => {
                        if (!res.success) throw new Error(res.message || 'Error en respuesta');
                        const data = res.data;

                        // Metadatos
                        document.getElementById('modalDetalleUsuario').textContent = `${data.usuario_nombre} (C.I. ${data.usuario_cedula})`;
                        document.getElementById('modalDetalleRol').textContent = data.usuario_rol;
                        document.getElementById('modalDetalleFecha').textContent = data.fecha;
                        document.getElementById('modalDetalleTiempo').textContent = data.tiempo_relativo ? `(${data.tiempo_relativo})` : '';
                        document.getElementById('modalDetalleModulo').textContent = data.modulo;
                        document.getElementById('modalDetalleRegistroId').textContent = data.registro_id ? `[Registro #${data.registro_id}]` : '';
                        
                        const badgeAccion = document.getElementById('modalDetalleAccion');
                        badgeAccion.textContent = data.accion;
                        badgeAccion.className = `badge bg-${data.badge_color} text-white text-uppercase`;

                        document.getElementById('modalDetalleIp').textContent = data.ip_address;
                        document.getElementById('modalDetalleUserAgent').textContent = data.user_agent;
                        document.getElementById('modalDetalleUserAgent').title = data.user_agent;
                        document.getElementById('modalDetalleDescripcion').textContent = data.descripcion;

                        // Icono del modal
                        document.getElementById('modalIcono').className = `${data.icono} fs-4`;

                        // Resetear visibilidad de secciones
                        const seccionMod = document.getElementById('seccionModificaciones');
                        const seccionCreacion = document.getElementById('seccionCreacion');
                        const seccionElim = document.getElementById('seccionEliminacion');
                        const seccionEvento = document.getElementById('seccionEventoSimple');

                        seccionMod.style.display = 'none';
                        seccionCreacion.style.display = 'none';
                        seccionElim.style.display = 'none';
                        seccionEvento.style.display = 'none';

                        // Caso 1: Modificación (updated)
                        if (data.evento === 'updated' && data.cambios && data.cambios.length > 0) {
                            seccionMod.style.display = 'block';
                            const tbody = document.getElementById('tablaComparativaBody');
                            tbody.innerHTML = '';

                            data.cambios.forEach(item => {
                                const row = document.createElement('tr');
                                row.innerHTML = `
                                    <td class="fw-bold text-dark">${item.campo_nombre}</td>
                                    <td class="text-danger bg-soft-danger font-monospace text-break">${escapeHtml(item.valor_anterior)}</td>
                                    <td class="text-success bg-soft-success font-monospace text-break">${escapeHtml(item.valor_nuevo)}</td>
                                `;
                                tbody.appendChild(row);
                            });
                        }
                        // Caso 2: Creación (created)
                        else if (data.evento === 'created' && data.valores_nuevos && data.valores_nuevos.length > 0) {
                            seccionCreacion.style.display = 'block';
                            const tbody = document.getElementById('tablaCreacionBody');
                            tbody.innerHTML = '';

                            data.valores_nuevos.forEach(item => {
                                const row = document.createElement('tr');
                                row.innerHTML = `
                                    <td class="fw-bold text-dark">${item.campo_nombre}</td>
                                    <td class="font-monospace text-break">${escapeHtml(item.valor)}</td>
                                `;
                                tbody.appendChild(row);
                            });
                        }
                        // Caso 3: Eliminación (deleted)
                        else if (data.evento === 'deleted' && data.valores_anteriores && data.valores_anteriores.length > 0) {
                            seccionElim.style.display = 'block';
                            const tbody = document.getElementById('tablaEliminacionBody');
                            tbody.innerHTML = '';

                            data.valores_anteriores.forEach(item => {
                                const row = document.createElement('tr');
                                row.innerHTML = `
                                    <td class="fw-bold text-dark">${item.campo_nombre}</td>
                                    <td class="font-monospace text-break text-danger">${escapeHtml(item.valor)}</td>
                                `;
                                tbody.appendChild(row);
                            });
                        }
                        // Caso 4: Evento simple / sin atributos Eloquent
                        else {
                            seccionEvento.style.display = 'block';
                        }

                        // Ocultar spinner y mostrar contenido
                        modalSpinner.style.display = 'none';
                        modalContenido.style.display = 'block';
                    })
                    .catch(err => {
                        modalSpinner.innerHTML = `
                            <div class="text-danger py-4">
                                <i class="ri-error-warning-line fs-2 d-block mb-2"></i>
                                <span>${err.message}</span>
                            </div>
                        `;
                    });
            }

            // Sanitizador para evitar XSS en inyección dinámica
            function escapeHtml(text) {
                if (!text) return '—';
                const map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };
                return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
            }
        });
    </script>
@endsection
