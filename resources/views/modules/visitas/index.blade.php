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
                <button id="btn-guide-visitas" class="btn btn-outline-secondary btn-icon" type="button" aria-label="Ver guía de visitas">
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
                                <th>Teléfono</th>
                                <th>Dirección</th>
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
@include('modules.visitas.modalEditarVisita')

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

        // Eliminar funcionalidad de borrado: no permitido por diseño
        
        function editarVisita(id) {
            $.ajax({
                url: '/visitas/' + id + '/edit',
                type: 'GET',
                success: function(visita) {
                    $('#formEditarVisita').attr('action', '/visitas/' + visita.id);
                    $('#edit-nombre').val(visita.persona?.nombres ?? '');
                    $('#edit-apellido').val(visita.persona?.apellidos ?? '');
                    $('#edit-cedula_tipo').val(visita.persona?.cedula_tipo ?? 'V');
                    $('#edit-cedula').val(visita.persona?.cedula ?? '');
                    $('#edit-telefono').val(visita.persona?.telefono ?? '');
                    $('#edit-direccion').val(visita.persona?.direccion ?? '');
                    $('#edit-proposito').val(visita.proposito ?? '');
                    $('#edit-de_parte').val(visita.de_parte ?? '');
                    var modalEl = document.getElementById('modalEditarVisita');
                    var modal = new bootstrap.Modal(modalEl);
                    modal.show();
                },
                error: function() {
                    Swal.fire({
                        title: 'Error',
                        text: 'No se pudo cargar la visita para editar.',
                        icon: 'error'
                    });
                }
            });
        }

        // Client-side validation before submitting the registrar visita form
        $(document).ready(function(){
            $('#formRegistrarVisita').on('submit', function(e){
                e.preventDefault();

                var nombre = $('#nombre').val().trim();
                var apellido = $('#apellido').val().trim();
                    var telefono = $('#telefono').val().trim();
                    var direccion = $('#direccion').val().trim();
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

                // Telefono (opcional): solo números, espacios o + - () , max 20
                if (telefono.length > 0 && !/^\+?[0-9\s\-()]{7,20}$/.test(telefono)){
                    errors.push('Teléfono: formato inválido.');
                }

                // Direccion (opcional)
                if (direccion.length > 0 && (direccion.length < 5 || direccion.length > 255)){
                    errors.push('Dirección: entre 5 y 255 caracteres.');
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
            $('#formEditarVisita').on('submit', function(e){
                e.preventDefault();

                var nombre = $('#edit-nombre').val().trim();
                var apellido = $('#edit-apellido').val().trim();
                    var telefono = $('#edit-telefono').val().trim();
                    var direccion = $('#edit-direccion').val().trim();
                var cedula = $('#edit-cedula').val().trim();
                var cedulaTipo = $('#edit-cedula_tipo').val();
                var proposito = $('#edit-proposito').val().trim();

                var errors = [];
                var invalidNameRegex = /[^A-Za-zÀ-ÖØ-öø-ÿ\s]/;

                if (nombre.length < 3 || nombre.length > 50 || invalidNameRegex.test(nombre)){
                    errors.push('Nombre: debe tener entre 3 y 50 caracteres y solo letras.');
                }
                if (apellido.length < 3 || apellido.length > 50 || invalidNameRegex.test(apellido)){
                    errors.push('Apellido: debe tener entre 3 y 50 caracteres y solo letras.');
                }
                if (!/^\d{7,8}$/.test(cedula)){
                    errors.push('Cédula: debe contener solo números (7 u 8 dígitos).');
                }
                if (!(cedulaTipo === 'V' || cedulaTipo === 'E')){
                    errors.push('Tipo de cédula inválido.');
                }
                if (proposito.length < 5){
                    errors.push('Propósito: mínimo 5 caracteres.');
                }

                if (telefono.length > 0 && !/^\+?[0-9\s\-()]{7,20}$/.test(telefono)){
                    errors.push('Teléfono: formato inválido.');
                }
                if (direccion.length > 0 && (direccion.length < 5 || direccion.length > 255)){
                    errors.push('Dirección: entre 5 y 255 caracteres.');
                }

                if (errors.length > 0){
                    Swal.fire({
                        title: 'Errores en el formulario',
                        html: errors.join('<br>'),
                        icon: 'error'
                    });
                    return false;
                }

                this.submit();
            });
            // Inicializar tooltips de Bootstrap para mostrar la dirección completa
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var guideButton = document.getElementById('btn-guide-visitas');
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
                                element: 'button[data-bs-target="#modalRegistrarVisita"]',
                                intro: 'Haz clic aquí para abrir el formulario de registro de visitas.'
                            },
                            {
                                element: '#nombre',
                                intro: 'Nombre: ingresa el nombre de la persona que visita.'
                            },
                            {
                                element: '#apellido',
                                intro: 'Apellido: ingresa el apellido de la persona que visita.'
                            },
                            {
                                element: '#cedula_tipo',
                                intro: 'Tipo de cédula: selecciona V o E según corresponda.'
                            },
                            {
                                element: '#cedula',
                                intro: 'Cédula: ingresa el número de cédula (7 u 8 dígitos).'
                            },
                            {
                                element: '#telefono',
                                intro: 'Teléfono: ingresa un número de contacto.'
                            },
                            {
                                element: '#direccion',
                                intro: 'Dirección: ingresa la dirección de la visita.'
                            },
                            {
                                element: '#proposito',
                                intro: 'Propósito: describe el motivo de la visita.'
                            },
                            {
                                element: '#de_parte',
                                intro: 'De parte: indica de quién es la visita.'
                            },
                            {
                                element: '#basic-table',
                                intro: 'Esta tabla muestra las visitas registradas y sus detalles.'
                            }
                        ]
                    }).onbeforechange(function(targetElement) {
                        var fieldSteps = ['nombre','apellido','cedula_tipo','cedula','telefono','direccion','proposito','de_parte'];
                        var modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalRegistrarVisita'));
                        if (fieldSteps.includes(targetElement.id)) {
                            modal.show();
                        } else {
                            var instance = bootstrap.Modal.getInstance(document.getElementById('modalRegistrarVisita'));
                            if (instance) {
                                instance.hide();
                            }
                        }
                    }).oncomplete(function() {
                        var instance = bootstrap.Modal.getInstance(document.getElementById('modalRegistrarVisita'));
                        if (instance) {
                            instance.hide();
                        }
                    }).onexit(function() {
                        var instance = bootstrap.Modal.getInstance(document.getElementById('modalRegistrarVisita'));
                        if (instance) {
                            instance.hide();
                        }
                    }).start();
                });
            }
        });
    </script>
@endpush