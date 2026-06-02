<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

        $items = User::all();
        return view('modules.usuarios.index', compact('titulo', 'usuariosActive', 'paginaTitulo', 'paginaSubtitulo', 'items'));
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
        User::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'cedula_usuario' => $request->cedula_usuario,
            'user' => $request->user,
            'password' => Hash::make($request->password),
            'activo' => true,
            'rol' => $request->rol
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
        $usuario->nombre = $request->nombre;
        $usuario->apellido = $request->apellido;
        $usuario->cedula_usuario = $request->cedula_usuario;
        $usuario->user = $request->user;
        $usuario->rol = $request->rol;
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

    public function tbody(){
        $items = User::all();
        return view('modules.usuarios.tbody', compact('items'));
    }

    public function cambiarEstado($id, $estado){
        $item = User::find($id);
        $item->activo = $estado;
        return $item->save();
    }

    public function cambiarPassword($id, $password){
        $item = User::find($id);
        $item->password = Hash::make($password);
        return $item->save();
    }
}
