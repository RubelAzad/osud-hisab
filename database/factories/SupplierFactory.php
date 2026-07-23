<?php

namespace Database\Factories;

use App\Support\PharmacyReferenceData;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    public function definition(): array
    {
        // Major Bangladeshi pharma companies distribute through their own regional
        // depots — a real, common distribution pattern, used here as supplier names.
        $company = $this->faker->randomElement(PharmacyReferenceData::MANUFACTURERS);
        $area = $this->faker->randomElement(['Dhaka', 'Chattogram', 'Sylhet', 'Khulna', 'Rajshahi']);

        return [
            'name' => "{$company} - {$area} Depot",
            'company_name' => $company,
            'phone' => '01'.$this->faker->numerify('#########'),
            'email' => null,
            'address' => "{$area}, Bangladesh",
            'opening_balance' => 0,
            'balance' => 0,
            'status' => true,
        ];
    }
}
