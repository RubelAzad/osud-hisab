<?php

namespace Database\Factories;

use App\Support\PharmacyReferenceData;
use Illuminate\Database\Eloquent\Factories\Factory;

class ManufacturerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement(PharmacyReferenceData::MANUFACTURERS),
            'phone' => '+8802'.$this->faker->numerify('#######'),
            'email' => null,
            'address' => $this->faker->randomElement(['Dhaka, Bangladesh', 'Chattogram, Bangladesh', 'Gazipur, Bangladesh']),
            'status' => true,
        ];
    }
}
