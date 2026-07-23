<?php

namespace Database\Factories;

use App\Support\PharmacyReferenceData;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->unique()->randomElement(PharmacyReferenceData::CATEGORIES);

        return [
            'name' => $name,
            'description' => null,
            'status' => true,
        ];
    }
}
