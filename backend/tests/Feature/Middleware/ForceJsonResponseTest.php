<?php

namespace Tests\Feature\Middleware;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ForceJsonResponseTest extends TestCase
{
    /** @test */
    public function it_sets_accept_header_to_application_json()
    {
        // Ruta temporal con middleware para probarlo
        Route::middleware(\App\Http\Middleware\ForceJsonResponse::class)
            ->get('/test-force-json', function () {
                return response()->json([
                    'accept' => request()->header('Accept')
                ]);
            });

        $response = $this->get('/test-force-json');

        $response->assertOk()
            ->assertJson([
                'accept' => 'application/json'
            ]);
    }
}
