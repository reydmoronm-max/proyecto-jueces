{{-- Modal --}}
<div class="modal fade" id="modalEditarUsuario" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalEditarUsuarioLabel">EDITAR USUARIO</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" novalidate id="formEditarUsuario" action="" autocomplete="off"
                    method="POST">
                    @csrf
                    @method('PUT')
                    <section class="row g-3">

                        <div class="col-12 col-md-6">
                            <label class="text-primary fw-bold mb-1" for="edit-cedula_usuario">CÉDULA DE IDENTIDAD</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-address-card"></i></span>
                                <input id="edit-cedula_usuario" type="number" name="cedula_usuario" class="form-control bg-white" placeholder="15000100" value="{{ old('cedula_usuario') }}" required oninput="if(this.value.length>8)this.value=this.value.slice(0,8)">
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="text-primary fw-bold mb-1" for="edit-rol">ROL</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-users-gear"></i></span>
                                <select class="form-select bg-white" name="rol" id="edit-rol" required>
                                    <option value="">Elija una opción</option>
                                    <option value="Jefe de comuna">Jefe de comuna</option>
                                    <option value="Jefe de Comando">Jefe de Comando</option>
                                    <option value="Juez">Juez</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="text-primary fw-bold mb-1" for="edit-nombre">NOMBRE</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                <input type="text" class="form-control bg-white" name="nombre" id="edit-nombre" placeholder="Juan" required pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ ]+" title="Solo letras y espacios" oninput="this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ ]+/g, '')">
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="text-primary fw-bold mb-1" for="edit-apellido">APELLIDO</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                <input type="text" class="form-control bg-white" name="apellido" id="edit-apellido" placeholder="García" required pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ ]+" title="Solo letras y espacios" oninput="this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ ]+/g, '')">
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="text-primary fw-bold mb-1" for="edit-pregunta_seguridad">PREGUNTA DE SEGURIDAD</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-question"></i></span>
                                <select class="form-select bg-white" name="pregunta_seguridad" id="edit-pregunta_seguridad">
                                    <option value="">Sin pregunta de seguridad</option>
                                    <option value="¿Qué color le gusta más?">¿Qué color le gusta más?</option>
                                    <option value="¿Cómo se llamaba su mascota favorita de la infancia?">¿Cómo se llamaba su mascota favorita de la infancia?</option>
                                    <option value="¿Cuál es su deporte favorito?">¿Cuál es su deporte favorito?</option>
                                    <option value="¿Qué raza de gato le gusta más?">¿Qué raza de gato le gusta más?</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="text-primary fw-bold mb-1" for="edit-respuesta_seguridad">NUEVA RESPUESTA DE SEGURIDAD</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                <input type="text" class="form-control bg-white" name="respuesta_seguridad" id="edit-respuesta_seguridad" placeholder="Dejar en blanco para no cambiar">
                            </div>
                        </div>
                    </section>
                    <br>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
