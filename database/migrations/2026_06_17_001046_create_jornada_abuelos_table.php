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
        Schema::create('jornada_abuelos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_jornada');
            $table->date('fecha_programada');
            $table->string('estatus')->default('Planificada'); // Planificada, Completada, Suspendida
            $table->foreignId('consejo_comunal_id')
                  ->constrained('consejos_comunales')
                  ->onDelete('cascade');
            $table->text('detalles')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jornada_abuelos');
    }
};
