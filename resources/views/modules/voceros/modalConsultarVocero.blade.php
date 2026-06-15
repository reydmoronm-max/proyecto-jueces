{{-- Modal Consultar Vocero --}}
<div class="modal fade" id="modalConsultarVocero" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalConsultarVoceroLabel">Detalles de vocero</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-2">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-floating">
                                    <input id="view-cedula" type="number" class="form-control bg-white" placeholder="Cédula" disabled>
                                    <label for="view-cedula">Cédula</label>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control bg-white" id="view-nombres" placeholder="Nombres" disabled>
                                    <label for="view-nombres">Nombres</label>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control bg-white" id="view-apellidos" placeholder="Apellidos" disabled>
                                    <label for="view-apellidos">Apellidos</label>
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="form-floating">
                                    <select id="view-categoria_vocero" class="form-select bg-white" disabled>
                                        <option value="" selected disabled>Seleccione una categoría</option>
                                        <option value="Vocero Principal">Vocero Principal / Coordinador</option>
                                        <option value="Vocero de Finanzas">Unidad Administrativa y Financiera</option>
                                        <option value="Vocero de Contraloría">Unidad de Contraloría Social</option>
                                        <option value="Comité de Alimentación">Comité de Alimentación (CLAP)</option>
                                        <option value="Comité de Salud">Comité de Salud y Prevención</option>
                                        <option value="Comité de Tierras Urbanas">Comité de Tierras Urbanas/Rurales</option>
                                        <option value="Comité de Deporte y Cultura">Comité de Deporte, Recreación y Cultura</option>
                                    </select>
                                    <label for="view-categoria_vocero">Categoría</label>
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label class="mb-2" for="view-fecha_eleccion">Fecha de elección</label>
                                    <div class="input-group">
                                        <input type="text" id="view-fecha_eleccion" class="form-control bg-white" placeholder="Fecha de elección" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer mt-3 pb-0 pe-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
