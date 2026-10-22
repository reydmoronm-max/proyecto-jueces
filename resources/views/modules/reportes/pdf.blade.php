<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte Demográfico del Censo</title>
    <style>
        @page {
            margin: 20px 22px 25px 22px;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            color: #1e293b;
            margin: 0;
            padding: 0;
            line-height: 1.3;
        }

        /* Encabezado */
        .header-wrapper {
            border-bottom: 2px solid #3a57e8;
            padding-bottom: 6px;
            margin-bottom: 8px;
        }

        .gobierno-tag {
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 0.6px;
            margin-bottom: 1px;
        }

        .main-title {
            font-size: 15px;
            font-weight: bold;
            color: #0f172a;
            margin: 0 0 1px 0;
        }

        .main-subtitle {
            font-size: 9px;
            color: #475569;
            margin: 0;
        }

        /* Tabla de Contexto */
        .meta-box {
            width: 100%;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 6px 10px;
            margin-bottom: 10px;
        }

        .meta-box table {
            width: 100%;
            border-collapse: collapse;
        }

        .meta-box td {
            font-size: 9.5px;
            vertical-align: middle;
            border: none;
            padding: 1px 0;
        }

        .badge-pill-comunidad {
            background-color: #e0e7ff;
            color: #3730a3;
            padding: 2px 7px;
            border-radius: 10px;
            font-weight: bold;
            font-size: 10px;
            display: inline-block;
        }

        /* Tarjetas de Métricas Rápidas (KPIs) */
        .kpi-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 5px 0;
            margin-bottom: 12px;
        }

        .kpi-card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 6px 4px;
            text-align: center;
            vertical-align: top;
        }

        .kpi-card-1 { border-left: 4px solid #3a57e8; }
        .kpi-card-2 { border-left: 4px solid #08b1ba; }
        .kpi-card-3 { border-left: 4px solid #1aa053; }
        .kpi-card-4 { border-left: 4px solid #c03221; }
        .kpi-card-5 { border-left: 4px solid #f16a1b; }

        .kpi-title {
            font-size: 7.5px;
            text-transform: uppercase;
            font-weight: bold;
            color: #64748b;
            margin-bottom: 2px;
        }

        .kpi-value {
            font-size: 15px;
            font-weight: bold;
            margin-bottom: 1px;
        }

        .kpi-value-1 { color: #3a57e8; }
        .kpi-value-2 { color: #08b1ba; }
        .kpi-value-3 { color: #1aa053; }
        .kpi-value-4 { color: #c03221; }
        .kpi-value-5 { color: #f16a1b; }

        .kpi-sub {
            font-size: 7.5px;
            color: #94a3b8;
        }

        /* Estructura de Secciones de Histogramas (2 columnas) */
        .section-row {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px 0;
            margin-bottom: 14px;
            page-break-inside: avoid;
        }

        .section-col {
            width: 50%;
            vertical-align: top;
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 10px;
        }

        .card-header-pdf {
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 5px;
            margin-bottom: 6px;
        }

        .card-title-pdf {
            font-size: 11px;
            font-weight: bold;
            color: #0f172a;
            margin: 0 0 1px 0;
        }

        .card-subtitle-pdf {
            font-size: 8px;
            color: #64748b;
            margin: 0;
        }

        /* HISTOGRAMAS VERTICALES (Columnas) */
        .histogram-vertical-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }

        .histogram-bar-cell {
            vertical-align: bottom;
            text-align: center;
            height: 125px;
            padding: 0 1px;
            border-bottom: 2px solid #94a3b8;
        }

        .histogram-bar-val {
            font-size: 8px;
            font-weight: bold;
            margin-bottom: 1px;
            line-height: 1;
        }

        .histogram-bar-val-primary { color: #3a57e8; }
        .histogram-bar-val-purple  { color: #4f46e5; }
        .histogram-bar-val-success { color: #1aa053; }

        .histogram-bar-v {
            margin: 0 auto;
            border-radius: 3px 3px 0 0;
        }

        .bar-v-primary { background-color: #3a57e8; }
        .bar-v-purple  { background-color: #4f46e5; }
        .bar-v-success { background-color: #1aa053; }
        .bar-v-male    { background-color: #3a57e8; }
        .bar-v-female  { background-color: #fe2f60; }
        .bar-v-empty   { background-color: #e2e8f0; height: 2px !important; }

        .histogram-label-cell {
            text-align: center;
            vertical-align: top;
            padding-top: 3px;
        }

        .histogram-label-txt {
            font-size: 7px;
            color: #334155;
            font-weight: 600;
            line-height: 1.1;
        }

        .histogram-label-pct {
            font-size: 6.5px;
            color: #64748b;
        }

        /* HISTOGRAMAS HORIZONTALES (Barras) */
        .bar-table-h {
            width: 100%;
            border-collapse: collapse;
        }

        .bar-table-h td {
            padding: 2px 0;
            vertical-align: middle;
            border: none;
        }

        .bar-label-h {
            font-size: 8.5px;
            color: #334155;
            font-weight: 500;
        }

        .bar-track-h {
            background-color: #f1f5f9;
            height: 8px;
            border-radius: 4px;
            overflow: hidden;
            width: 100%;
        }

        .bar-fill-h {
            height: 8px;
            border-radius: 4px;
        }

        .bar-fill-info   { background-color: #08b1ba; }
        .bar-fill-danger { background-color: #c03221; }

        .bar-value-h {
            font-size: 8.5px;
            font-weight: bold;
            text-align: right;
            color: #1e293b;
            padding-left: 5px;
            white-space: nowrap;
        }

        .bar-subvalue-h {
            font-size: 7.5px;
            color: #64748b;
            font-weight: normal;
        }

        /* Badges / Pills */
        .pill-tag {
            display: inline-block;
            padding: 1px 5px;
            border-radius: 6px;
            font-size: 7.5px;
            font-weight: bold;
            margin-right: 2px;
        }

        .pill-info    { background-color: #e0f7f9; color: #08b1ba; }
        .pill-primary { background-color: #e8ecff; color: #3a57e8; }
        .pill-warning { background-color: #fff3e8; color: #f16a1b; }
        .pill-danger  { background-color: #fde8e8; color: #c03221; }
        .pill-success { background-color: #e6f7ec; color: #1aa053; }

        /* Pie de página */
        .footer {
            position: fixed;
            bottom: -15px;
            left: 0;
            right: 0;
            height: 20px;
            text-align: center;
            font-size: 7.5px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 3px;
        }

        .empty-notice {
            text-align: center;
            color: #94a3b8;
            font-style: italic;
            font-size: 8.5px;
            padding: 20px 0;
        }
    </style>
</head>

<body>

    <!-- Encabezado Institucional -->
    <div class="header-wrapper">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 70%; vertical-align: middle;">
                    <div class="gobierno-tag">República Bolivariana de Venezuela &bull; Sistema de Gestión y Censo Comunitario</div>
                    <h1 class="main-title">REPORTE DEMOGRÁFICO DE CIUDADANOS</h1>
                    <p class="main-subtitle">Análisis Estadístico, Histogramas de Distribución y Censo Consolidado</p>
                </td>
                <td style="width: 30%; text-align: right; vertical-align: middle;">
                    <div style="font-size: 8px; color: #64748b;">Fecha de Emisión:</div>
                    <div style="font-size: 9.5px; font-weight: bold; color: #0f172a;">{{ $fechaReporte }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Metadatos de la Consulta -->
    <div class="meta-box">
        <table>
            <tr>
                <td style="width: 62%;">
                    <span style="font-size: 8px; color: #64748b; text-transform: uppercase; font-weight: bold; display: block; margin-bottom: 2px;">Comunidad / Consejo Comunal Consultado:</span>
                    <span class="badge-pill-comunidad">
                        {{ $comunidadSeleccionada ? $comunidadSeleccionada->nombre : 'Todas las Comunidades (Censo General Completo)' }}
                    </span>
                </td>
                <td style="width: 38%; text-align: right;">
                    <span style="font-size: 8px; color: #64748b; text-transform: uppercase; font-weight: bold; display: block; margin-bottom: 2px;">Población Analizada:</span>
                    <strong style="font-size: 13px; color: #3a57e8;">{{ $totalCiudadanos }}</strong>
                    <span style="font-size: 9px; color: #64748b;">ciudadanos censados</span>
                </td>
            </tr>
        </table>
    </div>

    <!-- Fila de Tarjetas de Métricas Rápidas (KPIs) - Idénticas a la vista web -->
    <table class="kpi-table">
        <tr>
            <!-- 1. Población Total -->
            <td class="kpi-card kpi-card-1" style="width: 20%;">
                <div class="kpi-title">Población Total</div>
                <div class="kpi-value kpi-value-1">{{ $totalCiudadanos }}</div>
                <div class="kpi-sub">Censados</div>
            </td>

            <!-- 2. Con Profesión / Oficio -->
            <td class="kpi-card kpi-card-2" style="width: 20%;">
                <div class="kpi-title">Con Profesión</div>
                <div class="kpi-value kpi-value-2">{{ $totalConProfesion }}</div>
                <div class="kpi-sub">
                    ({{ $totalCiudadanos > 0 ? round(($totalConProfesion / $totalCiudadanos) * 100, 1) : 0 }}% censo)
                </div>
            </td>

            <!-- 3. Estudiantes -->
            <td class="kpi-card kpi-card-3" style="width: 20%;">
                <div class="kpi-title">Estudiantes</div>
                <div class="kpi-value kpi-value-3">{{ $estudianCount }}</div>
                <div class="kpi-sub">
                    ({{ $totalCiudadanos > 0 ? round(($estudianCount / $totalCiudadanos) * 100, 1) : 0 }}% en form.)
                </div>
            </td>

            <!-- 4. Con Patologías -->
            <td class="kpi-card kpi-card-4" style="width: 20%;">
                <div class="kpi-title">Con Patología</div>
                <div class="kpi-value kpi-value-4">{{ $conEnfermedadCount }}</div>
                <div class="kpi-sub">
                    ({{ $totalCiudadanos > 0 ? round(($conEnfermedadCount / $totalCiudadanos) * 100, 1) : 0 }}% censo)
                </div>
            </td>

            <!-- 5. Pensionados / Jubilados -->
            <td class="kpi-card kpi-card-5" style="width: 20%;">
                <div class="kpi-title">Pensionados</div>
                <div class="kpi-value kpi-value-5">{{ $pensionadosCount }}</div>
                <div class="kpi-sub">
                    ({{ $totalCiudadanos > 0 ? round(($pensionadosCount / $totalCiudadanos) * 100, 1) : 0 }}% total)
                </div>
            </td>
        </tr>
    </table>

    <!-- FILA 1: HISTOGRAMA DE EDADES & ESTADÍSTICAS DE PROFESIONES -->
    <table class="section-row">
        <tr>
            <!-- Columna Izquierda: Histograma Vertical de Edades -->
            <td class="section-col">
                <div class="card-header-pdf">
                    <table style="width: 100%;">
                        <tr>
                            <td>
                                <div class="card-title-pdf">Histograma de Distribución de Edades</div>
                                <div class="card-subtitle-pdf">Frecuencia por intervalos etarios decenales</div>
                            </td>
                            <td style="text-align: right;">
                                <span class="pill-tag pill-primary">0-17: {{ $menoresCount }}</span>
                                <span class="pill-tag pill-primary">18-59: {{ $adultosCount }}</span>
                                <span class="pill-tag pill-primary">60+: {{ $abuelosCount }}</span>
                            </td>
                        </tr>
                    </table>
                </div>

                @php
                    $totalRef = $totalCiudadanos > 0 ? $totalCiudadanos : 1;
                    $rangosEdades = [
                        '0-9'  => $histogramaEdades['0-9'],
                        '10-19'=> $histogramaEdades['10-19'],
                        '20-29'=> $histogramaEdades['20-29'],
                        '30-39'=> $histogramaEdades['30-39'],
                        '40-49'=> $histogramaEdades['40-49'],
                        '50-59'=> $histogramaEdades['50-59'],
                        '60-69'=> $histogramaEdades['60-69'],
                        '70+'  => $histogramaEdades['70+'],
                    ];
                    $maxValEdades = max(1, max(array_values($rangosEdades)));
                @endphp

                <!-- Gráfico de Columnas (Histograma de Edades - Azul) -->
                <table class="histogram-vertical-table">
                    <tr>
                        @foreach ($rangosEdades as $rango => $val)
                            @php
                                $altPx = $val > 0 ? max(12, round(($val / $maxValEdades) * 110)) : 2;
                            @endphp
                            <td class="histogram-bar-cell">
                                @if ($val > 0)
                                    <div class="histogram-bar-val" style="color: #3a57e8;">{{ $val }}</div>
                                    <div class="histogram-bar-v" style="height: {{ $altPx }}px; width: 18px; background-color: #3a57e8;"></div>
                                @else
                                    <div class="histogram-bar-v bar-v-empty" style="width: 18px;"></div>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($rangosEdades as $rango => $val)
                            @php $pct = round(($val / $totalRef) * 100, 1); @endphp
                            <td class="histogram-label-cell">
                                <div class="histogram-label-txt">{{ $rango }}</div>
                                <div class="histogram-label-pct">{{ $pct }}%</div>
                            </td>
                        @endforeach
                    </tr>
                </table>
            </td>

            <!-- Columna Derecha: Histograma Vertical de Profesiones y Oficios (Amarillo) -->
            <td class="section-col">
                <div class="card-header-pdf">
                    <table style="width: 100%;">
                        <tr>
                            <td>
                                <div class="card-title-pdf">Histograma de Profesiones y Oficios</div>
                                <div class="card-subtitle-pdf">Ocupaciones más frecuentes en la comunidad</div>
                            </td>
                            <td style="text-align: right;">
                                <span class="pill-tag pill-warning">{{ $profesiones->count() }} actividades</span>
                            </td>
                        </tr>
                    </table>
                </div>

                @if ($profesiones->count() > 0)
                    @php
                        $topProf = $profesiones->take(6);
                        $maxProf = max(1, $topProf->max('total'));
                    @endphp
                    <table class="histogram-vertical-table">
                        <tr>
                            @foreach ($topProf as $prof)
                                @php
                                    $altPxProf = $prof->total > 0 ? max(12, round(($prof->total / $maxProf) * 110)) : 2;
                                @endphp
                                <td class="histogram-bar-cell">
                                    @if ($prof->total > 0)
                                        <div class="histogram-bar-val" style="color: #ffbe0c;">{{ $prof->total }}</div>
                                        <div class="histogram-bar-v" style="height: {{ $altPxProf }}px; width: 18px; background-color: #ffbe0c;"></div>
                                    @else
                                        <div class="histogram-bar-v bar-v-empty" style="width: 18px;"></div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        <tr>
                            @foreach ($topProf as $prof)
                                @php $pctProf = round(($prof->total / $totalRef) * 100, 1); @endphp
                                <td class="histogram-label-cell">
                                    <div class="histogram-label-txt" style="font-size: 6.5px;" title="{{ $prof->profesion }}">{{ Str::limit($prof->profesion, 9) }}</div>
                                    <div class="histogram-label-pct">{{ $pctProf }}%</div>
                                </td>
                            @endforeach
                        </tr>
                    </table>
                @else
                    <div class="empty-notice">No se registran ciudadanos con profesión u oficio registrado.</div>
                @endif
            </td>
        </tr>
    </table>

    <!-- FILA 2: NIVEL ACADÉMICO & GÉNERO POR CICLO DE VIDA -->
    <table class="section-row">
        <tr>
            <!-- Columna Izquierda: Histograma Vertical de Nivel Académico -->
            <td class="section-col">
                <div class="card-header-pdf">
                    <div class="card-title-pdf">Histograma de Nivel Académico</div>
                    <div class="card-subtitle-pdf">Instrucción escolar y universitaria alcanzada</div>
                </div>

                @php
                    $maxValNivel = max(1, max(array_values($niveles)));
                    $etiquetasCortas = [
                        'Ninguno' => 'Ninguno',
                        'Preescolar' => 'Preescolar',
                        'Primaria' => 'Primaria',
                        'Secundaria con título de bachiller' => 'Bachiller',
                        'Superior con certificación de técnico superior' => 'TSU',
                        'Pregrado universitario (licenciatura, ingeniería)' => 'Pregrado',
                        'Posgrado universitario (maestría, doctorado, especialización)' => 'Posgrado',
                        'No sabe/no responde' => 'NS/NR',
                    ];
                @endphp
                <table class="histogram-vertical-table">
                    <tr>
                        @foreach ($niveles as $nivel => $count)
                            @php
                                $altPxNiv = $count > 0 ? max(12, round(($count / $maxValNivel) * 110)) : 2;
                            @endphp
                            <td class="histogram-bar-cell">
                                @if ($count > 0)
                                    <div class="histogram-bar-val" style="color: #4f46e5;">{{ $count }}</div>
                                    <div class="histogram-bar-v" style="height: {{ $altPxNiv }}px; width: 14px; background-color: #4f46e5;"></div>
                                @else
                                    <div class="histogram-bar-v bar-v-empty" style="width: 14px;"></div>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($niveles as $nivel => $count)
                            @php $pctNiv = round(($count / $totalRef) * 100, 1); @endphp
                            <td class="histogram-label-cell">
                                <div class="histogram-label-txt" style="font-size: 6.5px;">{{ $etiquetasCortas[$nivel] ?? Str::limit($nivel, 8) }}</div>
                                <div class="histogram-label-pct">{{ $pctNiv }}%</div>
                            </td>
                        @endforeach
                    </tr>
                </table>
            </td>

            <!-- Columna Derecha: Histograma Comparativo de Género por Ciclo de Vida -->
            <td class="section-col">
                <div class="card-header-pdf">
                    <table style="width: 100%;">
                        <tr>
                            <td>
                                <div class="card-title-pdf">Género por Ciclo de Vida</div>
                                <div class="card-subtitle-pdf">Comparativa de hombres y mujeres por grupo etario</div>
                            </td>
                            <td style="text-align: right;">
                                <span class="pill-tag pill-primary">Masc: {{ $generoMasculino }}</span>
                                <span class="pill-tag pill-danger">Fem: {{ $generoFemenino }}</span>
                            </td>
                        </tr>
                    </table>
                </div>

                @php
                    $cicloGrupos = [
                        'Menores (0-17)' => ['m' => $generoPorCiclo['menores']['masculino'], 'f' => $generoPorCiclo['menores']['femenino']],
                        'Adultos (18-59)' => ['m' => $generoPorCiclo['adultos']['masculino'], 'f' => $generoPorCiclo['adultos']['femenino']],
                        'Mayores (60+)'  => ['m' => $generoPorCiclo['abuelos']['masculino'], 'f' => $generoPorCiclo['abuelos']['femenino']],
                    ];
                    $maxValGenero = 1;
                    foreach ($cicloGrupos as $g) {
                        $maxValGenero = max($maxValGenero, $g['m'], $g['f']);
                    }
                @endphp

                <!-- Gráfico de Columnas Agrupadas -->
                <table class="histogram-vertical-table">
                    <tr>
                        @foreach ($cicloGrupos as $grupoNombre => $gVals)
                            @php
                                $altM = $gVals['m'] > 0 ? max(12, round(($gVals['m'] / $maxValGenero) * 110)) : 2;
                                $altF = $gVals['f'] > 0 ? max(12, round(($gVals['f'] / $maxValGenero) * 110)) : 2;
                            @endphp
                            <td class="histogram-bar-cell" style="width: 33.3%;">
                                <table style="width: 100%; border-collapse: collapse;">
                                    <tr>
                                        <td style="width: 50%; vertical-align: bottom; text-align: center; border: none; padding: 0 1px;">
                                            @if ($gVals['m'] > 0)
                                                <div class="histogram-bar-val" style="color: #3a57e8;">{{ $gVals['m'] }}</div>
                                                <div class="histogram-bar-v bar-v-male" style="height: {{ $altM }}px; width: 14px;"></div>
                                            @else
                                                <div class="histogram-bar-v bar-v-empty" style="width: 14px;"></div>
                                            @endif
                                        </td>
                                        <td style="width: 50%; vertical-align: bottom; text-align: center; border: none; padding: 0 1px;">
                                            @if ($gVals['f'] > 0)
                                                <div class="histogram-bar-val" style="color: #fe2f60;">{{ $gVals['f'] }}</div>
                                                <div class="histogram-bar-v bar-v-female" style="height: {{ $altF }}px; width: 14px;"></div>
                                            @else
                                                <div class="histogram-bar-v bar-v-empty" style="width: 14px;"></div>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($cicloGrupos as $grupoNombre => $gVals)
                            <td class="histogram-label-cell">
                                <div class="histogram-label-txt">{{ $grupoNombre }}</div>
                                <div class="histogram-label-pct">
                                    <span style="color: #3a57e8;">M:{{ $gVals['m'] }}</span> | <span style="color: #fe2f60;">F:{{ $gVals['f'] }}</span>
                                </div>
                            </td>
                        @endforeach
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- FILA 3: BENEFICIOS / VIVIENDA & PATOLOGÍAS REPORTADAS -->
    <table class="section-row">
        <tr>
            <!-- Columna Izquierda: Histograma Vertical de Beneficios y Vivienda -->
            <td class="section-col">
                <div class="card-header-pdf">
                    <div class="card-title-pdf">Beneficios y Tenencia de Vivienda</div>
                    <div class="card-subtitle-pdf">Programas sociales e indicadores habitacionales</div>
                </div>

                @php
                    $socioItems = [
                        'CLAP'        => $recibeClapCount,
                        'Bono Fam.'   => $bonoFamiliarCount,
                        'Mis. Viv.'   => $misionViviendaCount,
                        'Propia'      => $viviendas['Propia'] ?? 0,
                        'Prestada'    => $viviendas['Prestada'] ?? 0,
                        'Alquilada'   => $viviendas['Alquilada'] ?? 0,
                    ];
                    $maxValSocio = max(1, max(array_values($socioItems)));
                @endphp

                <table class="histogram-vertical-table">
                    <tr>
                        @foreach ($socioItems as $itemNom => $valSocio)
                            @php
                                $altPxSocio = $valSocio > 0 ? max(12, round(($valSocio / $maxValSocio) * 110)) : 2;
                            @endphp
                            <td class="histogram-bar-cell">
                                @if ($valSocio > 0)
                                    <div class="histogram-bar-val" style="color: #1aa053;">{{ $valSocio }}</div>
                                    <div class="histogram-bar-v" style="height: {{ $altPxSocio }}px; width: 20px; background-color: #1aa053;"></div>
                                @else
                                    <div class="histogram-bar-v bar-v-empty" style="width: 20px;"></div>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($socioItems as $itemNom => $valSocio)
                            @php $pctS = round(($valSocio / $totalRef) * 100, 1); @endphp
                            <td class="histogram-label-cell">
                                <div class="histogram-label-txt">{{ $itemNom }}</div>
                                <div class="histogram-label-pct">{{ $pctS }}%</div>
                            </td>
                        @endforeach
                    </tr>
                </table>
            </td>

            <!-- Columna Derecha: Histograma Vertical de Patologías y Enfermedades Reportadas (Rojo) -->
            <td class="section-col">
                <div class="card-header-pdf">
                    <table style="width: 100%;">
                        <tr>
                            <td>
                                <div class="card-title-pdf">Histograma de Patologías y Enfermedades</div>
                                <div class="card-subtitle-pdf">Principales afecciones de salud reportadas</div>
                            </td>
                            <td style="text-align: right;">
                                <span class="pill-tag pill-danger">{{ $conEnfermedadCount }} casos totales</span>
                            </td>
                        </tr>
                    </table>
                </div>

                @if ($enfermedades->count() > 0)
                    @php
                        $topEnf = $enfermedades->take(6);
                        $maxEnf = max(1, $topEnf->max('total'));
                    @endphp
                    <table class="histogram-vertical-table">
                        <tr>
                            @foreach ($topEnf as $enf)
                                @php
                                    $altPxEnf = $enf->total > 0 ? max(12, round(($enf->total / $maxEnf) * 110)) : 2;
                                @endphp
                                <td class="histogram-bar-cell">
                                    @if ($enf->total > 0)
                                        <div class="histogram-bar-val" style="color: #ff2525;">{{ $enf->total }}</div>
                                        <div class="histogram-bar-v" style="height: {{ $altPxEnf }}px; width: 18px; background-color: #ff2525;"></div>
                                    @else
                                        <div class="histogram-bar-v bar-v-empty" style="width: 18px;"></div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        <tr>
                            @foreach ($topEnf as $enf)
                                <td class="histogram-label-cell">
                                    <div class="histogram-label-txt" style="font-size: 6.5px;" title="{{ $enf->tipo_enfermedad }}">{{ Str::limit($enf->tipo_enfermedad, 9) }}</div>
                                    <div class="histogram-label-pct" style="color: #ff2525; font-weight: bold;">{{ $enf->total }} cas.</div>
                                </td>
                            @endforeach
                        </tr>
                    </table>
                @else
                    <div class="empty-notice">No se reportan ciudadanos con patologías registradas en este grupo.</div>
                @endif
            </td>
        </tr>
    </table>

    <!-- Pie de Página -->
    <div class="footer">
        Sistema de Gestión y Censo Comunitario &bull; Reporte Oficial Demográfico con Histogramas Estadísticos &bull; Documento Generado Automáticamente
    </div>

</body>

</html>
