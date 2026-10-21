<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $titulo = 'Usuarios';
        $usuariosActive = 'active';
        $paginaTitulo = 'Usuarios';
        $paginaSubtitulo = 'Listado de usuarios registrados en el sistema.';

        $usuariosActivos = User::where('activo', true)->get();
        $usuariosInactivos = User::where('activo', false)->get();

        return view('modules.usuarios.index', compact(
            'titulo',
            'usuariosActive',
            'paginaTitulo',
            'paginaSubtitulo',
            'usuariosActivos',
            'usuariosInactivos'
        ));
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
        $validator = Validator::make($request->all(), [
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'cedula_usuario' => ['required', 'string', 'digits_between:1,8', Rule::unique('users', 'cedula_usuario')],
            'password' => ['required', 'string', 'min:4'],
            'rol' => ['required', Rule::in(['Jefe de comuna', 'Juez', 'Jefe de Comando'])],
            'pregunta_seguridad' => ['nullable', 'string', 'max:255'],
            'respuesta_seguridad' => ['nullable', 'string', 'max:255'],
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'apellido.required' => 'El apellido es obligatorio.',
            'cedula_usuario.required' => 'La cédula es obligatoria.',
            'cedula_usuario.digits_between' => 'La cédula debe contener entre 1 y 8 dígitos.',
            'cedula_usuario.unique' => 'Ya existe un usuario registrado con esta cédula.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 4 caracteres.',
            'rol.required' => 'Debe seleccionar un rol.',
            'rol.in' => 'El rol seleccionado no es válido.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->except(['password', 'respuesta_seguridad']))
                ->with('usuario_formulario', 'crear');
        }

        $datos = $validator->validated();
        $respuestaHash = filled($datos['respuesta_seguridad'] ?? null)
            ? Hash::make(mb_strtolower(trim($datos['respuesta_seguridad'])))
            : null;

        User::create([
            'nombre' => $datos['nombre'],
            'apellido' => $datos['apellido'],
            'cedula_usuario' => $datos['cedula_usuario'],
            // 'user' => $request->user,
            'password' => Hash::make($datos['password']),
            'activo' => true,
            'rol' => $datos['rol'],
            'pregunta_seguridad' => $datos['pregunta_seguridad'] ?? null,
            'respuesta_seguridad' => $respuestaHash,
        ]);

        return to_route('usuarios.index')->with('success', 'Usuario registrado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $usuario = User::findOrFail($id);

        return response()->json($usuario);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $usuario = User::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'cedula_usuario' => [
                'required',
                'string',
                'digits_between:1,8',
                Rule::unique('users', 'cedula_usuario')->ignore($usuario->id),
            ],
            'rol' => ['required', Rule::in(['Jefe de comuna', 'Juez', 'Jefe de Comando'])],
            'pregunta_seguridad' => ['nullable', 'string', 'max:255'],
            'respuesta_seguridad' => ['nullable', 'string', 'max:255'],
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'apellido.required' => 'El apellido es obligatorio.',
            'cedula_usuario.required' => 'La cédula es obligatoria.',
            'cedula_usuario.digits_between' => 'La cédula debe contener entre 1 y 8 dígitos.',
            'cedula_usuario.unique' => 'Ya existe otro usuario registrado con esta cédula.',
            'rol.required' => 'Debe seleccionar un rol.',
            'rol.in' => 'El rol seleccionado no es válido.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->except(['password', 'respuesta_seguridad']))
                ->with('usuario_formulario', 'editar')
                ->with('usuario_editar_id', $usuario->id);
        }

        $datos = $validator->validated();
        $usuario->nombre = $datos['nombre'];
        $usuario->apellido = $datos['apellido'];
        $usuario->cedula_usuario = $datos['cedula_usuario'];
        // $usuario->user = $request->user;
        $usuario->rol = $datos['rol'];
        if (filled($datos['pregunta_seguridad'] ?? null)) {
            $usuario->pregunta_seguridad = $datos['pregunta_seguridad'];
        }
        if (filled($datos['respuesta_seguridad'] ?? null)) {
            $usuario->respuesta_seguridad = Hash::make(mb_strtolower(trim($datos['respuesta_seguridad'])));
        }
        $usuario->save();

        return to_route('usuarios.index')->with('update', 'Usuario actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function tbody()
    {
        $items = User::all();

        return view('modules.usuarios.tbody', compact('items'));
    }

    public function cambiarEstado(Request $request, string $id, string $estado)
    {
        $request->merge(['estado' => $estado]);
        $datos = $request->validate([
            'estado' => ['required', 'boolean'],
        ]);

        $usuario = User::findOrFail($id);
        if (! $datos['estado'] && (int) $request->user()->getKey() === (int) $usuario->getKey()) {
            return response()->json([
                'message' => 'No puedes desactivar tu propio usuario.',
            ], 422);
        }

        $usuario->activo = (bool) $datos['estado'];
        $usuario->save();

        return response()->json(['activo' => $usuario->activo]);
    }

    public function cambiarPassword($id, $password)
    {
        $item = User::find($id);
        $item->password = Hash::make($password);

        return $item->save();
    }
}
