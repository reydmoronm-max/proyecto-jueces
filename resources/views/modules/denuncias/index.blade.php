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
                </div>
                <div class="card-body p-0">
                <div class="table-responsive mt-4">
                    <table id="basic-table" class="table table-striped mb-0" role="grid">
                        <thead>
                            <tr>
                                <th>Fecha de apertura</th>
                                <th>Denunciante</th>
                                <th>Motivo</th>
                                <th>Estado</th>
                                <th>Cita</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Fecha</td>
                                <td>Juan Pérez</td>
                                <td>Deuda</td>
                                <td>Abierto</td>
                                <td>
                                    <button class="btn btn-sm btn-primary">C</button>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-secondary">
                                            C
                                        </button>
                                        <button type="button" class="btn btn-sm btn-warning">
                                            E
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('modules.denuncias.modalDenuncia')

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
