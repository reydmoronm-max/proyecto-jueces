{{-- Modal --}}
<div class="modal fade" id="modalPosponerCita" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPosponerCitaLabel">Posponer citación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" novalidate id="formPosponerCita" action="{{ route('denuncias.posponer-cita') }}" autocomplete="off" method="POST">
                    @csrf
                    <section class="row g-3">
                        <input type="text" name="expediente_id" id="cita_expediente_id_posponer" hidden>

                        <div class="col-12">
                            <label for="solicita_por_posponer" class="form-label">Solicita el cambio</label>
                            <select id="solicita_por_posponer" name="solicita_por" class="form-select bg-white">
                                <option value="denunciante">Requirente (Denunciante)</option>
                                <option value="denunciado">Requerido (Denunciado)</option>
                            </select>
                        </div>

                        <div id="posponer_person_fields" class="col-12">
                            <h6 id="posponer_person_heading">Datos del requerido</h6>
                            <br>
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <div class="form-floating">
                                        <input id="cedulaRequerido" type="number" name="cedula" class="form-control bg-white" placeholder="Cédula" value="{{ old('cedula') }}" required oninput="if(this.value.length>8)this.value=this.value.slice(0,8)">
                                        <label for="cedulaRequerido">Cédula</label>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control bg-white" name="nombres" id="nombresRequerido" placeholder="Nombres" required pattern="^[A-Za-zÀ-ÖØ-öø-ÿ ]+$" title="Solo letras y espacios" oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ ]+/g, '')">
                                        <label for="nombresRequerido">Nombres</label>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control bg-white" name="apellidos" id="apellidosRequerido" placeholder="Apellidos" required pattern="^[A-Za-zÀ-ÖØ-öø-ÿ ]+$" title="Solo letras y espacios" oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ ]+/g, '')">
                                        <label for="apellidosRequerido">Apellidos</label>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-floating">
                                        <input id="telefonoRequerido" type="number" name="telefono" class="form-control bg-white" placeholder="Teléfono" value="{{ old('telefono') }}" required oninput="if(this.value.length>11)this.value=this.value.slice(0,11)">
                                        <label for="telefonoRequerido">Teléfono</label>
                                    </div>
                                </div>

                                <div class="col-12 col-md-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control bg-white" name="direccion" id="direccionRequerido" placeholder="Dirección" required>
                                        <label for="direccionRequerido">Dirección</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control bg-white" name="observaciones" id="observaciones" placeholder="Observaciones" required>
                                <label for="observaciones">Observaciones</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <h6>Nueva cita</h6>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="mb-2" for="start">Hora</label>
                                <input required type="text" name="hora_citacion" class="form-control time_flatpicker" placeholder="Hora de la citación">
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="mb-2" for="fecha_citacion">Fecha</label>
                                <div class="input-group wrap_flatpicker">
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