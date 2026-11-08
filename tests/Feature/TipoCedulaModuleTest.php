<?php

namespace Tests\Feature;

use App\Models\Familia;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TipoCedulaModuleTest extends TestCase
{
    use RefreshDatabase;

    protected User $administrador;
    protected User $juez;

    protected function setUp(): void
    {
        parent::setUp();

        $this->administrador = User::create([
            'nombre'         => 'Jefe',
            'apellido'       => 'Comuna',
            'cedula_usuario' => '10000001',
            'password'       => Hash::make('clave-segura'),
            'activo'         => true,
            'rol'            => 'Jefe de comuna',
        ]);

        $this->juez = User::create([
            'nombre'         => 'Juez',
            'apellido'       => 'Paz',
            'cedula_usuario' => '10000002',
            'password'       => Hash::make('clave-segura'),
            'activo'         => true,
            'rol'            => 'Juez',
        ]);

        // Asegurar que existan al menos 2 familias con integrantes para el middleware de Juez
        $f1 = Familia::create(['numero_familia' => 'FAM-SEED-1', 'direccion' => 'Dir 1']);
        Persona::create([
            'familia_id'  => $f1->id,
            'cedula_tipo' => 'V',
            'cedula'      => '11111111',
            'nombres'     => 'Persona',
            'apellidos'   => 'Uno',
            'direccion'   => 'Dir 1',
        ]);

        $f2 = Familia::create(['numero_familia' => 'FAM-SEED-2', 'direccion' => 'Dir 2']);
        Persona::create([
            'familia_id'  => $f2->id,
            'cedula_tipo' => 'V',
            'cedula'      => '22222222',
            'nombres'     => 'Persona',
            'apellidos'   => 'Dos',
            'direccion'   => 'Dir 2',
        ]);
    }

    public function test_options_contain_only_v_e_j_without_full_terms(): void
    {
        $this->actingAs($this->administrador);

        // Vista de censo
        $response = $this->get(route('censo.index'));
        $response->assertOk();
        $response->assertSee('<option value="V"', false);
        $response->assertSee('<option value="E"', false);
        $response->assertSee('<option value="J"', false);
        $response->assertDontSee('Venezolano', false);
        $response->assertDontSee('Extranjero', false);
        $response->assertDontSee('Jurídico', false);

        // Vista de visitas
        $response = $this->get(route('visitas.index'));
        $response->assertOk();
        $response->assertSee('<option value="V"', false);
        $response->assertSee('<option value="E"', false);
        $response->assertSee('<option value="J"', false);

        // Vista de consulta comunal
        $response = $this->get(route('consulta.index'));
        $response->assertOk();
        $response->assertSee('<option value="V"', false);
        $response->assertSee('<option value="E"', false);
        $response->assertSee('<option value="J"', false);

        // Vista de denuncias como juez
        $this->actingAs($this->juez);
        $response = $this->get(route('denuncias.index'));
        $response->assertOk();
        $response->assertSee('<option value="V">V</option>', false);
        $response->assertSee('<option value="E">E</option>', false);
        $response->assertSee('<option value="J">J</option>', false);
    }

    public function test_censo_can_register_members_with_e_and_j_types(): void
    {
        $this->actingAs($this->administrador);

        $familia = Familia::create([
            'numero_familia' => 'FAM-001',
            'direccion'      => 'Sector Central Casa 1',
        ]);

        // Registrar tipo E (Extranjero)
        $responseE = $this->post(route('censo.integrante.store'), [
            'familia_id'          => $familia->id,
            'cedula_tipo'         => 'E',
            'cedula'              => '81234567',
            'nombres'             => 'John',
            'apellidos'           => 'Doe',
            'telefono'            => '04141234567',
            'fecha_nacimiento'    => '15-05-1990',
            'nivel_academico'     => 'Universitario',
            'pensionado_jubilado' => 'No',
            'estudia'             => 'No',
            'genero'              => 'Masculino',
            'parentesco'          => 'Jefe de familia',
        ]);

        $responseE->assertRedirect(route('censo.index'));
        $this->assertDatabaseHas('personas', [
            'cedula_tipo' => 'E',
            'cedula'      => '81234567',
            'nombres'     => 'John',
        ]);

        // Registrar tipo J (Jurídico)
        $responseJ = $this->post(route('censo.integrante.store'), [
            'familia_id'          => $familia->id,
            'cedula_tipo'         => 'J',
            'cedula'              => '91234567',
            'nombres'             => 'Comercial',
            'apellidos'           => 'Empresa',
            'telefono'            => '04147654321',
            'fecha_nacimiento'    => '01-01-2000',
            'nivel_academico'     => 'Universitario',
            'pensionado_jubilado' => 'No',
            'estudia'             => 'No',
            'genero'              => 'Femenino',
            'parentesco'          => 'Hijo/a',
        ]);

        $responseJ->assertRedirect(route('censo.index'));
        $this->assertDatabaseHas('personas', [
            'cedula_tipo' => 'J',
            'cedula'      => '91234567',
            'nombres'     => 'Comercial',
        ]);
    }

    public function test_visitas_can_register_and_differentiate_by_cedula_tipo(): void
    {
        $this->actingAs($this->administrador);

        // Registrar visita con cédula tipo E
        $response = $this->post(route('visitas.store'), [
            'nombre'      => 'Pedro',
            'apellido'    => 'Perez',
            'cedula_tipo' => 'E',
            'cedula'      => '12345678',
            'proposito'   => 'Consulta comunitaria',
        ]);

        $response->assertRedirect(route('visitas.index'));
        $this->assertDatabaseHas('personas', [
            'cedula_tipo' => 'E',
            'cedula'      => '12345678',
        ]);
    }

    public function test_denuncias_searches_and_registers_with_different_cedula_tipos(): void
    {
        $familia = Familia::create([
            'numero_familia' => 'FAM-002',
            'direccion'      => 'Calle 2',
        ]);

        $personaE = Persona::create([
            'familia_id'  => $familia->id,
            'cedula_tipo' => 'E',
            'cedula'      => '88888888',
            'nombres'     => 'Carlos',
            'apellidos'   => 'Smith',
            'telefono'    => '04121234567',
            'direccion'   => 'Calle 2',
        ]);

        $this->actingAs($this->juez);

        // Buscar persona por E
        $buscar = $this->get(route('denuncias.buscar-persona', [
            'cedula'      => '88888888',
            'cedula_tipo' => 'E',
        ]));

        $buscar->assertOk()
            ->assertJsonPath('cedula_tipo', 'E')
            ->assertJsonPath('cedula', '88888888');

        // Registrar denuncia con requirente tipo E
        $store = $this->from(route('denuncias.index'))->post(route('denuncias.store'), [
            'denunciantes' => [
                [
                    'cedula_tipo' => 'E',
                    'cedula'      => '88888888',
                    'nombres'     => 'Carlos',
                    'apellidos'   => 'Smith',
                    'telefono'    => '04121234567',
                    'direccion'   => 'Calle 2',
                ]
            ],
            'caso'      => 'Conflicto vecinal',
            'tipo_caso' => 'Convivencia',
            'categoria' => 'Ruidos molestos',
        ]);

        $store->assertRedirect(route('denuncias.index'));
        $this->assertDatabaseHas('expedientes', [
            'caso' => 'Conflicto vecinal',
        ]);
    }
}

