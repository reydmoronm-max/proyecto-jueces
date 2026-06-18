@extends('layouts.main')
@section('titulo', $titulo)
@section('paginaTitulo', $paginaTitulo)
@section('paginaSubtitulo', $paginaSubtitulo)
@section('actasActive', $actasActive)

@section('contenido')
    <div class="container-fluid content-inner mt-n5 py-0">
        <div class="row">
            <!-- Panel de Búsqueda -->
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title"><i class="ri-search-2-line text-primary me-2"></i>Buscar Ciudadano</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small">Ingrese la cédula de identidad del ciudadano registrado en el censo para cargar sus datos.</p>
                        
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold" for="buscar_cedula">Cédula de Identidad</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="ri-id-card-line text-muted"></i></span>
                                <input type="number" id="buscar_cedula" class="form-control" placeholder="Ej. 12345678" min="1000000" max="99999999">
                                <button class="btn btn-primary" type="button" id="btn_buscar"><i class="ri-search-line"></i> Buscar</button>
                            </div>
                        </div>

                        <div id="loader_busqueda" class="text-center py-4 d-none">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Buscando...</span>
                            </div>
                            <p class="mt-2 text-muted small">Cargando datos del censo...</p>
                        </div>

                        <!-- Alerta de advertencia/instrucciones -->
                        <div class="alert alert-info d-flex align-items-center mb-0 mt-2" role="alert" id="instruccion_alerta">
                            <i class="ri-information-line fs-4 me-2"></i>
                            <div>
                                El ciudadano debe estar registrado en el censo, tener dirección válida y pertenecer a un Consejo Comunal con Jefe de Comando asignado.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detalle del Ciudadano (Se muestra dinámicamente) -->
                <div class="card d-none" id="card_datos_ciudadano">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="card-title mb-0 text-white"><i class="ri-user-shared-line me-2"></i>Datos del Ciudadano</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0">
                                <span class="text-muted"><i class="ri-user-line me-2"></i>Nombres y Apellidos</span>
                                <strong id="lbl_nombres_apellidos" class="text-dark">---</strong>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0">
                                <span class="text-muted"><i class="ri-fingerprint-line me-2"></i>Cédula</span>
                                <strong id="lbl_cedula" class="text-dark">---</strong>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0">
                                <span class="text-muted"><i class="ri-community-line me-2"></i>Consejo Comunal</span>
                                <strong id="lbl_consejo_comunal" class="text-dark">---</strong>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0">
                                <span class="text-muted"><i class="ri-user-star-line me-2"></i>Jefe de Comando</span>
                                <strong id="lbl_jefe_comando" class="text-dark">---</strong>
                            </div>
                            <div class="list-group-item d-flex flex-column bg-transparent px-0 pb-0">
                                <span class="text-muted mb-1"><i class="ri-map-pin-line me-2"></i>Dirección Registrada</span>
                                <p id="lbl_direccion" class="text-dark font-size-14 font-weight-bold mb-0">---</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel de Generación de Actas (Tabs) -->
            <div class="col-lg-7">
                <div class="card d-none" id="card_generacion_actas">
                    <div class="card-header">
                        <h4 class="card-title"><i class="ri-file-edit-line text-primary me-2"></i>Configuración de Actas</h4>
                    </div>
                    <div class="card-body p-0">
                        <!-- Navigation Tabs -->
                        <ul class="nav nav-tabs nav-fill" id="actasTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active py-3 font-weight-bold" id="residencia-tab" data-bs-toggle="tab" data-bs-target="#residencia" type="button" role="tab" aria-controls="residencia" aria-selected="true">
                                    <i class="ri-home-8-line me-2"></i>Constancia de Residencia
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link py-3 font-weight-bold" id="buena-conducta-tab" data-bs-toggle="tab" data-bs-target="#buena_conducta" type="button" role="tab" aria-controls="buena_conducta" aria-selected="false">
                                    <i class="ri-award-line me-2"></i>Buena Conducta
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content p-4" id="actasTabContent">
                            <!-- TAB: Constancia de Residencia -->
                            <div class="tab-pane fade show active" id="residencia" role="tabpanel" aria-labelledby="residencia-tab">
                                <form action="{{ route('actas.residencia-pdf') }}" method="POST" target="_blank" id="form_residencia">
                                    @csrf
                                    <input type="hidden" name="cedula" class="hidden_cedula">
                                    
                                    <div class="alert alert-warning mb-4 d-flex align-items-center">
                                        <i class="ri-alert-line fs-4 me-2"></i>
                                        <div>
                                            Este documento certifica el tiempo de residencia del ciudadano en el ámbito territorial del Consejo Comunal.
                                        </div>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="form-label font-weight-bold" for="anios_residencia">Años de Domicilio <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="anios_residencia" id="anios_residencia" placeholder="Ingrese los años domiciliado" min="0" max="120" required>
                                            <span class="input-group-text bg-light">años</span>
                                        </div>
                                        <div class="form-text">Indique manualmente el número de años que el ciudadano tiene residiendo en la comunidad.</div>
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="ri-file-pdf-line me-2"></i>Generar Constancia de Residencia (PDF)
                                        </button>
                                    </div>
                                </form>
                            </div>

                             <!-- TAB: Carta de Buena Conducta -->
                             <div class="tab-pane fade" id="buena_conducta" role="tabpanel" aria-labelledby="buena-conducta-tab">
                                 <form action="{{ route('actas.buena-conducta-pdf') }}" method="POST" target="_blank" id="form_buena_conducta">
                                     @csrf
                                     <input type="hidden" name="cedula" class="hidden_cedula">
 
                                     <div class="alert alert-warning mb-4 d-flex align-items-center">
                                         <i class="ri-alert-line fs-4 me-2"></i>
                                         <div>
                                             Este documento hace constar que el ciudadano es un habitante activo de la comunidad con excelente conducta moral, cívica y de convivencia.
                                         </div>
                                     </div>

                                     <!-- Alerta Denunciado -->
                                     <div class="alert alert-danger mb-4 d-none d-flex align-items-center" id="alerta_denunciado">
                                         <i class="ri-error-warning-line fs-4 me-2"></i>
                                         <div>
                                             <strong>Generación Bloqueada:</strong> Este ciudadano figura como denunciado en un expediente registrado en el sistema, por lo que no cumple con los requisitos de conducta para este certificado.
                                         </div>
                                     </div>
 
                                     <p class="text-muted mb-4">No se requiere ingresar campos adicionales. La carta se generará usando los datos de filiación y el Consejo Comunal del censo actual.</p>
 
                                     <div class="d-grid">
                                         <button type="submit" id="btn_buena_conducta_submit" class="btn btn-success btn-lg">
                                             <i class="ri-file-pdf-line me-2"></i>Generar Carta de Buena Conducta (PDF)
                                         </button>
                                     </div>
                                 </form>
                             </div>
                         </div>
                     </div>
                 </div>
 
                 <!-- Panel de Marcador de Posición (Placeholder) -->
                 <div class="card text-center py-5" id="card_placeholder">
                     <div class="card-body">
                         <i class="ri-file-paper-2-line text-muted display-1 mb-4 d-block"></i>
                         <h4 class="text-secondary font-weight-bold">Generador de Documentos</h4>
                         <p class="text-muted px-4 mt-2">Busque y seleccione un ciudadano mediante su cédula a la izquierda para poder previsualizar sus datos y configurar la emisión de actas y constancias.</p>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 @endsection
 
 @push('scripts')
     <script>
         $(document).ready(function() {
             var esDenunciado = false;

             // Event listener for search
             $('#btn_buscar').on('click', function() {
                 realizarBusqueda();
             });
 
             $('#buscar_cedula').on('keypress', function(e) {
                 if (e.which === 13) { // Enter key
                     realizarBusqueda();
                 }
             });

             // Tab change event to notify about block immediately
             $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                 if (e.target.id === 'buena-conducta-tab' && esDenunciado) {
                     mostrarAlertaDenunciado();
                 }
             });

             function mostrarAlertaDenunciado() {
                 Swal.fire({
                     title: 'Generación Bloqueada',
                     text: 'El ciudadano figura como denunciado en un expediente registrado en el sistema, por lo que no es posible emitir una Carta de Buena Conducta.',
                     icon: 'error',
                     confirmButtonText: 'Aceptar'
                 });
             }
 
             function realizarBusqueda() {
                 var cedula = $('#buscar_cedula').val().trim();
                 esDenunciado = false;
                 
                 if (cedula === '') {
                     Swal.fire({
                         title: 'Campo vacío',
                         text: 'Por favor, ingrese un número de cédula.',
                         icon: 'warning',
                         confirmButtonText: 'Entendido'
                     });
                     return;
                 }
 
                 if (!/^\d{7,8}$/.test(cedula)) {
                     Swal.fire({
                         title: 'Formato inválido',
                         text: 'La cédula debe contener entre 7 y 8 dígitos numéricos.',
                         icon: 'error',
                         confirmButtonText: 'Corregir'
                     });
                     return;
                 }
 
                 // Show loader and hide content/placeholder
                 $('#loader_busqueda').removeClass('d-none');
                 $('#instruccion_alerta').addClass('d-none');
                 
                 $('#card_datos_ciudadano').addClass('d-none');
                 $('#card_generacion_actas').addClass('d-none');
                 $('#card_placeholder').removeClass('d-none');
 
                 $.ajax({
                     url: '{{ route("actas.buscar-persona") }}',
                     method: 'GET',
                     data: {
                         cedula: cedula
                     },
                     success: function(response) {
                         $('#loader_busqueda').addClass('d-none');
                         
                         var persona = response.persona;
                         var consejoComunal = response.consejo_comunal;
                         var jefeComando = response.jefe_comando;
                         esDenunciado = response.es_denunciado;
 
                         // Fill labels
                         $('#lbl_nombres_apellidos').text(persona.nombres + ' ' + persona.apellidos);
                         $('#lbl_cedula').text((persona.cedula_tipo || 'V') + '-' + persona.cedula);
                         $('#lbl_consejo_comunal').text(consejoComunal.nombre);
                         $('#lbl_jefe_comando').text(jefeComando.nombres + ' ' + jefeComando.apellidos);
                         $('#lbl_direccion').text(persona.direccion || 'No registrada');
 
                         // Fill hidden inputs
                         $('.hidden_cedula').val(persona.cedula);

                         // Check if citizen is a 'denunciado'
                         if (esDenunciado) {
                             $('#alerta_denunciado').removeClass('d-none');
                             $('#btn_buena_conducta_submit').prop('disabled', true).removeClass('btn-success').addClass('btn-secondary');
                             if ($('#buena-conducta-tab').hasClass('active')) {
                                 mostrarAlertaDenunciado();
                             }
                         } else {
                             $('#alerta_denunciado').addClass('d-none');
                             $('#btn_buena_conducta_submit').prop('disabled', false).removeClass('btn-secondary').addClass('btn-success');
                         }
 
                         // Show cards and hide placeholder
                         $('#card_datos_ciudadano').removeClass('d-none');
                         $('#card_generacion_actas').removeClass('d-none');
                         $('#card_placeholder').addClass('d-none');
                     },
                     error: function(xhr) {
                         $('#loader_busqueda').addClass('d-none');
                         $('#instruccion_alerta').removeClass('d-none');
                         
                         var message = 'No se pudo completar la búsqueda.';
                         if (xhr.responseJSON && xhr.responseJSON.message) {
                             message = xhr.responseJSON.message;
                         } else if (xhr.status === 404) {
                             message = 'El ciudadano con la cédula ingresada no se encuentra registrado en el censo.';
                         }
 
                         Swal.fire({
                             title: 'Búsqueda Fallida',
                             text: message,
                             icon: 'error',
                             confirmButtonText: 'Aceptar'
                         });
                     }
                 });
             }
 
             // Client side validation for residency form submit
             $('#form_residencia').on('submit', function(e) {
                 var anios = $('#anios_residencia').val();
                 if (anios === '' || anios < 0 || anios > 120) {
                     e.preventDefault();
                     Swal.fire({
                         title: 'Años no válidos',
                         text: 'Por favor, ingrese un número válido de años de domicilio (0 a 120).',
                         icon: 'warning',
                         confirmButtonText: 'Aceptar'
                     });
                     return false;
                 }
             });
         });
     </script>
 @endpush
