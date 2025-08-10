<?php

namespace Tests\Feature\Handler;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;
use Exception;

class HandlerTest extends TestCase
{
    /** @test */
    public function it_returns_json_response_for_api_routes_on_exception()
    {
        // Ruta API que lanza una excepción
        Route::get('/api/v1/test-error', function () {
            throw new Exception('Test exception message', 1234);
        });

        $response = $this->getJson('/api/v1/test-error');

        $response->assertStatus(500) // El Handler usa 500 si no es HttpException
            ->assertJsonStructure([
                'error' => ['message', 'code']
            ])
            ->assertJson([
                'error' => [
                    'message' => 'Test exception message',
                    'code'    => 1234
                ]
            ]);
    }

    /** @test */
    public function it_returns_json_response_with_http_exception_status()
    {
        // Ruta API que lanza un HttpException
        Route::get('/api/v1/not-found', function () {
            abort(404, 'Not Found from test');
        });

        $response = $this->getJson('/api/v1/not-found');

        $response->assertStatus(404)
            ->assertJson([
                'error' => [
                    'message' => 'Not Found from test',
                    'code'    => 0 // Handler pone 0 si no hay code explícito
                ]
            ]);
    }
}
