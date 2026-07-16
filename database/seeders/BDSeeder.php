<?php

namespace Database\Seeders;

use App\Models\Actas;
use App\Models\ConsejoComunal;
use App\Models\Expediente;
use App\Models\Familia;
use App\Models\Involucrados;
use App\Models\Persona;
use App\Models\Visita;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BDSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Familias

            Familia::create([
                'numero_familia' => 'Familia 1'
            ]);
            
            Familia::create([
                'numero_familia' => 'Familia 2'
            ]);
        
        // Fin de Familias
    
        

        // Personas

        DB::beginTransaction();

            $persona = Persona::firstOrCreate(
                ['cedula' => '15389854'],
                [
                    'cedula_tipo' => 'V',
                    'nombres'     => 'María',
                    'apellidos'   => 'Gracia',
                    'telefono'    => '04164444444',
                ]
            );

            // Update all census and basic fields
            $persona->update([
                'nombres'             => 'María',
                'apellidos'           => 'Gracia',
                'telefono'            => '04164444444',
                'familia_id'          => 2,
                'fecha_nacimiento'    => '1960-07-20',
                'centro_votacion'     => 'Centro 2',
                'carnet_patria'       => '9641619685',
                'nivel_academico'     => 'Secundaria',
                'profesion'           => 'Comerciante',
                'situacion_laboral'   => 'Empleado',
                'vivienda'            => 'Alquilada',
                'tipo_enfermedad'     => 'Diabetes',
                'bono_unico_familiar' => 'No',
                'pensionado_jubilado' => 'Sí',
                'ayuda_tecnica'       => 'Ayuda',
                'mision_vivienda'     => 'No',
                'clap'                => 'Sí',
                'casa_alimentacion'   => 'No',
                'direccion'           => 'Calle 1, entre Av. 1 y 2',
                'estudia'             => 'No',
                'genero'              => 'Femenino',
                'parentesco'          => 'Jefe de Familia',
                'consejo_comunal_id'  => null,
            ]);

        DB::commit();




        // Consejo Comunal


        ConsejoComunal::create([
            'nombre'       => 'Brisas del Sur',
            'rif'          => 'C123456789',
            'jefe_comando' => 1,
            'direccion'    => 'Calle 3, Avenida 1 y 2',
        ]);


        // Fin de Consejo Comunal



        DB::beginTransaction();

            $persona = Persona::firstOrCreate(
                ['cedula' => '12345678'],
                [
                    'cedula_tipo' => 'V',
                    'nombres'     => 'Gregorio',
                    'apellidos'   => 'Díaz',
                    'telefono'    => '04122222222',
                ]
            );

            // Update all census and basic fields
            $persona->update([
                'nombres'             => 'Gregorio',
                'apellidos'           => 'Díaz',
                'telefono'            => '04122222222',
                'familia_id'          => 1,
                'fecha_nacimiento'    => '1991-01-03',
                'centro_votacion'     => 'Centro 1',
                'carnet_patria'       => '8956122587',
                'nivel_academico'     => 'Técnico',
                'profesion'           => 'Obrero',
                'situacion_laboral'   => 'Empleado',
                'vivienda'            => 'Propia',
                'tipo_enfermedad'     => 'Diabetes',
                'bono_unico_familiar' => 'No',
                'pensionado_jubilado' => 'No',
                'ayuda_tecnica'       => 'Ayuda',
                'mision_vivienda'     => 'No',
                'clap'                => 'Sí',
                'casa_alimentacion'   => 'No',
                'direccion'           => 'Avenida 2, entre Calles 1 y 2',
                'estudia'             => 'No',
                'genero'              => 'Masculino',
                'parentesco'          => 'Jefe de familia',
                'consejo_comunal_id'  => 1,
            ]);

        DB::commit();

        DB::beginTransaction();

            $persona = Persona::firstOrCreate(
                ['cedula' => '87654321'],
                [
                    'cedula_tipo' => 'V',
                    'nombres'     => 'Carlos',
                    'apellidos'   => 'Ávila',
                    'telefono'    => '04243333333',
                ]
            );

            // Update all census and basic fields
            $persona->update([
                'nombres'             => 'Carlos',
                'apellidos'           => 'Ávila',
                'telefono'            => '04243333333',
                'familia_id'          => 2,
                'fecha_nacimiento'    => '1987-04-02',
                'centro_votacion'     => 'Centro 2',
                'carnet_patria'       => '9874561328',
                'nivel_academico'     => 'Técnico',
                'profesion'           => 'Comerciante',
                'situacion_laboral'   => 'Empleado',
                'vivienda'            => 'Alquilada',
                'tipo_enfermedad'     => null,
                'bono_unico_familiar' => 'No',
                'pensionado_jubilado' => 'No',
                'ayuda_tecnica'       => 'No',
                'mision_vivienda'     => 'No',
                'clap'                => 'Sí',
                'casa_alimentacion'   => 'No',
                'direccion'           => 'Calle 1, entre Av. 1 y 2',
                'estudia'             => 'No',
                'genero'              => 'Masculino',
                'parentesco'          => 'Hijo/a',
                'consejo_comunal_id'  => 1,
            ]);

        DB::commit();


        // Fin de Personas




        // Visitas

        
        Visita::create([
            'persona_id' => 2,
            'proposito'  => 'Hacer una denuncia',
            'de_parte'   => 'De sí mismo',
        ]);

        Visita::create([
            'persona_id' => 3,
            'proposito'  => 'Comparecer',
            'de_parte'   => 'De sí mismo',
        ]);


        // Fin de Visitas



        // Denuncia

        DB::beginTransaction();
        
            // Guardar expediente
            $expediente = Expediente::create([
                'motivo_denuncia' => 'Deuda',
                'estatus' => 'Abierto',
            ]);

            // Relacionar persona y expediente en tabla involucrados
            Involucrados::create([
                'persona_id' => 2,
                'expediente_id' => 1,
                'rol' => 'denunciante',
            ]);

            Actas::create([
                'expediente_id' => 1,
                'tipo_acta' => 'recepcion',
                'contenido' => 'Requirente: Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                                Receptor: Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                                Acuerdos: Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.',
                'lo_atiende_juez_id' => 2,
            ]);

            DB::commit();


    }
}
