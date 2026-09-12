@extends('layouts.main')

@section('titulo', $titulo)
@section('paginaTitulo', $paginaTitulo)
@section('paginaSubtitulo', $paginaSubtitulo)
@section('consultaActive', $consultaActive)

@section('contenido')
    <div class="container-fluid content-inner mt-n5 py-0">

        {{-- ========================================================================= --}}
        {{-- VISTA EXCLUSIVA PARA EL ROL JUEZ (Juzgado de Paz: Expedientes y Denuncias) --}}
        {{-- ========================================================================= --}}
        @if ($esJuez)
            <div class="row">
                <!-- Panel Izquierdo: Formulario de búsqueda e Información Básica -->
                <div class="col-lg-4 col-md-12 mb-4">
                    <div class="card mb-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title fw-bold mb-0"><i class="ri-search-eye-line text-primary me-2"></i>BUSCADOR DE HISTORIAL</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('consulta.index') }}" method="GET" class="needs-validation" novalidate>
                                <div class="row">
                                    <div hidden class="col-3 mb-3">
                                        <label for="cedula_tipo" class="form-label fw-bold">Tipo</label>
                                        <select class="form-select border-primary" id="cedula_tipo" name="cedula_tipo" required>
                                            <option value="V" {{ request('cedula_tipo', $cedulaTipo) == 'V' ? 'selected' : '' }}>V</option>
                                            <option value="E" {{ request('cedula_tipo', $cedulaTipo) == 'E' ? 'selected' : '' }}>E</option>
                                        </select>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="cedula" class="form-label fw-bold">Cédula de Identidad</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-id-card"></i></span>
                                            <input type="number" class="form-control border-primary" id="cedula" name="cedula" value="{{ request('cedula', $cedula) }}" placeholder="Ej: 12345678" required min="100000" max="999999999" oninput="if(this.value.length>8)this.value=this.value.slice(0,8)">
                                        </div>
                                        <div class="invalid-feedback">
                                            Ingrese un número de cédula válido.
                                        </div>
                                    </div>
                                </div>
                                <div class="d-grid gap-2 mt-2">
                                    <button type="submit" class="btn btn-primary d-flex align-items-center justify-content-center">
                                        <i class="ri-search-2-line me-2"></i> Buscar
                                    </button>
                                    @if ($busquedaRealizada)
                                        <a href="{{ route('consulta.index') }}" class="btn btn-secondary d-flex align-items-center justify-content-center">
                                            <i class="ri-refresh-line me-2"></i> Limpiar resultados
                                        </a>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Datos Básicos del Ciudadano Encontrado -->
                    @if ($persona)
                        <div class="card" data-aos="fade-up" data-aos-delay="300">
                            <div class="card-header bg-light py-3">
                                <h5 class="card-title mb-0 d-flex align-items-center fw-bold">
                                    <i class="ri-user-search-line me-2"></i> Datos del Ciudadano
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="text-center mb-4">
                                    <div class="avatar avatar-80 bg-primary-subtle rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                                        style="width: 70px; height: 70px; background-color: rgba(7, 154, 162, 0.1);">
                                        <i class="ri-user-line text-primary" style="font-size: 2.5rem;"></i>
                                    </div>
                                    <h5 class="mb-1 fw-bold">{{ $persona->nombres }} {{ $persona->apellidos }}</h5>
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
                                            <span class="font-weight-bold text-dark">{{ $persona->telefono ?? 'No registrado' }}</span>
                                        </div>
                                    </li>
                                    <li class="list-group-item px-0 py-3 d-flex align-items-start bg-transparent">
                                        <div class="me-3 bg-light rounded p-2 text-primary">
                                            <i class="ri-map-pin-2-fill fs-5"></i>
                                        </div>
                                        <div>
                                            <span class="text-muted d-block small">Dirección</span>
                                            <span class="font-weight-bold text-dark">{{ $persona->direccion ?? 'No registrada' }}</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Panel Derecho: Expedientes, citaciones y actas -->
                <div class="col-lg-8 col-md-12">
                    @if (!$busquedaRealizada)
                        <div class="card text-center p-5" data-aos="fade-up" data-aos-delay="200">
                            <div class="card-body">
                                <div class="avatar bg-soft-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                                    style="width: 90px; height: 90px; background-color: rgba(7, 154, 162, 0.1);">
                                    <i class="ri-file-search-line text-primary" style="font-size: 3.5rem;"></i>
                                </div>
                                <h3 class="mb-2 fw-bold">Buscador de Expedientes</h3>
                                <p class="text-muted mx-auto" style="max-width: 600px;">
                                    Ingrese la cédula de un ciudadano en el panel izquierdo para consultar todos sus
                                    expedientes, actas redactadas y citaciones registradas en el sistema.
                                </p>
                            </div>
                        </div>
                    @endif

                    @if ($busquedaRealizada && !$persona)
                        <div class="card text-center p-5" data-aos="fade-up" data-aos-delay="200">
                            <div class="card-body">
                                <div class="avatar bg-soft-danger rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                                    style="width: 90px; height: 90px; background-color: rgba(235, 104, 122, 0.1);">
                                    <i class="ri-user-unfollow-line text-danger" style="font-size: 3.5rem;"></i>
                                </div>
                                <h3 class="mb-2 text-danger">Ciudadano no registrado</h3>
                                <p class="text-muted mx-auto" style="max-width: 500px;">
                                    No se encontraron registros de expedientes ni visitas vinculadas a la cédula
                                    <strong>{{ $cedulaTipo }}-{{ $cedula }}</strong>.
                                </p>
                                <div class="mt-4">
                                    <a href="{{ route('consulta.index') }}" class="btn btn-outline-primary">Intentar con otra cédula</a>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($persona && $expedientes->isEmpty())
                        <div class="card text-center p-5" data-aos="fade-up" data-aos-delay="200">
                            <div class="card-body">
                                <div class="avatar bg-soft-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                                    style="width: 90px; height: 90px; background-color: rgba(254, 141, 0, 0.1);">
                                    <i class="ri-folder-warning-line text-warning" style="font-size: 3.5rem;"></i>
                                </div>
                                <h3 class="mb-2">Sin expedientes activos</h3>
                                <p class="text-muted mx-auto" style="max-width: 500px;">
                                    La persona <strong>{{ $persona->nombres }} {{ $persona->apellidos }}</strong> está registrada,
                                    pero no posee expedientes activos ni cerrados en el sistema del juzgado.
                                </p>
                            </div>
                        </div>
                    @endif

                    @if ($persona && !$expedientes->isEmpty())
                        @foreach ($expedientes as $index => $expediente)
                            <div class="card mb-4 border-start border-4 @if ($expediente->estatus == 'Abierto') border-success @elseif($expediente->estatus == 'En proceso') border-warning @else border-secondary @endif"
                                data-aos="fade-up" data-aos-delay="{{ 200 + $index * 100 }}">
                                <div class="card-header d-flex flex-wrap align-items-center justify-content-between py-3">
                                    <div>
                                        <h5 class="mb-0 font-weight-bold d-inline-block">Expediente #{{ $expediente->id }}</h5>
                                        <span class="ms-2 text-muted small d-inline-block">
                                            <i class="ri-calendar-event-line me-1"></i>Apertura: {{ $expediente->created_at->format('d/m/Y h:i A') }}
                                        </span>
                                    </div>
                                    <span class="badge @if ($expediente->estatus == 'Abierto') bg-success @elseif($expediente->estatus == 'En proceso') bg-warning text-dark @else bg-secondary @endif px-3 py-2 rounded-pill mt-2 mt-sm-0">
                                        {{ $expediente->estatus }}
                                    </span>
                                </div>
                                <div class="card-body">
                                    <div class="mb-4 p-3 rounded border-start border-primary border-3" style="background-color: #f8f9faf1;">
                                        <h6 class="font-weight-bold mb-1">
                                            <i class="text-primary ri-information-fill me-1"></i> {{ $expediente->caso }}
                                            <span class="text-muted">/</span>
                                            <i class="text-primary ri-bookmark-fill me-1"></i> {{ $expediente->tipo_caso }}
                                            <span class="text-muted">/</span>
                                            <i class="text-primary ri-archive-stack-fill me-1"></i> {{ $expediente->categoria }}
                                            @if ($expediente->denunciado_a)
                                                <span class="text-muted">/</span> <i class="text-danger ri-user-unfollow-fill me-1"></i> Denunciado(a): {{ $expediente->denunciado_a }}
                                            @endif
                                        </h6>
                                    </div>

                                    <ul class="nav nav-pills mb-3 bg-light p-1 " id="pills-tab-{{ $expediente->id }}" role="tablist">
                                        <li class="nav-item flex-fill text-center" role="presentation">
                                            <button class="nav-link active w-100" id="pills-involucrados-tab-{{ $expediente->id }}" data-bs-toggle="pill"
                                                data-bs-target="#pills-involucrados-{{ $expediente->id }}" type="button" role="tab">
                                                <i class="ri-group-line me-1"></i> Involucrados ({{ $expediente->personas->count() }})
                                            </button>
                                        </li>
                                        <li class="nav-item flex-fill text-center" role="presentation">
                                            <button class="nav-link w-100" id="pills-citaciones-tab-{{ $expediente->id }}" data-bs-toggle="pill"
                                                data-bs-target="#pills-citaciones-{{ $expediente->id }}" type="button" role="tab">
                                                <i class="ri-time-line me-1"></i> Citaciones ({{ $expediente->citaciones->count() }})
                                            </button>
                                        </li>
                                        <li class="nav-item flex-fill text-center" role="presentation">
                                            <button class="nav-link w-100" id="pills-actas-tab-{{ $expediente->id }}" data-bs-toggle="pill"
                                                data-bs-target="#pills-actas-{{ $expediente->id }}" type="button" role="tab">
                                                <i class="ri-file-list-3-line me-1"></i> Actas ({{ $expediente->actas->count() }})
                                            </button>
                                        </li>
                                    </ul>

                                    <div class="tab-content" id="pills-tabContent-{{ $expediente->id }}">
                                        <!-- TAB Involucrados -->
                                        <div class="tab-pane fade show active" id="pills-involucrados-{{ $expediente->id }}" role="tabpanel">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered table-hover align-middle mb-0">
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
                                                            <tr class="@if ($involucrado->id === $persona->id) table-primary-subtle @endif">
                                                                <td class="fw-bold">
                                                                    {{ $involucrado->nombres }} {{ $involucrado->apellidos }}
                                                                    @if ($involucrado->id === $persona->id)
                                                                        <span class="badge bg-primary ms-2">Consultado</span>
                                                                    @endif
                                                                </td>
                                                                <td>{{ $involucrado->cedula_tipo }}-{{ number_format($involucrado->cedula, 0, ',', '.') }}</td>
                                                                <td>{{ $involucrado->telefono ?? '-' }}</td>
                                                                <td>
                                                                    @if ($involucrado->pivot->rol == 'denunciante')
                                                                        <span class="badge bg-info px-2 py-1"><i class="ri-user-voice-line me-1"></i>Requirente</span>
                                                                    @else
                                                                        <span class="badge bg-danger px-2 py-1"><i class="ri-user-received-line me-1"></i>Requerido</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <!-- TAB Citaciones -->
                                        <div class="tab-pane fade" id="pills-citaciones-{{ $expediente->id }}" role="tabpanel">
                                            @if ($expediente->citaciones->isEmpty())
                                                <div class="text-center py-4 text-muted">
                                                    <i class="ri-calendar-todo-line fs-2 d-block mb-2"></i>
                                                    No se han registrado citaciones para este expediente.
                                                </div>
                                            @else
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-hover align-middle">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Fecha y Hora</th>
                                                                <th>Observaciones</th>
                                                                <th>Estatus</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($expediente->citaciones as $citacion)
                                                                <tr>
                                                                    <td class="fw-bold text-dark">
                                                                        <i class="ri-calendar-line me-1 text-primary"></i>
                                                                        {{ \Carbon\Carbon::parse($citacion->fecha_citacion)->format('d/m/Y') }}
                                                                        <br>
                                                                        <small class="text-muted"><i class="ri-time-line me-1"></i>{{ $citacion->hora_citacion }}</small>
                                                                    </td>
                                                                    <td style="max-width:330px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="{{ $citacion->observaciones ?? 'Sin observaciones' }}" data-bs-toggle="tooltip">
                                                                        <span class="text-dark small">{{ $citacion->observaciones ?? 'Sin observaciones' }}</span>
                                                                        @if ($citacion->solicitaCambio)
                                                                            <div class="mt-1 small p-1 rounded" style="background-color: rgba(254, 141, 0, 0.08); display: inline-block;">
                                                                                <i class="ri-edit-line me-1 text-warning"></i>Modificador:
                                                                                <strong>{{ $citacion->solicitaCambio->nombres }} {{ $citacion->solicitaCambio->apellidos }}</strong>
                                                                            </div>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if ($citacion->estatus)
                                                                            <span class="badge bg-success text-white"><i class="ri-check-line me-1"></i>Vigente</span>
                                                                        @else
                                                                            <span class="badge bg-light text-muted"><i class="ri-history-line me-1"></i>Pasada/Cancelada</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- TAB Actas -->
                                        <div class="tab-pane fade" id="pills-actas-{{ $expediente->id }}" role="tabpanel">
                                            @if ($expediente->actas->isEmpty())
                                                <div class="text-center py-4 text-muted">
                                                    <i class="ri-file-warning-line fs-2 d-block mb-2"></i>
                                                    No se han registrado actas para este expediente.
                                                </div>
                                            @else
                                                <div class="row row-cols-1 g-3">
                                                    @foreach ($expediente->actas as $acta)
                                                        <div class="col">
                                                            <div class="card border shadow-none mb-2" style="background-color: #fafafa;">
                                                                <div class="card-header bg-transparent d-flex justify-content-between align-items-center border-bottom py-2">
                                                                    <strong class="text-dark">
                                                                        @if ($acta->tipo_acta == 'recepcion')
                                                                            <i class="ri-file-shield-2-line me-1 text-primary"></i> Acta de Recepción
                                                                        @elseif($acta->tipo_acta == 'conciliacion')
                                                                            <i class="ri-hand-heart-line me-1 text-success"></i> Acta de Conciliación
                                                                        @else
                                                                            <i class="ri-file-text-line me-1 text-secondary"></i> Acta: {{ ucfirst($acta->tipo_acta) }}
                                                                        @endif
                                                                        <span class="text-muted small ms-2">Registrada: {{ $acta->created_at->format('d/m/Y h:i A') }}</span>
                                                                    </strong>
                                                                    @if ($acta->tipo_acta == 'recepcion')
                                                                        <a class="btn btn-sm text-white" style="background-color: rgb(212, 25, 25);" href="{{ route('denuncias.exportar-acta-recepcion', $expediente->id) }}">
                                                                            <i class="ri-file-pdf-2-line me-1"></i> Exportar a PDF
                                                                        </a>
                                                                    @else
                                                                        <a class="btn btn-sm text-white" style="background-color: rgb(212, 25, 25);" href="{{ route('denuncias.exportar-acta-conciliacion', $expediente->id) }}">
                                                                            <i class="ri-file-pdf-2-line me-1"></i> Exportar a PDF
                                                                        </a>
                                                                    @endif
                                                                </div>
                                                                <div class="card-body p-3">
                                                                    <div class="bg-white p-3 border rounded shadow-sm">
                                                                        <p class="mb-0 text-dark" style="white-space: pre-line;">{{ $acta->contenido }}</p>
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
        @endif


        {{-- ========================================================================= --}}
        {{-- VISTA PARA ROLES DE GESTIÓN COMUNAL (Jefe de Comuna y Jefe de Comando)    --}}
        {{-- ========================================================================= --}}
        @if ($esComunal)
            {{-- Pestañas Superiores de Navegación --}}
            <div class="card rounded-5 shadow-sm mb-4">
                <div class="card-body p-2">
                    <ul class="nav nav-pills nav-fill gap-2" id="consultaTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-2.5 fw-bold {{ $tabActiva === 'individual' ? 'active' : '' }}" 
                                id="tab-individual-btn" data-bs-toggle="pill" data-bs-target="#tab-individual" type="button" role="tab">
                                <i class="ri-user-search-line me-2 fs-5 align-middle"></i> Consulta individual
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-2.5 fw-bold {{ $tabActiva === 'avanzada' ? 'active' : '' }}" 
                                id="tab-avanzada-btn" data-bs-toggle="pill" data-bs-target="#tab-avanzada" type="button" role="tab">
                                <i class="ri-filter-3-line me-2 fs-5 align-middle"></i> Consulta avanzada
                            </button>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="tab-content" id="consultaTabsContent">
                {{-- ------------------------------------------------------------- --}}
                {{-- PESTAÑA 1: CONSULTA INDIVIDUAL POR CÉDULA                    --}}
                {{-- ------------------------------------------------------------- --}}
                <div class="tab-pane fade {{ $tabActiva === 'individual' ? 'show active' : '' }}" id="tab-individual" role="tabpanel">
                    <div class="row">
                        <!-- Panel de Búsqueda -->
                        <div class="col-lg-4 col-md-12 mb-4">
                            <div class="card shadow-sm" data-aos="fade-up">
                                <div class="card-header py-3">
                                    <h5 class="card-title fw-bold mb-0 text-secondary d-flex align-items-center">
                                        <i class="ri-user-search-fill me-2 fs-5"></i> BÚSQUEDA DE CIUDADANO
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('consulta.index') }}" method="GET" class="needs-validation" novalidate id="formConsultaIndividual">
                                        <input type="hidden" name="tab" value="individual">
                                        <div class="row">
                                            <div hidden class="col-3 mb-3">
                                                <label for="comunal_cedula_tipo" class="form-label fw-bold">Tipo</label>
                                                <select class="form-select border-primary" id="comunal_cedula_tipo" name="cedula_tipo" required>
                                                    <option value="V" {{ request('cedula_tipo', $cedulaTipo) == 'V' ? 'selected' : '' }}>V</option>
                                                    <option value="E" {{ request('cedula_tipo', $cedulaTipo) == 'E' ? 'selected' : '' }}>E</option>
                                                </select>
                                            </div>
                                            <div class="col-12 mb-3">
                                                <label for="comunal_cedula" class="form-label fw-bold">Cédula de Identidad</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fa-solid fa-id-card"></i></span>
                                                    <input type="number" class="form-control border-primary" id="comunal_cedula" name="cedula" value="{{ request('cedula', $cedula) }}" placeholder="Ej: 12345678" required min="100000" max="999999999" oninput="if(this.value.length>8)this.value=this.value.slice(0,8)">
                                                </div>
                                                <div class="invalid-feedback">
                                                    Ingrese un número de cédula válido.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-grid gap-2 mt-1">
                                            <button type="submit" class="btn btn-primary d-flex align-items-center justify-content-center">
                                                <i class="ri-search-2-line me-2"></i> Consultar
                                            </button>
                                            @if ($busquedaRealizada)
                                                <a href="{{ route('consulta.index') }}?tab=individual" class="btn btn-secondary d-flex align-items-center justify-content-center">
                                                    <i class="ri-refresh-line me-2"></i> Limpiar resultados
                                                </a>
                                            @endif
                                        </div>
                                    </form>
                                </div>
                            </div>

                            {{-- Resumen Rápido y Contacto si la persona existe --}}
                            @if ($persona)
                                <div class="card shadow-sm mt-4 border-0" data-aos="fade-up" data-aos-delay="200">
                                    <div class="card-body text-center p-4">
                                        <div class="avatar avatar-80 rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm"
                                            style="width: 80px; height: 80px; background: linear-gradient(135deg, #000327 0%, #0f0f42 100%); color: white;">
                                            <i class="ri-user-smile-line" style="font-size: 2.8rem;"></i>
                                        </div>
                                        <h5 class="fw-bold mb-1 text-dark">{{ $persona->nombres }} {{ $persona->apellidos }}</h5>
                                        <div class="badge bg-light px-3 py-2 text-dark fs-7 mb-2">
                                            {{ $persona->cedula_tipo }}-{{ number_format($persona->cedula, 0, ',', '.') }}
                                        </div>

                                        {{-- Badges de roles y estatus --}}
                                        <div class="d-flex flex-wrap justify-content-center gap-1 mt-2">
                                            @if ($persona->parentesco === 'Jefe de familia')
                                                <span class="badge bg-warning text-dark"><i class="ri-home-4-fill me-1"></i>Jefe de Familia</span>
                                            @endif
                                            @if ($persona->consejosComunales->isNotEmpty())
                                                <span class="badge bg-success text-white"><i class="ri-shield-star-fill me-1"></i>Jefe de Comando</span>
                                            @endif
                                            @if ($persona->vocerias->isNotEmpty())
                                                <span class="badge bg-info text-white"><i class="ri-user-star-fill me-1"></i>Vocero Comunal</span>
                                            @endif
                                            @if ($persona->pensionado_jubilado === 'Sí')
                                                <span class="badge bg-secondary"><i class="ri-medal-fill me-1"></i>Pensionado</span>
                                            @endif
                                            @if ($persona->estudia === 'Sí')
                                                <span class="badge bg-primary text-white"><i class="ri-book-open-fill me-1"></i>Estudiante</span>
                                            @endif
                                        </div>

                                        <hr class="my-3">

                                        <div class="text-start">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="ri-phone-fill text-primary me-2 fs-5"></i>
                                                <div>
                                                    <small class="text-muted d-block">Teléfono:</small>
                                                    <span class="fw-bold text-dark">{{ $persona->telefono ?: 'No registrado' }}</span>
                                                    @if($persona->telefono)
                                                        <button type="button" class="btn btn-sm btn-link p-0 ms-1 text-primary" title="Copiar teléfono" onclick="copiarAlPortapapeles('{{ $persona->telefono }}')">
                                                            <i class="ri-file-copy-line"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-start mb-2">
                                                <i class="ri-map-pin-2-fill text-primary me-2 fs-5 mt-1"></i>
                                                <div>
                                                    <small class="text-muted d-block">Dirección de Habitación:</small>
                                                    <span class="fw-semibold text-dark">{{ $persona->direccion ?: 'No registrada' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Panel de Información Detallada del Censo -->
                        <div class="col-lg-8 col-md-12">
                            @if (!$busquedaRealizada)
                                <div class="card text-center p-5 shadow-sm" data-aos="fade-up">
                                    <div class="card-body">
                                        <div class="avatar bg-soft-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                                            style="width: 90px; height: 90px; background-color: rgba(7, 154, 162, 0.1);">
                                            <i class="ri-user-search-line text-primary" style="font-size: 3.5rem;"></i>
                                        </div>
                                        <h3 class="mb-2 fw-bold">Consulta del censo ciudadano</h3>
                                        <p class="text-muted mx-auto" style="max-width: 600px;">
                                            Ingrese el número de cédula en el panel izquierdo para consultar todo el expediente censal del ciudadano:
                                            núcleo familiar, vivienda, beneficios, liderazgo comunal y datos sociodemográficos.
                                        </p>
                                    </div>
                                </div>
                            @endif

                            @if ($busquedaRealizada && !$persona)
                                <div class="card text-center p-5 shadow-sm" data-aos="fade-up">
                                    <div class="card-body">
                                        <div class="avatar bg-soft-danger rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                                            style="width: 90px; height: 90px; background-color: rgba(235, 104, 122, 0.1);">
                                            <i class="ri-user-unfollow-line text-danger" style="font-size: 3.5rem;"></i>
                                        </div>
                                        <h3 class="mb-2 text-danger">Ciudadano no registrado en el censo</h3>
                                        <p class="text-muted mx-auto" style="max-width: 500px;">
                                            No se encontraron registros en el censo con la cédula <strong>{{ $cedulaTipo }}-{{ $cedula }}</strong>.
                                        </p>
                                        <div class="mt-4">
                                            <a href="{{ route('consulta.index') }}?tab=individual" class="btn btn-outline-primary">Intentar con otra cédula</a>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($persona)
                                @php
                                    $edadCalculada = $persona->edad ?? ($persona->fecha_nacimiento ? \Carbon\Carbon::parse($persona->fecha_nacimiento)->age : null);
                                    $fechaNacimientoFormato = $persona->fecha_nacimiento ? \Carbon\Carbon::parse($persona->fecha_nacimiento)->format('d/m/Y') : 'No registrada';
                                    $familia = $persona->familia;
                                    $consejoComunal = $familia?->consejoComunal;
                                @endphp

                                {{-- SECCIÓN 1: NÚCLEO FAMILIAR Y VIVIENDA --}}
                                <div class="card shadow-sm mb-4 border-0" data-aos="fade-up">
                                    <div class="card-header d-flex justify-content-between align-items-center py-3" style="background-color: #f5f6fa;">
                                        <h5 class="card-title mb-0 text-secondary fw-bold d-flex align-items-center">
                                            <i class="ri-home-smile-fill me-2 fs-5"></i> NÚCLEO FAMILIAR Y VIVIENDA
                                        </h5>
                                        @if ($familia)
                                            <button type="button" class="btn btn-primary btn-sm d-flex align-items-center" onclick="cargarModalFamilia({{ $familia->id }})">
                                                <i class="ri-team-fill me-1"></i> Ver núcleo familiar ({{ $familia->personas->count() }})
                                            </button>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        @if ($familia)
                                            <div class="row g-3">
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="p-3 border rounded" style="background-color: #f5f6fa;">
                                                        <span class="text-muted small fw-bold d-block text-uppercase">Comunidad / Consejo Comunal:</span>
                                                        <span class="fs-6 fw-bold text-dark">
                                                            <i class="ri-community-line text-primary me-1"></i> {{ $consejoComunal?->nombre ?: 'Sin comunidad vinculada' }}
                                                        </span>
                                                        @if($consejoComunal?->rif)
                                                            <small class="text-muted d-block mt-1">RIF: {{ $consejoComunal->rif }}</small>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-sm-12">
                                                    <div class="p-3 border rounded" style="background-color: #f5f6fa;">
                                                        <span class="text-muted small fw-bold d-block text-uppercase">Identificación Familiar:</span>
                                                        <div class="d-flex justify-content-between align-items-center mt-1">
                                                            <span class="fs-6 fw-bold text-dark">{{ $familia->numero_familia }}</span>
                                                            <span class="badge bg-secondary">{{ $persona->parentesco ?: 'Integrante' }}</span>
                                                        </div>
                                                        <small class="text-muted d-block mt-1">Total integrantes censados: {{ $familia->personas->count() }} personas</small>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <div class="border rounded p-2.5 text-center">
                                                        <small class="text-muted d-block fw-bold">Tipo Vivienda</small>
                                                        <span class="badge bg-secondary mt-1">{{ $familia->vivienda ?: 'No registrada' }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-6">
                                                    <div class="border rounded p-2.5 text-center">
                                                        <small class="text-muted d-block fw-bold">Misión Vivienda</small>
                                                        <span class="badge {{ $familia->mision_vivienda === 'Sí' ? 'bg-info' : 'bg-light text-dark' }} mt-1">
                                                            {{ $familia->mision_vivienda === 'Sí' ? 'GMVV Beneficiario' : ($familia->mision_vivienda ?: 'No') }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-6">
                                                    <div class="border rounded p-2.5 text-center">
                                                        <small class="text-muted d-block fw-bold">Recibe CLAP</small>
                                                        <span class="badge {{ $familia->clap === 'Sí' ? 'bg-success' : 'bg-light text-dark' }} mt-1">
                                                            {{ $familia->clap ?: 'No' }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-6">
                                                    <div class="border rounded p-2.5 text-center">
                                                        <small class="text-muted d-block fw-bold">Bono Familiar</small>
                                                        <span class="badge {{ $familia->bono_unico_familiar === 'Sí' ? 'bg-danger' : 'bg-light text-dark' }} mt-1">
                                                            {{ $familia->bono_unico_familiar ?: 'No' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="alert alert-warning mb-0 d-flex align-items-center">
                                                <i class="ri-alert-line fs-4 me-2"></i>
                                                <span>Este ciudadano actualmente <strong>no está vinculado a ningún núcleo familiar</strong> registrado en el censo.</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- SECCIÓN 2: DATOS PERSONALES, DEMOGRÁFICOS Y ELECTORALES --}}
                                <div class="card shadow-sm mb-4 border-0" data-aos="fade-up" data-aos-delay="100">
                                    <div class="card-header py-3" style="background-color: #f5f6fa;">
                                        <h5 class="card-title mb-0 text-secondary fw-bold d-flex align-items-center">
                                            <i class="ri-id-card-fill me-2 fs-5"></i> DATOS PERSONALES Y DEMOGRÁFICOS
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-4 col-sm-6">
                                                <small class="text-muted fw-bold d-block">Edad Actual:</small>
                                                <span class="fw-bold text-dark fs-6">
                                                    {{ $edadCalculada !== null ? $edadCalculada . ' años' : 'No registrada' }}
                                                </span>
                                            </div>
                                            <div class="col-md-4 col-sm-6">
                                                <small class="text-muted fw-bold d-block">Fecha de Nacimiento:</small>
                                                <span class="fw-bold text-dark fs-6">{{ $fechaNacimientoFormato }}</span>
                                            </div>
                                            <div class="col-md-4 col-sm-6">
                                                <small class="text-muted fw-bold d-block">Género:</small>
                                                <span class="fw-bold text-dark fs-6">{{ $persona->genero ?: 'No registrado' }}</span>
                                            </div>

                                            <div class="col-md-4 col-sm-12">
                                                <small class="text-muted fw-bold d-block">Centro de Votación:</small>
                                                <span class="fw-semibold text-dark">{{ $persona->centro_votacion ?: 'No registrado' }}</span>
                                            </div>
                                            <div class="col-md-4 col-sm-12">
                                                <small class="text-muted fw-bold d-block">Carnet de la Patria:</small>
                                                <span class="fw-semibold text-dark">{{ $persona->carnet_patria ?: 'No registrado' }}</span>
                                            </div>
                                            <div class="col-md-4 col-sm-12">
                                                {{-- Contenedor vacío --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- SECCIÓN 3: EDUCACIÓN, TRABAJO Y SALUD --}}
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <div class="card shadow-sm h-100 border-0" data-aos="fade-up" data-aos-delay="150">
                                            <div class="card-header py-3" style="background-color: #f5f6fa;">
                                                <h5 class="card-title mb-0 text-secondary fw-bold d-flex align-items-center">
                                                    <i class="ri-graduation-cap-fill me-2 fs-5"></i> EDUCACIÓN Y OCUPACIÓN
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item px-0 py-2 d-flex justify-content-between bg-transparent">
                                                        <span class="text-muted">Nivel Académico:</span>
                                                        <span class="fw-bold text-dark">{{ $persona->nivel_academico ?: 'No registrado' }}</span>
                                                    </li>
                                                    <li class="list-group-item px-0 py-2 d-flex justify-content-between bg-transparent">
                                                        <span class="text-muted">¿Estudia actualmente?:</span>
                                                        <span class="badge {{ $persona->estudia === 'Sí' ? 'bg-success' : 'bg-secondary' }}">{{ $persona->estudia ?: 'No' }}</span>
                                                    </li>
                                                    <li class="list-group-item px-0 py-2 d-flex justify-content-between bg-transparent">
                                                        <span class="text-muted">Profesión / Oficio:</span>
                                                        <span class="fw-bold text-dark">{{ $persona->profesion ?: 'No registrada' }}</span>
                                                    </li>
                                                    <li class="list-group-item px-0 py-2 d-flex justify-content-between bg-transparent">
                                                        <span class="text-muted">Situación Laboral:</span>
                                                        <span class="fw-bold text-dark">{{ $persona->situacion_laboral ?: 'No registrada' }}</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <div class="card shadow-sm h-100 border-0" data-aos="fade-up" data-aos-delay="200">
                                            <div class="card-header py-3" style="background-color: #f5f6fa;">
                                                <h5 class="card-title mb-0 text-secondary fw-bold d-flex align-items-center">
                                                    <i class="ri-heart-pulse-fill me-2 fs-5"></i> SALUD Y BIENESTAR
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item px-0 py-2 d-flex justify-content-between bg-transparent">
                                                        <span class="text-muted">Condición / Enfermedad:</span>
                                                        <span class="fw-bold text-dark">{{ $persona->tipo_enfermedad ?: 'Ninguna reportada' }}</span>
                                                    </li>
                                                    <li class="list-group-item px-0 py-2 d-flex justify-content-between bg-transparent">
                                                        <span class="text-muted">Pensionado / Jubilado:</span>
                                                        <span class="badge {{ $persona->pensionado_jubilado === 'Sí' ? 'bg-primary' : 'bg-secondary' }}">{{ $persona->pensionado_jubilado ?: 'No' }}</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- SECCIÓN 4: LIDERAZGO Y PARTICIPACIÓN COMUNITARIA --}}
                                @if ($persona->consejosComunales->isNotEmpty() || $persona->vocerias->isNotEmpty())
                                    <div class="card shadow-sm mb-4 border-0" data-aos="fade-up" data-aos-delay="250">
                                        <div class="card-header py-3" style="background-color: #f5f6fa;">
                                            <h5 class="card-title mb-0 text-secondary fw-bold  d-flex align-items-center">
                                                <i class="ri-award-fill me-2 fs-5"></i> LIDERAZGO Y PARTICIPACIÓN COMUNITARIA
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            {{-- Jefatura de Comando --}}
                                            @if ($persona->consejosComunales->isNotEmpty())
                                                <div class="mb-3 p-3 rounded border border-success bg-soft-success" style="background-color: rgba(40, 167, 69, 0.05);">
                                                    <h6 class="fw-bold text-success mb-2 d-flex align-items-center">
                                                        <i class="ri-shield-star-fill me-2"></i> Cargo: Jefe de Comando de Comunidad
                                                    </h6>
                                                    @foreach ($persona->consejosComunales as $ccLider)
                                                        <p class="mb-1 text-dark"><strong>Comunidad:</strong> {{ $ccLider->nombre }} (RIF: {{ $ccLider->rif ?: 'S/R' }})</p>
                                                        <p class="mb-0 text-muted small"><strong>Dirección:</strong> {{ $ccLider->direccion ?: 'No registrada' }}</p>
                                                    @endforeach
                                                </div>
                                            @endif

                                            {{-- Vocerías --}}
                                            @if ($persona->vocerias->isNotEmpty())
                                                <div class="p-3 rounded border border-info bg-soft-info" style="background-color: rgba(23, 162, 184, 0.05);">
                                                    <h6 class="fw-bold text-info mb-2 d-flex align-items-center">
                                                        <i class="ri-user-star-fill me-2"></i> Vocerías Asignadas
                                                    </h6>
                                                    <ul class="list-unstyled mb-0">
                                                        @foreach ($persona->vocerias as $voceria)
                                                            <li class="mb-1 text-dark d-flex justify-content-between align-items-center">
                                                                <span><strong>Comité:</strong> {{ $voceria->categoria_vocero }}</span>
                                                                <div>
                                                                    <span class="badge {{ $voceria->activo ? 'bg-success' : 'bg-secondary' }}">
                                                                        {{ $voceria->activo ? 'Activo' : 'Inactivo' }}
                                                                    </span>
                                                                    @if ($voceria->fecha_eleccion)
                                                                        <small class="text-muted ms-2">Elegido: {{ \Carbon\Carbon::parse($voceria->fecha_eleccion)->format('d/m/Y') }}</small>
                                                                    @endif
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ------------------------------------------------------------- --}}
                {{-- PESTAÑA 2: BÚSQUEDA AVANZADA DEL CENSO Y REPORTES PDF          --}}
                {{-- ------------------------------------------------------------- --}}
                <div class="tab-pane fade {{ $tabActiva === 'avanzada' ? 'show active' : '' }}" id="tab-avanzada" role="tabpanel">
                    <!-- Formulario de Filtros Multicriterio -->
                    <div class="card shadow-sm mb-4 border-0" data-aos="fade-up">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center flex-wrap gap-2" style="background-color: #f5f6fa;">
                            <div>
                                <h5 class="card-title mb-0 text-secondary fw-bold d-flex align-items-center">
                                    <i class="ri-filter-3-fill me-2 fs-5"></i> FILTROS MULTICRITERIO DEL CENSO
                                </h5>
                                <small class="text-muted fst-italic">Combine los parámetros que desee para generar consultas poblacionales específicas.</small>
                            </div>
                            {{-- @if ($busquedaAvanzadaRealizada && $resultadosAvanzados->isNotEmpty())
                                <a href="{{ route('consulta.busqueda-avanzada.pdf') }}?{{ http_build_query(request()->all()) }}" 
                                    class="btn btn-danger btn-sm d-flex align-items-center shadow-sm" target="_blank">
                                    <i class="ri-file-pdf-2-line me-1 fs-6"></i> Exportar Resultados a PDF
                                </a>
                            @endif --}}
                        </div>
                        <div class="card-body">
                            <form action="{{ route('consulta.index') }}" method="GET" id="formBusquedaAvanzada">
                                <input type="hidden" name="tab" value="avanzada">
                                <input type="hidden" name="buscar_avanzado" value="1">

                                <div class="row g-3">
                                    {{-- Fila 1: Comunidad y Demografía --}}
                                    <div class="col-lg-3 col-md-6">
                                        <label for="filtro_comunidad" class="form-label fw-bold text-secondary small text-uppercase">Comunidad / Consejo Comunal</label>
                                        <select class="form-select " id="filtro_comunidad" name="consejo_comunal_id">
                                            <option value="">-- Todas las comunidades --</option>
                                            @foreach ($consejosComunales as $cc)
                                                <option value="{{ $cc->id }}" {{ request('consejo_comunal_id') == $cc->id ? 'selected' : '' }}>
                                                    {{ $cc->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-lg-3 col-md-3">
                                        <label for="filtro_genero" class="form-label fw-bold text-secondary small text-uppercase">Género</label>
                                        <select class="form-select " id="filtro_genero" name="genero">
                                            <option value="">Todos</option>
                                            <option value="Masculino" {{ request('genero') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                            <option value="Femenino" {{ request('genero') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                        </select>
                                    </div>

                                    <div class="col-lg-3 col-md-3">
                                        <label class="form-label fw-bold text-secondary small text-uppercase">Condición de Edad</label>
                                        <div class="input-group">
                                            <select class="form-select " name="edad_operador" id="edad_operador">
                                                <option value="">Exacto / Libre</option>
                                                <option value="menor" {{ request('edad_operador') == 'menor' ? 'selected' : '' }}>Menores de (&lt;)</option>
                                                <option value="menor_o_igual" {{ request('edad_operador') == 'menor_o_igual' ? 'selected' : '' }}>Hasta (&le;)</option>
                                                <option value="mayor_o_igual" {{ request('edad_operador') == 'mayor_o_igual' ? 'selected' : '' }}>Desde (&ge;)</option>
                                                <option value="mayor" {{ request('edad_operador') == 'mayor' ? 'selected' : '' }}>Mayores de (&gt;)</option>
                                            </select>
                                            <input type="number" class="form-control " name="edad_limite" placeholder="Años" 
                                                value="{{ request('edad_limite') }}" min="0" max="120" style="max-width: 90px;">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-6">
                                        <label class="form-label fw-bold text-secondary small text-uppercase">Rango de Edad (Min - Max)</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control " name="edad_min" placeholder="Mín" 
                                                value="{{ request('edad_min') }}" min="0" max="120">
                                            <span class="input-group-text bg-light">a</span>
                                            <input type="number" class="form-control " name="edad_max" placeholder="Máx" 
                                                value="{{ request('edad_max') }}" min="0" max="120">
                                        </div>
                                    </div>

                                    {{-- Fila 2: Socio-Laboral y Educativo --}}
                                    <div class="col-lg-3 col-md-6">
                                        <label for="filtro_pensionado" class="form-label fw-bold text-secondary small text-uppercase">Pensionado / Jubilado</label>
                                        <select class="form-select " id="filtro_pensionado" name="pensionado_jubilado">
                                            <option value="">Todos</option>
                                            <option value="Sí" {{ request('pensionado_jubilado') == 'Sí' ? 'selected' : '' }}>Sí</option>
                                            <option value="No" {{ request('pensionado_jubilado') == 'No' ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>

                                    <div class="col-lg-3 col-md-6">
                                        <label for="filtro_estudia" class="form-label fw-bold text-secondary small text-uppercase">¿Estudia actualmente?</label>
                                        <select class="form-select " id="filtro_estudia" name="estudia">
                                            <option value="">Todos</option>
                                            <option value="Sí" {{ request('estudia') == 'Sí' ? 'selected' : '' }}>Sí</option>
                                            <option value="No" {{ request('estudia') == 'No' ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>

                                    <div class="col-lg-3 col-md-6">
                                        <label for="filtro_nivel" class="form-label fw-bold text-secondary small text-uppercase">Nivel Académico</label>
                                        <select class="form-select " id="filtro_nivel" name="nivel_academico">
                                            <option value="Todos">Todos</option>
                                            <option value="Ninguno" {{ request('nivel_academico') == 'Ninguno' ? 'selected' : '' }}>Ninguno</option>
                                            <option value="Primaria" {{ request('nivel_academico') == 'Primaria' ? 'selected' : '' }}>Primaria</option>
                                            <option value="Secundaria" {{ request('nivel_academico') == 'Secundaria' ? 'selected' : '' }}>Secundaria</option>
                                            <option value="Técnico" {{ request('nivel_academico') == 'Técnico' ? 'selected' : '' }}>Técnico</option>
                                            <option value="Universitario" {{ request('nivel_academico') == 'Universitario' ? 'selected' : '' }}>Universitario</option>
                                            <option value="Postgrado" {{ request('nivel_academico') == 'Postgrado' ? 'selected' : '' }}>Postgrado</option>
                                        </select>
                                    </div>

                                    <div class="col-lg-3 col-md-6">
                                        <label for="filtro_profesion" class="form-label fw-bold text-secondary small text-uppercase">Profesión / Ocupación</label>
                                        <input type="text" class="form-control " id="filtro_profesion" name="profesion" 
                                            placeholder="Ej: Docente, Obrero, Ingeniero..." value="{{ request('profesion') }}" list="listaProfesiones">
                                        <datalist id="listaProfesiones">
                                            @foreach ($profesiones as $prof)
                                                <option value="{{ $prof }}">
                                            @endforeach
                                        </datalist>
                                    </div>

                                    {{-- Fila 3: Vivienda y Beneficios --}}
                                    <div hidden class="col-lg-3 col-md-6">
                                        <label for="filtro_vivienda" class="form-label fw-bold text-secondary small text-uppercase">Tipo de Vivienda</label>
                                        <select class="form-select " id="filtro_vivienda" name="vivienda">
                                            <option value="">Todas</option>
                                            <option value="Propia" {{ request('vivienda') == 'Propia' ? 'selected' : '' }}>Propia</option>
                                            <option value="Prestada" {{ request('vivienda') == 'Prestada' ? 'selected' : '' }}>Prestada</option>
                                            <option value="Alquilada" {{ request('vivienda') == 'Alquilada' ? 'selected' : '' }}>Alquilada</option>
                                        </select>
                                    </div>

                                    <div hidden class="col-lg-3 col-md-6">
                                        <label for="filtro_mision" class="form-label fw-bold text-secondary small text-uppercase">Misión Vivienda (GMVV)</label>
                                        <select class="form-select " id="filtro_mision" name="mision_vivienda">
                                            <option value="">Todos</option>
                                            <option value="Sí" {{ request('mision_vivienda') == 'Sí' ? 'selected' : '' }}>Sí (Beneficiario)</option>
                                            <option value="No" {{ request('mision_vivienda') == 'No' ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>

                                    <div hidden class="col-lg-3 col-md-6">
                                        <label for="filtro_clap" class="form-label fw-bold text-secondary small text-uppercase">Recibe CLAP</label>
                                        <select class="form-select " id="filtro_clap" name="clap">
                                            <option value="">Todos</option>
                                            <option value="Sí" {{ request('clap') == 'Sí' ? 'selected' : '' }}>Sí</option>
                                            <option value="No" {{ request('clap') == 'No' ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>

                                    <div hidden class="col-lg-3 col-md-6">
                                        <label for="filtro_bono" class="form-label fw-bold text-secondary small text-uppercase">Bono Único Familiar</label>
                                        <select class="form-select " id="filtro_bono" name="bono_unico_familiar">
                                            <option value="">Todos</option>
                                            <option value="Sí" {{ request('bono_unico_familiar') == 'Sí' ? 'selected' : '' }}>Sí</option>
                                            <option value="No" {{ request('bono_unico_familiar') == 'No' ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>

                                    {{-- Fila 4: Liderazgo y Roles Comunitarios --}}
                                    <div class="col-lg-3 col-md-6">
                                        <label for="filtro_parentesco" class="form-label fw-bold text-secondary small text-uppercase">Rol / Parentesco</label>
                                        <select class="form-select " id="filtro_parentesco" name="parentesco">
                                            <option value="">Todos los roles</option>
                                            <option value="Jefe de familia" {{ request('parentesco') == 'Jefe de familia' ? 'selected' : '' }}>Jefe de Familia</option>
                                            <option value="Hijo/a" {{ request('parentesco') == 'Hijo/a' ? 'selected' : '' }}>Hijo/a</option>
                                            <option value="Padre" {{ request('parentesco') == 'Padre' ? 'selected' : '' }}>Padre</option>
                                            <option value="Madre" {{ request('parentesco') == 'Madre' ? 'selected' : '' }}>Madre</option>
                                            <option value="Abuelo/a" {{ request('parentesco') == 'Abuelo/a' ? 'selected' : '' }}>Abuelo/a</option>
                                            <option value="Tío/a" {{ request('parentesco') == 'Tío/a' ? 'selected' : '' }}>Tío/a</option>
                                            <option value="Primo/a" {{ request('parentesco') == 'Primo/a' ? 'selected' : '' }}>Primo/a</option>
                                        </select>
                                    </div>

                                    <div hidden class="col-lg-3 col-md-6">
                                        <label for="filtro_jefe_comando" class="form-label fw-bold text-secondary small text-uppercase">¿Es Jefe de Comando?</label>
                                        <select class="form-select " id="filtro_jefe_comando" name="es_jefe_comando">
                                            <option value="">Todos</option>
                                            <option value="Sí" {{ request('es_jefe_comando') == 'Sí' ? 'selected' : '' }}>Sí (Comandante de Comunidad)</option>
                                        </select>
                                    </div>

                                    <div hidden class="col-lg-3 col-md-6">
                                        <label for="filtro_vocero" class="form-label fw-bold text-secondary small text-uppercase">¿Es Vocero Comunal?</label>
                                        <select class="form-select " id="filtro_vocero" name="es_vocero">
                                            <option value="">Todos</option>
                                            <option value="Sí" {{ request('es_vocero') == 'Sí' ? 'selected' : '' }}>Sí</option>
                                        </select>
                                    </div>

                                    <div hidden class="col-lg-3 col-md-6">
                                        <label for="filtro_cat_voceria" class="form-label fw-bold text-secondary small text-uppercase">Comité de Vocería</label>
                                        <select class="form-select " id="filtro_cat_voceria" name="categoria_voceria">
                                            <option value="">Todas las vocerías</option>
                                            @foreach ($categoriasVoceria as $cat)
                                                <option value="{{ $cat->nombre }}" {{ request('categoria_voceria') == $cat->nombre ? 'selected' : '' }}>
                                                    {{ $cat->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end align-items-center gap-2 mt-4 pt-3 border-top">
                                    <a href="{{ route('consulta.index') }}?tab=avanzada" class="btn btn-secondary">
                                        <i class="ri-refresh-line me-1"></i> Limpiar filtros
                                    </a>
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="ri-search-2-line me-1"></i> Filtrar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Resumen de Métricas de la Búsqueda --}}
                    @if ($busquedaAvanzadaRealizada)
                        <div class="row g-3 mb-4" data-aos="fade-up">
                            <div class="col-xl-2 col-md-4 col-6">
                                <div class="card border-0 shadow-sm text-center p-3 h-100" style="background: linear-gradient(135deg, #002bb87e 0%, #000f50 100%); color: white;">
                                    <span class="small fw-bold text-uppercase opacity-75">Total Encontrados</span>
                                    <h3 class="fw-bold text-white mb-0 mt-1">{{ $metricasAvanzadas['total'] ?? 0 }}</h3>
                                    <small class="opacity-75">ciudadanos</small>
                                </div>
                            </div>
                            <div class="col-xl-2 col-md-4 col-6">
                                <div class="card border-0 shadow-sm text-center p-3 h-100" style="background: linear-gradient(135deg, #0086dfc5 0%, #003050 100%); color: white;">
                                    <span class="small fw-bold text-uppercase">Masculinos</span>
                                    <h3 class="fw-bold text-white mb-0 mt-1">{{ $metricasAvanzadas['masculinos'] ?? 0 }}</h3>
                                    <small class="opacity-75">hombres</small>
                                </div>
                            </div>
                            <div class="col-xl-2 col-md-4 col-6">
                                <div class="card border-0 shadow-sm text-center p-3 h-100" style="background: linear-gradient(135deg, #ff5fdce3 0%, #9e085f 100%); color: white;">
                                    <span class="small fw-bold text-uppercase">Femeninos</span>
                                    <h3 class="fw-bold text-white mb-0 mt-1">{{ $metricasAvanzadas['femeninos'] ?? 0 }}</h3>
                                    <small class="opacity-75">mujeres</small>
                                </div>
                            </div>
                            <div class="col-xl-2 col-md-4 col-6">
                                <div class="card border-0 shadow-sm text-center p-3 h-100" style="background: linear-gradient(135deg, #5f24ff 0%, #6850d3 100%); color: white;">
                                    <span class="small fw-bold text-uppercase">Menores (&lt;18)</span>
                                    <h3 class="fw-bold text-white mb-0 mt-1">{{ $metricasAvanzadas['menores18'] ?? 0 }}</h3>
                                    <small class="opacity-75">niños y jóvenes</small>
                                </div>
                            </div>
                            <div class="col-xl-2 col-md-4 col-6">
                                <div class="card border-0 shadow-sm text-center p-3 h-100 " style="background: linear-gradient(135deg, #24b5f8 0%, #4369d1 100%); color: white;">
                                    <span class="small fw-bold text-uppercase">Adultos 60+</span>
                                    <h3 class="fw-bold text-white mb-0 mt-1">{{ $metricasAvanzadas['adultosMayores60'] ?? 0 }}</h3>
                                    <small class="opacity-75">adultos mayores</small>
                                </div>
                            </div>
                            <div class="col-xl-2 col-md-4 col-6">
                                <div class="card border-0 shadow-sm text-center p-3 h-100" style="background: linear-gradient(135deg, #10d11aad 0%, #105000 100%); color: white;">
                                    <span class="small fw-bold text-uppercase">Núcleos Familiares</span>
                                    <h3 class="fw-bold text-white mb-0 mt-1">{{ $metricasAvanzadas['totalFamilias'] ?? 0 }}</h3>
                                    <small class="opacity-75">familias impactadas</small>
                                </div>
                            </div>
                        </div>

                        {{-- Tabla de Resultados --}}
                        <div class="card shadow-sm border-0 mb-4" data-aos="fade-up">
                            <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3">
                                <div>
                                    <h5 class="card-title mb-0 fw-bold text-dark">
                                        <i class="ri-user-follow-line text-primary me-2"></i> RESULTADOS ({{ $resultadosAvanzados->count() }})
                                    </h5>
                                </div>
                                @if ($resultadosAvanzados->isNotEmpty())
                                    <a href="{{ route('consulta.busqueda-avanzada.pdf') }}?{{ http_build_query(request()->all()) }}" 
                                        class="btn btn-danger btn-sm d-flex align-items-center" target="_blank">
                                        <i class="ri-file-pdf-2-line me-1"></i> Descargar PDF
                                    </a>
                                @endif
                            </div>
                            <div class="card-body p-0">
                                @if ($resultadosAvanzados->isEmpty())
                                    <div class="text-center py-5">
                                        <div class="avatar bg-soft-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                                            <i class="ri-filter-off-line text-warning fs-1"></i>
                                        </div>
                                        <h5 class="text-muted">No se encontraron ciudadanos con la combinación de filtros seleccionada.</h5>
                                        <p class="text-muted small">Intente flexibilizar o modificar algunos de los parámetros del formulario superior.</p>
                                    </div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered table-striped-columns align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Cédula</th>
                                                    <th>Apellidos y Nombres</th>
                                                    <th>Edad / Género</th>
                                                    <th>Comunidad / Familia</th>
                                                    <th>Profesión / Nivel</th>
                                                    <th>Condiciones</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($resultadosAvanzados as $item)
                                                    @php
                                                        $edadItem = $item->edad ?? ($item->fecha_nacimiento ? \Carbon\Carbon::parse($item->fecha_nacimiento)->age : null);
                                                        $ccItem = $item->familia?->consejoComunal?->nombre ?? 'Sin comunidad';
                                                        $famId = $item->familia_id;
                                                    @endphp
                                                    <tr>
                                                        <td class="fw-bold text-secondary">
                                                            <i class="fa-solid fa-id-card"></i>
                                                            {{ $item->cedula_tipo }}-{{ number_format($item->cedula, 0, ',', '.') }}
                                                        </td>
                                                        <td>
                                                            <span class="fw-bold text-dark d-block">{{ $item->apellidos }}, {{ $item->nombres }}</span>
                                                            <div class="d-flex gap-1 mt-0.5">
                                                                @if($item->parentesco === 'Jefe de familia')
                                                                    <span class="badge bg-light text-dark fs-8">Jefe de Familia</span>
                                                                @endif
                                                                @if($item->consejosComunales->isNotEmpty())
                                                                    <span class="badge bg-success text-white fs-8">Jefe de Comando</span>
                                                                @endif
                                                                @if($item->vocerias->isNotEmpty())
                                                                    <span class="badge bg-warning text-white fs-8">Vocero</span>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="fw-semibold text-dark">{{ $edadItem !== null ? $edadItem . ' años' : 'S/R' }}</span>
                                                            <small class="text-muted d-block">{{ $item->genero ?: 'No registrado' }}</small>
                                                        </td>
                                                        <td>
                                                            <span class="fw-bold text-dark d-block">{{ $ccItem }}</span>
                                                            <small class="text-muted">Fam: {{ $item->familia?->numero_familia ?: 'S/F' }}</small>
                                                        </td>
                                                        <td>
                                                            <span class="text-dark fw-semibold d-block">{{ $item->profesion ?: 'No registrada' }}</span>
                                                            <small class="text-muted">{{ $item->nivel_academico ?: 'N/R' }}</small>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex flex-wrap gap-1">
                                                                @if ($item->pensionado_jubilado === 'Sí')
                                                                    <span class="badge bg-secondary">Pensionado</span>
                                                                @endif
                                                                @if ($item->familia?->mision_vivienda === 'Sí')
                                                                    <span class="badge bg-info">GMVV</span>
                                                                @endif
                                                                @if ($item->familia?->clap === 'Sí')
                                                                    <span class="badge bg-success">CLAP</span>
                                                                @endif
                                                                @if ($item->pensionado_jubilado !== 'Sí' && $item->familia?->mision_vivienda !== 'Sí' && $item->familia?->clap !== 'Sí')
                                                                    <span class="text-muted small">-</span>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td class="text-end">
                                                            <div class="btn-group">
                                                                {{-- Botón para consultar perfil individual directo --}}
                                                                <a href="{{ route('consulta.index') }}?tab=individual&cedula={{ $item->cedula }}&cedula_tipo={{ $item->cedula_tipo }}" 
                                                                    class="btn btn-sm btn-secondary" title="Ver perfil" data-bs-toggle="tooltip">
                                                                    <i class="ri-user-line me-1"></i>
                                                                </a>
                                                                {{-- Botón para abrir modal de familia directo si tiene familia --}}
                                                                @if ($famId)
                                                                    <button type="button" class="btn btn-sm btn-primary" title="Ver núcleo familiar" data-bs-toggle="tooltip" onclick="cargarModalFamilia({{ $famId }})">
                                                                        <i class="ri-team-line"></i>
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Modal para Visualizar Núcleo Familiar en Consulta --}}
            @include('modules.expedientes.modalConsultaFamilia')
        @endif

    </div>

    {{-- Script de interacción y carga dinámica para la Modal Familiar --}}
    <script>
        // Cargar datos de la familia vía AJAX para la modal de consulta
        function cargarModalFamilia(familiaId) {
            $('#modal_fam_numero').text('Cargando...');
            $('#modal_fam_comunidad').text('Cargando...');
            $('#modal_fam_vivienda').text('...');
            $('#modal_fam_gmvv').text('...');
            $('#modal_fam_clap').text('...');
            $('#modal_fam_bono').text('...');
            $('#modal_fam_conteo').text('0');
            $('#modal_fam_integrantes_body').html('<tr><td colspan="7" class="text-center py-4"><div class="spinner-border spinner-border-sm text-primary" role="status"></div><span class="ms-2">Cargando integrantes...</span></td></tr>');

            var modalEl = document.getElementById('modalConsultaFamilia');
            var modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
            modalInstance.show();

            $.ajax({
                url: '/consulta/familia/' + familiaId,
                type: 'GET',
                success: function(data) {
                    $('#modal_fam_numero').text(data.numero_familia);
                    $('#modal_fam_comunidad').text(data.consejo_comunal ? data.consejo_comunal.nombre : 'No asignado');
                    $('#modal_fam_vivienda').text(data.vivienda ?? 'No registrada');
                    $('#modal_fam_gmvv').text(data.mision_vivienda === 'Sí' ? 'Sí (GMVV)' : (data.mision_vivienda ?? 'No'));
                    $('#modal_fam_clap').text(data.clap ?? 'No');
                    $('#modal_fam_bono').text(data.bono_unico_familiar ?? 'No');
                    $('#modal_fam_conteo').text(data.personas ? data.personas.length : 0);

                    var html = '';
                    if (data.personas && data.personas.length > 0) {
                        data.personas.forEach(function(p) {
                            var parentescoBadge = p.parentesco === 'Jefe de familia' 
                                ? '<span class="badge bg-warning text-dark"><i class="ri-home-4-fill me-1"></i>Jefe de Familia</span>' 
                                : '<span class="badge bg-secondary">' + (p.parentesco || 'Miembro') + '</span>';

                            var edadTexto = p.edad !== null ? p.edad + ' años' : 'S/R';

                            html += `<tr>
                                <td class="fw-bold">${p.cedula_tipo}-${p.cedula}</td>
                                <td class="fw-bold text-dark">${p.nombres} ${p.apellidos}</td>
                                <td>${parentescoBadge}</td>
                                <td>${edadTexto} <small class="text-muted">(${p.genero || 'S/R'})</small></td>
                                <td>${p.telefono || 'No registrado'}</td>
                                {{-- <td>${p.profesion || 'No aplica'}</td> --}}
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-primary py-1 px-2" title="Consultar a este ciudadano" onclick="consultarCiudadanoDesdeModal('${p.cedula}', '${p.cedula_tipo}')">
                                        <i class="ri-user-search-line me-1"></i> Consultar
                                    </button>
                                </td>
                            </tr>`;
                        });
                    } else {
                        html = '<tr><td colspan="7" class="text-center py-4 text-muted">No hay integrantes vinculados a esta familia.</td></tr>';
                    }
                    $('#modal_fam_integrantes_body').html(html);
                },
                error: function() {
                    $('#modal_fam_integrantes_body').html('<tr><td colspan="7" class="text-center py-4 text-danger"><i class="ri-error-warning-line me-1"></i> Error al cargar los integrantes de la familia.</td></tr>');
                }
            });
        }

        // Consultar directamente a un familiar desde la modal
        function consultarCiudadanoDesdeModal(cedula, cedulaTipo) {
            var modalEl = document.getElementById('modalConsultaFamilia');
            var modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (modalInstance) {
                modalInstance.hide();
            }

            // Redirigir a la pestaña individual con la cédula del integrante
            window.location.href = "{{ route('consulta.index') }}?tab=individual&cedula=" + cedula + "&cedula_tipo=" + (cedulaTipo || 'V');
        }

        // Utilidad para copiar texto al portapapeles con feedback
        function copiarAlPortapapeles(texto) {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(texto).then(function() {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Copiado: ' + texto,
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                });
            }
        }

        // Validación de formularios Bootstrap
        (function() {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
@endsection
