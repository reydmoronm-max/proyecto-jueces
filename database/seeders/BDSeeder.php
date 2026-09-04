<?php

namespace Database\Seeders;

use App\Models\Actas;
use App\Models\Citaciones;
use App\Models\ConsejoComunal;
use App\Models\Expediente;
use App\Models\Familia;
use App\Models\Involucrados;
use App\Models\JornadaAbuelo;
use App\Models\Persona;
use App\Models\Proyecto;
use App\Models\Visita;
use App\Models\Vocero;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BDSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();

        try {
            // -------------------------------------------------------------
            // 1. FAMILIAS
            // -------------------------------------------------------------
            $familias = [
                1 => Familia::firstOrCreate(['id' => 1], ['numero_familia' => 'Familia 1']),
                2 => Familia::firstOrCreate(['id' => 2], ['numero_familia' => 'Familia 2']),
                3 => Familia::firstOrCreate(['id' => 3], ['numero_familia' => 'Familia 3']),
                4 => Familia::firstOrCreate(['id' => 4], ['numero_familia' => 'Familia 4']),
                5 => Familia::firstOrCreate(['id' => 5], ['numero_familia' => 'Familia 5']),
                6 => Familia::firstOrCreate(['id' => 6], ['numero_familia' => 'Familia 6']),
            ];

            // -------------------------------------------------------------
            // 2. PERSONAS (Creación inicial)
            // -------------------------------------------------------------
            $personasData = [
                [
                    'cedula'              => '12345678',
                    'cedula_tipo'         => 'V',
                    'nombres'             => 'Gregorio',
                    'apellidos'           => 'Díaz',
                    'telefono'            => '04122222222',
                    'familia_id'          => 1,
                    'fecha_nacimiento'    => '1991-01-03',
                    'centro_votacion'     => 'Escuela Básica Bolivariana Centro',
                    'carnet_patria'       => '8956122587',
                    'nivel_academico'     => 'Técnico',
                    'profesion'           => 'Obrero',
                    'situacion_laboral'   => 'Empleado',
                    'vivienda'            => 'Propia',
                    'tipo_enfermedad'     => null,
                    'bono_unico_familiar' => 'No',
                    'pensionado_jubilado' => 'No',
                    'ayuda_tecnica'       => 'No',
                    'mision_vivienda'     => 'No',
                    'clap'                => 'Sí',
                    'casa_alimentacion'   => 'No',
                    'direccion'           => 'Avenida 2, entre Calles 1 y 2, Casa #4',
                    'estudia'             => 'No',
                    'genero'              => 'Masculino',
                    'parentesco'          => 'Jefe de familia',
                ],
                [
                    'cedula'              => '15389854',
                    'cedula_tipo'         => 'V',
                    'nombres'             => 'María',
                    'apellidos'           => 'Gracia',
                    'telefono'            => '04164444444',
                    'familia_id'          => 2,
                    'fecha_nacimiento'    => '1955-07-20', // > 60 años (Abuela)
                    'centro_votacion'     => 'Liceo Nacional Simón Bolívar',
                    'carnet_patria'       => '9641619685',
                    'nivel_academico'     => 'Secundaria',
                    'profesion'           => 'Comerciante',
                    'situacion_laboral'   => 'Cuenta propia',
                    'vivienda'            => 'Alquilada',
                    'tipo_enfermedad'     => 'Diabetes',
                    'bono_unico_familiar' => 'No',
                    'pensionado_jubilado' => 'Sí',
                    'ayuda_tecnica'       => 'Silla de ruedas',
                    'mision_vivienda'     => 'No',
                    'clap'                => 'Sí',
                    'casa_alimentacion'   => 'No',
                    'direccion'           => 'Calle 1, entre Av. 1 y 2, Casa #12',
                    'estudia'             => 'No',
                    'genero'              => 'Femenino',
                    'parentesco'          => 'Jefe de familia',
                ],
                [
                    'cedula'              => '87654321',
                    'cedula_tipo'         => 'V',
                    'nombres'             => 'Carlos',
                    'apellidos'           => 'Ávila',
                    'telefono'            => '04243333333',
                    'familia_id'          => 2,
                    'fecha_nacimiento'    => '1987-04-02',
                    'centro_votacion'     => 'Liceo Nacional Simón Bolívar',
                    'carnet_patria'       => '9874561328',
                    'nivel_academico'     => 'Universitario',
                    'profesion'           => 'Electricista',
                    'situacion_laboral'   => 'Empleado',
                    'vivienda'            => 'Compartida',
                    'tipo_enfermedad'     => null,
                    'bono_unico_familiar' => 'No',
                    'pensionado_jubilado' => 'No',
                    'ayuda_tecnica'       => 'No',
                    'mision_vivienda'     => 'No',
                    'clap'                => 'Sí',
                    'casa_alimentacion'   => 'No',
                    'direccion'           => 'Calle 1, entre Av. 1 y 2, Casa #12',
                    'estudia'             => 'No',
                    'genero'              => 'Masculino',
                    'parentesco'          => 'Hijo/a',
                ],
                [
                    'cedula'              => '11223344',
                    'cedula_tipo'         => 'V',
                    'nombres'             => 'Elena',
                    'apellidos'           => 'Mendoza',
                    'telefono'            => '04145556677',
                    'familia_id'          => 3,
                    'fecha_nacimiento'    => '1950-11-12', // > 60 años (Abuela)
                    'centro_votacion'     => 'Escuela Primaria Valles del Sol',
                    'carnet_patria'       => '1122445566',
                    'nivel_academico'     => 'Universitario',
                    'profesion'           => 'Docente Jubilada',
                    'situacion_laboral'   => 'Jubilado',
                    'vivienda'            => 'Propia',
                    'tipo_enfermedad'     => 'Hipertensión',
                    'bono_unico_familiar' => 'Sí',
                    'pensionado_jubilado' => 'Sí',
                    'ayuda_tecnica'       => 'Bastón',
                    'mision_vivienda'     => 'No',
                    'clap'                => 'Sí',
                    'casa_alimentacion'   => 'No',
                    'direccion'           => 'Sector Valles del Sol, Manzana B, Casa 8',
                    'estudia'             => 'No',
                    'genero'              => 'Femenino',
                    'parentesco'          => 'Jefe de familia',
                ],
                [
                    'cedula'              => '9876543',
                    'cedula_tipo'         => 'V',
                    'nombres'             => 'José',
                    'apellidos'           => 'Mendoza',
                    'telefono'            => '04128889900',
                    'familia_id'          => 3,
                    'fecha_nacimiento'    => '1948-03-25', // > 60 años (Abuelo)
                    'centro_votacion'     => 'Escuela Primaria Valles del Sol',
                    'carnet_patria'       => '9988776655',
                    'nivel_academico'     => 'Bachiller',
                    'profesion'           => 'Carpintero',
                    'situacion_laboral'   => 'Jubilado',
                    'vivienda'            => 'Propia',
                    'tipo_enfermedad'     => 'Artritis',
                    'bono_unico_familiar' => 'No',
                    'pensionado_jubilado' => 'Sí',
                    'ayuda_tecnica'       => 'Andadera',
                    'mision_vivienda'     => 'No',
                    'clap'                => 'Sí',
                    'casa_alimentacion'   => 'No',
                    'direccion'           => 'Sector Valles del Sol, Manzana B, Casa 8',
                    'estudia'             => 'No',
                    'genero'              => 'Masculino',
                    'parentesco'          => 'Cónyuge',
                ],
                [
                    'cedula'              => '14555666',
                    'cedula_tipo'         => 'V',
                    'nombres'             => 'Rosa',
                    'apellidos'           => 'Rojas',
                    'telefono'            => '04261112233',
                    'familia_id'          => 5,
                    'fecha_nacimiento'    => '1958-09-14', // > 60 años (Abuela)
                    'centro_votacion'     => 'Grupo Escolar Los Delirios',
                    'carnet_patria'       => '4455667788',
                    'nivel_academico'     => 'Técnico Superior',
                    'profesion'           => 'Enfermera Jubilada',
                    'situacion_laboral'   => 'Jubilado',
                    'vivienda'            => 'Propia',
                    'tipo_enfermedad'     => null,
                    'bono_unico_familiar' => 'No',
                    'pensionado_jubilado' => 'Sí',
                    'ayuda_tecnica'       => 'Lentes formulados',
                    'mision_vivienda'     => 'No',
                    'clap'                => 'Sí',
                    'casa_alimentacion'   => 'No',
                    'direccion'           => 'Urbanización Los Delirios, Calle Principal #23',
                    'estudia'             => 'No',
                    'genero'              => 'Femenino',
                    'parentesco'          => 'Jefe de familia',
                ],
                [
                    'cedula'              => '18999888',
                    'cedula_tipo'         => 'V',
                    'nombres'             => 'Pedro',
                    'apellidos'           => 'Pérez',
                    'telefono'            => '04147778899',
                    'familia_id'          => 4,
                    'fecha_nacimiento'    => '1995-02-18',
                    'centro_votacion'     => 'Escuela Primaria Valles del Sol',
                    'carnet_patria'       => '3344556677',
                    'nivel_academico'     => 'Universitario',
                    'profesion'           => 'Ingeniero Agrónomo',
                    'situacion_laboral'   => 'Empleado',
                    'vivienda'            => 'Alquilada',
                    'tipo_enfermedad'     => null,
                    'bono_unico_familiar' => 'No',
                    'pensionado_jubilado' => 'No',
                    'ayuda_tecnica'       => 'No',
                    'mision_vivienda'     => 'No',
                    'clap'                => 'Sí',
                    'casa_alimentacion'   => 'No',
                    'direccion'           => 'Sector Valles del Sol, Calle 3, Casa #15',
                    'estudia'             => 'No',
                    'genero'              => 'Masculino',
                    'parentesco'          => 'Jefe de familia',
                ],
                [
                    'cedula'              => '20111222',
                    'cedula_tipo'         => 'V',
                    'nombres'             => 'Ana Lucía',
                    'apellidos'           => 'Pérez',
                    'telefono'            => '04169990011',
                    'familia_id'          => 4,
                    'fecha_nacimiento'    => '1998-10-30',
                    'centro_votacion'     => 'Escuela Primaria Valles del Sol',
                    'carnet_patria'       => '2233445566',
                    'nivel_academico'     => 'Universitario',
                    'profesion'           => 'Contadora',
                    'situacion_laboral'   => 'Empleado',
                    'vivienda'            => 'Alquilada',
                    'tipo_enfermedad'     => null,
                    'bono_unico_familiar' => 'No',
                    'pensionado_jubilado' => 'No',
                    'ayuda_tecnica'       => 'No',
                    'mision_vivienda'     => 'No',
                    'clap'                => 'Sí',
                    'casa_alimentacion'   => 'No',
                    'direccion'           => 'Sector Valles del Sol, Calle 3, Casa #15',
                    'estudia'             => 'No',
                    'genero'              => 'Femenino',
                    'parentesco'          => 'Cónyuge',
                ],
                [
                    'cedula'              => '10444555',
                    'cedula_tipo'         => 'V',
                    'nombres'             => 'Roberto',
                    'apellidos'           => 'Gómez',
                    'telefono'            => '04245554433',
                    'familia_id'          => 6,
                    'fecha_nacimiento'    => '1962-05-15', // > 60 años (Abuelo)
                    'centro_votacion'     => 'Grupo Escolar Los Delirios',
                    'carnet_patria'       => '1100223344',
                    'nivel_academico'     => 'Bachiller',
                    'profesion'           => 'Mecánico',
                    'situacion_laboral'   => 'Cuenta propia',
                    'vivienda'            => 'Propia',
                    'tipo_enfermedad'     => 'Hipertensión',
                    'bono_unico_familiar' => 'No',
                    'pensionado_jubilado' => 'Sí',
                    'ayuda_tecnica'       => 'No',
                    'mision_vivienda'     => 'No',
                    'clap'                => 'Sí',
                    'casa_alimentacion'   => 'No',
                    'direccion'           => 'Urbanización Los Delirios, Bloque 4, Apto 2-B',
                    'estudia'             => 'No',
                    'genero'              => 'Masculino',
                    'parentesco'          => 'Jefe de familia',
                ],
                [
                    'cedula'              => '16777888',
                    'cedula_tipo'         => 'V',
                    'nombres'             => 'Carmen',
                    'apellidos'           => 'Fernández',
                    'telefono'            => '04123332211',
                    'familia_id'          => 6,
                    'fecha_nacimiento'    => '1980-12-05',
                    'centro_votacion'     => 'Grupo Escolar Los Delirios',
                    'carnet_patria'       => '7788990011',
                    'nivel_academico'     => 'Técnico',
                    'profesion'           => 'Administradora',
                    'situacion_laboral'   => 'Empleado',
                    'vivienda'            => 'Propia',
                    'tipo_enfermedad'     => null,
                    'bono_unico_familiar' => 'No',
                    'pensionado_jubilado' => 'No',
                    'ayuda_tecnica'       => 'No',
                    'mision_vivienda'     => 'No',
                    'clap'                => 'Sí',
                    'casa_alimentacion'   => 'No',
                    'direccion'           => 'Urbanización Los Delirios, Bloque 4, Apto 2-B',
                    'estudia'             => 'No',
                    'genero'              => 'Femenino',
                    'parentesco'          => 'Hijo/a',
                ],
            ];

            $personasInstancias = [];
            foreach ($personasData as $data) {
                $persona = Persona::updateOrCreate(
                    ['cedula' => $data['cedula']],
                    $data
                );
                $personasInstancias[$data['cedula']] = $persona;
            }

            // -------------------------------------------------------------
            // 3. CONSEJOS COMUNALES
            // -------------------------------------------------------------
            $cc1 = ConsejoComunal::updateOrCreate(
                ['rif' => 'C123456789'],
                [
                    'nombre'       => 'Brisas del Sur',
                    'jefe_comando' => $personasInstancias['12345678']->id, // Gregorio Díaz
                    'direccion'    => 'Calle 3, Avenida 1 y 2',
                ]
            );

            $cc2 = ConsejoComunal::updateOrCreate(
                ['rif' => 'C987654321'],
                [
                    'nombre'       => 'Valles del Sol',
                    'jefe_comando' => $personasInstancias['11223344']->id, // Elena Mendoza
                    'direccion'    => 'Sector Valles del Sol, Av. Principal',
                ]
            );

            $cc3 = ConsejoComunal::updateOrCreate(
                ['rif' => 'C555444333'],
                [
                    'nombre'       => 'Los Delirios Centro',
                    'jefe_comando' => $personasInstancias['14555666']->id, // Rosa Rojas
                    'direccion'    => 'Urbanización Los Delirios, Plaza Central',
                ]
            );

            // Asignar Consejo Comunal a cada persona
            $personasInstancias['12345678']->update(['consejo_comunal_id' => $cc1->id]); // Gregorio Díaz
            $personasInstancias['15389854']->update(['consejo_comunal_id' => $cc1->id]); // María Gracia
            $personasInstancias['87654321']->update(['consejo_comunal_id' => $cc1->id]); // Carlos Ávila

            $personasInstancias['11223344']->update(['consejo_comunal_id' => $cc2->id]); // Elena Mendoza
            $personasInstancias['9876543']->update(['consejo_comunal_id' => $cc2->id]);  // José Mendoza
            $personasInstancias['18999888']->update(['consejo_comunal_id' => $cc2->id]); // Pedro Pérez
            $personasInstancias['20111222']->update(['consejo_comunal_id' => $cc2->id]); // Ana Lucía Pérez

            $personasInstancias['14555666']->update(['consejo_comunal_id' => $cc3->id]); // Rosa Rojas
            $personasInstancias['10444555']->update(['consejo_comunal_id' => $cc3->id]); // Roberto Gómez
            $personasInstancias['16777888']->update(['consejo_comunal_id' => $cc3->id]); // Carmen Fernández

            // -------------------------------------------------------------
            // 4. VOCEROS
            // -------------------------------------------------------------
            $vocerosData = [
                [
                    'persona_id'       => $personasInstancias['15389854']->id, // María Gracia
                    'categoria_vocero' => 'Vocera de Salud y Bienestar',
                    'fecha_eleccion'   => '2025-03-10',
                ],
                [
                    'persona_id'       => $personasInstancias['14555666']->id, // Rosa Rojas
                    'categoria_vocero' => 'Vocera de Educación y Cultura',
                    'fecha_eleccion'   => '2025-04-15',
                ],
                [
                    'persona_id'       => $personasInstancias['18999888']->id, // Pedro Pérez
                    'categoria_vocero' => 'Vocero de Economía Comunal',
                    'fecha_eleccion'   => '2025-05-20',
                ],
                [
                    'persona_id'       => $personasInstancias['10444555']->id, // Roberto Gómez
                    'categoria_vocero' => 'Vocero de Seguridad y Defensa',
                    'fecha_eleccion'   => '2025-06-01',
                ],
            ];

            foreach ($vocerosData as $vData) {
                Vocero::updateOrCreate(
                    ['persona_id' => $vData['persona_id']],
                    $vData
                );
            }

            // -------------------------------------------------------------
            // 5. JORNADAS DEL CÍRCULO DE ABUELOS
            // -------------------------------------------------------------
            $jornadasData = [
                [
                    'nombre_jornada'     => 'Jornada Médica General y Entrega de Medicamentos',
                    'fecha_programada'   => '2026-05-10',
                    'estatus'            => 'Completada',
                    'consejo_comunal_id' => $cc1->id,
                    'detalles'           => 'Atención primaria, toma de tensión y entrega de medicamentos a adultos mayores.',
                ],
                [
                    'nombre_jornada'     => 'Jornada de Alimentación Especial y Despistaje',
                    'fecha_programada'   => '2026-08-15',
                    'estatus'            => 'Planificada',
                    'consejo_comunal_id' => $cc2->id,
                    'detalles'           => 'Entrega de suplementos nutricionales y evaluación de salud preventiva.',
                ],
                [
                    'nombre_jornada'     => 'Taller de Recreación y Bailoterapia Senior',
                    'fecha_programada'   => '2026-06-20',
                    'estatus'            => 'Completada',
                    'consejo_comunal_id' => $cc3->id,
                    'detalles'           => 'Actividades físicas adaptadas, dinámicas de integración y compartir comunitario.',
                ],
            ];

            foreach ($jornadasData as $jData) {
                JornadaAbuelo::updateOrCreate(
                    ['nombre_jornada' => $jData['nombre_jornada'], 'consejo_comunal_id' => $jData['consejo_comunal_id']],
                    $jData
                );
            }

            // -------------------------------------------------------------
            // 6. PROYECTOS COMUNALES
            // -------------------------------------------------------------
            $proyectosData = [
                [
                    'nombre'            => 'Rehabilitación del Alumbrado Público Sector A',
                    'sector_productivo' => 'Servicios Públicos',
                    'presupuesto'       => 15000.00,
                    'responsable'       => 'Carlos Ávila',
                    'fecha_inicio'      => '2026-02-01',
                    'estatus'           => 'Completado',
                    'descripcion'       => 'Sustitución de bombillos defectuosos e instalación de 40 luminarias LED en calles principales.',
                ],
                [
                    'nombre'            => 'Construcción de Cancha Deportiva Comunitaria',
                    'sector_productivo' => 'Infraestructura',
                    'presupuesto'       => 45000.00,
                    'responsable'       => 'Gregorio Díaz',
                    'fecha_inicio'      => '2026-04-15',
                    'estatus'           => 'En planificación',
                    'descripcion'       => 'Adecuación de terreno, cercado perimetral y cimentación de parque recreativo infantil y cancha.',
                ],
                [
                    'nombre'            => 'Mantenimiento del Sistema de Agua Potable',
                    'sector_productivo' => 'Agua y Sanidad',
                    'presupuesto'       => 28000.00,
                    'responsable'       => 'María Gracia',
                    'fecha_inicio'      => '2026-01-10',
                    'estatus'           => 'Paralizado',
                    'descripcion'       => 'Reparación de tuberías matrices y sustitución de bomba de impulsión de 10 HP.',
                ],
                [
                    'nombre'            => 'Huerto Comunitario y Cultivo Urbano',
                    'sector_productivo' => 'Agrícola',
                    'presupuesto'       => 8500.00,
                    'responsable'       => 'Elena Mendoza',
                    'fecha_inicio'      => '2026-03-01',
                    'estatus'           => 'En planificación',
                    'descripcion'       => 'Siembra de hortalizas y legumbres de ciclo corto para el abastecimiento familiar comunitario.',
                ],
            ];

            foreach ($proyectosData as $pData) {
                Proyecto::updateOrCreate(
                    ['nombre' => $pData['nombre']],
                    $pData
                );
            }

            // -------------------------------------------------------------
            // 7. VISITAS, EXPEDIENTES, CITACIONES Y ACTAS
            // -------------------------------------------------------------

            // --- CASO 1: DENUNCIA ABIERTA ---
            $v1 = Visita::create([
                'persona_id' => $personasInstancias['87654321']->id, // Carlos Ávila
                'proposito'  => 'Presentar reclamo por ruidos molestos',
                'de_parte'   => 'De sí mismo',
            ]);

            $exp1 = Expediente::create([
                'caso' => 'Contaminación acústica y perturbación de la tranquilidad',
                'tipo_caso' => 'Convivencia vecinal',
                'categoria' => 'Ruido',
                'estatus'   => 'Abierto',
            ]);

            Involucrados::create([
                'persona_id'    => $personasInstancias['87654321']->id, // Carlos Ávila
                'expediente_id' => $exp1->id,
                'rol'           => 'denunciante',
            ]);

            Involucrados::create([
                'persona_id'    => $personasInstancias['20111222']->id, // Ana Lucía Pérez
                'expediente_id' => $exp1->id,
                'rol'           => 'denunciado',
            ]);

            Actas::create([
                'expediente_id'      => $exp1->id,
                'tipo_acta'          => 'recepcion',
                'lo_atiende_juez_id' => 2, // Julieta (Juez)
                'contenido'          => "Requirente: El ciudadano Carlos Ávila expone perturbación continua por volumen excesivo a altas horas de la noche.\nReceptor: Despacho del Juez de Paz. Se registra el asunto y se da apertura al expediente formal.",
            ]);


            // --- CASO 2: DENUNCIA EN PROCESO (CON CITACIÓN) ---
            Visita::create([
                'persona_id' => $personasInstancias['18999888']->id, // Pedro Pérez
                'proposito'  => 'Solicitud de mediación por conflicto de linderos',
                'de_parte'   => 'De sí mismo',
            ]);

            Visita::create([
                'persona_id' => $personasInstancias['16777888']->id, // Carmen Fernández
                'proposito'  => 'Comparecencia previa a la cita',
                'de_parte'   => 'Citada',
            ]);

            $exp2 = Expediente::create([
                'caso' => 'Disputa de linderos y cercado no autorizado de terreno',
                'tipo_caso' => 'Vivienda y propiedad',
                'categoria' => 'Linderos',
                'estatus'   => 'En proceso',
            ]);

            Involucrados::create([
                'persona_id'    => $personasInstancias['18999888']->id, // Pedro Pérez
                'expediente_id' => $exp2->id,
                'rol'           => 'denunciante',
            ]);

            Involucrados::create([
                'persona_id'    => $personasInstancias['16777888']->id, // Carmen Fernández
                'expediente_id' => $exp2->id,
                'rol'           => 'denunciado',
            ]);

            Citaciones::create([
                'expediente_id'      => $exp2->id,
                'fecha_citacion'     => '2026-08-10',
                'hora_citacion'      => '09:30:00',
                'asistio'            => null,
                'observaciones'      => 'Primera citación oficial para conciliación de linderos.',
                'solicita_cambio_id' => null,
                'estatus'            => true, // Activa
            ]);

            Actas::create([
                'expediente_id'      => $exp2->id,
                'tipo_acta'          => 'recepcion',
                'lo_atiende_juez_id' => 2,
                'contenido'          => "Requirente: Pedro Pérez solicita inspección y fijación justa de límites entre parcelas colindantes.\nReceptor: Juez de Paz Municipal.",
            ]);

            Actas::create([
                'expediente_id'      => $exp2->id,
                'tipo_acta'          => 'conciliacion',
                'lo_atiende_juez_id' => 2,
                'contenido'          => 'Se remite citación formal a la ciudadana Carmen Fernández para el 10/08/2026 a las 09:30 AM.',
            ]);


            // --- CASO 3: DENUNCIA CERRADA ---
            Visita::create([
                'persona_id' => $personasInstancias['12345678']->id, // Gregorio Díaz
                'proposito'  => 'Denuncia por incumplimiento de acuerdo de pago comercial',
                'de_parte'   => 'De sí mismo',
            ]);

            Visita::create([
                'persona_id' => $personasInstancias['20111222']->id, // Ana Lucía Pérez
                'proposito'  => 'Comparecer a audiencia de conciliación',
                'de_parte'   => 'Citada',
            ]);

            $exp3 = Expediente::create([
                'caso' => 'Abuso sexual',
                'tipo_caso' => 'Violencia y grupos vulnerables',
                'categoria' => 'Violencia de género',
                'estatus'   => 'Cerrado',
            ]);

            Involucrados::create([
                'persona_id'    => $personasInstancias['12345678']->id, // Gregorio Díaz
                'expediente_id' => $exp3->id,
                'rol'           => 'denunciante',
            ]);

            Involucrados::create([
                'persona_id'    => $personasInstancias['20111222']->id, // Ana Lucía Pérez
                'expediente_id' => $exp3->id,
                'rol'           => 'denunciado',
            ]);

            Citaciones::create([
                'expediente_id'      => $exp3->id,
                'fecha_citacion'     => '2026-06-05',
                'hora_citacion'      => '11:00:00',
                'asistio'            => 'Sí',
                'observaciones'      => 'Ambas partes comparecieron y suscribieron acuerdo de cancelación en 3 cuotas.',
                'solicita_cambio_id' => null,
                'estatus'            => false, // Finalizada/Inactiva
            ]);

            Actas::create([
                'expediente_id'      => $exp3->id,
                'tipo_acta'          => 'recepcion',
                'lo_atiende_juez_id' => 2,
                'contenido'          => 'Requirente: Gregorio Díaz expone que su pareja le pega y lo insulta.',
            ]);

            Actas::create([
                'expediente_id'      => $exp3->id,
                'tipo_acta'          => 'conciliacion',
                'lo_atiende_juez_id' => 2,
                'contenido'          => 'Acuerdos: La parte denunciada se disculpó.',
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
