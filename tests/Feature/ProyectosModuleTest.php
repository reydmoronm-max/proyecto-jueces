<?php

namespace Tests\Feature;

use App\Models\Proyecto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProyectosModuleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $usuario = User::create([
            'nombre' => 'Jefe',
            'apellido' => 'Comuna',
            'cedula_usuario' => '10000001',
            'password' => Hash::make('clave-segura'),
            'activo' => true,
            'rol' => 'Jefe de comuna',
        ]);

        $this->actingAs($usuario);
    }

    public function test_index_has_live_search_for_all_project_fields_and_ignores_old_filters(): void
    {
        $proyecto = Proyecto::create([
            'nombre' => 'Rehabilitación de cancha',
            'sector_productivo' => 'Deporte comunitario',
            'presupuesto' => 12500.50,
            'responsable' => 'María Pérez',
            'fecha_inicio' => '2026-10-15',
            'estatus' => 'En planificación',
            'descripcion' => 'Mejoras integrales para la cancha del sector.',
        ]);

        $response = $this->get(route('proyectos.index', [
            'search' => 'no-coincide',
            'estatus_proyecto' => 'Paralizado',
        ]));

        $response->assertOk();
        $response->assertSee('id="buscar-proyecto"', false);
        $response->assertSee('placeholder="Buscar en todos los registros..."', false);
        $response->assertSee('data-search=', false);
        $response->assertSee('on(\'input\', filtrarProyectos)', false);

        foreach ([
            (string) $proyecto->id,
            $proyecto->nombre,
            $proyecto->sector_productivo,
            '12500.5',
            '12.500,50',
            $proyecto->responsable,
            '15-10-2026',
            $proyecto->estatus,
            $proyecto->descripcion,
        ] as $valor) {
            $response->assertSee($valor);
        }

        $response->assertDontSee('name="search"', false);
        $response->assertDontSee('name="estatus_proyecto"', false);
        $response->assertDontSee('>Buscar</button>', false);
    }
}
