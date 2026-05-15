{{-- Modal --}}
<div class="modal fade" id="modalRegistrarDenuncia" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalRegistrarDenunciaLabel">Registrar denuncia</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form class="needs-validation" novalidate id="formDenuncia" action="{{ route('denuncias.store') }}" autocomplete="off" method="POST">
                            @csrf
                            <section class="row g-3">                            
                                <div class="mb-1">
                                    <div class="row g-2">
                                        <div class="col-3">
                                            <div class="form-floating">
                                            <select id="cedula_tipo" name="cedula_tipo" class="form-select">
                                                <option value="V" {{ old('cedula_tipo', 'V') == 'V' ? 'selected' : '' }}>V</option>
                                                <option value="E" {{ old('cedula_tipo') == 'E' ? 'selected' : '' }}>E</option>
                                            </select>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-floating">
                                                <input id="cedula" type="number" name="cedula" class="form-control" placeholder="Cédula" value="{{ old('cedula') }}" required oninput="if(this.value.length>8)this.value=this.value.slice(0,8)"> 
                                                <label for="cedula">Cédula del denunciante</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control bg-white" name="nombres" id="nombres" placeholder="Nombres" required>
                                        <label for="nombres">Nombres</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control bg-white" name="apellidos" id="apellidos" placeholder="Apellidos" required>
                                        <label for="apellidos">Apellidos</label>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                            <div class="form-floating">
                                                <input id="telefono" type="number" name="telefono" class="form-control" placeholder="Teléfono" value="{{ old('telefono') }}" required oninput="if(this.value.length>11)this.value=this.value.slice(0,11)"> 
                                                <label for="telefono">Teléfono</label>
                                            </div>
                                        </div>

                                <div class="col-md-8">
                                    <div class="form-floating">
                                        <input type="text" class="form-control bg-white" name="direccion" id="direccion" placeholder="Dirección" required>
                                        <label for="direccion">Dirección</label>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label for="requirente">El requirente expone</label>
                                    <textarea name="requirente" id="requirente" class="form-control" rows="3"></textarea>
                                </div>

                                <div class="col-md-12">
                                    <label for="receptor">El receptor expone</label>
                                    <textarea name="receptor" id="receptor" class="form-control" rows="3"></textarea>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="acuerdos">Acuerdos</label>
                                    <textarea name="acuerdos" id="acuerdos" class="form-control" rows="3"></textarea>
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