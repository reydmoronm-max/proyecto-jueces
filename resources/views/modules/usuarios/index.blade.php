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
                </div>
                <div class="card-body p-0">
                <div class="table-responsive mt-4">
                    <table id="basic-table" class="table table-striped mb-0" role="grid">
                        <thead>
                            <tr>
                                <th>Nombre y Apellido</th>
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
@endpush