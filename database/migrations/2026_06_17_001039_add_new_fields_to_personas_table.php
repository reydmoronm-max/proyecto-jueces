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
            if (!Schema::hasColumn('personas', 'estudia')) {
                $table->string('estudia')->default('No')->nullable();
            }
            if (!Schema::hasColumn('personas', 'genero')) {
                $table->string('genero')->nullable();
            }
            if (!Schema::hasColumn('personas', 'parentesco')) {
                $table->string('parentesco')->nullable();
            }
            if (!Schema::hasColumn('personas', 'consejo_comunal_id')) {
                $table->foreignId('consejo_comunal_id')
                      ->nullable()
                      ->constrained('consejos_comunales')
                      ->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personas', function (Blueprint $table) {
            $table->dropForeign(['consejo_comunal_id']);
            $table->dropColumn(['estudia', 'genero', 'parentesco', 'consejo_comunal_id']);
        });
    }
};
