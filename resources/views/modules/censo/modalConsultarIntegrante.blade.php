<div class="modal fade" id="modalConsultarIntegrante" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del Ciudadano (Censo)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-2">
                <div class="row g-3">
                    <!-- Cédula -->
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label class="small text-muted mb-1">Cédula</label>
                            <input id="view-cedula" type="text" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <!-- Nombres -->
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label class="small text-muted mb-1">Nombres</label>
                            <input id="view-nombres" type="text" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <!-- Apellidos -->
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label class="small text-muted mb-1">Apellidos</label>
                            <input id="view-apellidos" type="text" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <!-- Teléfono -->
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label class="small text-muted mb-1">Teléfono</label>
                            <input id="view-telefono" type="text" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <!-- Fecha de Nacimiento -->
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label class="small text-muted mb-1">Fecha de nacimiento</label>
                            <input id="view-fecha_nacimiento" type="text" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <!-- Cantidad de Integrantes -->
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label class="small text-muted mb-1">Integrantes en núcleo familiar</label>
                            <input id="view-cantidad_integrantes" type="text" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <!-- Centro Votación -->
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="small text-muted mb-1">Centro de Votación</label>
                            <input id="view-centro_votacion" type="text" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <!-- Carnet de la patria -->
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="small text-muted mb-1">Carnet de la Patria</label>
                            <input id="view-carnet_patria" type="text" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <!-- Nivel Académico -->
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label class="small text-muted mb-1">Nivel Académico</label>
                            <input id="view-nivel_academico" type="text" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <!-- Profesión -->
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label class="small text-muted mb-1">Profesión</label>
                            <input id="view-profesion" type="text" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <!-- Situación Laboral -->
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label class="small text-muted mb-1">Situación Laboral</label>
                            <input id="view-situacion_laboral" type="text" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <!-- Vivienda -->
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label class="small text-muted mb-1">Vivienda</label>
                            <input id="view-vivienda" type="text" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <!-- Tipo Enfermedad -->
                    <div class="col-12 col-md-8">
                        <div class="form-group">
                            <label class="small text-muted mb-1">Tipo de Enfermedad</label>
                            <input id="view-tipo_enfermedad" type="text" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <!-- Ayuda Técnica Mencionada -->
                    <div class="col-12 col-md-8">
                        <div class="form-group">
                            <label class="small text-muted mb-1">Ayuda Técnica Mencionada</label>
                            <input id="view-ayuda_tecnica" type="text" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <!-- Bono Único Familiar -->
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label class="small text-muted mb-1">Bono Único Familiar</label>
                            <input id="view-bono_unico_familiar" type="text" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <!-- Pensionado/Jubilado -->
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label class="small text-muted mb-1">Pensionado / Jubilado</label>
                            <input id="view-pensionado_jubilado" type="text" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <!-- Misión Vivienda -->
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label class="small text-muted mb-1">Misión Vivienda</label>
                            <input id="view-mision_vivienda" type="text" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <!-- Clap -->
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label class="small text-muted mb-1">Recibe CLAP</label>
                            <input id="view-clap" type="text" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <!-- Casa Alimentación -->
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label class="small text-muted mb-1">Casa de Alimentación</label>
                            <input id="view-casa_alimentacion" type="text" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <!-- Género -->
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label class="small text-muted mb-1">Género</label>
                            <input id="view-genero" type="text" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <!-- Estudia -->
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label class="small text-muted mb-1">Estudia</label>
                            <input id="view-estudia" type="text" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <!-- Parentesco -->
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label class="small text-muted mb-1">Parentesco</label>
                            <input id="view-parentesco" type="text" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <!-- Consejo Comunal -->
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label class="small text-muted mb-1">Consejo Comunal</label>
                            <input id="view-consejo_comunal" type="text" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <!-- Dirección -->
                    <div class="col-12 col-md-12">
                        <div class="form-group">
                            <label class="small text-muted mb-1">Dirección específica</label>
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
