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
        Schema::create('expedientes', function (Blueprint $table) {
            $table->id();
            // $table->string('codigo_expediente')->unique(); //Este código aún no sé cómmo hacerlo, pero me imagino que no debe ser manual, asi que por ahora lo dejo fuera.
            $table->string('caso');
            $table->string('tipo_caso');
            $table->string('categoria');
            $table->enum('estatus', ['Abierto', 'En proceso', 'Cerrado'])->default('Abierto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expedientes');
    }
};
