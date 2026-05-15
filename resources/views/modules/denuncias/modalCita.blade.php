{{-- Modal --}}
<div class="modal fade" id="modalCita" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalCitaLabel">Agendar citación</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form class="needs-validation" novalidate id="formCita" action="{{ route('citaciones.store') }}" autocomplete="off" method="POST">
                            @csrf
                            <section class="row g-3">  
                                
                                <input type="text" name="expediente_id" id="cita_expediente_id" hidden>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="mb-2" for="start">Hora</label>
                                        <input required type="text" name="hora_citacion" class="form-control time_flatpicker" placeholder="Hora de la citación">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="input-group wrap_flatpicker">
                                            <input required type="text" name="fecha_citacion" class="form-control" placeholder="Fecha de la citación" data-input> <!-- input is mandatory -->

                                            <a class="input-group-text input-button" title="limpiar" data-clear href="javascript:void(0)">
                                                <svg width="24" class="icon-24" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                            </section>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </form>
        </div>
    </div>
</div>
</div>