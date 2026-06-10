{{-- Modal --}}
<div class="modal fade" id="modalConsultarDenuncia" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl"> {{-- Cambiado a modal-xl para lectura limpia en dos columnas --}}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalConsultarDenunciaLabel">Detalles de denuncia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-2">
                <form class="needs-validation" novalidate id="formConsultarDenuncia" autocomplete="off" method="POST">
                    @csrf
                    <div class="row g-4">
                        <div class="col-12 col-lg-5 border-end-lg">
                            <h6 class="text-primary mb-3 border-bottom pb-1">Datos del Requirente</h6>
                            <div class="row g-3">
                                
                                <div class="col-12">
                                    <label for="view-cedula" class="form-label mb-1 fw-bold text-muted small">Cédula del requirente</label>
                                    <div class="input-group">
                                        <select id="view-cedula_tipo" name="cedula_tipo" class="form-select bg-white" style="max-width: 80px;" disabled>
                                            <option value="V" {{ old('cedula_tipo', 'V') == 'V' ? 'selected' : '' }}>V</option>
                                            <option value="E" {{ old('cedula_tipo') == 'E' ? 'selected' : '' }}>E</option>
                                        </select>
                                        <input id="view-cedula" type="number" name="cedula" class="form-control bg-white" placeholder="Cédula" value="{{ old('cedula') }}" required oninput="if(this.value.length>8)this.value=this.value.slice(0,8)" disabled> 
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control bg-white" name="nombres" id="view-nombres" placeholder="Nombres" required disabled>
                                        <label for="view-nombres">Nombres</label>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control bg-white" name="apellidos" id="view-apellidos" placeholder="Apellidos" required disabled>
                                        <label for="view-apellidos">Apellidos</label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-floating">
                                        <input id="view-telefono" type="number" name="telefono" class="form-control bg-white" placeholder="Teléfono" value="{{ old('telefono') }}" required oninput="if(this.value.length>11)this.value=this.value.slice(0,11)" disabled>
                                        <label for="view-telefono">Teléfono</label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control bg-white" name="motivo_denuncia" id="view-motivo_denuncia" placeholder="Motivo de denuncia" required disabled>
                                        <label for="view-motivo_denuncia">Motivo de denuncia</label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control bg-white" name="direccion" id="view-direccion" placeholder="Dirección" style="height: 80px;" disabled></textarea>
                                        <label for="view-direccion">Dirección</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-7">
                            <h6 class="text-primary mb-3 border-bottom pb-1">Declaraciones Registradas</h6>
                            <div class="row g-2">
                                <div class="col-12">
                                    <label for="view-requirente" class="form-label mb-1 fw-bold text-muted small">El requirente expone:</label>
                                    <textarea name="requirente" id="view-requirente" class="form-control bg-white" rows="2" disabled></textarea>
                                </div>

                                <div class="col-12">
                                    <label for="view-receptor" class="form-label mb-1 fw-bold text-muted small">El receptor expone:</label>
                                    <textarea name="receptor" id="view-receptor" class="form-control bg-white" rows="2" disabled></textarea>
                                </div>

                                <div class="col-12">
                                    <label for="view-acuerdos" class="form-label mb-1 fw-bold text-muted small">Acuerdos:</label>
                                    <textarea name="acuerdos" id="view-acuerdos" class="form-control bg-white" rows="2" disabled></textarea>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer mt-3 pb-0 pe-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>