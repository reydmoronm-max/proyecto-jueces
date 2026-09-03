<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['cedula_usuario' => '11123456'],
            [
                'nombre'              => 'Juan',
                'apellido'            => 'Pérez',
                'password'            => Hash::make('jefe'),
                'activo'              => true,
                'rol'                 => 'Jefe de comuna',
                'pregunta_seguridad'  => '¿Qué color le gusta más?',
                'respuesta_seguridad' => Hash::make('azul'),
            ]
        );

        User::updateOrCreate(
            ['cedula_usuario' => '22123456'],
            [
                'nombre'              => 'Julieta',
                'apellido'            => 'Gonzáles',
                'password'            => Hash::make('juez'),
                'activo'              => true,
                'rol'                 => 'Juez',
                'pregunta_seguridad'  => '¿Qué color le gusta más?',
                'respuesta_seguridad' => Hash::make('azul'),
            ]
        );

        User::updateOrCreate(
            ['cedula_usuario' => '33123456'],
            [
                'nombre'              => 'Pedro',
                'apellido'            => 'Ramírez',
                'password'            => Hash::make('comando'),
                'activo'              => true,
                'rol'                 => 'Jefe de Comando',
                'pregunta_seguridad'  => '¿Qué color le gusta más?',
                'respuesta_seguridad' => Hash::make('azul'),
            ]
        );
    }
}
