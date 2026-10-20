<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SidebarHoverNavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_sidebar_starts_collapsed_and_keeps_mobile_toggle_only(): void
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
        $response->assertSee(
            'class="sidebar sidebar-default sidebar-white sidebar-base sidebar-mini sidebar-hover navs-rounded-all"',
            false
        );
        $response->assertSee('class="sidebar-toggle d-xl-none"', false);
        $this->assertSame(1, substr_count($response->getContent(), 'data-toggle="sidebar"'));
    }
}
