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
                        {{-- <button id="btn-guide-denuncias" class="btn btn-outline-secondary btn-icon" type="button" aria-label="Ver guía de denuncias">
                    <i class="ri-question-line"></i>
                </button> --}}
                        <!-- Botones de pestañas -->
                        <ul class="nav nav-tabs nav-tabs-bordered" id="borderedTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="abierto-tab" data-bs-toggle="tab"
                                    data-bs-target="#bordered-abierto" type="button" role="tab" aria-controls="abierto"
                                    aria-selected="true">Abierto</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="enProceso-tab" data-bs-toggle="tab"
                                    data-bs-target="#bordered-enProceso" type="button" role="tab"
                                    aria-controls="enProceso" aria-selected="false">En proceso</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="cerrado-tab" data-bs-toggle="tab"
                                    data-bs-target="#bordered-cerrado" type="button" role="tab" aria-controls="cerrado"
                                    aria-selected="false">Cerrado</button>
                            </li>
                        </ul>
                        {{-- Fin de botones de pestañas --}}
                    </div>
                    <div class="card-body p-0">
                        {{-- Contenido de las pestañas --}}
                        <div class="tab-content pt-2" id="borderedTabContent">
                            {{-- Contenido de la pestaña "Abierto" --}}
                            <div class="tab-pane fade show active" id="bordered-abierto" role="tabpanel"
                                aria-labelledby="abierto-tab">
                                <div class="table-responsive mt-4">
                                    <table id="basic-table" class="table table-striped mb-0" role="grid">
                                        <thead>
                                            <tr>
                                                <th>Denunciante</th>
                                                <th>Cédula</th>
                                                <th>Fecha de apertura</th>
                                                <th>Descripción del caso</th>
                                                <th>Tipo de caso</th>
                                                <th>Categoría</th>
                                                <th>Denunciado(a)</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($expedientesAbiertos as $expediente)
                                                <tr>
                                                    <td>
                                                        @php
                                                            $denunciantesList = $expediente->personas;
                                                            $primerDenunciante = $denunciantesList->first();
                                                        @endphp
                                                        {{ $primerDenunciante ? $primerDenunciante->nombres . ' ' . $primerDenunciante->apellidos : '-' }}
                                                        @if ($denunciantesList->count() > 1)
                                                            <div class="dropdown d-inline-block ms-1">
                                                                <button class="btn btn-xs btn-outline-primary dropdown-toggle py-0 px-1 border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.75rem; background-color: rgba(58, 87, 232, 0.1); border-radius: 4px;">
                                                                    +{{ $denunciantesList->count() - 1 }} más
                                                                </button>
                                                                <ul class="dropdown-menu shadow-sm border-0 py-1" style="min-width: 220px; font-size: 0.85rem;">
                                                                    <li class="dropdown-header text-muted py-1 px-3" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.5px;">Otros Requirentes</li>
                                                                    @foreach ($denunciantesList->skip(1) as $otro)
                                                                        <li class="px-3 py-1 d-flex justify-content-between align-items-center">
                                                                            <span><i class="ri-user-line me-1 text-primary"></i> {{ $otro->nombres }} {{ $otro->apellidos }}</span>
                                                                            <span class="badge bg-soft-secondary text-secondary ms-2" style="font-size: 0.75rem;">V-{{ $otro->cedula }}</span>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-primary me-1">V</span>
                                                        {{ $primerDenunciante ? $primerDenunciante->cedula : '-' }}
                                                    </td>
                                                    <td>{{ $expediente->created_at->format('d/m/Y h:i A') }}</td>
                                                    <td>{{ $expediente->caso }}</td>
                                                    <td>{{ $expediente->tipo_caso }}</td>
                                                    <td>{{ $expediente->categoria }}</td>
                                                    <td>
                                                        @if($expediente->denunciado_a)
                                                            <span class="text-dark fw-semibold">{{ $expediente->denunciado_a }}</span>
                                                        @else
                                                            <span class="text-muted small">No especificado</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-success">{{ $expediente->estatus }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <a type="button" class="btn btn-sm btn-light" href="{{ route('denuncias.exportar-acta-recepcion', $expediente->id) }}" title="Ver acta de recepción de denuncia">
                                                                <i class="ri-file-pdf-2-line"></i>
                                                            </a>
                                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalCita" onclick="agregar_id_expediente({{ $expediente->id }})" title="Agendar cita">
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
                            <div class="tab-pane fade" id="bordered-enProceso" role="tabpanel"
                                aria-labelledby="enProceso-tab">
                                <div class="table-responsive mt-4">
                                    <table id="basic-table" class="table table-striped mb-0" role="grid">
                                        <thead>
                                            <tr>
                                                <th>Denunciante</th>
                                                <th>Cédula</th>
                                                <th>Fecha de apertura</th>
                                                <th>Descripción del caso</th>
                                                <th>Tipo de caso</th>
                                                <th>Categoría</th>
                                                <th>Denunciado(a)</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($expedientesEnProceso as $expediente)
                                                <tr>
                                                    <td>
                                                        @php
                                                            $denunciantesList = $expediente->personas;
                                                            $primerDenunciante = $denunciantesList->first();
                                                        @endphp
                                                        {{ $primerDenunciante ? $primerDenunciante->nombres . ' ' . $primerDenunciante->apellidos : '-' }}
                                                        @if ($denunciantesList->count() > 1)
                                                            <div class="dropdown d-inline-block ms-1">
                                                                <button class="btn btn-xs btn-outline-primary dropdown-toggle py-0 px-1 border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.75rem; background-color: rgba(58, 87, 232, 0.1); border-radius: 4px;">
                                                                    +{{ $denunciantesList->count() - 1 }} más
                                                                </button>
                                                                <ul class="dropdown-menu shadow-sm border-0 py-1" style="min-width: 220px; font-size: 0.85rem;">
                                                                    <li class="dropdown-header text-muted py-1 px-3" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.5px;">Otros Requirentes</li>
                                                                    @foreach ($denunciantesList->skip(1) as $otro)
                                                                        <li class="px-3 py-1 d-flex justify-content-between align-items-center">
                                                                            <span><i class="ri-user-line me-1 text-primary"></i> {{ $otro->nombres }} {{ $otro->apellidos }}</span>
                                                                            <span class="badge bg-soft-secondary text-secondary ms-2" style="font-size: 0.75rem;">V-{{ $otro->cedula }}</span>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-primary me-1">V</span>
                                                        {{ $primerDenunciante ? $primerDenunciante->cedula : '-' }}
                                                    </td>
                                                    <td>{{ $expediente->created_at->format('d/m/Y h:i A') }}</td>
                                                    <td>{{ $expediente->caso }}</td>
                                                    <td>{{ $expediente->tipo_caso }}</td>
                                                    <td>{{ $expediente->categoria }}</td>
                                                    <td>
                                                        @if($expediente->denunciado_a)
                                                            <span class="text-dark fw-semibold">{{ $expediente->denunciado_a }}</span>
                                                        @else
                                                            <span class="text-muted small">No especificado</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-warning">{{ $expediente->estatus }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <a type="button" class="btn btn-sm btn-light" href="{{ route('denuncias.exportar-acta-recepcion', $expediente->id) }}" title="Ver acta de recepción de denuncia">
                                                                <i class="ri-file-pdf-2-line"></i>
                                                            </a>
                                                            <button type="button" class="btn btn-sm btn-warning"
                                                                data-bs-toggle="modal" data-bs-target="#modalPosponerCita"
                                                                onclick="agregar_id_expediente_posponer({{ $expediente->id }})">
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
                                                <th>Denunciante</th>
                                                <th>Cédula</th>
                                                <th>Fecha de apertura</th>
                                                <th>Descripción del caso</th>
                                                <th>Tipo de caso</th>
                                                <th>Categoría</th>
                                                <th>Denunciado(a)</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($expedientesCerrados as $expediente)
                                                <tr>
                                                    <td>
                                                        @php
                                                            $denunciantesList = $expediente->personas;
                                                            $primerDenunciante = $denunciantesList->first();
                                                        @endphp
                                                        {{ $primerDenunciante ? $primerDenunciante->nombres . ' ' . $primerDenunciante->apellidos : '-' }}
                                                        @if ($denunciantesList->count() > 1)
                                                            <div class="dropdown d-inline-block ms-1">
                                                                <button class="btn btn-xs btn-outline-primary dropdown-toggle py-0 px-1 border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.75rem; background-color: rgba(58, 87, 232, 0.1); border-radius: 4px;">
                                                                    +{{ $denunciantesList->count() - 1 }} más
                                                                </button>
                                                                <ul class="dropdown-menu shadow-sm border-0 py-1" style="min-width: 220px; font-size: 0.85rem;">
                                                                    <li class="dropdown-header text-muted py-1 px-3" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.5px;">Otros Requirentes</li>
                                                                    @foreach ($denunciantesList->skip(1) as $otro)
                                                                        <li class="px-3 py-1 d-flex justify-content-between align-items-center">
                                                                            <span><i class="ri-user-line me-1 text-primary"></i> {{ $otro->nombres }} {{ $otro->apellidos }}</span>
                                                                            <span class="badge bg-soft-secondary text-secondary ms-2" style="font-size: 0.75rem;">V-{{ $otro->cedula }}</span>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-primary me-1">V</span>
                                                        {{ $primerDenunciante ? $primerDenunciante->cedula : '-' }}
                                                    </td>
                                                    <td>{{ $expediente->created_at->format('d/m/Y h:i A') }}</td>
                                                    <td>{{ $expediente->caso }}</td>
                                                    <td>{{ $expediente->tipo_caso }}</td>
                                                    <td>{{ $expediente->categoria }}</td>
                                                    <td>
                                                        @if($expediente->denunciado_a)
                                                            <span class="text-dark fw-semibold">{{ $expediente->denunciado_a }}</span>
                                                        @else
                                                            <span class="text-muted small">No especificado</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge bg-light text-dark">{{ $expediente->estatus }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="ri-file-pdf-2-line"></i> Actas
                                                            </button>
                                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                                <li>
                                                                    <a type="button" class="dropdown-item" href="{{ route('denuncias.exportar-acta-recepcion', $expediente->id) }}">
                                                                        Ver acta de recepción
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a type="button" class="dropdown-item" href="{{ route('denuncias.exportar-acta-conciliacion', $expediente->id) }}">
                                                                        Ver acta de conciliación
                                                                    </a>
                                                                </li>
                                                            </ul>
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
        /*function consultarDenuncia(id) {
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
        */

        function agregar_id_expediente(id) {
            $('#cita_expediente_id').val(id);
        }

        // Estado usado por la modal para saber si el expediente ya tiene un 'denunciado'
        window.posponer_has_denunciado = false;

        function updatePosponerFields() {
            var selected = $('#solicita_por_posponer').val();
            if (selected === 'denunciado' && window.posponer_has_denunciado || selected === 'denunciante') {
                // ocultar campos personales
                $('#posponer_person_fields').hide();
                // quitar required para evitar validación al esconder
                $('#posponer_person_fields').find('input,select').prop('required', false);
            } else {
                $('#posponer_person_fields').show();
                $('#posponer_person_fields').find('input,select').prop('required', true);
            }
        }

        $('#solicita_por_posponer').on('change', function() {
            updatePosponerFields();
        });

        function agregar_id_expediente_posponer(id) {
            $('#cita_expediente_id_posponer').val(id);
            // Por defecto asumimos que no hay denunciado hasta consultar
            window.posponer_has_denunciado = false;
            // reset select
            $('#solicita_por_posponer').val('denunciante');
            // Consultar si el expediente tiene un 'denunciado' ya registrado
            $.get('/expedientes/' + id + '/tiene-denunciado', function(data) {
                if (data && data.hasDenunciado) {
                    window.posponer_has_denunciado = true;
                } else {
                    window.posponer_has_denunciado = false;
                }
            }).always(function() {
                updatePosponerFields();
            });
        }




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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var guideButton = document.getElementById('btn-guide-denuncias');
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
                                element: 'button[data-bs-target="#modalRegistrarDenuncia"]',
                                intro: 'Haz clic aquí para abrir el formulario de recepción de denuncias.'
                            },
                            {
                                element: '#borderedTab',
                                intro: 'Estas pestañas te permiten ver denuncias abiertas, en proceso o cerradas.'
                            },
                            {
                                element: '#cedula_tipo',
                                intro: 'Tipo de cédula: selecciona V o E según el denunciante.'
                            },
                            {
                                element: '#cedula',
                                intro: 'Cédula del denunciante: ingresa el número de cédula con hasta 8 dígitos.'
                            },
                            {
                                element: '#nombres',
                                intro: 'Nombres: ingresa los nombres del denunciante.'
                            },
                            {
                                element: '#apellidos',
                                intro: 'Apellidos: ingresa los apellidos del denunciante.'
                            },
                            {
                                element: '#telefono',
                                intro: 'Teléfono: ingresa un número de contacto del denunciante.'
                            },
                            {
                                element: '#direccion',
                                intro: 'Dirección: ingresa la dirección del denunciante.'
                            },
                            {
                                element: '#motivo_denuncia',
                                intro: 'Motivo de denuncia: describe el motivo principal de la denuncia.'
                            },
                            {
                                element: '#requirente',
                                intro: 'El denunciante expone: escribe lo que dice la persona que presenta la denuncia.'
                            },
                            {
                                element: '#receptor',
                                intro: 'El receptor expone: escribe la respuesta o registro hecho por el receptor.'
                            },
                            {
                                element: '#acuerdos',
                                intro: 'Acuerdos: registra los acuerdos o pasos a seguir tras recibir la denuncia.'
                            },
                            {
                                element: '#basic-table',
                                intro: 'Esta tabla muestra los expedientes de la pestaña activa.'
                            }
                        ]
                    }).onbeforechange(function(targetElement) {
                        var fieldSteps = ['cedula_tipo', 'cedula', 'nombres', 'apellidos',
                            'telefono', 'direccion', 'motivo_denuncia', 'requirente',
                            'receptor', 'acuerdos'
                        ];
                        var modal = bootstrap.Modal.getOrCreateInstance(document.getElementById(
                            'modalRegistrarDenuncia'));
                        if (fieldSteps.includes(targetElement.id)) {
                            modal.show();
                        } else {
                            var instance = bootstrap.Modal.getInstance(document.getElementById(
                                'modalRegistrarDenuncia'));
                            if (instance) {
                                instance.hide();
                            }
                        }
                    }).oncomplete(function() {
                        var instance = bootstrap.Modal.getInstance(document.getElementById(
                            'modalRegistrarDenuncia'));
                        if (instance) {
                            instance.hide();
                        }
                    }).onexit(function() {
                        var instance = bootstrap.Modal.getInstance(document.getElementById(
                            'modalRegistrarDenuncia'));
                        if (instance) {
                            instance.hide();
                        }
                    }).start();
                });
            }
        });
    </script>

    {{-- <script>
        new TomSelect("#tipo_caso",{
            sortField: {
                field: "text"
            }
        });

        new TomSelect("#descripcion",{
            sortField: {
                field: "text",
                direction: "asc"
            }
        });
    </script> --}}
@endpush
