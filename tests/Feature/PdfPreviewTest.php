<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PdfPreviewTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $usuario = User::create([
            'nombre' => 'Jefe',
            'apellido' => 'Comuna',
            'cedula_usuario' => '10000001',
            'password' => Hash::make('clave-segura'),
            'activo' => true,
            'rol' => 'Jefe de comuna',
        ]);

        $this->actingAs($usuario);
    }

    public function test_demographic_pdf_is_returned_inline_for_browser_preview(): void
    {
        $response = $this->get(route('reportes.pdf'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringStartsWith(
            'inline;',
            $response->headers->get('Content-Disposition')
        );
    }

    public function test_advanced_search_pdf_is_returned_inline_for_browser_preview(): void
    {
        $response = $this->get(route('consulta.busqueda-avanzada.pdf'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringStartsWith(
            'inline;',
            $response->headers->get('Content-Disposition')
        );
    }
}
