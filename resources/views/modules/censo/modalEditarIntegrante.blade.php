<div class="modal fade" id="modalEditarIntegrante" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Integrante</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-2">
                <form class="needs-validation" novalidate id="formEditarIntegrante" action="" autocomplete="off"
                    method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <!-- Cédula -->
                        <div class="col-12 col-md-4">
                            <div class="form-floating">
                                <input id="edit-cedula" type="number" name="cedula" class="form-control bg-white"
                                    placeholder="Cédula" required
                                    oninput="if(this.value.length>8)this.value=this.value.slice(0,8)">
                                <label for="edit-cedula">Cédula</label>
                            </div>
                        </div>

                        <!-- Nombres -->
                        <div class="col-12 col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control bg-white" name="nombres" id="edit-nombres"
                                    placeholder="Nombres" required pattern="^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$"
                                    title="Solo letras y espacios"
                                    oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ\s]+/g, '')">
                                <label for="edit-nombres">Nombres</label>
                            </div>
                        </div>

                        <!-- Apellidos -->
                        <div class="col-12 col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control bg-white" name="apellidos" id="edit-apellidos"
                                    placeholder="Apellidos" required pattern="^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$"
                                    title="Solo letras y espacios"
                                    oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ\s]+/g, '')">
                                <label for="edit-apellidos">Apellidos</label>
                            </div>
                        </div>

                        <!-- Teléfono -->
                        <div class="col-12 col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control bg-white" name="telefono" id="edit-telefono"
                                    placeholder="Teléfono">
                                <label for="edit-telefono">Teléfono</label>
                            </div>
                        </div>

                        <!-- Fecha de Nacimiento -->
                        <div class="col-12 col-md-8">
                            <div class="form-group">
                                <label class="mb-1 small text-muted" for="edit-fecha_nacimiento">Fecha de
                                    nacimiento</label>
                                <div class="input-group wrap_flatpicker" id="edit-fecha_nacimiento_container"
                                    data-min-date="none">
                                    <input required type="text" name="fecha_nacimiento" id="edit-fecha_nacimiento"
                                        class="form-control bg-white" placeholder="dd-mm-aaaa" data-input>
                                    <a class="input-group-text input-button bg-white" title="limpiar" data-clear
                                        href="javascript:void(0)">
                                        <svg width="18" class="icon-18" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Centro Votación -->
                        <div class="col-12 col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control bg-white" name="centro_votacion"
                                    id="edit-centro_votacion" placeholder="Centro de Votación">
                                <label for="edit-centro_votacion">Centro de Votación</label>
                            </div>
                        </div>

                        <!-- Carnet de la patria -->
                        <div class="col-12 col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control bg-white" name="carnet_patria"
                                    id="edit-carnet_patria" placeholder="Carnet de la Patria">
                                <label for="edit-carnet_patria">Carnet de la Patria</label>
                            </div>
                        </div>

                        <!-- Nivel Académico -->
                        <div class="col-12 col-md-4">
                            <div class="form-floating">
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
                                <label for="edit-nivel_academico">Nivel Académico</label>
                            </div>
                        </div>

                        <!-- Profesión -->
                        <div class="col-12 col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control bg-white" name="profesion"
                                    id="edit-profesion" placeholder="Profesión">
                                <label for="edit-profesion">Profesión</label>
                            </div>
                        </div>

                        <!-- Situación Laboral -->
                        <div class="col-12 col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control bg-white" name="situacion_laboral"
                                    id="edit-situacion_laboral" placeholder="Situación laboral">
                                <label for="edit-situacion_laboral">Situación Laboral</label>
                            </div>
                        </div>

                        <!-- Vivienda -->
                        <div class="col-12 col-md-4">
                            <div class="form-floating">
                                <select name="vivienda" id="edit-vivienda" class="form-select bg-white" required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    <option value="Propia">Propia</option>
                                    <option value="Prestada">Prestada</option>
                                    <option value="Alquilada">Alquilada</option>
                                </select>
                                <label for="edit-vivienda">Vivienda</label>
                            </div>
                        </div>

                        <!-- Tipo Enfermedad -->
                        <div class="col-12 col-md-8">
                            <div class="form-floating">
                                <input type="text" class="form-control bg-white" name="tipo_enfermedad"
                                    id="edit-tipo_enfermedad" placeholder="Tipo de enfermedad">
                                <label for="edit-tipo_enfermedad">Tipo de Enfermedad (si posee)</label>
                            </div>
                        </div>

                        <!-- Ayuda Técnica Mencionada -->
                        <div class="col-12 col-md-8">
                            <div class="form-floating">
                                <input type="text" class="form-control bg-white" name="ayuda_tecnica"
                                    id="edit-ayuda_tecnica" placeholder="Ayuda técnica mencionada">
                                <label for="edit-ayuda_tecnica">Ayuda Técnica Mencionada</label>
                            </div>
                        </div>

                        <!-- Bono Único Familiar -->
                        <div class="col-12 col-md-4">
                            <div class="form-floating">
                                <select name="bono_unico_familiar" id="edit-bono_unico_familiar"
                                    class="form-select bg-white" required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    <option value="Sí">Sí</option>
                                    <option value="No">No</option>
                                </select>
                                <label for="edit-bono_unico_familiar">Bono Único Familiar</label>
                            </div>
                        </div>

                        <!-- Pensionado/Jubilado -->
                        <div class="col-12 col-md-3">
                            <div class="form-floating">
                                <select name="pensionado_jubilado" id="edit-pensionado_jubilado"
                                    class="form-select bg-white" required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    <option value="Sí">Sí</option>
                                    <option value="No">No</option>
                                </select>
                                <label for="edit-pensionado_jubilado">Pensionado / Jubilado</label>
                            </div>
                        </div>

                        <!-- Misión Vivienda -->
                        <div class="col-12 col-md-3">
                            <div class="form-floating">
                                <select name="mision_vivienda" id="edit-mision_vivienda" class="form-select bg-white"
                                    required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    <option value="Sí">Sí</option>
                                    <option value="No">No</option>
                                </select>
                                <label for="edit-mision_vivienda">Misión Vivienda</label>
                            </div>
                        </div>

                        <!-- Clap -->
                        <div class="col-12 col-md-3">
                            <div class="form-floating">
                                <select name="clap" id="edit-clap" class="form-select bg-white" required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    <option value="Sí">Sí</option>
                                    <option value="No">No</option>
                                </select>
                                <label for="edit-clap">Recibe CLAP</label>
                            </div>
                        </div>

                        <!-- Casa Alimentación -->
                        <div class="col-12 col-md-3">
                            <div class="form-floating">
                                <select name="casa_alimentacion" id="edit-casa_alimentacion"
                                    class="form-select bg-white" required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    <option value="Sí">Sí</option>
                                    <option value="No">No</option>
                                </select>
                                <label for="edit-casa_alimentacion">Casa de Alimentación</label>
                            </div>
                        </div>

                        <!-- Género -->
                        <div class="col-12 col-md-3">
                            <div class="form-floating">
                                <select name="genero" id="edit-genero" class="form-select bg-white" required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                </select>
                                <label for="edit-genero">Género</label>
                            </div>
                        </div>

                        <!-- Estudia -->
                        <div class="col-12 col-md-3">
                            <div class="form-floating">
                                <select name="estudia" id="edit-estudia" class="form-select bg-white" required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    <option value="Sí">Sí</option>
                                    <option value="No">No</option>
                                </select>
                                <label for="edit-estudia">Estudia (Sí/No)</label>
                            </div>
                        </div>

                        <!-- Parentesco -->
                        <div class="col-12 col-md-3">
                            <div class="form-floating">
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
                                <label for="edit-parentesco">Parentesco</label>
                            </div>
                        </div>

                        <!-- Consejo Comunal -->
                        <div class="col-12 col-md-3">
                            <div class="form-floating">
                                <select name="consejo_comunal_id" id="edit-consejo_comunal_id"
                                    class="form-select bg-white">
                                    <option value="" selected>Ninguno (Sin vincular)</option>
                                    @foreach ($consejosComunales as $cc)
                                        <option value="{{ $cc->id }}">{{ $cc->nombre }}</option>
                                    @endforeach
                                </select>
                                <label for="edit-consejo_comunal_id">Consejo Comunal</label>
                            </div>
                        </div>

                        <!-- Dirección -->
                        <div class="col-12 col-md-12">
                            <div class="form-floating">
                                <input type="text" class="form-control bg-white" name="direccion"
                                    id="edit-direccion" placeholder="Dirección">
                                <label for="edit-direccion">Dirección específica</label>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer mt-4 pb-0 pe-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
