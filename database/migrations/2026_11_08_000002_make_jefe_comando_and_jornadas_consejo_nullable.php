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
        // 1. Modificar 'consejos_comunales.jefe_comando' a nullable
        Schema::table('consejos_comunales', function (Blueprint $table) {
            try {
                $table->dropForeign(['jefe_comando']);
            } catch (\Throwable $e) {
                // SQLite u otros entornos que no tengan nombre foráneo explícito
            }
        });

        Schema::table('consejos_comunales', function (Blueprint $table) {
            $table->unsignedBigInteger('jefe_comando')->nullable()->change();
            $table->foreign('jefe_comando')
                  ->references('id')
                  ->on('personas')
                  ->nullOnDelete();
        });

        // 2. Modificar 'jornada_abuelos.consejo_comunal_id' a nullable con nullOnDelete
        if (Schema::hasTable('jornada_abuelos')) {
            Schema::table('jornada_abuelos', function (Blueprint $table) {
                try {
                    $table->dropForeign(['consejo_comunal_id']);
                } catch (\Throwable $e) {
                    // SQLite u otros entornos
                }
            });

            Schema::table('jornada_abuelos', function (Blueprint $table) {
                $table->unsignedBigInteger('consejo_comunal_id')->nullable()->change();
                $table->foreign('consejo_comunal_id')
                      ->references('id')
                      ->on('consejos_comunales')
                      ->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consejos_comunales', function (Blueprint $table) {
            try {
                $table->dropForeign(['jefe_comando']);
            } catch (\Throwable $e) {}
            $table->unsignedBigInteger('jefe_comando')->nullable(false)->change();
            $table->foreign('jefe_comando')->references('id')->on('personas')->cascadeOnDelete();
        });

        if (Schema::hasTable('jornada_abuelos')) {
            Schema::table('jornada_abuelos', function (Blueprint $table) {
                try {
                    $table->dropForeign(['consejo_comunal_id']);
                } catch (\Throwable $e) {}
                $table->unsignedBigInteger('consejo_comunal_id')->nullable(false)->change();
                $table->foreign('consejo_comunal_id')->references('id')->on('consejos_comunales')->cascadeOnDelete();
            });
        }
    }
};

