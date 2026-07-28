{{-- Modal --}}
<div class="modal fade" id="modalEditarUsuario" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarUsuarioLabel">Editar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" novalidate id="formEditarUsuario" action="" autocomplete="off"
                    method="POST">
                    @csrf
                    @method('PUT')
                    <section class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control bg-white" name="nombre" id="edit-nombre"
                                    placeholder="Nombre" required pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ ]+"
                                    title="Solo letras y espacios"
                                    oninput="this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ ]+/g, '')">
                                <label for="edit-nombre">Nombre</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control bg-white" name="apellido" id="edit-apellido"
                                    placeholder="Apellido" required pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ ]+"
                                    title="Solo letras y espacios"
                                    oninput="this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ ]+/g, '')">
                                <label for="edit-apellido">Apellido</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-floating">
                                <input id="edit-cedula_usuario" type="number" name="cedula_usuario"
                                    class="form-control bg-white" placeholder="Cédula"
                                    value="{{ old('cedula_usuario') }}" required
                                    oninput="if(this.value.length>8)this.value=this.value.slice(0,8)">
                                <label for="edit-cedula_usuario">Cédula de identidad</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control bg-white" name="user" id="edit-user"
                                    placeholder="Nombre de Usuario" required>
                                <label for="edit-user">Nombre de usuario</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-floating">
                                <select class="form-select bg-white" name="rol" id="edit-rol" required>
                                    <option value="">Seleccione una opción</option>
                                    <option value="Jefe de comuna">Jefe de comuna</option>
                                    <option value="Jefe de comando">Jefe de comando</option>
                                    <option value="Juez">Juez</option>
                                </select>
                                <label for="edit-rol">Rol</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-floating">
                                <select class="form-select bg-white" name="pregunta_seguridad" id="edit-pregunta_seguridad">
                                    <option value="">Sin pregunta de seguridad</option>
                                    <option value="¿Qué color le gusta más?">¿Qué color le gusta más?</option>
                                    <option value="¿Cómo se llamaba su mascota favorita de la infancia?">¿Cómo se llamaba su mascota favorita de la infancia?</option>
                                    <option value="¿Cuál es su deporte favorito?">¿Cuál es su deporte favorito?</option>
                                    <option value="¿Qué raza de gato le gusta más?">¿Qué raza de gato le gusta más?</option>
                                </select>
                                <label for="edit-pregunta_seguridad">Pregunta de Seguridad</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control bg-white" name="respuesta_seguridad" id="edit-respuesta_seguridad"
                                    placeholder="Dejar en blanco para no cambiar">
                                <label for="edit-respuesta_seguridad">Nueva Respuesta de Seguridad</label>
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
