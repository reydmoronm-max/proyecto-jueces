<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte Especializado de Salud Comunal</title>
    <style>
        @page {
            margin: 22px 25px 25px 25px;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 9.5px;
            color: #1e293b;
            margin: 0;
            padding: 0;
            line-height: 1.3;
        }

        .header-wrapper {
            border-bottom: 2px solid #c03221;
            padding-bottom: 6px;
            margin-bottom: 8px;
        }

        .gobierno-tag {
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 0.6px;
            margin-bottom: 2px;
        }

        .main-title {
            font-size: 15px;
            font-weight: bold;
            color: #991b1b;
            margin: 0 0 2px 0;
        }

        .main-subtitle {
            font-size: 9px;
            color: #475569;
            margin: 0;
        }

        .meta-box {
            width: 100%;
            background-color: #fef2f2;
            border: 1px solid #fecaca;
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
            padding: 2px 0;
        }

        .badge-pill-comunidad {
            background-color: #fee2e2;
            color: #991b1b;
            padding: 2px 8px;
            border-radius: 10px;
            font-weight: bold;
            font-size: 10px;
            display: inline-block;
        }

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
            padding: 8px 6px;
            text-align: center;
            vertical-align: top;
        }

        .kpi-card-danger { border-left: 4px solid #c03221; }
        .kpi-card-warning { border-left: 4px solid #f16a1b; }
        .kpi-card-primary { border-left: 4px solid #3a57e8; }
        .kpi-card-success { border-left: 4px solid #1aa053; }

        .kpi-title {
            font-size: 7.5px;
            text-transform: uppercase;
            font-weight: bold;
            color: #64748b;
            margin-bottom: 2px;
        }

        .kpi-value {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .section-heading {
            font-size: 11px;
            font-weight: bold;
            color: #1e293b;
            text-transform: uppercase;
            border-bottom: 1.5px solid #cbd5e1;
            padding-bottom: 4px;
            margin: 14px 0 8px 0;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .data-table th {
            background-color: #991b1b;
            color: #ffffff;
            font-size: 8.5px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 5px 4px;
            border: 1px solid #7f1d1d;
            text-align: left;
        }

        .data-table td {
            font-size: 8.5px;
            padding: 5px 4px;
            border: 1px solid #cbd5e1;
            vertical-align: middle;
        }

        .data-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }

        .progress-bar-bg {
            background-color: #e2e8f0;
            border-radius: 4px;
            height: 8px;
            width: 100%;
            overflow: hidden;
        }

        .progress-bar-fill {
            background-color: #c03221;
            height: 8px;
            border-radius: 4px;
        }

        .footer {
            margin-top: 15px;
            padding-top: 6px;
            border-top: 1px dashed #cbd5e1;
            font-size: 7.5px;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>

<body>
    <!-- Encabezado -->
    <div class="header-wrapper">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="vertical-align: top;">
                    <div class="gobierno-tag">Sistema Integral de Gestión Comunal &bull; Área de Salud y Protección Social</div>
                    <h1 class="main-title">REPORTE ESPECIALIZADO DE SALUD COMUNAL</h1>
                    <p class="main-subtitle">Diagnóstico epidemiológico comunitario, caracterización de patologías y pacientes vulnerables</p>
                </td>
                <td style="width: 180px; text-align: right; vertical-align: top;">
                    <div style="font-size: 8px; color: #64748b;">EMISIÓN OFICIAL</div>
                    <div style="font-size: 11px; font-weight: bold; color: #991b1b;">{{ $fechaReporte }}</div>
                    <div style="font-size: 8px; color: #64748b; margin-top: 2px;">Censo de Salud Comunal</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Meta Contexto -->
    <div class="meta-box">
        <table>
            <tr>
                <td>
                    <span style="color: #64748b; font-size: 8.5px;">ÁMBITO TERRITORIAL:</span>
                    <span class="badge-pill-comunidad">
                        {{ $comunidadSeleccionada ? $comunidadSeleccionada->nombre : 'Todas las Comunidades (Consolidado General)' }}
                    </span>
                </td>
                <td style="text-align: right;">
                    <span style="color: #64748b; font-size: 8.5px;">UNIVERSO POBLACIONAL ANALIZADO:</span>
                    <strong style="color: #0f172a; font-size: 10.5px;">{{ $totalCiudadanos }} ciudadanos</strong>
                </td>
            </tr>
        </table>
    </div>

    <!-- KPIs de Salud -->
    <table class="kpi-table">
        <tr>
            <td class="kpi-card kpi-card-danger" style="width: 25%;">
                <div class="kpi-title">Personas con Patología</div>
                <div class="kpi-value" style="color: #c03221;">{{ $conEnfermedadCount }}</div>
                <div style="font-size: 8px; color: #64748b;">
                    {{ $totalCiudadanos > 0 ? round(($conEnfermedadCount / $totalCiudadanos) * 100, 1) : 0 }}% de la población
                </div>
            </td>
            <td class="kpi-card kpi-card-warning" style="width: 25%;">
                <div class="kpi-title">Adultos Mayores (60+)</div>
                <div class="kpi-value" style="color: #f16a1b;">{{ $abuelosCount }}</div>
                <div style="font-size: 8px; color: #64748b;">
                    {{ $totalCiudadanos > 0 ? round(($abuelosCount / $totalCiudadanos) * 100, 1) : 0 }}% grupo prioritario
                </div>
            </td>
            <td class="kpi-card kpi-card-primary" style="width: 25%;">
                <div class="kpi-title">Pensionados / Jubilados</div>
                <div class="kpi-value" style="color: #3a57e8;">{{ $pensionadosCount }}</div>
                <div style="font-size: 8px; color: #64748b;">
                    Cobertura de pensión IVSS
                </div>
            </td>
            <td class="kpi-card kpi-card-success" style="width: 25%;">
                <div class="kpi-title">Población Sin Afecciones</div>
                <div class="kpi-value" style="color: #1aa053;">{{ max(0, $totalCiudadanos - $conEnfermedadCount) }}</div>
                <div style="font-size: 8px; color: #64748b;">
                    Condición sana reportada
                </div>
            </td>
        </tr>
    </table>

    <!-- Tabla 1: Prevalencia de Patologías -->
    <div class="section-heading">1. Cuadro de Prevalencia de Enfermedades y Patologías Reportadas</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">#</th>
                <th style="width: 45%;">Patología / Condición Médica</th>
                <th style="width: 20%; text-align: center;">Casos Diagnosticados</th>
                <th style="width: 30%;">Prevalencia sobre Población</th>
            </tr>
        </thead>
        <tbody>
            @forelse($enfermedades as $idx => $enf)
                @php
                    $pct = $totalCiudadanos > 0 ? round(($enf->total / $totalCiudadanos) * 100, 1) : 0;
                @endphp
                <tr>
                    <td style="text-align: center; font-weight: bold;">{{ $idx + 1 }}</td>
                    <td><strong>{{ $enf->tipo_enfermedad }}</strong></td>
                    <td style="text-align: center; font-weight: bold; color: #c03221;">{{ $enf->total }} ciudadanos</td>
                    <td>
                        <table style="width: 100%; border-collapse: collapse; border: none;">
                            <tr>
                                <td style="width: 75%; border: none; padding: 0;">
                                    <div class="progress-bar-bg">
                                        <div class="progress-bar-fill" style="width: {{ min(100, $pct * 3) }}%;"></div>
                                    </div>
                                </td>
                                <td style="width: 25%; border: none; padding: 0 0 0 5px; font-weight: bold; font-size: 8px; color: #64748b;">
                                    {{ $pct }}%
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 12px; color: #64748b;">
                        No se registran ciudadanos con patologías crónicas en el filtro seleccionado.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Tabla 2: Listado Prioritario de Pacientes -->
    <div class="section-heading">2. Padrón Prioritario de Pacientes con Atención Médica Requerida</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%; text-align: center;">#</th>
                <th style="width: 14%;">Cédula</th>
                <th style="width: 24%;">Nombres y Apellidos</th>
                <th style="width: 8%; text-align: center;">Edad</th>
                <th style="width: 24%;">Patología(s) Reportada(s)</th>
                <th style="width: 14%;">Teléfono</th>
                <th style="width: 12%;">Comunidad</th>
            </tr>
        </thead>
        <tbody>
            @forelse($listadoSalud->take(25) as $idx => $persona)
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td><strong>{{ $persona->cedula_tipo ?? 'V' }}-{{ $persona->cedula ?? 'S/C' }}</strong></td>
                    <td>{{ $persona->nombres }} {{ $persona->apellidos }}</td>
                    <td style="text-align: center;">{{ $persona->edad ?? 'S/F' }} años</td>
                    <td style="color: #991b1b; font-weight: bold;">{{ $persona->tipo_enfermedad }}</td>
                    <td>{{ $persona->telefono ?? 'Sin teléfono' }}</td>
                    <td>{{ $persona->familia?->consejoComunal?->nombre ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 10px; color: #64748b;">
                        No existen registros de salud para mostrar en este ámbito.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Documento generado para fines de coordinación de salud comunitaria, comités de salud de los Consejos Comunales y articulación con ASIC y Centros de Diagnóstico Integral (CDI).
    </div>
</body>

</html>

