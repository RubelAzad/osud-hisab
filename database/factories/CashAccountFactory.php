<?php

namespace Database\Factories;

use App\Support\PharmacyReferenceData;
use Illuminate\Database\Eloquent\Factories\Factory;

class CashAccountFactory extends Factory
{
    public function definition(): array
    {
        return [
            'account_name' => $this->faker->unique()->randomElement(PharmacyReferenceData::CASH_ACCOUNTS),
            'balance' => 0,
        ];
    }
}
