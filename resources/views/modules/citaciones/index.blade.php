@extends('layouts.main')
@section('titulo', $titulo)
@section('paginaTitulo', $paginaTitulo)
@section('paginaSubtitulo', $paginaSubtitulo)
@section('citacionesActive', $citacionesActive)

@section('contenido')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalSeleccionarDenuncia">
                        <i class=" ri-add-fill"></i> Agregar cita
                    </button>
                </div>
                </div>
                <div class="card-body p-0">
                <div class="table-responsive mt-4">
                    <table id="basic-table" class="table table-striped mb-0" role="grid">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Denunciante</th>
                                <th>Cédula</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($citaciones as $citacion)
                            @php
                                $denunciante = $citacion->expediente->personas->first();
                            @endphp
                            <tr>
                                <td>{{ $citacion->fecha_citacion }}</td>
                                <td>{{ $citacion->hora_citacion }}</td>
                                <td>
                                    {{ $denunciante ? $denunciante->nombres . ' ' . $denunciante->apellidos : '-' }}
                                </td>
                                <td>
                                    {{ $denunciante ? $denunciante->cedula_tipo . $denunciante->cedula : '-' }}
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-primary" onclick="consultarDenuncia({{ $citacion->expediente_id }})">
                                            <i class="ri-eye-fill"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('modules.denuncias.modalDenuncia')
@include('modules.denuncias.modalConsultarDenuncia')
@include('modules.denuncias.modalCita')
@include('modules.citaciones.modalSeleccionarDenuncia')

{{-- <style>
    #modalVerProposito .modal-dialog { max-width: 900px; }
    #modalVerProposito .modal-body {
        white-space: pre-wrap;
        overflow-wrap: break-word;
        word-break: break-word;
        max-height: 60vh;
        overflow-y: auto;
    }
    #modal-proposito-content { white-space: pre-wrap; overflow-wrap: break-word; word-break: break-word; }
</style> --}}

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
        function consultarDenuncia(id) {
            $.ajax({
                url: 'denuncias/' + id,
                type: 'GET',
                success: function(data) {
                    $('#view-cedula_tipo').val(data.cedula_tipo);
                    $('#view-cedula').val(data.cedula);
                    $('#view-nombres').val(data.nombres);
                    $('#view-apellidos').val(data.apellidos);
                    $('#view-telefono').val(data.telefono);
                    $('#view-direccion').val(data.direccion);
                    $('#view-motivo_denuncia').val(data.motivo_denuncia);
                    $('#view-requirente').val(data.requirente);
                    $('#view-receptor').val(data.receptor);
                    $('#view-acuerdos').val(data.acuerdos);

                    var modal = new bootstrap.Modal(document.getElementById('modalConsultarDenuncia'));
                    modal.show();
                }
            });
        }

        function agregar_id_expediente(id){
            $('#cita_expediente_id').val(id);
        }

        $(document).ready(function() {
            $('#formCita').on('submit', function(e) {
                var expedienteId = $('#cita_expediente_id').val().trim();
                var fecha = $('[name="fecha_citacion"]').val().trim();
                var hora = $('[name="hora_citacion"]').val().trim();
                var errors = [];

                if (!expedienteId) {
                    errors.push('Debe seleccionar primero una denuncia para agendar la citación.');
                }

                if (!fecha) {
                    errors.push('La fecha de la citación es obligatoria.');
                }

                if (!hora) {
                    errors.push('La hora de la citación es obligatoria.');
                }

                if (errors.length > 0) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Errores en el formulario',
                        html: errors.join('<br>'),
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                    return false;
                }

                return true;
            });
        });

        @if (session('success')) 
            Swal.fire({
                title: '¡Éxito!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            });
        @endif
    </script>
@endpush
