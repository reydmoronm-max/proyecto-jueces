<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Persona;
use App\Models\Expediente;
use App\Models\Involucrados;
use App\Models\Actas;
use App\Models\Citaciones;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

class DenunciasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $titulo = 'Denuncias';
        $paginaTitulo = 'Denuncias';
        $paginaSubtitulo = 'Listado de denuncias registradas';
        $denunciasActive = 'active';

        // Obtener expedientes abiertos con persona denunciante (relación involucrados) y ordenados por fecha de creación descendente
        $expedientesAbiertos = Expediente::with(['personas' => function ($q) {
            $q->wherePivot('rol', 'denunciante');
        }])->where('estatus', 'Abierto')->orderBy('created_at', 'desc')->get();

        // Obtener expedientes en proceso con persona denunciante (relación involucrados) y ordenados por fecha de creación descendente
        $expedientesEnProceso = Expediente::with(['personas' => function ($q) {
            $q->wherePivot('rol', 'denunciante');
        }])->where('estatus', 'En proceso')->orderBy('created_at', 'desc')->get();

        // Obtener expedientes cerrados con persona denunciante (relación involucrados) y ordenados por fecha de creación descendente
        $expedientesCerrados = Expediente::with(['personas' => function ($q) {
            $q->wherePivot('rol', 'denunciante');
        }])->where('estatus', 'Cerrado')->orderBy('created_at', 'desc')->get();

        return view('modules.denuncias.index', compact('titulo', 'paginaTitulo', 'paginaSubtitulo', 'denunciasActive', 'expedientesAbiertos', 'expedientesEnProceso', 'expedientesCerrados'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación básica
        $request->validate([
            // 'cedula_tipo' => 'required',
            'cedula' => 'required',
            'nombres' => 'required',
            'apellidos' => 'required',
            'telefono' => 'required',
            'direccion' => 'required',
            'motivo_denuncia' => 'required',
        ]);

        DB::beginTransaction();
        try {
            // 1. Guardar persona (si no existe)
            $persona = Persona::firstOrCreate(
                [
                    'cedula_tipo' => 'V',
                    'cedula' => $request->cedula,
                ],
                [
                    'nombres' => $request->nombres,
                    'apellidos' => $request->apellidos,
                    'telefono' => $request->telefono,
                    'direccion' => $request->direccion,
                ]
            );

            // 2. Guardar expediente
            $expediente = Expediente::create([
                'motivo_denuncia' => $request->motivo_denuncia,
                'estatus' => 'Abierto',
            ]);

            // 3. Relacionar persona y expediente en tabla involucrados
            Involucrados::create([
                'persona_id' => $persona->id,
                'expediente_id' => $expediente->id,
                'rol' => 'denunciante',
            ]);

            // 4. Guardar acta
            $contenido = "Requirente: " . ($request->requirente ?? '') . "\n" .
                "Receptor: " . ($request->receptor ?? '') . "\n" .
                "Acuerdos: " . ($request->acuerdos ?? '');

            Actas::create([
                'expediente_id' => $expediente->id,
                'tipo_acta' => 'recepcion',
                'contenido' => $contenido,
                'lo_atiende_juez_id' => Auth::user()->id,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Denuncia registrada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Ocurrió un error al registrar la denuncia.');
        }
    }

    /*
        Display the specified resource.
    
    public function show($id)
    {
        $expediente = \App\Models\Expediente::with([
            'personas' => function ($q) {
                $q->wherePivot('rol', 'denunciante');
            },
            'actas'
        ])->findOrFail($id);

        $denunciante = $expediente->personas->first();
        $acta = $expediente->actas->first();

        return response()->json([
            'cedula_tipo' => $denunciante->cedula_tipo ?? '',
            'cedula' => $denunciante->cedula ?? '',
            'nombres' => $denunciante->nombres ?? '',
            'apellidos' => $denunciante->apellidos ?? '',
            'telefono' => $denunciante->telefono ?? '',
            'direccion' => $denunciante->direccion ?? '',
            'motivo_denuncia' => $expediente->motivo_denuncia,
            'requirente' => $acta ? $this->extraerCampoActa($acta->contenido, 'Requirente') : '',
            'receptor' => $acta ? $this->extraerCampoActa($acta->contenido, 'Receptor') : '',
            'acuerdos' => $acta ? $this->extraerCampoActa($acta->contenido, 'Acuerdos') : '',
        ]);
    }
        */

    public function buscarPersona(Request $request)
    {
        $request->validate([
            // 'cedula_tipo' => ['required', 'in:V,E'],
            'cedula' => ['required', 'digits_between:7,8'],
        ]);

        $persona = Persona::where('cedula', $request->cedula)
            ->where('cedula_tipo', 'V')
            ->whereHas('visitas')
            ->first();

        if (!$persona) {
            return response()->json(['message' => 'Persona no encontrada en visitas'], 404);
        }

        return response()->json([
            'cedula_tipo' => 'V',
            'cedula' => $persona->cedula,
            'nombres' => $persona->nombres,
            'apellidos' => $persona->apellidos,
            'telefono' => $persona->telefono,
            'direccion' => $persona->direccion,
        ]);
    }

    // Helper para extraer los campos del contenido del acta
    private function extraerCampoActa($contenido, $campo)
    {
        preg_match('/' . $campo . ': (.*?)(\\n|$)/', $contenido, $matches);
        return $matches[1] ?? '';
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function posponerCita(Request $request)
    {
        $request->validate([
            'expediente_id' => 'required|integer',
            'fecha_citacion' => 'required',
            'hora_citacion' => 'required',
            'solicita_por' => 'required|in:denunciante,denunciado',
        ]);

        DB::beginTransaction();
        try {
            // Solo crear/obtener persona si se enviaron datos de persona (cedula)
            $persona = null;
            if ($request->filled('cedula')) {
                $persona = Persona::firstOrCreate(
                    [
                        'cedula_tipo' => 'V',
                        'cedula' => $request->cedula,
                    ],
                    [
                        'nombres' => $request->nombres,
                        'apellidos' => $request->apellidos,
                        'telefono' => $request->telefono,
                        'direccion' => $request->direccion,
                    ]
                );
            }

            // Si se creó/tenemos persona y es necesario, relacionarla como 'denunciado'
            if ($persona) {
                Involucrados::firstOrCreate([
                    'persona_id' => $persona->id,
                    'expediente_id' => $request->expediente_id,
                    'rol' => 'denunciado',
                ]);
            }

            // Crear nueva cita, pero primero validar hora
            $fecha = $request->fecha_citacion;
            $hora = $request->hora_citacion;
            $fecha = \Carbon\Carbon::createFromFormat('d-m-Y', $fecha)->format('Y-m-d');

            $validarHora = Citaciones::where('fecha_citacion', $fecha)->where('hora_citacion', $hora)->where('estatus', true)->first();

            if ($validarHora) {
                DB::rollBack();
                return redirect()->back()->with('validar', 'Ya existe una citación para esa fecha y hora.');
            }

            // Determinar quién solicita el cambio y asignar su persona_id a solicita_cambio_id
            $solicitaCambioId = null;
            if ($request->solicita_por === 'denunciante') {
                $invol = Involucrados::where('expediente_id', $request->expediente_id)->where('rol', 'denunciante')->first();
                $solicitaCambioId = $invol ? $invol->persona_id : ($persona ? $persona->id : null);
            } else { // 'denunciado'
                $invol = Involucrados::where('expediente_id', $request->expediente_id)->where('rol', 'denunciado')->first();
                $solicitaCambioId = $invol ? $invol->persona_id : ($persona ? $persona->id : null);
            }

            Citaciones::create([
                'expediente_id' => $request->expediente_id,
                'fecha_citacion' => $fecha,
                'hora_citacion' => $hora,
                'estatus' => true,
            ]);

            // Modificar cita anterior
            $cita = Citaciones::where('expediente_id', $request->expediente_id)->where('estatus', true)->first();
            if ($cita) {
                $cita->observaciones = $request->observaciones;
                $cita->solicita_cambio_id = $solicitaCambioId;
                $cita->estatus = false;
                $cita->save();
            }

            DB::commit();

            return redirect()->back()->with('success', 'Citación pospuesta correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Ocurrió un error al posponer la citación.');
        }
    }

    /**
     * Exportar Acta de Recepcion de Denuncia en PDF.
     */
    public function exportarActaRecepcionPdf($id)
    {
        $expediente = \App\Models\Expediente::with([
            'personas' => function ($q) {
                $q->wherePivot('rol', 'denunciante');
            },
            'actas'
        ])->findOrFail($id);

        $denunciante = $expediente->personas->first();
        $acta = $expediente->actas->first();
        $idJuez = $acta->lo_atiende_juez_id;
        $juez = User::findOrFail($idJuez);

        $nombreJuez = $juez->nombre;
        $apellidoJuez = $juez->apellido;
        $cedulaJuez = $juez->cedula_usuario;

        $requirente = $acta ? $this->extraerCampoActa($acta->contenido, 'Requirente') : '';
        $receptor = $acta ? $this->extraerCampoActa($acta->contenido, 'Receptor') : '';
        $acuerdos = $acta ? $this->extraerCampoActa($acta->contenido, 'Acuerdos') : '';

        // Date formatting in Spanish
        $fecha = $acta->created_at;
        $hora = $acta->created_at->format('h:i A');
        $dia = $fecha->day;
        $meses = [
            1 => 'enero',
            2 => 'febrero',
            3 => 'marzo',
            4 => 'abril',
            5 => 'mayo',
            6 => 'junio',
            7 => 'julio',
            8 => 'agosto',
            9 => 'septiembre',
            10 => 'octubre',
            11 => 'noviembre',
            12 => 'diciembre'
        ];
        $mes = $meses[$fecha->month];
        $anio = $fecha->year;

        $pdf = Pdf::loadView('modules.denuncias.pdf_acta_recepcion_denuncia', compact('denunciante', 'requirente', 'receptor', 'acuerdos', 'dia', 'mes', 'anio', 'hora', 'nombreJuez', 'apellidoJuez', 'cedulaJuez'));

        return $pdf->stream('acta_recepcion_' . $expediente->id . '.pdf');
    }

    /**
     * Exportar Acta de Conciliacion de Denuncia en PDF.
     */
    public function exportarActaConciliacionPdf($id)
    {
        $expediente = \App\Models\Expediente::findOrFail($id);

        // Buscar el acta específica de conciliación
        $acta = $expediente->actas()->where('tipo_acta', 'conciliacion')->first();
        if (!$acta) {
            return redirect()->back()->with('error', 'El acta de conciliación no ha sido registrada para este expediente.');
        }

        // Obtener el denunciante y el denunciado
        $denunciante = $expediente->personas()->wherePivot('rol', 'denunciante')->first();
        $denunciado = $expediente->personas()->wherePivot('rol', 'denunciado')->first();

        $idJuez = $acta->lo_atiende_juez_id;
        $juez = User::findOrFail($idJuez);

        $nombreJuez = $juez->nombre;
        $apellidoJuez = $juez->apellido;
        $cedulaJuez = $juez->cedula_usuario;

        $requirente = $this->extraerCampoActa($acta->contenido, 'Requirente');
        $requerido = $this->extraerCampoActa($acta->contenido, 'Requerido');
        $coordinador = $this->extraerCampoActa($acta->contenido, 'Coordinador');
        $acuerdos = $this->extraerCampoActa($acta->contenido, 'Acuerdos');

        // Date formatting in Spanish
        $fecha = $acta->created_at;
        $hora = $acta->created_at->format('h:i A');
        $dia = $fecha->day;
        $meses = [
            1 => 'enero',
            2 => 'febrero',
            3 => 'marzo',
            4 => 'abril',
            5 => 'mayo',
            6 => 'junio',
            7 => 'julio',
            8 => 'agosto',
            9 => 'septiembre',
            10 => 'octubre',
            11 => 'noviembre',
            12 => 'diciembre'
        ];
        $mes = $meses[$fecha->month];
        $anio = $fecha->year;

        $pdf = Pdf::loadView('modules.denuncias.pdf_acta_conciliacion', compact(
            'denunciante',
            'denunciado',
            'requirente',
            'requerido',
            'coordinador',
            'acuerdos',
            'dia',
            'mes',
            'anio',
            'hora',
            'nombreJuez',
            'apellidoJuez',
            'cedulaJuez'
        ));

        return $pdf->stream('acta_conciliacion_' . $expediente->id . '.pdf');
    }
}
