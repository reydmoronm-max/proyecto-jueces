@extends('layouts.main')
@section('titulo', $titulo)
@section('paginaTitulo', $paginaTitulo)
@section('paginaSubtitulo', $paginaSubtitulo)
@section('categoriaVoceriasActive', $categoriaVoceriasActive)

@section('contenido')
    <div class="container-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="header-title">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalRegistrarCategoria">
                                <i class="ri-add-fill"></i> Registrar Categoría
                            </button>
                        </div>
                        <div class="d-flex align-items-center">
                            <form action="{{ route('categoria-vocerias.index') }}" method="GET" class="d-flex align-items-end flex-wrap gap-2">
                                <div>
                                    <label for="estado_categoria" class="form-label small text-muted fw-bold mb-1">Categoría Activa/Inactiva</label>
                                    <select name="estado_categoria" id="estado_categoria" class="form-select">
                                        <option value="">Todas las categorías</option>
                                        <option value="1" {{ $estadoCategoria === '1' ? 'selected' : '' }}>Activas</option>
                                        <option value="0" {{ $estadoCategoria === '0' ? 'selected' : '' }}>Inactivas</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="search" class="form-label small text-muted fw-bold mb-1">Buscar categoría</label>
                                <input type="text" name="search" class="form-control"
                                    placeholder="Buscar por nombre o descripción..." value="{{ request('search') }}"
                                    style="min-width: 280px;">
                                </div>
                                <button type="submit" class="btn btn-primary">Buscar</button>
                                @if ($search !== '' || $estadoCategoria !== '')
                                    <a href="{{ route('categoria-vocerias.index') }}" class="btn btn-secondary">Limpiar</a>
                                @endif
                            </form>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive mt-4">
                            <table id="basic-table" class="table table-striped mb-0" role="grid">
                                <thead>
                                    <tr>
                                        <th>Categoría</th>
                                        <th>Descripción</th>
                                        <th>Activo</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($items as $item)
                                        <tr>
                                            <td class="fw-bold">{{ $item->nombre }}</td>
                                            <td>{{ $item->descripcion ?? 'Sin descripción' }}</td>
                                            <td>
                                                <div class="form-check form-switch form-check-inline">
                                                    <input class="form-check-input estado-categoria" type="checkbox"
                                                        id="cat-{{ $item->id }}" data-id="{{ $item->id }}"
                                                        {{ $item->activo ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <button type="button" class="btn btn-sm btn-warning" title="Editar"
                                                        onclick="editarCategoria({{ $item->id }})">
                                                        <i class="ri-pencil-fill"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4">No se encontraron categorías registradas.</td>
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

    @include('modules.categoria_vocerias.modalCategoria')
    @include('modules.categoria_vocerias.modalEditarCategoria')

@endsection

@push('scripts')
    <script>
        // SweetAlert notification handlers
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

        // Edit category AJAX handler
        function editarCategoria(id) {
            $.ajax({
                url: '/categoria-vocerias/' + id + '/edit',
                type: 'GET',
                success: function(data) {
                    $('#formEditarCategoria').attr('action', '/categoria-vocerias/' + data.id);
                    $('#edit-nombre').val(data.nombre);
                    $('#edit-descripcion').val(data.descripcion ?? '');

                    var modal = new bootstrap.Modal(document.getElementById('modalEditarCategoria'));
                    modal.show();
                },
                error: function() {
                    Swal.fire({
                        title: 'Error',
                        text: 'No se pudo cargar la información de la categoría.',
                        icon: 'error'
                    });
                }
            });
        }

        // Toggle state AJAX handler
        $(document).on('change', '.estado-categoria', function() {
            var checkbox = $(this);
            var estado = checkbox.is(':checked') ? 1 : 0;
            var id = checkbox.data('id');

            $.ajax({
                type: 'GET',
                url: '{{ url('/categoria-vocerias/cambiar-estado') }}/' + id + '/' + estado,
                error: function() {
                    checkbox.prop('checked', !estado);
                    Swal.fire({
                        title: 'Error',
                        text: 'No se pudo actualizar el estado de la categoría.',
                        icon: 'error'
                    });
                }
            });
        });

        // Delete confirmation handler
        $(document).on('click', '.btn-eliminar-cat', function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            var nombre = $(this).data('nombre');

            Swal.fire({
                title: '¿Está seguro?',
                text: 'Desea eliminar la categoría "' + nombre + '". Esta acción no se puede deshacer.',
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

        // Form validations
        $(document).ready(function() {
            $('#formCategoria').on('submit', function(e) {
                var nombre = $('#nombre').val().trim();
                if (nombre.length < 3) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Error de validación',
                        text: 'El nombre de la categoría debe tener al menos 3 caracteres.',
                        icon: 'error'
                    });
                    return false;
                }
            });

            $('#formEditarCategoria').on('submit', function(e) {
                var nombre = $('#edit-nombre').val().trim();
                if (nombre.length < 3) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Error de validación',
                        text: 'El nombre de la categoría debe tener al menos 3 caracteres.',
                        icon: 'error'
                    });
                    return false;
                }
            });
        });
    </script>
@endpush
