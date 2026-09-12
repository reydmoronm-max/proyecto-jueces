{{-- Modal Registrar Familia --}}
<div class="modal fade" id="modalRegistrarFamilia" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="ri-home-heart-line me-1 text-primary"></i> REGISTRAR FAMILIA</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                <form class="needs-validation" novalidate id="formFamilia" action="{{ route('censo.store') }}" autocomplete="off" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="text-primary fw-bold mb-1" for="numero_familia">IDENTIFICACIÓN <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-people-roof"></i></span>
                                <input id="numero_familia" type="text" name="numero_familia" class="form-control bg-white" placeholder="Ej. Familia Gutierrez Rodríguez" required>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="text-primary fw-bold mb-1" for="consejo_comunal_id_fam">COMUNIDAD/CONSEJO COMUNAL <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-location-dot"></i></span>
                                <select name="consejo_comunal_id" id="consejo_comunal_id_fam" class="form-select bg-white" required>
                                    <option value="" selected>Seleccione...</option>
                                    @foreach ($consejosComunales as $cc)
                                        <option value="{{ $cc->id }}">{{ $cc->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="text-primary fw-bold mb-1" for="vivienda_fam">TIPO DE VIVIENDA  <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-house-user"></i></span>
                                <select name="vivienda" id="vivienda_fam" class="form-select bg-white" required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    <option value="Propia">Propia</option>
                                    <option value="Prestada">Prestada</option>
                                    <option value="Alquilada">Alquilada</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="text-primary fw-bold mb-1" for="mision_vivienda_fam">¿ADJUDICADA POR MISIÓN VIVIENDA?  <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-circle-question"></i></span>
                                <select name="mision_vivienda" id="mision_vivienda_fam" class="form-select bg-white" required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    <option value="Sí">Sí</option>
                                    <option value="No">No</option>
                                    <option value="NA">NA (No Aplica)</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="text-primary fw-bold mb-1" for="bono_unico_familiar_fam">¿RECIBE BONO ÚNICO FAMILIAR?  <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-circle-question"></i></span>
                                <select name="bono_unico_familiar" id="bono_unico_familiar_fam" class="form-select bg-white" required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    <option value="Sí">Sí</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="text-primary fw-bold mb-1" for="clap_fam">¿RECIBE CLAP?  <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-circle-question"></i></span>
                                <select name="clap" id="clap_fam" class="form-select bg-white" required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    <option value="Sí">Sí</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer mt-4 pb-0 pe-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Familia</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal Editar Familia --}}
<div class="modal fade" id="modalEditarFamilia" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="ri-edit-box-line me-1 text-warning"></i> EDITAR FAMILIA</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                <form class="needs-validation" novalidate id="formEditarFamilia" action="" autocomplete="off" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="text-primary fw-bold mb-1" for="edit_numero_familia">IDENTIFICACIÓN <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-people-roof"></i></span>
                                <input id="edit_numero_familia" type="text" name="numero_familia" class="form-control bg-white" placeholder="Ej. Familia Gutierrez Rodríguez" required>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="text-primary fw-bold mb-1" for="edit_consejo_comunal_id">COMUNIDAD/CONSEJO COMUNAL <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-location-dot"></i></span>
                                <select name="consejo_comunal_id" id="edit_consejo_comunal_id" class="form-select bg-white" required>
                                    <option value="" selected>Seleccione...</option>
                                    @foreach ($consejosComunales as $cc)
                                        <option value="{{ $cc->id }}">{{ $cc->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="text-primary fw-bold mb-1" for="edit_vivienda">TIPO DE VIVIENDA <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-house-user"></i></span>
                                <select name="vivienda" id="edit_vivienda" class="form-select bg-white" required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    <option value="Propia">Propia</option>
                                    <option value="Prestada">Prestada</option>
                                    <option value="Alquilada">Alquilada</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="text-primary fw-bold mb-1" for="edit_mision_vivienda">¿ADJUDICADA POR MISIÓN VIVIENDA? <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-circle-question"></i></span>
                                <select name="mision_vivienda" id="edit_mision_vivienda" class="form-select bg-white" required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    <option value="Sí">Sí</option>
                                    <option value="No">No</option>
                                    <option value="NA">NA</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="text-primary fw-bold mb-1" for="edit_bono_unico_familiar">¿RECIBE BONO ÚNICO FAMILIAR? <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-circle-question"></i></span>
                                <select name="bono_unico_familiar" id="edit_bono_unico_familiar" class="form-select bg-white" required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    <option value="Sí">Sí</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="text-primary fw-bold mb-1" for="edit_clap">¿RECIBE CLAP? <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-circle-question"></i></span>
                                <select name="clap" id="edit_clap" class="form-select bg-white" required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    <option value="Sí">Sí</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer mt-4 pb-0 pe-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Actualizar Familia</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
