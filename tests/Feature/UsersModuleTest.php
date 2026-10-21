<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UsersModuleTest extends TestCase
{
    use RefreshDatabase;

    private User $administrador;

    protected function setUp(): void
    {
        parent::setUp();

        $this->administrador = $this->crearUsuario([
            'cedula_usuario' => '10000001',
            'rol' => 'Jefe de comuna',
        ]);

        $this->actingAs($this->administrador);
    }

    public function test_users_index_and_edit_endpoint_are_available_to_a_commune_manager(): void
    {
        $response = $this->get(route('usuarios.index'));
        $response->assertOk();
        $response->assertSee('Usuarios');

        $this->get(route('usuarios.edit', $this->administrador))
            ->assertOk()
            ->assertJsonPath('cedula_usuario', '10000001');
    }

    public function test_users_index_separates_active_and_inactive_users(): void
    {
        $activo = $this->crearUsuario([
            'cedula_usuario' => '12345678',
            'nombre' => 'Activo',
            'activo' => true,
        ]);
        $inactivo = $this->crearUsuario([
            'cedula_usuario' => '87654321',
            'nombre' => 'Inactivo',
            'activo' => false,
        ]);

        $response = $this->get(route('usuarios.index'));

        $response->assertOk();
        $response->assertSee('Usuarios Activos');
        $response->assertSee('Usuarios Inactivos');
        $response->assertSee('id="buscar-usuario"', false);
        $response->assertSee('placeholder="Nombre, apellido, cédula o rol..."', false);
        $response->assertSeeInOrder([
            'id="usuarios-activos-content"',
            'id="tbody-usuarios">',
            'data-search="Usuario Prueba 10000001 Jefe de comuna"',
            'Usuario Prueba',
            'Activo Prueba',
            '</tbody>',
            '</div>',
            'id="usuarios-inactivos-content"',
            'id="tbody-usuarios-inactivos">',
            'data-search="Inactivo Prueba 87654321 Juez"',
            'Inactivo Prueba',
            '</tbody>',
        ], false);
        $this->assertTrue($activo->activo);
        $this->assertFalse($inactivo->activo);
    }

    public function test_changing_user_status_moves_them_between_active_and_inactive_sections(): void
    {
        $usuario = $this->crearUsuario([
            'cedula_usuario' => '12345678',
            'activo' => true,
        ]);

        $this->patch(route('cambiar-estado', ['id' => $usuario->id, 'estado' => 0]))
            ->assertOk()
            ->assertJsonPath('activo', false);
        $this->assertDatabaseHas('users', ['id' => $usuario->id, 'activo' => false]);
        $this->get(route('usuarios.index'))
            ->assertSee('Usuarios Inactivos')
            ->assertSee('Usuario Prueba', false);

        $this->patch(route('cambiar-estado', ['id' => $usuario->id, 'estado' => 1]))
            ->assertOk()
            ->assertJsonPath('activo', true);
        $this->assertDatabaseHas('users', ['id' => $usuario->id, 'activo' => true]);
        $this->get(route('usuarios.index'))
            ->assertSee('Usuario Prueba', false);
    }

    public function test_commune_manager_cannot_deactivate_their_own_account(): void
    {
        $this->patch(route('cambiar-estado', [
            'id' => $this->administrador->id,
            'estado' => 0,
        ]))
            ->assertUnprocessable()
            ->assertJsonPath('message', 'No puedes desactivar tu propio usuario.');

        $this->assertDatabaseHas('users', [
            'id' => $this->administrador->id,
            'activo' => true,
        ]);
    }

    public function test_store_rejects_an_existing_identity_number_with_a_validation_error(): void
    {
        $existente = $this->crearUsuario(['cedula_usuario' => '12345678']);

        $response = $this->post(route('usuarios.store'), $this->datosUsuario([
            'cedula_usuario' => $existente->cedula_usuario,
        ]));

        $response->assertRedirect();
        $response->assertSessionHasErrors('cedula_usuario');
        $response->assertSessionHas('usuario_formulario', 'crear');
        $this->assertDatabaseCount('users', 2);
    }

    public function test_update_rejects_an_identity_number_owned_by_another_user_without_changing_data(): void
    {
        $usuario = $this->crearUsuario(['cedula_usuario' => '12345678']);
        $otroUsuario = $this->crearUsuario(['cedula_usuario' => '87654321']);

        $response = $this->put(route('usuarios.update', $usuario), $this->datosUsuario([
            'cedula_usuario' => $otroUsuario->cedula_usuario,
            'password' => 'unused-password',
        ]));

        $response->assertRedirect();
        $response->assertSessionHasErrors('cedula_usuario');
        $response->assertSessionHas('usuario_formulario', 'editar');
        $response->assertSessionHas('usuario_editar_id', $usuario->id);
        $this->assertDatabaseHas('users', [
            'id' => $usuario->id,
            'cedula_usuario' => '12345678',
            'nombre' => $usuario->nombre,
        ]);
    }

    public function test_store_creates_a_user_and_hashes_the_password_and_security_answer(): void
    {
        $response = $this->post(route('usuarios.store'), $this->datosUsuario([
            'cedula_usuario' => '22345678',
            'password' => 'clave-segura',
            'pregunta_seguridad' => '¿Qué color le gusta más?',
            'respuesta_seguridad' => 'Azul',
        ]));

        $response->assertRedirect(route('usuarios.index'));
        $response->assertSessionHas('success');

        $usuario = User::where('cedula_usuario', '22345678')->firstOrFail();
        $this->assertSame('Nuevo', $usuario->nombre);
        $this->assertSame('Juez', $usuario->rol);
        $this->assertTrue(Hash::check('clave-segura', $usuario->password));
        $this->assertTrue(Hash::check('azul', $usuario->respuesta_seguridad));
    }

    public function test_update_allows_the_users_existing_identity_number(): void
    {
        $usuario = $this->crearUsuario(['cedula_usuario' => '12345678']);

        $response = $this->put(route('usuarios.update', $usuario), $this->datosUsuario([
            'cedula_usuario' => $usuario->cedula_usuario,
            'nombre' => 'Actualizado',
            'password' => 'unused-password',
        ]));

        $response->assertRedirect(route('usuarios.index'));
        $response->assertSessionHas('update');
        $this->assertDatabaseHas('users', [
            'id' => $usuario->id,
            'cedula_usuario' => '12345678',
            'nombre' => 'Actualizado',
        ]);
    }

    public function test_store_rejects_invalid_required_fields_and_identity_number_format(): void
    {
        $response = $this->post(route('usuarios.store'), [
            'nombre' => '',
            'apellido' => '',
            'cedula_usuario' => '123456789',
            'password' => 'abc',
            'rol' => 'Administrador',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors([
            'nombre',
            'apellido',
            'cedula_usuario',
            'password',
            'rol',
        ]);
        $this->assertDatabaseCount('users', 1);
    }

    private function datosUsuario(array $overrides = []): array
    {
        return array_merge([
            'nombre' => 'Nuevo',
            'apellido' => 'Usuario',
            'cedula_usuario' => '32345678',
            'password' => 'clave-segura',
            'rol' => 'Juez',
        ], $overrides);
    }

    private function crearUsuario(array $overrides = []): User
    {
        return User::create(array_merge([
            'nombre' => 'Usuario',
            'apellido' => 'Prueba',
            'cedula_usuario' => '42345678',
            'password' => Hash::make('clave-segura'),
            'activo' => true,
            'rol' => 'Juez',
        ], $overrides));
    }
}
