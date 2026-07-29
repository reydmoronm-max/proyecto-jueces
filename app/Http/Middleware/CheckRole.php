<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRol = Auth::user()->rol;

        if (in_array($userRol, $roles)) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'No tienes permisos para realizar esta acción.'], 403);
        }

        // Redirección adaptativa según el rol asignado al usuario
        if ($userRol === 'Juez') {
            return redirect()->route('visitas.index')->with('error', 'No tienes permisos para acceder a esa sección.');
        } elseif ($userRol === 'Jefe de comuna' || $userRol === 'Jefe de Comando') {
            return redirect()->route('reportes.index')->with('error', 'No tienes permisos para acceder a esa sección.');
        }

        return redirect()->route('login')->with('error', 'Acceso no autorizado.');
    }
}
