{{-- Modal --}}
<div class="modal fade" id="modalRegistrarDenuncia" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl"> {{-- Cambiado a modal-xl para balancear el diseño de dos columnas --}}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRegistrarDenunciaLabel">Registrar denuncia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-2">
                <form class="needs-validation" novalidate id="formDenuncia" action="{{ route('denuncias.store') }}" autocomplete="off" method="POST">
                    @csrf
                    
                    <div class="row g-4">
                        <div class="col-12 col-lg-5 border-end-lg">
                            <h6 class="text-primary mb-3 border-bottom pb-1">Datos del Requirente</h6>
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input id="cedula" type="number" name="cedula" class="form-control bg-white" placeholder="Cédula" value="{{ old('cedula') }}" required oninput="if(this.value.length>8)this.value=this.value.slice(0,8)">
                                        <label for="cedula">Cédula del requirente</label>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control bg-white" name="nombres" id="nombres" placeholder="Nombres" required pattern="^[A-Za-zÀ-ÖØ-öø-ÿ ]+$" title="Solo letras y espacios" oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ ]+/g, '')">
                                        <label for="nombres">Nombres</label>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control bg-white" name="apellidos" id="apellidos" placeholder="Apellidos" required pattern="^[A-Za-zÀ-ÖØ-öø-ÿ ]+$" title="Solo letras y espacios" oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ ]+/g, '')">
                                        <label for="apellidos">Apellidos</label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-floating">
                                        <input id="telefono" type="number" name="telefono" class="form-control bg-white" placeholder="Teléfono" value="{{ old('telefono') }}" required oninput="if(this.value.length>11)this.value=this.value.slice(0,11)">
                                        <label for="telefono">Teléfono</label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control bg-white" name="motivo_denuncia" id="motivo_denuncia" placeholder="Motivo de denuncia" required>
                                        <label for="motivo_denuncia">Motivo de denuncia</label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control bg-white" name="direccion" id="direccion" placeholder="Dirección" style="height: 80px;" required></textarea>
                                        <label for="direccion">Dirección</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-7">
                            <h6 class="text-primary mb-3 border-bottom pb-1">Declaraciones de la Denuncia</h6>
                            <div class="row g-2">
                                <div class="col-12">
                                    <label for="requirente" class="form-label mb-1 fw-bold text-muted small">El requirente expone:</label>
                                    <textarea name="requirente" id="requirente" class="form-control bg-white" rows="3" required></textarea>
                                </div>

                                <div class="col-12">
                                    <label for="receptor" class="form-label mb-1 fw-bold text-muted small">El receptor expone:</label>
                                    <textarea name="receptor" id="receptor" class="form-control bg-white" rows="3" required></textarea>
                                </div>

                                <div class="col-12">
                                    <label for="acuerdos" class="form-label mb-1 fw-bold text-muted small">Acuerdos:</label>
                                    <textarea name="acuerdos" id="acuerdos" class="form-control bg-white" rows="3" required></textarea>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer mt-3 pb-0 pe-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>