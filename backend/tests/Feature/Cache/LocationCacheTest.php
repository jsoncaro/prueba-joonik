<?php

namespace Tests\Feature\Cache;

use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class LocationCacheTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_cached_locations_if_available()
    {
        // Creamos un paginador falso con datos
        $locations = Location::factory()->count(2)->create();

        // Cacheamos el paginador real
        $paginator = Location::paginate(10);
        Cache::put('locations_' . md5(json_encode([
            'name'     => null,
            'code'     => null,
            'per_page' => 10,
            'page'     => 1
        ])), $paginator, 60);

        $response = $this->getJson('/api/v1/locations', [
            'X-API-KEY' => config('app.api_key'),
        ]);

        $response->assertOk()
            ->assertJson([
                'total' => $paginator->total(),
            ])
            ->assertJsonCount($paginator->count(), 'data');
    }

    /** @test */
    public function it_caches_locations_after_first_query()
    {
        $this->assertEmpty(Cache::get('locations_' . md5(json_encode([
            'name'     => null,
            'code'     => null,
            'per_page' => 10,
            'page'     => 1
        ]))));

        // Creamos datos en la base
        Location::factory()->count(3)->create();

        // Primera consulta (guarda en cache)
        $this->getJson('/api/v1/locations', [
            'X-API-KEY' => config('app.api_key'),
        ])->assertOk();

        $this->assertNotEmpty(Cache::get('locations_' . md5(json_encode([
            'name'     => null,
            'code'     => null,
            'per_page' => 10,
            'page'     => 1
        ]))));
    }
}
