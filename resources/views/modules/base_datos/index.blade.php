@extends('layouts.main')
@section('titulo', $titulo)
@section('baseDatosActive', 'active')
@section('paginaTitulo', $paginaTitulo)
@section('paginaSubtitulo', $paginaSubtitulo)

@section('contenido')
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        {{-- Tarjetas de Estadísticas Rápidas --}}
        <div class="row mb-4">
            <div class="col-12 col-md-6 col-lg-3 mb-3">
                <div class="card h-100 mb-0 shadow-sm border-0">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-3 p-3 bg-soft-primary text-primary me-3">
                            <i class="ri-database-2-fill fs-3"></i>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Base de Datos</span>
                            <h5 class="fw-bold mb-0">{{ $stats['db_name'] }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3 mb-3">
                <div class="card h-100 mb-0 shadow-sm border-0">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-3 p-3 bg-soft-info text-info me-3">
                            <i class="ri-table-line fs-3"></i>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Tablas del Sistema</span>
                            <h5 class="fw-bold mb-0">{{ $stats['tables_count'] }} tablas</h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3 mb-3">
                <div class="card h-100 mb-0 shadow-sm border-0">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-3 p-3 bg-soft-success text-success me-3">
                            <i class="ri-file-list-3-line fs-3"></i>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Total de Registros</span>
                            <h5 class="fw-bold mb-0">~ {{ $stats['total_records'] }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3 mb-3">
                <div class="card h-100 mb-0 shadow-sm border-0">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-3 p-3 bg-soft-warning text-warning me-3">
                            <i class="ri-hard-drive-2-line fs-3"></i>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Tamaño Aprox.</span>
                            <h5 class="fw-bold mb-0">{{ $stats['size_mb'] }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tarjetas de Acción: Exportar e Importar --}}
        <div class="row mb-4">
            {{-- Tarjeta 1: Exportar Base de Datos --}}
            <div class="col-12 col-lg-6 mb-4 mb-lg-0">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-header d-flex align-items-center justify-content-between border-bottom pb-3">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle p-2 bg-soft-primary text-primary me-2">
                                <i class="ri-download-cloud-2-fill fs-4"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-0 fw-bold">Exportar Base de Datos</h5>
                                <small class="text-muted">Generar archivo de respaldo descargable</small>
                            </div>
                        </div>
                        <span class="badge bg-primary">Recomendado periódicamente</span>
                    </div>

                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <p class="text-secondary mb-3">
                                Esta opción crea una copia exacta de todas las tablas, relaciones y registros almacenados
                                en el sistema en un único archivo con formato <code>.sql</code>.
                            </p>

                            <div class="p-3 bg-soft-primary rounded-3 mb-4">
                                <h6 class="fw-bold text-primary mb-2">
                                    <i class="ri-information-line me-1"></i> ¿Para qué sirve este respaldo?
                                </h6>
                                <ul class="mb-0 ps-3 small text-secondary">
                                    <li>Reinstalar el sistema sin perder usuarios, expedientes ni censos.</li>
                                    <li>Mudar el sistema a una nueva computadora o servidor.</li>
                                    <li>Mantener un respaldo seguro en una unidad USB o disco externo.</li>
                                </ul>
                            </div>
                        </div>

                        <div>
                            <a href="{{ route('database-backup.export') }}" id="btn-exportar-bd"
                               class="btn btn-primary w-100 py-3 fw-bold d-flex align-items-center justify-content-center gap-2 shadow-sm">
                                <i class="ri-download-2-line fs-4"></i>
                                <span>Descargar Respaldo Completo (.sql)</span>
                            </a>
                            <div class="text-center mt-2">
                                <small class="text-muted">
                                    Último respaldo guardado: <strong>{{ $stats['last_backup'] }}</strong>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tarjeta 2: Importar Base de Datos --}}
            <div class="col-12 col-lg-6">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-header d-flex align-items-center justify-content-between border-bottom pb-3">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle p-2 bg-soft-warning text-warning me-2">
                                <i class="ri-upload-cloud-2-fill fs-4"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-0 fw-bold">Importar / Restaurar Base de Datos</h5>
                                <small class="text-muted">Restablecer datos desde un archivo .sql</small>
                            </div>
                        </div>
                        <span class="badge bg-warning text-dark">Acción Crítica</span>
                    </div>

                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <div class="alert alert-warning border-0 d-flex align-items-start mb-3" role="alert">
                                <i class="ri-alert-fill fs-4 me-2 mt-1 flex-shrink-0"></i>
                                <div class="small">
                                    <strong>¡Advertencia importante!</strong> La importación reemplazará completamente los
                                    datos actuales con la información contenida en el archivo de respaldo seleccionado.
                                </div>
                            </div>

                            <p class="text-secondary small mb-3">
                                Seleccione el archivo <code>.sql</code> de respaldo previamente generado que desea restaurar.
                            </p>

                            <form action="{{ route('database-backup.import') }}" method="POST" enctype="multipart/form-data" id="form-importar-bd">
                                @csrf
                                <div class="mb-3">
                                    <label for="backup_file" class="form-label fw-bold text-dark small">Archivo de Respaldo (.sql)</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ri-file-code-line"></i></span>
                                        <input type="file" name="backup_file" id="backup_file" class="form-control" accept=".sql" required>
                                    </div>
                                    <div class="form-text small">Solo archivos con extensión <code>.sql</code> (máx. 100 MB).</div>
                                </div>

                                <button type="button" id="btn-iniciar-importacion"
                                        class="btn btn-warning w-100 py-3 fw-bold text-dark d-flex align-items-center justify-content-center gap-2 shadow-sm">
                                    <i class="ri-history-line fs-4"></i>
                                    <span>Restaurar Base de Datos Ahora</span>
                                </button>
                            </form>
                        </div>

                        <div class="text-center mt-3">
                            <small class="text-muted">
                                <i class="ri-shield-check-line text-success me-1"></i> Integridad y claves foráneas protegidas durante la restauración
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tarjeta 3: Respaldos Almacenados en el Servidor --}}
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header d-flex align-items-center justify-content-between border-bottom">
                        <div>
                            <h5 class="card-title mb-0 fw-bold">Copias de Seguridad Guardadas en el Servidor</h5>
                            <small class="text-muted">Archivos almacenados localmente en la carpeta de respaldos del sistema</small>
                        </div>
                        <span class="badge bg-soft-primary text-primary">
                            {{ count($backups) }} {{ count($backups) === 1 ? 'archivo' : 'archivos' }}
                        </span>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="ps-4">Nombre del Archivo</th>
                                        <th scope="col">Fecha de Creación</th>
                                        <th scope="col">Tamaño</th>
                                        <th scope="col" class="text-end pe-4">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($backups as $item)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <i class="ri-file-code-fill text-primary fs-4 me-2"></i>
                                                    <div>
                                                        <strong class="text-dark d-block">{{ $item['filename'] }}</strong>
                                                        <span class="badge bg-soft-info text-info small">MySQL Dump (.sql)</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <i class="ri-time-line text-muted me-1"></i>
                                                {{ $item['date'] }}
                                            </td>
                                            <td>
                                                <span class="badge bg-soft-secondary text-dark">{{ $item['size'] }}</span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <div class="btn-group" role="group">
                                                    {{-- Botón Descargar --}}
                                                    <a href="{{ route('database-backup.download', $item['filename']) }}"
                                                       class="btn btn-sm btn-outline-primary"
                                                       title="Descargar respaldo a su computadora">
                                                        <i class="ri-download-2-line"></i> Descargar
                                                    </a>

                                                    {{-- Botón Restaurar --}}
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-warning text-dark btn-restaurar-servidor"
                                                            data-filename="{{ $item['filename'] }}"
                                                            data-url="{{ route('database-backup.restore', $item['filename']) }}"
                                                            title="Restaurar base de datos a este punto">
                                                        <i class="ri-history-line"></i> Restaurar
                                                    </button>

                                                    {{-- Botón Eliminar --}}
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-danger btn-eliminar-servidor"
                                                            data-filename="{{ $item['filename'] }}"
                                                            data-url="{{ route('database-backup.destroy', $item['filename']) }}"
                                                            title="Eliminar este archivo">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5 text-muted">
                                                <i class="ri-inbox-line fs-1 d-block mb-2 text-secondary"></i>
                                                Aún no se han generado respaldos en el servidor. Haga clic en <strong>"Descargar Respaldo Completo"</strong> para crear el primero.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Formulario oculto para acciones de Restaurar y Eliminar del servidor --}}
    <form id="form-accion-servidor" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="_method" id="form-accion-method" value="POST">
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Notificación de éxito
            @if (session('success'))
                Swal.fire({
                    title: '¡Operación Exitosa!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    confirmButtonColor: '#3a57e8',
                    confirmButtonText: 'Aceptar'
                });
            @endif

            // Notificación de error
            @if (session('error'))
                Swal.fire({
                    title: '¡Error!',
                    text: "{{ session('error') }}",
                    icon: 'error',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Aceptar'
                });
            @endif

            // Confirmación antes de subir e importar archivo
            const btnImportar = document.getElementById('btn-iniciar-importacion');
            const fileInput = document.getElementById('backup_file');
            const formImportar = document.getElementById('form-importar-bd');

            if (btnImportar) {
                btnImportar.addEventListener('click', function () {
                    if (!fileInput.files || fileInput.files.length === 0) {
                        Swal.fire({
                            title: 'Archivo requerido',
                            text: 'Por favor, seleccione un archivo de respaldo .sql para continuar.',
                            icon: 'warning',
                            confirmButtonColor: '#3a57e8',
                            confirmButtonText: 'Entendido'
                        });
                        return;
                    }

                    const fileName = fileInput.files[0].name;
                    if (!fileName.toLowerCase().endsWith('.sql')) {
                        Swal.fire({
                            title: 'Formato incorrecto',
                            text: 'El archivo debe tener extensión .sql',
                            icon: 'error',
                            confirmButtonColor: '#3a57e8',
                            confirmButtonText: 'Entendido'
                        });
                        return;
                    }

                    Swal.fire({
                        title: '¿Confirmar Restauración de Base de Datos?',
                        html: `Se restaurará la base de datos con el archivo <strong>${fileName}</strong>.<br><br><span class="text-danger">¡ATENCIÓN! Toda la información actual será reemplazada por los datos del respaldo.</span>`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e0a800',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Sí, Restaurar Base de Datos',
                        cancelButtonText: 'Cancelar',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Restaurando Base de Datos...',
                                text: 'Por favor espere unos segundos mientras se procesa la información. No recargue ni cierre el navegador.',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                showConfirmButton: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            formImportar.submit();
                        }
                    });
                });
            }

            // Confirmación para Restaurar un archivo guardado en el servidor
            const formAccion = document.getElementById('form-accion-servidor');
            const formMethod = document.getElementById('form-accion-method');

            document.querySelectorAll('.btn-restaurar-servidor').forEach(button => {
                button.addEventListener('click', function () {
                    const filename = this.getAttribute('data-filename');
                    const url = this.getAttribute('data-url');

                    Swal.fire({
                        title: '¿Restaurar este Respaldo?',
                        html: `Está a punto de restaurar la base de datos al estado del archivo: <br><strong>${filename}</strong>.<br><br><span class="text-danger">¡Los datos actuales serán reemplazados!</span>`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e0a800',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Sí, Restaurar Ahora',
                        cancelButtonText: 'Cancelar',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Restaurando Base de Datos...',
                                text: 'Por favor espere mientras se completa el proceso...',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                showConfirmButton: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            formAccion.action = url;
                            formMethod.value = 'POST';
                            formAccion.submit();
                        }
                    });
                });
            });

            // Confirmación para Eliminar un archivo guardado en el servidor
            document.querySelectorAll('.btn-eliminar-servidor').forEach(button => {
                button.addEventListener('click', function () {
                    const filename = this.getAttribute('data-filename');
                    const url = this.getAttribute('data-url');

                    Swal.fire({
                        title: '¿Eliminar Copia de Seguridad?',
                        text: `¿Está seguro de que desea eliminar el archivo "${filename}"? Esta acción no se puede deshacer.`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Sí, Eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            formAccion.action = url;
                            formMethod.value = 'DELETE';
                            formAccion.submit();
                        }
                    });
                });
            });
        });
    </script>
@endpush
