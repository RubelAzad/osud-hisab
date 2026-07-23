<?php

namespace Database\Factories;

use App\Support\PharmacyReferenceData;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->randomElement(PharmacyReferenceData::GIVEN_NAMES).' '
            .$this->faker->randomElement(PharmacyReferenceData::SURNAMES);

        return [
            'name' => $name,
            'phone' => '01'.$this->faker->numerify('#########'),
            'email' => null,
            'address' => $this->faker->randomElement(['Dhaka', 'Chattogram', 'Sylhet', 'Khulna', 'Rajshahi', 'Barishal']).', Bangladesh',
            'opening_balance' => 0,
            'balance' => 0,
        ];
    }
}
