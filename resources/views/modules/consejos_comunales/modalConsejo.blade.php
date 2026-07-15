{{-- Modal Registrar Consejo Comunal --}}
<div class="modal fade" id="modalRegistrarConsejo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRegistrarConsejoLabel">Registrar Consejo Comunal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" novalidate id="formRegistrarConsejo"
                    action="{{ route('consejos-comunales.store') }}" autocomplete="off" method="POST">
                    @csrf
                    <div class="row g-3">
                        <!-- Nombre del Consejo -->
                        <div class="col-12 col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control bg-white" name="nombre" id="nombre"
                                    placeholder="Nombre del Consejo" required maxlength="255"
                                    value="{{ old('nombre') }}">
                                <label for="nombre">Nombre del Consejo Comunal</label>
                            </div>
                        </div>

                        <!-- RIF -->
                        <div class="col-12 col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control bg-white" name="rif" id="rif"
                                    placeholder="123456789" required maxlength="10" pattern="^C?\d{9}$"
                                    title="Ingrese 9 dígitos numéricos (Ej: 123456789) o C seguido de 9 dígitos (Ej: C123456789)"
                                    value="{{ old('rif') ? preg_replace('/^C/i', '', old('rif')) : '' }}">
                                <label for="rif">RIF (Ej: C123456789)</label>
                            </div>
                            <small class="text-muted">El prefijo <strong>C</strong> será agregado
                                automáticamente.</small>
                        </div>

                        <!-- Búsqueda de Jefe de Comando -->
                        <div class="col-12 mb-3">
                            <label class="form-label text-secondary fw-bold">Jefe de Comando (Persona
                                Registrada)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">Cédula</span>
                                <input type="text" class="form-control bg-white" id="buscar_jefe_cedula"
                                    placeholder="Ingrese la cédula de la persona y presione Buscar" minlength="7"
                                    maxlength="8" pattern="^[0-9]{7,8}$" title="Ingrese entre 7 y 8 dígitos numéricos"
                                    inputmode="numeric" autocomplete="off">
                                <button class="btn btn-primary" type="button" id="btn_buscar_jefe">
                                    <i class="ri-search-line"></i> Buscar
                                </button>
                            </div>
                        </div>

                        <!-- Contenedor para mostrar jefe seleccionado como input readonly -->
                        <div id="jefe_confirmacion_container" class="mt-2 d-none">
                            <div class="form-floating">
                                <input type="text" id="jefe_nombre_completo" class="form-control bg-white"
                                    placeholder="Nombre y Apellido" readonly>
                                <label for="jefe_nombre_completo">Jefe seleccionado</label>
                            </div>
                            <div class="mt-2 mb-3 text-end">
                                <button type="button" class="btn btn-sm btn-outline-danger" id="btn_remover_jefe">
                                    <i class="ri-close-circle-fill"></i> Quitar
                                </button>
                            </div>
                        </div>

                        <!-- Input hidden para almacenar el ID de la persona -->
                        <input type="hidden" name="jefe_comando" id="jefe_comando_id" value="{{ old('jefe_comando') }}"
                            required>
                    </div>

                    <!-- Dirección -->
                    <div class="col-12">
                        <div class="form-floating">
                            <textarea class="form-control bg-white" name="direccion" id="direccion" placeholder="Dirección" required maxlength="500"
                                style="height: 100px;">{{ old('direccion') }}</textarea>
                            <label for="direccion">Dirección detallada</label>
                        </div>
                    </div>
            </div>

            <div class="modal-footer mt-4">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary" id="btn_guardar_consejo">Guardar</button>
            </div>
            </form>
        </div>
    </div>
</div>
</div>
