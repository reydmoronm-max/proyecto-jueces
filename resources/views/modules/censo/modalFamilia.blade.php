{{-- Modal Registrar Familia --}}
<div class="modal fade" id="modalRegistrarFamilia" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar Familia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-2">
                <form class="needs-validation" novalidate id="formFamilia" action="{{ route('censo.store') }}" autocomplete="off" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-floating">
                                <input id="numero_familia" type="text" name="numero_familia" class="form-control bg-white" placeholder="Ej: Familia 1" required>
                                <label for="numero_familia">Identificación / Número de Familia</label>
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

{{-- Modal Editar Familia --}}
<div class="modal fade" id="modalEditarFamilia" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Familia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-2">
                <form class="needs-validation" novalidate id="formEditarFamilia" action="" autocomplete="off" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-floating">
                                <input id="edit_numero_familia" type="text" name="numero_familia" class="form-control bg-white" placeholder="Ej: Familia 1" required>
                                <label for="edit_numero_familia">Identificación / Número de Familia</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer mt-3 pb-0 pe-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
