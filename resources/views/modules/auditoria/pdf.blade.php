<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Auditoría - SGIC</title>
    <style>
        @page {
            margin: 20px 25px;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            color: #2b2b2b;
            line-height: 1.3;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #003366;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }

        .gobierno {
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            color: #555;
            letter-spacing: 0.8px;
        }

        .title {
            font-size: 15px;
            font-weight: bold;
            color: #003366;
            margin-top: 3px;
            margin-bottom: 2px;
        }

        .subtitle {
            font-size: 9px;
            color: #666;
        }

        .meta-box {
            width: 100%;
            margin-bottom: 12px;
            background-color: #f8f9fa;
            border: 1px solid #e2e8f0;
            padding: 8px;
            border-radius: 4px;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
        }

        .meta-table td {
            font-size: 9px;
            padding: 2px 4px;
            vertical-align: top;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .data-table th {
            background-color: #003366;
            color: #ffffff;
            font-size: 8.5px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 5px 4px;
            border: 1px solid #002244;
            text-align: left;
        }

        .data-table td {
            font-size: 8.5px;
            padding: 5px 4px;
            border: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        .data-table tr:nth-child(even) {
            background-color: #fbfcfe;
        }

        .badge {
            display: inline-block;
            padding: 2px 5px;
            font-size: 7.5px;
            font-weight: bold;
            border-radius: 3px;
            text-transform: uppercase;
            text-align: center;
        }

        .badge-success { background-color: #d1fae5; color: #065f46; }
        .badge-primary { background-color: #dbeafe; color: #1e40af; }
        .badge-danger  { background-color: #fee2e2; color: #991b1b; }
        .badge-info    { background-color: #e0f2fe; color: #0369a1; }
        .badge-warning { background-color: #fef3c7; color: #92400e; }
        .badge-dark    { background-color: #e2e8f0; color: #1e293b; }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #888;
            border-top: 1px solid #e2e8f0;
            padding-top: 4px;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="gobierno">REPÚBLICA BOLIVARIANA DE VENEZUELA</div>
        <div class="gobierno">SISTEMA DE GESTIÓN E INFORMACIÓN COMUNAL (SGIC)</div>
        <div class="title">REPORTE OFICIAL DE AUDITORÍA Y SUPERVISIÓN DE USUARIOS</div>
        <div class="subtitle">Historial de acciones, modificaciones en la base de datos y eventos de seguridad</div>
    </div>

    {{-- Cuadro de metadatos --}}
    <div class="meta-box">
        <table class="meta-table">
            <tr>
                <td style="width: 25%;"><strong>Fecha de Emisión:</strong> {{ $fechaImpresion }}</td>
                <td style="width: 35%;"><strong>Generado Por:</strong> {{ $generadoPor }} (Jefe de Comuna)</td>
                <td style="width: 20%;"><strong>Total Registros:</strong> {{ $audits->count() }}</td>
                <td style="width: 20%; text-align: right;"><strong>SGIC Auditoría v1.0</strong></td>
            </tr>
            @if ($request->filled('user_id') || $request->filled('modulo') || $request->filled('event') || $request->filled('fecha_desde') || $request->filled('fecha_hasta'))
                <tr>
                    <td colspan="4" style="border-top: 1px dashed #ccc; padding-top: 4px; margin-top: 4px;">
                        <strong>Filtros aplicados:</strong>
                        @if ($request->filled('event')) [Acción: {{ strtoupper($request->event) }}] @endif
                        @if ($request->filled('modulo')) [Módulo: {{ $request->modulo }}] @endif
                        @if ($request->filled('fecha_desde')) [Desde: {{ $request->fecha_desde }}] @endif
                        @if ($request->filled('fecha_hasta')) [Hasta: {{ $request->fecha_hasta }}] @endif
                        @if ($request->filled('buscar')) [Búsqueda: "{{ $request->buscar }}"] @endif
                    </td>
                </tr>
            @endif
        </table>
    </div>

    {{-- Tabla de datos --}}
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 3%; text-align: center;">#</th>
                <th style="width: 14%;">Fecha y Hora</th>
                <th style="width: 18%;">Usuario / Cédula</th>
                <th style="width: 10%;">Rol</th>
                <th style="width: 15%;">Módulo</th>
                <th style="width: 10%; text-align: center;">Acción</th>
                <th style="width: 10%;">IP</th>
                <th style="width: 20%;">Descripción</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($audits as $index => $audit)
                @php
                    $badgeClass = match($audit->event) {
                        'created' => 'badge-success',
                        'updated' => 'badge-primary',
                        'deleted' => 'badge-danger',
                        'login'   => 'badge-info',
                        'logout'  => 'badge-dark',
                        'backup_export', 'backup_import', 'backup_restore' => 'badge-warning',
                        'purge'   => 'badge-dark',
                        default   => 'badge-primary',
                    };
                @endphp
                <tr>
                    <td style="text-align: center; color: #777;">{{ $index + 1 }}</td>
                    <td>{{ $audit->created_at ? $audit->created_at->format('d/m/Y h:i:s A') : 'N/A' }}</td>
                    <td>
                        @if ($audit->user)
                            <strong>{{ $audit->user->nombre }} {{ $audit->user->apellido }}</strong><br>
                            <span style="color: #666; font-size: 7.5px;">C.I. {{ $audit->user->cedula_usuario }}</span>
                        @else
                            <span style="color: #888; font-style: italic;">Sistema</span>
                        @endif
                    </td>
                    <td>{{ $audit->user->rol ?? 'Sistema' }}</td>
                    <td>
                        <strong>{{ $audit->modulo_nombre }}</strong>
                        @if ($audit->auditable_id)
                            <br><span style="color: #666; font-size: 7.5px;">ID #{{ $audit->auditable_id }}</span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <span class="badge {{ $badgeClass }}">
                            {{ $audit->accion_nombre }}
                        </span>
                    </td>
                    <td><code>{{ $audit->ip_address ?? '127.0.0.1' }}</code></td>
                    <td>{{ $audit->descripcion_amigable }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px; color: #777;">
                        No se encontraron registros de auditoría que coincidan con los criterios establecidos.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Documento oficial generado de forma electrónica por el Sistema de Gestión e Información Comunal (SGIC) — Uso exclusivo para la Jefatura de Comuna
    </div>

</body>
</html>
