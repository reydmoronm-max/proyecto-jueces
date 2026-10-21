@if (Auth::check() && Auth::user()->rol === 'Juez' && !request()->routeIs('citaciones.index') && !empty($citacionesHoyNotificaciones) && $citacionesHoyNotificaciones->isNotEmpty())
    <div id="citaciones-notificaciones" class="citaciones-notificaciones" aria-live="polite">
        @if (session('mostrar_notificacion_citas'))
            <a class="citacion-notificacion citacion-notificacion-enlace citacion-notificacion-hoy" href="{{ route('citaciones.index', ['hoy' => 1]) }}">
                <span class="citacion-notificacion-icon"><i class="ri-calendar-check-line"></i></span>
                <span>
                    <strong>Citaciones para hoy</strong>
                    <small>{{ $citacionesHoyNotificaciones->count() }} {{ $citacionesHoyNotificaciones->count() === 1 ? 'cita pendiente' : 'citas pendientes' }}</small>
                </span>
                <i class="ri-arrow-right-line citacion-notificacion-arrow"></i>
            </a>
        @endif
    </div>

    <style>
        .citaciones-notificaciones {
            position: fixed;
            right: 1.25rem;
            bottom: 1.25rem;
            z-index: 1080;
            display: flex;
            flex-direction: column;
            gap: .75rem;
            width: min(22rem, calc(100vw - 2rem));
        }

        .citacion-notificacion {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: 1rem 1.1rem;
            border: 1px solid rgba(var(--bs-primary-rgb, 58, 87, 232), .18);
            border-left: 4px solid var(--bs-primary, #3a57e8);
            border-radius: .5rem;
            color: #242a35;
            text-decoration: none;
            background: #fff;
            box-shadow: 0 .5rem 1.5rem rgba(31, 45, 61, .16);
            animation: citacion-notificacion-entrada .35s ease-out;
            transition: transform .2s ease, box-shadow .2s ease, opacity .3s ease;
        }

        .citacion-notificacion-enlace {
            display: flex;
            align-items: center;
            flex: 1;
            gap: .75rem;
            color: #242a35;
            text-decoration: none;
        }

        .citacion-notificacion:hover {
            color: #242a35;
            transform: translateY(-2px);
            box-shadow: 0 .75rem 1.75rem rgba(31, 45, 61, .22);
        }

        .citacion-notificacion-icon {
            display: grid;
            flex: 0 0 2.25rem;
            width: 2.25rem;
            height: 2.25rem;
            place-items: center;
            border-radius: .5rem;
            color: var(--bs-primary, #3a57e8);
            background: rgba(var(--bs-primary-rgb, 58, 87, 232), .1);
            font-size: 1.25rem;
        }

        .citacion-notificacion strong,
        .citacion-notificacion small {
            display: block;
        }

        .citacion-notificacion strong {
            font-size: .95rem;
        }

        .citacion-notificacion small {
            margin-top: .15rem;
            color: #6c757d;
        }

        .citacion-notificacion-arrow {
            margin-left: auto;
            font-size: 1.2rem;
        }

        .citacion-notificacion-hora {
            border-left-color: #f0ad4e;
        }

        .citacion-notificacion-salida {
            pointer-events: none;
            animation: citacion-notificacion-salida .4s ease-in forwards;
        }

        @keyframes citacion-notificacion-entrada {
            from {
                opacity: 0;
                transform: translateY(1rem);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes citacion-notificacion-salida {
            to {
                opacity: 0;
                transform: translateY(1.5rem);
            }
        }

        @media (max-width: 575.98px) {
            .citaciones-notificaciones {
                right: 1rem;
                bottom: 1rem;
                width: calc(100vw - 2rem);
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const citasDeHoy = @json($citacionesHoyNotificaciones->map(fn ($citacion) => ['hora' => substr((string) $citacion->hora_citacion, 0, 5)])->values());
            const agendaUrl = @json(route('citaciones.index', ['hoy' => 1]));
            const contenedor = document.getElementById('citaciones-notificaciones');

            if (!contenedor) {
                return;
            }

            function retirarAviso(aviso) {
                aviso.classList.add('citacion-notificacion-salida');
                window.setTimeout(function() {
                    aviso.remove();
                }, 400);
            }

            const avisoInicio = contenedor.querySelector('.citacion-notificacion-hoy');
            if (avisoInicio) {
                window.setTimeout(function() {
                    retirarAviso(avisoInicio);
                }, 10000);
            }

            function comprobarCitas() {
                const ahora = new Date();
                const horaActual = String(ahora.getHours()).padStart(2, '0') + ':' + String(ahora.getMinutes()).padStart(2, '0');
                const fechaActual = ahora.getFullYear() + '-' + String(ahora.getMonth() + 1).padStart(2, '0') + '-' + String(ahora.getDate()).padStart(2, '0');

                citasDeHoy.forEach(function(cita) {
                    if (cita.hora !== horaActual) {
                        return;
                    }

                    const claveAviso = 'citacion-notificada-' + fechaActual + '-' + cita.hora;
                    if (localStorage.getItem(claveAviso)) {
                        return;
                    }

                    localStorage.setItem(claveAviso, '1');

                    const aviso = document.createElement('div');
                    aviso.className = 'citacion-notificacion citacion-notificacion-hora';
                    aviso.innerHTML = '<a class="citacion-notificacion-enlace" href="' + agendaUrl + '">' +
                        '<span class="citacion-notificacion-icon"><i class="ri-alarm-warning-line"></i></span>' +
                        '<span><strong>Hora de la citación</strong><small>Una cita está programada ahora</small></span>' +
                        '<i class="ri-arrow-right-line citacion-notificacion-arrow"></i></a>';
                    contenedor.appendChild(aviso);

                    window.setTimeout(function() {
                        retirarAviso(aviso);
                    }, 10000);
                });
            }

            comprobarCitas();
            window.setInterval(comprobarCitas, 15000);
        });
    </script>
@endif
