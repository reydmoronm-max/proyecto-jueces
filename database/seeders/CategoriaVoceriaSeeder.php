<?php

namespace Database\Seeders;

use App\Models\CategoriaVoceria;
use Illuminate\Database\Seeder;

class CategoriaVoceriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoriasData = [
            ['nombre' => 'Vocero Principal / Coordinador', 'descripcion' => 'Coordinación general de la vocería'],
            ['nombre' => 'Unidad Administrativa y Financiera', 'descripcion' => 'Gestión administrativa y financiera comunal'],
            ['nombre' => 'Unidad de Contraloría Social', 'descripcion' => 'Supervisión y contraloría ciudadana'],
            ['nombre' => 'Comité de Alimentación (CLAP)', 'descripcion' => 'Distribución y gestión alimentaria'],
            ['nombre' => 'Comité de Salud y Prevención', 'descripcion' => 'Salud comunitaria y medicina preventiva'],
            ['nombre' => 'Comité de Tierras Urbanas/Rurales', 'descripcion' => 'Gestión de tierras y habitabilidad'],
            ['nombre' => 'Comité de Deporte, Recreación y Cultura', 'descripcion' => 'Fomento de actividades culturales y deportivas'],
            ['nombre' => 'Vocera de Salud y Bienestar', 'descripcion' => 'Vocería de salud y bienestar comunitario'],
            ['nombre' => 'Vocera de Educación y Cultura', 'descripcion' => 'Vocería de formación y cultura local'],
            ['nombre' => 'Vocero de Economía Comunal', 'descripcion' => 'Vocería de desarrollo económico territorial'],
            ['nombre' => 'Vocero de Seguridad y Defensa', 'descripcion' => 'Vocería de protección y seguridad comunitaria'],
        ];

        foreach ($categoriasData as $cat) {
            CategoriaVoceria::updateOrCreate(
                ['nombre' => $cat['nombre']],
                ['descripcion' => $cat['descripcion'], 'activo' => true]
            );
        }
    }
}
