{{-- Modal --}}
<div class="modal fade" id="modalRegistrarVocero" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalRegistrarVoceroLabel">REGISTRAR VOCERO</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-2">
                <form class="needs-validation" novalidate id="formVocero" action="{{ route('voceros.store') }}" autocomplete="off"
                    method="POST">
                    @csrf

                    <div class="row g-4">
                        <div class="col-12">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="text-primary fw-bold mb-1" for="cedula">CÉDULA</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-address-card"></i></span>
                                        <input id="cedula" type="number" name="cedula" class="form-control bg-white" placeholder="Ingrese una cédula para buscar" value="{{ old('cedula') }}" required oninput="if(this.value.length>8)this.value=this.value.slice(0,8)">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="text-primary fw-bold mb-1" for="nombres">NOMBRE</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                        <input type="text" class="form-control bg-white" name="nombres" id="nombres" placeholder="Juan" required pattern="^[A-Za-zÀ-ÖØ-öø-ÿ ]+$" title="Solo letras y espacios" oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ ]+/g, '')">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="text-primary fw-bold mb-1" for="apellidos">APELLIDO</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                        <input type="text" class="form-control bg-white" name="apellidos" id="apellidos" placeholder="García" required pattern="^[A-Za-zÀ-ÖØ-öø-ÿ ]+$" title="Solo letras y espacios" oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ ]+/g, '')">
                                    </div>
                                </div>

                                <div class="col-12 col-md-12">
                                    <label class="text-primary fw-bold mb-1" for="categoria_vocero">CATEGORÍA</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-tag"></i></span>
                                        <select name="categoria_vocero" id="categoria_vocero" class="form-select bg-white" required>
                                            <option value="" selected disabled>Elija una categoría</option>
                                            @foreach ($categorias as $cat)
                                                <option value="{{ $cat->nombre }}">{{ $cat->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-12">
                                    <label class="text-primary fw-bold mb-1" for="fecha_eleccion">FECHA DE ELECCIÓN</label>
                                    <div class="form-group">
                                        <div class="input-group wrap_flatpicker" data-min-date="none">
                                            <span class="input-group-text"><i class="fa-solid fa-calendar"></i></span>
                                            <input required type="text" name="fecha_eleccion" class="form-control bg-white" placeholder="Indique la fecha de elección" data-input>
                                            <a class="input-group-text input-button bg-white" title="limpiar" data-clear href="javascript:void(0)">
                                                <svg width="24" class="icon-24" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer mt-3 pb-0 pe-0">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
