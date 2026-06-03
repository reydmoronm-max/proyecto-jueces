{{-- Modal --}}
<div class="modal fade" id="modalConciliacion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalConciliacionLabel">Conciliar denuncia</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form class="needs-validation" novalidate id="formConciliacion" action="{{ route('citaciones.conciliar') }}" autocomplete="off" method="POST">
                            @csrf
                            <section class="row g-3">   
                                
                                <input type="text" name="expediente_id" id="cita_expediente_id_conciliar" hidden>
                                
                                <div id="datos_denunciado_container" class="row g-3">
                                <div class="mb-1">
                                    <div class="row g-2">
                                        <div class="col-3">
                                            <div class="form-floating">
                                            <select id="cedula_tipo" name="cedula_tipo" class="form-select bg-white" required>
                                                <option value="V" {{ old('cedula_tipo', 'V') == 'V' ? 'selected' : '' }}>V</option>
                                                <option value="E" {{ old('cedula_tipo') == 'E' ? 'selected' : '' }}>E</option>
                                            </select>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-floating">
                                                <input id="cedula" type="number" name="cedula" class="form-control bg-white" placeholder="Cédula" value="{{ old('cedula') }}" required oninput="if(this.value.length>8)this.value=this.value.slice(0,8)"> 
                                                <label for="cedula">Cédula del requirente</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control bg-white" name="nombres" id="nombres" placeholder="Nombres" required pattern="^[A-Za-zÀ-ÖØ-öø-ÿ ]+$" title="Solo letras y espacios" oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ ]+/g, '')">
                                        <label for="nombres">Nombres</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control bg-white" name="apellidos" id="apellidos" placeholder="Apellidos" required pattern="^[A-Za-zÀ-ÖØ-öø-ÿ ]+$" title="Solo letras y espacios" oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ ]+/g, '')">
                                        <label for="apellidos">Apellidos</label>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                            <div class="form-floating">
                                                <input id="telefono" type="number" name="telefono" class="form-control bg-white" placeholder="Teléfono" value="{{ old('telefono') }}" required oninput="if(this.value.length>11)this.value=this.value.slice(0,11)"> 
                                                <label for="telefono">Teléfono</label>
                                            </div>
                                        </div>

                                <div class="col-md-8">
                                    <div class="form-floating">
                                        <input type="text" class="form-control bg-white" name="direccion" id="direccion" placeholder="Dirección" required>
                                        <label for="direccion">Dirección</label>
                                    </div>
                                </div>
                                </div>

                                <h6>Hechos</h6>

                                <div class="col-md-12">
                                    <label for="requirente">Requirente:</label>
                                    <textarea name="requirente" id="requirente" class="form-control bg-white" rows="3" required></textarea>
                                </div>

                                <div class="col-md-12">
                                    <label for="requerido">Requerido:</label>
                                    <textarea name="requerido" id="requerido" class="form-control bg-white" rows="3" required></textarea>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="coordinador">El coordinador una vez escuchados los hechos expone:</label>
                                    <textarea name="coordinador" id="coordinador" class="form-control bg-white" rows="3" required></textarea>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="acuerdos">Acuerdos para el cumplimiento voluntario por las partes:</label>
                                    <textarea name="acuerdos" id="acuerdos" class="form-control bg-white" rows="3" required></textarea>
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