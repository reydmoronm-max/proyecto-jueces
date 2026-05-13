<div class="modal fade" id="modalRegistrarVisita" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar Visita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('visitas.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <div class="form-floating">
                            <input type="text" name="nombre" class="form-control" placeholder="Nombre" required>
                            <label for="nombre">Nombre</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-floating">
                            <input type="text" name="apellido" class="form-control" placeholder="Apellido" required>
                            <label for="apellido">Apellido</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-floating">
                            <input type="text" name="cedula" class="form-control" placeholder="Cédula" required>
                            <label for="cedula">Cédula</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-floating">
                            <input type="text" name="proposito" class="form-control" placeholder="Propósito">
                            <label for="proposito">Propósito</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </div>
            </form>
        </div>
    </div>
</div>
