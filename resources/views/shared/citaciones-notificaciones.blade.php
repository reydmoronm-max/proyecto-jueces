@if (Auth::check() && Auth::user()->rol === 'Juez' && !request()->routeIs('citaciones.index') && !empty($citacionesHoyNotificaciones) && $citacionesHoyNotificaciones->isNotEmpty())
    <div id="citaciones-notificaciones" class="citaciones-notificaciones" aria-live="polite">
        <div class="citacion-notificacion citacion-notificacion-hoy">
            <a class="citacion-notificacion-enlace" href="{{ route('citaciones.index', ['hoy' => 1]) }}">
                <span class="citacion-notificacion-icon"><i class="ri-calendar-check-line"></i></span>
                <span>
                    <strong>Citaciones para hoy</strong>
                    <small>{{ $citacionesHoyNotificaciones->count() }} {{ $citacionesHoyNotificaciones->count() === 1 ? 'cita pendiente' : 'citas pendientes' }}</small>
                </span>
                <i class="ri-arrow-right-line citacion-notificacion-arrow"></i>
            </a>
            <button type="button" class="citacion-notificacion-cerrar" aria-label="Cerrar notificacion">&times;</button>
        </div>
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
            border: 1px solid rgba(255, 255, 255, .3);
            border-radius: .75rem;
            color: #fff;
            text-decoration: none;
            background: linear-gradient(135deg, #146c94, #0b3d57);
            box-shadow: 0 .75rem 2rem rgba(11, 61, 87, .28);
            animation: citacion-notificacion-entrada .35s ease-out;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .citacion-notificacion-enlace {
            display: flex;
            align-items: center;
            flex: 1;
            gap: .75rem;
            color: inherit;
            text-decoration: none;
        }

        .citacion-notificacion-enlace:hover {
            color: inherit;
        }

        .citacion-notificacion:hover {
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 1rem 2.25rem rgba(11, 61, 87, .35);
        }

        .citacion-notificacion-icon {
            display: grid;
            flex: 0 0 2.25rem;
            width: 2.25rem;
            height: 2.25rem;
            place-items: center;
            border-radius: 50%;
            color: #0b3d57;
            background: #d7f3ff;
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
            color: rgba(255, 255, 255, .8);
        }

        .citacion-notificacion-arrow {
            margin-left: auto;
            font-size: 1.2rem;
        }

        .citacion-notificacion-cerrar {
            align-self: flex-start;
            padding: 0;
            border: 0;
            color: rgba(255, 255, 255, .8);
            background: transparent;
            font-size: 1.5rem;
            line-height: 1;
            cursor: pointer;
        }

        .citacion-notificacion-cerrar:hover {
            color: #fff;
        }

        .citacion-notificacion-hora {
            background: linear-gradient(135deg, #a6401f, #64200f);
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

            contenedor.querySelectorAll('.citacion-notificacion-cerrar').forEach(function(boton) {
                boton.addEventListener('click', function() {
                    boton.closest('.citacion-notificacion').remove();
                });
            });

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
                        '<span><strong>Hora de la citacion</strong><small>Una cita esta programada ahora</small></span>' +
                        '<i class="ri-arrow-right-line citacion-notificacion-arrow"></i></a>' +
                        '<button type="button" class="citacion-notificacion-cerrar" aria-label="Cerrar notificacion">&times;</button>';
                    aviso.querySelector('.citacion-notificacion-cerrar').addEventListener('click', function() {
                        aviso.remove();
                    });
                    contenedor.appendChild(aviso);

                    window.setTimeout(function() {
                        aviso.remove();
                    }, 15000);
                });
            }

            comprobarCitas();
            window.setInterval(comprobarCitas, 15000);
        });
    </script>
@endif
