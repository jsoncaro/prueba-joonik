<?php

namespace Tests\Feature;

use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocationControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Migrar la base de datos de testing
        $this->artisan('migrate');

        // API Key para pruebas
        config(['app.api_key' => 'b05bf18e70585c6f37c34cf758ad777b']);
    }

    /** @test */
    public function it_lists_locations_without_filters()
    {
        Location::factory()->count(5)->create();

        $response = $this->getJson('/api/v1/locations', [
            'X-API-KEY' => 'b05bf18e70585c6f37c34cf758ad777b',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'code', 'name', 'image', 'created_at', 'updated_at']
                ],
                'total'
            ])
            ->assertJsonCount(5, 'data');
    }

    /** @test */
    public function it_lists_locations_with_filters()
    {
        Location::factory()->create([
            'name' => 'Bogotá',
            'code' => 'LOCBGTA'
        ]);

        Location::factory()->create([
            'name' => 'Medellín',
            'code' => 'LOCMDLL'
        ]);

        $response = $this->getJson('/api/v1/locations?name=Bogotá', [
            'X-API-KEY' => 'b05bf18e70585c6f37c34cf758ad777b',
        ]);

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['name' => 'Bogotá']);
    }

    /** @test */
    public function it_creates_a_location_with_valid_data()
    {
        $payload = [
            'name' => 'Cartagena',
            'code' => 'LOCCART',
            'image' => 'https://example.com/cartagena.png'
        ];

        $response = $this->postJson('/api/v1/locations', $payload, [
            'X-API-KEY' => 'b05bf18e70585c6f37c34cf758ad777b',
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'Cartagena']);

        $this->assertDatabaseHas('locations', ['name' => 'Cartagena']);
    }

    /** @test */
    public function it_fails_to_create_location_with_invalid_data()
    {
        $payload = [
            'name' => '', // vacío
            'code' => '123', // formato inválido
        ];

        $response = $this->postJson('/api/v1/locations', $payload, [
            'X-API-KEY' => 'b05bf18e70585c6f37c34cf758ad777b',
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'error' => [
                    'message',
                    'code',
                    'errors' => [
                        'name'
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_requires_api_key()
    {
        $response = $this->getJson('/api/v1/locations');

        $response->assertStatus(401)
            ->assertJsonFragment(['message' => 'Invalid API Key']);
    }
}
