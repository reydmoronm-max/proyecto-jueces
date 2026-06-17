@extends('layouts.main')
@section('titulo', $titulo)
@section('paginaTitulo', $paginaTitulo)
@section('paginaSubtitulo', $paginaSubtitulo)
@section('circuloActive', 'active')

@section('contenido')
    <div class="container-fluid content-inner mt-n5 py-0">
        <!-- Tarjetas Estadísticas -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white" style="border-radius: 15px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.15);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 text-uppercase mb-1 small fw-bold">Abuelos Registrados</h6>
                                <h2 class="text-white fw-bold mb-0">{{ $totalAbuelos }}</h2>
                            </div>
                            <div class="bg-white-10 p-3 rounded-circle d-flex align-items-center justify-content-center" style="background: rgba(255,255,255,0.15); width: 60px; height: 60px;">
                                <i class="ri-heart-pulse-fill text-white" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white" style="border-radius: 15px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.15);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 text-uppercase mb-1 small fw-bold">Jornadas Realizadas</h6>
                                <h2 class="text-white fw-bold mb-0">{{ $jornadasRealizadas }}</h2>
                            </div>
                            <div class="bg-white-10 p-3 rounded-circle d-flex align-items-center justify-content-center" style="background: rgba(255,255,255,0.15); width: 60px; height: 60px;">
                                <i class="ri-checkbox-circle-fill text-white" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-white" style="border-radius: 15px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.15);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 text-uppercase mb-1 small fw-bold">Jornadas Agendadas</h6>
                                <h2 class="text-white fw-bold mb-0">{{ $jornadasAgendadas }}</h2>
                            </div>
                            <div class="bg-white-10 p-3 rounded-circle d-flex align-items-center justify-content-center" style="background: rgba(255,255,255,0.15); width: 60px; height: 60px;">
                                <i class="ri-calendar-event-fill text-white" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card shadow-sm" style="border-radius: 15px;">
                    <div class="card-body p-4">
                        <!-- Pestañas (Tabs) -->
                        <ul class="nav nav-tabs nav-fill mb-4 border-bottom-0" id="abuelosTabs" role="tablist" style="background: #f8f9fa; padding: 6px; border-radius: 10px;">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active py-2 fw-semibold d-flex align-items-center justify-content-center gap-2" id="abuelitos-tab" data-bs-toggle="tab" data-bs-target="#abuelitos-content" type="button" role="tab" aria-controls="abuelitos-content" aria-selected="true" style="border-radius: 8px;">
                                    <i class="ri-team-fill" style="font-size: 1.15rem;"></i> Listado de Abuelos
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link py-2 fw-semibold d-flex align-items-center justify-content-center gap-2" id="jornadas-tab" data-bs-toggle="tab" data-bs-target="#jornadas-content" type="button" role="tab" aria-controls="jornadas-content" aria-selected="false" style="border-radius: 8px;">
                                    <i class="ri-calendar-todo-fill" style="font-size: 1.15rem;"></i> Planificación de Jornadas
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="abuelosTabsContent">
                            
                            <!-- TAB DE ABUELOS -->
                            <div class="tab-pane fade show active" id="abuelitos-content" role="tabpanel" aria-labelledby="abuelitos-tab">
                                <!-- Filtros y Búsqueda -->
                                <div class="row g-3 align-items-end mb-4">
                                    <div class="col-md-8">
                                        <form action="{{ route('circulo-abuelos.index') }}" method="GET" class="row g-2">
                                            <div class="col-md-5">
                                                <input type="text" name="search" class="form-control" placeholder="Buscar por cédula o nombre..." value="{{ request('search') }}">
                                            </div>
                                            <div class="col-md-4">
                                                <select name="consejo_comunal_id" class="form-select">
                                                    <option value="">Todas las comunidades</option>
                                                    @foreach($consejosComunales as $cc)
                                                        <option value="{{ $cc->id }}" {{ request('consejo_comunal_id') == $cc->id ? 'selected' : '' }}>{{ $cc->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3 d-flex gap-1">
                                                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                                                @if (request('search') || request('consejo_comunal_id'))
                                                    <a href="{{ route('circulo-abuelos.index') }}" class="btn btn-secondary" title="Limpiar Filtros"><i class="ri-refresh-line"></i></a>
                                                @endif
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <span class="text-muted fw-bold">Total Encontrados: <span class="badge bg-secondary">{{ $abuelos->count() }}</span></span>
                                    </div>
                                </div>

                                <!-- Tabla de Abuelitos -->
                                <div class="table-responsive">
                                    <table class="table table-striped align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>Cédula</th>
                                                <th>Nombre y Apellido</th>
                                                <th>Edad</th>
                                                <th>Género</th>
                                                <th>Enfermedad</th>
                                                <th>Comunidad</th>
                                                <th>Dirección</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($abuelos as $ab)
                                                <tr>
                                                    <td>
                                                        <span class="badge bg-primary me-1">{{ $ab->cedula_tipo ?? 'V' }}</span>
                                                        {{ $ab->cedula }}
                                                    </td>
                                                    <td class="fw-semibold">{{ $ab->nombres }} {{ $ab->apellidos }}</td>
                                                    <td>
                                                        <span class="badge bg-info">{{ $ab->edad }} años</span>
                                                    </td>
                                                    <td>{{ $ab->genero ?? 'No registrado' }}</td>
                                                    <td>
                                                        @if($ab->tipo_enfermedad)
                                                            <span class="badge bg-danger-subtle text-danger">{{ $ab->tipo_enfermedad }}</span>
                                                        @else
                                                            <span class="text-muted small">Ninguna</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $ab->consejoComunal->nombre ?? 'Sin vincular' }}</td>
                                                    <td class="text-truncate" style="max-width: 200px;" title="{{ $ab->direccion }}">{{ $ab->direccion ?? 'No registrada' }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-4 text-muted">No se encontraron adultos mayores censados (mayores o iguales a 60 años).</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- TAB DE JORNADAS -->
                            <div class="tab-pane fade" id="jornadas-content" role="tabpanel" aria-labelledby="jornadas-tab">
                                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalRegistrarJornada">
                                        <i class="ri-add-fill"></i> Planificar Jornada de Atención
                                    </button>
                                    <span class="text-muted fw-bold">Jornadas Registradas: <span class="badge bg-secondary">{{ $jornadas->count() }}</span></span>
                                </div>

                                <!-- Tabla de Jornadas -->
                                <div class="table-responsive">
                                    <table class="table table-striped align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>Nombre de Jornada</th>
                                                <th>Fecha Programada</th>
                                                <th>Comunidad / Consejo Comunal</th>
                                                <th>Estatus</th>
                                                <th class="text-end">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($jornadas as $jo)
                                                <tr>
                                                    <td class="fw-semibold">{{ $jo->nombre_jornada }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($jo->fecha_programada)->format('d-m-Y') }}</td>
                                                    <td>{{ $jo->consejoComunal->nombre ?? 'Sin especificar' }}</td>
                                                    <td>
                                                        @if($jo->estatus === 'Planificada')
                                                            <span class="badge bg-warning text-dark"><i class="ri-time-line me-1"></i>Planificada</span>
                                                        @elseif($jo->estatus === 'Completada')
                                                            <span class="badge bg-success"><i class="ri-checkbox-circle-line me-1"></i>Completada</span>
                                                        @else
                                                            <span class="badge bg-danger"><i class="ri-close-circle-line me-1"></i>Suspendida</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-end">
                                                        <div>
                                                            <button type="button" class="btn btn-sm btn-light me-1" title="Ver Detalles" onclick="consultarJornada({{ $jo->id }})">
                                                                <i class="ri-eye-fill"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-warning me-1" title="Editar" onclick="editarJornada({{ $jo->id }})">
                                                                <i class="ri-pencil-fill"></i>
                                                            </button>
                                                            <form action="{{ route('circulo-abuelos.destroy-jornada', $jo->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button" class="btn btn-sm btn-danger btn-eliminar-jornada" title="Eliminar Jornada">
                                                                    <i class="ri-delete-bin-fill"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-4 text-muted">No hay jornadas de atención planificadas.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODALES DE JORNADAS -->

    <!-- Modal Registrar -->
    <div class="modal fade" id="modalRegistrarJornada" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Planificar Nueva Jornada</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-2">
                    <form class="needs-validation" novalidate id="formJornada" action="{{ route('circulo-abuelos.store-jornada') }}" autocomplete="off" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" name="nombre_jornada" id="nombre_jornada" class="form-control bg-white" placeholder="Nombre de Jornada" required minlength="3">
                                    <label for="nombre_jornada">Nombre de la Jornada</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="mb-1 small text-muted" for="fecha_programada">Fecha programada</label>
                                    <div class="input-group wrap_flatpicker" data-min-date="today">
                                        <input required type="text" name="fecha_programada" id="fecha_programada" class="form-control bg-white" placeholder="dd-mm-aaaa" data-input>
                                        <a class="input-group-text input-button bg-white" title="limpiar" data-clear href="javascript:void(0)">
                                            <svg width="18" class="icon-18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <select name="consejo_comunal_id" id="consejo_comunal_id" class="form-select bg-white" required>
                                        <option value="" selected disabled>Seleccione...</option>
                                        @foreach($consejosComunales as $cc)
                                            <option value="{{ $cc->id }}">{{ $cc->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <label for="consejo_comunal_id">Comunidad (Consejo Comunal)</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea name="detalles" id="detalles" class="form-control bg-white" placeholder="Detalles de la actividad" style="height: 120px;"></textarea>
                                    <label for="detalles">Detalles o Metas de la Jornada</label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer mt-4 pb-0 pe-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Registrar Jornada</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar -->
    <div class="modal fade" id="modalEditarJornada" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modificar Jornada</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-2">
                    <form class="needs-validation" novalidate id="formEditarJornada" action="" autocomplete="off" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" name="nombre_jornada" id="edit-nombre_jornada" class="form-control bg-white" placeholder="Nombre de Jornada" required minlength="3">
                                    <label for="edit-nombre_jornada">Nombre de la Jornada</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="mb-1 small text-muted" for="edit-fecha_programada">Fecha programada</label>
                                    <div class="input-group wrap_flatpicker" id="edit-fecha_programada_container" data-min-date="none">
                                        <input required type="text" name="fecha_programada" id="edit-fecha_programada" class="form-control bg-white" placeholder="dd-mm-aaaa" data-input>
                                        <a class="input-group-text input-button bg-white" title="limpiar" data-clear href="javascript:void(0)">
                                            <svg width="18" class="icon-18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <select name="consejo_comunal_id" id="edit-consejo_comunal_id" class="form-select bg-white" required>
                                        <option value="" selected disabled>Seleccione...</option>
                                        @foreach($consejosComunales as $cc)
                                            <option value="{{ $cc->id }}">{{ $cc->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <label for="edit-consejo_comunal_id">Comunidad (Consejo Comunal)</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <select name="estatus" id="edit-estatus" class="form-select bg-white" required>
                                        <option value="Planificada">Planificada</option>
                                        <option value="Completada">Completada</option>
                                        <option value="Suspendida">Suspendida</option>
                                    </select>
                                    <label for="edit-estatus">Estatus de Ejecución</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea name="detalles" id="edit-detalles" class="form-control bg-white" placeholder="Detalles de la actividad" style="height: 120px;"></textarea>
                                    <label for="edit-detalles">Detalles o Metas de la Jornada</label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer mt-4 pb-0 pe-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Actualizar Jornada</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Consultar -->
    <div class="modal fade" id="modalConsultarJornada" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalles de la Jornada</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-2">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="small text-muted mb-1">Nombre de la Jornada</label>
                                <input id="view-nombre_jornada" type="text" class="form-control bg-light" readonly>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="small text-muted mb-1">Fecha Programada</label>
                                <input id="view-fecha_programada" type="text" class="form-control bg-light" readonly>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="small text-muted mb-1">Estatus</label>
                                <input id="view-estatus" type="text" class="form-control bg-light" readonly>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="small text-muted mb-1">Comunidad</label>
                                <input id="view-comunidad" type="text" class="form-control bg-light" readonly>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="small text-muted mb-1">Detalles de la Actividad</label>
                                <textarea id="view-detalles" class="form-control bg-light" style="height: 120px;" readonly></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Sincronizar Tab activa desde parámetro URL
        $(document).ready(function() {
            var urlParams = new URLSearchParams(window.location.search);
            var tab = urlParams.get('tab');
            if (tab === 'jornadas') {
                var triggerEl = document.querySelector('#jornadas-tab');
                if (triggerEl) {
                    var tabObj = new bootstrap.Tab(triggerEl);
                    tabObj.show();
                }
            }

            // Inicializar Flatpickrs si es necesario
            $('.wrap_flatpicker').each(function() {
                var min = $(this).data('min-date');
                var config = {
                    dateFormat: "d-m-Y",
                    allowInput: true,
                };
                if (min === 'today') {
                    config.minDate = "today";
                }
                flatpickr($(this).find('input')[0], config);
            });
        });

        // SweetAlert message displays
        @if (session('success'))
            Swal.fire({
                title: '¡Éxito!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                title: 'Errores en el formulario',
                html: '{!! implode('<br>', $errors->all()) !!}',
                icon: 'error',
                confirmButtonText: 'Corregir'
            });
        @endif

        // Consultar Jornada AJAX
        function consultarJornada(id) {
            $.ajax({
                url: '/circulo-abuelos/jornada/' + id,
                type: 'GET',
                success: function(data) {
                    $('#view-nombre_jornada').val(data.nombre_jornada ?? '');
                    $('#view-fecha_programada').val(data.fecha_programada_formateada ?? '');
                    $('#view-estatus').val(data.estatus ?? '');
                    $('#view-comunidad').val(data.consejo_comunal ? data.consejo_comunal.nombre : 'No especificada');
                    $('#view-detalles').val(data.detalles ?? 'Sin detalles registrados.');

                    var modal = new bootstrap.Modal(document.getElementById('modalConsultarJornada'));
                    modal.show();
                },
                error: function() {
                    Swal.fire('Error', 'No se pudo cargar la información de la jornada.', 'error');
                }
            });
        }

        // Editar Jornada AJAX
        function editarJornada(id) {
            $.ajax({
                url: '/circulo-abuelos/jornada/' + id + '/edit',
                type: 'GET',
                success: function(data) {
                    $('#formEditarJornada').attr('action', '/circulo-abuelos/jornada/' + data.id);
                    $('#edit-nombre_jornada').val(data.nombre_jornada ?? '');
                    $('#edit-consejo_comunal_id').val(data.consejo_comunal_id ?? '');
                    $('#edit-estatus').val(data.estatus ?? 'Planificada');
                    $('#edit-detalles').val(data.detalles ?? '');

                    // Sincronizar flatpickr de edición
                    var fp = document.getElementById('edit-fecha_programada_container')._flatpickr;
                    if (fp) {
                        fp.setDate(data.fecha_programada_formateada);
                    } else {
                        $('#edit-fecha_programada').val(data.fecha_programada_formateada);
                    }

                    var modal = new bootstrap.Modal(document.getElementById('modalEditarJornada'));
                    modal.show();
                },
                error: function() {
                    Swal.fire('Error', 'No se pudo cargar la información de la jornada.', 'error');
                }
            });
        }

        // Eliminar Jornada SweetAlert
        $(document).on('click', '.btn-eliminar-jornada', function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción eliminará de forma permanente la planificación de esta jornada.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
@endpush
