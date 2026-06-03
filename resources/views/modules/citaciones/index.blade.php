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
                {{-- <div class="header-title">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalSeleccionarDenuncia">
                        <i class=" ri-add-fill"></i> Agregar cita
                    </button>
                </div> --}}
                </div>
                @if(request()->boolean('hoy'))
                    <div class="alert alert-info mb-3 mx-4">
                        Mostrando citaciones pendientes para hoy.
                    </div>
                @endif
                <div class="card-body p-0">
                <div class="table-responsive mt-4">
                    <table id="basic-table" class="table table-striped mb-0" role="grid">
                        <thead>
                            <tr>
                                <th>Requirente</th>
                                <th>Cédula</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($citaciones as $citacion)
                            @php
                                $denunciante = $citacion->expediente->personas->first();
                            @endphp
                            <tr>
                                <td>
                                    {{ $denunciante ? $denunciante->nombres . ' ' . $denunciante->apellidos : '-' }}
                                </td>
                                <td>
                                    <span class="badge bg-primary me-1">V</span> {{ $denunciante ? $denunciante->cedula : '-' }}
                                </td>
                                <td>{{ $citacion->fecha_citacion->format('d/m/Y') }}</td>
                                <td>{{ $citacion->hora_citacion }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-light" title="Ver denuncia" onclick="consultarDenuncia({{ $citacion->expediente_id }})">
                                            <i class="ri-eye-fill"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-primary" title="Conciliar" onclick="">
                                            <i class=" ri-check-fill"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-warning" title="Marcar como inasistente" data-bs-toggle="modal" data-bs-target="#modalCitaNueva" onclick="agregar_id_expediente({{ $citacion->expediente_id }})">
                                            <i class="ri-alert-fill"></i>
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

{{-- @include('modules.denuncias.modalDenuncia') --}}
@include('modules.denuncias.modalConsultarDenuncia')
{{-- @include('modules.denuncias.modalCita')
@include('modules.citaciones.modalSeleccionarDenuncia') --}}
@include('modules.citaciones.modalCitaNueva')

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
