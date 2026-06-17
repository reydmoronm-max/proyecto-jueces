<div class="modal fade" id="modalConsultarFamilia" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Integrantes de la <span id="lbl_numero_familia"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-2">
                <div class="table-responsive">
                    <table class="table table-striped mb-0" id="tabla-integrantes-familia">
                        <thead>
                            <tr>
                                <th>Cédula</th>
                                <th>Nombre y Apellido</th>
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
