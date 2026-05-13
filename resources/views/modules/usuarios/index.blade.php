@extends('layouts.main')
@section('titulo', $titulo)
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

@endsection



@push('scripts')
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

        $(document).ready(function(){
            $('.form-check-input').on("change", function(){
                let id = $(this).attr("id");
                let estado = $(this).is(":checked") ? 1 : 0;

                cambiar_estado(id, estado);
            });
        });
    </script>
@endpush