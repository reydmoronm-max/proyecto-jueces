<div class="modal fade" id="modalConsultaFamilia" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header py-3" style="background-color: #f5f6fa;">
                <h5 class="modal-title text-secondary fw-bold d-flex align-items-center">
                    <i class="ri-home-heart-fill me-2 fs-4"></i>
                    <strong id="modal_fam_numero" class="">Cargando...</strong>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                {{-- Resumen de datos de la vivienda y comunidad --}}
                <div class="card border-0 mb-4" style="background-color: #ecedf1;">
                    <div class="card-body p-3">
                        <div class="row g-3">
                            <div class="col-md-4 col-sm-6">
                                <span class="text-muted small fw-bold d-block text-uppercase">Comunidad / Consejo Comunal:</span>
                                <span id="modal_fam_comunidad" class="fw-bold text-dark fs-6">No asignado</span>
                            </div>
                            <div class="col-md-2 col-sm-6">
                                <span class="text-muted small fw-bold d-block text-uppercase">Tipo de Vivienda:</span>
                                <span id="modal_fam_vivienda" class="badge bg-secondary fs-7">No registrada</span>
                            </div>
                            <div class="col-md-2 col-sm-4">
                                <span class="text-muted small fw-bold d-block text-uppercase">Misión Vivienda:</span>
                                <span id="modal_fam_gmvv" class="badge bg-info fs-7">No</span>
                            </div>
                            <div class="col-md-2 col-sm-4">
                                <span class="text-muted small fw-bold d-block text-uppercase">Recibe CLAP:</span>
                                <span id="modal_fam_clap" class="badge bg-success fs-7">No</span>
                            </div>
                            <div class="col-md-2 col-sm-4">
                                <span class="text-muted small fw-bold d-block text-uppercase">Bono Familiar:</span>
                                <span id="modal_fam_bono" class="badge bg-danger fs-7">No</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Miembros del núcleo familiar --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0 d-flex align-items-center">
                        <i class="ri-team-line me-2 text-primary"></i> Miembros del Núcleo Familiar
                        <span id="modal_fam_conteo" class="badge bg-primary ms-2 rounded-pill">0</span>
                    </h5>
                    <small class="text-muted"><i class="ri-information-line me-1"></i>Puedes consultar el perfil individual de cualquier miembro haciendo clic en su botón.</small>
                </div>

                <div class="table-responsive rounded border">
                    <table class="table table-striped table-bordered table-striped-columns align-middle mb-0">
                        <thead class="">
                            <tr>
                                <th>Cédula</th>
                                <th>Nombre y Apellido</th>
                                <th>Parentesco</th>
                                <th>Edad / Género</th>
                                <th>Teléfono</th>
                                {{-- <th>Ocupación</th> --}}
                                <th class="text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody id="modal_fam_integrantes_body">
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                    <span class="ms-2">Cargando integrantes...</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
