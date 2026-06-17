<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('personas', function (Blueprint $table) {
            $table->foreignId('familia_id')
                  ->nullable()
                  ->constrained('familias')
                  ->onDelete('set null');
            $table->date('fecha_nacimiento')->nullable();
            $table->integer('cantidad_integrantes')->nullable();
            $table->string('centro_votacion')->nullable();
            $table->string('carnet_patria')->nullable();
            $table->string('nivel_academico')->nullable();
            $table->string('profesion')->nullable();
            $table->string('situacion_laboral')->nullable();
            $table->string('vivienda')->nullable();
            $table->string('tipo_enfermedad')->nullable();
            $table->string('bono_unico_familiar')->nullable();
            $table->string('pensionado_jubilado')->nullable();
            $table->string('ayuda_tecnica')->nullable();
            $table->string('mision_vivienda')->nullable();
            $table->string('clap')->nullable();
            $table->string('casa_alimentacion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personas', function (Blueprint $table) {
            $table->dropForeign(['familia_id']);
            $table->dropColumn([
                'familia_id',
                'fecha_nacimiento',
                'cantidad_integrantes',
                'centro_votacion',
                'carnet_patria',
                'nivel_academico',
                'profesion',
                'situacion_laboral',
                'vivienda',
                'tipo_enfermedad',
                'bono_unico_familiar',
                'pensionado_jubilado',
                'ayuda_tecnica',
                'mision_vivienda',
                'clap',
                'casa_alimentacion'
            ]);
        });
    }
};
