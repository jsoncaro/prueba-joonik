<?php

namespace Tests\Unit\Services;

use App\Models\Location;
use App\Services\LocationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class LocationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected LocationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new LocationService();
    }

    /** @test */
    public function it_returns_paginated_locations_and_stores_in_cache()
    {
        Cache::flush();

        // Crear datos de prueba
        Location::factory()->count(3)->create();

        $request = new Request([
            'per_page' => 2,
            'page' => 1
        ]);

        // Primera llamada (sin cache aún)
        $resultFirstCall = $this->service->getAllWithFilters($request);
        $this->assertCount(2, $resultFirstCall->items());

        // Segunda llamada (debe venir desde cache)
        Cache::shouldReceive('remember')
            ->once()
            ->andReturn($resultFirstCall);

        $this->service->getAllWithFilters($request);
    }

    /** @test */
    public function it_filters_locations_by_name_and_code()
    {
        Location::factory()->create(['name' => 'Medellin', 'code' => 'MED']);
        Location::factory()->create(['name' => 'Bogota', 'code' => 'BOG']);

        $request = new Request(['name' => 'Medellin', 'per_page' => 10]);

        $result = $this->service->getAllWithFilters($request);

        $this->assertCount(1, $result->items());
        $this->assertEquals('Medellin', $result->items()[0]->name);
    }

    /** @test */
    public function it_flushes_cache_when_creating_location()
    {
        Cache::shouldReceive('flush')->once();

        $locationData = [
            'name' => 'Cali',
            'code' => 'CLO',
            'image' => 'https://picsum.photos/300/200'
        ];

        $location = $this->service->create($locationData);

        $this->assertDatabaseHas('locations', ['name' => 'Cali']);
        $this->assertInstanceOf(Location::class, $location);
    }
}
