{{-- Modal Editar Categoría --}}
<div class="modal fade" id="modalEditarCategoria" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarCategoriaLabel">Editar Categoría de Vocería</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-2">
                <form class="needs-validation" novalidate id="formEditarCategoria" action="" autocomplete="off"
                    method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <div class="col-12">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input id="edit-nombre" type="text" name="nombre"
                                            class="form-control bg-white" placeholder="Nombre de la categoría"
                                            required minlength="3" maxlength="100">
                                        <label for="edit-nombre">Nombre de la Categoría</label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea id="edit-descripcion" name="descripcion" class="form-control bg-white"
                                            placeholder="Descripción" style="height: 100px;"></textarea>
                                        <label for="edit-descripcion">Descripción (opcional)</label>
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
