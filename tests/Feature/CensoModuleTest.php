<?php

namespace Tests\Feature;

use App\Models\ConsejoComunal;
use App\Models\Familia;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CensoModuleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $administrador = User::create([
            'nombre' => 'Jefe',
            'apellido' => 'Comuna',
            'cedula_usuario' => '10000001',
            'password' => Hash::make('clave-segura'),
            'activo' => true,
            'rol' => 'Jefe de comuna',
        ]);

        $this->actingAs($administrador);
    }

    public function test_index_has_live_search_data_and_keeps_select_filters(): void
    {
        $consejo = $this->crearConsejo('Consejo Las Flores', '11111111');
        $familia = Familia::create([
            'numero_familia' => 'FAMILIA-001',
            'consejo_comunal_id' => $consejo->id,
            'vivienda' => 'Propia',
            'mision_vivienda' => 'Sí',
            'bono_unico_familiar' => 'No',
            'clap' => 'Sí',
        ]);
        $persona = $this->crearPersona($familia, [
            'cedula' => '12345678',
            'nombres' => 'María',
            'apellidos' => 'Pérez',
        ]);
        $otroConsejo = $this->crearConsejo('Consejo El Norte', '22222222');
        $otraFamilia = Familia::create([
            'numero_familia' => 'FAMILIA-002',
            'consejo_comunal_id' => $otroConsejo->id,
            'vivienda' => 'Alquilada',
            'mision_vivienda' => 'No',
            'bono_unico_familiar' => 'Sí',
            'clap' => 'No',
        ]);
        $this->crearPersona($otraFamilia, [
            'cedula' => '23456789',
            'nombres' => 'José',
            'apellidos' => 'Gómez',
        ]);

        $response = $this->get(route('censo.index'));

        $response->assertOk();
        $response->assertSee('id="buscar-censo"', false);
        $response->assertSee('placeholder="Buscar en todos los registros..."', false);
        $response->assertDontSee('name="search"', false);
        $response->assertSee('name="consejo_comunal_id"', false);
        $response->assertSee('name="tipo_vivienda"', false);
        $response->assertSee('name="beneficio"', false);
        $response->assertDontSee('>Buscar</button>', false);
        $response->assertSee('id="censo-filtros"', false);
        $response->assertSee('data-search=', false);
        $response->assertSee('data-council="'.$consejo->id.'"', false);
        $response->assertSee('data-housing="Propia"', false);
        $response->assertSee('data-clap="1"', false);
        $response->assertSee('data-bono="0"', false);

        foreach ([
            (string) $familia->id,
            'FAMILIA-001',
            (string) $consejo->id,
            'Consejo Las Flores',
            'Propia',
            'Sí',
            '12345678',
            'María',
            'Pérez',
        ] as $valor) {
            $response->assertSee($valor);
        }

        $this->get(route('censo.index', ['search' => 'no-coincide']))
            ->assertOk()
            ->assertSee('FAMILIA-001');

        $this->get(route('censo.index', ['tipo_vivienda' => 'Propia']))
            ->assertOk()
            ->assertSee('FAMILIA-001')
            ->assertDontSee('FAMILIA-002');

        $this->get(route('censo.index', ['consejo_comunal_id' => $consejo->id]))
            ->assertOk()
            ->assertSee('FAMILIA-001')
            ->assertDontSee('FAMILIA-002');

        $this->get(route('censo.index', ['beneficio' => 'clap']))
            ->assertOk()
            ->assertSee('FAMILIA-001')
            ->assertDontSee('FAMILIA-002');

        $this->assertSame('12345678', $persona->cedula);
    }

    private function crearConsejo(string $nombre, string $cedulaJefe): ConsejoComunal
    {
        $jefe = Persona::create([
            'cedula' => $cedulaJefe,
            'cedula_tipo' => 'V',
            'nombres' => 'Jefe',
            'apellidos' => 'Consejo',
        ]);

        return ConsejoComunal::create([
            'nombre' => $nombre,
            'rif' => 'C'.$cedulaJefe.'1',
            'jefe_comando' => $jefe->id,
            'direccion' => 'Dirección comunal',
        ]);
    }

    private function crearPersona(Familia $familia, array $overrides = []): Persona
    {
        return Persona::create(array_merge([
            'cedula' => '87654321',
            'cedula_tipo' => 'V',
            'nombres' => 'Persona',
            'apellidos' => 'Censada',
            'familia_id' => $familia->id,
            'genero' => 'Femenino',
            'parentesco' => 'Jefe de familia',
        ], $overrides));
    }
}
