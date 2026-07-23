<?php

namespace Database\Factories;

use App\Support\PharmacyReferenceData;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement(PharmacyReferenceData::LOCATIONS),
            'address' => $this->faker->randomElement(['Dhaka', 'Chattogram', 'Sylhet']).', Bangladesh',
            'phone' => '02'.$this->faker->numerify('#######'),
            'is_default' => false,
            'status' => true,
        ];
    }
}
