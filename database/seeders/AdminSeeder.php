<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Limpia completamente la base de datos y registra únicamente al usuario con rol "Jefe de comuna" (admin).
     */
    public function run(): void
    {
        $driver = DB::getDriverName();

        // 1. Desactivar temporalmente las restricciones de claves foráneas
        if ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        } elseif ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        }

        // 2. Tablas a limpiar
        $tables = [
            'audits',
            'actas',
            'citaciones',
            'involucrados',
            'expedientes',
            'visitas',
            'voceros',
            'jornada_abuelos',
            'proyectos',
            'personas',
            'familias',
            'consejos_comunales',
            'categoria_vocerias',
            'enfermedades',
            'profesiones',
            'users',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
            }
        }

        // 3. Reactivar restricciones de claves foráneas
        if ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } elseif ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        }

        // 4. Crear el único usuario Administrador (Jefe de comuna)
        User::create([
            'nombre'              => 'Juan',
            'apellido'            => 'Pérez',
            'cedula_usuario'      => '11123456',
            'password'            => Hash::make('jefe'),
            'activo'              => true,
            'rol'                 => 'Jefe de comuna',
            'pregunta_seguridad'  => '¿Qué color le gusta más?',
            'respuesta_seguridad' => Hash::make('azul'),
        ]);
    }
}
