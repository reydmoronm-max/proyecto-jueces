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
            try {
                $table->dropUnique('personas_cedula_unique');
            } catch (\Throwable $e) {
                // Ignore if not present (e.g. SQLite memory testing without full index name)
            }
            $table->unique(['cedula_tipo', 'cedula'], 'personas_cedula_tipo_cedula_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personas', function (Blueprint $table) {
            try {
                $table->dropUnique('personas_cedula_tipo_cedula_unique');
            } catch (\Throwable $e) {
                // Ignore
            }
            $table->unique('cedula', 'personas_cedula_unique');
        });
    }
};

