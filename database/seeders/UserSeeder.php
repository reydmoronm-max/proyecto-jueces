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
            ['user' => 'jefe'],
            [
                'nombre'              => 'Juan',
                'apellido'            => 'Pérez',
                'cedula_usuario'      => '28510329',
                'password'            => Hash::make('jefe'),
                'activo'              => true,
                'rol'                 => 'Jefe de comuna',
                'pregunta_seguridad'  => '¿Qué color le gusta más?',
                'respuesta_seguridad' => Hash::make('azul'),
            ]
        );

        User::updateOrCreate(
            ['user' => 'juez'],
            [
                'nombre'              => 'Julieta',
                'apellido'            => 'Gonzáles',
                'cedula_usuario'      => '15674876',
                'password'            => Hash::make('juez'),
                'activo'              => true,
                'rol'                 => 'Juez',
                'pregunta_seguridad'  => '¿Qué color le gusta más?',
                'respuesta_seguridad' => Hash::make('azul'),
            ]
        );

        User::updateOrCreate(
            ['user' => 'comando'],
            [
                'nombre'              => 'Pedro',
                'apellido'            => 'Ramírez',
                'cedula_usuario'      => '19876543',
                'password'            => Hash::make('comando'),
                'activo'              => true,
                'rol'                 => 'Jefe de Comando',
                'pregunta_seguridad'  => '¿Qué color le gusta más?',
                'respuesta_seguridad' => Hash::make('azul'),
            ]
        );
    }
}
