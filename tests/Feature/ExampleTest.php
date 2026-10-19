<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);

        $responsePeta = $this->get('/peta');
        $responsePeta->assertStatus(200);
        $responsePeta->assertSee('WEBGIS BWS KALIMANTAN I');

        $user = \App\Models\User::first() ?? \App\Models\User::factory()->create();

        $responseDashboard = $this->actingAs($user)->get('/dashboard');
        $responseDashboard->assertStatus(200);
        $responseDashboard->assertSee('Peta Daerah Irigasi (DIR)');

        $responseKelola = $this->actingAs($user)->get('/admin/kelola-gis');
        $responseKelola->assertStatus(200);
        $responseKelola->assertSee('Manajemen Data GIS');
    }
}
