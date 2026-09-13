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
                            <form action="{{ route('censo.index') }}" method="GET" class="d-flex align-items-end flex-wrap gap-2">
                                <div>
                                    <label for="consejo_comunal_id" class="form-label small text-muted fw-bold mb-1">Consejo Comunidad</label>
                                    <select name="consejo_comunal_id" id="consejo_comunal_id" class="form-select">
                                        <option value="">Todos los consejos</option>
                                        @foreach ($consejosComunales as $consejo)
                                            <option value="{{ $consejo->id }}" {{ $consejoComunalId === (string) $consejo->id ? 'selected' : '' }}>
                                                {{ $consejo->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="tipo_vivienda" class="form-label small text-muted fw-bold mb-1">Tipo de vivienda</label>
                                    <select name="tipo_vivienda" id="tipo_vivienda" class="form-select">
                                        <option value="">Todos los tipos</option>
                                        <option value="Propia" {{ $tipoVivienda === 'Propia' ? 'selected' : '' }}>Propias</option>
                                        <option value="Prestada" {{ $tipoVivienda === 'Prestada' ? 'selected' : '' }}>Prestadas</option>
                                        <option value="Alquilada" {{ $tipoVivienda === 'Alquilada' ? 'selected' : '' }}>Alquiladas</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="beneficio" class="form-label small text-muted fw-bold mb-1">Beneficios</label>
                                    <select name="beneficio" id="beneficio" class="form-select">
                                        <option value="">Todos los beneficios</option>
                                        <option value="clap" {{ $beneficio === 'clap' ? 'selected' : '' }}>Recibe CLAP</option>
                                        <option value="bono_familiar" {{ $beneficio === 'bono_familiar' ? 'selected' : '' }}>Recibe Bono Familiar</option>
                                        <option value="ninguno" {{ $beneficio === 'ninguno' ? 'selected' : '' }}>Ningún beneficio</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="search" class="form-label small text-muted fw-bold mb-1">Buscar</label>
                                <input type="text" name="search" id="search" class="form-control"
                                    placeholder="Buscar por familia, cédula, nombre, comunidad..." value="{{ request('search') }}"
                                    style="min-width: 280px;">
                                </div>
                                <button type="submit" class="btn btn-primary">Buscar</button>
                                @if ($search !== '' || $consejoComunalId !== '' || $tipoVivienda !== '' || $beneficio !== '')
                                    <a href="{{ route('censo.index') }}" class="btn btn-secondary">Limpiar</a>
                                @endif
                            </form>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive mt-4">
                            <table id="basic-table" class="table table-striped table-bordered table-striped-columns mb-0" role="grid">
                                <thead>
                                    <tr>
                                        <th>Familia / Identificación</th>
                                        <th>Comunidad / Consejo Comunal</th>
                                        <th>Vivienda</th>
                                        <th>Beneficios</th>
                                        <th>Integrantes</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($items as $item)
                                        <tr>
                                            <td>
                                                <span class="fw-bold text-dark">{{ $item->numero_familia }}</span>
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ $item->consejoComunal->nombre ?? 'Sin vincular' }}</span>
                                            </td>
                                            <td>
                                                @if ($item->vivienda)
                                                    <span class="badge bg-secondary">{{ $item->vivienda }}</span>
                                                    @if ($item->mision_vivienda === 'Sí')
                                                        <span class="badge bg-info ms-1" title="Misión Vivienda">GMVV</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted small">No registrada</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($item->clap === 'Sí')
                                                    <span class="badge bg-success" title="Recibe CLAP">CLAP</span>
                                                @endif
                                                @if ($item->bono_unico_familiar === 'Sí')
                                                    <span class="badge bg-danger ms-1" title="Recibe Bono Único Familiar">Bono Familiar</span>
                                                @endif
                                                @if ($item->clap !== 'Sí' && $item->bono_unico_familiar !== 'Sí')
                                                    <span class="text-muted small">Ninguno</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">{{ $item->personas->count() }} integrantes</span>
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
                                                        onclick="editarFamilia({{ $item->id }})">
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
                                            <td colspan="6" class="text-center py-4">No se encontraron familias registradas.</td>
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

        function obtenerEdadIntegrante(prefix) {
            var fecha = $('#' + (prefix ? 'edit-fecha_nacimiento' : 'fecha_nacimiento')).val();
            var partes = fecha ? fecha.split('-') : [];
            var fechaNacimiento = partes.length === 3
                ? new Date(partes[2], partes[1] - 1, partes[0])
                : null;
            var hoy = new Date();
            var edad = fechaNacimiento && !isNaN(fechaNacimiento.getTime())
                ? hoy.getFullYear() - fechaNacimiento.getFullYear()
                : -1;

            if (fechaNacimiento && (hoy.getMonth() < fechaNacimiento.getMonth() ||
                (hoy.getMonth() === fechaNacimiento.getMonth() && hoy.getDate() < fechaNacimiento.getDate()))) {
                edad--;
            }

            if (!fechaNacimiento || isNaN(fechaNacimiento.getTime())) {
                return -1;
            }

            return edad;
        }

        function actualizarPensionadoJubilado(prefix) {
            var genero = $('#' + (prefix ? 'edit-genero' : 'genero')).val();
            var pension = $('#' + (prefix ? 'edit-pensionado_jubilado' : 'pensionado_jubilado'));
            var aviso = $('#' + (prefix ? 'edit-aviso-pensionado-jubilado' : 'aviso-pensionado-jubilado'));
            var edad = obtenerEdadIntegrante(prefix);
            var edadMinima = genero === 'Masculino' ? 60 : genero === 'Femenino' ? 55 : Infinity;
            var tieneDatosSuficientes = edad >= 0 && genero;
            var puedeSeleccionar = tieneDatosSuficientes && edad >= edadMinima;
            pension.prop('disabled', !puedeSeleccionar);
            aviso.toggleClass('d-none', !tieneDatosSuficientes || puedeSeleccionar);
            if (!puedeSeleccionar) {
                pension.val('No');
            }
        }

        function actualizarCamposMenorEdad(prefix) {
            var edad = obtenerEdadIntegrante(prefix);
            var campos = prefix
                ? '#edit-profesion, #edit-situacion_laboral, #edit-centro_votacion'
                : '#profesion, #situacion_laboral, #centro_votacion';
            var nivel = $('#' + (prefix ? 'edit-nivel_academico' : 'nivel_academico'));
            var esMenor = edad >= 0 && edad < 18;

            $(campos).prop('disabled', esMenor);
            nivel.find('option[value="Universitario"], option[value="Postgrado"]').prop('disabled', esMenor);
            if (esMenor && ['Universitario', 'Postgrado'].includes(nivel.val())) {
                nivel.val('Técnico');
            }
            if (esMenor) {
                $(campos).val('No aplica');
            } else {
                $(campos).filter(function() {
                    return $(this).val() === 'No aplica';
                }).val('');
            }
        }

        function actualizarReglasPorEdad(prefix) {
            actualizarPensionadoJubilado(prefix);
            actualizarCamposMenorEdad(prefix);
        }

        function actualizarMisionVivienda(prefix) {
            var vivienda = $('#' + (prefix ? 'edit_vivienda' : 'vivienda_fam'));
            var mision = $('#' + (prefix ? 'edit_mision_vivienda' : 'mision_vivienda_fam'));
            var esPropia = vivienda.val() === 'Propia';

            mision.prop('disabled', !esPropia).val(esPropia ? '' : 'NA');
        }

        // Flatpickr initializations
        $(document).ready(function() {
            if (typeof flatpickr !== 'undefined') {
                flatpickr("#fecha_nacimiento", {
                    dateFormat: "d-m-Y",
                    maxDate: "today",
                    locale: "es",
                    allowInput: false,
                    onChange: function() {
                        actualizarReglasPorEdad('');
                    }
                });

                flatpickr("#edit-fecha_nacimiento", {
                    dateFormat: "d-m-Y",
                    maxDate: "today",
                    locale: "es",
                    allowInput: false,
                    onChange: function() {
                        actualizarReglasPorEdad('edit');
                    }
                });
            }
        });

        // AJAX search for Person globally by Cédula (Add Integrante)
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
                    
                    if (data.centro_votacion) $('#centro_votacion').val(data.centro_votacion);
                    if (data.carnet_patria) $('#carnet_patria').val(data.carnet_patria);
                    if (data.nivel_academico) $('#nivel_academico').val(data.nivel_academico);
                    if (data.profesion) $('#profesion').val(data.profesion);
                    if (data.situacion_laboral) $('#situacion_laboral').val(data.situacion_laboral);
                    if (data.tipo_enfermedad) $('#tipo_enfermedad').val(data.tipo_enfermedad);
                    if (data.pensionado_jubilado) $('#pensionado_jubilado').val(data.pensionado_jubilado);
                    if (data.direccion) $('#direccion').val(data.direccion);
                    if (data.estudia) $('#estudia').val(data.estudia);
                    if (data.genero) $('#genero').val(data.genero);
                    if (data.parentesco) $('#parentesco').val(data.parentesco);
                    actualizarReglasPorEdad('');
                },
                error: function(xhr) {
                    if (xhr.status === 404) {
                        $('#nombres').val('').prop('readonly', false);
                        $('#apellidos').val('').prop('readonly', false);
                        $('#telefono').val('');
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
            $('#formIntegrante')[0].reset();
            $('#nombres').prop('readonly', false);
            $('#apellidos').prop('readonly', false);
            $('#integrante_familia_id').val(familiaId);
            
            var fp = document.getElementById('fecha_nacimiento')._flatpickr;
            if (fp) fp.clear();

            $('#genero').val('');
            $('#estudia').val('');
            $('#parentesco').val('');
            $('#pensionado_jubilado').val('');
            actualizarReglasPorEdad('');
            $('#nivel_academico').val('');
            $('#direccion').val('');

            var modal = new bootstrap.Modal(document.getElementById('modalRegistrarIntegrante'));
            modal.show();
        }

        // Open Family Members List Modal
        function verIntegrantesFamilia(id, numeroFamilia) {
            $('#lbl_numero_familia').text(numeroFamilia);
            $('#view_fam_consejo_comunal').text('Cargando...');
            $('#view_fam_vivienda').text('Cargando...');
            $('#view_fam_mision_vivienda').text('...');
            $('#view_fam_bono').text('...');
            $('#view_fam_clap').text('...');
            $('#lista-integrantes-body').html('<tr><td colspan="5" class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary" role="status"></div> Cargando...</td></tr>');
            
            var modal = new bootstrap.Modal(document.getElementById('modalConsultarFamilia'));
            modal.show();

            $.ajax({
                url: '/censo/' + id,
                type: 'GET',
                success: function(data) {
                    $('#view_fam_consejo_comunal').text(data.consejo_comunal ? data.consejo_comunal.nombre : 'Sin vincular');
                    $('#view_fam_vivienda').text(data.vivienda ?? 'No registrada');
                    $('#view_fam_mision_vivienda').text(data.mision_vivienda ?? 'No');
                    $('#view_fam_bono').text(data.bono_unico_familiar ?? 'No');
                    $('#view_fam_clap').text(data.clap ?? 'No');

                    var html = '';
                    if (data.personas && data.personas.length > 0) {
                        data.personas.forEach(function(p) {
                            html += `<tr>
                                <td>${p.cedula}</td>
                                <td>${p.nombres} ${p.apellidos}</td>
                                <td><span class="badge bg-secondary">${p.parentesco ?? 'No registrado'}</span></td>
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
                        html = '<tr><td colspan="5" class="text-center py-3">No hay integrantes registrados en esta familia.</td></tr>';
                    }
                    $('#lista-integrantes-body').html(html);
                },
                error: function() {
                    $('#lista-integrantes-body').html('<tr><td colspan="5" class="text-center py-3 text-danger">Error al cargar integrantes.</td></tr>');
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
                    $('#view-centro_votacion').val(data.centro_votacion ?? 'No registrado');
                    $('#view-carnet_patria').val(data.carnet_patria ?? 'No registrado');
                    $('#view-nivel_academico').val(data.nivel_academico ?? '');
                    $('#view-profesion').val(data.profesion ?? 'No registrado');
                    $('#view-situacion_laboral').val(data.situacion_laboral ?? 'No registrado');
                    $('#view-tipo_enfermedad').val(data.tipo_enfermedad ?? 'Ninguna');
                    $('#view-pensionado_jubilado').val(data.pensionado_jubilado ?? '');
                    $('#view-genero').val(data.genero ?? 'No registrado');
                    $('#view-estudia').val(data.estudia ?? 'No registrado');
                    $('#view-parentesco').val(data.parentesco ?? 'No registrado');
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
                    
                    $('#edit-centro_votacion').val(data.centro_votacion ?? '');
                    $('#edit-carnet_patria').val(data.carnet_patria ?? '');
                    $('#edit-nivel_academico').val(data.nivel_academico ?? '');
                    $('#edit-profesion').val(data.profesion ?? '');
                    $('#edit-situacion_laboral').val(data.situacion_laboral ?? '');
                    $('#edit-tipo_enfermedad').val(data.tipo_enfermedad ?? '');
                    $('#edit-pensionado_jubilado').val(data.pensionado_jubilado ?? '');
                    $('#edit-genero').val(data.genero ?? '');
                    actualizarReglasPorEdad('edit');
                    $('#edit-estudia').val(data.estudia ?? '');
                    $('#edit-parentesco').val(data.parentesco ?? '');
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
        function editarFamilia(id) {
            $.ajax({
                url: '/censo/' + id,
                type: 'GET',
                success: function(data) {
                    $('#formEditarFamilia').attr('action', '/censo/' + data.id);
                    $('#edit_numero_familia').val(data.numero_familia ?? '');
                    $('#edit_consejo_comunal_id').val(data.consejo_comunal_id ?? '');
                    $('#edit_vivienda').val(data.vivienda ?? '');
                    $('#edit_mision_vivienda').val(data.mision_vivienda ?? '');
                    actualizarMisionVivienda('edit');
                    $('#edit_bono_unico_familiar').val(data.bono_unico_familiar ?? '');
                    $('#edit_clap').val(data.clap ?? '');
                    var modal = new bootstrap.Modal(document.getElementById('modalEditarFamilia'));
                    modal.show();
                },
                error: function() {
                    Swal.fire('Error', 'No se pudo cargar la información de la familia.', 'error');
                }
            });
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

        // Client-side validation for Family and Member forms
        $(document).ready(function() {
            function validateIntegranteForm(prefix) {
                var p = prefix ? prefix + '-' : '';
                var cedula = $('#' + p + 'cedula').val().trim();
                var nombres = $('#' + p + 'nombres').val().trim();
                var apellidos = $('#' + p + 'apellidos').val().trim();
                var fecha = $('#' + p + 'fecha_nacimiento').val().trim();
                var telefono = $('#' + p + 'telefono').val().trim();
                var genero = $('#' + p + 'genero').val();
                var parentesco = $('#' + p + 'parentesco').val();
                var estudia = $('#' + p + 'estudia').val();
                var pension = $('#' + p + 'pensionado_jubilado').val();
                var nivel = $('#' + p + 'nivel_academico').val();

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
                if (telefono && !/^\d{1,11}$/.test(telefono)) {
                    errors.push('Teléfono: debe contener solo números y tener máximo 11 dígitos.');
                }
                if (!fecha) {
                    errors.push('Fecha de nacimiento: campo obligatorio.');
                }
                if (!genero) {
                    errors.push('Género: campo obligatorio.');
                }
                if (!parentesco) {
                    errors.push('Parentesco: campo obligatorio.');
                }
                if (!estudia) {
                    errors.push('Estudia: campo obligatorio.');
                }
                if (!pension) {
                    errors.push('Pensionado / Jubilado: campo obligatorio.');
                }
                if (!nivel) {
                    errors.push('Nivel académico: campo obligatorio.');
                }

                return errors;
            }

            $('#genero, #edit-genero').on('change', function() {
                actualizarReglasPorEdad(this.id === 'edit-genero' ? 'edit' : '');
            });

            function validateFamiliaForm(prefix) {
                var p = prefix ? prefix + '_' : '';
                var num = $('#' + p + 'numero_familia').val().trim();
                var viv = $('#' + (prefix ? 'edit_vivienda' : 'vivienda_fam')).val();
                var mision = $('#' + (prefix ? 'edit_mision_vivienda' : 'mision_vivienda_fam')).val();
                var bono = $('#' + (prefix ? 'edit_bono_unico_familiar' : 'bono_unico_familiar_fam')).val();
                var clap = $('#' + (prefix ? 'edit_clap' : 'clap_fam')).val();

                var errors = [];
                if (!num) {
                    errors.push('Identificación / Número de Familia: campo obligatorio.');
                }
                if (!viv) {
                    errors.push('Tipo de vivienda: campo obligatorio.');
                }
                if (!mision) {
                    errors.push('Misión Vivienda: campo obligatorio.');
                }
                if (!bono) {
                    errors.push('Bono Único Familiar: campo obligatorio.');
                }
                if (!clap) {
                    errors.push('CLAP: campo obligatorio.');
                }
                return errors;
            }

            $('#vivienda_fam').on('change', function() {
                actualizarMisionVivienda('');
            });

            $('#edit_vivienda').on('change', function() {
                actualizarMisionVivienda('edit');
            });

            $('#formFamilia').on('submit', function(e) {
                var errors = validateFamiliaForm('');
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

            $('#formEditarFamilia').on('submit', function(e) {
                var errors = validateFamiliaForm('edit');
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
