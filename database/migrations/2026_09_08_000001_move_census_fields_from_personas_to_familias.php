<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Agregar campos a la tabla 'familias'
        Schema::table('familias', function (Blueprint $table) {
            $table->foreignId('consejo_comunal_id')
                ->nullable()
                ->after('numero_familia')
                ->constrained('consejos_comunales')
                ->onDelete('set null');
            $table->string('vivienda')->nullable()->after('consejo_comunal_id');
            $table->string('mision_vivienda')->nullable()->after('vivienda');
            $table->string('bono_unico_familiar')->nullable()->after('mision_vivienda');
            $table->string('clap')->nullable()->after('bono_unico_familiar');
        });

        // 2. Migrar datos existentes de personas a sus familias
        // Priorizar el jefe de familia o cualquier integrante registrado
        if (Schema::hasTable('personas')) {
            $personasConFamilia = DB::table('personas')
                ->whereNotNull('familia_id')
                ->orderByRaw("CASE WHEN parentesco = 'Jefe de familia' THEN 0 ELSE 1 END")
                ->get();

            $familiasActualizadas = [];

            foreach ($personasConFamilia as $persona) {
                if (!in_array($persona->familia_id, $familiasActualizadas)) {
                    $updateData = [];

                    if (isset($persona->consejo_comunal_id) && $persona->consejo_comunal_id) {
                        $updateData['consejo_comunal_id'] = $persona->consejo_comunal_id;
                    }
                    if (isset($persona->vivienda) && $persona->vivienda) {
                        $updateData['vivienda'] = $persona->vivienda;
                    }
                    if (isset($persona->mision_vivienda) && $persona->mision_vivienda) {
                        $updateData['mision_vivienda'] = $persona->mision_vivienda;
                    }
                    if (isset($persona->bono_unico_familiar) && $persona->bono_unico_familiar) {
                        $updateData['bono_unico_familiar'] = $persona->bono_unico_familiar;
                    }
                    if (isset($persona->clap) && $persona->clap) {
                        $updateData['clap'] = $persona->clap;
                    }

                    if (!empty($updateData)) {
                        DB::table('familias')->where('id', $persona->familia_id)->update($updateData);
                        $familiasActualizadas[] = $persona->familia_id;
                    }
                }
            }
        }

        // 3. Eliminar campos de la tabla 'personas'
        Schema::table('personas', function (Blueprint $table) {
            // Eliminar clave foránea si existe
            if (Schema::hasColumn('personas', 'consejo_comunal_id')) {
                $table->dropForeign(['consejo_comunal_id']);
            }

            $columnasAEliminar = [];
            foreach ([
                'consejo_comunal_id',
                'vivienda',
                'bono_unico_familiar',
                'ayuda_tecnica',
                'mision_vivienda',
                'clap',
                'casa_alimentacion'
            ] as $col) {
                if (Schema::hasColumn('personas', $col)) {
                    $columnasAEliminar[] = $col;
                }
            }

            if (!empty($columnasAEliminar)) {
                $table->dropColumn($columnasAEliminar);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Restaurar columnas en personas
        Schema::table('personas', function (Blueprint $table) {
            $table->string('vivienda')->nullable();
            $table->string('bono_unico_familiar')->nullable();
            $table->string('ayuda_tecnica')->nullable();
            $table->string('mision_vivienda')->nullable();
            $table->string('clap')->nullable();
            $table->string('casa_alimentacion')->nullable();
            $table->foreignId('consejo_comunal_id')
                ->nullable()
                ->constrained('consejos_comunales')
                ->onDelete('set null');
        });

        // 2. Restaurar datos desde familias hacia personas
        $familias = DB::table('familias')->get();
        foreach ($familias as $fam) {
            DB::table('personas')->where('familia_id', $fam->id)->update([
                'vivienda'            => $fam->vivienda,
                'bono_unico_familiar' => $fam->bono_unico_familiar,
                'mision_vivienda'     => $fam->mision_vivienda,
                'clap'                => $fam->clap,
                'consejo_comunal_id'  => $fam->consejo_comunal_id,
            ]);
        }

        // 3. Eliminar columnas de familias
        Schema::table('familias', function (Blueprint $table) {
            $table->dropForeign(['consejo_comunal_id']);
            $table->dropColumn([
                'consejo_comunal_id',
                'vivienda',
                'mision_vivienda',
                'bono_unico_familiar',
                'clap'
            ]);
        });
    }
};
