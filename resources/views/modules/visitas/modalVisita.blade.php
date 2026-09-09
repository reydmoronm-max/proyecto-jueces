<div class="modal fade" id="modalRegistrarVisita" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">REGISTRAR VISITA</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formRegistrarVisita" method="POST" action="{{ route('visitas.store') }}">
                @csrf
                <div class="modal-body">
                    <section class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="cedula" class="text-primary fw-bold mb-1">CÉDULA DE IDENTIDAD</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-address-card"></i></span>
                                <input id="cedula" type="text" name="cedula" class="form-control" placeholder="15000100" value="{{ old('cedula') }}" required maxlength="8" inputmode="numeric" pattern="^[0-9]{7,8}$" title="Solo números, hasta 8 dígitos" oninput="this.value = this.value.replace(/\D/g, '').slice(0, 8)">
                            </div>
                        </div>    

                        <div class="col-12 col-md-6">
                            <label for="telefono" class="text-primary fw-bold mb-1">TELÉFONO</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
                                <input id="telefono" type="text" name="telefono" class="form-control" placeholder="0412XXXXXXX" value="{{ old('telefono') }}" maxlength="11" minlength="11" inputmode="tel">
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="nombre" class="text-primary fw-bold mb-1">NOMBRE</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-address-book"></i></span>
                                <input id="nombre" type="text" name="nombre" class="form-control" placeholder="Juan" value="{{ old('nombre') }}" required maxlength="50" pattern="^[A-Za-zÀ-ÖØ-öø-ÿ ]+$" title="Solo letras y espacios" oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ ]+/g, '')">
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="apellido" class="text-primary fw-bold mb-1">APELLIDO</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-regular fa-address-book"></i></span>
                                <input id="apellido" type="text" name="apellido" class="form-control" placeholder="García" value="{{ old('apellido') }}" required maxlength="50" pattern="^[A-Za-zÀ-ÖØ-öø-ÿ ]+$" title="Solo letras y espacios" oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ ]+/g, '')">
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="direccion" class="text-primary fw-bold mb-1">DIRECCIÓN</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-house-chimney"></i></span>
                                <input id="direccion" type="text" name="direccion" class="form-control" placeholder="Calle 1, Casa N° 23, Sector 4" rows="3">{{ old('direccion') }}</input>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                                <label for="de_parte" class="text-primary fw-bold mb-1">DE PARTE</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-person-circle-question"></i></span>
                                    <input id="de_parte" type="text" name="de_parte" class="form-control" placeholder="¿A quién representa?" value="{{ old('de_parte') }}">
                                </div>
                        </div>

                        <div class="col-12">
                            <label for="proposito" class="text-primary fw-bold mb-1">PROPÓSITO</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-circle-question"></i></span>
                                <textarea id="proposito" name="proposito" class="form-control" rows="2" placeholder="¿Motivo por el que realiza la visita?" required>{{ old('proposito') }}</textarea>
                            </div>
                        </div>
                    </section>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </div>
            </form>
        </div>
    </div>
</div>