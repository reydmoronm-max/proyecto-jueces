<?php

namespace Tests\Feature;

use App\Models\ConsejoComunal;
use App\Models\Familia;
use App\Models\JornadaAbuelo;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CirculoAbuelosModuleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $jefe = User::create([
            'nombre' => 'Jefe',
            'apellido' => 'Comuna',
            'cedula_usuario' => '10000001',
            'password' => Hash::make('clave-segura'),
            'activo' => true,
            'rol' => 'Jefe de comuna',
        ]);

        $this->actingAs($jefe);
    }

    public function test_index_search_is_available_for_abuelos_and_jornadas(): void
    {
        $consejo = $this->crearConsejo();
        $familia = Familia::create([
            'numero_familia' => 'FAMILIA-CIRCULO-001',
            'consejo_comunal_id' => $consejo->id,
        ]);

        Persona::create([
            'cedula' => '12345678',
            'cedula_tipo' => 'V',
            'nombres' => 'María',
            'apellidos' => 'Pérez',
            'familia_id' => $familia->id,
            'fecha_nacimiento' => '1940-01-01',
            'genero' => 'Femenino',
            'tipo_enfermedad' => 'Hipertensión',
            'direccion' => 'Calle Principal',
        ]);

        JornadaAbuelo::create([
            'nombre_jornada' => 'Atención Oftalmológica',
            'fecha_programada' => '2026-06-15',
            'estatus' => 'Planificada',
            'consejo_comunal_id' => $consejo->id,
            'detalles' => 'Consulta de visión y entrega de lentes',
        ]);

        $response = $this->get(route('circulo-abuelos.index', [
            'search' => 'no-coincide',
            'consejo_comunal_id' => 99999,
        ]));

        $response->assertOk();
        $response->assertSee('id="buscar-circulo-abuelos"', false);
        $response->assertSee('placeholder="Buscar en todos los registros..."', false);
        $response->assertSee('data-search=', false);
        $response->assertSee('on(\'input\', filtrarCirculoAbuelos)', false);

        foreach ([
            'María',
            'Pérez',
            '12345678',
            'Hipertensión',
            'Calle Principal',
            'FAMILIA-CIRCULO-001',
            'Atención Oftalmológica',
            'Consulta de visión y entrega de lentes',
            'Planificada',
            'Consejo de Prueba',
        ] as $valor) {
            $response->assertSee($valor);
        }

        $response->assertDontSee('name="search"', false);
        $response->assertDontSee('name="consejo_comunal_id" class="form-select"', false);
        $response->assertDontSee('>Filtrar</button>', false);
    }

    private function crearConsejo(): ConsejoComunal
    {
        $jefe = Persona::create([
            'cedula' => '87654321',
            'cedula_tipo' => 'V',
            'nombres' => 'Jefe',
            'apellidos' => 'Consejo',
        ]);

        return ConsejoComunal::create([
            'nombre' => 'Consejo de Prueba',
            'rif' => 'J-12345678-0',
            'jefe_comando' => $jefe->id,
            'direccion' => 'Dirección de Prueba',
        ]);
    }
}
