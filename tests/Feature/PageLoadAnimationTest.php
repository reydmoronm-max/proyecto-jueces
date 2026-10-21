<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PageLoadAnimationTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_pages_show_the_brief_loading_animation_without_body_fade(): void
    {
        $usuario = User::create([
            'nombre' => 'Jefe',
            'apellido' => 'Comuna',
            'cedula_usuario' => '10000001',
            'password' => Hash::make('clave-segura'),
            'activo' => true,
            'rol' => 'Jefe de comuna',
        ]);

        $response = $this->actingAs($usuario)->get(route('proyectos.index'));

        $response->assertOk();
        $response->assertSee('id="page-loader"', false);
        $response->assertSee('class="page-loader__spinner"', false);
        $response->assertSee('Cargando...', false);
        $response->assertDontSee('class="fase-animada"', false);
    }

    public function test_login_page_uses_the_same_loading_animation(): void
    {
        $response = $this->get(route('login'));

        $response->assertOk();
        $response->assertSee('id="page-loader"', false);
        $response->assertSee('class="page-loader__spinner"', false);
        $response->assertDontSee('class="loader simple-loader"', false);
    }
}
