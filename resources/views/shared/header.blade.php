    <div class="position-relative iq-banner">
        <!--Nav Start-->
        <nav class="nav navbar navbar-expand-lg navbar-light iq-navbar" style="background-color: #151824;">
            <div class="container-fluid navbar-inner">
                <a href="" class="navbar-brand">
                    <!--Logo start-->
                    <!--logo End-->

                    <!--Logo start-->
                    <div class="logo-main">
                        <div class="logo-normal">
                            <img style="width: 2.3rem;" src="{{ asset('images/logo2.png') }}" alt="">
                        </div>
                    </div>
                    <!--logo End-->




                    <h4 class="logo-title">SGIC</h4>
                </a>
                <div class="sidebar-toggle" data-toggle="sidebar" data-active="true">
                    <i class="icon">
                        <svg width="20px" class="icon-20" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z" />
                        </svg>
                    </i>
                </div>
                <div class="input-group search-input">
                    <span class="input-group-text" id="search-input" style="background-color: #151824;">
                        <svg class="icon-18" width="18" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <circle cx="11.7669" cy="11.7666" r="8.98856" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round"></circle>
                            <path d="M18.0186 18.4851L21.5426 22" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </span>
                    <form action="{{ route('consulta.index') }}" method="GET" class="needs-validation" novalidate>
                        <input type="number" class="form-control" placeholder="Buscar por cédula..." style="background-color: #151824;"
                        oninput="if(this.value.length>8)this.value=this.value.slice(0,8)" id="header_buscar_cedula" name="cedula">
                    </form>
                </div>
                @if (Auth::user()->rol === 'Juez')
                    <a href="{{ route('citaciones.index', ['hoy' => 1]) }}"
                        class="btn btn-outline-primary btn-sm ms-3 d-flex align-items-center"
                        title="Ver citaciones activas para hoy">
                        <svg class="icon-16 me-1" width="16" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 7V3H16V7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round"></path>
                            <path
                                d="M19 7H5C3.89543 7 3 7.89543 3 9V19C3 20.1046 3.89543 21 5 21H19C20.1046 21 21 20.1046 21 19V9C21 7.89543 20.1046 7 19 7Z"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            </path>
                            <path d="M16 11H8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round"></path>
                        </svg>
                        Hoy
                        <span class="badge bg-primary text-white ms-2">{{ $citacionesHoyPendientesCount ?? 0 }}</span>
                    </a>
                @endif
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon">
                        <span class="mt-2 navbar-toggler-bar bar1"></span>
                        <span class="navbar-toggler-bar bar2"></span>
                        <span class="navbar-toggler-bar bar3"></span>
                    </span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="mb-2 navbar-nav ms-auto align-items-center navbar-list mb-lg-0">
                        {{-- Color Mode Toggle --}}

                        {{-- <li class="nav-item dropdown me-2">
                            <div class="btn btn-border active" data-setting="color-mode" data-name="color"
                                data-value="light">
                                <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill="currentColor"
                                        d="M12,8A4,4 0 0,0 8,12A4,4 0 0,0 12,16A4,4 0 0,0 16,12A4,4 0 0,0 12,8M12,18A6,6 0 0,1 6,12A6,6 0 0,1 12,6A6,6 0 0,1 18,12A6,6 0 0,1 12,18M20,8.69V4H15.31L12,0.69L8.69,4H4V8.69L0.69,12L4,15.31V20H8.69L12,23.31L15.31,20H20V15.31L23.31,12L20,8.69Z" />
                                </svg>
                            </div>
                        </li>
                        <li class="nav-item dropdown me-2">
                            <div class="btn btn-border" data-setting="color-mode" data-name="color" data-value="dark">
                                <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill="currentColor"
                                        d="M9,2C7.95,2 6.95,2.16 6,2.46C10.06,3.73 13,7.5 13,12C13,16.5 10.06,20.27 6,21.54C6.95,21.84 7.95,22 9,22A10,10 0 0,0 19,12A10,10 0 0,0 9,2Z" />
                                </svg>
                            </div>
                        </li> --}}

                        {{-- End Color Mode Toggle --}}

                        {{-- Notification Dropdown --}}

                        {{-- <li class="nav-item dropdown">
                            <a href="#" class="nav-link" id="notification-drop" data-bs-toggle="dropdown"
                                style="pointer-events: none;">
                                <svg class="icon-24" width="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M19.7695 11.6453C19.039 10.7923 18.7071 10.0531 18.7071 8.79716V8.37013C18.7071 6.73354 18.3304 5.67907 17.5115 4.62459C16.2493 2.98699 14.1244 2 12.0442 2H11.9558C9.91935 2 7.86106 2.94167 6.577 4.5128C5.71333 5.58842 5.29293 6.68822 5.29293 8.37013V8.79716C5.29293 10.0531 4.98284 10.7923 4.23049 11.6453C3.67691 12.2738 3.5 13.0815 3.5 13.9557C3.5 14.8309 3.78723 15.6598 4.36367 16.3336C5.11602 17.1413 6.17846 17.6569 7.26375 17.7466C8.83505 17.9258 10.4063 17.9933 12.0005 17.9933C13.5937 17.9933 15.165 17.8805 16.7372 17.7466C17.8215 17.6569 18.884 17.1413 19.6363 16.3336C20.2118 15.6598 20.5 14.8309 20.5 13.9557C20.5 13.0815 20.3231 12.2738 19.7695 11.6453Z"
                                        fill="currentColor"></path>
                                    <path opacity="0.4"
                                        d="M14.0088 19.2283C13.5088 19.1215 10.4627 19.1215 9.96275 19.2283C9.53539 19.327 9.07324 19.5566 9.07324 20.0602C9.09809 20.5406 9.37935 20.9646 9.76895 21.2335L9.76795 21.2345C10.2718 21.6273 10.8632 21.877 11.4824 21.9667C11.8123 22.012 12.1482 22.01 12.4901 21.9667C13.1083 21.877 13.6997 21.6273 14.2036 21.2345L14.2026 21.2335C14.5922 20.9646 14.8734 20.5406 14.8983 20.0602C14.8983 19.5566 14.4361 19.327 14.0088 19.2283Z"
                                        fill="currentColor"></path>
                                </svg>
                                <span class="bg-danger dots"></span>
                            </a>
                            <div class="p-0 sub-drop dropdown-menu dropdown-menu-end">
                                <div class="m-0 shadow-none card">
                                    <div class="py-3 card-header d-flex justify-content-between bg-primary">
                                        <div class="header-title">
                                            <h5 class="mb-0 text-white">Notificaciones</h5>
                                        </div>
                                    </div>
                                    <div class="p-0 card-body">
                                        <a href="#" class="iq-sub-card">
                                            <div class="d-flex align-items-center">
                                                <img class="p-1 avatar-40 rounded-pill bg-soft-primary"
                                                    src="{{ asset('images/shapes/01.png') }}" alt="">
                                                <div class="ms-3 w-100">
                                                    <h6 class="mb-0 ">Durán Julián</h6>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <p class="mb-0">Nuevo</p>
                                                        <small class="float-end font-size-12">Hace 5 min.</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </li> --}}

                        {{-- End notification dropdown --}}


                        <li class="nav-item dropdown">
                            <a class="py-0 nav-link d-flex align-items-center" href="#" id="navbarDropdown"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src={{ asset('images/avatars/01.png') }} alt="User-Profile"
                                    class="theme-color-default-img img-fluid avatar avatar-50 avatar-rounded">
                                <img src={{ asset('images/avatars/avtar_1.png') }} alt="User-Profile"
                                    class="theme-color-purple-img img-fluid avatar avatar-50 avatar-rounded">
                                <img src={{ asset('images/avatars/avtar_2.png') }} alt="User-Profile"
                                    class="theme-color-blue-img img-fluid avatar avatar-50 avatar-rounded">
                                <img src={{ asset('images/avatars/avtar_4.png') }} alt="User-Profile"
                                    class="theme-color-green-img img-fluid avatar avatar-50 avatar-rounded">
                                <img src={{ asset('images/avatars/avtar_5.png') }} alt="User-Profile"
                                    class="theme-color-yellow-img img-fluid avatar avatar-50 avatar-rounded">
                                <img src={{ asset('images/avatars/avtar_3.png') }} alt="User-Profile"
                                    class="theme-color-pink-img img-fluid avatar avatar-50 avatar-rounded">
                                <div class="caption ms-3 d-none d-md-block ">
                                    <h6 class="mb-0 caption-title" style="color: white;">{{ Auth::user()->nombre }}
                                        {{ Auth::user()->apellido }}</h6>
                                    <p class="mb-0 caption-sub-title" style="color: rgb(179, 179, 179);">{{ Auth::user()->rol }}</p>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('logout') }}">Salir <i
                                            class="ri-logout-box-r-fill"></i></a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav> <!-- Nav Header Component Start -->
        <div class="iq-navbar-header" style="height: 215px;">
            <div class="container-fluid iq-container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="flex-wrap d-flex justify-content-between align-items-center">
                            <div>
                                <h1 class="fw-bold">@yield('paginaTitulo')</h1>
                                <p>@yield('paginaSubtitulo')</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="iq-header-img">
                <img src={{ asset('images/dashboard/top-headerPrueba.png') }} alt="header"
                    class="theme-color-default-img img-fluid w-100 h-100 animated-scaleX">
                <img src={{ asset('images/dashboard/top-header1.png') }} alt="header"
                    class="theme-color-purple-img img-fluid w-100 h-100 animated-scaleX">
                <img src={{ asset('images/dashboard/top-header2.png') }} alt="header"
                    class="theme-color-blue-img img-fluid w-100 h-100 animated-scaleX">
                <img src={{ asset('images/dashboard/top-header3.png') }} alt="header"
                    class="theme-color-green-img img-fluid w-100 h-100 animated-scaleX">
                <img src={{ asset('images/dashboard/top-header4.png') }} alt="header"
                    class="theme-color-yellow-img img-fluid w-100 h-100 animated-scaleX">
                <img src={{ asset('images/dashboard/top-header5.png') }} alt="header"
                    class="theme-color-pink-img img-fluid w-100 h-100 animated-scaleX">
            </div>
        </div> <!-- Nav Header Component End -->
        <!--Nav End-->
    </div>
