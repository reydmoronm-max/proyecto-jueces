@extends('layouts.main')

@section('titulo', $titulo)
@section('paginaTitulo', $paginaTitulo)
@section('paginaSubtitulo', $paginaSubtitulo)
@section('consultaActive', $consultaActive)

@section('contenido')
    <div class="container-fluid content-inner mt-n5 py-0">
        <div class="row">
            <!-- Panel Izquierdo: Formulario de búsqueda e Información del Ciudadano -->
            <div class="col-lg-4 col-md-12 mb-4">
                <!-- Formulario de búsqueda -->
                <div class="card mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Buscador de Ciudadano</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('consulta.index') }}" method="GET" class="needs-validation" novalidate>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="cedula_tipo" class="form-label font-weight-bold">Tipo</label>
                                    <select class="form-select border-primary" id="cedula_tipo" name="cedula_tipo" required>
                                        <option value="V"
                                            {{ request('cedula_tipo', $cedulaTipo) == 'V' ? 'selected' : '' }}>V</option>
                                        <option value="E"
                                            {{ request('cedula_tipo', $cedulaTipo) == 'E' ? 'selected' : '' }}>E</option>
                                    </select>
                                </div>
                                <div class="col-md-8 mb-3">
                                    <label for="cedula" class="form-label font-weight-bold">Cédula de Identidad</label>
                                    <input type="number" class="form-control border-primary" id="cedula" name="cedula"
                                        value="{{ request('cedula', $cedula) }}" placeholder="Ingrese un número de cédula"
                                        required min="100000" max="999999999"
                                        oninput="if(this.value.length>8)this.value=this.value.slice(0,8)">
                                    <div class="invalid-feedback">
                                        Por favor ingrese un número de cédula válido.
                                    </div>
                                </div>
                            </div>
                            <div class="d-grid gap-2 mt-2">
                                <button type="submit"
                                    class="btn btn-primary d-flex align-items-center justify-content-center">
                                    <i class="ri-search-2-line me-2"></i> Buscar Historial
                                </button>
                                @if ($busquedaRealizada)
                                    <a href="{{ route('consulta.index') }}"
                                        class="btn btn-outline-secondary d-flex align-items-center justify-content-center">
                                        <i class="ri-refresh-line me-2"></i> Limpiar Filtros
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Información del Ciudadano Encontrado -->
                @if ($persona)
                    <div class="card" data-aos="fade-up" data-aos-delay="300">
                        <div class="card-header bg-soft-primary py-3">
                            <h5 class="card-title mb-0 d-flex align-items-center text-primary">
                                <i class="ri-user-search-line me-2"></i> Datos Personales
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <div class="avatar avatar-80 bg-primary-subtle rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                                    style="width: 70px; height: 70px; background-color: rgba(7, 154, 162, 0.1);">
                                    <i class="ri-user-line text-primary" style="font-size: 2.5rem;"></i>
                                </div>
                                <h5 class="mb-1 font-weight-bold">{{ $persona->nombres }} {{ $persona->apellidos }}</h5>
                                <span class="badge bg-primary text-white px-3 py-2 mt-1">
                                    Cédula: {{ $persona->cedula_tipo }}-{{ number_format($persona->cedula, 0, ',', '.') }}
                                </span>
                            </div>

                            <ul class="list-group list-group-flush">
                                <li class="list-group-item px-0 py-3 d-flex align-items-start bg-transparent">
                                    <div class="me-3 bg-light rounded p-2 text-primary">
                                        <i class="ri-phone-fill fs-5"></i>
                                    </div>
                                    <div>
                                        <span class="text-muted d-block small">Teléfono</span>
                                        <span
                                            class="font-weight-bold text-dark">{{ $persona->telefono ?? 'No registrado' }}</span>
                                    </div>
                                </li>
                                <li class="list-group-item px-0 py-3 d-flex align-items-start bg-transparent">
                                    <div class="me-3 bg-light rounded p-2 text-primary">
                                        <i class="ri-map-pin-2-fill fs-5"></i>
                                    </div>
                                    <div>
                                        <span class="text-muted d-block small">Dirección</span>
                                        <span
                                            class="font-weight-bold text-dark">{{ $persona->direccion ?? 'No registrada' }}</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Panel Derecho: Expedientes y contenido relacionado -->
            <div class="col-lg-8 col-md-12">
                <!-- Estado inicial (Antes de realizar búsqueda) -->
                @if (!$busquedaRealizada)
                    <div class="card text-center p-5" data-aos="fade-up" data-aos-delay="200">
                        <div class="card-body">
                            <div class="avatar bg-soft-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                                style="width: 90px; height: 90px; background-color: rgba(7, 154, 162, 0.1);">
                                <i class="ri-file-search-line text-primary" style="font-size: 3.5rem;"></i>
                            </div>
                            <h3 class="mb-2">Buscador de expediente</h3>
                            <p class="text-muted mx-auto" style="max-width: 500px;">
                                Ingrese la cédula de un ciudadano en el panel izquierdo para consultar todos sus
                                expedientes, actas redactadas y citaciones activas.
                            </p>
                        </div>
                    </div>
                @endif

                <!-- Búsqueda realizada pero sin ciudadano en base de datos -->
                @if ($busquedaRealizada && !$persona)
                    <div class="card text-center p-5" data-aos="fade-up" data-aos-delay="200">
                        <div class="card-body">
                            <div class="avatar bg-soft-danger rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                                style="width: 90px; height: 90px; background-color: rgba(235, 104, 122, 0.1);">
                                <i class="ri-user-unfollow-line text-danger" style="font-size: 3.5rem;"></i>
                            </div>
                            <h3 class="mb-2 text-danger">Ciudadano no registrado</h3>
                            <p class="text-muted mx-auto" style="max-width: 500px;">
                                No se encontraron registros de visitas ni expedientes relacionados con la cédula
                                <strong>{{ $cedulaTipo }}-{{ $cedula }}</strong>.
                            </p>
                            <div class="mt-4">
                                <a href="{{ route('consulta.index') }}" class="btn btn-outline-primary">Intentar con otra
                                    cédula</a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Ciudadano encontrado pero sin expedientes asociados -->
                @if ($persona && $expedientes->isEmpty())
                    <div class="card text-center p-5" data-aos="fade-up" data-aos-delay="200">
                        <div class="card-body">
                            <div class="avatar bg-soft-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                                style="width: 90px; height: 90px; background-color: rgba(254, 141, 0, 0.1);">
                                <i class="ri-folder-warning-line text-warning" style="font-size: 3.5rem;"></i>
                            </div>
                            <h3 class="mb-2">Sin expedientes activos</h3>
                            <p class="text-muted mx-auto" style="max-width: 500px;">
                                La persona <strong>{{ $persona->nombres }} {{ $persona->apellidos }}</strong> está
                                registrada en la base de datos de visitas, pero no posee expedientes activos ni cerrados en
                                el sistema.
                            </p>
                        </div>
                    </div>
                @endif

                <!-- Ciudadano encontrado con expedientes -->
                @if ($persona && !$expedientes->isEmpty())
                    {{-- <h4 class="mb-4 d-flex align-items-center" data-aos="fade-up" data-aos-delay="100">
                    <i class="ri-folder-shared-line text-primary me-2"></i> Expedientes Asociados ({{ $expedientes->count() }})
                </h4> --}}

                    @foreach ($expedientes as $index => $expediente)
                        <div class="card mb-4 border-start border-4 @if ($expediente->estatus == 'Abierto') border-success @elseif($expediente->estatus == 'En proceso') border-warning @else border-secondary @endif"
                            data-aos="fade-up" data-aos-delay="{{ 200 + $index * 100 }}">
                            <div class="card-header d-flex flex-wrap align-items-center justify-content-between py-3">
                                <div>
                                    <h5 class="mb-0 font-weight-bold d-inline-block">Expediente #{{ $expediente->id }}
                                    </h5>
                                    <span class="ms-2 text-muted small d-inline-block">
                                        <i class="ri-calendar-event-line me-1"></i>Apertura:
                                        {{ $expediente->created_at->format('d/m/Y h:i A') }}
                                    </span>
                                </div>
                                <span
                                    class="badge @if ($expediente->estatus == 'Abierto') bg-success @elseif($expediente->estatus == 'En proceso') bg-warning text-dark @else bg-secondary @endif px-3 py-2 rounded-pill mt-2 mt-sm-0">
                                    {{ $expediente->estatus }}
                                </span>
                            </div>
                            <div class="card-body">
                                <!-- Motivo de la denuncia -->
                                <div class="mb-4 p-3 rounded border-start border-primary border-3"
                                    style="background-color: #f8f9faf1;">
                                    <h6 class="font-weight-bold text-primary mb-1"><i
                                            class="ri-information-fill me-1"></i> Motivo de la denuncia:</h6>
                                    <p class="mb-0 text-dark" style="font-size: 0.95rem;">
                                        {{ $expediente->motivo_denuncia }}</p>
                                </div>

                                <!-- Tabs de Detalles del Expediente -->
                                <ul class="nav nav-pills mb-3 bg-light p-1 rounded" id="pills-tab-{{ $expediente->id }}"
                                    role="tablist" style="background-color: #f8f9faf1;">
                                    <li class="nav-item flex-fill text-center" role="presentation">
                                        <button class="nav-link active w-100"
                                            id="pills-involucrados-tab-{{ $expediente->id }}" data-bs-toggle="pill"
                                            data-bs-target="#pills-involucrados-{{ $expediente->id }}" type="button"
                                            role="tab" aria-controls="pills-involucrados-{{ $expediente->id }}"
                                            aria-selected="true">
                                            <i class="ri-group-line me-1"></i> Involucrados
                                            ({{ $expediente->personas->count() }})
                                        </button>
                                    </li>
                                    <li class="nav-item flex-fill text-center" role="presentation">
                                        <button class="nav-link w-100" id="pills-citaciones-tab-{{ $expediente->id }}"
                                            data-bs-toggle="pill"
                                            data-bs-target="#pills-citaciones-{{ $expediente->id }}" type="button"
                                            role="tab" aria-controls="pills-citaciones-{{ $expediente->id }}"
                                            aria-selected="false">
                                            <i class="ri-time-line me-1"></i> Citaciones
                                            ({{ $expediente->citaciones->count() }})
                                        </button>
                                    </li>
                                    <li class="nav-item flex-fill text-center" role="presentation">
                                        <button class="nav-link w-100" id="pills-actas-tab-{{ $expediente->id }}"
                                            data-bs-toggle="pill" data-bs-target="#pills-actas-{{ $expediente->id }}"
                                            type="button" role="tab"
                                            aria-controls="pills-actas-{{ $expediente->id }}" aria-selected="false">
                                            <i class="ri-file-list-3-line me-1"></i> Actas
                                            ({{ $expediente->actas->count() }})
                                        </button>
                                    </li>
                                </ul>

                                <div class="tab-content" id="pills-tabContent-{{ $expediente->id }}">
                                    <!-- TAB: Involucrados -->
                                    <div class="tab-pane fade show active" id="pills-involucrados-{{ $expediente->id }}"
                                        role="tabpanel" aria-labelledby="pills-involucrados-tab-{{ $expediente->id }}">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover align-middle mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Nombre Completo</th>
                                                        <th>Cédula</th>
                                                        <th>Teléfono</th>
                                                        <th>Rol</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($expediente->personas as $involucrado)
                                                        <tr
                                                            class="@if ($involucrado->id === $persona->id) table-primary-subtle @endif">
                                                            <td class="font-weight-bold">
                                                                {{ $involucrado->nombres }} {{ $involucrado->apellidos }}
                                                                @if ($involucrado->id === $persona->id)
                                                                    <span class="badge bg-primary ms-2">Consultado</span>
                                                                @endif
                                                            </td>
                                                            <td>{{ $involucrado->cedula_tipo }}-{{ number_format($involucrado->cedula, 0, ',', '.') }}
                                                            </td>
                                                            <td>{{ $involucrado->telefono ?? '-' }}</td>
                                                            <td>
                                                                @if ($involucrado->pivot->rol == 'denunciante')
                                                                    <span class="badge bg-info px-2.5 py-1.5"><i
                                                                            class="ri-user-voice-line me-1"></i>Requirente</span>
                                                                @else
                                                                    <span class="badge bg-danger px-2.5 py-1.5"><i
                                                                            class="ri-user-received-line me-1"></i>Requerido</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- TAB: Citaciones -->
                                    <div class="tab-pane fade" id="pills-citaciones-{{ $expediente->id }}"
                                        role="tabpanel" aria-labelledby="pills-citaciones-tab-{{ $expediente->id }}">
                                        @if ($expediente->citaciones->isEmpty())
                                            <div class="text-center py-4">
                                                <i class="ri-calendar-todo-line text-muted mb-2"
                                                    style="font-size: 2rem; display: block;"></i>
                                                <p class="text-muted mb-0">No se han registrado citaciones para este
                                                    expediente.</p>
                                            </div>
                                        @else
                                            <div class="timeline-container px-3 py-2">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-hover align-middle">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Fecha y Hora</th>
                                                                {{-- <th>Asistencia</th> --}}
                                                                <th>Observaciones</th>
                                                                <th>Estatus</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($expediente->citaciones as $citacion)
                                                                <tr>
                                                                    <td class="font-weight-bold text-dark">
                                                                        <i class="ri-calendar-line me-1 text-primary"></i>
                                                                        {{ \Carbon\Carbon::parse($citacion->fecha_citacion)->format('d/m/Y') }}
                                                                        <br>
                                                                        <small class="text-muted"><i
                                                                                class="ri-time-line me-1"></i>{{ $citacion->hora_citacion }}</small>
                                                                    </td>
                                                                    {{-- <td>
                                                                        @if (is_null($citacion->asistio))
                                                                            <span class="badge bg-warning text-dark"><i
                                                                                    class="ri-question-line me-1"></i>Pendiente</span>
                                                                        @elseif(strtolower($citacion->asistio) == 'sí' || strtolower($citacion->asistio) == 'si')
                                                                            <span class="badge bg-success"><i
                                                                                    class="ri-checkbox-circle-line me-1"></i>Asistió</span>
                                                                        @else
                                                                            <span class="badge bg-danger"><i
                                                                                    class="ri-close-circle-line me-1"></i>Inasistente</span>
                                                                        @endif
                                                                    </td> --}}
                                                                    <td>
                                                                        <span
                                                                            class="text-dark small">{{ $citacion->observaciones ?? 'Sin observaciones' }}</span>
                                                                        @if ($citacion->solicitaCambio)
                                                                            <div class="mt-1 small bg-soft-warning p-1 rounded text-warning"
                                                                                style="background-color: rgba(254, 141, 0, 0.08); display: inline-block;">
                                                                                <i
                                                                                    class="ri-edit-line me-1"></i>Modificador:
                                                                                <strong
                                                                                    class="text-dark">{{ $citacion->solicitaCambio->nombres }}
                                                                                    {{ $citacion->solicitaCambio->apellidos }}</strong>
                                                                            </div>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if ($citacion->estatus)
                                                                            <span class="badge bg-success text-white"><i
                                                                                    class="ri-check-line me-1"></i>Vigente</span>
                                                                        @else
                                                                            <span class="badge bg-light text-muted"><i
                                                                                    class="ri-history-line me-1"></i>Pasada/Cancelada</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- TAB: Actas -->
                                    <div class="tab-pane fade" id="pills-actas-{{ $expediente->id }}" role="tabpanel"
                                        aria-labelledby="pills-actas-tab-{{ $expediente->id }}">
                                        @if ($expediente->actas->isEmpty())
                                            <div class="text-center py-4">
                                                <i class="ri-file-warning-line text-muted mb-2"
                                                    style="font-size: 2rem; display: block;"></i>
                                                <p class="text-muted mb-0">No se han registrado actas para este expediente.
                                                </p>
                                            </div>
                                        @else
                                            <div class="row row-cols-1 g-3">
                                                @foreach ($expediente->actas as $acta)
                                                    <div class="col">
                                                        <div class="card shadow-none border mb-2"
                                                            style="background-color: #fafafa;">
                                                            <div
                                                                class="card-header bg-transparent d-flex justify-content-between align-items-center border-bottom py-2">
                                                                <strong class="text-dark font-weight-bold">
                                                                    @if ($acta->tipo_acta == 'recepcion')
                                                                        <i
                                                                            class="ri-file-shield-2-line me-1 text-primary"></i>
                                                                        Acta de Recepción
                                                                    @elseif($acta->tipo_acta == 'conciliacion')
                                                                        <i
                                                                            class="ri-hand-heart-line me-1 text-success"></i>
                                                                        Acta de Conciliación
                                                                    @else
                                                                        <i
                                                                            class="ri-file-text-line me-1 text-secondary"></i>
                                                                        Acta: {{ ucfirst($acta->tipo_acta) }}
                                                                    @endif
                                                                </strong>
                                                                <span class="text-muted small">
                                                                    Registrada:
                                                                    {{ $acta->created_at->format('d/m/Y h:i A') }}
                                                                </span>
                                                            </div>
                                                            <div class="card-body p-3">
                                                                <!-- Contenido formateado del acta -->
                                                                <div
                                                                    class="acta-content-box bg-white p-3 border rounded shadow-sm">
                                                                    @php
                                                                        // Intentar formatear las lineas de clave: valor del acta
                                                                        $lineas = explode("\n", $acta->contenido);
                                                                        $formateado = false;
                                                                        foreach ($lineas as $linea) {
                                                                            if (strpos($linea, ':') !== false) {
                                                                                $formateado = true;
                                                                                break;
                                                                            }
                                                                        }
                                                                    @endphp

                                                                    @if ($formateado)
                                                                        <div class="row g-2">
                                                                            @foreach ($lineas as $linea)
                                                                                @php
                                                                                    $parts = explode(':', $linea, 2);
                                                                                @endphp
                                                                                @if (count($parts) == 2)
                                                                                    <div
                                                                                        class="col-sm-3 text-muted small font-weight-bold text-uppercase">
                                                                                        {{ trim($parts[0]) }}:</div>
                                                                                    <div class="col-sm-9 text-dark mb-2 font-weight-bold"
                                                                                        style="white-space: pre-wrap;">
                                                                                        {{ trim($parts[1]) }}</div>
                                                                                @else
                                                                                    <div class="col-12 text-dark mb-2 font-weight-bold"
                                                                                        style="white-space: pre-wrap;">
                                                                                        {{ trim($linea) }}</div>
                                                                                @endif
                                                                            @endforeach
                                                                        </div>
                                                                    @else
                                                                        <p class="mb-0 text-dark"
                                                                            style="white-space: pre-line; line-height: 1.6;">
                                                                            {{ $acta->contenido }}</p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <script>
        // Validar formularios de Bootstrap
        (function() {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function(forms) {
                    forms.addEventListener('submit', function(event) {
                        if (!forms.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        forms.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
@endsection
