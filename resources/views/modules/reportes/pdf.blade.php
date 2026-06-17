<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Demográfico del Censo</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #003366;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .gobierno {
            font-size: 10px;
            font-weight: bold;
            text-uppercase: true;
            color: #555;
            letter-spacing: 1px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            color: #003366;
            margin-top: 5px;
            margin-bottom: 2px;
        }
        .subtitle {
            font-size: 11px;
            color: #666;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #003366;
            border-bottom: 1.5px solid #003366;
            padding-bottom: 3px;
            margin-top: 25px;
            margin-bottom: 12px;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .meta-table td {
            border: none;
            padding: 4px 0;
            font-size: 11px;
        }
        .metrics-wrapper {
            width: 100%;
            margin-bottom: 20px;
        }
        .metric-card {
            width: 23%;
            border: 1px solid #ccc;
            border-top: 4px solid #003366;
            background-color: #fcfcfc;
            padding: 10px 5px;
            text-align: center;
            display: inline-block;
            margin-right: 1.5%;
            vertical-align: top;
        }
        .metric-card-success {
            border-top: 4px solid #28a745;
        }
        .metric-card-danger {
            border-top: 4px solid #dc3545;
        }
        .metric-card-warning {
            border-top: 4px solid #ffc107;
        }
        .metric-title {
            font-size: 9px;
            text-transform: uppercase;
            color: #666;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .metric-value {
            font-size: 18px;
            font-weight: bold;
            color: #111;
        }
        .metric-sub {
            font-size: 9px;
            color: #888;
            margin-top: 3px;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data-table th {
            background-color: #003366;
            color: white;
            font-size: 10px;
            font-weight: bold;
            text-align: left;
            padding: 6px 8px;
            border: 1px solid #002244;
        }
        table.data-table td {
            padding: 6px 8px;
            border: 1px solid #ddd;
            font-size: 10px;
            vertical-align: middle;
        }
        table.data-table tr:nth-child(even) {
            background-color: #f7f7f7;
        }
        .progress-bg {
            background-color: #e0e0e0;
            width: 100px;
            height: 8px;
            border-radius: 4px;
            display: inline-block;
            vertical-align: middle;
        }
        .progress-bar {
            height: 8px;
            border-radius: 4px;
            background-color: #0056b3;
        }
        .progress-bar-success {
            background-color: #28a745;
        }
        .progress-bar-info {
            background-color: #17a2b8;
        }
        .progress-bar-warning {
            background-color: #ffc107;
        }
        .footer {
            position: fixed;
            bottom: 0px;
            left: 0px;
            right: 0px;
            height: 30px;
            text-align: center;
            font-size: 9px;
            color: #aaa;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 9px;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="gobierno">República Bolivariana de Venezuela &bull; Sistema de Gestión Comunitario</div>
        <div class="title">REPORTE DEMOGRÁFICO DE CIUDADANOS</div>
        <div class="subtitle">Análisis Estadístico Consolidador del Censo Comunitario</div>
    </div>

    <!-- Metadatos del Reporte -->
    <table class="meta-table">
        <tr>
            <td style="width: 50%;"><strong>Comunidad:</strong> {{ $comunidadSeleccionada ? $comunidadSeleccionada->nombre : 'Todas las Comunidades (Censo Completo)' }}</td>
            <td style="width: 50%; text-align: right;"><strong>Fecha de Generación:</strong> {{ $fechaReporte }}</td>
        </tr>
        <tr>
            <td><strong>Parámetro de Edad Consultado:</strong> Edad {{ $customAgeOperator === 'above' ? '>= (Mayor o igual)' : '< (Menor)' }} a {{ $customAge }} años</td>
            <td style="text-align: right;"><strong>Total Población Grupo:</strong> {{ $customAgeCount }} ciudadanos</td>
        </tr>
    </table>

    <!-- Tarjetas de Métricas -->
    <div class="metrics-wrapper">
        <div class="metric-card">
            <div class="metric-title">Población Total</div>
            <div class="metric-value" style="color: #0056b3;">{{ $totalCiudadanos }}</div>
            <div class="metric-sub">Ciudadanos censados</div>
        </div>
        <div class="metric-card metric-card-success">
            <div class="metric-title">Estudiantes</div>
            <div class="metric-value" style="color: #28a745;">{{ $estudianCount }}</div>
            <div class="metric-sub">({{$totalCiudadanos > 0 ? round(($estudianCount/$totalCiudadanos)*100, 1) : 0}}% del total)</div>
        </div>
        <div class="metric-card metric-card-danger">
            <div class="metric-title">Enfermos</div>
            <div class="metric-value" style="color: #dc3545;">{{ $conEnfermedadCount }}</div>
            <div class="metric-sub">({{$totalCiudadanos > 0 ? round(($conEnfermedadCount/$totalCiudadanos)*100, 1) : 0}}% del censo)</div>
        </div>
        <div class="metric-card metric-card-warning">
            <div class="metric-title">Jubilados / Pens.</div>
            <div class="metric-value" style="color: #ea6a12;">{{ $pensionadosCount }}</div>
            <div class="metric-sub">({{$totalCiudadanos > 0 ? round(($pensionadosCount/$totalCiudadanos)*100, 1) : 0}}% del censo)</div>
        </div>
    </div>

    <!-- Sección 1: Género y Edad -->
    <div class="section-title">Distribución por Género y Ciclo de Vida</div>
    <table style="width: 100%; border: none;">
        <tr>
            <td style="width: 48%; border: none; padding: 0; padding-right: 2%; vertical-align: top;">
                <h4 style="margin: 0 0 10px 0; color: #003366;">Género</h4>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Género</th>
                            <th>Casos</th>
                            <th>Porcentaje</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Masculino</td>
                            <td>{{ $generoMasculino }}</td>
                            <td>{{ $totalCiudadanos > 0 ? round(($generoMasculino / $totalCiudadanos) * 100, 1) : 0 }}%</td>
                        </tr>
                        <tr>
                            <td>Femenino</td>
                            <td>{{ $generoFemenino }}</td>
                            <td>{{ $totalCiudadanos > 0 ? round(($generoFemenino / $totalCiudadanos) * 100, 1) : 0 }}%</td>
                        </tr>
                        @if($generoOtros > 0)
                        <tr>
                            <td>No especificado</td>
                            <td>{{ $generoOtros }}</td>
                            <td>{{ $totalCiudadanos > 0 ? round(($generoOtros / $totalCiudadanos) * 100, 1) : 0 }}%</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </td>
            <td style="width: 50%; border: none; padding: 0; vertical-align: top;">
                <h4 style="margin: 0 0 10px 0; color: #003366;">Ciclo de Vida</h4>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Grupo de Edad</th>
                            <th>Casos</th>
                            <th>Porcentaje</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Infancia / Juventud (0-17)</td>
                            <td>{{ $menoresCount }}</td>
                            <td>{{ $totalCiudadanos > 0 ? round(($menoresCount / $totalCiudadanos) * 100, 1) : 0 }}%</td>
                        </tr>
                        <tr>
                            <td>Adultos (18-59)</td>
                            <td>{{ $adultosCount }}</td>
                            <td>{{ $totalCiudadanos > 0 ? round(($adultosCount / $totalCiudadanos) * 100, 1) : 0 }}%</td>
                        </tr>
                        <tr>
                            <td>Adulto Mayor (60+)</td>
                            <td>{{ $abuelosCount }}</td>
                            <td>{{ $totalCiudadanos > 0 ? round(($abuelosCount / $totalCiudadanos) * 100, 1) : 0 }}%</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    <!-- Sección 2: Educación y Socioeconomía -->
    <div class="section-title">Nivel Educativo e Indicadores Socioeconómicos</div>
    <table style="width: 100%; border: none;">
        <tr>
            <td style="width: 48%; border: none; padding: 0; padding-right: 2%; vertical-align: top;">
                <h4 style="margin: 0 0 10px 0; color: #003366;">Nivel Académico</h4>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nivel</th>
                            <th>Registrados</th>
                            <th>%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($niveles as $nivel => $count)
                            <tr>
                                <td>{{ $nivel }}</td>
                                <td>{{ $count }}</td>
                                <td>{{ $totalCiudadanos > 0 ? round(($count / $totalCiudadanos) * 100, 1) : 0 }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
            <td style="width: 50%; border: none; padding: 0; vertical-align: top;">
                <h4 style="margin: 0 0 10px 0; color: #003366;">Beneficios y Apoyo Social</h4>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Indicador Socioeconómico</th>
                            <th>Sí</th>
                            <th>Cobertura (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Reciben CLAP</td>
                            <td>{{ $recibeClapCount }}</td>
                            <td>{{ $totalCiudadanos > 0 ? round(($recibeClapCount / $totalCiudadanos) * 100, 1) : 0 }}%</td>
                        </tr>
                        <tr>
                            <td>Bono Único Familiar</td>
                            <td>{{ $bonoFamiliarCount }}</td>
                            <td>{{ $totalCiudadanos > 0 ? round(($bonoFamiliarCount / $totalCiudadanos) * 100, 1) : 0 }}%</td>
                        </tr>
                        <tr>
                            <td>Casa de Alimentación</td>
                            <td>{{ $casaAlimentacionCount }}</td>
                            <td>{{ $totalCiudadanos > 0 ? round(($casaAlimentacionCount / $totalCiudadanos) * 100, 1) : 0 }}%</td>
                        </tr>
                        <tr>
                            <td>Vivienda Propia</td>
                            <td>{{ $viviendas['Propia'] }}</td>
                            <td>{{ $totalCiudadanos > 0 ? round(($viviendas['Propia'] / $totalCiudadanos) * 100, 1) : 0 }}%</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    <!-- Sección 3: Enfermedades -->
    <div class="section-title">Frecuencia de Enfermedades Reportadas</div>
    @if($enfermedades->count() > 0)
        <table class="data-table" style="width: 100%;">
            <thead>
                <tr>
                    <th>Enfermedad / Condición Especial</th>
                    <th style="text-align: center;">Total Casos Registrados</th>
                </tr>
            </thead>
            <tbody>
                @foreach($enfermedades as $enf)
                    <tr>
                        <td><strong>{{ $enf->tipo_enfermedad }}</strong></td>
                        <td style="text-align: center;"><span class="badge-danger">{{ $enf->total }} casos</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="text-align: center; color: #888; font-style: italic;">No se reportan patologías registradas en el grupo consultado.</p>
    @endif

    <div class="footer">
        Sistema de Gestión y Censo Comunitario &bull; Reporte Oficial Demográfico &bull; Página 1 de 1
    </div>

</body>
</html>
