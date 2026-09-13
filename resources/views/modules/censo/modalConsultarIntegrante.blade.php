<div class="modal fade" id="modalConsultarIntegrante" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="ri-user-search-line me-1 text-primary"></i> Detalles del Ciudadano</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                <div class="row g-3">
                    <!-- Cédula -->
                    <div class="col-12 col-md-4">
                        <label class="text-primary fw-bold mb-1">CÉDULA</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-address-card"></i></span>
                            <input id="view-cedula" type="text" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <!-- Nombres -->
                    <div class="col-12 col-md-4">
                        <label class="text-primary fw-bold mb-1">NOMBRE</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                            <input id="view-nombres" type="text" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <!-- Apellidos -->
                    <div class="col-12 col-md-4">
                        <label class="text-primary fw-bold mb-1">APELLIDO</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                            <input id="view-apellidos" type="text" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <!-- Teléfono -->
                    <div class="col-12 col-md-4">
                        <label class="text-primary fw-bold mb-1">TELÉFONO</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
                            <input id="view-telefono" type="text" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <!-- Fecha de Nacimiento -->
                    <div class="col-12 col-md-4">
                        <label class="text-primary fw-bold mb-1">FECHA DE NACIMIENTO</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-calendar"></i></span>
                            <input id="view-fecha_nacimiento" type="text" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <!-- Género -->
                    <div class="col-12 col-md-4">
                        <label class="text-primary fw-bold mb-1">GÉNERO</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-venus-mars"></i></span>
                            <input id="view-genero" type="text" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <!-- Parentesco -->
                    <div class="col-12 col-md-4">
                        <label class="text-primary fw-bold mb-1">PARENTESCO</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-people-group"></i></span>
                            <input id="view-parentesco" type="text" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <!-- Estudia -->
                    <div class="col-12 col-md-4">
                        <label class="text-primary fw-bold mb-1">¿ESTUDIA ACTUALMENTE?</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-chalkboard-user"></i></span>
                            <input id="view-estudia" type="text" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <!-- Pensionado/Jubilado -->
                    <div class="col-12 col-md-4">
                        <label class="text-primary fw-bold mb-1">PENSIONADO / JUBILADO</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-person-cane"></i></span>
                            <input id="view-pensionado_jubilado" type="text" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <!-- Nivel Académico -->
                    <div class="col-12 col-md-4">
                        <label class="text-primary fw-bold mb-1">NIVEL ACADÉMICO</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-graduation-cap"></i></span>
                            <input id="view-nivel_academico" type="text" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <!-- Profesión -->
                    <div class="col-12 col-md-4">
                        <label class="text-primary fw-bold mb-1">PROFESIÓN</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-briefcase"></i></span>
                            <input id="view-profesion" type="text" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <!-- Situación Laboral -->
                    <div class="col-12 col-md-4">
                        <label class="text-primary fw-bold mb-1">SITUACIÓN LABORAL</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-circle-question"></i></span>
                            <input id="view-situacion_laboral" type="text" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <!-- Centro Votación -->
                    <div class="col-12 col-md-6">
                        <label class="text-primary fw-bold mb-1">CENTRO DE VOTACIÓN</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-check-to-slot"></i></span>
                            <input id="view-centro_votacion" type="text" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <!-- Carnet de la patria -->
                    <div class="col-12 col-md-6">
                        <label class="text-primary fw-bold mb-1">CARNET DE LA PATRIA</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-id-card-clip"></i></span>
                            <input id="view-carnet_patria" type="text" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <!-- Tipo Enfermedad -->
                    <div class="col-12 col-md-12">
                        <label class="text-primary fw-bold mb-1">ENFERMEDAD O CONDICIÓN ESPECIAL</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-heartbeat"></i></span>
                            <input id="view-tipo_enfermedad" type="text" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <!-- Dirección -->
                    <div class="col-12 col-md-12">
                        <label class="text-primary fw-bold mb-1">DIRECCIÓN ESPECÍFICA DE RESIDENCIA</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-location-dot"></i></span>
                            <input id="view-direccion" type="text" class="form-control bg-light" readonly>
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
