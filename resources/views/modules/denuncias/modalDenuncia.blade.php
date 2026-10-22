{{-- Modal --}}
<div class="modal fade" id="modalRegistrarDenuncia" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalRegistrarDenunciaLabel">REGISTRAR DENUNCIA</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-2">
                <form class="needs-validation" novalidate id="formDenuncia" action="{{ route('denuncias.store') }}"
                    autocomplete="off" method="POST">
                    @csrf

                    <div class="row g-4">
                        <div class="col-12 col-lg-6 border-end-lg">
                            {{-- <h6 class="text-primary mb-3 border-bottom pb-1">Datos del Denunciante</h6> --}}

                            {{-- Contenedor de bloques de denunciantes --}}
                            <div id="denunciantes-container">
                                {{-- Bloque inicial (índice 0) --}}
                                <div class="denunciante-block mb-3" data-index="0">
                                    <div class="row g-3">
                                        <div class="col-12 col-md-6">
                                            <label class="text-primary fw-bold mb-1">CÉDULA DEL DENUNCIANTE</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa-solid fa-id-card"></i></span>
                                                <input type="number" name="denunciantes[0][cedula]" class="form-control denunciante-cedula" placeholder="15000100" required oninput="if(this.value.length>8)this.value=this.value.slice(0,8)">
                                                <span class="input-group-text denunciante-cedula-status d-none" aria-live="polite"></span>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="text-primary fw-bold mb-1">TELÉFONO</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
                                                <input type="number" name="denunciantes[0][telefono]" class="form-control bg-light" placeholder="0412XXXXXXX" required readonly oninput="if(this.value.length>11)this.value=this.value.slice(0,11)">
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="text-primary fw-bold mb-1">NOMBRE</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                                <input type="text" class="form-control bg-light" name="denunciantes[0][nombres]" placeholder="Juan" required readonly pattern="^[A-Za-zÀ-ÖØ-öø-ÿ ]+$" title="Solo letras y espacios" oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ ]+/g, '')">
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="text-primary fw-bold mb-1">APELLIDO</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                                <input type="text" class="form-control bg-light" name="denunciantes[0][apellidos]" placeholder="García" required readonly pattern="^[A-Za-zÀ-ÖØ-öø-ÿ ]+$" title="Solo letras y espacios" oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ ]+/g, '')">
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label class="text-primary fw-bold mb-1">DIRECCIÓN</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa-solid fa-house-chimney"></i></span>
                                                <input type="text" class="form-control bg-light" name="denunciantes[0][direccion]" placeholder="Calle 1, Casa N° 23, Sector 4" required readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Botón para agregar otro denunciante --}}
                            <button type="button" class="btn btn-primary btn-sm w-100 mb-4"
                                id="btn-agregar-denunciante">
                                <i class="ri-user-add-line me-1"></i> Agregar otro denunciante
                            </button>

                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label for="caso" class="text-primary fw-bold mb-1">BREVE DESCRIPCIÓN DEL CASO</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-pen-to-square"></i></span>
                                        <input type="text" class="form-control" name="caso" id="caso" placeholder="Ej. Problema de lindero" required pattern="^[A-Za-zÀ-ÖØ-öø-ÿ ]+$" title="Solo letras y espacios" oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ ]+/g, '')">
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-6">
                                    <label for="denunciado_a" class="text-primary fw-bold mb-1">¿A QUIÉN SE VA A DENUNCIAR?</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-person-circle-question"></i></span>
                                        <input type="text" class="form-control" name="denunciado_a" id="denunciado_a" placeholder="Ej. Nombre, apodo, etc." value="{{ old('denunciado_a') }}" required>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="tipo_caso" class="text-primary fw-bold mb-1">TIPO DE CASO</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-tag"></i></span>
                                        <select class="form-select" name="tipo_caso" id="tipo_caso"
                                            placeholder="Tipo de caso" required>
                                            <option value="">Elija una opción</option>
                                            <option value="Convivencia vecinal" {{ old('tipo_caso') == 'Convivencia vecinal' ? 'selected' : '' }}>Convivencia vecinal</option>
                                            <option value="Convivencia familiar" {{ old('tipo_caso') == 'Convivencia familiar' ? 'selected' : '' }}>Convivencia familiar</option>
                                            <option value="Servicios públicos y ambientales" {{ old('tipo_caso') == 'Servicios públicos y ambientales' ? 'selected' : '' }}>Servicios públicos y ambientales</option>
                                            <option value="Violencia y grupos vulnerables" {{ old('tipo_caso') == 'Violencia y grupos vulnerables' ? 'selected' : '' }}>
                                                Violencia y grupos vulnerables</option>
                                            <option value="Vivienda y propiedad" {{ old('tipo_caso') == 'Vivienda y propiedad' ? 'selected' : '' }}>Vivienda y propiedad</option>
                                            <option value="Organización comunitaria" {{ old('tipo_caso') == 'Organización comunitaria' ? 'selected' : '' }}>Organización comunitaria</option>
                                            <option value="Patrimoniales" {{ old('tipo_caso') == 'Patrimoniales' ? 'selected' : '' }}>Patrimoniales</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="categoria" class="text-primary fw-bold mb-1">CATEGORÍA</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-tags"></i></span>
                                        <select class="form-select" name="categoria" id="categoria"
                                            placeholder="Categoría" required disabled>
                                            <option value="">Elija una opción</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-6">
                            {{-- <h6 class="text-primary mb-3 border-bottom pb-1">Declaraciones de la Denuncia</h6> --}}
                            <div class="row g-2">
                                <div class="col-12">
                                    <label for="requirente" class="form-label mb-1 fw-bold text-primary">EL DENUNCIANTE EXPONE:</label>
                                    <textarea name="requirente" id="requirente" class="form-control" rows="4" required placeholder="Transcriba los hechos relatados por el denunciante."></textarea>
                                </div>

                                <div class="col-12">
                                    <label for="receptor" class="form-label mb-1 fw-bold text-primary">EL RECEPTOR EXPONE:</label>
                                    <textarea name="receptor" id="receptor" class="form-control" rows="4" required placeholder="Detalle su punto de vista como receptor de la denuncia."></textarea>
                                </div>

                                <div class="col-12">
                                    <label for="acuerdos" class="form-label mb-1 fw-bold text-primary">ACUERDOS:</label>
                                    <textarea name="acuerdos" id="acuerdos" class="form-control" rows="5" required placeholder="Realice un resumen de los acuerdos establecidos entre las partes para continuar con la denuncia."></textarea>
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
    (function () {
        // ==========================================
        // Lógica de bloques repetibles de denunciantes
        // ==========================================
        function initDenunciantesRepetibles() {
            var container = document.getElementById('denunciantes-container');
            var btnAgregar = document.getElementById('btn-agregar-denunciante');
            if (!container || !btnAgregar) return;

            var denuncianteIndex = 1; // El bloque 0 ya existe

            btnAgregar.addEventListener('click', function () {
                var idx = denuncianteIndex++;
                var block = document.createElement('div');
                block.className = 'denunciante-block mb-3 border-top pt-3';
                block.setAttribute('data-index', idx);
                block.innerHTML =
                    '<div class="d-flex justify-content-between align-items-center mb-2">' +
                    '<span class="fw-bold text-muted small"><i class="ri-user-line me-1"></i>Denunciante #' + (idx + 1) + '</span>' +
                    '<button type="button" class="btn btn-danger btn-sm btn-remove-denunciante" title="Eliminar denunciante"><i class="ri-delete-bin-line"></i></button>' +
                    '</div>' +
                    '<div class="row g-3">' +
                    '<div class="col-12 col-md-6">' +
                    '<label class="text-primary fw-bold mb-1">CÉDULA DEL DENUNCIANTE</label>' +
                    '<div class="input-group">' +
                    '<span class="input-group-text"><i class="fa-solid fa-address-card"></i></span>' +
                    '<input type="number" name="denunciantes[' + idx + '][cedula]" class="form-control denunciante-cedula" placeholder="15000100" required oninput="if(this.value.length>8)this.value=this.value.slice(0,8)">' +
                    '<span class="input-group-text denunciante-cedula-status d-none" aria-live="polite"></span>' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-12 col-md-6">' +
                    '<label class="text-primary fw-bold mb-1">TELÉFONO</label>' +
                    '<div class="input-group">' +
                    '<span class="input-group-text"><i class="fa-solid fa-phone"></i></span>' +
                    '<input type="number" name="denunciantes[' + idx + '][telefono]" class="form-control bg-light" placeholder="0412XXXXXXX" required readonly oninput="if(this.value.length>11)this.value=this.value.slice(0,11)">' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-12 col-md-6">' +
                    '<label class="text-primary fw-bold mb-1">NOMBRE</label>' +
                    '<div class="input-group">' +
                    '<span class="input-group-text"><i class="fa-solid fa-user"></i></span>' +
                    '<input type="text" class="form-control bg-light" name="denunciantes[' + idx + '][nombres]" placeholder="Juan" required readonly pattern="^[A-Za-zÀ-ÖØ-öø-ÿ ]+$" title="Solo letras y espacios" oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ ]+/g, \'\')">' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-12 col-md-6">' +
                    '<label class="text-primary fw-bold mb-1">APELLIDO</label>' +
                    '<div class="input-group">' +
                    '<span class="input-group-text"><i class="fa-solid fa-user"></i></span>' +
                    '<input type="text" class="form-control bg-light" name="denunciantes[' + idx + '][apellidos]" placeholder="García" required readonly pattern="^[A-Za-zÀ-ÖØ-öø-ÿ ]+$" title="Solo letras y espacios" oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ ]+/g, \'\')">' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-12">' +
                    '<label class="text-primary fw-bold mb-1">DIRECCIÓN</label>' +
                    '<div class="input-group">' +
                    '<span class="input-group-text"><i class="fa-solid fa-house-chimney"></i></span>' +
                    '<input type="text" class="form-control bg-light" name="denunciantes[' + idx + '][direccion]" placeholder="Calle 1, Casa N° 23, Sector 4" required readonly>' +
                    '</div>' +
                    '</div>' +
                    '</div>';

                container.appendChild(block);

            });

            // Delegación de evento para eliminar bloques
            container.addEventListener('click', function (e) {
                var btn = e.target.closest('.btn-remove-denunciante');
                if (btn) {
                    var block = btn.closest('.denunciante-block');
                    if (block) block.remove();
                }
            });

            container.addEventListener('input', function (event) {
                var cedulaInput = event.target.closest('.denunciante-cedula');
                if (!cedulaInput) return;

                var block = cedulaInput.closest('.denunciante-block');
                limpiarDatosDenunciante(block);
                cedulaInput.dataset.censoValido = 'false';

                var cedula = cedulaInput.value.trim();
                if (!cedula) {
                    establecerEstadoCedula(block, 'empty', '');
                    return;
                }
                if (!/^[0-9]{7,8}$/.test(cedula)) {
                    establecerEstadoCedula(block, 'invalid', 'Ingrese una cédula válida para consultar el censo.');
                    return;
                }

                establecerEstadoCedula(block, 'loading', 'Verificando cédula en el censo...');
                if (cedulaInput.dataset.lookupTimeout) {
                    clearTimeout(Number(cedulaInput.dataset.lookupTimeout));
                }

                var version = Number(cedulaInput.dataset.lookupVersion || 0) + 1;
                cedulaInput.dataset.lookupVersion = String(version);
                cedulaInput.dataset.lookupTimeout = String(setTimeout(function () {
                    buscarPersonaPorCedulaEnBloque(block, version);
                }, 250));
            });

            container.addEventListener('focusout', function (event) {
                var cedulaInput = event.target.closest('.denunciante-cedula');
                if (!cedulaInput || !/^[0-9]{7,8}$/.test(cedulaInput.value.trim())) return;

                if (cedulaInput.dataset.lookupTimeout) {
                    clearTimeout(Number(cedulaInput.dataset.lookupTimeout));
                }
                var version = Number(cedulaInput.dataset.lookupVersion || 0);
                buscarPersonaPorCedulaEnBloque(cedulaInput.closest('.denunciante-block'), version);
            });

            var form = document.getElementById('formDenuncia');
            form.addEventListener('submit', function (event) {
                var bloques = Array.from(container.querySelectorAll('.denunciante-block'));
                var bloqueInvalido = bloques.find(function (block) {
                    var cedulaInput = block.querySelector('.denunciante-cedula');
                    return cedulaInput.dataset.censoValido !== 'true';
                });

                if (bloqueInvalido) {
                    event.preventDefault();
                    event.stopImmediatePropagation();
                    var cedulaInput = bloqueInvalido.querySelector('.denunciante-cedula');
                    establecerEstadoCedula(bloqueInvalido, 'invalid', 'La cédula debe existir en el censo para registrar la denuncia.');
                    cedulaInput.focus();
                    mostrarAlertaDenuncia('Verifique la cédula', 'Cada denunciante debe tener una cédula registrada en el censo.', 'warning');
                    return;
                }

                var bloqueIncompleto = bloques.find(function (block) {
                    return Array.from(block.querySelectorAll('input[readonly][required]'))
                        .some(function (input) { return !input.value.trim(); });
                });

                if (bloqueIncompleto) {
                    event.preventDefault();
                    event.stopImmediatePropagation();
                    bloqueIncompleto.querySelector('.denunciante-cedula').focus();
                    mostrarAlertaDenuncia(
                        'Datos censales incompletos',
                        'La persona existe en el censo, pero falta algún dato obligatorio. Complete nombre, apellido, teléfono y dirección en el censo antes de registrar la denuncia.',
                        'warning'
                    );
                }
            });
        }

        function limpiarDatosDenunciante(block) {
            ['nombres', 'apellidos', 'telefono', 'direccion'].forEach(function (campo) {
                var input = block.querySelector('input[name$="[' + campo + ']"]');
                if (input) input.value = '';
            });
        }

        function establecerEstadoCedula(block, estado, mensaje) {
            var status = block.querySelector('.denunciante-cedula-status');
            if (!status) return;

            status.className = 'input-group-text denunciante-cedula-status';
            if (estado === 'empty') {
                status.classList.add('d-none');
                status.innerHTML = '';
                status.removeAttribute('title');
                return;
            }

            status.classList.remove('text-success', 'text-danger', 'text-muted');
            status.title = mensaje;

            if (estado === 'valid') {
                status.classList.add('text-success');
                status.innerHTML = '<i class="ri-checkbox-circle-fill" aria-label="Cédula encontrada en el censo"></i>';
                block.querySelector('.denunciante-cedula').dataset.censoValido = 'true';
            } else if (estado === 'loading') {
                status.classList.add('text-muted');
                status.innerHTML = '<i class="fa-solid fa-spinner fa-spin" aria-label="Verificando cédula"></i>';
                block.querySelector('.denunciante-cedula').dataset.censoValido = 'false';
            } else {
                status.classList.add('text-danger');
                status.innerHTML = '<i class="ri-error-warning-fill" aria-label="Cédula no válida o no encontrada"></i>';
                block.querySelector('.denunciante-cedula').dataset.censoValido = 'false';
            }
        }

        function mostrarAlertaDenuncia(titulo, mensaje, icono) {
            if (window.Swal) {
                window.Swal.fire({ title: titulo, text: mensaje, icon: icono, confirmButtonText: 'Entendido' });
            } else {
                window.alert(mensaje);
            }
        }

        function buscarPersonaPorCedulaEnBloque(block, version) {
            var cedulaInput = block.querySelector('input[name$="[cedula]"]');
            if (!cedulaInput || Number(cedulaInput.dataset.lookupVersion || 0) !== version) return;
            var cedula = cedulaInput.value.trim();
            if (!/^[0-9]{7,8}$/.test(cedula)) return;

            var xhr = new XMLHttpRequest();
            xhr.open('GET', '{{ route("denuncias.buscar-persona") }}?cedula=' + encodeURIComponent(cedula), true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.onload = function () {
                if (Number(cedulaInput.dataset.lookupVersion || 0) !== version || cedulaInput.value.trim() !== cedula) return;

                if (xhr.status === 200) {
                    try {
                        var data = JSON.parse(xhr.responseText);
                        var nombresInput = block.querySelector('input[name$="[nombres]"]');
                        var apellidosInput = block.querySelector('input[name$="[apellidos]"]');
                        var telefonoInput = block.querySelector('input[name$="[telefono]"]');
                        var direccionInput = block.querySelector('input[name$="[direccion]"]');
                        if (nombresInput) nombresInput.value = data.nombres || '';
                        if (apellidosInput) apellidosInput.value = data.apellidos || '';
                        if (telefonoInput) telefonoInput.value = data.telefono || '';
                        if (direccionInput) direccionInput.value = data.direccion || '';
                        establecerEstadoCedula(block, 'valid', 'Cédula encontrada en el censo.');
                    } catch (error) {
                        establecerEstadoCedula(block, 'invalid', 'No se pudo procesar la respuesta del censo.');
                    }
                } else if (xhr.status === 404) {
                    limpiarDatosDenunciante(block);
                    establecerEstadoCedula(block, 'invalid', 'Cédula no encontrada en el censo.');
                } else {
                    limpiarDatosDenunciante(block);
                    establecerEstadoCedula(block, 'invalid', 'No se pudo verificar la cédula. Intente nuevamente.');
                }
            };
            xhr.onerror = function () {
                if (Number(cedulaInput.dataset.lookupVersion || 0) !== version || cedulaInput.value.trim() !== cedula) return;
                limpiarDatosDenunciante(block);
                establecerEstadoCedula(block, 'invalid', 'No se pudo conectar con el censo. Intente nuevamente.');
            };
            xhr.send();
        }

        // ==========================================
        // Lógica de categorías dependientes (existente)
        // ==========================================
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
                categoriaSelect.innerHTML = '<option value="">Elija una opción</option>';

                if (tipoSeleccionado && categoriasPorTipo[tipoSeleccionado]) {
                    categoriasPorTipo[tipoSeleccionado].forEach(function (categoria) {
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

            tipoCasoSelect.addEventListener('change', function () {
                actualizarCategorias(this.value);
            });

            // Soporte si se usa jQuery para disparar evento change
            if (window.jQuery) {
                $(tipoCasoSelect).on('change', function () {
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
                form.addEventListener('reset', function () {
                    setTimeout(function () {
                        actualizarCategorias('');
                    }, 0);
                });
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function () {
                initCategoriasDenuncia();
                initDenunciantesRepetibles();
            });
        } else {
            initCategoriasDenuncia();
            initDenunciantesRepetibles();
        }
    })();
</script>