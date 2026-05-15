{{-- Modal --}}
<div class="modal fade" id="modalConsultarDenuncia" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalConsultarDenunciaLabel">Detalles de denuncia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" novalidate id="formConsultarDenuncia" autocomplete="off" method="POST">
                                @csrf
                                <section class="row g-3">                            
                                    <div class="mb-1">
                                        <div class="row g-2">
                                            <div class="col-3">
                                                <div class="form-floating">
                                                <select id="view-cedula_tipo" name="cedula_tipo" class="form-select" disabled>
                                                    <option value="V" {{ old('cedula_tipo', 'V') == 'V' ? 'selected' : '' }}>V</option>
                                                    <option value="E" {{ old('cedula_tipo') == 'E' ? 'selected' : '' }}>E</option>
                                                </select>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-floating">
                                                    <input id="view-cedula" type="number" name="cedula" class="form-control" placeholder="Cédula" value="{{ old('cedula') }}" required oninput="if(this.value.length>8)this.value=this.value.slice(0,8)" disabled> 
                                                    <label for="view-cedula">Cédula del denunciante</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control bg-white" name="nombres" id="view-nombres" placeholder="Nombres" required disabled>
                                            <label for="view-nombres">Nombres</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control bg-white" name="apellidos" id="view-apellidos" placeholder="Apellidos" required disabled>
                                            <label for="view-apellidos">Apellidos</label>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                                <div class="form-floating">
                                                    <input id="view-telefono" type="number" name="telefono" class="form-control" placeholder="Teléfono" value="{{ old('telefono') }}" required oninput="if(this.value.length>11)this.value=this.value.slice(0,11)" disabled>
                                                    <label for="view-telefono">Teléfono</label>
                                                </div>
                                            </div>

                                    <div class="col-md-8">
                                        <div class="form-floating">
                                            <input type="text" class="form-control bg-white" name="direccion" id="view-direccion" placeholder="Dirección" required disabled>
                                            <label for="view-direccion">Dirección</label>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control bg-white" name="motivo_denuncia" id="view-motivo_denuncia" placeholder="Motivo de denuncia" required disabled>
                                            <label for="view-motivo_denuncia">Motivo de denuncia</label>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <label for="view-requirente">El requirente expone</label>
                                        <textarea name="requirente" id="view-requirente" class="form-control" rows="3" disabled></textarea>
                                    </div>

                                    <div class="col-md-12">
                                        <label for="view-receptor">El receptor expone</label>
                                        <textarea name="receptor" id="view-receptor" class="form-control" rows="3" disabled></textarea>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label for="view-acuerdos">Acuerdos</label>
                                        <textarea name="acuerdos" id="view-acuerdos" class="form-control" rows="3" disabled></textarea>
                                    </div>

                                </section>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </form>
            </div>
        </div>
    </div>
</div>