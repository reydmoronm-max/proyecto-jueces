<?php

namespace Tests\Feature;

use App\Models\CategoriaVoceria;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CategoriaVoceriasModuleTest extends TestCase
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

    public function test_index_separates_categories_and_provides_live_search_for_all_fields(): void
    {
        CategoriaVoceria::create([
            'nombre' => 'Salud',
            'descripcion' => 'Categoría activa',
            'activo' => true,
        ]);
        CategoriaVoceria::create([
            'nombre' => 'Educación',
            'descripcion' => 'Categoría inactiva',
            'activo' => false,
        ]);

        $response = $this->get(route('categoria-vocerias.index'));

        $response->assertOk();
        $response->assertSee('Categorias Activas');
        $response->assertSee('Categorias Inactivas');
        $response->assertSee('id="buscar-categoria-voceria"', false);
        $response->assertSee('placeholder="Buscar en todos los registros..."', false);
        $response->assertDontSee('name="search"', false);
        $response->assertSeeInOrder([
            'id="categorias-activas-content"',
            'id="tbody-categorias-activas">',
            'data-search="1 Salud Categoría activa"',
            'Salud',
            '</tbody>',
            'id="categorias-inactivas-content"',
            'id="tbody-categorias-inactivas">',
            'data-search="2 Educación Categoría inactiva"',
            'Educación',
            '</tbody>',
        ], false);

        $this->get(route('categoria-vocerias.index', ['search' => 'no-match']))
            ->assertOk()
            ->assertSee('Salud')
            ->assertSee('Educación');
    }

    public function test_changing_category_status_updates_the_category(): void
    {
        $categoria = CategoriaVoceria::create([
            'nombre' => 'Salud',
            'descripcion' => 'Categoría de prueba',
            'activo' => true,
        ]);

        $this->patch(route('categoria-vocerias.cambiar-estado', [
            'id' => $categoria->id,
            'estado' => 0,
        ]))
            ->assertOk()
            ->assertJsonPath('activo', false);
        $this->assertDatabaseHas('categoria_vocerias', [
            'id' => $categoria->id,
            'activo' => false,
        ]);

        $this->patch(route('categoria-vocerias.cambiar-estado', [
            'id' => $categoria->id,
            'estado' => 1,
        ]))
            ->assertOk()
            ->assertJsonPath('activo', true);
        $this->assertDatabaseHas('categoria_vocerias', [
            'id' => $categoria->id,
            'activo' => true,
        ]);
    }
}
