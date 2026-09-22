{{-- Modal Gestión de Catálogos (Enfermedades y Profesiones) --}}
<div class="modal fade" id="modalGestionCatalogos" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="ri-list-settings-line me-1 text-primary"></i> CATÁLOGOS DE CENSO
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                <p class="text-muted small mb-3">
                    Agregue, modifique o elimine las opciones disponibles en los selectores de enfermedades y profesiones del censo.
                </p>

                <!-- Pestañas de navegación -->
                <ul class="nav nav-tabs nav-fill mb-3" id="catalogosTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-bold text-primary" id="tab-enfermedades-btn" data-bs-toggle="tab" data-bs-target="#tab-enfermedades" type="button" role="tab" aria-controls="tab-enfermedades" aria-selected="true">
                            <i class="fa-solid fa-notes-medical me-2"></i> Enfermedades (<span id="contador-enfermedades">0</span>)
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold text-primary" id="tab-profesiones-btn" data-bs-toggle="tab" data-bs-target="#tab-profesiones" type="button" role="tab" aria-controls="tab-profesiones" aria-selected="false">
                            <i class="fa-solid fa-briefcase me-2"></i> Profesiones (<span id="contador-profesiones">0</span>)
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="catalogosTabContent">
                    <!-- Tab Enfermedades -->
                    <div class="tab-pane fade show active" id="tab-enfermedades" role="tabpanel" aria-labelledby="tab-enfermedades-btn">
                        <!-- Formulario para agregar -->
                        <div class="card bg-light border-0 mb-3 shadow-none">
                            <div class="card-body p-3">
                                <label for="nueva_enfermedad_nombre" class="form-label fw-bold small text-primary mb-1">
                                    <i class="ri-add-circle-line me-1"></i> AGREGAR NUEVA ENFERMEDAD O CONDICIÓN
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-heartbeat"></i></span>
                                    <input type="text" id="nueva_enfermedad_nombre" class="form-control bg-white" placeholder="Ej. Hipertensión, Diabetes, Asma...">
                                    <button class="btn btn-primary" type="button" id="btnAgregarEnfermedad">
                                        <i class="ri-add-line me-1"></i> Agregar
                                    </button>
                                </div>
                                <div id="error-enfermedad" class="text-danger small mt-1 d-none"></div>
                            </div>
                        </div>

                        <!-- Buscador y Tabla -->
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold small text-muted">LISTA DE ENFERMEDADES REGISTRADAS</span>
                            <div class="w-50">
                                <input type="text" id="buscarEnfermedadTabla" class="form-control form-control-sm bg-white" placeholder="Filtrar enfermedades...">
                            </div>
                        </div>

                        <div class="table-responsive border rounded" style="max-height: 280px; overflow-y: auto;">
                            <table class="table table-hover align-middle mb-0" id="tablaEnfermedades">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th style="width: 60px;" class="text-center">#</th>
                                        <th>Nombre de la Enfermedad</th>
                                        <th style="width: 120px;" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyEnfermedades">
                                    <!-- Dinámico vía JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab Profesiones -->
                    <div class="tab-pane fade" id="tab-profesiones" role="tabpanel" aria-labelledby="tab-profesiones-btn">
                        <!-- Formulario para agregar -->
                        <div class="card bg-light border-0 mb-3 shadow-none">
                            <div class="card-body p-3">
                                <label for="nueva_profesion_nombre" class="form-label fw-bold small text-primary mb-1">
                                    <i class="ri-add-circle-line me-1"></i> AGREGAR NUEVA PROFESIÓN U OFICIO
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-briefcase"></i></span>
                                    <input type="text" id="nueva_profesion_nombre" class="form-control bg-white" placeholder="Ej. Albañil, Ingeniero, Enfermera...">
                                    <button class="btn btn-primary" type="button" id="btnAgregarProfesion">
                                        <i class="ri-add-line me-1"></i> Agregar
                                    </button>
                                </div>
                                <div id="error-profesion" class="text-danger small mt-1 d-none"></div>
                            </div>
                        </div>

                        <!-- Buscador y Tabla -->
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold small text-muted">LISTA DE PROFESIONES REGISTRADAS</span>
                            <div class="w-50">
                                <input type="text" id="buscarProfesionTabla" class="form-control form-control-sm bg-white" placeholder="Filtrar profesiones...">
                            </div>
                        </div>

                        <div class="table-responsive border rounded" style="max-height: 280px; overflow-y: auto;">
                            <table class="table table-hover align-middle mb-0" id="tablaProfesiones">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th style="width: 60px;" class="text-center">#</th>
                                        <th>Nombre de la Profesión</th>
                                        <th style="width: 120px;" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyProfesiones">
                                    <!-- Dinámico vía JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
