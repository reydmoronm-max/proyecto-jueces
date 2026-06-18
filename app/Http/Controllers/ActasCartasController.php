<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\ConsejoComunal;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ActasCartasController extends Controller
{
    /**
     * Display the index generator interface.
     */
    public function index()
    {
        $titulo = 'Actas';
        $paginaTitulo = 'Generador de Actas y Constancias';
        $paginaSubtitulo = 'Emisión de constancia de residencia y carta de buena conducta.';
        $actasActive = 'active';

        return view('modules.actas.index', compact('titulo', 'paginaTitulo', 'paginaSubtitulo', 'actasActive'));
    }

    /**
     * Search for a person in the census and load all relationships.
     */
    public function buscarPersona(Request $request)
    {
        $request->validate([
            'cedula' => ['required', 'digits_between:7,8'],
        ]);

        $persona = Persona::with(['consejoComunal.jefe'])->where('cedula', $request->cedula)->first();

        if (!$persona) {
            return response()->json(['message' => 'El ciudadano no está registrado en el Censo.'], 404);
        }

        // Validate the relationship with Consejo Comunal
        if (!$persona->consejo_comunal_id || !$persona->consejoComunal) {
            return response()->json(['message' => 'El ciudadano no está vinculado a ningún Consejo Comunal en el censo. Para generar la carta, debe registrar esta vinculación en el censo primero.'], 422);
        }

        // Validate that Consejo Comunal has a Jefe de Comando
        if (!$persona->consejoComunal->jefe_comando || !$persona->consejoComunal->jefe) {
            return response()->json(['message' => 'El Consejo Comunal vinculado al ciudadano no tiene un Jefe de Comando registrado. Debe asignarlo en el módulo de Consejos Comunales primero.'], 422);
        }

        // Validate that citizen has an address
        if (empty($persona->direccion)) {
            return response()->json(['message' => 'El ciudadano no tiene registrada una dirección de domicilio en el censo. Para continuar, debe completar este dato en el censo.'], 422);
        }

        $esDenunciado = \DB::table('involucrados')
            ->where('persona_id', $persona->id)
            ->where('rol', 'denunciado')
            ->exists();

        return response()->json([
            'persona' => $persona,
            'consejo_comunal' => $persona->consejoComunal,
            'jefe_comando' => $persona->consejoComunal->jefe,
            'es_denunciado' => $esDenunciado,
        ]);
    }

    /**
     * Export Constancia de Residencia in PDF.
     */
    public function exportResidenciaPdf(Request $request)
    {
        $request->validate([
            'cedula' => ['required', 'digits_between:7,8'],
            'anios_residencia' => ['required', 'integer', 'min:0'],
        ]);

        $persona = Persona::with(['consejoComunal.jefe'])->where('cedula', $request->cedula)->first();

        if (!$persona || !$persona->consejoComunal || !$persona->consejoComunal->jefe || empty($persona->direccion)) {
            return back()->withErrors(['error' => 'Los datos del ciudadano están incompletos en el censo.']);
        }

        $anios_residencia = $request->anios_residencia;

        // Date formatting in Spanish
        $fechaActual = Carbon::now();
        $dia = $fechaActual->day;
        $meses = [
            1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril',
            5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto',
            9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre'
        ];
        $mes = $meses[$fechaActual->month];
        $anio = $fechaActual->year;

        $pdf = Pdf::loadView('modules.actas.pdf_residencia', compact('persona', 'anios_residencia', 'dia', 'mes', 'anio'));
        
        return $pdf->stream('constancia_residencia_' . $persona->cedula . '.pdf');
    }

    /**
     * Export Carta de Buena Conducta in PDF.
     */
    public function exportBuenaConductaPdf(Request $request)
    {
        $request->validate([
            'cedula' => ['required', 'digits_between:7,8'],
        ]);

        $persona = Persona::with(['consejoComunal.jefe'])->where('cedula', $request->cedula)->first();

        if (!$persona || !$persona->consejoComunal || !$persona->consejoComunal->jefe || empty($persona->direccion)) {
            return back()->withErrors(['error' => 'Los datos del ciudadano están incompletos en el censo.']);
        }

        $esDenunciado = \DB::table('involucrados')
            ->where('persona_id', $persona->id)
            ->where('rol', 'denunciado')
            ->exists();

        if ($esDenunciado) {
            return back()->withErrors(['error' => 'No se puede generar la carta de buena conducta porque el ciudadano figura como denunciado en un expediente del sistema.']);
        }

        // Date formatting in Spanish
        $fechaActual = Carbon::now();
        $dia = $fechaActual->day;
        $meses = [
            1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril',
            5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto',
            9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre'
        ];
        $mes = $meses[$fechaActual->month];
        $anio = $fechaActual->year;

        $pdf = Pdf::loadView('modules.actas.pdf_buena_conducta', compact('persona', 'dia', 'mes', 'anio'));

        return $pdf->stream('carta_buena_conducta_' . $persona->cedula . '.pdf');
    }
}
