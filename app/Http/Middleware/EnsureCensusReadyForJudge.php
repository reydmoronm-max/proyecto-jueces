<?php

namespace App\Http\Middleware;

use App\Models\Familia;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureCensusReadyForJudge
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user()->rol !== 'Juez') {
            return $next($request);
        }

        $familiasConIntegrantes = Familia::whereHas('personas')->count();

        if ($familiasConIntegrantes < 2) {
            return redirect()->route('login')->with('error', 'No hay familias/personas registradas en el censo para ejercer el juzgado de paz... Por favor, comuníquese con el Jefe de Comando');
        }

        return $next($request);
    }
}
