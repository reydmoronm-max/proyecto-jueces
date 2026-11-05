<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>{{ $tituloListado }}</title>
    <style>
        @page {
            size: landscape;
            margin: 18px 20px 20px 20px;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 8.5px;
            color: #1e293b;
            margin: 0;
            padding: 0;
            line-height: 1.25;
        }

        .header-wrapper {
            border-bottom: 2px solid #3a57e8;
            padding-bottom: 5px;
            margin-bottom: 8px;
        }

        .gobierno-tag {
            font-size: 7.5px;
            font-weight: bold;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 0.5px;
            margin-bottom: 1px;
        }

        .main-title {
            font-size: 13.5px;
            font-weight: bold;
            color: #1e293b;
            margin: 0;
        }

        .main-subtitle {
            font-size: 8.5px;
            color: #475569;
            margin: 0;
        }

        .meta-box {
            width: 100%;
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            padding: 4px 8px;
            margin-bottom: 8px;
        }

        .meta-box table {
            width: 100%;
            border-collapse: collapse;
        }

        .meta-box td {
            font-size: 8.5px;
            vertical-align: middle;
            border: none;
            padding: 1px 0;
        }

        .badge-comunidad {
            background-color: #e0e7ff;
            color: #3730a3;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 8.5px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .data-table th {
            background-color: #1e293b;
            color: #ffffff;
            font-size: 7.5px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 4px 3px;
            border: 1px solid #0f172a;
            text-align: left;
        }

        .data-table td {
            font-size: 7.5px;
            padding: 4px 3px;
            border: 1px solid #cbd5e1;
            vertical-align: middle;
        }

        .data-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }

        .checkbox-cell {
            width: 65px;
            text-align: center;
            border: 1px solid #94a3b8;
            background-color: #ffffff !important;
        }

        .signatures-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .signatures-table td {
            width: 33.33%;
            text-align: center;
            vertical-align: top;
            padding: 0 15px;
            border: none;
        }

        .signature-line {
            border-top: 1px solid #475569;
            margin-top: 25px;
            padding-top: 3px;
            font-size: 8px;
            color: #334155;
            font-weight: bold;
        }

        .signature-sub {
            font-size: 7px;
            color: #64748b;
        }

        .footer {
            margin-top: 10px;
            padding-top: 4px;
            border-top: 1px dashed #cbd5e1;
            font-size: 7px;
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
                    <div class="gobierno-tag">Sistema Integral de Gestión Comunal &bull; Registro Operativo de Campo</div>
                    <h1 class="main-title">{{ $tituloListado }}</h1>
                    <p class="main-subtitle">{{ $subtituloListado ?? 'Planilla de control nominal y despliegue comunitario' }}</p>
                </td>
                <td style="width: 180px; text-align: right; vertical-align: top;">
                    <div style="font-size: 7.5px; color: #64748b;">FECHA DE IMPRESIÓN</div>
                    <div style="font-size: 10px; font-weight: bold; color: #3a57e8;">{{ $fechaReporte }}</div>
                    <div style="font-size: 7.5px; color: #64748b; margin-top: 1px;">Total: {{ $personas->count() }} ciudadanos</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Contexto del Filtro -->
    <div class="meta-box">
        <table>
            <tr>
                <td>
                    <span style="color: #64748b;">ÁMBITO TERRITORIAL:</span>
                    <span class="badge-comunidad">
                        {{ $comunidadSeleccionada ? $comunidadSeleccionada->nombre : 'Todas las Comunidades (General)' }}
                    </span>
                    @if ($genero)
                        <span style="margin-left: 8px; color: #64748b;">Género:</span> <strong>{{ $genero }}</strong>
                    @endif
                    @if ($enfermedadFiltro)
                        <span style="margin-left: 8px; color: #64748b;">Patología:</span> <strong>{{ $enfermedadFiltro }}</strong>
                    @endif
                    @if ($profesionFiltro)
                        <span style="margin-left: 8px; color: #64748b;">Profesión:</span> <strong>{{ $profesionFiltro }}</strong>
                    @endif
                </td>
                <td style="text-align: right;">
                    <span style="color: #64748b;">REGISTROS LISTADOS:</span>
                    <strong style="color: #0f172a;">{{ $personas->count() }} personas</strong>
                </td>
            </tr>
        </table>
    </div>

    <!-- Tabla de Personas -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 3%; text-align: center;">#</th>
                <th style="width: 9%;">Cédula</th>
                <th style="width: 18%;">Nombres y Apellidos</th>
                <th style="width: 6%; text-align: center;">Género</th>
                <th style="width: 5%; text-align: center;">Edad</th>
                <th style="width: 9%;">Teléfono</th>
                <th style="width: 16%;">Comunidad / Dirección</th>
                @if ($listadoTipo === 'electoral')
                    <th style="width: 22%;">Centro de Votación</th>
                @elseif ($listadoTipo === 'salud')
                    <th style="width: 22%;">Patología / Condición Médica</th>
                @elseif ($listadoTipo === 'abuelos')
                    <th style="width: 22%;">Pensión / Salud</th>
                @elseif ($listadoTipo === 'estudiantes')
                    <th style="width: 22%;">Nivel Académico / Estudia</th>
                @elseif ($listadoTipo === 'talentos')
                    <th style="width: 22%;">Profesión u Oficio</th>
                @else
                    <th style="width: 22%;">Detalle</th>
                @endif
                <th style="width: 12%; text-align: center;">Firma / Verificación</th>
            </tr>
        </thead>
        <tbody>
            @forelse($personas as $idx => $persona)
                @php
                    $edad = $persona->edad;
                    $ccNombre = $persona->familia?->consejoComunal?->nombre ?? 'N/A';
                    $dir = $persona->direccion ?? ($persona->familia?->direccion ?? '');
                @endphp
                <tr>
                    <td style="text-align: center; font-weight: bold;">{{ $idx + 1 }}</td>
                    <td><strong>{{ $persona->cedula_tipo ?? 'V' }}-{{ $persona->cedula ?? 'S/C' }}</strong></td>
                    <td><strong>{{ $persona->nombres }} {{ $persona->apellidos }}</strong></td>
                    <td style="text-align: center;">{{ $persona->genero }}</td>
                    <td style="text-align: center;">{{ $edad !== null ? $edad . 'a' : 'S/F' }}</td>
                    <td>{{ $persona->telefono ?? 'Sin teléfono' }}</td>
                    <td>
                        <strong>{{ $ccNombre }}</strong>
                        @if ($dir)
                            <div style="font-size: 6.5px; color: #64748b;">{{ \Illuminate\Support\Str::limit($dir, 40) }}</div>
                        @endif
                    </td>

                    @if ($listadoTipo === 'electoral')
                        <td>{{ $persona->centro_votacion ?? 'No registrado' }}</td>
                    @elseif ($listadoTipo === 'salud')
                        <td style="color: #991b1b; font-weight: bold;">{{ $persona->tipo_enfermedad ?? 'No indica' }}</td>
                    @elseif ($listadoTipo === 'abuelos')
                        <td>
                            Pensión: <strong>{{ $persona->pensionado_jubilado === 'Sí' ? 'Sí' : 'No' }}</strong>
                            @if ($persona->tipo_enfermedad && !in_array(strtolower($persona->tipo_enfermedad), ['ninguna', 'no aplica', 'sano']))
                                | <span style="color: #991b1b;">{{ $persona->tipo_enfermedad }}</span>
                            @endif
                        </td>
                    @elseif ($listadoTipo === 'estudiantes')
                        <td>
                            {{ $persona->nivel_academico ?? 'No indica' }}
                            @if ($persona->estudia === 'Sí')
                                <strong style="color: #166534;">(Activo)</strong>
                            @endif
                        </td>
                    @elseif ($listadoTipo === 'talentos')
                        <td>
                            <strong>{{ $persona->profesion ?? 'No indica' }}</strong>
                            @if ($persona->situacion_laboral)
                                ({{ $persona->situacion_laboral }})
                            @endif
                        </td>
                    @else
                        <td>-</td>
                    @endif

                    <td class="checkbox-cell"></td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 12px; color: #64748b;">
                        No se encontraron ciudadanos para este criterio de listado.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Firmas -->
    <table class="signatures-table">
        <tr>
            <td>
                <div class="signature-line">VOCERO/A RESPONSABLE DE LA JORNADA</div>
                <div class="signature-sub">Nombre, C.I. y Teléfono</div>
            </td>
            <td>
                <div class="signature-line">COORDINACIÓN DEL CONSEJO COMUNAL</div>
                <div class="signature-sub">Validación Comunitaria / Sello</div>
            </td>
            <td>
                <div class="signature-line">JUEZ DE PAZ COMUNAL</div>
                <div class="signature-sub">Atestación Oficial</div>
            </td>
        </tr>
    </table>

    <div class="footer">
        Planilla oficial generada por el Sistema de Justicia de Paz y Gestión Comunal para control de campo, despliegue operativo y validación ciudadana.
    </div>
</body>

</html>

