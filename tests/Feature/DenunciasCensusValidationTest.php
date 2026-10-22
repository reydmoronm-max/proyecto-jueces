<?php

namespace Tests\Feature;

use App\Models\Familia;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DenunciasCensusValidationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $juez = User::create([
            'nombre' => 'Juez',
            'apellido' => 'Prueba',
            'cedula_usuario' => '10000001',
            'password' => Hash::make('clave-segura'),
            'activo' => true,
            'rol' => 'Juez',
        ]);
        $this->actingAs($juez);

        $this->crearPersonaCensada('11111111', 'Base Uno', 'Persona', '04120000001', 'Dirección uno');
        $this->crearPersonaCensada('22222222', 'Base Dos', 'Persona', '04120000002', 'Dirección dos');
    }

    public function test_non_census_cedula_is_rejected_without_creating_denuncia_records(): void
    {
        $response = $this->post(route('denuncias.store'), $this->datosDenuncia('99999999'));

        $response->assertRedirect();
        $response->assertSessionHasErrors('denunciantes.0.cedula');
        $this->assertDatabaseCount('expedientes', 0);
        $this->assertDatabaseCount('involucrados', 0);
        $this->assertDatabaseCount('actas', 0);
        $this->assertDatabaseMissing('personas', ['cedula' => '99999999']);
    }

    public function test_incomplete_census_person_is_rejected_before_saving(): void
    {
        $persona = $this->crearPersonaCensada('33333333', 'Datos', 'Incompletos', '', 'Dirección');
        $response = $this->post(route('denuncias.store'), $this->datosDenuncia($persona->cedula));

        $response->assertRedirect();
        $response->assertSessionHasErrors('denunciantes.0.cedula');
        $this->assertDatabaseCount('expedientes', 0);
        $this->assertDatabaseCount('involucrados', 0);
        $this->assertDatabaseCount('actas', 0);
    }

    public function test_valid_census_person_is_linked_without_trusting_submitted_readonly_fields(): void
    {
        $persona = $this->crearPersonaCensada(
            '44444444',
            'Nombre Censado',
            'Apellido Censado',
            '04125554444',
            'Dirección Censada'
        );

        $response = $this->post(route('denuncias.store'), $this->datosDenuncia($persona->cedula, [
            'nombres' => 'Nombre Manipulado',
            'apellidos' => 'Apellido Manipulado',
            'telefono' => '00000000000',
            'direccion' => 'Dirección Manipulada',
        ]));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Denuncia registrada correctamente.');
        $this->assertDatabaseCount('expedientes', 1);
        $this->assertDatabaseCount('involucrados', 1);
        $this->assertDatabaseCount('actas', 1);
        $this->assertDatabaseCount('personas', 3);
        $this->assertDatabaseHas('involucrados', [
            'persona_id' => $persona->id,
            'rol' => 'denunciante',
        ]);
        $this->assertSame('Nombre Censado', $persona->fresh()->nombres);
    }

    public function test_person_lookup_reports_census_presence(): void
    {
        $persona = $this->crearPersonaCensada(
            '55555555',
            'María',
            'Pérez',
            '04125555555',
            'Calle Central'
        );

        $this->get(route('denuncias.buscar-persona', ['cedula' => $persona->cedula]))
            ->assertOk()
            ->assertJsonPath('nombres', 'María')
            ->assertJsonPath('direccion', 'Calle Central');

        $this->get(route('denuncias.buscar-persona', ['cedula' => '99999998']))
            ->assertNotFound();
    }

    public function test_denuncia_form_shows_a_live_census_status_for_each_cedula_field(): void
    {
        $response = $this->get(route('denuncias.index'));

        $response->assertOk();
        $response->assertSee('denunciante-cedula-status', false);
        $response->assertSee('ri-checkbox-circle-fill', false);
        $response->assertSee('ri-error-warning-fill', false);
        $response->assertSee('La cédula debe existir en el censo para registrar la denuncia.', false);
        $response->assertSee('input[readonly][required]', false);
    }

    private function datosDenuncia(string $cedula, array $datosPersona = []): array
    {
        return [
            'denunciantes' => [
                array_merge([
                    'cedula' => $cedula,
                    'nombres' => 'Nombre enviado',
                    'apellidos' => 'Apellido enviado',
                    'telefono' => '04120000000',
                    'direccion' => 'Dirección enviada',
                ], $datosPersona),
            ],
            'caso' => 'Problema vecinal',
            'tipo_caso' => 'Convivencia vecinal',
            'categoria' => 'Ruidos',
            'denunciado_a' => 'Persona denunciada',
            'requirente' => 'Relato del denunciante',
            'receptor' => 'Registro del receptor',
            'acuerdos' => 'Acuerdos establecidos',
        ];
    }

    private function crearPersonaCensada(
        string $cedula,
        string $nombres,
        string $apellidos,
        string $telefono,
        string $direccion
    ): Persona {
        $familia = Familia::create([
            'numero_familia' => 'FAMILIA-'.$cedula,
        ]);

        return Persona::create([
            'cedula' => $cedula,
            'cedula_tipo' => 'V',
            'nombres' => $nombres,
            'apellidos' => $apellidos,
            'telefono' => $telefono ?: null,
            'direccion' => $direccion ?: null,
            'familia_id' => $familia->id,
        ]);
    }
}
