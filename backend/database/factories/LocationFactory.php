<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocationFactory extends Factory
{
    protected $model = Location::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->city(),
            'code' => strtoupper($this->faker->unique()->lexify('???')),
            'image' => $this->faker->optional()->imageUrl(640, 480, 'city'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
