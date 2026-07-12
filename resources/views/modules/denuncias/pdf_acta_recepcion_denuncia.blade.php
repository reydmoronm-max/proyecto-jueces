<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acta de Recepción de Denuncia</title>
    <style>
        @page {
            margin: 0px;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 14px;
            color: #000;
            line-height: 2.0;
            padding: 80px 80px 40px 80px;
        }
        .membrete{
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 4px;
            margin-top: 20px;
            line-height: 1.4;
        }
        .header {
            margin-top: 20px;
            text-align: left;
        }
        .header-item {
            font-size: 13px;
            margin-bottom: 4px;
        }
        .header-label {
            font-weight: bold;
        }
        .title {
            text-align: center;
            font-size: 16px;
            font-style: italic;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 20px;
            text-decoration: none;
        }
        .body-text {
            text-align: justify;
            text-indent: 50px;
            margin-bottom: 30px;
        }
        .expedition-text {
            text-align: justify;
            text-indent: 50px;
            margin-bottom: 90px;
        }
        .signature-section {
            text-align: center;
            margin-top: 60px;
            line-height: 1.5;
        }
        .signature-line {
            width: 150px;
            border-bottom: 1.5px solid #000;
            margin: 0 auto 15px auto;
        }
        .signature-name {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 3px;
            text-transform: uppercase;
        }
        .signature-ci {
            font-size: 13px;
            margin-bottom: 3px;
        }
        .signature-title {
            font-weight: bold;
            font-size: 13px;
            letter-spacing: 0.5px;
        }
        .highlight {
            font-weight: bold;
        }
        u {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
        <tr>
            <!-- Left Logo -->
            <td style="width: 33.33%; text-align: left; vertical-align: middle;">
                <img width="150px" height="60px" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/tsj.png'))) }}" alt="Logo TSJ">
            </td>
            
            <!-- Center Logo -->
            <td style="width: 33.33%; text-align: center; vertical-align: middle;">
                <img width="90px" height="80px" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/jpc.png'))) }}" alt="Logo TSJ">
            </td>
            
            <!-- Right Logo -->
            <td style="width: 33.33%; text-align: right; vertical-align: middle;">
                <img width="120px" height="60px" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/jueces.png'))) }}" alt="Logo TSJ">
            </td>
        </tr>
    </table>

    <div class="membrete">
        REPUBLICA BOLIVARIANA DE VENEZUELA
        <br>JUZGADO DE PAZ COMUNAL
        <br>COMUNA VALLES DE BRUZUAL
    </div>

    <!-- Encabezado formal -->
    <div class="header">
        <div class="header-item"><span class="header-label">COMUNIDAD:</span> <u><span class="highlight">{{ mb_ucfirst($denunciante->consejoComunal->nombre) }}</span></u>.</div>
    </div>

    <!-- Título del documento -->
    <div class="title">
        ACTA DE RECEPCION DE DENUNCIAS
    </div>

    <!-- Párrafo del Cuerpo -->
    <div class="body-text">
        En el día de hoy, <u><span class="highlight">{{ $dia }}</span></u>, de <u><span class="highlight">{{ mb_ucfirst($mes) }}</span></u> del año <u><span class="highlight">{{ $anio }}</span></u>
        siendo las <u><span class="highlight">{{ $hora }}</span></u>, comparecen ante este Juzgado De Paz Comunal, la (los), ciudadanos, (as), <u><span class="highlight">{{ mb_strtoupper($denunciante->nombres) }} 
        {{ mb_strtoupper($denunciante->apellidos) }}</span></u>, de nacionalidad <u class="highlight">Venezolana</u>, titular de la cédula de identidad N° V-<u><span class="highlight">{{ $denunciante->cedula }}</span></u>,
        Jurídicamente hábil, domiciliado(as) en <u><span class="highlight">{{ $denunciante->direccion }}</span></u>, con Jurisdicción en el Municipio <u class="highlight">Bruzual</u>, Parroquia <u class="highlight">Chivacoa</u>,
        Estado <u class="highlight">Yaracuy</u>, Teléfono <u><span class="highlight">{{ $denunciante->telefono }}</span></u>, en adelante el, (los) requirente, siendo atendido (as), por el (los), ciudadano(a), <u class="highlight">{{ mb_strtoupper($nombreJuez) }}</u>
        <u><span class="highlight">{{ mb_strtoupper($apellidoJuez) }}</span></u>, venezolano, (as), titular de la cédula de identidad N° V-<u><span class="highlight">{{ $cedulaJuez }}</span></u>, jurídicamente hábiles y de este domicilio, actuando en carácter
        de jueces de paz comunal, debidamente facultados para tomar denuncias y actuar como mediadores u conciliadores por esta comuna, para ello se deja constancia escrita, de las narrativas de los hechos que presentara el requirente para formular la problemática existente
        entre los particulares que más adelante se especificaran Transcripción que se realiza tomando en cuenta las disposiciones de los artículos 253 y 258 de la Constitución de la República Bolivariana de Venezuela, y en concordancia con los artículos 2, 3, 8, 12 de la
        Ley Orgánica De Justicia De Paz Comunal.
    </div>

    <div class="body-text">En virtud de lo antes expuesto, por medio de la presente, se deja constancia que el día de hoy, la (los), ciudadanos antes identificados exponen:</div>

    <div class="title">
        HECHOS
    </div>

    Requirente (s),<br>
    <div class="body-text">
        <u>{{ $requirente }}</u>, es todo.
    </div>

    Seguidamente el receptor, una vez escuchado y analizados los hechos expone: 
    <div class="body-text">
        <u>{{ $receptor }}</u>.
    </div>

    <div class="body-text">
        <i>Acuerdos</i>, <u>{{ $acuerdos }}</u>.
    </div>

    <div class="body-text" style="margin-bottom: 100px">
        Es todo, se terminó, se leyó y se firma en Chivacoa a la fecha de su recepción.
    </div>

    <!-- Firma / Pie de página -->
    <div class="signature-section" style="float: left;">
        <div class="signature-line"></div>
        <div class="signature-name">
            REQUIRENTE
        </div>
        <div class="signature-ci">
            C.I: {{ $denunciante->cedula }}
        </div>
    </div>

    <div class="signature-section" style="float: right;">
        <div class="signature-line"></div>
        <div class="signature-name">
            RECEPTOR
        </div>
        <div class="signature-ci">
            C.I: {{ $cedulaJuez }}
        </div>
    </div>

    {{--

    <!-- Párrafo de Expedición -->
    <div class="expedition-text">
        Se expide la presente a solicitud de la parte interesada, en la ciudad de Chivacoa, a los <u><span class="highlight">{{ $dia }}</span></u> días del mes de 
        <u><span class="highlight">{{ $mes }}</span></u> de <u><span class="highlight">{{ $anio }}</span></u>.
    </div>

    --}}

</body>
</html>
