{{-- Modal Registrar Consejo Comunal --}}
<div class="modal fade" id="modalRegistrarConsejo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalRegistrarConsejoLabel">REGISTRAR CONSEJO COMUNAL</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" novalidate id="formRegistrarConsejo"
                    action="{{ route('consejos-comunales.store') }}" autocomplete="off" method="POST">
                    @csrf
                    <div class="row g-3">
                        <!-- Nombre del Consejo -->
                        <div class="col-12 col-md-6">
                            <label class="text-primary fw-bold mb-1" for="nombre">NOMBRE DEL CONSEJO COMUNAL</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-people-roof"></i></span>
                                <input type="text" class="form-control bg-white" name="nombre" id="nombre" placeholder="Ej. Brisas del Sur" required maxlength="255" value="{{ old('nombre') }}">
                            </div>
                        </div>

                        <!-- RIF -->
                        <div class="col-12 col-md-6">
                            <label class="text-primary fw-bold mb-1" for="rif">RIF</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-file-lines"></i></span>
                                <input type="text" class="form-control bg-white" name="rif" id="rif"
                                    placeholder="Ej. 123456789" required maxlength="10" pattern="^C?\d{9}$" title="Ingrese 9 dígitos numéricos (Ej: 123456789)" value="{{ old('rif') ? preg_replace('/^C/i', '', old('rif')) : '' }}">
                            </div>
                            <small class="text-muted">El prefijo <strong>C</strong> será agregado automáticamente.</small>
                        </div>

                        <!-- Búsqueda de Jefe de Comando -->
                        <div class="col-12 mb-3">
                            <label class="form-label text-primary fw-bold mb-1">JEFE DE COMANDO</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">Cédula</span>
                                <input type="text" class="form-control bg-white" id="buscar_jefe_cedula" placeholder="Ingrese la cédula de una persona censada y presione Buscar" minlength="7" maxlength="8" pattern="^[0-9]{7,8}$" title="Ingrese entre 7 y 8 dígitos numéricos" inputmode="numeric" autocomplete="off">
                                <button class="btn btn-primary" type="button" id="btn_buscar_jefe">
                                    <i class="ri-search-line"></i> Buscar
                                </button>
                            </div>
                        </div>

                        <!-- Contenedor para mostrar jefe seleccionado como input readonly -->
                        <div id="jefe_confirmacion_container" class="mt-2 d-none">
                            <label class="text-primary fw-bold mb-1" for="jefe_nombre_completo">JEFE SELECCIONADO</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-clipboard-user"></i></span>
                                <input type="text" id="jefe_nombre_completo" class="form-control bg-white" placeholder="Nombre y Apellido" readonly>
                            </div>
                            <div class="mt-2 mb-3 text-end">
                                <button type="button" class="btn btn-sm btn-outline-danger" id="btn_remover_jefe">
                                    <i class="ri-close-circle-fill"></i> Quitar
                                </button>
                            </div>
                        </div>

                        <!-- Input hidden para almacenar el ID de la persona -->
                        <input type="hidden" name="jefe_comando" id="jefe_comando_id" value="{{ old('jefe_comando') }}" required>
                    </div>

                    <!-- Dirección -->
                    <div class="col-12">
                        <label class="text-primary fw-bold mb-1" for="direccion">DIRECCIÓN DETALLADA</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-location-dot"></i></span>
                            <textarea class="form-control bg-white" name="direccion" id="direccion" placeholder="Ingrese la dirección detallada del consejo comunal" required rows="1">{{ old('direccion') }}</textarea>
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
