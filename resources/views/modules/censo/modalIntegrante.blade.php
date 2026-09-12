<div class="modal fade" id="modalRegistrarIntegrante" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="ri-user-add-line me-1 text-primary"></i> AÑADIR INTEGRANTE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                <form class="needs-validation" novalidate id="formIntegrante"
                    action="{{ route('censo.integrante.store') }}" autocomplete="off" method="POST">
                    @csrf
                    <input type="hidden" name="familia_id" id="integrante_familia_id">

                    <div class="row g-3">
                        <!-- Cédula -->
                        <div class="col-12 col-md-4">
                            <label class="text-primary fw-bold mb-1" for="cedula">CÉDULA <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-address-card"></i></span>
                                <input id="cedula" type="number" name="cedula" class="form-control bg-white" placeholder="Ej. 15000100" title="Ingresar solo números sin puntos (Ej. 28472748)" required oninput="if(this.value.length>8)this.value=this.value.slice(0,8)">
                            </div>
                        </div>

                        <!-- Nombres -->
                        <div class="col-12 col-md-4">
                            <label class="text-primary fw-bold mb-1" for="nombres">NOMBRE <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                <input type="text" class="form-control bg-white" name="nombres" id="nombres" placeholder="Ingrese nombre(s) del ciudadano" required pattern="^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$" title="Solo letras y espacios" oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ\s]+/g, '')">
                            </div>
                        </div>

                        <!-- Apellidos -->
                        <div class="col-12 col-md-4">
                            <label class="text-primary fw-bold mb-1" for="apellidos">APELLIDO <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                <input type="text" class="form-control bg-white" name="apellidos" id="apellidos" placeholder="Ingrese apellido(s) del ciudadano" required pattern="^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$" title="Solo letras y espacios" oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ\s]+/g, '')">
                            </div>
                        </div>

                        <!-- Teléfono -->
                        <div class="col-12 col-md-4">
                            <label class="text-primary fw-bold mb-1" for="telefono">TELÉFONO</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
                                <input type="text" inputmode="numeric" class="form-control bg-white" name="telefono" id="telefono" placeholder="Ej. 0412XXXXXXX" maxlength="11" oninput="this.value=this.value.replace(/\D/g,'').slice(0,11)">
                            </div>
                        </div>

                        <!-- Fecha de Nacimiento -->
                        <div class="col-12 col-md-4">
                            <label class="text-primary fw-bold mb-1" for="fecha_nacimiento">FECHA DE NACIMIENTO <span class="text-danger">*</span></label>
                            <div class="form-group">
                                <div class="input-group wrap_flatpicker" data-min-date="none">
                                    <span class="input-group-text"><i class="fa-solid fa-calendar"></i></span>
                                    <input required type="text" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control bg-white" placeholder="dd-mm-aaaa" data-input>
                                </div>
                            </div>
                        </div>

                        <!-- Género -->
                        <div class="col-12 col-md-4">
                            <label class="text-primary fw-bold mb-1" for="genero">GÉNERO <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-venus-mars"></i></span>
                                <select name="genero" id="genero" class="form-select bg-white" required>
                                    <option value="" selected disabled>Especifique...</option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                </select>
                            </div>
                        </div>

                        <!-- Parentesco -->
                        <div class="col-12 col-md-4">
                            <label class="text-primary fw-bold mb-1" for="parentesco">PARENTESCO <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-people-group"></i></span>
                                <select name="parentesco" id="parentesco" class="form-select bg-white" required>
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
                            <label class="text-primary fw-bold mb-1" for="estudia">¿ESTUDIA ACTUALMENTE? <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-chalkboard-user"></i></span>
                                <select name="estudia" id="estudia" class="form-select bg-white" required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    <option value="Sí">Sí</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                        </div>

                        <!-- Pensionado/Jubilado -->
                        <div class="col-12 col-md-4">
                            <label class="text-primary fw-bold mb-1" for="pensionado_jubilado">PENSIONADO / JUBILADO <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-person-cane"></i></span>
                                <select name="pensionado_jubilado" id="pensionado_jubilado"
                                    class="form-select bg-white" required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    <option value="Sí">Sí</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                            <small id="aviso-pensionado-jubilado" class="text-danger d-none">No cumple los requisitos legales de edad</small>
                        </div>

                        <!-- Nivel Académico -->
                        <div class="col-12 col-md-4">
                            <label class="text-primary fw-bold mb-1" for="nivel_academico">NIVEL ACADÉMICO <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-graduation-cap"></i></span>
                                <select name="nivel_academico" id="nivel_academico" class="form-select bg-white"
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
                            <label class="text-primary fw-bold mb-1" for="profesion">PROFESIÓN</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-briefcase"></i></span>
                                <input type="text" class="form-control bg-white" name="profesion" id="profesion" placeholder="Ej. Mecánico">
                            </div>
                        </div>

                        <!-- Situación Laboral -->
                        <div class="col-12 col-md-4">
                            <label class="text-primary fw-bold mb-1" for="situacion_laboral">SITUACIÓN LABORAL</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-circle-question"></i></span>
                                <input type="text" class="form-control bg-white" name="situacion_laboral"
                                    id="situacion_laboral" placeholder="Situación laboral">
                            </div>
                        </div>

                        <!-- Centro Votación -->
                        <div class="col-12 col-md-6">
                            <label class="text-primary fw-bold mb-1" for="centro_votacion">CENTRO DE VOTACIÓN</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-check-to-slot"></i></span>
                                <input type="text" class="form-control bg-white" name="centro_votacion"
                                    id="centro_votacion" placeholder="Ingrese el centro de votación del ciudadano">
                            </div>
                        </div>

                        <!-- Carnet de la patria -->
                        <div class="col-12 col-md-6">
                            <label class="text-primary fw-bold mb-1" for="carnet_patria">CARNET DE LA PATRIA</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-id-card-clip"></i></span>
                                <input type="text" class="form-control bg-white" name="carnet_patria" id="carnet_patria" placeholder="Ingrese carnet de la patria del ciudadano" oninput="if(this.value.length>10)this.value=this.value.slice(0,10)">
                            </div>
                        </div>

                        <!-- Tipo Enfermedad -->
                        <div class="col-12 col-md-12">
                            <label class="text-primary fw-bold mb-1" for="tipo_enfermedad">ENFERMEDAD O CONDICIÓN (si posee)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-heartbeat"></i></span>
                                <input type="text" class="form-control bg-white" name="tipo_enfermedad"
                                    id="tipo_enfermedad" placeholder="Tipo de enfermedad">
                            </div>
                        </div>

                        <!-- Dirección -->
                        <div class="col-12 col-md-12">
                            <label class="text-primary fw-bold mb-1" for="direccion">DIRECCIÓN ESPECÍFICA DE RESIDENCIA</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-location-dot"></i></span>
                                <input type="text" class="form-control bg-white" name="direccion" id="direccion"
                                    placeholder="Dirección específica de domicilio">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer mt-4 pb-0 pe-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Integrante</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
