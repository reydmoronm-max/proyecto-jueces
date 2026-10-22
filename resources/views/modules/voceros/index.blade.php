@extends('layouts.main')
@section('titulo', $titulo)
@section('paginaTitulo', $paginaTitulo)
@section('paginaSubtitulo', $paginaSubtitulo)
@section('vocerosActive', $vocerosActive)

@section('contenido')
    <div class="container-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="header-title">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalRegistrarVocero">
                                <i class="ri-add-fill"></i> Registrar Vocero
                            </button>
                        </div>
                        <div class="d-flex flex-grow-1 justify-content-end">
                            <div class="input-group w-100 shadow-sm rounded-3" style="max-width: 360px;">
                                <label for="buscar-vocero" class="visually-hidden">Buscar voceros</label>
                                <span class="input-group-text bg-primary text-white border border-dark border-end-0 rounded-start-3">
                                    <i class="ri-search-line"></i>
                                </span>
                                <input type="search" id="buscar-vocero"
                                    class="form-control border-start-0 ps-2 rounded-end-3"
                                    placeholder="Buscar en todos los registros..."
                                    autocomplete="off" aria-label="Buscar voceros en todos sus datos">
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-fill mb-4 border-bottom-0" id="vocerosTabs" role="tablist"
                            style="background: #f8f9fa; padding: 6px; border-radius: 10px;">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active py-2 fw-semibold" id="voceros-activos-tab"
                                    data-bs-toggle="tab" data-bs-target="#voceros-activos-content" type="button"
                                    role="tab" aria-controls="voceros-activos-content" aria-selected="true"
                                    style="border-radius: 8px;">Voceros Activos</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link py-2 fw-semibold" id="voceros-inactivos-tab"
                                    data-bs-toggle="tab" data-bs-target="#voceros-inactivos-content" type="button"
                                    role="tab" aria-controls="voceros-inactivos-content" aria-selected="false"
                                    style="border-radius: 8px;">Voceros Inactivos</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="vocerosTabsContent">
                            <div class="tab-pane fade show active" id="voceros-activos-content" role="tabpanel"
                                aria-labelledby="voceros-activos-tab">
                                <div class="table-responsive">
                                    <table id="basic-table" class="table table-striped mb-0" role="grid">
                                        <thead>
                                            <tr>
                                                <th>Cédula</th>
                                                <th>Nombre y Apellido</th>
                                                <th>Categoría</th>
                                                <th>Fecha de elección</th>
                                                <th>Activo</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody-voceros-activos">
                                            @if ($vocerosActivos->isNotEmpty())
                                                @include('modules.voceros.tbody', ['items' => $vocerosActivos])
                                            @else
                                                <tr class="empty-voceros-row">
                                                    <td colspan="6" class="text-center py-4">No se encontraron voceros activos.</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="voceros-inactivos-content" role="tabpanel"
                                aria-labelledby="voceros-inactivos-tab">
                                <div class="table-responsive">
                                    <table id="tabla-voceros-inactivos" class="table table-striped mb-0" role="grid">
                                        <thead>
                                            <tr>
                                                <th>Cédula</th>
                                                <th>Nombre y Apellido</th>
                                                <th>Categoría</th>
                                                <th>Fecha de elección</th>
                                                <th>Activo</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody-voceros-inactivos">
                                            @if ($vocerosInactivos->isNotEmpty())
                                                @include('modules.voceros.tbody', ['items' => $vocerosInactivos])
                                            @else
                                                <tr class="empty-voceros-row">
                                                    <td colspan="6" class="text-center py-4">No se encontraron voceros inactivos.</td>
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

    @include('modules.voceros.modalVocero')
    @include('modules.voceros.modalEditarVocero')
    @include('modules.voceros.modalConsultarVocero')

@endsection

@push('scripts')
    <script>
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

        // AJAX search for Person globally by Cédula (Create Modal)
        function buscarPersonaEnVoceros() {
            var cedula = $('#cedula').val().trim();
            if (!/^\d{7,8}$/.test(cedula)) {
                return;
            }

            $.ajax({
                url: '{{ route('voceros.buscar-persona') }}',
                method: 'GET',
                data: {
                    cedula: cedula
                },
                success: function(data) {
                    $('#nombres').val(data.nombres).prop('readonly', true);
                    $('#apellidos').val(data.apellidos).prop('readonly', true);
                },
                error: function(xhr) {
                    if (xhr.status === 404) {
                        $('#nombres').val('').prop('readonly', false);
                        $('#apellidos').val('').prop('readonly', false);
                    }
                }
            });
        }

        $('#cedula').on('blur', function() {
            buscarPersonaEnVoceros();
        });

        // AJAX search for Person globally by Cédula (Edit Modal)
        function buscarPersonaEnVocerosEdit() {
            var cedula = $('#edit-cedula').val().trim();
            if (!/^\d{7,8}$/.test(cedula)) {
                return;
            }

            $.ajax({
                url: '{{ route('voceros.buscar-persona') }}',
                method: 'GET',
                data: {
                    cedula: cedula
                },
                success: function(data) {
                    $('#edit-nombres').val(data.nombres).prop('readonly', true);
                    $('#edit-apellidos').val(data.apellidos).prop('readonly', true);
                },
                error: function(xhr) {
                    if (xhr.status === 404) {
                        $('#edit-nombres').prop('readonly', false);
                        $('#edit-apellidos').prop('readonly', false);
                    }
                }
            });
        }

        $('#edit-cedula').on('blur', function() {
            buscarPersonaEnVocerosEdit();
        });

        // Consult Vocero Details (Read-only Modal)
        function consultarVocero(id) {
            $.ajax({
                url: '/voceros/' + id,
                type: 'GET',
                success: function(data) {
                    $('#view-cedula').val(data.persona?.cedula ?? '');
                    $('#view-nombres').val(data.persona?.nombres ?? '');
                    $('#view-apellidos').val(data.persona?.apellidos ?? '');
                    $('#view-categoria_vocero').val(data.categoria_vocero ?? '');
                    $('#view-fecha_eleccion').val(data.fecha_eleccion_formateada ?? '');

                    var modal = new bootstrap.Modal(document.getElementById('modalConsultarVocero'));
                    modal.show();
                },
                error: function() {
                    Swal.fire({
                        title: 'Error',
                        text: 'No se pudo cargar la información del vocero.',
                        icon: 'error'
                    });
                }
            });
        }

        // Edit Vocero Details (Edit Modal)
        function editarVocero(id) {
            $.ajax({
                url: '/voceros/' + id + '/edit',
                type: 'GET',
                success: function(data) {
                    $('#formEditarVocero').attr('action', '/voceros/' + data.id);
                    $('#edit-cedula').val(data.persona?.cedula ?? '');
                    $('#edit-nombres').val(data.persona?.nombres ?? '');
                    $('#edit-apellidos').val(data.persona?.apellidos ?? '');
                    $('#edit-categoria_vocero').val(data.categoria_vocero ?? '');

                    // Set flatpickr date
                    var fp = document.getElementById('edit-fecha_eleccion_container')._flatpickr;
                    if (fp) {
                        fp.setDate(data.fecha_eleccion_formateada);
                    } else {
                        $('#edit-fecha_eleccion').val(data.fecha_eleccion_formateada);
                    }

                    // Check if names should be readonly based on existence of persona
                    if (data.persona) {
                        $('#edit-nombres').prop('readonly', true);
                        $('#edit-apellidos').prop('readonly', true);
                    } else {
                        $('#edit-nombres').prop('readonly', false);
                        $('#edit-apellidos').prop('readonly', false);
                    }

                    var modal = new bootstrap.Modal(document.getElementById('modalEditarVocero'));
                    modal.show();
                },
                error: function() {
                    Swal.fire({
                        title: 'Error',
                        text: 'No se pudo cargar la información del vocero.',
                        icon: 'error'
                    });
                }
            });
        }

        function actualizarFilaVacia(tbody, mensaje) {
            var tieneVoceros = $(tbody).find('tr[data-search]').length > 0;
            $(tbody).find('.empty-voceros-row').remove();

            if (!tieneVoceros) {
                $(tbody).append(
                    '<tr class="empty-voceros-row"><td colspan="6" class="text-center py-4">' + mensaje + '</td></tr>'
                );
            }

            filtrarVoceros();
        }

        function normalizarTextoVocero(texto) {
            return texto
                .toLocaleLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '');
        }

        function filtrarVoceros() {
            var busqueda = normalizarTextoVocero($('#buscar-vocero').val().trim());

            ['#tbody-voceros-activos', '#tbody-voceros-inactivos'].forEach(function(selector) {
                var tbody = $(selector);
                var filas = tbody.find('tr[data-search]');
                var resultados = 0;

                filas.each(function() {
                    var textoBusqueda = $(this).attr('data-search') + ' ' + $(this).attr('data-status');
                    var coincide = normalizarTextoVocero(textoBusqueda).includes(busqueda);
                    $(this).toggle(coincide);

                    if (coincide) {
                        resultados++;
                    }
                });

                tbody.find('.empty-voceros-row').toggle(!busqueda && filas.length === 0);
                tbody.find('.no-search-results-row').remove();

                if (busqueda && resultados === 0) {
                    tbody.append(
                        '<tr class="no-search-results-row"><td colspan="6" class="text-center py-4">No se encontraron voceros que coincidan con la búsqueda.</td></tr>'
                    );
                }
            });
        }

        function moverVoceroDeSeccion(id, estado) {
            var fila = $('#vocero-' + id).closest('tr');
            var listaOrigen = estado ? '#tbody-voceros-inactivos' : '#tbody-voceros-activos';
            var listaDestino = estado ? '#tbody-voceros-activos' : '#tbody-voceros-inactivos';
            var mensajeOrigen = estado ?
                'No se encontraron voceros inactivos.' :
                'No se encontraron voceros activos.';
            var mensajeDestino = estado ?
                'No se encontraron voceros activos.' :
                'No se encontraron voceros inactivos.';

            $(listaDestino).find('.empty-voceros-row').remove();
            fila.attr('data-status', estado ? 'activo' : 'inactivo');
            $(listaDestino).append(fila);
            actualizarFilaVacia(listaOrigen, mensajeOrigen);
            filtrarVoceros();

            return {
                fila: fila,
                listaOrigen: listaOrigen,
                listaDestino: listaDestino,
                mensajeDestino: mensajeDestino
            };
        }

        $(document).on('change', '.estado-vocero', function() {
            var checkbox = $(this);
            var estado = checkbox.is(':checked') ? 1 : 0;
            var id = checkbox.data('id');
            var movimiento = moverVoceroDeSeccion(id, estado);

            $.ajax({
                type: 'PATCH',
                url: '{{ url('/voceros/cambiar-estado') }}/' + id + '/' + estado,
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function() {
                    var tab = estado ? '#voceros-activos-tab' : '#voceros-inactivos-tab';
                    bootstrap.Tab.getOrCreateInstance(document.querySelector(tab)).show();
                },
                error: function() {
                    $(movimiento.listaOrigen).find('.empty-voceros-row').remove();
                    $(movimiento.listaOrigen).append(movimiento.fila);
                    actualizarFilaVacia(movimiento.listaDestino, movimiento.mensajeDestino);
                    $('#vocero-' + id).prop('checked', !estado);
                    movimiento.fila.attr('data-status', estado ? 'inactivo' : 'activo');
                    filtrarVoceros();
                    Swal.fire({
                        title: 'Error',
                        text: 'No se pudo actualizar el estado del vocero.',
                        icon: 'error'
                    });
                }
            });
        });

        // Client-side forms validation
        $(document).ready(function() {
            $('#buscar-vocero').on('input', filtrarVoceros);
            filtrarVoceros();

            // Create Vocero validation
            $('#formVocero').on('submit', function(e) {
                var cedula = $('#cedula').val().trim();
                var nombres = $('#nombres').val().trim();
                var apellidos = $('#apellidos').val().trim();
                var categoria = $('#categoria_vocero').val();
                var fecha = $('[name="fecha_eleccion"]').val().trim();

                var errors = [];
                var invalidNameRegex = /[^A-Za-zÀ-ÖØ-öø-ÿ\s]/;

                if (!/^\d{7,8}$/.test(cedula)) {
                    errors.push('Cédula: debe contener solo números (7 u 8 dígitos).');
                }
                if (nombres.length < 3 || nombres.length > 50 || invalidNameRegex.test(nombres)) {
                    errors.push('Nombres: debe tener entre 3 y 50 caracteres y contener solo letras.');
                }
                if (apellidos.length < 3 || apellidos.length > 50 || invalidNameRegex.test(apellidos)) {
                    errors.push('Apellidos: debe tener entre 3 y 50 caracteres y contener solo letras.');
                }
                if (!categoria) {
                    errors.push('Categoría: debe seleccionar una categoría.');
                }
                if (!fecha) {
                    errors.push('Fecha de elección: campo obligatorio.');
                }

                if (errors.length > 0) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Errores en el formulario',
                        html: errors.join('<br>'),
                        icon: 'error'
                    });
                    return false;
                }
            });

            // Edit Vocero validation
            $('#formEditarVocero').on('submit', function(e) {
                var cedula = $('#edit-cedula').val().trim();
                var nombres = $('#edit-nombres').val().trim();
                var apellidos = $('#edit-apellidos').val().trim();
                var categoria = $('#edit-categoria_vocero').val();
                var fecha = $('#edit-fecha_eleccion').val().trim();

                var errors = [];
                var invalidNameRegex = /[^A-Za-zÀ-ÖØ-öø-ÿ\s]/;

                if (!/^\d{7,8}$/.test(cedula)) {
                    errors.push('Cédula: debe contener solo números (7 u 8 dígitos).');
                }
                if (nombres.length < 3 || nombres.length > 50 || invalidNameRegex.test(nombres)) {
                    errors.push('Nombres: debe tener entre 3 y 50 caracteres y contener solo letras.');
                }
                if (apellidos.length < 3 || apellidos.length > 50 || invalidNameRegex.test(apellidos)) {
                    errors.push('Apellidos: debe tener entre 3 y 50 caracteres y contener solo letras.');
                }
                if (!categoria) {
                    errors.push('Categoría: debe seleccionar una categoría.');
                }
                if (!fecha) {
                    errors.push('Fecha de elección: campo obligatorio.');
                }

                if (errors.length > 0) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Errores en el formulario',
                        html: errors.join('<br>'),
                        icon: 'error'
                    });
                    return false;
                }
            });
        });
    </script>
@endpush
