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
        Schema::create('actas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expediente_id')->constrained('expedientes')->onDelete('cascade');
            $table->enum('tipo_acta', ['recepcion', 'conciliacion'])->default('recepcion');
            $table->text('contenido');
            // $table->string('codigo_acta')->unique(); // Este código aún no sé cómo hacerlo, pero me imagino que no debe ser manual, así que por ahora lo dejo fuera.
            // $table->string('firma_juez')->nullable(); // Puede ser null si aún no se ha firmado el acta, aún no sé cómo manejar esto, así que por ahora lo dejo fuera.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actas');
    }
};
