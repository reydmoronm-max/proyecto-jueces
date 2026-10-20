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
                                    <label for="search" class="form-label small text-muted fw-bold mb-1">Buscar categoría</label>
                                <input type="text" name="search" class="form-control"
                                    placeholder="Buscar por nombre o descripción..." value="{{ request('search') }}"
                                    style="min-width: 280px;">
                                </div>
                                <button type="submit" class="btn btn-primary">Buscar</button>
                                @if ($search !== '')
                                    <a href="{{ route('categoria-vocerias.index') }}" class="btn btn-secondary">Limpiar</a>
                                @endif
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-fill mb-4 border-bottom-0" id="categoriasTabs" role="tablist"
                            style="background: #f8f9fa; padding: 6px; border-radius: 10px;">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active py-2 fw-semibold" id="categorias-activas-tab"
                                    data-bs-toggle="tab" data-bs-target="#categorias-activas-content" type="button"
                                    role="tab" aria-controls="categorias-activas-content" aria-selected="true"
                                    style="border-radius: 8px;">Categorias Activas</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link py-2 fw-semibold" id="categorias-inactivas-tab"
                                    data-bs-toggle="tab" data-bs-target="#categorias-inactivas-content" type="button"
                                    role="tab" aria-controls="categorias-inactivas-content" aria-selected="false"
                                    style="border-radius: 8px;">Categorias Inactivas</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="categoriasTabsContent">
                            <div class="tab-pane fade show active" id="categorias-activas-content" role="tabpanel"
                                aria-labelledby="categorias-activas-tab">
                                <div class="table-responsive">
                                    <table id="basic-table" class="table table-striped mb-0" role="grid">
                                        <thead>
                                            <tr>
                                                <th>Categoría</th>
                                                <th>Descripción</th>
                                                <th>Activo</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody-categorias-activas">
                                            @if ($categoriasActivas->isNotEmpty())
                                                @include('modules.categoria_vocerias.tbody', ['items' => $categoriasActivas])
                                            @else
                                                <tr class="empty-categorias-row">
                                                    <td colspan="4" class="text-center py-4">No se encontraron categorías activas.</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="categorias-inactivas-content" role="tabpanel"
                                aria-labelledby="categorias-inactivas-tab">
                                <div class="table-responsive">
                                    <table id="tabla-categorias-inactivas" class="table table-striped mb-0" role="grid">
                                        <thead>
                                            <tr>
                                                <th>Categoría</th>
                                                <th>Descripción</th>
                                                <th>Activo</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody-categorias-inactivas">
                                            @if ($categoriasInactivas->isNotEmpty())
                                                @include('modules.categoria_vocerias.tbody', ['items' => $categoriasInactivas])
                                            @else
                                                <tr class="empty-categorias-row">
                                                    <td colspan="4" class="text-center py-4">No se encontraron categorías inactivas.</td>
                                                </tr>
                                            @endif
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

        function actualizarFilaCategoriaVacia(tbody, mensaje) {
            var tieneCategorias = $(tbody).find('tr').not('.empty-categorias-row').length > 0;
            $(tbody).find('.empty-categorias-row').remove();

            if (!tieneCategorias) {
                $(tbody).append(
                    '<tr class="empty-categorias-row"><td colspan="4" class="text-center py-4">' + mensaje + '</td></tr>'
                );
            }
        }

        function moverCategoriaDeSeccion(id, estado) {
            var fila = $('#cat-' + id).closest('tr');
            var listaOrigen = estado ? '#tbody-categorias-inactivas' : '#tbody-categorias-activas';
            var listaDestino = estado ? '#tbody-categorias-activas' : '#tbody-categorias-inactivas';
            var mensajeOrigen = estado ?
                'No se encontraron categorías inactivas.' :
                'No se encontraron categorías activas.';
            var mensajeDestino = estado ?
                'No se encontraron categorías activas.' :
                'No se encontraron categorías inactivas.';

            $(listaDestino).find('.empty-categorias-row').remove();
            $(listaDestino).append(fila);
            actualizarFilaCategoriaVacia(listaOrigen, mensajeOrigen);

            return {
                fila: fila,
                listaOrigen: listaOrigen,
                listaDestino: listaDestino,
                mensajeDestino: mensajeDestino
            };
        }

        // Toggle state AJAX handler
        $(document).on('change', '.estado-categoria', function() {
            var checkbox = $(this);
            var estado = checkbox.is(':checked') ? 1 : 0;
            var id = checkbox.data('id');
            var movimiento = moverCategoriaDeSeccion(id, estado);

            $.ajax({
                type: 'PATCH',
                url: '{{ url('/categoria-vocerias/cambiar-estado') }}/' + id + '/' + estado,
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function() {
                    var tab = estado ? '#categorias-activas-tab' : '#categorias-inactivas-tab';
                    bootstrap.Tab.getOrCreateInstance(document.querySelector(tab)).show();
                },
                error: function() {
                    $(movimiento.listaOrigen).find('.empty-categorias-row').remove();
                    $(movimiento.listaOrigen).append(movimiento.fila);
                    actualizarFilaCategoriaVacia(movimiento.listaDestino, movimiento.mensajeDestino);
                    $('#cat-' + id).prop('checked', !estado);
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
