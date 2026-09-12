<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de Búsqueda Avanzada del Censo</title>
    <style>
        @page {
            margin: 25px 30px;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            color: #222;
            margin: 0;
            padding: 0;
            line-height: 1.3;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #003366;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }

        .gobierno {
            font-size: 8.5px;
            font-weight: bold;
            text-transform: uppercase;
            color: #444;
            letter-spacing: 0.8px;
            line-height: 1.2;
        }

        .title {
            font-size: 14px;
            font-weight: bold;
            color: #003366;
            margin-top: 6px;
            margin-bottom: 2px;
            text-transform: uppercase;
        }

        .subtitle {
            font-size: 9px;
            color: #666;
        }

        .meta-box {
            width: 100%;
            margin-bottom: 12px;
            border-collapse: collapse;
        }

        .meta-box td {
            padding: 3px 6px;
            font-size: 9px;
            border: 1px solid #e0e0e0;
            background-color: #f9f9f9;
        }

        .filtros-box {
            width: 100%;
            background-color: #f4f8fb;
            border: 1px solid #bce8f1;
            border-left: 4px solid #003366;
            padding: 8px 10px;
            margin-bottom: 14px;
        }

        .filtros-title {
            font-weight: bold;
            color: #003366;
            font-size: 9.5px;
            margin-bottom: 4px;
            text-transform: uppercase;
        }

        .filtros-list {
            margin: 0;
            padding-left: 15px;
        }

        .filtros-list li {
            font-size: 8.5px;
            color: #333;
            margin-bottom: 2px;
        }

        .metrics-wrapper {
            width: 100%;
            margin-bottom: 14px;
        }

        .metric-card {
            width: 17.3%;
            border: 1px solid #ccc;
            border-top: 3px solid #003366;
            background-color: #fff;
            padding: 6px 4px;
            text-align: center;
            display: inline-block;
            margin-right: 1.2%;
            vertical-align: top;
            box-sizing: border-box;
        }

        .metric-card-success {
            border-top-color: #28a745;
        }

        .metric-card-info {
            border-top-color: #17a2b8;
        }

        .metric-card-warning {
            border-top-color: #ffc107;
        }

        .metric-card-danger {
            border-top-color: #dc3545;
        }

        .metric-title {
            font-size: 7.5px;
            text-transform: uppercase;
            color: #555;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .metric-value {
            font-size: 14px;
            font-weight: bold;
            color: #111;
        }

        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #003366;
            border-bottom: 1.5px solid #003366;
            padding-bottom: 3px;
            margin-top: 10px;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        table.data-table th {
            background-color: #003366;
            color: white;
            font-size: 8.5px;
            font-weight: bold;
            text-align: left;
            padding: 5px 6px;
            border: 1px solid #002244;
            text-transform: uppercase;
        }

        table.data-table td {
            padding: 4px 6px;
            border: 1px solid #ddd;
            font-size: 8.5px;
            vertical-align: middle;
        }

        table.data-table tr:nth-child(even) {
            background-color: #fbfbfb;
        }

        .badge-pill {
            display: inline-block;
            padding: 1px 4px;
            font-size: 7.5px;
            font-weight: bold;
            border-radius: 3px;
            background-color: #eee;
            color: #333;
        }

        .badge-primary {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .badge-success {
            background-color: #dcfce7;
            color: #166534;
        }

        .badge-info {
            background-color: #e0f2fe;
            color: #0369a1;
        }

        .badge-warning {
            background-color: #fef3c7;
            color: #92400e;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #888;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }
    </style>
</head>

<body>
    {{-- Encabezado Institucional --}}
    <div class="header">
        <div class="gobierno">
            República Bolivariana de Venezuela<br>
            Ministerio del Poder Popular para las Comunas y los Movimientos Sociales<br>
            Sistema Comunal de Gestión y Censo Ciudadano
        </div>
        <div class="title">Reporte de Búsqueda Avanzada del Censo</div>
        <div class="subtitle">Resultado consolidado de ciudadanos según parámetros multicriterio</div>
    </div>

    {{-- Metadatos del Reporte --}}
    <table class="meta-box">
        <tr>
            <td width="30%"><strong>Fecha de Emisión:</strong> {{ $fechaReporte }}</td>
            <td width="45%"><strong>Generado por:</strong> {{ $usuarioGenerador }}</td>
            <td width="25%" align="right"><strong>Total Coincidencias:</strong> {{ $metricas['total'] }}</td>
        </tr>
    </table>

    {{-- Resumen de Filtros Aplicados --}}
    <div class="filtros-box">
        <div class="filtros-title"><i class="ri-filter-fill"></i> Parámetros de Búsqueda Aplicados:</div>
        <ul class="filtros-list">
            @foreach($filtros as $f)
                <li>{{ $f }}</li>
            @endforeach
        </ul>
    </div>

    {{-- Métricas Estadísticas --}}
    <div class="metrics-wrapper">
        <div class="metric-card">
            <div class="metric-title">Total Ciudadanos</div>
            <div class="metric-value">{{ $metricas['total'] }}</div>
        </div>
        <div class="metric-card metric-card-info">
            <div class="metric-title">Masculinos</div>
            <div class="metric-value">{{ $metricas['masculinos'] }}</div>
        </div>
        <div class="metric-card metric-card-success">
            <div class="metric-title">Femeninos</div>
            <div class="metric-value">{{ $metricas['femeninos'] }}</div>
        </div>
        <div class="metric-card metric-card-warning">
            <div class="metric-title">Menores (&lt;18)</div>
            <div class="metric-value">{{ $metricas['menores18'] }}</div>
        </div>
        <div class="metric-card metric-card-danger" style="margin-right: 0;">
            <div class="metric-title">Adultos Mayores (60+)</div>
            <div class="metric-value">{{ $metricas['adultosMayores60'] }}</div>
        </div>
    </div>

    {{-- Listado de Ciudadanos --}}
    <div class="section-title">Listado de Ciudadanos Censados ({{ $resultados->count() }})</div>

    @if($resultados->isEmpty())
        <p style="text-align: center; color: #888; padding: 20px;">No se encontraron ciudadanos que coincidan con los parámetros especificados.</p>
    @else
        <table class="data-table">
            <thead>
                <tr>
                    <th width="4%">#</th>
                    <th width="12%">Cédula</th>
                    <th width="24%">Apellidos y Nombres</th>
                    <th width="10%">Edad / Género</th>
                    <th width="22%">Comunidad / Familia</th>
                    <th width="16%">Profesión / Nivel</th>
                    <th width="12%">Beneficios / Cond.</th>
                </tr>
            </thead>
            <tbody>
                @foreach($resultados as $idx => $ciudadano)
                    @php
                        $edad = $ciudadano->edad ?? ($ciudadano->fecha_nacimiento ? \Carbon\Carbon::parse($ciudadano->fecha_nacimiento)->age : null);
                        $ccNombre = $ciudadano->familia?->consejoComunal?->nombre ?? 'Sin comunidad';
                        $famNum = $ciudadano->familia?->numero_familia ?? 'S/F';
                    @endphp
                    <tr>
                        <td align="center">{{ $idx + 1 }}</td>
                        <td><strong>{{ $ciudadano->cedula_tipo }}-{{ number_format($ciudadano->cedula, 0, ',', '.') }}</strong></td>
                        <td>
                            <strong>{{ $ciudadano->apellidos }}, {{ $ciudadano->nombres }}</strong>
                            @if($ciudadano->parentesco === 'Jefe de familia')
                                <br><span class="badge-pill badge-warning">Jefe de Familia</span>
                            @endif
                            @if($ciudadano->consejosComunales->isNotEmpty())
                                <br><span class="badge-pill badge-primary">Jefe de Comando</span>
                            @endif
                            @if($ciudadano->vocerias->isNotEmpty())
                                <br><span class="badge-pill badge-info">Vocero Comunal</span>
                            @endif
                        </td>
                        <td>
                            {{ $edad !== null ? $edad . ' años' : 'S/R' }}<br>
                            <span style="color: #666;">{{ $ciudadano->genero ?? 'S/R' }}</span>
                        </td>
                        <td>
                            <strong>{{ $ccNombre }}</strong><br>
                            <span style="color: #555;">Fam: {{ $famNum }} ({{ $ciudadano->parentesco ?? 'Miembro' }})</span>
                        </td>
                        <td>
                            <strong>{{ $ciudadano->profesion ?: 'No registrada' }}</strong><br>
                            <span style="color: #666;">{{ $ciudadano->nivel_academico ?: 'N/R' }}</span>
                        </td>
                        <td>
                            @if($ciudadano->pensionado_jubilado === 'Sí')
                                <span class="badge-pill badge-primary">Pensionado</span>
                            @endif
                            @if($ciudadano->familia?->mision_vivienda === 'Sí')
                                <span class="badge-pill badge-info">GMVV</span>
                            @endif
                            @if($ciudadano->familia?->clap === 'Sí')
                                <span class="badge-pill badge-success">CLAP</span>
                            @endif
                            @if($ciudadano->pensionado_jubilado !== 'Sí' && $ciudadano->familia?->mision_vivienda !== 'Sí' && $ciudadano->familia?->clap !== 'Sí')
                                <span style="color: #888;">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        
    </div>
</body>

</html>
