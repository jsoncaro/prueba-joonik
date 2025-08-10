<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LocationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_uses_the_has_factory_trait()
    {
        $this->assertContains(HasFactory::class, class_uses(Location::class));
    }

    /** @test */
    public function it_has_correct_fillable_attributes()
    {
        $location = new Location();

        $this->assertEquals([
            'code',
            'name',
            'image',
        ], $location->getFillable());
    }

    /** @test */
    public function it_can_be_created_with_fillable_attributes()
    {
        $data = [
            'code'  => 'BOG',
            'name'  => 'Bogotá',
            'image' => 'https://via.placeholder.com/640x480.png?text=Bogotá',
        ];

        $location = Location::create($data);

        $this->assertDatabaseHas('locations', $data);
        $this->assertEquals('Bogotá', $location->name);
    }

    /** @test */
    public function it_can_be_updated()
    {
        $location = Location::factory()->create();

        $location->update(['name' => 'Cartagena']);

        $this->assertDatabaseHas('locations', ['id' => $location->id, 'name' => 'Cartagena']);
    }

    /** @test */
    public function it_can_be_deleted()
    {
        $location = Location::factory()->create();

        $location->delete();

        $this->assertDatabaseMissing('locations', ['id' => $location->id]);
    }
}
