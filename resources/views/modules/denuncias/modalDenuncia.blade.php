{{-- Modal --}}
<div class="modal fade" id="modalRegistrarDenuncia" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl"> {{-- Cambiado a modal-xl para balancear el diseño de dos columnas --}}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRegistrarDenunciaLabel">Registrar denuncia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-2">
                <form class="needs-validation" novalidate id="formDenuncia" action="{{ route('denuncias.store') }}" autocomplete="off" method="POST">
                    @csrf
                    
                    <div class="row g-4">
                        <div class="col-12 col-lg-5 border-end-lg">
                            <h6 class="text-primary mb-3 border-bottom pb-1">Datos del Requirente</h6>
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input id="cedula" type="number" name="cedula" class="form-control bg-white" placeholder="Cédula" value="{{ old('cedula') }}" required oninput="if(this.value.length>8)this.value=this.value.slice(0,8)">
                                        <label for="cedula">Cédula del requirente</label>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control bg-white" name="nombres" id="nombres" placeholder="Nombres" required pattern="^[A-Za-zÀ-ÖØ-öø-ÿ ]+$" title="Solo letras y espacios" oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ ]+/g, '')">
                                        <label for="nombres">Nombres</label>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control bg-white" name="apellidos" id="apellidos" placeholder="Apellidos" required pattern="^[A-Za-zÀ-ÖØ-öø-ÿ ]+$" title="Solo letras y espacios" oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ ]+/g, '')">
                                        <label for="apellidos">Apellidos</label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-floating">
                                        <input id="telefono" type="number" name="telefono" class="form-control bg-white" placeholder="Teléfono" value="{{ old('telefono') }}" required oninput="if(this.value.length>11)this.value=this.value.slice(0,11)">
                                        <label for="telefono">Teléfono</label>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control bg-white" name="direccion" id="direccion" placeholder="Dirección" required></textarea>
                                        <label for="direccion">Dirección</label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control bg-white" name="caso" id="caso" placeholder="caso" required pattern="^[A-Za-zÀ-ÖØ-öø-ÿ ]+$" title="Solo letras y espacios" oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ ]+/g, '')">
                                        <label for="caso">Breve descripción del caso</label>
                                    </div>
                                </div>

                                {{-- <div class="col-12">
                                        <select name="motivo_denuncia" id="motivo_denuncia" placeholder="Motivo de denuncia" required>
                                            <option value="">Seleccione un motivo</option>
                                            <optgroup label="Convivencia vecinal">
                                                <option value="Ruidos">Ruidos</option>
                                                <option value="Mascotas">Mascotas</option>
                                                <option value="Desecho de basura">Desecho de basura</option>
                                            </optgroup>
                                            <optgroup label="Convivencia familiar">
                                                <option value="Relaciones familiares">Relaciones familiares</option>
                                            </optgroup>
                                            <optgroup label="Servicios públicos y ambientales">
                                                <option value="Agua">Agua</option>
                                                <option value="Electricidad">Electricidad</option>
                                                <option value="Gas">Gas</option>
                                                <option value="Fallas de cloacas">Fallas de cloacas</option>
                                                <option value="Afectaciones ambientales">Afectaciones ambientales</option>
                                            </optgroup>
                                            <optgroup label="Violencia y grupos vulnerables">
                                                <option value="Violencia de genero">Violencia de género</option>
                                                <option value="Situaciones de riesgo niños, niñas y adolescentes">Situaciones de riesgo niños, niñas y adolescentes</option>
                                                <option value="Remitidos a autoridades competentes">Remitidos a autoridades competentes</option>
                                            </optgroup>
                                            <optgroup label="Vivienda y propiedad">
                                                <option value="Linderos">Linderos</option>
                                                <option value="Filtraciones">Filtraciones</option>
                                                <option value="Daños a la propiedad">Daños a la propiedad</option>
                                                <option value="Condominios">Condominios</option>
                                                <option value="Arrendamientos">Arrendamientos</option>
                                            </optgroup>
                                            <optgroup label="Organización comunitaria">
                                                <option value="Disputa por vocerías">Disputa por vocerías</option>
                                                <option value="Uso de bienes comunales">Uso de bienes comunales</option>
                                                <option value="Conflictos internos del consejo comunal">Conflictos internos del consejo comunal</option>
                                            </optgroup>
                                            <optgroup label="Patrimoniales">
                                                <option value="Pequeños daños materiales">Pequeños daños materiales</option>
                                                <option value="Cobros de deudas no complejas (menor cuantía)">Cobros de deudas no complejas (menor cuantía)</option>
                                            </optgroup>
                                        </select> 
                                            
                                </div> --}}

                                <div class="col-12">
                                    <div class="form-floating">
                                        <select class="form-select bg-white" name="tipo_caso" id="tipo_caso" placeholder="Tipo de caso" required>
                                            <option value="">Seleccione un tipo de caso</option>
                                            <option value="Convivencia vecinal" {{ old('tipo_caso') == 'Convivencia vecinal' ? 'selected' : '' }}>Convivencia vecinal</option>
                                            <option value="Convivencia familiar" {{ old('tipo_caso') == 'Convivencia familiar' ? 'selected' : '' }}>Convivencia familiar</option>
                                            <option value="Servicios públicos y ambientales" {{ old('tipo_caso') == 'Servicios públicos y ambientales' ? 'selected' : '' }}>Servicios públicos y ambientales</option>
                                            <option value="Violencia y grupos vulnerables" {{ old('tipo_caso') == 'Violencia y grupos vulnerables' ? 'selected' : '' }}>Violencia y grupos vulnerables</option>
                                            <option value="Vivienda y propiedad" {{ old('tipo_caso') == 'Vivienda y propiedad' ? 'selected' : '' }}>Vivienda y propiedad</option>
                                            <option value="Organización comunitaria" {{ old('tipo_caso') == 'Organización comunitaria' ? 'selected' : '' }}>Organización comunitaria</option>
                                            <option value="Patrimoniales" {{ old('tipo_caso') == 'Patrimoniales' ? 'selected' : '' }}>Patrimoniales</option>
                                        </select>
                                        <label for="tipo_caso">Tipo de caso</label>
                                    </div>
                                </div> 
                                
                                <div class="col-12">
                                    <div class="form-floating">
                                        <select class="form-select bg-white" name="categoria" id="categoria" placeholder="Categoría" required disabled>
                                            <option value="">Seleccione una categoría</option>
                                        </select>
                                        <label for="categoria">Categoría</label>
                                    </div>
                                </div>  

                            </div>
                        </div>

                        <div class="col-12 col-lg-7">
                            <h6 class="text-primary mb-3 border-bottom pb-1">Declaraciones de la Denuncia</h6>
                            <div class="row g-2">
                                <div class="col-12">
                                    <label for="requirente" class="form-label mb-1 fw-bold text-muted small">El requirente expone:</label>
                                    <textarea name="requirente" id="requirente" class="form-control bg-white" rows="5" required></textarea>
                                </div>

                                <div class="col-12">
                                    <label for="receptor" class="form-label mb-1 fw-bold text-muted small">El receptor expone:</label>
                                    <textarea name="receptor" id="receptor" class="form-control bg-white" rows="5" required></textarea>
                                </div>

                                <div class="col-12">
                                    <label for="acuerdos" class="form-label mb-1 fw-bold text-muted small">Acuerdos:</label>
                                    <textarea name="acuerdos" id="acuerdos" class="form-control bg-white" rows="5" required></textarea>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer mt-3 pb-0 pe-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    function initCategoriasDenuncia() {
        const tipoCasoSelect = document.getElementById('tipo_caso');
        const categoriaSelect = document.getElementById('categoria');

        if (!tipoCasoSelect || !categoriaSelect) return;

        const categoriasPorTipo = {
            "Convivencia vecinal": [
                "Ruidos",
                "Mascotas",
                "Desecho de basura"
            ],
            "Convivencia familiar": [
                "Relaciones familiares"
            ],
            "Servicios públicos y ambientales": [
                "Agua",
                "Electricidad",
                "Gas",
                "Fallas de cloacas",
                "Afectaciones ambientales"
            ],
            "Violencia y grupos vulnerables": [
                "Violencia de genero",
                "Situaciones de riesgo niños, niñas y adolescentes",
                "Remitidos a autoridades competentes"
            ],
            "Vivienda y propiedad": [
                "Linderos",
                "Filtraciones",
                "Daños a la propiedad",
                "Condominios",
                "Arrendamientos"
            ],
            "Organización comunitaria": [
                "Disputa por vocerías",
                "Uso de bienes comunales",
                "Conflictos internos del consejo comunal"
            ],
            "Patrimoniales": [
                "Pequeños daños materiales",
                "Cobros de deudas no complejas (menor cuantía)"
            ]
        };

        function actualizarCategorias(tipoSeleccionado, categoriaSeleccionada = '') {
            categoriaSelect.innerHTML = '<option value="">Seleccione una categoría</option>';

            if (tipoSeleccionado && categoriasPorTipo[tipoSeleccionado]) {
                categoriasPorTipo[tipoSeleccionado].forEach(function(categoria) {
                    const option = document.createElement('option');
                    option.value = categoria;
                    option.textContent = categoria;
                    if (categoria === categoriaSeleccionada) {
                        option.selected = true;
                    }
                    categoriaSelect.appendChild(option);
                });
                categoriaSelect.disabled = false;
            } else {
                categoriaSelect.disabled = true;
            }
        }

        tipoCasoSelect.addEventListener('change', function() {
            actualizarCategorias(this.value);
        });

        // Soporte si se usa jQuery para disparar evento change
        if (window.jQuery) {
            $(tipoCasoSelect).on('change', function() {
                actualizarCategorias(this.value);
            });
        }

        // Cargar selección previa (por ejemplo al volver de validación con old())
        const oldTipo = tipoCasoSelect.value;
        const oldCategoria = @json(old('categoria', ''));
        if (oldTipo) {
            actualizarCategorias(oldTipo, oldCategoria);
        }

        // Si se resetea el formulario, restablecer select de categorías a deshabilitado
        const form = document.getElementById('formDenuncia');
        if (form) {
            form.addEventListener('reset', function() {
                setTimeout(function() {
                    actualizarCategorias('');
                }, 0);
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCategoriasDenuncia);
    } else {
        initCategoriasDenuncia();
    }
})();
</script>