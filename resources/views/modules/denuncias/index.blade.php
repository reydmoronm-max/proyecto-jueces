@extends('layouts.main')
@section('titulo', $titulo)
@section('paginaTitulo', $paginaTitulo)
@section('paginaSubtitulo', $paginaSubtitulo)
@section('denunciasActive', $denunciasActive)

@section('contenido')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalRegistrarDenuncia">
                        <i class=" ri-add-fill"></i> Recepcionar denuncia
                    </button>
                </div>
                <!-- Botones de pestañas -->
                    <ul class="nav nav-tabs nav-tabs-bordered" id="borderedTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="abierto-tab" data-bs-toggle="tab" data-bs-target="#bordered-abierto" type="button" role="tab" aria-controls="abierto" aria-selected="true">Abierto</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="enProceso-tab" data-bs-toggle="tab" data-bs-target="#bordered-enProceso" type="button" role="tab" aria-controls="enProceso" aria-selected="false">En proceso</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="cerrado-tab" data-bs-toggle="tab" data-bs-target="#bordered-cerrado" type="button" role="tab" aria-controls="cerrado" aria-selected="false">Cerrado</button>
                        </li>
                    </ul>
                {{-- Fin de botones de pestañas --}}
                </div>
                <div class="card-body p-0">
                    {{-- Contenido de las pestañas --}}
                    <div class="tab-content pt-2" id="borderedTabContent">
                        {{-- Contenido de la pestaña "Abierto" --}}
                        <div class="tab-pane fade show active" id="bordered-abierto" role="tabpanel" aria-labelledby="abierto-tab">
                            <div class="table-responsive mt-4">
                                <table id="basic-table" class="table table-striped mb-0" role="grid">
                                    <thead>
                                        <tr>
                                            <th>Fecha de apertura</th>
                                            <th>Requirente</th>
                                            <th>Motivo</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($expedientesAbiertos as $expediente)
                                            <tr>
                                                <td>{{ $expediente->created_at->format('d/m/Y h:i A') }}</td>
                                                <td>
                                                    @php
                                                        $denunciante = $expediente->personas->first();
                                                    @endphp
                                                    {{ $denunciante ? $denunciante->nombres . ' ' . $denunciante->apellidos : '-' }}
                                                </td>
                                                <td>{{ $expediente->motivo_denuncia }}</td>
                                                <td>
                                                    <span class="badge bg-success">{{ $expediente->estatus }}</span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-sm btn-light" onclick="consultarDenuncia({{ $expediente->id }})">
                                                            <i class="ri-eye-fill"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalCita" onclick="agregar_id_expediente({{ $expediente->id }})">
                                                            <i class="ri-calendar-2-fill"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{-- Fin de pestaña "Abierto" --}}

                        {{-- Pestaña "En proceso" --}}
                        <div class="tab-pane fade" id="bordered-enProceso" role="tabpanel" aria-labelledby="enProceso-tab">
                            <div class="table-responsive mt-4">
                                <table id="basic-table" class="table table-striped mb-0" role="grid">
                                    <thead>
                                        <tr>
                                            <th>Fecha de apertura</th>
                                            <th>Requirente</th>
                                            <th>Motivo</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($expedientesEnProceso as $expediente)
                                            <tr>
                                                <td>{{ $expediente->created_at->format('d/m/Y h:i A') }}</td>
                                                <td>
                                                    @php
                                                        $denunciante = $expediente->personas->first();
                                                    @endphp
                                                    {{ $denunciante ? $denunciante->nombres . ' ' . $denunciante->apellidos : '-' }}
                                                </td>
                                                <td>{{ $expediente->motivo_denuncia }}</td>
                                                <td>
                                                    <span class="badge bg-warning">{{ $expediente->estatus }}</span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-sm btn-light" onclick="consultarDenuncia({{ $expediente->id }})">
                                                            <i class="ri-eye-fill"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalPosponerCita" onclick="agregar_id_expediente_posponer({{ $expediente->id }})">
                                                            <i class="ri-calendar-event-fill"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{-- Fin de pestaña "En proceso" --}}

                        {{-- Pestaña "Cerrado" --}}
                        <div class="tab-pane fade" id="bordered-cerrado" role="tabpanel" aria-labelledby="cerrado-tab">
                            <div class="table-responsive mt-4">
                                <table id="basic-table" class="table table-striped mb-0" role="grid">
                                    <thead>
                                        <tr>
                                            <th>Fecha de apertura</th>
                                            <th>Requirente</th>
                                            <th>Motivo</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($expedientesCerrados as $expediente)
                                            <tr>
                                                <td>{{ $expediente->created_at->format('d/m/Y h:i A') }}</td>
                                                <td>
                                                    @php
                                                        $denunciante = $expediente->personas->first();
                                                    @endphp
                                                    {{ $denunciante ? $denunciante->nombres . ' ' . $denunciante->apellidos : '-' }}
                                                </td>
                                                <td>{{ $expediente->motivo_denuncia }}</td>
                                                <td>
                                                    <span class="badge bg-light">{{ $expediente->estatus }}</span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-sm btn-light" onclick="consultarDenuncia({{ $expediente->id }})">
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
                        {{-- Fin de pestaña "Cerrado" --}}
                    </div>
                    {{-- Fin de contenido de las pestañas --}}
                </div>
            </div>
        </div>
    </div>
</div>

@include('modules.denuncias.modalDenuncia')
@include('modules.denuncias.modalConsultarDenuncia')
@include('modules.denuncias.modalCita')
@include('modules.denuncias.modalPosponerCita')

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

        function agregar_id_expediente_posponer(id){
            $('#cita_expediente_id_posponer').val(id);
        }

        function buscarPersonaEnVisitas() {
            var cedula = $('#cedula').val().trim();
            if (!/^[0-9]{7,8}$/.test(cedula)) {
                return;
            }

            $.ajax({
                url: '{{ route('denuncias.buscar-persona') }}',
                method: 'GET',
                data: {
                    cedula_tipo: $('#cedula_tipo').val(),
                    cedula: cedula
                },
                success: function(data) {
                    $('#cedula_tipo').val(data.cedula_tipo);
                    $('#nombres').val(data.nombres);
                    $('#apellidos').val(data.apellidos);
                    $('#telefono').val(data.telefono);
                    $('#direccion').val(data.direccion);
                },
                error: function(xhr) {
                    if (xhr.status === 404) {
                        // No se encontró persona en visitas, el usuario puede completar manualmente.
                    }
                }
            });
        }

        function buscarPersonaEnVisitasRequerido() {
            var cedula = $('#cedulaRequerido').val().trim();
            if (!/^[0-9]{7,8}$/.test(cedula)) {
                return;
            }

            $.ajax({
                url: '{{ route('denuncias.buscar-persona') }}',
                method: 'GET',
                data: {
                    cedula_tipo: $('#cedula_tipoRequerido').val(),
                    cedula: cedula
                },
                success: function(data) {
                    $('#cedula_tipoRequerido').val(data.cedula_tipo);
                    $('#nombresRequerido').val(data.nombres);
                    $('#apellidosRequerido').val(data.apellidos);
                    $('#telefonoRequerido').val(data.telefono);
                    $('#direccionRequerido').val(data.direccion);
                },
                error: function(xhr) {
                    if (xhr.status === 404) {
                        // No se encontró persona en visitas, el usuario puede completar manualmente.
                    }
                }
            });
        }

        $('#cedula').on('blur', function() {
            buscarPersonaEnVisitas();
        });

        $('#cedula_tipo').on('change', function() {
            buscarPersonaEnVisitas();
        });

        $('#cedulaRequerido').on('blur', function() {
            buscarPersonaEnVisitasRequerido();
        });

        $('#cedula_tipoRequerido').on('change', function() {
            buscarPersonaEnVisitasRequerido();
        });
        

        @if (session('success')) 
            Swal.fire({
                title: '¡Éxito!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            });
        @endif

        @if (session('validar')) 
            Swal.fire({
                title: 'Error',
                text: '{{ session('validar') }}',
                icon: 'warning',
                confirmButtonText: 'Aceptar'
            });
        @endif
    </script>
@endpush
