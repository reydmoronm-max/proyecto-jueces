@extends('layouts.main')
@section('titulo', $titulo)
@section('paginaTitulo', $paginaTitulo)
@section('paginaSubtitulo', $paginaSubtitulo)
@section('proyectosActive', 'active')

@section('contenido')
    <div class="container-fluid content-inner mt-n5 py-0">
        <!-- Tarjetas Estadísticas -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="card bg-primary text-white" style="border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 text-uppercase mb-1 small fw-bold">Total Proyectos</h6>
                                <h3 class="text-white fw-bold mb-0">{{ $totalProyectos }}</h3>
                            </div>
                            <i class="ri-folder-open-fill opacity-50" style="font-size: 2.2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-white"
                    style="border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); background-color: #ffbe0c;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 text-uppercase mb-1 small fw-bold">En Planificación</h6>
                                <h3 class="text-white fw-bold mb-0">{{ $planificadosCount }}</h3>
                            </div>
                            <i class="ri-settings-4-fill opacity-50" style="font-size: 2.2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card bg-success text-white"
                    style="border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 text-uppercase mb-1 small fw-bold">Completados</h6>
                                <h3 class="text-white fw-bold mb-0">{{ $completadosCount }}</h3>
                            </div>
                            <i class="ri-checkbox-circle-fill opacity-50" style="font-size: 2.2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-white"
                    style="border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); background-color: #ff2525;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 text-uppercase mb-1 small fw-bold">Paralizados</h6>
                                <h3 class="text-white fw-bold mb-0">{{ $paralizadosCount }}</h3>
                            </div>
                            <i class="ri-close-circle-fill opacity-50" style="font-size: 2.2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla y Buscador -->
        <div class="row">
            <div class="col-sm-12">
                <div class="card shadow-sm" style="border-radius: 15px;">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="header-title">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalRegistrarProyecto">
                                <i class="ri-add-fill"></i> Registrar Proyecto
                            </button>
                        </div>
                        <div class="d-flex align-items-center">
                            <form action="{{ route('proyectos.index') }}" method="GET" class="d-flex gap-2">
                                <input type="text" name="search" class="form-control"
                                    placeholder="Buscar por nombre, sector, responsable..." value="{{ request('search') }}"
                                    style="min-width: 280px;">
                                <button type="submit" class="btn btn-primary">Buscar</button>
                                @if (request('search'))
                                    <a href="{{ route('proyectos.index') }}" class="btn btn-secondary">Limpiar</a>
                                @endif
                            </form>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive mt-3">
                            <table class="table table-striped align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Nombre del Proyecto</th>
                                        <th>Sector Productivo</th>
                                        <th>Presupuesto</th>
                                        <th>Responsable</th>
                                        <th>Fecha de Inicio</th>
                                        <th>Estatus</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($items as $item)
                                        <tr>
                                            <td class="fw-semibold">{{ $item->nombre }}</td>
                                            <td>{{ $item->sector_productivo }}</td>
                                            <td>
                                                <span class="fw-bold text-dark">Bs.
                                                    {{ number_format($item->presupuesto, 2, ',', '.') }}</span>
                                            </td>
                                            <td>{{ $item->responsable }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->fecha_inicio)->format('d-m-Y') }}</td>
                                            <td>
                                                @if ($item->estatus === 'En planificación')
                                                    <span class="badge bg-warning text-dark"><i
                                                            class="ri-time-line me-1"></i>En planificación</span>
                                                @elseif($item->estatus === 'Completado')
                                                    <span class="badge bg-success"><i
                                                            class="ri-checkbox-circle-line me-1"></i>Completado</span>
                                                @else
                                                    <span class="badge bg-danger"><i
                                                            class="ri-close-circle-line me-1"></i>Paralizado</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div>
                                                    <button type="button" class="btn btn-sm btn-light me-1"
                                                        title="Ver Detalles"
                                                        onclick="consultarProyecto({{ $item->id }})">
                                                        <i class="ri-eye-fill"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-warning me-1"
                                                        title="Editar" onclick="editarProyecto({{ $item->id }})">
                                                        <i class="ri-pencil-fill"></i>
                                                    </button>
                                                    <form action="{{ route('proyectos.destroy', $item->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                            class="btn btn-sm btn-danger btn-eliminar-proyecto"
                                                            title="Eliminar Proyecto">
                                                            <i class="ri-delete-bin-fill"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">No se encontraron
                                                proyectos comunitarios registrados.</td>
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

    <!-- MODALES CRUD PROYECTOS -->

    <!-- Modal Registrar -->
    <div class="modal fade" id="modalRegistrarProyecto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Nuevo Proyecto Comunitario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-2">
                    <form class="needs-validation" novalidate id="formProyecto" action="{{ route('proyectos.store') }}"
                        autocomplete="off" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="text" name="nombre" id="nombre" class="form-control bg-white"
                                        placeholder="Nombre del proyecto" required minlength="3">
                                    <label for="nombre">Nombre del Proyecto</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="sector_productivo" id="sector_productivo"
                                        class="form-control bg-white" placeholder="Sector productivo" required>
                                    <label for="sector_productivo">Sector Productivo</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number" step="0.01" name="presupuesto" id="presupuesto"
                                        class="form-control bg-white" placeholder="Presupuesto" required min="0">
                                    <label for="presupuesto">Presupuesto (Bs.)</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="responsable" id="responsable"
                                        class="form-control bg-white" placeholder="Responsable" required minlength="3">
                                    <label for="responsable">Responsable del Proyecto</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="mb-1 small text-muted" for="fecha_inicio">Fecha de Inicio</label>
                                    <div class="input-group wrap_flatpicker" data-min-date="none">
                                        <input required type="text" name="fecha_inicio" id="fecha_inicio"
                                            class="form-control bg-white" placeholder="dd-mm-aaaa" data-input>
                                        <a class="input-group-text input-button bg-white" title="limpiar" data-clear
                                            href="javascript:void(0)">
                                            <svg width="18" class="icon-18" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea name="descripcion" id="descripcion" class="form-control bg-white" placeholder="Descripción del proyecto"
                                        style="height: 150px;" required></textarea>
                                    <label for="descripcion">Descripción (Metas, objetivos, impacto)</label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer mt-4 pb-0 pe-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Registrar Proyecto</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar -->
    <div class="modal fade" id="modalEditarProyecto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modificar Proyecto Comunitario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-2">
                    <form class="needs-validation" novalidate id="formEditarProyecto" action="" autocomplete="off"
                        method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="text" name="nombre" id="edit-nombre" class="form-control bg-white"
                                        placeholder="Nombre del proyecto" required minlength="3">
                                    <label for="edit-nombre">Nombre del Proyecto</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="sector_productivo" id="edit-sector_productivo"
                                        class="form-control bg-white" placeholder="Sector productivo" required>
                                    <label for="edit-sector_productivo">Sector Productivo</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number" step="0.01" name="presupuesto" id="edit-presupuesto"
                                        class="form-control bg-white" placeholder="Presupuesto" required min="0">
                                    <label for="edit-presupuesto">Presupuesto (Bs.)</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="responsable" id="edit-responsable"
                                        class="form-control bg-white" placeholder="Responsable" required minlength="3">
                                    <label for="edit-responsable">Responsable del Proyecto</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="mb-1 small text-muted" for="edit-fecha_inicio">Fecha de Inicio</label>
                                    <div class="input-group wrap_flatpicker" id="edit-fecha_inicio_container"
                                        data-min-date="none">
                                        <input required type="text" name="fecha_inicio" id="edit-fecha_inicio"
                                            class="form-control bg-white" placeholder="dd-mm-aaaa" data-input>
                                        <a class="input-group-text input-button bg-white" title="limpiar" data-clear
                                            href="javascript:void(0)">
                                            <svg width="18" class="icon-18" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <select name="estatus" id="edit-estatus" class="form-select bg-white" required>
                                        <option value="En planificación">En planificación</option>
                                        <option value="Completado">Completado</option>
                                        <option value="Paralizado">Paralizado</option>
                                    </select>
                                    <label for="edit-estatus">Estatus del Proyecto</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea name="descripcion" id="edit-descripcion" class="form-control bg-white"
                                        placeholder="Descripción del proyecto" style="height: 150px;" required></textarea>
                                    <label for="edit-descripcion">Descripción (Metas, objetivos, impacto)</label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer mt-4 pb-0 pe-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Actualizar Proyecto</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Consultar -->
    <div class="modal fade" id="modalConsultarProyecto" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalles del Proyecto Comunitario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-2">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="small text-muted mb-1">Nombre del Proyecto</label>
                                <input id="view-nombre" type="text" class="form-control bg-light" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="small text-muted mb-1">Sector Productivo</label>
                                <input id="view-sector_productivo" type="text" class="form-control bg-light" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="small text-muted mb-1">Presupuesto</label>
                                <input id="view-presupuesto" type="text" class="form-control bg-light" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="small text-muted mb-1">Responsable</label>
                                <input id="view-responsable" type="text" class="form-control bg-light" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="small text-muted mb-1">Fecha de Inicio</label>
                                <input id="view-fecha_inicio" type="text" class="form-control bg-light" readonly>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="small text-muted mb-1">Estatus</label>
                                <input id="view-estatus" type="text" class="form-control bg-light" readonly>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="small text-muted mb-1">Descripción y Objetivos</label>
                                <textarea id="view-descripcion" class="form-control bg-light" style="height: 150px;" readonly></textarea>
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
        // Inicializar Flatpickrs
        $(document).ready(function() {
            $('.wrap_flatpicker').each(function() {
                flatpickr($(this).find('input')[0], {
                    dateFormat: "d-m-Y",
                    allowInput: true
                });
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

        // Consultar Proyecto AJAX
        function consultarProyecto(id) {
            $.ajax({
                url: '/proyectos/' + id,
                type: 'GET',
                success: function(data) {
                    $('#view-nombre').val(data.nombre ?? '');
                    $('#view-sector_productivo').val(data.sector_productivo ?? '');
                    $('#view-presupuesto').val('Bs. ' + data.presupuesto_formateado);
                    $('#view-responsable').val(data.responsable ?? '');
                    $('#view-fecha_inicio').val(data.fecha_inicio_formateada ?? '');
                    $('#view-estatus').val(data.estatus ?? '');
                    $('#view-descripcion').val(data.descripcion ?? 'Sin descripción.');

                    var modal = new bootstrap.Modal(document.getElementById('modalConsultarProyecto'));
                    modal.show();
                },
                error: function() {
                    Swal.fire('Error', 'No se pudo cargar la información del proyecto.', 'error');
                }
            });
        }

        // Editar Proyecto AJAX
        function editarProyecto(id) {
            $.ajax({
                url: '/proyectos/' + id + '/edit',
                type: 'GET',
                success: function(data) {
                    $('#formEditarProyecto').attr('action', '/proyectos/' + data.id);
                    $('#edit-nombre').val(data.nombre ?? '');
                    $('#edit-sector_productivo').val(data.sector_productivo ?? '');
                    $('#edit-presupuesto').val(data.presupuesto ?? '');
                    $('#edit-responsable').val(data.responsable ?? '');
                    $('#edit-estatus').val(data.estatus ?? 'En planificación');
                    $('#edit-descripcion').val(data.descripcion ?? '');

                    // Sincronizar flatpickr de edición
                    var fp = document.getElementById('edit-fecha_inicio_container')._flatpickr;
                    if (fp) {
                        fp.setDate(data.fecha_inicio_formateada);
                    } else {
                        $('#edit-fecha_inicio').val(data.fecha_inicio_formateada);
                    }

                    var modal = new bootstrap.Modal(document.getElementById('modalEditarProyecto'));
                    modal.show();
                },
                error: function() {
                    Swal.fire('Error', 'No se pudo cargar la información del proyecto.', 'error');
                }
            });
        }

        // Eliminar Proyecto SweetAlert
        $(document).on('click', '.btn-eliminar-proyecto', function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción eliminará de forma permanente el registro del proyecto comunitario.",
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
