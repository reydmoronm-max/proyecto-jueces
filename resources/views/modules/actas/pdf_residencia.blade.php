<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Constancia de Residencia - {{ $persona->cedula }}</title>
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
        .header {
            margin-bottom: 50px;
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
            font-size: 18px;
            font-weight: bold;
            margin-top: 60px;
            margin-bottom: 60px;
            text-decoration: none;
            letter-spacing: 1px;
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
            width: 350px;
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

    <!-- Encabezado formal -->
    <div class="header">
        <div class="header-item"><span class="header-label">CONSEJO COMUNAL:</span> <u><span class="highlight">{{ mb_strtoupper($persona->consejoComunal->nombre) }}</span></u>.</div>
        <div class="header-item"><span class="header-label">COMUNA:</span> VALLES DE BRUZUAL</div>
        <div class="header-item"><span class="header-label">MUNICIPIO</span> BRUZUAL, <span class="header-label">ESTADO</span> YARACUY</div>
    </div>

    <!-- Título del documento -->
    <div class="title">
        CONSTANCIA DE RESIDENCIA
    </div>

    <!-- Párrafo del Cuerpo -->
    <div class="body-text">
        Quienes suscriben, Representantes del Consejo Comunal <u><span class="highlight">{{ mb_strtoupper($persona->consejoComunal->nombre) }}</span></u>, 
        pertenecientes a la Comuna Valles de Bruzual, del Municipio Bruzual, Estado Yaracuy, hacen constar por medio de la presente que el (la) ciudadano(a): 
        <u><span class="highlight">{{ mb_strtoupper($persona->nombres) }} {{ mb_strtoupper($persona->apellidos) }}</span></u>. 
        Portador(a) de la cédula de identidad C.I.: <u><span class="highlight">{{ $persona->cedula_tipo ?? 'V' }}-{{ $persona->cedula }}</span></u>. 
        Es habitante residente de esta comunidad desde hace <u><span class="highlight">{{ $anios_residencia }}</span></u> años, domiciliado(a) 
        en la siguiente dirección exacta: <u><span class="highlight">{{ $persona->direccion }}</span></u>.
    </div>

    <!-- Párrafo de Expedición -->
    <div class="expedition-text">
        Se expide la presente a solicitud de la parte interesada, en la ciudad de Chivacoa, a los <u><span class="highlight">{{ $dia }}</span></u> días del mes de 
        <u><span class="highlight">{{ $mes }}</span></u> de <u><span class="highlight">{{ $anio }}</span></u>.
    </div>

    <!-- Firma / Pie de página -->
    <div class="signature-section">
        <div style="margin-bottom: 40px;">Atentamente,</div>
        
        <div class="signature-line"></div>
        
        <div class="signature-name">
            {{ $persona->consejoComunal->jefe->nombres }} {{ $persona->consejoComunal->jefe->apellidos }}
        </div>
        <div class="signature-ci">
            C.I: {{ $persona->consejoComunal->jefe->cedula }}
        </div>
        <div class="signature-title">
            JEFE DE COMANDO
        </div>
    </div>

</body>
</html>
