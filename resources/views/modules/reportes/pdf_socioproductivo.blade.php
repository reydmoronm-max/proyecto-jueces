<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte Socio-Productivo y Educativo</title>
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
            border-bottom: 2px solid #08b1ba;
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
            color: #0e7490;
            margin: 0 0 2px 0;
        }

        .main-subtitle {
            font-size: 9px;
            color: #475569;
            margin: 0;
        }

        .meta-box {
            width: 100%;
            background-color: #ecfeff;
            border: 1px solid #cffafe;
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
            background-color: #cffafe;
            color: #0e7490;
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

        .kpi-card-cyan { border-left: 4px solid #08b1ba; }
        .kpi-card-success { border-left: 4px solid #1aa053; }
        .kpi-card-indigo { border-left: 4px solid #4f46e5; }
        .kpi-card-orange { border-left: 4px solid #f16a1b; }

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
            background-color: #0e7490;
            color: #ffffff;
            font-size: 8.5px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 5px 4px;
            border: 1px solid #155e75;
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
                    <div class="gobierno-tag">Sistema Integral de Gestión Comunal &bull; Economía Comunal y Formación</div>
                    <h1 class="main-title">REPORTE SOCIO-PRODUCTIVO Y EDUCATIVO</h1>
                    <p class="main-subtitle">Diagnóstico del potencial productivo, talento humano, ocupaciones y nivel de escolaridad comunal</p>
                </td>
                <td style="width: 180px; text-align: right; vertical-align: top;">
                    <div style="font-size: 8px; color: #64748b;">EMISIÓN OFICIAL</div>
                    <div style="font-size: 11px; font-weight: bold; color: #0e7490;">{{ $fechaReporte }}</div>
                    <div style="font-size: 8px; color: #64748b; margin-top: 2px;">Censo Comunal de Capacidades</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Contexto Territorial -->
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
                    <span style="color: #64748b; font-size: 8.5px;">UNIVERSO ANALIZADO:</span>
                    <strong style="color: #0f172a; font-size: 10.5px;">{{ $totalCiudadanos }} ciudadanos</strong>
                </td>
            </tr>
        </table>
    </div>

    <!-- KPIs Socio-Productivos -->
    <table class="kpi-table">
        <tr>
            <td class="kpi-card kpi-card-cyan" style="width: 25%;">
                <div class="kpi-title">Con Profesión u Oficio</div>
                <div class="kpi-value" style="color: #08b1ba;">{{ $totalConProfesion }}</div>
                <div style="font-size: 8px; color: #64748b;">
                    {{ $totalCiudadanos > 0 ? round(($totalConProfesion / $totalCiudadanos) * 100, 1) : 0 }}% talento activo
                </div>
            </td>
            <td class="kpi-card kpi-card-success" style="width: 25%;">
                <div class="kpi-title">Estudiantes Activos</div>
                <div class="kpi-value" style="color: #1aa053;">{{ $estudianCount }}</div>
                <div style="font-size: 8px; color: #64748b;">
                    {{ $totalCiudadanos > 0 ? round(($estudianCount / $totalCiudadanos) * 100, 1) : 0 }}% en formación
                </div>
            </td>
            <td class="kpi-card kpi-card-indigo" style="width: 25%;">
                <div class="kpi-title">Nivel Universitario / TSU</div>
                <div class="kpi-value" style="color: #4f46e5;">
                    {{ ($niveles['Superior con certificación de técnico superior'] ?? 0) + ($niveles['Pregrado universitario (licenciatura, ingeniería)'] ?? 0) + ($niveles['Posgrado universitario (maestría, doctorado, especialización)'] ?? 0) }}
                </div>
                <div style="font-size: 8px; color: #64748b;">
                    Técnicos y profesionales
                </div>
            </td>
            <td class="kpi-card kpi-card-orange" style="width: 25%;">
                <div class="kpi-title">Beneficiarios CLAP</div>
                <div class="kpi-value" style="color: #f16a1b;">{{ $recibeClapCount }}</div>
                <div style="font-size: 8px; color: #64748b;">
                    Hogares con cobertura
                </div>
            </td>
        </tr>
    </table>

    <!-- Tabla 1: Distribución por Nivel Académico -->
    <div class="section-heading">1. Distribución del Nivel de Instrucción y Escolaridad</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 50%;">Nivel de Instrucción Alcanzado</th>
                <th style="width: 25%; text-align: center;">Ciudadanos</th>
                <th style="width: 25%; text-align: right;">Porcentaje</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($niveles as $nomNivel => $cntNivel)
                @php
                    $pct = $totalCiudadanos > 0 ? round(($cntNivel / $totalCiudadanos) * 100, 1) : 0;
                @endphp
                <tr>
                    <td><strong>{{ $nomNivel }}</strong></td>
                    <td style="text-align: center; font-weight: bold; color: #0e7490;">{{ $cntNivel }}</td>
                    <td style="text-align: right; font-weight: bold; color: #64748b;">{{ $pct }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Tabla 2: Profesiones y Oficios Comunitarios -->
    <div class="section-heading">2. Banco de Profesiones, Técnicos y Oficios Comunitarios</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">#</th>
                <th style="width: 55%;">Profesión u Oficio Reportado</th>
                <th style="width: 20%; text-align: center;">Personas</th>
                <th style="width: 20%; text-align: right;">% Talento Reportado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($profesiones as $idx => $prof)
                @php
                    $pctTalento = $totalConProfesion > 0 ? round(($prof->total / $totalConProfesion) * 100, 1) : 0;
                @endphp
                <tr>
                    <td style="text-align: center; font-weight: bold;">{{ $idx + 1 }}</td>
                    <td><strong>{{ $prof->profesion }}</strong></td>
                    <td style="text-align: center; font-weight: bold; color: #08b1ba;">{{ $prof->total }}</td>
                    <td style="text-align: right; font-weight: bold; color: #64748b;">{{ $pctTalento }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 12px; color: #64748b;">
                        No se encontraron registros de profesiones u oficios en este ámbito territorial.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Tabla 3: Indicadores Habitacionales -->
    <div class="section-heading">3. Indicadores de Tenencia de Vivienda y Protección Social</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 35%;">Indicador Socio-Habitacional</th>
                <th style="width: 30%; text-align: center;">Total Registrado</th>
                <th style="width: 35%;">Observación</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Vivienda Propia</strong></td>
                <td style="text-align: center; font-weight: bold; color: #1aa053;">{{ $viviendas['Propia'] ?? 0 }} ciudadanos</td>
                <td style="color: #64748b;">Tenencia consolidada</td>
            </tr>
            <tr>
                <td><strong>Vivienda Alquilada</strong></td>
                <td style="text-align: center; font-weight: bold; color: #f16a1b;">{{ $viviendas['Alquilada'] ?? 0 }} ciudadanos</td>
                <td style="color: #64748b;">Arrendamiento vecinal</td>
            </tr>
            <tr>
                <td><strong>Vivienda Prestada / Familiar</strong></td>
                <td style="text-align: center; font-weight: bold; color: #3a57e8;">{{ $viviendas['Prestada'] ?? 0 }} ciudadanos</td>
                <td style="color: #64748b;">Atención prioritaria habitacional</td>
            </tr>
            <tr>
                <td><strong>Adjudicados Misión Vivienda (GMVV)</strong></td>
                <td style="text-align: center; font-weight: bold; color: #08b1ba;">{{ $misionViviendaCount }} ciudadanos</td>
                <td style="color: #64748b;">Programa de vivienda estatal</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Reporte generado para planificación de proyectos de desarrollo socioproductivo, conformación de brigadas de trabajo y diseño de políticas formativas en los Consejos Comunales.
    </div>
</body>

</html>

