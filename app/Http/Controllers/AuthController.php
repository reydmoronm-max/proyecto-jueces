<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $titulo = 'Iniciar sesión';
        return view('modules.auth.login', compact('titulo'));
    }

    public function login(Request $request)
    {
        // Validar
        $credenciales = $request->validate([
            'cedula_usuario' => 'required',
            'password' => 'required'
        ]);

        // Buscar usuario
        $user = User::where('cedula_usuario', $request->cedula_usuario)->first();

        // Validar usuario y contraseña
        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['cedula_usuario' => 'Credenciales incorrectas'])->withInput();
        }

        // El usuario está activo
        if (!$user->activo) {
            return back()->withErrors(['cedula_usuario' => 'Tu usuario está inactivo'])->withInput();
        }

        // Crear sesión
        Auth::login($user);
        $request->session()->regenerate();

        if (Auth::user()->rol == 'Jefe de comuna' || Auth::user()->rol == 'Jefe de Comando') {
            return to_route('reportes.index');
        } else if (Auth::user()->rol == 'Juez') {
            return to_route('visitas.index');
        }
    }

    // public function crearAdmin()
    // {
    //     // Crear un admin
    //     User::create([
    //         'nombre' => 'Rey',
    //         'apellido' => 'Morón',
    //         'cedula_usuario' => '12312312',
    //         'user' => 'admin',
    //         'password' => Hash::make('admin'),
    //         'activo' => true,
    //         'pregunta_seguridad' => '¿Qué color le gusta más?',
    //         'respuesta_seguridad' => Hash::make('azul')
    //     ]);

    //     return 'Admin creado';
    // }

    public function logout()
    {
        Auth::logout();
        return to_route('login');
    }

    public function recuperarForm()
    {
        $titulo = 'Recuperar contraseña';
        return view('modules.auth.recuperar', compact('titulo'));
    }

    public function recuperarBuscar(Request $request)
    {
        $request->validate([
            'cedula_usuario' => 'required'
        ]);

        $user = User::where('cedula_usuario', $request->cedula_usuario)->first();

        if (!$user) {
            return back()->withErrors(['cedula_usuario' => 'El usuario no existe'])->withInput();
        }

        if (empty($user->pregunta_seguridad) || empty($user->respuesta_seguridad)) {
            return back()->withErrors(['cedula_usuario' => 'El usuario no tiene una pregunta de seguridad configurada. Por favor, contacte al administrador.'])->withInput();
        }

        $titulo = 'Recuperar contraseña';
        $paso = 2;
        return view('modules.auth.recuperar', compact('titulo', 'paso', 'user'));
    }

    public function recuperarRestablecer(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'respuesta_seguridad' => 'required',
            'password' => 'required|min:4|confirmed'
        ], [
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 4 caracteres.'
        ]);

        $user = User::findOrFail($request->user_id);

        $respuestaIngresada = mb_strtolower(trim($request->respuesta_seguridad));

        if (!Hash::check($respuestaIngresada, $user->respuesta_seguridad)) {
            $titulo = 'Recuperar contraseña';
            $paso = 2;
            return view('modules.auth.recuperar', compact('titulo', 'paso', 'user'))
                ->withErrors(['respuesta_seguridad' => 'La respuesta a la pregunta de seguridad es incorrecta.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('login')->with('success', 'Contraseña restablecida exitosamente. Ya puedes iniciar sesión con tu nueva clave.');
    }
}
