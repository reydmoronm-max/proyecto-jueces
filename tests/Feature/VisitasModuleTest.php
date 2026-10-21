<?php

namespace Tests\Feature;

use App\Models\Persona;
use App\Models\User;
use App\Models\Visita;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class VisitasModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_renders_live_search_covering_all_visit_record_fields(): void
    {
        $usuario = User::create([
            'nombre' => 'Jefe',
            'apellido' => 'Comuna',
            'cedula_usuario' => '10000001',
            'password' => Hash::make('clave-segura'),
            'activo' => true,
            'rol' => 'Jefe de comuna',
        ]);
        $this->actingAs($usuario);

        $persona = Persona::create([
            'cedula' => '12345678',
            'cedula_tipo' => 'V',
            'nombres' => 'María',
            'apellidos' => 'Pérez',
            'telefono' => '04121234567',
            'direccion' => 'Calle Central',
        ]);
        $visita = Visita::create([
            'persona_id' => $persona->id,
            'proposito' => 'Solicitud de apoyo comunitario',
            'de_parte' => 'Consejo Comunal',
        ]);

        $response = $this->get(route('visitas.index'));

        $response->assertOk();
        $response->assertSee('id="buscar-visita"', false);
        $response->assertSee('placeholder="Buscar en todos los registros..."', false);
        $response->assertSee('data-search=', false);
        $response->assertSee((string) $visita->id);
        $response->assertSee((string) $persona->id);

        foreach ([
            'María',
            'Pérez',
            'V',
            '12345678',
            '04121234567',
            'Calle Central',
            'Consejo Comunal',
            $visita->created_at->format('d/m/Y h:i A'),
            'Solicitud de apoyo comunitario',
        ] as $valor) {
            $response->assertSee($valor);
        }
    }
}
