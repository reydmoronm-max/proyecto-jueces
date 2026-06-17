@extends('layouts.main')
@section('titulo', $titulo)
@section('paginaTitulo', $paginaTitulo)
@section('paginaSubtitulo', $paginaSubtitulo)
@section('censoActive', $censoActive)

@section('contenido')
    <div class="container-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="header-title">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalRegistrarFamilia">
                                <i class="ri-add-fill"></i> Registrar Familia
                            </button>
                        </div>
                        <div class="d-flex align-items-center">
                            <form action="{{ route('censo.index') }}" method="GET" class="d-flex gap-2">
                                <input type="text" name="search" class="form-control"
                                    placeholder="Buscar por familia, cédula, nombre..." value="{{ request('search') }}"
                                    style="min-width: 280px;">
                                <button type="submit" class="btn btn-primary">Buscar</button>
                                @if (request('search'))
                                    <a href="{{ route('censo.index') }}" class="btn btn-secondary">Limpiar</a>
                                @endif
                            </form>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive mt-4">
                            <table id="basic-table" class="table table-striped mb-0" role="grid">
                                <thead>
                                    <tr>
                                        <th>Familia / Identificación</th>
                                        <th>Cantidad de Integrantes</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($items as $item)
                                        <tr>
                                            <td>
                                                <span class="fw-bold">{{ $item->numero_familia }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $item->personas->count() }} integrantes</span>
                                            </td>
                                            <td class="text-end">
                                                <div>
                                                    <button type="button" class="btn btn-sm btn-success me-1" title="Añadir Integrante"
                                                        onclick="abrirAñadirIntegrante({{ $item->id }})">
                                                        <i class="ri-user-add-fill"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-light me-1" title="Ver Integrantes"
                                                        onclick="verIntegrantesFamilia({{ $item->id }}, '{{ $item->numero_familia }}')">
                                                        <i class="ri-team-fill"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-warning me-1" title="Editar Familia"
                                                        onclick="editarFamilia({{ $item->id }}, '{{ $item->numero_familia }}')">
                                                        <i class="ri-pencil-fill"></i>
                                                    </button>
                                                    <form action="{{ route('censo.destroy', $item->id) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                            class="btn btn-sm btn-danger btn-eliminar-familia"
                                                            title="Eliminar Familia">
                                                            <i class="ri-delete-bin-fill"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-4">No se encontraron familias registradas.</td>
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

    @include('modules.censo.modalFamilia')
    @include('modules.censo.modalIntegrante')
    @include('modules.censo.modalEditarIntegrante')
    @include('modules.censo.modalConsultarIntegrante')
    @include('modules.censo.modalConsultarFamilia')

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

        // AJAX search for Person globally by Cédula (Create Integrante)
        function buscarPersonaEnCenso() {
            var cedula = $('#cedula').val().trim();
            if (!/^\d{7,8}$/.test(cedula)) {
                return;
            }

            $.ajax({
                url: '{{ route('censo.buscar-persona') }}',
                method: 'GET',
                data: {
                    cedula: cedula
                },
                success: function(data) {
                    $('#nombres').val(data.nombres).prop('readonly', true);
                    $('#apellidos').val(data.apellidos).prop('readonly', true);
                    $('#telefono').val(data.telefono || '');
                    
                    if (data.fecha_nacimiento_formateada) {
                        var fp = document.getElementById('fecha_nacimiento')._flatpickr;
                        if (fp) fp.setDate(data.fecha_nacimiento_formateada);
                        else $('#fecha_nacimiento').val(data.fecha_nacimiento_formateada);
                    }
                    
                    if (data.cantidad_integrantes) $('#cantidad_integrantes').val(data.cantidad_integrantes);
                    if (data.centro_votacion) $('#centro_votacion').val(data.centro_votacion);
                    if (data.carnet_patria) $('#carnet_patria').val(data.carnet_patria);
                    if (data.nivel_academico) $('#nivel_academico').val(data.nivel_academico);
                    if (data.profesion) $('#profesion').val(data.profesion);
                    if (data.situacion_laboral) $('#situacion_laboral').val(data.situacion_laboral);
                    if (data.vivienda) $('#vivienda').val(data.vivienda);
                    if (data.tipo_enfermedad) $('#tipo_enfermedad').val(data.tipo_enfermedad);
                    if (data.bono_unico_familiar) $('#bono_unico_familiar').val(data.bono_unico_familiar);
                    if (data.pensionado_jubilado) $('#pensionado_jubilado').val(data.pensionado_jubilado);
                    if (data.ayuda_tecnica) $('#ayuda_tecnica').val(data.ayuda_tecnica);
                    if (data.mision_vivienda) $('#mision_vivienda').val(data.mision_vivienda);
                    if (data.clap) $('#clap').val(data.clap);
                    if (data.casa_alimentacion) $('#casa_alimentacion').val(data.casa_alimentacion);
                    if (data.direccion) $('#direccion').val(data.direccion);
                    if (data.estudia) $('#estudia').val(data.estudia);
                    if (data.genero) $('#genero').val(data.genero);
                    if (data.parentesco) $('#parentesco').val(data.parentesco);
                    if (data.consejo_comunal_id) $('#consejo_comunal_id').val(data.consejo_comunal_id);
                },
                error: function(xhr) {
                    if (xhr.status === 404) {
                        $('#nombres').val('').prop('readonly', false);
                        $('#apellidos').val('').prop('readonly', false);
                        $('#telefono').val('');
                        // Leave other fields as blank for input
                    }
                }
            });
        }

        $('#cedula').on('blur', function() {
            buscarPersonaEnCenso();
        });

        // AJAX search for Person globally by Cédula (Edit Integrante)
        function buscarPersonaEnCensoEdit() {
            var cedula = $('#edit-cedula').val().trim();
            if (!/^\d{7,8}$/.test(cedula)) {
                return;
            }

            $.ajax({
                url: '{{ route('censo.buscar-persona') }}',
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
            buscarPersonaEnCensoEdit();
        });

        // Open Add Member Modal
        function abrirAñadirIntegrante(familiaId) {
            // Reset form
            $('#formIntegrante')[0].reset();
            $('#nombres').prop('readonly', false);
            $('#apellidos').prop('readonly', false);
            $('#integrante_familia_id').val(familiaId);
            
            var fp = document.getElementById('fecha_nacimiento')._flatpickr;
            if (fp) fp.clear();

            // Sane defaults or reset values
            $('#genero').val('');
            $('#estudia').val('');
            $('#parentesco').val('');
            $('#consejo_comunal_id').val('');
            $('#direccion').val('');

            var modal = new bootstrap.Modal(document.getElementById('modalRegistrarIntegrante'));
            modal.show();
        }

        // Open Family Members List Modal
        function verIntegrantesFamilia(id, numeroFamilia) {
            $('#lbl_numero_familia').text(numeroFamilia);
            $('#lista-integrantes-body').html('<tr><td colspan="4" class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary" role="status"></div> Cargando...</td></tr>');
            
            var modal = new bootstrap.Modal(document.getElementById('modalConsultarFamilia'));
            modal.show();

            $.ajax({
                url: '/censo/' + id,
                type: 'GET',
                success: function(data) {
                    var html = '';
                    if (data.personas && data.personas.length > 0) {
                        data.personas.forEach(function(p) {
                            html += `<tr>
                                <td>${p.cedula}</td>
                                <td>${p.nombres} ${p.apellidos}</td>
                                <td>${p.telefono || 'No registrado'}</td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-light py-0 px-1" title="Ver" onclick="consultarIntegrante(${p.id})">
                                        <i class="ri-eye-fill"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-warning py-0 px-1 ms-1" title="Editar" onclick="editarIntegrante(${p.id})">
                                        <i class="ri-pencil-fill"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger py-0 px-1 ms-1" title="Desvincular" onclick="desvincularIntegrante(${p.id}, '${p.nombres} ${p.apellidos}')">
                                        <i class="ri-link-unlink-m"></i>
                                    </button>
                                </td>
                            </tr>`;
                        });
                    } else {
                        html = '<tr><td colspan="4" class="text-center py-3">No hay integrantes registrados en esta familia.</td></tr>';
                    }
                    $('#lista-integrantes-body').html(html);
                },
                error: function() {
                    $('#lista-integrantes-body').html('<tr><td colspan="4" class="text-center py-3 text-danger">Error al cargar integrantes.</td></tr>');
                }
            });
        }

        // Consult Individual Member details
        function consultarIntegrante(id) {
            $.ajax({
                url: '/censo/integrante/' + id,
                type: 'GET',
                success: function(data) {
                    $('#view-cedula').val(data.cedula ?? '');
                    $('#view-nombres').val(data.nombres ?? '');
                    $('#view-apellidos').val(data.apellidos ?? '');
                    $('#view-telefono').val(data.telefono ?? 'No registrado');
                    $('#view-fecha_nacimiento').val(data.fecha_nacimiento_formateada ?? '');
                    $('#view-cantidad_integrantes').val(data.cantidad_integrantes ?? '');
                    $('#view-centro_votacion').val(data.centro_votacion ?? 'No registrado');
                    $('#view-carnet_patria').val(data.carnet_patria ?? 'No registrado');
                    $('#view-nivel_academico').val(data.nivel_academico ?? '');
                    $('#view-profesion').val(data.profesion ?? 'No registrado');
                    $('#view-situacion_laboral').val(data.situacion_laboral ?? 'No registrado');
                    $('#view-vivienda').val(data.vivienda ?? '');
                    $('#view-tipo_enfermedad').val(data.tipo_enfermedad ?? 'Ninguna');
                    $('#view-ayuda_tecnica').val(data.ayuda_tecnica ?? 'Ninguna');
                    $('#view-bono_unico_familiar').val(data.bono_unico_familiar ?? '');
                    $('#view-pensionado_jubilado').val(data.pensionado_jubilado ?? '');
                    $('#view-mision_vivienda').val(data.mision_vivienda ?? '');
                    $('#view-clap').val(data.clap ?? '');
                    $('#view-casa_alimentacion').val(data.casa_alimentacion ?? '');
                    $('#view-genero').val(data.genero ?? 'No registrado');
                    $('#view-estudia').val(data.estudia ?? 'No registrado');
                    $('#view-parentesco').val(data.parentesco ?? 'No registrado');
                    $('#view-consejo_comunal').val(data.consejo_comunal ? data.consejo_comunal.nombre : 'Ninguno');
                    $('#view-direccion').val(data.direccion ?? 'No registrada');

                    // Hide families list modal temporarily to avoid overlapping modal backdrops
                    var mFamilia = bootstrap.Modal.getInstance(document.getElementById('modalConsultarFamilia'));
                    if (mFamilia) mFamilia.hide();

                    var modal = new bootstrap.Modal(document.getElementById('modalConsultarIntegrante'));
                    modal.show();

                    // Re-show families modal when this details modal is closed
                    document.getElementById('modalConsultarIntegrante').addEventListener('hidden.bs.modal', function handler() {
                        if (mFamilia) mFamilia.show();
                        this.removeEventListener('hidden.bs.modal', handler);
                    });
                },
                error: function() {
                    Swal.fire('Error', 'No se pudo cargar la información del integrante.', 'error');
                }
            });
        }

        // Edit Individual Member details
        function editarIntegrante(id) {
            $.ajax({
                url: '/censo/integrante/' + id + '/edit',
                type: 'GET',
                success: function(data) {
                    $('#formEditarIntegrante').attr('action', '/censo/integrante/' + data.id);
                    $('#edit-cedula').val(data.cedula ?? '');
                    $('#edit-nombres').val(data.nombres ?? '');
                    $('#edit-apellidos').val(data.apellidos ?? '');
                    $('#edit-telefono').val(data.telefono ?? '');
                    
                    var fp = document.getElementById('edit-fecha_nacimiento_container')._flatpickr;
                    if (fp) {
                        fp.setDate(data.fecha_nacimiento_formateada);
                    } else {
                        $('#edit-fecha_nacimiento').val(data.fecha_nacimiento_formateada);
                    }
                    
                    $('#edit-cantidad_integrantes').val(data.cantidad_integrantes ?? '');
                    $('#edit-centro_votacion').val(data.centro_votacion ?? '');
                    $('#edit-carnet_patria').val(data.carnet_patria ?? '');
                    $('#edit-nivel_academico').val(data.nivel_academico ?? '');
                    $('#edit-profesion').val(data.profesion ?? '');
                    $('#edit-situacion_laboral').val(data.situacion_laboral ?? '');
                    $('#edit-vivienda').val(data.vivienda ?? '');
                    $('#edit-tipo_enfermedad').val(data.tipo_enfermedad ?? '');
                    $('#edit-ayuda_tecnica').val(data.ayuda_tecnica ?? '');
                    $('#edit-bono_unico_familiar').val(data.bono_unico_familiar ?? '');
                    $('#edit-pensionado_jubilado').val(data.pensionado_jubilado ?? '');
                    $('#edit-mision_vivienda').val(data.mision_vivienda ?? '');
                    $('#edit-clap').val(data.clap ?? '');
                    $('#edit-casa_alimentacion').val(data.casa_alimentacion ?? '');
                    $('#edit-genero').val(data.genero ?? '');
                    $('#edit-estudia').val(data.estudia ?? '');
                    $('#edit-parentesco').val(data.parentesco ?? '');
                    $('#edit-consejo_comunal_id').val(data.consejo_comunal_id ?? '');
                    $('#edit-direccion').val(data.direccion ?? '');

                    $('#edit-nombres').prop('readonly', true);
                    $('#edit-apellidos').prop('readonly', true);

                    // Hide families list modal temporarily
                    var mFamilia = bootstrap.Modal.getInstance(document.getElementById('modalConsultarFamilia'));
                    if (mFamilia) mFamilia.hide();

                    var modal = new bootstrap.Modal(document.getElementById('modalEditarIntegrante'));
                    modal.show();

                    // Re-show families modal when this edit modal is closed
                    document.getElementById('modalEditarIntegrante').addEventListener('hidden.bs.modal', function handler() {
                        if (mFamilia) mFamilia.show();
                        this.removeEventListener('hidden.bs.modal', handler);
                    });
                },
                error: function() {
                    Swal.fire('Error', 'No se pudo cargar la información del integrante.', 'error');
                }
            });
        }

        // Edit Family info
        function editarFamilia(id, numeroFamilia) {
            $('#formEditarFamilia').attr('action', '/censo/' + id);
            $('#edit_numero_familia').val(numeroFamilia);
            var modal = new bootstrap.Modal(document.getElementById('modalEditarFamilia'));
            modal.show();
        }

        // SweetAlert Delete Confirmation (Family)
        $(document).on('click', '.btn-eliminar-familia', function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Si eliminas la familia, los integrantes serán desvinculados, pero no borrados de la base de datos.",
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

        // SweetAlert Disassociate Member Confirmation
        function desvincularIntegrante(id, nombreCompleto) {
            Swal.fire({
                title: '¿Desvincular integrante?',
                text: `¿Estás seguro de que deseas desvincular a "${nombreCompleto}" de esta familia?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, desvincular',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create dynamic form to submit DELETE request to disassociate route
                    var form = $('<form>', {
                        action: '/censo/integrante/' + id,
                        method: 'POST'
                    }).append($('<input>', {
                        type: 'hidden',
                        name: '_token',
                        value: '{{ csrf_token() }}'
                    })).append($('<input>', {
                        type: 'hidden',
                        name: '_method',
                        value: 'DELETE'
                    }));
                    $('body').append(form);
                    form.submit();
                }
            });
        }

        // Client-side validation for Integrante forms
        $(document).ready(function() {
            function validateIntegranteForm(prefix) {
                var p = prefix ? prefix + '-' : '';
                var cedula = $('#' + p + 'cedula').val().trim();
                var nombres = $('#' + p + 'nombres').val().trim();
                var apellidos = $('#' + p + 'apellidos').val().trim();
                var fecha = $('#' + p + 'fecha_nacimiento').val().trim();
                var cant = $('#' + p + 'cantidad_integrantes').val().trim();
                var nivel = $('#' + p + 'nivel_academico').val();
                var vivienda = $('#' + p + 'vivienda').val();
                var bono = $('#' + p + 'bono_unico_familiar').val();
                var pension = $('#' + p + 'pensionado_jubilado').val();
                var mision = $('#' + p + 'mision_vivienda').val();
                var clap = $('#' + p + 'clap').val();
                var casa = $('#' + p + 'casa_alimentacion').val();
                var estudia = $('#' + p + 'estudia').val();
                var genero = $('#' + p + 'genero').val();
                var parentesco = $('#' + p + 'parentesco').val();

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
                if (!fecha) {
                    errors.push('Fecha de nacimiento: campo obligatorio.');
                }
                if (!cant || isNaN(cant) || parseInt(cant) < 1) {
                    errors.push('Cantidad de integrantes: debe ser un número mayor o igual a 1.');
                }
                if (!nivel) {
                    errors.push('Nivel académico: campo obligatorio.');
                }
                if (!vivienda) {
                    errors.push('Vivienda: campo obligatorio.');
                }
                if (!bono) {
                    errors.push('Bono único familiar: campo obligatorio.');
                }
                if (!pension) {
                    errors.push('Pensionado / Jubilado: campo obligatorio.');
                }
                if (!mision) {
                    errors.push('Misión vivienda: campo obligatorio.');
                }
                if (!clap) {
                    errors.push('CLAP: campo obligatorio.');
                }
                if (!casa) {
                    errors.push('Casa de alimentación: campo obligatorio.');
                }
                if (!genero) {
                    errors.push('Género: campo obligatorio.');
                }
                if (!estudia) {
                    errors.push('Estudia: campo obligatorio.');
                }
                if (!parentesco) {
                    errors.push('Parentesco: campo obligatorio.');
                }

                return errors;
            }

            $('#formIntegrante').on('submit', function(e) {
                var errors = validateIntegranteForm('');
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

            $('#formEditarIntegrante').on('submit', function(e) {
                var errors = validateIntegranteForm('edit');
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
