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
        Schema::create('citaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expediente_id')->constrained('expedientes')->onDelete('cascade');
            // $table->enum('numero_citacion', ['Primera', 'Segunda', 'Tercera'])->default('Primera');
            $table->string('hora_citacion');
            $table->date('fecha_citacion');
            $table->string('asistio')->nullable(); // Puede ser 'sí', 'no' o null si aún no se ha registrado la asistencia
            $table->string('observaciones')->nullable();
            $table->foreignId('solicita_cambio_id')->nullable()->constrained('personas')->onDelete('set null'); // Persona que solicita el cambio de fecha/hora
            $table->boolean('estatus')->default(true); // true para activa, false para inactiva
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citaciones');
    }
};
