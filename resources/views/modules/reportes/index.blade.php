@extends('layouts.main')
@section('titulo', $titulo)
@section('paginaTitulo', $paginaTitulo)
@section('paginaSubtitulo', $paginaSubtitulo)
@section('reportesActive', 'active')

@section('contenido')
    <div class="container-fluid content-inner mt-n5 py-0">
        <!-- Panel de Filtro Dinámico -->
        <div class="card shadow-sm mb-4 border-0" style="border-radius: 14px;">
            <div class="card-body p-4">
                <form id="form-filtro-reportes" action="{{ route('reportes.index') }}" method="GET" class="row g-3 align-items-center">
                    <div class="col-lg-6 col-md-8">
                        <label for="consejo_comunal_id" class="form-label small text-muted fw-bold mb-1">
                            <i class="ri-community-line text-primary me-1"></i> Filtrar por Comunidad (Consejo Comunal)
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-primary border-end-0">
                                <i class="ri-map-pin-2-fill"></i>
                            </span>
                            <select name="consejo_comunal_id" id="consejo_comunal_id" class="form-select border-start-0 ps-0 bg-white">
                                <option value="">-- Censo Completo (Todas las comunidades) --</option>
                                @foreach ($consejosComunales as $cc)
                                    <option value="{{ $cc->id }}" {{ $consejoComunalId == $cc->id ? 'selected' : '' }}>
                                        {{ $cc->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-text small text-muted mt-1 d-flex align-items-center gap-1">
                            <!-- <i class="ri-flashlight-line text-warning"></i>
                            <span>Actualización dinámica: el reporte se actualiza automáticamente al cambiar la selección.</span> -->
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-4 d-flex justify-content-md-end align-items-center gap-2 mt-3 mt-md-0">
                        @if ($consejoComunalId)
                            <a href="{{ route('reportes.index') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
                                <i class="ri-refresh-line"></i> Ver Censo Completo
                            </a>
                        @endif
                        <a href="{{ route('reportes.pdf', request()->query()) }}"
                            class="btn btn-danger d-flex align-items-center gap-2 shadow-sm">
                            <i class="ri-file-pdf-fill fs-5"></i> Exportar Reporte PDF
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Indicador de Contexto Actual -->
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2 px-1">
            <h5 class="mb-0 text-dark fw-bold d-flex align-items-center gap-2">
                <span class="badge bg-soft-primary text-primary px-3 py-2 rounded-pill fs-6 fw-bold">
                    <i class="ri-building-line me-1"></i>
                    {{ $comunidadSeleccionada ? $comunidadSeleccionada->nombre : 'Todas las Comunidades (Censo General)' }}
                </span>
                <span class="text-muted small fw-normal">| {{ $totalCiudadanos }} ciudadanos analizados</span>
            </h5>
            <span class="text-muted small">
                <i class="ri-time-line me-1"></i> Datos consolidados en tiempo real
            </span>
        </div>

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
            <!-- Histograma de Distribución por Edades -->
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
                        <div id="chart-histograma-edades" style="height: 270px; width: 100%;"></div>
                    </div>
                </div>
            </div>

            <!-- Histograma / Estadísticas de Profesiones y Oficios -->
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
                        @if ($profesiones->count() > 0)
                            <div id="chart-histograma-profesiones" style="height: 270px; width: 100%;"></div>
                        @else
                            <div class="text-center py-5">
                                <div class="bg-soft-warning rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background-color: #fff9e6;">
                                    <i class="ri-user-unfollow-line text-warning fs-3"></i>
                                </div>
                                <h6 class="text-muted fw-bold">Sin profesiones registradas</h6>
                                <p class="text-muted small mb-0">No se encontraron ciudadanos con profesión u oficio registrado en este filtro.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Fila 2 de Histogramas: Nivel Académico y Género por Ciclo de Vida -->
        <div class="row g-4 mb-4">
            <!-- Histograma de Nivel Académico -->
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
                            <span class="badge small" style="background-color: #eef2ff; color: #4f46e5; border: 1px solid #e0e7ff;" title="Técnico Superior Universitario">TSU: Téc. Superior</span>
                            <span class="badge small" style="background-color: #f5f3ff; color: #6366f1; border: 1px solid #ede9fe;" title="No sabe / no responde">NS/NR: Sin respuesta</span>
                        </div>
                    </div>
                    <div class="card-body px-3 pb-3">
                        <div id="chart-histograma-academico" style="height: 270px; width: 100%;"></div>
                    </div>
                </div>
            </div>

            <!-- Histograma Comparativo: Género por Ciclo de Vida -->
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
                        <div id="chart-histograma-genero-ciclo" style="height: 270px; width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fila 3 de Histogramas: Beneficios/Vivienda y Enfermedades Frecuentes -->
        <div class="row g-4 mb-4">
            <!-- Histograma de Beneficios Sociales y Vivienda -->
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
                        <div id="chart-histograma-socioeconomico" style="height: 270px; width: 100%;"></div>
                    </div>
                </div>
            </div>

            <!-- Histograma de Enfermedades Frecuentes -->
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
                        @if ($enfermedades->count() > 0)
                            <div id="chart-histograma-enfermedades" style="height: 270px; width: 100%;"></div>
                        @else
                            <div class="text-center py-5">
                                <div class="bg-soft-success rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="ri-shield-check-line text-success fs-3"></i>
                                </div>
                                <h6 class="text-success fw-bold">Sin patologías registradas</h6>
                                <p class="text-muted small mb-0">No se registran ciudadanos con afecciones médicas en el grupo consultado.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('vendor/echart/echarts.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // -------------------------------------------------------------
        // 1. FILTRADO DINÁMICO POR CONSEJO COMUNAL
        // -------------------------------------------------------------
        const consejoSelect = document.getElementById('consejo_comunal_id');
        if (consejoSelect) {
            consejoSelect.addEventListener('change', function () {
                const form = document.getElementById('form-filtro-reportes');
                // Indicador visual sutil de carga
                document.body.style.cursor = 'wait';
                consejoSelect.style.opacity = '0.7';
                form.submit();
            });
        }

        // Variable de población total para cálculos de porcentaje
        const totalPoblacion = {{ $totalCiudadanos > 0 ? $totalCiudadanos : 1 }};

        // Paleta de 4 colores del sistema (Azul, Amarillo, Verde, Rojo) distribuida equitativamente
        const paleta4 = ['#3a57e8', '#ffbe0c', '#1aa053', '#ff2525'];

        // -------------------------------------------------------------
        // 2. HISTOGRAMA DE EDADES (Intervalos Etarios) -> COLOR AZUL (#3a57e8)
        // -------------------------------------------------------------
        const elChartEdades = document.getElementById('chart-histograma-edades');
        let chartEdades = null;
        if (elChartEdades) {
            chartEdades = echarts.init(elChartEdades);
            const dataEdades = [
                {{ $histogramaEdades['0-9'] }},
                {{ $histogramaEdades['10-19'] }},
                {{ $histogramaEdades['20-29'] }},
                {{ $histogramaEdades['30-39'] }},
                {{ $histogramaEdades['40-49'] }},
                {{ $histogramaEdades['50-59'] }},
                {{ $histogramaEdades['60-69'] }},
                {{ $histogramaEdades['70+'] }}
            ];
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
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '4%',
                    top: '12%',
                    containLabel: true
                },
                xAxis: {
                    type: 'category',
                    data: categoriasEdades,
                    axisLabel: {
                        color: '#6c757d',
                        interval: 0,
                        fontSize: 11
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
                    name: 'Frecuencia',
                    type: 'bar',
                    barWidth: '55%',
                    data: dataEdades,
                    itemStyle: {
                        borderRadius: [6, 6, 0, 0],
                        color: '#3a57e8'
                    },
                    label: {
                        show: true,
                        position: 'top',
                        color: '#3a57e8',
                        fontWeight: 'bold',
                        fontSize: 11,
                        formatter: function(params) {
                            return params.value > 0 ? params.value : '';
                        }
                    }
                }]
            });
        }

        // -------------------------------------------------------------
        // 3. HISTOGRAMA DE PROFESIONES Y OFICIOS (VERTICAL) -> COLOR AMARILLO (#ffbe0c)
        // -------------------------------------------------------------
        const elChartProfesiones = document.getElementById('chart-histograma-profesiones');
        let chartProfesiones = null;
        if (elChartProfesiones) {
            chartProfesiones = echarts.init(elChartProfesiones);
            const categoriasProfesiones = [
                @foreach ($profesiones->take(8) as $prof)
                    '{{ addslashes($prof->profesion) }}',
                @endforeach
            ];
            const dataProfesiones = [
                @foreach ($profesiones->take(8) as $prof)
                    {{ $prof->total }},
                @endforeach
            ];

            chartProfesiones.setOption({
                tooltip: {
                    trigger: 'axis',
                    axisPointer: { type: 'shadow' },
                    formatter: function (params) {
                        const item = params[0];
                        const pct = ((item.value / totalPoblacion) * 100).toFixed(1);
                        return `<strong>${item.name}</strong><br/>
                                <span style="display:inline-block;margin-right:5px;border-radius:10px;width:10px;height:10px;background-color:#ffbe0c;"></span>
                                Personas: <b>${item.value}</b> (${pct}% del censo)`;
                    }
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '4%',
                    top: '12%',
                    containLabel: true
                },
                xAxis: {
                    type: 'category',
                    data: categoriasProfesiones,
                    axisLabel: {
                        color: '#495057',
                        fontSize: 10,
                        interval: 0,
                        rotate: 20,
                        formatter: function(val) {
                            return val.length > 14 ? val.substring(0, 12) + '..' : val;
                        }
                    },
                    axisLine: { lineStyle: { color: '#e0e0e0' } },
                    axisTick: { alignWithLabel: true }
                },
                yAxis: {
                    type: 'value',
                    name: 'Personas',
                    nameTextStyle: { color: '#6c757d', fontSize: 10 },
                    minInterval: 1,
                    splitLine: { lineStyle: { type: 'dashed', color: '#f0f0f0' } }
                },
                series: [{
                    name: 'Ocupación',
                    type: 'bar',
                    barWidth: '50%',
                    data: dataProfesiones,
                    itemStyle: {
                        borderRadius: [6, 6, 0, 0],
                        color: '#ffbe0c'
                    },
                    label: {
                        show: true,
                        position: 'top',
                        color: '#d99b00',
                        fontWeight: 'bold',
                        fontSize: 11,
                        formatter: function(params) {
                            return params.value > 0 ? params.value : '';
                        }
                    }
                }]
            });
        }

        // -------------------------------------------------------------
        // 4. HISTOGRAMA DE NIVEL ACADÉMICO -> COLOR PÚRPURA / ÍNDIGO (#4f46e5)
        // -------------------------------------------------------------
        const elChartAcademico = document.getElementById('chart-histograma-academico');
        let chartAcademico = null;
        if (elChartAcademico) {
            chartAcademico = echarts.init(elChartAcademico);
            const nivelesConfig = [
                { corto: 'Ninguno', full: 'Ninguno', val: {{ $niveles['Ninguno'] ?? 0 }} },
                { corto: 'Preescolar', full: 'Preescolar', val: {{ $niveles['Preescolar'] ?? 0 }} },
                { corto: 'Primaria', full: 'Primaria', val: {{ $niveles['Primaria'] ?? 0 }} },
                { corto: 'Bachiller', full: 'Secundaria con título de bachiller', val: {{ $niveles['Secundaria con título de bachiller'] ?? 0 }} },
                { corto: 'TSU', full: 'Superior con certificación de técnico superior (TSU)', val: {{ $niveles['Superior con certificación de técnico superior'] ?? 0 }} },
                { corto: 'Pregrado', full: 'Pregrado universitario (licenciatura, ingeniería)', val: {{ $niveles['Pregrado universitario (licenciatura, ingeniería)'] ?? 0 }} },
                { corto: 'Posgrado', full: 'Posgrado universitario (maestría, doctorado, especialización)', val: {{ $niveles['Posgrado universitario (maestría, doctorado, especialización)'] ?? 0 }} },
                { corto: 'NS / NR', full: 'No sabe / no responde', val: {{ $niveles['No sabe/no responde'] ?? 0 }} }
            ];

            const categoriasNivel = nivelesConfig.map(i => i.corto);
            const dataNivel = nivelesConfig.map(i => i.val);

            chartAcademico.setOption({
                tooltip: {
                    trigger: 'axis',
                    axisPointer: { type: 'shadow' },
                    formatter: function (params) {
                        const item = params[0];
                        const conf = nivelesConfig[item.dataIndex] || { full: item.name };
                        const pct = ((item.value / totalPoblacion) * 100).toFixed(1);
                        return `<strong>${conf.full}</strong><br/>
                                <span style="display:inline-block;margin-right:5px;border-radius:10px;width:10px;height:10px;background-color:#4f46e5;"></span>
                                Registrados: <b>${item.value} ciudadanos</b> (${pct}%)`;
                    }
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '4%',
                    top: '12%',
                    containLabel: true
                },
                xAxis: {
                    type: 'category',
                    data: categoriasNivel,
                    axisLabel: {
                        color: '#495057',
                        fontSize: 11,
                        fontWeight: '500',
                        interval: 0,
                        rotate: 0
                    },
                    axisLine: { lineStyle: { color: '#e0e0e0' } },
                    axisTick: { alignWithLabel: true }
                },
                yAxis: {
                    type: 'value',
                    minInterval: 1,
                    splitLine: { lineStyle: { type: 'dashed', color: '#f0f0f0' } }
                },
                series: [{
                    name: 'Nivel Educativo',
                    type: 'bar',
                    barWidth: '45%',
                    data: dataNivel,
                    itemStyle: {
                        borderRadius: [6, 6, 0, 0],
                        color: '#4f46e5'
                    },
                    label: {
                        show: true,
                        position: 'top',
                        color: '#4f46e5',
                        fontWeight: 'bold',
                        fontSize: 11,
                        formatter: function(params) {
                            return params.value > 0 ? params.value : '';
                        }
                    }
                }]
            });
        }

        // -------------------------------------------------------------
        // 5. HISTOGRAMA COMPARATIVO: GÉNERO POR CICLO DE VIDA (VERTICAL AGRUPADO)
        // -------------------------------------------------------------
        const elChartGeneroCiclo = document.getElementById('chart-histograma-genero-ciclo');
        let chartGeneroCiclo = null;
        if (elChartGeneroCiclo) {
            chartGeneroCiclo = echarts.init(elChartGeneroCiclo);
            chartGeneroCiclo.setOption({
                tooltip: {
                    trigger: 'axis',
                    axisPointer: { type: 'shadow' }
                },
                legend: {
                    data: ['Masculino', 'Femenino'],
                    top: '2%',
                    textStyle: { color: '#6c757d', fontSize: 11 }
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '4%',
                    top: '18%',
                    containLabel: true
                },
                xAxis: {
                    type: 'category',
                    data: ['Menores (0-17)', 'Adultos (18-59)', 'Adultos Mayores (60+)'],
                    axisLabel: { color: '#6c757d', fontSize: 11 },
                    axisLine: { lineStyle: { color: '#e0e0e0' } },
                    axisTick: { alignWithLabel: true }
                },
                yAxis: {
                    type: 'value',
                    minInterval: 1,
                    splitLine: { lineStyle: { type: 'dashed', color: '#f0f0f0' } }
                },
                series: [
                    {
                        name: 'Masculino',
                        type: 'bar',
                        barWidth: '35%',
                        data: [
                            {{ $generoPorCiclo['menores']['masculino'] }},
                            {{ $generoPorCiclo['adultos']['masculino'] }},
                            {{ $generoPorCiclo['abuelos']['masculino'] }}
                        ],
                        itemStyle: {
                            borderRadius: [6, 6, 0, 0],
                            color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                                { offset: 0, color: '#3a57e8' },
                                { offset: 1, color: '#6b8aff' }
                            ])
                        },
                        label: {
                            show: true,
                            position: 'top',
                            color: '#3a57e8',
                            fontWeight: 'bold',
                            fontSize: 10
                        }
                    },
                    {
                        name: 'Femenino',
                        type: 'bar',
                        barWidth: '35%',
                        data: [
                            {{ $generoPorCiclo['menores']['femenino'] }},
                            {{ $generoPorCiclo['adultos']['femenino'] }},
                            {{ $generoPorCiclo['abuelos']['femenino'] }}
                        ],
                        itemStyle: {
                            borderRadius: [6, 6, 0, 0],
                            color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                                { offset: 0, color: '#fe2f60' },
                                { offset: 1, color: '#ff7597' }
                            ])
                        },
                        label: {
                            show: true,
                            position: 'top',
                            color: '#fe2f60',
                            fontWeight: 'bold',
                            fontSize: 10
                        }
                    }
                ]
            });
        }

        // -------------------------------------------------------------
        // 6. HISTOGRAMA DE BENEFICIOS Y TENENCIA DE VIVIENDA -> COLOR VERDE (#1aa053)
        // -------------------------------------------------------------
        const elChartSocio = document.getElementById('chart-histograma-socioeconomico');
        let chartSocio = null;
        if (elChartSocio) {
            chartSocio = echarts.init(elChartSocio);
            const categoriasSocio = ['Reciben CLAP', 'Bono Familiar', 'Misión Viv.', 'Viv. Propia', 'Viv. Prestada', 'Viv. Alquilada'];
            const dataSocio = [
                {{ $recibeClapCount }},
                {{ $bonoFamiliarCount }},
                {{ $misionViviendaCount }},
                {{ $viviendas['Propia'] }},
                {{ $viviendas['Prestada'] }},
                {{ $viviendas['Alquilada'] }}
            ];

            chartSocio.setOption({
                tooltip: {
                    trigger: 'axis',
                    axisPointer: { type: 'shadow' },
                    formatter: function (params) {
                        const item = params[0];
                        const pct = ((item.value / totalPoblacion) * 100).toFixed(1);
                        return `<strong>${item.name}</strong><br/>
                                <span style="display:inline-block;margin-right:5px;border-radius:10px;width:10px;height:10px;background-color:#1aa053;"></span>
                                Beneficiarios / Casos: <b>${item.value}</b> (${pct}% de la población)`;
                    }
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '4%',
                    top: '12%',
                    containLabel: true
                },
                xAxis: {
                    type: 'category',
                    data: categoriasSocio,
                    axisLabel: {
                        color: '#6c757d',
                        fontSize: 10,
                        interval: 0,
                        rotate: 15
                    },
                    axisLine: { lineStyle: { color: '#e0e0e0' } },
                    axisTick: { alignWithLabel: true }
                },
                yAxis: {
                    type: 'value',
                    minInterval: 1,
                    splitLine: { lineStyle: { type: 'dashed', color: '#f0f0f0' } }
                },
                series: [{
                    name: 'Cobertura',
                    type: 'bar',
                    barWidth: '45%',
                    data: dataSocio,
                    itemStyle: {
                        borderRadius: [6, 6, 0, 0],
                        color: '#1aa053'
                    },
                    label: {
                        show: true,
                        position: 'top',
                        color: '#1aa053',
                        fontWeight: 'bold',
                        fontSize: 11
                    }
                }]
            });
        }

        // -------------------------------------------------------------
        // 7. HISTOGRAMA DE PATOLOGÍAS Y ENFERMEDADES (VERTICAL) -> COLOR ROJO (#ff2525)
        // -------------------------------------------------------------
        const elChartEnfermedades = document.getElementById('chart-histograma-enfermedades');
        let chartEnfermedades = null;
        if (elChartEnfermedades) {
            chartEnfermedades = echarts.init(elChartEnfermedades);
            const categoriasEnf = [
                @foreach ($enfermedades->take(8) as $enf)
                    '{{ addslashes($enf->tipo_enfermedad) }}',
                @endforeach
            ];
            const dataEnf = [
                @foreach ($enfermedades->take(8) as $enf)
                    {{ $enf->total }},
                @endforeach
            ];

            chartEnfermedades.setOption({
                tooltip: {
                    trigger: 'axis',
                    axisPointer: { type: 'shadow' },
                    formatter: function (params) {
                        const item = params[0];
                        return `<strong>${item.name}</strong><br/>
                                <span style="display:inline-block;margin-right:5px;border-radius:10px;width:10px;height:10px;background-color:#ff2525;"></span>
                                Casos Registrados: <b>${item.value} ciudadanos</b>`;
                    }
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '4%',
                    top: '12%',
                    containLabel: true
                },
                xAxis: {
                    type: 'category',
                    data: categoriasEnf,
                    axisLabel: {
                        color: '#495057',
                        fontSize: 10,
                        interval: 0,
                        rotate: 20,
                        formatter: function(val) {
                            return val.length > 14 ? val.substring(0, 12) + '..' : val;
                        }
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
                    barWidth: '50%',
                    data: dataEnf,
                    itemStyle: {
                        borderRadius: [6, 6, 0, 0],
                        color: '#ff2525'
                    },
                    label: {
                        show: true,
                        position: 'top',
                        color: '#ff2525',
                        fontWeight: 'bold',
                        fontSize: 11,
                        formatter: function(params) {
                            return params.value > 0 ? params.value : '';
                        }
                    }
                }]
            });
        }

        // -------------------------------------------------------------
        // REDIMENSIONAMIENTO AUTOMÁTICO RESPONSIVO
        // -------------------------------------------------------------
        window.addEventListener('resize', () => {
            if (chartEdades) chartEdades.resize();
            if (chartProfesiones) chartProfesiones.resize();
            if (chartAcademico) chartAcademico.resize();
            if (chartGeneroCiclo) chartGeneroCiclo.resize();
            if (chartSocio) chartSocio.resize();
            if (chartEnfermedades) chartEnfermedades.resize();
        });
    });
</script>
@endpush
