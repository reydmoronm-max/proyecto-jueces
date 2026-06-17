{{-- Modal Editar Vocero --}}
<div class="modal fade" id="modalEditarVocero" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarVoceroLabel">Editar vocero</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-2">
                <form class="needs-validation" novalidate id="formEditarVocero" action="" autocomplete="off"
                    method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <div class="col-12">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input id="edit-cedula" type="number" name="cedula"
                                            class="form-control bg-white" placeholder="Cédula"
                                            required
                                            oninput="if(this.value.length>8)this.value=this.value.slice(0,8)">
                                        <label for="edit-cedula">Cédula</label>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control bg-white" name="nombres"
                                            id="edit-nombres" placeholder="Nombres" required
                                            pattern="^[A-Za-zÀ-ÖØ-öø-ÿ ]+$" title="Solo letras y espacios"
                                            oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ ]+/g, '')">
                                        <label for="edit-nombres">Nombres</label>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control bg-white" name="apellidos"
                                            id="edit-apellidos" placeholder="Apellidos" required
                                            pattern="^[A-Za-zÀ-ÖØ-öø-ÿ ]+$" title="Solo letras y espacios"
                                            oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ ]+/g, '')">
                                        <label for="edit-apellidos">Apellidos</label>
                                    </div>
                                </div>

                                <div class="col-12 col-md-12">
                                    <div class="form-floating">
                                        <select name="categoria_vocero" id="edit-categoria_vocero"
                                            class="form-select bg-white" required>
                                            <option value="" selected disabled>Seleccione una categoría</option>
                                            <option value="Vocero Principal">Vocero Principal / Coordinador</option>
                                            <option value="Vocero de Finanzas">Unidad Administrativa y Financiera</option>
                                            <option value="Vocero de Contraloría">Unidad de Contraloría Social</option>
                                            <option value="Comité de Alimentación">Comité de Alimentación (CLAP)</option>
                                            <option value="Comité de Salud">Comité de Salud y Prevención</option>
                                            <option value="Comité de Tierras Urbanas">Comité de Tierras Urbanas/Rurales</option>
                                            <option value="Comité de Deporte y Cultura">Comité de Deporte, Recreación y Cultura</option>
                                        </select>
                                        <label for="edit-categoria_vocero">Categoría</label>
                                    </div>
                                </div>

                                <div class="col-12 col-md-12">
                                    <div class="form-group">
                                        <label class="mb-2" for="edit-fecha_eleccion">Fecha de elección</label>
                                        <div class="input-group wrap_flatpicker" data-min-date="none" id="edit-fecha_eleccion_container">
                                            <input required type="text" name="fecha_eleccion" id="edit-fecha_eleccion"
                                                class="form-control bg-white" placeholder="Fecha de elección"
                                                data-input>
                                            <a class="input-group-text input-button bg-white" title="limpiar" data-clear
                                                href="javascript:void(0)">
                                                <svg width="24" class="icon-24" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer mt-3 pb-0 pe-0">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Actualizar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
