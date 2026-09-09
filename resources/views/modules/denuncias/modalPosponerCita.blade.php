{{-- Modal --}}
<div class="modal fade" id="modalPosponerCita" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalPosponerCitaLabel">POSPONER CITACIÓN</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" novalidate id="formPosponerCita" action="{{ route('denuncias.posponer-cita') }}" autocomplete="off" method="POST">
                    @csrf
                    <section class="row g-3">
                        <input type="text" name="expediente_id" id="cita_expediente_id_posponer" hidden>

                        <div class="col-12">
                            <label for="solicita_por_posponer" class="text-primary fw-bold mb-1">SOLICITA EL CAMBIO</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-person-circle-question"></i></span>
                                <select id="solicita_por_posponer" name="solicita_por" class="form-select bg-white">
                                    <option value="denunciante">Denunciante</option>
                                    <option value="denunciado">Denunciado</option>
                                </select>
                            </div>
                        </div>

                        <div id="posponer_person_fields" class="col-12">
                            <h6 id="posponer_person_heading" class="border-bottom pb-1 fw-bold">DATOS DEL DENUNCIADO</h6>
                            <br>
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label for="cedulaRequerido" class="text-primary fw-bold mb-1">CÉDULA DEL DENUNCIADO</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-id-card"></i></span>
                                        <input id="cedulaRequerido" type="number" name="cedula" class="form-control bg-white" placeholder="15000100" value="{{ old('cedula') }}" required oninput="if(this.value.length>8)this.value=this.value.slice(0,8)">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="telefonoRequerido" class="text-primary fw-bold mb-1">TELÉFONO</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
                                        <input id="telefonoRequerido" type="number" name="telefono" class="form-control bg-white" placeholder="0412XXXXXXX" value="{{ old('telefono') }}" required oninput="if(this.value.length>11)this.value=this.value.slice(0,11)">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="nombresRequerido" class="text-primary fw-bold mb-1">NOMBRE</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                        <input type="text" class="form-control bg-white" name="nombres" id="nombresRequerido" placeholder="Juan" required pattern="^[A-Za-zÀ-ÖØ-öø-ÿ ]+$" title="Solo letras y espacios" oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ ]+/g, '')">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="apellidosRequerido" class="text-primary fw-bold mb-1">APELLIDO</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                        <input type="text" class="form-control bg-white" name="apellidos" id="apellidosRequerido" placeholder="García" required pattern="^[A-Za-zÀ-ÖØ-öø-ÿ ]+$" title="Solo letras y espacios" oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ ]+/g, '')">
                                    </div>
                                </div>

                                <div class="col-12 col-md-12">
                                    <label for="direccionRequerido" class="text-primary fw-bold mb-1">DIRECCIÓN</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-house-chimney"></i></span>
                                        <input type="text" class="form-control bg-white" name="direccion" id="direccionRequerido" placeholder="Calle 1, Casa N° 23, Sector 4" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="observaciones" class="text-primary fw-bold mb-1">OBSERVACIONES</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-pen-to-square"></i></span>
                                <input type="text" class="form-control bg-white" name="observaciones" id="observaciones" placeholder="¿Por qué se pospone la cita?" required>
                            </div>
                        </div>

                        <div class="col-12">
                            <h6 class="border-bottom pb-1 fw-bold">NUEVA CITA</h6>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="text-primary fw-bold mb-1" for="start">HORA</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-clock"></i></span>
                                    <input required type="text" name="hora_citacion" class="form-control time_flatpicker" placeholder="Hora de la citación">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="text-primary fw-bold mb-1" for="fecha_citacion">FECHA</label>
                                <div class="input-group wrap_flatpicker">
                                    <span class="input-group-text"><i class="fa-solid fa-calendar"></i></span>
                                    <input required type="text" name="fecha_citacion" class="form-control" placeholder="Fecha de la citación" data-input>
                                    <a class="input-group-text input-button" title="limpiar" data-clear href="javascript:void(0)">
                                        <svg width="24" class="icon-24" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </section>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>