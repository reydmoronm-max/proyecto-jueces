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

class ConsejosComunalesGestionTest extends TestCase
{
    use RefreshDatabase;

    protected User $administrador;

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

        $this->actingAs($this->administrador);
    }

    public function test_can_register_council_without_jefe_de_comando(): void
    {
        $response = $this->post(route('consejos-comunales.store'), [
            'nombre'       => 'Consejo Sin Jefe',
            'rif'          => 'C123456789',
            'jefe_comando' => null,
            'direccion'    => 'Sector El Carmen Calle 1',
        ]);

        $response->assertRedirect(route('consejos-comunales.index'));
        $this->assertDatabaseHas('consejos_comunales', [
            'nombre'       => 'Consejo Sin Jefe',
            'rif'          => 'C123456789',
            'jefe_comando' => null,
        ]);
    }

    public function test_can_assign_and_remove_jefe_de_comando_in_edit(): void
    {
        $consejo = ConsejoComunal::create([
            'nombre'       => 'Consejo Alpha',
            'rif'          => 'C111111111',
            'jefe_comando' => null,
            'direccion'    => 'Calle 1',
        ]);

        $persona = Persona::create([
            'cedula'      => '12345678',
            'cedula_tipo' => 'V',
            'nombres'     => 'Carlos',
            'apellidos'   => 'López',
        ]);

        // Asignar jefe
        $response = $this->put(route('consejos-comunales.update', $consejo->id), [
            'nombre'       => 'Consejo Alpha Modificado',
            'rif'          => 'C111111111',
            'jefe_comando' => $persona->id,
            'direccion'    => 'Calle 1',
        ]);

        $response->assertRedirect(route('consejos-comunales.index'));
        $this->assertDatabaseHas('consejos_comunales', [
            'id'           => $consejo->id,
            'jefe_comando' => $persona->id,
        ]);

        // Quitar jefe (dejar en null)
        $responseRemove = $this->put(route('consejos-comunales.update', $consejo->id), [
            'nombre'       => 'Consejo Alpha Modificado',
            'rif'          => 'C111111111',
            'jefe_comando' => null,
            'direccion'    => 'Calle 1',
        ]);

        $responseRemove->assertRedirect(route('consejos-comunales.index'));
        $this->assertDatabaseHas('consejos_comunales', [
            'id'           => $consejo->id,
            'jefe_comando' => null,
        ]);
    }

    public function test_delete_council_sets_null_on_linked_records_when_no_replacement_provided(): void
    {
        $consejo = ConsejoComunal::create([
            'nombre'       => 'Consejo a Eliminar',
            'rif'          => 'C222222222',
            'jefe_comando' => null,
            'direccion'    => 'Calle 2',
        ]);

        $familia = Familia::create([
            'numero_familia'     => 'FAM-100',
            'consejo_comunal_id' => $consejo->id,
            'direccion'          => 'Calle 2',
        ]);

        $jornada = JornadaAbuelo::create([
            'nombre_jornada'     => 'Jornada Salud',
            'fecha_programada'   => '2026-11-20',
            'estatus'            => 'Planificada',
            'consejo_comunal_id' => $consejo->id,
        ]);

        $response = $this->delete(route('consejos-comunales.destroy', $consejo->id));

        $response->assertRedirect(route('consejos-comunales.index'));
        $this->assertDatabaseMissing('consejos_comunales', [
            'id' => $consejo->id,
        ]);

        // Familias y jornadas deben tener consejo_comunal_id = null
        $this->assertDatabaseHas('familias', [
            'id'                 => $familia->id,
            'consejo_comunal_id' => null,
        ]);

        $this->assertDatabaseHas('jornada_abuelos', [
            'id'                 => $jornada->id,
            'consejo_comunal_id' => null,
        ]);
    }

    public function test_delete_council_replaces_with_another_council_on_linked_records(): void
    {
        $consejoA = ConsejoComunal::create([
            'nombre'       => 'Consejo Origen',
            'rif'          => 'C333333333',
            'jefe_comando' => null,
            'direccion'    => 'Calle 3',
        ]);

        $consejoB = ConsejoComunal::create([
            'nombre'       => 'Consejo Destino',
            'rif'          => 'C444444444',
            'jefe_comando' => null,
            'direccion'    => 'Calle 4',
        ]);

        $familia = Familia::create([
            'numero_familia'     => 'FAM-200',
            'consejo_comunal_id' => $consejoA->id,
            'direccion'          => 'Calle 3',
        ]);

        $jornada = JornadaAbuelo::create([
            'nombre_jornada'     => 'Jornada Integral',
            'fecha_programada'   => '2026-11-25',
            'estatus'            => 'Planificada',
            'consejo_comunal_id' => $consejoA->id,
        ]);

        $response = $this->delete(route('consejos-comunales.destroy', $consejoA->id), [
            'reemplazo_consejo_id' => $consejoB->id,
        ]);

        $response->assertRedirect(route('consejos-comunales.index'));
        $this->assertDatabaseMissing('consejos_comunales', [
            'id' => $consejoA->id,
        ]);

        // Familias y jornadas deben haber sido transferidas al Consejo B
        $this->assertDatabaseHas('familias', [
            'id'                 => $familia->id,
            'consejo_comunal_id' => $consejoB->id,
        ]);

        $this->assertDatabaseHas('jornada_abuelos', [
            'id'                 => $jornada->id,
            'consejo_comunal_id' => $consejoB->id,
        ]);
    }

    public function test_endpoint_verificar_vinculos_returns_correct_counts_and_other_councils(): void
    {
        $consejoA = ConsejoComunal::create([
            'nombre'       => 'Consejo 1',
            'rif'          => 'C555555555',
            'jefe_comando' => null,
            'direccion'    => 'Dir 1',
        ]);

        $consejoB = ConsejoComunal::create([
            'nombre'       => 'Consejo 2',
            'rif'          => 'C666666666',
            'jefe_comando' => null,
            'direccion'    => 'Dir 2',
        ]);

        Familia::create([
            'numero_familia'     => 'FAM-300',
            'consejo_comunal_id' => $consejoA->id,
            'direccion'          => 'Dir 1',
        ]);

        $response = $this->get(route('consejos-comunales.vinculos', $consejoA->id));

        $response->assertOk()
            ->assertJsonPath('total_vinculos', 1)
            ->assertJsonPath('familias_count', 1)
            ->assertJsonPath('jornadas_count', 0)
            ->assertJsonCount(1, 'otros_consejos')
            ->assertJsonPath('otros_consejos.0.id', $consejoB->id);
    }
}

