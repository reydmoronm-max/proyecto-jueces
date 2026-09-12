<div class="modal fade" id="modalConsultarFamilia" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="ri-home-line me-1 text-primary"></i> <span id="lbl_numero_familia"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                {{-- Resumen de datos de la familia --}}
                <div class="card border-0 mb-3 shadow-none" style="background-color: #f5f6fa">
                    <div class="card-body p-3">
                        <div class="row g-2 small">
                            <div class="col-12 col-md-6">
                                <span class="text-muted fw-bold d-block">Comunidad / Consejo Comunal</span>
                                <span id="view_fam_consejo_comunal" class="badge bg-light fw-semibold text-dark">No asignado</span>
                            </div>
                            <div class="col-6 col-md-3">
                                <span class="text-muted fw-bold d-block">Tipo de Vivienda</span>
                                <span id="view_fam_vivienda" class="badge bg-secondary">No registrada</span>
                            </div>
                            <div class="col-6 col-md-3">
                                <span class="text-muted fw-bold d-block">Misión Vivienda</span>
                                <span id="view_fam_mision_vivienda" class="badge bg-info">No</span>
                            </div>
                            <div class="col-6 col-md-3 mt-2">
                                <span class="text-muted fw-bold d-block">Bono Único Familiar</span>
                                <span id="view_fam_bono" class="badge bg-danger">No</span>
                            </div>
                            <div class="col-6 col-md-3 mt-2">
                                <span class="text-muted fw-bold d-block">Recibe CLAP</span>
                                <span id="view_fam_clap" class="badge bg-success">No</span>
                            </div>
                        </div>
                    </div>
                </div>

                <h6 class="fw-bold mb-2 text-dark"><i class="ri-team-line me-1"></i> Integrantes de la Familia</h6>
                <div class="table-responsive">
                    <table class="table table-striped mb-0" id="tabla-integrantes-familia">
                        <thead>
                            <tr>
                                <th>Cédula</th>
                                <th>Nombre y Apellido</th>
                                <th>Parentesco</th>
                                <th>Teléfono</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="lista-integrantes-body">
                            <!-- Populated via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
