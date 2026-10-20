<?php

namespace Tests\Feature;

use App\Models\ConsejoComunal;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ConsejosComunalesModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_renders_live_search_data_for_all_council_record_fields(): void
    {
        $administrador = User::create([
            'nombre' => 'Jefe',
            'apellido' => 'Comuna',
            'cedula_usuario' => '10000001',
            'password' => Hash::make('clave-segura'),
            'activo' => true,
            'rol' => 'Jefe de comuna',
        ]);
        $this->actingAs($administrador);

        $jefe = Persona::create([
            'cedula' => '12345678',
            'cedula_tipo' => 'V',
            'nombres' => 'María',
            'apellidos' => 'Pérez',
        ]);
        $consejo = ConsejoComunal::create([
            'nombre' => 'Consejo Las Flores',
            'rif' => 'C123456789',
            'jefe_comando' => $jefe->id,
            'direccion' => 'Calle Central, sector norte',
        ]);

        $response = $this->get(route('consejos-comunales.index'));

        $response->assertOk();
        $response->assertSee('id="buscar-consejo"', false);
        $response->assertSee('placeholder="Buscar en todos los registros..."', false);
        $response->assertDontSee('nombre_consejo');
        $response->assertDontSee('cedula_jefe');
        $response->assertSee('data-search=', false);

        foreach ([
            (string) $consejo->id,
            'Consejo Las Flores',
            'C123456789',
            'V',
            '12345678',
            'María',
            'Pérez',
            'Calle Central, sector norte',
        ] as $valor) {
            $response->assertSee($valor);
        }
    }

    public function test_old_server_side_filters_no_longer_filter_the_council_list(): void
    {
        $administrador = User::create([
            'nombre' => 'Jefe',
            'apellido' => 'Comuna',
            'cedula_usuario' => '10000001',
            'password' => Hash::make('clave-segura'),
            'activo' => true,
            'rol' => 'Jefe de comuna',
        ]);
        $this->actingAs($administrador);

        $persona = Persona::create([
            'cedula' => '12345678',
            'cedula_tipo' => 'V',
            'nombres' => 'María',
            'apellidos' => 'Pérez',
        ]);

        ConsejoComunal::create([
            'nombre' => 'Consejo Las Flores',
            'rif' => 'C123456789',
            'jefe_comando' => $persona->id,
            'direccion' => 'Calle Central',
        ]);
        ConsejoComunal::create([
            'nombre' => 'Consejo El Norte',
            'rif' => 'C987654321',
            'jefe_comando' => $persona->id,
            'direccion' => 'Avenida Principal',
        ]);

        $response = $this->get(route('consejos-comunales.index', [
            'nombre_consejo' => 'Las Flores',
            'cedula_jefe' => '11111111',
        ]));

        $response->assertOk();
        $response->assertSee('Consejo Las Flores');
        $response->assertSee('Consejo El Norte');
    }
}
