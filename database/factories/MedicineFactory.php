<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Generic;
use App\Models\Manufacturer;
use App\Models\MedicineType;
use App\Models\Unit;
use App\Support\PharmacyReferenceData;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Test/factory-only — builds a plausible medicine from real generic/manufacturer names
 * already curated in PharmacyReferenceData, reusing existing lookup rows when present.
 * Not wired into the default seeders: the real medicine catalog comes from a genuine
 * product-list import (see App\Imports\MedicinesImport), never fabricated seed data.
 */
class MedicineFactory extends Factory
{
    public function definition(): array
    {
        $generic = $this->faker->randomElement(PharmacyReferenceData::GENERICS);
        $brandSuffix = $this->faker->randomElement(['', ' Plus', ' Forte', ' DS']);
        $purchasePrice = $this->faker->randomFloat(2, 2, 300);

        return [
            'category_id' => Category::inRandomOrder()->value('id') ?? Category::factory(),
            'manufacturer_id' => Manufacturer::inRandomOrder()->value('id') ?? Manufacturer::factory(),
            'generic_id' => Generic::inRandomOrder()->value('id') ?? Generic::factory(),
            'medicine_type_id' => MedicineType::inRandomOrder()->value('id') ?? MedicineType::factory(),
            'unit_id' => Unit::inRandomOrder()->value('id') ?? Unit::factory(),
            'medicine_name' => $generic.$brandSuffix,
            'strength' => $this->faker->randomElement(['250mg', '500mg', '5mg', '10mg', '20mg', '100mg', '2.5mg', '400mg', '5ml', '10ml']),
            'barcode' => $this->faker->unique()->numerify('BC-########'),
            'purchase_price' => $purchasePrice,
            'sale_price' => round($purchasePrice * $this->faker->randomFloat(2, 1.1, 1.4), 2),
            'minimum_stock' => $this->faker->numberBetween(10, 100),
            'vat' => $this->faker->randomElement([0, 5, 7.5, 15]),
            'status' => true,
        ];
    }
}
