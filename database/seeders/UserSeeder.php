<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'cedula_usuario' => '28510329',
            'user' => 'jefe',
            'password' => Hash::make('jefe'),
            'activo' => true
        ]);

        User::create([
            'nombre' => 'Julieta',
            'apellido' => 'Gonzáles',
            'cedula_usuario' => '15674876',
            'user' => 'juez',
            'password' => Hash::make('juez'),
            'activo' => true,
            'rol' => 'Juez'
        ]);
    }
}
