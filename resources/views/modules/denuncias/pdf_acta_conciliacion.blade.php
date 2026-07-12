<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acta de Conciliación de Denuncia</title>
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
        <div class="header-item"><span class="header-label">COMUNIDAD:</span> <u><span class="highlight">{{ mb_ucfirst($denunciante->consejoComunal->nombre ?? '') }}</span></u>.</div>
    </div>

    <!-- Título del documento -->
    <div class="title">
        ACTA DE CONCILIACIÓN Y MEDIACIÓN
    </div>

    <!-- Párrafo del Cuerpo -->
    <div class="body-text">
        En el día de hoy, <u><span class="highlight">{{ $dia }}</span></u>, de <u><span class="highlight">{{ mb_ucfirst($mes) }}</span></u> del año <u><span class="highlight">{{ $anio }}</span></u>
        siendo las <u><span class="highlight">{{ $hora }}</span></u>, comparecen ante este Juzgado De Paz Comunal, la (los), ciudadanos, (as), <u><span class="highlight">{{ mb_strtoupper($denunciante->nombres ?? '') }} 
        {{ mb_strtoupper($denunciante->apellidos ?? '') }}</span></u>, de nacionalidad <u class="highlight">Venezolana</u>, titular de la cédula de identidad N° V-<u><span class="highlight">{{ $denunciante->cedula ?? '' }}</span></u>,
        jurídicamente hábil, domiciliada, (os), <u><span class="highlight">{{ $denunciante->direccion ?? '' }}</span></u>, con jurisdicción en el Municipio, <u class="highlight">Bruzual</u>, Parroquia <u class="highlight">Chivacoa</u>,
        Estado <u class="highlight">Yaracuy</u>, Teléfono <u><span class="highlight">{{ $denunciante->telefono ?? '' }}</span></u>. En adelante (el,los), Requirentes, y (el, los), ciudadanos, (as), <u><span class="highlight">{{ mb_strtoupper($denunciado->nombres ?? '') }} 
        {{ mb_strtoupper($denunciado->apellidos ?? '') }}</span></u>, de nacionalidad <u class="highlight">Venezolana</u>, titular de la cédula de identidad N° V-<u><span class="highlight">{{ $denunciado->cedula ?? '' }}</span></u>,
        jurídicamente hábil, domiciliados (as) en, <u><span class="highlight">{{ $denunciado->direccion ?? '' }}</span></u>, con jurisdicción en el Municipio <u class="highlight">Bruzual</u>, Parroquia <u class="highlight">Chivacoa</u>,
        Estado <u class="highlight">Yaracuy</u>, Teléfono, <u><span class="highlight">{{ $denunciado->telefono ?? '' }}</span></u>, en carácter de Requerido, siendo atendido (as), por el (los), ciudadanos (a), <u class="highlight">{{ mb_strtoupper($nombreJuez) }}</u>
        <u><span class="highlight">{{ mb_strtoupper($apellidoJuez) }}</span></u>, venezolanos (as), titular de la cédula de identidad N° V-<u><span class="highlight">{{ $cedulaJuez }}</span></u>, jurídicamente hábiles y de este domicilio, actuando en carácter
        de jueces de paz comunal, debidamente facultados para tomar denuncias y actuar como mediadores y conciliadores por esta comuna, con el objeto de resolver la controversia seguida entre las partes utilizando como instrumento la mediación, conciliación, como primera alternativa,
        para ello se deja constancia escrita, en la cual se asentarán las narrativas de los hechos que presentarán las partes y que permitan llegar a los acuerdos correspondientes, para solucionar la problemática que conllevó a tomar las medidas
        conciliatorias, ante el juzgado de paz comunal, comprometiéndose así las partes en cumplir los acuerdos que aquí queden por sentado, transcripción que se realiza tomando en cuenta la disposición de los artículos, 5, 253 y 258 de la Constitución de la República Bolivariana de Venezuela,
        y con concordancia con los artículos 2, 3, 8 y 12 de la Ley Orgánica de Justicia de Paz Comunal, así mismo se le orienta a las partes que deben mantener el orden y el respeto ante los jueces de paz y entre los mismos, cualquier comportamiento indebido,
        agresiones verbales, físicas o psicológicas que se puedan presentar durante la mediación, serán tomadas como faltas graves, y causal de solicitar el apoyo de los órganos de seguridad estadal o nacional, para dar el efectivo cumplimiento
        de sus funciones así como lo establece el artículo, 8 numeral 13, de la Ley Orgánica de Justicia de Paz Comunal. 
    </div>

    <div class="body-text">En virtud de lo antes expuesto, por medio de la presente, se deja constancia que el día de hoy los ciudadanos antes identificados exponen:</div>

    <div class="title">
        HECHOS
    </div>

    REQUIRENTE:
    <div class="body-text" style="text-indent: 0; text-align: justify; margin-top: 5px; margin-bottom: 20px;">
        <u>{{ $requirente }}</u>, es todo.
    </div>

    REQUERIDO:
    <div class="body-text" style="text-indent: 0; text-align: justify; margin-top: 5px; margin-bottom: 20px;">
        <u>{{ $requerido }}</u>, es todo.
    </div>

    @if(!empty($coordinador))
    SEGUIDAMENTE EL COORDINADOR UNA VEZ ESCUCHADO LOS HECHOS EXPONE:
    <div class="body-text" style="text-indent: 0; text-align: justify; margin-top: 5px; margin-bottom: 20px;">
        <u>{{ $coordinador }}</u>
    </div>
    @endif

    ACUERDOS PARA EL CUMPLIMIENTO ACORDADO LIBRE Y VOLUNTARIO POR LAS PARTES:
    <div class="body-text" style="text-indent: 0; text-align: justify; margin-top: 5px; margin-bottom: 40px;">
        <u>{{ $acuerdos }}</u>
    </div>

    <div class="body-text" style="font-weight: bold; margin-bottom: 60px">
        Una vez llegado a los acuerdos para el cumplimiento de este, se les deja saber a las partes que se realizará el seguimiento correspondiente,
        el incumplimiento de los acuerdos establecidos serán causal de remisión ante otros organismos con competencia en la materia, es todo se terminó se leyó y se firma en Chivacoa
        a la fecha de su realización.
    </div>

    <!-- Sección de Firmas -->
    <table style="width: 100%; border-collapse: collapse; text-align: center; margin-top: 100px;">
        <tr>
            <!-- Requirente -->
            <td style="width: 33.33%; vertical-align: bottom; padding: 0 10px;">
                <div style="border-bottom: 1px solid #000; width: 85%; margin: 0 auto 10px auto;"></div>
                <div style="font-weight: bold; font-size: 11px; margin-top: 5px;">REQUIRENTE</div>
            </td>
            
            <!-- Requerido -->
            <td style="width: 33.33%; vertical-align: bottom; padding: 0 10px;">
                <div style="border-bottom: 1px solid #000; width: 85%; margin: 0 auto 10px auto;"></div>
                <div style="font-weight: bold; font-size: 11px; margin-top: 5px;">REQUERIDO</div>
            </td>
            
            <!-- Coordinador -->
            <td style="width: 33.33%; vertical-align: bottom; padding: 0 10px;">
                <div style="border-bottom: 1px solid #000; width: 85%; margin: 0 auto 10px auto;"></div>
                <div style="font-weight: bold; font-size: 11px; margin-top: 5px;">COORDINADOR</div>
            </td>
        </tr>
    </table>

    <table style="width: 100%; border-collapse: collapse; text-align: center; margin-top: 100px;">
        <tr>
            <!-- Juez Principal -->
            <td style="width: 50%; vertical-align: bottom; padding: 0 10px;">
                <div style="border-bottom: 1px solid #000; width: 60%; margin: 0 auto 10px auto;"></div>
                <div style="font-weight: bold; font-size: 11px; margin-top: 5px;">JUEZ PRINCIPAL</div>
            </td>
            <td style="width: 50%; vertical-align: bottom; padding: 0 10px;">
                <div style="border-bottom: 1px solid #000; width: 60%; margin: 0 auto 10px auto;"></div>
                <div style="font-weight: bold; font-size: 11px; margin-top: 5px;">JUEZ PRINCIPAL</div>
            </td>
        </tr>
    </table>

</body>
</html>
