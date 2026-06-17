@extends('layouts.main')
@section('titulo', $titulo)
@section('paginaTitulo', $paginaTitulo)
@section('paginaSubtitulo', $paginaSubtitulo)

@section('contenido')
<div class="container-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="header-title">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalRegistrarConsejo">
                            <i class="ri-add-fill"></i> Registrar Consejo Comunal
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive mt-4">
                        <table id="basic-table" class="table table-striped mb-0" role="grid">
                            <thead>
                                <tr>
                                    <th>Nombre del Consejo</th>
                                    <th>RIF</th>
                                    <th>Jefe de Comando</th>
                                    <th>Dirección</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-consejos">
                                @include('modules.consejos_comunales.tbody')
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('modules.consejos_comunales.modalConsejo')
@include('modules.consejos_comunales.modalEditarConsejo')

@endsection

@push('scripts')
    <!-- SweetAlert y Mensajes del Servidor -->
    <script>
        @if (session('success')) 
            Swal.fire({
                title: '¡Éxito!',
                text: '{{ session("success") }}',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            });
        @endif

        @if ($errors->any())
            var erroresServidor = {!! json_encode($errors->all()) !!};
            Swal.fire({
                title: 'Errores en el formulario',
                html: erroresServidor.join('<br>'),
                icon: 'error',
                confirmButtonText: 'Corregir'
            }).then(function(){
                var modalEl = document.getElementById('modalRegistrarConsejo');
                var modal = new bootstrap.Modal(modalEl);
                modal.show();
            });
        @endif
    </script>

    <!-- Operaciones CRUD y AJAX Búsqueda -->
    <script>
        // Función de búsqueda de Jefe de Comando por Cédula (Común)
        function buscarJefeComando(cedulaInputId, hiddenIdId, containerId, labelId) {
            var cedula = $('#' + cedulaInputId).val().trim();
            if (cedula === '') {
                Swal.fire({
                    title: 'Campo vacío',
                    text: 'Por favor, ingrese un número de cédula.',
                    icon: 'warning'
                });
                return;
            }

            if (!/^[0-9]{7,8}$/.test(cedula)) {
                Swal.fire({
                    title: 'Formato inválido',
                    text: 'La cédula debe contener únicamente números y tener entre 7 y 8 dígitos.',
                    icon: 'warning'
                });
                return;
            }

            var url = '/consejos-comunales/buscar-persona/' + cedula;
            if (cedulaInputId === 'edit-buscar_jefe_cedula') {
                var action = $('#formEditarConsejo').attr('action');
                var match = action ? action.match(/\/consejos-comunales\/(\d+)$/) : null;
                if (match) {
                    url += '?current_consejo=' + match[1];
                }
            }

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Si encuentra a la persona, asignar valores
                    $('#' + hiddenIdId).val(response.id);
                    var $label = $('#' + labelId);
                    if ($label.is('input,textarea')) {
                        $label.val(response.nombre + ' ' + response.apellido);
                    } else {
                        $label.text(response.nombre + ' ' + response.apellido);
                    }
                    $('#' + containerId).removeClass('d-none');
                },
                error: function(xhr) {
                    // Si no la encuentra (404) u ocurre otro error
                    $('#' + hiddenIdId).val('');
                    $('#' + containerId).addClass('d-none');
                    var $label = $('#' + labelId);
                    if ($label.is('input,textarea')) {
                        $label.val('');
                    } else {
                        $label.text('');
                    }
                    $('#' + cedulaInputId).val('');

                    if (xhr.status === 404) {
                        Swal.fire({
                            title: 'No encontrado',
                            text: 'No existe ninguna persona registrada con la cédula ' + cedula + '.',
                            icon: 'error'
                        });
                    } else if (xhr.status === 409) {
                        Swal.fire({
                            title: 'Persona ya asignada',
                            text: xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Esta persona ya es Jefe de Comando en otro Consejo Comunal.',
                            icon: 'warning'
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'Ocurrió un problema durante la búsqueda.',
                            icon: 'error'
                        });
                    }
                }
            });
        }

        // Remoción de Jefe de Comando
        function removerJefe(cedulaInputId, hiddenIdId, containerId, labelId) {
            $('#' + hiddenIdId).val('');
            $('#' + cedulaInputId).val('');
            var $label = $('#' + labelId);
            if ($label.is('input,textarea')) {
                $label.val('');
            } else {
                $label.text('');
            }
            $('#' + containerId).addClass('d-none');
        }

        // Cargar datos en el Modal de Edición
        function editarConsejo(id) {
            $.ajax({
                url: '/consejos-comunales/' + id + '/edit',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#formEditarConsejo').attr('action', '/consejos-comunales/' + response.id);
                    $('#edit-nombre').val(response.nombre);
                    // Mostrar solo los dígitos del RIF sin el prefijo C en el input
                    $('#edit-rif').val(response.rif ? response.rif.replace(/^C/i, '') : '');
                    $('#edit-direccion').val(response.direccion);
                    
                    if (response.persona) {
                        $('#edit-buscar_jefe_cedula').val(response.persona.cedula);
                        $('#edit-jefe_comando_id').val(response.persona.id);
                        var $label = $('#edit-jefe_nombre_completo');
                        if ($label.is('input,textarea')) {
                            $label.val(response.persona.nombres + ' ' + response.persona.apellidos);
                        } else {
                            $label.text(response.persona.nombres + ' ' + response.persona.apellidos);
                        }
                        $('#edit-jefe_confirmacion_container').removeClass('d-none');
                    } else {
                        removerJefe('edit-buscar_jefe_cedula', 'edit-jefe_comando_id', 'edit-jefe_confirmacion_container', 'edit-jefe_nombre_completo');
                    }

                    var modalEl = document.getElementById('modalEditarConsejo');
                    var modal = new bootstrap.Modal(modalEl);
                    modal.show();
                },
                error: function() {
                    Swal.fire({
                        title: 'Error',
                        text: 'No se pudo cargar la información del consejo comunal.',
                        icon: 'error'
                    });
                }
            });
        }

        $(document).ready(function() {
            // Inicializar tooltips de Bootstrap
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Asignar eventos para el registro
            $('#btn_buscar_jefe').on('click', function() {
                buscarJefeComando('buscar_jefe_cedula', 'jefe_comando_id', 'jefe_confirmacion_container', 'jefe_nombre_completo');
            });

            $('#buscar_jefe_cedula').on('input', function() {
                this.value = this.value.replace(/\D/g, '').slice(0, 8);
            });

            $('#buscar_jefe_cedula').on('blur', function() {
                if ($(this).val().trim() !== '') {
                    buscarJefeComando('buscar_jefe_cedula', 'jefe_comando_id', 'jefe_confirmacion_container', 'jefe_nombre_completo');
                }
            });

            $('#btn_remover_jefe').on('click', function() {
                removerJefe('buscar_jefe_cedula', 'jefe_comando_id', 'jefe_confirmacion_container', 'jefe_nombre_completo');
            });

            // Asignar eventos para la edición
            $('#edit-btn_buscar_jefe').on('click', function() {
                buscarJefeComando('edit-buscar_jefe_cedula', 'edit-jefe_comando_id', 'edit-jefe_confirmacion_container', 'edit-jefe_nombre_completo');
            });

            $('#edit-buscar_jefe_cedula').on('input', function() {
                this.value = this.value.replace(/\D/g, '').slice(0, 8);
            });

            $('#edit-buscar_jefe_cedula').on('blur', function() {
                if ($(this).val().trim() !== '') {
                    buscarJefeComando('edit-buscar_jefe_cedula', 'edit-jefe_comando_id', 'edit-jefe_confirmacion_container', 'edit-jefe_nombre_completo');
                }
            });

            $('#edit-btn_remover_jefe').on('click', function() {
                removerJefe('edit-buscar_jefe_cedula', 'edit-jefe_comando_id', 'edit-jefe_confirmacion_container', 'edit-jefe_nombre_completo');
            });

            // Enforce numeric-only for RIF inputs (solo dígitos, máximo 9)
            $('#rif, #edit-rif').on('input', function() {
                this.value = this.value.replace(/\D/g, '').slice(0, 9);
            });

            // Validación del formulario de Registro antes del submit
            $('#formRegistrarConsejo').on('submit', function(e) {
                var nombre = $('#nombre').val().trim();
                var rifNumber = $('#rif').val().trim();
                if (/^C/i.test(rifNumber)) {
                    rifNumber = rifNumber.slice(1);
                }
                var jefeComando = $('#jefe_comando_id').val();
                var direccion = $('#direccion').val().trim();
                var errors = [];

                if (nombre.length < 3 || nombre.length > 255) {
                    errors.push('El nombre del consejo debe tener entre 3 y 255 caracteres.');
                }

                if (!/^\d{9}$/.test(rifNumber)) {
                    errors.push('El RIF es requerido y debe contener exactamente 9 dígitos (ej. 123456789).');
                }

                if (!jefeComando) {
                    errors.push('Debe buscar y seleccionar un Jefe de Comando registrado.');
                }

                if (direccion.length < 5 || direccion.length > 500) {
                    errors.push('La dirección detallada debe tener entre 5 y 500 caracteres.');
                }

                if (errors.length > 0) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Datos inválidos',
                        html: errors.join('<br>'),
                        icon: 'error'
                    });
                    return;
                }

                // Normalizar RIF anteponiendo la letra C antes de enviar
                $('#rif').val('C' + rifNumber);
            });

            // Validación del formulario de Edición antes del submit
            $('#formEditarConsejo').on('submit', function(e) {
                var nombre = $('#edit-nombre').val().trim();
                var rifNumber = $('#edit-rif').val().trim();
                if (/^C/i.test(rifNumber)) {
                    rifNumber = rifNumber.slice(1);
                }
                var jefeComando = $('#edit-jefe_comando_id').val();
                var direccion = $('#edit-direccion').val().trim();
                var errors = [];

                if (nombre.length < 3 || nombre.length > 255) {
                    errors.push('El nombre del consejo debe tener entre 3 y 255 caracteres.');
                }

                if (!/^\d{9}$/.test(rifNumber)) {
                    errors.push('El RIF es requerido y debe contener exactamente 9 dígitos (ej. 123456789).');
                }

                if (!jefeComando) {
                    errors.push('Debe buscar y seleccionar un Jefe de Comando registrado.');
                }

                if (direccion.length < 5 || direccion.length > 500) {
                    errors.push('La dirección detallada debe tener entre 5 y 500 caracteres.');
                }

                if (errors.length > 0) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Datos inválidos',
                        html: errors.join('<br>'),
                        icon: 'error'
                    });
                    return;
                }

                // Normalizar RIF anteponiendo la letra C antes de enviar
                $('#edit-rif').val('C' + rifNumber);
            });

            // Alerta de confirmación al eliminar
            $(document).on('click', '.btn-eliminar-consejo', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: 'Esta acción eliminará el Consejo Comunal permanentemente.',
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
        });
    </script>
@endpush
