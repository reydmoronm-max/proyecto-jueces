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
        // 1. Agregar campo direccion a la tabla familias
        Schema::table('familias', function (Blueprint $table) {
            $table->text('direccion')->nullable()->after('clap');
        });

        // 2. Migrar direcciones existentes desde personas hacia sus respectivas familias
        if (Schema::hasTable('personas') && Schema::hasColumn('personas', 'direccion')) {
            $personasConDireccion = DB::table('personas')
                ->whereNotNull('familia_id')
                ->whereNotNull('direccion')
                ->where('direccion', '!=', '')
                ->orderByRaw("CASE WHEN parentesco = 'Jefe de familia' THEN 0 ELSE 1 END")
                ->get();

            $familiasActualizadas = [];
            foreach ($personasConDireccion as $p) {
                if (!in_array($p->familia_id, $familiasActualizadas)) {
                    DB::table('familias')->where('id', $p->familia_id)->update([
                        'direccion' => $p->direccion
                    ]);
                    $familiasActualizadas[] = $p->familia_id;
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('familias', function (Blueprint $table) {
            $table->dropColumn('direccion');
        });
    }
};
