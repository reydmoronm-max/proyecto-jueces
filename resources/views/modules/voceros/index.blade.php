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
                        <div class="d-flex align-items-center">
                            <form action="{{ route('voceros.index') }}" method="GET" class="d-flex gap-2">
                                <input type="text" name="search" class="form-control" placeholder="Buscar por cédula, nombre o categoría..." value="{{ request('search') }}" style="min-width: 280px;">
                                <button type="submit" class="btn btn-primary">Buscar</button>
                                @if(request('search'))
                                    <a href="{{ route('voceros.index') }}" class="btn btn-secondary">Limpiar</a>
                                @endif
                            </form>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive mt-4">
                            <table id="basic-table" class="table table-striped mb-0" role="grid">
                                <thead>
                                    <tr>
                                        <th>Cédula</th>
                                        <th>Nombre y Apellido</th>
                                        <th>Categoría</th>
                                        <th>Fecha de elección</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($items as $item)
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary me-1">{{ $item->persona->cedula_tipo ?? 'V' }}</span>
                                                {{ $item->persona->cedula }}
                                            </td>
                                            <td>{{ $item->persona->nombres }} {{ $item->persona->apellidos }}</td>
                                            <td>{{ $item->categoria_vocero }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->fecha_eleccion)->format('d-m-Y') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-light" title="Consultar" onclick="consultarVocero({{ $item->id }})">
                                                        <i class="ri-eye-fill"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-warning" title="Editar" onclick="editarVocero({{ $item->id }})">
                                                        <i class="ri-pencil-fill"></i>
                                                    </button>
                                                    <form action="{{ route('voceros.destroy', $item->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-sm btn-danger btn-eliminar-vocero" title="Eliminar">
                                                            <i class="ri-delete-bin-fill"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">No se encontraron voceros registrados.</td>
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
                html: '{!! implode("<br>", $errors->all()) !!}',
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

        // SweetAlert Delete Confirmation
        $(document).on('click', '.btn-eliminar-vocero', function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            Swal.fire({
                title: '¿Estás seguro?',
                text: "No podrás revertir esto.",
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

        // Client-side forms validation
        $(document).ready(function() {
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
