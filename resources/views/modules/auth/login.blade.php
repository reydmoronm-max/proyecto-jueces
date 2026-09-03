@extends('layouts.login')
@section('titulo', $titulo)

@section('contenido')
    <main>
        <!-- loader Start -->
        <div id="loading">
            <div class="loader simple-loader">
                <div class="loader-body"></div>
            </div>
        </div>
        <!-- loader END -->

        <div class="wrapper">
            <section class="login-content">
                <div class="row m-0 align-items-center bg-white vh-100">
                    <div class="col-md-6">
                        <div class="row justify-content-center">
                            <div class="col-md-10">
                                <div class="card card-transparent shadow-none d-flex justify-content-center mb-0 auth-card">
                                    <div class="card-body">
                                        <img src="{{ asset('images/logo.png') }}" alt=""
                                            class="w-25 h-25 mb-4 opacity-50">
                                        <h1 class="mb-2 fw-bolder">INICIAR SESIÓN</h1>
                                        <a style="pointer-events: none;" href="#"
                                            class="navbar-brand d-flex align-items-center mb-3">
                                            <h4 class="text-primary fst-italic">Sistema de Gestión Integral Comunal
                                            </h4>
                                        </a>
                                        {{-- <p class="">Ingrese sus credenciales para acceder al sistema.</p> --}}
                                        <form class="needs-validation" novalidate action="{{ route('logear') }}"
                                            method="POST" autocomplete="off">
                                            @csrf
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-floating mt-4 mb-4">
                                                        <input type="number" class="form-control" name="cedula_usuario"
                                                            id="cedula_usuario" placeholder="" required oninput="if(this.value.length>8)this.value=this.value.slice(0,8)">
                                                        <label for="cedula_usuario" class="form-label">Cédula de usuario</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-floating mb-4">
                                                        <input type="password" class="form-control" name="password"
                                                            id="password" placeholder="" required>
                                                        <label for="password" class="form-label">Contraseña</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mt-2">
                                                <button type="submit" class="btn btn-md btn-primary rounded-pill"
                                                    style="font-weight: bold;">Acceder <i
                                                        class="ri-login-box-line"></i></button>
                                                <a href="{{ route('recuperar.index') }}" class="text-primary small">¿Olvidaste tu contraseña?</a>
                                            </div>
                                        </form>
                                        <div>
                                            @if (session('success'))
                                                <div class="alert alert-success mt-4">
                                                    {{ session('success') }}
                                                </div>
                                            @endif
                                            @if ($errors->any())
                                                <div class="mt-4" style="color: rgb(158, 0, 0);">
                                                    <ul>
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sign-bg">
                            <svg width="280" height="230" viewBox="0 0 431 398" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <g opacity="0.05">
                                    <rect x="-157.085" y="193.773" width="543" height="77.5714" rx="38.7857"
                                        transform="rotate(-45 -157.085 193.773)" fill="#3B8AFF" />
                                    <rect x="7.46875" y="358.327" width="543" height="77.5714" rx="38.7857"
                                        transform="rotate(-45 7.46875 358.327)" fill="#3B8AFF" />
                                    <rect x="61.9355" y="138.545" width="310.286" height="77.5714" rx="38.7857"
                                        transform="rotate(45 61.9355 138.545)" fill="#3B8AFF" />
                                    <rect x="62.3154" y="-190.173" width="543" height="77.5714" rx="38.7857"
                                        transform="rotate(45 62.3154 -190.173)" fill="#3B8AFF" />
                                </g>
                            </svg>
                        </div>
                    </div>
                    <div class="col-md-6 d-md-block d-none bg-primary p-0 mt-n1 vh-100 overflow-hidden">
                        <img src="{{ asset('images/auth/prueba.png') }}" class="img-fluid gradient-main animated-scaleX"
                            alt="images">
                    </div>
                </div>
            </section>
        </div>
    </main>
@endsection
