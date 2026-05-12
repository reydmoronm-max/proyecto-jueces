<!doctype html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>@yield('titulo')</title>
        
        <!-- Favicon -->
        {{-- <link rel="shortcut icon" href={{ asset('images/favicon.ico') }} /> --}}
        
        <!-- Library / Plugin Css Build -->
        <link rel="stylesheet" href={{ asset('css/core/libs.min.css') }} />
        
        
        <!-- Hope Ui Design System Css -->
        <link rel="stylesheet" href={{ asset('css/hope-ui.css?v=2.0.0') }} />
        
        <!-- Custom Css -->
        <link rel="stylesheet" href={{ asset('css/custom.css?v=2.0.0') }} />
        
        <!-- Dark Css -->
        <link rel="stylesheet" href={{ asset('css/dark.min.css') }} />
        
        <!-- Customizer Css -->
        <link rel="stylesheet" href={{ asset('css/customizer.css') }} />
        
        <!-- RTL Css -->
        <link rel="stylesheet" href={{ asset('css/rtl.min.css') }} />

        {{-- Remix Icons --}}
        <link href="{{ asset('vendor/remixicon/remixicon.css') }}" rel="stylesheet">

        
        
    </head>
    <body class=" " data-bs-spy="scroll" data-bs-target="#elements-section" data-bs-offset="0" tabindex="0">
        @yield('contenido')
    
        <!-- Library Bundle Script -->
        <script src={{ asset('js/core/libs.min.js') }}></script>
        
        <!-- External Library Bundle Script -->
        <script src={{ asset('js/core/external.min.js') }}></script>
        
        <!-- Widgetchart Script -->
        <script src={{ asset('js/charts/widgetcharts.js') }}></script>
        
        <!-- mapchart Script -->
        <script src={{ asset('js/charts/vectore-chart.js') }}></script>
        <script src={{ asset('js/charts/dashboard.js') }} ></script>
        
        <!-- fslightbox Script -->
        <script src={{ asset('js/plugins/fslightbox.js') }}></script>
        
        <!-- Settings Script -->
        <script src={{ asset('js/plugins/setting.js') }}></script>
        
        <!-- Slider-tab Script -->
        <script src={{ asset('js/plugins/slider-tabs.js') }}></script>
        
        <!-- Form Wizard Script -->
        <script src={{ asset('js/plugins/form-wizard.js') }}></script>
        
        <!-- AOS Animation Plugin-->
        
        <!-- App Script -->
        <script src={{ asset('js/hope-ui.js') }} defer></script>
        
    </body>
</html>