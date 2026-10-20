<?php

namespace Tests\Feature;

use App\Models\Persona;
use App\Models\User;
use App\Models\Vocero;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class VocerosModuleTest extends TestCase
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

    public function test_voceros_index_separates_active_and_inactive_voceros(): void
    {
        $activo = $this->crearVocero('Activo');
        $inactivo = $this->crearVocero('Inactivo', false);

        $response = $this->get(route('voceros.index'));

        $response->assertOk();
        $response->assertSee('Voceros Activos');
        $response->assertSee('Voceros Inactivos');
        $response->assertSeeInOrder([
            'id="voceros-activos-content"',
            'id="tbody-voceros-activos">',
            'Activo Persona',
            '</tbody>',
            'id="voceros-inactivos-content"',
            'id="tbody-voceros-inactivos">',
            'Inactivo Persona',
            '</tbody>',
        ], false);
        $this->assertTrue($activo->activo);
        $this->assertFalse($inactivo->activo);
    }

    public function test_changing_vocero_status_updates_the_record_for_moving_it_between_tabs(): void
    {
        $vocero = $this->crearVocero('Vocero');

        $this->patch(route('voceros.cambiar-estado', [
            'id' => $vocero->id,
            'estado' => 0,
        ]))
            ->assertOk()
            ->assertJsonPath('activo', false);
        $this->assertDatabaseHas('voceros', ['id' => $vocero->id, 'activo' => false]);

        $this->patch(route('voceros.cambiar-estado', [
            'id' => $vocero->id,
            'estado' => 1,
        ]))
            ->assertOk()
            ->assertJsonPath('activo', true);
        $this->assertDatabaseHas('voceros', ['id' => $vocero->id, 'activo' => true]);
    }

    private function crearVocero(string $nombre, bool $activo = true): Vocero
    {
        $persona = Persona::create([
            'cedula' => $nombre === 'Activo' ? '12345678' : ($nombre === 'Inactivo' ? '87654321' : '23456789'),
            'cedula_tipo' => 'V',
            'nombres' => $nombre,
            'apellidos' => 'Persona',
        ]);

        return Vocero::create([
            'persona_id' => $persona->id,
            'categoria_vocero' => 'Salud',
            'fecha_eleccion' => '2026-01-01',
            'activo' => $activo,
        ]);
    }
}
