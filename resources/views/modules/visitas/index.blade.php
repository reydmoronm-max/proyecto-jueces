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
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalRegistrarVisita">
                        <i class=" ri-add-fill"></i> Registrar Visita
                    </button>
                </div>
                </div>
                <div class="card-body p-0">
                <div class="table-responsive mt-4">
                    <table id="basic-table" class="table table-striped mb-0" role="grid">
                        <thead>
                            <tr>
                                <th>Nombre y Apellido</th>
                                <th>Cédula</th>
                                <th>De parte</th>
                                <th>Propósito</th>
                                <th>Fecha y Hora</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-visitas">
                            @include('modules.visitas.tbody')
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('modules.visitas.modalVisita')

<style>
    #modalVerProposito .modal-dialog { max-width: 900px; }
    #modalVerProposito .modal-body {
        white-space: pre-wrap;
        overflow-wrap: break-word;
        word-break: break-word;
        max-height: 60vh;
        overflow-y: auto;
    }
    #modal-proposito-content { white-space: pre-wrap; overflow-wrap: break-word; word-break: break-word; }
</style>

<!-- Modal para ver el propósito completo -->
<div class="modal fade" id="modalVerProposito" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Propósito</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modal-proposito-content"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script>
        @if (session('success')) 

            Swal.fire({
                title: '¡Éxito!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            });
            
        @endif
    </script>
    @if ($errors->any())
    <script>
        var erroresServidor = {!! json_encode($errors->all()) !!};
        Swal.fire({
            title: 'Errores en el formulario',
            html: erroresServidor.join('<br>'),
            icon: 'error',
            confirmButtonText: 'Corregir'
        }).then(function(){
            var modalEl = document.getElementById('modalRegistrarVisita');
            var modal = new bootstrap.Modal(modalEl);
            modal.show();
        });
    </script>
    @endif
    <script>
        function recargar_tbody_visitas(){
            $.ajax({
                type : "GET",
                url: "{{ route('tbody.visitas') }}",
                success: function(respuesta){
                    console.log(respuesta);
                }
            });
        }
    </script>
    <script>
        function mostrarProposito(id){
            var nodo = document.getElementById('proposito-text-' + id);
            var contenido = nodo ? nodo.innerText : '';
            document.getElementById('modal-proposito-content').innerText = contenido;
            var modalEl = document.getElementById('modalVerProposito');
            var modal = new bootstrap.Modal(modalEl);
            modal.show();
        }

        function confirmDeleteVisita(id){
            Swal.fire({
                title: '¿Eliminar visita?',
                text: 'Esta acción eliminará la visita de forma permanente.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
                confirmButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
        
        // Client-side validation before submitting the registrar visita form
        $(document).ready(function(){
            $('#formRegistrarVisita').on('submit', function(e){
                e.preventDefault();

                var nombre = $('#nombre').val().trim();
                var apellido = $('#apellido').val().trim();
                var cedula = $('#cedula').val().trim();
                var cedulaTipo = $('#cedula_tipo').val();
                var proposito = $('#proposito').val().trim();

                var errors = [];

                // Nombre y Apellido: letras y espacios, 3-50
                var invalidNameRegex = /[^A-Za-zÀ-ÖØ-öø-ÿ\s]/;
                if (nombre.length < 3 || nombre.length > 50 || invalidNameRegex.test(nombre)){
                    errors.push('Nombre: debe tener entre 3 y 50 caracteres y solo letras.');
                }
                if (apellido.length < 3 || apellido.length > 50 || invalidNameRegex.test(apellido)){
                    errors.push('Apellido: debe tener entre 3 y 50 caracteres y solo letras.');
                }

                // Cédula: solo dígitos, 7-8
                if (!/^\d{7,8}$/.test(cedula)){
                    errors.push('Cédula: debe contener solo números (7 u 8 dígitos).');
                }

                // Cedula tipo
                if (!(cedulaTipo === 'V' || cedulaTipo === 'E')){
                    errors.push('Tipo de cédula inválido.');
                }

                // Propósito: mínimo 5 caracteres
                if (proposito.length < 5){
                    errors.push('Propósito: mínimo 5 caracteres.');
                }

                if (errors.length > 0){
                    Swal.fire({
                        title: 'Errores en el formulario',
                        html: errors.join('<br>'),
                        icon: 'error'
                    });
                    return false;
                }

                // submit if all good
                this.submit();
            });
        });
    </script>
@endpush
