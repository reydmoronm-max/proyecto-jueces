<div class="modal fade" id="modalRegistrarVisita" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar Visita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formRegistrarVisita" method="POST" action="{{ route('visitas.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <div class="form-floating">
                            <input id="nombre" type="text" name="nombre" class="form-control" placeholder="Nombre" value="{{ old('nombre') }}" required maxlength="50" pattern="^[A-Za-zÀ-ÖØ-öø-ÿ ]+$" title="Solo letras y espacios" oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ ]+/g, '')">
                            <label for="nombre">Nombre</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-floating">
                            <input id="apellido" type="text" name="apellido" class="form-control" placeholder="Apellido" value="{{ old('apellido') }}" required maxlength="50" pattern="^[A-Za-zÀ-ÖØ-öø-ÿ ]+$" title="Solo letras y espacios" oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ ]+/g, '')">
                            <label for="apellido">Apellido</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="row g-2">
                            <div class="col-3">
                                <select id="cedula_tipo" name="cedula_tipo" class="form-select">
                                    <option value="V" {{ old('cedula_tipo', 'V') == 'V' ? 'selected' : '' }}>V</option>
                                    <option value="E" {{ old('cedula_tipo') == 'E' ? 'selected' : '' }}>E</option>
                                </select>
                            </div>
                            <div class="col">
                                <div class="form-floating">
                                    <input id="cedula" type="text" name="cedula" class="form-control" placeholder="Cédula" value="{{ old('cedula') }}" required maxlength="8" inputmode="numeric" pattern="^[0-9]{7,8}$" title="Solo números, hasta 8 dígitos" oninput="this.value = this.value.replace(/\D/g, '').slice(0, 8)">
                                    <label for="cedula">Cédula</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="proposito" class="form-label">Propósito</label>
                        <textarea id="proposito" name="proposito" class="form-control" rows="4" placeholder="Propósito" required>{{ old('proposito') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <div class="form-floating">
                            <input id="de_parte" type="text" name="de_parte" class="form-control" placeholder="De parte" value="{{ old('de_parte') }}">
                            <label for="de_parte">De parte</label>
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
