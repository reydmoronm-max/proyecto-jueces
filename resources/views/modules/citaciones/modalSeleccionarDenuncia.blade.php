{{-- Modal de selección de denuncia para crear cita --}}
<div class="modal fade" id="modalSeleccionarDenuncia" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Seleccionar denuncia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Denunciante</th>
                                <th>Motivo</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($expedientes as $expediente)
                                <tr>
                                    <td>{{ $expediente->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @php $denunciante = $expediente->personas->first(); @endphp
                                        {{ $denunciante ? $denunciante->nombres . ' ' . $denunciante->apellidos : '-' }}
                                    </td>
                                    <td>{{ $expediente->motivo_denuncia }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" data-bs-dismiss="modal" onclick="agregar_id_expediente({{ $expediente->id }}); var cita = new bootstrap.Modal(document.getElementById('modalCita')); cita.show();">
                                            Seleccionar
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>