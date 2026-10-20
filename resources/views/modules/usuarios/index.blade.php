@extends('layouts.main')
@section('titulo', $titulo)
@section('usuariosActive', $usuariosActive)
@section('paginaTitulo', $paginaTitulo)
@section('paginaSubtitulo', $paginaSubtitulo)

@section('contenido')
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="header-title">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalRegistrarUsuario">
                                <i class="ri-add-fill"></i> Registrar Usuario
                            </button>
                        </div>
                        <div class="d-flex flex-grow-1 justify-content-end">
                            <div class="input-group w-100 shadow-sm rounded-3" style="max-width: 360px;">
                                <label for="buscar-usuario" class="visually-hidden">Buscar usuarios</label>
                                <span class="input-group-text bg-primary text-white border border-dark border-end-0 rounded-start-3">
                                    <i class="ri-search-line"></i>
                                </span>
                                <input type="search" id="buscar-usuario"
                                    class="form-control border-start-0 ps-0 rounded-end-3"
                                    placeholder="Nombre, apellido, cédula o rol..."
                                    autocomplete="off" aria-label="Buscar usuarios por nombre, apellido, cédula o rol">
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-fill mb-4 border-bottom-0" id="usuariosTabs" role="tablist"
                            style="background: #f8f9fa; padding: 6px; border-radius: 10px;">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active py-2 fw-semibold" id="usuarios-activos-tab"
                                    data-bs-toggle="tab" data-bs-target="#usuarios-activos-content" type="button"
                                    role="tab" aria-controls="usuarios-activos-content" aria-selected="true"
                                    style="border-radius: 8px;">
                                    Usuarios Activos
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link py-2 fw-semibold" id="usuarios-inactivos-tab"
                                    data-bs-toggle="tab" data-bs-target="#usuarios-inactivos-content" type="button"
                                    role="tab" aria-controls="usuarios-inactivos-content" aria-selected="false"
                                    style="border-radius: 8px;">
                                    Usuarios Inactivos
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content" id="usuariosTabsContent">
                            <div class="tab-pane fade show active" id="usuarios-activos-content" role="tabpanel"
                                aria-labelledby="usuarios-activos-tab">
                                <div class="table-responsive">
                                    <table id="basic-table" class="table table-striped mb-0" role="grid">
                                        <thead>
                                            <tr>
                                                <th>Nombre y Apellido</th>
                                                <th>Cédula</th>
                                                <th>Contraseña</th>
                                                <th>Activo</th>
                                                <th>Rol</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody-usuarios">
                                            @if ($usuariosActivos->isNotEmpty())
                                                @include('modules.usuarios.tbody', ['items' => $usuariosActivos])
                                            @else
                                                <tr class="empty-users-row">
                                                    <td colspan="6" class="text-center py-4">No hay usuarios activos.</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="usuarios-inactivos-content" role="tabpanel"
                                aria-labelledby="usuarios-inactivos-tab">
                                <div class="table-responsive">
                                    <table id="tabla-usuarios-inactivos" class="table table-striped mb-0" role="grid">
                                        <thead>
                                            <tr>
                                                <th>Nombre y Apellido</th>
                                                <th>Cédula</th>
                                                <th>Contraseña</th>
                                                <th>Activo</th>
                                                <th>Rol</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody-usuarios-inactivos">
                                            @if ($usuariosInactivos->isNotEmpty())
                                                @include('modules.usuarios.tbody', ['items' => $usuariosInactivos])
                                            @else
                                                <tr class="empty-users-row">
                                                    <td colspan="6" class="text-center py-4">No hay usuarios inactivos.</td>
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

    @include('modules.usuarios.modalUsuario')
    @include('modules.usuarios.modalPassword')
    @include('modules.usuarios.modalEditarUsuario')


@endsection



@push('scripts')
    <script>
        @if (session('success'))

            Swal.fire({
                title: '¡Éxito!',
                text: 'Se registró el usuario correctamente.',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            });
        @endif

        @if (session('update'))

            Swal.fire({
                title: '¡Éxito!',
                text: 'Se actualizó el usuario correctamente.',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                title: 'Errores en el formulario',
                text: @json(implode(' ', $errors->all())),
                icon: 'error',
                confirmButtonText: 'Corregir'
            });
        @endif
    </script>
    <script>
        function recargar_tbody() {
            $.ajax({
                type: "GET",
                url: "{{ route('tbody') }}",
                success: function(respuesta) {
                    console.log(respuesta);
                }
            });
        }

        function normalizarTextoUsuario(texto) {
            return texto
                .toLocaleLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '');
        }

        function filtrarUsuarios() {
            const busqueda = normalizarTextoUsuario($('#buscar-usuario').val().trim());

            ['#tbody-usuarios', '#tbody-usuarios-inactivos'].forEach(function(selector) {
                const tbody = $(selector);
                const filas = tbody.find('tr[data-search]');
                let resultados = 0;

                filas.each(function() {
                    const coincide = normalizarTextoUsuario($(this).attr('data-search')).includes(busqueda);
                    $(this).toggle(coincide);

                    if (coincide) {
                        resultados++;
                    }
                });

                tbody.find('.empty-users-row').toggle(!busqueda && filas.length === 0);
                tbody.find('.no-search-results-row').remove();

                if (busqueda && resultados === 0) {
                    tbody.append(
                        '<tr class="no-search-results-row"><td colspan="6" class="text-center py-4">No se encontraron usuarios que coincidan con la búsqueda.</td></tr>'
                    );
                }
            });
        }

        function actualizarFilaVacia(tbody, mensaje) {
            const tieneUsuarios = $(tbody).find('tr[data-search]').length > 0;
            $(tbody).find('.empty-users-row').remove();

            if (!tieneUsuarios) {
                $(tbody).append(
                    '<tr class="empty-users-row"><td colspan="6" class="text-center py-4">' + mensaje + '</td></tr>'
                );
            }

            filtrarUsuarios();
        }

        function moverUsuarioDeSeccion(id, estado) {
            const fila = $('#' + id).closest('tr');
            const listaOrigen = estado ? '#tbody-usuarios-inactivos' : '#tbody-usuarios';
            const listaDestino = estado ? '#tbody-usuarios' : '#tbody-usuarios-inactivos';
            const mensajeOrigen = estado ? 'No hay usuarios inactivos.' : 'No hay usuarios activos.';
            const mensajeDestino = estado ? 'No hay usuarios activos.' : 'No hay usuarios inactivos.';

            $(listaDestino).find('.empty-users-row').remove();
            $(listaDestino).append(fila);
            actualizarFilaVacia(listaOrigen, mensajeOrigen);
            filtrarUsuarios();

            return {
                fila: fila,
                listaOrigen: listaOrigen,
                listaDestino: listaDestino,
                mensajeOrigen: mensajeOrigen,
                mensajeDestino: mensajeDestino
            };
        }

        function cambiar_estado(id, estado) {
            if (!estado && Number(id) === Number(@json(auth()->id()))) {
                $('#' + id).prop('checked', true);
                Swal.fire({
                    title: 'Acción no permitida',
                    text: 'No puedes desactivar tu propio usuario.',
                    icon: 'warning',
                    confirmButtonText: 'Aceptar'
                });
                return;
            }

            const movimiento = moverUsuarioDeSeccion(id, estado);

            $.ajax({
                type: "PATCH",
                url: "{{ url('/usuarios/cambiar-estado') }}/" + id + "/" + estado,
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(respuesta) {
                    const pestaña = estado ? '#usuarios-activos-tab' : '#usuarios-inactivos-tab';
                    bootstrap.Tab.getOrCreateInstance(document.querySelector(pestaña)).show();
                },
                error: function(respuesta) {
                    $(movimiento.listaOrigen).find('.empty-users-row').remove();
                    $(movimiento.listaOrigen).append(movimiento.fila);
                    actualizarFilaVacia(movimiento.listaDestino, movimiento.mensajeDestino);
                    $('#' + id).prop('checked', !estado);
                    filtrarUsuarios();
                    Swal.fire({
                        title: respuesta?.responseJSON?.message ? 'Acción no permitida' : 'No se pudo actualizar el usuario',
                        text: respuesta?.responseJSON?.message || 'Inténtelo de nuevo. Si el problema continúa, contacte al administrador.',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            });
        }

        function agregar_id_usuario(id) {
            $('#id_usuario').val(id);
        }

        function cambio_password() {
            let id = $('#id_usuario').val();
            let password = $('#newPassword').val();

            $.ajax({
                type: "GET",
                url: "usuarios/cambiar-password/" + id + "/" + password,
                success: function(respuesta) {
                    if (respuesta == 1) {
                        // alert("Contraseña actualizada correctamente.");
                        var modal = bootstrap.Modal.getInstance(document.getElementById(
                        'modalCambiarPassword'));
                        modal.hide();
                        Swal.fire({
                            title: '¡Éxito!',
                            text: 'Se actualizó la contraseña correctamente.',
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        });
                        $('#formPassword')[0].reset();
                    }
                }

            });
            return false;
        }

        function editarUsuario(id) {
            $.ajax({
                url: 'usuarios/' + id + '/edit',
                type: 'GET',
                success: function(usuario) {
                    $('#formEditarUsuario').attr('action', '/usuarios/update/' + usuario.id);
                    $('#edit-nombre').val(usuario.nombre);
                    $('#edit-apellido').val(usuario.apellido);
                    $('#edit-cedula_usuario').val(usuario.cedula_usuario);
                    // $('#edit-user').val(usuario.user);
                    $('#edit-rol').val(usuario.rol);
                    $('#edit-pregunta_seguridad').val(usuario.pregunta_seguridad || '');
                    $('#edit-respuesta_seguridad').val('');
                    $('#modalEditarUsuario').modal('show');
                }
            });
        }

        $(document).ready(function() {
            $('#buscar-usuario').on('input', filtrarUsuarios);
            filtrarUsuarios();

            $(document).on("change", '.form-check-input', function() {
                let id = $(this).attr("id");
                let estado = $(this).is(":checked") ? 1 : 0;

                cambiar_estado(id, estado);
            });

            @if (session('usuario_formulario') === 'crear')
                bootstrap.Modal.getOrCreateInstance(document.getElementById('modalRegistrarUsuario')).show();
            @elseif (session('usuario_formulario') === 'editar')
                $('#formEditarUsuario').attr('action', @json(route('usuarios.update', session('usuario_editar_id'))));
                $('#edit-nombre').val(@json(old('nombre')));
                $('#edit-apellido').val(@json(old('apellido')));
                $('#edit-cedula_usuario').val(@json(old('cedula_usuario')));
                $('#edit-rol').val(@json(old('rol')));
                $('#edit-pregunta_seguridad').val(@json(old('pregunta_seguridad')));
                $('#edit-respuesta_seguridad').val('');
                bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEditarUsuario')).show();
            @endif
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var guideButton = document.getElementById('btn-guide-usuarios');
            if (guideButton && typeof introJs === 'function') {
                guideButton.addEventListener('click', function() {
                    introJs().setOptions({
                        nextLabel: 'Siguiente',
                        prevLabel: 'Anterior',
                        skipLabel: 'Cerrar',
                        doneLabel: 'Finalizar',
                        exitOnOverlayClick: false,
                        exitOnEsc: false,
                        steps: [{
                                element: 'button[data-bs-target="#modalRegistrarUsuario"]',
                                intro: 'Haz clic aquí para abrir el formulario de registro de usuarios.'
                            },
                            {
                                element: '#nombre',
                                intro: 'Nombre: ingresa los nombres del usuario. Solo se permiten letras y espacios.'
                            },
                            {
                                element: '#apellido',
                                intro: 'Apellido: ingresa los apellidos del usuario. Solo se permiten letras y espacios.'
                            },
                            {
                                element: '#cedula_usuario',
                                intro: 'Cédula de identidad: ingresa el número de cédula con hasta 8 dígitos.'
                            },
                            {
                                element: '#user',
                                intro: 'Nombre de usuario: el identificador con el que se iniciará sesión.'
                            },
                            {
                                element: '#password',
                                intro: 'Contraseña: define una clave segura para el acceso.'
                            },
                            {
                                element: '#rol',
                                intro: 'Rol: selecciona el perfil del usuario dentro del sistema.'
                            },
                            {
                                element: '#basic-table',
                                intro: 'Esta es la tabla de usuarios. Desde aquí puedes ver la información y editar cada registro.'
                            }
                        ]
                    }).onbeforechange(function(targetElement) {
                        var fieldSteps = ['nombre', 'apellido', 'cedula_usuario', 'password',
                            'rol'
                        ];
                        var modal = bootstrap.Modal.getOrCreateInstance(document.getElementById(
                            'modalRegistrarUsuario'));
                        if (fieldSteps.includes(targetElement.id)) {
                            modal.show();
                        } else {
                            var instance = bootstrap.Modal.getInstance(document.getElementById(
                                'modalRegistrarUsuario'));
                            if (instance) {
                                instance.hide();
                            }
                        }
                    }).oncomplete(function() {
                        var instance = bootstrap.Modal.getInstance(document.getElementById(
                            'modalRegistrarUsuario'));
                        if (instance) {
                            instance.hide();
                        }
                    }).onexit(function() {
                        var instance = bootstrap.Modal.getInstance(document.getElementById(
                            'modalRegistrarUsuario'));
                        if (instance) {
                            instance.hide();
                        }
                    }).start();
                });
            }
        });
    </script>
@endpush
