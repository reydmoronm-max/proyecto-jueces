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

    public function test_index_separates_active_and_inactive_categories_and_keeps_search_filter(): void
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
        $response->assertDontSee('estado_categoria');
        $response->assertSeeInOrder([
            'id="categorias-activas-content"',
            'id="tbody-categorias-activas">',
            'Salud',
            '</tbody>',
            'id="categorias-inactivas-content"',
            'id="tbody-categorias-inactivas">',
            'Educación',
            '</tbody>',
        ], false);

        $this->get(route('categoria-vocerias.index', ['search' => 'Salud']))
            ->assertOk()
            ->assertSee('Salud')
            ->assertDontSee('Educación');
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
