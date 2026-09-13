{{-- Modal --}}
<div class="modal fade" id="modalCambiarPassword" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title fw-bold" id="modalCambiarPasswordLabel">CAMBIAR CONTRASEÑA</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form class="needs-validation" novalidate id="formPassword" autocomplete="off" method="POST" onsubmit="return cambio_password()">
                        
                            <section class="row g-3">                            
                                <div class="col-md-12 mb-3">
                                    <label class="text-primary fw-bold mb-1" for="newPassword">NUEVA CONTRASEÑA</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-key"></i></span>
                                        <input type="text" hidden id="id_usuario" name="id">
                                        <input type="password" class="form-control bg-white" name="password" id="newPassword" placeholder="Ingrese la nueva contraseña del usuario" required>
                                    </div>
                                </div>
                            </section>
                            <div class="modal-footer">
                                <span class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</span>
                                <button class="btn btn-primary">Guardar</button>
                            </div>
                        </form>
        </div>
    </div>
</div>
</div>