{{-- Modal --}}
<div class="modal fade" id="modalConciliacion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalConciliacionLabel">Conciliar denuncia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-2">
                <form class="needs-validation" novalidate id="formConciliacion"
                    action="{{ route('citaciones.conciliar') }}" autocomplete="off" method="POST">
                    @csrf
                    <input type="text" name="expediente_id" id="cita_expediente_id_conciliar" hidden>


                    <div class="row g-4">


                        <div class="col-12 col-lg-5 border-end-lg" id="datos_denunciado_container">
                            <h6 class="text-primary mb-3 border-bottom pb-1">Datos del Requerido / Denunciado</h6>

                            {{-- Contenedor de bloques de denunciados --}}
                            <div id="denunciados-container">
                                <div class="denunciado-block mb-3" data-index="0">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="form-floating">
                                                <input type="number" name="denunciados[0][cedula]" class="form-control bg-white denunciado-cedula" placeholder="Cédula" required oninput="if(this.value.length>8)this.value=this.value.slice(0,8)">
                                                <label>Cédula del requerido</label>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="form-floating">
                                                <input type="text" class="form-control bg-white" name="denunciados[0][nombres]"
                                                    placeholder="Nombres" required
                                                    pattern="^[A-Za-zÀ-ÖØ-öø-ÿ ]+$" title="Solo letras y espacios"
                                                    oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ ]+/g, '')">
                                                <label>Nombres</label>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="form-floating">
                                                <input type="text" class="form-control bg-white" name="denunciados[0][apellidos]"
                                                    placeholder="Apellidos" required
                                                    pattern="^[A-Za-zÀ-ÖØ-öø-ÿ ]+$" title="Solo letras y espacios"
                                                    oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ ]+/g, '')">
                                                <label>Apellidos</label>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-floating">
                                                <input type="number" name="denunciados[0][telefono]"
                                                    class="form-control bg-white" placeholder="Teléfono" required
                                                    oninput="if(this.value.length>11)this.value=this.value.slice(0,11)">
                                                <label>Teléfono</label>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-floating">
                                                <textarea class="form-control bg-white" name="denunciados[0][direccion]" placeholder="Dirección" style="height: 85px;"
                                                    required></textarea>
                                                <label>Dirección</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-outline-primary btn-sm w-100 mb-2" id="btn-agregar-denunciado">
                                <i class="ri-user-add-line me-1"></i> Agregar otro requerido
                            </button>
                        </div>

                        <div class="col-12 col-lg-7">
                            <h6 class="text-primary mb-3 border-bottom pb-1">Hechos y Conclusiones</h6>
                            <div class="row g-2">
                                <div class="col-12 col-md-6">
                                    <label for="requirente"
                                        class="form-label mb-1 fw-bold text-muted small">Requirente:</label>
                                    <textarea name="requirente" id="requirente" class="form-control bg-white" rows="2" required></textarea>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="requerido"
                                        class="form-label mb-1 fw-bold text-muted small">Requerido:</label>
                                    <textarea name="requerido" id="requerido" class="form-control bg-white" rows="2" required></textarea>
                                </div>

                                <div class="col-12">
                                    <label for="coordinador" class="form-label mb-1 fw-bold text-muted small">El
                                        coordinador una vez escuchados los hechos expone:</label>
                                    <textarea name="coordinador" id="coordinador" class="form-control bg-white" rows="2" required></textarea>
                                </div>

                                <div class="col-12">
                                    <label for="acuerdos" class="form-label mb-1 fw-bold text-muted small">Acuerdos
                                        para el cumplimiento voluntario:</label>
                                    <textarea name="acuerdos" id="acuerdos" class="form-control bg-white" rows="2" required></textarea>
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
    function initDenunciadosRepetibles() {
        var container = document.getElementById('denunciados-container');
        var btnAgregar = document.getElementById('btn-agregar-denunciado');
        if (!container || !btnAgregar) return;

        var denunciadoIndex = 1;

        btnAgregar.addEventListener('click', function() {
            var idx = denunciadoIndex++;
            var block = document.createElement('div');
            block.className = 'denunciado-block mb-3 border-top pt-3';
            block.setAttribute('data-index', idx);
            block.innerHTML =
                '<div class="d-flex justify-content-between align-items-center mb-2">' +
                    '<span class="fw-bold text-muted small"><i class="ri-user-line me-1"></i>Requerido #' + (idx + 1) + '</span>' +
                    '<button type="button" class="btn btn-outline-danger btn-sm btn-remove-denunciado" title="Eliminar"><i class="ri-delete-bin-line"></i></button>' +
                '</div>' +
                '<div class="row g-3">' +
                    '<div class="col-12">' +
                        '<div class="form-floating">' +
                            '<input type="number" name="denunciados[' + idx + '][cedula]" class="form-control bg-white denunciado-cedula" placeholder="Cédula" required oninput="if(this.value.length>8)this.value=this.value.slice(0,8)">' +
                            '<label>Cédula del requerido</label>' +
                        '</div>' +
                    '</div>' +
                    '<div class="col-12 col-md-6">' +
                        '<div class="form-floating">' +
                            '<input type="text" class="form-control bg-white" name="denunciados[' + idx + '][nombres]" placeholder="Nombres" required pattern="^[A-Za-zÀ-ÖØ-öø-ÿ ]+$" title="Solo letras y espacios" oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ ]+/g, \'\')">' +
                            '<label>Nombres</label>' +
                        '</div>' +
                    '</div>' +
                    '<div class="col-12 col-md-6">' +
                        '<div class="form-floating">' +
                            '<input type="text" class="form-control bg-white" name="denunciados[' + idx + '][apellidos]" placeholder="Apellidos" required pattern="^[A-Za-zÀ-ÖØ-öø-ÿ ]+$" title="Solo letras y espacios" oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ ]+/g, \'\')">' +
                            '<label>Apellidos</label>' +
                        '</div>' +
                    '</div>' +
                    '<div class="col-12">' +
                        '<div class="form-floating">' +
                            '<input type="number" name="denunciados[' + idx + '][telefono]" class="form-control bg-white" placeholder="Teléfono" required oninput="if(this.value.length>11)this.value=this.value.slice(0,11)">' +
                            '<label>Teléfono</label>' +
                        '</div>' +
                    '</div>' +
                    '<div class="col-12">' +
                        '<div class="form-floating">' +
                            '<textarea class="form-control bg-white" name="denunciados[' + idx + '][direccion]" placeholder="Dirección" style="height: 85px;" required></textarea>' +
                            '<label>Dirección</label>' +
                        '</div>' +
                    '</div>' +
                '</div>';

            container.appendChild(block);

            var cedulaInput = block.querySelector('.denunciado-cedula');
            if (cedulaInput) {
                cedulaInput.addEventListener('blur', function() {
                    buscarDenunciadoPorCedulaEnBloque(block);
                });
            }
        });

        container.addEventListener('click', function(e) {
            var btn = e.target.closest('.btn-remove-denunciado');
            if (btn) {
                var block = btn.closest('.denunciado-block');
                if (block) block.remove();
            }
        });

        var primerCedula = container.querySelector('.denunciado-cedula');
        if (primerCedula) {
            primerCedula.addEventListener('blur', function() {
                var block = this.closest('.denunciado-block');
                if (block) buscarDenunciadoPorCedulaEnBloque(block);
            });
        }
    }

    function buscarDenunciadoPorCedulaEnBloque(block) {
        var cedulaInput = block.querySelector('input[name$="[cedula]"]');
        if (!cedulaInput) return;
        var cedula = cedulaInput.value.trim();
        if (!/^[0-9]{7,8}$/.test(cedula)) return;

        var xhr = new XMLHttpRequest();
        xhr.open('GET', '{{ route("denuncias.buscar-persona") }}?cedula=' + encodeURIComponent(cedula), true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onload = function() {
            if (xhr.status === 200) {
                try {
                    var data = JSON.parse(xhr.responseText);
                    var nombresInput = block.querySelector('input[name$="[nombres]"]');
                    var apellidosInput = block.querySelector('input[name$="[apellidos]"]');
                    var telefonoInput = block.querySelector('input[name$="[telefono]"]');
                    var direccionInput = block.querySelector('textarea[name$="[direccion]"]');
                    if (nombresInput) nombresInput.value = data.nombres || '';
                    if (apellidosInput) apellidosInput.value = data.apellidos || '';
                    if (telefonoInput) telefonoInput.value = data.telefono || '';
                    if (direccionInput) direccionInput.value = data.direccion || '';
                } catch(e) {}
            }
        };
        xhr.send();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initDenunciadosRepetibles);
    } else {
        initDenunciadosRepetibles();
    }
})();
</script>
