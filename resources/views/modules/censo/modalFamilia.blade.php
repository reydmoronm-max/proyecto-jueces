{{-- Modal Registrar Familia --}}
<div class="modal fade" id="modalRegistrarFamilia" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-home-heart-line me-1 text-primary"></i> Registrar Familia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                <form class="needs-validation" novalidate id="formFamilia" action="{{ route('censo.store') }}" autocomplete="off" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="form-floating">
                                <input id="numero_familia" type="text" name="numero_familia" class="form-control bg-white" placeholder="Ej: Familia 1" required>
                                <label for="numero_familia">Identificación / Número de Familia <span class="text-danger">*</span></label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-floating">
                                <select name="consejo_comunal_id" id="consejo_comunal_id_fam" class="form-select bg-white">
                                    <option value="" selected>Ninguno (Sin vincular)</option>
                                    @foreach ($consejosComunales as $cc)
                                        <option value="{{ $cc->id }}">{{ $cc->nombre }}</option>
                                    @endforeach
                                </select>
                                <label for="consejo_comunal_id_fam">Comunidad (Consejo Comunal)</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-floating">
                                <select name="vivienda" id="vivienda_fam" class="form-select bg-white" required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    <option value="Propia">Propia</option>
                                    <option value="Prestada">Prestada</option>
                                    <option value="Alquilada">Alquilada</option>
                                </select>
                                <label for="vivienda_fam">Tipo de Vivienda <span class="text-danger">*</span></label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-floating">
                                <select name="mision_vivienda" id="mision_vivienda_fam" class="form-select bg-white" required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    <option value="Sí">Sí</option>
                                    <option value="No">No</option>
                                </select>
                                <label for="mision_vivienda_fam">¿Adjudicada por Misión Vivienda? <span class="text-danger">*</span></label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-floating">
                                <select name="bono_unico_familiar" id="bono_unico_familiar_fam" class="form-select bg-white" required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    <option value="Sí">Sí</option>
                                    <option value="No">No</option>
                                </select>
                                <label for="bono_unico_familiar_fam">¿Recibe Bono Único Familiar? <span class="text-danger">*</span></label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-floating">
                                <select name="clap" id="clap_fam" class="form-select bg-white" required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    <option value="Sí">Sí</option>
                                    <option value="No">No</option>
                                </select>
                                <label for="clap_fam">¿La familia recibe CLAP? <span class="text-danger">*</span></label>
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
                <h5 class="modal-title"><i class="ri-edit-box-line me-1 text-warning"></i> Editar Familia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                <form class="needs-validation" novalidate id="formEditarFamilia" action="" autocomplete="off" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="form-floating">
                                <input id="edit_numero_familia" type="text" name="numero_familia" class="form-control bg-white" placeholder="Ej: Familia 1" required>
                                <label for="edit_numero_familia">Identificación / Número de Familia <span class="text-danger">*</span></label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-floating">
                                <select name="consejo_comunal_id" id="edit_consejo_comunal_id" class="form-select bg-white">
                                    <option value="" selected>Ninguno (Sin vincular)</option>
                                    @foreach ($consejosComunales as $cc)
                                        <option value="{{ $cc->id }}">{{ $cc->nombre }}</option>
                                    @endforeach
                                </select>
                                <label for="edit_consejo_comunal_id">Comunidad (Consejo Comunal)</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-floating">
                                <select name="vivienda" id="edit_vivienda" class="form-select bg-white" required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    <option value="Propia">Propia</option>
                                    <option value="Prestada">Prestada</option>
                                    <option value="Alquilada">Alquilada</option>
                                </select>
                                <label for="edit_vivienda">Tipo de Vivienda <span class="text-danger">*</span></label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-floating">
                                <select name="mision_vivienda" id="edit_mision_vivienda" class="form-select bg-white" required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    <option value="Sí">Sí</option>
                                    <option value="No">No</option>
                                </select>
                                <label for="edit_mision_vivienda">¿Adjudicada por Misión Vivienda? <span class="text-danger">*</span></label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-floating">
                                <select name="bono_unico_familiar" id="edit_bono_unico_familiar" class="form-select bg-white" required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    <option value="Sí">Sí</option>
                                    <option value="No">No</option>
                                </select>
                                <label for="edit_bono_unico_familiar">¿Recibe Bono Único Familiar? <span class="text-danger">*</span></label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-floating">
                                <select name="clap" id="edit_clap" class="form-select bg-white" required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    <option value="Sí">Sí</option>
                                    <option value="No">No</option>
                                </select>
                                <label for="edit_clap">¿La familia recibe CLAP? <span class="text-danger">*</span></label>
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
