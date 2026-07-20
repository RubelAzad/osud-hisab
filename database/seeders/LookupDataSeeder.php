<?php

namespace Database\Seeders;

use App\Models\MedicineType;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class LookupDataSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Box', 'Strip', 'Piece', 'Bottle', 'Tube'] as $name) {
            Unit::firstOrCreate(['name' => $name], ['short_name' => strtoupper(substr($name, 0, 3))]);
        }

        foreach (['Tablet', 'Capsule', 'Syrup', 'Injection', 'Cream', 'Drops'] as $name) {
            MedicineType::firstOrCreate(['name' => $name]);
        }
    }
}
