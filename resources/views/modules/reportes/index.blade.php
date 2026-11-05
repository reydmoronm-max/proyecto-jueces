@extends('layouts.main')
@section('titulo', $titulo)
@section('paginaTitulo', $paginaTitulo)
@section('paginaSubtitulo', $paginaSubtitulo)
@section('reportesActive', 'active')

@push('styles')
<style>
    /* Suprimir la pantalla general de cargando en reportes */
    #page-loader, .page-loader {
        display: none !important;
        opacity: 0 !important;
        visibility: hidden !important;
        pointer-events: none !important;
    }
</style>
@endpush

@section('contenido')
    <div class="container-fluid content-inner mt-n5 py-0">
        <!-- Panel de Filtro Multicriterio Dinámico -->
        <div class="card shadow-sm mb-4 border-0" style="border-radius: 14px;">
            <div class="card-body p-4">
                <form id="form-filtro-reportes" action="{{ route('reportes.index') }}" method="GET" class="row g-3 align-items-end">
                    <input type="hidden" name="tab_activa" id="input_tab_activa" value="{{ $tabActiva ?? 'graficos' }}">
                    <input type="hidden" name="listado_activo" id="input_listado_activo" value="{{ $listadoActivo ?? 'electoral' }}">

                    <!-- 1. Comunidad -->
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <label for="consejo_comunal_id" class="form-label small text-muted fw-bold mb-1">
                            <i class="ri-community-line text-primary me-1"></i> Comunidad / Consejo
                        </label>
                        <select name="consejo_comunal_id" id="consejo_comunal_id" class="form-select bg-white select-filtro-dinamico">
                            <option value="">Censo Completo (Todas)</option>
                            @foreach ($consejosComunales as $cc)
                                <option value="{{ $cc->id }}" {{ $consejoComunalId == $cc->id ? 'selected' : '' }}>
                                    {{ $cc->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 2. Género -->
                    <div class="col-xl-2 col-lg-2 col-md-3 col-sm-6">
                        <label for="genero" class="form-label small text-muted fw-bold mb-1">
                            <i class="ri-user-shared-line text-primary me-1"></i> Género
                        </label>
                        <select name="genero" id="genero" class="form-select bg-white select-filtro-dinamico">
                            <option value="">Todos los géneros</option>
                            <option value="Masculino" {{ ($genero ?? '') === 'Masculino' ? 'selected' : '' }}>Masculino</option>
                            <option value="Femenino" {{ ($genero ?? '') === 'Femenino' ? 'selected' : '' }}>Femenino</option>
                        </select>
                    </div>

                    <!-- 3. Grupo Etario -->
                    <div class="col-xl-2 col-lg-2 col-md-3 col-sm-6">
                        <label for="grupo_etario" class="form-label small text-muted fw-bold mb-1">
                            <i class="ri-calendar-event-line text-primary me-1"></i> Grupo Etario
                        </label>
                        <select name="grupo_etario" id="grupo_etario" class="form-select bg-white select-filtro-dinamico">
                            <option value="">Todas las edades</option>
                            <option value="menores" {{ ($grupoEtario ?? '') === 'menores' ? 'selected' : '' }}>Menores (0 - 17)</option>
                            <option value="adultos" {{ ($grupoEtario ?? '') === 'adultos' ? 'selected' : '' }}>Adultos (18 - 59)</option>
                            <option value="abuelos" {{ ($grupoEtario ?? '') === 'abuelos' ? 'selected' : '' }}>Mayores (60+)</option>
                        </select>
                    </div>

                    <!-- 4. Patología Específica -->
                    <div class="col-xl-2 col-lg-4 col-md-6">
                        <label for="enfermedad" class="form-label small text-muted fw-bold mb-1">
                            <i class="ri-heart-pulse-line text-danger me-1"></i> Patología / Condición
                        </label>
                        <select name="enfermedad" id="enfermedad" class="form-select bg-white select-filtro-dinamico">
                            <option value="">Cualquier condición</option>
                            @foreach ($catalogoEnfermedades as $enfCat)
                                @if (!in_array(strtolower(trim($enfCat->nombre)), ['ninguna', 'no aplica', 'sano', 'ninguno']))
                                    <option value="{{ $enfCat->nombre }}" {{ ($enfermedadFiltro ?? '') === $enfCat->nombre ? 'selected' : '' }}>
                                        {{ $enfCat->nombre }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <!-- 5. Profesión u Oficio -->
                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <label for="profesion" class="form-label small text-muted fw-bold mb-1">
                            <i class="ri-briefcase-line text-warning me-1"></i> Profesión u Oficio
                        </label>
                        <select name="profesion" id="profesion" class="form-select bg-white select-filtro-dinamico">
                            <option value="">Cualquier profesión</option>
                            @foreach ($catalogoProfesiones as $profCat)
                                @if (!in_array(strtolower(trim($profCat->nombre)), ['ninguna', 'no aplica', 'ninguno']))
                                    <option value="{{ $profCat->nombre }}" {{ ($profesionFiltro ?? '') === $profCat->nombre ? 'selected' : '' }}>
                                        {{ $profCat->nombre }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <!-- Indicador Dinámico y Acciones -->
                    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2 pt-2 border-top">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span id="indicador-dinamico-badge" class="badge bg-soft-primary text-primary px-3 py-2 rounded-pill align-items-center gap-1" style="display: none;">
                                <span class="spinner-border spinner-border-sm me-1 text-primary" role="status"></span>
                                <span>Actualizando datos...</span>
                            </span>
                            <button type="button" id="btn-limpiar-filtros-reportes" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
                                <i class="ri-refresh-line"></i> Limpiar Filtros
                            </button>
                        </div>

                        <!-- Dropdown de Exportaciones Especializadas en PDF -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-danger dropdown-toggle d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-file-pdf fs-5"></i> Exportar Reporte en PDF
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius: 10px;">
                                <li>
                                    <a id="btn-pdf-general" class="dropdown-item py-2 d-flex align-items-center gap-2"
                                       href="{{ route('reportes.pdf', array_merge(request()->query(), ['tipo_reporte' => 'general'])) }}"
                                       target="_blank" rel="noopener">
                                        <i class="ri-pie-chart-2-fill text-primary"></i>
                                        <div>
                                            <strong>Reporte General Demográfico</strong>
                                            <div class="small text-muted">Estadísticas consolidadas e histogramas</div>
                                        </div>
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <a id="btn-pdf-salud" class="dropdown-item py-2 d-flex align-items-center gap-2"
                                       href="{{ route('reportes.pdf', array_merge(request()->query(), ['tipo_reporte' => 'salud'])) }}"
                                       target="_blank" rel="noopener">
                                        <i class="ri-heart-pulse-fill text-danger"></i>
                                        <div>
                                            <strong>Reporte Especializado de Salud</strong>
                                            <div class="small text-muted">Patologías, adultos mayores y atención médica</div>
                                        </div>
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <a id="btn-pdf-socioproductivo" class="dropdown-item py-2 d-flex align-items-center gap-2"
                                       href="{{ route('reportes.pdf', array_merge(request()->query(), ['tipo_reporte' => 'socioproductivo'])) }}"
                                       target="_blank" rel="noopener">
                                        <i class="ri-briefcase-4-fill text-warning"></i>
                                        <div>
                                            <strong>Reporte Socio-Productivo y Educativo</strong>
                                            <div class="small text-muted">Oficios, nivel académico, empleo y vivienda</div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- CONTENEDOR DINÁMICO QUE SE ACTUALIZA SIN PANTALLA DE CARGANDO -->
        <div id="contenedor-dinamico-reportes">
            <!-- Barra de Contexto y Pestañas Principales de Navegación -->
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 14px;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="badge bg-soft-primary text-primary px-3 py-2 rounded-pill fs-6 fw-bold">
                                <i class="ri-building-line me-1"></i>
                                {{ $comunidadSeleccionada ? $comunidadSeleccionada->nombre : 'Todas las Comunidades (Censo General)' }}
                            </span>
                            <span class="text-muted small fw-bold">
                                | {{ $totalCiudadanos }} ciudadanos analizados
                            </span>
                            @if ($enfermedadFiltro)
                                <span class="badge bg-soft-danger text-danger">Filtro Salud: {{ $enfermedadFiltro }}</span>
                            @endif
                            @if ($profesionFiltro)
                                <span class="badge bg-soft-warning text-dark">Filtro Oficio: {{ $profesionFiltro }}</span>
                            @endif
                        </div>

                        <!-- Tabs de Navegación -->
                        <ul class="nav nav-pills" id="reportesTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ ($tabActiva ?? 'graficos') === 'graficos' ? 'active' : '' }}"
                                        id="tab-graficos-btn" data-bs-toggle="pill" data-bs-target="#tab-graficos" type="button" role="tab">
                                    <i class="ri-bar-chart-grouped-line me-1"></i> Estadísticas y Gráficos
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ ($tabActiva ?? '') === 'listados' ? 'active' : '' }}"
                                        id="tab-listados-btn" data-bs-toggle="pill" data-bs-target="#tab-listados" type="button" role="tab">
                                    <i class="ri-file-list-3-line me-1"></i> Listados Focalizados de Acción
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ ($tabActiva ?? '') === 'comparador' ? 'active' : '' }}"
                                        id="tab-comparador-btn" data-bs-toggle="pill" data-bs-target="#tab-comparador" type="button" role="tab">
                                    <i class="ri-scales-3-line me-1"></i> Comparador de Comunidades
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="tab-content" id="reportesTabsContent">
                <!-- ============================================================= -->
                <!-- PESTAÑA 1: ESTADÍSTICAS Y GRÁFICOS DEMOGRÁFICOS -->
                <!-- ============================================================= -->
                <div class="tab-pane fade {{ ($tabActiva ?? 'graficos') === 'graficos' ? 'show active' : '' }}" id="tab-graficos" role="tabpanel">
                    <!-- Tarjetas de Métricas Rápidas (KPIs) -->
                    <div class="row g-3 mb-4">
                        <!-- 1. Total Ciudadanos -->
                        <div class="col-xl col-md-4 col-sm-6">
                            <div class="card shadow-sm h-100 border-0" style="border-radius: 12px; border-left: 5px solid #3a57e8 !important;">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-muted small text-uppercase fw-bold">Población Total</span>
                                        <div class="bg-soft-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                            <i class="ri-group-line text-primary"></i>
                                        </div>
                                    </div>
                                    <h3 class="fw-bold mb-0 text-primary">{{ $totalCiudadanos }}</h3>
                                    <span class="text-muted small">Ciudadanos censados</span>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Con Profesión / Oficio -->
                        <div class="col-xl col-md-4 col-sm-6">
                            <div class="card shadow-sm h-100 border-0" style="border-radius: 12px; border-left: 5px solid #08b1ba !important;">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-muted small text-uppercase fw-bold">Con Profesión</span>
                                        <div class="bg-soft-info rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                            <i class="ri-briefcase-line text-info"></i>
                                        </div>
                                    </div>
                                    <h3 class="fw-bold mb-0 text-info">{{ $totalConProfesion }}</h3>
                                    <span class="text-muted small">
                                        ({{ $totalCiudadanos > 0 ? round(($totalConProfesion / $totalCiudadanos) * 100, 1) : 0 }}% con oficio reportado)
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Estudiantes -->
                        <div class="col-xl col-md-4 col-sm-6">
                            <div class="card shadow-sm h-100 border-0" style="border-radius: 12px; border-left: 5px solid #1aa053 !important;">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-muted small text-uppercase fw-bold">Estudiantes</span>
                                        <div class="bg-soft-success rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                            <i class="ri-book-read-line text-success"></i>
                                        </div>
                                    </div>
                                    <h3 class="fw-bold mb-0 text-success">{{ $estudianCount }}</h3>
                                    <span class="text-muted small">
                                        ({{ $totalCiudadanos > 0 ? round(($estudianCount / $totalCiudadanos) * 100, 1) : 0 }}% en formación)
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- 4. Con Enfermedades -->
                        <div class="col-xl col-md-6 col-sm-6">
                            <div class="card shadow-sm h-100 border-0" style="border-radius: 12px; border-left: 5px solid #c03221 !important;">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-muted small text-uppercase fw-bold">Con Patología</span>
                                        <div class="bg-soft-danger rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                            <i class="ri-heart-pulse-line text-danger"></i>
                                        </div>
                                    </div>
                                    <h3 class="fw-bold mb-0 text-danger">{{ $conEnfermedadCount }}</h3>
                                    <span class="text-muted small">
                                        ({{ $totalCiudadanos > 0 ? round(($conEnfermedadCount / $totalCiudadanos) * 100, 1) : 0 }}% con condición médica)
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- 5. Pensionados / Jubilados -->
                        <div class="col-xl col-md-6 col-sm-6">
                            <div class="card shadow-sm h-100 border-0" style="border-radius: 12px; border-left: 5px solid #f16a1b !important;">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-muted small text-uppercase fw-bold">Pensionados</span>
                                        <div class="bg-soft-warning rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                            <i class="ri-empathize-line text-warning"></i>
                                        </div>
                                    </div>
                                    <h3 class="fw-bold mb-0 text-warning">{{ $pensionadosCount }}</h3>
                                    <span class="text-muted small">
                                        ({{ $totalCiudadanos > 0 ? round(($pensionadosCount / $totalCiudadanos) * 100, 1) : 0 }}% del total)
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fila 1 de Histogramas: Edades y Profesiones -->
                    <div class="row g-4 mb-4">
                        <div class="col-lg-6">
                            <div class="card shadow-sm h-100 border-0" style="border-radius: 14px;">
                                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div>
                                    <h5 class="card-title fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                                        <i class="ri-bar-chart-fill text-primary"></i> Histograma de Distribución de Edades
                                    </h5>
                                    <p class="text-muted small mb-0">Frecuencia por intervalos etarios (décadas de edad)</p>
                                </div>
                                <div class="d-flex gap-1 flex-wrap">
                                    <span class="badge bg-soft-primary text-primary small" title="0 a 17 años">
                                        Menores: <strong>{{ $menoresCount }}</strong>
                                    </span>
                                    <span class="badge bg-soft-primary text-primary small" title="18 a 59 años">
                                        Adultos: <strong>{{ $adultosCount }}</strong>
                                    </span>
                                    <span class="badge bg-soft-primary text-primary small" title="60+ años">
                                        Mayores: <strong>{{ $abuelosCount }}</strong>
                                    </span>
                                </div>
                            </div>
                            <div class="card-body px-3 pb-3">
                                <div id="chart-histograma-edades" style="height: 270px; width: 100%; min-height: 270px;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card shadow-sm h-100 border-0" style="border-radius: 14px;">
                            <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div>
                                    <h5 class="card-title fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                                        <i class="ri-briefcase-4-fill" style="color: #ffbe0c;"></i> Estadísticas de Profesiones y Oficios
                                    </h5>
                                    <p class="text-muted small mb-0">Distribución de las ocupaciones más frecuentes registradas</p>
                                </div>
                                <span class="badge rounded-pill px-3 py-1" style="background-color: #fff9e6; color: #b8860b; border: 1px solid #ffeeba;">
                                    {{ $profesiones->count() }} actividades registradas
                                </span>
                            </div>
                            <div class="card-body px-3 pb-3">
                                <div id="chart-histograma-profesiones" style="height: 270px; width: 100%; min-height: 270px; {{ $profesiones->count() == 0 ? 'display:none;' : '' }}"></div>
                                <div id="vacio-profesiones" class="text-center py-5" style="{{ $profesiones->count() > 0 ? 'display:none;' : '' }}">
                                    <div class="bg-soft-warning rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background-color: #fff9e6;">
                                        <i class="ri-user-unfollow-line text-warning fs-3"></i>
                                    </div>
                                    <h6 class="text-muted fw-bold">Sin profesiones registradas</h6>
                                    <p class="text-muted small mb-0">No se encontraron ciudadanos con profesión u oficio registrado en este filtro.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fila 2 de Histogramas: Nivel Académico y Género por Ciclo de Vida -->
                <div class="row g-4 mb-4">
                    <div class="col-lg-6">
                        <div class="card shadow-sm h-100 border-0" style="border-radius: 14px;">
                            <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div>
                                    <h5 class="card-title fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                                        <i class="ri-graduation-cap-fill" style="color: #4f46e5;"></i> Histograma de Nivel Académico
                                    </h5>
                                    <p class="text-muted small mb-0">Nivel de instrucción escolar y universitaria alcanzado</p>
                                </div>
                                <div class="d-flex align-items-center gap-1 flex-wrap">
                                    <span class="badge small" style="background-color: #eef2ff; color: #4f46e5; border: 1px solid #e0e7ff;">TSU: Téc. Superior</span>
                                    <span class="badge small" style="background-color: #f5f3ff; color: #6366f1; border: 1px solid #ede9fe;">NS/NR: Sin respuesta</span>
                                </div>
                            </div>
                            <div class="card-body px-3 pb-3">
                                <div id="chart-histograma-academico" style="height: 270px; width: 100%; min-height: 270px;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card shadow-sm h-100 border-0" style="border-radius: 14px;">
                            <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div>
                                    <h5 class="card-title fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                                        <i class="ri-women-line text-danger"></i><i class="ri-men-line text-primary"></i> Género por Ciclo de Vida
                                    </h5>
                                    <p class="text-muted small mb-0">Distribución comparativa de hombres y mujeres por grupo etario</p>
                                </div>
                                <div class="d-flex gap-2">
                                    <span class="badge bg-soft-primary text-primary small">
                                        <i class="ri-men-line"></i> Masc: {{ $generoMasculino }}
                                    </span>
                                    <span class="badge bg-soft-danger text-danger small">
                                        <i class="ri-women-line"></i> Fem: {{ $generoFemenino }}
                                    </span>
                                </div>
                            </div>
                            <div class="card-body px-3 pb-3">
                                <div id="chart-histograma-genero-ciclo" style="height: 270px; width: 100%; min-height: 270px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fila 3 de Histogramas: Beneficios/Vivienda y Enfermedades Frecuentes -->
                <div class="row g-4 mb-4">
                    <div class="col-lg-6">
                        <div class="card shadow-sm h-100 border-0" style="border-radius: 14px;">
                            <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div>
                                    <h5 class="card-title fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                                        <i class="ri-home-heart-fill text-success"></i> Beneficios y Tenencia de Vivienda
                                    </h5>
                                    <p class="text-muted small mb-0">Cobertura de programas sociales e indicadores habitacionales</p>
                                </div>
                            </div>
                            <div class="card-body px-3 pb-3">
                                <div id="chart-histograma-socioeconomico" style="height: 270px; width: 100%; min-height: 270px;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card shadow-sm h-100 border-0" style="border-radius: 14px;">
                            <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div>
                                    <h5 class="card-title fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                                        <i class="ri-heart-pulse-fill text-danger"></i> Patologías y Enfermedades Reportadas
                                    </h5>
                                    <p class="text-muted small mb-0">Frecuencia de las principales afecciones de salud en la comunidad</p>
                                </div>
                                <span class="badge bg-soft-danger text-danger rounded-pill px-3 py-1">
                                    {{ $conEnfermedadCount }} casos totales
                                </span>
                            </div>
                            <div class="card-body px-3 pb-3">
                                <div id="chart-histograma-enfermedades" style="height: 270px; width: 100%; min-height: 270px; {{ $enfermedades->count() == 0 ? 'display:none;' : '' }}"></div>
                                <div id="vacio-enfermedades" class="text-center py-5" style="{{ $enfermedades->count() > 0 ? 'display:none;' : '' }}">
                                    <div class="bg-soft-success rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                        <i class="ri-shield-check-line text-success fs-3"></i>
                                    </div>
                                    <h6 class="text-success fw-bold">Sin patologías registradas</h6>
                                    <p class="text-muted small mb-0">No se registran ciudadanos con afecciones médicas en el grupo consultado.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================================= -->
            <!-- PESTAÑA 2: LISTADOS FOCALIZADOS DE ACCIÓN (REGISTRO NOMINAL) -->
            <!-- ============================================================= -->
            <div class="tab-pane fade {{ ($tabActiva ?? '') === 'listados' ? 'show active' : '' }}" id="tab-listados" role="tabpanel">
                <div class="card shadow-sm border-0 mb-4" style="border-radius: 14px;">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-2 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h5 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                                <i class="ri-file-user-fill text-primary"></i> Registro Nominal y Listados Focalizados
                            </h5>
                            <p class="text-muted small mb-0">Listas operativas de ciudadanos para convocatorias, despliegues de salud, jornadas y proyectos.</p>
                        </div>
                    </div>

                    <div class="card-body px-4 pt-2 pb-4">
                        <!-- Sub-Pills de Listados -->
                        <ul class="nav nav-tabs nav-fill mb-4" id="listadosSubTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold py-3 {{ ($listadoActivo ?? 'electoral') === 'electoral' ? 'active' : '' }}"
                                        id="subtab-electoral-btn" data-bs-toggle="tab" data-bs-target="#subtab-electoral" type="button" role="tab">
                                    <i class="ri-user-follow-line text-primary me-1"></i> Registro Electoral (18+)
                                    <span class="badge bg-primary ms-1 rounded-pill">{{ $totalElectoral }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold py-3 {{ ($listadoActivo ?? '') === 'salud' ? 'active' : '' }}"
                                        id="subtab-salud-btn" data-bs-toggle="tab" data-bs-target="#subtab-salud" type="button" role="tab">
                                    <i class="ri-heart-pulse-line text-danger me-1"></i> Censo de Salud
                                    <span class="badge bg-danger ms-1 rounded-pill">{{ $totalSalud }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold py-3 {{ ($listadoActivo ?? '') === 'abuelos' ? 'active' : '' }}"
                                        id="subtab-abuelos-btn" data-bs-toggle="tab" data-bs-target="#subtab-abuelos" type="button" role="tab">
                                    <i class="ri-empathize-line text-warning me-1"></i> Adultos Mayores (60+)
                                    <span class="badge bg-warning text-dark ms-1 rounded-pill">{{ $totalAbuelosLista }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold py-3 {{ ($listadoActivo ?? '') === 'estudiantes' ? 'active' : '' }}"
                                        id="subtab-estudiantes-btn" data-bs-toggle="tab" data-bs-target="#subtab-estudiantes" type="button" role="tab">
                                    <i class="ri-book-read-line text-success me-1"></i> Niños y Estudiantes
                                    <span class="badge bg-success ms-1 rounded-pill">{{ $totalEstudiantes }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold py-3 {{ ($listadoActivo ?? '') === 'talentos' ? 'active' : '' }}"
                                        id="subtab-talentos-btn" data-bs-toggle="tab" data-bs-target="#subtab-talentos" type="button" role="tab">
                                    <i class="ri-briefcase-line text-info me-1"></i> Banco de Talentos
                                    <span class="badge bg-info ms-1 rounded-pill">{{ $totalTalentos }}</span>
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="listadosSubTabsContent">
                            <!-- 1. Registro Electoral -->
                            <div class="tab-pane fade {{ ($listadoActivo ?? 'electoral') === 'electoral' ? 'show active' : '' }}" id="subtab-electoral" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                                    <span class="text-muted small">Mostrando hasta los primeros 100 ciudadanos mayores de 18 años con centro de votación.</span>
                                    <a href="{{ route('reportes.listado.pdf', array_merge(request()->query(), ['listado_tipo' => 'electoral'])) }}"
                                       class="btn btn-sm btn-danger d-flex align-items-center gap-2 btn-pdf-listado" target="_blank" rel="noopener">
                                        <i class="fa-solid fa-file-pdf"></i> Imprimir Planilla de Campo PDF
                                    </a>
                                </div>
                                <div class="table-responsive border rounded-3">
                                    <table class="table table-striped table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Cédula</th>
                                                <th>Nombres y Apellidos</th>
                                                <th>Edad / Género</th>
                                                <th>Teléfono</th>
                                                <th>Comunidad / Dirección</th>
                                                <th>Centro de Votación</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($listadoElectoral as $idx => $p)
                                                <tr>
                                                    <td>{{ $idx + 1 }}</td>
                                                    <td><strong class="text-dark">{{ $p->cedula_tipo ?? 'V' }}-{{ $p->cedula ?? 'S/C' }}</strong></td>
                                                    <td>{{ $p->nombres }} {{ $p->apellidos }}</td>
                                                    <td>{{ $p->edad ?? 'S/F' }} años ({{ $p->genero }})</td>
                                                    <td>{{ $p->telefono ?? 'Sin teléfono' }}</td>
                                                    <td>
                                                        <span class="badge bg-soft-primary text-primary">{{ $p->familia?->consejoComunal?->nombre ?? 'N/A' }}</span>
                                                    </td>
                                                    <td>
                                                        <i class="ri-map-pin-line text-muted me-1"></i>
                                                        <span class="small">{{ $p->centro_votacion ?? 'No registrado' }}</span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-4 text-muted">No se encontraron electores en este filtro.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- 2. Censo de Salud -->
                            <div class="tab-pane fade {{ ($listadoActivo ?? '') === 'salud' ? 'show active' : '' }}" id="subtab-salud" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                                    <span class="text-muted small">Ciudadanos que presentan afecciones de salud crónicas para jornadas médicas y fármacos.</span>
                                    <a href="{{ route('reportes.listado.pdf', array_merge(request()->query(), ['listado_tipo' => 'salud'])) }}"
                                       class="btn btn-sm btn-danger d-flex align-items-center gap-2 btn-pdf-listado" target="_blank" rel="noopener">
                                        <i class="fa-solid fa-file-pdf"></i> Imprimir Planilla de Campo PDF
                                    </a>
                                </div>
                                <div class="table-responsive border rounded-3">
                                    <table class="table table-striped table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Cédula</th>
                                                <th>Nombres y Apellidos</th>
                                                <th>Edad</th>
                                                <th>Patología(s) Diagnosticada(s)</th>
                                                <th>Teléfono</th>
                                                <th>Comunidad</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($listadoSalud as $idx => $p)
                                                <tr>
                                                    <td>{{ $idx + 1 }}</td>
                                                    <td><strong class="text-dark">{{ $p->cedula_tipo ?? 'V' }}-{{ $p->cedula ?? 'S/C' }}</strong></td>
                                                    <td>{{ $p->nombres }} {{ $p->apellidos }}</td>
                                                    <td>{{ $p->edad ?? 'S/F' }} años</td>
                                                    <td>
                                                        <span class="badge bg-soft-danger text-danger fw-bold fs-7">
                                                            <i class="ri-heart-pulse-fill me-1"></i>{{ $p->tipo_enfermedad }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $p->telefono ?? 'Sin teléfono' }}</td>
                                                    <td>
                                                        <span class="badge bg-soft-primary text-primary">{{ $p->familia?->consejoComunal?->nombre ?? 'N/A' }}</span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-4 text-muted">No se registran personas con patologías en este filtro.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- 3. Adultos Mayores -->
                            <div class="tab-pane fade {{ ($listadoActivo ?? '') === 'abuelos' ? 'show active' : '' }}" id="subtab-abuelos" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                                    <span class="text-muted small">Ciudadanos con 60 años o más para el Círculo de Abuelos y programas de protección social.</span>
                                    <a href="{{ route('reportes.listado.pdf', array_merge(request()->query(), ['listado_tipo' => 'abuelos'])) }}"
                                       class="btn btn-sm btn-danger d-flex align-items-center gap-2 btn-pdf-listado" target="_blank" rel="noopener">
                                        <i class="fa-solid fa-file-pdf"></i> Imprimir Planilla de Campo PDF
                                    </a>
                                </div>
                                <div class="table-responsive border rounded-3">
                                    <table class="table table-striped table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Cédula</th>
                                                <th>Nombres y Apellidos</th>
                                                <th>Edad</th>
                                                <th>Pensión IVSS</th>
                                                <th>Salud / Patología</th>
                                                <th>Teléfono y Comunidad</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($listadoAbuelos as $idx => $p)
                                                <tr>
                                                    <td>{{ $idx + 1 }}</td>
                                                    <td><strong class="text-dark">{{ $p->cedula_tipo ?? 'V' }}-{{ $p->cedula ?? 'S/C' }}</strong></td>
                                                    <td>{{ $p->nombres }} {{ $p->apellidos }}</td>
                                                    <td><strong class="text-primary">{{ $p->edad ?? 'S/F' }}</strong> años</td>
                                                    <td>
                                                        @if ($p->pensionado_jubilado === 'Sí')
                                                            <span class="badge bg-soft-success text-success">Pensionado</span>
                                                        @else
                                                            <span class="badge bg-soft-secondary text-muted">Sin Pensión</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($p->tipo_enfermedad && !in_array(strtolower($p->tipo_enfermedad), ['ninguna', 'no aplica', 'sano']))
                                                            <span class="text-danger small fw-bold">{{ $p->tipo_enfermedad }}</span>
                                                        @else
                                                            <span class="text-success small">Sin patologías</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div>{{ $p->telefono ?? 'Sin teléfono' }}</div>
                                                        <small class="text-muted">{{ $p->familia?->consejoComunal?->nombre ?? 'N/A' }}</small>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-4 text-muted">No se registran adultos mayores en este filtro.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- 4. Niños y Estudiantes -->
                            <div class="tab-pane fade {{ ($listadoActivo ?? '') === 'estudiantes' ? 'show active' : '' }}" id="subtab-estudiantes" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                                    <span class="text-muted small">Población infanto-juvenil y ciudadanos con formación académica activa.</span>
                                    <a href="{{ route('reportes.listado.pdf', array_merge(request()->query(), ['listado_tipo' => 'estudiantes'])) }}"
                                       class="btn btn-sm btn-danger d-flex align-items-center gap-2 btn-pdf-listado" target="_blank" rel="noopener">
                                        <i class="fa-solid fa-file-pdf"></i> Imprimir Planilla de Campo PDF
                                    </a>
                                </div>
                                <div class="table-responsive border rounded-3">
                                    <table class="table table-striped table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Cédula / Identificación</th>
                                                <th>Nombres y Apellidos</th>
                                                <th>Edad</th>
                                                <th>Condición de Estudio</th>
                                                <th>Nivel Académico</th>
                                                <th>Comunidad</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($listadoEstudiantes as $idx => $p)
                                                <tr>
                                                    <td>{{ $idx + 1 }}</td>
                                                    <td><strong class="text-dark">{{ $p->cedula_tipo ?? 'V' }}-{{ $p->cedula ?? 'S/C' }}</strong></td>
                                                    <td>{{ $p->nombres }} {{ $p->apellidos }}</td>
                                                    <td>{{ $p->edad ?? 'S/F' }} años</td>
                                                    <td>
                                                        @if ($p->estudia === 'Sí')
                                                            <span class="badge bg-soft-success text-success"><i class="ri-check-line me-1"></i>Estudia Actualmente</span>
                                                        @else
                                                            <span class="badge bg-soft-warning text-dark">No Escolarizado</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $p->nivel_academico ?? 'No indica' }}</td>
                                                    <td>
                                                        <span class="badge bg-soft-primary text-primary">{{ $p->familia?->consejoComunal?->nombre ?? 'N/A' }}</span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-4 text-muted">No se registran estudiantes o menores en este filtro.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- 5. Banco de Talentos -->
                            <div class="tab-pane fade {{ ($listadoActivo ?? '') === 'talentos' ? 'show active' : '' }}" id="subtab-talentos" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                                    <span class="text-muted small">Registro de ciudadanos con oficios, ocupaciones y profesiones para proyectos y brigadas.</span>
                                    <a href="{{ route('reportes.listado.pdf', array_merge(request()->query(), ['listado_tipo' => 'talentos'])) }}"
                                       class="btn btn-sm btn-danger d-flex align-items-center gap-2 btn-pdf-listado" target="_blank" rel="noopener">
                                        <i class="fa-solid fa-file-pdf"></i> Imprimir Planilla de Campo PDF
                                    </a>
                                </div>
                                <div class="table-responsive border rounded-3">
                                    <table class="table table-striped table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Profesión u Oficio</th>
                                                <th>Cédula</th>
                                                <th>Nombres y Apellidos</th>
                                                <th>Situación Laboral</th>
                                                <th>Teléfono</th>
                                                <th>Comunidad</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($listadoTalentos as $idx => $p)
                                                <tr>
                                                    <td>{{ $idx + 1 }}</td>
                                                    <td>
                                                        <span class="badge bg-soft-info text-info fw-bold fs-7">
                                                            <i class="ri-briefcase-line me-1"></i>{{ $p->profesion }}
                                                        </span>
                                                    </td>
                                                    <td><strong class="text-dark">{{ $p->cedula_tipo ?? 'V' }}-{{ $p->cedula ?? 'S/C' }}</strong></td>
                                                    <td>{{ $p->nombres }} {{ $p->apellidos }}</td>
                                                    <td>{{ $p->situacion_laboral ?? 'No especificada' }}</td>
                                                    <td>{{ $p->telefono ?? 'Sin teléfono' }}</td>
                                                    <td>
                                                        <span class="badge bg-soft-primary text-primary">{{ $p->familia?->consejoComunal?->nombre ?? 'N/A' }}</span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-4 text-muted">No se registran talentos u oficios en este filtro.</td>
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

            <!-- ============================================================= -->
            <!-- PESTAÑA 3: COMPARADOR DE COMUNIDADES -->
            <!-- ============================================================= -->
            <div class="tab-pane fade {{ ($tabActiva ?? '') === 'comparador' ? 'show active' : '' }}" id="tab-comparador" role="tabpanel">
                <div class="card shadow-sm border-0 mb-4" style="border-radius: 14px;">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                        <h5 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                            <i class="ri-scales-3-line text-primary"></i> Comparativa entre Consejos Comunales
                        </h5>
                        <p class="text-muted small mb-0">Selecciona dos comunidades para contrastar lado a lado sus métricas demográficas, de salud y sociales.</p>
                    </div>

                    <div class="card-body p-4">
                        <form id="form-comparador-comunidades" action="{{ route('reportes.index') }}" method="GET" class="row g-3 align-items-end mb-4 p-3 bg-light rounded-3 border">
                            <input type="hidden" name="tab_activa" value="comparador">
                            <div class="col-md-5">
                                <label for="cc_comparar_1" class="form-label small fw-bold text-muted">Primera Comunidad (A):</label>
                                <select name="cc_comparar_1" id="cc_comparar_1" class="form-select bg-white select-comparador" required>
                                    <option value="">Seleccione Consejo Comunal A</option>
                                    @foreach ($consejosComunales as $cc)
                                        <option value="{{ $cc->id }}" {{ ($ccComparar1 ?? '') == $cc->id ? 'selected' : '' }}>
                                            {{ $cc->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-5">
                                <label for="cc_comparar_2" class="form-label small fw-bold text-muted">Segunda Comunidad (B):</label>
                                <select name="cc_comparar_2" id="cc_comparar_2" class="form-select bg-white select-comparador" required>
                                    <option value="">Seleccione Consejo Comunal B</option>
                                    @foreach ($consejosComunales as $cc)
                                        <option value="{{ $cc->id }}" {{ ($ccComparar2 ?? '') == $cc->id ? 'selected' : '' }}>
                                            {{ $cc->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-1">
                                    <i class="ri-swap-line"></i> Comparar
                                </button>
                            </div>
                        </form>

                        @if ($comparativa && $comparativa['cc1'] && $comparativa['cc2'])
                            @php
                                $c1 = $comparativa['cc1'];
                                $c2 = $comparativa['cc2'];
                            @endphp
                            <div class="table-responsive border rounded-3">
                                <table class="table table-bordered table-striped mb-0 text-center align-middle">
                                    <thead class="table-dark">
                                        <tr>
                                            <th style="width: 35%; text-align: left;" class="ps-4">Indicador Demográfico y Social</th>
                                            <th style="width: 32.5%;" class="bg-primary">{{ $c1['nombre'] }}</th>
                                            <th style="width: 32.5%;" class="bg-info">{{ $c2['nombre'] }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-start ps-4 fw-bold">Población Total</td>
                                            <td class="fs-5 fw-bold text-primary">{{ $c1['poblacion'] }} ciudadanos</td>
                                            <td class="fs-5 fw-bold text-info">{{ $c2['poblacion'] }} ciudadanos</td>
                                        </tr>
                                        <tr>
                                            <td class="text-start ps-4 fw-bold">Núcleos Familiares</td>
                                            <td><strong>{{ $c1['familias'] }}</strong> familias</td>
                                            <td><strong>{{ $c2['familias'] }}</strong> familias</td>
                                        </tr>
                                        <tr>
                                            <td class="text-start ps-4">Hombres vs Mujeres</td>
                                            <td>
                                                <span class="badge bg-soft-primary text-primary">{{ $c1['hombres'] }} Masc</span>
                                                <span class="badge bg-soft-danger text-danger">{{ $c1['mujeres'] }} Fem</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-soft-primary text-primary">{{ $c2['hombres'] }} Masc</span>
                                                <span class="badge bg-soft-danger text-danger">{{ $c2['mujeres'] }} Fem</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-start ps-4">Adultos Mayores (60+ años)</td>
                                            <td>
                                                <strong>{{ $c1['abuelos'] }}</strong>
                                                <small class="text-muted">({{ $c1['poblacion'] > 0 ? round(($c1['abuelos'] / $c1['poblacion']) * 100, 1) : 0 }}%)</small>
                                            </td>
                                            <td>
                                                <strong>{{ $c2['abuelos'] }}</strong>
                                                <small class="text-muted">({{ $c2['poblacion'] > 0 ? round(($c2['abuelos'] / $c2['poblacion']) * 100, 1) : 0 }}%)</small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-start ps-4">Menores de Edad (0 a 17 años)</td>
                                            <td>
                                                <strong>{{ $c1['menores'] }}</strong>
                                                <small class="text-muted">({{ $c1['poblacion'] > 0 ? round(($c1['menores'] / $c1['poblacion']) * 100, 1) : 0 }}%)</small>
                                            </td>
                                            <td>
                                                <strong>{{ $c2['menores'] }}</strong>
                                                <small class="text-muted">({{ $c2['poblacion'] > 0 ? round(($c2['menores'] / $c2['poblacion']) * 100, 1) : 0 }}%)</small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-start ps-4">Personas con Patologías Crónicas</td>
                                            <td>
                                                <span class="text-danger fw-bold">{{ $c1['patologias'] }}</span>
                                                <small class="text-muted">({{ $c1['poblacion'] > 0 ? round(($c1['patologias'] / $c1['poblacion']) * 100, 1) : 0 }}%)</small>
                                            </td>
                                            <td>
                                                <span class="text-danger fw-bold">{{ $c2['patologias'] }}</span>
                                                <small class="text-muted">({{ $c2['poblacion'] > 0 ? round(($c2['patologias'] / $c2['poblacion']) * 100, 1) : 0 }}%)</small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-start ps-4">Cobertura Beneficio CLAP</td>
                                            <td>
                                                <span class="badge bg-soft-success text-success">{{ $c1['clap'] }} ciudadanos</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-soft-success text-success">{{ $c2['clap'] }} ciudadanos</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-start ps-4">Tenencia de Vivienda Propia</td>
                                            <td>
                                                <strong>{{ $c1['viviendaPropia'] }}</strong>
                                                <small class="text-muted">({{ $c1['poblacion'] > 0 ? round(($c1['viviendaPropia'] / $c1['poblacion']) * 100, 1) : 0 }}%)</small>
                                            </td>
                                            <td>
                                                <strong>{{ $c2['viviendaPropia'] }}</strong>
                                                <small class="text-muted">({{ $c2['poblacion'] > 0 ? round(($c2['viviendaPropia'] / $c2['poblacion']) * 100, 1) : 0 }}%)</small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-start ps-4">Estudiantes en Formación</td>
                                            <td>
                                                <strong>{{ $c1['estudiantes'] }}</strong>
                                                <small class="text-muted">({{ $c1['poblacion'] > 0 ? round(($c1['estudiantes'] / $c1['poblacion']) * 100, 1) : 0 }}%)</small>
                                            </td>
                                            <td>
                                                <strong>{{ $c2['estudiantes'] }}</strong>
                                                <small class="text-muted">({{ $c2['poblacion'] > 0 ? round(($c2['estudiantes'] / $c2['poblacion']) * 100, 1) : 0 }}%)</small>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5 bg-white rounded-3 border">
                                <div class="bg-soft-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                                    <i class="ri-scales-3-line text-primary fs-2"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">Sin comparación activa</h6>
                                <p class="text-muted small mb-0">Seleccione dos consejos comunales en el formulario superior para ver los contrastes demográficos.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Datos JSON embebidos para actualización instantánea de gráficos -->
            <script id="echarts-data" type="application/json">
            {
                "totalCiudadanos": {{ $totalCiudadanos }},
                "edades": [
                    {{ $histogramaEdades['0-9'] }},
                    {{ $histogramaEdades['10-19'] }},
                    {{ $histogramaEdades['20-29'] }},
                    {{ $histogramaEdades['30-39'] }},
                    {{ $histogramaEdades['40-49'] }},
                    {{ $histogramaEdades['50-59'] }},
                    {{ $histogramaEdades['60-69'] }},
                    {{ $histogramaEdades['70+'] }}
                ],
                "profesionesNombres": {!! json_encode($profesiones->take(8)->pluck('profesion')->toArray()) !!},
                "profesionesTotales": {!! json_encode($profesiones->take(8)->pluck('total')->toArray()) !!},
                "academico": [
                    {{ $niveles['Ninguno'] ?? 0 }},
                    {{ $niveles['Preescolar'] ?? 0 }},
                    {{ $niveles['Primaria'] ?? 0 }},
                    {{ $niveles['Secundaria con título de bachiller'] ?? 0 }},
                    {{ $niveles['Superior con certificación de técnico superior'] ?? 0 }},
                    {{ $niveles['Pregrado universitario (licenciatura, ingeniería)'] ?? 0 }},
                    {{ $niveles['Posgrado universitario (maestría, doctorado, especialización)'] ?? 0 }},
                    {{ $niveles['No sabe/no responde'] ?? 0 }}
                ],
                "generoCicloMasc": [
                    {{ $generoPorCiclo['menores']['masculino'] }},
                    {{ $generoPorCiclo['adultos']['masculino'] }},
                    {{ $generoPorCiclo['abuelos']['masculino'] }}
                ],
                "generoCicloFem": [
                    {{ $generoPorCiclo['menores']['femenino'] }},
                    {{ $generoPorCiclo['adultos']['femenino'] }},
                    {{ $generoPorCiclo['abuelos']['femenino'] }}
                ],
                "socio": [
                    {{ $recibeClapCount }},
                    {{ $bonoFamiliarCount }},
                    {{ $misionViviendaCount }},
                    {{ $viviendas['Propia'] ?? 0 }},
                    {{ $viviendas['Prestada'] ?? 0 }},
                    {{ $viviendas['Alquilada'] ?? 0 }}
                ],
                "enfNombres": {!! json_encode($enfermedades->take(8)->pluck('tipo_enfermedad')->toArray()) !!},
                "enfTotales": {!! json_encode($enfermedades->take(8)->pluck('total')->toArray()) !!}
            }
            </script>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('vendor/echart/echarts.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let chartEdades = null;
        let chartProfesiones = null;
        let chartAcademico = null;
        let chartGeneroCiclo = null;
        let chartSocio = null;
        let chartEnfermedades = null;

        // Desechar instancias previas para evitar referencias a nodos DOM huérfanos
        function disposeGraficos() {
            [chartEdades, chartProfesiones, chartAcademico, chartGeneroCiclo, chartSocio, chartEnfermedades].forEach(c => {
                if (c && typeof c.dispose === 'function') {
                    try { c.dispose(); } catch (e) {}
                }
            });
            chartEdades = null;
            chartProfesiones = null;
            chartAcademico = null;
            chartGeneroCiclo = null;
            chartSocio = null;
            chartEnfermedades = null;
        }

        // Obtener o inicializar una instancia limpia de ECharts sobre el elemento DOM actual
        function initChartInstance(domElement) {
            if (!domElement) return null;
            const existing = echarts.getInstanceByDom(domElement);
            if (existing) {
                try { existing.dispose(); } catch (e) {}
            }
            return echarts.init(domElement);
        }

        function resizeAll() {
            [chartEdades, chartProfesiones, chartAcademico, chartGeneroCiclo, chartSocio, chartEnfermedades].forEach(c => {
                if (c && typeof c.resize === 'function') {
                    try { c.resize(); } catch (e) {}
                }
            });
        }

        function renderizarOActualizarGraficos(datos) {
            if (!datos) return;
            const totalPoblacion = (datos.totalCiudadanos && datos.totalCiudadanos > 0) ? datos.totalCiudadanos : 1;

            // 1. Histograma de Edades
            const elChartEdades = document.getElementById('chart-histograma-edades');
            if (elChartEdades) {
                chartEdades = initChartInstance(elChartEdades);
                if (chartEdades) {
                    const categoriasEdades = ['0-9 años', '10-19', '20-29', '30-39', '40-49', '50-59', '60-69', '70+ años'];
                    chartEdades.setOption({
                        tooltip: {
                            trigger: 'axis',
                            axisPointer: { type: 'shadow' },
                            formatter: function (params) {
                                const item = params[0];
                                const pct = ((item.value / totalPoblacion) * 100).toFixed(1);
                                return `<strong>${item.name}</strong><br/>
                                        <span style="display:inline-block;margin-right:5px;border-radius:10px;width:10px;height:10px;background-color:#3a57e8;"></span>
                                        Cantidad: <b>${item.value} ciudadanos</b> (${pct}%)`;
                            }
                        },
                        grid: { left: '3%', right: '4%', bottom: '4%', top: '12%', containLabel: true },
                        xAxis: {
                            type: 'category',
                            data: categoriasEdades,
                            axisLabel: { color: '#6c757d', interval: 0, fontSize: 11 },
                            axisLine: { lineStyle: { color: '#e0e0e0' } },
                            axisTick: { alignWithLabel: true }
                        },
                        yAxis: {
                            type: 'value',
                            name: 'Ciudadanos',
                            nameTextStyle: { color: '#6c757d', fontSize: 11 },
                            minInterval: 1,
                            splitLine: { lineStyle: { type: 'dashed', color: '#f0f0f0' } }
                        },
                        series: [{
                            name: 'Frecuencia',
                            type: 'bar',
                            barWidth: '55%',
                            data: datos.edades || [0,0,0,0,0,0,0,0],
                            itemStyle: { borderRadius: [6, 6, 0, 0], color: '#3a57e8' },
                            label: {
                                show: true,
                                position: 'top',
                                color: '#3a57e8',
                                fontWeight: 'bold',
                                fontSize: 11,
                                formatter: function(params) { return params.value > 0 ? params.value : ''; }
                            }
                        }]
                    }, true);
                }
            }

            // 2. Histograma de Profesiones
            const elChartProfesiones = document.getElementById('chart-histograma-profesiones');
            const elVacioProfesiones = document.getElementById('vacio-profesiones');
            if (elChartProfesiones) {
                if (datos.profesionesNombres && datos.profesionesNombres.length > 0) {
                    elChartProfesiones.style.display = 'block';
                    if (elVacioProfesiones) elVacioProfesiones.style.display = 'none';
                    chartProfesiones = initChartInstance(elChartProfesiones);
                    if (chartProfesiones) {
                        chartProfesiones.setOption({
                            tooltip: {
                                trigger: 'axis',
                                axisPointer: { type: 'shadow' },
                                formatter: function (params) {
                                    const item = params[0];
                                    const pct = ((item.value / totalPoblacion) * 100).toFixed(1);
                                    return `<strong>${item.name}</strong><br/>
                                            <span style="display:inline-block;margin-right:5px;border-radius:10px;width:10px;height:10px;background-color:#ffbe0c;"></span>
                                            Total personas: <b>${item.value}</b> (${pct}% de la población)`;
                                }
                            },
                            grid: { left: '3%', right: '4%', bottom: '18%', top: '12%', containLabel: true },
                            xAxis: {
                                type: 'category',
                                data: datos.profesionesNombres,
                                axisLabel: {
                                    color: '#6c757d',
                                    interval: 0,
                                    rotate: 20,
                                    fontSize: 10,
                                    formatter: function(val) { return val.length > 12 ? val.substring(0, 11) + '…' : val; }
                                },
                                axisLine: { lineStyle: { color: '#e0e0e0' } },
                                axisTick: { alignWithLabel: true }
                            },
                            yAxis: {
                                type: 'value',
                                name: 'Ciudadanos',
                                nameTextStyle: { color: '#6c757d', fontSize: 11 },
                                minInterval: 1,
                                splitLine: { lineStyle: { type: 'dashed', color: '#f0f0f0' } }
                            },
                            series: [{
                                name: 'Profesión / Oficio',
                                type: 'bar',
                                barWidth: '45%',
                                data: datos.profesionesTotales,
                                itemStyle: { borderRadius: [6, 6, 0, 0], color: '#ffbe0c' },
                                label: {
                                    show: true,
                                    position: 'top',
                                    color: '#b8860b',
                                    fontWeight: 'bold',
                                    fontSize: 11,
                                    formatter: function(params) { return params.value > 0 ? params.value : ''; }
                                }
                            }]
                        }, true);
                    }
                } else {
                    elChartProfesiones.style.display = 'none';
                    if (elVacioProfesiones) elVacioProfesiones.style.display = 'block';
                }
            }

            // 3. Histograma Nivel Académico
            const elChartAcademico = document.getElementById('chart-histograma-academico');
            if (elChartAcademico) {
                chartAcademico = initChartInstance(elChartAcademico);
                if (chartAcademico) {
                    const categoriasAcademico = ['Ninguno', 'Preescolar', 'Primaria', 'Secundaria / Bach.', 'TSU', 'Pregrado', 'Posgrado', 'NS/NR'];
                    chartAcademico.setOption({
                        tooltip: {
                            trigger: 'axis',
                            axisPointer: { type: 'shadow' },
                            formatter: function (params) {
                                const item = params[0];
                                const pct = ((item.value / totalPoblacion) * 100).toFixed(1);
                                return `<strong>${item.name}</strong><br/>
                                        <span style="display:inline-block;margin-right:5px;border-radius:10px;width:10px;height:10px;background-color:#4f46e5;"></span>
                                        Ciudadanos: <b>${item.value}</b> (${pct}%)`;
                            }
                        },
                        grid: { left: '3%', right: '4%', bottom: '18%', top: '12%', containLabel: true },
                        xAxis: {
                            type: 'category',
                            data: categoriasAcademico,
                            axisLabel: { color: '#6c757d', interval: 0, rotate: 22, fontSize: 10 },
                            axisLine: { lineStyle: { color: '#e0e0e0' } },
                            axisTick: { alignWithLabel: true }
                        },
                        yAxis: {
                            type: 'value',
                            name: 'Ciudadanos',
                            nameTextStyle: { color: '#6c757d', fontSize: 11 },
                            minInterval: 1,
                            splitLine: { lineStyle: { type: 'dashed', color: '#f0f0f0' } }
                        },
                        series: [{
                            name: 'Nivel Académico',
                            type: 'bar',
                            barWidth: '50%',
                            data: datos.academico || [0,0,0,0,0,0,0,0],
                            itemStyle: { borderRadius: [6, 6, 0, 0], color: '#4f46e5' },
                            label: {
                                show: true,
                                position: 'top',
                                color: '#4f46e5',
                                fontWeight: 'bold',
                                fontSize: 11,
                                formatter: function(params) { return params.value > 0 ? params.value : ''; }
                            }
                        }]
                    }, true);
                }
            }

            // 4. Género por Ciclo de Vida
            const elChartGeneroCiclo = document.getElementById('chart-histograma-genero-ciclo');
            if (elChartGeneroCiclo) {
                chartGeneroCiclo = initChartInstance(elChartGeneroCiclo);
                if (chartGeneroCiclo) {
                    chartGeneroCiclo.setOption({
                        tooltip: {
                            trigger: 'axis',
                            axisPointer: { type: 'shadow' },
                            formatter: function (params) {
                                let html = `<strong>${params[0].name}</strong><br/>`;
                                params.forEach(p => {
                                    const dotColor = p.seriesName === 'Masculino' ? '#3a57e8' : '#ff2525';
                                    html += `<span style="display:inline-block;margin-right:5px;border-radius:10px;width:10px;height:10px;background-color:${dotColor};"></span>
                                             ${p.seriesName}: <b>${p.value} personas</b><br/>`;
                                });
                                return html;
                            }
                        },
                        legend: { data: ['Masculino', 'Femenino'], top: '2%', textStyle: { color: '#6c757d', fontSize: 11 } },
                        grid: { left: '3%', right: '4%', bottom: '4%', top: '18%', containLabel: true },
                        xAxis: {
                            type: 'category',
                            data: ['Menores (0-17)', 'Adultos (18-59)', 'Mayores (60+)'],
                            axisLabel: { color: '#6c757d', fontSize: 11 },
                            axisLine: { lineStyle: { color: '#e0e0e0' } },
                            axisTick: { alignWithLabel: true }
                        },
                        yAxis: {
                            type: 'value',
                            name: 'Ciudadanos',
                            nameTextStyle: { color: '#6c757d', fontSize: 11 },
                            minInterval: 1,
                            splitLine: { lineStyle: { type: 'dashed', color: '#f0f0f0' } }
                        },
                        series: [
                            {
                                name: 'Masculino',
                                type: 'bar',
                                barWidth: '35%',
                                data: datos.generoCicloMasc || [0, 0, 0],
                                itemStyle: { borderRadius: [4, 4, 0, 0], color: '#3a57e8' },
                                label: {
                                    show: true,
                                    position: 'top',
                                    color: '#3a57e8',
                                    fontWeight: 'bold',
                                    fontSize: 11,
                                    formatter: function(params) { return params.value > 0 ? params.value : ''; }
                                }
                            },
                            {
                                name: 'Femenino',
                                type: 'bar',
                                barWidth: '35%',
                                data: datos.generoCicloFem || [0, 0, 0],
                                itemStyle: { borderRadius: [4, 4, 0, 0], color: '#ff2525' },
                                label: {
                                    show: true,
                                    position: 'top',
                                    color: '#ff2525',
                                    fontWeight: 'bold',
                                    fontSize: 11,
                                    formatter: function(params) { return params.value > 0 ? params.value : ''; }
                                }
                            }
                        ]
                    }, true);
                }
            }

            // 5. Socioeconómico
            const elChartSocio = document.getElementById('chart-histograma-socioeconomico');
            if (elChartSocio) {
                chartSocio = initChartInstance(elChartSocio);
                if (chartSocio) {
                    const catSocio = ['Recibe CLAP', 'Bono Familiar', 'Misión Viv.', 'Viv. Propia', 'Viv. Prestada', 'Viv. Alquilada'];
                    chartSocio.setOption({
                        tooltip: {
                            trigger: 'axis',
                            axisPointer: { type: 'shadow' },
                            formatter: function (params) {
                                const item = params[0];
                                const pct = ((item.value / totalPoblacion) * 100).toFixed(1);
                                return `<strong>${item.name}</strong><br/>
                                        <span style="display:inline-block;margin-right:5px;border-radius:10px;width:10px;height:10px;background-color:#1aa053;"></span>
                                        Beneficiarios / Ciudadanos: <b>${item.value}</b> (${pct}%)`;
                            }
                        },
                        grid: { left: '3%', right: '4%', bottom: '16%', top: '12%', containLabel: true },
                        xAxis: {
                            type: 'category',
                            data: catSocio,
                            axisLabel: { color: '#6c757d', interval: 0, rotate: 15, fontSize: 10 },
                            axisLine: { lineStyle: { color: '#e0e0e0' } },
                            axisTick: { alignWithLabel: true }
                        },
                        yAxis: {
                            type: 'value',
                            name: 'Ciudadanos',
                            nameTextStyle: { color: '#6c757d', fontSize: 11 },
                            minInterval: 1,
                            splitLine: { lineStyle: { type: 'dashed', color: '#f0f0f0' } }
                        },
                        series: [{
                            name: 'Beneficios y Vivienda',
                            type: 'bar',
                            barWidth: '45%',
                            data: datos.socio || [0,0,0,0,0,0],
                            itemStyle: { borderRadius: [6, 6, 0, 0], color: '#1aa053' },
                            label: {
                                show: true,
                                position: 'top',
                                color: '#1aa053',
                                fontWeight: 'bold',
                                fontSize: 11,
                                formatter: function(params) { return params.value > 0 ? params.value : ''; }
                            }
                        }]
                    }, true);
                }
            }

            // 6. Patologías
            const elChartEnfermedades = document.getElementById('chart-histograma-enfermedades');
            const elVacioEnfermedades = document.getElementById('vacio-enfermedades');
            if (elChartEnfermedades) {
                if (datos.enfNombres && datos.enfNombres.length > 0) {
                    elChartEnfermedades.style.display = 'block';
                    if (elVacioEnfermedades) elVacioEnfermedades.style.display = 'none';
                    chartEnfermedades = initChartInstance(elChartEnfermedades);
                    if (chartEnfermedades) {
                        chartEnfermedades.setOption({
                            tooltip: {
                                trigger: 'axis',
                                axisPointer: { type: 'shadow' },
                                formatter: function (params) {
                                    const item = params[0];
                                    const pct = ((item.value / totalPoblacion) * 100).toFixed(1);
                                    return `<strong>${item.name}</strong><br/>
                                            <span style="display:inline-block;margin-right:5px;border-radius:10px;width:10px;height:10px;background-color:#ff2525;"></span>
                                            Casos diagnosticados: <b>${item.value} personas</b> (${pct}%)`;
                                }
                            },
                            grid: { left: '3%', right: '4%', bottom: '18%', top: '12%', containLabel: true },
                            xAxis: {
                                type: 'category',
                                data: datos.enfNombres,
                                axisLabel: {
                                    color: '#6c757d',
                                    interval: 0,
                                    rotate: 20,
                                    fontSize: 10,
                                    formatter: function(val) { return val.length > 12 ? val.substring(0, 11) + '…' : val; }
                                },
                                axisLine: { lineStyle: { color: '#e0e0e0' } },
                                axisTick: { alignWithLabel: true }
                            },
                            yAxis: {
                                type: 'value',
                                name: 'Casos',
                                nameTextStyle: { color: '#6c757d', fontSize: 10 },
                                minInterval: 1,
                                splitLine: { lineStyle: { type: 'dashed', color: '#f0f0f0' } }
                            },
                            series: [{
                                name: 'Casos',
                                type: 'bar',
                                barWidth: '45%',
                                data: datos.enfTotales,
                                itemStyle: { borderRadius: [6, 6, 0, 0], color: '#ff2525' },
                                label: {
                                    show: true,
                                    position: 'top',
                                    color: '#ff2525',
                                    fontWeight: 'bold',
                                    fontSize: 11,
                                    formatter: function(params) { return params.value > 0 ? params.value : ''; }
                                }
                            }]
                        }, true);
                    }
                } else {
                    elChartEnfermedades.style.display = 'none';
                    if (elVacioEnfermedades) elVacioEnfermedades.style.display = 'block';
                }
            }

            // Redimensionar después de renderizar
            setTimeout(resizeAll, 30);
        }

        // Cargar datos iniciales desde el JSON embebido
        const initialJsonEl = document.getElementById('echarts-data');
        if (initialJsonEl) {
            try {
                const initialData = JSON.parse(initialJsonEl.textContent);
                renderizarOActualizarGraficos(initialData);
            } catch(e) {
                console.error('Error parseando json inicial de graficos:', e);
            }
        }

        // -------------------------------------------------------------
        // ACTUALIZACIÓN DE ENLACES PDF
        // -------------------------------------------------------------
        function actualizarEnlacesPdf(paramsString) {
            const baseUrlPdf = '{{ route("reportes.pdf") }}';
            const btnGeneral = document.getElementById('btn-pdf-general');
            const btnSalud = document.getElementById('btn-pdf-salud');
            const btnSocio = document.getElementById('btn-pdf-socioproductivo');

            if (btnGeneral) btnGeneral.href = baseUrlPdf + '?' + paramsString + '&tipo_reporte=general';
            if (btnSalud) btnSalud.href = baseUrlPdf + '?' + paramsString + '&tipo_reporte=salud';
            if (btnSocio) btnSocio.href = baseUrlPdf + '?' + paramsString + '&tipo_reporte=socioproductivo';
        }

        // -------------------------------------------------------------
        // ACTUALIZACIÓN DINÁMICA SIN RECARGAR LA PÁGINA (AJAX / FETCH)
        // -------------------------------------------------------------
        function actualizarFiltroDinamicoSinRecarga() {
            const form = document.getElementById('form-filtro-reportes');
            if (!form) return;

            const formData = new FormData(form);
            const params = new URLSearchParams(formData);
            const url = form.action + '?' + params.toString();

            // Sincronizar URL del navegador sin recargar pantalla
            window.history.replaceState(null, '', url);
            actualizarEnlacesPdf(params.toString());

            // Indicador sutil sin overlay de pantalla completa
            const indBadge = document.getElementById('indicador-dinamico-badge');
            if (indBadge) {
                indBadge.style.display = 'inline-flex';
            }
            const contenedor = document.getElementById('contenedor-dinamico-reportes');
            if (contenedor) {
                contenedor.style.transition = 'opacity 0.15s ease';
                contenedor.style.opacity = '0.45';
            }

            fetch(url)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    // 1. Reemplazar contenedor dinámico
                    const nuevoContenedor = doc.getElementById('contenedor-dinamico-reportes');
                    if (nuevoContenedor && contenedor) {
                        const activeMainTabId = $('#reportesTabs .nav-link.active').attr('data-bs-target') || '#tab-graficos';
                        const activeSubTabId = $('#listadosSubTabs .nav-link.active').attr('data-bs-target') || '#subtab-electoral';

                        // Desechar explícitamente instancias previas
                        disposeGraficos();

                        contenedor.innerHTML = nuevoContenedor.innerHTML;

                        // Restaurar pestaña activa
                        if (activeMainTabId) {
                            const btnMain = document.querySelector(`#reportesTabs button[data-bs-target="${activeMainTabId}"]`);
                            if (btnMain && window.bootstrap && window.bootstrap.Tab) {
                                window.bootstrap.Tab.getOrCreateInstance(btnMain).show();
                            }
                        }
                        if (activeSubTabId) {
                            const btnSub = document.querySelector(`#listadosSubTabs button[data-bs-target="${activeSubTabId}"]`);
                            if (btnSub && window.bootstrap && window.bootstrap.Tab) {
                                window.bootstrap.Tab.getOrCreateInstance(btnSub).show();
                            }
                        }

                        // 2. Extraer datos JSON y actualizar gráficos sobre los nuevos elementos DOM
                        const scriptData = doc.getElementById('echarts-data');
                        if (scriptData) {
                            try {
                                const nuevosDatos = JSON.parse(scriptData.textContent);
                                setTimeout(() => {
                                    renderizarOActualizarGraficos(nuevosDatos);
                                    setTimeout(resizeAll, 50);
                                }, 30);
                            } catch(e) {
                                console.error('Error parseando datos:', e);
                            }
                        }
                    }
                })
                .catch(err => {
                    console.error('Error en fetch dinámico:', err);
                })
                .finally(() => {
                    const contenedorActual = document.getElementById('contenedor-dinamico-reportes');
                    if (contenedorActual) contenedorActual.style.opacity = '1';
                    const badgeActual = document.getElementById('indicador-dinamico-badge');
                    if (badgeActual) badgeActual.style.display = 'none';
                });
        }

        // Listener en todos los selects con actualización instantánea
        $(document).on('change', '.select-filtro-dinamico', function () {
            actualizarFiltroDinamicoSinRecarga();
        });

        // Botón Limpiar Filtros
        $(document).on('click', '#btn-limpiar-filtros-reportes', function (e) {
            e.preventDefault();
            $('#consejo_comunal_id').val('');
            $('#genero').val('');
            $('#grupo_etario').val('');
            $('#enfermedad').val('');
            $('#profesion').val('');
            actualizarFiltroDinamicoSinRecarga();
        });

        // Prevenir submit tradicional del formulario de filtros
        $('#form-filtro-reportes').on('submit', function (e) {
            e.preventDefault();
            actualizarFiltroDinamicoSinRecarga();
        });

        // Recordar pestañas
        $(document).on('shown.bs.tab', '#reportesTabs button[data-bs-toggle="pill"]', function (e) {
            const target = $(e.target).attr('data-bs-target');
            if (target === '#tab-graficos') $('#input_tab_activa').val('graficos');
            else if (target === '#tab-listados') $('#input_tab_activa').val('listados');
            else if (target === '#tab-comparador') $('#input_tab_activa').val('comparador');

            // Redimensionar gráficos si se vuelve a la pestaña de gráficos
            if (target === '#tab-graficos') {
                setTimeout(resizeAll, 80);
            }
        });

        $(document).on('shown.bs.tab', '#listadosSubTabs button[data-bs-toggle="tab"]', function (e) {
            const target = $(e.target).attr('data-bs-target');
            if (target === '#subtab-electoral') $('#input_listado_activo').val('electoral');
            else if (target === '#subtab-salud') $('#input_listado_activo').val('salud');
            else if (target === '#subtab-abuelos') $('#input_listado_activo').val('abuelos');
            else if (target === '#subtab-estudiantes') $('#input_listado_activo').val('estudiantes');
            else if (target === '#subtab-talentos') $('#input_listado_activo').val('talentos');
        });

        // Comparador dinámico de comunidades
        $(document).on('change', '.select-comparador', function () {
            const cc1 = $('#cc_comparar_1').val();
            const cc2 = $('#cc_comparar_2').val();
            if (cc1 && cc2) {
                $('#form-comparador-comunidades').submit();
            }
        });

        window.addEventListener('resize', resizeAll);
    });
</script>
@endpush
