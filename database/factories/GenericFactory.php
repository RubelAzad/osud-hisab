<?php

namespace Database\Factories;

use App\Support\PharmacyReferenceData;
use Illuminate\Database\Eloquent\Factories\Factory;

class GenericFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement(PharmacyReferenceData::GENERICS),
            'description' => null,
        ];
    }
}
