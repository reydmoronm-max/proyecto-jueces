<div class="modal fade" id="modalEditarVisita" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Visita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditarVisita" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <section class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="form-floating">
                                <input id="edit-cedula" type="text" name="cedula" class="form-control" placeholder="Cédula" required maxlength="8" inputmode="numeric" pattern="^[0-9]{7,8}$" title="Solo números, hasta 8 dígitos" oninput="this.value = this.value.replace(/\D/g, '').slice(0, 8)">
                                <label for="edit-cedula" class="form-label">Cédula</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-floating">
                                <input id="edit-telefono" type="text" name="telefono" class="form-control" placeholder="Teléfono" maxlength="20" inputmode="tel">
                                <label for="edit-telefono">Teléfono</label>
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-6">
                            <div class="form-floating">
                                <input id="edit-nombre" type="text" name="nombre" class="form-control" placeholder="Nombre" required maxlength="50" pattern="^[A-Za-zÀ-ÖØ-öø-ÿ ]+$" title="Solo letras y espacios" oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ ]+/g, '')">
                                <label for="edit-nombre">Nombre</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-floating">
                                <input id="edit-apellido" type="text" name="apellido" class="form-control" placeholder="Apellido" required maxlength="50" pattern="^[A-Za-zÀ-ÖØ-öø-ÿ ]+$" title="Solo letras y espacios" oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ ]+/g, '')">
                                <label for="edit-apellido">Apellido</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-floating">
                                <textarea id="edit-direccion" name="direccion" class="form-control" placeholder="Dirección" rows="3"></textarea>
                                <label for="edit-direccion">Dirección</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="edit-proposito" class="form-label">Propósito</label>
                            <textarea id="edit-proposito" name="proposito" class="form-control" rows="3" placeholder="Propósito" required>{{ old('proposito') }}</textarea>
                        </div>

                        <div class="col-12">
                            <div class="form-floating">
                                <input id="edit-de_parte" type="text" name="de_parte" class="form-control" placeholder="De parte" value="{{ old('de_parte') }}">
                                <label for="edit-de_parte">De parte</label>
                            </div>
                        </div>
                    </section>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>