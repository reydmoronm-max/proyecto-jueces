<div class="modal fade" id="modalEditarIntegrante" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="ri-edit-line me-1 text-warning"></i> EDITAR INTEGRANTE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                <form class="needs-validation" novalidate id="formEditarIntegrante" action="" autocomplete="off"
                    method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <!-- Cédula -->
                        <div class="col-12 col-md-4">
                            <label class="text-primary fw-bold mb-1" for="edit-cedula">CÉDULA <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-address-card"></i></span>
                                <input id="edit-cedula" type="number" name="cedula" class="form-control bg-white" placeholder="Ej. 15000100" required oninput="if(this.value.length>8)this.value=this.value.slice(0,8)">
                            </div>
                        </div>

                        <!-- Nombres -->
                        <div class="col-12 col-md-4">
                            <label class="text-primary fw-bold mb-1" for="edit-nombres">NOMBRE <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                <input type="text" class="form-control bg-white" name="nombres" id="edit-nombres" placeholder="Ingrese nombre(s) del ciudadano" required pattern="^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$" title="Solo letras y espacios" oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ\s]+/g, '')">
                            </div>
                        </div>

                        <!-- Apellidos -->
                        <div class="col-12 col-md-4">
                            <label class="text-primary fw-bold mb-1" for="edit-apellidos">APELLIDO <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                <input type="text" class="form-control bg-white" name="apellidos" id="edit-apellidos" placeholder="Ingrese apellido(s) del ciudadano" required pattern="^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$" title="Solo letras y espacios" oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ\s]+/g, '')">
                            </div>
                        </div>

                        <!-- Teléfono -->
                        <div class="col-12 col-md-4">
                            <label class="text-primary fw-bold mb-1" for="edit-telefono">TELÉFONO</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
                                <input type="text" inputmode="numeric" class="form-control bg-white" name="telefono" id="edit-telefono" placeholder="Ej. 0412XXXXXXX" maxlength="11" oninput="this.value=this.value.replace(/\D/g,'').slice(0,11)">
                            </div>
                        </div>

                        <!-- Fecha de Nacimiento -->
                        <div class="col-12 col-md-4">
                            <label class="small text-muted" for="edit-fecha_nacimiento">FECHA DE NACIMIENTO <span class="text-danger">*</span></label>
                            <div class="form-group">
                                <div class="input-group wrap_flatpicker" id="edit-fecha_nacimiento_container" data-min-date="none">
                                    <span class="input-group-text"><i class="fa-solid fa-calendar"></i></span>
                                    <input required type="text" name="fecha_nacimiento" id="edit-fecha_nacimiento" class="form-control bg-white" placeholder="dd-mm-aaaa" data-input>
                                </div>
                            </div>
                        </div>

                        <!-- Género -->
                        <div class="col-12 col-md-4">
                            <label class="text-primary fw-bold mb-1" for="edit-genero">GÉNERO <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-venus-mars"></i></span>
                                <select name="genero" id="edit-genero" class="form-select bg-white" required>
                                    <option value="" selected disabled>Especifique...</option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                </select>
                            </div>
                        </div>

                        <!-- Parentesco -->
                        <div class="col-12 col-md-4">
                            <label class="text-primary fw-bold mb-1" for="edit-parentesco">PARENTESCO <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-people-group"></i></span>
                                <select name="parentesco" id="edit-parentesco" class="form-select bg-white" required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    <option value="Jefe de familia">Jefe de familia</option>
                                    <option value="Hijo/a">Hijo/a</option>
                                    <option value="Padre">Padre</option>
                                    <option value="Madre">Madre</option>
                                    <option value="Abuelo/a">Abuelo/a</option>
                                    <option value="Tío/a">Tío/a</option>
                                    <option value="Primo/a">Primo/a</option>
                                </select>
                            </div>
                        </div>

                        <!-- Estudia -->
                        <div class="col-12 col-md-4">
                            <label class="text-primary fw-bold mb-1" for="edit-estudia">¿ESTUDIA ACTUALMENTE? <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-chalkboard-user"></i></span>
                                <select name="estudia" id="edit-estudia" class="form-select bg-white" required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    <option value="Sí">Sí</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                        </div>

                        <!-- Pensionado/Jubilado -->
                        <div class="col-12 col-md-4">
                            <label class="text-primary fw-bold mb-1" for="edit-pensionado_jubilado">PENSIONADO / JUBILADO <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-person-cane"></i></span>
                                <select name="pensionado_jubilado" id="edit-pensionado_jubilado"
                                    class="form-select bg-white" required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    <option value="Sí">Sí</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                            <small id="edit-aviso-pensionado-jubilado" class="text-danger d-none">no cumple los requisitos legales de edad</small>
                        </div>

                        <!-- Nivel Académico -->
                        <div class="col-12 col-md-4">
                            <label class="text-primary fw-bold mb-1" for="edit-nivel_academico">NIVEL ACADÉMICO <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-graduation-cap"></i></span>
                                <select name="nivel_academico" id="edit-nivel_academico" class="form-select bg-white"
                                    required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    <option value="Ninguno">Ninguno</option>
                                    <option value="Primaria">Primaria</option>
                                    <option value="Secundaria">Secundaria</option>
                                    <option value="Técnico">Técnico</option>
                                    <option value="Universitario">Universitario</option>
                                    <option value="Postgrado">Postgrado</option>
                                </select>
                            </div>
                        </div>

                        <!-- Profesión -->
                        <div class="col-12 col-md-4">
                            <label class="text-primary fw-bold mb-1" for="edit-profesion">PROFESIÓN</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-briefcase"></i></span>
                                <input type="text" class="form-control bg-white" name="profesion" id="edit-profesion" placeholder="Ej. Abogado">
                            </div>
                        </div>

                        <!-- Situación Laboral -->
                        <div class="col-12 col-md-4">
                            <label class="text-primary fw-bold mb-1" for="edit-situacion_laboral">SITUACIÓN LABORAL</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-circle-question"></i></span>
                                <input type="text" class="form-control bg-white" name="situacion_laboral" id="edit-situacion_laboral" placeholder="Ej. Trabajador">
                            </div>
                        </div>

                        <!-- Centro Votación -->
                        <div class="col-12 col-md-6">
                            <label class="text-primary fw-bold mb-1" for="edit-centro_votacion">CENTRO DE VOTACIÓN</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-check-to-slot"></i></span>
                                <input type="text" class="form-control bg-white" name="centro_votacion" id="edit-centro_votacion" placeholder="Ej. Unidad Educativa Nacional">
                            </div>
                        </div>

                        <!-- Carnet de la patria -->
                        <div class="col-12 col-md-6">
                            <label class="text-primary fw-bold mb-1" for="edit-carnet_patria">CARNET DE LA PATRIA</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-id-card-clip"></i></span>
                                <input type="text" class="form-control bg-white" name="carnet_patria" id="edit-carnet_patria" placeholder="Ingrese carnet de la patria del ciudadano" oninput="if(this.value.length>10)this.value=this.value.slice(0,10)">
                            </div>
                        </div>

                        <!-- Tipo Enfermedad -->
                        <div class="col-12 col-md-12">
                            <label class="text-primary fw-bold mb-1" for="edit-tipo_enfermedad">ENFERMEDAD O CONDICIÓN (si posee)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-heartbeat"></i></span>
                                <input type="text" class="form-control bg-white" name="tipo_enfermedad" id="edit-tipo_enfermedad" placeholder="Ingrese tipo de enfermedad">
                            </div>
                        </div>

                        <!-- Dirección -->
                        <div class="col-12 col-md-12">
                            <label class="text-primary fw-bold mb-1" for="edit-direccion">DIRECCIÓN ESPECÍFICA DE RESIDENCIA</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-location-dot"></i></span>
                                <input type="text" class="form-control bg-white" name="direccion" id="edit-direccion" placeholder="Escriba la dirección de domicilio">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer mt-4 pb-0 pe-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Actualizar Integrante</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
