<?php

namespace Tests\Feature\Middleware;

use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ApiKeyMiddlewareTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Creamos una ruta temporal protegida por el middleware
        Route::middleware('api', \App\Http\Middleware\ApiKeyMiddleware::class)
            ->get('/test-api-key', function () {
                return response()->json(['message' => 'OK']);
            });
    }

    /** @test */
    public function it_returns_unauthorized_when_api_key_is_missing_or_invalid()
    {
        // Sin API Key
        $response = $this->getJson('/test-api-key');
        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
                 ->assertJson([
                     'error' => [
                         'message' => 'Invalid API Key',
                         'code' => 'E_INVALID_API_KEY',
                     ]
                 ]);

        // API Key incorrecta
        $response = $this->getJson('/test-api-key', [
            'X-API-KEY' => 'wrong-key',
        ]);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function it_allows_request_with_valid_api_key()
    {
        $validKey = config('app.api_key');

        $response = $this->getJson('/test-api-key', [
            'X-API-KEY' => $validKey,
        ]);

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                     'message' => 'OK',
                 ]);
    }
}
