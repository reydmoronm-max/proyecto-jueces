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
                <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalRegistrarUsuario">
                        <i class=" ri-add-fill"></i> Registrar Usuario
                    </button>
                </div>
                <button id="btn-guide-usuarios" class="btn btn-outline-secondary btn-icon" type="button" aria-label="Ver guía de usuarios">
                    <i class="ri-question-line"></i>
                </button>
                </div>
                <div class="card-body p-0">
                <div class="table-responsive mt-4">
                    <table id="basic-table" class="table table-striped mb-0" role="grid">
                        <thead>
                            <tr>
                                <th>Nombre y Apellido</th>
                                <th>Cédula</th>
                                <th>Usuario</th>
                                <th>Contraseña</th>
                                <th>Activo</th>
                                <th>Rol</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-usuarios">
                            @include('modules.usuarios.tbody')
                        </tbody>
                    </table>
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
    </script>
    <script>
        function recargar_tbody(){
            $.ajax({
                type : "GET",
                url: "{{ route('tbody') }}",
                success: function(respuesta){
                    console.log(respuesta);
                }
            });
        }

        function cambiar_estado(id, estado){
            $.ajax({
                type : "GET",
                url : "usuarios/cambiar-estado/" + id + "/" + estado,
                success: function(respuesta){
                    console.log(respuesta);
                }
            });
        }

        function agregar_id_usuario(id){
            $('#id_usuario').val(id);
        }

        function cambio_password(){
            let id = $('#id_usuario').val();
            let password = $('#newPassword').val();

            $.ajax({
                type : "GET",
                url : "usuarios/cambiar-password/" + id + "/" + password,
                success: function(respuesta){
                    if (respuesta == 1){
                        // alert("Contraseña actualizada correctamente.");
                        var modal = bootstrap.Modal.getInstance(document.getElementById('modalCambiarPassword'));
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
                    $('#edit-user').val(usuario.user);
                    $('#edit-rol').val(usuario.rol);
                    $('#modalEditarUsuario').modal('show');
                }
            });
        }

        $(document).ready(function(){
            $('.form-check-input').on("change", function(){
                let id = $(this).attr("id");
                let estado = $(this).is(":checked") ? 1 : 0;

                cambiar_estado(id, estado);
            });
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
                        steps: [
                            {
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
                        var fieldSteps = ['nombre','apellido','cedula_usuario','user','password','rol'];
                        var modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalRegistrarUsuario'));
                        if (fieldSteps.includes(targetElement.id)) {
                            modal.show();
                        } else {
                            var instance = bootstrap.Modal.getInstance(document.getElementById('modalRegistrarUsuario'));
                            if (instance) {
                                instance.hide();
                            }
                        }
                    }).oncomplete(function() {
                        var instance = bootstrap.Modal.getInstance(document.getElementById('modalRegistrarUsuario'));
                        if (instance) {
                            instance.hide();
                        }
                    }).onexit(function() {
                        var instance = bootstrap.Modal.getInstance(document.getElementById('modalRegistrarUsuario'));
                        if (instance) {
                            instance.hide();
                        }
                    }).start();
                });
            }
        });
    </script>
@endpush