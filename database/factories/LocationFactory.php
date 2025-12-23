<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocationFactory extends Factory
{
    protected $model = Location::class;

    public function definition(): array
    {
        $lat = $this->faker->randomFloat(7, -6.3000000, -6.1000000);
        $lng = $this->faker->randomFloat(7, 106.7000000, 106.9000000);

        return [
            'name' => 'Pangkalan '.$this->faker->company(),
            'address' => $this->faker->address(),
            'latitude' => $lat,
            'longitude' => $lng,
            'stock' => $this->faker->numberBetween(0, 80),
            'capacity' => $this->faker->optional()->numberBetween(50, 200),
            'is_open' => $this->faker->boolean(70),
            'phone' => $this->faker->optional()->phoneNumber(),
            'photo' => null,
            'operating_hours' => $this->faker->optional()->randomElement(['08:00-17:00', '07:00-16:00', '09:00-18:00']),
        ];
    }
}
