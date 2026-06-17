@extends('layouts.main')
@section('titulo', $titulo)
@section('paginaTitulo', $paginaTitulo)
@section('paginaSubtitulo', $paginaSubtitulo)
@section('reportesActive', 'active')

@section('contenido')
    <div class="container-fluid content-inner mt-n5 py-0">
        <!-- Panel de Filtros -->
        <div class="card shadow-sm mb-4" style="border-radius: 15px;">
            <div class="card-body p-4">
                <form action="{{ route('reportes.index') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="consejo_comunal_id" class="form-label small text-muted fw-bold">Filtrar por Comunidad (Consejo Comunal)</label>
                        <select name="consejo_comunal_id" id="consejo_comunal_id" class="form-select bg-white">
                            <option value="">Censo Completo (Todas las comunidades)</option>
                            @foreach($consejosComunales as $cc)
                                <option value="{{ $cc->id }}" {{ $consejoComunalId == $cc->id ? 'selected' : '' }}>{{ $cc->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="custom_age" class="form-label small text-muted fw-bold">Parámetro de Edad</label>
                        <input type="number" name="custom_age" id="custom_age" class="form-control bg-white" min="0" max="120" value="{{ $customAge }}">
                    </div>
                    <div class="col-md-3">
                        <label for="custom_age_operator" class="form-label small text-muted fw-bold">Comparación</label>
                        <select name="custom_age_operator" id="custom_age_operator" class="form-select bg-white">
                            <option value="above" {{ $customAgeOperator === 'above' ? 'selected' : '' }}>Mayor o igual (>=)</option>
                            <option value="below" {{ $customAgeOperator === 'below' ? 'selected' : '' }}>Menor (<)</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-grid gap-2">
                        <button type="submit" class="btn btn-primary"><i class="ri-search-line"></i> Consultar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Botón Exportar PDF -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h5 class="mb-0 text-dark fw-bold">
                @if($comunidadSeleccionada)
                    Resultados para: <span class="text-primary">{{ $comunidadSeleccionada->nombre }}</span>
                @else
                    Resultados para: <span class="text-primary">Todo el Censo</span>
                @endif
            </h5>
            <a href="{{ route('reportes.pdf', request()->query()) }}" class="btn btn-danger d-flex align-items-center gap-2">
                <i class="ri-file-pdf-fill" style="font-size: 1.2rem;"></i> Exportar Reporte en PDF
            </a>
        </div>

        <!-- Tarjetas de Métricas Rápidas -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm text-center" style="border-radius: 12px; border-left: 5px solid #0056b3;">
                    <div class="card-body py-3">
                        <p class="text-muted small text-uppercase fw-bold mb-1">Población Total</p>
                        <h2 class="fw-bold mb-0 text-primary">{{ $totalCiudadanos }}</h2>
                        <span class="text-muted small">Ciudadanos censados</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm text-center" style="border-radius: 12px; border-left: 5px solid #28a745;">
                    <div class="card-body py-3">
                        <p class="text-muted small text-uppercase fw-bold mb-1">Estudiantes</p>
                        <h2 class="fw-bold mb-0 text-success">{{ $estudianCount }}</h2>
                        <span class="text-muted small">({{$totalCiudadanos > 0 ? round(($estudianCount/$totalCiudadanos)*100, 1) : 0}}% del total)</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm text-center" style="border-radius: 12px; border-left: 5px solid #dc3545;">
                    <div class="card-body py-3">
                        <p class="text-muted small text-uppercase fw-bold mb-1">Con Enfermedades</p>
                        <h2 class="fw-bold mb-0 text-danger">{{ $conEnfermedadCount }}</h2>
                        <span class="text-muted small">({{$totalCiudadanos > 0 ? round(($conEnfermedadCount/$totalCiudadanos)*100, 1) : 0}}% del total)</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm text-center" style="border-radius: 12px; border-left: 5px solid #ffc107;">
                    <div class="card-body py-3">
                        <p class="text-muted small text-uppercase fw-bold mb-1">Pensionados / Jub.</p>
                        <h2 class="fw-bold mb-0 text-warning" style="color: #ffc107 !important;">{{ $pensionadosCount }}</h2>
                        <span class="text-muted small">({{$totalCiudadanos > 0 ? round(($pensionadosCount/$totalCiudadanos)*100, 1) : 0}}% del total)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Secciones de Distribución -->
        <div class="row mb-4">
            
            <!-- Columna Izquierda: Edad y Género -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100" style="border-radius: 15px;">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <h5 class="card-title fw-bold text-dark mb-0">Demografía de Edad y Género</h5>
                    </div>
                    <div class="card-body px-4 pb-4">
                        
                        <!-- Consulta de Edad Custom -->
                        <div class="p-3 mb-4 rounded bg-light border-start border-4 border-info">
                            <span class="small text-muted fw-bold d-block text-uppercase">Consulta de Edad Personalizada</span>
                            <p class="mb-0 fw-semibold">
                                Ciudadanos con edad {{ $customAgeOperator === 'above' ? 'mayor o igual (>=)' : 'menor (<)' }} a {{ $customAge }} años:
                                <span class="badge bg-info float-end fs-6">{{ $customAgeCount }}</span>
                            </p>
                        </div>

                        <!-- Distribución de Género -->
                        <h6 class="fw-bold mb-3">Distribución por Género</h6>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="small">Masculino</span>
                                <span class="small fw-bold">{{ $generoMasculino }} ({{ $totalCiudadanos > 0 ? round(($generoMasculino / $totalCiudadanos) * 100, 1) : 0 }}%)</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $totalCiudadanos > 0 ? ($generoMasculino / $totalCiudadanos) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="small">Femenino</span>
                                <span class="small fw-bold">{{ $generoFemenino }} ({{ $totalCiudadanos > 0 ? round(($generoFemenino / $totalCiudadanos) * 100, 1) : 0 }}%)</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $totalCiudadanos > 0 ? ($generoFemenino / $totalCiudadanos) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        @if($generoOtros > 0)
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="small">No especificado</span>
                                <span class="small fw-bold">{{ $generoOtros }} ({{ $totalCiudadanos > 0 ? round(($generoOtros / $totalCiudadanos) * 100, 1) : 0 }}%)</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-secondary" role="progressbar" style="width: {{ ($generoOtros / $totalCiudadanos) * 100 }}%"></div>
                            </div>
                        </div>
                        @endif

                        <hr class="hr-horizontal my-4">

                        <!-- Grupos de Edad -->
                        <h6 class="fw-bold mb-3">Distribución por Ciclo de Vida</h6>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="small">Menores de edad (0 - 17 años)</span>
                                <span class="small fw-bold">{{ $menoresCount }} ({{ $totalCiudadanos > 0 ? round(($menoresCount / $totalCiudadanos) * 100, 1) : 0 }}%)</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-info" role="progressbar" style="width: {{ $totalCiudadanos > 0 ? ($menoresCount / $totalCiudadanos) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="small">Adultos (18 - 59 años)</span>
                                <span class="small fw-bold">{{ $adultosCount }} ({{ $totalCiudadanos > 0 ? round(($adultosCount / $totalCiudadanos) * 100, 1) : 0 }}%)</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $totalCiudadanos > 0 ? ($adultosCount / $totalCiudadanos) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="small">Adultos Mayores / Abuelos (60+ años)</span>
                                <span class="small fw-bold">{{ $abuelosCount }} ({{ $totalCiudadanos > 0 ? round(($abuelosCount / $totalCiudadanos) * 100, 1) : 0 }}%)</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $totalCiudadanos > 0 ? ($abuelosCount / $totalCiudadanos) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Académico e Socioeconómico -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100" style="border-radius: 15px;">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <h5 class="card-title fw-bold text-dark mb-0">Nivel Académico e Indicadores Socioeconómicos</h5>
                    </div>
                    <div class="card-body px-4 pb-4">
                        
                        <!-- Nivel Académico -->
                        <h6 class="fw-bold mb-3">Nivel Académico Alcanzado</h6>
                        @foreach($niveles as $nivel => $count)
                            <div class="mb-2">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="small">{{ $nivel }}</span>
                                    <span class="small fw-bold">{{ $count }}</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-secondary" role="progressbar" style="width: {{ $totalCiudadanos > 0 ? ($count / $totalCiudadanos) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        @endforeach

                        <hr class="hr-horizontal my-4">

                        <!-- Indicadores Socioeconómicos -->
                        <h6 class="fw-bold mb-3">Cobertura de Beneficios y Apoyo Social</h6>
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="p-3 border rounded text-center">
                                    <span class="small text-muted d-block mb-1">Reciben CLAP</span>
                                    <span class="fw-bold fs-5 text-success">{{ $recibeClapCount }}</span>
                                    <span class="text-muted d-block small">({{$totalCiudadanos > 0 ? round(($recibeClapCount/$totalCiudadanos)*100, 1) : 0}}%)</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 border rounded text-center">
                                    <span class="small text-muted d-block mb-1">Bono Familiar</span>
                                    <span class="fw-bold fs-5 text-success">{{ $bonoFamiliarCount }}</span>
                                    <span class="text-muted d-block small">({{$totalCiudadanos > 0 ? round(($bonoFamiliarCount/$totalCiudadanos)*100, 1) : 0}}%)</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 border rounded text-center">
                                    <span class="small text-muted d-block mb-1">Casa de Alimentación</span>
                                    <span class="fw-bold fs-5 text-info">{{ $casaAlimentacionCount }}</span>
                                    <span class="text-muted d-block small">({{$totalCiudadanos > 0 ? round(($casaAlimentacionCount/$totalCiudadanos)*100, 1) : 0}}%)</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 border rounded text-center">
                                    <span class="small text-muted d-block mb-1">Vivienda Propia</span>
                                    <span class="fw-bold fs-5 text-primary">{{ $viviendas['Propia'] }}</span>
                                    <span class="text-muted d-block small">({{$totalCiudadanos > 0 ? round(($viviendas['Propia']/$totalCiudadanos)*100, 1) : 0}}%)</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <!-- Enfermedades Frecuentes -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm" style="border-radius: 15px;">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                        <h5 class="card-title fw-bold text-dark mb-0">Enfermedades Frecuentes en la Comunidad</h5>
                        <p class="text-muted small mb-0">Las enfermedades con mayor frecuencia reportadas en el censo.</p>
                    </div>
                    <div class="card-body p-4">
                        @if($enfermedades->count() > 0)
                            <div class="row g-3">
                                @foreach($enfermedades as $enf)
                                    <div class="col-md-4 col-sm-6">
                                        <div class="d-flex justify-content-between align-items-center p-3 border rounded-pill bg-light">
                                            <span class="fw-semibold small text-truncate" style="max-width: 80%;">{{ $enf->tipo_enfermedad }}</span>
                                            <span class="badge bg-danger rounded-pill">{{ $enf->total }} casos</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-center text-muted py-3 mb-0">No se registran ciudadanos con enfermedades en el grupo consultado.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
